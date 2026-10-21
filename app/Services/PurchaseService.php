<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Supplier;
use App\Support\DecimalId;
use App\Support\SaleMoney;
use App\Support\StockQuantity;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class PurchaseService
{
    public static function rules(): array
    {
        return ['supplier_id' => ['required', 'string', DecimalId::rule()], 'client_key' => ['required', 'uuid'], 'note' => ['nullable', 'string', 'max:1000'], 'lines' => ['required', 'array', 'min:1', 'max:50'], 'lines.*' => ['required', 'array:product_id,quantity,unit_price'], 'lines.*.product_id' => ['required', 'string', DecimalId::rule(), 'distinct:strict'], 'lines.*.quantity' => ['required', StockQuantity::rule()], 'lines.*.unit_price' => ['required', SaleMoney::rule()]];
    }

    public function draft(User $actor, array $input, ?string $id = null): Purchase
    {
        $data = Validator::make($input, [...self::rules(), ...($id ? ['version' => ['required', 'integer', 'min:1', 'max:4294967294']] : [])])->validate();
        $data['note'] = trim($data['note'] ?? '') ?: null;
        $data['client_key'] = strtolower($data['client_key']);
        foreach ($data['lines'] as &$line) {
            $line['quantity'] = StockQuantity::decimal(StockQuantity::milli($line['quantity'], true));
            $line['unit_price'] = SaleMoney::decimal(SaleMoney::cents($line['unit_price']));
        }
        unset($line);
        usort($data['lines'], static fn ($a, $b) => (int) $a['product_id'] <=> (int) $b['product_id']);
        $hash = hash('sha256', json_encode([$data['supplier_id'], $data['note'], $data['lines']], JSON_THROW_ON_ERROR));
        try {
            return DB::transaction(function () use ($actor, $data, $id, $hash) {
                $current = $this->actor($actor);
                $purchase = $id ? Purchase::lockForUpdate()->findOrFail(DecimalId::parse($id, 'purchase')) : Purchase::where('client_key', $data['client_key'])->lockForUpdate()->first();
                if ($purchase) {
                    $this->owner($current, $purchase);
                    if (! $id) {
                        if ($purchase->payload_hash !== $hash) throw ValidationException::withMessages(['client_key' => 'Mã phiếu đã dùng với nội dung khác.']);
                        return $purchase->load('lines');
                    }
                    if ($purchase->status !== 'draft') throw ValidationException::withMessages(['purchase' => 'Phiếu nhập đã xác nhận không thể sửa.']);
                    if ($purchase->version !== (int) $data['version']) throw ValidationException::withMessages(['version' => 'Phiếu đã thay đổi. Tải lại trước khi sửa/xác nhận.']);
                    if ($purchase->client_key !== $data['client_key']) throw ValidationException::withMessages(['client_key' => 'Không được đổi mã phiếu.']);
                }
                $supplier = Supplier::lockForUpdate()->find($data['supplier_id']);
                if (! $supplier?->is_active) throw ValidationException::withMessages(['supplier_id' => 'Nhà cung cấp không tồn tại hoặc đã ngừng hoạt động.']);
                $products = Product::with('unit')->whereIn('id', array_column($data['lines'], 'product_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $total = 0;
                $rows = [];
                foreach ($data['lines'] as $index => $line) {
                    $product = $products->get($line['product_id']);
                    if (! $product?->is_active) throw ValidationException::withMessages(["lines.$index.product_id" => 'Chỉ nhập vật tư đang hoạt động.']);
                    $amount = SaleMoney::line($line['quantity'], $line['unit_price']);
                    $total = SaleMoney::add($total, SaleMoney::cents($amount));
                    $rows[] = [...$line, 'product_code' => $product->code, 'product_name' => $product->name, 'unit_name' => $product->unit->name, 'line_total' => $amount];
                }
                if (! $purchase) $purchase = new Purchase(['user_id' => $current->id, 'status' => 'draft', 'version' => 0]);
                $purchase->fill(['supplier_id' => $supplier->id, 'supplier_code' => $supplier->code, 'supplier_name' => $supplier->name, 'supplier_address' => $supplier->address, 'supplier_phone' => $supplier->phone, 'client_key' => $data['client_key'], 'payload_hash' => $hash, 'note' => $data['note'], 'total' => SaleMoney::decimal($total), 'version' => $purchase->version + 1])->save();
                $purchase->lines()->delete();
                $purchase->lines()->createMany($rows);
                return $purchase->load('lines');
            }, 3);
        } catch (QueryException $error) {
            if (! $id && ($error->errorInfo[1] ?? null) === 1062) {
                $purchase = Purchase::where('client_key', $data['client_key'])->first();
                if ($purchase) {
                    $current = $this->actor($actor);
                    $this->owner($current, $purchase);
                    if ($purchase->payload_hash === $hash) return $purchase->load('lines');
                    throw ValidationException::withMessages(['client_key' => 'Mã phiếu đã dùng với nội dung khác.']);
                }
            }
            throw $error;
        }
    }

    public function confirm(User $actor, string $id, mixed $version): Purchase
    {
        Validator::make(['version' => $version], ['version' => ['required', 'integer', 'min:1', 'max:4294967294']])->validate();
        return DB::transaction(function () use ($actor, $id, $version) {
            $current = $this->actor($actor);
            $purchase = Purchase::lockForUpdate()->findOrFail(DecimalId::parse($id, 'purchase'));
            $this->owner($current, $purchase);
            if ($purchase->version !== (int) $version) throw ValidationException::withMessages(['version' => 'Phiếu đã thay đổi. Tải lại trước khi sửa/xác nhận.']);
            if ($purchase->status === 'confirmed') return $purchase->load('lines');
            $supplier = Supplier::lockForUpdate()->find($purchase->supplier_id);
            if (! $supplier?->is_active) throw ValidationException::withMessages(['supplier_id' => 'Nhà cung cấp đã ngừng hoạt động.']);
            $lines = $purchase->lines()->orderBy('product_id')->lockForUpdate()->get();
            if ($lines->isEmpty()) throw ValidationException::withMessages(['lines' => 'Phiếu phải có ít nhất một vật tư.']);
            Product::whereIn('id', $lines->pluck('product_id'))->orderBy('id')->lockForUpdate()->get();
            $total = 0;
            foreach ($lines as $line) {
                if ($line->line_total !== SaleMoney::line($line->quantity, $line->unit_price)) throw ValidationException::withMessages(['total' => 'Tiền dòng không khớp.']);
                $total = SaleMoney::add($total, SaleMoney::cents($line->line_total));
                $product = Product::with('unit')->findOrFail($line->product_id);
                if ($product->unit->name !== $line->unit_name) throw ValidationException::withMessages(['lines' => 'Đơn vị tính đã đổi; cần lưu lại phiếu nháp trước khi xác nhận.']);
                app(InventoryService::class)->receive($current, $line);
            }
            if ($purchase->total !== SaleMoney::decimal($total)) throw ValidationException::withMessages(['total' => 'Tổng phiếu không khớp.']);
            $purchase->fill(['status' => 'confirmed', 'confirmed_at' => now()])->save();
            return $purchase->load('lines');
        }, 3);
    }

    private function actor(User $actor): User
    {
        $current = User::query()->sharedLock()->find($actor->id);
        abort_unless($current?->is_active && $current->role === 'admin', 403);
        return $current;
    }

    private function owner(User $actor, Purchase $purchase): void
    {
        abort_unless($purchase->user_id === $actor->id, 403, 'Chỉ người lập được sửa hoặc xác nhận phiếu nhập của mình.');
    }
}
