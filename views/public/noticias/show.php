<?= SeoHelper::metaTags($noticia['titulo'], $noticia['bajada'] ?? '', $noticia['foto_destacada'] ? SITE_URL.'/uploads/'.$noticia['foto_destacada'] : '') ?? '' ?>
<?= SeoHelper::schemaNewsArticle($noticia) ?? '' ?>

<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <a href="<?= SITE_URL ?>/noticias">Noticias</a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($noticia['titulo']) ?></span>
    </nav>
<?php if (empty($noticia["featured"])): ?>
<div class="banner-ejemplo" style="max-width:800px;margin:0 auto 1rem;padding:0.8rem 1.2rem;">
    Este contenido es un ejemplo demostrativo. La informacion mostrada no es real.
</div>
<?php endif; ?>
</div>

<section class="section">
    <div class="container-narrow">
        <?php if (!empty($noticia['categoria_nombre'])): ?>
        <a href="<?= SITE_URL ?>/noticias/categoria/<?= htmlspecialchars($noticia['categoria_slug']) ?>" class="badge badge-secondary mb-1" style="display: inline-block;"><?= $noticia['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($noticia['categoria_nombre']) ?></a>
        <?php endif; ?>

        <h1><?= htmlspecialchars($noticia['titulo']) ?></h1>

        <?php if (!empty($noticia['bajada'])): ?>
        <p class="text-light" style="font-size: 1.15rem; margin-bottom: 1rem;"><?= htmlspecialchars($noticia['bajada']) ?></p>
        <?php endif; ?>

        <div class="flex flex-wrap items-center text-sm text-light mb-2" style="gap: 1rem;">
            <?php if (!empty($noticia['autor'])): ?>
            <span>👤 <?= htmlspecialchars($noticia['autor']) ?></span>
            <?php endif; ?>
            <span>📅 <?= date('d/m/Y', strtotime($noticia['publicado_en'])) ?></span>
            <?php if (!empty($tiempo_lectura)): ?>
            <span>🕑 <?= (int)$tiempo_lectura ?> min de lectura</span>
            <?php endif; ?>
            <?php if (isset($noticia['visitas'])): ?>
            <span>👁 <?= number_format((int)$noticia['visitas']) ?> visitas</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($noticia['foto_destacada'])): ?>
        <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 2rem;">
            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" style="width: 100%; max-height: 480px; object-fit: cover; display: block;">
        </div>
        <?php else: ?>
        <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: var(--radius-lg); height: 300px; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </div>
        <?php endif; ?>

        <div class="mb-3">
            <?= $noticia['contenido'] ?>
        </div>

        <div class="flex flex-wrap mb-3" style="gap: 0.5rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
            <span class="text-sm text-light" style="margin-right: 0.5rem;">Compartir:</span>
            <button class="btn btn-sm btn-outline" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href),'_blank','width=600,height=400')">Facebook</button>
            <button class="btn btn-sm btn-outline" onclick="window.open('https://twitter.com/intent/tweet?url='+encodeURIComponent(window.location.href)+'&text='+encodeURIComponent('<?= addslashes(htmlspecialchars($noticia['titulo'])) ?>'),'_blank','width=600,height=400')">X</button>
            <button class="btn btn-sm btn-outline" onclick="window.open('https://wa.me/?text='+encodeURIComponent('<?= addslashes(htmlspecialchars($noticia['titulo'])) ?> '+window.location.href),'_blank')">WhatsApp</button>
        </div>

        <?php if (!empty($relacionadas)): ?>
        <div class="mt-3">
            <h3 class="mb-2">Noticias relacionadas</h3>
            <div class="card-grid-sm">
                <?php foreach ($relacionadas as $rel): ?>
                <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($rel['slug']) ?>" class="card">
                    <?php if (!empty($rel['foto_destacada'])): ?>
                    <div class="card-img">
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($rel['foto_destacada']) ?>" alt="<?= htmlspecialchars($rel['titulo']) ?>" loading="lazy">
                    </div>
                    <?php else: ?>
                    <div class="card-img card-img-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if (!empty($rel['categoria_nombre'])): ?>
                        <span class="badge badge-secondary"><?= htmlspecialchars($rel['categoria_nombre']) ?></span>
                        <?php endif; ?>
                        <h4><?= htmlspecialchars($rel['titulo']) ?></h4>
                        <div class="card-meta">
                            <span>📅 <?= date('d/m/Y', strtotime($rel['publicado_en'] ?? $rel['created_at'])) ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
<div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">            <p style="font-size: 0.85rem; color: var(--text-lighter); line-height: 1.8;">                📖 Contenido elaborado con información de fuentes públicas.                <a href="<?= SITE_URL ?>/paginas-amigas" style="color: var(--primary);">Ver todas nuestras fuentes en Páginas Amigas</a>.            </p>        </div>
</section>
