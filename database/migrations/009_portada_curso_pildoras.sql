-- ============================================================
-- Migración 009: Portada del curso "Píldoras de Conocimiento"
-- (curso_id = 2). Imagen: img/portada_curso_pildoras.png
-- Fecha: 2026-08-01
-- ============================================================

UPDATE cursos SET imagen = 'img/portada_curso_pildoras.png' WHERE id = 2;
