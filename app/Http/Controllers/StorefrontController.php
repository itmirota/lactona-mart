<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantType;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $products = Product::with(['category', 'currentDiscounts'])
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact('products'));
    }

    public function products(): View
    {
        $products = Product::with(['category', 'currentDiscounts'])
            ->latest()
            ->paginate(12);

        return view('produk', compact('products'));
    }

    public function cartProducts(): JsonResponse
    {
        $products = Product::with([
            'currentDiscounts',
            'variantTypes.variants' => fn (Builder $query) => $query->where('is_active', true),
        ])
            ->latest()
            ->get();

        $cartProducts = $products->map(function (Product $product): array {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image_url,
                'price' => (int) round((float) $product->price),
                'discount' => [
                    'type' => $product->currentDiscount?->discount_type,
                    'value' => (float) ($product->currentDiscount?->discount_value ?? 0),
                ],
                'variantTypes' => $product->variantTypes
                    ->map(function (ProductVariantType $type): array {
                        return [
                            'id' => $type->id,
                            'name' => $type->name,
                            'affectsPrice' => $type->affects_price,
                            'variants' => $type->variants
                                ->map(function (ProductVariant $variant): array {
                                    return [
                                        'id' => $variant->id,
                                        'name' => $variant->name,
                                        'price' => (int) $variant->price,
                                        'image' => $variant->image
                                            ? Storage::url($variant->image)
                                            : null,
                                    ];
                                })
                                ->values()
                                ->all(),
                        ];
                    })
                    ->filter(fn (array $type): bool => $type['variants'] !== [])
                    ->values()
                    ->all(),
            ];
        });

        return response()->json($cartProducts->values());
    }

    public function detail(?Product $product = null): View
    {
        if (! $product || ! $product->exists) {
            $product = Product::with([
                'category',
                'currentDiscounts',
                'variantTypes.variants',
            ])->first();
        } else {
            $product->loadMissing([
                'category',
                'currentDiscounts',
                'variantTypes.variants',
            ]);
        }

        $nutritionFactSections = Product::nutritionFactSections();

        return view('detail', compact('nutritionFactSections', 'product'));
    }
}
