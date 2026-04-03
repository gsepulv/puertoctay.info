<?php

class EmailHelper
{
    private static string $fromEmail = 'no-reply@visitapuertoctay.cl';
    private static string $fromName = 'Visita Puerto Octay';

    /**
     * Send an HTML email. Returns true on success, false on failure.
     * Never throws — logs errors instead.
     */
    public static function send(string $to, string $subject, string $htmlBody): bool
    {
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . self::$fromName . ' <' . self::$fromEmail . '>',
            'Reply-To: contacto@purranque.info',
            'X-Mailer: VisitaPuertoOctay/1.0',
        ]);

        try {
            $result = @mail($to, $subject, $htmlBody, $headers);
            if (!$result) {
                error_log("EmailHelper: mail() failed for {$to} — subject: {$subject}");
            }
            return $result;
        } catch (\Throwable $e) {
            error_log("EmailHelper: Exception sending to {$to} — " . $e->getMessage());
            return false;
        }
    }

    /**
     * Wrap content in a branded HTML email template.
     */
    public static function wrap(string $content): string
    {
        return '<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#F1F5F9;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F1F5F9;padding:2rem 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <!-- Header -->
    <tr><td style="background:#1B4965;padding:1.5rem 2rem;text-align:center;">
        <span style="font-size:1.8rem;">⛵</span>
        <h1 style="color:#ffffff;font-size:1.3rem;margin:0.3rem 0 0;font-weight:700;">Visita Puerto Octay</h1>
    </td></tr>
    <!-- Body -->
    <tr><td style="padding:2rem;">' . $content . '</td></tr>
    <!-- Footer -->
    <tr><td style="background:#F8FAFC;padding:1.2rem 2rem;text-align:center;border-top:1px solid #E2E8F0;">
        <p style="margin:0;font-size:0.8rem;color:#94A3B8;">
            <a href="https://visitapuertoctay.cl" style="color:#1B4965;text-decoration:none;font-weight:600;">visitapuertoctay.cl</a>
            — Un servicio de <a href="https://purranque.info" style="color:#1B4965;text-decoration:none;">PurranQUE.INFO</a>
        </p>
        <p style="margin:0.3rem 0 0;font-size:0.75rem;color:#CBD5E1;">Puerto Octay, Región de Los Lagos, Chile</p>
    </td></tr>
</table>
</td></tr>
</table>
</body>
</html>';
    }

    /**
     * Notify admin about a new business registration.
     */
    public static function notificarNuevoRegistro(array $negocioData, array $propietarioData, string $categoriaNombre): void
    {
        $nombre = htmlspecialchars($negocioData['nombre_comercio']);
        $propietario = htmlspecialchars($propietarioData['nombre']);
        $email = htmlspecialchars($propietarioData['email']);
        $telefono = htmlspecialchars($propietarioData['telefono']);
        $fecha = date('d/m/Y H:i');

        // Email to admin
        $adminBody = self::wrap('
        <h2 style="color:#1B4965;margin:0 0 1rem;">Nuevo comercio registrado</h2>
        <p style="color:#334155;line-height:1.6;">Se ha registrado un nuevo comercio en la plataforma que requiere revisión:</p>
        <table style="width:100%;border-collapse:collapse;margin:1rem 0;">
            <tr><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;width:40%;">Comercio</td><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;color:#1E293B;">' . $nombre . '</td></tr>
            <tr><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;">Categoría</td><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;color:#1E293B;">' . htmlspecialchars($categoriaNombre) . '</td></tr>
            <tr><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;">Propietario</td><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;color:#1E293B;">' . $propietario . '</td></tr>
            <tr><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;">Email</td><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;color:#1E293B;">' . $email . '</td></tr>
            <tr><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;font-weight:600;color:#475569;">Teléfono</td><td style="padding:0.5rem 0;border-bottom:1px solid #E2E8F0;color:#1E293B;">' . $telefono . '</td></tr>
            <tr><td style="padding:0.5rem 0;font-weight:600;color:#475569;">Fecha</td><td style="padding:0.5rem 0;color:#1E293B;">' . $fecha . '</td></tr>
        </table>
        <p style="text-align:center;margin:1.5rem 0 0;">
            <a href="https://visitapuertoctay.cl/admin/negocios" style="display:inline-block;background:#1B4965;color:#ffffff;padding:0.7rem 1.5rem;border-radius:8px;text-decoration:none;font-weight:600;">Revisar en el admin</a>
        </p>');

        self::send('contacto@purranque.info', 'Nuevo comercio registrado en Visita Puerto Octay', $adminBody);

        // Email to comerciante
        $comercianteBody = self::wrap('
        <h2 style="color:#1B4965;margin:0 0 1rem;">¡Bienvenido a Visita Puerto Octay!</h2>
        <p style="color:#334155;line-height:1.6;">Hola <strong>' . $propietario . '</strong>,</p>
        <p style="color:#334155;line-height:1.6;">Hemos recibido el registro de tu comercio <strong>' . $nombre . '</strong> en nuestro directorio digital.</p>
        <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:1rem;margin:1rem 0;">
            <p style="color:#166534;margin:0;font-weight:600;">¿Qué sigue?</p>
            <ul style="color:#166534;margin:0.5rem 0 0;padding-left:1.2rem;line-height:1.8;">
                <li>Nuestro equipo revisará la información de tu comercio.</li>
                <li>Recibirás una notificación por email cuando sea aprobado.</li>
                <li>Una vez aprobado, tu comercio será visible en el directorio.</li>
            </ul>
        </div>
        <p style="color:#334155;line-height:1.6;">Si tienes consultas, no dudes en contactarnos:</p>
        <ul style="color:#334155;line-height:1.8;padding-left:1.2rem;">
            <li>Email: <a href="mailto:contacto@purranque.info" style="color:#1B4965;">contacto@purranque.info</a></li>
            <li>WhatsApp: <a href="https://wa.me/56976547757" style="color:#1B4965;">+56 9 7654 7757</a></li>
        </ul>
        <p style="color:#94A3B8;font-size:0.85rem;margin-top:1.5rem;">Gracias por confiar en Visita Puerto Octay para dar visibilidad a tu negocio.</p>');

        self::send($email, '¡Bienvenido a Visita Puerto Octay!', $comercianteBody);
    }

    /**
     * Notify business owner that their comercio was approved.
     */
    public static function notificarAprobacion(array $usuario, array $negocio): void
    {
        $body = self::wrap(
            "<h2 style='color:#166534;'>¡Tu comercio fue aprobado!</h2>" .
            "<p>¡Felicitaciones <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>!</p>" .
            "<p>Tu comercio <strong>" . htmlspecialchars($negocio['nombre']) . "</strong> ha sido aprobado y ya está visible en el directorio de Visita Puerto Octay.</p>" .
            "<div style='background:#F0FDF4;border:1px solid #22C55E;border-radius:8px;padding:1.25rem;margin:1.5rem 0;'>" .
            "<p style='margin:0 0 0.75rem;font-weight:600;color:#166534;'>Puedes acceder a tu panel:</p>" .
            "<p style='margin:0.25rem 0;'><a href='" . SITE_URL . "/login' style='color:#1B4965;font-weight:600;'>" . SITE_URL . "/login</a></p>" .
            "<p style='margin:0.25rem 0;'>Con tu email: <strong>" . htmlspecialchars($usuario['email']) . "</strong></p>" .
            "<p style='margin:0.25rem 0;'>Y la contraseña que registraste.</p>" .
            "</div>" .
            "<p>Desde tu panel puedes:</p>" .
            "<ul style='margin:0.5rem 0 1rem;padding-left:1.5rem;'>" .
            "<li>Editar la información de tu negocio</li>" .
            "<li>Actualizar redes sociales</li>" .
            "<li>Ver estadísticas de visitas</li>" .
            "</ul>" .
            "<p><a href='" . SITE_URL . "/mi-comercio' style='background:#1B4965;color:#fff;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;display:inline-block;'>Ir a mi panel</a></p>"
        );
        self::send($usuario['email'], '¡Tu comercio fue aprobado! — ' . SITE_NAME, $body);
    }

    /**
     * Notify business owner that their comercio registration was rejected.
     */
    public static function notificarRechazo(array $usuario, array $negocio): void
    {
        $body = self::wrap(
            "<h2>Sobre tu solicitud de registro</h2>" .
            "<p>Hola <strong>" . htmlspecialchars($usuario['nombre']) . "</strong>,</p>" .
            "<p>Lamentamos informarte que tu solicitud de registro del comercio <strong>" . htmlspecialchars($negocio['nombre']) . "</strong> no fue aprobada en esta oportunidad.</p>" .
            "<p>Si crees que hay un error o deseas más información, no dudes en contactarnos:</p>" .
            "<div style='background:#F8FAFC;border:1px solid #E2E8F0;border-radius:8px;padding:1rem;margin:1rem 0;'>" .
            "<p style='margin:0.25rem 0;'>Email: <a href='mailto:contacto@purranque.info' style='color:#1B4965;'>contacto@purranque.info</a></p>" .
            "<p style='margin:0.25rem 0;'>WhatsApp: <a href='https://wa.me/56976547757' style='color:#1B4965;'>+56 9 7654 7757</a></p>" .
            "</div>" .
            "<p style='color:#64748B;'>Gracias por tu interés en Visita Puerto Octay.</p>"
        );
        self::send($usuario['email'], 'Sobre tu solicitud de registro — ' . SITE_NAME, $body);
    }
}
