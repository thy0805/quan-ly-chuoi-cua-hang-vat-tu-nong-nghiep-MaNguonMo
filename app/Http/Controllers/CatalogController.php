<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogListRequest;
use App\Http\Requests\CatalogWriteRequest;
use App\Models\ProductCategory;
use App\Models\Unit;
use App\Services\CatalogDeletion;
use App\Support\CatalogResources;
use App\Support\DecimalId;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CatalogController extends Controller
{
    public function index(CatalogListRequest $request)
    {
        $catalog = $request->route('catalog');
        $definition = CatalogResources::definition($catalog);
        $filters = $request->validated();
        $query = $definition['model']::query()->with($definition['relations']);
        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }
        if (($filters['q'] ?? '') !== '') {
            $query->where(function ($query) use ($definition, $filters) {
                foreach ($definition['search'] as $column) {
                    $query->orWhereRaw('LOWER('.$column.') LIKE ?', ['%'.mb_strtolower($filters['q']).'%']);
                }
            });
        }
        foreach (['is_active', ...($catalog === 'products' ? ['category_id', 'unit_id'] : [])] as $key) {
            if (isset($filters[$key])) {
                $query->where($key, $filters[$key]);
            }
        }
        $records = $query->orderBy('name')->orderBy('id')->paginate($filters['per_page'] ?? 20)->withQueryString();

        return $request->expectsJson() ? response()->json($records) : view('catalog.index', compact('catalog', 'definition', 'records'));
    }

    public function show(Request $request)
    {
        $catalog = $request->route('catalog');
        $definition = CatalogResources::definition($catalog);
        $record = $this->record($request)->load($definition['relations']);

        return $request->expectsJson() ? response()->json(['data' => $record]) : view('catalog.show', compact('catalog', 'definition', 'record'));
    }

    public function create(Request $request)
    {
        return $this->form($request);
    }

    public function edit(Request $request)
    {
        return $this->form($request, $this->record($request));
    }

    public function store(CatalogWriteRequest $request)
    {
        $model = CatalogResources::definition($request->route('catalog'))['model'];
        $record = $this->write(fn () => DB::transaction(fn () => $model::create($request->validated()), 3));

        return $request->expectsJson() ? response()->json(['data' => $record->fresh()], 201) : redirect()->route($request->route('catalog').'.index')->with('status', 'Đã tạo dữ liệu.');
    }

    public function update(CatalogWriteRequest $request)
    {
        $record = $this->record($request);
        $this->write(fn () => DB::transaction(function () use ($record, $request) {
            $record->fill($request->validated())->save();
        }, 3));

        return $request->expectsJson() ? response()->json(['data' => $record->fresh()]) : redirect()->route($request->route('catalog').'.index')->with('status', 'Đã cập nhật dữ liệu.');
    }

    public function destroy(Request $request)
    {
        CatalogDeletion::delete($this->record($request));

        return $request->expectsJson() ? response()->noContent() : redirect()->route($request->route('catalog').'.index')->with('status', 'Đã xóa dữ liệu.');
    }

    private function record(Request $request)
    {
        $id = (string) $request->route('record');
        try {
            DecimalId::parse($id, 'id');
        } catch (ValidationException) {
            abort(404);
        }
        $model = CatalogResources::definition($request->route('catalog'))['model'];

        return $model::findOrFail($id);
    }

    private function write(callable $operation)
    {
        try {
            return $operation();
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) {
                throw ValidationException::withMessages(['code' => 'Mã đã được sử dụng.']);
            }
            throw $error;
        }
    }

    private function form(Request $request, $record = null)
    {
        $catalog = $request->route('catalog');
        $definition = CatalogResources::definition($catalog);
        $categories = ProductCategory::query()->where(function ($query) use ($record, $catalog) {
            $query->where('is_active', true);
            if ($catalog === 'products' && $record) {
                $query->orWhereKey($record->category_id);
            }
            if ($catalog === 'categories' && $record?->parent_id) {
                $query->orWhereKey($record->parent_id);
            }
        })->when($catalog === 'categories' && $record, fn ($query) => $query->where('id', '<>', $record->id))->orderBy('name')->orderBy('id')->get();
        $units = Unit::query()->where('is_active', true)->when($catalog === 'products' && $record, fn ($query) => $query->orWhereKey($record->unit_id))->orderBy('name')->orderBy('id')->get();

        return view('catalog.form', compact('catalog', 'definition', 'record', 'categories', 'units'));
    }
}
