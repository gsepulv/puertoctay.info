<?php

/**
 * Controller para módulos que aún no están implementados.
 * Se mantiene solo para redirects legacy; los placeholders fueron
 * eliminados del menú en la reorganización del 2026-03-29.
 */
class AdminPlaceholderController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    /**
     * Blog redirige a Noticias (son el mismo módulo).
     */
    public function blog(): void
    {
        header('Location: ' . SITE_URL . '/admin/noticias');
        exit;
    }
}
