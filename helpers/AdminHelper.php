<?php

class AdminHelper
{
    /**
     * Contadores dinámicos para el sidebar admin.
     */
    public static function sidebarCounts(PDO $db): array
    {
        $counts = [];

        $queries = [
            'negocios_activos'   => "SELECT COUNT(*) FROM negocios WHERE activo = 1",
            'negocios_pendientes'=> "SELECT COUNT(*) FROM negocios WHERE status = 'pendiente'",
            'negocios_verificados'=> "SELECT COUNT(*) FROM negocios WHERE verificado = 1",
            'categorias'         => "SELECT COUNT(*) FROM categorias WHERE activo = 1",
            'noticias_publicadas'=> "SELECT COUNT(*) FROM noticias WHERE estado = 'publicado'",
            'resenas_pendientes' => "SELECT COUNT(*) FROM resenas WHERE estado = 'pendiente'",
            'eventos_proximos'   => "SELECT COUNT(*) FROM eventos WHERE estado = 'publicado' AND fecha_inicio >= CURDATE()",
            'propietarios'       => "SELECT COUNT(*) FROM usuarios WHERE rol = 'comerciante' AND activo = 1",
            'usuarios'           => "SELECT COUNT(*) FROM usuarios WHERE activo = 1",
        ];

        foreach ($queries as $key => $sql) {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $counts[$key] = (int) $stmt->fetchColumn();
        }

        // Visitas
        $counts['visitas_hoy'] = self::visitasPeriodo($db, 'CURDATE()');
        $counts['visitas_semana'] = self::visitasPeriodo($db, 'DATE_SUB(CURDATE(), INTERVAL 7 DAY)');
        $counts['visitas_mes'] = self::visitasPeriodo($db, 'DATE_SUB(CURDATE(), INTERVAL 30 DAY)');

        return $counts;
    }

    private static function visitasPeriodo(PDO $db, string $desde): int
    {
        $sql = "SELECT COALESCE(SUM(visitas), 0) FROM negocios WHERE activo = 1";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    /**
     * Contadores para badges del sidebar (solo los que muestran número).
     */
    public static function sidebarBadges(PDO $db): array
    {
        return [
            'negocios' => self::queryCount($db, "SELECT COUNT(*) FROM negocios WHERE activo = 1"),
            'categorias' => self::queryCount($db, "SELECT COUNT(*) FROM categorias WHERE activo = 1"),
            'resenas' => self::queryCount($db, "SELECT COUNT(*) FROM resenas WHERE estado = 'pendiente'"),
            'mensajes' => self::queryCount($db, "SELECT COUNT(*) FROM mensajes WHERE leido = 0"),
            'pendientes_registro' => self::queryCount($db, "SELECT COUNT(*) FROM negocios WHERE status = 'pendiente'")
        ];
    }

    private static function queryCount(PDO $db, string $sql): int
    {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
