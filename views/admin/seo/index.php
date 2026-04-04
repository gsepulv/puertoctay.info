<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="admin-page-header">
    <h1>SEO</h1>
</div>

<!-- SEO Global -->
<div class="form-card">
    <h3 style="margin-bottom:1rem;">Configuración SEO Global</h3>
    <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:1.5rem;">Valores por defecto para las páginas que no tengan SEO específico.</p>

    <form method="POST" action="<?= SITE_URL ?>/admin/seo/guardar">
        <?= csrf_field() ?>
        <?php foreach ($campos as $campo): ?>
            <div class="form-group">
                <label for="campo_<?= htmlspecialchars($campo['clave']) ?>"><?= htmlspecialchars($campo['etiqueta']) ?></label>
                <?php if ($campo['tipo'] === 'textarea'): ?>
                    <textarea id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              rows="<?= in_array($campo['clave'], ['robots_txt', 'head_scripts', 'body_scripts']) ? 5 : 3 ?>"
                              style="<?= in_array($campo['clave'], ['robots_txt', 'head_scripts', 'body_scripts']) ? 'font-family:monospace;font-size:0.85rem;' : '' ?>"
                    ><?= htmlspecialchars($campo['valor'] ?? '') ?></textarea>
                <?php else: ?>
                    <input type="<?= $campo['tipo'] === 'url' ? 'url' : 'text' ?>"
                           id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           value="<?= htmlspecialchars($campo['valor'] ?? '') ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary">Guardar SEO Global</button>
    </form>
</div>

<!-- SEO por Página -->
<div class="form-card" style="margin-top:2rem;">
    <h3 style="margin-bottom:1rem;">SEO por Página</h3>
    <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:1.5rem;">Configura meta tags específicos para cada página pública.</p>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Página</th>
                    <th>Meta Title</th>
                    <th>Meta Description</th>
                    <th style="width:80px">Estado</th>
                    <th style="width:80px">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $p): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['page_identifier']) ?></strong></td>
                    <td style="font-size:0.85rem;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= htmlspecialchars($p['meta_title'] ?? '') ?: '<span style="color:var(--text-lighter)">—</span>' ?>
                    </td>
                    <td style="font-size:0.85rem;max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <?= htmlspecialchars($p['meta_description'] ?? '') ?: '<span style="color:var(--text-lighter)">—</span>' ?>
                    </td>
                    <td style="text-align:center">
                        <?php if (!empty($p['meta_title']) && !empty($p['meta_description'])): ?>
                            <span class="badge badge-green">OK</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Parcial</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= SITE_URL ?>/admin/seo/<?= $p['id'] ?>/editar" class="btn btn-sm btn-outline">Editar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
