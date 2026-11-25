@php
    $existingVariantTypes = isset($product)
        ? $product->variantTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name,
                'affects_price' => (bool) $type->affects_price,
                'variants' => $type->variants->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'name' => $variant->name,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                        'image' => $variant->image,
                        'is_active' => $variant->is_active,
                    ];
                })->all(),
            ];
        })->all()
        : [];

    $initialVariantTypes = old('variant_types', $existingVariantTypes);
@endphp

<div
    id="variant-builder"
    data-initial="{{ json_encode($initialVariantTypes, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
    class="mt-8 rounded-xl border border-gray-200 p-5"
>
    <input type="hidden" name="variant_types_present" value="1">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="font-semibold text-gray-800">Varian Produk</h3>
            <p class="mt-1 text-sm text-gray-500">
                Opsional. Tambahkan pilihan seperti rasa, ukuran, atau jenis produk.
            </p>
        </div>

        <button
            type="button"
            id="add-variant-type"
            class="rounded-lg bg-brand-dark px-4 py-2 text-sm font-medium text-white hover:bg-brand-accent"
        >
            + Tambah Jenis Varian
        </button>
    </div>

    <div id="variant-types-container" class="space-y-5"></div>

    <p id="variant-empty-message" class="rounded-lg bg-gray-50 p-4 text-sm text-gray-500">
        Produk ini belum memiliki varian. Biarkan kosong jika produk dijual tanpa pilihan varian.
    </p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const builder = document.getElementById('variant-builder');

    if (!builder) return;

    const container = document.getElementById('variant-types-container');
    const emptyMessage = document.getElementById('variant-empty-message');
    const addTypeButton = document.getElementById('add-variant-type');

    let variantTypes = JSON.parse(builder.dataset.initial || '[]');

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]);
    }

    function render() {
        container.innerHTML = '';

        emptyMessage.classList.toggle('hidden', variantTypes.length > 0);

        variantTypes.forEach((type, typeIndex) => {
            const typeElement = document.createElement('div');

            typeElement.className = 'variant-type-item rounded-lg border border-gray-200 p-4';

            const variants = (type.variants || []).map((variant, variantIndex) => `
                <div class="variant-item rounded-lg border border-gray-100 bg-gray-50 p-3" data-image="${escapeHtml(variant.image || '')}">
                    ${variant.id ? `<input type="hidden" name="variant_types[${typeIndex}][variants][${variantIndex}][id]" value="${variant.id}">` : ''}
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-700">
                            Pilihan ${variantIndex + 1}
                        </span>

                        <button
                            type="button"
                            data-remove-variant="${variantIndex}"
                            class="text-xs font-medium text-red-600 hover:text-red-800"
                        >
                            Hapus
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">
                                Nama Pilihan
                            </label>
                            <input
                                type="text"
                                name="variant_types[${typeIndex}][variants][${variantIndex}][name]"
                                value="${escapeHtml(variant.name)}"
                                placeholder="Contoh: Cokelat"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                            >
                        </div>

                        <div data-price-field class="${type.affects_price ? '' : 'hidden'}">
                            <label class="mb-1 block text-xs font-medium text-gray-600">
                                Harga Khusus (Rp)
                            </label>
                            <input
                                type="number"
                                name="variant_types[${typeIndex}][variants][${variantIndex}][price]"
                                value="${escapeHtml(variant.price)}"
                                min="0"
                                placeholder="Kosongkan untuk harga dasar"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">
                                Stok
                            </label>
                            <input
                                type="number"
                                name="variant_types[${typeIndex}][variants][${variantIndex}][stock]"
                                value="${escapeHtml(variant.stock)}"
                                min="0"
                                placeholder="Contoh: 50"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                            >
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-medium text-gray-600">
                                Foto Varian
                            </label>
                            ${variant.image ? `
                                <img
                                    src="/storage/${escapeHtml(variant.image)}"
                                    alt="Foto ${escapeHtml(variant.name)}"
                                    class="mb-2 h-20 w-20 rounded-lg border border-gray-200 bg-white object-contain p-1"
                                >
                                <p class="mb-2 text-xs text-gray-500">Foto saat ini</p>
                            ` : ''}
                            <div class="flex flex-wrap items-center gap-2">
                                <input
                                    id="variant-image-${typeIndex}-${variantIndex}"
                                    type="file"
                                    name="variant_types[${typeIndex}][variants][${variantIndex}][image]"
                                    accept="image/png,image/jpeg,image/webp"
                                    class="sr-only"
                                >
                                <label
                                    for="variant-image-${typeIndex}-${variantIndex}"
                                    class="inline-flex cursor-pointer items-center rounded-md bg-brand-dark px-2.5 py-1.5 text-xs font-medium text-white hover:bg-brand-accent"
                                >
                                    ${variant.image ? 'Ganti Foto' : 'Tambah Foto'}
                                </label>
                                <span data-variant-image-name class="text-xs text-gray-500"></span>
                            </div>
                        </div>
                    </div>

                    <label class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                        <input
                            type="checkbox"
                            name="variant_types[${typeIndex}][variants][${variantIndex}][is_active]"
                            value="1"
                            ${variant.is_active === false || variant.is_active === 0 || variant.is_active === '0' ? '' : 'checked'}
                            class="rounded border-gray-300 text-brand-dark"
                        >
                        Varian aktif
                    </label>
                </div>
            `).join('');

            typeElement.innerHTML = `
                ${type.id ? `<input type="hidden" name="variant_types[${typeIndex}][id]" value="${type.id}">` : ''}

                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <div class="min-w-0 flex-1">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Nama Jenis Varian
                        </label>

                        <input
                            type="text"
                            name="variant_types[${typeIndex}][name]"
                            value="${escapeHtml(type.name)}"
                            placeholder="Contoh: Rasa"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                        >
                    </div>

                    <label class="mb-0 inline-flex items-center gap-2 text-sm text-gray-600">
                        <input
                            type="checkbox"
                            name="variant_types[${typeIndex}][affects_price]"
                            value="1"
                            ${type.affects_price ? 'checked' : ''}
                            data-affects-price
                            class="rounded border-gray-300 text-brand-dark"
                        >
                        Jenis varian ini menentukan harga
                    </label>

                    <button
                        type="button"
                        data-remove-type="${typeIndex}"
                        class="mt-5 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                    >
                        Hapus Jenis
                    </button>
                </div>

                <div class="space-y-3">
                    ${variants}
                </div>

                <button
                    type="button"
                    data-add-variant
                    class="mt-3 rounded-lg border border-brand-dark px-3 py-2 text-sm font-medium text-brand-dark hover:bg-gray-50"
                >
                    + Tambah Pilihan
                </button>
            `;

            container.appendChild(typeElement);
        });
    }

    // Ambil nilai input dari tampilan sebelum melakukan render ulang.
    function collectVariantData() {
        const updatedTypes = [];

        container.querySelectorAll('.variant-type-item').forEach((typeElement, typeIndex) => {
            const typeId = typeElement.querySelector(
                `input[name="variant_types[${typeIndex}][id]"]`
            )?.value ?? null;

            const typeName = typeElement.querySelector(
                `input[name="variant_types[${typeIndex}][name]"]`
            )?.value ?? '';

            const type = {
                id: typeId,
                name: typeName,
                affects_price: typeElement.querySelector('[data-affects-price]')?.checked ?? false,
                variants: []
            };

            typeElement.querySelectorAll('.variant-item').forEach((variantElement) => {

                const variantId = variantElement.querySelector(
                    'input[type="hidden"][name$="[id]"]'
                )?.value ?? null;

                const variant = {
                    id: variantId,

                    name: variantElement.querySelector(
                        'input[name$="[name]"]'
                    )?.value ?? '',

                    price: variantElement.querySelector(
                        'input[name$="[price]"]'
                    )?.value ?? '',

                    stock: variantElement.querySelector(
                        'input[name$="[stock]"]'
                    )?.value ?? '',

                    image: variantElement.dataset.image ?? '',

                    is_active: variantElement.querySelector(
                        'input[type="checkbox"]'
                    )?.checked ?? false
                };

                type.variants.push(variant);
            });

            updatedTypes.push(type);
        });

        return updatedTypes;
    }

    addTypeButton.addEventListener('click', function () {
        variantTypes = collectVariantData();

        variantTypes.push({
            name: '',
            affects_price: false,
            variants: []
        });

        render();
    });

    container.addEventListener('click', function (event) {
        const addVariantButton = event.target.closest('[data-add-variant]');
        const removeVariantButton = event.target.closest('[data-remove-variant]');
        const removeTypeButton = event.target.closest('[data-remove-type]');

        const typeElement = event.target.closest('.variant-type-item');

        if (!typeElement) return;

        const typeIndex = Array.from(container.children).indexOf(typeElement);

        if (typeIndex < 0 || !variantTypes[typeIndex]) return;

        if (addVariantButton) {
            variantTypes = collectVariantData();

            variantTypes[typeIndex].variants.push({
                name: '',
                price: '',
                stock: '',
                is_active: true
            });

            render();
            return;
        }

        if (removeVariantButton) {
            variantTypes = collectVariantData();

            const variantIndex = Number(
                removeVariantButton.dataset.removeVariant
            );

            variantTypes[typeIndex].variants.splice(variantIndex, 1);

            render();
            return;
        }

        if (removeTypeButton) {
            variantTypes = collectVariantData();

            variantTypes.splice(typeIndex, 1);

            render();
        }
    });

    container.addEventListener('change', function (event) {
        const priceCheckbox = event.target.closest('[data-affects-price]');

        if (priceCheckbox) {
            const currentType = priceCheckbox.closest('.variant-type-item');

            if (priceCheckbox.checked) {
                container.querySelectorAll('[data-affects-price]').forEach((checkbox) => {
                    if (checkbox !== priceCheckbox) {
                        checkbox.checked = false;

                        const otherType = checkbox.closest('.variant-type-item');

                        otherType.querySelectorAll('[data-price-field]').forEach((field) => {
                            field.classList.add('hidden');
                        });
                    }
                });
            }

            currentType.querySelectorAll('[data-price-field]').forEach((field) => {
                field.classList.toggle('hidden', !priceCheckbox.checked);
            });

            return;
        }

        const imageInput = event.target.closest('input[type="file"]');

        if (!imageInput) return;

        const fileNameDisplay = imageInput
            .closest('.variant-item')
            .querySelector('[data-variant-image-name]');

        fileNameDisplay.textContent = imageInput.files[0]?.name ?? '';
    });

    render();
});
</script>
