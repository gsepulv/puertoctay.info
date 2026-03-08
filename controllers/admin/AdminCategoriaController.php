<?php

class AdminCategoriaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Categoria($this->db);
        $categorias = $model->findAll([], 'tipo ASC, orden ASC');

        $pageTitle = 'Categorías — Admin';
        $viewName = 'admin/categorias/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $categoria = [];
        $errores = [];
        $pageTitle = 'Nueva Categoría — Admin';
        $viewName = 'admin/categorias/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es obligatorio.';
        }

        if (!empty($errores)) {
            $categoria = $data;
            $pageTitle = 'Nueva Categoría — Admin';
            $viewName = 'admin/categorias/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $data['slug'] = SlugHelper::unique($this->db, 'categorias', $data['nombre']);
        $data['orden'] = !empty($data['orden']) ? (int) $data['orden'] : 0;
        unset($data['csrf_token']);

        $model = new Categoria($this->db);
        $id = $model->create($data);
        AuditLog::log('crear', 'categorias', $id, "Categoría: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/categorias');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new Categoria($this->db);
        $categoria = $model->find((int) $id);

        if (!$categoria) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($categoria['nombre']) . ' — Admin';
        $viewName = 'admin/categorias/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Categoria($this->db);
        $categoria = $model->find((int) $id);

        if (!$categoria) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        if (empty($data['nombre'])) {
            $errores = ['El nombre es obligatorio.'];
            $categoria = array_merge($categoria, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($categoria['nombre']) . ' — Admin';
            $viewName = 'admin/categorias/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        if ($data['nombre'] !== $categoria['nombre']) {
            $data['slug'] = SlugHelper::unique($this->db, 'categorias', $data['nombre'], (int) $id);
        }

        $data['orden'] = !empty($data['orden']) ? (int) $data['orden'] : 0;
        $data['activo'] = isset($data['activo']) ? 1 : 0;
        unset($data['csrf_token']);

        $model->update((int) $id, $data);
        AuditLog::log('editar', 'categorias', (int) $id, "Categoría: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/categorias');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Categoria($this->db);
        $categoria = $model->find((int) $id);

        if ($categoria) {
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'categorias', (int) $id, "Categoría: {$categoria['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/categorias');
        exit;
    }
}
