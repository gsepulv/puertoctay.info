<?php
$v = fn(string $key, string $default = '') => htmlspecialchars($seo[$key] ?? $default, ENT_QUOTES, 'UTF-8');
$pageNames = [
    'home' => 'Página de Inicio',
    'directorio' => 'Directorio de Negocios',
    'turismo' => 'Turismo',
    'patrimonio' => 'Patrimonio y Cultura',
    'noticias' => 'Noticias',
    'mapa' => 'Mapa Interactivo',
    'contacto' => 'Contacto',
    'buscar' => 'Buscar',
];
$pageName = $pageNames[$seo['page_identifier']] ?? $seo['page_identifier'];
?>

<div class="admin-page-header">
    <h1>SEO: <?= htmlspecialchars($pageName) ?></h1>
    <p class="admin-page-subtitle">
        <a href="<?= SITE_URL ?>/admin/seo">&larr; Volver a SEO</a>
    </p>
</div>

<form method="POST" action="<?= SITE_URL ?>/admin/seo/<?= $seo['id'] ?>/actualizar" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-card">
        <h3>Meta Tags</h3>

        <div class="form-group">
            <label for="meta_title">Meta Title</label>
            <input type="text" id="meta_title" name="meta_title" value="<?= $v('meta_title') ?>"
                   class="form-input" maxlength="200" placeholder="Título para buscadores">
            <small style="color:var(--text-lighter);">Máx. 70 caracteres recomendado.</small>
        </div>

        <div class="form-group">
            <label for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" class="form-input" rows="2"
                      maxlength="300" placeholder="Descripción para buscadores"><?= $v('meta_description') ?></textarea>
            <small style="color:var(--text-lighter);">Máx. 160 caracteres recomendado.</small>
        </div>

        <div class="form-group">
            <label for="keywords">Keywords</label>
            <input type="text" id="keywords" name="keywords" value="<?= $v('keywords') ?>"
                   class="form-input" maxlength="300" placeholder="palabra1, palabra2, palabra3">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="canonical_url">Canonical URL</label>
                <input type="text" id="canonical_url" name="canonical_url" value="<?= $v('canonical_url') ?>"
                       class="form-input" maxlength="255" placeholder="Dejar vacío para URL automática">
            </div>
            <div class="form-group">
                <label for="robots">Robots</label>
                <select id="robots" name="robots" class="form-input">
                    <option value="index, follow" <?= ($seo['robots'] ?? '') === 'index, follow' ? 'selected' : '' ?>>index, follow</option>
                    <option value="noindex, follow" <?= ($seo['robots'] ?? '') === 'noindex, follow' ? 'selected' : '' ?>>noindex, follow</option>
                    <option value="index, nofollow" <?= ($seo['robots'] ?? '') === 'index, nofollow' ? 'selected' : '' ?>>index, nofollow</option>
                    <option value="noindex, nofollow" <?= ($seo['robots'] ?? '') === 'noindex, nofollow' ? 'selected' : '' ?>>noindex, nofollow</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-card">
        <h3>Open Graph (redes sociales)</h3>

        <div class="form-group">
            <label for="og_title">og:title</label>
            <input type="text" id="og_title" name="og_title" value="<?= $v('og_title') ?>"
                   class="form-input" maxlength="200" placeholder="Si está vacío usa Meta Title">
        </div>

        <div class="form-group">
            <label for="og_description">og:description</label>
            <textarea id="og_description" name="og_description" class="form-input" rows="2"
                      maxlength="300" placeholder="Si está vacío usa Meta Description"><?= $v('og_description') ?></textarea>
        </div>

        <div class="form-group">
            <label for="og_image_file">og:image</label>
            <?php if (!empty($seo['og_image'])): ?>
                <div style="margin-bottom:0.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($seo['og_image']) ?>"
                         alt="OG Image" style="max-width:300px;border-radius:8px;">
                    <br><small>Imagen actual.</small>
                </div>
            <?php endif; ?>
            <input type="file" id="og_image_file" name="og_image_file" class="form-input" accept=".jpg,.jpeg,.png,.webp">
            <small style="color:var(--text-lighter);display:block;margin-top:0.3rem;">Recomendado: 1200 x 630 px · Máx. 2 MB</small>
        </div>
    </div>

    <div style="margin-top:1.5rem;display:flex;gap:1rem;">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="<?= SITE_URL ?>/admin/seo" class="btn">Cancelar</a>
    </div>
</form>
