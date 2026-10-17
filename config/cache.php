<?php

return [
    'default' => 'file',
    'stores' => [
        'array' => ['driver' => 'array', 'serialize' => false],
        'file' => [
            'driver' => 'file',
            'path' => storage_path('mnm/framework/cache/data'),
            'lock_path' => storage_path('mnm/framework/cache/data'),
        ],
    ],
    'prefix' => 'mnm_nhom6_cache_',
    'serializable_classes' => false,
];
