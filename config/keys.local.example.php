<?php
// ============================================================
// EJEMPLO de config/keys.local.php — copia este archivo como
// config/keys.local.php en el SERVIDOR y completa los valores.
//
// config/keys.local.php está en .gitignore: nunca subas el real.
// ============================================================
$SMTP_HOST  = 'tu-domino.tld';            // ej: salvatechnology.online
$SMTP_PORT  = 465;                         // 465 (ssl) o 587 (tls)
$SMTP_SECURE = 'ssl';                      // 'ssl' (465) o 'tls' (587)
$SMTP_USER  = 'correo@tu-domino.tld';      // usuario SMTP, ej: academy@salvatechnology.online
$SMTP_PASS  = 'PON_AQUI_TU_CONTRASENA';    // password REAL de tu buzón SMTP
$MAIL_FROM  = 'correo@tu-domino.tld';      // remitente visible
$MAIL_FROM_NAME = 'Salva Technology';
$APP_URL    = 'https://academy.salvatechnology.online'; // URL pública de la app (links del correo)

// Nota: si $SMTP_PASS se deja como 'PON_AQUI_...', helpers/correo.php
// rechaza el envío (['ok' => false, 'error' => 'SMTP sin contraseña configurada']).