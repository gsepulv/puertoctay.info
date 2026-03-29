<?php /** @var string $q @var string $tipo @var int|null $categoriaId @var array $negocios @var array $categorias */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span>Buscar</span>
</nav>

<div class="container">
    <div class="section">
        <h1 style="margin-bottom:1.5rem;">Buscar</h1>

        <form action="/buscar" method="GET" style="display:grid;grid-template-columns:1fr auto auto auto;gap:.75rem;align-items:end;margin-bottom:2.5rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="q">Buscar</label>
                <input type="text" id="q" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Nombre, dirección, descripción..." class="form-control">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label for="tipo">Tipo</label>
                <select id="tipo" name="tipo" class="form-control">
                    <option value="">Todos los tipos</option>
                    <option value="comercio" <?= ($tipo ?? '') === 'comercio' ? 'selected' : '' ?>>Comercio</option>
                    <option value="atractivo" <?= ($tipo ?? '') === 'atractivo' ? 'selected' : '' ?>>Atractivo</option>
                    <option value="servicio" <?= ($tipo ?? '') === 'servicio' ? 'selected' : '' ?>>Servicio</option>
                    <option value="gastronomia" <?= ($tipo ?? '') === 'gastronomia' ? 'selected' : '' ?>>Gastronomía</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" class="form-control">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= intval($cat['id']) ?>" <?= ($categoriaId ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn">Buscar</button>
        </form>

        <?php if (!empty($q) || !empty($tipo) || !empty($categoriaId)): ?>
            <p style="color:var(--text-muted);margin-bottom:1.5rem;">
                <?= count($negocios) ?> <?= count($negocios) === 1 ? 'resultado' : 'resultados' ?>
                <?php if (!empty($q)): ?> para "<strong><?= htmlspecialchars($q) ?></strong>"<?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($negocios)): ?>
            <div class="card-grid">
                <?php foreach ($negocios as $neg): ?>
                    <a href="/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card" style="text-decoration:none;color:inherit;">
                        <?php if (!empty($neg['foto_principal'])): ?>
                            <img src="<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" style="width:100%;height:200px;object-fit:cover;">
                        <?php else: ?>
                            <div style="width:100%;height:200px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;">
                                
                            </div>
                        <?php endif; ?>
                        <div style="padding:1.25rem;">
                            <h3 style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['nombre']) ?></h3>
                            <?php if (!empty($neg['categoria_nombre'])): ?>
                                <span class="badge" style="margin-bottom:.5rem;"><?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($neg['verificado'])): ?>
                                <span class="badge badge-success">&#10003;</span>
                            <?php endif; ?>
                            <?php if (!empty($neg['direccion'])): ?>
                                <p style="color:var(--text-muted);font-size:.9rem;margin-top:.5rem;">&#128205; <?= htmlspecialchars($neg['direccion']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($neg['descripcion_corta'])): ?>
                                <p style="color:var(--text-secondary);font-size:.9rem;margin-top:.5rem;line-height:1.5;">
                                    <?= htmlspecialchars(mb_strimwidth($neg['descripcion_corta'], 0, 120, '...')) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($q) || !empty($tipo) || !empty($categoriaId)): ?>
            <div class="empty-state">
                <span style="font-size:3rem;display:block;margin-bottom:1rem;">&#128270;</span>
                <p>No se encontraron resultados.</p>
                <p style="color:var(--text-muted);margin-top:.5rem;">Intenta con otros términos de búsqueda.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
