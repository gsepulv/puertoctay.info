<section class="hero">
    <div class="container">
        <h1>Descubre Puerto Octay</h1>
        <p>Encuentra comercios, servicios y atractivos turisticos en nuestra ciudad</p>
        <form class="hero-search" action="<?= SITE_URL ?>/buscar" method="GET">
            <input type="text" name="q" placeholder="Buscar negocios, servicios, turismo..." class="form-group">
            <button type="submit" class="btn btn-accent">🔎 Buscar</button>
        </form>
        <div class="hero-stats">
            <div>
                <span class="hero-stat-value"><?= count($categorias) ?></span>
                <span class="hero-stat-label">Categorias</span>
            </div>
            <div>
                <span class="hero-stat-value"><?php $totalNeg = 0; foreach ($categorias as $c) $totalNeg += (int)($c['total_negocios'] ?? 0); echo $totalNeg; ?></span>
                <span class="hero-stat-label">Negocios</span>
            </div>
            <div>
                <span class="hero-stat-value">365</span>
                <span class="hero-stat-label">dias</span>
            </div>
        </div>
    </div>
</section>

<section class="section section-warm">
    <div class="container">
        <div class="section-header">
            <h2>Categorias</h2>
            <p>Explora por tipo de negocio o servicio</p>
        </div>
        <div class="cat-grid">
            <?php foreach ($categorias as $cat): ?>
            <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($cat['slug']) ?>" class="cat-card">
                <span class="emoji"><?= $cat['emoji'] ?? '' ?></span>
                <span class="name"><?= htmlspecialchars($cat['nombre']) ?></span>
                <span class="count"><?= (int)$cat['total_negocios'] ?> <?= (int)$cat['total_negocios'] === 1 ? 'negocio' : 'negocios' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!empty($destacados)): ?>
<section class="section section-white">
    <div class="container">
        <div class="section-header">
            <h2>Destacados</h2>
            <p>Los mejores lugares recomendados</p>
        </div>
        <div class="card-grid">
            <?php foreach ($destacados as $neg): ?>
            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card">
                    <?php if (empty($neg['verificado'])): ?><span class="card-ejemplo">EJEMPLO</span><?php endif; ?>
                    <?php if (!empty($neg['foto_principal'])): ?>
                <div class="card-img">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($neg["foto_principal"]) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" loading="lazy">
                </div>
                <?php else: ?>
                <div class="card-img card-img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <?php if (!empty($neg['categoria_nombre'])): ?>
                    <span class="badge badge-primary"><?= $neg['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                    <?php if (!empty($neg['direccion'])): ?>
                    <p class="text-sm text-light">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($neg['verificado'])): ?>
                    <span class="badge badge-green">✓ Verificado</span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-2">
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-outline">Ver todo el directorio</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($ultimasNoticias)): ?>
<section class="section section-warm">
    <div class="container">
        <div class="section-header">
            <h2>📰 Noticias</h2>
            <p>Lo ultimo de Puerto Octay</p>
        </div>
        <div class="card-grid">
            <?php foreach ($ultimasNoticias as $noticia): ?>
            <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($noticia['slug']) ?>" class="card">
                <?php if (!empty($noticia['foto_destacada'])): ?>
                <div class="card-img">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" loading="lazy">
                </div>
                <?php else: ?>
                <div class="card-img card-img-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <?php if (!empty($noticia['categoria_nombre'])): ?>
                    <span class="badge badge-secondary"><?= htmlspecialchars($noticia['categoria_nombre']) ?></span>
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($noticia['titulo']) ?></h3>
                    <p class="text-sm"><?= htmlspecialchars(mb_strimwidth($noticia['bajada'] ?? strip_tags($noticia['contenido'] ?? ''), 0, 120, '...')) ?></p>
                    <div class="card-meta">
                        <span>📅 <?= date('d/m/Y', strtotime($noticia['publicado_en'] ?? $noticia['created_at'])) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-2">
            <a href="<?= SITE_URL ?>/noticias" class="btn btn-outline">Ver todas las noticias</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Temporada activa -->
<?php if (!empty($temporadaActual)): ?>
<section style="padding: 3rem 0; background: var(--bg-warm);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 2rem;">
            <span style="font-size: 2.5rem;"><?= $temporadaActual['emoji'] ?></span>
            <h2 style="margin: 0.5rem 0;"><?= htmlspecialchars($temporadaActual['nombre']) ?></h2>
            <p class="text-light" style="max-width: 600px; margin: 0 auto;"><?= htmlspecialchars($temporadaActual['descripcion'] ?? '') ?></p>
        </div>

        <?php if (!empty($negociosTemporada)): ?>
        <div class="card-grid">
            <?php foreach ($negociosTemporada as $nt): ?>
            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($nt['slug']) ?>" class="card" style="text-decoration: none; color: inherit;">
                <div style="height: 140px; background: var(--bg); border-radius: var(--radius-md) var(--radius-md) 0 0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <?php if (!empty($nt['foto_principal'])): ?>
                        <img src="<?= SITE_URL ?>/uploads/<?= $nt['foto_principal'] ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 2rem; color: var(--text-lighter);"><?= $nt['categoria_emoji'] ?? '📍' ?></span>
                    <?php endif; ?>
                </div>
                <div style="padding: 1rem;">
                    <h3 style="font-size: 1rem; margin-bottom: 0.25rem;"><?= htmlspecialchars($nt['nombre']) ?></h3>
                    <span style="font-size: 0.8rem; color: var(--text-light);"><?= $nt['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($nt['categoria_nombre'] ?? '') ?></span>
                    <?php if (!empty($nt['promocion'])): ?>
                        <div style="margin-top: 0.5rem; padding: 0.3rem 0.6rem; background: #FEF3C7; border-radius: 50px; display: inline-block; font-size: 0.75rem; font-weight: 600; color: #92400E;">
                            🏷️ <?= htmlspecialchars($nt['promocion']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p style="text-align: center; color: var(--text-light);">Próximamente: negocios destacados para esta temporada.</p>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<section style="padding: 3rem 0; background: var(--bg-warm);">
    <div class="container" style="text-align: center;">
        <span style="font-size: 2.5rem;">⛵</span>
        <h2 style="margin: 0.5rem 0;">Descubre Puerto Octay todo el año</h2>
        <p class="text-light" style="max-width: 600px; margin: 0 auto;">Gastronomía alemana-chilena, patrimonio colonial, lago Llanquihue y volcanes. Siempre hay algo por descubrir.</p>
        <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary" style="margin-top: 1rem;">Ver directorio completo</a>
    </div>
</section>
<?php endif; ?>

<!-- CTA: Registrar comercio -->
<section style="background: var(--primary-dark); color: #fff; padding: 4rem 0;">
    <div class="container" style="max-width: 800px; text-align: center;">
        <h2 style="color: #fff; font-size: 2rem; margin-bottom: 0.5rem;">¿Tienes un comercio en Puerto Octay?</h2>
        <p style="font-size: 1.1rem; opacity: 0.8; margin-bottom: 2rem;">Regístrate gratis por 30 días y obtén:</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 0.6rem; text-align: left; margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> Página exclusiva de tu negocio
            </div>
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> Logo y foto de portada
            </div>
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> Botón directo a tu WhatsApp
            </div>
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> Enlace a Google Maps
            </div>
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> 1 red social vinculada
            </div>
            <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem;">
                <span style="color: #22C55E; font-weight: 700;">✓</span> Visibilidad en el directorio
            </div>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= SITE_URL ?>/registrar-comercio" class="btn btn-lg" style="background: var(--accent); color: var(--primary-dark); font-size: 1.05rem; padding: 0.9rem 2.5rem;">
                Registrar mi comercio
            </a>
            <a href="<?= SITE_URL ?>/contacto" class="btn btn-lg btn-outline" style="border-color: rgba(255,255,255,0.3); color: #fff;">
                Contáctanos
            </a>
        </div>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="card-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            <div class="card" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: var(--white); padding: 2rem; text-align: center;">
                <h3 style="color: var(--white);">📍 Mapa Interactivo</h3>
                <p>Explora todos los negocios y atractivos en el mapa</p>
                <a href="<?= SITE_URL ?>/mapa" class="btn btn-accent">Ver Mapa</a>
            </div>
            <div class="card" style="background: linear-gradient(135deg, var(--secondary), var(--secondary-light)); color: var(--white); padding: 2rem; text-align: center;">
                <h3 style="color: var(--white);">🏛 Turismo y Patrimonio</h3>
                <p>Descubre los atractivos turísticos de Puerto Octay</p>
                <a href="<?= SITE_URL ?>/turismo" class="btn btn-accent">Ver Turismo</a>
            </div>
        </div>
    </div>
</section>
