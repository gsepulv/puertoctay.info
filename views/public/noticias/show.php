<?php /** @var array $noticia @var array $relacionadas */ ?>
<?= SeoHelper::metaTags($noticia['titulo'], $noticia['bajada'] ?? '', $noticia['foto_destacada'] ?? '') ?? '' ?>
<?= SeoHelper::schemaNewsArticle($noticia) ?? '' ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <a href="/noticias">Noticias</a>
    <span>/</span>
    <span><?= htmlspecialchars($noticia['titulo']) ?></span>
</nav>

<div class="container-narrow">
    <article>
        <?php if (!empty($noticia['categoria_nombre'])): ?>
            <span class="badge" style="margin-bottom:1rem;display:inline-block;"><?= htmlspecialchars($noticia['categoria_nombre']) ?></span>
        <?php endif; ?>

        <h1 style="margin-bottom:1rem;font-size:2.25rem;line-height:1.3;"><?= htmlspecialchars($noticia['titulo']) ?></h1>

        <?php if (!empty($noticia['bajada'])): ?>
            <p style="font-size:1.2rem;color:var(--text-secondary);line-height:1.7;margin-bottom:1.5rem;">
                <?= htmlspecialchars($noticia['bajada']) ?>
            </p>
        <?php endif; ?>

        <!-- Meta line -->
        <div style="display:flex;flex-wrap:wrap;gap:1.5rem;color:var(--text-muted);font-size:.9rem;margin-bottom:2rem;padding-bottom:1.5rem;border-bottom:1px solid var(--border);">
            <?php if (!empty($noticia['autor'])): ?>
                <span>&#128100; <?= htmlspecialchars($noticia['autor']) ?></span>
            <?php endif; ?>
            <?php if (!empty($noticia['publicado_en'])): ?>
                <span>&#128197; <?= date('d \d\e F, Y', strtotime($noticia['publicado_en'])) ?></span>
            <?php endif; ?>
            <?php
            $wordCount = str_word_count(strip_tags($noticia['contenido'] ?? ''));
            $readingTime = max(1, ceil($wordCount / 200));
            ?>
            <span>&#128337; <?= $readingTime ?> min de lectura</span>
            <?php if (isset($noticia['visitas'])): ?>
                <span>&#128065; <?= number_format($noticia['visitas']) ?> visitas</span>
            <?php endif; ?>
        </div>

        <!-- Featured image -->
        <?php if (!empty($noticia['foto_destacada'])): ?>
            <div style="margin-bottom:2rem;border-radius:var(--radius-lg);overflow:hidden;">
                <img src="<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" style="width:100%;height:auto;display:block;">
            </div>
        <?php else: ?>
            <div style="width:100%;height:300px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;margin-bottom:2rem;">
                <span style="font-size:4rem;opacity:.4;">&#128240;</span>
            </div>
        <?php endif; ?>

        <!-- Article content -->
        <div class="article-content" style="line-height:1.9;font-size:1.05rem;">
            <?= $noticia['contenido'] ?>
        </div>

        <!-- Share buttons -->
        <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--border);">
            <p style="font-weight:600;margin-bottom:1rem;">Compartir</p>
            <div style="display:flex;gap:.75rem;">
                <?php $shareUrl = urlencode(SITE_URL . '/noticia/' . ($noticia['slug'] ?? $noticia['id'])); ?>
                <?php $shareTitle = urlencode($noticia['titulo']); ?>
                <a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>','fb','width=600,height=400')" class="btn btn-outline" style="font-size:.9rem;">
                    Facebook
                </a>
                <a href="javascript:void(0)" onclick="window.open('https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>','tw','width=600,height=400')" class="btn btn-outline" style="font-size:.9rem;">
                    X
                </a>
                <a href="javascript:void(0)" onclick="window.open('https://wa.me/?text=<?= $shareTitle ?>%20<?= $shareUrl ?>','wa','width=600,height=400')" class="btn btn-outline" style="font-size:.9rem;">
                    WhatsApp
                </a>
            </div>
        </div>
    </article>

    <!-- Related articles -->
    <?php if (!empty($relacionadas)): ?>
        <div class="section" style="margin-top:3rem;">
            <h2 style="margin-bottom:1.5rem;">Noticias relacionadas</h2>
            <div class="card-grid-sm">
                <?php foreach ($relacionadas as $rel): ?>
                    <a href="/noticia/<?= htmlspecialchars($rel['slug'] ?? $rel['id']) ?>" class="card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($rel['imagen_portada'])): ?>
                            <img src="<?= htmlspecialchars($rel['imagen_portada']) ?>" alt="<?= htmlspecialchars($rel['titulo']) ?>" style="width:100%;height:150px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:150px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:2rem;opacity:.4;">&#128240;</span>
                            </div>
                        <?php endif; ?>
                        <div style="padding:1rem;">
                            <h4 style="margin-bottom:.25rem;"><?= htmlspecialchars($rel['titulo']) ?></h4>
                            <small style="color:var(--text-muted);">
                                <?= !empty($rel['fecha_publicacion']) ? date('d/m/Y', strtotime($rel['fecha_publicacion'])) : '' ?>
                            </small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
