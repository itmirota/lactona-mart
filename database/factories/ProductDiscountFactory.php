<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductDiscount;
use Illuminate\Database\Eloquent\Factories\Factory;
use LogicException;

/**
 * @extends Factory<ProductDiscount>
 */
class ProductDiscountFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (ProductDiscount $discount): void {
            $productId = Product::query()->inRandomOrder()->value('id');

            if (! $productId) {
                throw new LogicException('Buat produk terlebih dahulu sebelum membuat promo diskon.');
            }

            $discount->products()->attach($productId);
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'promotion_name' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'discount_type' => 'percentage',
            'discount_value' => fake()->numberBetween(5, 50),
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
            'is_active' => true,
        ];
    }
}
