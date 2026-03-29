<!-- Hero Banner -->
<section class="section" style="background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%); color: #fff; padding: 3rem 1rem; text-align: center; margin-bottom: 2rem; border-radius: 12px;">
    <h1 style="font-size: 2.2rem; margin-bottom: 0.5rem;">
        <?= ($esTurismo ?? false) ? 'Turismo y Atractivos' : 'Directorio Comercial' ?>
    </h1>
    <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">
        <?= ($esTurismo ?? false)
            ? 'Descubre los mejores destinos y atractivos turísticos de Puerto Octay'
            : 'Encuentra comercios, servicios y emprendimientos locales en Puerto Octay' ?>
    </p>
</section>

<?php if (!($esTurismo ?? false) && !empty($categorias)): ?>
<!-- Category Filter Pills -->
<div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 2rem; justify-content: center;">
    <a href="<?= SITE_URL ?>/directorio" class="badge" style="padding: 0.5rem 1rem; text-decoration: none; font-size: 0.95rem; border-radius: 20px; <?= empty($_GET['categoria'] ?? '') ? 'background:#2e86c1; color:#fff;' : 'background:#e8f0fe; color:#1a5276;' ?>">
        Todos
    </a>
    <?php foreach ($categorias as $cat): ?>
        <a href="<?= SITE_URL ?>/directorio?categoria=<?= urlencode($cat['slug']) ?>"
           class="badge"
           style="padding: 0.5rem 1rem; text-decoration: none; font-size: 0.95rem; border-radius: 20px; <?= ($_GET['categoria'] ?? '') === $cat['slug'] ? 'background:#2e86c1; color:#fff;' : 'background:#e8f0fe; color:#1a5276;' ?>">
            <?= htmlspecialchars($cat['nombre']) ?>
        </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (empty($negocios)): ?>
<!-- Empty State -->
<div class="section" style="text-align: center; padding: 4rem 1rem;">
    <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.4;">
        <?= ($esTurismo ?? false) ? '&#x1F3D4;' : '&#x1F3EA;' ?>
    </div>
    <h2 style="color: #555; margin-bottom: 0.5rem;">No se encontraron resultados</h2>
    <p style="color: #888;">
        <?= ($esTurismo ?? false)
            ? 'Aún no hay atractivos turísticos registrados.'
            : 'No hay negocios que coincidan con tu búsqueda.' ?>
    </p>
    <?php if (!($esTurismo ?? false) && !empty($_GET['categoria'] ?? '')): ?>
        <a href="<?= SITE_URL ?>/directorio" style="display: inline-block; margin-top: 1rem; color: #2e86c1; text-decoration: underline;">
            Ver todos los negocios
        </a>
    <?php endif; ?>
</div>

<?php else: ?>
<!-- Business Card Grid -->
<div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
    <?php foreach ($negocios as $negocio): ?>
        <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($negocio['slug']) ?>" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
            <!-- Card Image -->
            <?php if (!empty($negocio['foto_principal'])): ?>
                <div class="card-img" style="height: 200px; overflow: hidden;">
                    <img src="<?= SITE_URL ?>/uploads/negocios/<?= htmlspecialchars($negocio['foto_principal']) ?>"
                         alt="<?= htmlspecialchars($negocio['nombre']) ?>"
                         style="width: 100%; height: 100%; object-fit: cover;"
                         loading="lazy">
                </div>
            <?php else: ?>
                <div class="card-img-placeholder" style="height:200px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
            <?php endif; ?>

            <div class="card-body" style="padding: 1rem; flex: 1; display: flex; flex-direction: column;">
                <!-- Category Badge + Verified -->
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; flex-wrap: wrap;">
                    <?php if (!empty($negocio['categoria_nombre'])): ?>
                        <span class="badge" style="font-size: 0.8rem; background: #e8f0fe; color: #1a5276;">
                            <?= htmlspecialchars($negocio['categoria_nombre']) ?>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($negocio['verificado'])): ?>
                        <span class="badge" style="font-size: 0.75rem; background: #d4edda; color: #155724;">
                            &#x2713; Verificado
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Name -->
                <h3 style="margin: 0 0 0.4rem; font-size: 1.15rem; color: #222;">
                    <?= htmlspecialchars($negocio['nombre']) ?>
                </h3>

                <!-- Address -->
                <?php if (!empty($negocio['direccion'])): ?>
                    <p style="margin: 0 0 0.5rem; font-size: 0.9rem; color: #666;">
                        &#x1F4CD; <?= htmlspecialchars($negocio['direccion']) ?>
                    </p>
                <?php endif; ?>

                <!-- Rating Stars -->
                <?php if (!empty($negocio['rating_promedio']) && $negocio['rating_promedio'] > 0): ?>
                    <div class="stars" style="margin-top: auto; display: flex; align-items: center; gap: 0.3rem;">
                        <?php
                        $ratingVal = round($negocio['rating_promedio'], 1);
                        for ($i = 1; $i <= 5; $i++):
                            $starColor = ($i <= floor($ratingVal)) ? '#f39c12' : '#ccc';
                        ?>
                            <span style="color: <?= $starColor ?>; font-size: 1rem;">&#9733;</span>
                        <?php endfor; ?>
                        <span style="font-size: 0.85rem; color: #888; margin-left: 0.2rem;">
                            <?= number_format($ratingVal, 1) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>
