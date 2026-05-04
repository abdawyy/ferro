<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ShippingCity extends Model
{
    use HasTranslations;

    protected $fillable = [
        'slug',
        'name',
        'shipping_price',
        'currency',
        'sort_order',
        'is_active',
    ];

    public array $translatable = ['name'];

    protected $casts = [
        'shipping_price' => 'decimal:4',
        'is_active'      => 'boolean',
        'sort_order'     => 'integer',
    ];
}
