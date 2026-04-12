-- Migración: SEO fields para noticias
-- Fecha: 2026-04-04
ALTER TABLE noticias
  ADD COLUMN IF NOT EXISTS meta_titulo VARCHAR(60) DEFAULT NULL AFTER schema_type,
  ADD COLUMN IF NOT EXISTS meta_descripcion VARCHAR(160) DEFAULT NULL AFTER meta_titulo,
  ADD COLUMN IF NOT EXISTS keywords VARCHAR(255) DEFAULT NULL AFTER meta_descripcion;
