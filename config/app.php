<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Zay Shop',
    'url' => $_ENV['APP_URL'] ?? 'http://localhost/PaginaWebFLEX',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    
    'timezone' => 'America/Mexico_City',
    
    'locale' => 'es',
    'fallback_locale' => 'en',
    
    'encryption_key' => $_ENV['APP_KEY'] ?? 'base64:' . base64_encode('32-character-string-for-encryption'),
    
    'session' => [
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 7200), // 2 hours
        'name' => $_ENV['SESSION_NAME'] ?? 'zay_session',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    'upload' => [
        'max_size' => 10485760, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'upload_path' => 'public/uploads/'
    ]
];