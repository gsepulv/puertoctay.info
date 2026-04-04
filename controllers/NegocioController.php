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

        // Increment views (once per session per business)
        $viewedKey = 'viewed_negocio_' . $negocio['id'];
        if (empty($_SESSION[$viewedKey])) {
            $negocioModel->incrementarVisitas((int) $negocio['id']);
            $_SESSION[$viewedKey] = true;
        }

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
        $ratingData = $stmtAvg->fetch();
        $rating = (float) ($ratingData['promedio'] ?? 0);

        // Temporadas del negocio
        $tempModel = new Temporada($this->db);
        $negocioTemporadas = $tempModel->findForNegocio((int) $negocio['id']);

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

    public function patrimonio(): void
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.activo = 1 AND c.slug IN ('patrimonio', 'educacion-cultura')
                ORDER BY c.orden ASC, n.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $negocios = $stmt->fetchAll();

        $pageTitle = 'Patrimonio y Cultura — ' . SITE_NAME;
        $pageDescription = 'Arquitectura alemana, museos, sitios históricos y centros culturales de Puerto Octay.';
        $viewName = 'public/patrimonio';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function guardarResena(string $slug): void
    {
        CsrfMiddleware::validate();

        // Must be logged in
        if (empty($_SESSION['usuario_id'])) {
            header('Location: ' . SITE_URL . '/login');
            exit;
        }

        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->findBySlug($slug);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $userId = (int) $_SESSION['usuario_id'];

        // Check: one review per user per business
        $stmt = $this->db->prepare(
            "SELECT id FROM resenas WHERE negocio_id = :nid AND usuario_id = :uid LIMIT 1"
        );
        $stmt->execute(['nid' => $negocio['id'], 'uid' => $userId]);
        if ($stmt->fetch()) {
            $_SESSION['flash_error'] = 'Ya dejaste una reseña en este negocio.';
            header('Location: ' . SITE_URL . '/negocio/' . $slug);
            exit;
        }

        // Honeypot
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/negocio/' . $slug);
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $puntuacion = max(1, min(5, (int) ($data['puntuacion'] ?? 5)));
        $comentario = trim($data['comentario'] ?? '');

        if (empty($comentario) || mb_strlen($comentario) < 10) {
            $_SESSION['flash_error'] = 'El comentario debe tener al menos 10 caracteres.';
            header('Location: ' . SITE_URL . '/negocio/' . $slug);
            exit;
        }

        $stmtIns = $this->db->prepare(
            "INSERT INTO resenas (negocio_id, usuario_id, nombre_autor, email_autor, puntuacion, comentario, estado, ip_address)
             VALUES (:nid, :uid, :nombre, :email, :punt, :com, 'pendiente', :ip)"
        );
        $stmtIns->execute([
            'nid'    => $negocio['id'],
            'uid'    => $userId,
            'nombre' => $_SESSION['usuario_nombre'] ?? 'Anónimo',
            'email'  => '',
            'punt'   => $puntuacion,
            'com'    => $comentario,
            'ip'     => $_SERVER['REMOTE_ADDR'] ?? '',
        ]);

        AuditLog::log('crear', 'resenas', (int) $this->db->lastInsertId(),
            "Reseña en {$negocio['nombre']} por " . ($_SESSION['usuario_nombre'] ?? ''));

        $_SESSION['flash_success'] = 'Tu reseña fue enviada y será revisada por nuestro equipo.';
        header('Location: ' . SITE_URL . '/negocio/' . $slug);
        exit;
    }

}
