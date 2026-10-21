<?php

namespace App\Support;

use Closure;
use Illuminate\Validation\ValidationException;

final class SaleMoney
{
    public const MAX = 999999999999999999;

    public static function cents(mixed $value): int
    {
        if ((! is_string($value) && ! is_int($value)) || ! preg_match('/^(0|[1-9][0-9]{0,15})(?:\.([0-9]{1,2}))?$/D', (string) $value, $parts)) {
            throw ValidationException::withMessages(['unit_price' => 'Tiền dùng dấu chấm, không âm, tối đa 16 chữ số nguyên và 2 số lẻ.']);
        }
        return ((int) $parts[1]) * 100 + (int) str_pad($parts[2] ?? '', 2, '0');
    }

    public static function decimal(int $cents): string
    {
        if ($cents < 0 || $cents > self::MAX) throw ValidationException::withMessages(['total' => 'Tổng tiền vượt phạm vi DECIMAL(18,2).']);
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }

    public static function add(int $total, int $amount): int
    {
        if ($total < 0 || $total > self::MAX || $amount < 0 || $amount > self::MAX - $total) {
            throw ValidationException::withMessages(['total' => 'Tổng tiền vượt phạm vi DECIMAL(18,2).']);
        }
        return $total + $amount;
    }

    public static function line(string $quantity, string $price): string
    {
        $milli = StockQuantity::milli($quantity, true);
        $cents = self::cents($price);
        $whole = intdiv($milli, 1000);
        if ($cents > 0 && $whole > intdiv(self::MAX, $cents)) self::decimal(self::MAX + 1);
        $fraction = $milli % 1000;
        $remainder = $fraction * intdiv($cents, 1000) + intdiv($fraction * ($cents % 1000) + 500, 1000);
        return self::decimal($whole * $cents + $remainder);
    }

    public static function rule(): Closure
    {
        return static function (string $attribute, mixed $value, Closure $fail): void {
            try { self::cents($value); } catch (ValidationException $e) { $fail($e->errors()['unit_price'][0]); }
        };
    }
}
