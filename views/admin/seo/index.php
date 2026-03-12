<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="form-card">
    <h3 style="margin-bottom:1rem; color:<?= COLOR_PRIMARY ?>;">Configuración SEO</h3>
    <p style="color:#888; font-size:0.85rem; margin-bottom:1.5rem;">
        Estos valores se usan como meta tags globales del sitio. Las páginas individuales pueden sobrescribirlos.
    </p>

    <form method="POST" action="<?= SITE_URL ?>/admin/seo/guardar">
        <?= csrf_field() ?>

        <?php foreach ($campos as $campo): ?>
            <div class="form-group">
                <label for="campo_<?= htmlspecialchars($campo['clave']) ?>">
                    <?= htmlspecialchars($campo['etiqueta']) ?>
                </label>
                <?php if ($campo['tipo'] === 'textarea'): ?>
                    <textarea id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              rows="<?= $campo['clave'] === 'robots_txt' ? 6 : ($campo['clave'] === 'head_scripts' || $campo['clave'] === 'body_scripts' ? 5 : 3) ?>"
                              style="<?= in_array($campo['clave'], ['robots_txt', 'head_scripts', 'body_scripts']) ? 'font-family:monospace; font-size:0.85rem;' : '' ?>"
                    ><?= htmlspecialchars($campo['valor'] ?? '') ?></textarea>
                <?php else: ?>
                    <input type="<?= $campo['tipo'] === 'url' ? 'url' : 'text' ?>"
                           id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           value="<?= htmlspecialchars($campo['valor'] ?? '') ?>">
                <?php endif; ?>
                <?php if ($campo['clave'] === 'meta_description'): ?>
                    <small style="color:#888;">Máximo 160 caracteres recomendado.</small>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Guardar SEO</button>
        </div>
    </form>
</div>
