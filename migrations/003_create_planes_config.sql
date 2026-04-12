-- Migración: planes_config
-- Fecha: 2026-04-04
-- Descripción: Tabla de configuración de planes comerciales (5 niveles)
-- NOTA: Independiente de la tabla 'planes' existente

CREATE TABLE IF NOT EXISTS planes_config (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    slug VARCHAR(50) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    icono VARCHAR(10) DEFAULT NULL,
    color VARCHAR(7) NOT NULL DEFAULT '#6b7280',
    orden TINYINT UNSIGNED NOT NULL DEFAULT 1,
    descripcion TEXT NOT NULL,
    precio_intro INT UNSIGNED NOT NULL DEFAULT 0,
    precio_regular INT UNSIGNED NOT NULL DEFAULT 0,
    duracion_dias INT UNSIGNED NOT NULL DEFAULT 30,
    max_fotos INT UNSIGNED DEFAULT NULL,
    max_redes INT UNSIGNED DEFAULT NULL,
    cupos_globales INT UNSIGNED DEFAULT NULL,
    max_cupos_categoria INT UNSIGNED DEFAULT NULL,
    posicion_listado ENUM('normal','prioritaria','siempre_primero') NOT NULL DEFAULT 'normal',
    tiene_mapa TINYINT(1) NOT NULL DEFAULT 0,
    tiene_horarios TINYINT(1) NOT NULL DEFAULT 0,
    tiene_sello TINYINT(1) NOT NULL DEFAULT 0,
    tiene_reporte TINYINT(1) NOT NULL DEFAULT 0,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed idempotente
INSERT INTO planes_config (slug, nombre, icono, color, orden, descripcion, precio_intro, precio_regular, duracion_dias, max_fotos, max_redes, cupos_globales, max_cupos_categoria, posicion_listado, tiene_mapa, tiene_horarios, tiene_sello, tiene_reporte, activo)
VALUES
('freemium','Freemium','🆓','#6b7280',1,'Publica tu negocio gratis en el directorio digital de Puerto Octay. Incluye página propia, logo y botón de WhatsApp.',0,0,30,1,1,NULL,NULL,'normal',0,0,0,0,1),
('basico','Básico','⭐','#38A169',2,'Mayor visibilidad para tu negocio. Más fotos, todas tus redes sociales, mapa integrado y horarios de atención.',2990,4990,30,5,99,NULL,NULL,'prioritaria',1,1,0,0,1),
('premium','Premium','💎','#805AD5',3,'Máxima visibilidad. Tu comercio aparece primero en todas las búsquedas, con sello verificado, galería completa y reporte mensual de visitas.',5990,9990,30,10,99,NULL,NULL,'siempre_primero',1,1,1,1,1),
('sponsor','Sponsor','👑','#d69e2e',4,'El plan más exclusivo. Tu comercio aparece destacado en el home, en todas las categorías relacionadas y en fechas especiales. Incluye galería ilimitada y soporte prioritario.',9990,14990,30,NULL,99,5,2,'siempre_primero',1,1,1,1,1),
('banner','Banner','📢','#E53E3E',5,'Espacio publicitario premium. Tu banner aparece en el home y páginas principales del directorio. Ideal para promociones, lanzamientos y campañas estacionales. Incluye todos los beneficios Sponsor.',14990,24990,30,NULL,99,3,1,'siempre_primero',1,1,1,1,1)
ON DUPLICATE KEY UPDATE
    nombre=VALUES(nombre), icono=VALUES(icono), color=VALUES(color), orden=VALUES(orden),
    descripcion=VALUES(descripcion), precio_intro=VALUES(precio_intro), precio_regular=VALUES(precio_regular),
    duracion_dias=VALUES(duracion_dias), max_fotos=VALUES(max_fotos), max_redes=VALUES(max_redes),
    cupos_globales=VALUES(cupos_globales), max_cupos_categoria=VALUES(max_cupos_categoria),
    posicion_listado=VALUES(posicion_listado), tiene_mapa=VALUES(tiene_mapa), tiene_horarios=VALUES(tiene_horarios),
    tiene_sello=VALUES(tiene_sello), tiene_reporte=VALUES(tiene_reporte), activo=VALUES(activo);
