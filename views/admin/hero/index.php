<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="admin-page-header">
    <h1>Hero de la Home</h1>
    <a href="<?= SITE_URL ?>/admin/hero/crear" class="btn btn-primary">+ Nuevo Hero</a>
</div>

<p style="color:var(--text-light);margin-bottom:1.5rem;">Administra las imágenes y textos del banner principal de la página de inicio. Solo el slide activo se muestra en la home.</p>

<?php if (empty($slides)): ?>
<div class="empty-state">
    <p>No hay slides de hero. La home mostrará el gradiente por defecto.</p>
</div>
<?php else: ?>
<div class="table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width:60px">Orden</th>
                <th style="width:80px">Imagen</th>
                <th>Título</th>
                <th>Botón</th>
                <th style="width:80px">Estado</th>
                <th style="width:160px">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($slides as $s): ?>
            <tr>
                <td style="text-align:center"><?= (int) $s['orden'] ?></td>
                <td>
                    <?php if (!empty($s['imagen'])): ?>
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($s['imagen']) ?>" style="width:60px;height:35px;object-fit:cover;border-radius:4px;">
                    <?php else: ?>
                        <span style="color:var(--text-lighter)">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?= htmlspecialchars($s['titulo']) ?></strong>
                    <?php if (!empty($s['subtitulo'])): ?>
                        <br><small style="color:var(--text-light)"><?= htmlspecialchars(mb_strimwidth($s['subtitulo'], 0, 60, '...')) ?></small>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($s['texto_boton'])): ?>
                        <span class="badge badge-secondary"><?= htmlspecialchars($s['texto_boton']) ?></span>
                    <?php else: ?>
                        <span style="color:var(--text-lighter)">—</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:center">
                    <form method="POST" action="<?= SITE_URL ?>/admin/hero/<?= $s['id'] ?>/toggle" style="display:inline">
                        <?= csrf_field() ?>
                        <button type="submit" class="badge <?= $s['activo'] ? 'badge-green' : 'badge-secondary' ?>" style="cursor:pointer;border:none;">
                            <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                        </button>
                    </form>
                </td>
                <td>
                    <div style="display:flex;gap:0.4rem;">
                        <a href="<?= SITE_URL ?>/admin/hero/<?= $s['id'] ?>/editar" class="btn btn-sm btn-outline">Editar</a>
                        <form method="POST" action="<?= SITE_URL ?>/admin/hero/<?= $s['id'] ?>/eliminar" onsubmit="return confirm('¿Eliminar este hero?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm" style="background:#DC2626;color:#fff;">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
