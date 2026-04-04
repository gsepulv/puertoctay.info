<?php

class ImageHelper
{
    private static int $quality = 85;
    private static array $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    private static int $maxSize = 2 * 1024 * 1024; // 2MB

    /**
     * Upload and process image.
     * @param string $subdir  Target subdirectory (negocios, logos, portadas, noticias)
     * @return string|null  Relative path from uploads/ or null on failure
     */
    public static function upload(array $file, string $subdir = 'negocios'): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) {
            return null;
        }

        if ($file['size'] > self::$maxSize) {
            return null;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!in_array($mime, self::$allowedTypes, true)) {
            return null;
        }

        // Determine max dimensions based on subdirectory
        $maxWidth = match ($subdir) {
            'logos'    => 800,
            'hero'     => 800,
            'hero'     => 1920,
            'portadas' => 1200,
            default    => 1200,
        };
        $maxHeight = match ($subdir) {
            'logos'    => 800,
            'hero'     => 800,
            'hero'     => 1920,
            'portadas' => 400,
            default    => 0, // 0 = no height limit
        };

        $dir = $GLOBALS['_uploadBase'] ?? (ROOT_PATH . '/public/uploads/' . $subdir);
        // Try both possible upload paths
        $possibleDirs = [
            '/home/visitapuertoctay/public_html/uploads/' . $subdir,
            ROOT_PATH . '/public/uploads/' . $subdir,
        ];
        $dir = null;
        foreach ($possibleDirs as $d) {
            if (is_dir($d) || @mkdir($d, 0755, true)) {
                $dir = $d;
                break;
            }
        }
        if (!$dir) return null;

        $ext = 'webp';
        $filename = uniqid($subdir . '_', true) . '.' . $ext;
        $destPath = $dir . '/' . $filename;

        // Create image from source
        $source = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file['tmp_name']),
            'image/png'  => @imagecreatefrompng($file['tmp_name']),
            'image/webp' => @imagecreatefromwebp($file['tmp_name']),
            'image/gif'  => @imagecreatefromgif($file['tmp_name']),
            default      => null,
        };

        if (!$source) return null;

        $w = imagesx($source);
        $h = imagesy($source);

        // Resize if exceeds limits
        $needsResize = false;
        $newW = $w;
        $newH = $h;

        if ($w > $maxWidth) {
            $newW = $maxWidth;
            $newH = (int) round($h * $maxWidth / $w);
            $needsResize = true;
        }

        if ($maxHeight > 0 && $newH > $maxHeight) {
            $newH = $maxHeight;
            $newW = (int) round($w * $maxHeight / $h);
            if ($newW > $maxWidth) $newW = $maxWidth;
            $needsResize = true;
        }

        if ($needsResize) {
            $resized = imagecreatetruecolor($newW, $newH);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($source);
            $source = $resized;
        }

        imagewebp($source, $destPath, self::$quality);
        imagedestroy($source);

        return $subdir . '/' . $filename;
    }

    /**
     * Delete an uploaded file.
     */
    public static function delete(string $relativePath): void
    {
        $paths = [
            '/home/visitapuertoctay/public_html/uploads/' . $relativePath,
            ROOT_PATH . '/public/uploads/' . $relativePath,
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                @unlink($path);
                return;
            }
        }
    }
}