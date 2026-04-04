<div class="container">
    <nav class="breadcrumb">
        <a href="<?= SITE_URL ?>">Inicio</a>
        <span class="sep">/</span>
        <span><?= htmlspecialchars($pagina['titulo']) ?></span>
    </nav>
</div>

<section class="section">
    <div class="container-narrow">
        <h1><?= htmlspecialchars($pagina['titulo']) ?></h1>
        <div>
            <?= $pagina['contenido'] ?>
        </div>
        <?php if (($pagina['slug'] ?? '') === 'acerca-de'): ?>
        <?php $fuentes = [
            ['nombre' => 'Municipalidad de Puerto Octay', 'url' => 'https://munipuertoctay.cl'],
            ['nombre' => 'Wikipedia', 'url' => 'https://es.wikipedia.org/wiki/Puerto_Octay'],
            ['nombre' => 'Chile es Tuyo', 'url' => 'https://chileestuyo.cl'],
        ]; ?>
        <?php require ROOT_PATH . '/views/partials/fuentes.php'; ?>
        <?php endif; ?>
    </div>
</section>
