<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Middleware\EnsureInventoryInstalled;
use Illuminate\Support\Facades\Route;

Route::get('/login', [SessionController::class, 'create'])->name('login');
Route::post('/login', [SessionController::class, 'store'])->name('login.store');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/', fn () => redirect()->route('products.index'))->name('home');
    Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');
    Route::middleware(EnsureInventoryInstalled::class)->group(function () {
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/history', [InventoryController::class, 'history'])->name('inventory.history');
        Route::middleware('admin')->group(function () {
            foreach (['in', 'out'] as $direction) {
                Route::get('/inventory/'.$direction, [InventoryController::class, 'create'])->defaults('direction', $direction)->name('inventory.'.$direction.'.create');
                Route::post('/inventory/'.$direction, [InventoryController::class, 'store'])->defaults('direction', $direction)->name('inventory.'.$direction.'.store');
            }
        });
    });
    Route::middleware([EnsureInventoryInstalled::class, \App\Http\Middleware\EnsureSalesInstalled::class])->group(function () {
        Route::get('/sales', [\App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/create', [\App\Http\Controllers\SaleController::class, 'create'])->name('sales.create');
        Route::post('/sales', [\App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
        Route::get('/sales/{sale}', [\App\Http\Controllers\SaleController::class, 'show'])->where('sale', '[1-9][0-9]{0,18}')->name('sales.show');
        Route::get('/sales/{sale}/edit', [\App\Http\Controllers\SaleController::class, 'edit'])->where('sale', '[1-9][0-9]{0,18}')->name('sales.edit');
        Route::patch('/sales/{sale}', [\App\Http\Controllers\SaleController::class, 'update'])->where('sale', '[1-9][0-9]{0,18}')->name('sales.update');
        Route::post('/sales/{sale}/confirm', [\App\Http\Controllers\SaleController::class, 'confirm'])->where('sale', '[1-9][0-9]{0,18}')->name('sales.confirm');
        Route::get('/sales/{sale}/print', [\App\Http\Controllers\SaleController::class, 'print'])->where('sale', '[1-9][0-9]{0,18}')->name('sales.print');
    });
    Route::middleware([EnsureInventoryInstalled::class, \App\Http\Middleware\EnsurePurchasesInstalled::class])->group(function () {
        foreach (['suppliers' => \App\Http\Controllers\SupplierController::class, 'purchases' => \App\Http\Controllers\PurchaseController::class] as $section => $controller) {
            $parameter = $section === 'suppliers' ? 'supplier' : 'purchase';
            Route::get('/'.$section, [$controller, 'index'])->name($section.'.index');
            Route::middleware('admin')->group(function () use ($section, $controller, $parameter) {
                Route::get('/'.$section.'/create', [$controller, 'create'])->name($section.'.create');
                Route::post('/'.$section, [$controller, 'store'])->name($section.'.store');
                Route::get('/'.$section.'/{'.$parameter.'}/edit', [$controller, 'edit'])->where($parameter, '[1-9][0-9]{0,18}')->name($section.'.edit');
                Route::patch('/'.$section.'/{'.$parameter.'}', [$controller, 'update'])->where($parameter, '[1-9][0-9]{0,18}')->name($section.'.update');
                if ($section === 'suppliers') Route::delete('/suppliers/{supplier}', [$controller, 'destroy'])->where('supplier', '[1-9][0-9]{0,18}')->name('suppliers.destroy');
                else Route::post('/purchases/{purchase}/confirm', [$controller, 'confirm'])->where('purchase', '[1-9][0-9]{0,18}')->name('purchases.confirm');
            });
            Route::get('/'.$section.'/{'.$parameter.'}', [$controller, 'show'])->where($parameter, '[1-9][0-9]{0,18}')->name($section.'.show');
            if ($section === 'purchases') Route::get('/purchases/{purchase}/print', [$controller, 'print'])->where('purchase', '[1-9][0-9]{0,18}')->name('purchases.print');
        }
    });
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
