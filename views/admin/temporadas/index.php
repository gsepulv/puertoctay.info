<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <h1>Temporadas Turísticas</h1>
    <a href="<?= SITE_URL ?>/admin/temporadas/crear" class="btn btn-primary">+ Crear temporada</a>
</div>

<?php if (empty($temporadas)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--text-light);">No hay temporadas registradas.</p>
    </div>
<?php else: ?>
    <div class="card" style="padding: 0; overflow: hidden;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 50px;">Orden</th>
                    <th>Temporada</th>
                    <th>Fechas</th>
                    <th>Estado</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($temporadas as $temp): ?>
                <tr>
                    <td style="text-align: center; color: var(--text-light);"><?= (int) $temp['orden'] ?></td>
                    <td>
                        <span style="font-size: 1.3rem; margin-right: 0.3rem;"><?= $temp['emoji'] ?></span>
                        <strong><?= htmlspecialchars($temp['nombre']) ?></strong>
                        <?php if (!empty($temp['descripcion'])): ?>
                            <br><small class="text-light"><?= htmlspecialchars(mb_substr($temp['descripcion'], 0, 80)) ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="font-size: 0.85rem;">
                        <?php if ($temp['fecha_inicio'] && $temp['fecha_fin']): ?>
                            <?= date('d/m', strtotime($temp['fecha_inicio'])) ?> — <?= date('d/m/Y', strtotime($temp['fecha_fin'])) ?>
                            <?php
                            $hoy = date('Y-m-d');
                            if ($hoy >= $temp['fecha_inicio'] && $hoy <= $temp['fecha_fin']):
                            ?>
                                <span style="display: inline-block; margin-left: 0.3rem; padding: 0.1rem 0.4rem; background: #D1FAE5; color: #065F46; border-radius: 50px; font-size: 0.7rem; font-weight: 600;">ACTIVA AHORA</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-light">Sin fechas</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($temp['activa']): ?>
                            <span style="padding: 0.15rem 0.5rem; background: #D1FAE5; color: #065F46; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">Activa</span>
                        <?php else: ?>
                            <span style="padding: 0.15rem 0.5rem; background: #FEE2E2; color: #991B1B; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">Inactiva</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= SITE_URL ?>/admin/temporadas/<?= $temp['id'] ?>/editar" class="btn btn-outline btn-sm">Editar</a>
                            <form method="POST" action="<?= SITE_URL ?>/admin/temporadas/<?= $temp['id'] ?>/eliminar" onsubmit="return confirm('¿Eliminar esta temporada?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm" style="background: #FEE2E2; color: #991B1B; border: none;">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
