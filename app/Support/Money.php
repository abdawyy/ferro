<?php

namespace App\Support;

/**
 * Customer-facing money formatting. Egypt storefront displays LE (livre / local).
 */
final class Money
{
    public static function format(float|string|null $amount, ?string $currency = null): string
    {
        $n = number_format((float) $amount, 2);

        return match (strtoupper((string) ($currency ?? 'EGP'))) {
            'EGP', 'LE' => "{$n} LE",
            'USD' => "{$n} USD",
            'AED' => "{$n} AED",
            default => "{$n} ".strtoupper((string) $currency),
        };
    }
}
