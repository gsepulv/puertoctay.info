-- Migration: Fix tildes/acentos in all data
-- Date: 2026-03-29
-- Applied via PHP script (fix_tildes.php)

-- Categorías directorio
UPDATE categorias SET nombre = 'Gastronomía' WHERE id = 1;
UPDATE categorias SET nombre = 'Artesanías' WHERE id = 4;
UPDATE categorias SET nombre = 'Deportes y Recreación' WHERE id = 11;
UPDATE categorias SET nombre = 'Educación y Cultura' WHERE id = 12;

-- Categorías editorial
UPDATE categorias SET nombre = 'Gastronomía' WHERE id = 15;

-- Negocios (nombres)
UPDATE negocios SET nombre = 'Rancho Espantapájaros' WHERE id = 1;
UPDATE negocios SET nombre = 'Rincón Alemán Restaurant y Cabañas' WHERE id = 3;
UPDATE negocios SET nombre = 'El Fogón de Anita' WHERE id = 4;
UPDATE negocios SET nombre = 'Península Centinela y Playa La Baja' WHERE id = 9;
UPDATE negocios SET nombre = 'Parroquia San Agustín' WHERE id = 10;

-- Negocios (descripciones)
UPDATE negocios SET descripcion_corta = 'Restaurante familiar chileno-alemán con platos típicos y ambiente acogedor.' WHERE id = 1;
UPDATE negocios SET descripcion_corta = 'Acogedor restaurante de cocina alemana y chilena frente al lago.' WHERE id = 2;
UPDATE negocios SET descripcion_corta = 'Restaurante y cabañas en el campo, cocina típica del sur de Chile.' WHERE id = 3;
UPDATE negocios SET descripcion_corta = 'Fogón tradicional con parrilladas y cocina chilena casera.' WHERE id = 4;
UPDATE negocios SET descripcion_corta = 'Hotel histórico con arquitectura colonial alemana junto al lago.' WHERE id = 5;
UPDATE negocios SET descripcion_corta = 'Hostal acogedor y económico en el centro de Puerto Octay.' WHERE id = 6;
UPDATE negocios SET descripcion_corta = 'Hostal con vista al Lago Llanquihue y atención personalizada.' WHERE id = 7;
UPDATE negocios SET descripcion_corta = 'Museo de la colonización alemana con piezas históricas únicas.' WHERE id = 8;
UPDATE negocios SET descripcion_corta = 'Península con playas, senderos y vistas al volcán Osorno.' WHERE id = 9;
UPDATE negocios SET descripcion_corta = 'Iglesia patrimonial de arquitectura alemana del siglo XIX.' WHERE id = 10;
UPDATE negocios SET descripcion_corta = 'Cascada natural rodeada de bosque nativo, ideal para trekking.' WHERE id = 11;
UPDATE negocios SET descripcion_corta = 'Tienda de artesanías locales y productos típicos de la zona.' WHERE id = 12;
UPDATE negocios SET descripcion_corta = 'Información turística oficial de la Municipalidad de Puerto Octay.' WHERE id = 13;

-- Noticias
UPDATE noticias SET titulo = 'Puerto Octay: Zona Típica y Patrimonio Vivo a Orillas del Lago Llanquihue' WHERE id = 1;
UPDATE noticias SET titulo = 'Sabores del Sur: La Gastronomía Alemana-Chilena de Puerto Octay' WHERE id = 2;

-- Planes
UPDATE planes SET nombre = 'Básico' WHERE id = 1;
