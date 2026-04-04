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
        $model = new HeroSlide($this->db);
        $slides = $model->findAllOrdered();

        $pageTitle = 'Hero — Admin';
        $viewName = 'admin/hero/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $slide = [];
        $errores = [];
        $pageTitle = 'Nuevo Hero — Admin';
        $viewName = 'admin/hero/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['titulo'])) {
            $errores[] = 'El título es obligatorio.';
        }

        if (!empty($errores)) {
            $slide = $data;
            $pageTitle = 'Nuevo Hero — Admin';
            $viewName = 'admin/hero/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Handle image upload
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['imagen'], 'hero');
            if ($path) {
                $data['imagen'] = $path;
            }
        }

        $data['activo'] = isset($_POST['activo']) ? 1 : 0;
        $data['orden'] = (int) ($data['orden'] ?? 0);
        unset($data['csrf_token']);

        $model = new HeroSlide($this->db);
        $id = $model->create($data);
        AuditLog::log('crear', 'hero_slides', $id, "Hero: {$data['titulo']}");

        $_SESSION['flash_success'] = 'Hero creado correctamente.';
        header('Location: ' . SITE_URL . '/admin/hero');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new HeroSlide($this->db);
        $slide = $model->find((int) $id);

        if (!$slide) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar Hero — Admin';
        $viewName = 'admin/hero/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new HeroSlide($this->db);
        $slide = $model->find((int) $id);

        if (!$slide) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['titulo'])) {
            $errores[] = 'El título es obligatorio.';
        }

        if (!empty($errores)) {
            $slide = array_merge($slide, $data);
            $pageTitle = 'Editar Hero — Admin';
            $viewName = 'admin/hero/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Handle image upload
        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $path = ImageHelper::upload($_FILES['imagen'], 'hero');
            if ($path) {
                if (!empty($slide['imagen'])) {
                    ImageHelper::delete($slide['imagen']);
                }
                $data['imagen'] = $path;
            }
        }

        $data['activo'] = isset($_POST['activo']) ? 1 : 0;
        $data['orden'] = (int) ($data['orden'] ?? 0);
        unset($data['csrf_token']);

        $model->update((int) $id, $data);
        AuditLog::log('editar', 'hero_slides', (int) $id, "Hero: {$data['titulo']}");

        $_SESSION['flash_success'] = 'Hero actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/hero');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new HeroSlide($this->db);
        $slide = $model->find((int) $id);

        if ($slide) {
            if (!empty($slide['imagen'])) {
                ImageHelper::delete($slide['imagen']);
            }
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'hero_slides', (int) $id, "Hero: {$slide['titulo']}");
        }

        $_SESSION['flash_success'] = 'Hero eliminado.';
        header('Location: ' . SITE_URL . '/admin/hero');
        exit;
    }

    public function toggle(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new HeroSlide($this->db);
        $slide = $model->find((int) $id);

        if ($slide) {
            $nuevo = $slide['activo'] ? 0 : 1;
            $model->update((int) $id, ['activo' => $nuevo]);
            AuditLog::log('toggle', 'hero_slides', (int) $id, ($nuevo ? 'Activado' : 'Desactivado'));
        }

        header('Location: ' . SITE_URL . '/admin/hero');
        exit;
    }
}
