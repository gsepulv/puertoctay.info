<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Categorias</span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h1>Categorias</h1>
            <p>Explora todos los tipos de negocios y servicios disponibles en Puerto Octay</p>
        </div>

        <?php if (!empty($categorias)): ?>
        <div class="cat-grid">
            <?php foreach ($categorias as $cat): ?>
            <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
                <span class="emoji"><?= $cat['emoji'] ?? '' ?></span>
                <span class="name"><?= htmlspecialchars($cat['nombre']) ?></span>
                <span class="count"><?= (int)$cat['total_negocios'] ?> <?= (int)$cat['total_negocios'] === 1 ? 'negocio' : 'negocios' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>No hay categorias disponibles por el momento.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
