<?php

class NegocioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $negocioModel = new Negocio($this->db);
        $negocios = $negocioModel->findActivos();

        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findDirectorio();

        $pageTitle = 'Directorio — ' . SITE_NAME;
        $pageDescription = 'Directorio completo de negocios, comercios y servicios en Puerto Octay.';
        $viewName = 'public/negocios/index';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function show(string $slug): void
    {
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->findBySlug($slug);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $negocioModel->incrementarVisitas((int) $negocio['id']);

        // Reseñas aprobadas
        $stmt = $this->db->prepare(
            "SELECT * FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada' ORDER BY created_at DESC LIMIT 10"
        );
        $stmt->execute(['nid' => $negocio['id']]);
        $resenas = $stmt->fetchAll();

        // Promedio de puntuación
        $stmtAvg = $this->db->prepare(
            "SELECT AVG(puntuacion) AS promedio, COUNT(*) AS total FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada'"
        );
        $stmtAvg->execute(['nid' => $negocio['id']]);
        $rating = $stmtAvg->fetch();

        $pageTitle = htmlspecialchars($negocio['nombre']) . ' — ' . SITE_NAME;
        $pageDescription = $negocio['descripcion_corta'] ?? "Información de {$negocio['nombre']} en Puerto Octay.";
        $usarLeaflet = !empty($negocio['lat']) && !empty($negocio['lng']);
        $viewName = 'public/negocios/show';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function turismo(): void
    {
        $negocioModel = new Negocio($this->db);
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.activo = 1 AND n.tipo = 'atractivo'
                ORDER BY n.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $negocios = $stmt->fetchAll();

        $pageTitle = 'Turismo — ' . SITE_NAME;
        $pageDescription = 'Atractivos turísticos de Puerto Octay, a orillas del Lago Llanquihue.';
        $viewName = 'public/negocios/index';
        $esTurismo = true;
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
