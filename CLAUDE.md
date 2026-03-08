# puertoctay.info

Sitio independiente de turismo y comercio para Puerto Octay, Lago Llanquihue.

## Stack
- PHP 8.3 vanilla MVC, sin framework ni Composer
- MySQL 8.0
- Hosting: por definir (compartido cPanel probable)

## Entorno local
- PHP: `C:/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe`
- MySQL: `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root db_puertoctay`
- BD local: `db_puertoctay` (root, sin pass)
- URL local: `http://localhost/puertoctay/`

## Producción
- URL: `https://puertoctay.info`
- BD: `puertoctay_puertoctay` (user: `puertoctay_gticore`)
- Deploy: cPanel Git Version Control (`.cpanel.yml`)

## Estructura
```
config.php          # Credenciales (en .gitignore, NUNCA al repo)
bootstrap.php       # Sesión, autoload, getDB()
router.php          # Clase Router con dispatch y {slug}
schema.sql          # DDL completo + datos semilla
public/             # DocumentRoot
  index.php         # Punto de entrada
  .htaccess         # Rewrite + seguridad
models/             # Model.php base + modelos específicos
controllers/        # HomeController.php + futuros
middleware/         # CSRF, Sanitizer, RateLimiter, Auth, AuditLog
helpers/            # Funciones auxiliares
views/
  layouts/main.php  # Layout base HTML
  public/           # Vistas públicas
  errors/           # 404, 500
storage/            # cache/, logs/, sessions/ (gitignored)
```

## BD: 11 tablas
categorias(20), planes(4), negocios, noticias, eventos, resenas, usuarios(1 admin), propietarios, rate_limits, audit_log, page_cache

## Reglas de desarrollo
- CSRF en todo POST (csrf_field() helper)
- htmlspecialchars() en todo output
- PDO prepared statements en todo query
- config.php NUNCA se sube a Git
- NUNCA crear archivos de diagnóstico
- NUNCA hardcodear credenciales
- Idioma: español chileno formal

## Patrones clave
- Router: `$router->add('GET', '/ruta/{slug}', 'Controller@action')`
- Modelo: extiende Model.php, define `$table`, usa `$this->db`
- Controller: recibe PDO en constructor, carga vistas con require
- Middleware estáticos: `CsrfMiddleware::validate()`, `AuthMiddleware::check()`, etc.
- Auditoría: `AuditLog::log('accion', 'entidad', $id, 'detalle')`
