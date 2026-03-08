<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888;"><?= count($negocios) ?> negocio<?= count($negocios) !== 1 ? 's' : '' ?> registrado<?= count($negocios) !== 1 ? 's' : '' ?></p>
    <a href="<?= SITE_URL ?>/admin/negocios/crear" class="btn btn-primary">+ Nuevo Negocio</a>
</div>

<?php if (empty($negocios)): ?>
    <div style="text-align:center; padding:3rem; color:#888;">
        <p>No hay negocios registrados.</p>
    </div>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Plan</th>
            <th>Estado</th>
            <th>Verificado</th>
            <th>Visitas</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($negocios as $neg): ?>
        <tr>
            <td>
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" target="_blank">
                    <?= htmlspecialchars($neg['nombre']) ?>
                </a>
            </td>
            <td><?= htmlspecialchars($neg['categoria_nombre'] ?? '—') ?></td>
            <td><?= htmlspecialchars($neg['plan_nombre'] ?? 'Básico') ?></td>
            <td>
                <?php if ($neg['activo']): ?>
                    <span class="badge badge-green">Activo</span>
                <?php else: ?>
                    <span class="badge badge-red">Inactivo</span>
                <?php endif; ?>
            </td>
            <td>
                <form action="<?= SITE_URL ?>/admin/negocios/<?= $neg['id'] ?>/verificar" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm" style="background:<?= $neg['verificado'] ? '#d4edda' : '#f8f9fa' ?>; color:<?= $neg['verificado'] ? '#155724' : '#888' ?>; border:1px solid #dee2e6;">
                        <?= $neg['verificado'] ? '✓ Sí' : '✗ No' ?>
                    </button>
                </form>
            </td>
            <td><?= number_format((int)$neg['visitas']) ?></td>
            <td>
                <a href="<?= SITE_URL ?>/admin/negocios/<?= $neg['id'] ?>/editar" class="btn btn-sm btn-primary">Editar</a>
                <form action="<?= SITE_URL ?>/admin/negocios/<?= $neg['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este negocio?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
