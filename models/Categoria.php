<?php

class Categoria extends Model
{
    protected string $table = 'categorias';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findDirectorio(): array
    {
        return $this->findAll(['tipo' => 'directorio', 'activo' => 1], 'orden ASC');
    }

    public function findEditorial(): array
    {
        return $this->findAll(['tipo' => 'editorial', 'activo' => 1], 'orden ASC');
    }

    /**
     * Categorías de directorio con conteo de negocios activos.
     */
    public function findDirectorioConConteo(): array
    {
        $sql = "SELECT c.*, COUNT(n.id) AS total_negocios
                FROM categorias c
                LEFT JOIN negocios n ON n.categoria_id = c.id AND n.activo = 1
                WHERE c.tipo = 'directorio' AND c.activo = 1
                GROUP BY c.id
                ORDER BY c.orden ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
