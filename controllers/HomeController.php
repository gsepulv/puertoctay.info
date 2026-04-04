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

        // Si no hay negocios con plan destacado, mostrar los más recientes
        if (empty($destacados)) {
            $destacados = $negocioModel->findActivos(6);
        }

        // Últimas noticias para el home
        $noticiaModel = new Noticia($this->db);
        $ultimasNoticias = $noticiaModel->findPublicadas(3);

        // Temporada activa
        $tempModel = new Temporada($this->db);
        $temporadaActual = $tempModel->findActual();
        $negociosTemporada = [];
        if ($temporadaActual) {
            $negociosTemporada = $tempModel->findNegociosByTemporada((int) $temporadaActual['id'], 6);
        }

        $pageTitle = SITE_NAME . ' — ' . SITE_TAGLINE;
// Hero slide        $heroModel = new HeroSlide($this->db);        $heroSlide = $heroModel->findActivo();
        $pageDescription = 'Guía de turismo y comercio de Puerto Octay, a orillas del Lago Llanquihue. Encuentra negocios, atractivos, eventos y noticias.';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
