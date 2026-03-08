<?php

class BuscarController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $q = Sanitizer::clean($_GET['q'] ?? '');
        $tipo = isset($_GET['tipo']) ? Sanitizer::clean($_GET['tipo']) : null;
        $categoriaId = isset($_GET['categoria']) ? (int) $_GET['categoria'] : null;

        $negocios = [];
        if ($q !== '') {
            $negocioModel = new Negocio($this->db);
            $negocios = $negocioModel->buscar($q, $tipo, $categoriaId);
        }

        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findDirectorio();

        $pageTitle = ($q !== '' ? "Resultados para \"{$q}\"" : 'Buscar') . ' — ' . SITE_NAME;
        $pageDescription = 'Busca negocios, comercios y atractivos en Puerto Octay.';
        $viewName = 'public/buscar';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
