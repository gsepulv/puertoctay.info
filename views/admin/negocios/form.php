<?php
/**
 * Admin — Formulario Negocio (crear/editar) — visitapuertoctay.cl
 * Variables: $negocio (array vacío=crear), $categorias, $planes, $errores
 */
$isEdit = !empty($negocio);
$v = fn(string $key, string $default = '') => htmlspecialchars($negocio[$key] ?? $default, ENT_QUOTES, 'UTF-8');
$checked = fn(string $key, bool $defaultOn = false) =>
    $isEdit ? (!empty($negocio[$key]) ? 'checked' : '') : ($defaultOn ? 'checked' : '');
?>

<div class="admin-page-header">
    <h1><?= $isEdit ? 'Editar Negocio' : 'Nuevo Negocio' ?></h1>
    <p class="admin-page-subtitle">
        <a href="<?= SITE_URL ?>/admin/negocios">&larr; Volver al listado</a>
    </p>
</div>

<?php if (!empty($errores)): ?>
<div class="alert alert-danger">
    <ul style="margin:0; padding-left:1.2em;">
        <?php foreach ($errores as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST"
      action="<?= $isEdit ? SITE_URL . '/admin/negocios/' . $negocio['id'] . '/actualizar' : SITE_URL . '/admin/negocios/guardar' ?>"
      enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- ══ Sección 1: Información básica ══ -->
    <div class="form-card">
        <h3>Información básica</h3>

        <div class="form-row">
            <div class="form-group" style="flex:2">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?= $v('nombre') ?>"
                       class="form-input" required maxlength="200"
                       placeholder="Ej: Restaurant El Fogón">
            </div>
            <div class="form-group" style="flex:1">
                <label for="tipo">Tipo *</label>
                <select id="tipo" name="tipo" class="form-input" required>
                    <option value="">Seleccionar...</option>
                    <?php foreach (['comercio' => 'Comercio', 'atractivo' => 'Atractivo', 'servicio' => 'Servicio', 'gastronomia' => 'Gastronomía'] as $val => $label): ?>
                    <option value="<?= $val ?>" <?= (($negocio['tipo'] ?? '') === $val) ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select id="categoria_id" name="categoria_id" class="form-input">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($negocio['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['emoji'] ?? '') ?> <?= htmlspecialchars($cat['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="plan">Plan</label>
                <select id="plan" name="plan" class="form-input">
                    <option value="">Sin plan</option>
                    <?php foreach ($planes as $plan): ?>
                    <option value="<?= $plan['id'] ?>" <?= (($negocio['plan_id'] ?? '') == $plan['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($plan['nombre']) ?> ($<?= number_format($plan['precio'], 0, ',', '.') ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion_corta">Descripción corta</label>
            <input type="text" id="descripcion_corta" name="descripcion_corta"
                   value="<?= $v('descripcion_corta') ?>"
                   class="form-input" maxlength="300"
                   placeholder="Resumen breve para listados (máx. 300 caracteres)">
            <small id="desc_corta_counter" style="color:#888;">0/300</small>
        </div>

        <div class="form-group">
            <label for="descripcion_larga">Descripción larga</label>
            <textarea id="descripcion_larga" name="descripcion_larga" class="form-input editor-wysiwyg" rows="8"
                      placeholder="Descripción detallada del negocio..."><?= $v('descripcion_larga') ?></textarea>
        </div>
    </div>

    <!-- ══ Sección 2: Contacto ══ -->
    <div class="form-card">
        <h3>Contacto</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?= $v('direccion') ?>"
                       class="form-input" maxlength="255">
            </div>
            <div class="form-group">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" value="<?= $v('horario') ?>"
                       class="form-input" maxlength="255" placeholder="Lun-Vie 9:00-18:00">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?= $v('telefono') ?>"
                       class="form-input" maxlength="20">
            </div>
            <div class="form-group">
                <label for="whatsapp">WhatsApp</label>
                <input type="tel" id="whatsapp" name="whatsapp" value="<?= $v('whatsapp') ?>"
                       class="form-input" maxlength="20" placeholder="+56912345678">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $v('email') ?>"
                       class="form-input" maxlength="200">
            </div>
            <div class="form-group">
                <label for="sitio_web">Sitio web</label>
                <input type="url" id="sitio_web" name="sitio_web" value="<?= $v('sitio_web') ?>"
                       class="form-input" maxlength="255" placeholder="https://...">
            </div>
        </div>
    </div>

    <!-- ══ Sección 3: Redes sociales (colapsable) ══ -->
    <details class="form-card">
        <summary style="cursor:pointer; font-weight:600; font-size:1.1em;">Redes sociales</summary>

        <div class="form-row" style="margin-top:1rem;">
            <div class="form-group">
                <label for="facebook">Facebook</label>
                <input type="url" id="facebook" name="facebook" value="<?= $v('facebook') ?>"
                       class="form-input" maxlength="255" placeholder="https://facebook.com/...">
            </div>
            <div class="form-group">
                <label for="instagram">Instagram</label>
                <input type="url" id="instagram" name="instagram" value="<?= $v('instagram') ?>"
                       class="form-input" maxlength="255" placeholder="https://instagram.com/...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tiktok">TikTok</label>
                <input type="url" id="tiktok" name="tiktok" value="<?= $v('tiktok') ?>"
                       class="form-input" maxlength="255" placeholder="https://tiktok.com/@...">
            </div>
            <div class="form-group">
                <label for="youtube">YouTube</label>
                <input type="url" id="youtube" name="youtube" value="<?= $v('youtube') ?>"
                       class="form-input" maxlength="255" placeholder="https://youtube.com/...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="twitter">Twitter / X</label>
                <input type="url" id="twitter" name="twitter" value="<?= $v('twitter') ?>"
                       class="form-input" maxlength="255" placeholder="https://x.com/...">
            </div>
            <div class="form-group">
                <label for="linkedin">LinkedIn</label>
                <input type="url" id="linkedin" name="linkedin" value="<?= $v('linkedin') ?>"
                       class="form-input" maxlength="255" placeholder="https://linkedin.com/company/...">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telegram">Telegram</label>
                <input type="url" id="telegram" name="telegram" value="<?= $v('telegram') ?>"
                       class="form-input" maxlength="255" placeholder="https://t.me/...">
            </div>
            <div class="form-group">
                <label for="pinterest">Pinterest</label>
                <input type="url" id="pinterest" name="pinterest" value="<?= $v('pinterest') ?>"
                       class="form-input" maxlength="255" placeholder="https://pinterest.com/...">
            </div>
        </div>
    </details>

    <!-- ══ Sección 4: Imágenes ══ -->
    <div class="form-card">
        <h3>Imágenes</h3>

        <div class="form-group">
            <label for="foto_principal">Foto principal</label>
            <?php if ($isEdit && !empty($negocio['foto_principal'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['foto_principal']) ?>"
                         alt="Foto principal actual" style="max-width:200px; border-radius:8px;">
                    <br><small>Imagen actual. Sube otra para reemplazar.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="foto_principal" name="foto_principal" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 1200 x 800 px · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>

        <div class="form-group">
            <label for="portada">Portada</label>
            <?php if ($isEdit && !empty($negocio['portada'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['portada']) ?>"
                         alt="Portada actual" style="max-width:200px; border-radius:8px;">
                    <br><small>Imagen actual. Sube otra para reemplazar.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="portada" name="portada" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 1200 x 400 px (panorámica) · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>

        <div class="form-group">
            <label for="logo">Logo</label>
            <?php if ($isEdit && !empty($negocio['logo'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['logo']) ?>"
                         alt="Logo actual" style="max-width:200px; border-radius:8px;">
                    <br><small>Imagen actual. Sube otra para reemplazar.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="logo" name="logo" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 800 x 800 px (cuadrado) · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>
    </div>

    <!-- ══ Sección 5: Ubicación ══ -->
    <div class="form-card">
        <h3>Ubicación</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="lat">Latitud</label>
                <input type="text" id="lat" name="lat" value="<?= $v('lat') ?>"
                       class="form-input" placeholder="-40.9724">
            </div>
            <div class="form-group">
                <label for="lng">Longitud</label>
                <input type="text" id="lng" name="lng" value="<?= $v('lng') ?>"
                       class="form-input" placeholder="-72.8876">
            </div>
        </div>

        <div class="form-group">
            <label for="como_llegar">Cómo llegar</label>
            <textarea id="como_llegar" name="como_llegar" class="form-input" rows="3"
                      placeholder="Indicaciones de acceso..."><?= $v('como_llegar') ?></textarea>
        </div>
    </div>

    <!-- ══ Sección 6: Estado ══ -->
    <div class="form-card">
        <h3>Estado</h3>

        <div class="form-row">
            <label style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="activo" value="1" <?= $checked('activo', true) ?>>
                Activo
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="verificado" value="1" <?= $checked('verificado') ?>>
                Verificado
            </label>
            <label style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="destacado" value="1" <?= $checked('destacado') ?>>
                Destacado
            </label>
        </div>
    </div>

    <!-- ══ Temporadas turísticas ══ -->
    <?php if (!empty($temporadas)): ?>
    <div class="card" style="margin-bottom: 1.5rem;">
        <h3 style="margin-bottom: 0.5rem;">🌤️ Temporadas turísticas</h3>
        <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Selecciona las temporadas en las que este negocio tiene actividad.</p>
        <div style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;">
            <?php foreach ($temporadas as $temp):
                $isChecked = in_array($temp['id'], $negocioTempIds ?? []);
                $promo = $negocioPromociones[$temp['id']] ?? '';
            ?>
            <div style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 0.75rem 1rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.9rem;">
                    <input type="checkbox" name="temporadas[]" value="<?= $temp['id'] ?>"
                        <?= $isChecked ? 'checked' : '' ?>
                        onchange="this.closest('div').querySelector('.promo-field').style.display=this.checked?'block':'none'">
                    <span style="font-size: 1.1rem;"><?= $temp['emoji'] ?></span>
                    <strong><?= htmlspecialchars($temp['nombre']) ?></strong>
                    <?php if ($temp['fecha_inicio'] && $temp['fecha_fin']): ?>
                        <small style="color: var(--text-light);">(<?= date('d/m', strtotime($temp['fecha_inicio'])) ?> — <?= date('d/m', strtotime($temp['fecha_fin'])) ?>)</small>
                    <?php endif; ?>
                </label>
                <div class="promo-field" style="display: <?= $isChecked ? 'block' : 'none' ?>; margin-top: 0.5rem; padding-left: 1.8rem;">
                    <input type="text" name="temporada_promocion[<?= $temp['id'] ?>]" value="<?= htmlspecialchars($promo) ?>" placeholder="Promoción especial (ej: 20% descuento)" style="width: 100%; padding: 0.4rem 0.75rem; border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.85rem;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ══ Sección 7: SEO (colapsable) ══ -->
    <details class="form-card">
        <summary style="cursor:pointer; font-weight:600; font-size:1.1em;">SEO</summary>

        <div class="form-group" style="margin-top:1rem;">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" value="<?= $v('meta_title') ?>"
                   class="form-input" maxlength="60"
                   placeholder="Título para buscadores">
            <small id="meta_title_counter" style="color:#888;">0/60</small>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-input" rows="3"
                      maxlength="160"
                      placeholder="Descripción para buscadores..."><?= $v('meta_description') ?></textarea>
            <small id="meta_desc_counter" style="color:#888;">0/160</small>
        </div>
    </details>

    <!-- ══ Sección 8: Facturación y contrato (colapsable) ══ -->
    <details class="form-card">
        <summary style="cursor:pointer; font-weight:600; font-size:1.1em;">Facturación y contrato</summary>

        <div class="form-row" style="margin-top:1rem;">
            <div class="form-group" style="flex:2">
                <label for="razon_social">Razón social</label>
                <input type="text" id="razon_social" name="razon_social" value="<?= $v('razon_social') ?>"
                       class="form-input" maxlength="255">
            </div>
            <div class="form-group" style="flex:1">
                <label for="rut_empresa">RUT Empresa</label>
                <input type="text" id="rut_empresa" name="rut_empresa" value="<?= $v('rut_empresa') ?>"
                       class="form-input" maxlength="20" placeholder="12.345.678-9">
            </div>
        </div>

        <div class="form-group">
            <label for="giro_comercial">Giro comercial</label>
            <input type="text" id="giro_comercial" name="giro_comercial" value="<?= $v('giro_comercial') ?>"
                   class="form-input" maxlength="255">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="direccion_tributaria">Dirección tributaria</label>
                <input type="text" id="direccion_tributaria" name="direccion_tributaria"
                       value="<?= $v('direccion_tributaria') ?>"
                       class="form-input" maxlength="255">
            </div>
            <div class="form-group">
                <label for="comuna_tributaria">Comuna tributaria</label>
                <input type="text" id="comuna_tributaria" name="comuna_tributaria"
                       value="<?= $v('comuna_tributaria') ?>"
                       class="form-input" maxlength="100">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nombre_propietario">Nombre propietario</label>
                <input type="text" id="nombre_propietario" name="nombre_propietario"
                       value="<?= $v('nombre_propietario') ?>"
                       class="form-input" maxlength="200">
            </div>
            <div class="form-group">
                <label for="rut_propietario">RUT propietario</label>
                <input type="text" id="rut_propietario" name="rut_propietario"
                       value="<?= $v('rut_propietario') ?>"
                       class="form-input" maxlength="20" placeholder="12.345.678-9">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono_privado">Teléfono privado</label>
                <input type="tel" id="telefono_privado" name="telefono_privado"
                       value="<?= $v('telefono_privado') ?>"
                       class="form-input" maxlength="20">
            </div>
            <div class="form-group">
                <label for="email_facturacion">Email facturación</label>
                <input type="email" id="email_facturacion" name="email_facturacion"
                       value="<?= $v('email_facturacion') ?>"
                       class="form-input" maxlength="200">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio_contrato">Fecha inicio contrato</label>
                <input type="date" id="fecha_inicio_contrato" name="fecha_inicio_contrato"
                       value="<?= $v('fecha_inicio_contrato') ?>"
                       class="form-input">
            </div>
            <div class="form-group">
                <label for="monto_mensual">Monto mensual ($)</label>
                <input type="number" id="monto_mensual" name="monto_mensual"
                       value="<?= $v('monto_mensual') ?>"
                       class="form-input" min="0" placeholder="0">
            </div>
            <div class="form-group">
                <label for="metodo_pago">Método de pago</label>
                <select id="metodo_pago" name="metodo_pago" class="form-input">
                    <option value="">Seleccionar...</option>
                    <?php foreach (['Transferencia', 'Efectivo', 'Débito', 'Crédito'] as $mp): ?>
                    <option value="<?= $mp ?>" <?= (($negocio['metodo_pago'] ?? '') === $mp) ? 'selected' : '' ?>><?= $mp ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </details>

    <!-- ══ Botones ══ -->
    <div style="margin-top:1.5rem; display:flex; gap:1rem;">
        <button type="submit" class="btn btn-primary">Guardar negocio</button>
        <a href="<?= SITE_URL ?>/admin/negocios" class="btn">Cancelar</a>
    </div>
</form>

<script>
// Char counter helper
function charCounter(inputId, counterId, max) {
    var input = document.getElementById(inputId);
    var counter = document.getElementById(counterId);
    if (!input || !counter) return;
    function update() {
        var len = input.value.length;
        counter.textContent = len + '/' + max;
        counter.style.color = len > max * 0.9 ? '#c00' : '#888';
    }
    input.addEventListener('input', update);
    update();
}
charCounter('descripcion_corta', 'desc_corta_counter', 300);
charCounter('meta_title', 'meta_title_counter', 60);
charCounter('meta_description', 'meta_desc_counter', 160);
</script>
