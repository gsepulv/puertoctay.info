<?php $d = $datos ?? []; ?>

<section class="section-sm">
<div class="container" style="max-width: 660px;">

    <div style="text-align: center; margin-bottom: 2rem;">
        <span style="font-size: 3rem;">📝</span>
        <h1 style="margin: 0.5rem 0;">Datos de tu comercio</h1>
        <p class="text-light">Paso 2 de 2 — Completa la información de tu negocio</p>
        <p class="text-light">Hola <strong><?= htmlspecialchars($_SESSION['registro_nombre'] ?? '') ?></strong></p>
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

    <form method="POST" action="<?= SITE_URL ?>/registrar-comercio/datos" id="registroForm" enctype="multipart/form-data"
          style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
        <?= csrf_field() ?>
        <div style="position: absolute; left: -9999px;">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
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

        
        <div style="margin-top: 2rem; padding: 1rem; background: var(--bg); border-radius: var(--radius-md); font-size: 0.85rem; color: var(--text-light);">
            🔒 Tu comercio será revisado antes de ser publicado. Te informaremos en un máximo de 48 horas.
        </div>

        <button type="submit" id="submitBtn" class="btn btn-accent btn-lg" style="width: 100%; margin-top: 1.5rem; font-size: 1.05rem;">
            Registrar mi comercio
        </button>
    </form>
</div>
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
// Character counter for description
var descEl = document.getElementById('descComercio');
var descCount = document.getElementById('descCount');
if (descEl && descCount) {
    descCount.textContent = descEl.value.length;
    descEl.addEventListener('input', function() { descCount.textContent = this.value.length; });
}
</script>
