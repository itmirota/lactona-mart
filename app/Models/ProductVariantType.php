<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariantType extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'affects_price',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'affects_price' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'variant_type_id')
            ->orderBy('sort_order');
    }
}
