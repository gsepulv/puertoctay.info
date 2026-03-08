<?php
/**
 * Registro de auditoría en tabla audit_log.
 */

class AuditLog
{
    /**
     * Registrar una acción.
     */
    public static function log(string $accion, ?string $entidad = null, ?int $entidadId = null, ?string $detalle = null): void
    {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO audit_log (usuario_id, accion, entidad, entidad_id, detalle, ip_address)
             VALUES (:uid, :accion, :entidad, :eid, :detalle, :ip)"
        );
        $stmt->execute([
            'uid'     => AuthMiddleware::userId(),
            'accion'  => $accion,
            'entidad' => $entidad,
            'eid'     => $entidadId,
            'detalle' => $detalle,
            'ip'      => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }
}
