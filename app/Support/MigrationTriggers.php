<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

final class MigrationTriggers
{
    public static function apply(string $operation): void
    {
        if (! in_array($operation, ['create', 'drop'], true)) {
            throw new RuntimeException('MNM: thao tác trigger không hợp lệ.');
        }
        $token = getenv('MNM_TRIGGER_BRIDGE_TOKEN');
        if ($token) {
            $directory = base_path('.agent/private/g4a/trigger-requests');
            if (! is_dir($directory)) {
                throw new RuntimeException('MNM: phiên DDL quản trị chưa được chuẩn bị.');
            }
            $id = bin2hex(random_bytes(16));
            $request = $directory.'/'.$id.'.request.json';
            $response = $directory.'/'.$id.'.response.json';
            file_put_contents($request, json_encode(['token' => $token, 'operation' => $operation], JSON_THROW_ON_ERROR), LOCK_EX);
            $deadline = microtime(true) + 20;
            while (! is_file($response) && microtime(true) < $deadline) {
                usleep(100000);
                clearstatcache(true, $response);
            }
            $result = is_file($response) ? json_decode(file_get_contents($response), true) : null;
            if (($result['status'] ?? null) !== 'OK') {
                throw new RuntimeException('MNM: DDL trigger chưa được phiên quản trị xác nhận.');
            }
        } else {
            foreach (self::statements($operation) as $sql) {
                DB::unprepared($sql);
            }
        }
        $triggers = DB::select("SELECT TRIGGER_NAME, EVENT_MANIPULATION, ACTION_STATEMENT FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE() AND TRIGGER_NAME IN ('categories_no_self_insert', 'categories_no_self_update') ORDER BY TRIGGER_NAME");
        if ($operation === 'drop' && count($triggers) !== 0) {
            throw new RuntimeException('MNM: trigger chưa rollback đầy đủ.');
        }
        if ($operation === 'create' && (count($triggers) !== 2
            || array_column($triggers, 'EVENT_MANIPULATION') !== ['INSERT', 'UPDATE']
            || count(array_filter($triggers, fn ($trigger) => str_contains($trigger->ACTION_STATEMENT, 'NEW.parent_id = NEW.id') && str_contains($trigger->ACTION_STATEMENT, "SQLSTATE '45000'"))) !== 2)) {
            throw new RuntimeException('MNM: trigger không khớp ràng buộc tự trỏ đã duyệt.');
        }
    }

    public static function statements(string $operation): array
    {
        if ($operation === 'drop') {
            return ['DROP TRIGGER IF EXISTS categories_no_self_update', 'DROP TRIGGER IF EXISTS categories_no_self_insert'];
        }

        return array_map(fn ($event) => 'CREATE TRIGGER categories_no_self_'.strtolower($event)." BEFORE {$event} ON product_categories FOR EACH ROW BEGIN IF NEW.parent_id IS NOT NULL AND NEW.parent_id = NEW.id THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Category cannot reference itself'; END IF; END", ['INSERT', 'UPDATE']);
    }
}
