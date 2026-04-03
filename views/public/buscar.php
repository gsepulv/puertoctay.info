<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span>Buscar</span>
    </nav>
</div>

<section class="section">
    <div class="container">
        <h1>🔎 Buscar</h1>

        <form action="<?= SITE_URL ?>/buscar" method="GET" class="card mb-3" style="padding: 1.5rem;">
            <div class="form-row">
                <div class="form-group" style="flex: 2;">
                    <label for="q">Búsqueda</label>
                    <input type="text" id="q" name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Nombre, descripcion...">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos</option>
                        <option value="comercio" <?= ($tipo ?? '') === 'comercio' ? 'selected' : '' ?>>Comercio</option>
                        <option value="atractivo" <?= ($tipo ?? '') === 'atractivo' ? 'selected' : '' ?>>Atractivo</option>
                        <option value="servicio" <?= ($tipo ?? '') === 'servicio' ? 'selected' : '' ?>>Servicio</option>
                        <option value="gastronomia" <?= ($tipo ?? '') === 'gastronomia' ? 'selected' : '' ?>>Gastronomia</option>
                    </select>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="categoria">Categoría</label>
                    <select id="categoria" name="categoria_id">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>" <?= ((int)($categoriaId ?? 0)) === (int)$cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="align-self: flex-end;">
                    <button type="submit" class="btn btn-primary">🔎 Buscar</button>
                </div>
            </div>
        </form>

        <?php if (isset($negocios)): ?>
            <?php if (!empty($q) || !empty($tipo) || !empty($categoriaId)): ?>
            <p class="text-light mb-2"><?= count($negocios) ?> <?= count($negocios) === 1 ? 'resultado' : 'resultados' ?> encontrados<?= !empty($q) ? ' para "' . htmlspecialchars($q) . '"' : '' ?></p>
            <?php endif; ?>

            <?php if (!empty($negocios)): ?>
            <div class="card-grid">
                <?php foreach ($negocios as $neg): ?>
                <a href="<?= SITE_URL ?>/negocio/<?= htmlspecialchars($neg['slug']) ?>" class="card card-ejemplo-wrapper">
                    <?php if (empty($neg['verificado'])): ?><span class="card-ejemplo">EJEMPLO</span><?php endif; ?>
                    <?php if (!empty($neg['foto_principal'])): ?>
                    <div class="card-img">
                        <img src="<?= SITE_URL ?>/uploads/<?= htmlspecialchars($neg['foto_principal']) ?>" alt="<?= htmlspecialchars($neg['nombre']) ?>" loading="lazy">
                    </div>
                    <?php else: ?>
                    <div class="card-img card-img-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if (!empty($neg['categoria_nombre'])): ?>
                        <span class="badge badge-primary"><?= $neg['categoria_emoji'] ?? '' ?> <?= htmlspecialchars($neg['categoria_nombre']) ?></span>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($neg['nombre']) ?></h3>
                        <?php if (!empty($neg['direccion'])): ?>
                        <p class="text-sm text-light">📍 <?= htmlspecialchars($neg['direccion']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($neg['descripcion_corta'])): ?>
                        <p class="text-sm"><?= htmlspecialchars(mb_strimwidth($neg['descripcion_corta'], 0, 100, '...')) ?></p>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <p>🔎 No se encontraron resultados.</p>
                <p class="text-sm text-light">Intenta con otros terminos de busqueda o explora el directorio completo.</p>
                <a href="<?= SITE_URL ?>/directorio" class="btn btn-primary mt-1">Ver directorio</a>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
