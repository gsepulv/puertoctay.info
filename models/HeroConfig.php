<?php

class HeroConfig extends Model
{
    protected string $table = 'hero_config';

    public function getActive(): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM hero_config WHERE activo = 1 ORDER BY id ASC LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
