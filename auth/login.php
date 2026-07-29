<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($password, $usuario['password'])) {
        echo json_encode(['status' => 'error', 'message' => 'Email o contraseña incorrectos']);
        exit;
    }

    if ($usuario['plan'] === 'suscripcion' && $usuario['suscripcion_expira'] && strtotime($usuario['suscripcion_expira']) < time()) {
        $upd = $pdo->prepare("UPDATE usuarios SET plan = 'gratuito' WHERE id = ?");
        $upd->execute([$usuario['id']]);
        $usuario['plan'] = 'gratuito';
    }

    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nombre'] = $usuario['nombre'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_rol'] = $usuario['rol'];
    $_SESSION['usuario_plan'] = $usuario['plan'];
    $_SESSION['suscripcion_expira'] = $usuario['suscripcion_expira'];

    $redirect = ($usuario['rol'] === 'profesor') ? '/salvatechnology/profesor' : '/salvatechnology/dashboard';
    echo json_encode(['status' => 'success', 'message' => 'Inicio de sesión exitoso', 'redirect' => $redirect]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al iniciar sesión']);
}
