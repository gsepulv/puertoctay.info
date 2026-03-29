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
        <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 1.5rem; box-shadow: var(--shadow-md); position: relative;">
            <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($heroImg) ?>"
                 alt="<?= htmlspecialchars($negocio['nombre']) ?>"
                 style="width: 100%; height: 400px; object-fit: cover; display: block;">
            <?php if (!empty($negocio['logo'])): ?>
                <div style="position: absolute; bottom: -30px; left: 2rem; width: 80px; height: 80px; border-radius: 50%; border: 4px solid var(--white); overflow: hidden; box-shadow: var(--shadow-md); background: var(--white);">
                    <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['logo']) ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($negocio['logo'])): ?>
            <div style="height: 35px;"></div>
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
                    <h3 style="margin-bottom: 1rem;">Deja tu reseña</h3>
                    <form method="POST" action="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($negocio['slug']) ?>/resena" id="reviewForm">
                        <?= csrf_field() ?>
                        <input type="hidden" name="negocio_id" value="<?= (int)$negocio['id'] ?>">

                        <!-- Star Rating -->
                        <div class="form-group">
                            <label>Calificación</label>
                            <div id="starPicker" style="font-size: 1.8rem; cursor: pointer; letter-spacing: 4px;">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span data-star="<?= $i ?>" style="color: var(--border); transition: color 0.15s;">★</span>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="puntuacion" id="starValue" value="5" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="rev_nombre">Nombre</label>
                                <input type="text" id="rev_nombre" name="nombre_autor" required placeholder="Tu nombre">
                            </div>
                            <div class="form-group">
                                <label for="rev_email">Email</label>
                                <input type="email" id="rev_email" name="email_autor" required placeholder="tu@email.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rev_comentario">Comentario</label>
                            <textarea id="rev_comentario" name="comentario" rows="4" placeholder="Cuéntanos tu experiencia..."></textarea>
                        </div>

                        <!-- Honeypot -->
                        <div style="position: absolute; left: -9999px;">
                            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                        </div>

                        <button type="submit" class="btn btn-primary">Enviar reseña</button>
                    </form>
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
                                    <img class="card-img" src="<?= SITE_URL ?>/uploads/negocios/<?= htmlspecialchars($sim['foto_principal']) ?>" alt="<?= htmlspecialchars($sim['nombre']) ?>" loading="lazy">
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
