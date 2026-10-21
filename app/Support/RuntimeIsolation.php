<?php

namespace App\Support;

use LogicException;
use PDO;

final class RuntimeIsolation
{
    public static function allowsCommand(array $arguments): bool
    {
        $command = $arguments[1] ?? '';
        $options = array_slice($arguments, 2);
        $allowed = match ($command) {
            'list', 'about', 'route:list', 'schedule:list', 'migrate:status' => [],
            'migrate' => ['--force', '--no-interaction'],
            'db:seed' => ['--force', '--no-interaction', '--class=DatabaseSeeder'],
            'migrate:rollback' => getenv('MNM_G4A_ROLLBACK') === 'owned-fixture'
                ? ['--step=4', '--force', '--no-interaction'] : null,
            default => null,
        };

        return $allowed !== null && array_diff($options, $allowed) === []
            && count($options) === count(array_unique($options));
    }

    public static function assertConfiguration(array $configuration): void
    {
        $database = $configuration['database'];
        $mysql = $database['connections']['mysql'] ?? [];
        self::assertDatabaseConfiguration($mysql);
        $valid = $database['default'] === 'mysql'
            && array_keys($database['connections']) === ['mysql']
            && ($mysql['driver'] ?? null) === 'mysql'
            && ($mysql['database'] ?? null) === (InventoryTestProfile::active() ? InventoryTestProfile::DATABASE : 'mnm_vat_tu_nong_nghiep')
            && ($mysql['host'] ?? null) === '127.0.0.1'
            && empty($mysql['url']) && empty($mysql['unix_socket'])
            && ($mysql['engine'] ?? null) === 'InnoDB'
            && ($mysql['charset'] ?? null) === 'utf8mb4'
            && ($mysql['timezone'] ?? null) === '+00:00'
            && $configuration['session']['driver'] === 'file'
            && $configuration['session']['cookie'] === (InventoryTestProfile::active() ? 'mnm_inventory_test_session' : 'mnm_nhom6_session')
            && $configuration['session']['domain'] === null
            && $configuration['cache']['default'] === 'file'
            && $configuration['queue']['default'] === 'null';

        $localStorage = realpath(dirname(__DIR__, 2).'/storage/mnm');
        foreach ([$configuration['session']['files'], $configuration['cache']['stores']['file']['path']] as $path) {
            $resolved = realpath($path);
            $valid = $valid && $localStorage !== false && $resolved !== false
                && str_starts_with(str_replace('\\', '/', $resolved).'/', str_replace('\\', '/', $localStorage).'/');
        }

        if (! $valid || getenv('DB_URL') || getenv('APP_BASE_PATH') || getenv('LARAVEL_STORAGE_PATH')) {
            throw new LogicException('MNM: cấu hình chưa được cô lập; từ chối khởi động.');
        }
    }

    public static function assertDatabaseConfiguration(array $mysql): void
    {
        if (($mysql['driver'] ?? null) !== 'mysql'
            || ($mysql['database'] ?? null) !== (InventoryTestProfile::active() ? InventoryTestProfile::DATABASE : 'mnm_vat_tu_nong_nghiep')
            || ($mysql['host'] ?? null) !== '127.0.0.1'
            || (string) ($mysql['port'] ?? '') !== '3306'
            || ! (InventoryTestProfile::active()
                ? InventoryTestProfile::accountAllowed($mysql['username'] ?? '')
                : in_array($mysql['username'] ?? '', ['mnm_runtime_20261017', 'mnm_migrate_20261017'], true))
            || ! is_string($mysql['password'] ?? null) || $mysql['password'] === ''
            || ! empty($mysql['url']) || ! empty($mysql['unix_socket'])
            || isset($mysql['read']) || isset($mysql['write'])) {
            throw new LogicException('MNM: kết nối nằm ngoài database/account đã provision.');
        }
    }

    public static function assertConnectionIdentity(PDO $pdo, array $mysql): void
    {
        $identity = $pdo->query('SELECT DATABASE() AS db, VERSION() AS version, CURRENT_USER() AS account, @@datadir AS datadir')->fetch(PDO::FETCH_ASSOC);
        $datadir = strtolower(str_replace('\\', '/', $identity['datadir']));
        if ($identity['db'] !== (InventoryTestProfile::active() ? InventoryTestProfile::DATABASE : 'mnm_vat_tu_nong_nghiep')
            || ! str_starts_with($identity['version'], '8.4.')
            || $identity['account'] !== $mysql['username'].'@localhost'
            || rtrim($datadir, '/') !== 'c:/laragon/data/mysql-8.4') {
            throw new LogicException('MNM: danh tính MySQL không khớp preflight được duyệt.');
        }
    }
}
