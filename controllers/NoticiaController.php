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

        $perPage = 12;
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $total = $noticiaModel->countPublicadas();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);

        $destacadas = $noticiaModel->findDestacadas(1);
        $destacada = ($page === 1) ? ($destacadas[0] ?? null) : null;

        $noticias = $noticiaModel->findPublicadas($perPage, ($page - 1) * $perPage);
        if ($destacada) {
            $noticias = array_filter($noticias, fn($n) => (int)$n['id'] !== (int)$destacada['id']);
            $noticias = array_values($noticias);
        }

        $ultimas = $noticiaModel->findUltimas(5);
        $categoriasEditoriales = $noticiaModel->conteoCategoriasEditoriales();

        $pageTitle = 'Noticias — ' . SITE_NAME;
        $pageDescription = 'Noticias de turismo, comercio, cultura y comunidad de Puerto Octay.';
        $viewName = 'public/noticias/index';
        $pagination = ['page' => $page, 'totalPages' => $totalPages, 'baseUrl' => SITE_URL . '/noticias'];
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
public function showRedirect(string $slug): void    {        header('Location: ' . SITE_URL . '/noticias/' . $slug, true, 301);        exit;    }
}
