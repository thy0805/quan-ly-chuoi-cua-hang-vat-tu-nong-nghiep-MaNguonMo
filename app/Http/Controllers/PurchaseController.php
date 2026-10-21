<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseListRequest;
use App\Http\Requests\PurchaseWriteRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Services\PurchaseService;
use App\Support\DecimalId;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(PurchaseListRequest $request)
    {
        $data = $request->validated();
        $query = $this->visible($request)->with(['user', 'supplier'])->withCount('lines');
        if (! empty($data['q'])) {
            $term = $data['q'];
            $query->where(function ($search) use ($term) {
                $search->where('note', 'like', '%'.$term.'%')->orWhere('supplier_code', 'like', '%'.$term.'%')->orWhere('supplier_name', 'like', '%'.$term.'%')->orWhereHas('lines', fn ($lines) => $lines->where('product_code', 'like', '%'.$term.'%')->orWhere('product_name', 'like', '%'.$term.'%'));
                if (preg_match('/^(?:PN-)?0*([1-9][0-9]{0,18})$/iD', $term, $match)) {
                    try { $search->orWhere('id', DecimalId::parse($match[1], 'q')); } catch (ValidationException) {}
                }
            });
        }
        if (! empty($data['supplier_id'])) $query->where('supplier_id', $data['supplier_id']);
        if (! empty($data['status'])) $query->where('status', $data['status']);
        if (! empty($data['from'])) $query->where('created_at', '>=', $data['from'].' 00:00:00');
        if (! empty($data['to'])) $query->where('created_at', '<', \Carbon\CarbonImmutable::parse($data['to'], 'UTC')->addDay()->format('Y-m-d').' 00:00:00');
        $records = $query->orderByDesc('id')->paginate($data['per_page'] ?? 20)->withQueryString();
        return $request->expectsJson() ? response()->json($records) : view('purchases.index', ['records' => $records, 'suppliers' => Supplier::orderBy('name')->get()]);
    }

    public function create(Request $request)
    {
        $purchase = null;
        $clientKey = (string) Str::uuid();
        $products = $this->products();
        return view('purchases.form', ['purchase' => $purchase, 'clientKey' => $clientKey, 'products' => $products, 'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get()]);
    }

    public function store(PurchaseWriteRequest $request, PurchaseService $service)
    {
        return $this->saved($request, $service->draft($request->user(), $request->validated()));
    }

    public function show(Request $request)
    {
        $purchase = $this->record($request)->load(['lines', 'user']);
        return $request->expectsJson() ? response()->json(['data' => $purchase]) : view('purchases.show', compact('purchase'));
    }

    public function edit(Request $request)
    {
        $purchase = $this->record($request)->load('lines');
        abort_unless($purchase->user_id === $request->user()->id, 403);
        if ($purchase->status !== 'draft') throw ValidationException::withMessages(['purchase' => 'Phiếu nhập đã xác nhận không thể sửa.']);
        $clientKey = $purchase->client_key;
        $products = $this->products();
        return view('purchases.form', ['purchase' => $purchase, 'clientKey' => $clientKey, 'products' => $products, 'suppliers' => Supplier::where('is_active', true)->orderBy('name')->get()]);
    }

    public function update(PurchaseWriteRequest $request, PurchaseService $service)
    {
        $purchase = $this->record($request);
        return $this->saved($request, $service->draft($request->user(), $request->validated(), $purchase->id));
    }

    public function confirm(Request $request, PurchaseService $service)
    {
        $purchase = $this->record($request);
        $request->validate(['version' => ['required', 'integer', 'min:1', 'max:4294967294']]);
        return $this->saved($request, $service->confirm($request->user(), $purchase->id, $request->input('version')));
    }

    public function print(Request $request)
    {
        $purchase = $this->record($request)->load(['lines', 'user']);
        abort_unless($purchase->status === 'confirmed', 409, 'Phiếu nháp chưa có phiếu nhập.');
        return view('purchases.print', compact('purchase'));
    }

    private function products()
    {
        return Product::with(['unit', 'stock'])->where('is_active', true)->orderBy('name')->orderBy('id')->get();
    }

    private function visible(Request $request)
    {
        return Purchase::query();
    }

    private function record(Request $request): Purchase
    {
        try { $id = DecimalId::parse((string) $request->route('purchase'), 'purchase'); } catch (ValidationException) { abort(404); }
        return $this->visible($request)->findOrFail($id);
    }

    private function saved(Request $request, Purchase $purchase)
    {
        return $request->expectsJson() ? response()->json(['data' => $purchase]) : redirect()->route('purchases.show', $purchase->id)->with('status', $purchase->status === 'draft' ? 'Đã lưu nháp. Tồn kho chưa thay đổi.' : 'Đã xác nhận phiếu nhập '.$purchase->number.'. Tồn kho chỉ được cộng một lần.');
    }
}
