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
        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            $this->variantValidationRules()
        ));

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
        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ],
            $this->variantValidationRules()
        ));

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
