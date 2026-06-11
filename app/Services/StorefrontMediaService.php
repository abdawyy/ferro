<?php

namespace App\Services;

use App\Models\StorefrontMedia;

class StorefrontMediaService
{
    /**
     * @return array<string, array{label: string, group: string, fallback: string}>
     */
    public function definitions(): array
    {
        $heroes = (array) config('ferro.page_backgrounds.heroes', []);
        $backdrops = (array) config('ferro.page_backgrounds.backdrop_routes', []);

        $defs = [
            'brand.logo' => [
                'label' => 'Site logo (nav, emails, PDF)',
                'group' => 'brand',
                'fallback' => 'images/brand/ferro-hex-logo.png',
            ],
            'brand.og_default' => [
                'label' => 'Default social share image (OG / Twitter)',
                'group' => 'brand',
                'fallback' => 'images/ferro-og-default.png',
            ],
            'brand.favicon' => [
                'label' => 'Favicon',
                'group' => 'brand',
                'fallback' => 'favicon.svg',
            ],
            'brand.apple_touch' => [
                'label' => 'Apple touch icon',
                'group' => 'brand',
                'fallback' => 'images/apple-touch-icon.png',
            ],
        ];

        $heroLabels = [
            'home' => 'Home hero',
            'shop' => 'Shop hero',
            'about' => 'About hero',
            'contact' => 'Contact hero',
            'quiz' => 'Quiz background',
            'brand_story' => 'Home brand story section',
            'about_story' => 'About story section',
        ];

        foreach ($heroLabels as $key => $label) {
            $defs['hero.'.$key] = [
                'label' => $label,
                'group' => 'heroes',
                'fallback' => $heroes[$key] ?? 'images/brand/ferro-hex-logo.png',
            ];
        }

        $backdropLabels = [
            '__default' => 'Default backdrop (all pages)',
            'home' => 'Home',
            'about' => 'About',
            'contact' => 'Contact',
            'quiz' => 'Quiz',
            'products.index' => 'Shop listing',
            'products.show' => 'Product detail',
            'cart' => 'Cart',
            'checkout' => 'Checkout',
            'account' => 'Account',
            'orders.show' => 'Order detail',
            'login' => 'Login',
            'register' => 'Register',
            'password.request' => 'Forgot password',
            'password.reset' => 'Reset password',
        ];

        foreach ($backdropLabels as $route => $label) {
            $defs['backdrop.'.$route] = [
                'label' => $label,
                'group' => 'backdrops',
                'fallback' => $backdrops[$route] ?? ($backdrops['__default'] ?? 'images/brand/ferro-texture-dark.png'),
            ];
        }

        $quizStepLabels = ['Lifestyle', 'Skin concern', 'Routine', 'Skin feel', 'Goal'];
        for ($i = 0; $i < 5; $i++) {
            $defs['quiz.step.'.$i] = [
                'label' => 'Quiz step '.($i + 1).' header — '.$quizStepLabels[$i],
                'group' => 'quiz',
                'fallback' => 'https://picsum.photos/seed/ferrot'.($i + 1).'/720/400',
            ];
            for ($j = 0; $j < 4; $j++) {
                $defs['quiz.option.'.$i.'.'.$j] = [
                    'label' => 'Quiz step '.($i + 1).' option '.($j + 1),
                    'group' => 'quiz',
                    'fallback' => 'https://picsum.photos/seed/ferro'.$i.$j.'/720/420',
                ];
            }
        }

        return $defs;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function groupedDefinitions(): array
    {
        $groups = [
            'brand' => ['title' => 'Brand & SEO', 'items' => []],
            'heroes' => ['title' => 'Page hero images', 'items' => []],
            'backdrops' => ['title' => 'Page backdrops', 'items' => []],
            'quiz' => ['title' => 'Quiz images', 'items' => []],
        ];

        foreach ($this->definitions() as $key => $meta) {
            $groups[$meta['group']]['items'][$key] = $meta + [
                'current' => StorefrontMedia::pathFor($key),
                'url' => $this->url($key),
            ];
        }

        return $groups;
    }

    public function path(string $key): string
    {
        $custom = StorefrontMedia::pathFor($key);
        if ($custom !== null) {
            return $custom;
        }

        $defs = $this->definitions();

        if (isset($defs[$key])) {
            return $defs[$key]['fallback'];
        }

        if (str_starts_with($key, 'backdrop.')) {
            return $defs['backdrop.__default']['fallback'] ?? '';
        }

        return '';
    }

    public function url(string $key): ?string
    {
        $path = $this->path($key);
        if ($path === '') {
            return null;
        }

        $url = ferro_public_url($path);
        if ($url === null) {
            return null;
        }

        $custom = StorefrontMedia::pathFor($key);
        if ($custom !== null) {
            $url .= (str_contains($url, '?') ? '&' : '?').'v='.substr(md5($custom), 0, 10);
        }

        return $url;
    }

    public function backdropPath(?string $routeName = null): string
    {
        $routeName = $routeName ?? request()->route()?->getName();
        $key = 'backdrop.'.($routeName ?? '__default');
        $defs = $this->definitions();

        if (! isset($defs[$key])) {
            $key = 'backdrop.__default';
        }

        return $this->path($key);
    }

    public function backdropPosition(?string $routeName = null): string
    {
        $routeName = $routeName ?? request()->route()?->getName();
        $positions = (array) config('ferro.page_backgrounds.backdrop_position', []);

        if ($routeName !== null && array_key_exists($routeName, $positions)) {
            return (string) $positions[$routeName];
        }

        return (string) ($positions['__default'] ?? 'center');
    }

    /**
     * Flat map for quiz JS: step.0, option.0.0, etc. => full URL
     *
     * @return array<string, string>
     */
    public function quizImageMap(): array
    {
        $map = [];
        for ($i = 0; $i < 5; $i++) {
            $stepUrl = $this->url('quiz.step.'.$i);
            if ($stepUrl) {
                $map['step.'.$i] = $stepUrl;
            }
            for ($j = 0; $j < 4; $j++) {
                $optUrl = $this->url('quiz.option.'.$i.'.'.$j);
                if ($optUrl) {
                    $map['option.'.$i.'.'.$j] = $optUrl;
                }
            }
        }

        return $map;
    }

    /**
     * Absolute filesystem path for PDF generation.
     */
    public function absolutePath(string $key): ?string
    {
        $path = $this->path($key);
        if ($path === '' || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return null;
        }

        $full = public_path(ltrim(str_replace('\\', '/', $path), '/'));

        return is_file($full) ? $full : null;
    }

    public function showLogo(): bool
    {
        if (! $this->brandSettingsTableExists()) {
            return false;
        }

        return (bool) StorefrontBrandSetting::current()->show_logo;
    }

    public function showFavicon(): bool
    {
        if (! $this->brandSettingsTableExists()) {
            return false;
        }

        return (bool) StorefrontBrandSetting::current()->show_favicon;
    }

    /** Custom uploaded logo only; null = use default F mark in views. */
    public function visibleLogoUrl(): ?string
    {
        if (! $this->showLogo()) {
            return null;
        }

        if (StorefrontMedia::pathFor('brand.logo') === null) {
            return null;
        }

        return $this->url('brand.logo');
    }

    /** Always returns a favicon (custom when enabled, otherwise default SVG). */
    public function visibleFaviconUrl(): ?string
    {
        if ($this->showFavicon() && StorefrontMedia::pathFor('brand.favicon') !== null) {
            return $this->url('brand.favicon');
        }

        return ferro_public_url('favicon.svg') ?? asset('favicon.svg');
    }

    /** Always returns an Apple touch icon (custom when enabled, otherwise default). */
    public function visibleAppleTouchUrl(): ?string
    {
        if ($this->showFavicon() && StorefrontMedia::pathFor('brand.apple_touch') !== null) {
            return $this->url('brand.apple_touch');
        }

        return ferro_public_url('images/apple-touch-icon.png') ?? asset('images/apple-touch-icon.png');
    }

    /** Absolute logo path for PDFs when logo is enabled. */
    public function visibleLogoAbsolutePath(): ?string
    {
        if (! $this->showLogo()) {
            return null;
        }

        return $this->absolutePath('brand.logo');
    }

    private function brandSettingsTableExists(): bool
    {
        return \Illuminate\Support\Facades\Schema::hasTable('storefront_brand_settings');
    }
}
