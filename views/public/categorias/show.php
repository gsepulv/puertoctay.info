<section class="section">
    <h2 class="section-title"><?= $categoria['emoji'] ?> <?= htmlspecialchars($categoria['nombre']) ?></h2>
    <?php if (!empty($categoria['descripcion'])): ?>
        <p class="mb-2"><?= htmlspecialchars($categoria['descripcion']) ?></p>
    <?php endif; ?>

    <?php if (empty($negocios)): ?>
        <div class="empty-state">
            <p>Aún no hay negocios registrados en esta categoría.</p>
            <a href="<?= SITE_URL ?>/categorias" class="btn btn-primary mt-2">Ver todas las categorías</a>
        </div>
    <?php else: ?>
        <p class="mb-2" style="color:#888;"><?= $totalNegocios ?> resultado<?= $totalNegocios !== 1 ? 's' : '' ?></p>
        <div class="card-grid">
            <?php foreach ($negocios as $neg): ?>
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
                            <span class="badge badge-gold">Destacado</span>
                        <?php endif; ?>
                    </h3>
                    <?php if (!empty($neg['descripcion_corta'])): ?>
                        <p><?= htmlspecialchars(mb_substr($neg['descripcion_corta'], 0, 120)) ?></p>
                    <?php endif; ?>
                    <div class="card-meta">
                        <?php if (!empty($neg['direccion'])): ?>
                            <span>📍 <?= htmlspecialchars($neg['direccion']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
