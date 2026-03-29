<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success'] ?></div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<div class="form-card" style="max-width:600px;">
    <h3 style="margin-bottom:1rem; color:<?= COLOR_PRIMARY ?>;">Colores del tema</h3>
    <p style="color:#888; font-size:0.85rem; margin-bottom:1.5rem;">
        Estos colores se usan en todo el sitio. Modifica los valores y guarda los cambios.
    </p>

    <form method="POST" action="<?= SITE_URL ?>/admin/apariencia/guardar">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Color primario</label>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="color" name="color_primary" value="<?= htmlspecialchars($colores['color_primary']) ?>" style="width:60px; height:40px; border:2px solid #dee2e6; border-radius:6px; cursor:pointer; padding:2px;">
                <input type="text" id="txt_primary" value="<?= htmlspecialchars($colores['color_primary']) ?>" readonly style="flex:1; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-family:monospace; background:#f8f9fa;">
                <div style="width:40px; height:40px; border-radius:6px; background:<?= htmlspecialchars($colores['color_primary']) ?>;"></div>
            </div>
        </div>

        <div class="form-group">
            <label>Color secundario</label>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="color" name="color_secondary" value="<?= htmlspecialchars($colores['color_secondary']) ?>" style="width:60px; height:40px; border:2px solid #dee2e6; border-radius:6px; cursor:pointer; padding:2px;">
                <input type="text" id="txt_secondary" value="<?= htmlspecialchars($colores['color_secondary']) ?>" readonly style="flex:1; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-family:monospace; background:#f8f9fa;">
                <div style="width:40px; height:40px; border-radius:6px; background:<?= htmlspecialchars($colores['color_secondary']) ?>;"></div>
            </div>
        </div>

        <div class="form-group">
            <label>Color de acento</label>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <input type="color" name="color_accent" value="<?= htmlspecialchars($colores['color_accent']) ?>" style="width:60px; height:40px; border:2px solid #dee2e6; border-radius:6px; cursor:pointer; padding:2px;">
                <input type="text" id="txt_accent" value="<?= htmlspecialchars($colores['color_accent']) ?>" readonly style="flex:1; padding:0.55rem 0.8rem; border:2px solid #dee2e6; border-radius:6px; font-family:monospace; background:#f8f9fa;">
                <div style="width:40px; height:40px; border-radius:6px; background:<?= htmlspecialchars($colores['color_accent']) ?>;"></div>
            </div>
        </div>

        <!-- Preview -->
        <div style="background:#f8f9fa; border-radius:8px; padding:1rem; margin-bottom:1.5rem;">
            <h4 style="font-size:0.9rem; color:#666; margin-bottom:0.5rem;">Vista previa</h4>
            <div style="display:flex; gap:0.5rem;">
                <span class="btn" style="background:<?= htmlspecialchars($colores['color_primary']) ?>; color:#fff;">Primario</span>
                <span class="btn" style="background:<?= htmlspecialchars($colores['color_secondary']) ?>; color:#fff;">Secundario</span>
                <span class="btn" style="background:<?= htmlspecialchars($colores['color_accent']) ?>; color:#fff;">Acento</span>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar colores</button>
    </form>
</div>

<script>
document.querySelectorAll('input[type="color"]').forEach(function(picker) {
    picker.addEventListener('input', function() {
        this.nextElementSibling.value = this.value;
    });
});
</script>
