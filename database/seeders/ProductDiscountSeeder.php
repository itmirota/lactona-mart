<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasColumn('products', 'discount_percentage')) {
            return;
        }

        $timestamp = now();

        Product::query()
            ->where('discount_percentage', '>', 0)
            ->whereDoesntHave('discounts')
            ->get(['id', 'discount_percentage'])
            ->each(function (Product $product) use ($timestamp): void {
                $product->discounts()->create([
                    'promotion_name' => 'Diskon produk sebelumnya',
                    'description' => 'Diskon ini dipindahkan dari pengaturan diskon produk sebelumnya.',
                    'discount_type' => 'percentage',
                    'discount_value' => $product->discount_percentage,
                    'starts_at' => $timestamp,
                    'ends_at' => null,
                    'is_active' => true,
                ]);
            });
    }
}
