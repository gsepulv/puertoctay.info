-- Migration 006: User System
-- Date: 2026-04-03
-- Adds visitante role, avatar, reset tokens to usuarios;
-- adds status to negocios; creates favoritos table;
-- adds usuario_id to resenas.

-- 1. Add 'visitante' to usuarios.rol enum
ALTER TABLE usuarios MODIFY COLUMN rol ENUM('admin','editor','moderador','comerciante','visitante') NOT NULL DEFAULT 'visitante';

-- 2. Add avatar column to usuarios (after activo)
ALTER TABLE usuarios ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER activo;

-- 3. Add reset_token and reset_expira to usuarios
ALTER TABLE usuarios ADD COLUMN reset_token VARCHAR(255) DEFAULT NULL;
ALTER TABLE usuarios ADD COLUMN reset_expira DATETIME DEFAULT NULL;

-- 4. Add status to negocios
ALTER TABLE negocios ADD COLUMN status ENUM('pendiente','activo','rechazado','suspendido') DEFAULT 'activo' AFTER activo;

-- 5. Set status based on current activo values
UPDATE negocios SET status = 'activo' WHERE activo = 1 AND status IS NULL;
UPDATE negocios SET status = 'pendiente' WHERE activo = 0 AND status IS NULL;

-- 6. Create favoritos table
CREATE TABLE IF NOT EXISTS favoritos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  negocio_id INT UNSIGNED NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_fav (usuario_id, negocio_id),
  KEY idx_usuario (usuario_id),
  KEY idx_negocio (negocio_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Add usuario_id to resenas
ALTER TABLE resenas ADD COLUMN usuario_id INT UNSIGNED DEFAULT NULL AFTER negocio_id;
