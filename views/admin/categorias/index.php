<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888;"><?= count($categorias) ?> categoría<?= count($categorias) !== 1 ? 's' : '' ?></p>
    <a href="<?= SITE_URL ?>/admin/categorias/crear" class="btn btn-primary">+ Nueva Categoría</a>
</div>

<table class="admin-table">
    <thead>
        <tr><th>Emoji</th><th>Nombre</th><th>Slug</th><th>Tipo</th><th>Orden</th><th>Estado</th><th>Acciones</th></tr>
    </thead>
    <tbody>
        <?php foreach ($categorias as $cat): ?>
        <tr>
            <td style="font-size:1.3rem;"><?= $cat['emoji'] ?></td>
            <td><?= htmlspecialchars($cat['nombre']) ?></td>
            <td style="color:#888; font-size:0.8rem;"><?= htmlspecialchars($cat['slug']) ?></td>
            <td><span class="badge <?= $cat['tipo'] === 'directorio' ? 'badge-blue' : 'badge-yellow' ?>"><?= ucfirst($cat['tipo']) ?></span></td>
            <td><?= (int)$cat['orden'] ?></td>
            <td><span class="badge <?= $cat['activo'] ? 'badge-green' : 'badge-red' ?>"><?= $cat['activo'] ? 'Activa' : 'Inactiva' ?></span></td>
            <td>
                <a href="<?= SITE_URL ?>/admin/categorias/<?= $cat['id'] ?>/editar" class="btn btn-sm btn-primary">Editar</a>
                <form action="<?= SITE_URL ?>/admin/categorias/<?= $cat['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta categoría?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
