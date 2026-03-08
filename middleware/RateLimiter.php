<?php
/**
 * Rate limiter por IP usando tabla rate_limits.
 */

class RateLimiter
{
    /**
     * Verificar si la IP puede realizar la acción.
     *
     * @param string $endpoint  Identificador del endpoint
     * @param int    $maxHits   Máximo de peticiones permitidas
     * @param int    $windowSec Ventana de tiempo en segundos (default 60)
     * @return bool true si permitido, false si excede límite
     */
    public static function check(string $endpoint, int $maxHits = 10, int $windowSec = 60): bool
    {
        $db = getDB();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Limpiar registros expirados
        $stmt = $db->prepare("DELETE FROM rate_limits WHERE window_start < DATE_SUB(NOW(), INTERVAL :sec SECOND)");
        $stmt->execute(['sec' => $windowSec]);

        // Buscar registro actual
        $stmt = $db->prepare(
            "SELECT id, hits FROM rate_limits
             WHERE ip_address = :ip AND endpoint = :ep
             AND window_start > DATE_SUB(NOW(), INTERVAL :sec SECOND)
             LIMIT 1"
        );
        $stmt->execute(['ip' => $ip, 'ep' => $endpoint, 'sec' => $windowSec]);
        $row = $stmt->fetch();

        if ($row) {
            if ((int) $row['hits'] >= $maxHits) {
                return false;
            }
            $stmt = $db->prepare("UPDATE rate_limits SET hits = hits + 1 WHERE id = :id");
            $stmt->execute(['id' => $row['id']]);
        } else {
            $stmt = $db->prepare(
                "INSERT INTO rate_limits (ip_address, endpoint, hits, window_start) VALUES (:ip, :ep, 1, NOW())"
            );
            $stmt->execute(['ip' => $ip, 'ep' => $endpoint]);
        }

        return true;
    }
}
