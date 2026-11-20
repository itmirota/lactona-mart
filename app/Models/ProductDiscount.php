<?php

namespace App\Models;

use Database\Factories\ProductDiscountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductDiscount extends Model
{
    /** @use HasFactory<ProductDiscountFactory> */
    use HasFactory;

    protected $fillable = [
        'promotion_name',
        'description',
        'discount_type',
        'discount_value',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'starts_at' => 'immutable_datetime',
            'ends_at' => 'immutable_datetime',
            'is_active' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'discount_product', 'discount_id', 'product_id')
            ->withTimestamps();
    }

    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'Nonaktif';
        }

        if ($this->starts_at->isFuture()) {
            return 'Akan Datang';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return 'Berakhir';
        }

        return 'Aktif';
    }

    public function discountedPriceFor(Product $product): int
    {
        $price = (int) round((float) $product->price);
        $amount = $this->discount_type === 'percentage'
            ? (int) round($price * ((float) $this->discount_value / 100))
            : min($price, (int) $this->discount_value);

        return max(0, $price - $amount);
    }
}
