<?php

class PanelComercianteController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        RolMiddleware::check('comerciante');
    }

    private function getMiNegocio(): ?array
    {
        $userId = (int) $_SESSION['usuario_id'];
        $stmt = $this->db->prepare(
            "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
             FROM negocios n
             LEFT JOIN categorias c ON c.id = n.categoria_id
             WHERE n.propietario_id = :uid
             LIMIT 1"
        );
        $stmt->execute(['uid' => $userId]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function dashboard(): void
    {
        $negocio = $this->getMiNegocio();
        $stats = [];

        if ($negocio) {
            $stats['visitas'] = (int) $negocio['visitas'];

            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada'"
            );
            $stmt->execute(['nid' => $negocio['id']]);
            $stats['resenas'] = (int) $stmt->fetchColumn();

            $stmt2 = $this->db->prepare(
                "SELECT AVG(puntuacion) FROM resenas WHERE negocio_id = :nid AND estado = 'aprobada'"
            );
            $stmt2->execute(['nid' => $negocio['id']]);
            $stats['rating'] = round((float) $stmt2->fetchColumn(), 1);
        }

        $viewName = 'comerciante/dashboard';
        require ROOT_PATH . '/views/layouts/comerciante.php';
    }

    public function editarNegocio(): void
    {
        $negocio = $this->getMiNegocio();
        if (!$negocio) {
            $_SESSION['flash_error'] = 'No tienes un negocio registrado.';
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $catModel = new Categoria($this->db);
        $categorias = $catModel->findDirectorio();

        $viewName = 'comerciante/editar-negocio';
        require ROOT_PATH . '/views/layouts/comerciante.php';
    }

    public function actualizarNegocio(): void
    {
        CsrfMiddleware::validate();

        $negocio = $this->getMiNegocio();
        if (!$negocio) {
            header('Location: ' . SITE_URL . '/mi-comercio');
            exit;
        }

        $data = Sanitizer::cleanArray($_POST);
        $errores = [];

        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 3) {
            $errores[] = 'El nombre debe tener al menos 3 caracteres.';
        }
        if (empty($data['descripcion_corta'])) {
            $errores[] = 'La descripción corta es obligatoria.';
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode('<br>', $errores);
            header('Location: ' . SITE_URL . '/mi-comercio/editar');
            exit;
        }

        $updateData = [
            'nombre'            => $data['nombre'],
            'descripcion_corta' => mb_substr($data['descripcion_corta'], 0, 300),
            'descripcion_larga' => $_POST['descripcion_larga'] ?? $negocio['descripcion_larga'],
            'direccion'         => $data['direccion'] ?? $negocio['direccion'],
            'telefono'          => $data['telefono'] ?? $negocio['telefono'],
            'whatsapp'          => $data['whatsapp'] ?? $negocio['whatsapp'],
            'email'             => $data['email'] ?? $negocio['email'],
            'sitio_web'         => $data['sitio_web'] ?? $negocio['sitio_web'],
            'horario'           => $_POST['horario'] ?? $negocio['horario'],
            'facebook'          => $data['facebook'] ?? null,
            'instagram'         => $data['instagram'] ?? null,
            'tiktok'            => $data['tiktok'] ?? null,
            'youtube'           => $data['youtube'] ?? null,
        ];

        if (!empty($data['categoria_id'])) {
            $updateData['categoria_id'] = (int) $data['categoria_id'];
        }

        // Update slug if name changed
        if ($data['nombre'] !== $negocio['nombre']) {
            $updateData['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre'], (int) $negocio['id']);
        }

        $negocioModel = new Negocio($this->db);
        $negocioModel->update((int) $negocio['id'], $updateData);

        AuditLog::log('editar', 'negocios', (int) $negocio['id'],
            "Comerciante editó: {$data['nombre']}");

        $_SESSION['flash_success'] = 'Negocio actualizado correctamente.';
        header('Location: ' . SITE_URL . '/mi-comercio/editar');
        exit;
    }

    public function perfil(): void
    {
        $usuarioModel = new Usuario($this->db);
        $usuario = $usuarioModel->find((int) $_SESSION['usuario_id']);

        $viewName = 'comerciante/perfil';
        require ROOT_PATH . '/views/layouts/comerciante.php';
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
            header('Location: ' . SITE_URL . '/mi-comercio/perfil');
            exit;
        }

        $updateData = [
            'nombre'   => $data['nombre'],
            'telefono' => $data['telefono'] ?? null,
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $usuarioModel = new Usuario($this->db);
        $usuarioModel->update((int) $_SESSION['usuario_id'], $updateData);

        $_SESSION['usuario_nombre'] = $data['nombre'];
        $_SESSION['flash_success'] = 'Perfil actualizado correctamente.';
        header('Location: ' . SITE_URL . '/mi-comercio/perfil');
        exit;
    }
}
