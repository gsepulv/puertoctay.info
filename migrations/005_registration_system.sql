-- Migration 005: Registration system
-- Date: 2026-03-29

-- Add comerciante role to usuarios
ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin','editor','moderador','comerciante') NOT NULL DEFAULT 'editor';

-- Add telefono to usuarios
ALTER TABLE usuarios ADD COLUMN telefono VARCHAR(20) NULL AFTER email;