<?php
$isEdit = !empty($slide) && isset($slide['id']);
$v = fn(string $key, string $default = '') => htmlspecialchars($slide[$key] ?? $default, ENT_QUOTES, 'UTF-8');
$checked = fn(string $key, bool $defaultOn = false) =>
    $isEdit ? (!empty($slide[$key]) ? 'checked' : '') : ($defaultOn ? 'checked' : '');
?>

<div class="admin-page-header">
    <h1><?= $isEdit ? 'Editar Hero' : 'Nuevo Hero' ?></h1>
    <p class="admin-page-subtitle">
        <a href="<?= SITE_URL ?>/admin/hero">&larr; Volver al listado</a>
    </p>
</div>

<?php if (!empty($errores)): ?>
<div class="alert alert-danger">
    <ul style="margin:0;padding-left:1.2em;">
        <?php foreach ($errores as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form method="POST"
      action="<?= $isEdit ? SITE_URL . '/admin/hero/' . $slide['id'] . '/actualizar' : SITE_URL . '/admin/hero/guardar' ?>"
      enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card">
        <h3>Contenido</h3>

        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" id="titulo" name="titulo" value="<?= $v('titulo') ?>"
                   class="form-input" required maxlength="200" placeholder="Ej: Descubre Puerto Octay">
        </div>

        <div class="form-group">
            <label for="subtitulo">Subtítulo</label>
            <input type="text" id="subtitulo" name="subtitulo" value="<?= $v('subtitulo') ?>"
                   class="form-input" maxlength="300" placeholder="Texto secundario debajo del título">
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
            <?php if ($isEdit && !empty($slide['imagen'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($slide['imagen']) ?>"
                         alt="Imagen actual" style="max-width:400px;border-radius:8px;width:100%;">
                    <br><small>Imagen actual. Sube otra para reemplazar.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="imagen" name="imagen" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;line-height:1.5;">Recomendado: 1920 x 800 px (panorámica) · Máx. 2 MB · JPG, PNG o WebP</small>
        </div>
    </div>

    <div class="form-card">
        <h3>Configuración</h3>

        <div class="form-row">
            <div class="form-group" style="flex:1">
                <label for="orden">Orden</label>
                <input type="number" id="orden" name="orden" value="<?= $v('orden', '0') ?>"
                       class="form-input" min="0">
            </div>
            <div class="form-group" style="flex:2;display:flex;align-items:center;padding-top:1.5rem;">
                <label style="display:flex;align-items:center;gap:0.5rem;">
                    <input type="checkbox" name="activo" value="1" <?= $checked('activo', true) ?>>
                    Activo (visible en la home)
                </label>
            </div>
        </div>
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:1rem;">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?= SITE_URL ?>/admin/hero" class="btn">Cancelar</a>
    </div>
</form>
