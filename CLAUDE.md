# CLAUDE.md - Instrucciones para Claude Code
# Proyecto: puertoctay.info - GTI Core v3
# Ecosistema: PurranQUE.INFO

## Datos del Proyecto
- **Dominio:** puertoctay.info
- **Repositorio:** github.com/gsepulv/puertoctay.info
- **Ruta local:** C:\Proyectos\puertoctay.info
- **Servidor:** Apache en cPanel/HostGator
- **Usuario cPanel:** puertoctay
- **Ruta servidor:** /home/puertoctay/puertoctay_repo
- **Document Root:** /home/puertoctay/public_html (deploy via .cpanel.yml)

## Stack Tecnológico
- PHP 8.3, MySQL 8.0, Apache, MVC propio (sin framework)
- Frontend: HTML5 + CSS3 + JS vanilla (cero dependencias JS frameworks)
- Mapas: Leaflet.js + OpenStreetMap
- Editor rich text: Editor.js (MIT)
- PWA: manifest.json + service worker

## Arquitectura Multi-Tenant (GTI Core v3)
- **gti-core/** → Núcleo compartido entre todos los tenants
- **tenants/** → Configuración específica por dominio (config.php, modules.php, routes.php)
- **public/** → DocumentRoot de Apache (index.php, assets, uploads)
- **admin/** → Panel de administración (fuera de public)
- **storage/** → Cache, logs, sesiones (no versionado)
- El router detecta el tenant por dominio en bootstrap.php
- Cada tenant tiene su propia base de datos

## Reglas de Seguridad (OBLIGATORIAS - SIN EXCEPCIÓN)
1. **CSRF token** en TODOS los formularios POST → CsrfMiddleware.php
2. **htmlspecialchars() + strip_tags()** en TODOS los inputs → Sanitizer.php
3. **PDO prepared statements con bindParam** en TODOS los queries → Model.php
4. **Rate limiting** en formularios y API → RateLimiter.php
5. **session_regenerate_id()** tras cada login exitoso
6. **NUNCA** crear archivos .php de diagnóstico (phpinfo.php, test.php, debug.php)
7. **NUNCA** hardcodear credenciales de base de datos
8. **NUNCA** subir tenants/*/config.php a Git (contiene credenciales)
9. **Validación de uploads:** MIME real con finfo, extensión, tamaño máximo

## Reglas de Código
- Sin framework. PHP puro. Consistente con regalospurranque.cl y visitapurranque.cl
- Sin \n en HTML. Usar plantillas PHP con vistas separadas
- Imágenes: upload + resize + conversión WebP automática (ImageHelper.php)
- URLs limpias vía .htaccess rewrite → todo pasa por public/index.php
- Schema.org en cada tipo de página (LocalBusiness, TouristAttraction, NewsArticle, Event)
- UTF-8 en todo: base de datos, HTML meta charset, PHP mb_string
- Colores del branding: azul lago #1B4F72, verde bosque #1E8449, dorado #B7950B
- Fonts: DM Serif Display (títulos) + Source Sans 3 (cuerpo)

## Convención de Commits
- Prefijo de sprint obligatorio: "S1: ...", "S2: ...", "S3: ..."
- Mensajes descriptivos en español
- Ejemplos:
  - "S2: implementar middleware CSRF y rate limiter"
  - "S3: CRUD negocios con panel admin"
  - "S5: módulo noticias con Editor.js"

## Deploy
- Push a GitHub (main) → cPanel Git Version Control → Update from Remote → Deploy HEAD Commit
- El .cpanel.yml copia public/* a /home/puertoctay/public_html/
- El archivo tenants/puertoctay/config.php de producción se crea MANUALMENTE en el servidor

## Base de Datos
- Nombre: db_puertoctay
- Motor: MySQL 8.0 con InnoDB
- Charset: utf8mb4_unicode_ci
- Tablas principales: categorias, negocios, noticias, eventos, resenas, usuarios, propietarios, planes, rate_limits, audit_log, page_cache

## Entidad Principal: negocios
- Fusiona comercio + atractivo + servicio + gastronomía en una sola tabla
- Campo tipo ENUM('comercio','atractivo','servicio','gastronomia')
- Campo tenant VARCHAR(50) para multi-tenant
- Campo verificado TINYINT(1) → sello "Verificado GTI"

## Principios del Ecosistema
- Cloudflare NO reemplaza la seguridad PHP
- Imágenes IA > SVG programático para portadas y thumbnails
- Ediciones quirúrgicas > scripts masivos
- Ningún archivo diagnóstico en producción. Jamás.
- Tono comunitario > tono publicitario con comerciantes
- Evidencia antes de cobrar: primero tráfico real, luego monetización
- La replicabilidad es el negocio: un núcleo para N comunas
- Verificado es el sello de confianza editorial
