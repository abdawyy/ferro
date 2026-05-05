<?php

use App\Support\Money;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (! function_exists('ferro_public_url')) {
    /**
     * URL for a path stored in public/ (e.g. images/…) or on the public disk (e.g. products/… after upload).
     */
    function ferro_public_url(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }
        if (Str::startsWith($path, 'images/')) {
            return asset($path);
        }

        return Storage::disk('public')->url($path);
    }
}

if (! function_exists('ferro_money')) {
    /**
     * Format an amount for display (e.g. 1,234.56 EGP).
     */
    function ferro_money(float|string|null $amount, ?string $currency = null): string
    {
        return Money::format($amount, $currency);
    }
}
