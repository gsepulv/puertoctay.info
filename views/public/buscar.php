<section class="section">
    <h2 class="section-title">Buscar</h2>

    <form action="<?= SITE_URL ?>/buscar" method="GET" class="search-bar">
        <input type="text" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Buscar negocios, servicios, atractivos...">
        <select name="tipo">
            <option value="">Todos los tipos</option>
            <option value="comercio" <?= ($tipo ?? '') === 'comercio' ? 'selected' : '' ?>>Comercio</option>
            <option value="atractivo" <?= ($tipo ?? '') === 'atractivo' ? 'selected' : '' ?>>Atractivo</option>
            <option value="servicio" <?= ($tipo ?? '') === 'servicio' ? 'selected' : '' ?>>Servicio</option>
            <option value="gastronomia" <?= ($tipo ?? '') === 'gastronomia' ? 'selected' : '' ?>>Gastronomía</option>
        </select>
        <select name="categoria">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($categoriaId ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if (isset($q) && $q !== ''): ?>
        <p style="color:#888; margin-bottom:1rem;"><?= count($negocios) ?> resultado<?= count($negocios) !== 1 ? 's' : '' ?> para "<?= htmlspecialchars($q) ?>"</p>

        <?php if (empty($negocios)): ?>
            <div class="empty-state">
                <p>No se encontraron resultados. Intenta con otros términos de búsqueda.</p>
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
                        <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
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
        <?php endif; ?>
    <?php endif; ?>
</section>

<style>
@media (max-width: 768px) {
    .search-bar { flex-direction: column; }
}
</style>
