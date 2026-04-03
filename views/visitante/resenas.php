<h1 style="margin-bottom: 2rem;">Mis Reseñas</h1>

<?php if (empty($resenas)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-light); margin-bottom: 1rem;">Aún no has dejado ninguna reseña.</p>
        <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary">Explorar directorio</a>
    </div>
<?php else: ?>
    <?php foreach ($resenas as $res): ?>
        <div class="card" style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($res['negocio_slug'] ?? '') ?>" style="font-weight: 600; font-size: 1.05rem; color: var(--primary); text-decoration: none;">
                    <?= htmlspecialchars($res['negocio_nombre'] ?? 'Negocio eliminado') ?>
                </a>
                <div>
                    <span style="color: var(--accent); letter-spacing: 2px;">
                        <?php for ($i = 1; $i <= 5; $i++): ?><?= $i <= (int)$res['puntuacion'] ? '★' : '☆' ?><?php endfor; ?>
                    </span>
                    <span style="font-size: 0.8rem; color: var(--text-light); margin-left: 0.5rem;"><?= date('d/m/Y', strtotime($res['created_at'])) ?></span>
                </div>
            </div>
            <?php if (!empty($res['comentario'])): ?>
                <p style="color: var(--text-light); line-height: 1.6; margin: 0;"><?= nl2br(htmlspecialchars($res['comentario'])) ?></p>
            <?php endif; ?>
            <?php
            $estadoColor = ['pendiente' => '#F59E0B', 'aprobada' => '#22C55E', 'rechazada' => '#EF4444'];
            $estadoLabel = ['pendiente' => 'Pendiente', 'aprobada' => 'Publicada', 'rechazada' => 'Rechazada'];
            $est = $res['estado'] ?? 'pendiente';
            ?>
            <span style="display: inline-block; margin-top: 0.5rem; padding: 0.15rem 0.6rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; background: <?= $estadoColor[$est] ?? '#ccc' ?>20; color: <?= $estadoColor[$est] ?? '#666' ?>;"><?= $estadoLabel[$est] ?? $est ?></span>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
