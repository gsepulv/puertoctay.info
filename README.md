# Visita Puerto Octay

Guía turística y directorio comercial digital de Puerto Octay, a orillas del Lago Llanquihue, Región de Los Lagos, Chile.

**URL:** https://visitapuertoctay.cl
**Stack:** PHP 8.3 vanilla MVC, MySQL 8, sin frameworks
**Hosting:** HostGator compartido, cPanel

## Estructura del proyecto

```
puertoctay_repo/                  ← Repositorio (ROOT_PATH)
├── public/
│   └── index.php                 ← Entry point (se copia a public_html/)
├── config.php                    ← Credenciales BD, constantes (NO versionado)
├── bootstrap.php                 ← Autoload, sesión, PDO singleton
├── router.php                    ← Pattern matching con {param}
├── controllers/
│   ├── HomeController.php        ← Públicos
│   ├── NegocioController.php
│   ├── CategoriaController.php
│   ├── NoticiaController.php
│   ├── MapaController.php
│   ├── BuscarController.php
│   ├── ContactoController.php
│   ├── PlanController.php
│   ├── PaginaController.php
│   ├── SitemapController.php
│   ├── api/                      ← JSON endpoints
│   └── admin/                    ← Panel de administración
├── models/
│   ├── Model.php                 ← CRUD base con PDO
│   ├── Negocio.php
│   ├── Categoria.php
│   ├── Noticia.php
│   └── ...
├── views/
│   ├── layouts/
│   │   ├── main.php              ← Layout público (CSS inline, header, footer)
│   │   └── admin.php             ← Layout admin (sidebar, CSS)
│   ├── public/                   ← Vistas públicas
│   ├── admin/                    ← Vistas admin
│   ├── maintenance.php           ← Página de modo construcción
│   └── errors/404.php
├── middleware/
│   ├── AuthMiddleware.php
│   ├── CsrfMiddleware.php
│   ├── MaintenanceMiddleware.php
│   ├── Sanitizer.php
│   ├── RateLimiter.php
│   └── AuditLog.php
├── helpers/
│   ├── ImageHelper.php
│   ├── SeoHelper.php
│   ├── SlugHelper.php
│   └── AdminHelper.php
├── migrations/                   ← SQL de cambios al schema
├── .cpanel.yml                   ← Deploy config
└── .gitignore
```

## Arquitectura de deploy

El sitio usa una estructura donde:

1. **`~/public_html/`** es el web root (DocumentRoot de Apache)
   - Solo contiene: `index.php`, `.htaccess`, `robots.txt`, `uploads/`
2. **`~/puertoctay_repo/`** contiene todo el código fuente
   - `public/index.php` define `ROOT_PATH` apuntando al repo
   - El resto de archivos (controllers, models, views) se acceden via `ROOT_PATH`
3. **`.cpanel.yml`** copia `public/*` a `public_html/` en cada deploy

```
Request → public_html/index.php → ROOT_PATH=/puertoctay_repo → bootstrap → router → controller → view
```

## Setup inicial

```bash
# 1. Clonar repositorio
cd ~
git clone https://github.com/gsepulv/puertoctay.info.git puertoctay_repo

# 2. Crear config.php (NO versionado)
cp puertoctay_repo/config.example.php puertoctay_repo/config.php
# Editar con credenciales reales de BD

# 3. Crear directorios de uploads
mkdir -p ~/public_html/uploads/{negocios,logos,portadas,noticias}
chmod 755 ~/public_html/uploads -R

# 4. Copiar entry point
cp puertoctay_repo/public/index.php ~/public_html/index.php

# 5. Importar BD
mysql -u USER -p DB_NAME < migrations/001_initial.sql
```

## Deploy manual

```bash
cd ~/puertoctay_repo
git pull origin main
cp public/index.php ~/public_html/index.php
```

O via cPanel: **Git Version Control → Update from Remote** (ejecuta `.cpanel.yml`).

## Deploy automático

El archivo `.cpanel.yml` se ejecuta automáticamente al hacer push o update:

```yaml
deployment:
  tasks:
    - export DEPLOYPATH=/home/visitapuertoctay/public_html/
    - /bin/cp -rf /home/visitapuertoctay/puertoctay_repo/public/* $DEPLOYPATH
```

## Modo construcción

Toggle en el sidebar del admin panel. Cuando está activo:
- Las rutas públicas devuelven 503 con página de "en construcción"
- Las rutas `/admin/*` siguen funcionando normalmente
- Usuarios logueados pueden ver el sitio público

## Backups

Backup diario automático a las 03:00 via crontab:
- `~/backups/db-YYYYMMDD-HHMM.sql.gz` — Dump de la BD
- `~/backups/files-YYYYMMDD-HHMM.tar.gz` — Archivos del repo

## Contacto

- **Email:** contacto@purranque.info
- **Proyecto:** [PurranQUE.INFO](https://purranque.info)
