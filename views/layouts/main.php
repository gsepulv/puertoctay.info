<?php
$__siteName = htmlspecialchars(SITE_NAME, ENT_QUOTES, 'UTF-8');
$__siteTagline = htmlspecialchars(SITE_TAGLINE, ENT_QUOTES, 'UTF-8');
// Social links
$__redes = [];
try {
    $__cfgModel = new Configuracion(getDB());
    $__social = $__cfgModel->findByGrupo('social');
    foreach ($__social as $__s) {
        if (!empty($__s['valor'])) $__redes[$__s['clave']] = $__s['valor'];
    }
} catch (Exception $e) {}
?>
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
        <meta property="og:site_name" content="<?= $__siteName ?>">
        <?php if ($_img): ?><meta property="og:image" content="<?= $_img ?>"><?php endif; ?>
    <?php endif; ?>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <?php if (!empty($usarLeaflet)): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <?php endif; ?>
    <style>
/* ═══════════════════════════════════════════════════════════
   VISITA PUERTO OCTAY — Design System
   Palette: Lake Blue, Forest Green, Warm Amber
   ═══════════════════════════════════════════════════════════ */
:root {
    --primary: <?= COLOR_PRIMARY ?>;
    --primary-light: #285E85;
    --primary-dark: #0D1B2A;
    --secondary: <?= COLOR_SECONDARY ?>;
    --secondary-light: #40916C;
    --accent: <?= COLOR_ACCENT ?>;
    --accent-dark: #D4A843;
    --dark: #0D1B2A;
    --text: #1E293B;
    --text-light: #64748B;
    --text-lighter: #94A3B8;
    --border: #E2E8F0;
    --bg: #F8FAFC;
    --bg-warm: #FDFBF7;
    --white: #FFFFFF;
    --shadow-sm: 0 1px 3px rgba(13,27,42,0.06);
    --shadow-md: 0 4px 12px rgba(13,27,42,0.08);
    --shadow-lg: 0 12px 32px rgba(13,27,42,0.12);
    --shadow-hover: 0 8px 24px rgba(13,27,42,0.14);
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --radius-xl: 24px;
    --transition: 0.25s cubic-bezier(0.4,0,0.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--text);
    line-height: 1.65;
    background: var(--bg);
    -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4, h5 { font-weight: 700; line-height: 1.25; color: var(--primary-dark); }
h1 { font-size: 2.25rem; }
h2 { font-size: 1.75rem; }
h3 { font-size: 1.25rem; }
a { color: var(--primary); text-decoration: none; transition: color var(--transition); }
a:hover { color: var(--primary-light); }
img { max-width: 100%; height: auto; }
p { margin-bottom: 1rem; }

/* Container */
.container { max-width: 1200px; margin: 0 auto; padding: 0 1.25rem; }
.container-narrow { max-width: 800px; margin: 0 auto; padding: 0 1.25rem; }

/* ── HEADER / NAVBAR ──────────────────────────────────── */
.site-header {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    transition: box-shadow var(--transition);
}
.site-header.scrolled { box-shadow: var(--shadow-md); }
.header-inner {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.7rem 0; gap: 1rem;
}
.header-brand { display: flex; align-items: center; gap: 0.6rem; }
.header-brand a { display: flex; align-items: center; gap: 0.6rem; color: var(--primary-dark); }
.header-brand a:hover { text-decoration: none; color: var(--primary); }
.header-logo { font-size: 1.6rem; line-height: 1; }
.header-title { font-size: 1.15rem; font-weight: 800; letter-spacing: -0.02em; }
.header-tagline { font-size: 0.7rem; color: var(--text-light); font-weight: 500; display: block; }

.site-nav { display: flex; align-items: center; gap: 0.3rem; }
.site-nav a {
    padding: 0.45rem 0.75rem; border-radius: var(--radius-sm);
    font-size: 0.88rem; font-weight: 600; color: var(--text);
    transition: all var(--transition);
}
.site-nav a:hover { background: var(--bg); color: var(--primary); text-decoration: none; }
.site-nav .nav-cta {
    background: var(--accent); color: var(--primary-dark);
    padding: 0.45rem 1rem; border-radius: var(--radius-md);
    font-weight: 700; font-size: 0.85rem;
}
.site-nav .nav-cta:hover { background: var(--accent-dark); }

/* Mobile menu toggle */
.menu-toggle {
    display: none; background: none; border: none; cursor: pointer;
    padding: 0.5rem; color: var(--text);
}
.menu-toggle svg { display: block; }

/* ── BUTTONS ──────────────────────────────────────────── */
.btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem;
    padding: 0.65rem 1.4rem; border-radius: var(--radius-md);
    font-weight: 700; font-size: 0.9rem; text-decoration: none;
    cursor: pointer; border: none; font-family: inherit;
    transition: all var(--transition); line-height: 1.3;
}
.btn:hover { text-decoration: none; transform: translateY(-1px); }
.btn-primary { background: var(--primary); color: var(--white); }
.btn-primary:hover { background: var(--primary-light); color: var(--white); box-shadow: var(--shadow-md); }
.btn-secondary { background: var(--secondary); color: var(--white); }
.btn-secondary:hover { background: var(--secondary-light); color: var(--white); }
.btn-accent { background: var(--accent); color: var(--primary-dark); }
.btn-accent:hover { background: var(--accent-dark); }
.btn-outline {
    background: transparent; color: var(--primary);
    border: 2px solid var(--primary); padding: 0.55rem 1.3rem;
}
.btn-outline:hover { background: var(--primary); color: var(--white); }
.btn-sm { padding: 0.4rem 0.9rem; font-size: 0.82rem; }
.btn-lg { padding: 0.85rem 2rem; font-size: 1rem; }
.btn-ghost { background: transparent; color: var(--text-light); }
.btn-ghost:hover { background: var(--bg); color: var(--text); }

/* ── BADGES ───────────────────────────────────────────── */
.badge {
    display: inline-flex; align-items: center; padding: 0.2rem 0.6rem;
    border-radius: 20px; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.02em;
}
.badge-primary { background: rgba(27,73,101,0.1); color: var(--primary); }
.badge-secondary { background: rgba(45,106,79,0.1); color: var(--secondary); }
.badge-accent { background: var(--accent); color: var(--primary-dark); }
.badge-green { background: #DCFCE7; color: #166534; }
.badge-yellow { background: #FEF3C7; color: #92400E; }
.badge-red { background: #FEE2E2; color: #991B1B; }
.badge-gold { background: var(--accent); color: var(--primary-dark); }

/* ── CARDS ────────────────────────────────────────────── */
.card {
    background: var(--white); border-radius: var(--radius-lg);
    overflow: hidden; box-shadow: var(--shadow-sm);
    transition: all var(--transition); border: 1px solid var(--border);
}
.card:hover { box-shadow: var(--shadow-hover); transform: translateY(-3px); }
.card-img {
    width: 100%; height: 200px; object-fit: cover;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
}
.card-img-placeholder {
    width: 100%; height: 200px; display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #E0F2FE 0%, #DCFCE7 100%);
    color: var(--text-lighter); font-size: 2.5rem;
}
.card-body { padding: 1.2rem; }
.card-body h3 { font-size: 1.1rem; margin-bottom: 0.3rem; }
.card-body h3 a { color: var(--primary-dark); }
.card-body h3 a:hover { color: var(--primary); }
.card-body p { font-size: 0.9rem; color: var(--text-light); margin-bottom: 0.5rem; }
.card-meta {
    display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    font-size: 0.78rem; color: var(--text-lighter); margin-top: 0.6rem;
}
.card-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
.card-grid-sm { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.2rem; }

/* ── STAR RATINGS ─────────────────────────────────────── */
.stars { color: var(--accent); font-size: 0.9rem; letter-spacing: 1px; }
.stars-lg { font-size: 1.2rem; }

/* ── SECTIONS ─────────────────────────────────────────── */
.section { padding: 3.5rem 0; }
.section-sm { padding: 2rem 0; }
.section-header { text-align: center; margin-bottom: 2.5rem; }
.section-header h2 { margin-bottom: 0.5rem; }
.section-header p { color: var(--text-light); font-size: 1.05rem; max-width: 600px; margin: 0 auto; }
.section-white { background: var(--white); }
.section-warm { background: var(--bg-warm); }

/* ── HERO ─────────────────────────────────────────────── */
.hero {
    position: relative; padding: 5rem 0 4rem;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 40%, var(--secondary) 100%);
    color: var(--white); overflow: hidden;
}
.hero::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='rgba(255,255,255,0.04)' d='M0,224L48,208C96,192,192,160,288,165.3C384,171,480,213,576,218.7C672,224,768,192,864,181.3C960,171,1056,181,1152,186.7C1248,192,1344,192,1392,192L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E") no-repeat bottom/cover;
    opacity: 0.6;
}
.hero .container { position: relative; z-index: 1; }
.hero h1 { font-size: 2.8rem; font-weight: 800; margin-bottom: 0.6rem; color: var(--white); letter-spacing: -0.03em; }
.hero p { font-size: 1.15rem; opacity: 0.85; margin-bottom: 2rem; max-width: 520px; }
.hero-search {
    display: flex; gap: 0.5rem; max-width: 560px;
    background: rgba(255,255,255,0.15); backdrop-filter: blur(8px);
    border-radius: var(--radius-lg); padding: 0.4rem; border: 1px solid rgba(255,255,255,0.2);
}
.hero-search input {
    flex: 1; padding: 0.8rem 1.2rem; border: none; border-radius: var(--radius-md);
    font-size: 1rem; font-family: inherit; background: var(--white); color: var(--text);
    outline: none;
}
.hero-search input::placeholder { color: var(--text-lighter); }
.hero-search button {
    padding: 0.8rem 1.5rem; border: none; border-radius: var(--radius-md);
    background: var(--accent); color: var(--primary-dark); font-weight: 700;
    font-family: inherit; font-size: 0.95rem; cursor: pointer;
    transition: all var(--transition);
}
.hero-search button:hover { background: var(--accent-dark); }
.hero-stats {
    display: flex; gap: 2.5rem; margin-top: 2.5rem; padding-top: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.15);
}
.hero-stat-value { font-size: 1.8rem; font-weight: 800; display: block; }
.hero-stat-label { font-size: 0.8rem; opacity: 0.7; }

/* ── CATEGORY CARDS ───────────────────────────────────── */
.cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; }
.cat-card {
    background: var(--white); border-radius: var(--radius-lg); padding: 1.5rem 1rem;
    text-align: center; border: 1px solid var(--border);
    transition: all var(--transition); cursor: pointer;
}
.cat-card:hover { border-color: var(--primary); box-shadow: var(--shadow-md); transform: translateY(-2px); text-decoration: none; }
.cat-card .emoji { font-size: 2.2rem; display: block; margin-bottom: 0.6rem; }
.cat-card .name { font-weight: 700; color: var(--primary-dark); font-size: 0.9rem; display: block; }
.cat-card .count { font-size: 0.78rem; color: var(--text-lighter); margin-top: 0.2rem; display: block; }

/* ── FORMS ────────────────────────────────────────────── */
.form-group { margin-bottom: 1.3rem; }
.form-group label { display: block; margin-bottom: 0.35rem; font-weight: 600; font-size: 0.88rem; color: var(--text); }
.form-group input, .form-group select, .form-group textarea {
    width: 100%; padding: 0.7rem 1rem; border: 2px solid var(--border);
    border-radius: var(--radius-md); font-size: 0.95rem; font-family: inherit;
    color: var(--text); background: var(--white);
    transition: border-color var(--transition), box-shadow var(--transition);
}
.form-group input:focus, .form-group select:focus, .form-group textarea:focus {
    border-color: var(--primary); outline: none;
    box-shadow: 0 0 0 3px rgba(27,73,101,0.1);
}
.form-group input::placeholder, .form-group textarea::placeholder { color: var(--text-lighter); }
.form-group textarea { min-height: 120px; resize: vertical; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

/* ── ALERTS ───────────────────────────────────────────── */
.alert {
    padding: 0.9rem 1.2rem; border-radius: var(--radius-md); margin-bottom: 1.2rem;
    font-size: 0.9rem; font-weight: 500; border: 1px solid;
}
.alert-success { background: #F0FDF4; color: #166534; border-color: #BBF7D0; }
.alert-danger { background: #FEF2F2; color: #991B1B; border-color: #FECACA; }

/* ── BREADCRUMB ───────────────────────────────────────── */
.breadcrumb {
    display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap;
    font-size: 0.82rem; color: var(--text-lighter); margin-bottom: 1.5rem;
    padding: 0.8rem 0;
}
.breadcrumb a { color: var(--text-light); }
.breadcrumb a:hover { color: var(--primary); }
.breadcrumb .sep { opacity: 0.4; }

/* ── PAGINATION ───────────────────────────────────────── */
.pagination {
    display: flex; justify-content: center; gap: 0.3rem; margin-top: 2rem;
}
.pagination a, .pagination span {
    padding: 0.5rem 0.9rem; border-radius: var(--radius-sm);
    font-size: 0.85rem; font-weight: 600;
    border: 1px solid var(--border); color: var(--text);
}
.pagination a:hover { background: var(--bg); border-color: var(--primary); color: var(--primary); text-decoration: none; }
.pagination .active { background: var(--primary); color: var(--white); border-color: var(--primary); }

/* ── EMPTY STATE ──────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 4rem 1rem; color: var(--text-lighter);
}
.empty-state .icon { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }

/* ── UTILITY ──────────────────────────────────────────── */
.text-center { text-align: center; }
.text-light { color: var(--text-light); }
.text-sm { font-size: 0.85rem; }
.mt-1 { margin-top: 0.5rem; }
.mt-2 { margin-top: 1rem; }
.mt-3 { margin-top: 1.5rem; }
.mb-1 { margin-bottom: 0.5rem; }
.mb-2 { margin-bottom: 1rem; }
.mb-3 { margin-bottom: 1.5rem; }
.gap-1 { gap: 0.5rem; }
.flex { display: flex; }
.flex-wrap { flex-wrap: wrap; }
.items-center { align-items: center; }
.justify-between { justify-content: space-between; }
.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }

/* ── SCROLL TO TOP ────────────────────────────────────── */
.scroll-top {
    position: fixed; bottom: 2rem; right: 2rem; z-index: 90;
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--primary); color: var(--white);
    border: none; cursor: pointer; font-size: 1.2rem;
    display: none; align-items: center; justify-content: center;
    box-shadow: var(--shadow-md); transition: all var(--transition);
}
.scroll-top:hover { background: var(--primary-light); transform: translateY(-2px); }
.scroll-top.visible { display: flex; }

/* ── FOOTER ───────────────────────────────────────────── */
.site-footer {
    background: var(--primary-dark); color: rgba(255,255,255,0.7);
    padding: 3.5rem 0 0; margin-top: 4rem;
}
.footer-grid {
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 2.5rem;
    padding-bottom: 2.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);
}
.footer-brand h3 { color: var(--white); font-size: 1.2rem; margin-bottom: 0.6rem; }
.footer-brand p { font-size: 0.88rem; line-height: 1.6; margin-bottom: 1rem; }
.footer-col h4 {
    color: var(--white); font-size: 0.82rem; text-transform: uppercase;
    letter-spacing: 0.08em; margin-bottom: 1rem; font-weight: 700;
}
.footer-col a {
    display: block; color: rgba(255,255,255,0.6); font-size: 0.88rem;
    padding: 0.25rem 0; transition: color var(--transition);
}
.footer-col a:hover { color: var(--accent); text-decoration: none; }
.footer-social { display: flex; gap: 0.7rem; margin-top: 0.5rem; }
.footer-social a {
    display: flex; align-items: center; justify-content: center;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);
    transition: all var(--transition);
}
.footer-social a:hover { background: var(--accent); color: var(--primary-dark); transform: translateY(-2px); }
.footer-social a svg { width: 16px; height: 16px; }
.footer-bottom {
    padding: 1.5rem 0; text-align: center; font-size: 0.8rem;
    color: rgba(255,255,255,0.35);
}
.footer-bottom a { color: rgba(255,255,255,0.5); }
.footer-bottom a:hover { color: var(--accent); }

/* ── RESPONSIVE ───────────────────────────────────────── */
@media (max-width: 900px) {
    .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 768px) {
    h1 { font-size: 1.75rem; }
    h2 { font-size: 1.4rem; }
    .hero { padding: 3rem 0 2.5rem; }
    .hero h1 { font-size: 2rem; }
    .hero p { font-size: 1rem; }
    .hero-search { flex-direction: column; }
    .hero-search button { width: 100%; }
    .hero-stats { gap: 1.5rem; flex-wrap: wrap; }
    .site-nav { display: none; position: absolute; top: 100%; left: 0; right: 0; background: var(--white); flex-direction: column; padding: 1rem; border-bottom: 1px solid var(--border); box-shadow: var(--shadow-lg); }
    .site-nav.open { display: flex; }
    .site-nav a { padding: 0.7rem 1rem; }
    .menu-toggle { display: block; }
    .card-grid { grid-template-columns: 1fr; }
    .card-grid-sm { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
    .cat-grid { grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); }
    .form-row { grid-template-columns: 1fr; }
    .grid-2 { grid-template-columns: 1fr; }
    .footer-grid { grid-template-columns: 1fr; gap: 1.5rem; }
    .section { padding: 2.5rem 0; }
}

/* ── LEAFLET OVERRIDES ────────────────────────────────── */
.leaflet-popup-content-wrapper { border-radius: var(--radius-md) !important; }
.leaflet-popup-content { font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 0.9rem !important; }
    </style>
    <?php if (isset($extraHead)) echo $extraHead; ?>
</head>
<body>

<!-- HEADER -->
<header class="site-header" id="siteHeader">
    <div class="container">
        <div class="header-inner">
            <div class="header-brand">
                <a href="<?= SITE_URL ?>">
                    <span class="header-logo">⛵</span>
                    <div>
                        <span class="header-title"><?= $__siteName ?></span>
                        <span class="header-tagline">Lago Llanquihue, Chile</span>
                    </div>
                </a>
            </div>
            <button class="menu-toggle" onclick="document.querySelector('.site-nav').classList.toggle('open')" aria-label="Menú">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
            <nav class="site-nav" id="siteNav">
                <a href="<?= SITE_URL ?>/directorio">Directorio</a>
                <a href="<?= SITE_URL ?>/categorias">Categorías</a>
                <a href="<?= SITE_URL ?>/turismo">Turismo</a>
                <a href="<?= SITE_URL ?>/noticias">Noticias</a>
                <a href="<?= SITE_URL ?>/mapa">Mapa</a>
                <a href="<?= SITE_URL ?>/contacto" class="nav-cta">Contacto</a>
            </nav>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main>
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

<!-- FOOTER -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <h3>⛵ <?= $__siteName ?></h3>
                <p>Guía turística y directorio comercial de Puerto Octay, a orillas del Lago Llanquihue en la Región de Los Lagos, Chile.</p>
                <?php if (!empty($__redes)): ?>
                <div class="footer-social">
                    <?php if (!empty($__redes['facebook'])): ?>
                    <a href="<?= htmlspecialchars($__redes['facebook']) ?>" target="_blank" rel="noopener" title="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <?php endif; ?>
                    <?php if (!empty($__redes['instagram'])): ?>
                    <a href="<?= htmlspecialchars($__redes['instagram']) ?>" target="_blank" rel="noopener" title="Instagram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                    <?php endif; ?>
                    <?php if (!empty($__redes['youtube'])): ?>
                    <a href="<?= htmlspecialchars($__redes['youtube']) ?>" target="_blank" rel="noopener" title="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                    <?php endif; ?>
                    <?php if (!empty($__redes['tiktok'])): ?>
                    <a href="<?= htmlspecialchars($__redes['tiktok']) ?>" target="_blank" rel="noopener" title="TikTok"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg></a>
                    <?php endif; ?>
                    <?php if (!empty($__redes['whatsapp'])): ?>
                    <a href="<?= htmlspecialchars($__redes['whatsapp']) ?>" target="_blank" rel="noopener" title="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg></a>
                    <?php endif; ?>
                    <?php if (!empty($__redes['twitter'])): ?>
                    <a href="<?= htmlspecialchars($__redes['twitter']) ?>" target="_blank" rel="noopener" title="X"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="footer-col">
                <h4>Explora</h4>
                <a href="<?= SITE_URL ?>/directorio">Directorio</a>
                <a href="<?= SITE_URL ?>/categorias">Categorías</a>
                <a href="<?= SITE_URL ?>/turismo">Turismo</a>
                <a href="<?= SITE_URL ?>/patrimonio">Patrimonio</a>
                <a href="<?= SITE_URL ?>/mapa">Mapa</a>
            </div>
            <div class="footer-col">
                <h4>Información</h4>
                <a href="<?= SITE_URL ?>/noticias">Noticias</a>
                <a href="<?= SITE_URL ?>/planes">Planes</a>
                <a href="<?= SITE_URL ?>/contacto">Contacto</a>
                <a href="<?= SITE_URL ?>/pagina/acerca-de">Acerca de</a>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <a href="<?= SITE_URL ?>/politica-de-privacidad">Privacidad</a>
                <a href="<?= SITE_URL ?>/terminos-y-condiciones">Términos</a>
                <a href="<?= SITE_URL ?>/politica-de-cookies">Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> <?= $__siteName ?>. Todos los derechos reservados.</p>
            <p style="margin-top:0.3rem;">visitapuertoctay.cl — Un servicio de <a href="https://purranque.info" target="_blank">PurranQUE.INFO</a></p>
        </div>
    </div>
</footer>

<!-- Scroll to top -->
<button class="scroll-top" id="scrollTop" onclick="window.scrollTo({top:0,behavior:'smooth'})" aria-label="Volver arriba">&#8593;</button>

<script>
// Sticky header shadow
var header = document.getElementById('siteHeader');
window.addEventListener('scroll', function() {
    header.classList.toggle('scrolled', window.scrollY > 10);
    var st = document.getElementById('scrollTop');
    st.classList.toggle('visible', window.scrollY > 400);
});
// Close mobile menu on link click
document.querySelectorAll('.site-nav a').forEach(function(a) {
    a.addEventListener('click', function() {
        document.getElementById('siteNav').classList.remove('open');
    });
});
</script>
<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
