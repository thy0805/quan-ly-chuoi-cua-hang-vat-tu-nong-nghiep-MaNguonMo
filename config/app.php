<?php

return [
    'name' => 'MNM Nhóm 6',
    'env' => env('APP_ENV', 'local'),
    'debug' => false,
    'url' => env('APP_URL', 'http://127.0.0.1:8086'),
    'timezone' => 'UTC',
    'locale' => 'vi',
    'fallback_locale' => 'en',
    'faker_locale' => 'vi_VN',
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [],
    'maintenance' => ['driver' => 'file', 'store' => 'file'],
];
