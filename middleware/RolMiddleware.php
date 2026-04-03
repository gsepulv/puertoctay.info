<?php

class RolMiddleware
{
    public static function check(string ...$rolesPermitidos): void
    {
        if (empty($_SESSION['usuario_id'])) {
            header('Location: ' . SITE_URL . '/login');
            exit;
        }

        $rolActual = $_SESSION['usuario_rol'] ?? '';
        if (!in_array($rolActual, $rolesPermitidos, true)) {
            http_response_code(403);
            echo '<h1>403 — Acceso denegado</h1><p>No tienes permisos para acceder a esta sección.</p>';
            echo '<p><a href="' . SITE_URL . '/">Volver al inicio</a></p>';
            exit;
        }
    }

    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['usuario_id']);
    }

    public static function currentUser(): ?array
    {
        if (empty($_SESSION['usuario_id'])) {
            return null;
        }
        return [
            'id'     => (int) $_SESSION['usuario_id'],
            'nombre' => $_SESSION['usuario_nombre'] ?? '',
            'rol'    => $_SESSION['usuario_rol'] ?? '',
        ];
    }
}
