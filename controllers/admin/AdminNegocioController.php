<?php

class AdminNegocioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $negocioModel = new Negocio($this->db);
        $negocios = $negocioModel->findAllAdmin();

        $pageTitle = 'Negocios — Admin';
        $viewName = 'admin/negocios/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $categorias = (new Categoria($this->db))->findDirectorio();
        $planes = (new Plan($this->db))->findActivos();
        $negocio = [];
        $errores = [];

        $pageTitle = 'Nuevo Negocio — Admin';
        $viewName = 'admin/negocios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['nombre'])) $errores[] = 'El nombre es obligatorio.';
        if (empty($data['tipo'])) $errores[] = 'El tipo es obligatorio.';

        if (!empty($errores)) {
            $categorias = (new Categoria($this->db))->findDirectorio();
            $planes = (new Plan($this->db))->findActivos();
            $negocio = $data;
            $pageTitle = 'Nuevo Negocio — Admin';
            $viewName = 'admin/negocios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $data['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre']);

        // Handle file uploads
        $this->handleUploads($data);

        // Checkboxes
        $data['activo'] = isset($_POST['activo']) ? 1 : 0;
        $data['verificado'] = isset($_POST['verificado']) ? 1 : 0;
        $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;

        // Nullable fields
        $data['categoria_id'] = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : null;
        $data['plan_id'] = !empty($data['plan_id']) ? (int)$data['plan_id'] : 1;
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual'] = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Raw content fields (not sanitized by Sanitizer)
        $data['descripcion_larga'] = $_POST['descripcion_larga'] ?? '';
        $data['como_llegar'] = $_POST['como_llegar'] ?? '';

        unset($data['csrf_token']);

        $negocioModel = new Negocio($this->db);
        $id = $negocioModel->create($data);
        AuditLog::log('crear', 'negocios', $id, "Negocio: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Negocio creado correctamente.';
        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function editar(string $id): void
    {
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $categorias = (new Categoria($this->db))->findDirectorio();
        $planes = (new Plan($this->db))->findActivos();
        $errores = [];

        $pageTitle = 'Editar: ' . htmlspecialchars($negocio['nombre']) . ' — Admin';
        $viewName = 'admin/negocios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['nombre'])) $errores[] = 'El nombre es obligatorio.';

        if (!empty($errores)) {
            $categorias = (new Categoria($this->db))->findDirectorio();
            $planes = (new Plan($this->db))->findActivos();
            $negocio = array_merge($negocio, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($negocio['nombre']) . ' — Admin';
            $viewName = 'admin/negocios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Slug if name changed
        if ($data['nombre'] !== $negocio['nombre']) {
            $data['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre'], (int)$id);
        }

        // Handle file uploads (with old file cleanup)
        $this->handleUploads($data, $negocio);

        // Checkboxes
        $data['activo'] = isset($_POST['activo']) ? 1 : 0;
        $data['verificado'] = isset($_POST['verificado']) ? 1 : 0;
        $data['destacado'] = isset($_POST['destacado']) ? 1 : 0;

        // Nullable fields
        $data['categoria_id'] = !empty($data['categoria_id']) ? (int)$data['categoria_id'] : null;
        $data['plan_id'] = !empty($data['plan_id']) ? (int)$data['plan_id'] : 1;
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual'] = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Raw content fields
        $data['descripcion_larga'] = $_POST['descripcion_larga'] ?? '';
        $data['como_llegar'] = $_POST['como_llegar'] ?? '';

        unset($data['csrf_token']);

        $negocioModel->update((int)$id, $data);
        AuditLog::log('editar', 'negocios', (int)$id, "Negocio: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Negocio actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);

        if ($negocio) {
            foreach (['foto_principal', 'portada', 'logo'] as $field) {
                if (!empty($negocio[$field])) {
                    ImageHelper::delete($negocio[$field]);
                }
            }
            $negocioModel->delete((int)$id);
            AuditLog::log('eliminar', 'negocios', (int)$id, "Negocio: {$negocio['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function verificar(string $id): void
    {
        CsrfMiddleware::validate();
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);

        if ($negocio) {
            $nuevoEstado = $negocio['verificado'] ? 0 : 1;
            $negocioModel->update((int)$id, ['verificado' => $nuevoEstado]);
            AuditLog::log('verificar', 'negocios', (int)$id, ($nuevoEstado ? 'Verificado' : 'No verificado'));
        }

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    /**
     * Handle file uploads for foto_principal, portada, logo.
     */
    private function handleUploads(array &$data, ?array $existing = null): void
    {
        $uploads = [
            'foto_principal' => 'negocios',
            'portada' => 'portadas',
            'logo' => 'logos',
        ];

        foreach ($uploads as $field => $subdir) {
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $path = ImageHelper::upload($_FILES[$field], $subdir);
                if ($path) {
                    // Delete old file on update
                    if ($existing && !empty($existing[$field])) {
                        ImageHelper::delete($existing[$field]);
                    }
                    $data[$field] = $path;
                }
            }
        }
    }
}
