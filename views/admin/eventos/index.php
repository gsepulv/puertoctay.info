<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888;"><?= count($eventos) ?> evento<?= count($eventos) !== 1 ? 's' : '' ?></p>
    <a href="<?= SITE_URL ?>/admin/eventos/crear" class="btn btn-primary">+ Nuevo Evento</a>
</div>

<?php if (empty($eventos)): ?>
    <div style="text-align:center; padding:3rem; color:#888;"><p>No hay eventos registrados.</p></div>
<?php else: ?>
<table class="admin-table">
    <thead><tr><th>Nombre</th><th>Fecha</th><th>Lugar</th><th>Estado</th><th>Acciones</th></tr></thead>
    <tbody>
        <?php foreach ($eventos as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['nombre']) ?></td>
            <td style="font-size:0.85rem;">
                <?= date('d/m/Y', strtotime($e['fecha_inicio'])) ?>
                <?php if (!empty($e['fecha_fin']) && $e['fecha_fin'] !== $e['fecha_inicio']): ?>
                    — <?= date('d/m/Y', strtotime($e['fecha_fin'])) ?>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($e['lugar'] ?? '—') ?></td>
            <td>
                <?php
                $bc = match($e['estado']) { 'publicado'=>'badge-green', 'borrador'=>'badge-red', 'finalizado'=>'badge-blue', default=>'' };
                ?>
                <span class="badge <?= $bc ?>"><?= ucfirst($e['estado']) ?></span>
            </td>
            <td>
                <a href="<?= SITE_URL ?>/admin/eventos/<?= $e['id'] ?>/editar" class="btn btn-sm btn-primary">Editar</a>
                <form action="<?= SITE_URL ?>/admin/eventos/<?= $e['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar este evento?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
