<?php

class AdminSeoController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Configuracion($this->db);
        $campos = $model->findByGrupo('seo');

        $pageTitle = 'SEO — Admin';
        $viewName = 'admin/seo/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $model = new Configuracion($this->db);
        $campos = $model->findByGrupo('seo');

        foreach ($campos as $campo) {
            $valor = $_POST['campo_' . $campo['clave']] ?? '';
            $model->setValue('seo', $campo['clave'], $valor);
        }

        AuditLog::log('editar', 'configuracion', null, 'SEO actualizado');

        $_SESSION['flash_success'] = 'Configuración SEO guardada correctamente.';
        header('Location: ' . SITE_URL . '/admin/seo');
        exit;
    }
}
