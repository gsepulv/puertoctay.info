<?php
$esEdicion = !empty($pagina['id']);
$titulo = $esEdicion ? $pagina['titulo'] : '';
$action = $esEdicion
    ? SITE_URL . '/admin/paginas/' . $pagina['id'] . '/actualizar'
    : SITE_URL . '/admin/paginas/guardar';
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
                <label for="titulo">Título *</label>
                <input type="text" id="titulo" name="titulo"
                       value="<?= htmlspecialchars($pagina['titulo'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="orden">Orden</label>
                <input type="number" id="orden" name="orden" min="0"
                       value="<?= htmlspecialchars($pagina['orden'] ?? '0') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="contenido">Contenido (HTML)</label>
            <textarea id="contenido" name="contenido" class="editor-wysiwyg" rows="15"
                      style="font-family:monospace; font-size:0.85rem;"
            ><?= htmlspecialchars($pagina['contenido'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="meta_title">Meta Title (SEO)</label>
                <input type="text" id="meta_title" name="meta_title"
                       value="<?= htmlspecialchars($pagina['meta_title'] ?? '') ?>">
                <small style="color:#888;">Deja vacío para usar el título de la página.</small>
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
                    <?= (!$esEdicion || !empty($pagina['activo'])) ? 'checked' : '' ?>>
                Página activa (visible en el sitio)
            </label>
        </div>

        <div style="margin-top:1.5rem; display:flex; gap:0.8rem;">
            <button type="submit" class="btn btn-primary">
                <?= $esEdicion ? 'Actualizar Página' : 'Crear Página' ?>
            </button>
            <a href="<?= SITE_URL ?>/admin/paginas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
