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
        header('Location: ' . SITE_URL . '/', true, 302);
        exit;
    }
}
