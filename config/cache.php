<?php

return [
    'default' => 'file',
    'stores' => [
        'array' => ['driver' => 'array', 'serialize' => false],
        'file' => [
            'driver' => 'file',
            'path' => storage_path(\App\Support\InventoryTestProfile::active() ? 'mnm/c1-test/cache' : 'mnm/framework/cache/data'),
            'lock_path' => storage_path(\App\Support\InventoryTestProfile::active() ? 'mnm/c1-test/cache' : 'mnm/framework/cache/data'),
        ],
    ],
    'prefix' => \App\Support\InventoryTestProfile::active() ? 'mnm_c1_test_cache_' : 'mnm_nhom6_cache_',
    'serializable_classes' => false,
];
