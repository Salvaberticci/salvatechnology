<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$telefono = trim($_POST['telefono'] ?? '');
$pais = trim($_POST['pais'] ?? '');

$errors = [];
if (empty($nombre) || strlen($nombre) < 2) $errors[] = 'Nombre debe tener al menos 2 caracteres';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
if (strlen($password) < 6) $errors[] = 'Contraseña debe tener al menos 6 caracteres';

if (!empty($errors)) {
    echo json_encode(['status' => 'error', 'message' => implode('. ', $errors)]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Este email ya está registrado']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, telefono, pais, rol, plan) VALUES (?, ?, ?, ?, ?, 'estudiante', 'gratuito')");
    $stmt->execute([$nombre, $email, $hash, $telefono, $pais]);

    $usuarioId = $pdo->lastInsertId();
    $_SESSION['usuario_id'] = $usuarioId;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['usuario_email'] = $email;
    $_SESSION['usuario_rol'] = 'estudiante';
    $_SESSION['usuario_plan'] = 'gratuito';

    echo json_encode(['status' => 'success', 'message' => 'Registro exitoso', 'redirect' => '/salvatechnology/dashboard']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar: ' . $e->getMessage()]);
}
