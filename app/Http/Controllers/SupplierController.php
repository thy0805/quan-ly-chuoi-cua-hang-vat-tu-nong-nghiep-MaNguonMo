<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierListRequest;
use App\Http\Requests\SupplierWriteRequest;
use App\Models\Supplier;
use App\Models\User;
use App\Support\DecimalId;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    public function index(SupplierListRequest $request)
    {
        $data = $request->validated();
        $query = Supplier::query()->withCount('purchases');
        if (! empty($data['q'])) $query->where(function ($q) use ($data) {
            foreach (['code', 'name', 'phone', 'email', 'address'] as $field) $q->orWhere($field, 'like', '%'.$data['q'].'%');
        });
        if (isset($data['is_active'])) $query->where('is_active', $data['is_active']);
        $records = $query->orderBy('name')->orderBy('id')->paginate($data['per_page'] ?? 20)->withQueryString();
        return $request->expectsJson() ? response()->json($records) : view('suppliers.index', compact('records'));
    }

    public function create() { return view('suppliers.form', ['supplier' => null]); }
    public function edit(Request $request) { return view('suppliers.form', ['supplier' => $this->record($request)]); }
    public function show(Request $request)
    {
        $supplier = $this->record($request)->loadCount('purchases');
        return $request->expectsJson() ? response()->json(['data' => $supplier]) : view('suppliers.show', compact('supplier'));
    }
    public function store(SupplierWriteRequest $request) { return $this->write($request); }
    public function update(SupplierWriteRequest $request) { return $this->write($request, $this->record($request)->id); }

    public function destroy(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->admin($request);
                $this->record($request, true)->delete();
            }, 3);
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1451) throw ValidationException::withMessages(['supplier' => 'Nhà cung cấp đã có phiếu nhập; hãy chuyển sang ngừng hoạt động.']);
            throw $error;
        }
        return $request->expectsJson() ? response()->noContent() : redirect()->route('suppliers.index')->with('status', 'Đã xóa nhà cung cấp chưa được sử dụng.');
    }

    private function write(SupplierWriteRequest $request, ?string $id = null)
    {
        try {
            $supplier = DB::transaction(function () use ($request, $id) {
                $this->admin($request);
                $supplier = $id ? Supplier::lockForUpdate()->findOrFail($id) : new Supplier;
                $supplier->fill($request->validated())->save();
                return $supplier;
            }, 3);
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) throw ValidationException::withMessages(['code' => 'Mã nhà cung cấp đã được sử dụng.']);
            throw $error;
        }
        return $request->expectsJson() ? response()->json(['data' => $supplier], $id ? 200 : 201) : redirect()->route('suppliers.show', $supplier->id)->with('status', 'Đã lưu nhà cung cấp.');
    }

    private function admin(Request $request): void
    {
        $actor = User::sharedLock()->find($request->user()->id);
        abort_unless($actor?->is_active && $actor->role === 'admin', 403);
    }

    private function record(Request $request, bool $lock = false): Supplier
    {
        try { $id = DecimalId::parse((string) $request->route('supplier'), 'supplier'); } catch (ValidationException) { abort(404); }
        return Supplier::query()->when($lock, fn ($q) => $q->lockForUpdate())->findOrFail($id);
    }
}
