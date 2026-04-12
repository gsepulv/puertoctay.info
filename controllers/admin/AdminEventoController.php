<?php

class AdminEventoController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new Evento($this->db);
        $eventos = $model->findAllAdmin();

        $pageTitle = 'Eventos — Admin';
        $viewName = 'admin/eventos/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $evento = [];
        $errores = [];
        $pageTitle = 'Nuevo Evento — Admin';
        $viewName = 'admin/eventos/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);
// Raw HTML content (WYSIWYG)
        $data["descripcion"] = HtmlSanitizer::clean($_POST["descripcion"] ?? "");

        $errores = [];
        if (empty($data['nombre'])) $errores[] = 'El nombre es obligatorio.';
        if (empty($data['fecha_inicio'])) $errores[] = 'La fecha de inicio es obligatoria.';

        if (!empty($errores)) {
            $evento = $data;
            $pageTitle = 'Nuevo Evento — Admin';
            $viewName = 'admin/eventos/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $data['slug'] = SlugHelper::unique($this->db, 'eventos', $data['nombre']);
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['fecha_fin'] = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;

        if (!empty($_FILES['foto']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto'], 'eventos');
            if ($foto) $data['foto'] = $foto;
        }

        unset($data['csrf_token']);

        $model = new Evento($this->db);
        $id = $model->create($data);
        AuditLog::log('crear', 'eventos', $id, "Evento: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/eventos');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new Evento($this->db);
        $evento = $model->find((int) $id);

        if (!$evento) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($evento['nombre']) . ' — Admin';
        $viewName = 'admin/eventos/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Evento($this->db);
        $evento = $model->find((int) $id);

        if (!$evento) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);
// Raw HTML content (WYSIWYG)
        $data["descripcion"] = HtmlSanitizer::clean($_POST["descripcion"] ?? "");

        if (empty($data['nombre'])) {
            $errores = ['El nombre es obligatorio.'];
            $evento = array_merge($evento, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($evento['nombre']) . ' — Admin';
            $viewName = 'admin/eventos/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        if ($data['nombre'] !== $evento['nombre']) {
            $data['slug'] = SlugHelper::unique($this->db, 'eventos', $data['nombre'], (int) $id);
        }

        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['fecha_fin'] = !empty($data['fecha_fin']) ? $data['fecha_fin'] : null;

        if (!empty($_FILES['foto']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto'], 'eventos');
            if ($foto) {
                if (!empty($evento['foto'])) ImageHelper::delete($evento['foto']);
                $data['foto'] = $foto;
            }
        }

        unset($data['csrf_token']);

        $model->update((int) $id, $data);
        AuditLog::log('editar', 'eventos', (int) $id, "Evento: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/eventos');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Evento($this->db);
        $evento = $model->find((int) $id);

        if ($evento) {
            if (!empty($evento['foto'])) ImageHelper::delete($evento['foto']);
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'eventos', (int) $id, "Evento: {$evento['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/eventos');
        exit;
    }
}
