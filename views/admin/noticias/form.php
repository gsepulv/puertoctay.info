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

    <!-- ══ SEO ══ -->
    <details class="form-card" style="margin-bottom:1.5rem;">
        <summary style="cursor:pointer; font-weight:600; font-size:1.1em;">SEO</summary>

        <div class="form-group" style="margin-top:1rem;">
            <label for="meta_titulo">Meta título</label>
            <input type="text" id="meta_titulo" name="meta_titulo"
                   value="<?= htmlspecialchars($noticia['meta_titulo'] ?? '') ?>"
                   maxlength="60"
                   placeholder="Si queda vacío se usa el título de la noticia">
            <small id="meta_titulo_counter" style="color:#888;">0/60</small>
        </div>

        <div class="form-group">
            <label for="meta_descripcion">Meta descripción</label>
            <textarea id="meta_descripcion" name="meta_descripcion" rows="3"
                      maxlength="160"
                      placeholder="Si queda vacío se usa la bajada"><?= htmlspecialchars($noticia['meta_descripcion'] ?? '') ?></textarea>
            <small id="meta_desc_counter" style="color:#888;">0/160</small>
        </div>

        <div class="form-group">
            <label for="keywords">Palabras clave</label>
            <input type="text" id="keywords" name="keywords"
                   value="<?= htmlspecialchars($noticia['keywords'] ?? '') ?>"
                   maxlength="255"
                   placeholder="Separadas por coma">
        </div>

        <div class="form-group">
            <label for="slug">Slug / URL amigable</label>
            <input type="text" id="slug" name="slug"
                   value="<?= htmlspecialchars($noticia['slug'] ?? '') ?>"
                   maxlength="200"
                   placeholder="Se genera automáticamente desde el título">
            <small style="color:#888;">URL: <?= SITE_URL ?>/noticias/<span id="slug_preview"><?= htmlspecialchars($noticia['slug'] ?? '...') ?></span></small>
        </div>
    </details>

    <div style="display:flex; gap:1rem; align-items:center;">
        <button type="submit" class="btn btn-primary"><?= $esEdicion ? 'Guardar cambios' : 'Crear noticia' ?></button>
        <a href="<?= SITE_URL ?>/admin/noticias" style="color:#888;">Cancelar</a>
    </div>
</form>

<script>
// Contadores SEO
function seoCounter(inputId, counterId, max) {
    var el = document.getElementById(inputId);
    var counter = document.getElementById(counterId);
    if (!el || !counter) return;
    function update() {
        var len = el.value.length;
        counter.textContent = len + '/' + max;
        counter.style.color = len > max * 0.9 ? '#c00' : '#888';
    }
    el.addEventListener('input', update);
    update();
}
seoCounter('meta_titulo', 'meta_titulo_counter', 60);
seoCounter('meta_descripcion', 'meta_desc_counter', 160);

// Auto-generar slug desde título
var tituloEl = document.getElementById('titulo');
var slugEl = document.getElementById('slug');
var slugPreview = document.getElementById('slug_preview');
var slugManual = false;

if (slugEl) {
    slugEl.addEventListener('input', function() {
        slugManual = this.value !== '';
        if (slugPreview) slugPreview.textContent = this.value || '...';
    });
}
if (tituloEl && slugEl) {
    tituloEl.addEventListener('input', function() {
        if (slugManual) return;
        var s = this.value.toLowerCase()
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '')
            .substring(0, 200);
        slugEl.value = s;
        if (slugPreview) slugPreview.textContent = s || '...';
    });
}
</script>
