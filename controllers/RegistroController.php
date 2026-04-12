<?php

class RegistroController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * GET /registrar-comercio — Formulario simplificado (1 paso)
     */
    public function index(): void
    {
        // Si ya está logueado como comerciante, ir a mi-comercio
        if (!empty($_SESSION['usuario_id']) && ($_SESSION['usuario_rol'] ?? '') === 'comerciante') {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $stmt = $this->db->prepare("SELECT id, nombre FROM sectores WHERE activo = 1 ORDER BY orden");
        $stmt->execute();
        $sectores = $stmt->fetchAll();

        $pageTitle = 'Registra tu Negocio — ' . SITE_NAME;
        $pageDescription = 'Registra tu negocio gratis en el directorio digital de Puerto Octay.';
        $viewName = 'public/registro-comercio';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    /**
     * POST /registrar-comercio — Procesar solicitud
     */
    public function storeCuenta(): void
    {
        // Honeypot
        if (!empty($_POST['website'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Rate limiting
        if (!RateLimiter::check('registro_comercio_' . ($_SERVER['REMOTE_ADDR'] ?? ''), 3, 3600)) {
            $_SESSION['form_errors'] = ['Demasiados intentos. Intenta de nuevo en 1 hora.'];
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);
        $errores = [];

        // Validar tipo y subtipo
        $tiposValidos = ['alojamiento', 'gastronomia', 'actividad', 'arriendo', 'tour', 'atractivo', 'comercio', 'servicio'];
        if (empty($data['tipo']) || !in_array($data['tipo'], $tiposValidos)) {
            $errores[] = 'Selecciona un tipo de negocio válido.';
        }
        if (empty($data['subtipo'])) {
            $errores[] = 'Selecciona el tipo específico de tu negocio.';
        }

        // Validar nombre comercio
        if (empty($data['nombre_comercio']) || mb_strlen($data['nombre_comercio']) < 3 || mb_strlen($data['nombre_comercio']) > 150) {
            $errores[] = 'El nombre del comercio debe tener entre 3 y 150 caracteres.';
        }

        // Validar descripción
        if (empty($data['descripcion']) || mb_strlen($data['descripcion']) < 20) {
            $errores[] = 'La descripción debe tener al menos 20 caracteres.';
        }
        if (mb_strlen($data['descripcion'] ?? '') > 300) {
            $errores[] = 'La descripción no puede superar 300 caracteres.';
        }

        // Validar sector
        if (empty($data['sector_id'])) {
            $errores[] = 'Selecciona un sector.';
        }

        // Validar datos propietario
        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'Tu nombre debe tener al menos 3 caracteres.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Ingresa un email válido.';
        }
        if (empty($data['telefono']) || mb_strlen($data['telefono']) < 9) {
            $errores[] = 'Ingresa un teléfono válido (mínimo 9 caracteres).';
        }

        // Email único
        if (!empty($data['email'])) {
            $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
            $stmt->execute([strtolower(trim($data['email']))]);
            if ($stmt->fetch()) {
                $errores[] = 'Este email ya está registrado. Si ya tienes cuenta, <a href="' . SITE_URL . '/mi-comercio/login">inicia sesión aquí</a>.';
            }
        }

        // Políticas (5 deben estar aceptadas)
        $politicasIds = ['terminos', 'privacidad', 'contenidos', 'cookies', 'derechos'];
        $politicasAceptadas = 0;
        foreach ($politicasIds as $pid) {
            if (!empty($data['politica_' . $pid]) && $data['politica_' . $pid] === 'acepto') {
                $politicasAceptadas++;
            }
        }
        if ($politicasAceptadas < 5) {
            $errores[] = 'Debes aceptar todas las políticas para continuar.';
        }

        if (!empty($errores)) {
            $_SESSION['form_errors'] = $errores;
            $_SESSION['form_data'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Crear usuario SIN password (se asigna al aprobar)
        $emailLimpio = strtolower(trim($data['email']));
        $stmt = $this->db->prepare(
            "INSERT INTO usuarios (nombre, email, telefono, password_hash, rol, activo, created_at)
             VALUES (?, ?, ?, NULL, 'comerciante', 0, NOW())"
        );
        $stmt->execute([
            $data['nombre'],
            $emailLimpio,
            $data['telefono'],
        ]);
        $usuarioId = (int) $this->db->lastInsertId();

        // Crear negocio
        $slug = SlugHelper::unique($this->db, 'negocios', $data['nombre_comercio']);
        $stmt = $this->db->prepare(
            "INSERT INTO negocios (nombre, slug, tipo, subtipo, descripcion_corta, sector_id,
             propietario_id, plan, activo, verificado, status, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, 'freemium', 0, 0, 'pendiente', NOW())"
        );
        $stmt->execute([
            $data['nombre_comercio'],
            $slug,
            $data['tipo'],
            $data['subtipo'],
            mb_substr($data['descripcion'], 0, 300),
            (int) $data['sector_id'],
            $usuarioId,
        ]);
        $negocioId = (int) $this->db->lastInsertId();

        // Registrar aceptación de políticas
        foreach ($politicasIds as $pid) {
            $decision = $data['politica_' . $pid] ?? 'rechazo';
            $stmt = $this->db->prepare(
                "INSERT INTO politicas_aceptacion (usuario_id, email, politica, decision, ip_address, user_agent, created_at)
                 VALUES (?, ?, ?, ?, ?, ?, NOW())"
            );
            $stmt->execute([
                $usuarioId,
                $emailLimpio,
                $pid,
                $decision,
                $_SERVER['REMOTE_ADDR'] ?? null,
                mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]);
        }

        // Audit log
        AuditLog::log('registro_comercio', 'negocios', $negocioId,
            "Solicitud: {$data['nombre_comercio']} ({$data['tipo']}/{$data['subtipo']}) — {$data['nombre']} <{$emailLimpio}>");

        // Email al admin
        $sectorNombre = '';
        if (!empty($data['sector_id'])) {
            $stmt = $this->db->prepare("SELECT nombre FROM sectores WHERE id = ?");
            $stmt->execute([(int) $data['sector_id']]);
            $row = $stmt->fetch();
            $sectorNombre = $row['nombre'] ?? '';
        }
        $adminBody = EmailHelper::wrap(
            "<h2>Nueva solicitud de comercio</h2>" .
            "<table style='width:100%;border-collapse:collapse;margin:1rem 0;'>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Negocio</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['nombre_comercio']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Tipo</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['tipo'] . ' / ' . $data['subtipo']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Sector</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($sectorNombre) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Propietario</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['nombre']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Email</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($emailLimpio) . "</td></tr>" .
            "<tr><td style='padding:8px;font-weight:600;'>Teléfono</td><td style='padding:8px;'>" . htmlspecialchars($data['telefono']) . "</td></tr>" .
            "</table>" .
            "<p><a href='" . SITE_URL . "/admin/negocios/" . $negocioId . "/editar' style='background:#1B4965;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;'>Revisar en el panel</a></p>"
        );
        EmailHelper::send('contacto@purranque.info', 'Nueva solicitud de comercio — ' . $data['nombre_comercio'], $adminBody);

        // Email al solicitante
        $welcomeBody = EmailHelper::wrap(
            "<h2>¡Hola " . htmlspecialchars($data['nombre']) . "!</h2>" .
            "<p>Hemos recibido tu solicitud para registrar <strong>" . htmlspecialchars($data['nombre_comercio']) . "</strong> en Visita Puerto Octay.</p>" .
            "<p>Nuestro equipo revisará tu información y te contactaremos en un máximo de <strong>48 horas hábiles</strong>.</p>" .
            "<p>Cuando tu comercio sea aprobado, recibirás un email con tus credenciales de acceso al panel.</p>" .
            "<hr style='border:none;border-top:1px solid #E2E8F0;margin:1.5rem 0;'>" .
            "<p style='font-size:0.85rem;color:#718096;'>Si tienes consultas: <a href='mailto:contacto@purranque.info'>contacto@purranque.info</a></p>"
        );
        EmailHelper::send($emailLimpio, 'Solicitud de registro recibida — ' . SITE_NAME, $welcomeBody);

        // Redirect a confirmación
        header('Location: ' . SITE_URL . '/solicitud-recibida');
        exit;
    }

    /**
     * GET /solicitud-recibida — Página de confirmación
     */
    public function solicitudRecibida(): void
    {
        $pageTitle = 'Solicitud Recibida — ' . SITE_NAME;
        $pageDescription = 'Tu solicitud de registro ha sido recibida exitosamente.';
        $viewName = 'public/solicitud-recibida';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    // ═══════════════════════════════════════════════════════
    // Rutas antiguas (2 pasos) — redirigen al nuevo flujo
    // ═══════════════════════════════════════════════════════

    public function datos(): void
    {
        header('Location: ' . SITE_URL . '/registrar-comercio');
        exit;
    }

    public function storeDatos(): void
    {
        header('Location: ' . SITE_URL . '/registrar-comercio');
        exit;
    }

    public function gracias(): void
    {
        header('Location: ' . SITE_URL . '/solicitud-recibida');
        exit;
    }
}
