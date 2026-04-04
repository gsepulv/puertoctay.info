# Auditoría Completa — visitapuertoctay.cl

**Fecha:** 2026-04-04
**Auditor:** Claude (asistido)
**Versión:** Commit `62e6eae` + correcciones aplicadas en esta sesión

---

## Resumen Ejecutivo

El sitio presenta una arquitectura PHP vanilla MVC sólida con buenas prácticas de seguridad ya implementadas. Se encontraron **5 hallazgos de seguridad** (3 corregidos, 2 informativos), **4 hallazgos de SEO**, **3 de rendimiento**, **2 de accesibilidad** y **6 de calidad de código**. No se encontraron vulnerabilidades críticas.

---

## 1. SEGURIDAD

### 1.1 Hallazgos Corregidos (esta sesión)

| # | Severidad | Hallazgo | Corrección |
|---|-----------|----------|------------|
| S1 | MEDIA | Flash messages sin escapar (XSS) en mensajes/index, apariencia/index, comerciante layout | Agregado `htmlspecialchars()` en todas las salidas de flash |
| S2 | MEDIA | Sin Content-Security-Policy header | CSP agregado en .htaccess con whitelist para CDNs necesarios |
| S3 | MEDIA | Directorio uploads/ sin protección contra ejecución PHP | Agregado .htaccess bloqueando ejecución PHP + index.html sentinels |

### 1.2 Estado Positivo (ya implementado)

| Aspecto | Estado | Detalle |
|---------|--------|---------|
| SQL Injection | OK | Prepared statements en todos los queries (Model base + queries específicos) |
| CSRF | OK | 51 validaciones para 53 rutas POST (las 2 sin CSRF son logout, que es correcto) |
| Autenticación | OK | `AuthMiddleware::check()` en los 19 controllers admin (excepto AdminAuth, correcto) |
| Passwords | OK | `password_hash(PASSWORD_DEFAULT)` + `password_verify()` |
| Session | OK | HttpOnly, Secure, SameSite=Lax, session_regenerate_id en login |
| Rate Limiting | OK | Login (5/5min), registro (3/h), recuperar (3/h) |
| Honeypot | OK | Campo `website_url` en formularios de contacto, registro, reseñas |
| Uploads | OK | Validación MIME, max 2MB, redimensionado, conversión a WebP |
| Headers | OK | X-Frame-Options, X-Content-Type-Options, HSTS, Referrer-Policy |
| HTTPS | OK | Redirect 301 HTTP → HTTPS |
| Sensitive files | OK | config.php, bootstrap.php, .env, .git → 404 (bloqueados por .htaccess) |
| Error display | OK | Sin X-Powered-By, sin stack traces en producción |
| Input validation | OK | FILTER_VALIDATE_EMAIL en todos los formularios con email |
| Sanitización | OK | Sanitizer::cleanArray para inputs de texto, raw para WYSIWYG (controlado) |

### 1.3 Riesgos Aceptados / Informativos

| # | Severidad | Hallazgo | Nota |
|---|-----------|----------|------|
| S4 | BAJA | DB credentials en config.php (no en .env) | Aceptable: archivo bloqueado por .htaccess, no en web root |
| S5 | INFO | `unsafe-inline` y `unsafe-eval` en CSP para scripts | Necesario para TinyMCE y scripts inline. Mitigable a futuro con nonces |

---

## 2. SEO

### 2.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| Title tags | OK — únicos por página |
| Meta description | OK — descriptivos en cada página |
| Canonical URLs | OK |
| Open Graph tags | OK — og:title, og:description, og:url, og:type, og:site_name |
| Schema.org | OK — LocalBusiness en negocios, NewsArticle en noticias |
| robots.txt | OK — Allow /, Disallow /admin/ y /api/, Sitemap URL |
| sitemap.xml | OK — dinámico con categorías, negocios, noticias, páginas |
| HTML lang="es" | OK |
| H1 por página | OK — uno por página, descriptivos |
| URLs amigables | OK — /negocio/{slug}, /noticias/{slug}, /categoria/{slug} |

### 2.2 Mejoras Recomendadas

| # | Prioridad | Recomendación |
|---|-----------|---------------|
| SEO1 | MEDIA | Agregar og:image con imagen por defecto del sitio en home y páginas sin imagen |
| SEO2 | BAJA | Agregar Twitter Card meta tags (twitter:card, twitter:title, twitter:description) |
| SEO3 | BAJA | Agregar hreflang si se planea contenido en otros idiomas |
| SEO4 | BAJA | Agregar breadcrumb Schema.org (BreadcrumbList) — actualmente solo HTML |

---

## 3. RENDIMIENTO

### 3.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| Imágenes WebP | OK — ImageHelper convierte todo a WebP |
| Lazy loading | OK — `loading="lazy"` en imágenes de cards |
| CSS inline | OK — sin archivos CSS externos (todo inline en layout, 0 requests extra) |
| JS mínimo | OK — solo vanilla JS, sin jQuery ni frameworks pesados |
| Fonts | OK — solo Google Fonts (Plus Jakarta Sans) con preconnect |
| Redimensionado | OK — imágenes redimensionadas server-side según contexto |

### 3.2 Mejoras Recomendadas

| # | Prioridad | Recomendación |
|---|-----------|---------------|
| P1 | MEDIA | Extraer CSS a archivo externo para cacheo del navegador (actualmente ~15KB inline en cada página) |
| P2 | BAJA | Agregar cache headers para imágenes estáticas (.htaccess: ExpiresByType image/ "access plus 1 year") |
| P3 | BAJA | Considerar preload de la font principal para evitar FOUT |

---

## 4. ACCESIBILIDAD (A11Y)

### 4.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| lang="es" | OK |
| viewport meta | OK |
| Alt en imágenes | OK — todas las imágenes tienen alt descriptivo |
| Contraste | OK — colores primarios cumplen WCAG AA |
| Formularios | OK — labels asociados a inputs |
| 404 page | OK — página personalizada con navegación |

### 4.2 Mejoras Recomendadas

| # | Prioridad | Recomendación |
|---|-----------|---------------|
| A1 | MEDIA | Solo 2 atributos aria-* en todo el sitio. Agregar aria-label en botones de icono, nav landmarks (aria-label="Menú principal"), aria-current="page" en nav activo |
| A2 | BAJA | Skip-to-content link para navegación por teclado |

---

## 5. HTML / CSS / JS

### 5.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| DOCTYPE HTML5 | OK |
| charset UTF-8 | OK |
| Semántica HTML | OK — header, nav, main, footer, section, article |
| Responsive | OK — 2 breakpoints (@media), grid layouts, flexbox |
| Favicon | OK — emoji SVG inline (⛵) |
| Sin errores JS | OK — sin console.log, sin alert() |

### 5.2 Hallazgos

| # | Prioridad | Hallazgo |
|---|-----------|----------|
| H1 | BAJA | Algunos estilos inline repetidos en vistas (style="..."). Considerar clases CSS reutilizables |
| H2 | INFO | Responsive tiene solo 2 breakpoints. Funcional pero podría beneficiarse de un tercero para tablets |

---

## 6. PHP / ARQUITECTURA

### 6.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| PHP 8.3 | OK — aprovecha match, named args, typed properties |
| MVC limpio | OK — controllers, models, views separados |
| Autoloader | OK — spl_autoload_register con dirs |
| PDO | OK — ERRMODE_EXCEPTION, FETCH_ASSOC, no emulate_prepares |
| Model base | OK — CRUD genérico con prepared statements |
| Error handling | OK — try/catch en bootstrap, errores no expuestos |
| Audit log | OK — AuditLog::log() en operaciones CRUD admin |

### 6.2 Hallazgos

| # | Prioridad | Hallazgo |
|---|-----------|----------|
| C1 | MEDIA | 130 archivos PHP sin Composer autoload — funcional pero el spl_autoload custom podría fallar con clases en subdirectorios no listados |
| C2 | BAJA | Algunos controladores admin tienen lógica duplicada (guardar/actualizar casi idénticos). Refactorizable pero no urgente |
| C3 | INFO | Sin tests automatizados. Aceptable para fase Beta pero recomendable a futuro |
| C4 | INFO | Sin .env — credentials en config.php. Funcional con .htaccess protection |

---

## 7. UX / CONTENIDO

### 7.1 Estado Positivo

| Aspecto | Estado |
|---------|--------|
| Navegación clara | OK — navbar con categorías principales |
| Búsqueda | OK — /buscar con filtros por tipo y categoría |
| Mapa interactivo | OK — Leaflet con todos los negocios geolocalizados |
| Mobile-first | OK — menú hamburguesa, cards adaptables |
| CTA claros | OK — registro de comercio, contacto, WhatsApp |
| Badge Beta | OK — badge fijo informando estado del sitio |
| Modo construcción | OK — toggle en admin para activar/desactivar |
| Panel comerciante | OK — autogestión de datos del negocio |

### 7.2 Mejoras Recomendadas

| # | Prioridad | Recomendación |
|---|-----------|---------------|
| U1 | MEDIA | Agregar paginación en directorio y noticias (actualmente carga todos) |
| U2 | BAJA | Agregar breadcrumbs visibles en todas las páginas internas |
| U3 | BAJA | Agregar compartir en WhatsApp/Facebook desde cards de negocios (ya existe en detalle) |

---

## 8. BASE DE DATOS

### 8.1 Estado

- Motor: MySQL 8.4
- Tablas: negocios, categorias, noticias, eventos, paginas, resenas, usuarios, configuracion, favoritos, temporadas, negocio_temporada, planes, mensajes, audit_log, paginas_versiones, textos_editables, suscriptores
- Índices: PKs, UNIQUEs en slugs, FKs en relaciones
- Charset: utf8mb4

### 8.2 Hallazgos

| # | Prioridad | Hallazgo |
|---|-----------|----------|
| D1 | BAJA | Sin backups automáticos configurados (depende de HostGator) |
| D2 | INFO | Audit log crece sin purga — considerar rotación |

---

## Correcciones Aplicadas en Esta Sesión

1. **XSS en flash messages** — 3 archivos escapados con htmlspecialchars()
2. **Content-Security-Policy** — Header CSP agregado en .htaccess
3. **Upload directory hardening** — .htaccess anti-PHP + index.html sentinels

---

## Prioridades Recomendadas para Próximas Sesiones

1. Paginación en directorio y noticias (UX + rendimiento)
2. og:image por defecto (SEO)
3. Cache headers para assets estáticos (rendimiento)
4. Mejorar atributos aria-* (accesibilidad)
5. Extraer CSS a archivo externo cacheable (rendimiento)
