<?php

namespace App\Services;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Support\DecimalId;
use App\Support\StockQuantity;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class InventoryService
{
    public function move(User $actor, array $input): InventoryMovement
    {
        $data = Validator::make($input, [
            'product_id' => ['required', 'string', DecimalId::rule()],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'quantity' => ['required', StockQuantity::rule()],
            'note' => ['nullable', 'string', 'max:1000'],
            'idempotency_key' => ['required', 'uuid'],
            'source_type' => ['sometimes', Rule::in(['manual', 'sale', 'purchase'])],
            'source_id' => ['nullable', 'string', 'regex:/^[A-Za-z0-9_-]{1,64}$/D'],
        ])->validate();
        $data['quantity'] = StockQuantity::decimal(StockQuantity::milli($data['quantity'], true));
        $data['note'] = trim($data['note'] ?? '');
        if ($data['note'] === '') $data['note'] = null;
        $data['idempotency_key'] = strtolower($data['idempotency_key']);
        $data['source_type'] = $data['source_type'] ?? 'manual';
        $data['source_id'] = $data['source_id'] ?? null;
        if (($data['source_type'] === 'manual') !== ($data['source_id'] === null)) {
            throw ValidationException::withMessages(['source_id' => 'Tham chiếu nguồn không hợp lệ.']);
        }
        try {
            return DB::transaction(function () use ($actor, $data) {
                $currentActor = User::query()->sharedLock()->find($actor->id);
                abort_unless($currentActor?->is_active && $currentActor->role === 'admin', 403, 'Chỉ Admin đang hoạt động được nhập/xuất kho.');
                $product = Product::query()->lockForUpdate()->find($data['product_id']);
                if (! $product) throw ValidationException::withMessages(['product_id' => 'Vật tư không tồn tại.']);
                $existing = InventoryMovement::where('idempotency_key', $data['idempotency_key'])->first();
                if ($existing) return $this->replay($existing, $currentActor, $data);
                if (! $product->is_active) throw ValidationException::withMessages(['product_id' => 'Không thể nhập/xuất vật tư đã ngừng hoạt động.']);
                $stock = ProductStock::where('product_id', $product->id)->lockForUpdate()->first();
                $before = StockQuantity::milli($stock?->quantity ?? '0.000');
                $change = StockQuantity::milli($data['quantity'], true);
                if ($data['direction'] === 'out' && $change > $before) {
                    throw ValidationException::withMessages(['quantity' => 'Không đủ tồn kho. Số lượng hiện có: '.StockQuantity::decimal($before).'.']);
                }
                $after = StockQuantity::decimal($data['direction'] === 'in' ? $before + $change : $before - $change);
                $time = now();
                if ($stock) {
                    $stock->update(['quantity' => $after, 'updated_at' => $time]);
                } else {
                    ProductStock::create(['product_id' => $product->id, 'quantity' => $after, 'updated_at' => $time]);
                }

                return InventoryMovement::create([...$data, 'user_id' => $currentActor->id, 'quantity_before' => StockQuantity::decimal($before), 'quantity_after' => $after, 'created_at' => $time]);
            }, 3);
        } catch (QueryException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) {
                $existing = InventoryMovement::where('idempotency_key', $data['idempotency_key'])->first();
                if ($existing) return $this->replay($existing, $actor, $data);
                if ($data['source_id'] !== null && InventoryMovement::where('source_type', $data['source_type'])->where('source_id', $data['source_id'])->exists()) {
                    throw ValidationException::withMessages(['source_id' => 'Dòng nguồn đã được ghi kho. Hãy dùng lại mã xác nhận ban đầu.']);
                }
            }
            throw $error;
        }
    }

    private function replay(InventoryMovement $movement, User $actor, array $data): InventoryMovement
    {
        foreach (['product_id', 'direction', 'quantity', 'note', 'source_type', 'source_id'] as $field) {
            if ($movement->{$field} !== $data[$field]) {
                throw ValidationException::withMessages(['idempotency_key' => 'Mã xác nhận đã được dùng với nội dung khác. Hãy mở phiếu mới.']);
            }
        }
        if ($movement->user_id !== $actor->id) abort(403);

        return $movement;
    }
}
