<?php
/**
 * API para suscripción de emails (página de construcción).
 */

class SubscribeApiController
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function store(): void
    {
        header('Content-Type: application/json');

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            http_response_code(400);
            echo json_encode(['error' => 'Email inválido']);
            return;
        }

        // Crear tabla si no existe
        $this->db->exec(
            "CREATE TABLE IF NOT EXISTS suscriptores (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(200) NOT NULL UNIQUE,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        // Insertar (ignorar duplicados)
        $stmt = $this->db->prepare(
            "INSERT IGNORE INTO suscriptores (email) VALUES (:email)"
        );
        $stmt->execute(['email' => $email]);

        echo json_encode(['ok' => true]);
    }
}
