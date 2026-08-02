-- ============================================================
-- Migración 010: Reemplaza Cursor y v0.dev por OpenCode
-- en las lecciones y actividades del Curso 1 (Vibe Coding / ADD)
-- Fecha: 2026-08-02
-- ============================================================

-- Semana 9: lección principal
UPDATE lecciones
SET titulo = REPLACE(titulo, 'Dominando Cursor, Claude y Motores de Generación', 'Dominando OpenCode y Motores de Generación')
WHERE curso_id = 1 AND titulo LIKE '%Dominando Cursor%';

UPDATE lecciones
SET descripcion = 'Configurar OpenCode para programar a velocidad profesional mediante Vibe Coding.'
WHERE curso_id = 1 AND titulo LIKE 'Clase 9:%';

-- Semana 9.1 Instalación de Cursor IDE
UPDATE lecciones SET titulo = 'Clase 9.1: Instalación de OpenCode'
WHERE curso_id = 1 AND titulo = 'Clase 9.1: Instalación de Cursor IDE';

-- Semana 9.2 Archivos .cursorrules
UPDATE lecciones SET titulo = REPLACE(titulo, 'Archivos .cursorrules', 'Archivo AGENTS.md')
WHERE curso_id = 1 AND titulo LIKE '%Archivos .cursorrules%';

-- Semana 9.3 Generación de UI con v0.dev
UPDATE lecciones SET titulo = REPLACE(titulo, 'Generación de UI con v0.dev', 'Generación de UI con OpenCode')
WHERE curso_id = 1 AND titulo LIKE '%Generación de UI con v0.dev%';

-- Actividades de la semana 9
UPDATE actividades SET descripcion = 'Repo público con AGENTS.md + maqueta base generada con OpenCode.'
WHERE titulo = 'Quest: Configuración de Entorno AI';

UPDATE actividades SET titulo = 'Act: Configurar OpenCode'
WHERE titulo = 'Act: Configurar Cursor';

UPDATE actividades SET descripcion = 'Instala OpenCode, enlaza los proveedores de IA y personaliza atajos.'
WHERE titulo = 'Act: Configurar OpenCode' AND descripcion LIKE '%Instala Cursor%';

UPDATE actividades SET titulo = 'Act: Escribir AGENTS.md'
WHERE titulo = 'Act: Escribir .cursorrules';

UPDATE actividades SET descripcion = 'Crea un AGENTS.md con stack, convenciones y estilo.'
WHERE titulo = 'Act: Escribir AGENTS.md' AND descripcion LIKE '%.cursorrules%';

-- Semana 12: Depurar Stack Trace con Cursor
UPDATE actividades SET descripcion = 'Provoca 2 errores y corrígelos con OpenCode.'
WHERE descripcion LIKE '%corrígelos con Cursor%';

-- Semana 13: Configurar proyecto base con Cursor
UPDATE actividades SET descripcion = 'Configura Tauri/Electron/Python con OpenCode.'
WHERE descripcion LIKE '%Python con Cursor%';

-- Limpieza genérica por si queda alguna mención residual
UPDATE lecciones SET titulo = REPLACE(titulo, 'Cursor', 'OpenCode'), titulo = REPLACE(titulo, 'v0.dev', 'OpenCode')
WHERE curso_id = 1;