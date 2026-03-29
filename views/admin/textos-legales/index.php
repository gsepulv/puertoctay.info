<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<p style="color:#888; font-size:0.9rem; margin-bottom:1rem;">Edita los textos legales del sitio. Estos se muestran en el footer y son accesibles públicamente.</p>

<?php if (empty($paginas)): ?>
    <div class="empty-state">
        <p>No hay textos legales configurados.</p>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Documento</th>
                <th>URL pública</th>
                <th>Estado</th>
                <th>Última edición</th>
                <th style="width:160px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paginas as $p): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($p['titulo']) ?></strong></td>
                    <td><code>/<?= htmlspecialchars($p['slug']) ?></code></td>
                    <td>
                        <?php if ($p['activo']): ?>
                            <span class="badge badge-green">Activa</span>
                        <?php else: ?>
                            <span class="badge badge-yellow">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($p['updated_at'])) ?></td>
                    <td>
                        <a href="<?= SITE_URL ?>/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Ver</a>
                        <a href="<?= SITE_URL ?>/admin/textos-legales/<?= $p['id'] ?>/editar" class="btn btn-primary btn-sm">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
