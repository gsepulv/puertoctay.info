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
    </div>
</section>
