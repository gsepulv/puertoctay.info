<section class="section-sm" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary)); color: var(--white); padding: 3rem 0;">
    <div class="container text-center">
        <h1 style="color: var(--white);">🏛 Patrimonio de Puerto Octay</h1>
        <p>Descubre la riqueza patrimonial y cultural de nuestra ciudad</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($negocios)): ?>
        <div class="card-grid">
            <?php foreach ($negocios as $neg): ?>
            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card">
                <?php if (!empty($neg['foto_principal'])): ?>
                <div class="card-img">
                    <img src="<?= SITE_URL ?>/uploads/negocios/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" loading="lazy">
                </div>
                <?php else: ?>
                <div class="card-img card-img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <?php if (!empty($neg['categoria_nombre'])): ?>
                    <span class="badge badge-primary"><?= $neg['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                    <?php if (!empty($neg['direccion'])): ?>
                    <p class="text-sm text-light">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($neg['descripcion_corta'])): ?>
                    <p class="text-sm"><?= htmlspecialchars(mb_strimwidth($neg['descripcion_corta'], 0, 120, '...')) ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>🏛 No hay sitios patrimoniales registrados por el momento.</p>
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary">Ver directorio</a>
        </div>
        <?php endif; ?>
    </div>
</section>
