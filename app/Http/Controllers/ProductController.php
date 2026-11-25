<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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
            'nutrition_facts.groups.*.use_serving' => 'sometimes|boolean',

            'nutrition_facts.groups.*.columns' => 'sometimes|array',
            'nutrition_facts.groups.*.columns.*.label' => 'required|string|max:100',

            'nutrition_facts.groups.*.items' => 'required|array|min:1',
            'nutrition_facts.groups.*.items.*.name' => 'required|string|max:100',
            'nutrition_facts.groups.*.items.*.unit' => 'nullable|string|max:30',
            'nutrition_facts.groups.*.items.*.value' => 'nullable|string|max:50',

            'nutrition_facts.groups.*.items.*.percentages' => 'sometimes|array',
            'nutrition_facts.groups.*.items.*.percentages.*' => 'nullable|string|max:50',
        ];
    }

    /**
     * Normalize per-group options while keeping legacy groups enabled by default.
     *
     * @param  array<string, mixed>  $nutritionFacts
     * @return array<string, mixed>
     */
    private function normalizeNutritionFacts(array $nutritionFacts): array
    {
        foreach ($nutritionFacts['groups'] as &$group) {
            $group['use_serving'] = in_array(
                $group['use_serving'] ?? true,
                [true, 1, '1'],
                true
            );
        }
        unset($group);

        $nutritionFacts['columns'] = $nutritionFacts['columns'] ?? [];

        return $nutritionFacts;
    }

    private function variantValidationRules(): array
    {
        return [
            'variant_types' => 'sometimes|array',

            'variant_types.*.id' => 'nullable|integer',
            'variant_types.*.name' => 'required|string|max:100',
            'variant_types.*.affects_price' => 'sometimes|boolean',

            'variant_types.*.variants' => 'required|array|min:1',
            'variant_types.*.variants.*.id' => 'nullable|integer',

            'variant_types.*.variants.*.name' => 'required|string|max:100',
            'variant_types.*.variants.*.price' => 'nullable|numeric|min:0',
            'variant_types.*.variants.*.stock' => 'nullable|integer|min:0',
            'variant_types.*.variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variant_types.*.variants.*.is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Pastikan hanya satu jenis varian yang menjadi penentu harga.
     *
     * @param array<string, mixed> $validated
     */
    private function validatePriceAffectingVariantType(array $validated): void
    {
        $priceTypes = collect($validated['variant_types'] ?? [])
            ->filter(fn (array $type): bool => (bool) ($type['affects_price'] ?? false));

        if ($priceTypes->count() > 1) {
            throw ValidationException::withMessages([
                'variant_types' => 'Pilih hanya satu jenis varian sebagai penentu harga.',
            ]);
        }
    }

    private function discardEmptyVariantTypes(Request $request): void
    {
        $variantTypes = $request->input('variant_types');

        if (! is_array($variantTypes)) {
            return;
        }

        $request->merge([
            'variant_types' => array_filter(
                $variantTypes,
                static fn (mixed $type): bool => ! is_array($type)
                    || filled($type['name'] ?? null)
                    || ! empty($type['id'] ?? null)
                    || ! empty($type['variants'] ?? [])
            ),
        ]);
    }

    private function syncVariantTypes(
        Product $product,
        Request $request,
        array $variantTypes
    ): void {
        $savedTypeIds = [];

        foreach ($variantTypes as $typeIndex => $typeData) {
            $typeSortOrder = count($savedTypeIds);

            if (! empty($typeData['id'])) {
                $type = $product->variantTypes()
                    ->whereKey($typeData['id'])
                    ->firstOrFail();

                $type->update([
                    'name' => $typeData['name'],
                    'affects_price' => (bool) ($typeData['affects_price'] ?? false),
                    'sort_order' => $typeSortOrder,
                ]);
            } else {
                $type = $product->variantTypes()->create([
                    'name' => $typeData['name'],
                    'affects_price' => (bool) ($typeData['affects_price'] ?? false),
                    'sort_order' => $typeSortOrder,
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
        $products = Product::with(['category', 'currentDiscounts', 'variantTypes.variants', 'images',])->latest()->get();

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
        $this->discardEmptyVariantTypes($request);

        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'gallery_images' => 'nullable|array',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
                'primary_image' => 'nullable|string|regex:/^new:\d+$/',
            ],
            $this->nutritionValidationRules($nutritionEnabled),
            $this->variantValidationRules()
        ));

        $this->validatePriceAffectingVariantType($validated);

        if ($nutritionEnabled) {
            $validated['nutrition_facts'] = $this->normalizeNutritionFacts(
                $validated['nutrition_facts']
            );
        }

        $imagePath = null;
        $storedGalleryImages = [];

        if ($request->hasFile('gallery_images')) {
            $uploadedImages = $request->file('gallery_images');

            // Tentukan gambar utama berdasarkan pilihan di form.
            $primaryIndex = 0;

            if (preg_match('/^new:(\d+)$/', $request->input('primary_image', ''), $matches)) {
                $primaryIndex = (int) $matches[1];
            }

            // Jika indeks pilihan tidak tersedia, gunakan gambar pertama.
            if (! isset($uploadedImages[$primaryIndex])) {
                $primaryIndex = array_key_first($uploadedImages);
            }

            foreach ($uploadedImages as $index => $imageFile) {
                $path = $imageFile->store('products/gallery', 'public');

                if ($index === $primaryIndex) {
                    $imagePath = $path;
                } else {
                    $storedGalleryImages[] = [
                        'image' => $path,
                        'sort_order' => count($storedGalleryImages),
                    ];
                }
            }
        } elseif ($request->hasFile('image')) {
            // Kompatibilitas sementara dengan input gambar utama lama.
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

        foreach ($storedGalleryImages as $galleryImage) {
            $product->images()->create($galleryImage);
        }

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

        $product->load([
            'variantTypes.variants',
            'images',
        ]);

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $nutritionEnabled = $request->boolean('nutrition_facts_enabled');
        $this->discardEmptyVariantTypes($request);

        $validated = $request->validate(array_merge(
            [
                'category_id' => 'required|exists:category_product,id',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'gallery_images' => 'nullable|array',
                'gallery_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
                'remove_gallery_images' => 'sometimes|array',
                'remove_gallery_images.*' => 'integer',
                'primary_image' => ['nullable', 'string', 'regex:/^(current|gallery:\d+|new:\d+)$/', ],
            ],
            $this->nutritionValidationRules($nutritionEnabled),
            $this->variantValidationRules()
        ));

        $this->validatePriceAffectingVariantType($validated);

        if ($nutritionEnabled) {
            $validated['nutrition_facts'] = $this->normalizeNutritionFacts(
                $validated['nutrition_facts']
            );
        }

        $oldMainImage = $product->image;
        $imagePath = $oldMainImage;

        $primaryChoice = $request->input('primary_image', 'current');
        $uploadedImages = $request->file('gallery_images', []);

        $removeCurrentImage = $request->boolean('remove_current_image');
        $removeGalleryIds = $request->input('remove_gallery_images', []);

        // Pastikan gambar galeri yang akan dihapus benar-benar milik produk ini.
        $removedGalleryImages = $product->images()
            ->whereIn('id', $removeGalleryIds)
            ->get();

        $selectedGalleryImage = null;

        // Jika gambar utama dipilih dari galeri yang sudah tersimpan.
        if (preg_match('/^gallery:(\d+)$/', $primaryChoice, $matches)) {
            $selectedGalleryImage = $product->images()
                ->whereKey((int) $matches[1])
                ->firstOrFail();

            $imagePath = $selectedGalleryImage->image;
        }

        // Jika gambar utama dipilih dari file baru.
        elseif (preg_match('/^new:(\d+)$/', $primaryChoice, $matches)) {
            $primaryIndex = (int) $matches[1];

            if (! isset($uploadedImages[$primaryIndex])) {
                throw ValidationException::withMessages([
                    'primary_image' => 'Gambar utama yang dipilih tidak ditemukan.',
                ]);
            }

            $imagePath = $uploadedImages[$primaryIndex]
                ->store('products/gallery', 'public');
        }

        // Jika masih menggunakan input gambar utama versi lama.
        elseif ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('products', 'public');
        }

        // Jika gambar utama lama dihapus dan tidak ada gambar pengganti.
        elseif ($removeCurrentImage && $primaryChoice === 'current') {
            $imagePath = null;
        }

        // Simpan perubahan data produk.
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

        // Jika gambar utama baru berasal dari galeri,
        // gambar utama lama dipindahkan menjadi gambar galeri.
        if ($selectedGalleryImage) {
            $selectedGalleryImage->delete();

            if ($oldMainImage && ! $removeCurrentImage) {
                $product->images()->create([
                    'image' => $oldMainImage,
                    'sort_order' => (int) $product->images()->max('sort_order') + 1,
                ]);
            }
        }

        // Jika gambar utama baru berasal dari file upload,
        // gambar utama lama dipindahkan ke galeri.
        elseif (
            str_starts_with($primaryChoice, 'new:')
            || $request->hasFile('image')
        ) {
            if ($oldMainImage && ! $removeCurrentImage) {
                $product->images()->create([
                    'image' => $oldMainImage,
                    'sort_order' => (int) $product->images()->max('sort_order') + 1,
                ]);
            }
        }

        // Simpan file baru yang bukan gambar utama.
        if ($request->hasFile('gallery_images')) {
            foreach ($uploadedImages as $index => $imageFile) {
                $isPrimaryImage = (
                    preg_match('/^new:(\d+)$/', $primaryChoice, $matches)
                    && $index === (int) $matches[1]
                );

                if ($isPrimaryImage) {
                    continue;
                }

                $path = $imageFile->store('products/gallery', 'public');

                $product->images()->create([
                    'image' => $path,
                    'sort_order' => (int) $product->images()->max('sort_order') + 1,
                ]);
            }
        }

        // Hapus gambar galeri yang dipilih.
        // Gambar yang sedang dijadikan gambar utama tidak ikut dihapus.
        foreach ($removedGalleryImages as $galleryImage) {
            if (
                $selectedGalleryImage
                && $galleryImage->id === $selectedGalleryImage->id
            ) {
                continue;
            }

            if (
                $galleryImage->image
                && Storage::disk('public')->exists($galleryImage->image)
            ) {
                Storage::disk('public')->delete($galleryImage->image);
            }

            $galleryImage->delete();
        }

        // Hapus file gambar utama lama jika memang diminta dihapus,
        // bukan dipindahkan ke galeri dan bukan lagi gambar utama.
        if (
            $removeCurrentImage
            && $oldMainImage
            && $oldMainImage !== $imagePath
            && ! $product->images()->where('image', $oldMainImage)->exists()
            && Storage::disk('public')->exists($oldMainImage)
        ) {
            Storage::disk('public')->delete($oldMainImage);
        }

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

        $product->load([
            'variantTypes.variants',
            'images',
        ]);

        foreach ($product->variantTypes as $type) {
            foreach ($type->variants as $variant) {
                if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
            }
        }

        // Hapus seluruh file gambar galeri.
        foreach ($product->images as $galleryImage) {
            if (
                $galleryImage->image &&
                Storage::disk('public')->exists($galleryImage->image)
            ) {
                Storage::disk('public')->delete($galleryImage->image);
            }
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
