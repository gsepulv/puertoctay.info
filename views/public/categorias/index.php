<?php /** @var array $categorias */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Categorías</span>
</nav>

<div class="container">
    <div class="section">
        <h1 style="margin-bottom:2rem;">Categorías</h1>

        <?php if (!empty($categorias)): ?>
            <div class="cat-grid">
                <?php foreach ($categorias as $cat): ?>
                    <a href="/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
                        <span style="font-size:2.5rem;display:block;margin-bottom:.75rem;"><?= htmlspecialchars($cat['icono'] ?? '&#128193;') ?></span>
                        <h3><?= htmlspecialchars($cat['nombre']) ?></h3>
                        <span style="color:var(--text-muted);font-size:.9rem;">
                            <?= intval($cat['total'] ?? $cat['negocios_count'] ?? 0) ?> <?= (intval($cat['total'] ?? $cat['negocios_count'] ?? 0)) === 1 ? 'negocio' : 'negocios' ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay categorías disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
