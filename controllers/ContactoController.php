<?php

class ContactoController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function index(): void
    {
        $pageTitle = 'Contacto - ' . SITE_NAME;
        $pageDescription = 'Contacta con ' . SITE_NAME . '. Envíanos tus consultas, sugerencias o comentarios.';
        $viewName = 'public/contacto';
        require ROOT_PATH . '/views/layouts/main.php';
    }

    public function enviar(): void
    {
        CsrfMiddleware::validate();

        // Rate limiting simple por IP
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM mensajes WHERE ip = :ip AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)"
        );
        $stmt->execute(['ip' => $ip]);
        $recientes = (int) $stmt->fetchColumn();

        if ($recientes >= 5) {
            $_SESSION['flash_error'] = 'Has enviado demasiados mensajes. Intenta de nuevo mas tarde.';
            header('Location: ' . SITE_URL . '/contacto');
            exit;
        }

        // Validar campos
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $asunto = trim($_POST['asunto'] ?? '');
        $mensaje = trim($_POST['mensaje'] ?? '');

        $errores = [];
        if (strlen($nombre) < 2 || strlen($nombre) > 100) {
            $errores[] = 'El nombre debe tener entre 2 y 100 caracteres.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'El email no es valido.';
        }
        if (strlen($mensaje) < 10 || strlen($mensaje) > 2000) {
            $errores[] = 'El mensaje debe tener entre 10 y 2000 caracteres.';
        }

        // Honeypot anti-spam
        if (!empty($_POST['website_url'])) {
            header('Location: ' . SITE_URL . '/contacto');
            exit;
        }

        if (!empty($errores)) {
            $_SESSION['flash_error'] = implode('<br>', $errores);
            $_SESSION['form_data'] = $_POST;
            header('Location: ' . SITE_URL . '/contacto');
            exit;
        }

        // Guardar mensaje
        $modelo = new Mensaje($this->db);
        $modelo->create([
            'nombre' => $nombre,
            'email'  => $email,
            'asunto' => $asunto ?: null,
            'mensaje' => $mensaje,
            'ip'     => $ip,
        ]);

        $_SESSION['flash_success'] = 'Mensaje enviado! Te responderemos a la brevedad.';
        unset($_SESSION['form_data']);
        header('Location: ' . SITE_URL . '/contacto');
        exit;
    }
}
