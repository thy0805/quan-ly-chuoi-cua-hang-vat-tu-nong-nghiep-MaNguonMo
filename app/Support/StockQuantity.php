<?php

namespace App\Support;

use Closure;
use Illuminate\Validation\ValidationException;

final class StockQuantity
{
    public const MAX = 999999999999999;

    public static function milli(mixed $value, bool $positive = false): int
    {
        if ((! is_string($value) && ! is_int($value)) || ! preg_match('/^(0|[1-9][0-9]{0,11})(?:\.([0-9]{1,3}))?$/D', (string) $value, $parts)) {
            throw ValidationException::withMessages(['quantity' => 'Số lượng dùng dấu chấm, tối đa 12 chữ số nguyên và 3 chữ số thập phân.']);
        }
        $quantity = ((int) $parts[1]) * 1000 + (int) str_pad($parts[2] ?? '', 3, '0');
        if ($quantity > self::MAX || ($positive && $quantity === 0)) {
            throw ValidationException::withMessages(['quantity' => 'Số lượng phải lớn hơn 0 và không vượt 999999999999.999.']);
        }

        return $quantity;
    }

    public static function decimal(int $milli): string
    {
        if ($milli < 0 || $milli > self::MAX) {
            throw ValidationException::withMessages(['quantity' => 'Số lượng tồn vượt phạm vi cho phép.']);
        }

        return intdiv($milli, 1000).'.'.str_pad((string) ($milli % 1000), 3, '0', STR_PAD_LEFT);
    }

    public static function rule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            try {
                self::milli($value, true);
            } catch (ValidationException $error) {
                $fail($error->errors()['quantity'][0]);
            }
        };
    }
}
