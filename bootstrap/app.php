<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureActiveUser;
use App\Http\Middleware\RequireAdmin;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withExceptions()
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias(['active' => EnsureActiveUser::class, 'admin' => RequireAdmin::class]);
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('products.index'));
    })
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )->create();

$app->dontMergeFrameworkConfiguration();

return $app;
