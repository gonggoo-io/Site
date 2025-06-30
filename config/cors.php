<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://gonggoo.kro.kr','https://gonggoo.kro.kr', 'http://127.0.0.1:5173', 'http://localhost:5173', 'http://localhost:8000'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
]; 