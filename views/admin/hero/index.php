<?php
$v = fn(string $key, string $default = '') => htmlspecialchars($hero[$key] ?? $default, ENT_QUOTES, 'UTF-8');
?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="admin-page-header">
    <h1>Hero de la Home</h1>
</div>

<p style="color:var(--text-light);margin-bottom:1.5rem;">Configura el banner principal y los meta tags SEO de la página de inicio.</p>

<form method="POST" action="<?= SITE_URL ?>/admin/hero" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <!-- ══ Sección 1: Contenido ══ -->
    <div class="form-card">
        <h3>Contenido del Hero</h3>

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" value="<?= $v('titulo') ?>"
                   class="form-input" maxlength="200" placeholder="Ej: Descubre Puerto Octay">
        </div>

        <div class="form-group">
            <label for="subtitulo">Subtítulo</label>
            <input type="text" id="subtitulo" name="subtitulo" value="<?= $v('subtitulo') ?>"
                   class="form-input" maxlength="300" placeholder="Texto secundario">
        </div>

        <div class="form-group">
            <label for="imagen">Imagen de fondo</label>
            <?php if (!empty($hero['imagen'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($hero['imagen']) ?>"
                         alt="Imagen actual" style="max-width:500px;width:100%;border-radius:8px;">
                    <br><small>Imagen actual. Sube otra para reemplazar.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="imagen" name="imagen" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;">Recomendado: 1920 x 800 px · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="texto_boton">Texto del botón</label>
                <input type="text" id="texto_boton" name="texto_boton" value="<?= $v('texto_boton') ?>"
                       class="form-input" maxlength="50" placeholder="Ej: Ver directorio">
            </div>
            <div class="form-group">
                <label for="url_boton">URL del botón</label>
                <input type="text" id="url_boton" name="url_boton" value="<?= $v('url_boton') ?>"
                       class="form-input" maxlength="255" placeholder="/directorio">
            </div>
        </div>
    </div>

    <!-- ══ Sección 2: SEO ══ -->
    <div class="form-card">
        <h3>SEO y Open Graph</h3>
        <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:1rem;">Estos campos controlan cómo se muestra la home en buscadores y al compartir en redes sociales.</p>

        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" value="<?= $v('meta_title') ?>"
                   class="form-input" maxlength="70" placeholder="Título para Google (máx. 70 caracteres)">
            <small style="color:var(--text-lighter);">Si está vacío se usa el título por defecto del sitio.</small>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-input" rows="2"
                      maxlength="160" placeholder="Descripción para Google (máx. 160 caracteres)"><?= $v('meta_description') ?></textarea>
        </div>

        <div class="form-group">
            <label for="og_title">og:title (redes sociales)</label>
            <input type="text" id="og_title" name="og_title" value="<?= $v('og_title') ?>"
                   class="form-input" maxlength="100" placeholder="Título al compartir. Si está vacío usa Meta Title.">
        </div>

        <div class="form-group">
            <label for="og_description">og:description (redes sociales)</label>
            <textarea id="og_description" name="og_description" class="form-input" rows="2"
                      maxlength="200" placeholder="Descripción al compartir. Si está vacío usa Meta Description."><?= $v('og_description') ?></textarea>
        </div>

        <div class="form-group">
            <label for="og_image_file">og:image (imagen al compartir)</label>
            <?php if (!empty($hero['og_image'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($hero['og_image']) ?>"
                         alt="OG Image actual" style="max-width:300px;border-radius:8px;">
                    <br><small>Imagen actual para redes sociales.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="og_image_file" name="og_image_file" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;">Recomendado: 1200 x 630 px · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
</form>
