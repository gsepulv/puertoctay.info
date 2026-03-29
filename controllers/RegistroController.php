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
        $viewName = 'public/registro';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function store(): void
    {
        CsrfMiddleware::validate();

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
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        // 1. Validate policies
        if (($data['politica_terminos'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar los Términos y Condiciones.';
        }
        if (($data['politica_privacidad'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Privacidad.';
        }
        if (($data['politica_cookies'] ?? '') !== 'acepto') {
            $errores[] = 'Debes aceptar la Política de Cookies.';
        }

        // 2. Validate owner fields
        if (empty($data['nombre_propietario']) || mb_strlen($data['nombre_propietario']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (empty($data['email_propietario']) || !filter_var($data['email_propietario'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Ingresa un email válido.';
        }
        if (empty($data['telefono_propietario']) || mb_strlen($data['telefono_propietario']) < 9) {
            $errores[] = 'Ingresa un teléfono válido (mínimo 9 caracteres).';
        }
        if (mb_strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden.';
        }

        // 3. Check email uniqueness in propietarios
        $stmt = $this->db->prepare("SELECT id FROM propietarios WHERE email = ?");
        $stmt->execute([$data['email_propietario']]);
        if ($stmt->fetch()) {
            $errores[] = 'Ya existe una cuenta registrada con ese email.';
        }

        // 4. Validate business fields
        if (empty($data['nombre_comercio']) || mb_strlen($data['nombre_comercio']) < 3) {
            $errores[] = 'El nombre del comercio debe tener al menos 3 caracteres.';
        }
        if (empty($data['categoria_id'])) {
            $errores[] = 'Selecciona una categoría.';
        }
        if (empty($data['descripcion_comercio']) || mb_strlen($data['descripcion_comercio']) < 20) {
            $errores[] = 'La descripción debe tener al menos 20 caracteres.';
        }
        if (empty($data['direccion_comercio'])) {
            $errores[] = 'La dirección es obligatoria.';
        }

        // If errors, redirect back with data
        if (!empty($errores)) {
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registrar-comercio');
            exit;
        }

        // 5. Create propietario (propietario_id FK points to propietarios table)
        $stmtProp = $this->db->prepare(
            "INSERT INTO propietarios (nombre, email, password_hash, telefono, activo)
             VALUES (?, ?, ?, ?, 0)"
        );
        $stmtProp->execute([
            $data['nombre_propietario'],
            $data['email_propietario'],
            password_hash($password, PASSWORD_DEFAULT),
            $data['telefono_propietario'],
        ]);
        $propietarioId = (int) $this->db->lastInsertId();

        // 6. Create negocio
        $slug = SlugHelper::unique($this->db, 'negocios', $data['nombre_comercio']);
        $negocioModel = new Negocio($this->db);
        $negocioModel->create([
            'nombre'           => $data['nombre_comercio'],
            'slug'             => $slug,
            'tipo'             => 'comercio',
            'categoria_id'     => (int) $data['categoria_id'],
            'descripcion_corta'=> mb_substr($data['descripcion_comercio'], 0, 300),
            'descripcion_larga'=> $data['descripcion_comercio'],
            'direccion'        => $data['direccion_comercio'],
            'telefono'         => $data['telefono_comercio'] ?? null,
            'whatsapp'         => $data['telefono_propietario'],
            'email'            => $data['email_propietario'],
            'sitio_web'        => !empty($data['sitio_web_comercio']) ? $data['sitio_web_comercio'] : null,
            'activo'           => 0,
            'verificado'       => 0,
            'plan_id'          => 1,
            'propietario_id'   => $propietarioId,
        ]);

        AuditLog::log('registro', 'negocios', null,
            "Nuevo registro: {$data['nombre_comercio']} por {$data['nombre_propietario']}");

        // Send notification emails (non-blocking: failure won't break registration)
        $catNombre = '';
        if (!empty($data['categoria_id'])) {
            $cat = (new Categoria($this->db))->find((int) $data['categoria_id']);
            $catNombre = $cat['nombre'] ?? '';
        }
        EmailHelper::notificarNuevoRegistro(
            $data,
            ['nombre' => $data['nombre_propietario'], 'email' => $data['email_propietario'], 'telefono' => $data['telefono_propietario']],
            $catNombre
        );

        $_SESSION['registro_exito'] = true;
        header('Location: ' . SITE_URL . '/registrar-comercio');
        exit;
    }
}
