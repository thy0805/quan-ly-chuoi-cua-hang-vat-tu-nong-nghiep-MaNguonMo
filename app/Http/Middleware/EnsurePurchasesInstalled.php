<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnsurePurchasesInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (! Schema::hasTable('suppliers') || ! Schema::hasTable('purchases') || ! Schema::hasTable('purchase_lines')) {
            $message = 'Nhà cung cấp và nhập hàng chưa được cài đặt trên database này. Không tự chạy migration.';
            return $request->expectsJson() ? response()->json(['message' => $message], 503) : response()->view('purchases.unavailable', compact('message'), 503);
        }
        return $next($request);
    }
}
