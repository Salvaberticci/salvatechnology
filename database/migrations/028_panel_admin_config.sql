-- ============================================================
-- Migración 028: Configuración del sistema (panel admin)
--  * tabla config_sistema: claves editables desde el panel admin
--    (correo de notificación, SMTP, APP_URL, admins)
-- Fecha: 2026-08-08
-- ============================================================

CREATE TABLE IF NOT EXISTS config_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) NOT NULL,
    valor TEXT NULL,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY config_sistema_clave_unique (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO config_sistema (clave, valor) VALUES
  ('email_notificacion', 'salvatechnologyacademy@gmail.com'),
  ('smtp_host', ''),
  ('smtp_port', ''),
  ('smtp_secure', ''),
  ('smtp_user', ''),
  ('smtp_pass', ''),
  ('mail_from', ''),
  ('mail_from_name', ''),
  ('app_url', 'https://academy.salvatechnology.online'),
  ('admins', ''),
  ('nombre_plataforma', 'SalvaTechnology Academy');