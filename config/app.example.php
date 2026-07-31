<?php
/*
  Copia este archivo como app.php y ajusta BASE_URL según tu entorno:
  - Local XAMPP (sin subdominio):  define('BASE_URL', '/salvatechnology/');
  - Producción (subdominio raíz):   define('BASE_URL', '/');
  - Producción (dominio propio):     define('BASE_URL', '/');
*/
define('BASE_URL', '/');

/*
  Token de seguridad para ejecutar migraciones desde URL (migrate.php).
  Déjalo vacío para deshabilitar la protección (solo desarrollo local).
  En producción usa un valor aleatorio y seguro:
    define('MIGRATION_TOKEN', 'cambia-este-token-por-uno-seguro');
*/
define('MIGRATION_TOKEN', '');
