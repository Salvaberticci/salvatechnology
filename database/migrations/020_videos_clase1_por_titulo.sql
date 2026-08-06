-- ============================================================
-- Migración 020: Asignar videos de Clase 1 y Clase 1.1 por TÍTULO
-- (corrige la divergencia de ids entre BD local y servidor)
-- 002 apuntó al id 781 y 019 al id 1115; los ids difieren por
-- reseed. Este UPDATE es idempotente y por título, funciona en
-- cualquier generación de ids.
-- Fecha: 2026-08-06
-- ============================================================

-- Clase 1 maestra -> video de la migración 002
UPDATE lecciones
SET video_url = 'https://player.mediadelivery.net/play/717430/eb1ee748-c5a0-4f53-9cd5-d77f679768aa'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*:'
  AND (video_url IS NULL OR video_url = '');

-- Clase 1.1 -> video de la migración 019
UPDATE lecciones
SET video_url = 'https://player.mediadelivery.net/play/717430/a960b9ef-8f03-4e54-a817-babb05d71916'
WHERE curso_id = 1
  AND titulo REGEXP '^Clase[[:space:]]+1[[:space:]]*\.1[[:space:]]*:'
  AND (video_url IS NULL OR video_url = '');