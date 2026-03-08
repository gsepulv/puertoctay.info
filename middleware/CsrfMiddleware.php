<?php
/**
 * Protección CSRF por token de sesión.
 */

class CsrfMiddleware
{
    /**
     * Generar token si no existe en sesión.
     */
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validar token enviado en POST.
     */
    public static function validate(): bool
    {
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            echo 'Solicitud no válida. Por favor, recargue la página e intente nuevamente.';
            exit;
        }
        return true;
    }
}

/**
 * Helper: retorna input hidden con token CSRF.
 */
function csrf_field(): string
{
    $token = htmlspecialchars(CsrfMiddleware::generateToken(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
