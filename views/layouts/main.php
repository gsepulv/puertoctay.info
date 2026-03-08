<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (isset($seoTags)): ?>
        <?= $seoTags ?>
    <?php else: ?>
        <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
        <meta name="description" content="<?= htmlspecialchars($pageDescription ?? SITE_TAGLINE) ?>">
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
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(SITE_NAME) ?>. Todos los derechos reservados.</p>
    </div>
</footer>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
