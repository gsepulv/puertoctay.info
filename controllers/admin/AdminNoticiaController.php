<?php

class AdminNoticiaController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::checkAdmin();
    }

    public function index(): void
    {
        $noticiaModel = new Noticia($this->db);
        $noticias = $noticiaModel->findAllAdmin();

        $pageTitle = 'Noticias — Admin';
        $viewName = 'admin/noticias/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function crear(): void
    {
        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findEditorial();

        $noticia = [];
        $errores = [];

        $pageTitle = 'Nueva Noticia — Admin';
        $viewName = 'admin/noticias/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function guardar(): void
    {
        CsrfMiddleware::validate();

        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['titulo'])) {
            $errores[] = 'El título es obligatorio.';
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->db);
            $categorias = $categoriaModel->findEditorial();
            $noticia = $data;
            $pageTitle = 'Nueva Noticia — Admin';
            $viewName = 'admin/noticias/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Slug: usar el enviado por el form, o generar desde título
        if (!empty($data['slug'])) {
            $data['slug'] = SlugHelper::unique($this->db, 'noticias', $data['slug']);
        } else {
            $data['slug'] = SlugHelper::unique($this->db, 'noticias', $data['titulo']);
        }
// Raw HTML content (WYSIWYG)
        $data["contenido"] = $_POST["contenido"] ?? "";
        $data['tiempo_lectura'] = Noticia::calcularTiempoLectura($data['contenido'] ?? '');
        $data['categoria_id'] = !empty($data['categoria_id']) ? (int) $data['categoria_id'] : null;
        $data['featured'] = isset($data['featured']) ? 1 : 0;

        // SEO
        $data['meta_titulo'] = !empty($data['meta_titulo']) ? mb_substr($data['meta_titulo'], 0, 60) : null;
        $data['meta_descripcion'] = !empty($data['meta_descripcion']) ? mb_substr($data['meta_descripcion'], 0, 160) : null;
        $data['keywords'] = !empty($data['keywords']) ? $data['keywords'] : null;

        // Publicación programada
        if (empty($data['publicado_en'])) {
            if ($data['estado'] === 'publicado') {
                $data['publicado_en'] = date('Y-m-d H:i:s');
            } else {
                $data['publicado_en'] = null;
            }
        }

        // Foto destacada
        if (!empty($_FILES['foto_destacada']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto_destacada'], 'noticias');
            if ($foto) {
                $data['foto_destacada'] = $foto;
            }
        }

        unset($data['csrf_token']);

        $noticiaModel = new Noticia($this->db);
        $id = $noticiaModel->create($data);
        AuditLog::log('crear', 'noticias', $id, "Noticia: {$data['titulo']}");

        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }

    public function editar(string $id): void
    {
        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->find((int) $id);

        if (!$noticia) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $categoriaModel = new Categoria($this->db);
        $categorias = $categoriaModel->findEditorial();
        $errores = [];

        $pageTitle = 'Editar: ' . htmlspecialchars($noticia['titulo']) . ' — Admin';
        $viewName = 'admin/noticias/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();

        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->find((int) $id);

        if (!$noticia) {
            http_response_code(404);
            require ROOT_PATH . '/views/errors/404.php';
            return;
        }

        $data = Sanitizer::cleanArray($_POST);

        $errores = [];
        if (empty($data['titulo'])) {
            $errores[] = 'El título es obligatorio.';
        }

        if (!empty($errores)) {
            $categoriaModel = new Categoria($this->db);
            $categorias = $categoriaModel->findEditorial();
            $noticia = array_merge($noticia, $data);
            $pageTitle = 'Editar: ' . htmlspecialchars($noticia['titulo']) . ' — Admin';
            $viewName = 'admin/noticias/form';
            require ROOT_PATH . '/views/layouts/admin.php';
            return;
        }

        // Slug: si el usuario lo editó manualmente, usarlo; si cambió el título y slug está vacío, regenerar
        if (!empty($data['slug']) && $data['slug'] !== $noticia['slug']) {
            $data['slug'] = SlugHelper::unique($this->db, 'noticias', $data['slug'], (int) $id);
        } elseif ($data['titulo'] !== $noticia['titulo'] && (empty($data['slug']) || $data['slug'] === $noticia['slug'])) {
            $data['slug'] = SlugHelper::unique($this->db, 'noticias', $data['titulo'], (int) $id);
        } else {
            unset($data['slug']);
        }

// Raw HTML content (WYSIWYG)
        $data["contenido"] = $_POST["contenido"] ?? "";
        $data['tiempo_lectura'] = Noticia::calcularTiempoLectura($data['contenido'] ?? '');
        $data['categoria_id'] = !empty($data['categoria_id']) ? (int) $data['categoria_id'] : null;
        $data['featured'] = isset($data['featured']) ? 1 : 0;

        // SEO
        $data['meta_titulo'] = !empty($data['meta_titulo']) ? mb_substr($data['meta_titulo'], 0, 60) : null;
        $data['meta_descripcion'] = !empty($data['meta_descripcion']) ? mb_substr($data['meta_descripcion'], 0, 160) : null;
        $data['keywords'] = !empty($data['keywords']) ? $data['keywords'] : null;

        if (empty($data['publicado_en'])) {
            if ($data['estado'] === 'publicado' && empty($noticia['publicado_en'])) {
                $data['publicado_en'] = date('Y-m-d H:i:s');
            } else {
                $data['publicado_en'] = $noticia['publicado_en'];
            }
        }

        // Foto destacada
        if (!empty($_FILES['foto_destacada']['name'])) {
            $foto = ImageHelper::upload($_FILES['foto_destacada'], 'noticias');
            if ($foto) {
                if (!empty($noticia['foto_destacada'])) {
                    ImageHelper::delete($noticia['foto_destacada']);
                }
                $data['foto_destacada'] = $foto;
            }
        }

        unset($data['csrf_token']);

        $noticiaModel->update((int) $id, $data);
        AuditLog::log('editar', 'noticias', (int) $id, "Noticia: {$data['titulo']}");

        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();

        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->find((int) $id);

        if ($noticia) {
            if (!empty($noticia['foto_destacada'])) {
                ImageHelper::delete($noticia['foto_destacada']);
            }
            $noticiaModel->delete((int) $id);
            AuditLog::log('eliminar', 'noticias', (int) $id, "Noticia: {$noticia['titulo']}");
        }

        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }

    public function estado(string $id): void
    {
        CsrfMiddleware::validate();

        $nuevoEstado = Sanitizer::clean($_POST['estado'] ?? '');
        $validos = ['borrador', 'revision', 'publicado', 'archivado'];

        if (!in_array($nuevoEstado, $validos, true)) {
            header('Location: ' . SITE_URL . '/admin/noticias');
            exit;
        }

        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->find((int) $id);

        if ($noticia) {
            $updateData = ['estado' => $nuevoEstado];
            if ($nuevoEstado === 'publicado' && empty($noticia['publicado_en'])) {
                $updateData['publicado_en'] = date('Y-m-d H:i:s');
            }
            $noticiaModel->update((int) $id, $updateData);
            AuditLog::log('cambiar_estado', 'noticias', (int) $id, "Estado: {$nuevoEstado}");
        }

        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }

    public function destacar(string $id): void
    {
        CsrfMiddleware::validate();

        $noticiaModel = new Noticia($this->db);
        $noticia = $noticiaModel->find((int) $id);

        if ($noticia) {
            $nuevo = $noticia['featured'] ? 0 : 1;
            $noticiaModel->update((int) $id, ['featured' => $nuevo]);
            AuditLog::log('destacar', 'noticias', (int) $id, $nuevo ? 'Destacada' : 'No destacada');
        }

        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }
}
