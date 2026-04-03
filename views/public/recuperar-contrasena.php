<section class="section-sm">
<div class="container" style="max-width: 440px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🔑</span>
        <h1 style="margin: 0.5rem 0;">Recuperar Contraseña</h1>
        <p class="text-light">Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.</p>
    </div>

    <?php if (!empty($exito)): ?>
        <div style="background: #F0FDF4; border: 1px solid #22C55E; border-radius: var(--radius-md); padding: 1.5rem; text-align: center;">
            <p style="color: #166534; margin: 0;">Si existe una cuenta con ese email, recibirás un enlace para restablecer tu contraseña.</p>
        </div>
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?= SITE_URL ?>/login" class="btn btn-outline">Volver al login</a>
        </div>
    <?php else: ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= SITE_URL ?>/recuperar-contrasena"
              style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email de tu cuenta</label>
                <input type="email" id="email" name="email" placeholder="tu@email.com" required autofocus>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Enviar enlace</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
            <a href="<?= SITE_URL ?>/login" style="color: var(--primary);">Volver al login</a>
        </div>

    <?php endif; ?>

</div>
</section>
