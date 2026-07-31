-- ============================================================
-- Migración 003: Portada del curso "Desarrollo de Software
-- con Inteligencia Artificial" (curso id = 1)
-- Imagen: img/portada_curso_ia.png
-- Fecha: 2026-07-31
-- ============================================================

UPDATE cursos
SET imagen = 'img/portada_curso_ia.png'
WHERE id = 1 AND (imagen IS NULL OR imagen = '');
