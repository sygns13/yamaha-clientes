-- ============================================================================
-- Script: agregar documento de identidad a la tabla `clientes`
-- Equivalente directo en MySQL de las migraciones:
--   2026_06_24_000000_add_documento_to_clientes_table.php
--   2026_06_24_000001_add_documento_unique_to_clientes_table.php
--
-- Ejecutar sobre la base de datos de la aplicación, p. ej.:
--   mysql -u USUARIO -p NOMBRE_BD < 2026_06_24_add_documento_to_clientes.sql
-- ============================================================================

-- 1) Nuevas columnas (nullable: la tabla la comparten VIN1 y VIN2)
ALTER TABLE `clientes`
    ADD COLUMN `tipo_documento`   VARCHAR(255) NULL AFTER `id`,
    ADD COLUMN `numero_documento` VARCHAR(20)  NULL AFTER `tipo_documento`;

-- 2) Índice único compuesto para evitar clientes duplicados por documento.
--    En MySQL los valores NULL se consideran distintos entre sí, por lo que los
--    registros existentes (sin documento) no generan conflicto.
ALTER TABLE `clientes`
    ADD UNIQUE INDEX `clientes_documento_unique` (`tipo_documento`, `numero_documento`);
