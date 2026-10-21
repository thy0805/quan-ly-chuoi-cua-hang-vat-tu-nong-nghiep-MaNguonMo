<?php

return [
    'paths' => [resource_path('views')],
    'compiled' => storage_path(\App\Support\InventoryTestProfile::active() ? 'mnm/c1-test/views' : 'mnm/framework/views'),
];
