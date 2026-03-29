<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<p style="color:#888; font-size:0.9rem; margin-bottom:1.5rem;">Configura los enlaces a las redes sociales. Se mostrarán en el footer del sitio y en la página de construcción.</p>

<div class="form-card">
    <form method="POST" action="<?= SITE_URL ?>/admin/redes-sociales/guardar">
        <?= csrf_field() ?>

        <?php
        $iconos = [
            'facebook' => '📘',
            'instagram' => '📷',
            'youtube' => '🎬',
            'tiktok' => '🎵',
            'whatsapp' => '💬',
            'twitter' => '🐦',
        ];
        ?>

        <?php foreach ($campos as $campo): ?>
            <div class="form-group">
                <label for="campo_<?= htmlspecialchars($campo['clave']) ?>">
                    <?= $iconos[$campo['clave']] ?? '🔗' ?> <?= htmlspecialchars($campo['etiqueta']) ?>
                </label>
                <input type="url" id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                       name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                       value="<?= htmlspecialchars($campo['valor'] ?? '') ?>"
                       placeholder="https://...">
            </div>
        <?php endforeach; ?>

        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>

<div class="dash-section" style="margin-top:1.5rem;">
    <h3>Vista previa</h3>
    <p style="font-size:0.85rem; color:#888; margin-bottom:0.8rem;">Así se verán los iconos en el footer del sitio:</p>
    <div style="display:flex; gap:0.8rem; padding:1rem; background:#1a2530; border-radius:8px; justify-content:center;">
        <?php foreach ($campos as $campo): ?>
            <?php if (!empty($campo['valor'])): ?>
                <a href="<?= htmlspecialchars($campo['valor']) ?>" target="_blank" style="color:#ddd; font-size:1.3rem; text-decoration:none;" title="<?= htmlspecialchars($campo['etiqueta']) ?>">
                    <?= $iconos[$campo['clave']] ?? '🔗' ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if (empty(array_filter(array_column($campos, 'valor')))): ?>
            <span style="color:#888; font-size:0.85rem;">No hay redes sociales configuradas aún.</span>
        <?php endif; ?>
    </div>
</div>
