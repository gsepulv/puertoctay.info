<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin — ' . SITE_NAME) ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <?php if (!empty($usarLeaflet)): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <?php endif; ?>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Source Sans 3', sans-serif; color: #333; line-height: 1.6; background: #f0f2f5; }
        a { color: <?= COLOR_PRIMARY ?>; text-decoration: none; }

        /* Layout */
        .admin-layout { display: flex; min-height: 100vh; }
        .admin-sidebar { width: 240px; background: <?= COLOR_PRIMARY ?>; color: #fff; padding: 1.5rem 0; flex-shrink: 0; }
        .admin-sidebar .brand { padding: 0 1.2rem 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 1rem; }
        .admin-sidebar .brand h2 { font-size: 1.1rem; }
        .admin-sidebar .brand small { opacity: 0.7; font-size: 0.8rem; }
        .admin-sidebar nav a { display: block; padding: 0.6rem 1.2rem; color: rgba(255,255,255,0.8); font-size: 0.9rem; transition: background 0.2s; }
        .admin-sidebar nav a:hover, .admin-sidebar nav a.active { background: rgba(255,255,255,0.1); color: #fff; text-decoration: none; }
        .admin-main { flex: 1; padding: 1.5rem 2rem; overflow-x: auto; }

        /* Top bar */
        .admin-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .admin-topbar h1 { font-size: 1.5rem; font-weight: 700; color: <?= COLOR_PRIMARY ?>; }

        /* Tabla */
        .admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .admin-table th { background: #f8f9fa; text-align: left; padding: 0.8rem 1rem; font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.03em; }
        .admin-table td { padding: 0.8rem 1rem; border-top: 1px solid #eee; font-size: 0.9rem; }
        .admin-table tr:hover td { background: #fafbfc; }

        /* Botones */
        .btn { display: inline-block; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 0.9rem; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; text-decoration: none; }
        .btn-primary { background: <?= COLOR_PRIMARY ?>; color: #fff; }
        .btn-secondary { background: <?= COLOR_SECONDARY ?>; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.8rem; }
        .badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600; }
        .badge-green { background: #d4edda; color: #155724; }
        .badge-red { background: #f8d7da; color: #721c24; }
        .badge-gold { background: <?= COLOR_ACCENT ?>; color: #fff; }

        /* Formulario */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-weight: 600; font-size: 0.9rem; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.55rem 0.8rem; border: 2px solid #dee2e6; border-radius: 6px; font-size: 0.95rem; font-family: inherit; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: <?= COLOR_PRIMARY ?>; outline: none; }
        .form-group textarea { min-height: 100px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-card { background: #fff; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }

        /* Alertas */
        .alert { padding: 0.8rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        @media (max-width: 768px) {
            .admin-layout { flex-direction: column; }
            .admin-sidebar { width: 100%; }
            .admin-main { padding: 1rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="brand">
            <h2>⛵ <?= htmlspecialchars(SITE_NAME) ?></h2>
            <small>Panel de Administración</small>
        </div>
        <nav>
            <a href="<?= SITE_URL ?>/admin/negocios">📋 Negocios</a>
            <a href="<?= SITE_URL ?>/admin/noticias">📰 Noticias</a>
            <a href="<?= SITE_URL ?>/" target="_blank">🌐 Ver sitio</a>
            <a href="<?= SITE_URL ?>/admin/logout">🚪 Cerrar sesión</a>
        </nav>
    </aside>

    <div class="admin-main">
        <div class="admin-topbar">
            <h1><?= htmlspecialchars($pageTitle ?? 'Admin') ?></h1>
            <span style="font-size:0.85rem; color:#888;">
                <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin') ?>
            </span>
        </div>

        <?php
        $viewFile = ROOT_PATH . '/views/' . ($viewName ?? '') . '.php';
        if (isset($viewName) && file_exists($viewFile)) {
            require $viewFile;
        }
        ?>
    </div>
</div>

<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
