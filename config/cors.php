<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'register'],

    'allowed_methods' => ['*'], // Autorise GET, POST, PUT, DELETE

    'allowed_origins' => ['*'], // Autorise n'importe quelle adresse (celle de ton binôme)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Autorise tous les headers (Content-Type, Authorization, etc.)

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Indispensable pour l'authentification Sanctum
];
