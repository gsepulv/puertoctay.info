<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <div>
        <p style="color:#888; font-size:0.9rem; margin:0;"><?= count($paginas) ?> página(s) registrada(s)</p>
    </div>
    <div style="display:flex; gap:0.5rem;">
        <a href="<?= SITE_URL ?>/admin/textos-legales" class="btn btn-secondary btn-sm">📄 Textos Legales</a>
        <a href="<?= SITE_URL ?>/admin/paginas/crear" class="btn btn-primary btn-sm">+ Nueva Página</a>
    </div>
</div>

<?php if (empty($paginas)): ?>
    <div class="empty-state">
        <p>No hay páginas creadas aún.</p>
        <a href="<?= SITE_URL ?>/admin/paginas/crear" class="btn btn-primary" style="margin-top:1rem;">Crear primera página</a>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Orden</th>
                <th>Título</th>
                <th>Slug</th>
                <th>Estado</th>
                <th>Modificada</th>
                <th style="width:180px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($paginas as $p): ?>
                <tr>
                    <td><?= (int) $p['orden'] ?></td>
                    <td><strong><?= htmlspecialchars($p['titulo']) ?></strong></td>
                    <td><code>/pagina/<?= htmlspecialchars($p['slug']) ?></code></td>
                    <td>
                        <?php if ($p['activo']): ?>
                            <span class="badge badge-green">Activa</span>
                        <?php else: ?>
                            <span class="badge badge-yellow">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($p['updated_at'])) ?></td>
                    <td>
                        <a href="<?= SITE_URL ?>/pagina/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Ver</a>
                        <a href="<?= SITE_URL ?>/admin/paginas/<?= $p['id'] ?>/editar" class="btn btn-primary btn-sm">Editar</a>
                        <form method="POST" action="<?= SITE_URL ?>/admin/paginas/<?= $p['id'] ?>/eliminar"
                              style="display:inline;" onsubmit="return confirm('¿Eliminar esta página?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
