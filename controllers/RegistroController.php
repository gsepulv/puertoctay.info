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
        $categorias = (new Categoria($this->db))->findDirectorio();
        $errores = $_SESSION['registro_errores'] ?? [];
        $datos = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_errores'], $_SESSION['registro_datos']);

        $pageTitle = 'Registrar mi comercio — ' . SITE_NAME;
        $pageDescription = 'Registra tu negocio gratis en el directorio digital de Puerto Octay.';
        $viewName = 'public/registrar-comercio';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function store(): void
    {
        CsrfMiddleware::validate();

        // Rate limiting: 3 per hour per IP
        if (!RateLimiter::check('registro_comercio', 3, 3600)) {
            $_SESSION['registro_errores'] = ['Demasiados intentos. Intenta de nuevo en 1 hora.'];
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Honeypot
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $errores = [];

        // Validate policies
        if (($data['politica_terminos'] ?? '') !== 'acepto') $errores[] = 'Debes aceptar los Términos y Condiciones.';
        if (($data['politica_privacidad'] ?? '') !== 'acepto') $errores[] = 'Debes aceptar la Política de Privacidad.';
        if (($data['politica_cookies'] ?? '') !== 'acepto') $errores[] = 'Debes aceptar la Política de Cookies.';

        // Validate owner data
        if (empty($data['nombre_propietario']) || strlen($data['nombre_propietario']) < 3) $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        if (empty($data['email_propietario']) || !filter_var($data['email_propietario'], FILTER_VALIDATE_EMAIL)) $errores[] = 'Email no válido.';
        if (empty($data['telefono_propietario']) || strlen($data['telefono_propietario']) < 9) $errores[] = 'Teléfono debe tener al menos 9 caracteres.';

        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        if (strlen($password) < 8) $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        if ($password !== $passwordConfirm) $errores[] = 'Las contraseñas no coinciden.';

        // Check email uniqueness
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$data['email_propietario']]);
        if ($stmt->fetch()) $errores[] = 'Ya existe una cuenta con este email.';

        // Validate business data
        if (empty($data['nombre_comercio']) || strlen($data['nombre_comercio']) < 3) $errores[] = 'El nombre del comercio debe tener al menos 3 caracteres.';
        if (empty($data['categoria_id'])) $errores[] = 'Selecciona una categoría.';
        if (empty($data['descripcion_comercio']) || strlen($data['descripcion_comercio']) < 20) $errores[] = 'La descripción debe tener al menos 20 caracteres.';
        if (empty($data['direccion_comercio'])) $errores[] = 'La dirección es obligatoria.';

        if (!empty($errores)) {
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // Create user (inactive, role=comerciante)
        $usuarioModel = new Usuario($this->db);
        $userId = $usuarioModel->create([
            'nombre' => $data['nombre_propietario'],
            'email' => $data['email_propietario'],
            'telefono' => $data['telefono_propietario'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol' => 'comerciante',
            'activo' => 0,
        ]);

        // Create business (inactive, pending)
        $negocioModel = new Negocio($this->db);
        $slug = SlugHelper::unique($this->db, 'negocios', $data['nombre_comercio']);
        $negocioModel->create([
            'nombre' => $data['nombre_comercio'],
            'slug' => $slug,
            'tipo' => 'comercio',
            'categoria_id' => (int) $data['categoria_id'],
            'descripcion_corta' => mb_substr($data['descripcion_comercio'], 0, 300),
            'descripcion_larga' => $data['descripcion_comercio'],
            'direccion' => $data['direccion_comercio'],
            'telefono' => $data['telefono_comercio'] ?? null,
            'whatsapp' => $data['telefono_propietario'],
            'email' => $data['email_propietario'],
            'sitio_web' => $data['sitio_web_comercio'] ?? null,
            'activo' => 0,
            'verificado' => 0,
            'plan_id' => 1,
            'propietario_id' => $userId,
        ]);

        AuditLog::log('registro', 'negocios', null, "Nuevo registro: {$data['nombre_comercio']} por {$data['nombre_propietario']}");

        $_SESSION['registro_exito'] = true;
        header('Location: ' . SITE_URL . '/registrar-comercio');
        exit;
    }
}