<?php

class RegistroVisitanteController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        if (!empty($_SESSION['usuario_id'])) {
            header('Location: ' . SITE_URL . '/mi-cuenta');
            exit;
        }

        $errores = $_SESSION['registro_errores'] ?? [];
        $datos = $_SESSION['registro_datos'] ?? [];
        unset($_SESSION['registro_errores'], $_SESSION['registro_datos']);

        $pageTitle = 'Crear Cuenta — ' . SITE_NAME;
        $pageDescription = 'Regístrate gratis en ' . SITE_NAME . ' para dejar reseñas y guardar favoritos.';
        $viewName = 'public/registro-visitante';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function store(): void
    {
        CsrfMiddleware::validate();

        if (!RateLimiter::check('registro_visitante', 3, 3600)) {
            $_SESSION['registro_errores'] = ['Demasiados intentos. Intenta de nuevo en 1 hora.'];
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }

        // Honeypot
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Ingresa un email válido.';
        }
        if (mb_strlen($password) < 8) {
            $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
        }
        if ($password !== $passwordConfirm) {
            $errores[] = 'Las contraseñas no coinciden.';
        }
        if (($data['acepto_terminos'] ?? '') !== '1') {
            $errores[] = 'Debes aceptar los términos y la política de privacidad.';
        }

        // Check email uniqueness
        $usuarioModel = new Usuario($this->db);
        if ($usuarioModel->findByEmail($data['email'])) {
            $errores[] = 'Ya existe una cuenta con ese email.';
        }

        if (!empty($errores)) {
            $_SESSION['registro_errores'] = $errores;
            $_SESSION['registro_datos'] = $data;
            header('Location: ' . SITE_URL . '/registro');
            exit;
        }

        // Create user
        $userId = $usuarioModel->create([
            'nombre'        => $data['nombre'],
            'email'         => $data['email'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'rol'           => 'visitante',
            'activo'        => 1,
        ]);

        AuditLog::log("registro", "usuarios", $userId, "Registro visitante: " . $data["nombre"]);

        // Auto-login
        $usuario = $usuarioModel->find($userId);
        AuthMiddleware::login($usuario);

        header('Location: ' . SITE_URL . '/mi-cuenta');
        exit;
    }
}
