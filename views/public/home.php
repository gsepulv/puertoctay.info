<!-- HERO -->
<section class="hero">
    <div class="container">
        <h1>Descubre Puerto Octay</h1>
        <p>Explora los mejores negocios, atractivos turísticos, gastronomía y patrimonio a orillas del Lago Llanquihue.</p>
        <form class="hero-search" action="<?= SITE_URL ?>/buscar" method="GET">
            <input type="text" name="q" placeholder="Buscar negocios, restaurantes, turismo..." aria-label="Buscar">
            <button type="submit">Buscar</button>
        </form>
        <?php
        $totalNegocios = count($destacados ?? []);
        $totalCategorias = count($categorias ?? []);
        ?>
        <div class="hero-stats">
            <div>
                <span class="hero-stat-value"><?= $totalCategorias ?></span>
                <span class="hero-stat-label">Categorías</span>
            </div>
            <div>
                <span class="hero-stat-value"><?= $totalNegocios ?>+</span>
                <span class="hero-stat-label">Negocios</span>
            </div>
            <div>
                <span class="hero-stat-value">365</span>
                <span class="hero-stat-label">Días de turismo</span>
            </div>
        </div>
    </div>
</section>

<!-- CATEGORÍAS -->
<?php if (!empty($categorias)): ?>
<section class="section section-white">
    <div class="container">
        <div class="section-header">
            <h2>Explora por Categoría</h2>
            <p>Encuentra exactamente lo que buscas en Puerto Octay</p>
        </div>
        <div class="cat-grid">
            <?php foreach ($categorias as $cat): ?>
                <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
                    <span class="emoji"><?= $cat['emoji'] ?? '📌' ?></span>
                    <span class="name"><?= htmlspecialchars($cat['nombre']) ?></span>
                    <span class="count"><?= (int)($cat['total_negocios'] ?? 0) ?> negocios</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- DESTACADOS -->
<?php if (!empty($destacados)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Negocios Destacados</h2>
            <p>Los mejores lugares para visitar, comer y disfrutar</p>
        </div>
        <div class="card-grid">
            <?php foreach ($destacados as $neg): ?>
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="text-decoration:none; color:inherit;">
                    <?php if (!empty($neg['foto_principal'])): ?>
                        <img class="card-img" src="<?= SITE_URL ?>/uploads/negocios/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="card-img-placeholder"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta" style="margin-top:0; margin-bottom:0.5rem;">
                            <span class="badge badge-primary"><?= htmlspecialchars($neg['categoria_nombre'] ?? 'General') ?></span>
                            <?php if (!empty($neg['verificado'])): ?>
                                <span class="badge badge-green">Verificado</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                        <?php if (!empty($neg['direccion'])): ?>
                            <p style="margin-bottom:0.3rem;">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($neg['rating_promedio']) && $neg['rating_promedio'] > 0): ?>
                            <div class="card-meta">
                                <span class="stars"><?= str_repeat('★', round($neg['rating_promedio'])) ?><?= str_repeat('☆', 5 - round($neg['rating_promedio'])) ?></span>
                                <span><?= number_format($neg['rating_promedio'], 1) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-outline">Ver todo el directorio</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- NOTICIAS -->
<?php if (!empty($ultimasNoticias)): ?>
<section class="section section-warm">
    <div class="container">
        <div class="section-header">
            <h2>Últimas Noticias</h2>
            <p>Novedades y eventos de Puerto Octay</p>
        </div>
        <div class="card-grid">
            <?php foreach ($ultimasNoticias as $not): ?>
                <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($not['slug']) ?>" class="card" style="text-decoration:none; color:inherit;">
                    <?php if (!empty($not['foto_destacada'])): ?>
                        <img class="card-img" src="<?= SITE_URL ?>/uploads/noticias/<?= htmlspecialchars($not['foto_destacada']) ?>" alt="<?= htmlspecialchars($not['titulo']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="card-img-placeholder"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if (!empty($not['categoria_nombre'])): ?>
                            <span class="badge badge-secondary mb-1"><?= htmlspecialchars($not['categoria_nombre']) ?></span>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($not['titulo']) ?></h3>
                        <p><?= htmlspecialchars(mb_substr(strip_tags($not['bajada'] ?? $not['contenido'] ?? ''), 0, 100)) ?>...</p>
                        <div class="card-meta">
                            <span><?= date('d M Y', strtotime($not['publicado_en'] ?? $not['created_at'])) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="<?= SITE_URL ?>/noticias" class="btn btn-outline">Todas las noticias</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA: Mapa y Turismo -->
<section class="section">
    <div class="container">
        <div class="grid-2">
            <div class="card" style="border:none; background:linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color:var(--white); padding:2.5rem;">
                <h3 style="color:var(--white); font-size:1.4rem; margin-bottom:0.6rem;">🗺 Mapa Interactivo</h3>
                <p style="opacity:0.85; margin-bottom:1.5rem;">Explora todos los negocios y atractivos de Puerto Octay en nuestro mapa interactivo.</p>
                <a href="<?= SITE_URL ?>/mapa" class="btn btn-accent">Abrir mapa</a>
            </div>
            <div class="card" style="border:none; background:linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%); color:var(--white); padding:2.5rem;">
                <h3 style="color:var(--white); font-size:1.4rem; margin-bottom:0.6rem;">🏔 Turismo y Patrimonio</h3>
                <p style="opacity:0.85; margin-bottom:1.5rem;">Descubre los atractivos turísticos y el patrimonio colonial alemán de la zona.</p>
                <a href="<?= SITE_URL ?>/turismo" class="btn btn-accent">Explorar turismo</a>
            </div>
        </div>
    </div>
</section>
