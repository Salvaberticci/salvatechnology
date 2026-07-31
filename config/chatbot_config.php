<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chatbot_kb.php';

$keysFile = __DIR__ . '/keys.local.php';
if (!file_exists($keysFile)) {
    http_response_code(500);
    die('Falta config/keys.local.php — cópialo desde keys.local.example.php con tus claves de API.');
}
$claves = require $keysFile;

return [
    'providers' => [
        [
            'nombre'    => 'gemini',
            'url'       => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'api_key'   => $claves['gemini'],
            'modelo'    => 'gemini-flash-latest',
            'max_tokens' => 1024,
        ],
        [
            'nombre'    => 'groq',
            'url'       => 'https://api.groq.com/openai/v1/chat/completions',
            'api_key'   => $claves['groq'],
            'modelo'    => 'llama-3.3-70b-versatile',
            'max_tokens' => 1024,
        ],
    ],
    'core_prompt' => chatbot_core(),
];
