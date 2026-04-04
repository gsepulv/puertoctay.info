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

<p style="color:var(--text-light);margin-bottom:1.5rem;">Configura el banner principal de la página de inicio.</p>

<form method="POST" action="<?= SITE_URL ?>/admin/hero" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card">
        <h3>Contenido</h3>

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

    <div class="form-card">
        <h3>Imagen de fondo</h3>

        <div class="form-group">
            <label for="imagen">Imagen</label>
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
    </div>

    <div style="margin-top:1.5rem;">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
</form>
