<?php

class AdminConfiguracionController
{
    private PDO $db;

    // Solo estos grupos se muestran aquí; SEO y Social tienen sus propias páginas
    private array $gruposPermitidos = ['general', 'mantenimiento'];

    private array $grupoLabels = [
        'general'       => 'General',
        'mantenimiento' => 'Modo Construcción',
    ];

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Configuracion($this->db);
        $grupo = $_GET['grupo'] ?? 'general';

        // Restringir a grupos permitidos
        if (!in_array($grupo, $this->gruposPermitidos)) {
            $grupo = 'general';
        }

        $campos = $model->findByGrupo($grupo);

        $pageTitle = 'Configuración General — Admin';
        $viewName = 'admin/configuracion/index';
        $grupos = $this->gruposPermitidos;
        $grupoLabels = $this->grupoLabels;
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $model = new Configuracion($this->db);
        $grupo = $_POST['grupo'] ?? 'general';

        if (!in_array($grupo, $this->gruposPermitidos)) {
            header('Location: ' . SITE_URL . '/admin/configuracion');
            exit;
        }

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
