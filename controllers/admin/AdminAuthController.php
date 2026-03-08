<?php

class AdminAuthController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function loginForm(): void
    {
        // Si ya está logueado, redirigir al dashboard
        if (!empty($_SESSION['usuario_id'])) {
            header('Location: ' . SITE_URL . '/admin');
            exit;
        }

        $error = '';
        require ROOT_PATH . '/views/admin/login.php';
    }

    public function login(): void
    {
        CsrfMiddleware::validate();

        if (!RateLimiter::check('admin_login', 5, 300)) {
            $error = 'Demasiados intentos. Intente nuevamente en 5 minutos.';
            require ROOT_PATH . '/views/admin/login.php';
            return;
        }

        $email = Sanitizer::clean($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->findByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password_hash'])) {
            $error = 'Email o contraseña incorrectos.';
            require ROOT_PATH . '/views/admin/login.php';
            return;
        }

        if (!$usuario['activo']) {
            $error = 'Su cuenta se encuentra deshabilitada.';
            require ROOT_PATH . '/views/admin/login.php';
            return;
        }

        AuthMiddleware::login($usuario);

        // Actualizar último login
        $usuarioModel->update((int) $usuario['id'], [
            'ultimo_login' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('login', 'usuarios', (int) $usuario['id'], 'Inicio de sesión');

        header('Location: ' . SITE_URL . '/admin');
        exit;
    }

    public function logout(): void
    {
        $uid = AuthMiddleware::userId();
        if ($uid) {
            AuditLog::log('logout', 'usuarios', $uid, 'Cierre de sesión');
        }

        AuthMiddleware::logout();
        header('Location: ' . SITE_URL . '/admin/login');
        exit;
    }
}
