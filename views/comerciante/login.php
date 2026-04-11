<style>
.login-wrap { max-width:420px; margin:60px auto; padding:0 16px; }
.login-card { background:white; border:1px solid var(--border); border-radius:var(--radius-lg); padding:32px; box-shadow:var(--shadow-sm); }
.login-campo { margin-bottom:16px; }
.login-campo label { display:block; font-weight:600; margin-bottom:6px; font-size:0.9rem; }
.login-campo input { width:100%; padding:10px 12px; border:2px solid var(--border);
                     border-radius:var(--radius-md); font-size:1rem; box-sizing:border-box; font-family:inherit; }
.login-campo input:focus { outline:none; border-color:var(--primary); box-shadow:0 0 0 3px rgba(27,73,101,0.1); }
.login-btn { width:100%; padding:12px; background:var(--primary); color:white;
             border:none; border-radius:var(--radius-md); font-size:1rem; font-weight:700;
             cursor:pointer; margin-top:8px; font-family:inherit; transition:all 0.2s; }
.login-btn:hover { background:var(--primary-light,#285E85); }
.login-footer { text-align:center; margin-top:20px; color:var(--text-light); font-size:0.9rem; }
.login-footer a { color:var(--primary); font-weight:600; }
</style>

<div class="login-wrap">
    <div style="text-align:center; margin-bottom:28px;">
        <span style="font-size:2.5rem;">⛵</span>
        <h1 style="font-size:1.5rem; margin:0.5rem 0;">Mi Comercio</h1>
        <p style="color:var(--text-light);">Accede con el email y contraseña de tu registro.</p>
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div style="background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; padding:12px 16px; border-radius:var(--radius-md); margin-bottom:16px; font-size:0.9rem;">
            <?= htmlspecialchars($_SESSION['flash_error']) ?>
            <?php unset($_SESSION['flash_error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div style="background:#F0FDF4; border:1px solid #BBF7D0; color:#166534; padding:12px 16px; border-radius:var(--radius-md); margin-bottom:16px; font-size:0.9rem;">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
            <?php unset($_SESSION['flash_success']); ?>
        </div>
    <?php endif; ?>

    <div class="login-card">
        <form method="POST" action="<?= SITE_URL ?>/mi-comercio/login">
            <?= csrf_field() ?>

            <div class="login-campo">
                <label>Email</label>
                <input type="email" name="email" required placeholder="tu@email.com" autofocus>
            </div>

            <div class="login-campo">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="Tu contraseña">
            </div>

            <button type="submit" class="login-btn">Ingresar</button>
        </form>
    </div>

    <div class="login-footer">
        <p>¿No tienes cuenta? <a href="<?= SITE_URL ?>/registrar-comercio">Registra tu comercio gratis</a></p>
        <p style="margin-top:8px; font-size:0.82rem;"><a href="<?= SITE_URL ?>/admin/login" style="color:var(--text-light);">Acceso administradores</a></p>
    </div>
</div>
