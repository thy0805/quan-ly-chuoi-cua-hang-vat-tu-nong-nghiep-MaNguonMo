<?php

return [
    'default' => 'single',
    'deprecations' => ['channel' => 'null', 'trace' => false],
    'channels' => [
        'single' => [
            'driver' => 'single',
            'path' => storage_path('mnm/logs/laravel.log'),
            'level' => 'warning',
            'replace_placeholders' => true,
        ],
        'null' => ['driver' => 'monolog', 'handler' => Monolog\Handler\NullHandler::class],
        'emergency' => ['path' => storage_path('mnm/logs/laravel.log')],
    ],
];
