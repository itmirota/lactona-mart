<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.productId' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.variants' => ['nullable', 'array'],
            'items.*.variants.*' => ['required', 'integer'],
        ]);

        $order = DB::transaction(function () use ($validated) {
            $orderItems = [];
            $totalAmount = 0;

            foreach ($validated['items'] as $item) {
                $product = Product::query()
                    ->where('is_active', true)
                    ->with(['variantTypes.variants', 'currentDiscounts'])
                    ->find($item['productId']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => 'Salah satu produk sudah tidak tersedia.',
                    ]);
                }

                $selectedVariants = $item['variants'] ?? [];
                $variantLabels = [];
                $price = (int) round((float) $product->price);

                foreach ($product->variantTypes as $type) {
                    $selectedId = $selectedVariants[$type->id] ?? null;

                    if (! $selectedId) {
                        throw ValidationException::withMessages([
                            'items' => "Silakan pilih varian {$type->name} untuk produk {$product->name}.",
                        ]);
                    }

                    $variant = $type->variants->firstWhere('id', (int) $selectedId);

                    if (! $variant || ! $variant->is_active) {
                        throw ValidationException::withMessages([
                            'items' => "Varian produk {$product->name} tidak valid atau sudah tidak tersedia.",
                        ]);
                    }

                    $variantLabels[] = $type->name . ': ' . $variant->name;

                    if ($type->affects_price) {
                        $price = (int) ($variant->price ?? 0);

                        if ($price <= 0) {
                            $price = (int) round((float) $product->price);
                        }
                    }
                }

                $discount = $product->currentDiscount;

                if ($discount) {
                    if ($discount->discount_type === 'percentage') {
                        $discountAmount = (int) round(
                            $price * ((float) $discount->discount_value / 100)
                        );
                    } else {
                        $discountAmount = min(
                            $price,
                            (int) $discount->discount_value
                        );
                    }

                    $price = max(0, $price - $discountAmount);
                }

                $quantity = (int) $item['quantity'];
                $subtotal = $price * $quantity;

                $totalAmount += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'variant_label' => implode(' / ', $variantLabels) ?: null,
                    'unit_price' => $price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // Buat pesanan terlebih dahulu menggunakan nomor sementara
            $order = Order::create([
                'order_number' => 'TEMP-' . Str::uuid(),
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_address' => $validated['address'],
                'total_amount' => $totalAmount,
                'status' => 'Menunggu Konfirmasi',
            ]);

            // Bentuk nomor pesanan berdasarkan ID database
            $orderNumber = 'LM-' . now()->format('Ymd') . '-' .
                str_pad((string) $order->id, 4, '0', STR_PAD_LEFT);

            // Perbarui nomor pesanan
            $order->update([
                'order_number' => $orderNumber,
            ]);

            $order->items()->createMany($orderItems);

            // Catat status awal pesanan
            $order->statusHistories()->create([
                'user_id' => null,
                'old_status' => null,
                'new_status' => $order->status,
            ]);

            return $order;
        });

        return response()->json([
            'success' => true,
            'order_number' => $order->order_number,
            'total_amount' => (float) $order->total_amount,
        ]);
    }
}
