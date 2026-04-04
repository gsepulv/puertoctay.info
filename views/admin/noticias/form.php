<?php $esEdicion = !empty($noticia['id']); ?>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.2rem;">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= SITE_URL ?>/admin/noticias/<?= $esEdicion ? $noticia['id'] . '/actualizar' : 'guardar' ?>"
      method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Contenido</h3>

        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($noticia['titulo'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="bajada">Bajada / subtítulo</label>
            <input type="text" id="bajada" name="bajada" value="<?= htmlspecialchars($noticia['bajada'] ?? '') ?>" maxlength="350" placeholder="Resumen breve de la noticia">
        </div>

        <div class="form-group">
            <label for="contenido">Contenido</label>
            <textarea id="contenido" name="contenido" class="editor-wysiwyg" rows="12"><?= htmlspecialchars($noticia['contenido'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Clasificación y autoría</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id">Categoría editorial</label>
                <select id="categoria_id" name="categoria_id">
                    <option value="">Sin categoría</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($noticia['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= $cat['emoji'] ?> <?= htmlspecialchars($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="autor">Autor</label>
                <input type="text" id="autor" name="autor" value="<?= htmlspecialchars($noticia['autor'] ?? '') ?>" placeholder="Nombre del autor">
            </div>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Publicación</h3>

        <div class="form-row">
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <?php foreach (['borrador' => 'Borrador', 'revision' => 'En revisión', 'publicado' => 'Publicado', 'archivado' => 'Archivado'] as $val => $label): ?>
                        <option value="<?= $val ?>" <?= ($noticia['estado'] ?? 'borrador') === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="publicado_en">Fecha de publicación</label>
                <input type="datetime-local" id="publicado_en" name="publicado_en"
                       value="<?= !empty($noticia['publicado_en']) ? date('Y-m-d\TH:i', strtotime($noticia['publicado_en'])) : '' ?>"
                       placeholder="Dejar vacío para publicar ahora">
                <small style="color:#888;">Dejar vacío para publicar de inmediato al cambiar estado a Publicado.</small>
            </div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="featured" value="1" <?= ($noticia['featured'] ?? 0) ? 'checked' : '' ?>>
                Marcar como noticia destacada
            </label>
        </div>
    </div>

    <div class="form-card" style="margin-bottom:1.5rem;">
        <h3 style="margin-bottom:1rem;">Imagen</h3>

        <div class="form-group">
            <label for="foto_destacada">Foto destacada</label>
            <input type="file" id="foto_destacada" name="foto_destacada" accept="image/jpeg,image/png,image/webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 1200 x 630 px · Máx. 2 MB · JPG, PNG o WebP</small>
            <?php if (!empty($noticia['foto_destacada'])): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="Foto actual" style="max-width:300px; margin-top:0.5rem; border-radius:6px;">
            <?php endif; ?>
        </div>
    </div>

    <div style="display:flex; gap:1rem; align-items:center;">
        <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear noticia' ?></button>
        <a href="<?= SITE_URL ?>/admin/noticias" style="color:#888;">Cancelar</a>
    </div>
</form>
