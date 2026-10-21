<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleListRequest;
use App\Http\Requests\SaleWriteRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use App\Support\DecimalId;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(SaleListRequest $request)
    {
        $data = $request->validated();
        $query = $this->visible($request)->with('user')->withCount('lines');
        if (! empty($data['q'])) {
            $term = $data['q'];
            $query->where(function ($search) use ($term) {
                $search->where('note', 'like', '%'.$term.'%')->orWhereHas('lines', fn ($lines) => $lines->where('product_code', 'like', '%'.$term.'%')->orWhere('product_name', 'like', '%'.$term.'%'));
                if (preg_match('/^(?:BH-)?0*([1-9][0-9]{0,18})$/iD', $term, $match)) {
                    try { $search->orWhere('id', DecimalId::parse($match[1], 'q')); } catch (ValidationException) {}
                }
            });
        }
        if (! empty($data['status'])) $query->where('status', $data['status']);
        if (! empty($data['from'])) $query->where('created_at', '>=', $data['from'].' 00:00:00');
        if (! empty($data['to'])) $query->where('created_at', '<', \Carbon\CarbonImmutable::parse($data['to'], 'UTC')->addDay()->format('Y-m-d').' 00:00:00');
        $records = $query->orderByDesc('id')->paginate($data['per_page'] ?? 20)->withQueryString();
        return $request->expectsJson() ? response()->json($records) : view('sales.index', compact('records'));
    }

    public function create(Request $request)
    {
        $sale = null;
        $clientKey = (string) Str::uuid();
        $products = $this->products();
        return view('sales.form', compact('sale', 'clientKey', 'products'));
    }

    public function store(SaleWriteRequest $request, SaleService $service)
    {
        return $this->saved($request, $service->draft($request->user(), $request->validated()));
    }

    public function show(Request $request)
    {
        $sale = $this->record($request)->load(['lines', 'user']);
        return $request->expectsJson() ? response()->json(['data' => $sale]) : view('sales.show', compact('sale'));
    }

    public function edit(Request $request)
    {
        $sale = $this->record($request)->load('lines');
        abort_unless($sale->user_id === $request->user()->id, 403);
        if ($sale->status !== 'draft') throw ValidationException::withMessages(['sale' => 'Hóa đơn đã xác nhận không thể sửa.']);
        $clientKey = $sale->client_key;
        $products = $this->products();
        return view('sales.form', compact('sale', 'clientKey', 'products'));
    }

    public function update(SaleWriteRequest $request, SaleService $service)
    {
        $sale = $this->record($request);
        return $this->saved($request, $service->draft($request->user(), $request->validated(), $sale->id));
    }

    public function confirm(Request $request, SaleService $service)
    {
        $sale = $this->record($request);
        $request->validate(['version' => ['required', 'integer', 'min:1', 'max:4294967294']]);
        return $this->saved($request, $service->confirm($request->user(), $sale->id, $request->input('version')));
    }

    public function print(Request $request)
    {
        $sale = $this->record($request)->load(['lines', 'user']);
        abort_unless($sale->status === 'confirmed', 409, 'Phiếu nháp chưa có hóa đơn.');
        return view('sales.print', compact('sale'));
    }

    private function products()
    {
        return Product::with(['unit', 'stock'])->where('is_active', true)->orderBy('name')->orderBy('id')->get();
    }

    private function visible(Request $request)
    {
        return Sale::query()->when($request->user()->role !== 'admin', fn ($q) => $q->where('user_id', $request->user()->id));
    }

    private function record(Request $request): Sale
    {
        try { $id = DecimalId::parse((string) $request->route('sale'), 'sale'); } catch (ValidationException) { abort(404); }
        return $this->visible($request)->findOrFail($id);
    }

    private function saved(Request $request, Sale $sale)
    {
        return $request->expectsJson() ? response()->json(['data' => $sale]) : redirect()->route('sales.show', $sale->id)->with('status', $sale->status === 'draft' ? 'Đã lưu nháp. Tồn kho chưa thay đổi.' : 'Đã xác nhận hóa đơn '.$sale->number.'. Tồn kho chỉ được trừ một lần.');
    }
}
