<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'sku',
        'additional_price',
        'stock',
    ];

    protected $casts = [
        'additional_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Human-readable label used on cart/order lines, e.g. "Blue / L".
     */
    public function getLabelAttribute(): string
    {
        return trim(collect([$this->color, $this->size])->filter()->implode(' / '));
    }
}
