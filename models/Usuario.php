<?php

class Usuario extends Model
{
    protected string $table = 'usuarios';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function findAllAdmin(): array
    {
        return $this->findAll([], 'created_at DESC');
    }
}
