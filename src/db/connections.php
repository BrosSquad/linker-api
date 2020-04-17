<?php

use function BrosSquad\Linker\Api\Helpers\env;

return [
    'mysql' => [
        'driver' => env('DB_DRIVER', 'mysql'),
        'host' => env('DB_HOST', 'localhost'),
        'port' => env('DB_PORT', 3306),
        'database' => env('DB_NAME', 'linkerdb'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD'),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],
    'sqlite' => [
        'driver' => env('DB_DRIVER', 'sqlite'),
        'database' => env('DB_NAME', ':memory:'),
    ],
];
