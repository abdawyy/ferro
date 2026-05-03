<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitlistEntry extends Model
{
    use HasFactory;

    protected $table = 'waitlist_entries';

    protected $fillable = [
        'product_id',
        'lead_id',
        'email',
        'preferred_language',
        'position',
        'notified',
        'notified_at',
        'purchased_at',
    ];

    protected $casts = [
        'notified' => 'boolean',
        'notified_at' => 'datetime',
        'purchased_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
