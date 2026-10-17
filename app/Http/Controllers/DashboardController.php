<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $statistics = [
            'products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'categories' => ProductCategory::count(),
            'units' => Unit::count(),
        ];
        if ($request->user()->role === 'admin') {
            $statistics['users'] = User::count();
        }
        $categories = ProductCategory::withCount('products')->orderByDesc('products_count')->orderBy('name')->get();
        $products = Product::with(['category', 'unit'])->orderBy('name')->orderBy('id')->limit(5)->get();

        return view('dashboard', compact('statistics', 'categories', 'products'));
    }
}
