-- Migración: columna idiomas en negocios
-- Fecha: 2026-04-04
ALTER TABLE negocios ADD COLUMN IF NOT EXISTS idiomas TEXT DEFAULT NULL AFTER metodo_pago;
