<?php

namespace Tests\Feature;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_create_places_nutrition_facts_on_the_second_form_step(): void
    {
        $this->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('data-product-step="1"', false)
            ->assertSee('data-product-step="2"', false)
            ->assertSee('data-step-next', false)
            ->assertSee('data-step-previous', false);
    }

    public function test_store_saves_nutrition_facts_and_displays_them_on_product_detail(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);
        $nutritionFacts = [
            'serving_size' => '40 g',
            'servings_per_package' => '5',
            'columns' => [
                ['label' => 'Hamil'],
            ],
            'groups' => [
                [
                    'title' => 'Komponen Gizi',
                    'columns' => [
                        ['label' => 'Hamil'],
                    ],
                    'items' => [
                        [
                            'name' => 'Protein',
                            'value' => '8',
                            'unit' => 'g',
                            'percentages' => ['11%'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Nutrition Product',
            'price' => 59000,
            'nutrition_facts_enabled' => '1',
            'nutrition_facts' => $nutritionFacts,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $product = Product::query()->where('name', 'Nutrition Product')->firstOrFail();
        $this->assertSame($nutritionFacts, $product->nutrition_facts);

        $this->get(route('products.detail', $product))
            ->assertOk()
            ->assertSee('Protein')
            ->assertSee('8 g')
            ->assertSee('11%');
    }

    public function test_update_clears_nutrition_facts_when_the_field_is_disabled(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Nutrition Product',
            'price' => 59000,
            'nutrition_facts' => ['serving_size' => '40 g'],
        ]);

        $response = $this->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => $product->name,
            'price' => $product->price,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertNull($product->fresh()->nutrition_facts);
    }

    public function test_edit_converts_existing_nutrition_facts_to_builder_fields(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Nutrition Product',
            'price' => 59000,
            'nutrition_facts' => [
                'takaran_saji' => ['per_serving' => '3 sendok makan penuh (40g)'],
                'jumlah_sajian_per_kemasan' => ['per_serving' => '± 5'],
                'protein' => [
                    'per_serving' => '8 g',
                    'pregnancy' => '11%',
                    'lactation' => '11%',
                ],
            ],
        ]);

        $this->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('data-product-step="2"', false)
            ->assertSee('data-step-previous', false)
            ->assertSee('3 sendok makan penuh (40g)')
            ->assertSee('8 g')
            ->assertSee('11%');
    }
}
