<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span><?= ($esTurismo ?? false) ? 'Turismo' : 'Directorio' ?></span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h1><?= ($esTurismo ?? false) ? '🏛 Turismo en Puerto Octay' : 'Directorio de Negocios' ?></h1>
            <p><?= ($esTurismo ?? false) ? 'Descubre los atractivos turisticos, gastronomia y alojamientos' : 'Encuentra comercios, servicios y mas en Puerto Octay' ?></p>
        </div>

        <?php if (!empty($categorias)): ?>
        <div class="flex flex-wrap mb-2" style="gap: 0.5rem;">
            <a href="<?= SITE_URL ?>/<?= ($esTurismo ?? false) ? 'turismo' : 'directorio' ?>" class="btn btn-sm <?= empty($categoriaActual) ? 'btn-primary' : 'btn-outline' ?>">Todas</a>
            <?php foreach ($categorias as $cat): ?>
            <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="btn btn-sm btn-outline"><?= $cat['emoji'] ?? '' ?> <?= htmlspecialchars($cat['nombre']) ?></a>
            <?php endforeach; ?>
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
                    <?php if (!empty($neg['categoria_nombre'])): ?>
                    <span class="badge badge-primary"><?= $neg['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                    <?php endif; ?>
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
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>🔎 No se encontraron negocios en esta seccion.</p>
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary">Ver todo el directorio</a>
        </div>
        <?php endif; ?>
    </div>
</section>
