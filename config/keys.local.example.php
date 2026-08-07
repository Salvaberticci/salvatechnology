<?php
// ============================================================
// EJEMPLO de config/keys.local.php — copia este archivo como
// config/keys.local.php en el SERVIDOR y completa los valores.
//
// config/keys.local.php está en .gitignore: nunca subas el real.
// Este archivo alimenta a DOS sistemas:
//   1) helpers/correo.php  → lee las variables sueltas ($SMTP_*, $APP_URL)
//   2) config/chatbot_config.php → requiere el return array ($claves['gemini'], $claves['groq'])
// ============================================================

// ------------------------------------------------------------
// SMTP (correo de bienvenida — Namecheap)
// ------------------------------------------------------------
$SMTP_HOST  = 'tu-domino.tld';            // ej: salvatechnology.online
$SMTP_PORT  = 465;                         // 465 (ssl) o 587 (tls)
$SMTP_SECURE = 'ssl';                      // 'ssl' (465) o 'tls' (587)
$SMTP_USER  = 'correo@tu-domino.tld';      // usuario SMTP, ej: academy@salvatechnology.online
$SMTP_PASS  = 'PON_AQUI_TU_CONTRASENA';    // password REAL de tu buzón SMTP
$MAIL_FROM  = 'correo@tu-domino.tld';      // remitente visible
$MAIL_FROM_NAME = 'Salva Technology';

// URL pública de la app (se usa para el link "INGRESAR A MI CUENTA" del correo)
$APP_URL    = 'https://academy.salvatechnology.online';

// ------------------------------------------------------------
// Claves de API de IA (chatbot SALVA AI — Gemini y Groq)
// ------------------------------------------------------------
return [
    'gemini' => 'PON_AQUI_TU_GEMINI_API_KEY',
    'groq'   => 'PON_AQUI_TU_GROQ_API_KEY',
];