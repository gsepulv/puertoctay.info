<?php
$camposEspecificos = json_decode($negocio['campos_especificos'] ?? '{}', true) ?: [];
$camposConfig = CamposHelper::getCamposParaSubtipo($negocio['tipo'] ?? null, $negocio['subtipo'] ?? null);
?>

<!-- Cabecera con progreso -->
<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
    <div>
        <h1 style="margin:0;">Editar Negocio</h1>
        <div style="margin-top:0.5rem;display:flex;gap:0.5rem;align-items:center;">
            <?php if (!empty($negocio['tipo'])): ?>
                <span class="badge badge-active"><?= htmlspecialchars(ucfirst($negocio['tipo'])) ?></span>
            <?php endif; ?>
            <?php if (!empty($negocio['subtipo'])): ?>
                <span class="badge" style="background:#EFF6FF;color:#1E40AF;"><?= htmlspecialchars($negocio['subtipo']) ?></span>
            <?php endif; ?>
            <span class="badge" style="background:#F3F4F6;color:#6B7280;">Plan: <?= htmlspecialchars(ucfirst($negocio['plan'] ?? 'freemium')) ?></span>
        </div>
    </div>
    <div style="text-align:right;">
        <div style="width:160px;height:8px;background:#E2E8F0;border-radius:4px;overflow:hidden;">
            <div style="height:100%;background:#38A169;width:<?= $progreso ?>%;transition:width 0.3s;"></div>
        </div>
        <span style="font-size:0.8rem;color:var(--text-light);"><?= $progreso ?>% completado</span>
    </div>
</div>

<form method="POST" action="<?= SITE_URL ?>/mi-comercio/editar" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- INFORMACIÓN BÁSICA -->
    <div class="card">
        <h2 style="margin-bottom:1rem;">📝 Información básica</h2>

        <div class="form-group">
            <label>Nombre del negocio <span style="color:#DC2626">*</span></label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($negocio['nombre']) ?>" required minlength="3">
        </div>

        <div class="form-group">
            <label>Categoría</label>
            <select name="categoria_id">
                <option value="">Sin categoría</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($negocio['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= $cat['emoji'] ?? '' ?> <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Descripción corta <span style="color:#DC2626">*</span></label>
            <input type="text" name="descripcion_corta" value="<?= htmlspecialchars($negocio['descripcion_corta'] ?? '') ?>" maxlength="300" required>
            <small>Máximo 300 caracteres</small>
        </div>

        <div class="form-group">
            <label>Descripción completa</label>
            <textarea name="descripcion_larga" class="editor-wysiwyg" rows="6"><?= htmlspecialchars($negocio['descripcion_larga'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- IMÁGENES -->
    <div class="card">
        <h2 style="margin-bottom:1rem;">📸 Imágenes</h2>
        <p style="font-size:0.85rem;color:var(--text-light);margin-bottom:1rem;">Tu plan permite hasta <?= $planConfig['max_fotos'] ?? 3 ?> fotos (máx 2MB cada una)</p>

        <div class="form-row">
            <div class="form-group">
                <label>Logo</label>
                <?php if (!empty($negocio['logo'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/logos/<?= htmlspecialchars($negocio['logo']) ?>" style="max-width:120px;border-radius:8px;margin-bottom:0.5rem;display:block;">
                <?php endif; ?>
                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp">
                <small>800x800px recomendado</small>
            </div>
            <div class="form-group">
                <label>Portada</label>
                <?php if (!empty($negocio['portada'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/portadas/<?= htmlspecialchars($negocio['portada']) ?>" style="max-width:200px;border-radius:8px;margin-bottom:0.5rem;display:block;">
                <?php endif; ?>
                <input type="file" name="portada" accept="image/jpeg,image/png,image/webp">
                <small>1200x630px recomendado</small>
            </div>
        </div>
    </div>

    <!-- CAMPOS ESPECÍFICOS DEL TIPO/SUBTIPO -->
    <?php if (!empty($camposConfig)): ?>
    <div class="card">
        <h2 style="margin-bottom:1rem;">⚙️ Detalles de tu <?= htmlspecialchars(ucfirst($negocio['subtipo'] ?? $negocio['tipo'] ?? 'negocio')) ?></h2>
        <?= CamposHelper::renderTodos($camposConfig, $camposEspecificos) ?>
    </div>
    <?php endif; ?>

    <!-- UBICACIÓN -->
    <div class="card">
        <h2 style="margin-bottom:1rem;">📍 Ubicación</h2>

        <div class="form-row">
            <div class="form-group">
                <label>Dirección</label>
                <input type="text" name="direccion" value="<?= htmlspecialchars($negocio['direccion'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Sector</label>
                <select name="sector_id">
                    <option value="">Selecciona...</option>
                    <?php foreach ($sectores as $sector): ?>
                        <option value="<?= $sector['id'] ?>" <?= ($negocio['sector_id'] ?? '') == $sector['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sector['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Cómo llegar</label>
            <textarea name="como_llegar" rows="2" placeholder="Ej: A 2 km del centro, camino a Centinela..."><?= htmlspecialchars($negocio['como_llegar'] ?? '') ?></textarea>
        </div>

        <div style="margin-top:1rem;">
            <p style="font-size:0.85rem;color:var(--text-light);margin-bottom:0.5rem;">Haz clic en el mapa para marcar la ubicación exacta</p>
            <div id="mapa" style="height:300px;border-radius:8px;border:1px solid var(--border);"></div>
            <input type="hidden" name="lat" id="lat" value="<?= htmlspecialchars($negocio['lat'] ?? '') ?>">
            <input type="hidden" name="lng" id="lng" value="<?= htmlspecialchars($negocio['lng'] ?? '') ?>">
        </div>
    </div>

    <!-- CONTACTO -->
    <div class="card">
        <h2 style="margin-bottom:1rem;">📞 Contacto</h2>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono</label>
                <input type="tel" name="telefono" value="<?= htmlspecialchars($negocio['telefono'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>WhatsApp</label>
                <input type="tel" name="whatsapp" value="<?= htmlspecialchars($negocio['whatsapp'] ?? '') ?>" placeholder="+56 9 XXXX XXXX">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($negocio['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Sitio web</label>
                <input type="url" name="sitio_web" value="<?= htmlspecialchars($negocio['sitio_web'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>
    </div>

    <!-- REDES SOCIALES -->
    <div class="card">
        <h2 style="margin-bottom:1rem;">📱 Redes sociales</h2>
        <?php $maxRedes = (int)($planConfig['max_redes'] ?? 1); ?>
        <?php if ($maxRedes < 99): ?>
            <p style="background:#FEF3C7;padding:0.75rem 1rem;border-radius:6px;margin-bottom:1rem;font-size:0.85rem;">
                Tu plan permite <?= $maxRedes ?> red social. <a href="<?= SITE_URL ?>/planes" style="color:var(--primary);font-weight:600;">Mejora tu plan</a> para agregar más.
            </p>
        <?php endif; ?>

        <div class="form-row">
            <div class="form-group">
                <label>Facebook</label>
                <input type="url" name="facebook" value="<?= htmlspecialchars($negocio['facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label>Instagram</label>
                <input type="url" name="instagram" value="<?= htmlspecialchars($negocio['instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
            </div>
        </div>
        <?php if ($maxRedes >= 99): ?>
        <div class="form-row">
            <div class="form-group">
                <label>TikTok</label>
                <input type="url" name="tiktok" value="<?= htmlspecialchars($negocio['tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@...">
            </div>
            <div class="form-group">
                <label>YouTube</label>
                <input type="url" name="youtube" value="<?= htmlspecialchars($negocio['youtube'] ?? '') ?>" placeholder="https://youtube.com/...">
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- HORARIOS -->
    <div class="card">
        <?php if (!empty($planConfig['tiene_horarios'])): ?>
            <h2 style="margin-bottom:1rem;">🕐 Horarios de atención</h2>
            <div class="form-group">
                <label>Horario</label>
                <input type="text" name="horario" value="<?= htmlspecialchars($negocio['horario'] ?? '') ?>" placeholder="Ej: Lun-Vie 9:00-18:00, Sáb 10:00-14:00">
            </div>
        <?php else: ?>
            <h2 style="margin-bottom:1rem;opacity:0.6;">🔒 Horarios de atención</h2>
            <p style="background:#E2E8F0;padding:1rem;border-radius:6px;text-align:center;font-size:0.9rem;color:var(--text-light);">
                Disponible desde el plan Básico. <a href="<?= SITE_URL ?>/planes" style="color:var(--primary);font-weight:600;">Mejora tu plan</a>
            </p>
        <?php endif; ?>
    </div>

    <!-- TEMPORADAS -->
    <?php if (!empty($temporadas)): ?>
    <div class="card">
        <h2 style="margin-bottom:1rem;">🌤️ Temporadas turísticas</h2>
        <p style="font-size:0.85rem;color:var(--text-light);margin-bottom:1rem;">Selecciona las temporadas en las que tu negocio tiene actividad destacada.</p>
        <?php foreach ($temporadas as $temp):
            $isChecked = in_array($temp['id'], $negocioTempIds ?? []);
            $promo = $negocioPromociones[$temp['id']] ?? '';
        ?>
        <div style="border:1px solid var(--border);border-radius:8px;padding:0.75rem 1rem;margin-bottom:0.5rem;">
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;font-size:0.9rem;">
                <input type="checkbox" name="temporadas[]" value="<?= $temp['id'] ?>" <?= $isChecked ? 'checked' : '' ?>
                       onchange="this.closest('div').querySelector('.promo-field').style.display=this.checked?'block':'none'" style="width:auto;">
                <span style="font-size:1.1rem;"><?= $temp['emoji'] ?? '' ?></span>
                <strong><?= htmlspecialchars($temp['nombre']) ?></strong>
                <?php if ($temp['fecha_inicio'] && $temp['fecha_fin']): ?>
                    <small style="color:var(--text-light);">(<?= date('d/m', strtotime($temp['fecha_inicio'])) ?> — <?= date('d/m', strtotime($temp['fecha_fin'])) ?>)</small>
                <?php endif; ?>
            </label>
            <div class="promo-field" style="display:<?= $isChecked ? 'block' : 'none' ?>;margin-top:0.5rem;padding-left:1.8rem;">
                <input type="text" name="temporada_promocion[<?= $temp['id'] ?>]" value="<?= htmlspecialchars($promo) ?>"
                       placeholder="Promoción especial (ej: 20% descuento)" style="font-size:0.85rem;">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- BOTÓN GUARDAR -->
    <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary" style="padding:0.85rem 2rem;font-size:1rem;">Guardar cambios</button>
    </div>
</form>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var lat = <?= !empty($negocio['lat']) ? (float)$negocio['lat'] : -40.9712 ?>;
    var lng = <?= !empty($negocio['lng']) ? (float)$negocio['lng'] : -72.8834 ?>;
    var hasCoords = <?= (!empty($negocio['lat']) && !empty($negocio['lng'])) ? 'true' : 'false' ?>;

    var mapa = L.map('mapa').setView([lat, lng], hasCoords ? 16 : 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapa);

    var marker = null;
    if (hasCoords) {
        marker = L.marker([lat, lng]).addTo(mapa);
    }

    mapa.on('click', function(e) {
        if (marker) mapa.removeLayer(marker);
        marker = L.marker(e.latlng).addTo(mapa);
        document.getElementById('lat').value = e.latlng.lat.toFixed(6);
        document.getElementById('lng').value = e.latlng.lng.toFixed(6);
    });
});
</script>
