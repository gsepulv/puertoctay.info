<?php
$badges = AdminHelper::sidebarBadges(getDB());
$__modoConstruccion = false;
try {
    $__stmtModo = getDB()->prepare("SELECT valor FROM configuracion WHERE grupo = 'mantenimiento' AND clave = 'modo_construccion' LIMIT 1");
    $__stmtModo->execute();
    $__modoConstruccion = ($__stmtModo->fetchColumn() === '1');
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin — ' . SITE_NAME) ?></title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php if (!empty($usarLeaflet)): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <?php endif; ?>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; color: #333; line-height: 1.6; background: #f0f2f5; }
        a { color: <?= COLOR_PRIMARY ?>; text-decoration: none; }

        /* Layout */
        .admin-layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .admin-sidebar {
            width: 250px; background: <?= COLOR_PRIMARY ?>; color: #fff;
            flex-shrink: 0; display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto;
        }
        .admin-sidebar .brand {
            padding: 1rem 1rem 0.8rem; border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .admin-sidebar .brand h2 { font-size: 1.1rem; margin: 0; }
        .admin-sidebar .brand small { opacity: 0.7; font-size: 0.75rem; }

        .admin-sidebar .nav-section {
            padding: 0.6rem 0 0.3rem;
        }
        .admin-sidebar .nav-label {
            padding: 0.4rem 1rem 0.2rem; font-size: 0.65rem; text-transform: uppercase;
            letter-spacing: 0.08em; color: rgba(255,255,255,0.4); font-weight: 700;
        }
        .admin-sidebar nav a {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.45rem 1rem; color: rgba(255,255,255,0.8);
            font-size: 0.85rem; transition: background 0.15s;
        }
        .admin-sidebar nav a:hover,
        .admin-sidebar nav a.active {
            background: rgba(255,255,255,0.12); color: #fff; text-decoration: none;
        }
        .admin-sidebar .nav-badge {
            background: rgba(255,255,255,0.2); color: #fff; padding: 0.1rem 0.45rem;
            border-radius: 10px; font-size: 0.7rem; font-weight: 700; min-width: 20px; text-align: center;
        }
        .admin-sidebar .nav-badge.warn { background: #e74c3c; }

        .admin-sidebar .sidebar-footer {
            margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);
            padding: 0.8rem 1rem; font-size: 0.8rem;
        }
        .admin-sidebar .sidebar-footer .user-info {
            margin-bottom: 0.5rem; line-height: 1.3;
        }
        .admin-sidebar .sidebar-footer .user-name { font-weight: 700; }
        .admin-sidebar .sidebar-footer .user-role { opacity: 0.6; font-size: 0.75rem; }
        .admin-sidebar .sidebar-footer a {
            display: block; color: rgba(255,255,255,0.7); font-size: 0.8rem;
            padding: 0.2rem 0;
        }
        .admin-sidebar .sidebar-footer a:hover { color: #fff; text-decoration: none; }

        /* Main */
        .admin-main { flex: 1; margin-left: 250px; padding: 1.5rem 2rem; overflow-x: auto; min-height: 100vh; }

        /* Top bar */
        .admin-topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .admin-topbar h1 { font-size: 1.4rem; font-weight: 700; color: <?= COLOR_PRIMARY ?>; }

        /* Stat cards */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card {
            background: #fff; border-radius: 10px; padding: 1.2rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .stat-card .stat-icon { font-size: 1.8rem; margin-bottom: 0.3rem; }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 700; color: <?= COLOR_PRIMARY ?>; }
        .stat-card .stat-label { font-size: 0.8rem; color: #888; }

        /* Tabla */
        .admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .admin-table th { background: #f8f9fa; text-align: left; padding: 0.7rem 0.8rem; font-size: 0.8rem; color: #666; text-transform: uppercase; letter-spacing: 0.03em; }
        .admin-table td { padding: 0.7rem 0.8rem; border-top: 1px solid #eee; font-size: 0.88rem; }
        .admin-table tr:hover td { background: #fafbfc; }

        /* Dashboard sections */
        .dash-section { background: #fff; border-radius: 10px; padding: 1.2rem; box-shadow: 0 1px 4px rgba(0,0,0,0.06); margin-bottom: 1.5rem; }
        .dash-section h3 { font-size: 1rem; font-weight: 700; color: <?= COLOR_PRIMARY ?>; margin-bottom: 0.8rem; }

        /* Quick actions */
        .quick-actions { display: flex; gap: 0.8rem; flex-wrap: wrap; }

        /* Botones */
        .btn { display: inline-block; padding: 0.5rem 1rem; border-radius: 6px; font-weight: 600; text-decoration: none; cursor: pointer; border: none; font-size: 0.88rem; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; text-decoration: none; }
        .btn-primary { background: <?= COLOR_PRIMARY ?>; color: #fff; }
        .btn-secondary { background: <?= COLOR_SECONDARY ?>; color: #fff; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-warning { background: #f39c12; color: #fff; }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.78rem; }
        .badge { display: inline-block; padding: 0.15rem 0.45rem; border-radius: 4px; font-size: 0.73rem; font-weight: 600; }
        .badge-green { background: #d4edda; color: #155724; }
        .badge-red { background: #f8d7da; color: #721c24; }
        .badge-yellow { background: #fff3cd; color: #856404; }
        .badge-blue { background: #d1ecf1; color: #0c5460; }
        .badge-gold { background: <?= COLOR_ACCENT ?>; color: #fff; }

        /* Formulario */
        .form-group { margin-bottom: 1.2rem; }
        .form-group label { display: block; margin-bottom: 0.3rem; font-weight: 600; font-size: 0.88rem; }
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
            .admin-sidebar { position: static; width: 100%; }
            .admin-main { margin-left: 0; padding: 1rem; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
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

        <!-- Site Status Toggle -->
        <div style="padding: 0.6rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <form method="POST" action="<?= SITE_URL ?>/admin/toggle-construccion" style="margin: 0;">
                <?= csrf_field() ?>
                <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: space-between; background: <?= $__modoConstruccion ? 'rgba(245,158,11,0.15)' : 'rgba(34,197,94,0.15)' ?>; border: none; border-radius: 6px; padding: 0.5rem 0.75rem; cursor: pointer; font-family: inherit; font-size: 0.78rem; color: #fff;">
                    <span style="display: flex; align-items: center; gap: 0.4rem;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: <?= $__modoConstruccion ? '#f59e0b' : '#22c55e' ?>; display: inline-block;"></span>
                        <?= $__modoConstruccion ? 'En construcción' : 'Sitio en línea' ?>
                    </span>
                    <span style="font-size: 0.7rem; opacity: 0.6;"><?= $__modoConstruccion ? 'Poner en línea' : 'Modo construcción' ?></span>
                </button>
            </form>
        </div>

        <nav>
            <!-- CONTENIDO -->
            <div class="nav-section">
                <div class="nav-label">Contenido</div>
                <a href="<?= SITE_URL ?>/admin">📊 Dashboard</a>
                <a href="<?= SITE_URL ?>/admin/hero">🖼 Hero Home</a>
                <a href="<?= SITE_URL ?>/admin/negocios">
                    🏪 Negocios
                    <?php if ($badges['pendientes_registro']): ?><span class="nav-badge warn"><?= $badges['pendientes_registro'] ?></span><?php elseif ($badges['negocios']): ?><span class="nav-badge"><?= $badges['negocios'] ?></span><?php endif; ?>
                </a>
                <a href="<?= SITE_URL ?>/admin/categorias">
                    📂 Categorías
                    <?php if ($badges['categorias']): ?><span class="nav-badge"><?= $badges['categorias'] ?></span><?php endif; ?>
                </a>
                <a href="<?= SITE_URL ?>/admin/temporadas" class="<?= str_contains($_SERVER['REQUEST_URI'], '/admin/temporadas') ? 'active' : '' ?>">
                    🌤️ Temporadas
                </a>
                <a href="<?= SITE_URL ?>/admin/eventos">📅 Eventos</a>
                <a href="<?= SITE_URL ?>/admin/noticias">📰 Noticias</a>
                <a href="<?= SITE_URL ?>/admin/resenas">
                    ⭐ Reseñas
                    <?php if ($badges['resenas']): ?><span class="nav-badge warn"><?= $badges['resenas'] ?></span><?php endif; ?>
                </a>
            </div>

            <!-- GESTIÓN -->
            <div class="nav-section">
                <div class="nav-label">Gestión</div>
                <a href="<?= SITE_URL ?>/admin/mensajes">
                    📧 Mensajes
                    <?php if ($badges['mensajes']): ?><span class="nav-badge warn"><?= $badges['mensajes'] ?></span><?php endif; ?>
                </a>
                <a href="<?= SITE_URL ?>/admin/estadisticas">📈 Estadísticas</a>
                <a href="<?= SITE_URL ?>/admin/planes">💰 Planes</a>
            </div>

            <!-- CONFIGURACIÓN -->
            <div class="nav-section">
                <div class="nav-label">Configuración</div>
                <a href="<?= SITE_URL ?>/admin/configuracion">⚙ General</a>
                <a href="<?= SITE_URL ?>/admin/seo">🔍 SEO</a>
                <a href="<?= SITE_URL ?>/admin/redes-sociales">📱 Redes Sociales</a>
                <a href="<?= SITE_URL ?>/admin/apariencia">🎨 Apariencia</a>
                <a href="<?= SITE_URL ?>/admin/paginas">📃 Páginas</a>
                <a href="<?= SITE_URL ?>/admin/usuarios">👥 Usuarios</a>
                <a href="<?= SITE_URL ?>/admin/mantenimiento">🔧 Mantenimiento</a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-name">👤 <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Admin') ?></div>
                <div class="user-role"><?= ucfirst(htmlspecialchars($_SESSION['usuario_rol'] ?? 'Administrador')) ?></div>
            </div>
            <a href="<?= SITE_URL ?>/" target="_blank">↗ Ver sitio</a>
            <a href="<?= SITE_URL ?>/admin/logout">➜ Cerrar sesión</a>
        </div>
    </aside>

    <div class="admin-main">
        <div class="admin-topbar">
            <h1><?= htmlspecialchars($pageTitle ?? 'Admin') ?></h1>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script><script>if (document.querySelector("textarea.editor-wysiwyg")) {    tinymce.init({        selector: "textarea.editor-wysiwyg",        language: "es",        height: 400,        menubar: false,        plugins: "lists link image table code wordcount fullscreen",        toolbar: "undo redo | blocks | bold italic underline | bullist numlist | link image table | alignleft aligncenter alignright | code fullscreen",        content_style: "body { font-family: Plus Jakarta Sans, sans-serif; font-size: 16px; line-height: 1.7; }",        branding: false,        promotion: false    });}</script>
</body>
</html>
