-- ============================================================
-- Migración 007: Eliminar lecciones placeholder de
-- "Píldoras de Conocimiento" (curso_id = 2)
-- Se eliminan las lecciones demo ids 6-10; solo quedan las
-- clases reales agregadas (migraciones 004, 005, 006).
-- Fecha: 2026-07-31
-- ============================================================

DELETE FROM progreso_lecciones WHERE leccion_id IN (6, 7, 8, 9, 10);
DELETE FROM actividades WHERE leccion_id IN (6, 7, 8, 9, 10);
DELETE FROM lecciones WHERE id IN (6, 7, 8, 9, 10) AND curso_id = 2;
