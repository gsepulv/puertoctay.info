<?php
$tiempoLectura = $noticia['tiempo_lectura'] ?: Noticia::calcularTiempoLectura($noticia['contenido'] ?? '');
$fechaPublicacion = $noticia['publicado_en'] ?? $noticia['created_at'];
$imagenOg = !empty($noticia['foto_destacada']) ? SITE_URL . '/uploads/' . $noticia['foto_destacada'] : null;
?>

<?= SeoHelper::schemaNewsArticle($noticia) ?? '' ?>

<?php
$extraHead = ($extraHead ?? '') . SeoHelper::metaTags(
    $noticia['titulo'] . ' — ' . SITE_NAME,
    $noticia['bajada'] ?? strip_tags($noticia['contenido'] ?? ''),
    $imagenOg
);
?>

<article style="max-width:800px; margin:0 auto;">
    <!-- Breadcrumb -->
    <div style="margin-bottom:1rem; font-size:0.9rem;">
        <a href="<?= SITE_URL ?>/noticias">Noticias</a>
        <?php if (!empty($noticia['categoria_nombre'])): ?>
            / <a href="<?= SITE_URL ?>/noticias/categoria/<?= htmlspecialchars($noticia['categoria_slug']) ?>"><?= $noticia['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($noticia['categoria_nombre']) ?></a>
        <?php endif; ?>
    </div>

    <!-- Categoría -->
    <?php if (!empty($noticia['categoria_nombre'])): ?>
        <span class="badge badge-gold"><?= $noticia['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($noticia['categoria_nombre']) ?></span>
    <?php endif; ?>

    <!-- Título -->
    <h1 style="font-size:2.2rem; margin:0.5rem 0 0.3rem; line-height:1.2;"><?= htmlspecialchars($noticia['titulo']) ?></h1>

    <!-- Bajada -->
    <?php if (!empty($noticia['bajada'])): ?>
        <p style="font-size:1.15rem; color:#555; margin-bottom:1rem; line-height:1.5;"><?= htmlspecialchars($noticia['bajada']) ?></p>
    <?php endif; ?>

    <!-- Meta -->
    <div style="display:flex; gap:1rem; flex-wrap:wrap; color:#888; font-size:0.9rem; margin-bottom:1.5rem; padding-bottom:1rem; border-bottom:1px solid #eee;">
        <?php if (!empty($noticia['autor'])): ?>
            <span>✍️ <?= htmlspecialchars($noticia['autor']) ?></span>
        <?php endif; ?>
        <span>📅 <?= date('d \d\e F, Y', strtotime($fechaPublicacion)) ?></span>
        <span>⏱️ <?= $tiempoLectura ?> min de lectura</span>
        <span>👁️ <?= number_format((int) $noticia['visitas']) ?> visita<?= (int)$noticia['visitas'] !== 1 ? 's' : '' ?></span>
    </div>

    <!-- Foto destacada -->
    <?php if (!empty($noticia['foto_destacada'])): ?>
        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($noticia['foto_destacada']) ?>"
             alt="<?= htmlspecialchars($noticia['titulo']) ?>"
             style="width:100%; border-radius:10px; margin-bottom:2rem; max-height:500px; object-fit:cover;">
    <?php endif; ?>

    <!-- Contenido -->
    <div style="font-size:1.05rem; line-height:1.8; color:#333;">
        <?= nl2br(htmlspecialchars($noticia['contenido'] ?? '')) ?>
    </div>

    <!-- Compartir -->
    <div style="margin-top:2rem; padding:1.2rem; background:#f8f9fa; border-radius:8px; text-align:center;">
        <p style="font-weight:600; margin-bottom:0.5rem; font-size:0.9rem;">Compartir esta noticia</p>
        <div style="display:flex; gap:0.8rem; justify-content:center;">
            <?php
            $shareUrl = urlencode(SITE_URL . '/noticias/' . $noticia['slug']);
            $shareTitle = urlencode($noticia['titulo']);
            ?>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background:#1877f2;color:#fff;">Facebook</a>
            <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareTitle ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background:#1da1f2;color:#fff;">Twitter</a>
            <a href="https://api.whatsapp.com/send?text=<?= $shareTitle ?>%20<?= $shareUrl ?>" target="_blank" rel="noopener" class="btn btn-sm" style="background:#25d366;color:#fff;">WhatsApp</a>
        </div>
    </div>
</article>

<!-- Noticias relacionadas -->
<?php if (!empty($relacionadas)): ?>
<section style="max-width:800px; margin:2.5rem auto 0;">
    <h3 class="section-title">Noticias relacionadas</h3>
    <div class="card-grid" style="grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));">
        <?php foreach ($relacionadas as $r): ?>
        <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($r['slug']) ?>" class="card" style="color:inherit;">
            <?php if (!empty($r['foto_destacada'])): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($r['foto_destacada']) ?>" alt="<?= htmlspecialchars($r['titulo']) ?>" class="card-img" style="height:150px;" loading="lazy">
            <?php else: ?>
                <div class="card-img" style="height:150px;display:flex;align-items:center;justify-content:center;font-size:2rem;color:#ccc;">📰</div>
            <?php endif; ?>
            <div class="card-body">
                <h3 style="font-size:0.95rem;"><?= htmlspecialchars($r['titulo']) ?></h3>
                <div class="card-meta">
                    <span><?= date('d/m/Y', strtotime($r['publicado_en'] ?? $r['created_at'])) ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
