<?php

class RegistroController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ═══════════════════════════════════════════════════════
    // PASO 1 — Crear cuenta
    // ═══════════════════════════════════════════════════════

    public function index(): void
    {
        // Si ya está logueado como comerciante, ir a mi-comercio
        if (!empty($_SESSION['usuario_id']) && ($_SESSION['usuario_rol'] ?? '') === 'comerciante') {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        // Si ya tiene sesión de registro (paso 1 completado), ir a paso 2
        if (!empty($_SESSION['registro_uid'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio/datos');
            exit;
        }

        $errores = $_SESSION['registro_errores'] ?? [];
        $datos = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_errores'], $_SESSION['registro_datos']);

        $pageTitle = 'Registrar mi comercio — ' . SITE_NAME;
        $pageDescription = 'Registra tu negocio gratis en el directorio digital de Puerto Octay.';
        $viewName = 'public/registro-cuenta';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function storeCuenta(): void
    {
        // Honeypot
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Rate limiting
        if (!RateLimiter::check('registro_comercio_' . ($_SERVER['REMOTE_ADDR'] ?? ''), 3, 3600)) {
            $_SESSION['registro_errores'] = ['Demasiados intentos. Intenta de nuevo en 1 hora.'];
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        // Validar campos de cuenta
        if (empty($data['nombre_propietario']) || mb_strlen($data['nombre_propietario']) < 3 || mb_strlen($data['nombre_propietario']) > 100) {
            $errores[] = 'El nombre debe tener entre 3 y 100 caracteres.';
        }
        if (empty($data['email_propietario']) || !filter_var($data['email_propietario'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Ingresa un email válido.';
        }
        if (empty($data['telefono_propietario']) || mb_strlen($data['telefono_propietario']) < 9 || mb_strlen($data['telefono_propietario']) > 15) {
            $errores[] = 'Ingresa un teléfono válido (entre 9 y 15 caracteres).';
        }

        // Password
        if (mb_strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errores[] = 'La contraseña debe contener al menos 1 mayúscula, 1 minúscula y 1 número.';
        }
        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden.';
        }

        // Email único
        $usuarioModel = new Usuario($this->db);
        if ($usuarioModel->findByEmail($data['email_propietario'] ?? '')) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        if (!empty($errores)) {
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Crear usuario
        $userId = $usuarioModel->create([
            'nombre'        => $data['nombre_propietario'],
            'email'         => $data['email_propietario'],
            'telefono'      => $data['telefono_propietario'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol'           => 'comerciante',
            'activo'        => 1,
        ]);

        // Registrar políticas si fueron aceptadas
        $politicas = ['terminos', 'privacidad', 'contenidos', 'derechos', 'cookies'];
        foreach ($politicas as $politica) {
            $decision = $_POST['politica_' . $politica] ?? '';
            if (in_array($decision, ['acepto', 'rechazo'])) {
                $stmt = $this->db->prepare(
                    "INSERT INTO politicas_aceptacion (usuario_id, email, politica, decision, ip_address, user_agent, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, NOW())"
                );
                $stmt->execute([
                    $userId,
                    $data['email_propietario'],
                    $politica,
                    $decision,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                ]);
            }
        }

        // Guardar en sesión para paso 2
        $_SESSION['registro_uid'] = $userId;
        $_SESSION['registro_nombre'] = $data['nombre_propietario'];
        $_SESSION['registro_email'] = $data['email_propietario'];
        $_SESSION['registro_telefono'] = $data['telefono_propietario'];

        $this->logRegistro($data['email_propietario'], 'cuenta_creada');

        header('Location: ' . SITE_URL . '/registrar-comercio/datos');
        exit;
    }

    // ═══════════════════════════════════════════════════════
    // PASO 2 — Datos del comercio
    // ═══════════════════════════════════════════════════════

    public function datos(): void
    {
        if (empty($_SESSION['registro_uid'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        $catModel = new Categoria($this->db);
        $categorias = $catModel->findDirectorio();

        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findActivas();

        $errores = $_SESSION['registro_errores'] ?? [];
        $datos = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_errores'], $_SESSION['registro_datos']);

        $pageTitle = 'Datos de tu comercio — ' . SITE_NAME;
        $pageDescription = 'Completa la información de tu negocio.';
        $usarLeaflet = true;
        $viewName = 'public/registro-datos';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function storeDatos(): void
    {
        if (empty($_SESSION['registro_uid'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);
        $errores = [];
        $userId = $_SESSION['registro_uid'];

        // Validar campos del comercio
        if (empty($data['nombre_comercio']) || mb_strlen($data['nombre_comercio']) < 3 || mb_strlen($data['nombre_comercio']) > 150) {
            $errores[] = 'El nombre del comercio debe tener entre 3 y 150 caracteres.';
        }
        if (empty($data['categoria_id'])) {
            $errores[] = 'Selecciona una categoría.';
        }
        if (empty($data['descripcion_comercio']) || mb_strlen($data['descripcion_comercio']) < 20 || mb_strlen($data['descripcion_comercio']) > 2000) {
            $errores[] = 'La descripción debe tener entre 20 y 2000 caracteres.';
        }
        if (empty($data['direccion_comercio']) || mb_strlen($data['direccion_comercio']) < 5 || mb_strlen($data['direccion_comercio']) > 255) {
            $errores[] = 'La dirección debe tener entre 5 y 255 caracteres.';
        }

        if (!empty($errores)) {
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio/datos');
            exit;
        }

        // Upload images
        $logoPath = null;
        $portadaPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $logoPath = ImageHelper::upload($_FILES['logo'], 'logos');
        }
        if (!empty($_FILES['portada']['name'])) {
            $portadaPath = ImageHelper::upload($_FILES['portada'], 'portadas');
        }

        // Red social
        $redSocialUrl = !empty($data['red_social_url']) ? $data['red_social_url'] : null;
        $redSocialTipo = $data['red_social_tipo'] ?? '';
        $redSocialMap = [
            'facebook' => 'facebook', 'instagram' => 'instagram', 'tiktok' => 'tiktok',
            'youtube' => 'youtube', 'twitter' => 'twitter', 'linkedin' => 'linkedin',
        ];

        // Idiomas
        $idiomas = !empty($_POST['idiomas']) && is_array($_POST['idiomas']) ? json_encode($_POST['idiomas']) : null;

        // Create negocio
        $slug = SlugHelper::unique($this->db, 'negocios', $data['nombre_comercio']);
        $negocioModel = new Negocio($this->db);
        $negocioData = [
            'nombre'            => $data['nombre_comercio'],
            'slug'              => $slug,
            'tipo'              => in_array($data['tipo'] ?? '', ['comercio','atractivo','gastronomia','servicio']) ? $data['tipo'] : 'comercio',
            'categoria_id'      => (int) $data['categoria_id'],
            'descripcion_corta' => mb_substr($data['descripcion_comercio'], 0, 300),
            'descripcion_larga' => $data['descripcion_comercio'],
            'direccion'         => $data['direccion_comercio'],
            'telefono'          => $data['telefono_comercio'] ?? null,
            'whatsapp'          => $_SESSION['registro_telefono'] ?? null,
            'email'             => $_SESSION['registro_email'] ?? null,
            'sitio_web'         => !empty($data['sitio_web_comercio']) ? $data['sitio_web_comercio'] : null,
            'lat'               => !empty($data['lat']) ? (float) $data['lat'] : null,
            'lng'               => !empty($data['lng']) ? (float) $data['lng'] : null,
            'como_llegar'       => !empty($_POST['como_llegar']) ? $_POST['como_llegar'] : null,
            'horario'           => !empty($data['horario']) ? mb_substr($data['horario'], 0, 255) : null,
            'activo'            => 0,
            'verificado'        => 0,
            'plan_id'           => 1,
            'propietario_id'    => $userId,
            'idiomas'           => $idiomas,
        ];
        if ($logoPath) $negocioData['logo'] = $logoPath;
        if ($portadaPath) $negocioData['portada'] = $portadaPath;
        if ($redSocialUrl && isset($redSocialMap[$redSocialTipo])) {
            $negocioData[$redSocialMap[$redSocialTipo]] = $redSocialUrl;
        } elseif ($redSocialUrl && $redSocialTipo === 'otra') {
            $negocioData['red_social_1'] = $redSocialUrl;
        }
        $negocioId = $negocioModel->create($negocioData);

        // Temporadas
        $temporadaIds = $_POST['temporadas'] ?? [];
        $promociones = $_POST['temporada_promocion'] ?? [];
        if (!empty($temporadaIds) && is_array($temporadaIds)) {
            $tempModel = new Temporada($this->db);
            $tempModel->syncNegocioTemporadas($negocioId, $temporadaIds, $promociones);
        }

        // Audit log
        $this->logRegistro($_SESSION['registro_email'] ?? '', 'exitoso', $negocioId);

        // Email al admin
        $catNombre = '';
        if (!empty($data['categoria_id'])) {
            $cat = (new Categoria($this->db))->find((int) $data['categoria_id']);
            $catNombre = $cat['nombre'] ?? '';
        }
        $adminBody = EmailHelper::wrap(
            "<h2>Nuevo comercio registrado</h2>" .
            "<table style='width:100%;border-collapse:collapse;margin:1rem 0;'>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Comercio</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['nombre_comercio']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Categoría</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($catNombre) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Propietario</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($_SESSION['registro_nombre'] ?? '') . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Email</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($_SESSION['registro_email'] ?? '') . "</td></tr>" .
            "</table>" .
            "<p><a href='" . SITE_URL . "/admin/negocios' style='background:#1B4965;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;'>Revisar en el panel</a></p>"
        );
        EmailHelper::send('contacto@purranque.info', 'Nuevo registro de comercio — ' . $data['nombre_comercio'], $adminBody);

        // Email al comerciante
        $welcomeBody = EmailHelper::wrap(
            "<h2>¡Registro recibido!</h2>" .
            "<p>Hola <strong>" . htmlspecialchars($_SESSION['registro_nombre'] ?? '') . "</strong>,</p>" .
            "<p>Hemos recibido tu solicitud de registro del comercio <strong>" . htmlspecialchars($data['nombre_comercio']) . "</strong> en Visita Puerto Octay.</p>" .
            "<p>Tu solicitud será revisada en un máximo de <strong>48 horas</strong>.</p>" .
            "<p>Una vez aprobado, podrás acceder a tu panel en <a href='" . SITE_URL . "/mi-comercio'>" . SITE_URL . "/mi-comercio</a></p>"
        );
        EmailHelper::send($_SESSION['registro_email'] ?? '', 'Solicitud de registro recibida — ' . SITE_NAME, $welcomeBody);

        // Limpiar sesión y redirigir
        $_SESSION['registro_exito'] = true;
        $_SESSION['registro_email_confirmacion'] = $_SESSION['registro_email'];
        $_SESSION['registro_nombre_confirmacion'] = $data['nombre_comercio'];
        unset($_SESSION['registro_uid'], $_SESSION['registro_nombre'], $_SESSION['registro_email'], $_SESSION['registro_telefono']);

        header('Location: ' . SITE_URL . '/registrar-comercio/gracias');
        exit;
    }

    // ═══════════════════════════════════════════════════════
    // PASO 3 — Confirmación
    // ═══════════════════════════════════════════════════════

    public function gracias(): void
    {
        if (empty($_SESSION['registro_exito'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        $email = $_SESSION['registro_email_confirmacion'] ?? '';
        $nombre = $_SESSION['registro_nombre_confirmacion'] ?? '';
        unset($_SESSION['registro_exito'], $_SESSION['registro_email_confirmacion'], $_SESSION['registro_nombre_confirmacion']);

        $pageTitle = '¡Registro recibido! — ' . SITE_NAME;
        $pageDescription = 'Tu comercio ha sido registrado exitosamente.';
        $viewName = 'public/registro-gracias';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    private function logRegistro(string $email, string $resultado, ?int $negocioId = null): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $detalle = "Registro comercio [{$resultado}] - Email: {$email} - IP: {$ip}";
        AuditLog::log('registro_comercio', 'negocios', $negocioId, $detalle);
    }
}
