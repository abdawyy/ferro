<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'product_id',
        'product_sku', 'product_name_en', 'product_name_ar',
        'quantity', 'unit_price', 'discount_amount',
        'tax_rate', 'tax_amount', 'line_total',
        'image_path', 'product_options',
    ];

    protected $casts = [
        'unit_price'      => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'tax_rate'        => 'decimal:4',
        'tax_amount'      => 'decimal:4',
        'line_total'      => 'decimal:4',
        'product_options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Display name: use locale or fallback to English.
     */
    public function getProductNameAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ar' && $this->product_name_ar) {
            return $this->product_name_ar;
        }
        return $this->product_name_en;
    }
}
