<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StorefrontSeoPage extends Model
{
    protected $fillable = [
        'page_key',
        'meta_title_en',
        'meta_title_ar',
        'meta_description_en',
        'meta_description_ar',
        'meta_keywords_en',
        'meta_keywords_ar',
    ];
}
