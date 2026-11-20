
@php
    $nutritionData = old(
        'nutrition_facts',
        isset($product) ? ($product->nutrition_facts ?? []) : []
    );

    /*
    |--------------------------------------------------------------------------
    | Kompatibilitas data Nutrition Facts format lama
    |--------------------------------------------------------------------------
    | Data lama masih menggunakan key seperti protein.value,
    | protein.daily_value, dan protein.breastfeeding_daily_value.
    | Ubah sementara ke format groups dan columns agar bisa diedit.
    */

    if (
        !empty($nutritionData) &&
        !isset($nutritionData['groups'])
    ) {
        $legacyDefinitions = [
            'Komponen Gizi' => [
                'energy_total' => ['Energi Total', 'kkal'],
                'energy_from_fat' => ['Energi dari Lemak', 'kkal'],
                'energy_from_saturated_fat' => ['Energi dari Lemak Jenuh', 'kkal'],
                'total_fat' => ['Lemak Total', 'g'],
                'trans_fat' => ['Lemak Trans', 'g'],
                'cholesterol' => ['Kolesterol', 'mg'],
                'saturated_fat' => ['Lemak Jenuh', 'g'],
                'protein' => ['Protein', 'g'],
                'total_carbohydrate' => ['Karbohidrat Total', 'g'],
                'dietary_fiber' => ['Serat Pangan', 'g'],
                'soluble_fiber' => ['Serat Pangan Larut', 'g'],
                'total_sugars' => ['Gula Total', 'g'],
                'sucrose' => ['Sukrosa', 'g'],
                'sodium' => ['Natrium', 'mg'],
            ],
            'Vitamin dan Mineral' => [
                'vitamin_a' => ['Vitamin A', 'mcg'],
                'vitamin_d3' => ['Vitamin D3', 'mcg'],
                'vitamin_e' => ['Vitamin E', 'mg'],
                'vitamin_k' => ['Vitamin K', 'mcg'],
                'vitamin_b1' => ['Vitamin B1', 'mg'],
                'vitamin_b2' => ['Vitamin B2', 'mg'],
                'vitamin_b3' => ['Vitamin B3', 'mg'],
                'vitamin_b5' => ['Vitamin B5', 'mg'],
                'vitamin_b6' => ['Vitamin B6', 'mg'],
                'vitamin_b9' => ['Vitamin B9', 'mcg'],
                'vitamin_b12' => ['Vitamin B12', 'mcg'],
                'vitamin_c' => ['Vitamin C', 'mg'],
                'choline' => ['Kolin', 'mg'],
                'biotin' => ['Biotin', 'mcg'],
                'potassium' => ['Kalium', 'mg'],
                'calcium' => ['Kalsium', 'mg'],
                'phosphorus' => ['Fosfor', 'mg'],
                'magnesium' => ['Magnesium', 'mg'],
                'zinc' => ['Zink', 'mg'],
                'iron' => ['Zat Besi', 'mg'],
                'manganese' => ['Mangan', 'mg'],
                'iodine' => ['Iodium', 'mcg'],
                'selenium' => ['Selenium', 'mcg'],
            ],
            'Komponen Lain' => [
                'dha' => ['DHA', 'mg'],
                'omega_3' => ['Omega 3', 'mg'],
                'omega_6' => ['Omega 6', 'mg'],
            ],
        ];

        $legacyGroups = [];

        foreach ($legacyDefinitions as $groupTitle => $definitions) {
            $items = [];

            foreach ($definitions as $key => [$name, $unit]) {
                $legacyItem = $nutritionData[$key] ?? [];

                // Lewati nutrisi yang benar-benar tidak memiliki data.
                if (
                    !isset($legacyItem['value']) &&
                    !isset($legacyItem['daily_value']) &&
                    !isset($legacyItem['breastfeeding_daily_value'])
                ) {
                    continue;
                }

                $items[] = [
                    'name' => $name,
                    'unit' => $unit,
                    'value' => $legacyItem['value'] ?? '',
                    'percentages' => [
                        $legacyItem['daily_value'] ?? '',
                        $legacyItem['breastfeeding_daily_value'] ?? '',
                    ],
                ];
            }

            if (count($items)) {
                $legacyGroups[] = [
                    'title' => $groupTitle,
                    'items' => $items,
                ];
            }
        }

        $nutritionData = [
            'serving_size' => $nutritionData['serving_size'] ?? '',
            'servings_per_package' => $nutritionData['servings_per_package'] ?? '',
            'columns' => [
                ['label' => 'Hamil'],
                ['label' => 'Menyusui'],
            ],
            'groups' => $legacyGroups,
        ];
    }

    $nutritionData['columns'] = $nutritionData['columns'] ?? [];

    // Data awal untuk produk baru atau produk yang belum memiliki Nutrition Facts.
    if (empty($nutritionData['groups'])) {
        $nutritionData['groups'] = [
            [
                'title' => 'Komponen Gizi',
                'columns' => [],
                'items' => [
                    [
                        'name' => '',
                        'unit' => 'g',
                        'value' => '',
                        'percentages' => [],
                    ],
                ],
            ],
        ];
    }

    foreach ($nutritionData['groups'] as &$group) {
        $group['columns'] = $group['columns'] ?? $nutritionData['columns'];
    }
    unset($group);
@endphp

<div
    id="dynamic-nutrition-builder"
    data-initial="{{ json_encode($nutritionData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
    class="space-y-6"
>
    {{-- Informasi takaran saji --}}
    <div class="grid grid-cols-1 gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Takaran Saji <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="nutrition-serving-size"
                name="nutrition_facts[serving_size]"
                value="{{ $nutritionData['serving_size'] ?? '' }}"
                placeholder="Contoh: 3 sendok makan (40 g)"
                data-nutrition-input
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-dark focus:outline-none"
            >
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Jumlah Sajian per Kemasan <span class="text-red-500">*</span>
            </label>

            <input
                type="text"
                id="nutrition-servings-per-package"
                name="nutrition_facts[servings_per_package]"
                value="{{ $nutritionData['servings_per_package'] ?? '' }}"
                placeholder="Contoh: ± 5"
                data-nutrition-input
                required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-brand-dark focus:outline-none"
            >
        </div>
    </div>

    {{-- Pengaturan kolom AKG --}}
    <section class="rounded-xl border border-gray-200 p-4">
        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="font-semibold text-gray-800">Kolom AKG</h3>
                <p class="text-xs text-gray-500">
                    Tambahkan kategori sesuai kelompok konsumen produk.
                </p>
            </div>

            <button
                type="button"
                id="add-nutrition-column"
                class="rounded-lg bg-brand-dark px-3 py-2 text-sm font-medium text-white hover:bg-brand-accent"
            >
                + Tambah Kolom AKG
            </button>
        </div>

        <div id="nutrition-columns-container" class="flex flex-wrap gap-3"></div>

        <p id="nutrition-no-columns" class="hidden text-sm text-gray-500">
            Belum ada kolom AKG. Tabel hanya akan menampilkan nilai per saji.
        </p>
    </section>

    {{-- Grup dan baris nutrisi --}}
    <section class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="font-semibold text-gray-800">Komponen Nutrition Facts</h3>
                <p class="text-xs text-gray-500">
                    Atur kelompok dan komponen gizi sesuai informasi produk.
                </p>
            </div>

            <button
                type="button"
                id="add-nutrition-group"
                class="rounded-lg border border-brand-dark px-3 py-2 text-sm font-medium text-brand-dark hover:bg-gray-50"
            >
                + Tambah Grup
            </button>
        </div>

        <div id="nutrition-groups-container" class="space-y-5"></div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const builder = document.getElementById('dynamic-nutrition-builder');

    if (!builder) return;

    const columnsContainer = document.getElementById('nutrition-columns-container');
    const groupsContainer = document.getElementById('nutrition-groups-container');
    const noColumnsMessage = document.getElementById('nutrition-no-columns');

    let nutritionData = JSON.parse(builder.dataset.initial);

    nutritionData.columns = nutritionData.columns || [];
    nutritionData.groups = nutritionData.groups || [];

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (character) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[character];
        });
    }

    function collectFormData() {
        nutritionData.columns = Array.from(
            columnsContainer.querySelectorAll('[data-column-label]')
        ).map(input => ({
            label: input.value
        }));

        nutritionData.groups = Array.from(
            groupsContainer.querySelectorAll('[data-group]')
        ).map(groupElement => {
            const groupTitle = groupElement.querySelector('[data-group-title]');
            const columns = Array.from(
                groupElement.querySelectorAll('[data-group-column-label]')
            ).map(input => ({
                label: input.value
            }));

            const items = Array.from(
                groupElement.querySelectorAll('[data-item]')
            ).map(itemElement => {
                const percentages = Array.from(
                    itemElement.querySelectorAll('[data-percentage]')
                ).map(input => input.value);

                return {
                    name: itemElement.querySelector('[data-item-name]').value,
                    unit: itemElement.querySelector('[data-item-unit]').value,
                    value: itemElement.querySelector('[data-item-value]').value,
                    percentages: percentages
                };
            });

            return {
                title: groupTitle.value,
                columns: columns,
                items: items
            };
        });
    }

    function renderColumns() {
        columnsContainer.innerHTML = '';

        noColumnsMessage.classList.toggle(
            'hidden',
            nutritionData.columns.length > 0
        );

        nutritionData.columns.forEach((column, index) => {
            const element = document.createElement('div');

            element.className = 'flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2';

            element.innerHTML = `
                <input
                    type="text"
                    name="nutrition_facts[columns][${index}][label]"
                    value="${escapeHtml(column.label)}"
                    placeholder="Contoh: Anak 1-3 Tahun"
                    data-column-label
                    required
                    class="w-48 rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-dark focus:outline-none"
                >

                <button
                    type="button"
                    data-remove-column="${index}"
                    class="rounded-md px-2 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                    title="Hapus kolom"
                >
                    Hapus
                </button>
            `;

            columnsContainer.appendChild(element);
        });
    }

    function renderGroups() {
        groupsContainer.innerHTML = '';

        nutritionData.groups.forEach((group, groupIndex) => {
            const groupElement = document.createElement('div');

            groupElement.className = 'overflow-hidden rounded-xl border border-gray-200';
            groupElement.dataset.group = groupIndex;

            const tableHeader = (group.columns || []).map((column, columnIndex) => `
                <th class="min-w-32 border border-gray-200 px-2 py-2 text-center">
                    <input
                        type="text"
                        name="nutrition_facts[groups][${groupIndex}][columns][${columnIndex}][label]"
                        value="${escapeHtml(column.label)}"
                        data-group-column-label
                        required
                        class="w-32 rounded-md border border-gray-300 px-2 py-1 text-center text-sm focus:border-brand-dark focus:outline-none"
                    >
                </th>
            `).join('');

            const itemRows = (group.items || []).map((item, itemIndex) => {
                const percentageInputs = (group.columns || []).map((column, columnIndex) => `
                    <td class="border border-gray-200 p-2">
                        <input
                            type="text"
                            name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][percentages][${columnIndex}]"
                            value="${escapeHtml(item.percentages?.[columnIndex] ?? '')}"
                            placeholder="-"
                            data-percentage
                            required
                            class="w-full min-w-20 rounded-md border border-gray-300 px-2 py-2 text-center text-sm focus:border-brand-dark focus:outline-none"
                        >
                    </td>
                `).join('');

                return `
                    <tr data-item>
                        <td class="min-w-48 border border-gray-200 p-2">
                            <input
                                type="text"
                                name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][name]"
                                value="${escapeHtml(item.name)}"
                                placeholder="Nama komponen gizi"
                                data-item-name
                                required
                                class="w-full rounded-md border border-gray-300 px-2 py-2 text-sm focus:border-brand-dark focus:outline-none"
                            >
                        </td>

                        <td class="min-w-28 border border-gray-200 p-2">
                            <div class="flex items-center gap-1">
                                <input
                                    type="text"
                                    name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][value]"
                                    value="${escapeHtml(item.value)}"
                                    placeholder="-"
                                    data-item-value
                                    required
                                    class="w-full min-w-0 rounded-md border border-gray-300 px-2 py-2 text-center text-sm focus:border-brand-dark focus:outline-none"
                                >

                                <input
                                    type="text"
                                    name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][unit]"
                                    value="${escapeHtml(item.unit)}"
                                    placeholder="Satuan"
                                    data-item-unit
                                    class="w-16 rounded-md border border-gray-300 px-1 py-2 text-center text-xs focus:border-brand-dark focus:outline-none"
                                >
                            </div>
                        </td>

                        ${percentageInputs}

                        <td class="border border-gray-200 p-2 text-center">
                            <button
                                type="button"
                                data-remove-item="${itemIndex}"
                                class="rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                            >
                                Hapus
                            </button>
                        </td>
                    `}).join('');

            groupElement.innerHTML = `
                <div class="flex flex-wrap items-center gap-3 bg-gray-100 p-3">
                    <input
                        type="text"
                        name="nutrition_facts[groups][${groupIndex}][title]"
                        value="${escapeHtml(group.title)}"
                        placeholder="Nama grup nutrisi"
                        data-group-title
                        required
                        class="min-w-0 flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold focus:border-brand-dark focus:outline-none"
                    >

                    <button
                        type="button"
                        data-add-item
                        class="rounded-lg border border-brand-dark bg-white px-3 py-2 text-xs font-medium text-brand-dark hover:bg-gray-50"
                    >
                        + Tambah Nutrisi
                    </button>

                    <button
                        type="button"
                        data-remove-group
                        class="rounded-lg px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50"
                    >
                        Hapus Grup
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-max border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="min-w-48 border border-gray-200 px-3 py-2 text-left">
                                    Komponen Gizi
                                </th>
                                <th class="min-w-28 border border-gray-200 px-3 py-2 text-center">
                                    Per Saji
                                </th>
                                ${tableHeader}
                                <th class="border border-gray-200 px-3 py-2 text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            ${itemRows}
                        </tbody>
                    </table>
                </div>
            `;

            groupsContainer.appendChild(groupElement);
        });
    }

    function render() {
        renderColumns();
        renderGroups();
    }

    document.getElementById('add-nutrition-column').addEventListener('click', function () {
        collectFormData();

        nutritionData.columns.push({
            label: 'Kolom Baru'
        });

        nutritionData.groups.forEach(group => {
            group.columns = group.columns || [];
            group.columns.push({
                label: 'Kolom Baru'
            });

            group.items.forEach(item => {
                item.percentages = item.percentages || [];
                item.percentages.push('');
            });
        });

        render();
    });

    document.getElementById('add-nutrition-group').addEventListener('click', function () {
        collectFormData();

        nutritionData.groups.push({
            title: 'Grup Baru',
            columns: nutritionData.columns.map(column => ({
                label: column.label
            })),
            items: [
                {
                    name: '',
                    unit: '',
                    value: '',
                    percentages: nutritionData.columns.map(() => '')
                }
            ]
        });

        render();
    });

    columnsContainer.addEventListener('click', function (event) {
        const button = event.target.closest('[data-remove-column]');

        if (!button) return;

        collectFormData();

        const index = Number(button.dataset.removeColumn);

        nutritionData.columns.splice(index, 1);

        render();
    });

    groupsContainer.addEventListener('click', function (event) {
        const addItemButton = event.target.closest('[data-add-item]');
        const removeItemButton = event.target.closest('[data-remove-item]');
        const removeGroupButton = event.target.closest('[data-remove-group]');

        if (!addItemButton && !removeItemButton && !removeGroupButton) {
            return;
        }

        collectFormData();

        const groupElement = event.target.closest('[data-group]');
        const groupIndex = Number(groupElement.dataset.group);

        if (addItemButton) {
            nutritionData.groups[groupIndex].items.push({
                name: '',
                unit: '',
                value: '',
                percentages: nutritionData.groups[groupIndex].columns.map(() => '')
            });
        }

        if (removeItemButton) {
            const itemIndex = Number(removeItemButton.dataset.removeItem);

            if (nutritionData.groups[groupIndex].items.length <= 1) {
                alert('Setiap grup harus memiliki minimal satu komponen nutrisi.');
                return;
            }

            nutritionData.groups[groupIndex].items.splice(itemIndex, 1);
        }

        if (removeGroupButton) {
            if (nutritionData.groups.length <= 1) {
                alert('Minimal harus ada satu grup nutrisi.');
                return;
            }

            nutritionData.groups.splice(groupIndex, 1);
        }

        render();
    });

    render();
});
</script>
