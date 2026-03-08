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
$router->add('GET', '/categorias', 'CategoriaController@index');
$router->add('GET', '/categoria/{slug}', 'CategoriaController@show');
$router->add('GET', '/directorio', 'NegocioController@index');
$router->add('GET', '/negocio/{slug}', 'NegocioController@show');
$router->add('GET', '/turismo', 'NegocioController@turismo');
$router->add('GET', '/patrimonio', 'NegocioController@patrimonio');
$router->add('GET', '/mapa', 'MapaController@index');
$router->add('GET', '/buscar', 'BuscarController@index');

// Noticias
$router->add('GET', '/noticias', 'NoticiaController@index');
$router->add('GET', '/noticias/categoria/{slug}', 'NoticiaController@porCategoria');
$router->add('GET', '/noticias/{slug}', 'NoticiaController@show');

// API
$router->add('GET', '/api/negocios.json', 'NegociosApiController@json');

// Admin — Negocios
$router->add('GET', '/admin/negocios', 'AdminNegocioController@index');
$router->add('GET', '/admin/negocios/crear', 'AdminNegocioController@crear');
$router->add('POST', '/admin/negocios/guardar', 'AdminNegocioController@guardar');
$router->add('GET', '/admin/negocios/{id}/editar', 'AdminNegocioController@editar');
$router->add('POST', '/admin/negocios/{id}/actualizar', 'AdminNegocioController@actualizar');
$router->add('POST', '/admin/negocios/{id}/eliminar', 'AdminNegocioController@eliminar');
$router->add('POST', '/admin/negocios/{id}/verificar', 'AdminNegocioController@verificar');

// Admin — Noticias
$router->add('GET', '/admin/noticias', 'AdminNoticiaController@index');
$router->add('GET', '/admin/noticias/crear', 'AdminNoticiaController@crear');
$router->add('POST', '/admin/noticias/guardar', 'AdminNoticiaController@guardar');
$router->add('GET', '/admin/noticias/{id}/editar', 'AdminNoticiaController@editar');
$router->add('POST', '/admin/noticias/{id}/actualizar', 'AdminNoticiaController@actualizar');
$router->add('POST', '/admin/noticias/{id}/eliminar', 'AdminNoticiaController@eliminar');
$router->add('POST', '/admin/noticias/{id}/estado', 'AdminNoticiaController@estado');
$router->add('POST', '/admin/noticias/{id}/destacar', 'AdminNoticiaController@destacar');

// Despachar
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
