<?php

return [
    'default' => 'local',
    'disks' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('mnm/app/private'),
            'serve' => false,
            'throw' => false,
            'report' => false,
        ],
    ],
    'links' => [],
];
