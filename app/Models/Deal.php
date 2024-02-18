<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mews\Purifier\Casts\CleanHtml;

class Deal extends Model
{
    use HasFactory;
    protected $table = 'deals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'thumbnail',
        'gallery',
        'user_id',
        'category_id',
        'seo_id',
        'is_active',
        'is_featured',
        'title',
        'slug',
        'description',
        'price',
        'discount_type',
        'discount_value',
        'terms_and_conditions',
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'gallery' => 'array',
        'description' => CleanHtml::class
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seo() : BelongsTo
    {
        return $this->belongsTo(Seo::class);
    }

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'user_id', 'user_id');
    }
}
