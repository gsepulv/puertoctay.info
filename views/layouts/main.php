<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($seoTags)): ?>
        <?= $seoTags ?>
    <?php else: ?>
        <?php
        $_title = htmlspecialchars($pageTitle ?? SITE_NAME, ENT_QUOTES, 'UTF-8');
        $_desc = htmlspecialchars($pageDescription ?? SITE_TAGLINE, ENT_QUOTES, 'UTF-8');
        $_url = htmlspecialchars(SITE_URL . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ENT_QUOTES, 'UTF-8');
        $_img = isset($pageImage) ? htmlspecialchars($pageImage, ENT_QUOTES, 'UTF-8') : '';
        ?>
        <title><?= $_title ?></title>
        <meta name="description" content="<?= $_desc ?>">
        <link rel="canonical" href="<?= $_url ?>">
        <meta property="og:title" content="<?= $_title ?>">
        <meta property="og:description" content="<?= $_desc ?>">
        <meta property="og:url" content="<?= $_url ?>">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="<?= htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8') ?>">
        <?php if ($_img): ?><meta property="og:image" content="<?= $_img ?>"><?php endif; ?>
    <?php endif; ?>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <?php if (!empty($usarLeaflet)): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <?php endif; ?>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, sans-serif; color: #333; line-height: 1.6; background: #f8f9fa; }
        h1, h2, h3, h4 { font-family: 'DM Serif Display', serif; color: <?= COLOR_PRIMARY ?>; }
        a { color: <?= COLOR_PRIMARY ?>; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
        .btn { display: inline-block; padding: 0.6rem 1.2rem; border-radius: 6px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 0.95rem; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; text-decoration: none; }
        .btn-primary { background: <?= COLOR_PRIMARY ?>; color: #fff; }
        .btn-secondary { background: <?= COLOR_SECONDARY ?>; color: #fff; }
        .btn-accent { background: <?= COLOR_ACCENT ?>; color: #fff; }
        .btn-sm { padding: 0.35rem 0.8rem; font-size: 0.85rem; }
        .badge { display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .badge-gold { background: <?= COLOR_ACCENT ?>; color: #fff; }
        .badge-green { background: <?= COLOR_SECONDARY ?>; color: #fff; }

        /* Header */
        .site-header { background: <?= COLOR_PRIMARY ?>; color: #fff; padding: 0; }
        .site-header .header-inner { display: flex; align-items: center; justify-content: space-between; padding: 0.8rem 0; }
        .site-header .brand { display: flex; align-items: center; gap: 0.5rem; }
        .site-header .brand h1 { font-size: 1.3rem; margin: 0; color: #fff; }
        .site-header .brand a { color: #fff; }
        .site-header .brand a:hover { text-decoration: none; }
        .site-header .tagline { font-size: 0.8rem; opacity: 0.8; }
        .site-nav { display: flex; gap: 1.2rem; align-items: center; flex-wrap: wrap; }
        .site-nav a { color: rgba(255,255,255,0.9); font-size: 0.9rem; font-weight: 600; }
        .site-nav a:hover { color: #fff; text-decoration: none; }

        /* Cards */
        .card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
        .card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.12); }
        .card-img { width: 100%; height: 200px; object-fit: cover; background: #e9ecef; }
        .card-body { padding: 1rem; }
        .card-body h3 { font-size: 1.1rem; margin-bottom: 0.3rem; }
        .card-body p { font-size: 0.9rem; color: #666; }
        .card-meta { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #888; margin-top: 0.5rem; }

        /* Categorías grid */
        .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        .cat-card { background: #fff; border-radius: 10px; padding: 1.5rem 1rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.06); transition: transform 0.2s; }
        .cat-card:hover { transform: translateY(-3px); text-decoration: none; }
        .cat-card .emoji { font-size: 2.5rem; display: block; margin-bottom: 0.5rem; }
        .cat-card .name { font-weight: 600; color: <?= COLOR_PRIMARY ?>; font-size: 0.95rem; }
        .cat-card .count { font-size: 0.8rem; color: #888; }

        /* Sección */
        .section { margin-bottom: 2.5rem; }
        .section-title { font-size: 1.5rem; margin-bottom: 1rem; }

        /* Búsqueda */
        .search-bar { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; }
        .search-bar input[type="text"] { flex: 1; padding: 0.6rem 1rem; border: 2px solid #dee2e6; border-radius: 6px; font-size: 1rem; }
        .search-bar input[type="text"]:focus { border-color: <?= COLOR_PRIMARY ?>; outline: none; }
        .search-bar select { padding: 0.6rem; border: 2px solid #dee2e6; border-radius: 6px; font-size: 0.9rem; }

        /* Footer */
        .site-footer { background: #1a2530; color: #aaa; padding: 2rem 0; margin-top: 3rem; text-align: center; font-size: 0.85rem; }
        .site-footer a { color: #ddd; }

        /* Utilidades */
        .text-center { text-align: center; }
        .mt-1 { margin-top: 0.5rem; }
        .mt-2 { margin-top: 1rem; }
        .mb-2 { margin-bottom: 1rem; }
        .empty-state { text-align: center; padding: 3rem 1rem; color: #888; }

        /* Responsive */
        @media (max-width: 768px) {
            .site-header .header-inner { flex-direction: column; text-align: center; gap: 0.5rem; }
            .site-nav { justify-content: center; }
            .cat-grid { grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); }
            .card-grid { grid-template-columns: 1fr; }
        }
    </style>
    <?php if (isset($extraHead)) echo $extraHead; ?>
</head>
<body>

<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <div class="brand">
                <div>
                    <h1><a href="<?= SITE_URL ?>">⛵ <?= htmlspecialchars(SITE_NAME) ?></a></h1>
                    <p class="tagline"><?= htmlspecialchars(SITE_TAGLINE) ?></p>
                </div>
            </div>
            <nav class="site-nav">
                <a href="<?= SITE_URL ?>/directorio">Directorio</a>
                <a href="<?= SITE_URL ?>/categorias">Categorías</a>
                <a href="<?= SITE_URL ?>/turismo">Turismo</a>
                <a href="<?= SITE_URL ?>/patrimonio">Patrimonio</a>
                <a href="<?= SITE_URL ?>/noticias">Noticias</a>
                <a href="<?= SITE_URL ?>/mapa">Mapa</a>
                <a href="<?= SITE_URL ?>/buscar">Buscar</a>
            </nav>
        </div>
    </div>
</header>

<main class="container" style="padding: 2rem 1rem;">
    <?php
    $viewFile = ROOT_PATH . '/views/public/home.php';
    if (isset($viewName)) {
        $viewFile = ROOT_PATH . '/views/' . $viewName . '.php';
    }
    if (file_exists($viewFile)) {
        require $viewFile;
    }
    ?>
</main>

<footer class="site-footer">
    <div class="container">
        <?php
        // Load social links
        $__redes = [];
        try {
            $__cfgModel = new Configuracion(getDB());
            $__social = $__cfgModel->findByGrupo('social');
            foreach ($__social as $__s) {
                if (!empty($__s['valor'])) $__redes[$__s['clave']] = $__s['valor'];
            }
        } catch (Exception $e) {}
        ?>
        <?php if (!empty($__redes)): ?>
        <div style="display:flex; gap:1rem; justify-content:center; margin-bottom:1rem;">
            <?php if (!empty($__redes['facebook'])): ?>
                <a href="<?= htmlspecialchars($__redes['facebook']) ?>" target="_blank" rel="noopener" title="Facebook" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
            <?php endif; ?>
            <?php if (!empty($__redes['instagram'])): ?>
                <a href="<?= htmlspecialchars($__redes['instagram']) ?>" target="_blank" rel="noopener" title="Instagram" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
            <?php endif; ?>
            <?php if (!empty($__redes['youtube'])): ?>
                <a href="<?= htmlspecialchars($__redes['youtube']) ?>" target="_blank" rel="noopener" title="YouTube" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
            <?php endif; ?>
            <?php if (!empty($__redes['tiktok'])): ?>
                <a href="<?= htmlspecialchars($__redes['tiktok']) ?>" target="_blank" rel="noopener" title="TikTok" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
            <?php endif; ?>
            <?php if (!empty($__redes['whatsapp'])): ?>
                <a href="<?= htmlspecialchars($__redes['whatsapp']) ?>" target="_blank" rel="noopener" title="WhatsApp" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
            <?php endif; ?>
            <?php if (!empty($__redes['twitter'])): ?>
                <a href="<?= htmlspecialchars($__redes['twitter']) ?>" target="_blank" rel="noopener" title="Twitter / X" style="color:#ddd; font-size:1.2rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <p style="margin-bottom:0.5rem;">
            <a href="<?= SITE_URL ?>/politica-de-privacidad" style="color:#999; margin:0 0.5rem;">Privacidad</a> |
            <a href="<?= SITE_URL ?>/terminos-y-condiciones" style="color:#999; margin:0 0.5rem;">Términos</a> |
            <a href="<?= SITE_URL ?>/politica-de-cookies" style="color:#999; margin:0 0.5rem;">Cookies</a> |
            <a href="<?= SITE_URL ?>/contacto" style="color:#999; margin:0 0.5rem;">Contacto</a>
        </p>
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?>. Todos los derechos reservados.</p>
        <p style="text-align:center;font-size:0.8rem;color:#999;margin-top:0.5rem">visitapuertoctay.cl — Otro servicio de <a href="https://purranque.info" target="_blank" style="color:#999">PurranQUE.INFO</a></p>
    </div>
</footer>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
