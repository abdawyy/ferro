<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email', 'first_name', 'last_name', 'phone', 'country_code',
        'source', 'priority', 'status', 'preferred_language',
        'quiz_results', 'product_interests', 'utm_data', 'custom_attributes',
        'engagement_score', 'last_engaged_at', 'converted_at', 'converted_order_id',
        'on_waitlist', 'waitlist_product_id', 'waitlist_notified_at',
        'abandoned_cart_items', 'abandoned_cart_value', 'cart_abandoned_at',
        'recovery_emails_sent', 'last_recovery_sent_at',
        'marketing_consent', 'gdpr_consent', 'consented_at', 'ip_address',
    ];

    protected $casts = [
        'quiz_results'          => 'array',
        'product_interests'     => 'array',
        'utm_data'              => 'array',
        'custom_attributes'     => 'array',
        'abandoned_cart_items'  => 'array',
        'abandoned_cart_value'  => 'decimal:2',
        'on_waitlist'           => 'boolean',
        'marketing_consent'     => 'boolean',
        'gdpr_consent'          => 'boolean',
        'last_engaged_at'       => 'datetime',
        'converted_at'          => 'datetime',
        'waitlist_notified_at'  => 'datetime',
        'cart_abandoned_at'     => 'datetime',
        'last_recovery_sent_at' => 'datetime',
        'consented_at'          => 'datetime',
    ];

    // ── Source constants ───────────────────────────────────────────────────
    const SOURCE_WAITLIST       = 'waitlist';
    const SOURCE_QUIZ           = 'quiz';
    const SOURCE_ABANDONED_CART = 'abandoned_cart';
    const SOURCE_NEWSLETTER     = 'newsletter';
    const SOURCE_CHECKOUT       = 'checkout';
    const SOURCE_REFERRAL       = 'referral';

    // ── Priority constants ─────────────────────────────────────────────────
    const PRIORITY_STANDARD = 'standard';
    const PRIORITY_HIGH     = 'high';
    const PRIORITY_VIP      = 'vip';

    // ── Status constants ───────────────────────────────────────────────────
    const STATUS_NEW          = 'new';
    const STATUS_ENGAGED      = 'engaged';
    const STATUS_QUALIFIED    = 'qualified';
    const STATUS_CONVERTED    = 'converted';
    const STATUS_UNSUBSCRIBED = 'unsubscribed';

    // ── Relationships ──────────────────────────────────────────────────────
    public function waitlistProduct()
    {
        return $this->belongsTo(Product::class, 'waitlist_product_id');
    }

    public function convertedOrder()
    {
        return $this->belongsTo(Order::class, 'converted_order_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function waitlistEntries()
    {
        return $this->hasMany(WaitlistEntry::class);
    }

    public function quizSessions()
    {
        return $this->hasMany(QuizSession::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', [self::PRIORITY_HIGH, self::PRIORITY_VIP]);
    }

    public function scopeOnWaitlist($query)
    {
        return $query->where('on_waitlist', true)
                     ->whereNotIn('status', [self::STATUS_CONVERTED, self::STATUS_UNSUBSCRIBED]);
    }

    public function scopeAbandonedCart($query)
    {
        return $query->where('source', self::SOURCE_ABANDONED_CART)
                     ->whereNotNull('cart_abandoned_at')
                     ->whereNull('converted_at');
    }

    public function scopeForRecovery($query)
    {
        return $query->abandonedCart()
                     ->where('recovery_emails_sent', '<', 3)
                     ->where(function ($q) {
                         $q->whereNull('last_recovery_sent_at')
                           ->orWhere('last_recovery_sent_at', '<', now()->subHours(24));
                     });
    }

    // ── Business methods ───────────────────────────────────────────────────
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->email;
    }

    public function incrementEngagement(int $points = 1): void
    {
        $this->increment('engagement_score', $points);
        $this->update(['last_engaged_at' => now()]);

        // Auto-elevate priority based on score
        if ($this->engagement_score >= 50 && $this->priority === self::PRIORITY_STANDARD) {
            $this->update(['priority' => self::PRIORITY_HIGH]);
        } elseif ($this->engagement_score >= 100 && $this->priority === self::PRIORITY_HIGH) {
            $this->update(['priority' => self::PRIORITY_VIP]);
        }
    }

    public function markConverted(Order $order): void
    {
        $this->update([
            'status'             => self::STATUS_CONVERTED,
            'converted_at'       => now(),
            'converted_order_id' => $order->id,
            'on_waitlist'        => false,
        ]);
    }
}
