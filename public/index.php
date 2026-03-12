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
    define('ROOT_PATH', '/home/visitapuertoctay/puertoctay_repo');
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
$router->add('GET', '/pagina/{slug}', 'PaginaController@show');

// Noticias
$router->add('GET', '/noticias', 'NoticiaController@index');
$router->add('GET', '/noticias/categoria/{slug}', 'NoticiaController@porCategoria');
$router->add('GET', '/noticias/{slug}', 'NoticiaController@show');

// API
$router->add('GET', '/api/negocios.json', 'NegociosApiController@json');

// Admin — Auth
$router->add('GET', '/admin/login', 'AdminAuthController@loginForm');
$router->add('POST', '/admin/login', 'AdminAuthController@login');
$router->add('GET', '/admin/logout', 'AdminAuthController@logout');

// Admin — Dashboard
$router->add('GET', '/admin', 'AdminDashboardController@index');

// Admin — Negocios
$router->add('GET', '/admin/negocios', 'AdminNegocioController@index');
$router->add('GET', '/admin/negocios/crear', 'AdminNegocioController@crear');
$router->add('POST', '/admin/negocios/guardar', 'AdminNegocioController@guardar');
$router->add('GET', '/admin/negocios/{id}/editar', 'AdminNegocioController@editar');
$router->add('POST', '/admin/negocios/{id}/actualizar', 'AdminNegocioController@actualizar');
$router->add('POST', '/admin/negocios/{id}/eliminar', 'AdminNegocioController@eliminar');
$router->add('POST', '/admin/negocios/{id}/verificar', 'AdminNegocioController@verificar');

// Admin — Categorías
$router->add('GET', '/admin/categorias', 'AdminCategoriaController@index');
$router->add('GET', '/admin/categorias/crear', 'AdminCategoriaController@crear');
$router->add('POST', '/admin/categorias/guardar', 'AdminCategoriaController@guardar');
$router->add('GET', '/admin/categorias/{id}/editar', 'AdminCategoriaController@editar');
$router->add('POST', '/admin/categorias/{id}/actualizar', 'AdminCategoriaController@actualizar');
$router->add('POST', '/admin/categorias/{id}/eliminar', 'AdminCategoriaController@eliminar');

// Admin — Eventos
$router->add('GET', '/admin/eventos', 'AdminEventoController@index');
$router->add('GET', '/admin/eventos/crear', 'AdminEventoController@crear');
$router->add('POST', '/admin/eventos/guardar', 'AdminEventoController@guardar');
$router->add('GET', '/admin/eventos/{id}/editar', 'AdminEventoController@editar');
$router->add('POST', '/admin/eventos/{id}/actualizar', 'AdminEventoController@actualizar');
$router->add('POST', '/admin/eventos/{id}/eliminar', 'AdminEventoController@eliminar');

// Admin — Noticias
$router->add('GET', '/admin/noticias', 'AdminNoticiaController@index');
$router->add('GET', '/admin/noticias/crear', 'AdminNoticiaController@crear');
$router->add('POST', '/admin/noticias/guardar', 'AdminNoticiaController@guardar');
$router->add('GET', '/admin/noticias/{id}/editar', 'AdminNoticiaController@editar');
$router->add('POST', '/admin/noticias/{id}/actualizar', 'AdminNoticiaController@actualizar');
$router->add('POST', '/admin/noticias/{id}/eliminar', 'AdminNoticiaController@eliminar');
$router->add('POST', '/admin/noticias/{id}/estado', 'AdminNoticiaController@estado');
$router->add('POST', '/admin/noticias/{id}/destacar', 'AdminNoticiaController@destacar');

// Admin — Reseñas
$router->add('GET', '/admin/resenas', 'AdminResenaController@index');
$router->add('POST', '/admin/resenas/{id}/aprobar', 'AdminResenaController@aprobar');
$router->add('POST', '/admin/resenas/{id}/rechazar', 'AdminResenaController@rechazar');
$router->add('POST', '/admin/resenas/{id}/eliminar', 'AdminResenaController@eliminar');

// Admin — Usuarios
$router->add('GET', '/admin/usuarios', 'AdminUsuarioController@index');
$router->add('GET', '/admin/usuarios/crear', 'AdminUsuarioController@crear');
$router->add('POST', '/admin/usuarios/guardar', 'AdminUsuarioController@guardar');
$router->add('GET', '/admin/usuarios/{id}/editar', 'AdminUsuarioController@editar');
$router->add('POST', '/admin/usuarios/{id}/actualizar', 'AdminUsuarioController@actualizar');
$router->add('POST', '/admin/usuarios/{id}/eliminar', 'AdminUsuarioController@eliminar');

// Admin — Configuración
$router->add('GET', '/admin/configuracion', 'AdminConfiguracionController@index');
$router->add('POST', '/admin/configuracion/guardar', 'AdminConfiguracionController@guardar');

// Admin — SEO
$router->add('GET', '/admin/seo', 'AdminSeoController@index');
$router->add('POST', '/admin/seo/guardar', 'AdminSeoController@guardar');

// Admin — Páginas
$router->add('GET', '/admin/paginas', 'AdminPaginaController@index');
$router->add('GET', '/admin/paginas/crear', 'AdminPaginaController@crear');
$router->add('POST', '/admin/paginas/guardar', 'AdminPaginaController@guardar');
$router->add('GET', '/admin/paginas/{id}/editar', 'AdminPaginaController@editar');
$router->add('POST', '/admin/paginas/{id}/actualizar', 'AdminPaginaController@actualizar');
$router->add('POST', '/admin/paginas/{id}/eliminar', 'AdminPaginaController@eliminar');

// Admin — Placeholders (módulos pendientes)
$router->add('GET', '/admin/blog', 'AdminPlaceholderController@blog');
$router->add('GET', '/admin/banners', 'AdminPlaceholderController@banners');
$router->add('GET', '/admin/estadisticas', 'AdminPlaceholderController@estadisticas');
$router->add('GET', '/admin/mensajes', 'AdminPlaceholderController@mensajes');
$router->add('GET', '/admin/nurturing', 'AdminPlaceholderController@nurturing');
$router->add('GET', '/admin/correo', 'AdminPlaceholderController@correo');
$router->add('GET', '/admin/reportes', 'AdminPlaceholderController@reportes');
$router->add('GET', '/admin/planes', 'AdminPlaceholderController@planes');
$router->add('GET', '/admin/redes-sociales', 'AdminPlaceholderController@redesSociales');
$router->add('GET', '/admin/apariencia', 'AdminPlaceholderController@apariencia');
$router->add('GET', '/admin/textos-legales', 'AdminPlaceholderController@textosLegales');
$router->add('GET', '/admin/menu', 'AdminPlaceholderController@menu');
$router->add('GET', '/admin/mantenimiento', 'AdminPlaceholderController@mantenimiento');

// Despachar
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
