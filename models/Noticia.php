<?php

class Noticia extends Model
{
    protected string $table = 'noticias';

    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.slug = :slug AND n.estado = 'publicado'
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findPublicadas(int $limit = 0, int $offset = 0): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.estado = 'publicado' AND (n.publicado_en IS NULL OR n.publicado_en <= NOW())
                ORDER BY n.publicado_en DESC, n.created_at DESC";

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

    public function findDestacadas(int $limit = 1): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.estado = 'publicado' AND n.featured = 1
                AND (n.publicado_en IS NULL OR n.publicado_en <= NOW())
                ORDER BY n.publicado_en DESC, n.created_at DESC
                LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findPorCategoria(int $categoriaId, int $limit = 0): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.estado = 'publicado' AND n.categoria_id = :cid
                AND (n.publicado_en IS NULL OR n.publicado_en <= NOW())
                ORDER BY n.publicado_en DESC, n.created_at DESC";

        if ($limit > 0) {
            $sql .= " LIMIT {$limit}";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cid' => $categoriaId]);
        return $stmt->fetchAll();
    }

    public function findUltimas(int $limit = 5): array
    {
        return $this->findPublicadas($limit);
    }

    public function findRelacionadas(int $noticiaId, ?int $categoriaId, int $limit = 3): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.slug AS categoria_slug, c.emoji AS categoria_emoji
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE n.estado = 'publicado' AND n.id != :nid
                AND (n.publicado_en IS NULL OR n.publicado_en <= NOW())";
        $params = ['nid' => $noticiaId];

        if ($categoriaId) {
            $sql .= " AND n.categoria_id = :cid";
            $params['cid'] = $categoriaId;
        }

        $sql .= " ORDER BY n.publicado_en DESC LIMIT :lim";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue('lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function calcularTiempoLectura(?string $contenido): int
    {
        if (empty($contenido)) {
            return 1;
        }
        $palabras = str_word_count(strip_tags($contenido));
        return max(1, (int) ceil($palabras / 200));
    }

    public function incrementarVisitas(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE noticias SET visitas = visitas + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /**
     * Categorías editoriales con conteo de noticias publicadas.
     */
    public function conteoCategoriasEditoriales(): array
    {
        $sql = "SELECT c.id, c.nombre, c.slug, c.emoji, COUNT(n.id) AS total
                FROM categorias c
                LEFT JOIN noticias n ON n.categoria_id = c.id AND n.estado = 'publicado'
                    AND (n.publicado_en IS NULL OR n.publicado_en <= NOW())
                WHERE c.tipo = 'editorial' AND c.activo = 1
                GROUP BY c.id
                ORDER BY c.orden ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Todos para admin (incluye no publicados).
     */
    public function findAllAdmin(): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre
                FROM noticias n
                LEFT JOIN categorias c ON c.id = n.categoria_id
                ORDER BY n.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
