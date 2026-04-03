<?php $d = $datos ?? []; ?>

<section class="section-sm">
<div class="container" style="max-width: 480px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">👤</span>
        <h1 style="margin: 0.5rem 0;">Crear Cuenta</h1>
        <p class="text-light">Regístrate para dejar reseñas y guardar tus lugares favoritos.</p>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.2rem;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/registro"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-group">
            <label>Nombre completo <span style="color: #DC2626;">*</span></label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($d['nombre'] ?? '') ?>" placeholder="Tu nombre" minlength="3" required>
        </div>

        <div class="form-group">
            <label>Email <span style="color: #DC2626;">*</span></label>
            <input type="email" name="email" value="<?= htmlspecialchars($d['email'] ?? '') ?>" placeholder="tu@email.com" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña <span style="color: #DC2626;">*</span></label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8" required>
            </div>
            <div class="form-group">
                <label>Confirmar <span style="color: #DC2626;">*</span></label>
                <input type="password" name="password_confirm" placeholder="Repite tu contraseña" minlength="8" required>
            </div>
        </div>

        <div class="form-group" style="margin-top: 0.5rem;">
            <label style="display: flex; align-items: flex-start; gap: 0.5rem; cursor: pointer; font-size: 0.85rem; font-weight: normal;">
                <input type="checkbox" name="acepto_terminos" value="1" style="margin-top: 3px;" required>
                <span>Acepto los <a href="<?= SITE_URL ?>/terminos-y-condiciones" target="_blank" style="color: var(--primary);">Términos y Condiciones</a> y la <a href="<?= SITE_URL ?>/politica-de-privacidad" target="_blank" style="color: var(--primary);">Política de Privacidad</a>.</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%; margin-top: 1rem;">Crear mi cuenta</button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        <p>¿Ya tienes cuenta? <a href="<?= SITE_URL ?>/login" style="color: var(--primary); font-weight: 600;">Inicia sesión</a></p>
    </div>

</div>
</section>
