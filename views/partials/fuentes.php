<?php
/**
 * Partial: Sección "Fuentes" al pie de páginas de contenido.
 * Requiere: $fuentes = [['nombre' => '...', 'url' => '...'], ...]
 */
if (!empty($fuentes)): ?>
<div style="margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border);">
    <p style="font-size: 0.85rem; color: var(--text-lighter); line-height: 1.8;">
        📖 <strong style="color: var(--text-light);">Fuentes:</strong>
        <?php foreach ($fuentes as $i => $f): ?><a href="<?= htmlspecialchars($f['url']) ?>" target="_blank" rel="noopener noreferrer" style="color: var(--primary);"><?= htmlspecialchars($f['nombre']) ?></a><?= $i < count($fuentes) - 1 ? ', ' : '.' ?>
        <?php endforeach; ?>
        <a href="<?= SITE_URL ?>/paginas-amigas" style="color: var(--text-lighter); margin-left: 0.3rem;">Ver todas nuestras fuentes</a>
    </p>
</div>
<?php endif; ?>
