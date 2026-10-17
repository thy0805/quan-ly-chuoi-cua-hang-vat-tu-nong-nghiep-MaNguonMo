<?php

namespace App\Providers;

use App\Support\RuntimeIsolation;
use App\Support\FixtureRollbackGuard;
use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Database\MySqlConnection;
use LogicException;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole() && ($_SERVER['argv'][1] ?? '') === 'migrate:rollback') {
            FixtureRollbackGuard::assertMatches();
        }
    }

    public function register(): void
    {
        RuntimeIsolation::assertConfiguration([
            'database' => config('database'),
            'session' => config('session'),
            'cache' => config('cache'),
            'queue' => config('queue'),
        ]);

        $this->app->make('db')->extend('mysql', static function (array $configuration, string $name) {
            if ($name !== 'mysql') {
                throw new LogicException('MNM: chỉ cho phép connection mysql độc lập.');
            }
            RuntimeIsolation::assertDatabaseConfiguration($configuration);
            $pdo = (new MySqlConnector)->connect($configuration);
            RuntimeIsolation::assertConnectionIdentity($pdo, $configuration);
            $configuration['name'] = $name;

            return new MySqlConnection($pdo, $configuration['database'], $configuration['prefix'], $configuration);
        });
    }
}
