<section class="section">
    <h2 class="section-title">Categorías</h2>
    <p class="mb-2">Explora los negocios y servicios de Puerto Octay organizados por categoría.</p>

    <div class="cat-grid">
        <?php foreach ($categorias as $cat): ?>
        <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
            <span class="emoji"><?= $cat['emoji'] ?></span>
            <span class="name"><?= htmlspecialchars($cat['nombre']) ?></span>
            <span class="count"><?= (int) $cat['total_negocios'] ?> negocio<?= (int) $cat['total_negocios'] !== 1 ? 's' : '' ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</section>
