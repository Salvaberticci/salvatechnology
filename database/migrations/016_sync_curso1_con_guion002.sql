-- 016_sync_curso1_con_guion002.sql
-- Alinea Curso 1 con los cambios de stack reales del MD 002 (fuente de verdad):
--   * Desktop pasa de Electron/Tauri/Python a Python puro.
--   * Mobile pasa de Expo a Flutter.
--   * Se agrega (Home + Tabs) a Clase 15.2 y se unifica el título de Clase 1
--     con el de la diapositiva clase-1.html ("FUNDAMENTOS").
-- Solo contenidos/títulos cuyo significado cambió; no se acortan títulos descriptivos.

-- Clase 1: fuente MD 002 + diapositiva clase-1.html ("CAPÍTULO 1 // FUNDAMENTOS")
UPDATE lecciones SET titulo = 'Clase 1: Fundamentos Base de la Ingeniería de Software — Anatomía de los Sistemas Modernos y la Metodología ADD'
WHERE id = 1004;

-- Clase 13 (Desktop): Electron/Tauri/Python -> Python puro
UPDATE lecciones SET titulo = 'Clase 13: Proyecto Desktop: Configuración y Lógica — Apps de Escritorio con Python e IA'
WHERE id = 1064;

-- Actividad 13.1 (Configurar Proyecto Base). antes: "Configura Tauri/Electron/Python con OpenCode."
UPDATE actividades SET descripcion = 'Configura el proyecto desktop con OpenCode, usando Python e integración SQLite.'
WHERE id = 1052;

-- Clase 15 (Mobile): Expo/Flutter -> Flutter
UPDATE lecciones SET titulo = 'Clase 15: Proyecto Mobile: Configuración y Vistas — Desarrollo Móvil Asistido por IA (Flutter)'
WHERE id = 1074;

-- Clase 15.1: Expo -> Flutter
UPDATE lecciones SET titulo = 'Clase 15.1: Configurar Flutter'
WHERE id = 1075;

-- Clase 15.2: agrega el detalle (Home + Tabs)
UPDATE lecciones SET titulo = 'Clase 15.2: Pantallas con IA (Home + Tabs)'
WHERE id = 1076;

-- Actividad 15.1 (Entorno Móvil). antes: "Configura Expo Go en tu teléfono."
UPDATE actividades SET descripcion = 'Configura Flutter en tu teléfono/emulador y ejecuta la app base.'
WHERE id = 1062;
