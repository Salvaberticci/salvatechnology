<?php
/**
 * Migraciones SQL — Ejecutor desde URL
 * ------------------------------------------------------------
 * Ejecuta las migraciones pendientes en database/migrations/.
 *
 * Uso:
 *   https://tudominio.com/migrate.php                     → aplica todas las pendientes
 *   https://tudominio.com/migrate.php?file=002_xxx.sql    → aplica solo un archivo
 *   https://tudominio.com/migrate.php?dry=1               → muestra qué aplicaría sin ejecutar
 *
 * Seguridad (recomendado en producción):
 *   Define MIGRATION_TOKEN en config/app.php y visita:
 *   https://tudominio.com/migrate.php?token=TU_TOKEN
 */

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

$MIGRATIONS_DIR = __DIR__ . '/database/migrations';
$LOG_TABLE      = 'migrations_log';

// ---------- Protección por token ----------
if (defined('MIGRATION_TOKEN') && MIGRATION_TOKEN !== '') {
    $token = $_GET['token'] ?? '';
    if (!hash_equals((string) MIGRATION_TOKEN, (string) $token)) {
        http_response_code(403);
        die('<h1 style="font-family:sans-serif">403 — Token inválido</h1><p style="font-family:sans-serif">Para ejecutar migraciones necesitas el token definido en config/app.php (MIGRATION_TOKEN).</p>');
    }
}

// ---------- Obtener listado de archivos ----------
$files = glob($MIGRATIONS_DIR . '/*.sql');
sort($files);

// ---------- Si se pide un archivo específico ----------
if (!empty($_GET['file'])) {
    $requested = basename($_GET['file']);
    $files = array_filter($files, function ($f) use ($requested) {
        return basename($f) === $requested;
    });
    if (empty($files)) {
        die("<p>No se encontró el archivo: " . htmlspecialchars($requested) . "</p>");
    }
}

if (empty($files)) {
    echo '<p>No hay archivos .sql en ' . htmlspecialchars($MIGRATIONS_DIR) . '</p>';
    exit;
}

// ---------- Asegurar tabla de log ----------
$pdo->exec("CREATE TABLE IF NOT EXISTS `$LOG_TABLE` (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    migration VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY migrations_log_migration_unique (migration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$applied = $pdo->query("SELECT migration FROM `$LOG_TABLE`")->fetchAll(PDO::FETCH_COLUMN);
$applied = array_flip($applied);

// ---------- Modo dry-run ----------
$dry = !empty($_GET['dry']);

echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'><title>Migraciones</title>
<style>
body{background:#0a0a0a;color:#e2e8f0;font-family:'Courier New',monospace;padding:2rem;max-width:900px;margin:0 auto}
h1{color:#ff8c00;border-bottom:1px solid #333;padding-bottom:.5rem}
.ok{color:#34d399}.err{color:#ef4444}.info{color:#94a3b8}
pre{background:#111;padding:1rem;border-radius:8px;overflow-x:auto;border:1px solid #333}
.s{background:#151515;border:1px solid #2a2a2a;border-radius:8px;padding:.75rem 1rem;margin-bottom:.5rem}
</style></head><body>
<h1>🚀 Migraciones SQL</h1>";

if ($dry) echo "<p class='info'>Modo <strong>DRY-RUN</strong>: no se ejecuta nada, solo se muestra qué se aplicaría.</p>";

$successCount = 0;
$errorCount   = 0;

foreach ($files as $file) {
    $name = basename($file);

    if (isset($applied[$name])) {
        echo "<div class='s'><span class='info'>[SKIP]</span> $name <span class='info'>(ya aplicada)</span></div>";
        continue;
    }

    $sql = file_get_contents($file);
    if ($sql === false || trim($sql) === '') {
        echo "<div class='s'><span class='err'>[ERROR]</span> $name — archivo vacío o ilegible</div>";
        $errorCount++;
        continue;
    }

    if ($dry) {
        echo "<div class='s'><span class='info'>[DRY]</span> $name <span class='info'>(pendiente)</span></div>";
        $successCount++;
        continue;
    }

    try {
        $pdo->exec($sql);
        $ins = $pdo->prepare("INSERT INTO `$LOG_TABLE` (migration) VALUES (?)");
        $ins->execute([$name]);
        echo "<div class='s'><span class='ok'>[OK]</span> $name</div>";
        $successCount++;
    } catch (PDOException $e) {
        echo "<div class='s'><span class='err'>[ERROR]</span> $name<br><pre>" . htmlspecialchars($e->getMessage()) . "</pre></div>";
        $errorCount++;
    }
}

echo "<hr><p class='info'>Resumen: $successCount aplicada(s), $errorCount con error.</p>";
echo "</body></html>";
