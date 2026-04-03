-- Migration 007: Unify propietarios into usuarios
-- Date: 2026-04-03
-- Purpose: Drop the incorrect FK negocios.propietario_id -> propietarios(id)
--          and migrate propietario records into usuarios table so propietario_id
--          correctly references usuarios(id) going forward.

-- STEP 1: Migrate propietario id=2 (gsepulv@gmail.com) into usuarios.
-- This email did not exist in usuarios (was previously deleted editor account).
-- We insert with explicit id=2 to match the original propietarios.id, rol=comerciante, activo=0.
-- NOTE: If this row already exists (re-running), skip or use INSERT IGNORE.
INSERT IGNORE INTO usuarios (id, nombre, email, telefono, password_hash, rol, activo, created_at)
VALUES (
    2,
    'Gustavo Sepúlveda',
    'gsepulv@gmail.com',
    '+56958199804',
    'y0$.za1ihe7kg3tqxJnk/nZn.fKzoaDvx1B.YxanMcGJBxsnHK48sxYy',
    'comerciante',
    0,
    '2026-03-29 16:51:22'
);
-- propietario id=4 (gsepulv@outlook.es) already had matching usuario id=4, no insert needed.

-- STEP 2: Drop the incorrect FK constraint (propietario_id -> propietarios.id)
ALTER TABLE negocios DROP FOREIGN KEY negocios_ibfk_3;

-- STEP 3: No negocios.propietario_id updates needed.
-- negocio id=21 has propietario_id=4 which already maps to usuarios id=4 (same email).
-- No negocios had propietario_id=2, so nothing to remap there.

-- NOTE: We intentionally do NOT add a new FK from negocios.propietario_id -> usuarios(id)
-- because the propietarios table still exists and the codebase is being migrated incrementally.
-- Add the FK in a later migration once propietarios table is fully retired.

-- Verification queries (run manually to confirm):
-- SELECT id, nombre, email, rol, activo FROM usuarios ORDER BY id;
-- SELECT id, nombre, propietario_id, status FROM negocios WHERE propietario_id IS NOT NULL;
-- SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME
--   FROM information_schema.KEY_COLUMN_USAGE
--   WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='negocios' AND REFERENCED_TABLE_NAME IS NOT NULL;
