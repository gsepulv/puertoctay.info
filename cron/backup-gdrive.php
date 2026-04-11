<?php
/**
 * Subida de backups a Google Drive — visitapuertoctay.cl
 * Usa OAuth2 refresh token para autenticación
 * Crontab: 15 3 * * *
 */

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/config/backup.php';

$storageBackups = BASE_PATH . '/storage/backups';
$logFile = $storageBackups . '/gdrive.log';
$fecha = date('Y-m-d_His');

// ─── 1. Obtener access token via OAuth2 refresh token ────────────
function getAccessToken(): ?string
{
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'client_id'     => GDRIVE_CLIENT_ID,
            'client_secret' => GDRIVE_CLIENT_SECRET,
            'refresh_token' => GDRIVE_REFRESH_TOKEN,
            'grant_type'    => 'refresh_token',
        ]),
        CURLOPT_TIMEOUT => 30,
    ]);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        logMsg("ERROR: Token request falló (HTTP $httpCode): " . substr($resp, 0, 200));
        return null;
    }

    $data = json_decode($resp, true);
    return $data['access_token'] ?? null;
}

// ─── 2. Subir archivo a Google Drive ─────────────────────────────
function uploadToDrive(string $filePath, string $fileName, string $token): ?string
{
    $fileSize = filesize($filePath);

    if ($fileSize < 5 * 1024 * 1024) {
        return uploadMultipart($filePath, $fileName, $token);
    } else {
        return uploadResumable($filePath, $fileName, $fileSize, $token);
    }
}

function uploadMultipart(string $filePath, string $fileName, string $token): ?string
{
    $folderId = GDRIVE_FOLDER_ID;
    $boundary = 'backup_boundary_' . uniqid();
    $mimeType = 'application/octet-stream';
    $metadata = json_encode([
        'name'    => $fileName,
        'parents' => [$folderId],
    ]);

    $body = "--$boundary\r\n";
    $body .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
    $body .= $metadata . "\r\n";
    $body .= "--$boundary\r\n";
    $body .= "Content-Type: $mimeType\r\n\r\n";
    $body .= file_get_contents($filePath) . "\r\n";
    $body .= "--$boundary--";

    $ch = curl_init('https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: multipart/related; boundary=$boundary",
            'Content-Length: ' . strlen($body),
        ],
        CURLOPT_POSTFIELDS => $body,
        CURLOPT_TIMEOUT => 120,
    ]);

    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $data = json_decode($resp, true);
        return $data['id'] ?? null;
    }

    logMsg("ERROR upload multipart (HTTP $httpCode): " . substr($resp, 0, 200));
    return null;
}

function uploadResumable(string $filePath, string $fileName, int $fileSize, string $token): ?string
{
    $folderId = GDRIVE_FOLDER_ID;
    $mimeType = 'application/octet-stream';
    $metadata = json_encode([
        'name'    => $fileName,
        'parents' => [$folderId],
    ]);

    // Paso 1: Iniciar sesión resumable
    $ch = curl_init('https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            'Content-Type: application/json; charset=UTF-8',
            "X-Upload-Content-Type: $mimeType",
            "X-Upload-Content-Length: $fileSize",
        ],
        CURLOPT_POSTFIELDS => $metadata,
        CURLOPT_HEADER => true,
        CURLOPT_TIMEOUT => 30,
    ]);

    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        logMsg("ERROR resumable init (HTTP $httpCode)");
        return null;
    }

    // Extraer Location header
    if (!preg_match('/location:\s*(.+)/i', $resp, $m)) {
        logMsg('ERROR: No se encontró Location header');
        return null;
    }
    $uploadUrl = trim($m[1]);

    // Paso 2: Enviar contenido del archivo
    $fp = fopen($filePath, 'rb');
    $ch2 = curl_init($uploadUrl);
    curl_setopt_array($ch2, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_PUT => true,
        CURLOPT_INFILE => $fp,
        CURLOPT_INFILESIZE => $fileSize,
        CURLOPT_HTTPHEADER => [
            "Content-Length: $fileSize",
            "Content-Type: $mimeType",
        ],
        CURLOPT_TIMEOUT => 600,
    ]);

    $resp2 = curl_exec($ch2);
    $httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
    curl_close($ch2);
    fclose($fp);

    if ($httpCode2 === 200 || $httpCode2 === 201) {
        $data = json_decode($resp2, true);
        return $data['id'] ?? null;
    }

    logMsg("ERROR resumable upload (HTTP $httpCode2): " . substr($resp2, 0, 200));
    return null;
}

// ─── 3. Listar archivos en carpeta y rotar ───────────────────────
function rotateOldFiles(string $token, int $maxFiles = 30): void
{
    $folderId = GDRIVE_FOLDER_ID;
    $url = "https://www.googleapis.com/drive/v3/files?"
         . "q='" . $folderId . "'+in+parents+and+trashed=false"
         . "&orderBy=createdTime+asc"
         . "&fields=files(id,name,createdTime)"
         . "&pageSize=100";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"],
        CURLOPT_TIMEOUT => 30,
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($resp, true);
    $files = $data['files'] ?? [];

    if (count($files) > $maxFiles) {
        $toDelete = array_slice($files, 0, count($files) - $maxFiles);
        foreach ($toDelete as $file) {
            $ch = curl_init("https://www.googleapis.com/drive/v3/files/" . $file['id']);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => ["Authorization: Bearer $token"],
                CURLOPT_TIMEOUT => 15,
            ]);
            curl_exec($ch);
            curl_close($ch);
            logMsg("Rotación: eliminado " . $file['name']);
        }
    }
}

// ─── 4. Helpers ──────────────────────────────────────────────────
function logMsg(string $msg): void
{
    global $logFile;
    $line = '[' . date('Y-m-d_His') . '] ' . $msg . "\n";
    file_put_contents($logFile, $line, FILE_APPEND);
    echo $line;
}

function formatSize(int $bytes): string
{
    if ($bytes >= 1024 * 1024) return round($bytes / 1024 / 1024, 1) . 'MB';
    return round($bytes / 1024, 1) . 'KB';
}

// ─── MAIN ────────────────────────────────────────────────────────

// Obtener token
$token = getAccessToken();
if (!$token) {
    logMsg('ABORT: No se pudo obtener access token');
    exit(1);
}

// Buscar backups más recientes con prefijo vpo_
$dbFiles = glob("$storageBackups/vpo_backup_db_*.sql.gz");
$uploadFiles = glob("$storageBackups/vpo_backup_uploads_*.tar.gz");

if (empty($dbFiles) && empty($uploadFiles)) {
    logMsg('WARN: No se encontraron archivos vpo_backup_* para subir');
    exit(0);
}

// Tomar el más reciente de cada tipo
$results = [];

if (!empty($dbFiles)) {
    sort($dbFiles);
    $latestDb = end($dbFiles);
    $dbName = basename($latestDb);
    $dbId = uploadToDrive($latestDb, $dbName, $token);
    $results[] = $dbName . ' (' . formatSize(filesize($latestDb)) . ')' . ($dbId ? '' : ' FAIL');
}

if (!empty($uploadFiles)) {
    sort($uploadFiles);
    $latestUploads = end($uploadFiles);
    $upName = basename($latestUploads);
    $upId = uploadToDrive($latestUploads, $upName, $token);
    $results[] = $upName . ' (' . formatSize(filesize($latestUploads)) . ')' . ($upId ? '' : ' FAIL');
}

// Rotación en Drive
rotateOldFiles($token, 30);

// Log final
$hasFailure = false;
foreach ($results as $r) {
    if (strpos($r, 'FAIL') !== false) {
        $hasFailure = true;
        break;
    }
}
$status = $hasFailure ? 'ERROR' : 'OK';
logMsg("$status | " . implode(' | ', $results));

if ($hasFailure) {
    @mail('contacto@purranque.info', 'GDrive backup FALLIDO — visitapuertoctay.cl', implode("\n", $results));
}
