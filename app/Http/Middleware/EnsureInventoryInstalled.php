<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnsureInventoryInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (! Schema::hasTable('product_stocks') || ! Schema::hasTable('inventory_movements')) {
            if ($request->expectsJson()) return response()->json(['message' => 'Chức năng kho chưa được cài đặt trên database này. Không tự chạy migration.'], 503);

            return response()->view('inventory.unavailable', [], 503);
        }

        return $next($request);
    }
}
