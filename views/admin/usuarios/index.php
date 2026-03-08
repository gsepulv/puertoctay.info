<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888;"><?= count($usuarios) ?> usuario<?= count($usuarios) !== 1 ? 's' : '' ?></p>
    <a href="<?= SITE_URL ?>/admin/usuarios/crear" class="btn btn-primary">+ Nuevo Usuario</a>
</div>

<table class="admin-table">
    <thead><tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Estado</th><th>Último login</th><th>Acciones</th></tr></thead>
    <tbody>
        <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge <?= $u['rol'] === 'admin' ? 'badge-gold' : 'badge-blue' ?>"><?= ucfirst($u['rol']) ?></span></td>
            <td><span class="badge <?= $u['activo'] ? 'badge-green' : 'badge-red' ?>"><?= $u['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
            <td style="font-size:0.8rem;"><?= $u['ultimo_login'] ? date('d/m/Y H:i', strtotime($u['ultimo_login'])) : '—' ?></td>
            <td>
                <a href="<?= SITE_URL ?>/admin/usuarios/<?= $u['id'] ?>/editar" class="btn btn-sm btn-primary">Editar</a>
                <?php if ((int)$u['id'] !== AuthMiddleware::userId()): ?>
                <form action="<?= SITE_URL ?>/admin/usuarios/<?= $u['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este usuario?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
