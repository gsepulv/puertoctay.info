<?php /** @var array $pagina */ ?>

<nav class="breadcrumb">
    <a href="/">Inicio</a>
    <span>/</span>
    <span><?= htmlspecialchars($pagina['titulo']) ?></span>
</nav>

<div class="container-narrow">
    <div class="section">
        <h1 style="margin-bottom:2rem;"><?= htmlspecialchars($pagina['titulo']) ?></h1>

        <div class="page-content" style="line-height:1.9;font-size:1.05rem;letter-spacing:.01em;">
            <?= $pagina['contenido'] ?>
        </div>
    </div>
</div>
