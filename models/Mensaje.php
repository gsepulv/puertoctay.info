<?php

class Mensaje extends Model
{
    protected string $table = 'mensajes';

    /**
     * Obtener todos los mensajes ordenados por fecha descendente.
     */
    public function findAllOrdered(int $limit = 50): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM mensajes ORDER BY created_at DESC LIMIT :lim"
        );
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Contar mensajes no leidos.
     */
    public function countNoLeidos(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM mensajes WHERE leido = 0");
        return (int) $stmt->fetchColumn();
    }

    /**
     * Marcar como leido.
     */
    public function marcarLeido(int $id): bool
    {
        return $this->update($id, ['leido' => 1]);
    }
}
