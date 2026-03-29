<?php
/**
 * Sanitización de input del usuario.
 *
 * Solo limpia input (strip_tags + trim). NO aplica htmlspecialchars aquí
 * porque el output escaping se hace en las vistas con htmlspecialchars().
 * Aplicarlo aquí causaría doble-escape y rompería emojis/caracteres especiales.
 */

class Sanitizer
{
    /**
     * Limpiar string: strip tags y trim.
     */
    public static function clean(string $value): string
    {
        return trim(strip_tags($value));
    }

    /**
     * Limpiar array de strings recursivamente.
     */
    public static function cleanArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = self::clean($value);
            } elseif (is_array($value)) {
                $data[$key] = self::cleanArray($value);
            }
        }
        return $data;
    }
}
