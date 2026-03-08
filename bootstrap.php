<?php
/**
 * puertoctay.info — Bootstrap
 * Carga configuración, sesión, autoload y conexión BD.
 */

// Configuración
require_once ROOT_PATH . '/config.php';

// Zona horaria
date_default_timezone_set(SITE_TIMEZONE);

// Sesión segura
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => APP_ENV === 'production',
        'httponly'  => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// Autoload de clases: models/, controllers/, middleware/, helpers/ y subdirectorios
spl_autoload_register(function (string $class): void {
    $dirs = [
        'models', 'controllers', 'middleware', 'helpers',
        'controllers/api', 'controllers/admin',
    ];
    foreach ($dirs as $dir) {
        $file = ROOT_PATH . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Conexión PDO singleton
function getDB(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
