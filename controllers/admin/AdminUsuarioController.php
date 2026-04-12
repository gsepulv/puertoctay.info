<?php

class AdminUsuarioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $model = new Usuario($this->db);
        $usuarios = $model->findAllAdmin();

        $pageTitle = 'Usuarios — Admin';
        $viewName = 'admin/usuarios/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $usuario = [];
        $errores = [];
        $pageTitle = 'Nuevo Usuario — Admin';
        $viewName = 'admin/usuarios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();
        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['nombre'])) $errores[] = 'El nombre es obligatorio.';
        if (empty($data['email'])) $errores[] = 'El email es obligatorio.';
        if (empty($data['password'])) $errores[] = 'La contraseña es obligatoria.';

        if (!empty($errores)) {
            $usuario = $data;
            $pageTitle = 'Nuevo Usuario — Admin';
            $viewName = 'admin/usuarios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $model = new Usuario($this->db);

        // Verificar email único
        if ($model->findByEmail($data['email'])) {
            $errores = ['Ya existe un usuario con ese email.'];
            $usuario = $data;
            $pageTitle = 'Nuevo Usuario — Admin';
            $viewName = 'admin/usuarios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $insertData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'rol' => $data['rol'] ?? 'editor',
            'activo' => isset($data['activo']) ? 1 : 1,
        ];

        $id = $model->create($insertData);
        AuditLog::log('crear', 'usuarios', $id, "Usuario: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/usuarios');
        exit;
    }

    public function editar(string $id): void
    {
        $model = new Usuario($this->db);
        $usuario = $model->find((int) $id);

        if (!$usuario) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($usuario['nombre']) . ' — Admin';
        $viewName = 'admin/usuarios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Usuario($this->db);
        $usuario = $model->find((int) $id);

        if (!$usuario) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        if (empty($data['nombre'])) {
            $errores = ['El nombre es obligatorio.'];
            $usuario = array_merge($usuario, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($usuario['nombre']) . ' — Admin';
            $viewName = 'admin/usuarios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $updateData = [
            'nombre' => $data['nombre'],
            'email' => $data['email'],
            'rol' => $data['rol'] ?? $usuario['rol'],
            'activo' => isset($data['activo']) ? 1 : 0,
        ];

        // Solo actualizar contraseña si se proporcionó
        if (!empty($data['password'])) {
            $updateData['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $model->update((int) $id, $updateData);
        AuditLog::log('editar', 'usuarios', (int) $id, "Usuario: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/usuarios');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();

        // No permitir eliminar el propio usuario
        if ((int) $id === AuthMiddleware::userId()) {
            header('Location: ' . SITE_URL . '/admin/usuarios');
            exit;
        }

        $model = new Usuario($this->db);
        $usuario = $model->find((int) $id);

        if ($usuario) {
            $model->delete((int) $id);
            AuditLog::log('eliminar', 'usuarios', (int) $id, "Usuario: {$usuario['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/usuarios');
        exit;
    }
}
