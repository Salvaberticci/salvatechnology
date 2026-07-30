<?php
$instructionsFile = __DIR__ . '/../chatbot-instructions.md';
$systemPrompt = file_exists($instructionsFile)
    ? file_get_contents($instructionsFile)
    : "Eres SALVA AI, el asistente de aprendizaje de Salvatechnology Academy. Respondes en español, de forma amigable y técnica.";

return [
    'groq_api_key' => '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',
    'groq_model'   => 'llama-3.3-70b-versatile',
    'system_prompt' => $systemPrompt,
];
