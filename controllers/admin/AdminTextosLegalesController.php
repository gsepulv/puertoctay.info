<?php

class AdminTextosLegalesController
{
    private PDO $db;
    private array $slugsLegales = ['politica-de-privacidad', 'terminos-y-condiciones', 'politica-de-cookies'];

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $model = new Pagina($this->db);
        $paginas = [];
        foreach ($this->slugsLegales as $slug) {
            $p = $model->findBySlug($slug);
            if ($p) $paginas[] = $p;
        }

        $pageTitle = 'Textos Legales — Admin';
        $viewName = 'admin/textos-legales/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function editar(string $id): void
    {
        $model = new Pagina($this->db);
        $pagina = $model->find((int) $id);

        if (!$pagina || !in_array($pagina['slug'], $this->slugsLegales)) {
            $_SESSION['flash_error'] = 'Página legal no encontrada.';
            header('Location: ' . SITE_URL . '/admin/textos-legales');
            exit;
        }

        $errores = [];
        $pageTitle = 'Editar: ' . htmlspecialchars($pagina['titulo']) . ' — Admin';
        $viewName = 'admin/textos-legales/form';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function actualizar(string $id): void
    {
        CsrfMiddleware::validate();
        $model = new Pagina($this->db);
        $pagina = $model->find((int) $id);

        if (!$pagina || !in_array($pagina['slug'], $this->slugsLegales)) {
            $_SESSION['flash_error'] = 'Página legal no encontrada.';
            header('Location: ' . SITE_URL . '/admin/textos-legales');
            exit;
        }

        $contenido = $_POST['contenido'] ?? '';
        $metaTitle = Sanitizer::clean($_POST['meta_title'] ?? '');
        $metaDescription = Sanitizer::clean($_POST['meta_description'] ?? '');
        $activo = isset($_POST['activo']) ? 1 : 0;

        $model->update((int) $id, [
            'contenido' => $contenido,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'activo' => $activo,
        ]);

        AuditLog::log('editar', 'paginas', (int) $id, "Texto legal: {$pagina['titulo']}");

        $_SESSION['flash_success'] = 'Texto legal actualizado correctamente.';
        header('Location: ' . SITE_URL . '/admin/textos-legales');
        exit;
    }
}
