<?php $esEdicion = !empty($evento['id']); ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= SITE_URL ?>/admin/eventos/<?= $esEdicion ? $evento['id'] . '/actualizar' : 'guardar' ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Información del evento</h3>

        <div class="form-group">
            <label for="nombre">Nombre *</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($evento['nombre'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="editor-wysiwyg" rows="5"><?= htmlspecialchars($evento['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="fecha_inicio">Fecha inicio *</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($evento['fecha_inicio'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin">Fecha fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="<?= htmlspecialchars($evento['fecha_fin'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="hora">Hora</label>
                <input type="text" id="hora" name="hora" value="<?= htmlspecialchars($evento['hora'] ?? '') ?>" placeholder="19:00 hrs">
            </div>
            <div class="form-group">
                <label for="lugar">Lugar</label>
                <input type="text" id="lugar" name="lugar" value="<?= htmlspecialchars($evento['lugar'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="text" id="precio" name="precio" value="<?= htmlspecialchars($evento['precio'] ?? 'Gratuito') ?>">
            </div>
            <div class="form-group">
                <label for="organizador">Organizador</label>
                <input type="text" id="organizador" name="organizador" value="<?= htmlspecialchars($evento['organizador'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="lat">Latitud</label>
                <input type="text" id="lat" name="lat" value="<?= htmlspecialchars($evento['lat'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="lng">Longitud</label>
                <input type="text" id="lng" name="lng" value="<?= htmlspecialchars($evento['lng'] ?? '') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <?php foreach (['borrador' => 'Borrador', 'publicado' => 'Publicado', 'finalizado' => 'Finalizado'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($evento['estado'] ?? 'borrador') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp">
                <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 1200 x 630 px · Máx. 2 MB · JPG, PNG o WebP</small>
                <?php if (!empty($evento['foto'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($evento['foto']) ?>" alt="Foto actual" style="max-width:200px; margin-top:0.5rem; border-radius:6px;">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1rem; align-items:center;">
        <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear evento' ?></button>
        <a href="<?= SITE_URL ?>/admin/eventos" style="color:#888;">Cancelar</a>
    </div>
</form>
