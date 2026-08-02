-- ============================================================
-- Migración 015: Quitar el "tiempo de duración" y la mención a
-- "(Para la Plataforma / Discord)" de las descripciones.
-- Aplica: lección 1004, 1005 y actividad 1092.
-- Fecha: 2026-08-02
-- ============================================================

UPDATE lecciones
SET descripcion = REPLACE(REPLACE(descripcion, "\nDuración: 90 minutos (1h 30m)\n", "\n"), "\n\n\n", "\n\n")
WHERE id = 1004;

UPDATE lecciones
SET descripcion = REPLACE(REPLACE(
    REPLACE(descripcion, "\nDuración estimada: 12 a 15 minutos\n", "\n"),
    " (Para la Plataforma / Discord)", ""),
    "\n\n\n", "\n\n")
WHERE id = 1005;

UPDATE actividades
SET descripcion = REPLACE(descripcion, "Prueba la herramienta y reporta en Discord.", "Prueba la herramienta y reporta evidencias.")
WHERE id = 1092;