<?php

use App\Support\Money;

if (! function_exists('ferro_money')) {
    /**
     * Format an amount for display (e.g. 1,234.56 LE).
     */
    function ferro_money(float|string|null $amount, ?string $currency = null): string
    {
        return Money::format($amount, $currency);
    }
}
