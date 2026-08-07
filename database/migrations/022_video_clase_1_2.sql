-- ============================================================
-- Migración 022: Video de la Clase 1.2 (Anatomía del Software:
-- Frontend, Backend, Persistencia y el Modelo Cliente-Servidor)
-- UPDATE por título (idempotente, igual que la 020) para que
-- funcione en cualquier generación de ids (local y servidor).
-- Fecha: 2026-08-07
-- ============================================================

UPDATE lecciones
SET video_url = 'https://player.mediadelivery.net/play/717430/7b649492-1cb0-4677-ac52-b67199f25e31'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.2[[:space:]]*:'
  AND (video_url IS NULL OR video_url = '');
