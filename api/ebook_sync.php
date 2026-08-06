<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autenticado']);
    exit;
}

$usuarioId = (int)$_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}

$json = json_decode(file_get_contents('php://input'), true);
if (!$json || !isset($json['ebook_key'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    exit;
}

$ebookKey = trim($json['ebook_key']);
$xp = max(0, (int)($json['xp'] ?? 0));
$level = max(1, (int)($json['level'] ?? 1));
$logros = $json['logros'] ?? [];
$quizAciertos = max(0, (int)($json['quiz_aciertos'] ?? 0));

if (!is_array($logros)) {
    $logros = [];
}

$stmt = $pdo->prepare('INSERT INTO ebook_progreso (usuario_id, ebook_key, xp, level, logros, quiz_aciertos)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        xp = VALUES(xp),
        level = VALUES(level),
        logros = VALUES(logros),
        quiz_aciertos = VALUES(quiz_aciertos)');
$stmt->execute([$usuarioId, $ebookKey, $xp, $level, json_encode($logros, JSON_UNESCAPED_UNICODE), $quizAciertos]);

echo json_encode(['status' => 'success', 'message' => 'Progreso guardado']);