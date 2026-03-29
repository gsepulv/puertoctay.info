<?php

class AdminConfiguracionController
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
        $grupo = $_GET['grupo'] ?? 'general';

        $grupos = $model->getGrupos();
        $campos = $model->findByGrupo($grupo);

        $grupoLabels = [
            'general' => 'General',
            'mantenimiento' => 'Modo Construccion',
            'seo'     => 'SEO',
            'social'  => 'Redes Sociales',
        ];

        $pageTitle = 'Configuración — Admin';
        $viewName = 'admin/configuracion/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $model = new Configuracion($this->db);
        $grupo = $_POST['grupo'] ?? 'general';

        $campos = $model->findByGrupo($grupo);

        foreach ($campos as $campo) {
            $valor = $_POST['campo_' . $campo['clave']] ?? '';
            $model->setValue($campo['grupo'], $campo['clave'], $valor);
        }

        AuditLog::log('editar', 'configuracion', null, "Grupo: {$grupo}");

        $_SESSION['flash_success'] = 'Configuración guardada correctamente.';
        header('Location: ' . SITE_URL . '/admin/configuracion?grupo=' . urlencode($grupo));
        exit;
    }
}
