<?php
/**
 * Admin — Listado de Negocios — visitapuertoctay.cl
 * Variables: $negocios (from findAllAdmin: n.* + categoria_nombre, plan_nombre)
 *            $statusFilter (string|null) — current ?status= filter
 */
$total = count($negocios);

// Count pendientes from status column for the alert banner
$pendientesStatus = 0;
foreach ($negocios as $n) {
    if (($n['status'] ?? '') === 'pendiente') $pendientesStatus++;
}
// If filtered, count from current set; if not filtered, count all pendientes shown
$pendientes = $pendientesStatus;
?>
<div style="background:#FEF3C7;border:1px solid #F59E0B;border-radius:8px;padding:0.8rem 1.2rem;margin-bottom:1.2rem;font-size:0.88rem;color:#92400E;">Los datos actuales son de ejemplo. Reemplazalos con informacion real de comercios que hayan autorizado su publicacion.</div>

<?php if ($pendientes > 0 && empty($statusFilter)): ?>
<div class="alert" style="background:#FEF3C7;border:1px solid #F59E0B;color:#92400E;margin-bottom:1rem;">
    📋 Hay <strong><?= $pendientes ?></strong> comercio<?= $pendientes !== 1 ? "s" : "" ?> pendiente<?= $pendientes !== 1 ? "s" : "" ?> de aprobación.
    <a href="<?= SITE_URL ?>/admin/negocios?status=pendiente" style="margin-left:0.5rem;font-weight:600;">Ver pendientes</a>
</div>
<?php endif; ?>

<div class="admin-page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1>Negocios</h1>
        <p style="color:#666;"><?= $total ?> negocio<?= $total !== 1 ? 's' : '' ?> registrado<?= $total !== 1 ? 's' : '' ?><?= $statusFilter ? ' (filtro: ' . htmlspecialchars($statusFilter) . ')' : '' ?></p>
    </div>
    <a href="<?= SITE_URL ?>/admin/negocios/crear" class="btn btn-primary">+ Nuevo Negocio</a>
</div>

<!-- Filtros de status -->
<div style="margin-bottom:1rem; display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center;">
    <span style="color:#666; font-size:0.88rem; font-weight:600;">Filtrar:</span>
    <a href="<?= SITE_URL ?>/admin/negocios" class="btn btn-sm<?= empty($statusFilter) ? '' : '' ?>" style="<?= empty($statusFilter) ? 'background:#374151;color:#fff;' : 'background:#e9ecef;color:#333;' ?>">Todos</a>
    <a href="<?= SITE_URL ?>/admin/negocios?status=pendiente" class="btn btn-sm" style="<?= $statusFilter === 'pendiente' ? 'background:#F59E0B;color:#fff;' : 'background:#FEF3C7;color:#92400E;border:1px solid #F59E0B;' ?>">Pendientes</a>
    <a href="<?= SITE_URL ?>/admin/negocios?status=activo" class="btn btn-sm" style="<?= $statusFilter === 'activo' ? 'background:#16a34a;color:#fff;' : 'background:#DCFCE7;color:#166534;border:1px solid #86efac;' ?>">Activos</a>
    <a href="<?= SITE_URL ?>/admin/negocios?status=rechazado" class="btn btn-sm" style="<?= $statusFilter === 'rechazado' ? 'background:#dc2626;color:#fff;' : 'background:#FEE2E2;color:#991B1B;border:1px solid #fca5a5;' ?>">Rechazados</a>
    <a href="<?= SITE_URL ?>/admin/negocios?status=suspendido" class="btn btn-sm" style="<?= $statusFilter === 'suspendido' ? 'background:#7c3aed;color:#fff;' : 'background:#EDE9FE;color:#5B21B6;border:1px solid #c4b5fd;' ?>">Suspendidos</a>
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
                <th>Status</th>
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
                    <?php
                    $st = $n['status'] ?? 'activo';
                    $stClass = match($st) {
                        'pendiente'  => 'badge-yellow',
                        'activo'     => 'badge-green',
                        'rechazado'  => 'badge-red',
                        'suspendido' => 'badge-red',
                        default      => 'badge-blue',
                    };
                    ?>
                    <span class="badge <?= $stClass ?>"><?= ucfirst(htmlspecialchars($st)) ?></span>
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
                    <div style="display:flex; gap:0.3rem; flex-wrap:wrap;">
                        <a href="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/editar"
                           class="btn btn-sm btn-primary">Editar</a>

                        <?php if (($n['status'] ?? '') === 'pendiente'): ?>
                        <form method="POST" action="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/aprobar"
                              style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm" style="background:#16a34a;color:#fff;">Aprobar</button>
                        </form>
                        <form method="POST" action="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/rechazar"
                              style="display:inline;"
                              onsubmit="return confirm('¿Rechazar «<?= htmlspecialchars(addslashes($n['nombre'])) ?>»?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">Rechazar</button>
                        </form>
                        <?php endif; ?>

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
    <?php if ($statusFilter): ?>
        No hay negocios con status «<?= htmlspecialchars($statusFilter) ?>». <a href="<?= SITE_URL ?>/admin/negocios">Ver todos</a>
    <?php else: ?>
        Aún no hay negocios registrados. <a href="<?= SITE_URL ?>/admin/negocios/crear">Crear el primero</a>
    <?php endif; ?>
</p>
<?php endif; ?>
