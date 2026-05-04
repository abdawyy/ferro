<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ShopQuickFilter extends Model
{
    use HasTranslations;

    protected $fillable = [
        'product_status',
        'name',
        'is_active',
        'sort_order',
    ];

    public array $translatable = ['name'];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];
}
