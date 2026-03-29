<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888; font-size:0.9rem;"><?= count($planes) ?> plan(es) registrado(s)</p>
    <a href="<?= SITE_URL ?>/admin/planes/crear" class="btn btn-primary btn-sm">+ Nuevo Plan</a>
</div>

<?php if (empty($planes)): ?>
    <div class="empty-state">
        <p>No hay planes creados aún.</p>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Prioridad</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Fotos</th>
                <th>Features</th>
                <th>Cupos</th>
                <th>Estado</th>
                <th style="width:220px;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($planes as $p): ?>
                <tr>
                    <td><?= (int) $p['prioridad'] ?></td>
                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                    <td>
                        <?php if ((int)$p['precio'] === 0): ?>
                            <span class="badge badge-green">Gratis</span>
                        <?php else: ?>
                            $<?= number_format($p['precio'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </td>
                    <td><?= (int) $p['max_fotos'] ?></td>
                    <td>
                        <?php if ($p['badge']): ?><span class="badge badge-gold" style="margin:1px;">Badge</span><?php endif; ?>
                        <?php if ($p['estadisticas']): ?><span class="badge badge-blue" style="margin:1px;">Stats</span><?php endif; ?>
                        <?php if ($p['noticia_mensual']): ?><span class="badge badge-blue" style="margin:1px;">Noticia</span><?php endif; ?>
                        <?php if ($p['banner_portada']): ?><span class="badge badge-blue" style="margin:1px;">Banner</span><?php endif; ?>
                    </td>
                    <td><?= $p['max_cupos'] ? (int) $p['max_cupos'] : 'Ilimitado' ?></td>
                    <td>
                        <?php if ($p['activo']): ?>
                            <span class="badge badge-green">Activo</span>
                        <?php else: ?>
                            <span class="badge badge-red">Inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= SITE_URL ?>/admin/planes/<?= $p['id'] ?>/editar" class="btn btn-primary btn-sm">Editar</a>
                        <form method="POST" action="<?= SITE_URL ?>/admin/planes/<?= $p['id'] ?>/toggle" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn <?= $p['activo'] ? 'btn-warning' : 'btn-secondary' ?> btn-sm">
                                <?= $p['activo'] ? 'Desactivar' : 'Activar' ?>
                            </button>
                        </form>
                        <form method="POST" action="<?= SITE_URL ?>/admin/planes/<?= $p['id'] ?>/eliminar"
                              style="display:inline;" onsubmit="return confirm('¿Eliminar este plan?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div style="margin-top:1.5rem;">
    <a href="<?= SITE_URL ?>/planes" target="_blank" class="btn btn-secondary btn-sm">Ver página pública de planes</a>
</div>
