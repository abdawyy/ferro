<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorefrontSeoPage;
use App\Services\StorefrontSeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontSeoController extends Controller
{
    public function edit(): View
    {
        $keys = StorefrontSeoService::allowedPageKeys();
        $labels = (array) config('ferro_storefront_seo.labels', []);
        $defaults = (array) config('ferro_storefront_seo.pages', []);
        $rows = StorefrontSeoPage::query()->whereIn('page_key', $keys)->get()->keyBy('page_key');

        return view('admin.storefront-seo.edit', compact('keys', 'labels', 'defaults', 'rows'));
    }

    public function update(Request $request): RedirectResponse
    {
        $keys = StorefrontSeoService::allowedPageKeys();
        $rules = [];
        foreach ($keys as $key) {
            $rules["seo.$key.meta_title_en"] = ['nullable', 'string', 'max:300'];
            $rules["seo.$key.meta_title_ar"] = ['nullable', 'string', 'max:300'];
            $rules["seo.$key.meta_description_en"] = ['nullable', 'string', 'max:2000'];
            $rules["seo.$key.meta_description_ar"] = ['nullable', 'string', 'max:2000'];
            $rules["seo.$key.meta_keywords_en"] = ['nullable', 'string', 'max:500'];
            $rules["seo.$key.meta_keywords_ar"] = ['nullable', 'string', 'max:500'];
        }
        $data = $request->validate($rules);
        $seo = $data['seo'] ?? [];

        foreach ($keys as $key) {
            $fields = $seo[$key] ?? [];
            $payload = [
                'meta_title_en' => $fields['meta_title_en'] ?? null,
                'meta_title_ar' => $fields['meta_title_ar'] ?? null,
                'meta_description_en' => $fields['meta_description_en'] ?? null,
                'meta_description_ar' => $fields['meta_description_ar'] ?? null,
                'meta_keywords_en' => $fields['meta_keywords_en'] ?? null,
                'meta_keywords_ar' => $fields['meta_keywords_ar'] ?? null,
            ];
            if (! $this->rowHasContent($payload)) {
                StorefrontSeoPage::query()->where('page_key', $key)->delete();

                continue;
            }
            StorefrontSeoPage::query()->updateOrCreate(
                ['page_key' => $key],
                $payload
            );
        }

        return back()->with('success', __('admin.storefront_seo.saved'));
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function rowHasContent(array $payload): bool
    {
        foreach ($payload as $v) {
            if ($v !== null && $v !== '') {
                return true;
            }
        }

        return false;
    }
}
