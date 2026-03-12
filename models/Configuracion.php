<?php

class Configuracion extends Model
{
    protected string $table = 'configuracion';

    /**
     * Obtener todos los valores de un grupo.
     */
    public function findByGrupo(string $grupo): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE grupo = :grupo ORDER BY orden ASC"
        );
        $stmt->execute(['grupo' => $grupo]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener valor por grupo y clave.
     */
    public function getValue(string $grupo, string $clave): ?string
    {
        $stmt = $this->db->prepare(
            "SELECT valor FROM {$this->table} WHERE grupo = :grupo AND clave = :clave LIMIT 1"
        );
        $stmt->execute(['grupo' => $grupo, 'clave' => $clave]);
        $val = $stmt->fetchColumn();
        return $val !== false ? $val : null;
    }

    /**
     * Guardar valor por grupo y clave (upsert).
     */
    public function setValue(string $grupo, string $clave, ?string $valor): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET valor = :valor WHERE grupo = :grupo AND clave = :clave"
        );
        return $stmt->execute(['valor' => $valor, 'grupo' => $grupo, 'clave' => $clave]);
    }

    /**
     * Obtener todos los grupos disponibles.
     */
    public function getGrupos(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT grupo FROM {$this->table} ORDER BY grupo");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
