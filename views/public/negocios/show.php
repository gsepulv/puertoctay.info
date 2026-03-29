<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <a href="<?= SITE_URL ?>/directorio">Directorio</a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($negocio['nombre']) ?></span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;">
            <div>
                <?php if (!empty($negocio['foto_principal'])): ?>
                <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 1.5rem;">
                    <img src="<?= SITE_URL ?>/uploads/negocios/<?= htmlspecialchars($negocio['foto_principal']) ?>" alt="<?= htmlspecialchars($negocio['nombre']) ?>" style="width: 100%; height: 360px; object-fit: cover; display: block;">
                </div>
                <?php else: ?>
                <div style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: var(--radius-lg); height: 360px; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                </div>
                <?php endif; ?>

                <h1><?= htmlspecialchars($negocio['nombre']) ?></h1>

                <div class="flex flex-wrap items-center mb-2" style="gap: 0.5rem;">
                    <?php if (!empty($negocio['categoria_nombre'])): ?>
                    <a href="<?= SITE_URL ?>/categoria/<?= htmlspecialchars($negocio['categoria_slug']) ?>" class="badge badge-primary"><?= $negocio['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($negocio['categoria_nombre']) ?></a>
                    <?php endif; ?>
                    <?php if (!empty($negocio['verificado'])): ?>
                    <span class="badge badge-green">✓ Verificado</span>
                    <?php endif; ?>
                </div>

                <?php if ($rating > 0): ?>
                <div class="flex items-center mb-2" style="gap: 0.5rem;">
                    <span class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?= $i <= round($rating) ? '★' : '☆' ?>
                        <?php endfor; ?>
                    </span>
                    <span class="text-sm text-light"><?= number_format($rating, 1) ?> (<?= count($resenas) ?> <?= count($resenas) === 1 ? 'resena' : 'resenas' ?>)</span>
                </div>
                <?php endif; ?>

                <?php if (!empty($negocio['descripcion_corta'])): ?>
                <div class="mb-2">
                    <p><?= nl2br(htmlspecialchars($negocio['descripcion_corta'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($negocio['como_llegar'])): ?>
                <div class="mb-2">
                    <h3>Como llegar</h3>
                    <p><?= nl2br(htmlspecialchars($negocio['como_llegar'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($negocio['lat']) && !empty($negocio['lng'])): ?>
                <div class="mb-2">
                    <h3>📍 Ubicacion</h3>
                    <div id="mapNegocio" style="height: 300px; border-radius: var(--radius-md);"></div>
                </div>
                <?php endif; ?>

                <?php if (!empty($resenas)): ?>
                <div class="mt-3">
                    <h3>💬 Resenas (<?= count($resenas) ?>)</h3>
                    <?php foreach ($resenas as $resena): ?>
                    <div class="card mb-1" style="padding: 1rem;">
                        <div class="flex justify-between items-center mb-1">
                            <div>
                                <strong>👤 <?= htmlspecialchars($resena['nombre_autor']) ?></strong>
                                <span class="stars" style="margin-left: 0.5rem;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?= $i <= (int)$resena['puntuacion'] ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <span class="text-sm text-light">📅 <?= date('d/m/Y', strtotime($resena['created_at'])) ?></span>
                        </div>
                        <p><?= nl2br(htmlspecialchars($resena['comentario'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <div class="card" style="padding: 1.5rem; position: sticky; top: 1rem;">
                    <h3 style="margin-bottom: 1rem;">Informacion de contacto</h3>

                    <?php if (!empty($negocio['direccion'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">📍 Direccion</p>
                        <p><?= htmlspecialchars($negocio['direccion']) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($negocio['telefono'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">📞 Telefono</p>
                        <p><a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>"><?= htmlspecialchars($negocio['telefono']) ?></a></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($negocio['email'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">✉ Email</p>
                        <p><a href="mailto:<?= htmlspecialchars($negocio['email']) ?>"><?= htmlspecialchars($negocio['email']) ?></a></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($negocio['sitio_web'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">🌐 Sitio web</p>
                        <p><a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($negocio['sitio_web']) ?></a></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($negocio['whatsapp'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">💬 WhatsApp</p>
                        <p><a href="https://wa.me/<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $negocio['whatsapp'])) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($negocio['whatsapp']) ?></a></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($negocio['horario'])): ?>
                    <div class="mb-1">
                        <p class="text-sm text-light">🕐 Horario</p>
                        <p><?= nl2br(htmlspecialchars($negocio['horario'])) ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?= SeoHelper::schemaLocalBusiness($negocio) ?? '' ?>

<?php if (!empty($negocio['lat']) && !empty($negocio['lng'])): ?>
<?php $extraScripts = '<script>
document.addEventListener("DOMContentLoaded", function() {
    var lat = ' . (float)$negocio['lat'] . ';
    var lng = ' . (float)$negocio['lng'] . ';
    var map = L.map("mapNegocio").setView([lat, lng], 15);
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);
    L.marker([lat, lng]).addTo(map)
        .bindPopup("<strong>' . addslashes(htmlspecialchars($negocio['nombre'])) . '</strong>")
        .openPopup();
});
</script>'; ?>
<?php endif; ?>
