<?php

class AdminNegocioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $negocioModel = new Negocio($this->db);

        // Support ?status= filter
        $allowedStatuses = ['pendiente', 'activo', 'rechazado', 'suspendido'];
        $statusFilter = null;
        if (!empty($_GET['status']) && in_array($_GET['status'], $allowedStatuses, true)) {
            $statusFilter = $_GET['status'];
        }

        $negocios = $negocioModel->findAllAdmin($statusFilter);

        $pageTitle = 'Negocios — Admin';
        $viewName = 'admin/negocios/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $categorias = (new Categoria($this->db))->findDirectorio();
        $planes = (new PlanConfig($this->db))->findActivos();
        $negocio = [];
        $errores = [];

        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findActivas();
        $negocioTempIds = [];
        $negocioPromociones = [];

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
            $planes = (new PlanConfig($this->db))->findActivos();
            $negocio = $data;
            $tempModel = new Temporada($this->db);
            $temporadas = $tempModel->findActivas();
            $negocioTempIds = [];
            $negocioPromociones = [];
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
        $data['plan'] = !empty($data['plan']) ? $data['plan'] : 'freemium';
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual'] = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Raw content fields (not sanitized by Sanitizer)
        $data['descripcion_larga'] = HtmlSanitizer::clean($_POST['descripcion_larga'] ?? '');
        $data['como_llegar'] = HtmlSanitizer::clean($_POST['como_llegar'] ?? '');

        unset($data["csrf_token"], $data["temporadas"], $data["temporada_promocion"]);

        $negocioModel = new Negocio($this->db);
        $negocioId = $negocioModel->create($data);
        AuditLog::log('crear', 'negocios', $negocioId, "Negocio: {$data['nombre']}");

        // Sync temporadas
        $temporadaIds = $_POST['temporadas'] ?? [];
        $promociones = $_POST['temporada_promocion'] ?? [];
        if (is_array($temporadaIds)) {
            $tempModel = new Temporada($this->db);
            $tempModel->syncNegocioTemporadas($negocioId, $temporadaIds, $promociones);
        }

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
        $planes = (new PlanConfig($this->db))->findActivos();
        $errores = [];

        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findActivas();
        $negocioTemporadas = $tempModel->findForNegocio((int) $id);
        $negocioTempIds = array_column($negocioTemporadas, 'id');
        $negocioPromociones = [];
        foreach ($negocioTemporadas as $nt) {
            $negocioPromociones[$nt['id']] = $nt['promocion'] ?? '';
        }

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
            $planes = (new PlanConfig($this->db))->findActivos();
            $negocio = array_merge($negocio, $data);
            $tempModel = new Temporada($this->db);
            $temporadas = $tempModel->findActivas();
            $negocioTemporadas = $tempModel->findForNegocio((int) $id);
            $negocioTempIds = array_column($negocioTemporadas, 'id');
            $negocioPromociones = [];
            foreach ($negocioTemporadas as $nt) {
                $negocioPromociones[$nt['id']] = $nt['promocion'] ?? '';
            }
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
        $data['plan'] = !empty($data['plan']) ? $data['plan'] : 'freemium';
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;
        $data['monto_mensual'] = (int)($data['monto_mensual'] ?? 0);
        $data['fecha_inicio_contrato'] = !empty($data['fecha_inicio_contrato']) ? $data['fecha_inicio_contrato'] : null;

        // Raw content fields
        $data['descripcion_larga'] = HtmlSanitizer::clean($_POST['descripcion_larga'] ?? '');
        $data['como_llegar'] = HtmlSanitizer::clean($_POST['como_llegar'] ?? '');

        unset($data["csrf_token"], $data["temporadas"], $data["temporada_promocion"]);

        $negocioModel->update((int)$id, $data);
        AuditLog::log('editar', 'negocios', (int)$id, "Negocio: {$data['nombre']}");

        // Sync temporadas
        $temporadaIds = $_POST['temporadas'] ?? [];
        $promociones = $_POST['temporada_promocion'] ?? [];
        if (is_array($temporadaIds)) {
            $tempModel = new Temporada($this->db);
            $tempModel->syncNegocioTemporadas((int) $id, $temporadaIds, $promociones);
        }

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

    public function aprobar(string $id): void
    {
        CsrfMiddleware::validate();
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);
        if ($negocio) {
            // Activate negocio and set status to activo
            $negocioModel->update((int)$id, [
                'activo'    => 1,
                'verificado'=> 1,
                'status'    => 'activo',
            ]);

            // Generate temp password, activate user, send credentials
            if (!empty($negocio['propietario_id'])) {
                $usuario = (new Usuario($this->db))->find((int) $negocio['propietario_id']);
                if ($usuario) {
                    $tempPass = substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 10);
                    $stmt = $this->db->prepare("UPDATE usuarios SET activo = 1, password_hash = ? WHERE id = ?");
                    $stmt->execute([password_hash($tempPass, PASSWORD_DEFAULT), $negocio['propietario_id']]);

                    EmailHelper::notificarAprobacionConCredenciales($usuario, $negocio, $tempPass);
                }
            }

            AuditLog::log('aprobar', 'negocios', (int)$id, "Aprobado: {$negocio['nombre']}");
            $_SESSION['flash_success'] = "Negocio \"{$negocio['nombre']}\" aprobado. Credenciales enviadas por email.";
        }
        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function rechazar(string $id): void
    {
        CsrfMiddleware::validate();
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int)$id);
        if ($negocio) {
            // Send rejection email before deleting
            if (!empty($negocio['propietario_id'])) {
                $usuario = (new Usuario($this->db))->find((int) $negocio['propietario_id']);
                if ($usuario) {
                    EmailHelper::notificarRechazo($usuario, $negocio);
                }
            }

            // Delete negocio
            $negocioModel->delete((int)$id);

            // Delete associated user (only if comerciante with no other negocios)
            if (!empty($negocio['propietario_id'])) {
                $stmtCount = $this->db->prepare("SELECT COUNT(*) FROM negocios WHERE propietario_id = ?");
                $stmtCount->execute([$negocio['propietario_id']]);
                if ((int) $stmtCount->fetchColumn() === 0) {
                    $this->db->prepare("DELETE FROM usuarios WHERE id = ? AND rol = 'comerciante'")->execute([$negocio['propietario_id']]);
                }
            }

            AuditLog::log('rechazar', 'negocios', (int)$id, "Rechazado y eliminado: {$negocio['nombre']}");
            $_SESSION['flash_success'] = "Negocio \"{$negocio['nombre']}\" rechazado y eliminado.";
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
