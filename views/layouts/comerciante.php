<?php
$__currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$__flashSuccess = $_SESSION['flash_success'] ?? ''; unset($_SESSION['flash_success']);
$__flashError = $_SESSION['flash_error'] ?? ''; unset($_SESSION['flash_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Comercio — <?= SITE_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --primary: <?= COLOR_PRIMARY ?>; --secondary: <?= COLOR_SECONDARY ?>; --accent: <?= COLOR_ACCENT ?>; --dark: #0D1B2A; --text: #1E293B; --text-light: #64748B; --border: #E2E8F0; --bg: #F8FAFC; --white: #FFF; --radius-md: 10px; --radius-lg: 16px; --shadow-sm: 0 1px 3px rgba(0,0,0,0.06); }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
    .panel-sidebar { position: fixed; top: 0; left: 0; width: 260px; height: 100vh; background: var(--dark); color: #CBD5E1; display: flex; flex-direction: column; z-index: 100; transition: transform 0.3s; }
    .panel-sidebar .brand { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .panel-sidebar .brand a { color: #fff; text-decoration: none; font-size: 1.1rem; font-weight: 700; }
    .panel-sidebar .brand small { display: block; font-size: 0.75rem; color: #94A3B8; margin-top: 2px; }
    .panel-sidebar nav { flex: 1; padding: 1rem 0; }
    .panel-sidebar nav a { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.5rem; color: #CBD5E1; text-decoration: none; font-size: 0.9rem; transition: all 0.2s; }
    .panel-sidebar nav a:hover { background: rgba(255,255,255,0.05); color: #fff; }
    .panel-sidebar nav a.active { background: rgba(255,255,255,0.1); color: #fff; font-weight: 600; border-right: 3px solid var(--accent); }
    .panel-sidebar .user-info { padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.1); font-size: 0.8rem; }
    .panel-sidebar .user-info strong { color: #fff; display: block; }
    .panel-main { margin-left: 260px; padding: 2rem; min-height: 100vh; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
    .panel-toggle { display: none; background: none; border: none; cursor: pointer; padding: 0.5rem; color: var(--text); }
    .flash-success { background: #F0FDF4; border: 1px solid #22C55E; color: #166534; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; }
    .flash-error { background: #FEF2F2; border: 1px solid #EF4444; color: #991B1B; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; }
    .stat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; text-align: center; }
    .stat-card .number { font-size: 2rem; font-weight: 700; color: var(--primary); }
    .stat-card .label { font-size: 0.85rem; color: var(--text-light); margin-top: 0.25rem; }
    .card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 1.5rem; }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; padding: 0.65rem 1.4rem; border-radius: var(--radius-md); font-weight: 600; font-size: 0.9rem; text-decoration: none; border: none; cursor: pointer; transition: all 0.25s; }
    .btn-primary { background: var(--primary); color: #fff; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; margin-bottom: 0.4rem; font-weight: 600; font-size: 0.85rem; color: var(--text); }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 0.65rem 0.9rem; border: 1px solid var(--border); border-radius: var(--radius-md); font-family: inherit; font-size: 0.9rem; transition: border 0.2s; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(27,73,101,0.1); }
    .form-group small { display: block; margin-top: 0.3rem; font-size: 0.8rem; color: var(--text-light); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
    .badge-pending { background: #FEF3C7; color: #92400E; }
    .badge-active { background: #D1FAE5; color: #065F46; }
    .badge-rejected { background: #FEE2E2; color: #991B1B; }
    @media (max-width: 768px) {
        .panel-sidebar { transform: translateX(-100%); }
        .panel-sidebar.open { transform: translateX(0); }
        .panel-main { margin-left: 0; }
        .panel-toggle { display: block; }
        .form-row { grid-template-columns: 1fr; }
    }
    </style>
</head>
<body>

<aside class="panel-sidebar" id="panelSidebar">
    <div class="brand">
        <a href="<?= SITE_URL ?>/"><?= SITE_NAME ?></a>
        <small>Panel del Comerciante</small>
    </div>
    <nav>
        <a href="<?= SITE_URL ?>/mi-comercio" class="<?= $__currentUri === '/mi-comercio' ? 'active' : '' ?>">📊 Mi Comercio</a>
        <a href="<?= SITE_URL ?>/mi-comercio/editar" class="<?= str_contains($__currentUri, '/mi-comercio/editar') ? 'active' : '' ?>">✏️ Editar Negocio</a>
        <a href="<?= SITE_URL ?>/mi-comercio/perfil" class="<?= str_contains($__currentUri, '/mi-comercio/perfil') ? 'active' : '' ?>">👤 Mi Perfil</a>
        <a href="<?= SITE_URL ?>/logout">🚪 Cerrar Sesión</a>
    </nav>
    <div class="user-info">
        <strong><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></strong>
        Comerciante
    </div>
</aside>

<main class="panel-main">
    <div class="panel-header">
        <button class="panel-toggle" onclick="document.getElementById('panelSidebar').classList.toggle('open')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div></div>
    </div>

    <?php if ($__flashSuccess): ?>
        <div class="flash-success"><?= $__flashSuccess ?></div>
    <?php endif; ?>
    <?php if ($__flashError): ?>
        <div class="flash-error"><?= $__flashError ?></div>
    <?php endif; ?>

    <?php require ROOT_PATH . '/views/' . $viewName . '.php'; ?>
</main>

</body>
</html>
