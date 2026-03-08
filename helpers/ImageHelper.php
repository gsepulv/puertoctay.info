<?php

class ImageHelper
{
    private static string $uploadDir = 'uploads/negocios/';
    private static int $maxWidth = 1200;
    private static int $quality = 85;
    private static array $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    /**
     * Subir y procesar imagen.
     * @return string|null Ruta relativa desde uploads/ o null si falla
     */
    public static function upload(array $file, string $subdir = 'negocios'): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) {
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, self::$allowedTypes, true)) {
            return null;
        }

        $dir = ROOT_PATH . '/public/uploads/' . $subdir;
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = 'webp';
        $filename = uniqid($subdir . '_', true) . '.' . $ext;
        $destPath = $dir . '/' . $filename;

        // Crear imagen desde fuente
        $source = match ($mime) {
            'image/jpeg' => imagecreatefromjpeg($file['tmp_name']),
            'image/png'  => imagecreatefrompng($file['tmp_name']),
            'image/webp' => imagecreatefromwebp($file['tmp_name']),
            default      => null,
        };

        if (!$source) {
            return null;
        }

        // Redimensionar si excede ancho máximo
        $w = imagesx($source);
        $h = imagesy($source);

        if ($w > self::$maxWidth) {
            $newH = (int) round($h * self::$maxWidth / $w);
            $resized = imagecreatetruecolor(self::$maxWidth, $newH);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, self::$maxWidth, $newH, $w, $h);
            imagedestroy($source);
            $source = $resized;
        }

        // Guardar como WebP
        imagewebp($source, $destPath, self::$quality);
        imagedestroy($source);

        return $subdir . '/' . $filename;
    }

    /**
     * Eliminar imagen.
     */
    public static function delete(string $relativePath): void
    {
        $path = ROOT_PATH . '/public/uploads/' . $relativePath;
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
