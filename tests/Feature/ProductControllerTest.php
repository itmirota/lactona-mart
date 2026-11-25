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
            ->assertSee('data-step-previous', false)
            ->assertSee('data-step-progress', false)
            ->assertSee('role="switch"', false);
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
                    'use_serving' => true,
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

    public function test_store_preserves_hidden_serving_values_and_group_specific_columns(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);
        $nutritionFacts = [
            'serving_size' => '40 g',
            'servings_per_package' => '5',
            'groups' => [
                [
                    'title' => 'Grup A',
                    'use_serving' => '0',
                    'columns' => [['label' => 'Hamil']],
                    'items' => [
                        [
                            'name' => 'Protein',
                            'value' => '8',
                            'unit' => 'g',
                            'percentages' => ['11%'],
                        ],
                    ],
                ],
                [
                    'title' => 'Grup B',
                    'use_serving' => '0',
                    'columns' => [
                        ['label' => 'Vitamin A'],
                        ['label' => 'Vitamin B'],
                    ],
                    'items' => [
                        [
                            'name' => 'Vitamin',
                            'value' => '12',
                            'unit' => 'mg',
                            'percentages' => ['15%', '20%'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Group Nutrition Product',
            'price' => 59000,
            'nutrition_facts_enabled' => '1',
            'nutrition_facts' => $nutritionFacts,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('name', 'Group Nutrition Product')->firstOrFail();
        $savedNutritionFacts = $product->nutrition_facts;

        $this->assertFalse($savedNutritionFacts['groups'][0]['use_serving']);
        $this->assertFalse($savedNutritionFacts['groups'][1]['use_serving']);
        $this->assertSame(
            [['label' => 'Hamil']],
            $savedNutritionFacts['groups'][0]['columns']
        );
        $this->assertSame(
            [['label' => 'Vitamin A'], ['label' => 'Vitamin B']],
            $savedNutritionFacts['groups'][1]['columns']
        );
        $this->assertSame('8', $savedNutritionFacts['groups'][0]['items'][0]['value']);
        $this->assertSame('g', $savedNutritionFacts['groups'][0]['items'][0]['unit']);

        $this->get(route('products.detail', $product))
            ->assertOk()
            ->assertSee('Hamil')
            ->assertSee('Vitamin A')
            ->assertSee('Vitamin B')
            ->assertDontSee('Per Saji');

        $this->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('&quot;use_serving&quot;:false', false)
            ->assertSee('&quot;value&quot;:&quot;8&quot;', false)
            ->assertSee('&quot;unit&quot;:&quot;g&quot;', false);
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

    public function test_store_ignores_an_empty_optional_variant_type(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);

        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Product Without Variants',
            'price' => 59000,
            'variant_types_present' => '1',
            'variant_types' => [
                [
                    'name' => '',
                    'variants' => [],
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('name', 'Product Without Variants')->firstOrFail();
        $this->assertCount(0, $product->variantTypes()->get());
    }

    public function test_update_ignores_an_empty_optional_variant_type(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);
        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Product Without Variants',
            'price' => 59000,
        ]);

        $response = $this->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => $product->name,
            'price' => $product->price,
            'variant_types_present' => '1',
            'variant_types' => [
                [
                    'name' => '',
                    'variants' => [],
                ],
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertCount(0, $product->fresh()->variantTypes()->get());
    }

    public function test_store_still_requires_a_choice_for_a_named_variant_type(): void
    {
        $category = CategoryProduct::query()->create(['name' => 'Nutrition']);

        $response = $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'Incomplete Variant Product',
            'price' => 59000,
            'variant_types' => [
                [
                    'name' => 'Rasa',
                    'variants' => [],
                ],
            ],
        ]);

        $response->assertSessionHasErrors('variant_types.0.variants');
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
