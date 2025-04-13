<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'csrf-cookie',
        'login',
        'logout',
        'dashboard',
        'users/*',
        'clients/*',
        'sessions/*',
        'profile',
        'settings',
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:8000',
        'http://intro.localhost:8000',
        // Add other specific subdomains if needed
    ],
    'allowed_origins_patterns' => [
        '/^http:\/\/.*\.localhost:8000$/', // This will match any subdomain of localhost:8000
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
