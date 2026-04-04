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
    <form method="POST" action="<?= SITE_URL ?>/admin/textos-legales/<?= $pagina['id'] ?>/actualizar">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Documento</label>
            <input type="text" value="<?= htmlspecialchars($pagina['titulo']) ?>" disabled style="background:#f0f0f0;">
            <small style="color:#888;">URL: <code>/<?= htmlspecialchars($pagina['slug']) ?></code></small>
        </div>

        <div class="form-group">
            <label for="contenido">Contenido (HTML)</label>
            <textarea id="contenido" name="contenido" class="editor-wysiwyg" rows="20"
                      style="font-family:monospace; font-size:0.85rem;"
            ><?= htmlspecialchars($pagina['contenido'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="meta_title">Meta Title (SEO)</label>
                <input type="text" id="meta_title" name="meta_title"
                       value="<?= htmlspecialchars($pagina['meta_title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="meta_description">Meta Description (SEO)</label>
                <input type="text" id="meta_description" name="meta_description"
                       value="<?= htmlspecialchars($pagina['meta_description'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="activo" value="1"
                    <?= !empty($pagina['activo']) ? 'checked' : '' ?>>
                Texto visible públicamente
            </label>
        </div>

        <div style="margin-top:1.5rem; display:flex; gap:0.8rem;">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="<?= SITE_URL ?>/admin/textos-legales" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
