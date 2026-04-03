<section class="section-sm">
<div class="container" style="max-width: 440px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🔒</span>
        <h1 style="margin: 0.5rem 0;">Nueva Contraseña</h1>
    </div>

    <?php if (empty($tokenValido)): ?>
        <div style="background: #FEF2F2; border: 1px solid #EF4444; border-radius: var(--radius-md); padding: 1.5rem; text-align: center;">
            <p style="color: #991B1B; margin: 0;">El enlace ha expirado o no es válido. Solicita uno nuevo.</p>
        </div>
        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="<?= SITE_URL ?>/recuperar-contrasena" class="btn btn-primary">Solicitar nuevo enlace</a>
        </div>
    <?php else: ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Nueva contraseña</label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8" required autofocus>
            </div>

            <div class="form-group">
                <label>Confirmar contraseña</label>
                <input type="password" name="password_confirm" placeholder="Repite tu contraseña" minlength="8" required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">Guardar nueva contraseña</button>
        </form>

    <?php endif; ?>

</div>
</section>
