<?php

class PanelComercianteController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        // Auth check is per-method: login/logout don't require session
    }

    private function requireAuth(): void
    {
        if (empty($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'comerciante') {
            header('Location: ' . SITE_URL . '/mi-comercio/login');
            exit;
        }
    }

    public function loginForm(): void
    {
        if (!empty($_SESSION['usuario_id']) && ($_SESSION['usuario_rol'] ?? '') === 'comerciante') {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $pageTitle = 'Acceder a mi comercio — ' . SITE_NAME;
        $viewName = 'comerciante/login';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function login(): void
    {
        CsrfMiddleware::validate();

        // Rate limiting: max 5 intentos en 15 minutos
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if (!RateLimiter::check('login_comerciante_' . $ip, 5, 900)) {
            $_SESSION['flash_error'] = 'Demasiados intentos. Espera 15 minutos.';
            header('Location: ' . SITE_URL . '/mi-comercio/login');
            exit;
        }

        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['flash_error'] = 'Ingresa tu email y contraseña.';
            header('Location: ' . SITE_URL . '/mi-comercio/login');
            exit;
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE email = ? AND rol = 'comerciante' AND activo = 1 LIMIT 1"
        );
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            $_SESSION['flash_error'] = 'Email o contraseña incorrectos.';
            header('Location: ' . SITE_URL . '/mi-comercio/login');
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['usuario_id']     = (int) $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol']    = 'comerciante';

        header('Location: ' . SITE_URL . '/mi-comercio');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
        // Restart session for flash message
        session_start();
        $_SESSION['flash_success'] = 'Has cerrado sesión correctamente.';
        header('Location: ' . SITE_URL . '/mi-comercio/login');
        exit;
    }

    private function getMiNegocio(): ?array
    {
        $userId = (int) $_SESSION['usuario_id'];
        $stmt = $this->db->prepare(
            "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
             FROM negocios n
             LEFT JOIN categorias c ON c.id = n.categoria_id
             WHERE n.propietario_id = :uid
             LIMIT 1"
        );
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private function getPlanConfig(string $planSlug): array
    {
        $stmt = $this->db->prepare("SELECT * FROM planes_config WHERE slug = ? LIMIT 1");
        $stmt->execute([$planSlug]);
        return $stmt->fetch() ?: ['max_fotos' => 3, 'max_redes' => 1, 'tiene_horarios' => 0, 'tiene_mapa' => 1, 'tiene_sello' => 0];
    }

    private function calcularProgreso(array $negocio, array $camposEspecificos): int
    {
        $total = 10;
        $completados = 0;
        if (!empty($negocio['nombre'])) $completados++;
        if (!empty($negocio['descripcion_corta'])) $completados++;
        if (!empty($negocio['direccion'])) $completados++;
        if (!empty($negocio['telefono']) || !empty($negocio['whatsapp'])) $completados++;
        if (!empty($negocio['lat']) && !empty($negocio['lng'])) $completados++;
        if (!empty($negocio['logo'])) $completados++;
        if (!empty($negocio['portada'])) $completados++;
        if (!empty($negocio['facebook']) || !empty($negocio['instagram'])) $completados++;
        $camposLlenos = count(array_filter($camposEspecificos, fn($v) => !empty($v)));
        if ($camposLlenos >= 3) $completados++;
        if (!empty($negocio['horario'])) $completados++;
        return (int) round(($completados / $total) * 100);
    }

    public function dashboard(): void
    {
        $this->requireAuth();
        $negocio = $this->getMiNegocio();
        $stats = [];

        if ($negocio) {
            $stats['visitas'] = (int) $negocio['visitas'];

            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada'"
            );
            $stmt->execute(['nid' => $negocio['id']]);
            $stats['resenas'] = (int) $stmt->fetchColumn();

            $stmt2 = $this->db->prepare(
                "SELECT AVG(puntuacion) FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada'"
            );
            $stmt2->execute(['nid' => $negocio['id']]);
            $stats['rating'] = round((float) $stmt2->fetchColumn(), 1);
        }

        $viewName = 'comerciante/dashboard';
        require ROOT_PATH . '/views/layouts/comerciante.php';
    }

    public function editarNegocio(): void
    {
        $this->requireAuth();
        $negocio = $this->getMiNegocio();
        if (!$negocio) {
            $_SESSION['flash_error'] = 'No tienes un negocio registrado.';
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $planConfig = $this->getPlanConfig($negocio['plan'] ?? 'freemium');
        $camposEspecificos = json_decode($negocio['campos_especificos'] ?? '{}', true) ?: [];
        $progreso = $this->calcularProgreso($negocio, $camposEspecificos);

        $catModel = new Categoria($this->db);
        $categorias = $catModel->findDirectorio();

        $stmt = $this->db->prepare("SELECT id, nombre FROM sectores WHERE activo = 1 ORDER BY orden");
        $stmt->execute();
        $sectores = $stmt->fetchAll();

        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findActivas();
        $negocioTemporadas = $tempModel->findForNegocio((int) $negocio['id']);
        $negocioTempIds = array_column($negocioTemporadas, 'id');
        $negocioPromociones = [];
        foreach ($negocioTemporadas as $nt) {
            $negocioPromociones[$nt['id']] = $nt['promocion'] ?? '';
        }

        $viewName = 'comerciante/editar-negocio';
        require ROOT_PATH . '/views/layouts/comerciante.php';
    }

    public function actualizarNegocio(): void
    {
        $this->requireAuth();
        CsrfMiddleware::validate();

        $negocio = $this->getMiNegocio();
        if (!$negocio) {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (empty($data['descripcion_corta'])) {
            $errores[] = 'La descripción corta es obligatoria.';
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode('<br>', $errores);
            header('Location: ' . SITE_URL . '/mi-comercio/editar');
            exit;
        }

        $updateData = [
            'nombre'            => $data['nombre'],
            'descripcion_corta' => mb_substr($data['descripcion_corta'], 0, 300),
            'descripcion_larga' => HtmlSanitizer::clean($_POST['descripcion_larga'] ?? $negocio['descripcion_larga']),
            'direccion'         => $data['direccion'] ?? $negocio['direccion'],
            'como_llegar'       => HtmlSanitizer::clean($_POST['como_llegar'] ?? $negocio['como_llegar']),
            'telefono'          => $data['telefono'] ?? $negocio['telefono'],
            'whatsapp'          => $data['whatsapp'] ?? $negocio['whatsapp'],
            'email'             => $data['email'] ?? $negocio['email'],
            'sitio_web'         => $data['sitio_web'] ?? $negocio['sitio_web'],
            'horario'           => HtmlSanitizer::clean($_POST['horario'] ?? $negocio['horario']),
            'facebook'          => $data['facebook'] ?? null,
            'instagram'         => $data['instagram'] ?? null,
            'tiktok'            => $data['tiktok'] ?? null,
            'youtube'           => $data['youtube'] ?? null,
            'lat'               => !empty($data['lat']) ? (float) $data['lat'] : $negocio['lat'],
            'lng'               => !empty($data['lng']) ? (float) $data['lng'] : $negocio['lng'],
            'sector_id'         => !empty($data['sector_id']) ? (int) $data['sector_id'] : $negocio['sector_id'],
        ];

        if (!empty($data['categoria_id'])) {
            $updateData['categoria_id'] = (int) $data['categoria_id'];
        }

        // Update slug if name changed
        if ($data['nombre'] !== $negocio['nombre']) {
            $updateData['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre'], (int) $negocio['id']);
        }

        // Upload logo/portada
        foreach (['logo' => 'logos', 'portada' => 'portadas'] as $field => $subdir) {
            if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $path = ImageHelper::upload($_FILES[$field], $subdir);
                if ($path) {
                    if (!empty($negocio[$field])) {
                        ImageHelper::delete($negocio[$field]);
                    }
                    $updateData[$field] = $path;
                }
            }
        }

        // Campos específicos del tipo/subtipo
        $camposRaw = $_POST['campos'] ?? [];
        $updateData['campos_especificos'] = json_encode($camposRaw, JSON_UNESCAPED_UNICODE);

        $negocioModel = new Negocio($this->db);
        $negocioModel->update((int) $negocio['id'], $updateData);

        // Sync temporadas
        $temporadaIds = $_POST['temporadas'] ?? [];
        $promociones = $_POST['temporada_promocion'] ?? [];
        if (is_array($temporadaIds)) {
            $tempModel = new Temporada($this->db);
            $tempModel->syncNegocioTemporadas((int) $negocio['id'], $temporadaIds, $promociones);
        }

        AuditLog::log('editar', 'negocios', (int) $negocio['id'],
            "Comerciante editó: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Negocio actualizado correctamente.';
        header('Location: ' . SITE_URL . '/mi-comercio/editar');
        exit;
    }

    public function perfil(): void
    {
        $this->requireAuth();
        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->find((int) $_SESSION['usuario_id']);

        $viewName = 'comerciante/perfil';
        require ROOT_PATH . '/views/layouts/comerciante.php';
    }

    public function actualizarPerfil(): void
    {
        $this->requireAuth();
        CsrfMiddleware::validate();

        $data = Sanitizer::cleanArray($_POST);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }

        if (!empty($password)) {
            if (mb_strlen($password) < 8) {
                $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
            }
            if ($password !== $passwordConfirm) {
                $errores[] = 'Las contraseñas no coinciden.';
            }
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode('<br>', $errores);
            header('Location: ' . SITE_URL . '/mi-comercio/perfil');
            exit;
        }

        $updateData = [
            'nombre'   => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usuarioModel = new Usuario($this->db);
        $usuarioModel->update((int) $_SESSION['usuario_id'], $updateData);

        $_SESSION['usuario_nombre'] = $data['nombre'];
        $_SESSION['flash_success'] = 'Perfil actualizado correctamente.';
        header('Location: ' . SITE_URL . '/mi-comercio/perfil');
        exit;
    }
}
