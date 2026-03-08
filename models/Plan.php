<?php

class Plan extends Model
{
    protected string $table = 'planes';

    public function findActivos(): array
    {
        return $this->findAll(['activo' => 1], 'prioridad ASC');
    }
}
