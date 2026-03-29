<?php

class PlanController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $model = new Plan($this->db);
        $planes = $model->findActivos();

        $pageTitle = 'Planes para Negocios — ' . SITE_NAME;
        $pageDescription = 'Conoce los planes disponibles para que tu negocio tenga mayor visibilidad en Visita Puerto Octay.';
        $viewName = 'public/planes';
        require ROOT_PATH . '/views/layouts/main.php';
    }
}
