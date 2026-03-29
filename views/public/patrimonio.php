<?php /** @var array $negocios @var array $categorias */ ?>

<div class="hero" style="background:linear-gradient(135deg,var(--primary),var(--secondary));padding:3rem 0;text-align:center;color:#fff;">
    <div class="container">
        <h1 style="font-size:2.5rem;margin-bottom:.75rem;">Patrimonio y Cultura</h1>
        <p style="font-size:1.15rem;opacity:.9;max-width:600px;margin:0 auto;">
            Descubre la rica herencia colonial alemana y el patrimonio cultural de Puerto Octay, a orillas del Lago Llanquihue.
        </p>
    </div>
</div>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Patrimonio y Cultura</span>
</nav>

<div class="container">
    <div class="section">
        <?php if (!empty($negocios)): ?>
            <div class="card-grid">
                <?php foreach ($negocios as $neg): ?>
                    <a href="/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($neg['foto_principal'])): ?>
                            <img src="<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" style="width:100%;height:200px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:200px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:3rem;opacity:.5;">🏛</span>
                            </div>
                        <?php endif; ?>
                        <div style="padding:1.25rem;">
                            <h3 style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['nombre']) ?></h3>
                            <?php if (!empty($neg['categoria_nombre'])): ?>
                                <span class="badge" style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($neg['verificado'])): ?>
                                <span class="badge badge-success">✓</span>
                            <?php endif; ?>
                            <?php if (!empty($neg['direccion'])): ?>
                                <p style="color:var(--text-muted);font-size:.9rem;margin-top:.5rem;">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($neg['descripcion_corta'])): ?>
                                <p style="color:var(--text-secondary);font-size:.9rem;margin-top:.5rem;line-height:1.5;">
                                    <?= htmlspecialchars(mb_strimwidth($neg['descripcion_corta'], 0, 120, '...')) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <span style="font-size:3rem;display:block;margin-bottom:1rem;">🏛</span>
                <p>No hay lugares de patrimonio registrados aún.</p>
                <a href="/directorio" class="btn" style="margin-top:1rem;">Ver directorio completo</a>
            </div>
        <?php endif; ?>
    </div>
</div>
