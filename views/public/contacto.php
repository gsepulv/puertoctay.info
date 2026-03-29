<?php $old = $_SESSION['form_data'] ?? []; unset($_SESSION['form_data']); ?>

<div class="section">
    <h1 class="section-title">Contacto</h1>
    <p style="color:#666; margin-bottom:1.5rem;">Tienes alguna consulta, sugerencia o comentario? Escribenos y te responderemos a la brevedad.</p>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; padding:0.8rem 1rem; border-radius:6px; margin-bottom:1rem;">
            <?= $_SESSION['flash_success'] ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div style="background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; padding:0.8rem 1rem; border-radius:6px; margin-bottom:1rem;">
            <?= $_SESSION['flash_error'] ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <div style="max-width:600px;">
        <form method="POST" action="<?= SITE_URL ?>/contacto" style="background:#fff; padding:1.5rem; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            <?= csrf_field() ?>

            <!-- Honeypot -->
            <div style="position:absolute; left:-9999px;" aria-hidden="true">
                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-weight:600; margin-bottom:0.3rem;">Nombre *</label>
                <input type="text" name="nombre" required minlength="2" maxlength="100"
                       value="<?= htmlspecialchars($old['nombre'] ?? '') ?>"
                       style="width:100%; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-size:0.95rem;">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-weight:600; margin-bottom:0.3rem;">Email *</label>
                <input type="email" name="email" required maxlength="150"
                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                       style="width:100%; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-size:0.95rem;">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-weight:600; margin-bottom:0.3rem;">Asunto</label>
                <input type="text" name="asunto" maxlength="200"
                       value="<?= htmlspecialchars($old['asunto'] ?? '') ?>"
                       style="width:100%; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-size:0.95rem;">
            </div>

            <div style="margin-bottom:1rem;">
                <label style="display:block; font-weight:600; margin-bottom:0.3rem;">Mensaje *</label>
                <textarea name="mensaje" required minlength="10" maxlength="2000" rows="6"
                          style="width:100%; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-size:0.95rem; resize:vertical;"><?= htmlspecialchars($old['mensaje'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; padding:0.7rem; font-size:1rem;">Enviar mensaje</button>
        </form>
    </div>
</div>
