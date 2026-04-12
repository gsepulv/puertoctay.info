<?php

class AdminMantenimientoController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        // PHP version
        $phpVersion = phpversion();

        // MySQL version
        $stmt = $this->db->query("SELECT VERSION()");
        $mysqlVersion = $stmt->fetchColumn();

        // DB size
        $stmt = $this->db->prepare(
            "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
             FROM information_schema.tables WHERE table_schema = :db"
        );
        $stmt->execute(['db' => DB_NAME]);
        $dbSize = $stmt->fetchColumn() ?: '0.00';

        // Table sizes
        $stmt = $this->db->prepare(
            "SELECT table_name, table_rows,
                    ROUND((data_length + index_length) / 1024, 2) AS size_kb
             FROM information_schema.tables
             WHERE table_schema = :db
             ORDER BY (data_length + index_length) DESC"
        );
        $stmt->execute(['db' => DB_NAME]);
        $tableSizes = $stmt->fetchAll();

        // Disk usage (sin shell_exec, usa PHP nativo)
        $repoSize = $this->dirSize('/home/visitapuertoctay/puertoctay_repo/');
        $publicSize = $this->dirSize('/home/visitapuertoctay/public_html/');
        $totalDisk = $this->formatBytes(disk_total_space('/home/visitapuertoctay/') - disk_free_space('/home/visitapuertoctay/'));

        // Last backup
        $lastBackup = 'No encontrado';
        $backupDir = '/home/visitapuertoctay/backups/';
        if (is_dir($backupDir)) {
            $files = glob($backupDir . '*');
            if (!empty($files)) {
                usort($files, function ($a, $b) { return filemtime($b) - filemtime($a); });
                $lastBackup = basename($files[0]) . ' (' . date('Y-m-d H:i', filemtime($files[0])) . ')';
            } else {
                $lastBackup = 'Directorio vacio';
            }
        }

        // PHP extensions
        $extensions = get_loaded_extensions();
        sort($extensions);

        // PHP info summary
        $phpInfo = [
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'display_errors' => ini_get('display_errors') ? 'On' : 'Off',
            'error_reporting' => ini_get('error_reporting'),
            'session.gc_maxlifetime' => ini_get('session.gc_maxlifetime') . 's',
        ];

        // Server info
        $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Desconocido';
        $serverOS = php_uname('s') . ' ' . php_uname('r');

        $pageTitle = 'Mantenimiento';
        $viewName = 'admin/mantenimiento/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    private function dirSize(string $path): string
    {
        if (!is_dir($path)) return 'N/A';
        $size = 0;
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $size += $file->getSize();
            }
        }
        return $this->formatBytes($size);
    }

    private function formatBytes(float $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024) return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
