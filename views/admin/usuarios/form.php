<?php $esEdicion = !empty($usuario['id']); ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= SITE_URL ?>/admin/usuarios/<?= $esEdicion ? $usuario['id'] . '/actualizar' : 'guardar' ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-card" style="max-width:600px;">
        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Contraseña <?= $esEdicion ? '(dejar vacío para no cambiar)' : '*' ?></label>
            <input type="password" id="password" name="password" <?= $esEdicion ? '' : 'required' ?>>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <?php foreach (['admin' => 'Administrador', 'editor' => 'Editor', 'moderador' => 'Moderador'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($usuario['rol'] ?? 'editor') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if ($esEdicion): ?>
            <div class="form-group">
                <label>&nbsp;</label>
                <label><input type="checkbox" name="activo" value="1" <?= ($usuario['activo'] ?? 1) ? 'checked' : '' ?>> Activo</label>
            </div>
            <?php endif; ?>
        </div>

        <div style="display:flex; gap:1rem; align-items:center; margin-top:1rem;">
            <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear usuario' ?></button>
            <a href="<?= SITE_URL ?>/admin/usuarios" style="color:#888;">Cancelar</a>
        </div>
    </div>
</form>
