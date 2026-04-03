<?php $flash = $_SESSION['flash_success'] ?? ''; unset($_SESSION['flash_success']); ?>

<section class="section-sm">
<div class="container" style="max-width: 440px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🔐</span>
        <h1 style="margin: 0.5rem 0;">Iniciar Sesión</h1>
        <p class="text-light">Accede a tu cuenta en <?= SITE_NAME ?></p>
    </div>

    <?php if ($flash): ?>
        <div style="background: #F0FDF4; border: 1px solid #22C55E; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; color: #166534; text-align: center;">
            <?= htmlspecialchars($flash) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/login"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="tu@email.com" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Tu contraseña" required>
        </div>

        <div style="text-align: right; margin-bottom: 1rem;">
            <a href="<?= SITE_URL ?>/recuperar-contrasena" style="font-size: 0.85rem; color: var(--primary);">¿Olvidaste tu contraseña?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Iniciar Sesión</button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        <p>¿No tienes cuenta? <a href="<?= SITE_URL ?>/registro" style="color: var(--primary); font-weight: 600;">Regístrate gratis</a></p>
        <p style="margin-top: 0.5rem;">¿Tienes un comercio? <a href="<?= SITE_URL ?>/registrar-comercio" style="color: var(--secondary); font-weight: 600;">Regístralo aquí</a></p>
    </div>

</div>
</section>
