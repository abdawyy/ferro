<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use SoftDeletes, HasTranslations;

    protected $fillable = [
        'slug', 'title', 'content', 'meta_title', 'meta_description',
        'is_published', 'sort_order', 'template',
    ];

    public array $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    protected $casts = [
        'is_published' => 'boolean',
        'sort_order'   => 'integer',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
