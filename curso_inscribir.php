<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$cursoId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND activo = 1");
$stmt->execute([$cursoId]);
$curso = $stmt->fetch();

if (!$curso) {
    die("Curso no encontrado");
}

$stmt = $pdo->prepare("SELECT id FROM inscripciones WHERE usuario_id = ? AND curso_id = ? AND estado = 'activa'");
$stmt->execute([$usuarioId, $cursoId]);
if ($stmt->fetch()) {
    header('Location: ' . BASE_URL . 'curso/' . $cursoId);
    exit;
}

$tipo = 'gratuito';
if ($curso['precio'] > 0 && $_SESSION['usuario_plan'] !== 'suscripcion') {
    header('Location: ' . BASE_URL . 'planes');
    exit;
}
if ($_SESSION['usuario_plan'] === 'suscripcion') {
    $tipo = 'suscripcion';
}

$stmt = $pdo->prepare("INSERT INTO inscripciones (usuario_id, curso_id, tipo, estado) VALUES (?, ?, ?, 'activa')");
$stmt->execute([$usuarioId, $cursoId, $tipo]);

header('Location: ' . BASE_URL . 'curso/' . $cursoId);
exit;
