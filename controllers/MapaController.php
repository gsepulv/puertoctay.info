<?php

class MapaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $pageTitle = 'Mapa — ' . SITE_NAME;
        $pageDescription = 'Mapa interactivo con todos los negocios y atractivos de Puerto Octay.';
        $usarLeaflet = true;
        $viewName = 'public/mapa';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
