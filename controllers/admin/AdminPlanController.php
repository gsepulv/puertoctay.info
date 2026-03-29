<?php

class AdminPlanController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Plan($this->db);
        $planes = $model->findAll([], 'prioridad ASC, id ASC');

        $pageTitle = 'Planes — Admin';
        $viewName = 'admin/planes/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $plan = [];
        $errores = [];
        $pageTitle = 'Nuevo Plan — Admin';
        $viewName = 'admin/planes/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $errores = $this->validar($data);
        if (!empty($errores)) {
            $plan = $data;
            $pageTitle = 'Nuevo Plan — Admin';
            $viewName = 'admin/planes/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $model = new Plan($this->db);
        $insert = $this->preparar($data);
        $insert['slug'] = SlugHelper::unique($this->db, 'planes', $data['nombre']);
        $id = $model->create($insert);

        AuditLog::log('crear', 'planes', $id, "Plan: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Plan creado correctamente.';
        header('Location: ' . SITE_URL . '/admin/planes');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new Plan($this->db);
        $plan = $model->find((int) $id);

        if (!$plan) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($plan['nombre']) . ' — Admin';
        $viewName = 'admin/planes/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Plan($this->db);
        $plan = $model->find((int) $id);

        if (!$plan) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);
        $errores = $this->validar($data);

        if (!empty($errores)) {
            $plan = array_merge($plan, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($plan['nombre']) . ' — Admin';
            $viewName = 'admin/planes/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $update = $this->preparar($data);
        if ($data['nombre'] !== $plan['nombre']) {
            $update['slug'] = SlugHelper::unique($this->db, 'planes', $data['nombre'], (int) $id);
        }

        $model->update((int) $id, $update);
        AuditLog::log('editar', 'planes', (int) $id, "Plan: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Plan actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/planes');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Plan($this->db);
        $plan = $model->find((int) $id);

        if ($plan) {
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'planes', (int) $id, "Plan: {$plan['nombre']}");
            $_SESSION['flash_success'] = 'Plan eliminado.';
        }

        header('Location: ' . SITE_URL . '/admin/planes');
        exit;
    }

    public function toggleActivo(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Plan($this->db);
        $plan = $model->find((int) $id);

        if ($plan) {
            $nuevoEstado = $plan['activo'] ? 0 : 1;
            $model->update((int) $id, ['activo' => $nuevoEstado]);
            $accion = $nuevoEstado ? 'activar' : 'desactivar';
            AuditLog::log($accion, 'planes', (int) $id, "Plan: {$plan['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/planes');
        exit;
    }

    private function validar(array $data): array
    {
        $errores = [];
        if (empty($data['nombre'])) $errores[] = 'El nombre es obligatorio.';
        if (!isset($data['precio']) || $data['precio'] === '') $errores[] = 'El precio es obligatorio.';
        return $errores;
    }

    private function preparar(array $data): array
    {
        return [
            'nombre' => $data['nombre'],
            'precio' => (int) $data['precio'],
            'descripcion' => $_POST['descripcion'] ?? '',
            'max_fotos' => (int) ($data['max_fotos'] ?? 1),
            'prioridad' => (int) ($data['prioridad'] ?? 0),
            'badge' => isset($data['badge']) ? 1 : 0,
            'estadisticas' => isset($data['estadisticas']) ? 1 : 0,
            'noticia_mensual' => isset($data['noticia_mensual']) ? 1 : 0,
            'banner_portada' => isset($data['banner_portada']) ? 1 : 0,
            'max_cupos' => !empty($data['max_cupos']) ? (int) $data['max_cupos'] : null,
            'activo' => isset($data['activo']) ? 1 : 0,
        ];
    }
}
