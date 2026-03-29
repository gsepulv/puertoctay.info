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

-- 12. CONFIGURACIÓN
CREATE TABLE IF NOT EXISTS configuracion (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    grupo       VARCHAR(50) NOT NULL,
    clave       VARCHAR(100) NOT NULL,
    valor       TEXT DEFAULT NULL,
    tipo        ENUM('text','textarea','email','url','color','number','boolean') NOT NULL DEFAULT 'text',
    etiqueta    VARCHAR(150) NOT NULL,
    orden       TINYINT UNSIGNED DEFAULT 0,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_grupo_clave (grupo, clave),
    INDEX idx_grupo (grupo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. PÁGINAS ESTÁTICAS
CREATE TABLE IF NOT EXISTS paginas (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo      VARCHAR(200) NOT NULL,
    slug        VARCHAR(200) NOT NULL UNIQUE,
    contenido   LONGTEXT DEFAULT NULL,
    meta_title  VARCHAR(200) DEFAULT NULL,
    meta_description VARCHAR(300) DEFAULT NULL,
    activo      TINYINT(1) NOT NULL DEFAULT 1,
    orden       TINYINT UNSIGNED DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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

-- Configuración general
INSERT INTO configuracion (grupo, clave, valor, tipo, etiqueta, orden) VALUES
('general', 'sitio_nombre',    'Visita Puerto Octay', 'text', 'Nombre del sitio', 1),
('general', 'sitio_tagline',   'Guía turística y directorio comercial de Puerto Octay', 'text', 'Eslogan', 2),
('general', 'sitio_email',     'contacto@puertoctay.info', 'email', 'Email de contacto', 3),
('general', 'sitio_telefono',  '', 'text', 'Teléfono de contacto', 4),
('general', 'sitio_direccion', 'Puerto Octay, Región de Los Lagos, Chile', 'text', 'Dirección', 5),
('general', 'analytics_id',   '', 'text', 'Google Analytics ID', 6);

-- SEO
INSERT INTO configuracion (grupo, clave, valor, tipo, etiqueta, orden) VALUES
('seo', 'meta_title',        'Visita Puerto Octay — Turismo y Comercio del Lago Llanquihue', 'text', 'Título global (meta title)', 1),
('seo', 'meta_description',  'Descubre Puerto Octay: guía de turismo, comercio, gastronomía y patrimonio a orillas del Lago Llanquihue.', 'textarea', 'Descripción global (meta description)', 2),
('seo', 'meta_keywords',     'Puerto Octay, turismo, lago Llanquihue, patrimonio, gastronomía', 'textarea', 'Palabras clave', 3),
('seo', 'og_image',          '', 'url', 'Imagen Open Graph por defecto', 4),
('seo', 'robots_txt',        'User-agent: *\nAllow: /\nSitemap: https://visitapuertoctay.cl/sitemap.xml', 'textarea', 'Contenido de robots.txt', 5),
('seo', 'head_scripts',      '', 'textarea', 'Scripts adicionales en <head>', 6),
('seo', 'body_scripts',      '', 'textarea', 'Scripts antes de </body>', 7);

-- Redes sociales
INSERT INTO configuracion (grupo, clave, valor, tipo, etiqueta, orden) VALUES
('social', 'facebook',  '', 'url', 'Facebook', 1),
('social', 'instagram', '', 'url', 'Instagram', 2),
('social', 'youtube',   '', 'url', 'YouTube', 3),
('social', 'tiktok',    '', 'url', 'TikTok', 4);

-- Páginas estáticas
INSERT INTO paginas (titulo, slug, contenido, meta_title, meta_description, activo, orden) VALUES
('Acerca de', 'acerca-de', '<h2>Sobre Visita Puerto Octay</h2>\n<p>Somos una plataforma dedicada a promover el turismo, comercio y patrimonio de Puerto Octay.</p>', 'Acerca de — Visita Puerto Octay', 'Conoce nuestra misión de promover Puerto Octay.', 1, 1),
('Términos de uso', 'terminos-de-uso', '<h2>Términos de uso</h2>\n<p>Al utilizar este sitio web, aceptas los siguientes términos y condiciones.</p>', 'Términos de uso — Visita Puerto Octay', 'Términos y condiciones de uso del sitio.', 1, 2),
('Política de privacidad', 'politica-de-privacidad', '<h2>Política de privacidad</h2>\n<p>Tu privacidad es importante para nosotros.</p>', 'Política de privacidad — Visita Puerto Octay', 'Política de privacidad del sitio.', 1, 3);
-- =============================================
-- NEGOCIOS (Businesses) for Puerto Octay
-- =============================================

-- GASTRONOMIA (4 restaurants)
INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, telefono, sitio_web, activo) VALUES
('rancho-espantapajaros', 'gastronomia', 'Rancho Espantapajaros', 'Restaurante familiar chileno-aleman con granja organica propia. Especialidad en carnes de jabali, cordero y preparaciones con productos de la huerta, rodeado de vistas al lago y volcanes.', 1, -40.96850000, -72.82200000, 'Camino a Puerto Octay Km 6, Puerto Octay', '+56652330049', 'https://espantapajaros.cl', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('restaurant-willhause', 'gastronomia', 'Restaurant WillHause', 'Acogedor restaurante de cocina alemana y chilena en pleno centro de Puerto Octay. Reconocido por su pork knuckle con chucrut, kuchen casero y ambiente hogareno.', 1, -40.97230000, -72.88350000, 'Independencia 659, esquina German Wulf, Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, telefono, activo) VALUES
('rincon-aleman', 'gastronomia', 'Rincon Aleman Restaurant y Cabanas', 'Restaurante y cabanas en el camino a Las Cascadas. Ofrece gastronomia tipica alemana-surena, tienda gourmet, souvenirs y artesanias en un entorno natural privilegiado.', 1, -40.98500000, -72.78300000, 'Ruta U-55 Km 68, camino a Las Cascadas, Puerto Octay', '+56996415365', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('el-fogon-de-anita', 'gastronomia', 'El Fogon de Anita', 'Parrilla y buffet campestre con carnes al palo de cordero, cerdo y vacuno. Terraza con vista panoramica al Lago Llanquihue y los volcanes Osorno, Calbuco y Puntiagudo.', 1, -40.97100000, -72.87800000, 'Sector rural, Puerto Octay', 1);

-- ALOJAMIENTO (3 accommodations - tipo comercio, categoria alojamiento=2)
INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, sitio_web, activo) VALUES
('hotel-haase', 'comercio', 'Hotel Haase', 'Hotel patrimonial fundado en 1914, declarado monumento arquitectonico. Ofrece 11 habitaciones con bano privado y compartido, restaurante de cocina regional y una historia que refleja la colonizacion alemana del lago.', 2, -40.97260000, -72.88400000, 'Pedro Montt 344, Puerto Octay', 'https://hotelhaase.cl', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('hostal-zapato-amarillo', 'comercio', 'Hostal Zapato Amarillo', 'Bed and Breakfast ecologico con techos verdes y arquitectura sustentable en la Ruta U-55. Habitaciones familiares y dormitorios compartidos, desayuno casero y jardines con vista al entorno natural.', 2, -40.97500000, -72.86500000, 'Ruta U-55 Km 2.5 La Gruta, Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('hostal-del-lago', 'comercio', 'Hostal del Lago', 'Hospedaje centrico con 18 habitaciones equipadas con cocina, a solo 450 metros de la Casa Niklitschek y 5 minutos a pie del Lago Llanquihue. Ideal para recorrer el pueblo y sus atractivos patrimoniales.', 2, -40.97300000, -72.88300000, 'Centro de Puerto Octay', 1);

-- ATRACTIVOS TURISTICOS (4 attractions)
INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('museo-el-colono', 'atractivo', 'Museo El Colono', 'Museo historico que conserva objetos de las familias colonas alemanas de fines del siglo XIX. Exhibe maquinaria agricola, herramientas, fotografias y utensilios en dos sedes: un galpon restaurado y la casa en calle Independencia.', 9, -40.97200000, -72.88450000, 'Independencia 591, segundo piso, Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('peninsula-centinela', 'atractivo', 'Peninsula Centinela y Playa La Baja', 'Peninsula boscosa a 3 km de Puerto Octay con playa de aguas calmas y transparentes en el Lago Llanquihue. Lugar ideal para bano, picnic y contemplacion de los volcanes Osorno y Calbuco.', 10, -40.97800000, -72.90200000, 'Camino a Frutillar Km 3, Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('iglesia-san-agustin', 'atractivo', 'Parroquia San Agustin', 'Iglesia patrimonial construida en 1908 integramente en madera, simbolo de la fusion cultural chilota-alemana. Su estructura conserva vestigios del terremoto de 1960 en uno de sus pilares interiores.', 9, -40.97280000, -72.88430000, 'Plaza de Armas, Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('salto-las-cascadas', 'atractivo', 'Salto Las Cascadas', 'Impresionante caida de agua de 50 metros en el Rio Blanco, accesible por un sendero de 30 minutos a traves de bosque nativo. Uno de los principales atractivos naturales de la comuna.', 10, -41.05000000, -72.63500000, 'Sector Las Cascadas, 35 km al sureste de Puerto Octay', 1);

-- COMERCIO Y SERVICIOS (2)
INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, activo) VALUES
('emporio-octay', 'comercio', 'Emporio Octay', 'Tienda de artesanias en lana y madera, decoracion, accesorios y cosmeticos naturales elaborados por artesanos locales. Un imperdible para llevar un recuerdo de Puerto Octay.', 4, -40.97250000, -72.88380000, 'Centro de Puerto Octay', 1);

INSERT INTO negocios (slug, tipo, nombre, descripcion_corta, categoria_id, lat, lng, direccion, sitio_web, activo) VALUES
('oficina-turismo-puerto-octay', 'servicio', 'Oficina de Turismo Puerto Octay', 'Punto de informacion turistica oficial de la Corporacion de Desarrollo Turistico. Mapas, recomendaciones de rutas, alojamientos y actividades en la comuna.', 6, -40.97270000, -72.88420000, 'Centro de Puerto Octay', 'https://corporacionpuertooctay.cl', 1);

-- =============================================
-- NOTICIAS (News articles)
-- =============================================
INSERT INTO noticias (slug, titulo, bajada, contenido, categoria_id, autor, estado, featured, publicado_en, tiempo_lectura) VALUES
('puerto-octay-zona-tipica-patrimonio-vivo',
'Puerto Octay: Zona Tipica y Patrimonio Vivo a Orillas del Lago Llanquihue',
'Declarada Zona Tipica en 2010, esta localidad conserva una arquitectura unica que fusiona tradiciones alemanas y chilotas.',
'<p>Puerto Octay, ubicada en la ribera norte del Lago Llanquihue, fue declarada Zona Tipica en 2010, reconociendo el valor de su patrimonio arquitectonico heredado de la colonizacion alemana que comenzo en 1852. Sus calles exhiben tres estilos constructivos distintivos: Primitivo, Neoclasico y Chalet, que reflejan las distintas etapas de desarrollo del pueblo.</p><p>Entre los edificios mas emblematicos destacan el Hotel Haase (1914), declarado monumento arquitectonico; la Parroquia San Agustin (1908), construida integramente en madera; y la Casa Niklitschek, que hoy forma parte del circuito patrimonial. El Museo El Colono preserva objetos, herramientas y fotografias de las primeras familias colonas.</p><p>Recorrer Puerto Octay es sumergirse en una historia viva donde la arquitectura de madera y zinc convive con vistas privilegiadas al lago y los volcanes Osorno, Calbuco y Puntiagudo. La Corporacion de Desarrollo Turistico trabaja activamente en la puesta en valor de estos atractivos para que visitantes de todo Chile y el mundo puedan disfrutar de este tesoro del sur.</p>',
16, 'Equipo Editorial', 'publicado', 1, NOW(), 3);

INSERT INTO noticias (slug, titulo, bajada, contenido, categoria_id, autor, estado, featured, publicado_en, tiempo_lectura) VALUES
('gastronomia-alemana-chilena-puerto-octay',
'Sabores del Sur: La Gastronomia Alemana-Chilena de Puerto Octay',
'Restaurantes locales rescatan recetas de la colonizacion alemana fusionadas con ingredientes del sur de Chile.',
'<p>La tradicion gastronomica de Puerto Octay es un reflejo directo de su historia. Los colonos alemanes que se asentaron en la zona a mediados del siglo XIX trajeron consigo recetas que, con el paso de las generaciones, se fusionaron con los ingredientes y tecnicas del sur de Chile, dando origen a una cocina unica.</p><p>Restaurantes como WillHause, en pleno centro del pueblo, ofrecen preparaciones clasicas como el pork knuckle con chucrut junto a kuchen caseros que mantienen viva la tradicion repostera alemana. El Rancho Espantapajaros, a pocos kilometros del centro, lleva esta fusion al siguiente nivel con su granja organica certificada, donde cultivan los ingredientes para sus platos de jabali, cordero y ensaladas de huerta.</p><p>Para quienes prefieren la experiencia campestre, El Fogon de Anita ofrece carnes al palo con vistas panoramicas al lago y los volcanes, mientras que Rincon Aleman combina restaurante, cabanas y tienda gourmet en el camino a Las Cascadas. Puerto Octay se consolida como un destino gastronomico imperdible en el Circuito Llanquihue.</p>',
15, 'Equipo Editorial', 'publicado', 0, NOW(), 3);

INSERT INTO noticias (slug, titulo, bajada, contenido, categoria_id, autor, estado, featured, publicado_en, tiempo_lectura) VALUES
('rutas-naturaleza-puerto-octay-lago-llanquihue',
'Tres Rutas de Naturaleza Imperdibles en Puerto Octay',
'Desde playas lacustres hasta cascadas de 50 metros, la comuna ofrece experiencias naturales para todos los gustos.',
'<p>Puerto Octay no es solo patrimonio arquitectonico. La comuna esconde algunos de los paisajes naturales mas espectaculares de la Region de Los Lagos, accesibles para visitantes de todas las edades y niveles de experiencia.</p><p>La primera parada obligada es la Peninsula Centinela, a solo 3 kilometros del centro. Su Playa La Baja ofrece aguas calmas y transparentes ideales para el bano, con una vista privilegiada a los volcanes. La segunda ruta lleva al Salto Las Cascadas, una impresionante caida de agua de 50 metros sobre el Rio Blanco, accesible tras una caminata de 30 minutos por bosque nativo, a unos 35 kilometros al sureste del pueblo.</p><p>La tercera opcion es el mirador de acceso a Puerto Octay, ubicado en la ruta desde Osorno. Desde este punto elevado se contempla el pueblo completo con el Lago Llanquihue de fondo y la cadena volcanica en el horizonte. Estos tres paseos, combinados con la oferta gastronomica y patrimonial del pueblo, convierten a Puerto Octay en un destino completo para una escapada de fin de semana en el sur de Chile.</p>',
13, 'Equipo Editorial', 'publicado', 0, NOW(), 4);
