<?php $d = $datos ?? []; ?>

<section class="section-sm">
<div class="container" style="max-width: 620px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🏪</span>
        <h1 style="margin: 0.5rem 0;">Registra tu comercio</h1>
        <p class="text-light">Publica tu negocio en el directorio digital de Puerto Octay. <strong>Gratis por 30 días.</strong></p>
    </div>

    <?php if (!empty($_SESSION['registro_exito'])): ?>
        <?php unset($_SESSION['registro_exito']); ?>
        <div style="background: #F0FDF4; border: 2px solid #22C55E; border-radius: var(--radius-lg); padding: 2rem; text-align: center;">
            <span style="font-size: 2.5rem; display: block; margin-bottom: 0.5rem;">&#10004;</span>
            <h2 style="margin-bottom: 0.5rem; color: #166534;">Registro exitoso</h2>
            <p style="color: #166534; margin-bottom: 1.5rem;">Tu comercio será revisado por nuestro equipo. Te notificaremos por email cuando esté publicado.</p>
            <a href="<?= SITE_URL ?>/" class="btn btn-primary">Volver al inicio</a>
        </div>
    <?php return; endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.2rem;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <!-- SECCION 1: Políticas -->
        <div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2rem;">
            <h3 style="margin: 0 0 0.3rem; font-size: 1rem; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Términos y Políticas</h3>
            <p style="margin: 0 0 1rem; font-size: 0.8rem; color: #B45309;">Debes aceptar todas las políticas para registrarte.</p>

            <?php
            $pols = [
                ['politica_terminos', 'Términos y Condiciones', SITE_URL . '/terminos-y-condiciones'],
                ['politica_privacidad', 'Política de Privacidad', SITE_URL . '/politica-de-privacidad'],
                ['politica_cookies', 'Política de Cookies', SITE_URL . '/politica-de-cookies'],
            ];
            foreach ($pols as [$name, $label, $url]):
            ?>
            <div style="background: #fff; border: 1px solid #FED7AA; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 0.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <a href="<?= $url ?>" target="_blank" style="font-weight: 600; font-size: 0.9rem; color: var(--primary); text-decoration: underline;"><?= $label ?></a>
                    <div style="display: flex; gap: 1rem;">
                        <label style="display: flex; align-items: center; gap: 0.3rem; cursor: pointer; font-size: 0.85rem; color: #15803D; font-weight: 600;">
                            <input type="radio" name="<?= $name ?>" value="acepto" <?= ($d[$name] ?? '') === 'acepto' ? 'checked' : '' ?> required> Acepto
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.3rem; cursor: pointer; font-size: 0.85rem; color: #DC2626; font-weight: 600;">
                            <input type="radio" name="<?= $name ?>" value="rechazo" <?= ($d[$name] ?? '') === 'rechazo' ? 'checked' : '' ?>> Rechazo
                        </label>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- SECCION 2: Datos del propietario -->
        <h3 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Datos del propietario</h3>

        <div class="form-group">
            <label>Nombre completo <span style="color: #DC2626;">*</span></label>
            <input type="text" name="nombre_propietario" value="<?= htmlspecialchars($d['nombre_propietario'] ?? '') ?>" placeholder="Ej: María González" minlength="3" required>
        </div>
        <div class="form-group">
            <label>Email <span style="color: #DC2626;">*</span></label>
            <input type="email" name="email_propietario" value="<?= htmlspecialchars($d['email_propietario'] ?? '') ?>" placeholder="tu@email.com" required>
            <small class="text-light">Será tu usuario para acceder</small>
        </div>
        <div class="form-group">
            <label>Teléfono / WhatsApp <span style="color: #DC2626;">*</span></label>
            <input type="text" name="telefono_propietario" value="<?= htmlspecialchars($d['telefono_propietario'] ?? '') ?>" placeholder="+56 9 XXXX XXXX" minlength="9" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contraseña <span style="color: #DC2626;">*</span></label>
                <input type="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8" required>
            </div>
            <div class="form-group">
                <label>Confirmar contraseña <span style="color: #DC2626;">*</span></label>
                <input type="password" name="password_confirm" placeholder="Repite tu contraseña" minlength="8" required>
            </div>
        </div>

        <!-- SECCION 3: Datos del comercio -->
        <h3 style="margin: 2rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Datos del comercio</h3>

        <div class="form-group">
            <label>Nombre del comercio <span style="color: #DC2626;">*</span></label>
            <input type="text" name="nombre_comercio" value="<?= htmlspecialchars($d['nombre_comercio'] ?? '') ?>" placeholder="Ej: Restaurante El Lago" minlength="3" required>
        </div>
        <div class="form-group">
            <label>Categoría <span style="color: #DC2626;">*</span></label>
            <select name="categoria_id" required>
                <option value="">Seleccionar categoría...</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($d['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['emoji'] ?? '' ?> <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Descripción del comercio <span style="color: #DC2626;">*</span></label>
            <textarea name="descripcion_comercio" rows="4" placeholder="Describe tu negocio: qué ofreces, qué te hace especial..." minlength="20" required><?= htmlspecialchars($d['descripcion_comercio'] ?? '') ?></textarea>
            <small class="text-light">Mínimo 20 caracteres</small>
        </div>
        <div class="form-group">
            <label>Dirección <span style="color: #DC2626;">*</span></label>
            <input type="text" name="direccion_comercio" value="<?= htmlspecialchars($d['direccion_comercio'] ?? '') ?>" placeholder="Ej: Pedro Montt 150, Puerto Octay" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Teléfono del comercio</label>
                <input type="text" name="telefono_comercio" value="<?= htmlspecialchars($d['telefono_comercio'] ?? '') ?>" placeholder="+56 65 XXX XXXX">
            </div>
            <div class="form-group">
                <label>Sitio web</label>
                <input type="url" name="sitio_web_comercio" value="<?= htmlspecialchars($d['sitio_web_comercio'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>

        <button type="submit" class="btn btn-accent btn-lg" style="width: 100%; margin-top: 1rem; font-size: 1.05rem;">
            Registrar mi comercio
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.85rem;">
        <p>¿Ya tienes cuenta? <a href="<?= SITE_URL ?>/login" style="color: var(--primary); font-weight: 600;">Inicia sesión</a></p>
        <p style="margin-top: 0.75rem; color: var(--text-lighter); font-size: 0.8rem;">Tu información está segura y no será compartida. Tu comercio será revisado antes de ser publicado.</p>
    </div>

</div>
</section>
