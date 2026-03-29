<?php /** @var array $negocio @var array $resenas @var float $rating @var bool $usarLeaflet */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <a href="/directorio">Directorio</a>
    <span>/</span>
    <span><?= htmlspecialchars($negocio['nombre']) ?></span>
</nav>

<div class="container">
    <div class="detail-layout" style="display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:start;">
        <!-- Main Column -->
        <div class="detail-main">
            <?php if (!empty($negocio['foto_principal'])): ?>
                <div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:1.5rem;">
                    <img src="<?= htmlspecialchars($negocio['foto_principal']) ?>" alt="<?= htmlspecialchars($negocio['nombre']) ?>" style="width:100%;height:360px;object-fit:cover;display:block;">
                </div>
            <?php else: ?>
                <div style="width:100%;height:360px;border-radius:var(--radius-lg);background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                    <span style="font-size:4rem;opacity:.5;">&#128444;</span>
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom:.5rem;"><?= htmlspecialchars($negocio['nombre']) ?></h1>

            <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;">
                <?php if (!empty($negocio['categoria_nombre'])): ?>
                    <span class="badge"><?= htmlspecialchars($negocio['categoria_nombre']) ?></span>
                <?php endif; ?>
                <?php if (!empty($negocio['tipo'])): ?>
                    <span class="badge badge-outline"><?= htmlspecialchars(ucfirst($negocio['tipo'])) ?></span>
                <?php endif; ?>
                <?php if (!empty($negocio['verificado'])): ?>
                    <span class="badge badge-success">&#10003; Verificado</span>
                <?php endif; ?>
            </div>

            <?php if ($rating > 0): ?>
                <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:1.5rem;">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?= $i <= round($rating) ? 'star-filled' : '' ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                    <span><?= number_format($rating, 1) ?></span>
                    <span style="color:var(--text-muted);">(<?= count($resenas) ?> <?= count($resenas) === 1 ? 'resena' : 'resenas' ?>)</span>
                </div>
            <?php endif; ?>

            <?php if (!empty($negocio['descripcion'])): ?>
                <div style="margin-bottom:2rem;line-height:1.8;">
                    <?= nl2br(htmlspecialchars($negocio['descripcion'])) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($negocio['como_llegar'])): ?>
                <div style="margin-bottom:2rem;">
                    <h3 style="margin-bottom:.75rem;">&#128205; Como llegar</h3>
                    <p style="line-height:1.7;"><?= nl2br(htmlspecialchars($negocio['como_llegar'])) ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <aside>
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;">Informacion de contacto</h3>

                <?php if (!empty($negocio['direccion'])): ?>
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                        <span>&#128205;</span>
                        <span><?= htmlspecialchars($negocio['direccion']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['telefono'])): ?>
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                        <span>&#128222;</span>
                        <a href="tel:<?= htmlspecialchars($negocio['telefono']) ?>"><?= htmlspecialchars($negocio['telefono']) ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['email'])): ?>
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                        <span>&#9993;</span>
                        <a href="mailto:<?= htmlspecialchars($negocio['email']) ?>"><?= htmlspecialchars($negocio['email']) ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['sitio_web'])): ?>
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                        <span>&#127760;</span>
                        <a href="<?= htmlspecialchars($negocio['sitio_web']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars(preg_replace('#^https?://#', '', $negocio['sitio_web'])) ?></a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['whatsapp'])): ?>
                    <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                        <span>&#128172;</span>
                        <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $negocio['whatsapp'])) ?>" target="_blank" rel="noopener">WhatsApp</a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($negocio['horario'])): ?>
                    <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);">
                        <h4 style="margin-bottom:.5rem;">&#128336; Horario</h4>
                        <p style="line-height:1.7;"><?= nl2br(htmlspecialchars($negocio['horario'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>

    <!-- Map -->
    <?php if ($usarLeaflet && !empty($negocio['latitud']) && !empty($negocio['longitud'])): ?>
        <div class="section">
            <h2 style="margin-bottom:1rem;">Ubicacion</h2>
            <div id="map" style="height:350px;border-radius:var(--radius-lg);"></div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = <?= floatval($negocio['latitud']) ?>;
            var lng = <?= floatval($negocio['longitud']) ?>;
            var map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map)
                .bindPopup('<strong><?= addslashes(htmlspecialchars($negocio['nombre'])) ?></strong>').openPopup();
        });
        </script>
    <?php endif; ?>

    <!-- Reviews -->
    <?php if (!empty($resenas)): ?>
        <div class="section">
            <h2 style="margin-bottom:1.5rem;">Resenas (<?= count($resenas) ?>)</h2>
            <div style="display:grid;gap:1rem;">
                <?php foreach ($resenas as $resena): ?>
                    <div class="card" style="padding:1.25rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.75rem;">
                            <strong><?= htmlspecialchars($resena['autor'] ?? 'Anonimo') ?></strong>
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i <= ($resena['rating'] ?? 0) ? 'star-filled' : '' ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if (!empty($resena['comentario'])): ?>
                            <p style="line-height:1.7;color:var(--text-secondary);"><?= nl2br(htmlspecialchars($resena['comentario'])) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($resena['fecha'])): ?>
                            <small style="color:var(--text-muted);margin-top:.5rem;display:block;"><?= date('d/m/Y', strtotime($resena['fecha'])) ?></small>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= SeoHelper::schemaLocalBusiness($negocio) ?? '' ?>
