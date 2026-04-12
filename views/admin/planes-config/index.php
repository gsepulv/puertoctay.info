<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
    <p style="color:#888; font-size:0.9rem;"><?= count($planes) ?> plan(es) configurado(s)</p>
</div>

<?php if (empty($planes)): ?>
    <div class="dash-section">
        <p>No hay planes configurados.</p>
    </div>
<?php else: ?>

<!-- Tabla resumen de precios -->
<div class="dash-section" style="margin-bottom:1.5rem;">
    <h3>💰 Escalera de Precios</h3>
    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:0.8rem; margin-top:0.8rem;">
        <?php foreach ($planes as $p): ?>
        <div style="text-align:center; padding:0.8rem; border-radius:8px; border:2px solid <?= htmlspecialchars($p['color']) ?>; background: <?= htmlspecialchars($p['color']) ?>0a;">
            <div style="font-size:1.5rem;"><?= $p['icono'] ?? '' ?></div>
            <div style="font-weight:700; color:<?= htmlspecialchars($p['color']) ?>; font-size:0.9rem;"><?= htmlspecialchars($p['nombre']) ?></div>
            <div style="font-size:0.75rem; color:#888; margin-top:0.3rem;">Intro</div>
            <div style="font-weight:700; font-size:1.1rem;"><?= PlanConfig::formatPrecio((int)$p['precio_intro']) ?></div>
            <div style="font-size:0.75rem; color:#888; margin-top:0.2rem;">Regular</div>
            <div style="font-weight:600; font-size:0.95rem; color:#666;"><?= PlanConfig::formatPrecio((int)$p['precio_regular']) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Plan</th>
            <th>Precio Intro</th>
            <th>Precio Regular</th>
            <th>Fotos</th>
            <th>Posición</th>
            <th>Características</th>
            <th>Cupos</th>
            <th>Estado</th>
            <th style="width:80px;">Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($planes as $p): ?>
        <tr>
            <td><?= (int) $p['orden'] ?></td>
            <td>
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:<?= htmlspecialchars($p['color']) ?>; margin-right:0.4rem;"></span>
                <strong><?= $p['icono'] ?? '' ?> <?= htmlspecialchars($p['nombre']) ?></strong>
            </td>
            <td>
                <?php if (PlanConfig::esGratuito($p)): ?>
                    <span class="badge badge-green">Gratis</span>
                <?php else: ?>
                    <?= PlanConfig::formatPrecio((int)$p['precio_intro']) ?>
                <?php endif; ?>
            </td>
            <td><?= PlanConfig::formatPrecio((int)$p['precio_regular']) ?></td>
            <td><?= $p['max_fotos'] !== null ? (int)$p['max_fotos'] : '∞' ?></td>
            <td>
                <?php
                $posBadge = match($p['posicion_listado']) {
                    'siempre_primero' => 'badge-gold',
                    'prioritaria' => 'badge-blue',
                    default => 'badge-green',
                };
                ?>
                <span class="badge <?= $posBadge ?>"><?= PlanConfig::labelPosicion($p['posicion_listado']) ?></span>
            </td>
            <td>
                <?php if ($p['tiene_mapa']): ?><span class="badge badge-blue" style="margin:1px;">Mapa</span><?php endif; ?>
                <?php if ($p['tiene_horarios']): ?><span class="badge badge-blue" style="margin:1px;">Horarios</span><?php endif; ?>
                <?php if ($p['tiene_sello']): ?><span class="badge badge-gold" style="margin:1px;">Sello</span><?php endif; ?>
                <?php if ($p['tiene_reporte']): ?><span class="badge badge-blue" style="margin:1px;">Reporte</span><?php endif; ?>
            </td>
            <td>
                <?php if ($p['cupos_globales']): ?>
                    <?= (int)$p['cupos_globales'] ?> global<?= $p['max_cupos_categoria'] ? ' / ' . (int)$p['max_cupos_categoria'] . ' x cat.' : '' ?>
                <?php else: ?>
                    Ilimitado
                <?php endif; ?>
            </td>
            <td>
                <?php if ($p['activo']): ?>
                    <span class="badge badge-green">Activo</span>
                <?php else: ?>
                    <span class="badge badge-red">Inactivo</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?= SITE_URL ?>/admin/planes-config/<?= $p['id'] ?>/editar" class="btn btn-primary btn-sm">Editar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
