<section class="section section-warm">
    <div class="container">
        <div class="section-header">
            <h1>Planes y Precios</h1>
            <p>Elige el plan que mejor se adapte a tu negocio</p>
        </div>

        <?php if (!empty($planes)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem;">
            <?php foreach ($planes as $plan): ?>
            <?php $esPopular = ((int)($plan['prioridad'] ?? 0)) === 2; ?>
            <div class="card" style="padding: 2rem; text-align: center; position: relative;<?= $esPopular ? ' border: 2px solid var(--accent);' : '' ?>">
                <?php if ($esPopular): ?>
                <span class="badge badge-accent" style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%);">Mas Popular</span>
                <?php endif; ?>

                <h3 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($plan['nombre']) ?></h3>

                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                    <?php if ((float)($plan['precio'] ?? 0) > 0): ?>
                    $<?= number_format((float)$plan['precio'], 0, ',', '.') ?><span class="text-sm text-light">/mes</span>
                    <?php else: ?>
                    Gratis
                    <?php endif; ?>
                </div>

                <?php if (!empty($plan['descripcion'])): ?>
                <p class="text-sm text-light mb-2"><?= htmlspecialchars($plan['descripcion']) ?></p>
                <?php endif; ?>

                <div style="text-align: left; margin-bottom: 1.5rem;">
                    <div class="mb-1">
                        <span><?= !empty($plan['max_fotos']) ? '✓' : '✗' ?></span>
                        <span class="text-sm"><?= (int)($plan['max_fotos'] ?? 0) ?> fotos</span>
                    </div>
                    <div class="mb-1">
                        <span><?= !empty($plan['estadisticas']) ? '✓' : '✗' ?></span>
                        <span class="text-sm">Estadisticas</span>
                    </div>
                    <div class="mb-1">
                        <span><?= !empty($plan['noticia_mensual']) ? '✓' : '✗' ?></span>
                        <span class="text-sm">Noticia mensual</span>
                    </div>
                    <div class="mb-1">
                        <span><?= !empty($plan['banner_portada']) ? '✓' : '✗' ?></span>
                        <span class="text-sm">Banner en portada</span>
                    </div>
                    <?php if (!empty($plan['badge'])): ?>
                    <div class="mb-1">
                        <span>✓</span>
                        <span class="text-sm">Badge: <?= htmlspecialchars($plan['badge']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($plan['max_cupos'])): ?>
                    <div class="mb-1">
                        <span class="text-sm text-light">Cupos limitados: <?= (int)$plan['max_cupos'] ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <a href="<?= SITE_URL ?>/contacto" class="btn <?= $esPopular ? 'btn-accent' : 'btn-primary' ?>" style="width: 100%;">Contratar</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>Los planes estaran disponibles proximamente.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
