<?php

namespace App\Support;

use LogicException;

final class RuntimeIsolation
{
    public static function allowsCommand(array $arguments): bool
    {
        $allowed = ['list', 'about', 'route:list', 'schedule:list', 'config:show'];

        return count($arguments) === 2 && in_array($arguments[1], $allowed, true);
    }

    public static function assertConfiguration(array $configuration): void
    {
        $database = $configuration['database'];
        $mysql = $database['connections']['mysql'] ?? [];
        $valid = $database['default'] === 'mysql'
            && array_keys($database['connections']) === ['mysql']
            && ($mysql['driver'] ?? null) === 'mysql'
            && ($mysql['database'] ?? null) === 'mnm_vat_tu_nong_nghiep'
            && in_array($mysql['host'] ?? null, ['127.0.0.1', 'localhost', '::1'], true)
            && empty($mysql['url']) && empty($mysql['unix_socket'])
            && ($mysql['engine'] ?? null) === 'InnoDB'
            && ($mysql['charset'] ?? null) === 'utf8mb4'
            && ($mysql['timezone'] ?? null) === '+00:00'
            && $configuration['session']['driver'] === 'file'
            && $configuration['session']['cookie'] === 'mnm_nhom6_session'
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
            throw new LogicException('MNM G3: cấu hình chưa được cô lập; từ chối khởi động.');
        }
    }

    public static function rejectDatabaseConnection(): never
    {
        throw new LogicException('MNM G3: chưa cho phép kết nối database, migration hoặc seed.');
    }
}
