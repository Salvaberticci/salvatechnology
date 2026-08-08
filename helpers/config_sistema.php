<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Lee una clave de configuración del sistema (tabla config_sistema).
 * Devuelve el valor o $valorDefecto si la clave está vacía o no existe.
 */
function configSistema($clave, $valorDefecto = '') {
    global $configSistemaCache;
    if ($configSistemaCache === null) {
        $configSistemaCache = [];
        try {
            global $pdo;
            $stmt = $pdo->query("SELECT clave, valor FROM config_sistema");
            foreach ($stmt->fetchAll() as $fila) {
                $configSistemaCache[$fila['clave']] = $fila['valor'];
            }
        } catch (Throwable $e) {
            $configSistemaCache = []; // sin conexión o sin tabla aún
        }
    }
    $valor = $configSistemaCache[$clave] ?? $valorDefecto;
    return ($valor === null || $valor === '') ? $valorDefecto : $valor;
}

/**
 * Guarda o actualiza una clave de configuración del sistema.
 */
function guardarConfig($clave, $valor) {
    global $pdo, $configSistemaCache;
    $configSistemaCache = null; // invalidar cache para que el siguiente configSistema() lea lo nuevo
    $stmt = $pdo->prepare("INSERT INTO config_sistema (clave, valor) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE valor = VALUES(valor), actualizado_en = NOW()");
    return $stmt->execute([$clave, (string)$valor]);
}

/**
 * ¿El usuario en sesión es administrador de la plataforma?
 * Es admin si su rol es 'admin' o si su email está en la lista
 * de admins de la config (un email por línea).
 */
function esAdmin() {
    if (!isset($_SESSION['usuario_email'])) return false;
    if (($_SESSION['usuario_rol'] ?? '') === 'admin') return true;

    $email = strtolower(trim($_SESSION['usuario_email']));
    $adminsRaw = configSistema('admins', '');

    // Fallback 1: si la tabla config_sistema no existe/no tiene admins,
    // usa la lista $ADMIN_EMAILS de config/keys.local.php
    if (trim($adminsRaw) === '') {
        @include_once __DIR__ . '/../config/keys.local.php';
        if (isset($ADMIN_EMAILS) && $ADMIN_EMAILS !== '') $adminsRaw = $ADMIN_EMAILS;
    }

    // Fallback 2: lista de emergencia versionada en git (funciona en producción
    // aunque keys.local no tenga $ADMIN_EMAILS y la tabla no exista aún).
    if (trim($adminsRaw) === '') {
        $adminsRaw = 'salvatore@salvatechnology.com';
    }

    foreach (explode("\n", $adminsRaw) as $a) {
        if (strtolower(trim($a)) === $email) return true;
    }
    return false;
}