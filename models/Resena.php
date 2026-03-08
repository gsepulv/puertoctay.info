<?php

class Resena extends Model
{
    protected string $table = 'resenas';

    public function findPendientes(int $limit = 0): array
    {
        $sql = "SELECT r.*, n.nombre AS negocio_nombre, n.slug AS negocio_slug
                FROM resenas r
                LEFT JOIN negocios n ON n.id = r.negocio_id
                WHERE r.estado = 'pendiente'
                ORDER BY r.created_at DESC";
        if ($limit > 0) $sql .= " LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllAdmin(): array
    {
        $sql = "SELECT r.*, n.nombre AS negocio_nombre
                FROM resenas r
                LEFT JOIN negocios n ON n.id = r.negocio_id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countPendientes(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM resenas WHERE estado = 'pendiente'");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function aprobar(int $id): void
    {
        $this->update($id, ['estado' => 'aprobada']);
    }

    public function rechazar(int $id): void
    {
        $this->update($id, ['estado' => 'rechazada']);
    }
}
