<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function nutritionValidationRules(bool $enabled): array
    {
        if (! $enabled) {
            return [];
        }

        return [
            'nutrition_facts' => 'required|array',
            'nutrition_facts.serving_size' => 'required|string|max:100',
            'nutrition_facts.servings_per_package' => 'required|string|max:100',
            'nutrition_facts.columns' => 'sometimes|array',
            'nutrition_facts.columns.*.label' => 'required|string|max:100',
            'nutrition_facts.groups' => 'required|array|min:1',
            'nutrition_facts.groups.*.title' => 'required|string|max:100',
            'nutrition_facts.groups.*.columns' => 'sometimes|array',
            'nutrition_facts.groups.*.columns.*.label' => 'required|string|max:100',
            'nutrition_facts.groups.*.items' => 'required|array|min:1',
            'nutrition_facts.groups.*.items.*.name' => 'required|string|max:100',
            'nutrition_facts.groups.*.items.*.unit' => 'nullable|string|max:30',
            'nutrition_facts.groups.*.items.*.value' => 'required|string|max:50',
            'nutrition_facts.groups.*.items.*.percentages' => 'sometimes|array',
            'nutrition_facts.groups.*.items.*.percentages.*' => 'nullable|string|max:50',
        ];
    }

    private function variantValidationRules(): array
    {
        return [
            'variant_types' => 'sometimes|array',

            'variant_types.*.id' => 'nullable|integer',
            'variant_types.*.name' => 'required|string|max:100',

            'variant_types.*.variants' => 'required|array|min:1',
            'variant_types.*.variants.*.id' => 'nullable|integer',

            'variant_types.*.variants.*.name' => 'required|string|max:100',
            'variant_types.*.variants.*.price' => 'nullable|numeric|min:0',
            'variant_types.*.variants.*.stock' => 'nullable|integer|min:0',
            'variant_types.*.variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variant_types.*.variants.*.is_active' => 'sometimes|boolean',
        ];
    }

    private function syncVariantTypes(
        Product $product,
        Request $request,
        array $variantTypes
    ): void {
        $savedTypeIds = [];

        foreach ($variantTypes as $typeIndex => $typeData) {
            if (! empty($typeData['id'])) {
                $type = $product->variantTypes()
                    ->whereKey($typeData['id'])
                    ->firstOrFail();

                $type->update([
                    'name' => $typeData['name'],
                    'sort_order' => $typeIndex,
                ]);
            } else {
                $type = $product->variantTypes()->create([
                    'name' => $typeData['name'],
                    'sort_order' => $typeIndex,
                ]);
            }

            $savedTypeIds[] = $type->id;

            $savedVariantIds = [];

            foreach ($typeData['variants'] as $variantIndex => $variantData) {
                // Cari pilihan varian lama atau buat yang baru.
                if (! empty($variantData['id'])) {
                    $variant = $type->variants()
                        ->whereKey($variantData['id'])
                        ->firstOrFail();
                } else {
                    $variant = new ProductVariant;
                }

                $imagePath = $variant->image;

                $imageFile = $request->file(
                    "variant_types.{$typeIndex}.variants.{$variantIndex}.image"
                );

                if ($imageFile) {
                    if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }

                    $imagePath = $imageFile->store('product-variants', 'public');
                }

                $variant->fill([
                    'name' => $variantData['name'],
                    'price' => $variantData['price'] ?? null,
                    'stock' => $variantData['stock'] ?? null,
                    'image' => $imagePath,
                    'is_active' => (bool) ($variantData['is_active'] ?? false),
                    'sort_order' => $variantIndex,
                ]);

                $type->variants()->save($variant);

                $savedVariantIds[] = $variant->id;
            }

            $removedVariants = $type->variants()
                ->whereNotIn('id', $savedVariantIds)
                ->get();

            foreach ($removedVariants as $removedVariant) {
                if ($removedVariant->image && Storage::disk('public')->exists($removedVariant->image)) {
                    Storage::disk('public')->delete($removedVariant->image);
                }

                $removedVariant->delete();
            }
        }

        $removedTypes = $product->variantTypes()
            ->whereNotIn('id', $savedTypeIds)
            ->with('variants')
            ->get();

        foreach ($removedTypes as $removedType) {
            foreach ($removedType->variants as $variant) {
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
            }

            $removedType->delete();
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $products = Product::with(['category', 'variantTypes.variants'])->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = CategoryProduct::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $nutritionEnabled = $request->boolean('nutrition_facts_enabled');

        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            $this->nutritionValidationRules($nutritionEnabled),
            $this->variantValidationRules()
        ));

        if ($nutritionEnabled) {
            $validated['nutrition_facts']['columns'] =
                $validated['nutrition_facts']['columns'] ?? [];
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'nutrition_facts' => $nutritionEnabled
                ? $validated['nutrition_facts']
                : null,
        ]);

        if (! empty($validated['variant_types'])) {
            $this->syncVariantTypes(
                $product,
                $request,
                $validated['variant_types']
            );
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = CategoryProduct::orderBy('name')->get();

        $product->load('variantTypes.variants');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $nutritionEnabled = $request->boolean('nutrition_facts_enabled');

        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            $this->nutritionValidationRules($nutritionEnabled),
            $this->variantValidationRules()
        ));

        if ($nutritionEnabled) {
            $validated['nutrition_facts']['columns'] =
                $validated['nutrition_facts']['columns'] ?? [];
        }

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'nutrition_facts' => $nutritionEnabled
                ? $validated['nutrition_facts']
                : null,
        ]);

        if ($request->boolean('variant_types_present')) {
            $this->syncVariantTypes(
                $product,
                $request,
                $validated['variant_types'] ?? []
            );
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->load('variantTypes.variants');

        foreach ($product->variantTypes as $type) {
            foreach ($type->variants as $variant) {
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
