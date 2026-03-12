<?php

class Pagina extends Model
{
    protected string $table = 'paginas';

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function findActivas(): array
    {
        return $this->findAll(['activo' => 1], 'orden ASC');
    }

    public function findAllAdmin(): array
    {
        return $this->findAll([], 'orden ASC');
    }
}
