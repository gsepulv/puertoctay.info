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
        // Redirect to dedicated page (fallback if session still has data)
        header('Location: ' . SITE_URL . '/registrar-comercio/gracias');
        exit;
        ?>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 1.2rem;">
                <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio" id="registroForm" enctype="multipart/form-data"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <!-- SECCION 1: Datos del propietario -->
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
            <label>Tipo de establecimiento <span style="color: #DC2626;">*</span></label>
            <select name="tipo" required>
                <option value="">Selecciona el tipo...</option>
                <option value="comercio" <?= ($d['tipo'] ?? '') === 'comercio' ? 'selected' : '' ?>>Comercio local</option>
                <option value="atractivo" <?= ($d['tipo'] ?? '') === 'atractivo' ? 'selected' : '' ?>>Atractivo turístico</option>
                <option value="gastronomia" <?= ($d['tipo'] ?? '') === 'gastronomia' ? 'selected' : '' ?>>Gastronomía</option>
                <option value="servicio" <?= ($d['tipo'] ?? '') === 'servicio' ? 'selected' : '' ?>>Servicio turístico</option>
            </select>
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

        <!-- Ubicación -->
        <h4 style="margin: 1.5rem 0 0.75rem; font-size: 0.95rem; color: var(--text-light);">Ubicación (opcional)</h4>
        <div class="form-row">
            <div class="form-group">
                <label>Latitud</label>
                <input type="text" name="lat" value="<?= htmlspecialchars($d['lat'] ?? '') ?>" placeholder="-40.9724" pattern="-?[0-9]+(\.[0-9]+)?" title="Número decimal, ej: -40.9724">
            </div>
            <div class="form-group">
                <label>Longitud</label>
                <input type="text" name="lng" value="<?= htmlspecialchars($d['lng'] ?? '') ?>" placeholder="-72.8876" pattern="-?[0-9]+(\.[0-9]+)?" title="Número decimal, ej: -72.8876">
            </div>
        </div>
        <div class="form-group">
            <label>Cómo llegar</label>
            <textarea name="como_llegar" rows="2" placeholder="Indicaciones de acceso: desde la plaza, tomar calle..." maxlength="500"><?= htmlspecialchars($d['como_llegar'] ?? '') ?></textarea>
        <div class="form-group">
            <label>Horario de atención</label>
            <input type="text" name="horario" value="<?= htmlspecialchars($d['horario'] ?? '') ?>" placeholder="Ej: Lun-Vie 09:00-18:00, Sáb 10:00-14:00" maxlength="255">
        </div>

        <!-- SECCION 4: Temporadas -->
        <?php if (!empty($temporadas)): ?>
        <h3 style="margin: 2rem 0 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">🗓️ Temporadas turísticas</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Selecciona las temporadas en que tu comercio opera. Puedes agregar una promoción especial para cada una.</p>

        <div class="temporadas-grid">
            <?php foreach ($temporadas as $temp): ?>
            <div class="temporada-card" id="card-<?= $temp['id'] ?>">
                <label class="temporada-label">
                    <input type="checkbox" name="temporadas[]" value="<?= $temp['id'] ?>"
                           <?= in_array($temp['id'], (array)($d['temporadas'] ?? [])) ? 'checked' : '' ?>
                           onchange="togglePromo(<?= $temp['id'] ?>)">
                    <span class="temporada-emoji"><?= $temp['emoji'] ?? '📅' ?></span>
                    <span class="temporada-nombre"><?= htmlspecialchars($temp['nombre']) ?></span>
                </label>
                <div class="promo-campo" id="promo-<?= $temp['id'] ?>" style="display:<?= in_array($temp['id'], (array)($d['temporadas'] ?? [])) ? 'block' : 'none' ?>;">
                    <input type="text" name="temporada_promocion[<?= $temp['id'] ?>]"
                           value="<?= htmlspecialchars($d['temporada_promocion'][$temp['id']] ?? '') ?>"
                           placeholder="Promoción especial (opcional)" maxlength="150">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- SECCION 5: Imágenes -->
        <h3 style="margin: 2rem 0 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Imágenes</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Plan Freemium permite 2 imágenes: logo y portada. JPG, PNG o WebP, máximo 2 MB cada una.</p>

        <div class="form-row">
            <div class="form-group">
                <label>Logo</label>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp">
                <small class="text-light">Ideal: 800x800px. Se muestra circular.</small>
            </div>
            <div class="form-group">
                <label>Foto de portada</label>
                <input type="file" name="portada" accept="image/jpeg,image/png,image/webp">
                <small class="text-light">Ideal: 1200x400px. Se muestra como banner.</small>
            </div>
        </div>

        <!-- SECCION 6: Red social -->
        <h3 style="margin: 2rem 0 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">Red social</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Plan Freemium permite 1 red social. Elige la más importante para tu negocio.</p>

        <div class="form-row">
            <div class="form-group">
                <label>Red social</label>
                <select name="red_social_tipo">
                    <option value="">— Seleccionar —</option>
                    <option value="facebook" <?= ($d['red_social_tipo'] ?? '') === 'facebook' ? 'selected' : '' ?>>Facebook</option>
                    <option value="instagram" <?= ($d['red_social_tipo'] ?? '') === 'instagram' ? 'selected' : '' ?>>Instagram</option>
                    <option value="tiktok" <?= ($d['red_social_tipo'] ?? '') === 'tiktok' ? 'selected' : '' ?>>TikTok</option>
                    <option value="youtube" <?= ($d['red_social_tipo'] ?? '') === 'youtube' ? 'selected' : '' ?>>YouTube</option>
                    <option value="twitter" <?= ($d['red_social_tipo'] ?? '') === 'twitter' ? 'selected' : '' ?>>X / Twitter</option>
                    <option value="linkedin" <?= ($d['red_social_tipo'] ?? '') === 'linkedin' ? 'selected' : '' ?>>LinkedIn</option>
                    <option value="otra" <?= ($d['red_social_tipo'] ?? '') === 'otra' ? 'selected' : '' ?>>Otra</option>
                </select>
            </div>
            <div class="form-group">
                <label>URL del perfil</label>
                <input type="url" name="red_social_url" value="<?= htmlspecialchars($d['red_social_url'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>

        <!-- SECCION 7: Idiomas -->
        <h3 style="margin: 2rem 0 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border);">🌐 Idiomas de atención</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">¿En qué idiomas pueden comunicarse los turistas contigo?</p>

        <div class="idiomas-grid">
            <label class="idioma-card">
                <input type="checkbox" name="idiomas[]" value="es" <?= in_array('es', (array)($d['idiomas'] ?? [])) ? 'checked' : '' ?>>
                <span class="idioma-bandera">🇨🇱</span>
                <span class="idioma-nombre">Español</span>
            </label>
            <label class="idioma-card">
                <input type="checkbox" name="idiomas[]" value="en" <?= in_array('en', (array)($d['idiomas'] ?? [])) ? 'checked' : '' ?>>
                <span class="idioma-bandera">🇬🇧</span>
                <span class="idioma-nombre">English</span>
            </label>
            <label class="idioma-card">
                <input type="checkbox" name="idiomas[]" value="de" <?= in_array('de', (array)($d['idiomas'] ?? [])) ? 'checked' : '' ?>>
                <span class="idioma-bandera">🇩🇪</span>
                <span class="idioma-nombre">Deutsch</span>
            </label>
            <label class="idioma-card">
                <input type="checkbox" name="idiomas[]" value="fr" <?= in_array('fr', (array)($d['idiomas'] ?? [])) ? 'checked' : '' ?>>
                <span class="idioma-bandera">🇫🇷</span>
                <span class="idioma-nombre">Français</span>
            </label>
            <label class="idioma-card">
                <input type="checkbox" name="idiomas[]" value="pt" <?= in_array('pt', (array)($d['idiomas'] ?? [])) ? 'checked' : '' ?>>
                <span class="idioma-bandera">🇧🇷</span>
                <span class="idioma-nombre">Português</span>
            </label>
        </div>

        <!-- SECCION 8: Términos y Políticas (card con modales) -->
        <?php
        $pols = [
            ['politica_terminos', 'Términos y Condiciones', SITE_URL . '/terminos-y-condiciones'],
            ['politica_privacidad', 'Política de Privacidad', SITE_URL . '/politica-de-privacidad'],
            ['politica_contenidos', 'Política de Contenidos', SITE_URL . '/pagina/politica-de-contenidos'],
            ['politica_derechos', 'Ejercicio de Derechos', SITE_URL . '/pagina/ejercicio-de-derechos'],
            ['politica_cookies', 'Política de Cookies', SITE_URL . '/politica-de-cookies'],
        ];
        ?>
        <div id="politicasCard" style="margin-top: 2rem; border: 2.5px solid #f97316; border-radius: 16px; padding: 24px 28px 28px; background: #fffbf0; transition: all 0.3s ease;">
            <!-- Header -->
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                <span style="font-size: 20px;">📋</span>
                <span style="font-weight: 800; font-size: 16px; letter-spacing: 0.5px; color: #1f2937;">TÉRMINOS Y POLÍTICAS</span>
                <span id="polCountBadge" style="background: #fff3e0; color: #ea580c; font-size: 13px; font-weight: 700; padding: 3px 10px; border-radius: 20px;">0 de 5 aceptadas</span>
                <span id="polStatusIcon" style="margin-left: auto; font-size: 20px;">⚠️</span>
            </div>
            <p style="font-size: 14px; color: #6b7280; margin: 0 0 20px; line-height: 1.5;">Lee cada política y selecciona tu decisión. <strong style="color: #374151;">Debes aceptar todas para registrarte.</strong></p>

            <!-- Policy rows -->
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php foreach ($pols as [$name, $label, $url]): ?>
                <div class="pol-row" data-pol="<?= $name ?>" style="display: flex; align-items: center; justify-content: space-between; border: 1.5px solid #fddcb5; border-radius: 10px; padding: 12px 18px; background: #fff; transition: all 0.2s ease; flex-wrap: wrap; gap: 8px;">
                    <button type="button" onclick="openPolModal('<?= $name ?>')" style="background: none; border: none; color: #2563eb; font-size: 15px; font-weight: 500; cursor: pointer; padding: 0; text-decoration: underline; text-underline-offset: 3px; text-align: left; min-width: 200px;">
                        <?= $label ?>
                    </button>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; color: #374151;">
                            <input type="radio" name="<?= $name ?>" value="acepto" class="policy-radio" style="accent-color: #16a34a; width: 16px; height: 16px;" <?= ($d[$name] ?? '') === 'acepto' ? 'checked' : '' ?> required> Acepto
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 14px; color: #dc2626;">
                            <input type="radio" name="<?= $name ?>" value="rechazo" class="policy-radio" style="accent-color: #dc2626; width: 16px; height: 16px;" <?= ($d[$name] ?? '') === 'rechazo' ? 'checked' : '' ?>> Rechazo
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Messages -->
            <div id="polMsgReject" style="display: none; margin-top: 16px; padding: 10px 14px; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; font-size: 13px; color: #991b1b; align-items: center; gap: 8px;">
                <span>❌</span> Debes aceptar todas las políticas para poder registrarte. Si rechazas alguna, no podrás continuar con el registro.
            </div>
            <div id="polMsgOk" style="display: none; margin-top: 16px; padding: 10px 14px; background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; font-size: 13px; color: #166534; align-items: center; gap: 8px;">
                <span>✅</span> Has aceptado todas las políticas. Puedes continuar con el registro.
            </div>
        </div>

        <!-- Modal de política (hidden by default) -->
        <div id="polModal" onclick="closePolModal()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 1000; padding: 16px;">
            <div onclick="event.stopPropagation()" style="background: #fff; border-radius: 16px; max-width: 680px; width: 100%; max-height: 85vh; display: flex; flex-direction: column; box-shadow: 0 25px 50px rgba(0,0,0,0.25);">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; flex-shrink: 0;">
                    <h2 id="polModalTitle" style="margin: 0; font-size: 18px; font-weight: 700; color: #1f2937;">📋 Política</h2>
                    <button type="button" onclick="closePolModal()" style="background: #f3f4f6; border: none; width: 36px; height: 36px; border-radius: 50%; font-size: 18px; cursor: pointer; color: #6b7280;">✕</button>
                </div>
                <div id="polModalBody" style="padding: 24px; overflow-y: auto; flex: 1; font-size: 14px; line-height: 1.7; color: #374151; white-space: pre-wrap;"></div>
                <div style="display: flex; gap: 12px; padding: 16px 24px; border-top: 1px solid #e5e7eb; flex-shrink: 0; justify-content: flex-end;">
                    <button type="button" id="polModalReject" onclick="polModalDecide('rechazo')" style="padding: 10px 24px; border-radius: 8px; border: 1.5px solid #fca5a5; background: #fff; color: #dc2626; font-weight: 600; font-size: 14px; cursor: pointer;">Rechazo</button>
                    <button type="button" id="polModalAccept" onclick="polModalDecide('acepto')" style="padding: 10px 24px; border-radius: 8px; border: none; background: #16a34a; color: #fff; font-weight: 600; font-size: 14px; cursor: pointer;">Acepto</button>
                </div>
            </div>
        </div>

        <!-- Nota y envío -->
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

<style>
/* TEMPORADAS */
.temporadas-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;margin-top:12px}
.temporada-card{border:2px solid #e5e7eb;border-radius:10px;padding:10px 12px;transition:border-color 0.2s,background 0.2s}
.temporada-card:has(input:checked){border-color:var(--primary);background:#fff8f0}
.temporada-label{display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.9rem;font-weight:500}
.temporada-label input[type="checkbox"]{width:18px;height:18px;accent-color:var(--primary);flex-shrink:0}
.temporada-emoji{font-size:1.4rem;line-height:1}
.temporada-nombre{flex:1;line-height:1.2}
.promo-campo{margin-top:8px}
.promo-campo input{width:100%;font-size:0.8rem;padding:6px 8px;border:1px solid #d1d5db;border-radius:6px;box-sizing:border-box}
/* IDIOMAS */
.idiomas-grid{display:flex;flex-wrap:wrap;gap:10px;margin-top:12px}
.idioma-card{display:flex;align-items:center;gap:8px;border:2px solid #e5e7eb;border-radius:10px;padding:10px 16px;cursor:pointer;transition:border-color 0.2s,background 0.2s;min-width:130px}
.idioma-card:has(input:checked){border-color:var(--primary);background:#fff8f0}
.idioma-card input[type="checkbox"]{width:16px;height:16px;accent-color:var(--primary)}
.idioma-bandera{font-size:1.5rem;line-height:1}
.idioma-nombre{font-size:0.9rem;font-weight:500}
</style>
</section>


<script>
function togglePromo(id) {
    var checkbox = document.querySelector('input[name="temporadas[]"][value="' + id + '"]');
    var promo = document.getElementById('promo-' + id);
    if (checkbox && promo) {
        promo.style.display = checkbox.checked ? 'block' : 'none';
    }
}
</script>

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

// === Policies: modal + dynamic styling ===
var policyNames = ['politica_terminos', 'politica_privacidad', 'politica_contenidos', 'politica_derechos', 'politica_cookies'];
var policyLabels = {'politica_terminos':'Términos y Condiciones','politica_privacidad':'Política de Privacidad','politica_contenidos':'Política de Contenidos','politica_derechos':'Ejercicio de Derechos','politica_cookies':'Política de Cookies'};
var currentPolName = null;

function openPolModal(name) {
    currentPolName = name;
    document.getElementById('polModalTitle').textContent = '📋 ' + (policyLabels[name] || name);
    document.getElementById('polModalBody').textContent = 'Cargando...';
    document.getElementById('polModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Load content via fetch from the policy page
    var urls = {
        'politica_terminos': '<?= SITE_URL ?>/terminos-y-condiciones',
        'politica_privacidad': '<?= SITE_URL ?>/politica-de-privacidad',
        'politica_contenidos': '<?= SITE_URL ?>/pagina/politica-de-contenidos',
        'politica_derechos': '<?= SITE_URL ?>/pagina/ejercicio-de-derechos',
        'politica_cookies': '<?= SITE_URL ?>/politica-de-cookies'
    };
    fetch(urls[name]).then(function(r) { return r.text(); }).then(function(html) {
        var tmp = document.createElement('div');
        tmp.innerHTML = html;
        var content = tmp.querySelector('.container-narrow, .noticia-contenido, article, .section .container');
        document.getElementById('polModalBody').innerHTML = content ? content.innerHTML : '<p>Contenido no disponible. <a href="' + urls[name] + '" target="_blank">Abrir en nueva pestaña</a></p>';
    }).catch(function() {
        document.getElementById('polModalBody').innerHTML = '<p>No se pudo cargar. <a href="' + (urls[name]||'#') + '" target="_blank">Abrir en nueva pestaña</a></p>';
    });
}

function closePolModal() {
    document.getElementById('polModal').style.display = 'none';
    document.body.style.overflow = '';
    currentPolName = null;
}

function polModalDecide(value) {
    if (!currentPolName) return;
    var radios = document.querySelectorAll('input[name="' + currentPolName + '"]');
    radios.forEach(function(r) { r.checked = (r.value === value); });
    closePolModal();
    updatePoliciesUI();
}

document.querySelectorAll('.policy-radio').forEach(function(r) {
    r.addEventListener('change', updatePoliciesUI);
});

function updatePoliciesUI() {
    var accepted = 0;
    var rejected = false;
    policyNames.forEach(function(name) {
        var checked = document.querySelector('input[name="' + name + '"]:checked');
        var row = document.querySelector('.pol-row[data-pol="' + name + '"]');
        if (checked && checked.value === 'acepto') {
            accepted++;
            if (row) { row.style.borderColor = '#86efac'; row.style.background = '#f0fdf4'; }
            var lbl = row ? row.querySelector('label:first-of-type') : null;
            if (lbl) { lbl.style.fontWeight = '700'; lbl.style.color = '#16a34a'; }
        } else if (checked && checked.value === 'rechazo') {
            rejected = true;
            if (row) { row.style.borderColor = '#fca5a5'; row.style.background = '#fef2f2'; }
        } else {
            if (row) { row.style.borderColor = '#fddcb5'; row.style.background = '#fff'; }
        }
    });
    var allOk = (accepted === 5);
    var card = document.getElementById('politicasCard');
    var badge = document.getElementById('polCountBadge');
    var icon = document.getElementById('polStatusIcon');
    var msgOk = document.getElementById('polMsgOk');
    var msgReject = document.getElementById('polMsgReject');
    var btn = document.getElementById('submitBtn');

    badge.textContent = accepted + ' de 5 aceptadas';
    if (allOk) {
        card.style.borderColor = '#22c55e'; card.style.background = '#f0fdf4';
        badge.style.background = '#dcfce7'; badge.style.color = '#16a34a';
        icon.textContent = '✅';
        msgOk.style.display = 'flex'; msgReject.style.display = 'none';
    } else if (rejected) {
        card.style.borderColor = '#ef4444'; card.style.background = '#fef2f2';
        badge.style.background = '#fee2e2'; badge.style.color = '#dc2626';
        icon.textContent = '⚠️';
        msgOk.style.display = 'none'; msgReject.style.display = 'flex';
    } else {
        card.style.borderColor = '#f97316'; card.style.background = '#fffbf0';
        badge.style.background = '#fff3e0'; badge.style.color = '#ea580c';
        icon.textContent = '⚠️';
        msgOk.style.display = 'none'; msgReject.style.display = 'none';
    }
    btn.disabled = !allOk;
    btn.style.opacity = allOk ? '1' : '0.5';
}
updatePoliciesUI();

// On submit: scroll to policies if not all accepted
document.getElementById('registroForm').addEventListener('submit', function(e) {
    updatePoliciesUI();
    var accepted = 0;
    policyNames.forEach(function(name) {
        var c = document.querySelector('input[name="' + name + '"]:checked');
        if (c && c.value === 'acepto') accepted++;
    });
    if (accepted < 5) {
        e.preventDefault();
        document.getElementById('politicasCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
});

// Close modal on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePolModal();
});
</script>
