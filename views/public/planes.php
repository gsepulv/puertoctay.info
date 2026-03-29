<?php /** @var array $planes */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Planes</span>
</nav>

<div class="container">
    <div class="section" style="text-align:center;">
        <h1 style="margin-bottom:.75rem;">Planes para tu Negocio</h1>
        <p style="color:var(--text-secondary);max-width:560px;margin:0 auto 2.5rem;font-size:1.1rem;">
            Elige el plan que mejor se adapte a tu negocio y aumenta tu visibilidad en Puerto Octay.
        </p>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.5rem;text-align:left;">
            <?php foreach ($planes as $plan): ?>
                <?php $isPopular = ($plan['prioridad'] ?? 0) == 2; ?>
                <div class="card" style="padding:2rem;position:relative;<?= $isPopular ? 'border:2px solid var(--accent);' : '' ?>">
                    <?php if ($isPopular): ?>
                        <span class="badge" style="position:absolute;top:-12px;right:1rem;background:var(--accent);color:#fff;padding:.25rem .75rem;">Más Popular</span>
                    <?php endif; ?>

                    <h3 style="margin-bottom:.5rem;"><?= htmlspecialchars($plan['nombre']) ?></h3>

                    <div style="margin-bottom:1rem;">
                        <?php if (($plan['precio'] ?? 0) == 0): ?>
                            <span style="font-size:2rem;font-weight:700;">Gratis</span>
                        <?php else: ?>
                            <span style="font-size:2rem;font-weight:700;">$<?= number_format($plan['precio'], 0, ',', '.') ?></span>
                            <span style="color:var(--text-muted);">/mes</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($plan['descripcion'])): ?>
                        <p style="color:var(--text-secondary);margin-bottom:1.5rem;line-height:1.6;font-size:.95rem;">
                            <?= htmlspecialchars($plan['descripcion']) ?>
                        </p>
                    <?php endif; ?>

                    <ul style="list-style:none;padding:0;margin:0 0 2rem;">
                        <?php if (isset($plan['max_fotos'])): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                Hasta <?= intval($plan['max_fotos']) ?> fotos
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($plan['badge'])): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                Badge: <?= htmlspecialchars($plan['badge']) ?>
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($plan['estadisticas'])): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                Estadísticas de visitas
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($plan['noticia_mensual'])): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                Noticia mensual incluida
                            </li>
                        <?php endif; ?>
                        <?php if (!empty($plan['banner_portada'])): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                Banner en portada
                            </li>
                        <?php endif; ?>
                        <?php if (isset($plan['max_cupos']) && $plan['max_cupos'] > 0): ?>
                            <li style="padding:.4rem 0;display:flex;gap:.5rem;align-items:center;">
                                <span style="color:var(--accent);">✓</span>
                                <?= intval($plan['max_cupos']) ?> cupos disponibles
                            </li>
                        <?php endif; ?>
                    </ul>

                    <a href="/contacto?asunto=<?= urlencode('Plan ' . $plan['nombre']) ?>" class="btn <?= $isPopular ? '' : 'btn-outline' ?>" style="display:block;text-align:center;">
                        <?= ($plan['precio'] ?? 0) == 0 ? 'Comenzar gratis' : 'Contratar plan' ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
