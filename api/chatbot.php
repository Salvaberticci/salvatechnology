<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$config = require __DIR__ . '/../config/chatbot_config.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Mensaje vacío']);
    exit;
}

$message = trim($input['message']);

$history = [];
if (!empty($input['history']) && is_array($input['history'])) {
    $history = array_slice($input['history'], -20);
}

$messages = array_merge(
    [['role' => 'system', 'content' => $config['system_prompt']]],
    $history,
    [['role' => 'user', 'content' => $message]]
);

$payload = json_encode([
    'model'       => $config['groq_model'],
    'messages'    => $messages,
    'temperature' => 0.7,
    'max_tokens'  => 1024,
]);

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $config['groq_api_key'],
        'User-Agent: Salvatechnology/1.0',
    ],
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

if ($curlError) {
    echo json_encode(['error' => 'Error de conexión con Groq: ' . $curlError]);
    exit;
}

$data = json_decode($response, true);

if ($httpCode !== 200) {
    $errorMsg = $data['error']['message'] ?? 'Error HTTP ' . $httpCode;
    if (stripos($errorMsg, 'rate limit') !== false) {
        echo json_encode(['reply' => "⏳ ¡Uy! SALVA AI alcanzó el límite de consultas del día por ahora. Descansa un par de horas y vuelve a intentarlo, o escribe al profesor por WhatsApp si necesitas ayuda urgente. ¡Seguimos disponibles! 🚀"]);
        exit;
    }
    echo json_encode(['error' => 'Groq rechazó la solicitud: ' . $errorMsg]);
    exit;
}

if (empty($data['choices'][0]['message']['content'])) {
    echo json_encode(['error' => 'Groq devolvió una respuesta vacía']);
    exit;
}

echo json_encode(['reply' => $data['choices'][0]['message']['content']]);
