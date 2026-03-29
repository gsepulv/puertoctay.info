<!-- Resumen general -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">👁</div>
        <div class="stat-value"><?= number_format($totalVisitas) ?></div>
        <div class="stat-label">Visitas totales (negocios)</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🏪</div>
        <div class="stat-value"><?= $counts['negocios'] ?></div>
        <div class="stat-label">Negocios</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📰</div>
        <div class="stat-value"><?= $counts['noticias'] ?></div>
        <div class="stat-label">Noticias</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💾</div>
        <div class="stat-value"><?= $dbSize ?> MB</div>
        <div class="stat-label">Base de datos</div>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 1.5rem;">
    <div class="stat-card">
        <div class="stat-icon">📂</div>
        <div class="stat-value"><?= $counts['categorias'] ?></div>
        <div class="stat-label">Categorias</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value"><?= $counts['eventos'] ?></div>
        <div class="stat-label">Eventos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value"><?= $counts['resenas'] ?></div>
        <div class="stat-label">Resenas</div>
    </div>
</div>

<!-- Top 10 negocios -->
<div class="dash-section">
    <h3>Top 10 negocios mas visitados</h3>
    <?php if (empty($topNegocios)): ?>
        <p style="color:#888;">No hay datos de visitas aun.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Negocio</th>
                    <th>Categoria</th>
                    <th>Visitas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topNegocios as $i => $neg): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" target="_blank">
                            <?= htmlspecialchars($neg['nombre']) ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($neg['categoria'] ?? 'Sin categoria') ?></td>
                    <td><strong><?= number_format($neg['visitas']) ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Contenido por mes -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="dash-section">
        <h3>Negocios creados por mes</h3>
        <?php if (empty($negociosPorMes)): ?>
            <p style="color:#888;">Sin datos.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Mes</th><th>Cantidad</th></tr></thead>
                <tbody>
                    <?php foreach ($negociosPorMes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['mes']) ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <div class="dash-section">
        <h3>Noticias creadas por mes</h3>
        <?php if (empty($noticiasPorMes)): ?>
            <p style="color:#888;">Sin datos.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead><tr><th>Mes</th><th>Cantidad</th></tr></thead>
                <tbody>
                    <?php foreach ($noticiasPorMes as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['mes']) ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Audit log -->
<div class="dash-section">
    <h3>Ultimas 20 acciones (Audit Log)</h3>
    <?php if (empty($auditLog)): ?>
        <p style="color:#888;">No hay registros de auditoria.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Accion</th>
                    <th>Entidad</th>
                    <th>Detalle</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($auditLog as $log): ?>
                <tr>
                    <td style="white-space:nowrap; font-size:0.8rem;"><?= htmlspecialchars($log['created_at'] ?? '') ?></td>
                    <td><?= htmlspecialchars($log['usuario_nombre'] ?? 'Sistema') ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($log['accion']) ?></span></td>
                    <td><?= htmlspecialchars($log['entidad'] ?? '') ?> <?= $log['entidad_id'] ? '#' . $log['entidad_id'] : '' ?></td>
                    <td style="max-width:250px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars($log['detalle'] ?? '') ?></td>
                    <td style="font-size:0.8rem;"><?= htmlspecialchars($log['ip_address'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
