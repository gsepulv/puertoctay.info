<h1 style="margin-bottom: 0.5rem;">Mi Comercio</h1>
<p class="text-light" style="margin-bottom: 2rem;">Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></p>

<?php if (!$negocio): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="font-size: 1.1rem; color: var(--text-light); margin-bottom: 1rem;">No tienes un negocio registrado aún.</p>
        <a href="<?= SITE_URL ?>/registrar-comercio" class="btn btn-primary">Registrar mi comercio</a>
    </div>
<?php else: ?>

    <!-- Estado del negocio -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.3rem; margin-bottom: 0.25rem;"><?= htmlspecialchars($negocio['nombre']) ?></h2>
                <span style="font-size: 0.85rem; color: var(--text-light);"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre'] ?? 'Sin categoría') ?></span>
            </div>
            <div>
                <?php
                $statusMap = [
                    'pendiente'  => ['badge-pending', 'Pendiente de aprobación'],
                    'activo'     => ['badge-active', 'Activo y publicado'],
                    'rechazado'  => ['badge-rejected', 'Rechazado'],
                    'suspendido' => ['badge-rejected', 'Suspendido'],
                ];
                $st = $statusMap[$negocio['status'] ?? 'pendiente'] ?? $statusMap['pendiente'];
                ?>
                <span class="badge <?= $st[0] ?>"><?= $st[1] ?></span>
            </div>
        </div>

        <?php if (($negocio['status'] ?? 'pendiente') === 'pendiente'): ?>
            <div style="margin-top: 1rem; padding: 1rem; background: #FEF3C7; border-radius: var(--radius-md); font-size: 0.9rem; color: #92400E;">
                Tu comercio está siendo revisado por nuestro equipo. Te notificaremos cuando sea aprobado.
            </div>
        <?php endif; ?>
    </div>

    <!-- Stats -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        <div class="stat-card">
            <div class="number"><?= number_format($stats['visitas'] ?? 0) ?></div>
            <div class="label">Visitas</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $stats['resenas'] ?? 0 ?></div>
            <div class="label">Reseñas</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $stats['rating'] ?? '—' ?></div>
            <div class="label">Puntuación</div>
        </div>
    </div>

    <!-- Quick actions -->
    <div class="card">
        <h3 style="margin-bottom: 1rem;">Acciones rápidas</h3>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="<?= SITE_URL ?>/mi-comercio/editar" class="btn btn-primary">Editar mi negocio</a>
            <?php if ($negocio['activo']): ?>
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($negocio['slug']) ?>" class="btn btn-outline" target="_blank">Ver página pública</a>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>
