<?php

class HomeController
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

        $negocioModel = new Negocio($this->db);
        $destacados = $negocioModel->findDestacados(6);

        $pageTitle = SITE_NAME . ' — ' . SITE_TAGLINE;
        $pageDescription = 'Guía de turismo y comercio de Puerto Octay, a orillas del Lago Llanquihue. Encuentra negocios, atractivos, eventos y noticias.';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
