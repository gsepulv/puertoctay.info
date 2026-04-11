<?php $d = $datos ?? []; ?>

<section class="section-sm">
<div class="container" style="max-width: 520px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🏪</span>
        <h1 style="margin: 0.5rem 0;">Registra tu comercio</h1>
        <p class="text-light">Paso 1 de 2 — Crea tu cuenta</p>
        <p class="text-light"><strong>Gratis por 30 días.</strong></p>
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

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio" id="registroCuentaForm"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <h3 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Datos del propietario</h3>

        <div class="form-group">
            <label>Nombre completo <span style="color: #DC2626;">*</span></label>
            <input type="text" name="nombre_propietario" value="<?= htmlspecialchars($d['nombre_propietario'] ?? '') ?>" placeholder="Ej: María González" minlength="3" maxlength="100" required>
        </div>
        <div class="form-group">
            <label>Email <span style="color: #DC2626;">*</span></label>
            <input type="email" name="email_propietario" value="<?= htmlspecialchars($d['email_propietario'] ?? '') ?>" placeholder="tu@email.com" required>
            <small class="text-light">Será tu usuario para acceder</small>
        </div>
        <div class="form-group">
            <label>Teléfono / WhatsApp <span style="color: #DC2626;">*</span></label>
            <input type="text" name="telefono_propietario" value="<?= htmlspecialchars($d['telefono_propietario'] ?? '') ?>" placeholder="+56 9 XXXX XXXX" minlength="9" maxlength="15" required>
        </div>
        <div class="form-group">
            <label>Contraseña <span style="color: #DC2626;">*</span></label>
            <input type="password" name="password" placeholder="Mínimo 8 caracteres (1 mayúscula, 1 minúscula, 1 número)" minlength="8" required>
        </div>
        <div class="form-group">
            <label>Confirmar contraseña <span style="color: #DC2626;">*</span></label>
            <input type="password" name="password_confirm" placeholder="Repite tu contraseña" minlength="8" required>
        </div>

        <!-- Políticas -->
        <?php
        $pols = [
            ['politica_terminos', 'Términos y Condiciones', SITE_URL . '/terminos-y-condiciones'],
            ['politica_privacidad', 'Política de Privacidad', SITE_URL . '/politica-de-privacidad'],
            ['politica_contenidos', 'Política de Contenidos', SITE_URL . '/pagina/politica-de-contenidos'],
            ['politica_derechos', 'Ejercicio de Derechos', SITE_URL . '/pagina/ejercicio-de-derechos'],
            ['politica_cookies', 'Política de Cookies', SITE_URL . '/politica-de-cookies'],
        ];
        ?>
        <div style="margin-top: 1.5rem; border: 2px solid var(--border); border-radius: var(--radius-lg); padding: 1.25rem;">
            <h4 style="margin: 0 0 0.75rem; font-size: 0.95rem;">📋 Términos y Políticas</h4>
            <p style="font-size: 0.82rem; color: var(--text-light); margin-bottom: 0.75rem;">Debes aceptar todas para registrarte.</p>
            <?php foreach ($pols as [$name, $label, $url]): ?>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--border); flex-wrap: wrap; gap: 0.5rem;">
                <a href="<?= $url ?>" target="_blank" style="font-size: 0.88rem; color: var(--primary); text-decoration: underline;"><?= $label ?></a>
                <div style="display: flex; gap: 1rem;">
                    <label style="font-size: 0.85rem; cursor: pointer; color: #166534;"><input type="radio" name="<?= $name ?>" value="acepto" <?= ($d[$name] ?? '') === 'acepto' ? 'checked' : '' ?> required> Acepto</label>
                    <label style="font-size: 0.85rem; cursor: pointer; color: #DC2626;"><input type="radio" name="<?= $name ?>" value="rechazo" <?= ($d[$name] ?? '') === 'rechazo' ? 'checked' : '' ?>> Rechazo</label>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top: 1.5rem; padding: 1rem; background: var(--bg); border-radius: var(--radius-md); font-size: 0.85rem; color: var(--text-light);">
            🔒 Tu información está segura y no será compartida.
        </div>

        <button type="submit" class="btn btn-accent btn-lg" style="width: 100%; margin-top: 1.5rem;">
            Continuar → Datos del comercio
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        <p>¿Ya tienes cuenta? <a href="<?= SITE_URL ?>/login" style="color: var(--primary); font-weight: 600;">Inicia sesión</a></p>
    </div>
</div>
</section>
