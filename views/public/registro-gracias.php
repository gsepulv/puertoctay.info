<?php
/**
 * Página de confirmación post-registro
 * Variables disponibles: $email, $nombre (del negocio)
 */
?>
<section class="section-sm">
<div class="container" style="max-width: 600px;">
    <div style="background: #F0FDF4; border: 2px solid #22C55E; border-radius: var(--radius-lg); padding: 2.5rem; text-align: center;">
        <div style="width: 64px; height: 64px; background: #22C55E; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 style="margin-bottom: 0.5rem; color: #166534; font-size: 1.6rem;">¡Registro recibido!</h1>
        <?php if (!empty($email)): ?>
            <p style="color: #166534; margin-bottom: 1rem;">Hemos enviado un correo de confirmación a <strong><?= htmlspecialchars($email) ?></strong></p>
        <?php endif; ?>
        <p style="color: #15803D; margin-bottom: 1.5rem; line-height: 1.6;">
            Nuestro equipo revisará los datos de tu comercio y te notificaremos por email en un máximo de <strong>48 horas</strong>.
        </p>

        <div style="background: #EFF6FF; border: 1px solid #93C5FD; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1rem; text-align: left; font-size: 0.9rem; color: #1E40AF;">
            <p style="margin: 0;">💡 Una vez aprobado, podrás agregar más fotos a tu galería, actualizar horarios y gestionar reseñas desde tu panel de comerciante.</p>
        </div>

        <div style="background: #fff; border: 1px solid #BBF7D0; border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1.5rem; text-align: left; font-size: 0.9rem; color: #166534;">
            <p style="margin: 0 0 0.5rem;"><strong>¿Tienes consultas?</strong></p>
            <p style="margin: 0.25rem 0;">Email: <a href="mailto:contacto@purranque.info" style="color: #166534;">contacto@purranque.info</a></p>
            <p style="margin: 0.25rem 0;">WhatsApp: <a href="https://wa.me/56976547757" style="color: #166534;">+56 9 7654 7757</a></p>
        </div>

        <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?= SITE_URL ?>/" class="btn btn-primary">Volver al inicio</a>
            <a href="<?= SITE_URL ?>/directorio" class="btn btn-outline">Explorar Puerto Octay</a>
        </div>
    </div>
</div>
</section>
