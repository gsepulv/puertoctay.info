<?php

class Negocio extends Model
{
    protected string $table = 'negocios';

    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji,
                       p.nombre AS plan_nombre, p.tiene_sello AS plan_badge
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                LEFT JOIN planes_config p ON p.slug = n.plan
                WHERE n.slug = :slug AND n.activo = 1
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findByCategoria(int $categoriaId, int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT n.*, p.orden AS plan_prioridad, p.tiene_sello AS plan_badge
                FROM negocios n
                LEFT JOIN planes_config p ON p.slug = n.plan
                WHERE n.categoria_id = :cid AND n.activo = 1
                ORDER BY p.orden DESC, n.nombre ASC";
        $params = ['cid' => $categoriaId];

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findActivos(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji,
                       p.orden AS plan_prioridad, p.tiene_sello AS plan_badge
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                LEFT JOIN planes_config p ON p.slug = n.plan
                WHERE n.activo = 1
                ORDER BY p.orden DESC, n.nombre ASC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
            if ($offset > 0) {
                $sql .= " OFFSET {$offset}";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findDestacados(int $limit = 6): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji,
                       p.nombre AS plan_nombre, p.tiene_sello AS plan_badge
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                LEFT JOIN planes_config p ON p.slug = n.plan
                WHERE n.activo = 1 AND p.orden >= 1
                ORDER BY p.orden DESC, n.visitas DESC
                LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementarVisitas(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE negocios SET visitas = visitas + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function findParaMapa(): array
    {
        $sql = "SELECT n.id, n.slug, n.nombre, n.tipo, n.descripcion_corta, n.lat, n.lng,
                       n.foto_principal, n.telefono, n.whatsapp,
                       c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.activo = 1 AND n.lat IS NOT NULL AND n.lng IS NOT NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function buscar(string $termino, ?string $tipo = null, ?int $categoriaId = null): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.activo = 1 AND (n.nombre LIKE :term OR n.descripcion_corta LIKE :term2)";
        $params = ['term' => "%{$termino}%", 'term2' => "%{$termino}%"];

        if ($tipo !== null) {
            $sql .= " AND n.tipo = :tipo";
            $params['tipo'] = $tipo;
        }
        if ($categoriaId !== null) {
            $sql .= " AND n.categoria_id = :cid";
            $params['cid'] = $categoriaId;
        }

        $sql .= " ORDER BY n.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Conteo por categoría para admin.
     */
    public function countByCategoria(int $categoriaId): int
    {
        return $this->count(['categoria_id' => $categoriaId, 'activo' => 1]);
    }

    /**
     * Todos para admin (incluye inactivos). Opcionalmente filtra por status.
     */
    public function findAllAdmin(?string $statusFilter = null): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, p.nombre AS plan_nombre
                FROM negocios n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                LEFT JOIN planes_config p ON p.slug = n.plan";

        $params = [];
        if ($statusFilter !== null) {
            $sql .= " WHERE n.status = :status";
            $params['status'] = $statusFilter;
        }

        $sql .= " ORDER BY n.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
