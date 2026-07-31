-- ============================================================
-- Migración 002: Asignar video de Bunny Stream a la Clase 1
-- Lección 781: "Clase 1: Conceptos Base de la Ingeniería de
-- Software — Anatomía de los Sistemas Modernos y la Metodología ADD"
-- Fecha: 2026-07-31
-- ============================================================

UPDATE lecciones
SET video_url = 'https://player.mediadelivery.net/play/717430/eb1ee748-c5a0-4f53-9cd5-d14f679768aa'
WHERE id = 781 AND (video_url IS NULL OR video_url = '');
