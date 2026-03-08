<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888;"><?= count($noticias) ?> noticia<?= count($noticias) !== 1 ? 's' : '' ?></p>
    <a href="<?= SITE_URL ?>/admin/noticias/crear" class="btn btn-primary">+ Nueva Noticia</a>
</div>

<?php if (empty($noticias)): ?>
    <div style="text-align:center; padding:3rem; color:#888;">
        <p>No hay noticias registradas.</p>
    </div>
<?php else: ?>
<table class="admin-table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Categoría</th>
            <th>Estado</th>
            <th>Destacada</th>
            <th>Publicación</th>
            <th>Visitas</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($noticias as $n): ?>
        <tr>
            <td>
                <?php if ($n['estado'] === 'publicado'): ?>
                    <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($n['slug']) ?>" target="_blank"><?= htmlspecialchars($n['titulo']) ?></a>
                <?php else: ?>
                    <?= htmlspecialchars($n['titulo']) ?>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($n['categoria_nombre'] ?? '—') ?></td>
            <td>
                <?php
                $estadoColor = match($n['estado']) {
                    'publicado' => 'badge-green',
                    'borrador'  => 'badge-red',
                    'revision'  => 'badge-gold',
                    'archivado' => '',
                    default     => '',
                };
                ?>
                <form action="<?= SITE_URL ?>/admin/noticias/<?= $n['id'] ?>/estado" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <select name="estado" onchange="this.form.submit()" style="font-size:0.8rem; padding:0.2rem 0.4rem; border:1px solid #dee2e6; border-radius:4px;">
                        <?php foreach (['borrador', 'revision', 'publicado', 'archivado'] as $est): ?>
                            <option value="<?= $est ?>" <?= $n['estado'] === $est ? 'selected' : '' ?>><?= ucfirst($est) ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </td>
            <td>
                <form action="<?= SITE_URL ?>/admin/noticias/<?= $n['id'] ?>/destacar" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm" style="background:<?= $n['featured'] ? COLOR_ACCENT : '#f8f9fa' ?>; color:<?= $n['featured'] ? '#fff' : '#888' ?>; border:1px solid #dee2e6;">
                        <?= $n['featured'] ? '★ Sí' : '☆ No' ?>
                    </button>
                </form>
            </td>
            <td style="font-size:0.85rem;">
                <?php if ($n['publicado_en']): ?>
                    <?= date('d/m/Y H:i', strtotime($n['publicado_en'])) ?>
                <?php else: ?>
                    <span style="color:#aaa;">—</span>
                <?php endif; ?>
            </td>
            <td><?= number_format((int)$n['visitas']) ?></td>
            <td>
                <a href="<?= SITE_URL ?>/admin/noticias/<?= $n['id'] ?>/editar" class="btn btn-sm btn-primary">Editar</a>
                <form action="<?= SITE_URL ?>/admin/noticias/<?= $n['id'] ?>/eliminar" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar esta noticia?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
