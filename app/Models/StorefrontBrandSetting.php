<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontBrandSetting extends Model
{
    private static ?self $memoryCache = null;

    protected $fillable = [
        'show_logo',
        'show_favicon',
    ];

    protected function casts(): array
    {
        return [
            'show_logo' => 'boolean',
            'show_favicon' => 'boolean',
        ];
    }

    public static function current(): self
    {
        if (self::$memoryCache instanceof self) {
            return self::$memoryCache;
        }

        $row = static::query()->first();
        if ($row !== null) {
            self::$memoryCache = $row;

            return $row;
        }

        self::$memoryCache = static::query()->create([
            'show_logo' => false,
            'show_favicon' => false,
        ]);

        return self::$memoryCache;
    }

    public static function flushMemoryCache(): void
    {
        self::$memoryCache = null;
    }
}
