<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email',
        'preferred_language',
        'coupon_code',
        'discount_percent',
        'coupon_expires_at',
        'subscribed_at',
        'unsubscribed_at',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'integer',
            'coupon_expires_at' => 'datetime',
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    public function isActive(): bool
    {
        return $this->unsubscribed_at === null;
    }

    public function unsubscribeToken(): string
    {
        return hash('sha256', $this->email.config('app.key'));
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterCampaign::class, 'newsletter_campaign_subscriber')
            ->withPivot(['sent_at', 'failed'])
            ->withTimestamps();
    }
}
