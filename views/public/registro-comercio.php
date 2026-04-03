<?php $d = $datos ?? []; ?>

<section class="section-sm">
<div class="container" style="max-width: 660px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">🏪</span>
        <h1 style="margin: 0.5rem 0;">Registra tu comercio</h1>
        <p class="text-light">Publica tu negocio en el directorio digital de Puerto Octay. <strong>Gratis por 30 días.</strong></p>
    </div>

    <?php if (!empty($_SESSION['registro_exito'])): ?>
        <?php
        $regEmail = $_SESSION['registro_email'] ?? '';
        $regNombre = $_SESSION['registro_nombre'] ?? '';
        unset($_SESSION['registro_exito'], $_SESSION['registro_email'], $_SESSION['registro_nombre']);
        ?>
        <div style="background: #F0FDF4; border: 2px solid #22C55E; border-radius: var(--radius-lg); padding: 2.5rem; text-align: center;">
            <div style="width: 64px; height: 64px; background: #22C55E; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h2 style="margin-bottom: 0.5rem; color: #166534;">Registro recibido exitosamente</h2>
            <?php if ($regEmail): ?>
                <p style="color: #166534; margin-bottom: 1rem;">Hemos enviado un correo de confirmación a <strong><?= htmlspecialchars($regEmail) ?></strong></p>
            <?php endif; ?>
            <p style="color: #15803D; margin-bottom: 1.5rem; line-height: 1.6;">
                Nuestro equipo revisará tu solicitud y te informaremos en un máximo de <strong>48 horas</strong> si tu registro fue aprobado.
            </p>
            <div style="background: #fff; border: 1px solid #BBF7D0; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; text-align: left; font-size: 0.9rem; color: #166534;">
                <p style="margin: 0 0 0.5rem;"><strong>¿Tienes consultas?</strong></p>
                <p style="margin: 0.25rem 0;">Email: <a href="mailto:contacto@purranque.info" style="color: #166534;">contacto@purranque.info</a></p>
                <p style="margin: 0.25rem 0;">WhatsApp: <a href="https://wa.me/56976547757" style="color: #166534;">+56 9 7654 7757</a></p>
            </div>
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

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio" id="registroForm"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <!-- SECCION 1: Políticas -->
        <div style="background: #FEF3C7; border: 2px dashed #F59E0B; border-radius: var(--radius-md); padding: 1.25rem; margin-bottom: 2rem;">
            <h3 style="margin: 0 0 0.3rem; font-size: 0.95rem; color: #92400E; text-transform: uppercase; letter-spacing: 0.5px;">Términos y Políticas — Lectura Obligatoria</h3>
            <p style="margin: 0 0 1rem; font-size: 0.8rem; color: #B45309;">Antes de continuar, lee cada política y selecciona tu decisión. Debes aceptar todas para registrarte.</p>

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
                            <input type="radio" name="<?= $name ?>" value="acepto" class="policy-radio" <?= ($d[$name] ?? '') === 'acepto' ? 'checked' : '' ?> required> Acepto
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.3rem; cursor: pointer; font-size: 0.85rem; color: #DC2626; font-weight: 600;">
                            <input type="radio" name="<?= $name ?>" value="rechazo" class="policy-radio" <?= ($d[$name] ?? '') === 'rechazo' ? 'checked' : '' ?>> Rechazo
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
            <input type="password" name="password" id="regPassword" placeholder="Mínimo 8 caracteres (1 mayúscula, 1 minúscula, 1 número)" minlength="8" required>
            <div id="passwordStrength" style="margin-top: 0.4rem; height: 4px; border-radius: 2px; background: var(--border); overflow: hidden;">
                <div id="strengthBar" style="height: 100%; width: 0; transition: all 0.3s;"></div>
            </div>
            <small id="strengthText" style="font-size: 0.8rem; color: var(--text-light);"></small>
        </div>
        <div class="form-group">
            <label>Confirmar contraseña <span style="color: #DC2626;">*</span></label>
            <input type="password" name="password_confirm" id="regPasswordConfirm" placeholder="Repite tu contraseña" minlength="8" required>
            <small id="matchText" style="font-size: 0.8rem;"></small>
        </div>

        <!-- SECCION 3: Datos del comercio -->
        <h3 style="margin: 2rem 0 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Datos del comercio</h3>

        <div class="form-group">
            <label>Nombre del comercio <span style="color: #DC2626;">*</span></label>
            <input type="text" name="nombre_comercio" value="<?= htmlspecialchars($d['nombre_comercio'] ?? '') ?>" placeholder="Ej: Restaurante El Lago" minlength="3" maxlength="150" required>
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
            <textarea name="descripcion_comercio" id="descComercio" rows="4" placeholder="Describe tu negocio: qué ofreces, qué te hace especial..." minlength="20" maxlength="2000" required><?= htmlspecialchars($d['descripcion_comercio'] ?? '') ?></textarea>
            <small class="text-light"><span id="descCount">0</span>/2000 caracteres (mínimo 20)</small>
        </div>
        <div class="form-group">
            <label>Dirección <span style="color: #DC2626;">*</span></label>
            <input type="text" name="direccion_comercio" value="<?= htmlspecialchars($d['direccion_comercio'] ?? '') ?>" placeholder="Ej: Pedro Montt 150, Puerto Octay" minlength="5" maxlength="255" required>
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

        <!-- SECCION 4: Temporadas -->
        <?php if (!empty($temporadas)): ?>
        <h3 style="margin: 2rem 0 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Temporadas turísticas</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Selecciona las temporadas en las que tu comercio tiene actividad. Puedes agregar una promoción especial para cada temporada.</p>

        <div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">
            <?php foreach ($temporadas as $temp): ?>
            <div class="temp-item" style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 0.75rem 1rem; transition: all 0.2s;">
                <label style="display: flex; align-items: center; gap: 0.6rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="temporadas[]" value="<?= $temp['id'] ?>" class="temp-check"
                        <?= in_array($temp['id'], (array)($d['temporadas'] ?? [])) ? 'checked' : '' ?>
                        onchange="this.closest('.temp-item').querySelector('.temp-promo').style.display=this.checked?'block':'none'">
                    <span style="font-size: 1.2rem;"><?= $temp['emoji'] ?></span>
                    <span><strong><?= htmlspecialchars($temp['nombre']) ?></strong></span>
                </label>
                <div class="temp-promo" style="display: <?= in_array($temp['id'], (array)($d['temporadas'] ?? [])) ? 'block' : 'none' ?>; margin-top: 0.5rem; padding-left: 2.2rem;">
                    <input type="text" name="temporada_promocion[<?= $temp['id'] ?>]"
                           value="<?= htmlspecialchars($d['temporada_promocion'][$temp['id']] ?? '') ?>"
                           placeholder="Promoción especial (ej: 20% descuento)"
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.85rem;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- SECCION 5: Nota y envío -->
        <div style="margin-top: 2rem; padding: 1rem; background: var(--bg); border-radius: var(--radius-md); display: flex; align-items: flex-start; gap: 0.75rem; font-size: 0.85rem; color: var(--text-light);">
            <span style="font-size: 1.2rem;">🔒</span>
            <div>
                <p style="margin: 0 0 0.25rem;">Tu información está segura y no será compartida.</p>
                <p style="margin: 0;">Tu comercio será revisado antes de ser publicado. Te informaremos en un máximo de 48 horas.</p>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-accent btn-lg" style="width: 100%; margin-top: 1.5rem; font-size: 1.05rem;">
            Registrar mi comercio
        </button>
    </form>

    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
        <p>¿Ya tienes cuenta? <a href="<?= SITE_URL ?>/login" style="color: var(--primary); font-weight: 600;">Inicia sesión</a></p>
    </div>

</div>
</section>

<script>
// Password strength indicator
document.getElementById('regPassword').addEventListener('input', function() {
    var pw = this.value;
    var bar = document.getElementById('strengthBar');
    var txt = document.getElementById('strengthText');
    var score = 0;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[a-z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;

    var levels = [
        {w: '0%', c: 'transparent', t: ''},
        {w: '20%', c: '#EF4444', t: 'Muy débil'},
        {w: '40%', c: '#F59E0B', t: 'Débil'},
        {w: '60%', c: '#F59E0B', t: 'Media'},
        {w: '80%', c: '#22C55E', t: 'Fuerte'},
        {w: '100%', c: '#16A34A', t: 'Muy fuerte'}
    ];
    var l = levels[score];
    bar.style.width = l.w;
    bar.style.background = l.c;
    txt.textContent = l.t;
    txt.style.color = l.c;
});

// Password match check
document.getElementById('regPasswordConfirm').addEventListener('input', function() {
    var pw = document.getElementById('regPassword').value;
    var mt = document.getElementById('matchText');
    if (this.value === '') { mt.textContent = ''; return; }
    if (this.value === pw) { mt.textContent = 'Las contraseñas coinciden'; mt.style.color = '#22C55E'; }
    else { mt.textContent = 'Las contraseñas no coinciden'; mt.style.color = '#EF4444'; }
});

// Character counter for description
var descEl = document.getElementById('descComercio');
var descCount = document.getElementById('descCount');
if (descEl && descCount) {
    descCount.textContent = descEl.value.length;
    descEl.addEventListener('input', function() { descCount.textContent = this.value.length; });
}

// Policy validation - disable submit if any policy rejected
document.querySelectorAll('.policy-radio').forEach(function(r) {
    r.addEventListener('change', checkPolicies);
});
function checkPolicies() {
    var allAccepted = true;
    ['politica_terminos', 'politica_privacidad', 'politica_cookies'].forEach(function(name) {
        var checked = document.querySelector('input[name="' + name + '"]:checked');
        if (!checked || checked.value !== 'acepto') allAccepted = false;
    });
    var btn = document.getElementById('submitBtn');
    btn.disabled = !allAccepted;
    btn.style.opacity = allAccepted ? '1' : '0.5';
}
checkPolicies();
</script>
