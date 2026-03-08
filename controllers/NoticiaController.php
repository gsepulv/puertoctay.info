<?php

class NoticiaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $noticiaModel = new Noticia($this->db);

        $destacadas = $noticiaModel->findDestacadas(1);
        $destacada = $destacadas[0] ?? null;

        // Noticias principales (excluir destacada si existe)
        $todas = $noticiaModel->findPublicadas(7);
        if ($destacada) {
            $noticias = array_filter($todas, fn($n) => (int)$n['id'] !== (int)$destacada['id']);
            $noticias = array_slice(array_values($noticias), 0, 6);
        } else {
            $noticias = array_slice($todas, 0, 6);
        }

        // Sidebar
        $ultimas = $noticiaModel->findUltimas(5);
        $categoriasEditoriales = $noticiaModel->conteoCategoriasEditoriales();

        $pageTitle = 'Noticias — ' . SITE_NAME;
        $pageDescription = 'Noticias de turismo, comercio, cultura y comunidad de Puerto Octay.';
        $viewName = 'public/noticias/index';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function show(string $slug): void
    {
        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->findBySlug($slug);

        if (!$noticia) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $noticiaModel->incrementarVisitas((int) $noticia['id']);

        $noticia['tiempo_lectura'] = $noticia['tiempo_lectura']
            ?: Noticia::calcularTiempoLectura($noticia['contenido']);

        $relacionadas = $noticiaModel->findRelacionadas(
            (int) $noticia['id'],
            $noticia['categoria_id'] ? (int) $noticia['categoria_id'] : null
        );

        $pageTitle = htmlspecialchars($noticia['titulo']) . ' — ' . SITE_NAME;
        $pageDescription = $noticia['bajada'] ?? mb_substr(strip_tags($noticia['contenido'] ?? ''), 0, 160);
        $viewName = 'public/noticias/show';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function porCategoria(string $slug): void
    {
        $categoriaModel = new Categoria($this->db);
        $categoria = $categoriaModel->findBySlug($slug);

        if (!$categoria) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $noticiaModel = new Noticia($this->db);
        $noticias = $noticiaModel->findPorCategoria((int) $categoria['id']);
        $ultimas = $noticiaModel->findUltimas(5);
        $categoriasEditoriales = $noticiaModel->conteoCategoriasEditoriales();

        $categoriaActual = $categoria;
        $destacada = null;

        $pageTitle = htmlspecialchars($categoria['nombre']) . ' — Noticias — ' . SITE_NAME;
        $pageDescription = "Noticias sobre {$categoria['nombre']} en Puerto Octay.";
        $viewName = 'public/noticias/index';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
