-- =====================================================
-- puertoctay.info — Schema de Base de Datos
-- Idéntico para producción y local
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. CATEGORÍAS
CREATE TABLE IF NOT EXISTS categorias (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    emoji       VARCHAR(10) DEFAULT NULL,
    descripcion VARCHAR(300) DEFAULT NULL,
    tipo        ENUM('directorio','editorial') NOT NULL DEFAULT 'directorio',
    orden       TINYINT UNSIGNED DEFAULT 0,
    activo      TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. PLANES
CREATE TABLE IF NOT EXISTS planes (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(50) NOT NULL,
    slug            VARCHAR(50) NOT NULL UNIQUE,
    precio          INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'CLP mensual',
    max_fotos       TINYINT UNSIGNED NOT NULL DEFAULT 1,
    prioridad       TINYINT UNSIGNED NOT NULL DEFAULT 0,
    badge           TINYINT(1) NOT NULL DEFAULT 0,
    estadisticas    TINYINT(1) NOT NULL DEFAULT 0,
    noticia_mensual TINYINT(1) NOT NULL DEFAULT 0,
    banner_portada  TINYINT(1) NOT NULL DEFAULT 0,
    max_cupos       INT DEFAULT NULL COMMENT 'NULL = ilimitado',
    activo          TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PROPIETARIOS
CREATE TABLE IF NOT EXISTS propietarios (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    telefono        VARCHAR(20) DEFAULT NULL,
    activo          TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. NEGOCIOS
CREATE TABLE IF NOT EXISTS negocios (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug              VARCHAR(180) NOT NULL UNIQUE,
    tipo              ENUM('comercio','atractivo','servicio','gastronomia') NOT NULL DEFAULT 'comercio',
    nombre            VARCHAR(150) NOT NULL,
    descripcion_corta VARCHAR(300) DEFAULT NULL,
    descripcion_larga TEXT DEFAULT NULL,
    categoria_id      INT UNSIGNED DEFAULT NULL,
    lat               DECIMAL(10,8) DEFAULT NULL,
    lng               DECIMAL(11,8) DEFAULT NULL,
    direccion         VARCHAR(200) DEFAULT NULL,
    como_llegar       TEXT DEFAULT NULL,
    telefono          VARCHAR(20) DEFAULT NULL,
    whatsapp          VARCHAR(20) DEFAULT NULL,
    email             VARCHAR(150) DEFAULT NULL,
    sitio_web         VARCHAR(255) DEFAULT NULL,
    red_social_1      VARCHAR(255) DEFAULT NULL,
    red_social_2      VARCHAR(255) DEFAULT NULL,
    foto_principal    VARCHAR(255) DEFAULT NULL,
    logo              VARCHAR(255) DEFAULT NULL,
    galeria           JSON DEFAULT NULL,
    horario           VARCHAR(200) DEFAULT NULL,
    precio_referencial VARCHAR(100) DEFAULT NULL,
    plan_id           INT UNSIGNED DEFAULT 1,
    verificado        TINYINT(1) NOT NULL DEFAULT 0,
    activo            TINYINT(1) NOT NULL DEFAULT 1,
    visitas           INT UNSIGNED NOT NULL DEFAULT 0,
    propietario_id    INT UNSIGNED DEFAULT NULL,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo (tipo),
    INDEX idx_categoria (categoria_id),
    INDEX idx_activo (activo),
    INDEX idx_plan (plan_id),
    INDEX idx_verificado (verificado),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (plan_id) REFERENCES planes(id) ON DELETE SET NULL,
    FOREIGN KEY (propietario_id) REFERENCES propietarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. NOTICIAS
CREATE TABLE IF NOT EXISTS noticias (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(200) NOT NULL UNIQUE,
    titulo          VARCHAR(200) NOT NULL,
    bajada          VARCHAR(350) DEFAULT NULL,
    contenido       LONGTEXT DEFAULT NULL,
    foto_destacada  VARCHAR(255) DEFAULT NULL,
    categoria_id    INT UNSIGNED DEFAULT NULL,
    autor           VARCHAR(100) DEFAULT NULL,
    tiempo_lectura  TINYINT UNSIGNED DEFAULT NULL,
    estado          ENUM('borrador','revision','publicado','archivado') NOT NULL DEFAULT 'borrador',
    featured        TINYINT(1) NOT NULL DEFAULT 0,
    publicado_en    DATETIME DEFAULT NULL,
    schema_type     VARCHAR(50) DEFAULT 'NewsArticle',
    visitas         INT UNSIGNED NOT NULL DEFAULT 0,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_estado (estado),
    INDEX idx_publicado (publicado_en),
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. EVENTOS
CREATE TABLE IF NOT EXISTS eventos (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(180) NOT NULL UNIQUE,
    nombre          VARCHAR(200) NOT NULL,
    descripcion     TEXT DEFAULT NULL,
    foto            VARCHAR(255) DEFAULT NULL,
    fecha_inicio    DATE NOT NULL,
    fecha_fin       DATE DEFAULT NULL,
    hora            VARCHAR(50) DEFAULT NULL,
    lugar           VARCHAR(200) DEFAULT NULL,
    lat             DECIMAL(10,8) DEFAULT NULL,
    lng             DECIMAL(11,8) DEFAULT NULL,
    precio          VARCHAR(100) DEFAULT 'Gratuito',
    organizador     VARCHAR(150) DEFAULT NULL,
    estado          ENUM('borrador','publicado','finalizado') NOT NULL DEFAULT 'borrador',
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_fecha (fecha_inicio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. RESEÑAS
CREATE TABLE IF NOT EXISTS resenas (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    negocio_id      INT UNSIGNED NOT NULL,
    nombre_autor    VARCHAR(100) NOT NULL,
    email_autor     VARCHAR(150) DEFAULT NULL,
    puntuacion      TINYINT UNSIGNED NOT NULL,
    comentario      TEXT DEFAULT NULL,
    estado          ENUM('pendiente','aprobada','rechazada') NOT NULL DEFAULT 'pendiente',
    ip_address      VARCHAR(45) DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_negocio (negocio_id),
    INDEX idx_estado (estado),
    FOREIGN KEY (negocio_id) REFERENCES negocios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. USUARIOS
CREATE TABLE IF NOT EXISTS usuarios (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    rol             ENUM('admin','editor','moderador') NOT NULL DEFAULT 'editor',
    activo          TINYINT(1) NOT NULL DEFAULT 1,
    ultimo_login    DATETIME DEFAULT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. RATE LIMITS
CREATE TABLE IF NOT EXISTS rate_limits (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address   VARCHAR(45) NOT NULL,
    endpoint     VARCHAR(200) NOT NULL,
    hits         INT UNSIGNED NOT NULL DEFAULT 1,
    window_start DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_ip_endpoint (ip_address, endpoint),
    INDEX idx_window (window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. AUDIT LOG
CREATE TABLE IF NOT EXISTS audit_log (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT UNSIGNED DEFAULT NULL,
    accion      VARCHAR(100) NOT NULL,
    entidad     VARCHAR(50) DEFAULT NULL,
    entidad_id  INT UNSIGNED DEFAULT NULL,
    detalle     TEXT DEFAULT NULL,
    ip_address  VARCHAR(45) DEFAULT NULL,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_usuario (usuario_id),
    INDEX idx_accion (accion),
    INDEX idx_fecha (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. CACHE DE PÁGINAS
CREATE TABLE IF NOT EXISTS page_cache (
    cache_key   VARCHAR(200) PRIMARY KEY,
    content     LONGTEXT NOT NULL,
    expires_at  DATETIME NOT NULL,
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- DATOS SEMILLA
-- =====================================================

INSERT INTO planes (nombre, slug, precio, max_fotos, prioridad, badge, estadisticas, noticia_mensual, banner_portada, max_cupos) VALUES
('Basico',    'basico',    0,     1,  0, 0, 0, 0, 0, NULL),
('Destacado', 'destacado', 9990,  5,  1, 1, 1, 0, 0, NULL),
('Premium',   'premium',   19990, 15, 2, 1, 1, 1, 0, NULL),
('Sponsor',   'sponsor',   35000, 30, 3, 1, 1, 1, 1, 5);

INSERT INTO categorias (nombre, slug, emoji, tipo, orden) VALUES
('Gastronomia',           'gastronomia',         '🍽️', 'directorio', 1),
('Alojamiento',           'alojamiento',         '🏨', 'directorio', 2),
('Turismo Aventura',      'turismo-aventura',    '🏔️', 'directorio', 3),
('Artesanias',            'artesanias',          '🎨', 'directorio', 4),
('Transporte',            'transporte',          '🚌', 'directorio', 5),
('Servicios',             'servicios',           'ℹ️',  'directorio', 6),
('Comercio Local',        'comercio-local',      '🛍️', 'directorio', 7),
('Belleza y Bienestar',   'belleza-bienestar',   '💆', 'directorio', 8),
('Patrimonio',            'patrimonio',          '🏛️', 'directorio', 9),
('Naturaleza',            'naturaleza',          '🌿', 'directorio', 10),
('Deportes y Recreacion', 'deportes-recreacion', '⛵', 'directorio', 11),
('Educacion y Cultura',   'educacion-cultura',   '📚', 'directorio', 12);

INSERT INTO categorias (nombre, slug, emoji, tipo, orden) VALUES
('Turismo',        'noticias-turismo',        '✈️',  'editorial', 1),
('Comercio',       'noticias-comercio',       '💼', 'editorial', 2),
('Gastronomia',    'noticias-gastronomia',    '🍳', 'editorial', 3),
('Patrimonio',     'noticias-patrimonio',     '🏛️', 'editorial', 4),
('Medioambiente',  'noticias-medioambiente',  '🌍', 'editorial', 5),
('Eventos',        'noticias-eventos',        '🎉', 'editorial', 6),
('Comunidad',      'noticias-comunidad',      '🤝', 'editorial', 7),
('Emprendimiento', 'noticias-emprendimiento', '🚀', 'editorial', 8);

INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES
('Gustavo Sepulveda', 'contacto@purranque.info',
 '$2y$10$xLRsYP9rKq1nX5YQzK3CDeVpmFGjU8RH6/AUfB6q5x1x6xMjKvGy', 'admin');
