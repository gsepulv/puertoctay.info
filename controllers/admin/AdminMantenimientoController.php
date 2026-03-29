<?php

class AdminMantenimientoController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
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

        // Disk usage of repo
        $repoSize = 'N/A';
        $out = @shell_exec('du -sh /home/visitapuertoctay/puertoctay_repo/ 2>/dev/null');
        if ($out) {
            $parts = preg_split('/\s+/', trim($out));
            $repoSize = $parts[0] ?? 'N/A';
        }

        // Disk usage of public_html
        $publicSize = 'N/A';
        $out = @shell_exec('du -sh /home/visitapuertoctay/public_html/ 2>/dev/null');
        if ($out) {
            $parts = preg_split('/\s+/', trim($out));
            $publicSize = $parts[0] ?? 'N/A';
        }

        // Total disk usage
        $totalDisk = 'N/A';
        $out = @shell_exec('du -sh /home/visitapuertoctay/ 2>/dev/null');
        if ($out) {
            $parts = preg_split('/\s+/', trim($out));
            $totalDisk = $parts[0] ?? 'N/A';
        }

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
}
