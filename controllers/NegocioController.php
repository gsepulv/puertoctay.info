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

        // Negocios similares (misma categoría, excluir actual)
        $similares = [];
        if (!empty($negocio['categoria_id'])) {
            $stmtSim = $this->db->prepare(
                "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
                 FROM negocios n
                 LEFT JOIN categorias c ON c.id = n.categoria_id
                 WHERE n.activo = 1 AND n.categoria_id = :cid AND n.id != :nid
                 ORDER BY RAND() LIMIT 3"
            );
            $stmtSim->execute(['cid' => $negocio['categoria_id'], 'nid' => $negocio['id']]);
            $similares = $stmtSim->fetchAll();
        }

        // Galería (decodificar JSON)
        $galeria = [];
        if (!empty($negocio['galeria'])) {
            $decoded = json_decode($negocio['galeria'], true);
            if (is_array($decoded)) $galeria = $decoded;
        }

        // Inject rating data into negocio for Schema.org
        $negocio['_rating_avg'] = $rating;
        $negocio['_rating_count'] = (int) ($ratingData['total'] ?? 0);

        // Campos especificos del tipo/subtipo
        $camposEspecificos = json_decode($negocio['campos_especificos'] ?? '{}', true) ?: [];

        // Sector
        $sectorNombre = '';
        if (!empty($negocio['sector_id'])) {
            $stmtSec = $this->db->prepare('SELECT nombre FROM sectores WHERE id = ?');
            $stmtSec->execute([$negocio['sector_id']]);
            $secRow = $stmtSec->fetch();
            $sectorNombre = $secRow['nombre'] ?? '';
        }

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

        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->findBySlug($slug);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        // Honeypot
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/negocio/' . $slug);
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $isLoggedIn = !empty($_SESSION['usuario_id']);

        // Datos del autor
        if ($isLoggedIn) {
            $nombreAutor = $_SESSION['usuario_nombre'] ?? 'Anónimo';
            $emailAutor = '';
            $userId = (int) $_SESSION['usuario_id'];
        } else {
            $nombreAutor = trim($data['visitante_nombre'] ?? '');
            $emailAutor = trim($data['visitante_email'] ?? '');
            $userId = null;
        }

        $puntuacion = max(1, min(5, (int) ($data['puntuacion'] ?? 0)));
        $comentario = trim($data['comentario'] ?? '');
        $origen = trim($data['visitante_origen'] ?? '');
        $aceptaPublicacion = !empty($_POST['acepta_publicacion']);

        // Validaciones
        $errores = [];
        if (mb_strlen($nombreAutor) < 2) $errores[] = 'El nombre es requerido (mín. 2 caracteres).';
        if (!$isLoggedIn && !filter_var($emailAutor, FILTER_VALIDATE_EMAIL)) $errores[] = 'Email inválido.';
        if ($puntuacion < 1 || $puntuacion > 5) $errores[] = 'Selecciona una calificación.';
        if (mb_strlen($comentario) < 20) $errores[] = 'El comentario debe tener al menos 20 caracteres.';
        if (!$isLoggedIn && !$aceptaPublicacion) $errores[] = 'Debes aceptar la publicación de tu reseña.';

        // Rate limiting: max 2 por IP en 24h al mismo negocio
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmtRate = $this->db->prepare(
            "SELECT COUNT(*) FROM resenas WHERE ip_address = :ip AND negocio_id = :nid AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        );
        $stmtRate->execute(['ip' => $ip, 'nid' => $negocio['id']]);
        if ((int) $stmtRate->fetchColumn() >= 2) {
            $errores[] = 'Ya enviaste reseñas recientemente. Intenta mañana.';
        }

        // One review per logged-in user per business
        if ($isLoggedIn) {
            $stmtCheck = $this->db->prepare("SELECT id FROM resenas WHERE negocio_id = :nid AND usuario_id = :uid LIMIT 1");
            $stmtCheck->execute(['nid' => $negocio['id'], 'uid' => $userId]);
            if ($stmtCheck->fetch()) {
                $errores[] = 'Ya dejaste una reseña en este negocio.';
            }
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode(' ', $errores);
            header('Location: ' . SITE_URL . '/negocio/' . $slug . '#dejar-resena');
            exit;
        }

        // Guardar
        if ($isLoggedIn) {
            $stmtIns = $this->db->prepare(
                "INSERT INTO resenas (negocio_id, usuario_id, nombre_autor, email_autor, puntuacion, comentario, estado, ip_address, user_agent)
                 VALUES (:nid, :uid, :nombre, :email, :punt, :com, 'pendiente', :ip, :ua)"
            );
            $stmtIns->execute([
                'nid'    => $negocio['id'],
                'uid'    => $userId,
                'nombre' => $nombreAutor,
                'email'  => $emailAutor,
                'punt'   => $puntuacion,
                'com'    => $comentario,
                'ip'     => $ip,
                'ua'     => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]);
        } else {
            $resenaModel = new Resena($this->db);
            $resenaModel->crearDeVisitante([
                'negocio_id'       => $negocio['id'],
                'nombre_autor'     => $nombreAutor,
                'email_autor'      => $emailAutor,
                'puntuacion'       => $puntuacion,
                'comentario'       => $comentario,
                'visitante_origen' => $origen ?: null,
                'ip_address'       => $ip,
                'user_agent'       => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            ]);
        }

        AuditLog::log('crear', 'resenas', null, "Reseña en {$negocio['nombre']} por {$nombreAutor}");

        $_SESSION['flash_success'] = '¡Gracias por tu reseña! Será publicada tras revisión por nuestro equipo.';
        header('Location: ' . SITE_URL . '/negocio/' . $slug . '#dejar-resena');
        exit;
    }

}
