<?php
/**
 * Autenticación de sesión para panel admin.
 */

class AuthMiddleware
{
    /**
     * Verificar que el usuario esté autenticado.
     * Redirige al login si no lo está.
     */
    public static function check(): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: ' . SITE_URL . '/admin/login');
            exit;
        }
    }

    /**
     * Iniciar sesión: guardar datos y regenerar ID.
     */
    public static function login(array $usuario): void
    {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_rol'] = $usuario['rol'];
    }

    /**
     * Cerrar sesión.
     */
    public static function logout(): void
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
    }

    /**
     * Obtener ID del usuario autenticado.
     */
    public static function userId(): ?int
    {
        return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
    }
}
