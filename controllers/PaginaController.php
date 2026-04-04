<?php

class PaginaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function show(string $slug): void
    {
        $model = new Pagina($this->db);
        $pagina = $model->findBySlug($slug);

        if (!$pagina || !$pagina['activo']) {
            http_response_code(404);
            $pageTitle = 'Página no encontrada';
            $viewName = 'errors/404';
            require ROOT_PATH . '/views/layouts/main.php';
            return;
        }

        $pageTitle = $pagina['meta_title'] ?: $pagina['titulo'] . ' — ' . SITE_NAME;
        $pageDescription = $pagina['meta_description'] ?: mb_substr(strip_tags($pagina['contenido'] ?? ''), 0, 160);
        $viewName = 'public/pagina';
        require ROOT_PATH . '/views/layouts/main.php';
    }

public function showAmigas(): void    {        $pageTitle = "Páginas Amigas y Bibliografía — " . SITE_NAME;        $pageDescription = "Sitios web que recomendamos sobre Puerto Octay y la Region de Los Lagos.";        $viewName = "public/paginas-amigas";        require ROOT_PATH . "/views/layouts/main.php";    }
    public function showLegal(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
        $slug = ltrim($uri, '/');
        $this->show($slug);
    }
}
