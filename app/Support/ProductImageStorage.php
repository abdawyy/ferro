<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Product images on shared hosting (Hostinger): save under public/uploads so Apache/LiteSpeed
 * can serve them directly — no storage symlink or Laravel /storage route required.
 */
final class ProductImageStorage
{
    public const FEATURED_DIR = 'uploads/products';

    public const GALLERY_DIR = 'uploads/products/gallery';

    public static function storeFeatured(UploadedFile $file): string
    {
        return self::store($file, self::FEATURED_DIR);
    }

    public static function storeGallery(UploadedFile $file): string
    {
        return self::store($file, self::GALLERY_DIR);
    }

    public static function store(UploadedFile $file, string $relativeDir): string
    {
        $dir = public_path($relativeDir);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = $file->hashName();
        $file->move($dir, $filename);

        return $relativeDir.'/'.$filename;
    }

    public static function delete(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalized, 'uploads/')) {
            $full = public_path($normalized);
            if (is_file($full)) {
                @unlink($full);
            }

            return;
        }

        Storage::disk('public')->delete($normalized);
    }

    public static function publicRelativePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalized, ['images/', 'uploads/'])) {
            return $normalized;
        }

        if (Str::startsWith($normalized, 'storage/')) {
            return $normalized;
        }

        return 'storage/'.$normalized;
    }

    public static function isLegacyStoragePath(string $path): bool
    {
        $normalized = ltrim(str_replace('\\', '/', $path), '/');

        return Str::startsWith($normalized, 'products/')
            && ! Str::startsWith($normalized, ['uploads/', 'images/', 'storage/']);
    }

    /**
     * Copy a legacy storage-disk path into public/uploads (for Hostinger migration).
     */
    public static function migrateLegacyPath(string $path, string $targetDir): ?string
    {
        $normalized = ltrim(str_replace('\\', '/', $path), '/');
        if (! Storage::disk('public')->exists($normalized)) {
            return null;
        }

        $destDir = public_path($targetDir);
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $filename = basename($normalized);
        $dest = $destDir.DIRECTORY_SEPARATOR.$filename;
        $source = Storage::disk('public')->path($normalized);

        if (! copy($source, $dest)) {
            return null;
        }

        return $targetDir.'/'.$filename;
    }
}
