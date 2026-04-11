<?php
/**
 * Backup automático diario — visitapuertoctay.cl
 * Ejecutado por crontab: 0 3 * * *
 */
define('BASE_PATH', dirname(__DIR__));

$storageBackups = BASE_PATH . '/storage/backups';
if (!is_dir($storageBackups)) {
    mkdir($storageBackups, 0750, true);
}

$fecha = date('Y-m-d_His');
$log   = $storageBackups . '/backup.log';

$dbHost = 'localhost';
$dbUser = 'visitapuertoctay_puertoctay';
$dbPass = '+D0$OBgwYl.o';
$dbName = 'visitapuertoctay_puertoctay';

// 1. Backup de base de datos
$dbFile = "$storageBackups/vpo_backup_db_{$fecha}.sql.gz";
$cmd = "mysqldump --no-tablespaces -h$dbHost -u$dbUser -p'$dbPass' $dbName | gzip > $dbFile 2>&1";
exec($cmd, $out, $code);
$dbOk = ($code === 0 && file_exists($dbFile));

// 2. Backup de uploads
$uploadsFile = "$storageBackups/vpo_backup_uploads_{$fecha}.tar.gz";
$uploadsDir  = '/home/visitapuertoctay/public_html/uploads';
$cmd2 = "tar -czf $uploadsFile -C $uploadsDir . 2>&1";
exec($cmd2, $out2, $code2);
$uploadsOk = ($code2 === 0 && file_exists($uploadsFile));

// 3. Rotación: eliminar backups con más de 30 días
$archivos = glob("$storageBackups/vpo_backup_*.{sql.gz,tar.gz}", GLOB_BRACE);
foreach ($archivos as $archivo) {
    if (filemtime($archivo) < strtotime('-30 days')) {
        unlink($archivo);
    }
}

// 4. Log
$status = ($dbOk && $uploadsOk) ? 'OK' : 'ERROR';
$dbSize = $dbOk ? round(filesize($dbFile)/1024, 1) . 'KB' : 'FAIL';
$uploadsSize = $uploadsOk ? round(filesize($uploadsFile)/1024/1024, 1) . 'MB' : 'FAIL';
$linea = "[{$fecha}] {$status} | DB: {$dbSize} | Uploads: {$uploadsSize}\n";
file_put_contents($log, $linea, FILE_APPEND);

// 5. Notificación por email si falla
if ($status === 'ERROR') {
    mail('contacto@purranque.info', '⚠️ Backup FALLIDO — visitapuertoctay.cl', $linea);
}

echo $linea;
