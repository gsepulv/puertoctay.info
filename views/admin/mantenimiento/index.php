<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">🐘</div>
        <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($phpVersion) ?></div>
        <div class="stat-label">PHP</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🐬</div>
        <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($mysqlVersion) ?></div>
        <div class="stat-label">MySQL</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💾</div>
        <div class="stat-value"><?= $dbSize ?> MB</div>
        <div class="stat-label">Base de datos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📁</div>
        <div class="stat-value" style="font-size:1.2rem;"><?= htmlspecialchars($totalDisk) ?></div>
        <div class="stat-label">Disco total cuenta</div>
    </div>
</div>

<!-- Disk usage -->
<div class="dash-section">
    <h3>Uso de disco</h3>
    <table class="admin-table">
        <tbody>
            <tr><td><strong>Repositorio</strong> <small>(/puertoctay_repo)</small></td><td><?= htmlspecialchars($repoSize) ?></td></tr>
            <tr><td><strong>Public HTML</strong> <small>(/public_html)</small></td><td><?= htmlspecialchars($publicSize) ?></td></tr>
            <tr><td><strong>Total cuenta</strong></td><td><?= htmlspecialchars($totalDisk) ?></td></tr>
        </tbody>
    </table>
</div>

<!-- Backups -->
<div class="dash-section">
    <h3>Backups</h3>
    <p><strong>Ultimo backup:</strong> <?= htmlspecialchars($lastBackup) ?></p>
</div>

<!-- Tabla de tamanos -->
<div class="dash-section">
    <h3>Tamano de tablas</h3>
    <table class="admin-table">
        <thead>
            <tr><th>Tabla</th><th>Filas</th><th>Tamano (KB)</th></tr>
        </thead>
        <tbody>
            <?php foreach ($tableSizes as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['table_name'] ?? $t['TABLE_NAME'] ?? '') ?></td>
                <td><?= number_format($t['table_rows'] ?? $t['TABLE_ROWS'] ?? 0) ?></td>
                <td><?= $t['size_kb'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Server info -->
<div class="dash-section">
    <h3>Servidor</h3>
    <table class="admin-table">
        <tbody>
            <tr><td><strong>Software</strong></td><td><?= htmlspecialchars($serverSoftware) ?></td></tr>
            <tr><td><strong>Sistema Operativo</strong></td><td><?= htmlspecialchars($serverOS) ?></td></tr>
        </tbody>
    </table>
</div>

<!-- PHP Config -->
<div class="dash-section">
    <h3>Configuracion PHP</h3>
    <table class="admin-table">
        <tbody>
            <?php foreach ($phpInfo as $key => $val): ?>
            <tr>
                <td><strong><?= htmlspecialchars($key) ?></strong></td>
                <td><?= htmlspecialchars($val) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- PHP Extensions -->
<div class="dash-section">
    <h3>Extensiones PHP (<?= count($extensions) ?>)</h3>
    <div style="display:flex; flex-wrap:wrap; gap:0.3rem;">
        <?php foreach ($extensions as $ext): ?>
            <span class="badge badge-blue"><?= htmlspecialchars($ext) ?></span>
        <?php endforeach; ?>
    </div>
</div>
