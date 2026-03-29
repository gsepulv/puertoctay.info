<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <?php if (!empty($categoriaActual)): ?>
        <a href="<?= SITE_URL ?>/noticias">Noticias</a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($categoriaActual['nombre']) ?></span>
        <?php else: ?>
        <span>Noticias</span>
        <?php endif; ?>
    </nav>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h1>📰 Noticias</h1>
            <p>Mantente informado sobre Puerto Octay</p>
        </div>

        <?php if (!empty($categoriasEditoriales)): ?>
        <div class="flex flex-wrap mb-2" style="gap: 0.5rem;">
            <a href="<?= SITE_URL ?>/noticias" class="btn btn-sm <?= empty($categoriaActual) ? 'btn-primary' : 'btn-outline' ?>">Todas</a>
            <?php foreach ($categoriasEditoriales as $catEd): ?>
            <a href="<?= SITE_URL ?>/noticias/categoria/<?= htmlspecialchars($catEd['slug']) ?>" class="btn btn-sm <?= (!empty($categoriaActual) && $categoriaActual['slug'] === $catEd['slug']) ? 'btn-primary' : 'btn-outline' ?>"><?= $catEd['emoji'] ?? '' ?> <?= htmlspecialchars($catEd['nombre']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($destacada)): ?>
        <div class="card mb-3" style="display: grid; grid-template-columns: 1fr 1fr; overflow: hidden;">
            <?php if (!empty($destacada['foto_destacada'])): ?>
            <div class="card-img" style="height: 100%; min-height: 280px;">
                <img src="<?= SITE_URL ?>/uploads/noticias/<?= htmlspecialchars($destacada['foto_destacada']) ?>" alt="<?= htmlspecialchars($destacada['titulo']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <?php else: ?>
            <div class="card-img card-img-placeholder" style="height: 100%; min-height: 280px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <?php endif; ?>
            <div class="card-body" style="padding: 2rem; display: flex; flex-direction: column; justify-content: center;">
                <?php if (!empty($destacada['categoria_nombre'])): ?>
                <span class="badge badge-secondary mb-1" style="align-self: flex-start;"><?= htmlspecialchars($destacada['categoria_nombre']) ?></span>
                <?php endif; ?>
                <h2 style="margin-bottom: 0.75rem;">
                    <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($destacada['slug']) ?>" style="color: inherit; text-decoration: none;"><?= htmlspecialchars($destacada['titulo']) ?></a>
                </h2>
                <p class="text-light"><?= htmlspecialchars(mb_strimwidth($destacada['bajada'] ?? strip_tags($destacada['contenido'] ?? ''), 0, 200, '...')) ?></p>
                <div class="card-meta mt-1">
                    <span>📅 <?= date('d/m/Y', strtotime($destacada['publicado_en'] ?? $destacada['created_at'])) ?></span>
                </div>
                <div class="mt-1">
                    <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($destacada['slug']) ?>" class="btn btn-primary btn-sm">Leer mas</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($noticias)): ?>
        <div class="card-grid">
            <?php foreach ($noticias as $noticia): ?>
            <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($noticia['slug']) ?>" class="card">
                <?php if (!empty($noticia['foto_destacada'])): ?>
                <div class="card-img">
                    <img src="<?= SITE_URL ?>/uploads/noticias/<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" loading="lazy">
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
                    <p class="text-sm"><?= htmlspecialchars(mb_strimwidth($noticia['bajada'] ?? strip_tags($noticia['contenido'] ?? ''), 0, 100, '...')) ?></p>
                    <div class="card-meta">
                        <span>📅 <?= date('d/m/Y', strtotime($noticia['publicado_en'] ?? $noticia['created_at'])) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <p>📰 No hay noticias disponibles por el momento.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
