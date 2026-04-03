<h1 style="margin-bottom: 2rem;">Editar Negocio</h1>

<form method="POST" action="<?= SITE_URL ?>/mi-comercio/editar" class="card">
    <?= csrf_field() ?>

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
        <textarea name="descripcion_larga" rows="6"><?= htmlspecialchars($negocio['descripcion_larga'] ?? '') ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Dirección</label>
            <input type="text" name="direccion" value="<?= htmlspecialchars($negocio['direccion'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" value="<?= htmlspecialchars($negocio['telefono'] ?? '') ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>WhatsApp</label>
            <input type="text" name="whatsapp" value="<?= htmlspecialchars($negocio['whatsapp'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($negocio['email'] ?? '') ?>">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label>Sitio web</label>
            <input type="url" name="sitio_web" value="<?= htmlspecialchars($negocio['sitio_web'] ?? '') ?>" placeholder="https://...">
        </div>
        <div class="form-group">
            <label>Horario</label>
            <input type="text" name="horario" value="<?= htmlspecialchars($negocio['horario'] ?? '') ?>">
        </div>
    </div>

    <h3 style="margin: 1.5rem 0 1rem; padding-top: 1rem; border-top: 1px solid var(--border);">Redes Sociales</h3>

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

    <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">Guardar cambios</button>
</form>
