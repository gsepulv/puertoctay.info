<?php

class AdminMensajeController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        AuthMiddleware::check();
    }

    public function index(): void
    {
        $modelo = new Mensaje($this->db);
        $mensajes = $modelo->findAllOrdered(100);
        $noLeidos = $modelo->countNoLeidos();

        $pageTitle = 'Mensajes';
        $viewName = 'admin/mensajes/index';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function leer(string $id): void
    {
        $id = (int) $id;
        $modelo = new Mensaje($this->db);
        $mensaje = $modelo->find($id);

        if (!$mensaje) {
            $_SESSION['flash_error'] = 'Mensaje no encontrado.';
            header('Location: ' . SITE_URL . '/admin/mensajes');
            exit;
        }

        // Marcar como leido
        if (!$mensaje['leido']) {
            $modelo->marcarLeido($id);
            $mensaje['leido'] = 1;
        }

        $pageTitle = 'Mensaje de ' . htmlspecialchars($mensaje['nombre']);
        $viewName = 'admin/mensajes/show';
        require ROOT_PATH . '/views/layouts/admin.php';
    }

    public function eliminar(string $id): void
    {
        CsrfMiddleware::validate();
        $id = (int) $id;
        $modelo = new Mensaje($this->db);
        $modelo->delete($id);

        $_SESSION['flash_success'] = 'Mensaje eliminado.';
        header('Location: ' . SITE_URL . '/admin/mensajes');
        exit;
    }
}
