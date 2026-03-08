<section class="section">
    <h2 class="section-title">🏛️ Patrimonio y Cultura</h2>
    <p class="mb-2">Arquitectura alemana, museos, sitios históricos, bibliotecas y centros culturales de Puerto Octay, a orillas del Lago Llanquihue.</p>

    <?php if (empty($negocios)): ?>
        <div class="empty-state">
            <p>Próximamente se publicarán los atractivos patrimoniales y culturales de Puerto Octay.</p>
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary mt-2">Ver directorio completo</a>
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($negocios as $neg): ?>
            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="color:inherit;">
                <?php if (!empty($neg['foto_principal'])): ?>
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" class="card-img" loading="lazy">
                <?php else: ?>
                    <div class="card-img" style="display:flex;align-items:center;justify-content:center;font-size:3rem;color:#ccc;">🏛️</div>
                <?php endif; ?>
                <div class="card-body">
                    <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                    <?php if (!empty($neg['descripcion_corta'])): ?>
                        <p><?= htmlspecialchars(mb_substr($neg['descripcion_corta'], 0, 120)) ?></p>
                    <?php endif; ?>
                    <div class="card-meta">
                        <?php if (!empty($neg['categoria_emoji'])): ?>
                            <span><?= $neg['categoria_emoji'] ?> <?= htmlspecialchars($neg['categoria_nombre']) ?></span>
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
