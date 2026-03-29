<?php /** @var array $categoria @var array $negocios @var int $totalNegocios */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <a href="/categorias">Categorías</a>
    <span>/</span>
    <span><?= htmlspecialchars($categoria['nombre']) ?></span>
</nav>

<div class="container">
    <div class="section">
        <h1 style="margin-bottom:.5rem;">
            <?= $categoria['emoji'] ?? "" ?> <?= htmlspecialchars($categoria['nombre']) ?>
        </h1>
        <p style="color:var(--text-muted);margin-bottom:2rem;">
            <?= intval($totalNegocios) ?> <?= $totalNegocios === 1 ? 'negocio encontrado' : 'negocios encontrados' ?>
        </p>

        <?php if (!empty($categoria['descripcion'])): ?>
            <p style="margin-bottom:2rem;line-height:1.7;"><?= nl2br(htmlspecialchars($categoria['descripcion'])) ?></p>
        <?php endif; ?>

        <?php if (!empty($negocios)): ?>
            <div class="card-grid">
                <?php foreach ($negocios as $neg): ?>
                    <a href="/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($neg['foto_principal'])): ?>
                            <img src="<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" style="width:100%;height:200px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:200px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:3rem;opacity:.5;">&#128444;</span>
                            </div>
                        <?php endif; ?>
                        <div style="padding:1.25rem;">
                            <h3 style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['nombre']) ?></h3>
                            <?php if (!empty($neg['categoria_nombre'])): ?>
                                <span class="badge" style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($neg['verificado'])): ?>
                                <span class="badge badge-success">&#10003;</span>
                            <?php endif; ?>
                            <?php if (!empty($neg['direccion'])): ?>
                                <p style="color:var(--text-muted);font-size:.9rem;margin-top:.5rem;">&#128205; <?= htmlspecialchars($neg['direccion']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($neg['descripcion'])): ?>
                                <p style="color:var(--text-secondary);font-size:.9rem;margin-top:.5rem;line-height:1.5;"><?= htmlspecialchars(mb_strimwidth($neg['descripcion'], 0, 120, '...')) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay negocios en esta categoría.</p>
                <a href="/directorio" class="btn">Ver directorio completo</a>
            </div>
        <?php endif; ?>
    </div>
</div>
