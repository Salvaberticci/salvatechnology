-- ============================================================
-- Migración 012: Nueva consigna de la Actividad de la Clase 1.1
-- "Análisis Anatómico de Dato, Información y Sistema"
-- (dominio real, 3 datos crudos, contexto→información, regla+acción)
-- Fecha: 2026-08-02
-- ============================================================

UPDATE actividades
SET titulo = 'Act: Análisis Anatómico de Dato, Información y Sistema',
    descripcion = 'Análisis Anatómico de Dato, Información y Sistema.

Tu misión para esta lección consiste en:

1. Seleccionar un dominio de negocio de la vida real (por ejemplo: un sistema de reservas de vuelos, una plataforma de delivery de comida o una red social).

2. Identificar y documentar 3 Datos crudos que circulen en esa plataforma.

3. Explicar cómo el software envuelve esos 3 datos en un marco de contexto para transformarlos en Información valiosa.

4. Describir la Regla de Negocio y la Acción Automatizada que ejecuta el Sistema al procesar esa información.'
WHERE id = 772;