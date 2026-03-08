<div style="text-align:center; padding:4rem 2rem;">
    <div style="font-size:4rem; margin-bottom:1rem;">🚧</div>
    <h2 style="font-size:1.5rem; color:<?= COLOR_PRIMARY ?>; margin-bottom:0.5rem;"><?= htmlspecialchars($placeholderTitulo ?? 'Módulo') ?></h2>
    <p style="color:#888; max-width:400px; margin:0 auto 1.5rem;"><?= htmlspecialchars($placeholderDescripcion ?? 'Este módulo está en desarrollo.') ?></p>
    <p style="color:#aaa; font-size:0.85rem;">Este módulo estará disponible próximamente.</p>
    <a href="<?= SITE_URL ?>/admin" class="btn btn-primary" style="margin-top:1rem;">Volver al Dashboard</a>
</div>
