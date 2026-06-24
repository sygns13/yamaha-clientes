-- ============================================================================
-- Script: agregar autorización de fines promocionales a la tabla `clientes`
-- Equivalente directo en MySQL de la migración:
--   2026_06_24_000002_add_acepta_promociones_to_clientes_table.php
--
-- Ejecutar sobre la base de datos de la aplicación, p. ej.:
--   mysql -u USUARIO -p NOMBRE_BD < 2026_06_24_add_acepta_promociones_to_clientes.sql
-- ============================================================================

-- Consentimiento (opcional) para fines promocionales. La aceptación de la
-- Política de Privacidad es obligatoria pero solo actúa como gate y no se guarda.
ALTER TABLE `clientes`
    ADD COLUMN `acepta_promociones` TINYINT(1) NOT NULL DEFAULT 0 AFTER `correo`;
