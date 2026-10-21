<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatalogListRequest;
use App\Http\Requests\UserWriteRequest;
use App\Models\User;
use App\Support\DecimalId;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(CatalogListRequest $request)
    {
        $filters = $request->validated();
        $query = User::query();
        if ($request->boolean('active_only')) {
            $query->where('is_active', true);
        }
        if (($filters['q'] ?? '') !== '') {
            $query->whereRaw('LOWER(username) LIKE ?', ['%'.mb_strtolower($filters['q']).'%']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
        $records = $query->orderBy('username')->orderBy('id')->paginate($filters['per_page'] ?? 20)->withQueryString();

        return $request->expectsJson() ? response()->json($records) : view('users.index', compact('records'));
    }

    public function show(Request $request)
    {
        $record = $this->record($request);

        return $request->expectsJson() ? response()->json(['data' => $record]) : view('users.show', compact('record'));
    }

    public function create()
    {
        return view('users.form', ['record' => null]);
    }

    public function edit(Request $request)
    {
        return view('users.form', ['record' => $this->record($request)]);
    }

    public function store(UserWriteRequest $request)
    {
        $record = $this->write(fn () => DB::transaction(fn () => User::create($this->attributes($request)), 3));

        return $request->expectsJson() ? response()->json(['data' => $record->fresh()], 201) : redirect()->route('users.index')->with('status', 'Đã tạo tài khoản nhân viên.');
    }

    public function update(UserWriteRequest $request)
    {
        $record = $this->record($request);
        $this->write(fn () => DB::transaction(function () use ($record, $request) {
            User::query()->lockForUpdate()->findOrFail($record->id)->fill($this->attributes($request))->save();
        }, 3));

        return $request->expectsJson() ? response()->json(['data' => $record->fresh()]) : redirect()->route('users.index')->with('status', 'Đã cập nhật tài khoản.');
    }

    public function destroy(Request $request)
    {
        $record = $this->record($request);
        try {
            DB::transaction(fn () => User::query()->lockForUpdate()->findOrFail($record->id)->delete(), 3);
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1451) {
                throw ValidationException::withMessages(['delete' => 'Không thể xóa tài khoản đã có lịch sử kho.']);
            }
            throw $error;
        }

        return $request->expectsJson() ? response()->noContent() : redirect()->route('users.index')->with('status', 'Đã xóa tài khoản.');
    }

    private function attributes(UserWriteRequest $request): array
    {
        $attributes = $request->safe()->only(['username', 'is_active']);
        if ($request->filled('password')) {
            $attributes['password_hash'] = Hash::make($request->validated('password'));
        }

        return $attributes;
    }

    private function record(Request $request): User
    {
        try {
            $id = DecimalId::parse((string) $request->route('record'), 'id');
        } catch (ValidationException) {
            abort(404);
        }

        return User::findOrFail($id);
    }

    private function write(callable $operation)
    {
        try {
            return $operation();
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) {
                throw ValidationException::withMessages(['username' => 'Tên đăng nhập đã được sử dụng.']);
            }
            throw $error;
        }
    }
}
