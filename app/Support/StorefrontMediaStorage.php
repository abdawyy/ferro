<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Brand / hero / backdrop uploads under public/uploads/brand (shared-hosting friendly).
 */
final class StorefrontMediaStorage
{
    public const UPLOAD_DIR = 'uploads/brand';

    public static function store(UploadedFile $file, ?string $subdir = null): string
    {
        $dir = self::UPLOAD_DIR.($subdir ? '/'.$subdir : '');
        $absolute = public_path($dir);
        if (! is_dir($absolute)) {
            mkdir($absolute, 0755, true);
        }

        $filename = $file->hashName();
        $file->move($absolute, $filename);

        return $dir.'/'.$filename;
    }

    public static function delete(?string $path): void
    {
        ProductImageStorage::delete($path);
    }
}
