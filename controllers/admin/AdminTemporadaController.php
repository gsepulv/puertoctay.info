<?php

class AdminTemporadaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findAll([], 'orden ASC');

        $viewName = 'admin/temporadas/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $temporada = null;
        $viewName = 'admin/temporadas/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $slug = SlugHelper::unique($this->db, 'temporadas', $data['nombre'] ?? '');

        $tempModel = new Temporada($this->db);
        $tempModel->create([
            'nombre'       => $data['nombre'],
            'emoji'        => $_POST['emoji'] ?? '',
            'slug'         => $slug,
            'descripcion'  => $_POST['descripcion'] ?? null,
            'fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            'activa'       => isset($data['activa']) ? 1 : 0,
            'orden'        => (int) ($data['orden'] ?? 0),
        ]);

        AuditLog::log('crear', 'temporadas', null, "Temporada creada: {$data['nombre']}");
        $_SESSION['flash_success'] = 'Temporada creada correctamente.';
        header('Location: ' . SITE_URL . '/admin/temporadas');
        exit;
    }

    public function editar(string $id): void
    {
        $tempModel = new Temporada($this->db);
        $temporada = $tempModel->find((int) $id);

        if (!$temporada) {
            $_SESSION['flash_error'] = 'Temporada no encontrada.';
            header('Location: ' . SITE_URL . '/admin/temporadas');
            exit;
        }

        $viewName = 'admin/temporadas/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $tempModel = new Temporada($this->db);
        $temporada = $tempModel->find((int) $id);

        if (!$temporada) {
            header('Location: ' . SITE_URL . '/admin/temporadas');
            exit;
        }

        $updateData = [
            'nombre'       => $data['nombre'],
            'emoji'        => $_POST['emoji'] ?? '',
            'descripcion'  => $_POST['descripcion'] ?? null,
            'fecha_inicio' => !empty($data['fecha_inicio']) ? $data['fecha_inicio'] : null,
            'fecha_fin'    => !empty($data['fecha_fin']) ? $data['fecha_fin'] : null,
            'activa'       => isset($data['activa']) ? 1 : 0,
            'orden'        => (int) ($data['orden'] ?? 0),
        ];

        if ($data['nombre'] !== $temporada['nombre']) {
            $updateData['slug'] = SlugHelper::unique($this->db, 'temporadas', $data['nombre'], (int) $id);
        }

        $tempModel->update((int) $id, $updateData);

        AuditLog::log('editar', 'temporadas', (int) $id, "Temporada editada: {$data['nombre']}");
        $_SESSION['flash_success'] = 'Temporada actualizada correctamente.';
        header('Location: ' . SITE_URL . '/admin/temporadas');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $tempModel = new Temporada($this->db);
        $temporada = $tempModel->find((int) $id);

        if ($temporada) {
            // Delete pivot records first
            $this->db->prepare("DELETE FROM negocio_temporada WHERE temporada_id = :tid")->execute(['tid' => $id]);
            $tempModel->delete((int) $id);
            AuditLog::log('eliminar', 'temporadas', (int) $id, "Temporada eliminada: {$temporada['nombre']}");
            $_SESSION['flash_success'] = 'Temporada eliminada.';
        }

        header('Location: ' . SITE_URL . '/admin/temporadas');
        exit;
    }
}
