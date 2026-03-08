<!-- Hero -->
<section style="text-align:center; padding:2rem 0 3rem;">
    <h1 style="font-size:2.5rem; margin-bottom:0.5rem;">⛵ <?= htmlspecialchars(SITE_NAME) ?></h1>
    <p style="font-size:1.2rem; color:#555; max-width:600px; margin:0 auto 1.5rem;"><?= htmlspecialchars(SITE_TAGLINE) ?></p>

    <form action="<?= SITE_URL ?>/buscar" method="GET" class="search-bar" style="max-width:500px; margin:0 auto;">
        <input type="text" name="q" placeholder="Buscar negocios, servicios, atractivos...">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
</section>

<!-- Categorías -->
<?php if (!empty($categorias)): ?>
<section class="section">
    <h2 class="section-title">Categorías</h2>
    <div class="cat-grid">
        <?php foreach ($categorias as $cat): ?>
        <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
            <span class="emoji"><?= $cat['emoji'] ?></span>
            <span class="name"><?= htmlspecialchars($cat['nombre']) ?></span>
            <span class="count"><?= (int)$cat['total_negocios'] ?> negocio<?= (int)$cat['total_negocios'] !== 1 ? 's' : '' ?></span>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="text-center mt-2"><a href="<?= SITE_URL ?>/categorias" class="btn btn-sm btn-primary">Ver todas</a></p>
</section>
<?php endif; ?>

<!-- Destacados -->
<?php if (!empty($destacados)): ?>
<section class="section">
    <h2 class="section-title">Negocios Destacados</h2>
    <div class="card-grid">
        <?php foreach ($destacados as $neg): ?>
        <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="color:inherit;">
            <?php if (!empty($neg['foto_principal'])): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" class="card-img" loading="lazy">
            <?php else: ?>
                <div class="card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;color:#ccc;">⛵</div>
            <?php endif; ?>
            <div class="card-body">
                <h3>
                    <?= htmlspecialchars($neg['nombre']) ?>
                    <?php if (!empty($neg['plan_badge'])): ?>
                        <span class="badge badge-gold"><?= htmlspecialchars($neg['plan_nombre'] ?? 'Destacado') ?></span>
                    <?php endif; ?>
                </h3>
                <?php if (!empty($neg['descripcion_corta'])): ?>
                    <p><?= htmlspecialchars(mb_substr($neg['descripcion_corta'], 0, 120)) ?></p>
                <?php endif; ?>
                <div class="card-meta">
                    <?php if (!empty($neg['categoria_emoji'])): ?>
                        <span><?= $neg['categoria_emoji'] ?> <?= htmlspecialchars($neg['categoria_nombre'] ?? '') ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <p class="text-center mt-2"><a href="<?= SITE_URL ?>/directorio" class="btn btn-sm btn-secondary">Ver directorio completo</a></p>
</section>
<?php endif; ?>

<!-- CTA -->
<section style="text-align:center; padding:2rem; background:#fff; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-top:1rem;">
    <h2 style="font-size:1.5rem;">Descubre Puerto Octay</h2>
    <p style="color:#666; max-width:500px; margin:0.5rem auto 1rem;">Explora el mapa interactivo con todos los puntos de interés de la ciudad y sus alrededores.</p>
    <a href="<?= SITE_URL ?>/mapa" class="btn btn-primary">Ver mapa interactivo</a>
    <a href="<?= SITE_URL ?>/turismo" class="btn btn-secondary" style="margin-left:0.5rem;">Atractivos turísticos</a>
</section>
