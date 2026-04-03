<?php if (($counts['negocios_pendientes'] ?? 0) > 0): ?>
<div style="background: #FEF3C7; border: 2px solid #F59E0B; border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <strong style="color: #92400E; font-size: 1.05rem;">📋 <?= $counts['negocios_pendientes'] ?> registro<?= $counts['negocios_pendientes'] > 1 ? 's' : '' ?> pendiente<?= $counts['negocios_pendientes'] > 1 ? 's' : '' ?> de aprobación</strong>
        <p style="color: #B45309; font-size: 0.85rem; margin: 0.25rem 0 0;">Comercios nuevos esperando revisión.</p>
    </div>
    <a href="<?= SITE_URL ?>/admin/negocios?status=pendiente" style="background: #F59E0B; color: #fff; padding: 0.6rem 1.2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem;">Revisar ahora</a>
</div>
<?php endif; ?>

<!-- Fila 1: Principales -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">🏪</div>
        <div class="stat-value"><?= $counts['negocios_activos'] ?></div>
        <div class="stat-label">Negocios activos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📂</div>
        <div class="stat-value"><?= $counts['categorias'] ?></div>
        <div class="stat-label">Categorías</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📰</div>
        <div class="stat-value"><?= $counts['noticias_publicadas'] ?></div>
        <div class="stat-label">Noticias publicadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-value"><?= $counts['resenas_pendientes'] ?></div>
        <div class="stat-label">Reseñas pendientes</div>
    </div>
</div>

<!-- Fila 2: Visitas y eventos -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon">👁</div>
        <div class="stat-value"><?= number_format($counts['visitas_hoy']) ?></div>
        <div class="stat-label">Visitas totales</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📅</div>
        <div class="stat-value"><?= $counts['eventos_proximos'] ?></div>
        <div class="stat-label">Eventos próximos</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?= $counts['propietarios'] ?></div>
        <div class="stat-label">Propietarios registrados</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✓</div>
        <div class="stat-value"><?= $counts['negocios_verificados'] ?></div>
        <div class="stat-label">Negocios verificados</div>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="dash-section">
    <h3>Acciones rápidas</h3>
    <div class="quick-actions">
        <a href="<?= SITE_URL ?>/admin/negocios/crear" class="btn btn-primary">+ Agregar negocio</a>
        <a href="<?= SITE_URL ?>/admin/noticias/crear" class="btn btn-secondary">+ Crear noticia</a>
        <a href="<?= SITE_URL ?>/admin/eventos/crear" class="btn btn-warning">+ Crear evento</a>
        <a href="<?= SITE_URL ?>/admin/resenas" class="btn btn-sm" style="background:#e9ecef;color:#333;">Ver reseñas pendientes (<?= $counts['resenas_pendientes'] ?>)</a>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Últimos negocios -->
    <div class="dash-section">
        <h3>Últimos negocios agregados</h3>
        <?php if (empty($ultimosNegocios)): ?>
            <p style="color:#888; font-size:0.9rem;">Sin negocios registrados.</p>
        <?php else: ?>
        <table class="admin-table" style="box-shadow:none;">
            <thead><tr><th>Nombre</th><th>Tipo</th><th>Categoría</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($ultimosNegocios as $n): ?>
                <tr>
                    <td><a href="<?= SITE_URL ?>/admin/negocios/<?= $n['id'] ?>/editar"><?= htmlspecialchars($n['nombre']) ?></a></td>
                    <td><?= htmlspecialchars($n['tipo']) ?></td>
                    <td><?= htmlspecialchars($n['categoria_nombre'] ?? '—') ?></td>
                    <td><span class="badge <?= $n['activo'] ? 'badge-green' : 'badge-red' ?>"><?= $n['activo'] ? 'Activo' : 'Inactivo' ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Últimas noticias -->
    <div class="dash-section">
        <h3>Últimas noticias</h3>
        <?php if (empty($ultimasNoticias)): ?>
            <p style="color:#888; font-size:0.9rem;">Sin noticias registradas.</p>
        <?php else: ?>
        <table class="admin-table" style="box-shadow:none;">
            <thead><tr><th>Título</th><th>Categoría</th><th>Estado</th><th>Fecha</th></tr></thead>
            <tbody>
                <?php foreach ($ultimasNoticias as $n): ?>
                <tr>
                    <td><a href="<?= SITE_URL ?>/admin/noticias/<?= $n['id'] ?>/editar"><?= htmlspecialchars(mb_substr($n['titulo'], 0, 35)) ?></a></td>
                    <td><?= htmlspecialchars($n['categoria_nombre'] ?? '—') ?></td>
                    <td>
                        <?php
                        $bc = match($n['estado']) { 'publicado'=>'badge-green', 'borrador'=>'badge-red', 'revision'=>'badge-yellow', default=>'badge-blue' };
                        ?>
                        <span class="badge <?= $bc ?>"><?= ucfirst($n['estado']) ?></span>
                    </td>
                    <td style="font-size:0.8rem;"><?= date('d/m/Y', strtotime($n['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
    <!-- Reseñas pendientes -->
    <div class="dash-section">
        <h3>Reseñas pendientes de moderación</h3>
        <?php if (empty($resenasPendientes)): ?>
            <p style="color:#888; font-size:0.9rem;">No hay reseñas pendientes.</p>
        <?php else: ?>
        <table class="admin-table" style="box-shadow:none;">
            <thead><tr><th>Negocio</th><th>Autor</th><th>Punt.</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($resenasPendientes as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['negocio_nombre'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($r['nombre_autor']) ?></td>
                    <td style="color:#f39c12;"><?= str_repeat('★', (int)$r['puntuacion']) ?></td>
                    <td>
                        <form action="<?= SITE_URL ?>/admin/resenas/<?= $r['id'] ?>/aprobar" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-secondary">✓</button>
                        </form>
                        <form action="<?= SITE_URL ?>/admin/resenas/<?= $r['id'] ?>/rechazar" method="POST" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-danger">✗</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Próximos eventos -->
    <div class="dash-section">
        <h3>Próximos eventos</h3>
        <?php if (empty($proximosEventos)): ?>
            <p style="color:#888; font-size:0.9rem;">No hay eventos próximos.</p>
        <?php else: ?>
        <table class="admin-table" style="box-shadow:none;">
            <thead><tr><th>Nombre</th><th>Fecha</th><th>Lugar</th><th>Estado</th></tr></thead>
            <tbody>
                <?php foreach ($proximosEventos as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nombre']) ?></td>
                    <td style="font-size:0.8rem;"><?= date('d/m/Y', strtotime($e['fecha_inicio'])) ?></td>
                    <td><?= htmlspecialchars($e['lugar'] ?? '—') ?></td>
                    <td><span class="badge badge-green"><?= ucfirst($e['estado']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
}
</style>
