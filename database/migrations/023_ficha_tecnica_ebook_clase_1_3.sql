-- ============================================================
-- Migración 023: Ficha técnica (descripción) de Clase 1.2 y 1.3,
-- título oficial y Actividad 1.3 según el guion "Clase 1.3:
-- Gestión de Estado, Límites de Seguridad y la Física de
-- RAM vs Persistencia".
-- Idempotente, UPDATEs condicionales por título/estado.
-- Fecha: 2026-08-07
-- ============================================================

-- 1) FICHA TÉCNICA clase 1.2 (solo si está en la versión corta anterior)
UPDATE lecciones
SET descripcion = 'Modelo Cliente-Servidor: Frontend (presentación y captura de eventos), Backend (reglas de negocio, seguridad y algoritmo pesado) y Base de Datos (persistencia y memoria histórica); viaje de la petición HTTP y su respuesta; regla de oro de la arquitectura: nunca confiar en el Frontend; validación, del renderizado a la transacción en el servidor.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.2[[:space:]]*:'
  AND CHAR_LENGTH(descripcion) < 160;

-- 2) TÍTULO + FICHA TÉCNICA clase 1.3 según el guion magistral
UPDATE lecciones
SET titulo = 'Clase 1.3: Gestión de Estado, Límites de Seguridad y la Física de RAM vs Persistencia'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.3[[:space:]]*:[[:space:]]*Backend'

UPDATE lecciones
SET descripcion = 'Frontera de seguridad: Zona No Confiable (Frontend, bajo control del usuario) vs Zona de Confianza (Backend, detrás del firewall); la física de la memoria: nanosegundos de la RAM volátil vs milisegundos de la I/O de disco persistente y los mecanismos ACID/WAL; la arquitectura de estado: Estado Efímero del Cliente, Estado en Red y Fuente de la Verdad en Base de Datos; caso Flash Sale: concurrencia, Rate Limiting y bloqueos de filas; Actividad 1.3: Matriz de Decisiones de Arquitectura de Estado.'
WHERE curso_id = 1
  AND titulo = 'Clase 1.3: Gestión de Estado, Límites de Seguridad y la Física de RAM vs Persistencia';

-- 3) ACTIVIDAD 1.3: renombrar/actualizar la actividad de la lección (matriz del guion)
UPDATE actividades
SET titulo = 'Actividad 1.3: Matriz de Decisiones de Arquitectura de Estado',
    descripcion = 'Asume el rol de Arquitecto de Software y decide en qué capa debe vivir el estado primario de cada uno de los datos (Frontend RAM, Backend Runtime/Caché o Base de Datos Persistente) justificando con Seguridad, Velocidad o Persistencia: (1) el segundo exacto en que el usuario pausó un video de la clase → Frontend RAM con sincronización periódica; (2) el token JWT de autenticación que valida su suscripción → Backend (verificado y firmado criptográficamente en cada petición); (3) la nota final del examen del módulo → Base de Datos Persistente (dato legal e histórico); (4) el estado Abierto/Cerrado del menú lateral → Frontend/Client State; (5) el límite máximo de intentos fallidos antes de bloquear una cuenta por brute-force → Backend (regla de seguridad, no puede vivir en el navegador).'
WHERE titulo = 'Act: Clasificación Front/Back/BD';