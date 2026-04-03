<h1 style="margin-bottom: 0.5rem;">Mi Cuenta</h1>
<p class="text-light" style="margin-bottom: 2rem;">Bienvenido, <?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="number"><?= $totalFavoritos ?></div>
        <div class="label">Favoritos</div>
    </div>
    <div class="stat-card">
        <div class="number"><?= $totalResenas ?></div>
        <div class="label">Reseñas</div>
    </div>
</div>

<?php if (!empty($ultimasResenas)): ?>
<div class="card">
    <h3 style="margin-bottom: 1rem;">Tus últimas reseñas</h3>
    <?php foreach ($ultimasResenas as $res): ?>
        <div style="padding: 0.75rem 0; border-bottom: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($res['negocio_slug'] ?? '') ?>" style="font-weight: 600; color: var(--primary); text-decoration: none;">
                    <?= htmlspecialchars($res['negocio_nombre'] ?? 'Negocio eliminado') ?>
                </a>
                <span style="color: var(--accent);">
                    <?php for ($i = 1; $i <= 5; $i++): ?><?= $i <= (int)$res['puntuacion'] ? '★' : '☆' ?><?php endfor; ?>
                </span>
            </div>
            <?php if (!empty($res['comentario'])): ?>
                <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: var(--text-light);"><?= htmlspecialchars(mb_substr($res['comentario'], 0, 100)) ?><?= mb_strlen($res['comentario']) > 100 ? '...' : '' ?></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="card">
    <h3 style="margin-bottom: 1rem;">Acciones rápidas</h3>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= SITE_URL ?>/mi-cuenta/favoritos" class="btn btn-primary">Ver mis favoritos</a>
        <a href="<?= SITE_URL ?>/directorio" class="btn btn-outline">Explorar directorio</a>
    </div>
</div>
