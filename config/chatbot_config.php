<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/chatbot_kb.php';

return [
    'providers' => [
        [
            'nombre'    => 'gemini',
            'url'       => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
            'api_key'   => 'PON_AQUI_TU_GEMINI_API_KEY',
            'modelo'    => 'gemini-2.5-flash',
            'max_tokens' => 1024,
        ],
        [
            'nombre'    => 'groq',
            'url'       => 'https://api.groq.com/openai/v1/chat/completions',
            'api_key'   => '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',
            'modelo'    => 'llama-3.3-70b-versatile',
            'max_tokens' => 1024,
        ],
    ],
    'core_prompt' => chatbot_core(),
];
