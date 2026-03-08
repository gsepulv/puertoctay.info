<?php $esEdicion = !empty($categoria['id']); ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= SITE_URL ?>/admin/categorias/<?= $esEdicion ? $categoria['id'] . '/actualizar' : 'guardar' ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-card" style="max-width:600px;">
        <div class="form-row">
            <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="emoji">Emoji</label>
                <input type="text" id="emoji" name="emoji" value="<?= htmlspecialchars($categoria['emoji'] ?? '') ?>" maxlength="10" style="font-size:1.5rem;">
            </div>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($categoria['descripcion'] ?? '') ?>" maxlength="300">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo">
                    <option value="directorio" <?= ($categoria['tipo'] ?? '') === 'directorio' ? 'selected' : '' ?>>Directorio</option>
                    <option value="editorial" <?= ($categoria['tipo'] ?? '') === 'editorial' ? 'selected' : '' ?>>Editorial</option>
                </select>
            </div>
            <div class="form-group">
                <label for="orden">Orden</label>
                <input type="number" id="orden" name="orden" value="<?= (int)($categoria['orden'] ?? 0) ?>" min="0">
            </div>
        </div>

        <?php if ($esEdicion): ?>
        <div class="form-group">
            <label><input type="checkbox" name="activo" value="1" <?= ($categoria['activo'] ?? 1) ? 'checked' : '' ?>> Activa</label>
        </div>
        <?php endif; ?>

        <div style="display:flex; gap:1rem; align-items:center; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear categoría' ?></button>
            <a href="<?= SITE_URL ?>/admin/categorias" style="color:#888;">Cancelar</a>
        </div>
    </div>
</form>
