<?php
/**
 * Modelo PlanConfig — Configuración de planes comerciales.
 * Tabla: planes_config (5 planes fijos, solo edición).
 */

class PlanConfig extends Model
{
    protected string $table = 'planes_config';

    public function findActivos(): array
    {
        return $this->findAll(['activo' => 1], 'orden ASC');
    }

    public function findOrdenados(): array
    {
        return $this->findAll([], 'orden ASC');
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public static function formatPrecio(int $precio): string
    {
        if ($precio === 0) {
            return 'Gratis';
        }
        return '$' . number_format($precio, 0, ',', '.');
    }

    public static function esGratuito(array $plan): bool
    {
        return (int) $plan['precio_intro'] === 0 && (int) $plan['precio_regular'] === 0;
    }

    public static function tieneLimiteFotos(array $plan): bool
    {
        return $plan['max_fotos'] !== null;
    }

    public static function labelPosicion(string $posicion): string
    {
        return match ($posicion) {
            'normal' => 'Normal',
            'prioritaria' => 'Prioritaria',
            'siempre_primero' => 'Siempre primero',
            default => $posicion,
        };
    }
}
