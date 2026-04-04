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

        $seoModel = new SeoMeta($this->db);
        $pages = $seoModel->findAllOrdered();

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

        AuditLog::log('editar', 'configuracion', null, 'SEO global actualizado');

        $_SESSION['flash_success'] = 'Configuración SEO global guardada.';
        header('Location: ' . SITE_URL . '/admin/seo');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new SeoMeta($this->db);
        $seo = $model->find((int) $id);

        if (!$seo) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'SEO: ' . $seo['page_identifier'] . ' — Admin';
        $viewName = 'admin/seo/edit';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new SeoMeta($this->db);
        $seo = $model->find((int) $id);

        if (!$seo) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        // Handle og:image upload
        if (!empty($_FILES['og_image_file']['name']) && $_FILES['og_image_file']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['og_image_file'], 'seo');
            if ($path) {
                if (!empty($seo['og_image'])) {
                    ImageHelper::delete($seo['og_image']);
                }
                $data['og_image'] = $path;
            }
        }

        unset($data['csrf_token'], $data['og_image_file']);

        $model->update((int) $id, $data);
        AuditLog::log('editar', 'seo_meta', (int) $id, "SEO: {$seo['page_identifier']}");

        $_SESSION['flash_success'] = 'SEO de "' . $seo['page_identifier'] . '" actualizado.';
        header('Location: ' . SITE_URL . '/admin/seo');
        exit;
    }
}
