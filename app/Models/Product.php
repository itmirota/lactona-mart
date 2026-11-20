<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'description',
        'image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'nutrition_facts' => 'array',
        ];
    }

    /**
     * Return the nutrition fact rows used by the product form and detail page.
     *
     * @return array<int, array{title: string, show_heading: bool, rows: array<int, array{key: string, label: string, single: bool}>}>
     */
    public static function nutritionFactSections(): array
    {
        return [
            [
                'title' => 'Takaran Saji dan Energi',
                'show_heading' => false,
                'rows' => [
                    ['key' => 'takaran_saji', 'label' => 'Takaran Saji', 'single' => true],
                    ['key' => 'jumlah_sajian_per_kemasan', 'label' => 'Jumlah Sajian per Kemasan', 'single' => true],
                    ['key' => 'energi_total', 'label' => 'Energi Total', 'single' => true],
                    ['key' => 'energi_dari_lemak', 'label' => 'Energi dari Lemak', 'single' => true],
                    ['key' => 'energi_dari_lemak_jenuh', 'label' => 'Energi dari Lemak Jenuh', 'single' => true],
                ],
            ],
            [
                'title' => 'Komponen Gizi',
                'show_heading' => false,
                'rows' => [
                    ['key' => 'lemak_total', 'label' => 'Lemak Total', 'single' => false],
                    ['key' => 'lemak_trans', 'label' => 'Lemak Trans', 'single' => false],
                    ['key' => 'kolesterol', 'label' => 'Kolesterol', 'single' => false],
                    ['key' => 'lemak_jenuh', 'label' => 'Lemak Jenuh', 'single' => false],
                    ['key' => 'protein', 'label' => 'Protein', 'single' => false],
                    ['key' => 'karbohidrat_total', 'label' => 'Karbohidrat Total', 'single' => false],
                    ['key' => 'serat_pangan', 'label' => 'Serat Pangan', 'single' => false],
                    ['key' => 'serat_pangan_larut', 'label' => 'Serat Pangan Larut', 'single' => false],
                    ['key' => 'gula_total', 'label' => 'Gula Total', 'single' => false],
                    ['key' => 'sukrosa', 'label' => 'Sukrosa', 'single' => false],
                    ['key' => 'natrium', 'label' => 'Natrium', 'single' => false],
                ],
            ],
            [
                'title' => 'Vitamin dan Mineral',
                'show_heading' => true,
                'rows' => [
                    ['key' => 'vitamin_a', 'label' => 'Vitamin A', 'single' => false],
                    ['key' => 'vitamin_d3', 'label' => 'Vitamin D3', 'single' => false],
                    ['key' => 'vitamin_e', 'label' => 'Vitamin E', 'single' => false],
                    ['key' => 'vitamin_k', 'label' => 'Vitamin K', 'single' => false],
                    ['key' => 'vitamin_b1', 'label' => 'Vitamin B1', 'single' => false],
                    ['key' => 'vitamin_b2', 'label' => 'Vitamin B2', 'single' => false],
                    ['key' => 'vitamin_b3', 'label' => 'Vitamin B3', 'single' => false],
                    ['key' => 'vitamin_b5', 'label' => 'Vitamin B5', 'single' => false],
                    ['key' => 'vitamin_b6', 'label' => 'Vitamin B6', 'single' => false],
                    ['key' => 'vitamin_b9', 'label' => 'Vitamin B9', 'single' => false],
                    ['key' => 'vitamin_b12', 'label' => 'Vitamin B12', 'single' => false],
                    ['key' => 'vitamin_c', 'label' => 'Vitamin C', 'single' => false],
                    ['key' => 'kolin', 'label' => 'Kolin', 'single' => false],
                    ['key' => 'biotin', 'label' => 'Biotin', 'single' => false],
                    ['key' => 'kalium', 'label' => 'Kalium', 'single' => false],
                    ['key' => 'kalsium', 'label' => 'Kalsium', 'single' => false],
                    ['key' => 'fosfor', 'label' => 'Fosfor', 'single' => false],
                    ['key' => 'magnesium', 'label' => 'Magnesium', 'single' => false],
                    ['key' => 'zink', 'label' => 'Zink', 'single' => false],
                    ['key' => 'zat_besi', 'label' => 'Zat Besi', 'single' => false],
                    ['key' => 'mangan', 'label' => 'Mangan', 'single' => false],
                    ['key' => 'iodium', 'label' => 'Iodium', 'single' => false],
                    ['key' => 'selenium', 'label' => 'Selenium', 'single' => false],
                ],
            ],
            [
                'title' => 'Komponen Lain',
                'show_heading' => true,
                'rows' => [
                    ['key' => 'dha', 'label' => 'DHA', 'single' => true],
                    ['key' => 'omega_3', 'label' => 'Omega 3', 'single' => true],
                    ['key' => 'omega_6', 'label' => 'Omega 6', 'single' => true],
                ],
            ],
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
     * Promo diskon yang tercatat untuk produk ini.
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(ProductDiscount::class, 'discount_product', 'product_id', 'discount_id')
            ->withTimestamps();
    }

    /**
     * Promo aktif yang sedang berlaku untuk produk ini.
     */
    public function currentDiscounts(): BelongsToMany
    {
        $now = now();

        return $this->belongsToMany(ProductDiscount::class, 'discount_product', 'product_id', 'discount_id')
            ->where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where(function ($query) use ($now): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->withTimestamps();
    }

    public function getCurrentDiscountAttribute(): ?ProductDiscount
    {
        return $this->currentDiscounts->first();
    }

    /**
     * Nilai potongan yang sedang berlaku dalam rupiah.
     */
    public function getDiscountAmountAttribute(): int
    {
        $discount = $this->currentDiscount;
        $price = (int) round((float) $this->price);

        if (! $discount) {
            return 0;
        }

        if ($discount->discount_type === 'percentage') {
            return (int) round($price * ((float) $discount->discount_value / 100));
        }

        return min($price, (int) $discount->discount_value);
    }

    /**
     * Harga produk setelah potongan promo yang sedang berlaku.
     */
    public function getDiscountedPriceAttribute(): int
    {
        return max(0, (int) round((float) $this->price) - $this->discount_amount);
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
}
