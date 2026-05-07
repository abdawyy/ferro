<?php

namespace App\Services;

use App\Models\StorefrontSeoPage;

final class StorefrontSeoService
{
    /**
     * @return list<string>
     */
    public static function allowedPageKeys(): array
    {
        return array_keys((array) config('ferro_storefront_seo.pages', []));
    }

    /**
     * @param  array<string, string|int|float>  $replacements  e.g. ['order_number' => 'FERRO-2026-00001']
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string}
     */
    public function forPage(string $pageKey, array $replacements = []): array
    {
        $defaults = config('ferro_storefront_seo.pages')[$pageKey] ?? null;
        if ($defaults === null) {
            return $this->fallback();
        }

        $row = StorefrontSeoPage::query()->where('page_key', $pageKey)->first();
        $locale = app()->getLocale();
        $fallbackLocale = $locale === 'ar' ? 'en' : 'ar';

        $pick = function (string $field) use ($row, $defaults, $locale, $fallbackLocale): string {
            $colPrimary = $field.'_'.$locale;
            $colAlt = $field.'_'.$fallbackLocale;
            $fromRow = $row?->{$colPrimary};
            if ($fromRow !== null && $fromRow !== '') {
                return (string) $fromRow;
            }
            $defPrimary = $defaults[$field][$locale] ?? '';
            if ($defPrimary !== '') {
                return (string) $defPrimary;
            }
            $fromRowAlt = $row?->{$colAlt};
            if ($fromRowAlt !== null && $fromRowAlt !== '') {
                return (string) $fromRowAlt;
            }

            return (string) ($defaults[$field][$fallbackLocale] ?? '');
        };

        $title = $this->interpolate($pick('meta_title'), $replacements);
        $description = $this->interpolate($pick('meta_description'), $replacements);
        $keywords = $this->interpolate($pick('meta_keywords'), $replacements);

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $title,
            'og_description' => $description,
        ];
    }

    /**
     * @param  array<string, string|int|float>  $replacements
     */
    private function interpolate(string $value, array $replacements): string
    {
        foreach ($replacements as $key => $v) {
            $value = str_replace(':'.ltrim((string) $key, ':'), (string) $v, $value);
        }

        return $value;
    }

    /**
     * @return array{title: string, description: string, keywords: string, og_title: string, og_description: string}
     */
    private function fallback(): array
    {
        $title = 'FERRO — Forged from Iron, Polished by Luxury';
        $desc = 'Premium natural grooming essentials engineered for the high-performance man.';

        return [
            'title' => $title,
            'description' => $desc,
            'keywords' => 'FERRO, mens grooming',
            'og_title' => $title,
            'og_description' => $desc,
        ];
    }
}
