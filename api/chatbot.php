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
require_once __DIR__ . '/../config/db.php';

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

$system = $config['core_prompt'] . chatbot_contexto_rag($pdo, $message);

$messages = array_merge(
    [['role' => 'system', 'content' => $system]],
    $history,
    [['role' => 'user', 'content' => $message]]
);

function chatbot_llamar_proveedor(array $proveedor, array $messages): array
{
    $payload = json_encode([
        'model'       => $proveedor['modelo'],
        'messages'    => $messages,
        'temperature' => 0.7,
        'max_tokens'  => $proveedor['max_tokens'] ?? 1024,
    ]);

    $ch = curl_init($proveedor['url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $proveedor['api_key'],
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
        return ['ok' => false, 'error' => 'Error de conexión: ' . $curlError];
    }

    $data = json_decode($response, true);

    if ($httpCode !== 200) {
        $errorMsg = $data['error']['message'] ?? $data['error'] ?? 'Error HTTP ' . $httpCode;
        return ['ok' => false, 'error' => $errorMsg];
    }

    if (empty($data['choices'][0]['message']['content'])) {
        return ['ok' => false, 'error' => 'Respuesta vacía'];
    }

    $reply = $data['choices'][0]['message']['content'];
    $finishReason = $data['choices'][0]['finish_reason'] ?? null;
    if ($finishReason === 'length') {
        $reply .= "\n\n*(respuesta cortada por el límite — escribe \"continúa\" para seguir)*";
    }

    return ['ok' => true, 'reply' => $reply];
}

if (empty($_SESSION['chatbot_ultimo_proveedor'])) {
    $_SESSION['chatbot_ultimo_proveedor'] = 0;
}

$inicio = (int) $_SESSION['chatbot_ultimo_proveedor'];
$fallback = 1 - $inicio;
$orden = $config['providers'];
$ordenIntentos = [$orden[$inicio], $orden[$fallback]];

$errores = [];
foreach ($ordenIntentos as $proveedor) {
    $resultado = chatbot_llamar_proveedor($proveedor, $messages);
    if ($resultado['ok']) {
        $_SESSION['chatbot_ultimo_proveedor'] = $fallback;
        echo json_encode(['reply' => $resultado['reply']]);
        exit;
    }
    $errores[] = $proveedor['nombre'] . ': ' . $resultado['error'];
}

$todosRateLimit = true;
foreach ($errores as $e) {
    if (stripos($e, 'rate limit') === false) {
        $todosRateLimit = false;
        break;
    }
}

if ($todosRateLimit) {
    echo json_encode(['reply' => "⏳ ¡Uy! SALVA AI alcanzó el límite de consultas del día en ambos proveedores por ahora. Descansa un par de horas y vuelve a intentarlo, o escribe al profesor por WhatsApp si necesitas ayuda urgente. ¡Seguimos disponibles! 🚀"]);
} else {
    echo json_encode(['error' => 'SALVA AI no pudo responder: ' . implode(' | ', $errores)]);
}
