<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login.store');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/', fn () => redirect()->route('products.index'))->name('home');
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    foreach (['categories', 'units', 'products'] as $catalog) {
        Route::get('/'.$catalog, [CatalogController::class, 'index'])->defaults('catalog', $catalog)->name($catalog.'.index');
        Route::middleware('admin')->group(function () use ($catalog) {
            Route::get('/'.$catalog.'/create', [CatalogController::class, 'create'])->defaults('catalog', $catalog)->name($catalog.'.create');
            Route::post('/'.$catalog, [CatalogController::class, 'store'])->defaults('catalog', $catalog)->name($catalog.'.store');
            Route::get('/'.$catalog.'/{record}/edit', [CatalogController::class, 'edit'])->where('record', '[1-9][0-9]{0,18}')->defaults('catalog', $catalog)->name($catalog.'.edit');
            Route::match(['PUT', 'PATCH'], '/'.$catalog.'/{record}', [CatalogController::class, 'update'])->where('record', '[1-9][0-9]{0,18}')->defaults('catalog', $catalog)->name($catalog.'.update');
            Route::delete('/'.$catalog.'/{record}', [CatalogController::class, 'destroy'])->where('record', '[1-9][0-9]{0,18}')->defaults('catalog', $catalog)->name($catalog.'.destroy');
        });
        Route::get('/'.$catalog.'/{record}', [CatalogController::class, 'show'])->where('record', '[1-9][0-9]{0,18}')->defaults('catalog', $catalog)->name($catalog.'.show');
    }
    Route::middleware('admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{record}/edit', [UserController::class, 'edit'])->where('record', '[1-9][0-9]{0,18}')->name('users.edit');
        Route::get('/users/{record}', [UserController::class, 'show'])->where('record', '[1-9][0-9]{0,18}')->name('users.show');
        Route::match(['PUT', 'PATCH'], '/users/{record}', [UserController::class, 'update'])->where('record', '[1-9][0-9]{0,18}')->name('users.update');
        Route::delete('/users/{record}', [UserController::class, 'destroy'])->where('record', '[1-9][0-9]{0,18}')->name('users.destroy');
    });
});
