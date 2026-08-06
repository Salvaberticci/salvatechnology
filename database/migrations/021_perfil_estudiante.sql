-- ============================================================
-- Migración 021: Perfil de estudiante
--  * avatar en usuarios (ruta de imagen de perfil)
--  * tabla ebook_progreso: XP/logros sincronizados de los
--    e-books interactivos por usuario (estilo videojuego)
-- Fecha: 2026-08-06
-- ============================================================

ALTER TABLE usuarios
  ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER telefono;

CREATE TABLE IF NOT EXISTS ebook_progreso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id BIGINT UNSIGNED NOT NULL,
  ebook_key VARCHAR(50) NOT NULL,
  xp INT NOT NULL DEFAULT 0,
  level INT NOT NULL DEFAULT 1,
  logros TEXT NULL,
  quiz_aciertos INT NOT NULL DEFAULT 0,
  actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY ebook_progreso_usuario_ebook_unique (usuario_id, ebook_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;