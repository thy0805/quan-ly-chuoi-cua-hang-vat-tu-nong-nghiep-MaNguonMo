<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EnsureSalesInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (! Schema::hasTable('sales') || ! Schema::hasTable('sale_lines')) {
            $message = 'Bán hàng chưa được cài đặt trên database này. Không tự chạy migration.';
            return $request->expectsJson() ? response()->json(['message' => $message], 503) : response()->view('sales.unavailable', compact('message'), 503);
        }
        return $next($request);
    }
}
