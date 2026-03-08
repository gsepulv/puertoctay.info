<?php
/**
 * Sanitización de input del usuario.
 */

class Sanitizer
{
    /**
     * Limpiar string: strip tags + htmlspecialchars.
     */
    public static function clean(string $value): string
    {
        $value = strip_tags($value);
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
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
