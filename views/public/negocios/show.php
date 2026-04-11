<?php
/**
 * Ficha de negocio — visitapuertoctay.cl
 * Variables: $negocio, $resenas, $rating, $ratingData, $negocioTemporadas, $similares, $galeria
 */
$shareUrl = SITE_URL . '/negocio/' . htmlspecialchars($negocio['slug']);
$shareTitle = htmlspecialchars($negocio['nombre'], ENT_QUOTES);
$hasCoords = !empty($negocio['lat']) && !empty($negocio['lng']);
$gmapsUrl = $hasCoords ? "https://www.google.com/maps?q={$negocio['lat']},{$negocio['lng']}" : '';
$totalResenas = (int) ($ratingData['total'] ?? 0);

// Tipo badges
$tipoBadges = [
    'comercio'     => ['color' => '#2563eb', 'bg' => '#EFF6FF', 'label' => 'Comercio'],
    'atractivo'    => ['color' => '#059669', 'bg' => '#ECFDF5', 'label' => 'Atractivo turístico'],
    'servicio'     => ['color' => '#7C3AED', 'bg' => '#F5F3FF', 'label' => 'Servicio'],
    'gastronomia'  => ['color' => '#DC2626', 'bg' => '#FEF2F2', 'label' => 'Gastronomía'],
];
$tipo = $tipoBadges[$negocio['tipo'] ?? 'comercio'] ?? $tipoBadges['comercio'];
?>

<style>
/* ── FICHA NEGOCIO ─────────────────────────────────── */
.ficha-hero { position: relative; width: 100%; height: 420px; overflow: hidden; border-radius: var(--radius-lg); margin-bottom: 1.5rem; }
.ficha-hero img { width: 100%; height: 100%; object-fit: cover; display: block; }
.ficha-hero-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; }
.ficha-hero-overlay { position: absolute; bottom: 0; left: 0; right: 0; height: 60%; background: linear-gradient(to top, rgba(13,27,42,0.85) 0%, transparent 100%); }
.ficha-hero-content { position: absolute; bottom: 0; left: 0; right: 0; padding: 2rem; color: #fff; display: flex; align-items: flex-end; gap: 1.2rem; }
.ficha-logo { width: 80px; height: 80px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.8); overflow: hidden; flex-shrink: 0; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
.ficha-logo img { width: 100%; height: 100%; object-fit: cover; }
.ficha-hero-info h1 { color: #fff; font-size: 2rem; margin: 0 0 0.5rem; text-shadow: 0 2px 8px rgba(0,0,0,0.3); }
.ficha-badges { display: flex; gap: 0.4rem; flex-wrap: wrap; }
.ficha-badge { padding: 0.25rem 0.7rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; }
.ficha-meta { font-size: 0.85rem; opacity: 0.8; margin-top: 0.3rem; }

.ficha-grid { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }
.ficha-sidebar { position: sticky; top: 5rem; }

/* Secciones */
.ficha-section { background: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 1.5rem; border: 1px solid var(--border); }
.ficha-section h2 { font-size: 1.25rem; margin-bottom: 1rem; }
.ficha-desc-intro { font-size: 1.05rem; color: var(--text-light); line-height: 1.75; margin-bottom: 1.5rem; }

/* Galería */
.ficha-galeria { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.75rem; }
.ficha-galeria-item { border-radius: var(--radius-md); overflow: hidden; cursor: pointer; aspect-ratio: 4/3; }
.ficha-galeria-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.ficha-galeria-item:hover img { transform: scale(1.05); }

/* Lightbox */
.ficha-lightbox { position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.92); display: none; align-items: center; justify-content: center; }
.ficha-lightbox.active { display: flex; }
.ficha-lightbox img { max-width: 90vw; max-height: 85vh; border-radius: 8px; box-shadow: 0 4px 32px rgba(0,0,0,0.5); }
.ficha-lightbox-close { position: absolute; top: 1.5rem; right: 1.5rem; background: none; border: none; color: #fff; font-size: 2rem; cursor: pointer; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; }
.ficha-lightbox-nav { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.15); border: none; color: #fff; font-size: 2rem; cursor: pointer; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.ficha-lightbox-nav:hover { background: rgba(255,255,255,0.3); }
.ficha-lightbox-prev { left: 1rem; }
.ficha-lightbox-next { right: 1rem; }

/* Redes */
.ficha-redes { display: flex; gap: 0.6rem; flex-wrap: wrap; }
.ficha-red { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; text-decoration: none; }
.ficha-red:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
.ficha-red svg { width: 20px; height: 20px; }

/* Contact card */
.ficha-contact-item { display: flex; gap: 0.6rem; margin-bottom: 0.9rem; font-size: 0.9rem; color: var(--text-light); align-items: flex-start; }
.ficha-contact-item a { color: var(--primary); text-decoration: none; }
.ficha-contact-item a:hover { text-decoration: underline; }

/* Responsive */
@media (max-width: 768px) {
    .ficha-hero { height: 280px; border-radius: 0; }
    .ficha-hero-content { padding: 1.2rem; }
    .ficha-hero-info h1 { font-size: 1.5rem; }
    .ficha-logo { width: 60px; height: 60px; }
    .ficha-grid { grid-template-columns: 1fr !important; }
    .ficha-sidebar { position: static !important; }
    .ficha-galeria { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
}
</style>

<!-- Breadcrumb -->
<div class="container" style="padding-top: 1rem;">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a> <span class="sep">/</span>
        <a href="<?= SITE_URL ?>/directorio">Directorio</a> <span class="sep">/</span>
        <?php if (!empty($negocio['categoria_nombre'])): ?>
            <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($negocio['categoria_slug'] ?? '') ?>"><?= htmlspecialchars($negocio['categoria_nombre']) ?></a> <span class="sep">/</span>
        <?php endif; ?>
        <span><?= htmlspecialchars($negocio['nombre']) ?></span>
    </nav>
</div>

<!-- CABECERA: Portada + Logo + Nombre -->
<div class="container">
    <div class="ficha-hero">
        <?php
        $heroImg = !empty($negocio['portada']) ? $negocio['portada'] : (!empty($negocio['foto_principal']) ? $negocio['foto_principal'] : null);
        ?>
        <?php if ($heroImg): ?>
            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($heroImg) ?>" alt="<?= htmlspecialchars($negocio['nombre']) ?>">
        <?php else: ?>
            <div class="ficha-hero-placeholder">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
        <?php endif; ?>
        <div class="ficha-hero-overlay"></div>
        <div class="ficha-hero-content">
            <?php if (!empty($negocio['logo'])): ?>
                <div class="ficha-logo">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['logo']) ?>" alt="Logo">
                </div>
            <?php endif; ?>
            <div class="ficha-hero-info">
                <h1><?= htmlspecialchars($negocio['nombre']) ?></h1>
                <div class="ficha-badges">
                    <span class="ficha-badge" style="background:<?= $tipo['bg'] ?>;color:<?= $tipo['color'] ?>;"><?= $tipo['label'] ?></span>
                    <?php if (!empty($negocio['verificado'])): ?>
                        <span class="ficha-badge" style="background:#DCFCE7;color:#166534;">✓ Verificado</span>
                    <?php endif; ?>
                    <?php if (!empty($negocio['plan_badge'])): ?>
                        <span class="ficha-badge" style="background:var(--accent);color:var(--primary-dark);">★ Destacado</span>
                    <?php endif; ?>
                    <?php if (!empty($negocio['categoria_nombre'])): ?>
                        <span class="ficha-badge" style="background:rgba(255,255,255,0.2);color:#fff;"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="ficha-meta">
                    <?= number_format($negocio['visitas'] ?? 0) ?> visitas
                    <?php if ($totalResenas > 0): ?>
                        · <?= number_format($rating, 1) ?> ★ (<?= $totalResenas ?> <?= $totalResenas === 1 ? 'reseña' : 'reseñas' ?>)
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTENIDO PRINCIPAL: 2 columnas -->
<div class="container">
    <div class="ficha-grid">

        <!-- COLUMNA IZQUIERDA -->
        <div>
            <!-- Descripción -->
            <?php if (!empty($negocio['descripcion_corta'])): ?>
                <p class="ficha-desc-intro"><?= htmlspecialchars($negocio['descripcion_corta']) ?></p>
            <?php endif; ?>

            <?php if (!empty($negocio['descripcion_larga'])): ?>
                <div class="ficha-section">
                    <h2>Sobre nosotros</h2>
                    <div style="line-height: 1.8; color: var(--text);">
                        <?= nl2br(htmlspecialchars($negocio['descripcion_larga'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contacto (inline en columna principal para mobile) -->
            <?php if (!empty($negocio['direccion']) || !empty($negocio['telefono']) || !empty($negocio['whatsapp']) || !empty($negocio['email']) || !empty($negocio['sitio_web']) || !empty($negocio['como_llegar'])): ?>
                <div class="ficha-section">
                    <h2>📞 Contacto</h2>
                    <?php if (!empty($negocio['telefono'])): ?>
                        <div class="ficha-contact-item"><span>📞</span><a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>"><?= htmlspecialchars($negocio['telefono']) ?></a></div>
                    <?php endif; ?>
                    <?php if (!empty($negocio['whatsapp'])): ?>
                        <div class="ficha-contact-item">
                            <span>💬</span>
                            <a href="https://wa.me/56<?= preg_replace('/\D/', '', $negocio['whatsapp']) ?>" target="_blank" rel="noopener" style="color:#25D366;font-weight:600;">WhatsApp: <?= htmlspecialchars($negocio['whatsapp']) ?></a>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($negocio['email'])): ?>
                        <div class="ficha-contact-item"><span>✉️</span><a href="mailto:<?= htmlspecialchars($negocio['email']) ?>"><?= htmlspecialchars($negocio['email']) ?></a></div>
                    <?php endif; ?>
                    <?php if (!empty($negocio['sitio_web'])): ?>
                        <div class="ficha-contact-item"><span>🌐</span><a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(preg_replace('#^https?://#', '', $negocio['sitio_web'])) ?> ↗</a></div>
                    <?php endif; ?>
                    <?php if (!empty($negocio['direccion'])): ?>
                        <div class="ficha-contact-item"><span>📍</span><span><?= htmlspecialchars($negocio['direccion']) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($negocio['como_llegar'])): ?>
                        <div class="ficha-contact-item"><span>🗺️</span><span style="line-height:1.6;"><?= nl2br(htmlspecialchars($negocio['como_llegar'])) ?></span></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Mapa -->
            <?php if ($hasCoords): ?>
                <div class="ficha-section">
                    <h2>📍 Ubicación</h2>
                    <div id="mapNegocio" style="height: 300px; border-radius: var(--radius-md); margin-bottom: 1rem;"></div>
                    <a href="<?= $gmapsUrl ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">🗺 Cómo llegar (Google Maps)</a>
                </div>
            <?php endif; ?>

            <!-- Galería -->
            <?php if (!empty($galeria)): ?>
                <div class="ficha-section">
                    <h2>📷 Galería</h2>
                    <div class="ficha-galeria">
                        <?php foreach ($galeria as $i => $foto): ?>
                            <div class="ficha-galeria-item" onclick="openLightbox(<?= $i ?>)">
                                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($foto) ?>" alt="Foto <?= $i + 1 ?> de <?= htmlspecialchars($negocio['nombre']) ?>" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Redes sociales -->
            <?php
            $redes = [];
            $redesData = [
                'facebook'  => ['#1877F2', '#fff', '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>'],
                'instagram' => ['#E4405F', '#fff', '<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>'],
                'tiktok'    => ['#000', '#fff', '<path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>'],
                'youtube'   => ['#FF0000', '#fff', '<path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>'],
                'twitter'   => ['#0f1419', '#fff', '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>'],
                'linkedin'  => ['#0A66C2', '#fff', '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>'],
            ];
            foreach ($redesData as $key => [$bg, $fg, $path]) {
                if (!empty($negocio[$key])) {
                    $redes[] = ['url' => $negocio[$key], 'bg' => $bg, 'fg' => $fg, 'path' => $path, 'name' => ucfirst($key)];
                }
            }
            ?>
            <?php if (!empty($redes)): ?>
                <div class="ficha-section">
                    <h2>Redes sociales</h2>
                    <div class="ficha-redes">
                        <?php foreach ($redes as $red): ?>
                            <a href="<?= htmlspecialchars($red['url']) ?>" target="_blank" rel="noopener" class="ficha-red" style="background:<?= $red['bg'] ?>;" title="<?= $red['name'] ?>">
                                <svg viewBox="0 0 24 24" fill="<?= $red['fg'] ?>"><?= $red['path'] ?></svg>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Reseñas -->
            <div class="ficha-section">
                <h2>⭐ Reseñas</h2>

                <?php if ($rating > 0): ?>
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding:1rem;background:var(--bg);border-radius:var(--radius-md);">
                        <div style="font-size:2.5rem;font-weight:800;color:var(--primary-dark);"><?= number_format($rating, 1) ?></div>
                        <div>
                            <div class="stars stars-lg">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color:<?= $i <= round($rating) ? 'var(--accent)' : 'var(--border)' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <div class="text-sm text-light"><?= $totalResenas ?> <?= $totalResenas === 1 ? 'reseña' : 'reseñas' ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($resenas)): ?>
                    <?php foreach ($resenas as $res): ?>
                        <div style="padding:1rem 0;border-bottom:1px solid var(--border);">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                                <strong><?= htmlspecialchars($res['nombre_autor']) ?></strong>
                                <span class="text-sm text-light"><?= date('d/m/Y', strtotime($res['created_at'])) ?></span>
                            </div>
                            <div class="stars" style="margin-bottom:0.4rem;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span style="color:<?= $i <= (int)$res['puntuacion'] ? 'var(--accent)' : 'var(--border)' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <?php if (!empty($res['comentario'])): ?>
                                <p style="color:var(--text-light);margin:0;line-height:1.6;"><?= nl2br(htmlspecialchars($res['comentario'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-light">Aún no hay reseñas. ¡Sé el primero en opinar!</p>
                <?php endif; ?>

                <!-- formulario reseñas: pendiente prompt 3 -->
            </div>

            <!-- Similares -->
            <?php if (!empty($similares)): ?>
                <div style="margin-bottom:2rem;">
                    <h2 style="font-size:1.25rem;margin-bottom:1.2rem;">Negocios similares</h2>
                    <div class="card-grid-sm">
                        <?php foreach ($similares as $sim): ?>
                            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($sim['slug']) ?>" class="card" style="text-decoration:none;color:inherit;">
                                <?php if (!empty($sim['foto_principal'])): ?>
                                    <div class="card-img"><img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($sim['foto_principal']) ?>" alt="<?= htmlspecialchars($sim['nombre']) ?>" loading="lazy"></div>
                                <?php else: ?>
                                    <div class="card-img-placeholder"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <span class="badge badge-primary mb-1"><?= $sim['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($sim['categoria_nombre'] ?? '') ?></span>
                                    <h3><?= htmlspecialchars($sim['nombre']) ?></h3>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- COLUMNA DERECHA: Sidebar -->
        <div class="ficha-sidebar">
            <!-- Botones de acción -->
            <div class="ficha-section" style="padding:1.5rem;">
                <div style="display:flex;flex-direction:column;gap:0.6rem;">
                    <?php if (!empty($negocio['whatsapp'])): ?>
                        <a href="https://wa.me/56<?= preg_replace('/\D/', '', $negocio['whatsapp']) ?>" target="_blank" rel="noopener" class="btn" style="background:#25D366;color:white;width:100%;">💬 WhatsApp</a>
                    <?php endif; ?>
                    <?php if (!empty($negocio['telefono'])): ?>
                        <a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>" class="btn btn-primary" style="width:100%;">📞 Llamar</a>
                    <?php endif; ?>
                    <?php if (!empty($negocio['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($negocio['email']) ?>" class="btn btn-outline" style="width:100%;">✉ Enviar email</a>
                    <?php endif; ?>
                    <?php if (!empty($negocio['sitio_web'])): ?>
                        <a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="width:100%;border:1px solid var(--border);">🌐 Sitio web</a>
                    <?php endif; ?>
                    <?php if ($hasCoords): ?>
                        <a href="<?= $gmapsUrl ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="width:100%;border:1px solid var(--border);">🗺 Google Maps</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Compartir -->
            <div class="ficha-section" style="padding:1.5rem;">
                <h3 style="font-size:1rem;margin-bottom:0.8rem;">Compartir</h3>
                <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
                    <a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>','fb','width=600,height=400')" class="btn btn-sm btn-ghost" style="border:1px solid var(--border);">Facebook</a>
                    <a href="javascript:void(0)" onclick="window.open('https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl) ?>&text=<?= urlencode($shareTitle) ?>','tw','width=600,height=400')" class="btn btn-sm btn-ghost" style="border:1px solid var(--border);">X</a>
                    <a href="https://wa.me/?text=<?= urlencode($shareTitle . ' ' . $shareUrl) ?>" target="_blank" class="btn btn-sm btn-ghost" style="border:1px solid var(--border);">WhatsApp</a>
                    <button onclick="navigator.clipboard.writeText('<?= $shareUrl ?>');this.textContent='¡Copiado!';setTimeout(()=>this.textContent='Copiar link',2000)" class="btn btn-sm btn-ghost" style="border:1px solid var(--border);cursor:pointer;">Copiar link</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schema.org -->
<?= SeoHelper::schemaLocalBusiness($negocio) ?? '' ?>

<!-- Lightbox -->
<?php if (!empty($galeria)): ?>
<div class="ficha-lightbox" id="fichaLightbox">
    <button class="ficha-lightbox-close" onclick="closeLightbox()">&times;</button>
    <button class="ficha-lightbox-nav ficha-lightbox-prev" onclick="navLightbox(-1)">&#8249;</button>
    <img id="lightboxImg" src="" alt="">
    <button class="ficha-lightbox-nav ficha-lightbox-next" onclick="navLightbox(1)">&#8250;</button>
</div>
<?php endif; ?>

<?php
$extraScripts = '';

// Mapa Leaflet
if ($hasCoords) {
    $extraScripts .= '<script>
    var map = L.map("mapNegocio").setView([' . $negocio['lat'] . ', ' . $negocio['lng'] . '], 15);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {attribution: "© OpenStreetMap"}).addTo(map);
    L.marker([' . $negocio['lat'] . ', ' . $negocio['lng'] . ']).addTo(map).bindPopup("<strong>' . addslashes($negocio['nombre']) . '</strong>").openPopup();
    </script>';
}

// Lightbox JS
if (!empty($galeria)) {
    $galeriaJs = json_encode(array_map(fn($f) => SITE_URL . '/uploads/' . $f, $galeria));
    $extraScripts .= '<script>
    var lbPhotos = ' . $galeriaJs . ';
    var lbIndex = 0;
    function openLightbox(i) { lbIndex = i; document.getElementById("lightboxImg").src = lbPhotos[i]; document.getElementById("fichaLightbox").classList.add("active"); document.body.style.overflow = "hidden"; }
    function closeLightbox() { document.getElementById("fichaLightbox").classList.remove("active"); document.body.style.overflow = ""; }
    function navLightbox(dir) { lbIndex = (lbIndex + dir + lbPhotos.length) % lbPhotos.length; document.getElementById("lightboxImg").src = lbPhotos[lbIndex]; }
    document.getElementById("fichaLightbox").addEventListener("click", function(e) { if (e.target === this) closeLightbox(); });
    document.addEventListener("keydown", function(e) { if (!document.getElementById("fichaLightbox").classList.contains("active")) return; if (e.key === "Escape") closeLightbox(); if (e.key === "ArrowRight") navLightbox(1); if (e.key === "ArrowLeft") navLightbox(-1); });
    </script>';
}
?>
