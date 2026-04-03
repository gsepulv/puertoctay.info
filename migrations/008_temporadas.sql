CREATE TABLE IF NOT EXISTS temporadas (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  emoji VARCHAR(10),
  slug VARCHAR(100) NOT NULL UNIQUE,
  descripcion TEXT,
  fecha_inicio DATE,
  fecha_fin DATE,
  activa TINYINT(1) DEFAULT 1,
  orden INT DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS negocio_temporada (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  negocio_id INT UNSIGNED NOT NULL,
  temporada_id INT UNSIGNED NOT NULL,
  promocion VARCHAR(255) DEFAULT NULL,
  horario_especial VARCHAR(255) DEFAULT NULL,
  activo TINYINT(1) DEFAULT 1,
  UNIQUE KEY unique_nt (negocio_id, temporada_id),
  KEY idx_negocio (negocio_id),
  KEY idx_temporada (temporada_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO temporadas (nombre, emoji, slug, descripcion, fecha_inicio, fecha_fin, orden) VALUES
('Temporada de Verano', '☀️', 'verano', 'Diciembre a marzo: lago, playa, navegación, camping, gastronomía al aire libre', '2026-12-01', '2027-03-31', 1),
('Semana Santa', '✝️', 'semana-santa', 'Turismo religioso, gastronomía tradicional, actividades familiares', '2026-03-29', '2026-04-05', 2),
('Fiestas Patrias', '🇨🇱', 'fiestas-patrias', 'Fondas, rodeos, gastronomía criolla, celebraciones comunitarias', '2026-09-14', '2026-09-21', 3),
('Aniversario Puerto Octay', '⛵', 'aniversario-puerto-octay', 'Festividades locales, desfiles, actividades culturales', '2026-11-12', '2026-11-16', 4),
('Festival Gastronómico', '🍽️', 'festival-gastronomico', 'Cocina alemana-chilena, Kuchen, cervezas artesanales', '2026-01-15', '2026-01-25', 5),
('Semana del Patrimonio', '🏛️', 'semana-patrimonio', 'Arquitectura colonial alemana, museo, recorridos históricos', '2026-05-25', '2026-05-31', 6),
('Temporada de Nieve', '🏔️', 'temporada-nieve', 'Cercanía al Volcán Osorno, ski, termas, gastronomía de invierno', '2026-06-15', '2026-09-15', 7),
('Navidad y Año Nuevo', '🎄', 'navidad-ano-nuevo', 'Eventos de fin de año, mercados navideños, celebraciones', '2026-12-20', '2027-01-05', 8),
('Día del Patrimonio Cultural', '🎭', 'dia-patrimonio-cultural', 'Puertas abiertas en museos y edificios históricos', '2026-05-26', '2026-05-26', 9);
