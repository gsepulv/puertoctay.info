<?php

class PanelVisitanteController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        RolMiddleware::check('visitante', 'comerciante', 'editor', 'moderador', 'admin');
    }

    public function dashboard(): void
    {
        $userId = (int) $_SESSION['usuario_id'];

        // Count favoritos
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM favoritos WHERE usuario_id = :uid");
        $stmt->execute(['uid' => $userId]);
        $totalFavoritos = (int) $stmt->fetchColumn();

        // Count reseñas
        $stmt2 = $this->db->prepare("SELECT COUNT(*) FROM resenas WHERE usuario_id = :uid");
        $stmt2->execute(['uid' => $userId]);
        $totalResenas = (int) $stmt2->fetchColumn();

        // Last 5 reseñas
        $stmt3 = $this->db->prepare(
            "SELECT r.*, n.nombre AS negocio_nombre, n.slug AS negocio_slug
             FROM resenas r
             LEFT JOIN negocios n ON n.id = r.negocio_id
             WHERE r.usuario_id = :uid
             ORDER BY r.created_at DESC LIMIT 5"
        );
        $stmt3->execute(['uid' => $userId]);
        $ultimasResenas = $stmt3->fetchAll();

        $viewName = 'visitante/dashboard';
        require ROOT_PATH . '/views/layouts/visitante.php';
    }

    public function favoritos(): void
    {
        $userId = (int) $_SESSION['usuario_id'];
        $stmt = $this->db->prepare(
            "SELECT n.id, n.nombre, n.slug, n.descripcion_corta, n.foto_principal,
                    n.tipo, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji,
                    f.created_at AS fecha_favorito
             FROM favoritos f
             INNER JOIN negocios n ON n.id = f.negocio_id
             LEFT JOIN categorias c ON c.id = n.categoria_id
             WHERE f.usuario_id = :uid
             ORDER BY f.created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        $favoritos = $stmt->fetchAll();

        $viewName = 'visitante/favoritos';
        require ROOT_PATH . '/views/layouts/visitante.php';
    }

    public function resenas(): void
    {
        $userId = (int) $_SESSION['usuario_id'];
        $stmt = $this->db->prepare(
            "SELECT r.*, n.nombre AS negocio_nombre, n.slug AS negocio_slug
             FROM resenas r
             LEFT JOIN negocios n ON n.id = r.negocio_id
             WHERE r.usuario_id = :uid
             ORDER BY r.created_at DESC"
        );
        $stmt->execute(['uid' => $userId]);
        $resenas = $stmt->fetchAll();

        $viewName = 'visitante/resenas';
        require ROOT_PATH . '/views/layouts/visitante.php';
    }

    public function perfil(): void
    {
        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->find((int) $_SESSION['usuario_id']);

        $viewName = 'visitante/perfil';
        require ROOT_PATH . '/views/layouts/visitante.php';
    }

    public function actualizarPerfil(): void
    {
        CsrfMiddleware::validate();

        $data = Sanitizer::cleanArray($_POST);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }

        if (!empty($password)) {
            if (mb_strlen($password) < 8) {
                $errores[] = 'La contraseña debe tener al menos 8 caracteres.';
            }
            if ($password !== $passwordConfirm) {
                $errores[] = 'Las contraseñas no coinciden.';
            }
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode('<br>', $errores);
            header('Location: ' . SITE_URL . '/mi-cuenta/perfil');
            exit;
        }

        $updateData = ['nombre' => $data['nombre']];
        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usuarioModel = new Usuario($this->db);
        $usuarioModel->update((int) $_SESSION['usuario_id'], $updateData);

        $_SESSION['usuario_nombre'] = $data['nombre'];
        $_SESSION['flash_success'] = 'Perfil actualizado correctamente.';
        header('Location: ' . SITE_URL . '/mi-cuenta/perfil');
        exit;
    }

    public function toggleFavorito(): void
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['usuario_id'])) {
            echo json_encode(['ok' => false, 'error' => 'No autenticado']);
            exit;
        }

        $negocioId = (int) ($_POST['negocio_id'] ?? 0);
        if ($negocioId < 1) {
            echo json_encode(['ok' => false, 'error' => 'Negocio inválido']);
            exit;
        }

        $userId = (int) $_SESSION['usuario_id'];

        // Check if already favorited
        $stmt = $this->db->prepare(
            "SELECT id FROM favoritos WHERE usuario_id = :uid AND negocio_id = :nid"
        );
        $stmt->execute(['uid' => $userId, 'nid' => $negocioId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Remove
            $this->db->prepare("DELETE FROM favoritos WHERE id = :id")->execute(['id' => $existing['id']]);
            echo json_encode(['ok' => true, 'action' => 'removed']);
        } else {
            // Add
            $this->db->prepare(
                "INSERT INTO favoritos (usuario_id, negocio_id) VALUES (:uid, :nid)"
            )->execute(['uid' => $userId, 'nid' => $negocioId]);
            echo json_encode(['ok' => true, 'action' => 'added']);
        }
        exit;
    }
}
