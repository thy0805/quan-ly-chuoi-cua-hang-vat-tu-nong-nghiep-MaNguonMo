<?php

namespace App\Support;

final class InventoryTestProfile
{
    public const DATABASE = 'mnm_inventory_test_20261021';

    public static function active(): bool
    {
        return getenv('MNM_INVENTORY_TEST') === 'isolated-c1'
            && getenv('APP_ENV') === 'inventory-testing'
            && getenv('DB_DATABASE') === self::DATABASE;
    }

    public static function accountAllowed(string $account): bool
    {
        return in_array($account, ['mnm_c1_test_runtime', 'mnm_c1_test_migrate'], true);
    }
}
