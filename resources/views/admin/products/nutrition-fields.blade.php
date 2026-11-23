
@php
    $nutritionData = old(
        'nutrition_facts',
        isset($product) ? ($product->nutrition_facts ?? []) : []
    );

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

    // Data awal untuk produk baru atau produk yang belum memiliki Nutrition Facts.
    if (empty($nutritionData['groups'])) {
        $nutritionData['groups'] = [
            [
                'title' => 'Komponen Gizi',
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

    $nutritionData['columns'] = $nutritionData['columns'] ?? [];
@endphp

<div
    id="dynamic-nutrition-builder"
    data-initial="{{ json_encode($nutritionData, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
    class="min-w-0 space-y-6"
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

        <div id="nutrition-groups-container" class="min-w-0 space-y-5"></div>
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

    const legacyColumns = nutritionData.columns || [];

    nutritionData.groups = nutritionData.groups || [];
    nutritionData.groups.forEach(group => {
        if (!Array.isArray(group.columns)) {
            group.columns = legacyColumns.map(column => ({
                label: column.label
            }));
        }

        group.items = group.items || [];
    });

    function normalizePercentages() {
        nutritionData.groups.forEach(group => {
            group.columns = group.columns || [];
            group.items = group.items || [];

            group.items.forEach(item => {
                const percentages = Array.isArray(item.percentages)
                    ? item.percentages
                    : [];

                item.percentages = group.columns.map((column, index) => (
                    percentages[index] ?? ''
                ));
            });
        });
    }

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

        normalizePercentages();
    }

    function renderGroups() {
        groupsContainer.innerHTML = '';

        nutritionData.groups.forEach((group, groupIndex) => {
            group.columns = group.columns || [];

            const groupElement = document.createElement('div');

            groupElement.className = 'min-w-0 overflow-hidden rounded-xl border border-gray-200';
            groupElement.dataset.group = groupIndex;

            const tableHeader = group.columns.map((column, columnIndex) => `
                <th class="min-w-32 border border-gray-200 px-3 py-2 text-center">
                    ${escapeHtml(column.label)}
                </th>
            `).join('');

            const itemRows = (group.items || []).map((item, itemIndex) => {
                const percentageInputs = group.columns.map((column, columnIndex) => `
                    <td class="grid grid-cols-[6rem_minmax(0,1fr)] items-center gap-2 border-b border-gray-100 px-3 py-3 md:table-cell md:border md:p-2">
                        <span class="text-xs font-medium text-gray-500 md:hidden">
                            ${escapeHtml(column.label || `Kolom ${columnIndex + 1}`)}
                        </span>

                        <input
                            type="text"
                            name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][percentages][${columnIndex}]"
                            value="${escapeHtml(item.percentages?.[columnIndex] ?? '')}"
                            placeholder="-"
                            data-percentage
                            class="w-full min-w-0 rounded-md border border-gray-300 px-2 py-2 text-center text-sm focus:border-brand-dark focus:outline-none"
                        >
                    </td>
                `).join('');

                return `
                    <tr data-item class="block border-b border-gray-100 last:border-b-0 md:table-row">
                        <td class="grid grid-cols-[6rem_minmax(0,1fr)] items-center gap-2 border-b border-gray-100 px-3 py-3 md:table-cell md:min-w-48 md:border md:p-2">
                            <span class="text-xs font-medium text-gray-500 md:hidden">Komponen Gizi</span>
                            <input
                                type="text"
                                name="nutrition_facts[groups][${groupIndex}][items][${itemIndex}][name]"
                                value="${escapeHtml(item.name)}"
                                placeholder="Nama komponen gizi"
                                data-item-name
                                required
                                class="w-full min-w-0 rounded-md border border-gray-300 px-2 py-2 text-sm focus:border-brand-dark focus:outline-none"
                            >
                        </td>

                        <td class="grid grid-cols-[6rem_minmax(0,1fr)] items-center gap-2 border-b border-gray-100 px-3 py-3 md:table-cell md:min-w-28 md:border md:p-2">
                            <span class="text-xs font-medium text-gray-500 md:hidden">Per Saji</span>
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

                        <td class="grid grid-cols-[6rem_minmax(0,1fr)] items-center gap-2 border-b border-gray-100 px-3 py-3 text-left md:table-cell md:border md:p-2 md:text-center">
                            <span class="text-xs font-medium text-gray-500 md:hidden">Aksi</span>
                            <button
                                type="button"
                                data-remove-item="${itemIndex}"
                                class="whitespace-nowrap rounded-md px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                            >
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            groupElement.innerHTML = `
                <div class="flex flex-col items-stretch gap-3 bg-gray-100 p-3 sm:flex-row sm:items-center">
                    <input
                        type="text"
                        value="${escapeHtml(group.title)}"
                        placeholder="Nama grup nutrisi"
                        data-group-title
                        required
                        class="w-full min-w-0 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold focus:border-brand-dark focus:outline-none sm:flex-1"
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

                {{-- Kolom AKG khusus grup ini --}}
                <div class="space-y-3 border-b border-gray-200 p-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="text-sm font-medium text-gray-700">
                            Kolom AKG grup ini
                        </p>

                        <button
                            type="button"
                            data-add-column
                            class="rounded-lg bg-brand-dark px-3 py-2 text-xs font-medium text-white hover:bg-brand-accent disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            + Tambah Kolom AKG
                        </button>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        ${group.columns.map((column, columnIndex) => `
                            <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2">
                                <input
                                    type="text"
                                    name="nutrition_facts[groups][${groupIndex}][columns][${columnIndex}][label]"
                                    value="${escapeHtml(column.label)}"
                                    placeholder="Nama AKG"
                                    data-group-column-label
                                    class="w-40 rounded-md border border-gray-300 px-2 py-2 text-sm focus:border-brand-dark focus:outline-none"
                                >

                                <button
                                    type="button"
                                    data-remove-group-column="${columnIndex}"
                                    class="rounded-md px-2 py-2 text-xs font-medium text-red-600 hover:bg-red-50"
                                >
                                    Hapus
                                </button>
                            </div>
                        `).join('')}
                    </div>
                </div>

                <div class="max-w-full overflow-x-auto">
                    <table class="w-full min-w-full border-collapse text-sm md:min-w-max">
                        <thead class="hidden md:table-header-group">
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
        renderGroups();
    }

    document.getElementById('add-nutrition-group').addEventListener('click', function () {
        collectFormData();

        nutritionData.groups.push({
            title: 'Grup Baru',
            columns: [],
            items: [
                {
                    name: '',
                    unit: '',
                    value: '',
                    percentages: []
                }
            ]
        });

        render();
    });

    groupsContainer.addEventListener('click', function (event) {
        const addColumnButton = event.target.closest('[data-add-column]');
        const removeColumnButton = event.target.closest('[data-remove-group-column]');
        const addItemButton = event.target.closest('[data-add-item]');
        const removeItemButton = event.target.closest('[data-remove-item]');
        const removeGroupButton = event.target.closest('[data-remove-group]');

        if (
            !addColumnButton &&
            !removeColumnButton &&
            !addItemButton &&
            !removeItemButton &&
            !removeGroupButton
        ) {
            return;
        }

        const groupElement = event.target.closest('[data-group]');
        if (!groupElement) return;

        const groupIndex = Number(groupElement.dataset.group);

        collectFormData();

        const group = nutritionData.groups[groupIndex];

        if (addColumnButton) {
            group.columns.push({
                label: 'Kolom Baru'
            });

            group.items.forEach(item => {
                item.percentages.push('');
            });
        }

        if (removeColumnButton) {
            const columnIndex = Number(removeColumnButton.dataset.removeGroupColumn);

            group.columns.splice(columnIndex, 1);

            group.items.forEach(item => {
                item.percentages.splice(columnIndex, 1);
            });
        }

        if (addItemButton) {
            group.items.push({
                name: '',
                unit: '',
                value: '',
                percentages: group.columns.map(() => '')
            });
        }

        if (removeItemButton) {
            const itemIndex = Number(removeItemButton.dataset.removeItem);

            if (group.items.length <= 1) {
                alert('Setiap grup harus memiliki minimal satu komponen nutrisi.');
                return;
            }

            group.items.splice(itemIndex, 1);
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

    columnsContainer.addEventListener('input', function (event) {
        const input = event.target.closest('[data-column-index]');

        if (!input) {
            return;
        }

        const index = Number(input.dataset.columnIndex);
        const label = input.value || `Kolom ${index + 1}`;

        groupsContainer.querySelectorAll(`[data-column-header="${index}"]`).forEach(header => {
            header.textContent = label;
        });

        groupsContainer.querySelectorAll(`[data-column-label-display="${index}"]`).forEach(labelElement => {
            labelElement.textContent = label;
        });
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
                percentages: nutritionData.columns.map(() => '')
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

    normalizePercentages();
    render();
});
</script>
