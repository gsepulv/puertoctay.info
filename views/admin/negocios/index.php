<?php
/**
 * Admin — Listado de Negocios — visitapuertoctay.cl
 * Variables: $negocios (from findAllAdmin: n.* + categoria_nombre, plan_nombre)
 */
$total = count($negocios);
?>
<div style="background:#FEF3C7;border:1px solid #F59E0B;border-radius:8px;padding:0.8rem 1.2rem;margin-bottom:1.2rem;font-size:0.88rem;color:#92400E;">Los datos actuales son de ejemplo. Reemplazalos con informacion real de comercios que hayan autorizado su publicacion.</div>

<?php $pendientes = 0; foreach ($negocios as $n) { if (!$n["activo"] && !empty($n["propietario_id"])) $pendientes++; } ?>
<?php if ($pendientes > 0): ?>
<div class="alert" style="background:#FEF3C7;border:1px solid #F59E0B;color:#92400E;margin-bottom:1rem;">
    📋 Hay <strong><?= $pendientes ?></strong> comercio<?= $pendientes !== 1 ? "s" : "" ?> pendiente<?= $pendientes !== 1 ? "s" : "" ?> de aprobación.
</div>
<?php endif; ?>

<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1>Negocios</h1>
        <p style="color:#666;"><?= $total ?> negocio<?= $total !== 1 ? 's' : '' ?> registrado<?= $total !== 1 ? 's' : '' ?></p>
    </div>
    <a href="<?= SITE_URL ?>/admin/negocios/crear" class="btn btn-primary">+ Nuevo Negocio</a>
</div>

<?php if (!empty($_GET['success'])): ?>
<div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
<?php endif; ?>

<?php if (!empty($_GET['error'])): ?>
<div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<?php if (!empty($negocios)): ?>
<div style="overflow-x:auto;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width:50px;">Logo</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Plan</th>
                <th>Estado</th>
                <th>Verificado</th>
                <th>Destacado</th>
                <th>Visitas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($negocios as $n): ?>
            <tr>
                <td>
                    <?php if (!empty($n['logo'])): ?>
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($n['logo']) ?>"
                             alt="" style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                    <?php else: ?>
                        <span style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:50%; background:#ddd; color:#888; font-weight:600; font-size:14px;">
                            <?= mb_strtoupper(mb_substr($n['nombre'], 0, 1)) ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($n['slug'] ?? $n['id']) ?>" target="_blank" style="font-weight:600;">
                        <?= htmlspecialchars($n['nombre']) ?>
                    </a>
                </td>
                <td><?= htmlspecialchars($n['categoria_nombre'] ?? '—') ?></td>
                <td><?= htmlspecialchars($n['plan_nombre'] ?? '—') ?></td>
                <td>
                    <?php if (!empty($n['activo'])): ?>
                        <span class="badge badge-green">Activo</span>
                    <?php else: ?>
                        <span class="badge badge-red">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/verificar" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-sm" style="padding:2px 8px; font-size:12px;"
                                title="<?= !empty($n['verificado']) ? 'Quitar verificación' : 'Verificar' ?>">
                            <?= !empty($n['verificado']) ? '&#10003;' : '&#10007;' ?>
                        </button>
                    </form>
                </td>
                <td>
                    <?php if (!empty($n['destacado'])): ?>
                        <span class="badge badge-green">Si</span>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td><?= (int)($n['visitas'] ?? 0) ?></td>
                <td>
                    <div style="display:flex; gap:0.3rem;">
                        <a href="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/editar"
                           class="btn btn-sm btn-primary">Editar</a>

                        <form method="POST" action="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/eliminar"
                              style="display:inline;"
                              onsubmit="return confirm('¿Eliminar «<?= htmlspecialchars(addslashes($n['nombre'])) ?>»? Esta acción no se puede deshacer.');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php else: ?>
<p style="text-align:center; padding:3rem; color:#888;">
    Aún no hay negocios registrados. <a href="<?= SITE_URL ?>/admin/negocios/crear">Crear el primero</a>
</p>
<?php endif; ?>
