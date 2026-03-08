<p style="color:#888; margin-bottom:1rem;"><?= count($resenas) ?> reseña<?= count($resenas) !== 1 ? 's' : '' ?></p>

<?php if (empty($resenas)): ?>
    <div style="text-align:center; padding:3rem; color:#888;"><p>No hay reseñas registradas.</p></div>
<?php else: ?>
<table class="admin-table">
    <thead><tr><th>Negocio</th><th>Autor</th><th>Puntuación</th><th>Comentario</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr></thead>
    <tbody>
        <?php foreach ($resenas as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['negocio_nombre'] ?? '—') ?></td>
            <td><?= htmlspecialchars($r['nombre_autor']) ?></td>
            <td style="color:#f39c12;"><?= str_repeat('★', (int)$r['puntuacion']) ?><?= str_repeat('☆', 5-(int)$r['puntuacion']) ?></td>
            <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars(mb_substr($r['comentario'] ?? '', 0, 60)) ?></td>
            <td>
                <?php
                $bc = match($r['estado']) { 'aprobada'=>'badge-green', 'pendiente'=>'badge-yellow', 'rechazada'=>'badge-red', default=>'' };
                ?>
                <span class="badge <?= $bc ?>"><?= ucfirst($r['estado']) ?></span>
            </td>
            <td style="font-size:0.8rem;"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
            <td style="white-space:nowrap;">
                <?php if ($r['estado'] === 'pendiente'): ?>
                <form action="<?= SITE_URL ?>/admin/resenas/<?= $r['id'] ?>/aprobar" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-secondary">Aprobar</button>
                </form>
                <form action="<?= SITE_URL ?>/admin/resenas/<?= $r['id'] ?>/rechazar" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-warning">Rechazar</button>
                </form>
                <?php endif; ?>
                <form action="<?= SITE_URL ?>/admin/resenas/<?= $r['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta reseña?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
