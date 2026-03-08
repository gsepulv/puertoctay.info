<?php

class SlugHelper
{
    /**
     * Generar slug URL-safe desde texto.
     */
    public static function generate(string $text): string
    {
        $slug = mb_strtolower($text, 'UTF-8');
        // Reemplazar acentos
        $map = [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ñ' => 'n', 'ü' => 'u',
        ];
        $slug = strtr($slug, $map);
        // Solo alfanuméricos y guiones
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    /**
     * Generar slug único verificando contra la tabla.
     */
    public static function unique(PDO $db, string $table, string $text, ?int $excludeId = null): string
    {
        $base = self::generate($text);
        $slug = $base;
        $i = 1;

        while (true) {
            $sql = "SELECT COUNT(*) FROM {$table} WHERE slug = :slug";
            $params = ['slug' => $slug];

            if ($excludeId !== null) {
                $sql .= " AND id != :eid";
                $params['eid'] = $excludeId;
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            if ((int) $stmt->fetchColumn() === 0) {
                return $slug;
            }

            $slug = $base . '-' . (++$i);
        }
    }
}
