<?php

class AdminBackupController
{
    private PDO $db;
    private string $backupDir;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
        $this->backupDir = ROOT_PATH . '/storage/backups';
    }

    public function index(): void
    {
        // Listar backups locales
        $backups = [];
        if (is_dir($this->backupDir)) {
            $files = glob($this->backupDir . '/vpo_backup_*.{sql.gz,tar.gz}', GLOB_BRACE);
            foreach ($files as $file) {
                $backups[] = [
                    'name'    => basename($file),
                    'size'    => filesize($file),
                    'date'    => filemtime($file),
                    'type'    => str_contains(basename($file), '_db_') ? 'db' : 'uploads',
                ];
            }
            usort($backups, fn($a, $b) => $b['date'] - $a['date']);
        }

        // Leer logs
        $backupLog = '';
        $logFile = $this->backupDir . '/backup.log';
        if (file_exists($logFile)) {
            $lines = file($logFile, FILE_IGNORE_NEW_LINES);
            $backupLog = implode("\n", array_slice($lines, -10));
        }

        $gdriveLog = '';
        $gdriveFile = $this->backupDir . '/gdrive.log';
        if (file_exists($gdriveFile)) {
            $lines = file($gdriveFile, FILE_IGNORE_NEW_LINES);
            $gdriveLog = implode("\n", array_slice($lines, -10));
        }

        // Espacio en disco
        $totalSize = 0;
        foreach ($backups as $b) {
            $totalSize += $b['size'];
        }

        $pageTitle = 'Backups';
        $viewName = 'admin/backups/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function runLocal(): void
    {
        CsrfMiddleware::validate();

        $output = [];
        $code = 0;
        $script = ROOT_PATH . '/cron/backup-auto.php';

        if (!file_exists($script)) {
            $_SESSION['flash_error'] = 'Script de backup no encontrado.';
            header('Location: ' . SITE_URL . '/admin/backups');
            exit;
        }

        // Ejecutar el script de backup
        ob_start();
        try {
            include $script;
        } catch (\Throwable $e) {
            $output[] = 'ERROR: ' . $e->getMessage();
        }
        $result = ob_get_clean();

        if (str_contains($result, 'OK')) {
            $_SESSION['flash_success'] = 'Backup local ejecutado correctamente: ' . trim($result);
        } else {
            $_SESSION['flash_error'] = 'Backup local con problemas: ' . trim($result);
        }

        header('Location: ' . SITE_URL . '/admin/backups');
        exit;
    }

    public function runGdrive(): void
    {
        CsrfMiddleware::validate();

        $script = ROOT_PATH . '/cron/backup-gdrive.php';

        if (!file_exists($script)) {
            $_SESSION['flash_error'] = 'Script de Google Drive no encontrado.';
            header('Location: ' . SITE_URL . '/admin/backups');
            exit;
        }

        ob_start();
        try {
            include $script;
        } catch (\Throwable $e) {
            ob_end_clean();
            $_SESSION['flash_error'] = 'Error subiendo a Google Drive: ' . $e->getMessage();
            header('Location: ' . SITE_URL . '/admin/backups');
            exit;
        }
        $result = ob_get_clean();

        if (str_contains($result, 'OK')) {
            $_SESSION['flash_success'] = 'Subida a Google Drive exitosa: ' . trim($result);
        } else {
            $_SESSION['flash_error'] = 'Error en Google Drive: ' . trim($result);
        }

        header('Location: ' . SITE_URL . '/admin/backups');
        exit;
    }

    public function delete(): void
    {
        CsrfMiddleware::validate();

        $filename = $_POST['filename'] ?? '';

        // Validar que sea un archivo de backup válido (prevenir path traversal)
        if (!preg_match('/^vpo_backup_(db|uploads)_\d{4}-\d{2}-\d{2}_\d{6}\.(sql\.gz|tar\.gz)$/', $filename)) {
            $_SESSION['flash_error'] = 'Nombre de archivo no válido.';
            header('Location: ' . SITE_URL . '/admin/backups');
            exit;
        }

        $filepath = $this->backupDir . '/' . $filename;

        if (file_exists($filepath)) {
            unlink($filepath);
            $_SESSION['flash_success'] = 'Backup eliminado: ' . $filename;
        } else {
            $_SESSION['flash_error'] = 'Archivo no encontrado.';
        }

        header('Location: ' . SITE_URL . '/admin/backups');
        exit;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
