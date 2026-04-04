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

        // Hero config
        $heroModel = new HeroConfig($this->db);
        $heroData = $heroModel->getActive();

        // SEO from hero config
        $pageTitle = !empty($heroData['meta_title']) ? $heroData['meta_title'] : SITE_NAME . ' — ' . SITE_TAGLINE;
        $pageDescription = !empty($heroData['meta_description']) ? $heroData['meta_description'] : 'Guía de turismo y comercio de Puerto Octay, a orillas del Lago Llanquihue. Encuentra negocios, atractivos, eventos y noticias.';
        $ogTitle = !empty($heroData['og_title']) ? $heroData['og_title'] : $pageTitle;
        $ogDescription = !empty($heroData['og_description']) ? $heroData['og_description'] : $pageDescription;
        $pageImage = !empty($heroData['og_image']) ? SITE_URL . '/uploads/' . $heroData['og_image'] : (!empty($heroData['imagen']) ? SITE_URL . '/uploads/' . $heroData['imagen'] : null);
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
