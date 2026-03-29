<?php
/**
 * Middleware de modo construcción.
 * Si está activo, muestra la página de "en construcción" a todos
 * excepto admins logueados.
 */

class MaintenanceMiddleware
{
    /**
     * Verificar si el modo construcción está activo.
     * Debe llamarse después de session_start() y getDB().
     */
    public static function check(PDO $db): void
    {
        // No bloquear rutas de admin ni login
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        if (str_starts_with($uri, '/admin')) {
            return;
        }

        // Si el admin está logueado, dejar pasar
        if (!empty($_SESSION['usuario_id'])) {
            return;
        }

        // Consultar si el modo está activo
        $stmt = $db->prepare(
            "SELECT valor FROM configuracion WHERE grupo = 'mantenimiento' AND clave = :clave LIMIT 1"
        );

        $stmt->execute(['clave' => 'modo_construccion']);
        $activo = $stmt->fetchColumn();

        if ($activo !== '1') {
            return;
        }

        // Obtener configuración
        $config = [];
        $keys = ['mensaje_principal', 'mensaje_secundario', 'fecha_lanzamiento', 'mostrar_countdown', 'mostrar_suscripcion'];
        foreach ($keys as $key) {
            $stmt->execute(['clave' => $key]);
            $config[$key] = $stmt->fetchColumn() ?: '';
        }

        // Redes sociales
        $redes = [];
        $stmtSocial = $db->prepare(
            "SELECT clave, valor FROM configuracion WHERE grupo = 'social' AND valor != '' AND valor IS NOT NULL"
        );
        $stmtSocial->execute();
        while ($row = $stmtSocial->fetch()) {
            $redes[$row['clave']] = $row['valor'];
        }

        // Nombre del sitio
        $stmt->execute(['clave' => 'sitio_nombre']);
        $sitioNombre = $stmt->fetchColumn() ?: 'Visita Puerto Octay';

        http_response_code(503);
        header('Retry-After: 86400');
        require ROOT_PATH . '/views/maintenance.php';
        exit;
    }
}
