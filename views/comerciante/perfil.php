<h1 style="margin-bottom: 2rem;">Mi Perfil</h1>

<form method="POST" action="<?= SITE_URL ?>/mi-comercio/perfil" class="card">
    <?= csrf_field() ?>

    <div class="form-group">
        <label>Nombre completo <span style="color:#DC2626">*</span></label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required minlength="3">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
        <small>El email no se puede cambiar</small>
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
    </div>

    <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">Cambiar contraseña</h3>
    <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 1rem;">Deja en blanco si no deseas cambiarla.</p>

    <div class="form-row">
        <div class="form-group">
            <label>Nueva contraseña</label>
            <input type="password" name="password" placeholder="Mínimo 8 caracteres" minlength="8">
        </div>
        <div class="form-group">
            <label>Confirmar</label>
            <input type="password" name="password_confirm" placeholder="Repite la contraseña">
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Guardar cambios</button>
</form>
