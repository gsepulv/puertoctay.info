<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada — <?= htmlspecialchars(SITE_NAME) ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; background: #f5f5f5; color: #333; }
        .error-box { text-align: center; }
        .error-box h1 { font-size: 4rem; color: <?= COLOR_PRIMARY ?>; }
        .error-box p { margin: 1rem 0; }
        .error-box a { color: <?= COLOR_PRIMARY ?>; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>404</h1>
        <p>La página que buscas no existe.</p>
        <p><a href="<?= SITE_URL ?>">Volver al inicio</a></p>
    </div>
</body>
</html>
