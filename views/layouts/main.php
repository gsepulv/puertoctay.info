<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription ?? SITE_TAGLINE) ?>">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #333; line-height: 1.6; }
        a { color: <?= COLOR_PRIMARY ?>; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }

        /* Header */
        .site-header { background: <?= COLOR_PRIMARY ?>; color: #fff; padding: 1rem 0; }
        .site-header h1 { font-size: 1.5rem; }
        .site-header a { color: #fff; }
        .site-header .tagline { font-size: 0.9rem; opacity: 0.8; }

        /* Footer */
        .site-footer { background: #2c3e50; color: #ccc; padding: 2rem 0; margin-top: 3rem; text-align: center; font-size: 0.9rem; }
        .site-footer a { color: #eee; }
    </style>
</head>
<body>

<header class="site-header">
    <div class="container">
        <h1><a href="<?= SITE_URL ?>"><?= htmlspecialchars(SITE_NAME) ?></a></h1>
        <p class="tagline"><?= htmlspecialchars(SITE_TAGLINE) ?></p>
    </div>
</header>

<main class="container" style="padding: 2rem 1rem;">
    <?php
    // Cargar contenido de la vista correspondiente
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

</body>
</html>
