<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizSession extends Model
{
    protected $fillable = [
        'session_token',
        'user_id',
        'lead_id',
        'answers',
        'recommended_product_ids',
        'skin_profile',
        'email_captured',
    ];

    protected $casts = [
        'answers'                   => 'array',
        'recommended_product_ids' => 'array',
        'email_captured'            => 'boolean',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
