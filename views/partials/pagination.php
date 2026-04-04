<?php if (!empty($pagination) && $pagination['totalPages'] > 1): ?>
<nav aria-label="Paginación" style="display:flex;justify-content:center;align-items:center;gap:0.5rem;margin-top:2rem;">
    <?php if ($pagination['page'] > 1): ?>
        <a href="<?= $pagination['baseUrl'] ?>?page=<?= $pagination['page'] - 1 ?>" class="btn btn-sm btn-outline">&larr; Anterior</a>
    <?php endif; ?>
    <span style="font-size:0.9rem;color:var(--text-light);padding:0 0.5rem;">
        Página <?= $pagination['page'] ?> de <?= $pagination['totalPages'] ?>
    </span>
    <?php if ($pagination['page'] < $pagination['totalPages']): ?>
        <a href="<?= $pagination['baseUrl'] ?>?page=<?= $pagination['page'] + 1 ?>" class="btn btn-sm btn-outline">Siguiente &rarr;</a>
    <?php endif; ?>
</nav>
<?php endif; ?>
