-- ============================================================
-- Migración 024: Ficha técnica (descripción) de la Clase 1.3
-- "Backend vs Frontend vs Base de Datos — Qué hace cada capa,
-- dónde vive cada dato y cómo se comunican" según el guion
-- oficial del video. Idempotente (UPDATE por título).
-- Fecha: 2026-08-07
-- ============================================================

UPDATE lecciones
SET descripcion = 'Frontend: capa de presentación e interacción (renderiza pantallas, captura eventos y validaciones estéticas en el dispositivo del usuario). Backend: capa de lógica y orquestación (autoridad central: valida autenticación y permisos, aplica reglas de negocio y orquesta la persistencia). Base de Datos: capa de persistencia (conservación duradera, integridad referencial y consultas rápidas). Mapa del dato: RAM del cliente → payload HTTP/JSON en tránsito → memoria del servidor → disco persistente (Single Source of Truth). Comunicación: HTTP/REST con verbos GET/POST/PUT/DELETE entre Frontend y Backend, y SQL/NoSQL (drivers u ORM) entre Backend y Base de Datos. Actividad 1.3: Clasificación Front/Back/BD de 5 funciones de una aplicación a tu elección.'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.3[[:space:]]*:'
  AND (descripcion IS NULL OR CHAR_LENGTH(descripcion) < 200);