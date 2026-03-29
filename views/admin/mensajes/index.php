<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success'] ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['flash_error'] ?></div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<div class="dash-section" style="margin-bottom:1rem;">
    <p>Total: <strong><?= count($mensajes) ?></strong> mensajes | <strong><?= $noLeidos ?></strong> sin leer</p>
</div>

<?php if (empty($mensajes)): ?>
    <div class="empty-state">
        <p style="font-size:2rem;">📭</p>
        <p>No hay mensajes aun.</p>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Asunto</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mensajes as $m): ?>
            <tr style="<?= !$m['leido'] ? 'font-weight:600; background:#f0f7ff;' : '' ?>">
                <td>
                    <?php if (!$m['leido']): ?>
                        <span class="badge badge-blue">Nuevo</span>
                    <?php else: ?>
                        <span class="badge badge-green">Leido</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($m['nombre']) ?></td>
                <td><a href="mailto:<?= htmlspecialchars($m['email']) ?>"><?= htmlspecialchars($m['email']) ?></a></td>
                <td><?= htmlspecialchars($m['asunto'] ?? '-') ?></td>
                <td style="white-space:nowrap; font-size:0.82rem;"><?= htmlspecialchars($m['created_at']) ?></td>
                <td>
                    <a href="<?= SITE_URL ?>/admin/mensajes/<?= $m['id'] ?>" class="btn btn-primary btn-sm">Ver</a>
                    <form method="POST" action="<?= SITE_URL ?>/admin/mensajes/<?= $m['id'] ?>/eliminar" style="display:inline;" onsubmit="return confirm('Eliminar este mensaje?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
