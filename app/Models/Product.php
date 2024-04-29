<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;
    protected $table='products';
    protected $fillable = [
        'company_id',
        'claimed_by', // 'claimed_by' is the id of the user who claimed the product
        'category_id',
        'seo_id',
        'is_active',
        'is_featured',
        'is_claimed',
        'is_approved',
        'name',
        'slug',
        'description',
        'price',
        'condition',
        'brand',
        'thumbnail',
        'gallery',
        'color',
        'size',
        'material'
    ];
    protected $casts = [
        'gallery' => 'array',
        'price' => 'decimal:2',
        'is_approved' => 'boolean',
        'is_claimed' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function company() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function claimedBy() : BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function seo() : BelongsTo
    {
        return $this->belongsTo(Seo::class);
    }

    public function getReviews() : array | object
    {
        return RateReview::where('type', 'product')->where('item_id', $this->id)->paginate(3);
    }

    public function getReviewsCount() : int
    {
        return RateReview::where('type', 'product')->where('item_id', $this->id)->count();
    }
}
