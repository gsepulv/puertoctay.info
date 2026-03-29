<div style="margin-bottom:1rem;">
    <a href="<?= SITE_URL ?>/admin/mensajes" class="btn btn-secondary btn-sm">&larr; Volver a mensajes</a>
</div>

<div class="form-card">
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <strong>De:</strong> <?= htmlspecialchars($mensaje['nombre']) ?>
        </div>
        <div>
            <strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($mensaje['email']) ?>"><?= htmlspecialchars($mensaje['email']) ?></a>
        </div>
        <div>
            <strong>Asunto:</strong> <?= htmlspecialchars($mensaje['asunto'] ?? 'Sin asunto') ?>
        </div>
        <div>
            <strong>Fecha:</strong> <?= htmlspecialchars($mensaje['created_at']) ?>
        </div>
        <div>
            <strong>IP:</strong> <?= htmlspecialchars($mensaje['ip'] ?? 'Desconocida') ?>
        </div>
        <div>
            <strong>Estado:</strong>
            <?php if ($mensaje['leido']): ?>
                <span class="badge badge-green">Leido</span>
            <?php else: ?>
                <span class="badge badge-blue">Nuevo</span>
            <?php endif; ?>
        </div>
    </div>

    <hr style="border:none; border-top:1px solid #eee; margin:1rem 0;">

    <div style="background:#f8f9fa; border-radius:6px; padding:1.2rem; white-space:pre-wrap; line-height:1.7; font-size:0.95rem;">
<?= htmlspecialchars($mensaje['mensaje']) ?>
    </div>

    <div style="margin-top:1.5rem; display:flex; gap:0.5rem;">
        <a href="mailto:<?= htmlspecialchars($mensaje['email']) ?>?subject=Re: <?= urlencode($mensaje['asunto'] ?? 'Contacto ' . SITE_NAME) ?>" class="btn btn-primary">Responder por email</a>
        <form method="POST" action="<?= SITE_URL ?>/admin/mensajes/<?= $mensaje['id'] ?>/eliminar" onsubmit="return confirm('Eliminar este mensaje?')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>
</div>
