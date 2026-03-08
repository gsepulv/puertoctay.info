<?php
/**
 * Controlador de la portada pública.
 */

class HomeController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $pageTitle = SITE_NAME . ' — ' . SITE_TAGLINE;
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
