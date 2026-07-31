<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chatbot_kb.php';

$instructionsFile = __DIR__ . '/../chatbot-instructions.md';
$basePrompt = file_exists($instructionsFile)
    ? file_get_contents($instructionsFile)
    : "Eres SALVA AI, el asistente de aprendizaje de Salvatechnology Academy. Respondes en español, de forma amigable y técnica.";

$catalogo = chatbot_generar_catalogo($pdo);
$systemPrompt = $basePrompt . $catalogo;

return [
    'groq_api_key' => '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',
    'groq_model'   => 'llama-3.3-70b-versatile',
    'system_prompt' => $systemPrompt,
];
