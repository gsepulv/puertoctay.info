<?php

class RecuperarController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function form(): void
    {
        $error = '';
        $exito = false;
        $pageTitle = 'Recuperar Contraseña — ' . SITE_NAME;
        $pageDescription = 'Recupera el acceso a tu cuenta.';
        $viewName = 'public/recuperar-contrasena';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function enviar(): void
    {
        CsrfMiddleware::validate();

        if (!RateLimiter::check('recuperar_' . ($_POST['email'] ?? ''), 3, 3600)) {
            $error = 'Demasiados intentos. Intenta de nuevo en 1 hora.';
            $exito = false;
            $pageTitle = 'Recuperar Contraseña — ' . SITE_NAME;
            $pageDescription = 'Recupera el acceso a tu cuenta.';
            $viewName = 'public/recuperar-contrasena';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        $email = Sanitizer::clean($_POST['email'] ?? '');
        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->findByEmail($email);

        // Always show success message (prevent email enumeration)
        $exito = true;
        $error = '';

        if ($usuario) {
            $token = bin2hex(random_bytes(32));
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $usuarioModel->update((int) $usuario['id'], [
                'reset_token'  => hash('sha256', $token),
                'reset_expira' => $expira,
            ]);

            $resetUrl = SITE_URL . '/reset-password/' . $token;
            $body = EmailHelper::wrap("
                <h2>Recuperar contraseña</h2>
                <p>Hola <strong>{$usuario['nombre']}</strong>,</p>
                <p>Recibimos una solicitud para restablecer tu contraseña.</p>
                <p>Haz clic en el siguiente enlace (válido por 1 hora):</p>
                <p style='margin:1.5rem 0;'><a href='{$resetUrl}' style='background:#1B4965;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;'>Restablecer contraseña</a></p>
                <p style='font-size:0.85rem;color:#64748B;'>Si no solicitaste este cambio, ignora este mensaje.</p>
            ");
            EmailHelper::send($usuario['email'], 'Recuperar contraseña — ' . SITE_NAME, $body);
        }

        $pageTitle = 'Recuperar Contraseña — ' . SITE_NAME;
        $pageDescription = 'Recupera el acceso a tu cuenta.';
        $viewName = 'public/recuperar-contrasena';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function resetForm(string $token): void
    {
        $error = '';
        $tokenValido = $this->validarToken($token);

        $pageTitle = 'Nueva Contraseña — ' . SITE_NAME;
        $pageDescription = 'Establece tu nueva contraseña.';
        $viewName = 'public/reset-password';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function reset(string $token): void
    {
        CsrfMiddleware::validate();

        $usuario = $this->validarToken($token);
        if (!$usuario) {
            $error = 'El enlace ha expirado o no es válido.';
            $tokenValido = false;
            $pageTitle = 'Nueva Contraseña — ' . SITE_NAME;
            $pageDescription = 'Establece tu nueva contraseña.';
            $viewName = 'public/reset-password';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (mb_strlen($password) < 8) {
            $error = 'La contraseña debe tener al menos 8 caracteres.';
            $tokenValido = true;
            $pageTitle = 'Nueva Contraseña — ' . SITE_NAME;
            $pageDescription = 'Establece tu nueva contraseña.';
            $viewName = 'public/reset-password';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        if ($password !== $passwordConfirm) {
            $error = 'Las contraseñas no coinciden.';
            $tokenValido = true;
            $pageTitle = 'Nueva Contraseña — ' . SITE_NAME;
            $pageDescription = 'Establece tu nueva contraseña.';
            $viewName = 'public/reset-password';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        $usuarioModel = new Usuario($this->db);
        $usuarioModel->update((int) $usuario['id'], [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'   => null,
            'reset_expira'  => null,
        ]);

        AuditLog::log('reset_password', 'usuarios', (int) $usuario['id'], 'Contraseña restablecida');

        $_SESSION['flash_success'] = 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
        header('Location: ' . SITE_URL . '/login');
        exit;
    }

    private function validarToken(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $stmt = $this->db->prepare(
            "SELECT * FROM usuarios WHERE reset_token = :token AND reset_expira > NOW() LIMIT 1"
        );
        $stmt->execute(['token' => $hash]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }
}
