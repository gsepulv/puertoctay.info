<?php
/**
 * puertoctay.info — Punto de entrada
 */

// Local: bootstrap.php está en dirname(__DIR__)
// Producción: public_html está separado del repo
$parentDir = dirname(__DIR__);
if (file_exists($parentDir . '/bootstrap.php')) {
    define('ROOT_PATH', $parentDir);
} else {
    define('ROOT_PATH', '/home/puertoctay/puertoctay_repo');
}

require_once ROOT_PATH . '/bootstrap.php';
require_once ROOT_PATH . '/router.php';

// Instanciar router y definir rutas
$router = new Router();

// Públicas
$router->add('GET', '/', 'HomeController@index');

// Despachar
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
