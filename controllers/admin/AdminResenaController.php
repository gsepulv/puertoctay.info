<?php

class AdminResenaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Resena($this->db);
        $resenas = $model->findAllAdmin();

        $pageTitle = 'Reseñas — Admin';
        $viewName = 'admin/resenas/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function aprobar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Resena($this->db);
        $resena = $model->find((int) $id);

        if ($resena) {
            $model->aprobar((int) $id);
            AuditLog::log('aprobar', 'resenas', (int) $id, "Reseña de: {$resena['nombre_autor']}");
        }

        header('Location: ' . SITE_URL . '/admin/resenas');
        exit;
    }

    public function rechazar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Resena($this->db);
        $resena = $model->find((int) $id);

        if ($resena) {
            $model->rechazar((int) $id);
            AuditLog::log('rechazar', 'resenas', (int) $id, "Reseña de: {$resena['nombre_autor']}");
        }

        header('Location: ' . SITE_URL . '/admin/resenas');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Resena($this->db);
        $resena = $model->find((int) $id);

        if ($resena) {
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'resenas', (int) $id, "Reseña de: {$resena['nombre_autor']}");
        }

        header('Location: ' . SITE_URL . '/admin/resenas');
        exit;
    }
}
