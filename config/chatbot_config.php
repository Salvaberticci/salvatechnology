<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chatbot_kb.php';

return [
    'groq_api_key' => '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',
    'groq_model'   => 'llama-3.3-70b-versatile',
    'core_prompt'  => chatbot_core(),
];
