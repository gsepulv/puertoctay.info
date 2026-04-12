<?php

class AdminDashboardController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $counts = AdminHelper::sidebarCounts($this->db);

        // Últimos 5 negocios
        $stmt = $this->db->prepare(
            "SELECT n.*, c.nombre AS categoria_nombre
             FROM negocios n LEFT JOIN categorias c ON c.id = n.categoria_id
             ORDER BY n.created_at DESC LIMIT 5"
        );
        $stmt->execute();
        $ultimosNegocios = $stmt->fetchAll();

        // Últimas 5 noticias
        $stmt = $this->db->prepare(
            "SELECT n.*, c.nombre AS categoria_nombre
             FROM noticias n LEFT JOIN categorias c ON c.id = n.categoria_id
             ORDER BY n.created_at DESC LIMIT 5"
        );
        $stmt->execute();
        $ultimasNoticias = $stmt->fetchAll();

        // Reseñas pendientes
        $resenaModel = new Resena($this->db);
        $resenasPendientes = $resenaModel->findPendientes(5);

        // Próximos eventos
        $eventoModel = new Evento($this->db);
        $proximosEventos = $eventoModel->findProximos(5);

        $pageTitle = 'Dashboard';
        $viewName = 'admin/dashboard';
        require ROOT_PATH . '/views/layouts/admin.php';
    }
}
