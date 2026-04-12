<?php

class AdminEstadisticasController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        // Total visitas
        $stmt = $this->db->query("SELECT COALESCE(SUM(visitas), 0) FROM negocios");
        $totalVisitas = (int) $stmt->fetchColumn();

        // Top 10 negocios mas visitados
        $stmt = $this->db->query(
            "SELECT n.nombre, n.slug, n.visitas, c.nombre AS categoria
             FROM negocios n
             LEFT JOIN categorias c ON c.id = n.categoria_id
             WHERE n.activo = 1
             ORDER BY n.visitas DESC
             LIMIT 10"
        );
        $topNegocios = $stmt->fetchAll();

        // Conteo por mes (negocios)
        $stmt = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS mes, COUNT(*) AS total
             FROM negocios
             WHERE created_at IS NOT NULL
             GROUP BY mes ORDER BY mes DESC LIMIT 12"
        );
        $negociosPorMes = $stmt->fetchAll();

        // Conteo por mes (noticias)
        $stmt = $this->db->query(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS mes, COUNT(*) AS total
             FROM noticias
             WHERE created_at IS NOT NULL
             GROUP BY mes ORDER BY mes DESC LIMIT 12"
        );
        $noticiasPorMes = $stmt->fetchAll();

        // Ultimas 20 entradas de audit_log
        $stmt = $this->db->query(
            "SELECT a.*, u.nombre AS usuario_nombre
             FROM audit_log a
             LEFT JOIN usuarios u ON u.id = a.usuario_id
             ORDER BY a.created_at DESC
             LIMIT 20"
        );
        $auditLog = $stmt->fetchAll();

        // Tamano de la base de datos
        $stmt = $this->db->prepare(
            "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
             FROM information_schema.tables
             WHERE table_schema = :db"
        );
        $stmt->execute(['db' => DB_NAME]);
        $dbSize = $stmt->fetchColumn() ?: '0.00';

        // Contadores generales
        $counts = [];
        $tables = ['negocios', 'categorias', 'noticias', 'eventos', 'resenas', 'usuarios'];
        foreach ($tables as $t) {
            $s = $this->db->query("SELECT COUNT(*) FROM {$t}");
            $counts[$t] = (int) $s->fetchColumn();
        }

        $pageTitle = 'Estadisticas';
        $viewName = 'admin/estadisticas/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }
}
