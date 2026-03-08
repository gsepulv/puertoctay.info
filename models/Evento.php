<?php

class Evento extends Model
{
    protected string $table = 'eventos';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findPublicados(int $limit = 0): array
    {
        $sql = "SELECT * FROM eventos WHERE estado = 'publicado' ORDER BY fecha_inicio ASC";
        if ($limit > 0) $sql .= " LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findProximos(int $limit = 5): array
    {
        $sql = "SELECT * FROM eventos WHERE estado = 'publicado' AND fecha_inicio >= CURDATE()
                ORDER BY fecha_inicio ASC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAllAdmin(): array
    {
        $sql = "SELECT * FROM eventos ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countProximos(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM eventos WHERE estado = 'publicado' AND fecha_inicio >= CURDATE()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
