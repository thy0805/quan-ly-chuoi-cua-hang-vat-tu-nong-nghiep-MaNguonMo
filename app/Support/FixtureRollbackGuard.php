<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

final class FixtureRollbackGuard
{
    public static function content(): array
    {
        $result = [];
        foreach (['product_categories', 'products', 'units', 'users'] as $table) {
            $rows = DB::table($table)->orderBy('id')->get()->all();
            $result[$table] = ['rows' => count($rows), 'sha256' => hash('sha256', json_encode($rows, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE))];
        }

        return $result;
    }

    public static function assertMatches(): void
    {
        $provision = json_decode(file_get_contents(base_path('.agent/private/g4a/fixture.json')), true);
        $snapshot = json_decode(file_get_contents(base_path('.agent/private/g4a/fixture-content.json')), true);
        $tables = DB::select('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME');
        $migrations = DB::table('migrations')->orderBy('migration')->pluck('migration')->all();
        $objects = DB::selectOne('SELECT (SELECT COUNT(*) FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()) + (SELECT COUNT(*) FROM information_schema.EVENTS WHERE EVENT_SCHEMA = DATABASE()) AS extra_objects');
        $triggers = DB::select('SELECT TRIGGER_NAME FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE() ORDER BY TRIGGER_NAME');
        if (($provision['database'] ?? null) !== 'mnm_vat_tu_nong_nghiep'
            || ($provision['target_previously_absent'] ?? null) !== true
            || array_column($tables, 'TABLE_NAME') !== ['migrations', 'product_categories', 'products', 'units', 'users']
            || $migrations !== ($provision['migrations'] ?? [])
            || (int) $objects->extra_objects !== 0
            || array_column($triggers, 'TRIGGER_NAME') !== ['categories_no_self_insert', 'categories_no_self_update']
            || self::content() !== $snapshot) {
            throw new RuntimeException('MNM: chưa chứng minh exact-set/schema chỉ chứa fixture G4A; rollback bị chặn.');
        }
    }
}
