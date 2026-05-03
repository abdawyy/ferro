<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

/**
 * FERRO Product Model
 *
 * Localization strategy: spatie/laravel-translatable with JSON columns.
 * WHY JSON columns over translation tables:
 *  - Single SELECT query — no JOINs for bilingual reads
 *  - Atomic updates per locale
 *  - Ideal for 2-locale setup (en + ar)
 *  - Less schema complexity than polymorphic translation tables
 *
 * @property string $status  'coming_soon' | 'active' | 'out_of_stock' | 'archived'
 */
class Product extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'category_id', 'sku', 'slug',
        'name', 'tagline', 'description', 'short_description',
        'ingredients', 'how_to_use', 'benefits',
        'seo_title', 'seo_description', 'seo_keywords',
        'price', 'compare_price', 'cost_price', 'currency',
        'status', 'stock_quantity', 'low_stock_threshold',
        'track_inventory', 'allow_backorder',
        'weight_grams', 'volume_ml', 'dimensions',
        'is_subscribable', 'subscription_intervals', 'quiz_tags',
        'featured_image', 'gallery_images', 'video_url',
        'is_featured', 'is_new_arrival', 'is_best_seller',
        'sort_order', 'available_at',
    ];

    /**
     * Translatable JSON columns — spatie/laravel-translatable handles
     * get/set magic: $product->name returns string in current app locale.
     * $product->getTranslation('name', 'ar') for explicit locale access.
     */
    public array $translatable = [
        'name', 'tagline', 'description', 'short_description',
        'ingredients', 'how_to_use', 'benefits',
        'seo_title', 'seo_description', 'seo_keywords',
    ];

    protected $casts = [
        'price'                  => 'decimal:2',
        'compare_price'          => 'decimal:2',
        'cost_price'             => 'decimal:2',
        'weight_grams'           => 'decimal:2',
        'dimensions'             => 'array',
        'subscription_intervals' => 'array',
        'quiz_tags'              => 'array',
        'gallery_images'         => 'array',
        'benefits'               => 'array',   // translatable array
        'is_subscribable'        => 'boolean',
        'is_featured'            => 'boolean',
        'is_new_arrival'         => 'boolean',
        'is_best_seller'         => 'boolean',
        'track_inventory'        => 'boolean',
        'allow_backorder'        => 'boolean',
        'available_at'           => 'datetime',
    ];

    // ── Status constants ───────────────────────────────────────────────────
    const STATUS_COMING_SOON  = 'coming_soon';
    const STATUS_ACTIVE       = 'active';
    const STATUS_OUT_OF_STOCK = 'out_of_stock';
    const STATUS_ARCHIVED     = 'archived';

    // ── Relationships ──────────────────────────────────────────────────────
    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function waitlistEntries()
    {
        return $this->hasMany(WaitlistEntry::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeComingSoon($query)
    {
        return $query->where('status', self::STATUS_COMING_SOON);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeVisible($query)
    {
        // Products shown on storefront (not archived)
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_COMING_SOON, self::STATUS_OUT_OF_STOCK]);
    }

    public function scopeSubscribable($query)
    {
        return $query->where('is_subscribable', true)->where('status', self::STATUS_ACTIVE);
    }

    // ── Accessors ─────────────────────────────────────────────────────────
    public function getIsOnSaleAttribute(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (! $this->is_on_sale) {
            return null;
        }
        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE && $this->stock_quantity > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->track_inventory
            && $this->stock_quantity > 0
            && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getStatusBadgeLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_COMING_SOON  => __('product.status.coming_soon'),
            self::STATUS_ACTIVE       => __('product.status.active'),
            self::STATUS_OUT_OF_STOCK => __('product.status.out_of_stock'),
            default                   => ucfirst($this->status),
        };
    }

    /**
     * Returns translated name safe for SEO meta.
     */
    public function getSeoTitleForLocale(string $locale): string
    {
        $custom = $this->getTranslation('seo_title', $locale, false);
        if ($custom) {
            return $custom;
        }
        $name = $this->getTranslation('name', $locale, false) ?: $this->name;
        return $locale === 'ar'
            ? "{$name} | فيرو"
            : "{$name} | FERRO";
    }

    /**
     * Schema.org Product structured data array for JSON-LD.
     */
    public function toSchemaOrg(string $locale = 'en'): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $this->getTranslation('name', $locale),
            'description' => strip_tags($this->getTranslation('short_description', $locale) ?? ''),
            'sku'         => $this->sku,
            'brand'       => ['@type' => 'Brand', 'name' => 'FERRO'],
            'image'       => $this->featured_image ? asset($this->featured_image) : null,
            'offers'      => [
                '@type'         => 'Offer',
                'priceCurrency' => $this->currency,
                'price'         => (string) $this->price,
                'availability'  => $this->status === self::STATUS_ACTIVE
                    ? 'https://schema.org/InStock'
                    : ($this->status === self::STATUS_OUT_OF_STOCK
                        ? 'https://schema.org/OutOfStock'
                        : 'https://schema.org/PreOrder'),
                'url'           => route('products.show', $this->slug),
            ],
        ];
    }
}
