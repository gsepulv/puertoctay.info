<?php
/**
 * Vista admin: Gestión de Backups
 * Variables: $backups, $backupLog, $gdriveLog, $totalSize
 */
$formatBytes = function(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
};
?>

<!-- Acciones rápidas -->
<div class="dash-section">
    <h3>⚡ Acciones</h3>
    <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1rem;">
        <form method="POST" action="<?= SITE_URL ?>/admin/backups/run-local" style="display:inline;">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-primary" onclick="this.disabled=true;this.textContent='Ejecutando...';this.form.submit();">
                💾 Ejecutar Backup Local
            </button>
        </form>
        <form method="POST" action="<?= SITE_URL ?>/admin/backups/run-gdrive" style="display:inline;">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-accent" onclick="this.disabled=true;this.textContent='Subiendo...';this.form.submit();">
                ☁️ Subir a Google Drive
            </button>
        </form>
    </div>
</div>

<!-- Resumen -->
<div class="stat-grid" style="margin-top:1.5rem;">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?= count($backups) ?></div>
        <div class="stat-label">Archivos de backup</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💾</div>
        <div class="stat-value"><?= $formatBytes($totalSize) ?></div>
        <div class="stat-label">Espacio en disco</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🕐</div>
        <div class="stat-value" style="font-size:1rem;">
            <?php if (!empty($backups)): ?>
                <?= date('d/m/Y H:i', $backups[0]['date']) ?>
            <?php else: ?>
                Sin backups
            <?php endif; ?>
        </div>
        <div class="stat-label">Último backup</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏰</div>
        <div class="stat-value" style="font-size:1rem;">02:00 AM</div>
        <div class="stat-label">Backup automático</div>
    </div>
</div>

<!-- Lista de backups -->
<div class="dash-section" style="margin-top:1.5rem;">
    <h3>📁 Backups locales</h3>
    <?php if (empty($backups)): ?>
        <p style="color:#64748b; padding:1rem 0;">No hay backups disponibles. Ejecuta uno manualmente.</p>
    <?php else: ?>
        <table class="admin-table" style="margin-top:0.8rem;">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Tipo</th>
                    <th>Tamaño</th>
                    <th>Fecha</th>
                    <th style="width:80px;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backups as $b): ?>
                <tr>
                    <td style="font-family:monospace;font-size:0.82rem;"><?= htmlspecialchars($b['name']) ?></td>
                    <td>
                        <?php if ($b['type'] === 'db'): ?>
                            <span class="badge badge-blue">🐬 Base de datos</span>
                        <?php else: ?>
                            <span class="badge badge-green">📁 Uploads</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $formatBytes($b['size']) ?></td>
                    <td><?= date('d/m/Y H:i', $b['date']) ?></td>
                    <td>
                        <form method="POST" action="<?= SITE_URL ?>/admin/backups/delete"
                              onsubmit="return confirm('¿Eliminar este backup?');" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="filename" value="<?= htmlspecialchars($b['name']) ?>">
                            <button type="submit" class="btn btn-sm" style="background:#fee2e2;color:#991b1b;border:none;padding:0.3rem 0.6rem;border-radius:4px;cursor:pointer;font-size:0.78rem;">
                                🗑️
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Logs -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-top:1.5rem;">
    <div class="dash-section">
        <h3>📋 Log Backup Local</h3>
        <pre style="background:#1e293b;color:#e2e8f0;padding:1rem;border-radius:8px;font-size:0.78rem;overflow-x:auto;max-height:250px;margin-top:0.8rem;"><?= htmlspecialchars($backupLog ?: 'Sin registros') ?></pre>
    </div>
    <div class="dash-section">
        <h3>☁️ Log Google Drive</h3>
        <pre style="background:#1e293b;color:#e2e8f0;padding:1rem;border-radius:8px;font-size:0.78rem;overflow-x:auto;max-height:250px;margin-top:0.8rem;"><?= htmlspecialchars($gdriveLog ?: 'Sin registros') ?></pre>
    </div>
</div>

<!-- Info de configuración -->
<div class="dash-section" style="margin-top:1.5rem;">
    <h3>⚙️ Configuración</h3>
    <table class="admin-table" style="margin-top:0.8rem;">
        <tbody>
            <tr><td><strong>Backup local</strong></td><td>Diario a las 02:00 AM (cron)</td></tr>
            <tr><td><strong>Subida a Google Drive</strong></td><td>Diario a las 02:15 AM (cron)</td></tr>
            <tr><td><strong>Retención local</strong></td><td>30 días</td></tr>
            <tr><td><strong>Retención Drive</strong></td><td>30 archivos máximo</td></tr>
            <tr><td><strong>Prefijo archivos</strong></td><td><code>vpo_</code></td></tr>
            <tr><td><strong>Carpeta Drive</strong></td><td>visitapuertoctay_backups</td></tr>
        </tbody>
    </table>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns:1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
