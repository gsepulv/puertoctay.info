<h1 style="margin-bottom: 2rem;">Mis Favoritos</h1>

<?php if (empty($favoritos)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-light); margin-bottom: 1rem;">Aún no has guardado ningún favorito.</p>
        <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary">Explorar directorio</a>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;">
        <?php foreach ($favoritos as $fav): ?>
            <div class="card" style="padding: 1.25rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h3 style="font-size: 1rem; margin-bottom: 0.25rem;">
                            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($fav['slug']) ?>" style="color: var(--text); text-decoration: none;">
                                <?= htmlspecialchars($fav['nombre']) ?>
                            </a>
                        </h3>
                        <span style="font-size: 0.8rem; color: var(--text-light);"><?= $fav['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($fav['categoria_nombre'] ?? '') ?></span>
                    </div>
                    <button onclick="toggleFav(<?= (int)$fav['id'] ?>, this)" class="btn-fav" style="background: none; border: none; cursor: pointer; font-size: 1.3rem; color: #EF4444;" title="Quitar de favoritos">♥</button>
                </div>
                <?php if (!empty($fav['descripcion_corta'])): ?>
                    <p style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-light); line-height: 1.5;"><?= htmlspecialchars(mb_substr($fav['descripcion_corta'], 0, 120)) ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function toggleFav(negocioId, btn) {
    fetch('<?= SITE_URL ?>/api/favorito', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'negocio_id=' + negocioId
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok && data.action === 'removed') {
            btn.closest('.card').remove();
        }
    });
}
</script>
