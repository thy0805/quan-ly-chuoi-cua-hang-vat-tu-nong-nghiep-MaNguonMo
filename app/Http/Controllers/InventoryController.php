<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryListRequest;
use App\Http\Requests\InventoryMovementRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(InventoryListRequest $request)
    {
        $filters = $request->validated();
        $query = Product::query()->with(['stock', 'category', 'unit']);
        $this->filterProducts($query, $filters);
        if (($filters['stock_status'] ?? null) === 'positive') $query->whereHas('stock', fn ($stock) => $stock->where('quantity', '>', '0.000'));
        if (($filters['stock_status'] ?? null) === 'zero') $query->where(fn ($zero) => $zero->whereDoesntHave('stock')->orWhereHas('stock', fn ($stock) => $stock->where('quantity', '0.000')));
        $records = $query->orderBy('name')->orderBy('id')->paginate($filters['per_page'] ?? 20)->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();

        return $request->expectsJson() ? response()->json($records) : view('inventory.index', compact('records', 'categories'));
    }

    public function history(InventoryListRequest $request)
    {
        $filters = $request->validated();
        $query = InventoryMovement::with(['product.unit', 'user'])->whereHas('product', fn ($products) => $this->filterProducts($products, $filters));
        if (! empty($filters['direction'])) $query->where('direction', $filters['direction']);
        if (! empty($filters['from'])) $query->where('created_at', '>=', $filters['from'].' 00:00:00');
        if (! empty($filters['to'])) $query->where('created_at', '<', \Carbon\CarbonImmutable::parse($filters['to'], 'UTC')->addDay()->format('Y-m-d').' 00:00:00');
        $records = $query->orderByDesc('id')->paginate($filters['per_page'] ?? 20)->withQueryString();
        $categories = ProductCategory::orderBy('name')->get();

        return $request->expectsJson() ? response()->json($records) : view('inventory.history', compact('records', 'categories'));
    }

    public function create(Request $request)
    {
        $direction = $request->route('direction');
        $products = Product::with(['stock', 'unit'])->where('is_active', true)->orderBy('name')->orderBy('id')->get();
        $idempotencyKey = (string) Str::uuid();

        return view('inventory.form', compact('direction', 'products', 'idempotencyKey'));
    }

    public function store(InventoryMovementRequest $request, InventoryService $inventory)
    {
        $movement = $inventory->move($request->user(), [...$request->validated(), 'direction' => $request->route('direction')]);

        return $request->expectsJson() ? response()->json(['data' => $movement]) : redirect()->route('inventory.history', ['product_id' => $movement->product_id])->with('status', 'Đã ghi nhận phiếu kho #'.$movement->id.'. Tồn sau giao dịch: '.$movement->quantity_after.'. Gửi lại cùng mã xác nhận không tạo phiếu mới.');
    }

    private function filterProducts($query, array $filters): void
    {
        if (! empty($filters['q'])) {
            $query->where(fn ($search) => $search->whereRaw('LOWER(code) LIKE ?', ['%'.mb_strtolower($filters['q']).'%'])->orWhereRaw('LOWER(name) LIKE ?', ['%'.mb_strtolower($filters['q']).'%']));
        }
        foreach (['product_id' => 'id', 'category_id' => 'category_id'] as $filter => $column) {
            if (! empty($filters[$filter])) $query->where($column, $filters[$filter]);
        }
    }
}
