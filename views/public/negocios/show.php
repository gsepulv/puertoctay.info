<?php
$svgPlaceholder = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>';
$shareUrl = SITE_URL . '/negocio/' . htmlspecialchars($negocio['slug']);
$shareTitle = htmlspecialchars($negocio['nombre'], ENT_QUOTES);
$hasCoords = !empty($negocio['lat']) && !empty($negocio['lng']);
$gmapsUrl = $hasCoords ? "https://www.google.com/maps?q={$negocio['lat']},{$negocio['lng']}" : '';
?>

<!-- Breadcrumb -->
<div class="container" style="padding-top: 1rem;">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a> <span class="sep">/</span>
        <a href="<?= SITE_URL ?>/directorio">Directorio</a> <span class="sep">/</span>
        <span><?= htmlspecialchars($negocio['nombre']) ?></span>
    </nav>
    <?php if (empty($negocio['verificado'])): ?>
    <div class="banner-ejemplo">
        ⚠️ Este comercio es un ejemplo demostrativo. La información mostrada no es real. Si deseas publicar tu negocio, <a href="<?= SITE_URL ?>/contacto">contáctanos</a>.
    </div>
    <?php endif; ?>
</div>

<!-- Hero Image -->
<div class="container">
    <?php
    $heroImg = !empty($negocio['portada']) ? $negocio['portada'] : (!empty($negocio['foto_principal']) ? 'negocios/' . $negocio['foto_principal'] : null);
    // portada already has subdir prefix, foto_principal needs negocios/ prefix
    if (!empty($negocio['portada'])) $heroImg = $negocio['portada'];
    elseif (!empty($negocio['foto_principal'])) $heroImg = $negocio['foto_principal'];
    else $heroImg = null;
    ?>
    <?php if ($heroImg): ?>
        <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 0; box-shadow: var(--shadow-md);">
            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($heroImg) ?>"
                 alt="<?= htmlspecialchars($negocio['nombre']) ?>"
                 style="width: 100%; height: 400px; object-fit: cover; display: block;">
        </div>
        <?php if (!empty($negocio['logo'])): ?>
        <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.5rem; background: var(--white); border: 1px solid var(--border); border-top: none; border-radius: 0 0 var(--radius-lg) var(--radius-lg); margin-bottom: 1.5rem; box-shadow: var(--shadow-sm);">
            <div style="width: 64px; height: 64px; border-radius: 50%; border: 3px solid var(--border); overflow: hidden; flex-shrink: 0; background: var(--white);">
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['logo']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div>
                <h2 style="font-size: 1.3rem; margin: 0; line-height: 1.3;"><?= htmlspecialchars($negocio['nombre']) ?></h2>
                <?php if (!empty($negocio['categoria_nombre'])): ?>
                <span style="font-size: 0.85rem; color: var(--text-light);"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div style="margin-bottom: 1.5rem;"></div>
        <?php endif; ?>
    <?php else: ?>
        <div style="width: 100%; height: 320px; border-radius: var(--radius-lg); background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: var(--shadow-md);">
            <?= $svgPlaceholder ?>
        </div>
    <?php endif; ?>
</div>

<!-- Main Content: 2 columns -->
<div class="container">
    <div style="display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start;">

        <!-- LEFT: Main Content -->
        <div>
            <!-- Name + Badges -->
            <h1 style="margin-bottom: 0.75rem;"><?= htmlspecialchars($negocio['nombre']) ?></h1>

            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <?php
                $__favStmt = getDB()->prepare("SELECT id FROM favoritos WHERE usuario_id = :uid AND negocio_id = :nid");
                $__favStmt->execute(['uid' => $_SESSION['usuario_id'], 'nid' => $negocio['id']]);
                $__isFav = (bool) $__favStmt->fetch();
                ?>
                <button onclick="toggleFavorito(<?= (int)$negocio['id'] ?>, this)"
                        style="background: none; border: 1px solid var(--border); border-radius: 50px; padding: 0.4rem 1rem; cursor: pointer; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.3rem; color: <?= $__isFav ? '#EF4444' : 'var(--text-light)' ?>; transition: all 0.2s;"
                        title="<?= $__isFav ? 'Quitar de favoritos' : 'Agregar a favoritos' ?>">
                    <span class="fav-icon"><?= $__isFav ? '&#9829;' : '&#9825;' ?></span> <span class="fav-text"><?= $__isFav ? 'En favoritos' : 'Favorito' ?></span>
                </button>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/login" style="border: 1px solid var(--border); border-radius: 50px; padding: 0.4rem 1rem; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.3rem; color: var(--text-light); text-decoration: none;">&#9825; Favorito</a>
            <?php endif; ?>

            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                <?php if (!empty($negocio['categoria_nombre'])): ?>
                    <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($negocio['categoria_slug'] ?? '') ?>" class="badge badge-primary" style="text-decoration: none;">
                        <?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?>
                    </a>
                <?php endif; ?>
                <?php if (!empty($negocio['verificado'])): ?>
                    <span class="badge badge-green">✓ Verificado</span>
                <?php endif; ?>
                <?php if (!empty($negocio['plan_badge'])): ?>
                    <span class="badge badge-accent">★ Destacado</span>
                <?php endif; ?>
            </div>

            <!-- Rating -->
            <?php if ($rating > 0): ?>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                    <div class="stars stars-lg">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span style="color: <?= $i <= round($rating) ? 'var(--accent)' : 'var(--border)' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <span style="font-weight: 700;"><?= number_format($rating, 1) ?></span>
                    <span class="text-light text-sm">(<?= count($resenas) ?> <?= count($resenas) === 1 ? 'reseña' : 'reseñas' ?>)</span>
                </div>
            <?php endif; ?>

            <!-- Descripción corta -->
            <?php if (!empty($negocio['descripcion_corta'])): ?>
                <p style="font-size: 1.05rem; color: var(--text-light); margin-bottom: 1.5rem; line-height: 1.7;">
                    <?= htmlspecialchars($negocio['descripcion_corta']) ?>
                </p>
            <?php endif; ?>

            <!-- Sobre nosotros (descripción larga) -->
            <?php if (!empty($negocio['descripcion_larga'])): ?>
                <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; border: 1px solid var(--border);">
                    <h2 style="font-size: 1.3rem; margin-bottom: 1rem;">Sobre nosotros</h2>
                    <div style="line-height: 1.8; color: var(--text);">
                        <?= nl2br(htmlspecialchars($negocio['descripcion_larga'])) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Temporadas -->
            <?php if (!empty($negocioTemporadas)): ?>
            <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; border: 1px solid var(--border);">
                <h2 style="font-size: 1.3rem; margin-bottom: 1rem;">🌤️ Temporadas</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <?php foreach ($negocioTemporadas as $nt): ?>
                    <div style="padding: 0.4rem 0.8rem; background: var(--bg); border: 1px solid var(--border); border-radius: 50px; font-size: 0.85rem; display: flex; align-items: center; gap: 0.3rem;">
                        <span><?= $nt['emoji'] ?></span>
                        <span><?= htmlspecialchars($nt['nombre']) ?></span>
                    </div>
                    <?php if (!empty($nt['promocion'])): ?>
                    <div style="padding: 0.4rem 0.8rem; background: #FEF3C7; border: 1px solid #F59E0B; border-radius: 50px; font-size: 0.8rem; font-weight: 600; color: #92400E;">
                        🏷️ <?= htmlspecialchars($nt['promocion']) ?>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Ubicación + Mapa -->
            <?php if (!empty($negocio['direccion']) || $hasCoords): ?>
                <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; border: 1px solid var(--border);">
                    <h2 style="font-size: 1.3rem; margin-bottom: 1rem;">📍 Ubicación</h2>

                    <?php if (!empty($negocio['direccion'])): ?>
                        <p style="margin-bottom: 0.75rem; color: var(--text-light);"><?= htmlspecialchars($negocio['direccion']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($negocio['como_llegar'])): ?>
                        <p style="margin-bottom: 1rem; color: var(--text-light); font-size: 0.9rem; line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($negocio['como_llegar'])) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($hasCoords): ?>
                        <div id="mapNegocio" style="height: 300px; border-radius: var(--radius-md); margin-bottom: 1rem;"></div>
                        <a href="<?= $gmapsUrl ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                            🗺 Cómo llegar (Google Maps)
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Reseñas -->
            <div style="background: var(--white); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem; border: 1px solid var(--border);">
                <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">⭐ Reseñas</h2>

                <?php if (!empty($resenas)): ?>
                    <?php foreach ($resenas as $res): ?>
                        <div style="padding: 1rem 0; border-bottom: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <div>
                                    <strong><?= htmlspecialchars($res['nombre_autor']) ?></strong>
                                    <span class="stars" style="margin-left: 0.5rem;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span style="color: <?= $i <= (int)$res['puntuacion'] ? 'var(--accent)' : 'var(--border)' ?>">★</span>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <span class="text-sm text-light"><?= date('d/m/Y', strtotime($res['created_at'])) ?></span>
                            </div>
                            <?php if (!empty($res['comentario'])): ?>
                                <p style="color: var(--text-light); margin: 0; line-height: 1.6;"><?= nl2br(htmlspecialchars($res['comentario'])) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-light">Aún no hay reseñas. ¡Sé el primero en opinar!</p>
                <?php endif; ?>

                <!-- Formulario de reseña -->
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">

                <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div style="background: #F0FDF4; border: 1px solid #22C55E; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; color: #166534;">
                        <?= htmlspecialchars($_SESSION['flash_success']) ?>
                    </div>
                    <?php unset($_SESSION['flash_success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['flash_error'])): ?>
                    <div style="background: #FEF2F2; border: 1px solid #EF4444; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; color: #991B1B;">
                        <?= htmlspecialchars($_SESSION['flash_error']) ?>
                    </div>
                    <?php unset($_SESSION['flash_error']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['usuario_id'])): ?>
                    <?php
                    // Check if user already reviewed this business
                    $__checkReview = $this->db ?? getDB();
                    $__rvStmt = $__checkReview->prepare("SELECT id FROM resenas WHERE negocio_id = :nid AND usuario_id = :uid LIMIT 1");
                    $__rvStmt->execute(['nid' => $negocio['id'], 'uid' => $_SESSION['usuario_id']]);
                    $__yaReseno = $__rvStmt->fetch();
                    ?>

                    <?php if ($__yaReseno): ?>
                        <p style="color: var(--text-light); font-style: italic;">Ya dejaste una reseña en este negocio.</p>
                    <?php else: ?>
                        <h3 style="margin-bottom: 1rem;">Deja tu reseña</h3>
                        <form method="POST" action="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($negocio['slug']) ?>/resena" id="reviewForm">
                            <?= csrf_field() ?>
                            <input type="hidden" name="negocio_id" value="<?= (int)$negocio['id'] ?>">
                            <div style="position: absolute; left: -9999px;"><input type="text" name="website_url" tabindex="-1" autocomplete="off"></div>

                            <!-- Star Rating -->
                            <div class="form-group">
                                <label>Calificación</label>
                                <div id="starPicker" style="font-size: 1.8rem; cursor: pointer; letter-spacing: 4px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span data-star="<?= $i ?>" style="color: var(--border); transition: color 0.15s;">&#9733;</span>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="puntuacion" id="starValue" value="5" required>
                            </div>

                            <div class="form-group">
                                <label for="rev_comentario">Tu opinión</label>
                                <textarea id="rev_comentario" name="comentario" rows="3" required placeholder="Cuéntanos tu experiencia (mínimo 10 caracteres)" minlength="10"></textarea>
                            </div>

                            <p style="font-size: 0.85rem; color: var(--text-light); margin-bottom: 0.75rem;">Publicando como <strong><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></strong></p>

                            <button type="submit" class="btn btn-accent">Enviar reseña</button>
                        </form>
                    <?php endif; ?>

                <?php else: ?>
                    <div style="text-align: center; padding: 1.5rem; background: var(--bg); border-radius: var(--radius-md);">
                        <p style="margin-bottom: 0.75rem; color: var(--text-light);">Inicia sesión para dejar una reseña</p>
                        <a href="<?= SITE_URL ?>/login" class="btn btn-primary">Iniciar Sesión</a>
                    </div>
                <?php endif; ?>

                </div>
            </div>

            <!-- Negocios similares -->
            <?php if (!empty($similares)): ?>
                <div style="margin-bottom: 2rem;">
                    <h2 style="font-size: 1.3rem; margin-bottom: 1.5rem;">Negocios similares</h2>
                    <div class="card-grid-sm">
                        <?php foreach ($similares as $sim): ?>
                            <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($sim['slug']) ?>" class="card" style="text-decoration: none; color: inherit;">
                                <?php if (!empty($sim['foto_principal'])): ?>
                                    <img class="card-img" src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($sim['foto_principal']) ?>" alt="<?= htmlspecialchars($sim['nombre']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="card-img-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                    </div>
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

            <!-- Share Buttons -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2rem;">
                <span class="text-sm text-light" style="align-self: center; margin-right: 0.5rem;">Compartir:</span>
                <a href="javascript:void(0)" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($shareUrl) ?>','fb','width=600,height=400')" class="btn btn-sm btn-ghost" style="border: 1px solid var(--border);">Facebook</a>
                <a href="javascript:void(0)" onclick="window.open('https://twitter.com/intent/tweet?url=<?= urlencode($shareUrl) ?>&text=<?= urlencode($shareTitle) ?>','tw','width=600,height=400')" class="btn btn-sm btn-ghost" style="border: 1px solid var(--border);">X</a>
                <a href="https://wa.me/?text=<?= urlencode($shareTitle . ' ' . $shareUrl) ?>" target="_blank" class="btn btn-sm btn-ghost" style="border: 1px solid var(--border);">WhatsApp</a>
                <a href="mailto:?subject=<?= urlencode($shareTitle) ?>&body=<?= urlencode($shareUrl) ?>" class="btn btn-sm btn-ghost" style="border: 1px solid var(--border);">Email</a>
                <button onclick="navigator.clipboard.writeText('<?= $shareUrl ?>');this.textContent='¡Copiado!';setTimeout(()=>this.textContent='Copiar link',2000)" class="btn btn-sm btn-ghost" style="border: 1px solid var(--border);">Copiar link</button>
            </div>
        </div>

        <!-- RIGHT: Sidebar -->
        <div style="position: sticky; top: 5rem;">
            <!-- Contact Card -->
            <div style="background: var(--white); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 1.5rem;">
                <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem;">Información de contacto</h3>

                <?php if (!empty($negocio['direccion'])): ?>
                    <div style="display: flex; gap: 0.6rem; margin-bottom: 1rem; font-size: 0.9rem; color: var(--text-light);">
                        <span>📍</span>
                        <span><?= htmlspecialchars($negocio['direccion']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['horario'])): ?>
                    <div style="display: flex; gap: 0.6rem; margin-bottom: 1rem; font-size: 0.9rem; color: var(--text-light);">
                        <span>🕐</span>
                        <span><?= htmlspecialchars($negocio['horario']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['telefono'])): ?>
                    <a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>" style="display: flex; gap: 0.6rem; margin-bottom: 1rem; font-size: 0.9rem; color: var(--primary); text-decoration: none;">
                        <span>📞</span>
                        <span><?= htmlspecialchars($negocio['telefono']) ?></span>
                    </a>
                <?php endif; ?>

                <?php if (!empty($negocio['email'])): ?>
                    <a href="mailto:<?= htmlspecialchars($negocio['email']) ?>" style="display: flex; gap: 0.6rem; margin-bottom: 1rem; font-size: 0.9rem; color: var(--primary); text-decoration: none;">
                        <span>✉</span>
                        <span><?= htmlspecialchars($negocio['email']) ?></span>
                    </a>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div style="display: flex; flex-direction: column; gap: 0.6rem; margin-top: 1.25rem;">
                    <?php if (!empty($negocio['whatsapp'])): ?>
                        <a href="https://wa.me/56<?= preg_replace('/\D/', '', $negocio['whatsapp']) ?>" target="_blank" rel="noopener" class="btn" style="background: #25D366; color: white; width: 100%;">
                            💬 WhatsApp
                        </a>
                    <?php elseif (!empty($negocio['telefono'])): ?>
                        <a href="https://wa.me/56<?= preg_replace('/\D/', '', $negocio['telefono']) ?>" target="_blank" rel="noopener" class="btn" style="background: #25D366; color: white; width: 100%;">
                            💬 WhatsApp
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($negocio['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($negocio['email']) ?>" class="btn btn-primary" style="width: 100%;">
                            ✉ Enviar mensaje
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/contacto" class="btn btn-primary" style="width: 100%;">
                            ✉ Enviar mensaje
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($negocio['sitio_web'])): ?>
                        <a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener" class="btn btn-outline" style="width: 100%;">
                            🌐 Visitar sitio web
                        </a>
                    <?php endif; ?>

                    <?php if ($hasCoords): ?>
                        <a href="<?= $gmapsUrl ?>" target="_blank" rel="noopener" class="btn btn-ghost" style="width: 100%; border: 1px solid var(--border);">
                            🗺 Ver en Google Maps
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Redes sociales del negocio -->
            <?php
            $redes = [];
            $redesMap = [
                'facebook' => ['📘', 'Facebook'],
                'instagram' => ['📷', 'Instagram'],
                'tiktok' => ['🎵', 'TikTok'],
                'youtube' => ['🎬', 'YouTube'],
                'twitter' => ['🐦', 'X/Twitter'],
                'linkedin' => ['💼', 'LinkedIn'],
                'telegram' => ['✈', 'Telegram'],
                'pinterest' => ['📌', 'Pinterest'],
            ];
            foreach ($redesMap as $key => [$icon, $label]) {
                if (!empty($negocio[$key])) $redes[$key] = ['icon' => $icon, 'label' => $label, 'url' => $negocio[$key]];
            }
            // Fallback to old fields
            if (empty($redes) && !empty($negocio['red_social_1'])) $redes['rs1'] = ['icon' => '🔗', 'label' => parse_url($negocio['red_social_1'], PHP_URL_HOST) ?: 'Red social', 'url' => $negocio['red_social_1']];
            if (empty($redes) && !empty($negocio['red_social_2'])) $redes['rs2'] = ['icon' => '🔗', 'label' => parse_url($negocio['red_social_2'], PHP_URL_HOST) ?: 'Red social', 'url' => $negocio['red_social_2']];
            ?>
            <?php if (!empty($redes)): ?>
                <div style="background: var(--white); border-radius: var(--radius-lg); padding: 1.5rem; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                    <h3 style="margin-bottom: 1rem; font-size: 1rem;">Redes sociales</h3>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <?php foreach ($redes as $red): ?>
                            <a href="<?= htmlspecialchars($red['url']) ?>" target="_blank" rel="noopener" class="text-sm" style="color: var(--primary); display: flex; align-items: center; gap: 0.4rem;">
                                <?= $red['icon'] ?> <?= htmlspecialchars($red['label']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- Schema.org -->
<?= SeoHelper::schemaLocalBusiness($negocio) ?? '' ?>

<!-- Map + Star Picker Scripts -->
<?php
$extraScripts = '';
if ($hasCoords) {
    $extraScripts .= '<script>
    var map = L.map("mapNegocio").setView([' . $negocio['lat'] . ', ' . $negocio['lng'] . '], 15);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {attribution: "© OpenStreetMap"}).addTo(map);
    L.marker([' . $negocio['lat'] . ', ' . $negocio['lng'] . ']).addTo(map).bindPopup("<strong>' . addslashes($negocio['nombre']) . '</strong>").openPopup();
    </script>';
}
$extraScripts .= '<script>
document.querySelectorAll("#starPicker span").forEach(function(star) {
    star.addEventListener("click", function() {
        var val = parseInt(this.dataset.star);
        document.getElementById("starValue").value = val;
        document.querySelectorAll("#starPicker span").forEach(function(s, i) {
            s.style.color = (i < val) ? "var(--accent)" : "var(--border)";
        });
    });
    star.addEventListener("mouseenter", function() {
        var val = parseInt(this.dataset.star);
        document.querySelectorAll("#starPicker span").forEach(function(s, i) {
            s.style.color = (i < val) ? "var(--accent)" : "var(--border)";
        });
    });
});
document.getElementById("starPicker").addEventListener("mouseleave", function() {
    var val = parseInt(document.getElementById("starValue").value);
    document.querySelectorAll("#starPicker span").forEach(function(s, i) {
        s.style.color = (i < val) ? "var(--accent)" : "var(--border)";
    });
});
</script>';
$extraScripts .= '<script>
function toggleFavorito(negocioId, btn) {
    fetch("<?= SITE_URL ?>/api/favorito", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "negocio_id=" + negocioId
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.ok) {
            var icon = btn.querySelector(".fav-icon");
            var text = btn.querySelector(".fav-text");
            if (data.action === "added") {
                icon.textContent = "\u2665";
                text.textContent = "En favoritos";
                btn.style.color = "#EF4444";
            } else {
                icon.textContent = "\u2661";
                text.textContent = "Favorito";
                btn.style.color = "";
            }
        }
    });
}
</script>';
?>

<style>
@media (max-width: 768px) {
    .container > div[style*="grid-template-columns: 1fr 360px"] {
        grid-template-columns: 1fr !important;
    }
    div[style*="position: sticky"] {
        position: static !important;
    }
}
</style>
