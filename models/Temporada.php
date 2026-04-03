<?php

class Temporada extends Model
{
    protected string $table = 'temporadas';

    public function findActivas(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM temporadas WHERE activa = 1 ORDER BY orden ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findActual(): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM temporadas WHERE activa = 1 AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE() ORDER BY orden ASC LIMIT 1"
        );
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findForNegocio(int $negocioId): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.*, nt.promocion, nt.horario_especial, nt.activo AS vinculo_activo
             FROM temporadas t
             INNER JOIN negocio_temporada nt ON nt.temporada_id = t.id
             WHERE nt.negocio_id = :nid AND t.activa = 1
             ORDER BY t.orden ASC"
        );
        $stmt->execute(['nid' => $negocioId]);
        return $stmt->fetchAll();
    }

    public function findNegociosByTemporada(int $temporadaId, int $limit = 0): array
    {
        $sql = "SELECT n.*, c.nombre AS categoria_nombre, c.emoji AS categoria_emoji, nt.promocion
                FROM negocios n
                INNER JOIN negocio_temporada nt ON nt.negocio_id = n.id
                LEFT JOIN categorias c ON c.id = n.categoria_id
                WHERE nt.temporada_id = :tid AND n.activo = 1
                ORDER BY n.verificado DESC, n.nombre ASC";
        if ($limit > 0) $sql .= " LIMIT {$limit}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tid' => $temporadaId]);
        return $stmt->fetchAll();
    }

    public function syncNegocioTemporadas(int $negocioId, array $temporadaIds, array $promociones = []): void
    {
        // Delete existing
        $this->db->prepare("DELETE FROM negocio_temporada WHERE negocio_id = :nid")->execute(['nid' => $negocioId]);

        // Insert new
        if (!empty($temporadaIds)) {
            $stmt = $this->db->prepare(
                "INSERT INTO negocio_temporada (negocio_id, temporada_id, promocion) VALUES (:nid, :tid, :promo)"
            );
            foreach ($temporadaIds as $tid) {
                $tid = (int) $tid;
                if ($tid < 1) continue;
                $stmt->execute([
                    'nid'   => $negocioId,
                    'tid'   => $tid,
                    'promo' => $promociones[$tid] ?? null,
                ]);
            }
        }
    }
}
