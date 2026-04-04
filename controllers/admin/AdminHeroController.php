<?php

class AdminHeroController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new HeroConfig($this->db);
        $hero = $model->getActive();

        if (!$hero) {
            $hero = ['titulo' => '', 'subtitulo' => '', 'imagen' => '', 'texto_boton' => '', 'url_boton' => '', 'activo' => 1];
        }

        $pageTitle = 'Hero Home — Admin';
        $viewName = 'admin/hero/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(): void
    {
        CsrfMiddleware::validate();

        $model = new HeroConfig($this->db);
        $hero = $model->getActive();

        $data = Sanitizer::cleanArray($_POST);

        // Handle image upload
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['imagen'], 'hero');
            if ($path) {
                if (!empty($hero['imagen'])) {
                    ImageHelper::delete($hero['imagen']);
                }
                $data['imagen'] = $path;
            }
        }

        $data['activo'] = 1;
        unset($data['csrf_token']);

        if ($hero && isset($hero['id'])) {
            $model->update((int) $hero['id'], $data);
            AuditLog::log('editar', 'hero_config', (int) $hero['id'], "Hero actualizado");
        } else {
            $model->create($data);
            AuditLog::log('crear', 'hero_config', 0, "Hero creado");
        }

        $_SESSION['flash_success'] = 'Hero actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/hero');
        exit;
    }
}
