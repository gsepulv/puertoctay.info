<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div style="margin-bottom:1.5rem;">
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        <?php foreach ($grupos as $g): ?>
            <a href="<?= SITE_URL ?>/admin/configuracion?grupo=<?= urlencode($g) ?>"
               class="btn <?= $g === $grupo ? 'btn-primary' : 'btn-secondary' ?> btn-sm">
                <?= htmlspecialchars($grupoLabels[$g] ?? ucfirst($g)) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="<?= SITE_URL ?>/admin/configuracion/guardar">
        <?= csrf_field() ?>
        <input type="hidden" name="grupo" value="<?= htmlspecialchars($grupo) ?>">

        <?php foreach ($campos as $campo): ?>
            <div class="form-group">
                <label for="campo_<?= htmlspecialchars($campo['clave']) ?>">
                    <?= htmlspecialchars($campo['etiqueta']) ?>
                </label>
                <?php if ($campo['tipo'] === 'textarea'): ?>
                    <textarea id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                              rows="4"><?= htmlspecialchars($campo['valor'] ?? '') ?></textarea>
                <?php elseif ($campo['tipo'] === 'boolean'): ?>
                    <select id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                            name="campo_<?= htmlspecialchars($campo['clave']) ?>">
                        <option value="0" <?= empty($campo['valor']) ? 'selected' : '' ?>>No</option>
                        <option value="1" <?= !empty($campo['valor']) ? 'selected' : '' ?>>Sí</option>
                    </select>
                <?php else: ?>
                    <input type="<?= $campo['tipo'] === 'email' ? 'email' : ($campo['tipo'] === 'url' ? 'url' : ($campo['tipo'] === 'number' ? 'number' : ($campo['tipo'] === 'color' ? 'color' : 'text'))) ?>"
                           id="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           name="campo_<?= htmlspecialchars($campo['clave']) ?>"
                           value="<?= htmlspecialchars($campo['valor'] ?? '') ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
    </form>
</div>
