<section class="section">
    <h2 class="section-title"><?= !empty($esTurismo) ? 'Atractivos Turísticos' : 'Directorio' ?></h2>
    <p class="mb-2">
        <?= !empty($esTurismo)
            ? 'Descubre los atractivos turísticos de Puerto Octay y sus alrededores.'
            : 'Todos los negocios, comercios y servicios de Puerto Octay.' ?>
    </p>

    <?php if (!empty($categorias) && empty($esTurismo)): ?>
    <div style="margin-bottom:1.5rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
        <a href="<?= SITE_URL ?>/directorio" class="btn btn-sm btn-primary">Todos</a>
        <?php foreach ($categorias as $cat): ?>
            <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="btn btn-sm" style="background:#e9ecef;color:#333;"><?= $cat['emoji'] ?> <?= htmlspecialchars($cat['nombre']) ?></a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (empty($negocios)): ?>
        <div class="empty-state">
            <p>No se encontraron resultados.</p>
        </div>
    <?php else: ?>
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
                        <?php if (!empty($neg['categoria_emoji'])): ?>
                            <span><?= $neg['categoria_emoji'] ?> <?= htmlspecialchars($neg['categoria_nombre'] ?? '') ?></span>
                        <?php endif; ?>
                        <?php if (!empty($neg['direccion'])): ?>
                            <span>📍 <?= htmlspecialchars(mb_substr($neg['direccion'], 0, 40)) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
