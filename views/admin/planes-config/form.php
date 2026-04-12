<?php
$action = SITE_URL . '/admin/planes-config/' . $plan['id'] . '/actualizar';
?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>

        <!-- IDENTIFICACIÓN -->
        <div class="dash-section" style="margin-bottom:1.5rem;">
            <h3>📌 Identificación</h3>
            <div class="form-row" style="margin-top:0.8rem;">
                <div class="form-group">
                    <label for="slug">Slug (identificador único)</label>
                    <input type="text" id="slug" value="<?= htmlspecialchars($plan['slug']) ?>" disabled
                           style="background:#f0f2f5; cursor:not-allowed;">
                    <small style="color:#888;">No se puede cambiar — es el identificador interno del plan.</small>
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre visible *</label>
                    <input type="text" id="nombre" name="nombre" required minlength="3" maxlength="50"
                           value="<?= htmlspecialchars($plan['nombre'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="icono">Icono (emoji)</label>
                    <input type="text" id="icono" name="icono" maxlength="10"
                           value="<?= htmlspecialchars($plan['icono'] ?? '') ?>"
                           placeholder="Ej: ⭐ 💎 👑">
                </div>
                <div class="form-group">
                    <label for="color">Color del plan</label>
                    <div style="display:flex; gap:0.5rem; align-items:center;">
                        <input type="color" id="color" name="color"
                               value="<?= htmlspecialchars($plan['color'] ?? '#6b7280') ?>"
                               style="width:50px; height:38px; padding:2px; cursor:pointer;">
                        <input type="text" id="color_text" maxlength="7"
                               value="<?= htmlspecialchars($plan['color'] ?? '#6b7280') ?>"
                               style="width:100px; font-family:monospace;"
                               readonly>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="orden">Orden de visualización *</label>
                    <input type="number" id="orden" name="orden" min="1" max="10" required
                           value="<?= htmlspecialchars($plan['orden'] ?? '1') ?>">
                    <small style="color:#888;">Menor número = aparece primero.</small>
                </div>
                <div></div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <textarea id="descripcion" name="descripcion" rows="3" required minlength="10" maxlength="500"
                          placeholder="Describe los beneficios del plan (10-500 caracteres)."
                ><?= htmlspecialchars($plan['descripcion'] ?? '') ?></textarea>
                <small style="color:#888;">
                    <span id="desc-count"><?= mb_strlen($plan['descripcion'] ?? '') ?></span>/500 caracteres
                </small>
            </div>
        </div>

        <!-- PRECIOS -->
        <div class="dash-section" style="margin-bottom:1.5rem;">
            <h3>💰 Precios (CLP mensual)</h3>
            <div class="form-row" style="margin-top:0.8rem;">
                <div class="form-group">
                    <label for="precio_intro">Precio introductorio *</label>
                    <input type="number" id="precio_intro" name="precio_intro" min="0" required
                           value="<?= htmlspecialchars($plan['precio_intro'] ?? '0') ?>">
                    <small style="color:#888;">Primeros meses post-Beta. 0 = gratuito.</small>
                </div>
                <div class="form-group">
                    <label for="precio_regular">Precio regular *</label>
                    <input type="number" id="precio_regular" name="precio_regular" min="0" required
                           value="<?= htmlspecialchars($plan['precio_regular'] ?? '0') ?>">
                    <small style="color:#888;">Precio definitivo.</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="duracion_dias">Duración del plan (días) *</label>
                    <input type="number" id="duracion_dias" name="duracion_dias" min="1" max="365" required
                           value="<?= htmlspecialchars($plan['duracion_dias'] ?? '30') ?>">
                    <small style="color:#888;">Por defecto 30 días.</small>
                </div>
                <div></div>
            </div>
        </div>

        <!-- LÍMITES -->
        <div class="dash-section" style="margin-bottom:1.5rem;">
            <h3>📏 Límites <small style="font-weight:400; color:#888;">(vacío = sin límite)</small></h3>
            <div class="form-row" style="margin-top:0.8rem;">
                <div class="form-group">
                    <label for="max_fotos">Máximo de fotos</label>
                    <input type="number" id="max_fotos" name="max_fotos" min="1"
                           value="<?= $plan['max_fotos'] !== null ? htmlspecialchars($plan['max_fotos']) : '' ?>"
                           placeholder="Vacío = sin límite">
                </div>
                <div class="form-group">
                    <label for="max_redes">Máximo de redes sociales</label>
                    <input type="number" id="max_redes" name="max_redes" min="1" max="99"
                           value="<?= $plan['max_redes'] !== null ? htmlspecialchars($plan['max_redes']) : '' ?>"
                           placeholder="Vacío = sin límite">
                    <small style="color:#888;">99 = todas las redes.</small>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="cupos_globales">Cupos globales</label>
                    <input type="number" id="cupos_globales" name="cupos_globales" min="1"
                           value="<?= $plan['cupos_globales'] !== null ? htmlspecialchars($plan['cupos_globales']) : '' ?>"
                           placeholder="Vacío = ilimitado">
                </div>
                <div class="form-group">
                    <label for="max_cupos_categoria">Máx. cupos por categoría</label>
                    <input type="number" id="max_cupos_categoria" name="max_cupos_categoria" min="1"
                           value="<?= $plan['max_cupos_categoria'] !== null ? htmlspecialchars($plan['max_cupos_categoria']) : '' ?>"
                           placeholder="Vacío = sin límite">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="posicion_listado">Posición en listados *</label>
                    <select id="posicion_listado" name="posicion_listado" required>
                        <option value="normal" <?= ($plan['posicion_listado'] ?? '') === 'normal' ? 'selected' : '' ?>>Normal</option>
                        <option value="prioritaria" <?= ($plan['posicion_listado'] ?? '') === 'prioritaria' ? 'selected' : '' ?>>Prioritaria</option>
                        <option value="siempre_primero" <?= ($plan['posicion_listado'] ?? '') === 'siempre_primero' ? 'selected' : '' ?>>SIEMPRE PRIMERO</option>
                    </select>
                </div>
                <div></div>
            </div>
        </div>

        <!-- CARACTERÍSTICAS -->
        <div class="dash-section" style="margin-bottom:1.5rem;">
            <h3>✨ Características incluidas</h3>
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:0.8rem; margin-top:0.5rem;">
                <label>
                    <input type="checkbox" name="tiene_mapa" value="1"
                        <?= !empty($plan['tiene_mapa']) ? 'checked' : '' ?>>
                    Mapa integrado en ficha
                </label>
                <label>
                    <input type="checkbox" name="tiene_horarios" value="1"
                        <?= !empty($plan['tiene_horarios']) ? 'checked' : '' ?>>
                    Horarios de atención
                </label>
                <label>
                    <input type="checkbox" name="tiene_sello" value="1"
                        <?= !empty($plan['tiene_sello']) ? 'checked' : '' ?>>
                    Sello verificado del plan
                </label>
                <label>
                    <input type="checkbox" name="tiene_reporte" value="1"
                        <?= !empty($plan['tiene_reporte']) ? 'checked' : '' ?>>
                    Reporte mensual de visitas
                </label>
            </div>
        </div>

        <!-- ESTADO -->
        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" value="1"
                    <?= !empty($plan['activo']) ? 'checked' : '' ?>>
                Plan activo <small style="color:#888;">— Visible para asignar a comercios</small>
            </label>
        </div>

        <div style="margin-top:1.5rem; display:flex; gap:0.8rem;">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="<?= SITE_URL ?>/admin/planes-config" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.getElementById('descripcion').addEventListener('input', function() {
    document.getElementById('desc-count').textContent = this.value.length;
});
document.getElementById('color').addEventListener('input', function() {
    document.getElementById('color_text').value = this.value;
});
</script>
