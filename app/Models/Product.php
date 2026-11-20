<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['category_id', 'name', 'price', 'description', 'image', 'nutrition_facts'];

    protected function casts(): array
    {
        return [
            'nutrition_facts' => 'array',
        ];
    }

    /**
     * Relasi ke kategori produk.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id');
    }

    /**
     * Accessor URL gambar yang aman untuk public asset maupun storage upload.
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('produk/Lactona Ibu Cokelat.png');
        }

        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'produk/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function variantTypes(): HasMany
    {
        return $this->hasMany(ProductVariantType::class)
            ->orderBy('sort_order');
    }
}
