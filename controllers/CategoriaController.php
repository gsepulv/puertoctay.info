<?php

class CategoriaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findDirectorioConConteo();

        $pageTitle = 'Categorías — ' . SITE_NAME;
        $pageDescription = 'Explora todas las categorías de negocios y servicios en Puerto Octay.';
        $viewName = 'public/categorias/index';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function show(string $slug): void
    {
        $categoriaModel = new Categoria($this->db);
        $categoria = $categoriaModel->findBySlug($slug);

        if (!$categoria) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $negocioModel = new Negocio($this->db);
        $negocios = $negocioModel->findByCategoria((int) $categoria['id']);
        $totalNegocios = $negocioModel->countByCategoria((int) $categoria['id']);

        $pageTitle = htmlspecialchars($categoria['nombre']) . ' — ' . SITE_NAME;
        $pageDescription = $categoria['descripcion'] ?? "Negocios en la categoría {$categoria['nombre']} en Puerto Octay.";
        $viewName = 'public/categorias/show';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
