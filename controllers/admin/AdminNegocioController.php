<?php

class AdminNegocioController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $negocioModel = new Negocio($this->db);
        $negocios = $negocioModel->findAllAdmin();

        $pageTitle = 'Negocios — Admin';
        $viewName = 'admin/negocios/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findDirectorio();

        $planModel = new Plan($this->db);
        $planes = $planModel->findActivos();

        $negocio = [];
        $errores = [];

        $pageTitle = 'Nuevo Negocio — Admin';
        $viewName = 'admin/negocios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();

        $data = Sanitizer::cleanArray($_POST);

        // Validaciones
        $errores = [];
        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es obligatorio.';
        }
        if (empty($data['tipo'])) {
            $errores[] = 'El tipo es obligatorio.';
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->db);
            $categorias = $categoriaModel->findDirectorio();
            $planModel = new Plan($this->db);
            $planes = $planModel->findActivos();
            $negocio = $data;
            $pageTitle = 'Nuevo Negocio — Admin';
            $viewName = 'admin/negocios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        $negocioModel = new Negocio($this->db);

        // Slug
        $data['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre']);

        // Foto principal
        if (!empty($_FILES['foto_principal']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto_principal'], 'negocios');
            if ($foto) {
                $data['foto_principal'] = $foto;
            }
        }

        // Logo
        if (!empty($_FILES['logo']['name'])) {
            $logo = ImageHelper::upload($_FILES['logo'], 'logos');
            if ($logo) {
                $data['logo'] = $logo;
            }
        }

        // Campos numéricos opcionales
        $data['categoria_id'] = !empty($data['categoria_id']) ? (int) $data['categoria_id'] : null;
        $data['plan_id'] = !empty($data['plan_id']) ? (int) $data['plan_id'] : 1;
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;

        // Eliminar campos que no son de la tabla
        unset($data['csrf_token']);

        $id = $negocioModel->create($data);
        AuditLog::log('crear', 'negocios', $id, "Negocio: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function editar(string $id): void
    {
        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int) $id);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findDirectorio();

        $planModel = new Plan($this->db);
        $planes = $planModel->findActivos();

        $errores = [];

        $pageTitle = 'Editar: ' . htmlspecialchars($negocio['nombre']) . ' — Admin';
        $viewName = 'admin/negocios/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();

        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int) $id);

        if (!$negocio) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['nombre'])) {
            $errores[] = 'El nombre es obligatorio.';
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->db);
            $categorias = $categoriaModel->findDirectorio();
            $planModel = new Plan($this->db);
            $planes = $planModel->findActivos();
            $negocio = array_merge($negocio, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($negocio['nombre']) . ' — Admin';
            $viewName = 'admin/negocios/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Slug si cambió el nombre
        if ($data['nombre'] !== $negocio['nombre']) {
            $data['slug'] = SlugHelper::unique($this->db, 'negocios', $data['nombre'], (int) $id);
        }

        // Foto principal
        if (!empty($_FILES['foto_principal']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto_principal'], 'negocios');
            if ($foto) {
                if (!empty($negocio['foto_principal'])) {
                    ImageHelper::delete($negocio['foto_principal']);
                }
                $data['foto_principal'] = $foto;
            }
        }

        // Logo
        if (!empty($_FILES['logo']['name'])) {
            $logo = ImageHelper::upload($_FILES['logo'], 'logos');
            if ($logo) {
                if (!empty($negocio['logo'])) {
                    ImageHelper::delete($negocio['logo']);
                }
                $data['logo'] = $logo;
            }
        }

        $data['categoria_id'] = !empty($data['categoria_id']) ? (int) $data['categoria_id'] : null;
        $data['plan_id'] = !empty($data['plan_id']) ? (int) $data['plan_id'] : 1;
        $data['lat'] = !empty($data['lat']) ? $data['lat'] : null;
        $data['lng'] = !empty($data['lng']) ? $data['lng'] : null;

        unset($data['csrf_token']);

        $negocioModel->update((int) $id, $data);
        AuditLog::log('editar', 'negocios', (int) $id, "Negocio: {$data['nombre']}");

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();

        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int) $id);

        if ($negocio) {
            if (!empty($negocio['foto_principal'])) {
                ImageHelper::delete($negocio['foto_principal']);
            }
            if (!empty($negocio['logo'])) {
                ImageHelper::delete($negocio['logo']);
            }
            $negocioModel->delete((int) $id);
            AuditLog::log('eliminar', 'negocios', (int) $id, "Negocio: {$negocio['nombre']}");
        }

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }

    public function verificar(string $id): void
    {
        CsrfMiddleware::validate();

        $negocioModel = new Negocio($this->db);
        $negocio = $negocioModel->find((int) $id);

        if ($negocio) {
            $nuevoEstado = $negocio['verificado'] ? 0 : 1;
            $negocioModel->update((int) $id, ['verificado' => $nuevoEstado]);
            AuditLog::log('verificar', 'negocios', (int) $id, ($nuevoEstado ? 'Verificado' : 'No verificado'));
        }

        header('Location: ' . SITE_URL . '/admin/negocios');
        exit;
    }
}
