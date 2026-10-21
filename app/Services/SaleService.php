<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Support\DecimalId;
use App\Support\SaleMoney;
use App\Support\StockQuantity;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

final class SaleService
{
    public static function rules(): array
    {
        return ['client_key' => ['required', 'uuid'], 'note' => ['nullable', 'string', 'max:1000'], 'lines' => ['required', 'array', 'min:1', 'max:50'], 'lines.*' => ['required', 'array:product_id,quantity,unit_price'], 'lines.*.product_id' => ['required', 'string', DecimalId::rule(), 'distinct:strict'], 'lines.*.quantity' => ['required', StockQuantity::rule()], 'lines.*.unit_price' => ['required', SaleMoney::rule()]];
    }

    public function draft(User $actor, array $input, ?string $id = null): Sale
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
        $hash = hash('sha256', json_encode([$data['note'], $data['lines']], JSON_THROW_ON_ERROR));
        try {
            return DB::transaction(function () use ($actor, $data, $id, $hash) {
                $current = $this->actor($actor);
                $sale = $id ? Sale::lockForUpdate()->findOrFail(DecimalId::parse($id, 'sale')) : Sale::where('client_key', $data['client_key'])->lockForUpdate()->first();
                if ($sale) {
                    $this->owner($current, $sale);
                    if (! $id) {
                        if ($sale->payload_hash !== $hash) throw ValidationException::withMessages(['client_key' => 'Mã phiếu đã dùng với nội dung khác.']);
                        return $sale->load('lines');
                    }
                    if ($sale->status !== 'draft') throw ValidationException::withMessages(['sale' => 'Hóa đơn đã xác nhận không thể sửa.']);
                    if ($sale->version !== (int) $data['version']) throw ValidationException::withMessages(['version' => 'Phiếu đã thay đổi. Tải lại trước khi sửa/xác nhận.']);
                    if ($sale->client_key !== $data['client_key']) throw ValidationException::withMessages(['client_key' => 'Không được đổi mã phiếu.']);
                }
                $products = Product::with('unit')->whereIn('id', array_column($data['lines'], 'product_id'))->orderBy('id')->lockForUpdate()->get()->keyBy('id');
                $total = 0;
                $rows = [];
                foreach ($data['lines'] as $index => $line) {
                    $product = $products->get($line['product_id']);
                    if (! $product?->is_active) throw ValidationException::withMessages(["lines.$index.product_id" => 'Chỉ bán vật tư đang hoạt động.']);
                    $amount = SaleMoney::line($line['quantity'], $line['unit_price']);
                    $total = SaleMoney::add($total, SaleMoney::cents($amount));
                    $rows[] = [...$line, 'product_code' => $product->code, 'product_name' => $product->name, 'unit_name' => $product->unit->name, 'line_total' => $amount];
                }
                if (! $sale) $sale = new Sale(['user_id' => $current->id, 'status' => 'draft', 'version' => 0]);
                $sale->fill(['client_key' => $data['client_key'], 'payload_hash' => $hash, 'note' => $data['note'], 'total' => SaleMoney::decimal($total), 'version' => $sale->version + 1])->save();
                $sale->lines()->delete();
                $sale->lines()->createMany($rows);
                return $sale->load('lines');
            }, 3);
        } catch (QueryException $error) {
            if (! $id && ($error->errorInfo[1] ?? null) === 1062) {
                $sale = Sale::where('client_key', $data['client_key'])->first();
                if ($sale) {
                    $current = $this->actor($actor);
                    $this->owner($current, $sale);
                    if ($sale->payload_hash === $hash) return $sale->load('lines');
                    throw ValidationException::withMessages(['client_key' => 'Mã phiếu đã dùng với nội dung khác.']);
                }
            }
            throw $error;
        }
    }

    public function confirm(User $actor, string $id, mixed $version): Sale
    {
        Validator::make(['version' => $version], ['version' => ['required', 'integer', 'min:1', 'max:4294967294']])->validate();
        return DB::transaction(function () use ($actor, $id, $version) {
            $current = $this->actor($actor);
            $sale = Sale::lockForUpdate()->findOrFail(DecimalId::parse($id, 'sale'));
            $this->owner($current, $sale);
            if ($sale->version !== (int) $version) throw ValidationException::withMessages(['version' => 'Phiếu đã thay đổi. Tải lại trước khi sửa/xác nhận.']);
            if ($sale->status === 'confirmed') return $sale->load('lines');
            $lines = $sale->lines()->orderBy('product_id')->lockForUpdate()->get();
            if ($lines->isEmpty()) throw ValidationException::withMessages(['lines' => 'Phiếu phải có ít nhất một vật tư.']);
            Product::whereIn('id', $lines->pluck('product_id'))->orderBy('id')->lockForUpdate()->get();
            $total = 0;
            foreach ($lines as $line) {
                if ($line->line_total !== SaleMoney::line($line->quantity, $line->unit_price)) throw ValidationException::withMessages(['total' => 'Tiền dòng không khớp.']);
                $total = SaleMoney::add($total, SaleMoney::cents($line->line_total));
                app(InventoryService::class)->sell($current, $line);
            }
            if ($sale->total !== SaleMoney::decimal($total)) throw ValidationException::withMessages(['total' => 'Tổng phiếu không khớp.']);
            $sale->fill(['status' => 'confirmed', 'confirmed_at' => now()])->save();
            return $sale->load('lines');
        }, 3);
    }

    private function actor(User $actor): User
    {
        $current = User::query()->sharedLock()->find($actor->id);
        abort_unless($current?->is_active && in_array($current->role, ['admin', 'user'], true), 403);
        return $current;
    }

    private function owner(User $actor, Sale $sale): void
    {
        abort_unless($sale->user_id === $actor->id, 403, 'Chỉ người lập được sửa hoặc xác nhận phiếu bán của mình.');
    }
}
