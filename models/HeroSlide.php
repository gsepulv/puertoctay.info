<?php

class HeroSlide extends Model
{
    protected string $table = 'hero_slides';

    public function findActivo(): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM hero_slides WHERE activo = 1 ORDER BY orden ASC LIMIT 1"
        );
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findAllOrdered(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM hero_slides ORDER BY orden ASC, id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
