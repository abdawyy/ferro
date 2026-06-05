<?php

use App\Support\Money;
use Illuminate\Support\Str;

if (! function_exists('ferro_request_asset_root')) {
    /**
     * Root URL for assets (scheme + host + port + base path).
     * Uses the current HTTP request when available so image URLs match the browser (e.g. 127.0.0.1:8000),
     * instead of always using APP_URL (often http://localhost with no port).
     */
    function ferro_request_asset_root(): string
    {
        if (! app()->runningInConsole() && app()->bound('request') && request()->hasHeader('Host')) {
            return rtrim(request()->getSchemeAndHttpHost().request()->getBaseUrl(), '/');
        }

        return rtrim((string) config('app.url'), '/');
    }
}

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

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalized, 'images/')) {
            return asset($normalized);
        }

        if (Str::startsWith($normalized, 'storage/')) {
            return asset($normalized);
        }

        return asset('storage/'.$normalized);
    }
}

if (! function_exists('ferro_money')) {
    /**
     * Format an amount for display (e.g. 1,234.56 LE).
     */
    function ferro_money(float|string|null $amount, ?string $currency = null): string
    {
        return Money::format($amount, $currency);
    }
}

if (! function_exists('ferro_storefront_seo')) {
    /**
     * Meta tags for storefront pages (admin-overridable via storefront_seo_pages).
     *
     * @param  array<string, string|int|float>  $replacements
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string}
     */
    function ferro_storefront_seo(string $pageKey, array $replacements = []): array
    {
        return app(\App\Services\StorefrontSeoService::class)->forPage($pageKey, $replacements);
    }
}
