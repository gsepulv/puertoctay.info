<?php
$esEdicion = !empty($plan['id']);
$action = $esEdicion
    ? SITE_URL . '/admin/planes/' . $plan['id'] . '/actualizar'
    : SITE_URL . '/admin/planes/guardar';
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

        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre del plan *</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?= htmlspecialchars($plan['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio (CLP, sin decimales) *</label>
                <input type="number" id="precio" name="precio" min="0"
                       value="<?= htmlspecialchars($plan['precio'] ?? '0') ?>" required>
                <small style="color:#888;">0 = Plan gratuito</small>
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción / Features</label>
            <textarea id="descripcion" name="descripcion" rows="4"
                      placeholder="Describe los beneficios del plan. Se muestra en la página pública."
            ><?= htmlspecialchars($plan['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="max_fotos">Máximo de fotos</label>
                <input type="number" id="max_fotos" name="max_fotos" min="0"
                       value="<?= htmlspecialchars($plan['max_fotos'] ?? '1') ?>">
            </div>
            <div class="form-group">
                <label for="prioridad">Prioridad (orden de visualización)</label>
                <input type="number" id="prioridad" name="prioridad" min="0"
                       value="<?= htmlspecialchars($plan['prioridad'] ?? '0') ?>">
                <small style="color:#888;">Menor número = aparece primero</small>
            </div>
            <div class="form-group">
                <label for="max_cupos">Máximo de cupos</label>
                <input type="number" id="max_cupos" name="max_cupos" min="0"
                       value="<?= htmlspecialchars($plan['max_cupos'] ?? '') ?>"
                       placeholder="Vacío = ilimitado">
            </div>
        </div>

        <div class="dash-section" style="margin-top:1rem;">
            <h3>Beneficios incluidos</h3>
            <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap:0.8rem; margin-top:0.5rem;">
                <label>
                    <input type="checkbox" name="badge" value="1"
                        <?= !empty($plan['badge']) ? 'checked' : '' ?>>
                    Badge destacado (sello visual en el negocio)
                </label>
                <label>
                    <input type="checkbox" name="estadisticas" value="1"
                        <?= !empty($plan['estadisticas']) ? 'checked' : '' ?>>
                    Acceso a estadísticas de visitas
                </label>
                <label>
                    <input type="checkbox" name="noticia_mensual" value="1"
                        <?= !empty($plan['noticia_mensual']) ? 'checked' : '' ?>>
                    Noticia mensual incluida
                </label>
                <label>
                    <input type="checkbox" name="banner_portada" value="1"
                        <?= !empty($plan['banner_portada']) ? 'checked' : '' ?>>
                    Banner en portada
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top:1rem;">
            <label>
                <input type="checkbox" name="activo" value="1"
                    <?= (!$esEdicion || !empty($plan['activo'])) ? 'checked' : '' ?>>
                Plan activo (visible en la página pública)
            </label>
        </div>

        <div style="margin-top:1.5rem; display:flex; gap:0.8rem;">
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar Plan' : 'Crear Plan' ?>
            </button>
            <a href="<?= SITE_URL ?>/admin/planes" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
