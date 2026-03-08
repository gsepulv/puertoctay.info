<?php
/**
 * puertoctay.info — Punto de entrada
 */

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/bootstrap.php';
require_once ROOT_PATH . '/router.php';

// Instanciar router y definir rutas
$router = new Router();

// Públicas
$router->add('GET', '/', 'HomeController@index');

// Despachar
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
