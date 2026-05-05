<?php

namespace App\Support;

/**
 * Customer-facing money formatting (EGP market: LE suffix).
 */
final class Money
{
    public static function format(float|string|null $amount, ?string $currency = null): string
    {
        $n = number_format((float) $amount, 2);

        return match (strtoupper((string) ($currency ?? 'EGP'))) {
            'EGP', 'USD', 'LE' => $n.' LE',
            'AED' => $n.' AED',
            default => $n.' '.strtoupper((string) $currency),
        };
    }
}
