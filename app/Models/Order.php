<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'lead_id', 'status', 'payment_status',
        'subtotal', 'discount_amount', 'shipping_amount', 'tax_amount', 'tax_rate', 'total',
        'currency', 'exchange_rate', 'coupon_code', 'discount_type',
        'billing_address', 'shipping_address',
        'shipping_method', 'tracking_number', 'carrier',
        'shipped_at', 'delivered_at',
        'payment_method', 'payment_transaction_id', 'payment_metadata', 'paid_at',
        'invoice_number', 'invoice_pdf_path', 'invoice_generated_at',
        'language', 'customer_notes', 'admin_notes', 'metadata',
    ];

    protected $casts = [
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'payment_metadata' => 'array',
        'metadata' => 'array',
        'subtotal' => 'decimal:4',
        'discount_amount' => 'decimal:4',
        'shipping_amount' => 'decimal:4',
        'tax_amount' => 'decimal:4',
        'tax_rate' => 'decimal:4',
        'total' => 'decimal:4',
        'exchange_rate' => 'decimal:6',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'paid_at' => 'datetime',
        'invoice_generated_at' => 'datetime',
    ];

    // ── Status constants ───────────────────────────────────────────────────
    const STATUS_PENDING = 'pending_payment';

    const STATUS_CONFIRMED = 'confirmed';

    const STATUS_PROCESSING = 'processing';

    const STATUS_SHIPPED = 'shipped';

    const STATUS_DELIVERED = 'delivered';

    const STATUS_CANCELLED = 'cancelled';

    const STATUS_REFUNDED = 'refunded';

    // ── Order number generation ────────────────────────────────────────────
    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (! $order->order_number) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        $year = now()->format('Y');
        $last = static::whereYear('created_at', $year)->lockForUpdate()->count();
        $sequence = str_pad($last + 1, 5, '0', STR_PAD_LEFT);

        return "FERRO-{$year}-{$sequence}";
    }

    // ── Relationships ──────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(OrderReturnRequest::class);
    }

    /**
     * Email used for transactional messages (matches HandleOrderPlaced recipient logic).
     */
    public function customerFacingEmail(): ?string
    {
        return $this->user?->email
            ?? $this->lead?->email
            ?? ($this->billing_address['email'] ?? null)
            ?? ($this->shipping_address['email'] ?? null);
    }

    /**
     * Single-line shipping summary for emails (checkout uses address + name keys).
     */
    public function shippingSummaryForMail(): string
    {
        $s = $this->shipping_address ?? [];
        $name = $s['name'] ?? trim(($s['first_name'] ?? '').' '.($s['last_name'] ?? ''));

        $parts = array_filter([
            $name !== '' ? $name : null,
            $s['address'] ?? $s['address_line1'] ?? null,
            $s['address_line2'] ?? null,
            trim(implode(', ', array_filter([
                $s['city'] ?? null,
                $s['state'] ?? null,
                $s['postal_code'] ?? $s['zip'] ?? null,
                $s['country'] ?? null,
            ]))),
        ]);

        return implode(', ', $parts);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ── Accessors ─────────────────────────────────────────────────────────
    public function getFormattedTotalAttribute(): string
    {
        return Money::format($this->total, $this->currency);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => __('order.status.pending_payment'),
            self::STATUS_CONFIRMED => __('order.status.confirmed'),
            self::STATUS_PROCESSING => __('order.status.processing'),
            self::STATUS_SHIPPED => __('order.status.shipped'),
            self::STATUS_DELIVERED => __('order.status.delivered'),
            self::STATUS_CANCELLED => __('order.status.cancelled'),
            self::STATUS_REFUNDED => __('order.status.refunded'),
            default => ucfirst($this->status),
        };
    }

    public function hasInvoice(): bool
    {
        return (bool) $this->invoice_pdf_path;
    }

    /**
     * Alias accessor: shipping_cost → shipping_amount
     * Used in Blade views and email templates.
     */
    public function getShippingCostAttribute(): string
    {
        return $this->shipping_amount;
    }
}
