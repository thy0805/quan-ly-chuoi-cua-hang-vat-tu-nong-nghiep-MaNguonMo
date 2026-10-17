<?php

namespace App\Providers;

use App\Support\RuntimeIsolation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        RuntimeIsolation::assertConfiguration([
            'database' => config('database'),
            'session' => config('session'),
            'cache' => config('cache'),
            'queue' => config('queue'),
        ]);

        $this->app->make('db')->extend('mysql', static function () {
            RuntimeIsolation::rejectDatabaseConnection();
        });
    }
}
