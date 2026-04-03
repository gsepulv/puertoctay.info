<div style="margin-bottom: 1.5rem;">
    <a href="<?= SITE_URL ?>/admin/temporadas" style="color: var(--text-light); text-decoration: none; font-size: 0.85rem;">&larr; Volver a temporadas</a>
    <h1 style="margin-top: 0.5rem;"><?= $temporada ? 'Editar' : 'Crear' ?> Temporada</h1>
</div>

<form method="POST" action="<?= SITE_URL ?>/admin/temporadas/<?= $temporada ? $temporada['id'] . '/actualizar' : 'guardar' ?>" class="card">
    <?= csrf_field() ?>

    <div class="form-row">
        <div class="form-group" style="flex: 1;">
            <label>Nombre <span style="color:#DC2626">*</span></label>
            <input type="text" name="nombre" value="<?= htmlspecialchars($temporada['nombre'] ?? '') ?>" required placeholder="Ej: Temporada de Verano">
        </div>
        <div class="form-group" style="width: 100px;">
            <label>Emoji</label>
            <input type="text" name="emoji" value="<?= $temporada['emoji'] ?? '' ?>" placeholder="&#x2600;&#xFE0F;" style="font-size: 1.3rem; text-align: center;">
        </div>
        <div class="form-group" style="width: 80px;">
            <label>Orden</label>
            <input type="number" name="orden" value="<?= (int) ($temporada['orden'] ?? 0) ?>" min="0">
        </div>
    </div>

    <div class="form-group">
        <label>Descripción</label>
        <textarea name="descripcion" rows="3" placeholder="Describe brevemente esta temporada turística..."><?= htmlspecialchars($temporada['descripcion'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Fecha inicio</label>
            <input type="date" name="fecha_inicio" value="<?= $temporada['fecha_inicio'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Fecha fin</label>
            <input type="date" name="fecha_fin" value="<?= $temporada['fecha_fin'] ?? '' ?>">
        </div>
    </div>

    <div class="form-group" style="margin-top: 0.5rem;">
        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-weight: normal;">
            <input type="checkbox" name="activa" value="1" <?= ($temporada['activa'] ?? 1) ? 'checked' : '' ?>>
            Temporada activa (visible en el sitio)
        </label>
    </div>

    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
        <button type="submit" class="btn btn-primary"><?= $temporada ? 'Guardar cambios' : 'Crear temporada' ?></button>
        <a href="<?= SITE_URL ?>/admin/temporadas" class="btn btn-outline">Cancelar</a>
    </div>
</form>
