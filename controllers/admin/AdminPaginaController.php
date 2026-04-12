<?php

class AdminPaginaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new Pagina($this->db);
        $paginas = $model->findAllAdmin();

        $pageTitle = 'Páginas — Admin';
        $viewName = 'admin/paginas/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $pagina = [];
        $errores = [];
        $pageTitle = 'Nueva Página — Admin';
        $viewName = 'admin/paginas/form';
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
            $pagina = $data;
            $pageTitle = 'Nueva Página — Admin';
            $viewName = 'admin/paginas/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $data['slug'] = SlugHelper::unique($this->db, 'paginas', $data['titulo']);
        $data['contenido'] = HtmlSanitizer::clean($_POST['contenido'] ?? '');
        $data['orden'] = !empty($data['orden']) ? (int) $data['orden'] : 0;
        $data['activo'] = isset($data['activo']) ? 1 : 0;
        unset($data['csrf_token']);

        $model = new Pagina($this->db);
        $id = $model->create($data);
        AuditLog::log('crear', 'paginas', $id, "Página: {$data['titulo']}");

        header('Location: ' . SITE_URL . '/admin/paginas');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new Pagina($this->db);
        $pagina = $model->find((int) $id);

        if (!$pagina) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($pagina['titulo']) . ' — Admin';
        $viewName = 'admin/paginas/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Pagina($this->db);
        $pagina = $model->find((int) $id);

        if (!$pagina) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        if (empty($data['titulo'])) {
            $errores = ['El título es obligatorio.'];
            $pagina = array_merge($pagina, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($pagina['titulo']) . ' — Admin';
            $viewName = 'admin/paginas/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        if ($data['titulo'] !== $pagina['titulo']) {
            $data['slug'] = SlugHelper::unique($this->db, 'paginas', $data['titulo'], (int) $id);
        }

        $data['contenido'] = HtmlSanitizer::clean($_POST['contenido'] ?? '');
        $data['orden'] = !empty($data['orden']) ? (int) $data['orden'] : 0;
        $data['activo'] = isset($data['activo']) ? 1 : 0;
        unset($data['csrf_token']);

        $model->update((int) $id, $data);
        AuditLog::log('editar', 'paginas', (int) $id, "Página: {$data['titulo']}");

        header('Location: ' . SITE_URL . '/admin/paginas');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Pagina($this->db);
        $pagina = $model->find((int) $id);

        if ($pagina) {
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'paginas', (int) $id, "Página: {$pagina['titulo']}");
        }

        header('Location: ' . SITE_URL . '/admin/paginas');
        exit;
    }
}
