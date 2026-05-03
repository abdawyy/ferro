<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'image_path',
        'sort_order',
        'is_active',
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
