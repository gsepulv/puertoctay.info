<?php

class AdminRedesSocialesController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new Configuracion($this->db);
        $campos = $model->findByGrupo('social');

        $pageTitle = 'Redes Sociales — Admin';
        $viewName = 'admin/redes-sociales/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $model = new Configuracion($this->db);
        $campos = $model->findByGrupo('social');

        foreach ($campos as $campo) {
            $valor = trim($_POST['campo_' . $campo['clave']] ?? '');
            $model->setValue($campo['grupo'], $campo['clave'], $valor);
        }

        AuditLog::log('editar', 'configuracion', null, 'Redes sociales actualizadas');

        $_SESSION['flash_success'] = 'Redes sociales actualizadas correctamente.';
        header('Location: ' . SITE_URL . '/admin/redes-sociales');
        exit;
    }
}
