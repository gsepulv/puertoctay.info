<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <a href="<?= SITE_URL ?>/categorias">Categorias</a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($categoria['nombre']) ?></span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h1><?= $categoria['emoji'] ?? '' ?> <?= htmlspecialchars($categoria['nombre']) ?></h1>
            <p><?= (int)$totalNegocios ?> <?= (int)$totalNegocios === 1 ? 'negocio encontrado' : 'negocios encontrados' ?></p>
        </div>

        <?php if (!empty($categoria['descripcion'])): ?>
        <div class="mb-2">
            <p><?= nl2br(htmlspecialchars($categoria['descripcion'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($negocios)): ?>
        <div class="card-grid">
            <?php foreach ($negocios as $neg): ?>
            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card card-ejemplo-wrapper">
                        <?php if (empty($neg['verificado'])): ?><span class="card-ejemplo">EJEMPLO</span><?php endif; ?>
                <?php if (!empty($neg['foto_principal'])): ?>
                <div class="card-img">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" loading="lazy">
                </div>
                <?php else: ?>
                <div class="card-img card-img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <span class="badge badge-primary"><?= $categoria['emoji'] ?? '' ?> <?= htmlspecialchars($categoria['nombre']) ?></span>
                    <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                    <?php if (!empty($neg['direccion'])): ?>
                    <p class="text-sm text-light">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($neg['descripcion_corta'])): ?>
                    <p class="text-sm"><?= htmlspecialchars(mb_strimwidth($neg['descripcion_corta'], 0, 100, '...')) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($neg['verificado'])): ?>
                    <span class="badge badge-green">✓ Verificado</span>
                    <?php endif; ?>
                    <?php if (!empty($neg['plan_badge'])): ?>
                    <span class="badge badge-accent"><?= htmlspecialchars($neg['plan_badge']) ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>🔎 No hay negocios en esta categoria por el momento.</p>
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary">Ver directorio completo</a>
        </div>
        <?php endif; ?>
    </div>
</section>
