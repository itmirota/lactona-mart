@php
    $selectedProductIds = collect(old('product_ids', $discount?->products?->modelKeys() ?? []))
        ->map(fn ($id) => (string) $id);
@endphp

<form action="{{ $discount ? route('admin.discounts.update', $discount) : route('admin.discounts.store') }}" method="POST" class="space-y-5">
    @csrf
    @if ($discount)
        @method('PUT')
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-semibold">Periksa kembali data promo:</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5">
            <h2 class="font-semibold text-slate-900">Informasi promo</h2>
            <p class="mt-1 text-sm text-slate-500">Gunakan nama yang mudah dikenali, misalnya Promo Ramadan atau Diskon Hari Kemerdekaan.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="promotion_name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama promo <span class="text-rose-600">*</span></label>
                <input id="promotion_name" name="promotion_name" type="text" required maxlength="160" value="{{ old('promotion_name', $discount?->promotion_name) }}" placeholder="Contoh: Promo Hari Kemerdekaan" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10">
                @error('promotion_name') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="mb-1.5 block text-sm font-medium text-slate-700">Keterangan promo <span class="font-normal text-slate-400">(opsional)</span></label>
                <textarea id="description" name="description" rows="3" maxlength="2000" placeholder="Jelaskan tema atau alasan promo ini diadakan." class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10">{{ old('description', $discount?->description) }}</textarea>
                @error('description') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5">
            <h2 class="font-semibold text-slate-900">Produk dan potongan</h2>
            <p class="mt-1 text-sm text-slate-500">Potongan berlaku untuk satu produk. Promo aktif pada produk yang sama tidak boleh memiliki jadwal bertumpuk.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                    <label class="block text-sm font-medium text-slate-700">Produk yang ikut promo <span class="text-rose-600">*</span></label>
                    <span id="selected-product-count" class="text-xs font-semibold text-brand-dark">{{ $selectedProductIds->count() }} produk dipilih</span>
                </div>
                <p class="mb-3 text-xs text-slate-500">Pilih satu atau beberapa produk. Promo dan jadwal yang sama akan diterapkan ke seluruh produk terpilih.</p>
                <div class="grid max-h-72 gap-2 overflow-y-auto rounded-xl border border-slate-200 bg-slate-50 p-3 sm:grid-cols-2">
                    @forelse ($products as $product)
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 bg-white p-3 transition hover:border-brand-dark/40 hover:bg-brand-light/30">
                            <input class="discount-product-checkbox mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-dark focus:ring-brand-dark" type="checkbox" name="product_ids[]" value="{{ $product->id }}" data-price="{{ (int) round((float) $product->price) }}" data-product-name="{{ $product->name }}" @checked($selectedProductIds->contains((string) $product->id))>
                            <span class="min-w-0">
                                <span class="block text-sm font-medium text-slate-800">{{ $product->name }}</span>
                                <span class="mt-1 block text-xs text-slate-500">Harga Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </span>
                        </label>
                    @empty
                        <p class="sm:col-span-2 rounded-lg bg-amber-50 p-3 text-sm text-amber-800">Belum ada produk. Tambahkan produk terlebih dahulu sebelum membuat promo.</p>
                    @endforelse
                </div>
                @error('product_ids') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="discount_type" class="mb-1.5 block text-sm font-medium text-slate-700">Jenis diskon <span class="text-rose-600">*</span></label>
                <select id="discount_type" name="discount_type" required class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10">
                    <option value="percentage" @selected(old('discount_type', $discount?->discount_type ?? 'percentage') === 'percentage')>Persentase (%)</option>
                    <option value="fixed" @selected(old('discount_type', $discount?->discount_type) === 'fixed')>Potongan harga (Rp)</option>
                </select>
                @error('discount_type') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="discount_value" class="mb-1.5 block text-sm font-medium text-slate-700">Nilai potongan <span class="text-rose-600">*</span></label>
                <div class="flex overflow-hidden rounded-lg border border-slate-300 focus-within:border-brand-dark focus-within:ring-2 focus-within:ring-brand-dark/10">
                    <input id="discount_value" name="discount_value" type="number" required min="0.01" step="0.01" value="{{ old('discount_value', $discount?->discount_value) }}" class="min-w-0 flex-1 border-0 px-3.5 py-2.5 text-sm text-slate-900 outline-none focus:ring-0">
                    <span id="discount-unit" class="flex min-w-12 items-center justify-center border-l border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-500">%</span>
                </div>
                <p id="discount-help" class="mt-1.5 text-xs text-slate-500">Masukkan persentase antara 0 sampai 100.</p>
                @error('discount_value') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5">
            <h2 class="font-semibold text-slate-900">Masa berlaku</h2>
            <p class="mt-1 text-sm text-slate-500">Jadwalkan promo untuk tanggal tertentu, seperti awal bulan, hari besar, atau hari nasional. Semua waktu menggunakan WIB (Asia/Jakarta).</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="starts_at" class="mb-1.5 block text-sm font-medium text-slate-700">Mulai pada <span class="text-rose-600">*</span><span class="font-normal text-slate-400"> (WIB)</span></label>
                <input id="starts_at" name="starts_at" type="datetime-local" required value="{{ old('starts_at', $discount?->starts_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10">
                @error('starts_at') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ends_at" class="mb-1.5 block text-sm font-medium text-slate-700">Berakhir pada <span class="font-normal text-slate-400">(WIB, opsional)</span></label>
                <input id="ends_at" name="ends_at" type="datetime-local" value="{{ old('ends_at', $discount?->ends_at?->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-brand-dark focus:ring-2 focus:ring-brand-dark/10">
                <p class="mt-1.5 text-xs text-slate-500">Kosongkan jika promo tidak memiliki tanggal akhir.</p>
                @error('ends_at') <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p> @enderror
            </div>
            @error('schedule')
                <p class="sm:col-span-2 rounded-lg bg-rose-50 px-3.5 py-3 text-sm text-rose-700">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
        <label for="is_active" class="flex cursor-pointer items-start gap-3">
            <input type="hidden" name="is_active" value="0">
            <input id="is_active" name="is_active" type="checkbox" value="1" @checked((bool) old('is_active', $discount?->is_active ?? true)) class="mt-0.5 h-4 w-4 rounded border-slate-300 text-brand-dark focus:ring-brand-dark">
            <span>
                <span class="block text-sm font-semibold text-slate-800">Aktifkan promo</span>
                <span class="mt-1 block text-xs text-slate-500">Promo aktif akan diterapkan otomatis sesuai jadwal.</span>
            </span>
        </label>

        <div class="flex flex-col-reverse gap-3 sm:flex-row">
            <a href="{{ route('admin.discounts.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-lg bg-brand-dark px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-accent">{{ $discount ? 'Simpan Perubahan' : 'Simpan Promo' }}</button>
        </div>
    </section>
</form>

<script>
    (() => {
        const typeInput = document.getElementById('discount_type');
        const valueInput = document.getElementById('discount_value');
        const productCheckboxes = [...document.querySelectorAll('.discount-product-checkbox')];
        const selectedCount = document.getElementById('selected-product-count');
        const unit = document.getElementById('discount-unit');
        const help = document.getElementById('discount-help');

        if (!typeInput || !valueInput || productCheckboxes.length === 0) return;

        const updateDiscountInput = () => {
            const isPercentage = typeInput.value === 'percentage';
            const selectedProducts = productCheckboxes.filter((checkbox) => checkbox.checked);
            const cheapestProduct = selectedProducts.reduce((cheapest, checkbox) => {
                return !cheapest || Number(checkbox.dataset.price) < Number(cheapest.dataset.price) ? checkbox : cheapest;
            }, null);
            const productPrice = cheapestProduct?.dataset.price ?? '';

            selectedCount.textContent = `${selectedProducts.length} produk dipilih`;

            unit.textContent = isPercentage ? '%' : 'Rp';
            valueInput.step = isPercentage ? '0.01' : '1';
            valueInput.min = isPercentage ? '0.01' : '1';
            valueInput.max = isPercentage ? '100' : productPrice;
            help.textContent = isPercentage
                ? 'Masukkan persentase lebih dari 0 sampai dengan 100.'
                : productPrice
                    ? `Maksimal Rp ${new Intl.NumberFormat('id-ID').format(productPrice)}, mengikuti harga produk termurah (${cheapestProduct.dataset.productName}).`
                    : 'Pilih minimal satu produk untuk melihat batas potongan.';
        };

        typeInput.addEventListener('change', updateDiscountInput);
        productCheckboxes.forEach((checkbox) => checkbox.addEventListener('change', updateDiscountInput));
        updateDiscountInput();
    })();
</script>
