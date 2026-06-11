<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontMedia extends Model
{
    protected $fillable = [
        'key',
        'path',
    ];

    /** @var array<string, string|null> */
    private static array $memoryCache = [];

    public static function pathFor(string $key): ?string
    {
        if (array_key_exists($key, self::$memoryCache)) {
            return self::$memoryCache[$key];
        }

        $path = static::query()->where('key', $key)->value('path');
        self::$memoryCache[$key] = $path !== null && $path !== '' ? (string) $path : null;

        return self::$memoryCache[$key];
    }

    public static function setPath(string $key, ?string $path): void
    {
        if ($path === null || $path === '') {
            static::query()->where('key', $key)->delete();
            self::$memoryCache[$key] = null;

            return;
        }

        static::query()->updateOrCreate(
            ['key' => $key],
            ['path' => $path]
        );

        self::$memoryCache[$key] = $path;
    }

    public static function flushMemoryCache(): void
    {
        self::$memoryCache = [];
    }

    /**
     * @return array<string, string|null>
     */
    public static function allPaths(): array
    {
        return static::query()->pluck('path', 'key')->all();
    }
}
