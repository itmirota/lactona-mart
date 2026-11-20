<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDiscount;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductDiscountController extends Controller
{
    public function index(Request $request): View
    {
        $now = now();
        $filter = $request->query('status', 'all');
        $filters = ['all', 'active', 'upcoming', 'ended', 'inactive'];

        if (! in_array($filter, $filters, true)) {
            $filter = 'all';
        }

        $discounts = ProductDiscount::with('products')
            ->when($filter === 'active', function ($query) use ($now): void {
                $query->where('is_active', true)
                    ->where('starts_at', '<=', $now)
                    ->where(function ($query) use ($now): void {
                        $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                    });
            })
            ->when($filter === 'upcoming', function ($query) use ($now): void {
                $query->where('is_active', true)->where('starts_at', '>', $now);
            })
            ->when($filter === 'ended', function ($query) use ($now): void {
                $query->where('is_active', true)->whereNotNull('ends_at')->where('ends_at', '<', $now);
            })
            ->when($filter === 'inactive', fn ($query) => $query->where('is_active', false))
            ->latest('starts_at')
            ->paginate(10)
            ->withQueryString();

        $totalCampaigns = ProductDiscount::count();
        $activeCampaigns = ProductDiscount::where('is_active', true)
            ->where('starts_at', '<=', $now)
            ->where(function ($query) use ($now): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->count();
        $upcomingCampaigns = ProductDiscount::where('is_active', true)
            ->where('starts_at', '>', $now)
            ->count();

        return view('admin.discounts.index', compact(
            'activeCampaigns',
            'discounts',
            'filter',
            'totalCampaigns',
            'upcomingCampaigns',
        ));
    }

    public function create(): View
    {
        $discount = null;
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('admin.discounts.create', compact('discount', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedDiscount($request);
        $this->assertNoScheduleOverlap($validated, $request->boolean('is_active'));
        $productIds = $validated['product_ids'];
        unset($validated['product_ids']);

        DB::transaction(function () use ($validated, $productIds, $request): void {
            $discount = ProductDiscount::create($validated + ['is_active' => $request->boolean('is_active')]);
            $discount->products()->sync($productIds);
        });

        return redirect()->route('admin.discounts.index')->with('success', 'Promo diskon berhasil ditambahkan.');
    }

    public function edit(ProductDiscount $discount): View
    {
        $discount->load('products');
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, ProductDiscount $discount): RedirectResponse
    {
        $validated = $this->validatedDiscount($request);
        $this->assertNoScheduleOverlap($validated, $request->boolean('is_active'), $discount);
        $productIds = $validated['product_ids'];
        unset($validated['product_ids']);

        DB::transaction(function () use ($discount, $validated, $productIds, $request): void {
            $discount->update($validated + ['is_active' => $request->boolean('is_active')]);
            $discount->products()->sync($productIds);
        });

        return redirect()->route('admin.discounts.index')->with('success', 'Promo diskon berhasil diperbarui.');
    }

    public function destroy(ProductDiscount $discount): RedirectResponse
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Promo diskon berhasil dihapus.');
    }

    /**
     * Validate campaign details and ensure a fixed deduction fits every selected product.
     *
     * @return array<string, mixed>
     */
    private function validatedDiscount(Request $request): array
    {
        $valueRules = $request->input('discount_type') === 'percentage'
            ? ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:100']
            : ['required', 'integer', 'min:1'];

        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'promotion_name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:2000'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => $valueRules,
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $products = Product::whereKey($validated['product_ids'])->get(['id', 'name', 'price']);
        $cheapestProduct = $products->sortBy('price')->first();

        if ($validated['discount_type'] === 'fixed' && (int) $validated['discount_value'] > (int) round((float) $cheapestProduct->price)) {
            throw ValidationException::withMessages([
                'discount_value' => 'Potongan harga tidak boleh melebihi harga produk termurah yang dipilih ('.$cheapestProduct->name.').',
            ]);
        }

        return [
            'product_ids' => $products->modelKeys(),
            'promotion_name' => $validated['promotion_name'],
            'description' => $validated['description'] ?? null,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'starts_at' => CarbonImmutable::parse($validated['starts_at']),
            'ends_at' => isset($validated['ends_at']) ? CarbonImmutable::parse($validated['ends_at']) : null,
        ];
    }

    /**
     * Prevent enabled campaigns on selected products from overlapping in time.
     *
     * @param  array<string, mixed>  $validated
     */
    private function assertNoScheduleOverlap(array $validated, bool $isActive, ?ProductDiscount $ignore = null): void
    {
        if (! $isActive) {
            return;
        }

        $startsAt = CarbonImmutable::parse($validated['starts_at']);
        $endsAt = $validated['ends_at'];

        $overlapping = ProductDiscount::whereHas('products', function ($query) use ($validated): void {
            $query->whereIn('products.id', $validated['product_ids']);
        })
            ->where('is_active', true)
            ->where(function ($query) use ($startsAt): void {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', $startsAt);
            })
            ->when($endsAt !== null, fn ($query) => $query->where('starts_at', '<=', $endsAt))
            ->when($ignore, fn ($query) => $query->where('id', '<>', $ignore->getKey()))
            ->exists();

        if ($overlapping) {
            throw ValidationException::withMessages([
                'schedule' => 'Jadwal promo bertumpuk dengan promo aktif lain pada salah satu produk yang dipilih. Pilih tanggal berbeda atau ubah produk yang dipilih.',
            ]);
        }
    }
}
