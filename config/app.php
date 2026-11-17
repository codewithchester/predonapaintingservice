<?php
return [
    'name' => env('APP_NAME', 'Peridona Painting Service'),
    'env' => env('APP_ENV', 'production'),
    'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim((string) env('APP_URL', ''), '/'),
    'timezone' => env('APP_TIMEZONE', 'Africa/Accra'),
    'root' => dirname(__DIR__),
    'public' => dirname(__DIR__) . '/public',
    'storage' => dirname(__DIR__) . '/storage',
];
