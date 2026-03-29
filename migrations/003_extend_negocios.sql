-- Migration 003: Extend negocios table with social media, billing, SEO fields
-- Date: 2026-03-29
-- Applied via PHP script

-- Social media (specific fields instead of generic red_social_1/2)
ALTER TABLE negocios ADD COLUMN facebook VARCHAR(255) NULL AFTER red_social_2;
ALTER TABLE negocios ADD COLUMN instagram VARCHAR(255) NULL AFTER facebook;
ALTER TABLE negocios ADD COLUMN tiktok VARCHAR(255) NULL AFTER instagram;
ALTER TABLE negocios ADD COLUMN youtube VARCHAR(255) NULL AFTER tiktok;
ALTER TABLE negocios ADD COLUMN twitter VARCHAR(255) NULL AFTER youtube;
ALTER TABLE negocios ADD COLUMN linkedin VARCHAR(255) NULL AFTER twitter;
ALTER TABLE negocios ADD COLUMN telegram VARCHAR(255) NULL AFTER linkedin;
ALTER TABLE negocios ADD COLUMN pinterest VARCHAR(255) NULL AFTER telegram;

-- Cover image separate from main photo
ALTER TABLE negocios ADD COLUMN portada VARCHAR(255) NULL AFTER foto_principal;

-- Highlighted/featured flag (different from verificado)
ALTER TABLE negocios ADD COLUMN destacado TINYINT(1) NOT NULL DEFAULT 0 AFTER verificado;

-- SEO
ALTER TABLE negocios ADD COLUMN meta_title VARCHAR(255) NULL AFTER propietario_id;
ALTER TABLE negocios ADD COLUMN meta_description TEXT NULL AFTER meta_title;

-- Billing/invoicing data
ALTER TABLE negocios ADD COLUMN razon_social VARCHAR(255) NULL AFTER meta_description;
ALTER TABLE negocios ADD COLUMN rut_empresa VARCHAR(20) NULL AFTER razon_social;
ALTER TABLE negocios ADD COLUMN giro_comercial VARCHAR(255) NULL AFTER rut_empresa;
ALTER TABLE negocios ADD COLUMN direccion_tributaria VARCHAR(255) NULL AFTER giro_comercial;
ALTER TABLE negocios ADD COLUMN comuna_tributaria VARCHAR(100) NULL AFTER direccion_tributaria;
ALTER TABLE negocios ADD COLUMN nombre_propietario VARCHAR(255) NULL AFTER comuna_tributaria;
ALTER TABLE negocios ADD COLUMN rut_propietario VARCHAR(20) NULL AFTER nombre_propietario;
ALTER TABLE negocios ADD COLUMN telefono_privado VARCHAR(20) NULL AFTER rut_propietario;
ALTER TABLE negocios ADD COLUMN email_facturacion VARCHAR(255) NULL AFTER telefono_privado;
ALTER TABLE negocios ADD COLUMN fecha_inicio_contrato DATE NULL AFTER email_facturacion;
ALTER TABLE negocios ADD COLUMN monto_mensual INT NOT NULL DEFAULT 0 AFTER fecha_inicio_contrato;
ALTER TABLE negocios ADD COLUMN metodo_pago VARCHAR(50) NULL AFTER monto_mensual;

-- Widen existing fields
ALTER TABLE negocios MODIFY COLUMN horario TEXT NULL;
ALTER TABLE negocios MODIFY COLUMN email VARCHAR(255) NULL;