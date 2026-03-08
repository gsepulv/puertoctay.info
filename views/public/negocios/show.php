<?= SeoHelper::schemaLocalBusiness($negocio) ?>

<section class="section">
    <div style="margin-bottom:0.5rem;">
        <a href="<?= SITE_URL ?>/directorio" style="font-size:0.9rem;">← Directorio</a>
        <?php if (!empty($negocio['categoria_slug'])): ?>
            / <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($negocio['categoria_slug']) ?>" style="font-size:0.9rem;"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?></a>
        <?php endif; ?>
    </div>

    <h1 style="font-size:2rem; margin-bottom:0.3rem;">
        <?= htmlspecialchars($negocio['nombre']) ?>
        <?php if ($negocio['verificado']): ?>
            <span class="badge badge-green" title="Verificado">✓ Verificado</span>
        <?php endif; ?>
        <?php if (!empty($negocio['plan_badge'])): ?>
            <span class="badge badge-gold"><?= htmlspecialchars($negocio['plan_nombre'] ?? 'Destacado') ?></span>
        <?php endif; ?>
    </h1>

    <?php if (!empty($negocio['categoria_nombre'])): ?>
        <p style="color:#888; margin-bottom:1rem;"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?></p>
    <?php endif; ?>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-top:1.5rem;">
        <!-- Columna izquierda: foto + descripción -->
        <div style="min-width:0;">
            <?php if (!empty($negocio['foto_principal'])): ?>
                <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($negocio['foto_principal']) ?>"
                     alt="<?= htmlspecialchars($negocio['nombre']) ?>"
                     style="width:100%; border-radius:10px; margin-bottom:1rem; max-height:400px; object-fit:cover;">
            <?php endif; ?>

            <?php if (!empty($negocio['descripcion_corta'])): ?>
                <p style="font-size:1.1rem; color:#555; margin-bottom:1rem;"><?= htmlspecialchars($negocio['descripcion_corta']) ?></p>
            <?php endif; ?>

            <?php if (!empty($negocio['descripcion_larga'])): ?>
                <div style="line-height:1.8;"><?= nl2br(htmlspecialchars($negocio['descripcion_larga'])) ?></div>
            <?php endif; ?>
        </div>

        <!-- Columna derecha: info de contacto -->
        <div>
            <div style="background:#fff; border-radius:10px; padding:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                <h3 style="font-size:1.1rem; margin-bottom:1rem;">Información de contacto</h3>

                <?php if (!empty($negocio['direccion'])): ?>
                    <p style="margin-bottom:0.7rem;">📍 <?= htmlspecialchars($negocio['direccion']) ?></p>
                <?php endif; ?>

                <?php if (!empty($negocio['horario'])): ?>
                    <p style="margin-bottom:0.7rem;">🕐 <?= htmlspecialchars($negocio['horario']) ?></p>
                <?php endif; ?>

                <?php if (!empty($negocio['telefono'])): ?>
                    <p style="margin-bottom:0.7rem;">📞 <a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>"><?= htmlspecialchars($negocio['telefono']) ?></a></p>
                <?php endif; ?>

                <?php if (!empty($negocio['email'])): ?>
                    <p style="margin-bottom:0.7rem;">✉️ <a href="mailto:<?= htmlspecialchars($negocio['email']) ?>"><?= htmlspecialchars($negocio['email']) ?></a></p>
                <?php endif; ?>

                <?php if (!empty($negocio['sitio_web'])): ?>
                    <p style="margin-bottom:0.7rem;">🌐 <a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(parse_url($negocio['sitio_web'], PHP_URL_HOST) ?: $negocio['sitio_web']) ?></a></p>
                <?php endif; ?>

                <?php if (!empty($negocio['precio_referencial'])): ?>
                    <p style="margin-bottom:0.7rem;">💰 <?= htmlspecialchars($negocio['precio_referencial']) ?></p>
                <?php endif; ?>

                <?php if (!empty($negocio['whatsapp'])): ?>
                    <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/\D/', '', $negocio['whatsapp'])) ?>?text=<?= urlencode('Hola, vi su negocio en ' . SITE_NAME) ?>"
                       class="btn btn-secondary" style="width:100%; text-align:center; margin-top:1rem; display:block;" target="_blank" rel="noopener">
                        💬 Contactar por WhatsApp
                    </a>
                <?php endif; ?>
            </div>

            <?php if (!empty($negocio['como_llegar'])): ?>
                <div style="background:#fff; border-radius:10px; padding:1.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-top:1rem;">
                    <h3 style="font-size:1.1rem; margin-bottom:0.5rem;">Cómo llegar</h3>
                    <p><?= nl2br(htmlspecialchars($negocio['como_llegar'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mapa -->
    <?php if (!empty($negocio['lat']) && !empty($negocio['lng'])): ?>
    <div style="margin-top:2rem;">
        <h3 class="section-title">Ubicación</h3>
        <div id="mapa-negocio" style="height:350px; border-radius:10px; overflow:hidden;"></div>
    </div>
    <?php
    $extraScripts = $extraScripts ?? '';
    $extraScripts .= '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map("mapa-negocio").setView([' . (float)$negocio['lat'] . ', ' . (float)$negocio['lng'] . '], 15);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; OpenStreetMap"
            }).addTo(map);
            L.marker([' . (float)$negocio['lat'] . ', ' . (float)$negocio['lng'] . '])
                .addTo(map)
                .bindPopup("' . htmlspecialchars(addslashes($negocio['nombre']), ENT_QUOTES, 'UTF-8') . '")
                .openPopup();
        });
    </script>';
    ?>
    <?php endif; ?>

    <!-- Reseñas -->
    <div style="margin-top:2rem;">
        <h3 class="section-title">
            Reseñas
            <?php if ($rating && $rating['total'] > 0): ?>
                <span style="font-family:'Source Sans 3',sans-serif; font-size:1rem; color:#888;">
                    — <?= number_format((float)$rating['promedio'], 1) ?>/5 (<?= (int)$rating['total'] ?> reseña<?= (int)$rating['total'] !== 1 ? 's' : '' ?>)
                </span>
            <?php endif; ?>
        </h3>

        <?php if (empty($resenas)): ?>
            <p class="empty-state">Aún no hay reseñas para este negocio.</p>
        <?php else: ?>
            <?php foreach ($resenas as $r): ?>
            <div style="background:#fff; border-radius:8px; padding:1rem; margin-bottom:0.8rem; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.3rem;">
                    <strong><?= htmlspecialchars($r['nombre_autor']) ?></strong>
                    <span style="color:#f39c12;">
                        <?= str_repeat('★', (int)$r['puntuacion']) ?><?= str_repeat('☆', 5 - (int)$r['puntuacion']) ?>
                    </span>
                </div>
                <?php if (!empty($r['comentario'])): ?>
                    <p style="color:#555; font-size:0.95rem;"><?= htmlspecialchars($r['comentario']) ?></p>
                <?php endif; ?>
                <p style="font-size:0.8rem; color:#aaa; margin-top:0.3rem;"><?= date('d/m/Y', strtotime($r['created_at'])) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<style>
@media (max-width: 768px) {
    section > div[style*="grid-template-columns:1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
