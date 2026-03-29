<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Próximamente — Visita Puerto Octay</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⛵</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Source+Sans+3:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Source Sans 3', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #fff;
            overflow-x: hidden;
            background: #0f2027;
            background: linear-gradient(135deg, #0f2027 0%, #163a45 30%, #1a4a3a 60%, #1e5a3a 100%);
            position: relative;
        }

        /* Textura sutil de ondas */
        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% 100%, rgba(59,130,246,0.12) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(34,197,94,0.08) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        /* Logo / marca */
        .brand {
            margin-bottom: 2rem;
        }
        .brand-icon {
            font-size: 3.5rem;
            display: block;
            margin-bottom: 0.5rem;
            filter: drop-shadow(0 4px 12px rgba(59,130,246,0.3));
        }
        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .brand-tagline {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.55);
            margin-top: 0.25rem;
        }

        /* Mensaje principal */
        .main-message {
            max-width: 560px;
            margin-bottom: 2.5rem;
        }
        .main-message h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2.2rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #93c5fd 0%, #6ee7b7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .main-message p {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.6;
        }

        /* Countdown */
        .countdown {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 2.5rem;
        }
        .countdown-item {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 1rem 0.8rem;
            min-width: 72px;
            backdrop-filter: blur(8px);
        }
        .countdown-value {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            display: block;
            color: #93c5fd;
        }
        .countdown-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.45);
            margin-top: 0.25rem;
        }

        /* Formulario suscripción */
        .subscribe {
            max-width: 420px;
            width: 100%;
            margin-bottom: 2rem;
        }
        .subscribe p {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.75rem;
        }
        .subscribe-form {
            display: flex;
            gap: 0.5rem;
        }
        .subscribe-form input[type="email"] {
            flex: 1;
            padding: 0.7rem 1rem;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            background: rgba(255,255,255,0.08);
            color: #fff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .subscribe-form input[type="email"]::placeholder {
            color: rgba(255,255,255,0.35);
        }
        .subscribe-form input[type="email"]:focus {
            border-color: rgba(147,197,253,0.5);
        }
        .subscribe-form button {
            padding: 0.7rem 1.3rem;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #2563eb, #1d9f6f);
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: opacity 0.2s;
            white-space: nowrap;
        }
        .subscribe-form button:hover {
            opacity: 0.85;
        }
        .subscribe-success {
            display: none;
            padding: 0.75rem 1rem;
            background: rgba(34,197,94,0.15);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 8px;
            color: #6ee7b7;
            font-size: 0.9rem;
        }

        /* Redes sociales */
        .socials {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .socials a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .socials a:hover {
            background: rgba(255,255,255,0.12);
            color: #fff;
            transform: translateY(-2px);
        }

        /* Footer */
        .maint-footer {
            padding: 1.5rem;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
            position: relative;
            z-index: 1;
        }
        .maint-footer a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
        }
        .maint-footer a:hover {
            color: rgba(255,255,255,0.6);
        }

        /* Decoración de olas */
        .wave {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 120px;
            z-index: 0;
            opacity: 0.15;
        }
        .wave svg {
            width: 100%;
            height: 100%;
        }

        @media (max-width: 600px) {
            .brand-name { font-size: 1.5rem; }
            .brand-icon { font-size: 2.5rem; }
            .main-message h1 { font-size: 1.6rem; }
            .main-message p { font-size: 1rem; }
            .countdown { gap: 0.5rem; }
            .countdown-item { min-width: 60px; padding: 0.75rem 0.5rem; }
            .countdown-value { font-size: 1.5rem; }
            .subscribe-form { flex-direction: column; }
            .subscribe-form button { width: 100%; }
        }
    </style>
</head>
<body>

<div class="wave">
    <svg viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path d="M0,60 C360,120 720,0 1080,60 C1260,90 1380,80 1440,60 L1440,120 L0,120 Z" fill="rgba(255,255,255,0.3)"/>
        <path d="M0,80 C240,40 480,100 720,80 C960,60 1200,100 1440,80 L1440,120 L0,120 Z" fill="rgba(255,255,255,0.15)"/>
    </svg>
</div>

<div class="wrapper">
    <!-- Marca -->
    <div class="brand">
        <span class="brand-icon">⛵</span>
        <div class="brand-name">Visita Puerto Octay</div>
        <div class="brand-tagline">A orillas del Lago Llanquihue</div>
    </div>

    <!-- Mensaje principal -->
    <div class="main-message">
        <h1><?= htmlspecialchars($config['mensaje_principal'] ?: 'Estamos preparando algo increíble') ?></h1>
        <?php if (!empty($config['mensaje_secundario'])): ?>
            <p><?= htmlspecialchars($config['mensaje_secundario']) ?></p>
        <?php else: ?>
            <p>Muy pronto podrás descubrir los mejores negocios, atractivos turísticos, gastronomía y patrimonio de Puerto Octay. Estamos trabajando para ofrecerte la mejor guía turística y comercial de la zona.</p>
        <?php endif; ?>
    </div>

    <!-- Countdown -->
    <?php if (!empty($config['fecha_lanzamiento']) && ($config['mostrar_countdown'] ?? '1') === '1'): ?>
    <div class="countdown" id="countdown" data-target="<?= htmlspecialchars($config['fecha_lanzamiento']) ?>">
        <div class="countdown-item">
            <span class="countdown-value" id="cd-days">--</span>
            <span class="countdown-label">Días</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="cd-hours">--</span>
            <span class="countdown-label">Horas</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="cd-mins">--</span>
            <span class="countdown-label">Minutos</span>
        </div>
        <div class="countdown-item">
            <span class="countdown-value" id="cd-secs">--</span>
            <span class="countdown-label">Segundos</span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Suscripción -->
    <?php if (($config['mostrar_suscripcion'] ?? '1') === '1'): ?>
    <div class="subscribe">
        <p>Suscríbete para saber cuándo estemos listos</p>
        <form class="subscribe-form" id="subscribeForm" onsubmit="return handleSubscribe(event)">
            <input type="email" name="email" placeholder="tu@email.com" required>
            <button type="submit">Avisarme</button>
        </form>
        <div class="subscribe-success" id="subscribeOk">¡Gracias! Te avisaremos cuando el sitio esté listo.</div>
    </div>
    <?php endif; ?>

    <!-- Redes sociales -->
    <?php if (!empty($redes)): ?>
    <div class="socials">
        <?php if (!empty($redes['facebook'])): ?>
            <a href="<?= htmlspecialchars($redes['facebook']) ?>" target="_blank" rel="noopener" title="Facebook">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($redes['instagram'])): ?>
            <a href="<?= htmlspecialchars($redes['instagram']) ?>" target="_blank" rel="noopener" title="Instagram">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($redes['youtube'])): ?>
            <a href="<?= htmlspecialchars($redes['youtube']) ?>" target="_blank" rel="noopener" title="YouTube">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
            </a>
        <?php endif; ?>
        <?php if (!empty($redes['tiktok'])): ?>
            <a href="<?= htmlspecialchars($redes['tiktok']) ?>" target="_blank" rel="noopener" title="TikTok">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<footer class="maint-footer">
    <p>visitapuertoctay.cl — Un servicio de <a href="https://purranque.info" target="_blank">PurranQUE.INFO</a></p>
</footer>

<script>
// Countdown
(function() {
    var el = document.getElementById('countdown');
    if (!el) return;
    var target = new Date(el.dataset.target + 'T00:00:00').getTime();
    function update() {
        var now = Date.now();
        var diff = target - now;
        if (diff <= 0) {
            document.getElementById('cd-days').textContent = '0';
            document.getElementById('cd-hours').textContent = '0';
            document.getElementById('cd-mins').textContent = '0';
            document.getElementById('cd-secs').textContent = '0';
            return;
        }
        var d = Math.floor(diff / 86400000);
        var h = Math.floor((diff % 86400000) / 3600000);
        var m = Math.floor((diff % 3600000) / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        document.getElementById('cd-days').textContent = d;
        document.getElementById('cd-hours').textContent = h;
        document.getElementById('cd-mins').textContent = m;
        document.getElementById('cd-secs').textContent = s;
    }
    update();
    setInterval(update, 1000);
})();

// Suscripción
function handleSubscribe(e) {
    e.preventDefault();
    var form = document.getElementById('subscribeForm');
    var email = form.querySelector('input[name="email"]').value;
    // Enviar al backend
    fetch('<?= SITE_URL ?>/api/subscribe', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'email=' + encodeURIComponent(email)
    }).then(function() {
        form.style.display = 'none';
        document.getElementById('subscribeOk').style.display = 'block';
    }).catch(function() {
        form.style.display = 'none';
        document.getElementById('subscribeOk').style.display = 'block';
    });
    return false;
}
</script>
</body>
</html>
