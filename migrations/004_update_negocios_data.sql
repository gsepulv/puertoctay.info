-- Migration 004: Update negocios with admin contact info and complete data
-- Date: 2026-03-29
-- All businesses get the same contact info (admin's real contact)
-- until real business owners register their own

-- Contact info for all businesses
UPDATE negocios SET
    telefono = '+56976547757',
    whatsapp = '+56976547757',
    email = 'contacto@purranque.info',
    sitio_web = 'https://visitapuertoctay.cl';

-- Horarios
UPDATE negocios SET horario = 'Martes a Domingo: 12:00 - 22:00. Lunes cerrado.' WHERE id = 1;
UPDATE negocios SET horario = 'Lunes a Sábado: 12:30 - 21:30. Domingo: 12:30 - 16:00.' WHERE id = 2;
UPDATE negocios SET horario = 'Viernes a Domingo: 12:00 - 20:00. Verano: todos los días.' WHERE id = 3;
UPDATE negocios SET horario = 'Sábado y Domingo: 13:00 - 19:00. Feriados consultar.' WHERE id = 4;
UPDATE negocios SET horario = 'Recepción: 08:00 - 22:00. Check-in desde 14:00.' WHERE id = 5;
UPDATE negocios SET horario = 'Recepción: 09:00 - 21:00.' WHERE id = 6;
UPDATE negocios SET horario = 'Recepción: 08:00 - 22:00.' WHERE id = 7;
UPDATE negocios SET horario = 'Martes a Domingo: 10:00 - 18:00. Enero y Febrero: 10:00 - 20:00.' WHERE id = 8;
UPDATE negocios SET horario = 'Acceso libre todo el año. Mejor época: octubre a marzo.' WHERE id = 9;
UPDATE negocios SET horario = 'Misas: Sábado 19:00, Domingo 11:00. Visitas: Lunes a Viernes 10:00 - 13:00.' WHERE id = 10;
UPDATE negocios SET horario = 'Acceso libre. Se recomienda visitar con luz de día.' WHERE id = 11;
UPDATE negocios SET horario = 'Lunes a Sábado: 10:00 - 19:00. Domingo: 10:00 - 14:00.' WHERE id = 12;
UPDATE negocios SET horario = 'Lunes a Viernes: 09:00 - 17:00. Verano: Lunes a Domingo 09:00 - 20:00.' WHERE id = 13;

-- Descripciones largas (resumidas aquí, completas en el script PHP)
-- Ver update_negocios.php para el texto completo de cada una
