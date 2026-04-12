<?php

class AdminHeroController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new HeroConfig($this->db);
        $hero = $model->getActive();

        if (!$hero) {
            $hero = [];
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

        // Handle hero image upload
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['imagen'], 'hero');
            if ($path) {
                if (!empty($hero['imagen'])) {
                    ImageHelper::delete($hero['imagen']);
                }
                $data['imagen'] = $path;
            }
        }

        // Handle og:image upload
        if (!empty($_FILES['og_image_file']['name']) && $_FILES['og_image_file']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['og_image_file'], 'hero');
            if ($path) {
                if (!empty($hero['og_image'])) {
                    ImageHelper::delete($hero['og_image']);
                }
                $data['og_image'] = $path;
            }
        }

        $data['activo'] = 1;
        unset($data['csrf_token'], $data['og_image_file']);

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
