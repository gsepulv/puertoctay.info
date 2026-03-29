<?php /** @var array|null $destacada @var array $noticias @var array $ultimas @var array $categoriasEditoriales */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Noticias</span>
</nav>

<div class="container">
    <div class="section">
        <h1 style="margin-bottom:1.5rem;">Noticias</h1>

        <!-- Category filters -->
        <?php if (!empty($categoriasEditoriales)): ?>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;">
                <a href="/noticias" class="btn <?= empty($_GET['categoria']) ? '' : 'btn-outline' ?>" style="font-size:.9rem;">Todas</a>
                <?php foreach ($categoriasEditoriales as $catEd): ?>
                    <a href="/noticias?categoria=<?= urlencode($catEd["slug"] ?? "") ?>" class="btn <?= ($_GET['categoria'] ?? '') === ($catEd["slug"] ?? "") ? '' : 'btn-outline' ?>" style="font-size:.9rem;">
                        <?= htmlspecialchars($catEd["nombre"] ?? "") ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Featured article -->
        <?php if ($destacada): ?>
            <a href="/noticia/<?= htmlspecialchars($destacada['slug'] ?? $destacada['id']) ?>" class="card" style="display:grid;grid-template-columns:1fr 1fr;gap:0;text-decoration:none;color:inherit;margin-bottom:2.5rem;overflow:hidden;">
                <?php if (!empty($destacada['foto_destacada'])): ?>
                    <img src="<?= htmlspecialchars($destacada['foto_destacada']) ?>" alt="<?= htmlspecialchars($destacada['titulo']) ?>" style="width:100%;height:320px;object-fit:cover;">
                <?php else: ?>
                    <div style="width:100%;height:320px;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;">
                        <span style="font-size:4rem;opacity:.4;">📰</span>
                    </div>
                <?php endif; ?>
                <div style="padding:2rem;display:flex;flex-direction:column;justify-content:center;">
                    <?php if (!empty($destacada['categoria_nombre'])): ?>
                        <span class="badge" style="margin-bottom:.75rem;width:fit-content;"><?= htmlspecialchars($destacada['categoria_nombre']) ?></span>
                    <?php endif; ?>
                    <h2 style="margin-bottom:.75rem;"><?= htmlspecialchars($destacada['titulo']) ?></h2>
                    <p style="color:var(--text-secondary);line-height:1.6;margin-bottom:1rem;">
                        <?= htmlspecialchars(mb_strimwidth($destacada['bajada'] ?? $destacada['contenido'] ?? '', 0, 180, '...')) ?>
                    </p>
                    <small style="color:var(--text-muted);">
                        <?= !empty($destacada['publicado_en']) ? date('d/m/Y', strtotime($destacada['publicado_en'])) : '' ?>
                    </small>
                </div>
            </a>
        <?php endif; ?>

        <!-- News grid -->
        <?php if (!empty($noticias)): ?>
            <div class="card-grid">
                <?php foreach ($noticias as $noticia): ?>
                    <a href="/noticia/<?= htmlspecialchars($noticia['slug'] ?? $noticia['id']) ?>" class="card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($noticia['foto_destacada'])): ?>
                            <img src="<?= htmlspecialchars($noticia['foto_destacada']) ?>" alt="<?= htmlspecialchars($noticia['titulo']) ?>" style="width:100%;height:200px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:200px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:2.5rem;opacity:.4;">📰</span>
                            </div>
                        <?php endif; ?>
                        <div style="padding:1.25rem;">
                            <?php if (!empty($noticia['categoria_nombre'])): ?>
                                <span class="badge" style="margin-bottom:.5rem;"><?= htmlspecialchars($noticia['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <h3 style="margin-bottom:.5rem;"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                            <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.5;margin-bottom:.75rem;">
                                <?= htmlspecialchars(mb_strimwidth(strip_tags($noticia['bajada'] ?? $noticia['contenido'] ?? ''), 0, 100, '...')) ?>
                            </p>
                            <small style="color:var(--text-muted);">
                                <?= !empty($noticia['publicado_en']) ? date('d/m/Y', strtotime($noticia['publicado_en'])) : '' ?>
                            </small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No hay noticias disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
