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

    /**
     * Crear reseña de visitante anónimo (sin cuenta)
     */
    public function crearDeVisitante(array $datos): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO resenas (negocio_id, usuario_id, nombre_autor, email_autor, puntuacion,
                                  comentario, estado, ip_address, user_agent, visitante_origen)
             VALUES (:nid, NULL, :nombre, :email, :punt, :com, 'pendiente', :ip, :ua, :origen)"
        );
        $stmt->execute([
            'nid'    => $datos['negocio_id'],
            'nombre' => $datos['nombre_autor'],
            'email'  => $datos['email_autor'],
            'punt'   => $datos['puntuacion'],
            'com'    => $datos['comentario'],
            'ip'     => $datos['ip_address'],
            'ua'     => $datos['user_agent'],
            'origen' => $datos['visitante_origen'],
        ]);
        return (int) $this->db->lastInsertId();
    }
}
