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
require_once ROOT_PATH . '/middleware/MaintenanceMiddleware.php';
require_once ROOT_PATH . '/router.php';
MaintenanceMiddleware::check(getDB());

$router = new Router();

// ── Públicas ──────────────────────────────────────────
$router->add('GET', '/', 'HomeController@index');
$router->add('GET', '/categorias', 'CategoriaController@index');
$router->add('GET', '/categoria/{slug}', 'CategoriaController@show');
$router->add('GET', '/directorio', 'NegocioController@index');
$router->add('GET', '/negocio/{slug}', 'NegocioController@show');
$router->add('GET', '/turismo', 'NegocioController@turismo');
$router->add('GET', '/patrimonio', 'NegocioController@patrimonio');
$router->add('GET', '/mapa', 'MapaController@index');
$router->add('GET', '/buscar', 'BuscarController@index');
$router->add("GET", "/paginas-amigas", "PaginaController@showAmigas");
$router->add('GET', '/pagina/{slug}', 'PaginaController@show');
$router->add('GET', '/noticias', 'NoticiaController@index');
$router->add('GET', '/noticias/categoria/{slug}', 'NoticiaController@porCategoria');
$router->add('GET', '/noticias/{slug}', 'NoticiaController@show');
$router->add('GET', '/politica-de-privacidad', 'PaginaController@showLegal');
$router->add('GET', '/terminos-y-condiciones', 'PaginaController@showLegal');
$router->add('GET', '/politica-de-cookies', 'PaginaController@showLegal');
$router->add('GET', '/planes', 'PlanController@index');
$router->add('GET', '/registrar-comercio', 'RegistroController@index');
$router->add('POST', '/registrar-comercio', 'RegistroController@store');
$router->add('GET', '/contacto', 'ContactoController@index');
$router->add('POST', '/contacto', 'ContactoController@enviar');
$router->add('GET', '/sitemap.xml', 'SitemapController@index');
$router->add('GET', '/api/negocios.json', 'NegociosApiController@json');
$router->add('POST', '/api/subscribe', 'SubscribeApiController@store');

// ── Auth pública ─────────────────────────────────────
$router->add('GET', '/login', 'AuthController@loginForm');
$router->add('POST', '/login', 'AuthController@login');
$router->add('GET', '/logout', 'AuthController@logout');
$router->add('GET', '/registro', 'RegistroVisitanteController@index');
$router->add('POST', '/registro', 'RegistroVisitanteController@store');
$router->add('GET', '/recuperar-contrasena', 'RecuperarController@form');
$router->add('POST', '/recuperar-contrasena', 'RecuperarController@enviar');
$router->add('GET', '/reset-password/{token}', 'RecuperarController@resetForm');
$router->add('POST', '/reset-password/{token}', 'RecuperarController@reset');

// ── Panel Comerciante ────────────────────────────────
$router->add('GET', '/mi-comercio', 'PanelComercianteController@dashboard');
$router->add('GET', '/mi-comercio/editar', 'PanelComercianteController@editarNegocio');
$router->add('POST', '/mi-comercio/editar', 'PanelComercianteController@actualizarNegocio');
$router->add('GET', '/mi-comercio/perfil', 'PanelComercianteController@perfil');
$router->add('POST', '/mi-comercio/perfil', 'PanelComercianteController@actualizarPerfil');

// ── Panel Visitante ──────────────────────────────────
$router->add('GET', '/mi-cuenta', 'PanelVisitanteController@dashboard');
$router->add('GET', '/mi-cuenta/favoritos', 'PanelVisitanteController@favoritos');
$router->add('GET', '/mi-cuenta/resenas', 'PanelVisitanteController@resenas');
$router->add('GET', '/mi-cuenta/perfil', 'PanelVisitanteController@perfil');
$router->add('POST', '/mi-cuenta/perfil', 'PanelVisitanteController@actualizarPerfil');
$router->add('POST', '/api/favorito', 'PanelVisitanteController@toggleFavorito');

// ── Reseñas públicas ─────────────────────────────────
$router->add('POST', '/negocio/{slug}/resena', 'NegocioController@guardarResena');

// ── Admin: Auth ───────────────────────────────────────
$router->add('GET', '/admin/login', 'AdminAuthController@loginForm');
$router->add('POST', '/admin/login', 'AdminAuthController@login');
$router->add('GET', '/admin/logout', 'AdminAuthController@logout');
$router->add('GET', '/admin', 'AdminDashboardController@index');

// ── Admin: Negocios ───────────────────────────────────
$router->add('GET', '/admin/hero', 'AdminHeroController@index');
$router->add('POST', '/admin/hero', 'AdminHeroController@actualizar');
$router->add('GET', '/admin/negocios', 'AdminNegocioController@index');
$router->add('GET', '/admin/negocios/crear', 'AdminNegocioController@crear');
$router->add('POST', '/admin/negocios/guardar', 'AdminNegocioController@guardar');
$router->add('GET', '/admin/negocios/{id}/editar', 'AdminNegocioController@editar');
$router->add('POST', '/admin/negocios/{id}/actualizar', 'AdminNegocioController@actualizar');
$router->add('POST', '/admin/negocios/{id}/eliminar', 'AdminNegocioController@eliminar');
$router->add('POST', '/admin/negocios/{id}/verificar', 'AdminNegocioController@verificar');
$router->add('POST', '/admin/negocios/{id}/aprobar', 'AdminNegocioController@aprobar');
$router->add('POST', '/admin/negocios/{id}/rechazar', 'AdminNegocioController@rechazar');

// ── Admin: Categorías ─────────────────────────────────
$router->add('GET', '/admin/categorias', 'AdminCategoriaController@index');
$router->add('GET', '/admin/categorias/crear', 'AdminCategoriaController@crear');
$router->add('POST', '/admin/categorias/guardar', 'AdminCategoriaController@guardar');
$router->add('GET', '/admin/categorias/{id}/editar', 'AdminCategoriaController@editar');
$router->add('POST', '/admin/categorias/{id}/actualizar', 'AdminCategoriaController@actualizar');
$router->add('POST', '/admin/categorias/{id}/eliminar', 'AdminCategoriaController@eliminar');

// ── Admin: Temporadas ────────────────────────────────
$router->add('GET', '/admin/temporadas', 'AdminTemporadaController@index');
$router->add('GET', '/admin/temporadas/crear', 'AdminTemporadaController@crear');
$router->add('POST', '/admin/temporadas/guardar', 'AdminTemporadaController@guardar');
$router->add('GET', '/admin/temporadas/{id}/editar', 'AdminTemporadaController@editar');
$router->add('POST', '/admin/temporadas/{id}/actualizar', 'AdminTemporadaController@actualizar');
$router->add('POST', '/admin/temporadas/{id}/eliminar', 'AdminTemporadaController@eliminar');

// ── Admin: Eventos ────────────────────────────────────
$router->add('GET', '/admin/eventos', 'AdminEventoController@index');
$router->add('GET', '/admin/eventos/crear', 'AdminEventoController@crear');
$router->add('POST', '/admin/eventos/guardar', 'AdminEventoController@guardar');
$router->add('GET', '/admin/eventos/{id}/editar', 'AdminEventoController@editar');
$router->add('POST', '/admin/eventos/{id}/actualizar', 'AdminEventoController@actualizar');
$router->add('POST', '/admin/eventos/{id}/eliminar', 'AdminEventoController@eliminar');

// ── Admin: Noticias ───────────────────────────────────
$router->add('GET', '/admin/noticias', 'AdminNoticiaController@index');
$router->add('GET', '/admin/noticias/crear', 'AdminNoticiaController@crear');
$router->add('POST', '/admin/noticias/guardar', 'AdminNoticiaController@guardar');
$router->add('GET', '/admin/noticias/{id}/editar', 'AdminNoticiaController@editar');
$router->add('POST', '/admin/noticias/{id}/actualizar', 'AdminNoticiaController@actualizar');
$router->add('POST', '/admin/noticias/{id}/eliminar', 'AdminNoticiaController@eliminar');
$router->add('POST', '/admin/noticias/{id}/estado', 'AdminNoticiaController@estado');
$router->add('POST', '/admin/noticias/{id}/destacar', 'AdminNoticiaController@destacar');

// ── Admin: Reseñas ────────────────────────────────────
$router->add('GET', '/admin/resenas', 'AdminResenaController@index');
$router->add('POST', '/admin/resenas/{id}/aprobar', 'AdminResenaController@aprobar');
$router->add('POST', '/admin/resenas/{id}/rechazar', 'AdminResenaController@rechazar');
$router->add('POST', '/admin/resenas/{id}/eliminar', 'AdminResenaController@eliminar');

// ── Admin: Mensajes ───────────────────────────────────
$router->add('GET', '/admin/mensajes', 'AdminMensajeController@index');
$router->add('GET', '/admin/mensajes/{id}', 'AdminMensajeController@leer');
$router->add('POST', '/admin/mensajes/{id}/eliminar', 'AdminMensajeController@eliminar');

// ── Admin: Estadísticas ───────────────────────────────
$router->add('GET', '/admin/estadisticas', 'AdminEstadisticasController@index');

// ── Admin: Planes ─────────────────────────────────────
$router->add('GET', '/admin/planes', 'AdminPlanController@index');
$router->add('GET', '/admin/planes/crear', 'AdminPlanController@crear');
$router->add('POST', '/admin/planes/guardar', 'AdminPlanController@guardar');
$router->add('GET', '/admin/planes/{id}/editar', 'AdminPlanController@editar');
$router->add('POST', '/admin/planes/{id}/actualizar', 'AdminPlanController@actualizar');
$router->add('POST', '/admin/planes/{id}/eliminar', 'AdminPlanController@eliminar');
$router->add('POST', '/admin/planes/{id}/toggle', 'AdminPlanController@toggleActivo');

// ── Admin: Configuración ──────────────────────────────
$router->add('GET', '/admin/configuracion', 'AdminConfiguracionController@index');
$router->add('POST', '/admin/configuracion/guardar', 'AdminConfiguracionController@guardar');
$router->add('POST', '/admin/toggle-construccion', 'AdminConfiguracionController@toggleMantenimiento');

// ── Admin: SEO ────────────────────────────────────────
$router->add('GET', '/admin/seo', 'AdminSeoController@index');
$router->add('POST', '/admin/seo/guardar', 'AdminSeoController@guardar');
$router->add('GET', '/admin/seo/{id}/editar', 'AdminSeoController@editar');
$router->add('POST', '/admin/seo/{id}/actualizar', 'AdminSeoController@actualizar');

// ── Admin: Redes Sociales ─────────────────────────────
$router->add('GET', '/admin/redes-sociales', 'AdminRedesSocialesController@index');
$router->add('POST', '/admin/redes-sociales/guardar', 'AdminRedesSocialesController@guardar');

// ── Admin: Apariencia ─────────────────────────────────
$router->add('GET', '/admin/apariencia', 'AdminAparienciaController@index');
$router->add('POST', '/admin/apariencia/guardar', 'AdminAparienciaController@guardar');

// ── Admin: Páginas ────────────────────────────────────
$router->add('GET', '/admin/paginas', 'AdminPaginaController@index');
$router->add('GET', '/admin/paginas/crear', 'AdminPaginaController@crear');
$router->add('POST', '/admin/paginas/guardar', 'AdminPaginaController@guardar');
$router->add('GET', '/admin/paginas/{id}/editar', 'AdminPaginaController@editar');
$router->add('POST', '/admin/paginas/{id}/actualizar', 'AdminPaginaController@actualizar');
$router->add('POST', '/admin/paginas/{id}/eliminar', 'AdminPaginaController@eliminar');

// ── Admin: Textos Legales ─────────────────────────────
$router->add('GET', '/admin/textos-legales', 'AdminTextosLegalesController@index');
$router->add('GET', '/admin/textos-legales/{id}/editar', 'AdminTextosLegalesController@editar');
$router->add('POST', '/admin/textos-legales/{id}/actualizar', 'AdminTextosLegalesController@actualizar');

// ── Admin: Usuarios ───────────────────────────────────
$router->add('GET', '/admin/usuarios', 'AdminUsuarioController@index');
$router->add('GET', '/admin/usuarios/crear', 'AdminUsuarioController@crear');
$router->add('POST', '/admin/usuarios/guardar', 'AdminUsuarioController@guardar');
$router->add('GET', '/admin/usuarios/{id}/editar', 'AdminUsuarioController@editar');
$router->add('POST', '/admin/usuarios/{id}/actualizar', 'AdminUsuarioController@actualizar');
$router->add('POST', '/admin/usuarios/{id}/eliminar', 'AdminUsuarioController@eliminar');

// ── Admin: Mantenimiento ──────────────────────────────
$router->add('GET', '/admin/mantenimiento', 'AdminMantenimientoController@index');

// Aliases: singular -> plural redirects
$router->add('GET', '/noticia/{slug}', 'NoticiaController@showRedirect');
// Despachar
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
