<?php

class RegistroController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        if (!empty($_SESSION['usuario_id']) && ($_SESSION['usuario_rol'] ?? '') === 'comerciante') {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $catModel = new Categoria($this->db);
        $categorias = $catModel->findDirectorio();

        $tempModel = new Temporada($this->db);
        $temporadas = $tempModel->findActivas();

        $errores = $_SESSION['registro_errores'] ?? [];
        $datos = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_errores'], $_SESSION['registro_datos']);

        $pageTitle = 'Registrar mi comercio — ' . SITE_NAME;
        $pageDescription = 'Registra tu negocio gratis en el directorio digital de Puerto Octay.';
        $viewName = 'public/registro-comercio';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function store(): void
    {
        // 1. Honeypot
        if (!empty($_POST['website_url'])) {
            $this->logRegistro($_POST['email_propietario'] ?? '', 'bloqueado_honeypot');
            header('Location: ' . SITE_URL . '/registrar-comercio/gracias');
            exit;
        }

        // 2. Rate limiting
        if (!RateLimiter::check('registro_comercio_' . ($_SERVER['REMOTE_ADDR'] ?? ''), 3, 3600)) {
            $this->logRegistro($_POST['email_propietario'] ?? '', 'bloqueado_rate_limit');
            $_SESSION['registro_errores'] = ['Demasiados intentos. Intenta de nuevo en 1 hora.'];
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // 3. CSRF
        CsrfMiddleware::validate();

        $data = Sanitizer::cleanArray($_POST);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        // 4. Validate policies
        if (($data['politica_terminos'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar los Términos y Condiciones.';
        }
        if (($data['politica_privacidad'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Privacidad.';
        }
        if (($data['politica_cookies'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Cookies.';
        }
        if (($data['politica_contenidos'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Contenidos.';
        }
        if (($data['politica_derechos'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Ejercicio de Derechos.';
        }

        // 5. Validate owner fields
        if (empty($data['nombre_propietario']) || mb_strlen($data['nombre_propietario']) < 3 || mb_strlen($data['nombre_propietario']) > 100) {
            $errores[] = 'El nombre debe tener entre 3 y 100 caracteres.';
        }
        if (empty($data['email_propietario']) || !filter_var($data['email_propietario'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Ingresa un email válido.';
        }
        if (empty($data['telefono_propietario']) || mb_strlen($data['telefono_propietario']) < 9 || mb_strlen($data['telefono_propietario']) > 15) {
            $errores[] = 'Ingresa un teléfono válido (entre 9 y 15 caracteres).';
        }

        // 6. Password strength
        if (mb_strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $errores[] = 'La contraseña debe contener al menos 1 mayúscula, 1 minúscula y 1 número.';
        }
        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden.';
        }

        // 7. Check email unique
        $usuarioModel = new Usuario($this->db);
        if ($usuarioModel->findByEmail($data['email_propietario'] ?? '')) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        // 8. Validate business fields
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
            $this->logRegistro($data['email_propietario'] ?? '', 'validacion_fallida');
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // 9. Create user
        $userId = $usuarioModel->create([
            'nombre'        => $data['nombre_propietario'],
            'email'         => $data['email_propietario'],
            'telefono'      => $data['telefono_propietario'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol'           => 'comerciante',
            'activo'        => 0,
        ]);

        // 10. Upload images (logo, portada)
        $logoPath = null;
        $portadaPath = null;
        if (!empty($_FILES['logo']['name'])) {
            $logoPath = ImageHelper::upload($_FILES['logo'], 'negocios');
        }
        if (!empty($_FILES['portada']['name'])) {
            $portadaPath = ImageHelper::upload($_FILES['portada'], 'negocios');
        }

        // 10b. Red social
        $redSocialField = null;
        $redSocialUrl = !empty($data['red_social_url']) ? $data['red_social_url'] : null;
        $redSocialTipo = $data['red_social_tipo'] ?? '';
        $redSocialMap = [
            'facebook' => 'facebook', 'instagram' => 'instagram', 'tiktok' => 'tiktok',
            'youtube' => 'youtube', 'twitter' => 'twitter', 'linkedin' => 'linkedin',
        ];

        // 10c. Idiomas
        $idiomas = !empty($_POST['idiomas']) && is_array($_POST['idiomas']) ? json_encode($_POST['idiomas']) : null;

        // 11. Create negocio
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
            'whatsapp'          => $data['telefono_propietario'],
            'email'             => $data['email_propietario'],
            'sitio_web'         => !empty($data['sitio_web_comercio']) ? $data['sitio_web_comercio'] : null,
            'lat'               => !empty($data['lat']) ? (float) $data['lat'] : null,
            'lng'               => !empty($data['lng']) ? (float) $data['lng'] : null,
            'como_llegar'       => !empty($_POST['como_llegar']) ? $_POST['como_llegar'] : null,
            'activo'            => 0,
            'verificado'        => 0,
            'status'            => 'pendiente',
            'plan_id'           => 1,
            'propietario_id'    => $userId,
            'idiomas'           => $idiomas,
            'horario'           => !empty($data['horario']) ? mb_substr($data['horario'], 0, 255) : null,
        ];
        if ($logoPath) $negocioData['logo'] = $logoPath;
        if ($portadaPath) $negocioData['portada'] = $portadaPath;
        if ($redSocialUrl && isset($redSocialMap[$redSocialTipo])) {
            $negocioData[$redSocialMap[$redSocialTipo]] = $redSocialUrl;
        } elseif ($redSocialUrl && $redSocialTipo === 'otra') {
            $negocioData['red_social_1'] = $redSocialUrl;
        }
        $negocioId = $negocioModel->create($negocioData);

        // 12. Save temporadas
        $temporadaIds = $_POST['temporadas'] ?? [];
        $promociones = $_POST['temporada_promocion'] ?? [];
        if (!empty($temporadaIds) && is_array($temporadaIds)) {
            $tempModel = new Temporada($this->db);
            $tempModel->syncNegocioTemporadas($negocioId, $temporadaIds, $promociones);
        }

        // 13. Audit log
        $this->logRegistro($data['email_propietario'], 'exitoso', $negocioId);

        // 14. Send emails
        $catNombre = '';
        if (!empty($data['categoria_id'])) {
            $cat = (new Categoria($this->db))->find((int) $data['categoria_id']);
            $catNombre = $cat['nombre'] ?? '';
        }

        // Email to admin
        $tempNames = [];
        if (!empty($temporadaIds)) {
            $tempModel = $tempModel ?? new Temporada($this->db);
            foreach ($temporadaIds as $tid) {
                $t = $tempModel->find((int) $tid);
                if ($t) $tempNames[] = ($t['emoji'] ?? '') . ' ' . $t['nombre'];
            }
        }

        $adminBody = EmailHelper::wrap(
            "<h2>Nuevo comercio registrado</h2>" .
            "<table style='width:100%;border-collapse:collapse;margin:1rem 0;'>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;width:140px;'>Comercio</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['nombre_comercio']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Categoría</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($catNombre) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Propietario</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['nombre_propietario']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Email</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['email_propietario']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Teléfono</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['telefono_propietario']) . "</td></tr>" .
            "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Dirección</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . htmlspecialchars($data['direccion_comercio']) . "</td></tr>" .
            (!empty($tempNames) ? "<tr><td style='padding:8px;border-bottom:1px solid #E2E8F0;font-weight:600;'>Temporadas</td><td style='padding:8px;border-bottom:1px solid #E2E8F0;'>" . implode(', ', $tempNames) . "</td></tr>" : "") .
            "</table>" .
            "<p style='margin-top:1rem;color:#64748B;'>Recuerda: el compromiso es responder en un máximo de 48 horas.</p>" .
            "<p style='margin-top:1rem;'><a href='" . SITE_URL . "/admin/negocios?status=pendiente' style='background:#1B4965;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;'>Revisar en el panel</a></p>"
        );
        EmailHelper::send('contacto@purranque.info', 'Nuevo registro de comercio — ' . $data['nombre_comercio'], $adminBody);

        // Email to comerciante
        $welcomeBody = EmailHelper::wrap(
            "<h2>Solicitud de registro recibida</h2>" .
            "<p>Hola <strong>" . htmlspecialchars($data['nombre_propietario']) . "</strong>,</p>" .
            "<p>Hemos recibido tu solicitud de registro del comercio <strong>" . htmlspecialchars($data['nombre_comercio']) . "</strong> en Visita Puerto Octay.</p>" .
            "<p>Tu solicitud será revisada por nuestro equipo. Te informaremos en un máximo de <strong>48 horas</strong> si tu registro fue aprobado.</p>" .
            "<div style='background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:1rem;margin:1.5rem 0;'>" .
            "<p style='margin:0 0 0.5rem;font-weight:600;'>Datos registrados:</p>" .
            "<p style='margin:0.25rem 0;'>Comercio: " . htmlspecialchars($data['nombre_comercio']) . "</p>" .
            "<p style='margin:0.25rem 0;'>Categoría: " . htmlspecialchars($catNombre) . "</p>" .
            "<p style='margin:0.25rem 0;'>Dirección: " . htmlspecialchars($data['direccion_comercio']) . "</p>" .
            "</div>" .
            "<p>Si necesitas modificar algo o tienes consultas:</p>" .
            "<p>Email: <a href='mailto:contacto@purranque.info'>contacto@purranque.info</a><br>WhatsApp: <a href='https://wa.me/56976547757'>+56 9 7654 7757</a></p>" .
            "<p style='margin-top:1.5rem;'>Gracias por confiar en Visita Puerto Octay.</p>"
        );
        EmailHelper::send($data['email_propietario'], 'Solicitud de registro recibida — ' . SITE_NAME, $welcomeBody);

        // 15. Set success data for dedicated page
        $_SESSION['registro_exito'] = true;
        $_SESSION['registro_email'] = $data['email_propietario'];
        $_SESSION['registro_nombre'] = $data['nombre_comercio'];
        header('Location: ' . SITE_URL . '/registrar-comercio/gracias');
        exit;
    }

    public function gracias(): void
    {
        if (empty($_SESSION['registro_exito'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        $email = $_SESSION['registro_email'] ?? '';
        $nombre = $_SESSION['registro_nombre'] ?? '';
        unset($_SESSION['registro_exito'], $_SESSION['registro_email'], $_SESSION['registro_nombre']);

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
