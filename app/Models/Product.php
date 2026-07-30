<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'price',
        'offer_price',
        'tax',
        'stock',
        'gender',
        'fabric',
        'sleeve_type',
        'thumbnail',
        'is_featured',
        'is_new_arrival',
        'is_best_seller',
        'meta_title',
        'meta_description',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'tax' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_seller' => 'boolean',
        'status' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ----- Relationships -------------------------------------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    // ----- Accessors -----------------------------------------------------

    /**
     * The price customers actually pay: offer price when set, otherwise base price.
     */
    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->offer_price ?? $this->price);
    }

    /**
     * Discount percentage, or 0 when there is no active offer.
     */
    public function getDiscountPercentAttribute(): int
    {
        if (! $this->offer_price || $this->price <= 0) {
            return 0;
        }

        return (int) round((($this->price - $this->offer_price) / $this->price) * 100);
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0 || $this->variants()->where('stock', '>', 0)->exists();
    }

    // ----- Scopes --------------------------------------------------------

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeNewArrivals(Builder $query): Builder
    {
        return $query->where('is_new_arrival', true);
    }

    public function scopeBestSellers(Builder $query): Builder
    {
        return $query->where('is_best_seller', true);
    }
}
