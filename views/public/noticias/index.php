<section class="section">
    <h2 class="section-title">
        <?php if (!empty($categoriaActual)): ?>
            <?= $categoriaActual['emoji'] ?? '' ?> <?= htmlspecialchars($categoriaActual['nombre']) ?>
        <?php else: ?>
            Noticias
        <?php endif; ?>
    </h2>

    <!-- Filtros por categoría editorial -->
    <?php if (!empty($categoriasEditoriales)): ?>
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem;">
        <a href="<?= SITE_URL ?>/noticias" class="btn btn-sm <?= empty($categoriaActual) ? 'btn-primary' : '' ?>" style="<?= !empty($categoriaActual) ? 'background:#e9ecef;color:#333;' : '' ?>">Todas</a>
        <?php foreach ($categoriasEditoriales as $ce): ?>
            <a href="<?= SITE_URL ?>/noticias/categoria/<?= htmlspecialchars($ce['slug']) ?>"
               class="btn btn-sm <?= (!empty($categoriaActual) && (int)$categoriaActual['id'] === (int)$ce['id']) ? 'btn-primary' : '' ?>"
               style="<?= (empty($categoriaActual) || (int)$categoriaActual['id'] !== (int)$ce['id']) ? 'background:#e9ecef;color:#333;' : '' ?>">
                <?= $ce['emoji'] ?> <?= htmlspecialchars($ce['nombre']) ?> (<?= (int) $ce['total'] ?>)
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:1fr 300px; gap:2rem;">
        <!-- Columna principal -->
        <div>
            <!-- Noticia destacada -->
            <?php if (!empty($destacada)): ?>
            <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($destacada['slug']) ?>" style="color:inherit; display:block; margin-bottom:2rem;">
                <div style="background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,0.1);">
                    <?php if (!empty($destacada['foto_destacada'])): ?>
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($destacada['foto_destacada']) ?>"
                             alt="<?= htmlspecialchars($destacada['titulo']) ?>"
                             style="width:100%; height:350px; object-fit:cover;">
                    <?php endif; ?>
                    <div style="padding:1.5rem;">
                        <?php if (!empty($destacada['categoria_nombre'])): ?>
                            <span class="badge badge-gold" style="margin-bottom:0.5rem;"><?= $destacada['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($destacada['categoria_nombre']) ?></span>
                        <?php endif; ?>
                        <h2 style="font-size:1.6rem; margin:0.3rem 0;"><?= htmlspecialchars($destacada['titulo']) ?></h2>
                        <?php if (!empty($destacada['bajada'])): ?>
                            <p style="color:#555; font-size:1.05rem; margin-top:0.5rem;"><?= htmlspecialchars($destacada['bajada']) ?></p>
                        <?php endif; ?>
                        <div style="color:#888; font-size:0.85rem; margin-top:0.8rem;">
                            <?php if (!empty($destacada['autor'])): ?>
                                <span><?= htmlspecialchars($destacada['autor']) ?></span> ·
                            <?php endif; ?>
                            <span><?= date('d/m/Y', strtotime($destacada['publicado_en'] ?? $destacada['created_at'])) ?></span>
                            · <span><?= $destacada['tiempo_lectura'] ?: Noticia::calcularTiempoLectura($destacada['contenido'] ?? '') ?> min de lectura</span>
                        </div>
                    </div>
                </div>
            </a>
            <?php endif; ?>

            <!-- Grilla de noticias -->
            <?php if (empty($noticias) && empty($destacada)): ?>
                <div class="empty-state">
                    <p>No hay noticias publicadas<?= !empty($categoriaActual) ? ' en esta categoría' : '' ?>.</p>
                </div>
            <?php elseif (!empty($noticias)): ?>
                <div class="card-grid" style="grid-template-columns:repeat(auto-fill, minmax(250px, 1fr));">
                    <?php foreach ($noticias as $n): ?>
                    <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($n['slug']) ?>" class="card" style="color:inherit;">
                        <?php if (!empty($n['foto_destacada'])): ?>
                            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($n['foto_destacada']) ?>" alt="<?= htmlspecialchars($n['titulo']) ?>" class="card-img" loading="lazy">
                        <?php else: ?>
                            <div class="card-img" style="display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:#ccc;">📰</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <?php if (!empty($n['categoria_nombre'])): ?>
                                <span style="font-size:0.75rem; color:<?= COLOR_ACCENT ?>; font-weight:600;"><?= $n['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($n['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <h3 style="font-size:1rem;"><?= htmlspecialchars($n['titulo']) ?></h3>
                            <?php if (!empty($n['bajada'])): ?>
                                <p><?= htmlspecialchars(mb_substr($n['bajada'], 0, 100)) ?></p>
                            <?php endif; ?>
                            <div class="card-meta">
                                <span><?= date('d/m/Y', strtotime($n['publicado_en'] ?? $n['created_at'])) ?></span>
                                <span>· <?= $n['tiempo_lectura'] ?: Noticia::calcularTiempoLectura($n['contenido'] ?? '') ?> min</span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside>
            <!-- Últimas noticias -->
            <?php if (!empty($ultimas)): ?>
            <div style="background:#fff; border-radius:10px; padding:1.2rem; box-shadow:0 2px 8px rgba(0,0,0,0.06); margin-bottom:1.5rem;">
                <h3 style="font-size:1rem; margin-bottom:0.8rem; color:<?= COLOR_PRIMARY ?>;">Últimas noticias</h3>
                <?php foreach ($ultimas as $u): ?>
                <a href="<?= SITE_URL ?>/noticias/<?= htmlspecialchars($u['slug']) ?>" style="display:block; padding:0.5rem 0; border-bottom:1px solid #f0f0f0; color:inherit;">
                    <span style="font-size:0.9rem; font-weight:600; color:#333;"><?= htmlspecialchars($u['titulo']) ?></span>
                    <span style="display:block; font-size:0.8rem; color:#888;"><?= date('d/m/Y', strtotime($u['publicado_en'] ?? $u['created_at'])) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Categorías editoriales -->
            <?php if (!empty($categoriasEditoriales)): ?>
            <div style="background:#fff; border-radius:10px; padding:1.2rem; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                <h3 style="font-size:1rem; margin-bottom:0.8rem; color:<?= COLOR_PRIMARY ?>;">Categorías</h3>
                <?php foreach ($categoriasEditoriales as $ce): ?>
                <a href="<?= SITE_URL ?>/noticias/categoria/<?= htmlspecialchars($ce['slug']) ?>" style="display:flex; justify-content:space-between; padding:0.4rem 0; border-bottom:1px solid #f0f0f0; color:#333; font-size:0.9rem;">
                    <span><?= $ce['emoji'] ?> <?= htmlspecialchars($ce['nombre']) ?></span>
                    <span style="color:#888;"><?= (int) $ce['total'] ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<style>
@media (max-width: 768px) {
    section > div[style*="grid-template-columns:1fr 300px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
