<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StorefrontBrandSetting;
use App\Models\StorefrontMedia;
use App\Services\StorefrontMediaService;
use App\Support\StorefrontMediaStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class StorefrontMediaController extends Controller
{
    private const FILE_RULES = 'nullable|file|mimes:jpeg,jpg,png,gif,webp,svg|max:8192';

    public function __construct(private readonly StorefrontMediaService $media) {}

    public function edit(): View
    {
        StorefrontMedia::flushMemoryCache();
        $groups = $this->media->groupedDefinitions();
        $needsMigration = ! Schema::hasTable('storefront_media');
        $brandSettings = Schema::hasTable('storefront_brand_settings')
            ? StorefrontBrandSetting::current()
            : null;

        return view('admin.storefront-media.edit', compact('groups', 'needsMigration', 'brandSettings'));
    }

    public function update(Request $request): RedirectResponse
    {
        if (! Schema::hasTable('storefront_media')) {
            return back()->with('error', __('admin.storefront_media.migration_required'));
        }

        if (Schema::hasTable('storefront_brand_settings')) {
            $brand = StorefrontBrandSetting::current();
            $newShowLogo = $request->boolean('show_logo');
            $newShowFavicon = $request->boolean('show_favicon');
            if ($brand->show_logo !== $newShowLogo || $brand->show_favicon !== $newShowFavicon) {
                $saved++;
            }
            $brand->update([
                'show_logo' => $newShowLogo,
                'show_favicon' => $newShowFavicon,
            ]);
            StorefrontBrandSetting::flushMemoryCache();
        }

        $defs = $this->media->definitions();
        $uploads = $request->file('media', []);
        $removeFlags = $request->input('remove', []);

        if (! is_array($uploads)) {
            $uploads = [];
        }
        if (! is_array($removeFlags)) {
            $removeFlags = [];
        }

        $saved = 0;

        foreach (array_keys($defs) as $key) {
            if ($this->flagIsTruthy($removeFlags[$key] ?? null)) {
                $old = StorefrontMedia::pathFor($key);
                StorefrontMediaStorage::delete($old);
                StorefrontMedia::setPath($key, null);
                $saved++;

                continue;
            }

            $file = $uploads[$key] ?? null;
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $validated = validator(['file' => $file], ['file' => self::FILE_RULES]);
            if ($validated->fails()) {
                throw ValidationException::withMessages([
                    "media.{$key}" => $validated->errors()->first('file'),
                ]);
            }

            $subdir = str_replace('.', '/', $key);
            $old = StorefrontMedia::pathFor($key);
            $path = StorefrontMediaStorage::store($file, $subdir);
            StorefrontMedia::setPath($key, $path);
            if ($old !== null && $old !== $path) {
                StorefrontMediaStorage::delete($old);
            }
            $saved++;
        }

        StorefrontMedia::flushMemoryCache();
        StorefrontBrandSetting::flushMemoryCache();

        return redirect()->route('admin.storefront-media.edit')
            ->with('success', $saved > 0
                ? __('admin.storefront_media.saved_count', ['count' => $saved])
                : __('admin.storefront_media.nothing_changed'));
    }

    private function flagIsTruthy(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 'on', 'yes'], true);
    }
}
