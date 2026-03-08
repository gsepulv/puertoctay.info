<?php

class NegociosApiController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function json(): void
    {
        $negocioModel = new Negocio($this->db);
        $negocios = $negocioModel->findParaMapa();

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: public, max-age=300');
        echo json_encode($negocios, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
