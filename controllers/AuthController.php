<?php

class AuthController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function loginForm(): void
    {
        // If already logged in, redirect based on role
        if (!empty($_SESSION['usuario_id'])) {
            $this->redirectByRole($_SESSION['usuario_rol'] ?? '');
            return;
        }

        $error = '';
        $pageTitle = 'Iniciar Sesión — ' . SITE_NAME;
        $pageDescription = 'Accede a tu cuenta en ' . SITE_NAME;
        $viewName = 'public/login';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function login(): void
    {
        CsrfMiddleware::validate();

        if (!RateLimiter::check('login_' . ($_POST['email'] ?? ''), 5, 300)) {
            $error = 'Demasiados intentos. Intenta de nuevo en 5 minutos.';
            $pageTitle = 'Iniciar Sesión — ' . SITE_NAME;
            $pageDescription = 'Accede a tu cuenta en ' . SITE_NAME;
            $viewName = 'public/login';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        $email = Sanitizer::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            $error = 'Email o contraseña incorrectos.';
            $pageTitle = 'Iniciar Sesión — ' . SITE_NAME;
            $pageDescription = 'Accede a tu cuenta en ' . SITE_NAME;
            $viewName = 'public/login';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        if (!$usuario['activo']) {
            $error = 'Tu cuenta aún no ha sido activada. Revisa tu email o contacta al administrador.';
            $pageTitle = 'Iniciar Sesión — ' . SITE_NAME;
            $pageDescription = 'Accede a tu cuenta en ' . SITE_NAME;
            $viewName = 'public/login';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        AuthMiddleware::login($usuario);

        // Update last login
        $usuarioModel->update((int) $usuario['id'], [
            'ultimo_login' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('login', 'usuarios', (int) $usuario['id'], 'Inicio de sesión');

        $this->redirectByRole($usuario['rol']);
    }

    public function logout(): void
    {
        $uid = AuthMiddleware::userId();
        if ($uid) {
            AuditLog::log('logout', 'usuarios', $uid, 'Cierre de sesión');
        }
        AuthMiddleware::logout();
        header('Location: ' . SITE_URL . '/');
        exit;
    }

    private function redirectByRole(string $rol): void
    {
        switch ($rol) {
            case 'admin':
            case 'editor':
            case 'moderador':
                header('Location: ' . SITE_URL . '/admin');
                break;
            case 'comerciante':
                header('Location: ' . SITE_URL . '/mi-comercio');
                break;
            default:
                header('Location: ' . SITE_URL . '/mi-cuenta');
                break;
        }
        exit;
    }
}
