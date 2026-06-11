<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsletterCampaign extends Model
{
    const SEND_TO_ALL = 'all';

    const SEND_TO_SELECTED = 'selected';

    const STATUS_DRAFT = 'draft';

    const STATUS_SENT = 'sent';

    protected $fillable = [
        'subject_en',
        'subject_ar',
        'body_en',
        'body_ar',
        'product_id',
        'send_to',
        'status',
        'sent_count',
        'sent_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'sent_count' => 'integer',
            'sent_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(NewsletterSubscriber::class, 'newsletter_campaign_subscriber')
            ->withPivot(['sent_at', 'failed'])
            ->withTimestamps();
    }

    public function subject(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar'
            ? ($this->subject_ar ?: $this->subject_en)
            : ($this->subject_en ?: $this->subject_ar));
    }

    public function body(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar'
            ? ($this->body_ar ?: $this->body_en)
            : ($this->body_en ?: $this->body_ar));
    }

    public function isSent(): bool
    {
        return $this->status === self::STATUS_SENT;
    }
}
