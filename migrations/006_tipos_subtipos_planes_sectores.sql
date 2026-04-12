-- Migración 006: Estructura BD para tipos, subtipos, planes y sectores
-- Ejecutada: 2026-04-12

-- 1. Usuarios: password_hash acepta NULL
ALTER TABLE usuarios MODIFY password_hash VARCHAR(255) NULL;

-- 2. Negocios: nuevas columnas
ALTER TABLE negocios ADD COLUMN subtipo VARCHAR(50) NULL AFTER tipo;
ALTER TABLE negocios ADD COLUMN campos_especificos JSON NULL AFTER subtipo;
ALTER TABLE negocios ADD COLUMN sector_id INT UNSIGNED NULL AFTER direccion;

-- 3. Sectores
CREATE TABLE IF NOT EXISTS sectores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  orden INT DEFAULT 0,
  activo TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. planes_config: agregar es_banner, ampliar nombre y color
ALTER TABLE planes_config ADD COLUMN es_banner TINYINT(1) NOT NULL DEFAULT 0 AFTER tiene_reporte;
ALTER TABLE planes_config MODIFY nombre VARCHAR(100) NOT NULL;
ALTER TABLE planes_config MODIFY color VARCHAR(20) NOT NULL DEFAULT '#6b7280';

-- 5. Subtipos
CREATE TABLE IF NOT EXISTS subtipos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  tipo VARCHAR(50) NOT NULL,
  slug VARCHAR(50) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  icono VARCHAR(10) DEFAULT NULL,
  orden INT DEFAULT 0,
  activo TINYINT(1) DEFAULT 1,
  UNIQUE KEY unique_tipo_slug (tipo, slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
