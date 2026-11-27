

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('cart', {
    items: [],
    isOpen: false,
    openedFrom: null,
    previousBodyPaddingRight: null,
    pendingRemoval: null,
    removalTrigger: null,
    whatsappNumber: document.body?.dataset.whatsappNumber ?? '',
    productsUrl: document.body?.dataset.cartProductsUrl ?? '',
    addProductsOpen: false,
    addableProducts: [],
    productsLoaded: false,
    productsLoading: false,
    productsError: '',
    productSearch: '',
    lastAddedProduct: '',
    addedProductTimer: null,

    init() {
        try {
            const savedItems = JSON.parse(
                window.localStorage.getItem('lactona-cart') || '[]'
            );

            if (Array.isArray(savedItems)) {
                this.items = savedItems
                    .filter(item => (
                        item
                        && typeof item.key === 'string'
                        && typeof item.name === 'string'
                        && Number.isFinite(Number(item.unitPrice))
                        && Number.isFinite(Number(item.quantity))
                    ))
                    .map(item => ({
                        ...item,
                        unitPrice: Math.max(0, Math.round(Number(item.unitPrice))),
                        quantity: Math.max(1, Math.floor(Number(item.quantity))),
                    }));
            }
        } catch {
            this.items = [];
        }
    },

    get totalProducts() {
        return this.items.length;
    },

    get totalQuantity() {
        return this.items.reduce((total, item) => total + item.quantity, 0);
    },

    get subtotal() {
        return this.items.reduce(
            (total, item) => total + (item.unitPrice * item.quantity),
            0
        );
    },

    open() {
        this.openedFrom = document.activeElement;

        if (!this.isOpen) {
            this.previousBodyPaddingRight = document.body.style.paddingRight;

            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;

            if (scrollbarWidth > 0) {
                const currentPaddingRight = Number.parseFloat(
                    window.getComputedStyle(document.body).paddingRight
                ) || 0;

                document.body.style.paddingRight = (currentPaddingRight + scrollbarWidth) + 'px';
            }
        }

        this.isOpen = true;

        window.requestAnimationFrame(() => {
            document.getElementById('cart-drawer-panel')?.focus();
        });
    },

    close() {
        if (this.isOpen) {
            document.body.style.paddingRight = this.previousBodyPaddingRight ?? '';
            this.previousBodyPaddingRight = null;
        }

        this.isOpen = false;

        if (this.openedFrom instanceof HTMLElement && this.openedFrom.isConnected) {
            this.openedFrom.focus();
        }
    },

    add(product) {
        const variants = Array.isArray(product.variants) ? product.variants : [];
        const variantKey = variants
            .map(variant => String(variant.typeId) + '-' + String(variant.variantId))
            .sort()
            .join('|');
        const key = String(product.productId) + ':' + variantKey;
        const existingItem = this.items.find(item => item.key === key);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                key,
                productId: String(product.productId),
                name: String(product.name),
                image: String(product.image || ''),
                unitPrice: Math.max(0, Math.round(Number(product.unitPrice) || 0)),
                variants: variants.map(variant => ({
                    typeId: Number(variant.typeId),
                    variantId: Number(variant.variantId),
                    typeName: String(variant.typeName),
                    name: String(variant.name),
                })),

                variantLabel: variants.map(variant =>
                    String(variant.typeName) + ': ' + String(variant.name)
                ).join(' / '),
                quantity: 1,
            });
        }

        this.persist();
    },

    updateQuantity(key, amount) {
        const item = this.items.find(cartItem => cartItem.key === key);

        if (!item) {
            return;
        }

        if (item.quantity + amount <= 0) {
            this.remove(key);

            return;
        }

        item.quantity += amount;
        this.persist();
    },

    remove(key) {
        const item = this.items.find(cartItem => cartItem.key === key);

        if (!item) {
            return;
        }

        this.removalTrigger = document.activeElement;
        this.pendingRemoval = {
            key: item.key,
            name: item.name,
        };

        window.requestAnimationFrame(() => {
            document.getElementById('cart-removal-cancel')?.focus();
        });
    },

    toggleProductPicker() {
        this.addProductsOpen = !this.addProductsOpen;

        if (this.addProductsOpen && !this.productsLoaded) {
            this.loadAddableProducts();
        }
    },

    async loadAddableProducts() {
        if (this.productsLoading || this.productsLoaded) {
            return;
        }

        this.productsLoading = true;
        this.productsError = '';

        try {
            const response = await fetch(this.productsUrl, {
                headers: {
                    Accept: 'application/json',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error('Product list request failed.');
            }

            const products = await response.json();

            this.addableProducts = Array.isArray(products)
                ? products.map(product => ({
                    ...product,
                    selectedVariants: {},
                    variantError: '',
                }))
                : [];
            this.productsLoaded = true;
        } catch {
            this.productsError = 'Produk belum dapat dimuat. Coba lagi.';
        } finally {
            this.productsLoading = false;
        }
    },

    get filteredAddableProducts() {
        const search = this.productSearch.trim().toLowerCase();
        const productsInCart = new Set(
            this.items.map(item => String(item.productId))
        );

        return this.addableProducts.filter(product => {
            return (
                !productsInCart.has(String(product.id))
                && (!search || product.name.toLowerCase().includes(search))
            );
        });
    },

    priceForProduct(product) {
        let price = Number(product.price) || 0;
        const priceType = product.variantTypes.find(type => type.affectsPrice);

        if (priceType) {
            const selectedVariant = priceType.variants.find(variant => (
                String(variant.id) === String(product.selectedVariants[priceType.id])
            ));

            if (selectedVariant && Number(selectedVariant.price) > 0) {
                price = Number(selectedVariant.price);
            }
        }

        const discountValue = Number(product.discount?.value) || 0;

        if (product.discount?.type === 'percentage') {
            price = Math.max(0, price - (price * discountValue / 100));
        } else if (product.discount?.type === 'fixed') {
            price = Math.max(0, price - discountValue);
        }

        return Math.round(price);
    },

    quickAddProduct(product) {
        const missingType = product.variantTypes.find(type => (
            !product.selectedVariants[type.id]
        ));

        if (missingType) {
            product.variantError = 'Pilih ' + missingType.name + ' terlebih dahulu.';

            return;
        }

        const selectedVariants = product.variantTypes.map(type => {
            const variant = type.variants.find(option => (
                String(option.id) === String(product.selectedVariants[type.id])
            ));

            return {
                typeId: type.id,
                variantId: variant.id,
                typeName: type.name,
                name: variant.name,
                price: variant.price,
                image: variant.image,
            };
        });
        const imageVariant = selectedVariants.find(variant => variant.image);

        this.add({
            productId: product.id,
            name: product.name,
            image: imageVariant?.image || product.image,
            unitPrice: this.priceForProduct(product),
            variants: selectedVariants,
        });
        product.variantError = '';
        this.lastAddedProduct = product.name;

        window.clearTimeout(this.addedProductTimer);
        this.addedProductTimer = window.setTimeout(() => {
            this.lastAddedProduct = '';
            this.addedProductTimer = null;
        }, 1800);
    },

    confirmRemoval() {
        if (!this.pendingRemoval) {
            return;
        }

        this.items = this.items.filter(item => item.key !== this.pendingRemoval.key);
        this.persist();
        this.pendingRemoval = null;
        this.removalTrigger = null;

        window.requestAnimationFrame(() => {
            document.getElementById('cart-drawer-panel')?.focus();
        });
    },

    cancelRemoval() {
        const trigger = this.removalTrigger;

        this.pendingRemoval = null;
        this.removalTrigger = null;

        window.requestAnimationFrame(() => {
            if (trigger instanceof HTMLElement && trigger.isConnected) {
                trigger.focus();
            }
        });
    },

    handleEscape() {
        if (this.pendingRemoval) {
            this.cancelRemoval();

            return;
        }

        this.close();
    },

    formatPrice(value) {
        return new Intl.NumberFormat('id-ID').format(
            Math.round(Number(value) || 0)
        );
    },

    persist() {
        try {
            window.localStorage.setItem('lactona-cart', JSON.stringify(this.items));
        } catch {
            // Keep the current cart usable for this page even if storage is unavailable.
        }
    },

    nextOrderNumber() {
        const storageKey = 'lactona-order-counter';

        try {
            const storedOrderNumber = Number(window.localStorage.getItem(storageKey));
            const nextOrderNumber = (
                Number.isFinite(storedOrderNumber) && storedOrderNumber > 0
                    ? Math.floor(storedOrderNumber)
                    : 0
            ) + 1;

            window.localStorage.setItem(storageKey, String(nextOrderNumber));

            return nextOrderNumber;
        } catch {
            return Date.now();
        }
    },

    
    async sendToWhatsapp(form) {
        if (this.items.length === 0 || !form.reportValidity()) {
            return;
        }

        let adminNumber = this.whatsappNumber.replace(/\D/g, '');

        if (adminNumber.startsWith('0')) {
            adminNumber = '62' + adminNumber.slice(1);
        }

        if (!adminNumber) {
            alert('Nomor WhatsApp admin belum tersedia.');
            return;
        }

        const formData = new FormData(form);

        // Buka tab terlebih dahulu agar tidak diblokir browser.
        const whatsappWindow = window.open('about:blank', '_blank');

        if (!whatsappWindow) {
            alert('Browser memblokir tab WhatsApp. Izinkan pop-up untuk situs ini, lalu coba kembali.');
            return;
        }

        whatsappWindow.opener = null;

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        )?.content || '';

        const payload = {
            name: String(formData.get('name') || '').trim(),
            phone: String(formData.get('phone') || '').trim(),
            address: String(formData.get('address') || '').trim(),
            items: this.items.map(item => ({
                productId: Number(item.productId),
                quantity: Number(item.quantity),
                variants: (Array.isArray(item.variants) ? item.variants : [])
                    .reduce((result, variant) => {
                        result[variant.typeId] = variant.variantId;
                        return result;
                    }, {}),
            })),
        };

        try {
            const response = await fetch(document.body.dataset.checkoutUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message || 'Pesanan gagal disimpan. Silakan coba kembali.'
                );
            }

            const itemDetails = this.items.map(item => (
                '- ' + item.name
                + (item.variantLabel ? ' - ' + item.variantLabel : '')
                + ' (x' + item.quantity + ') = Rp'
                + this.formatPrice(item.unitPrice * item.quantity)
            )).join('\n');

            const message = [
                'Halo Admin Lactona Mart, saya ingin memesan barang:',
                '',
                '*Nomor Pesanan:* #' + result.order_number,
                '*Nama:* ' + payload.name,
                '*Alamat:* ' + payload.address,
                '',
                '*Rincian Pesanan:*',
                itemDetails,
                '',
                '*Total Pembayaran: Rp ' + this.formatPrice(result.total_amount) + '*',
                '',
                'Mohon info selanjutnya untuk proses pembayaran. Terima kasih.',
            ].join('\n');

            const whatsappUrl = 'https://wa.me/' + adminNumber
                + '?text=' + encodeURIComponent(message);

            whatsappWindow.location.href = whatsappUrl;

        } catch (error) {
            whatsappWindow.close();

            console.error('Checkout error:', error);

            alert(
                error.message || 'Terjadi kesalahan saat menyimpan pesanan.'
            );
        }
    },
});

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

    if (sidebar && sidebarToggle && sidebarOverlay) {
        function setSidebarOpen(isOpen) {
            sidebar.classList.toggle('translate-x-0', isOpen);
            sidebar.classList.toggle('-translate-x-full', !isOpen);
            sidebarOverlay.hidden = !isOpen;
            sidebarOverlay.classList.toggle('hidden', !isOpen);
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            sidebarToggle.setAttribute('aria-label', isOpen ? 'Tutup navigasi' : 'Buka navigasi');
        }

        sidebarToggle.addEventListener('click', function () {
            setSidebarOpen(sidebarToggle.getAttribute('aria-expanded') !== 'true');
        });

        sidebarOverlay.addEventListener('click', function () {
            setSidebarOpen(false);
        });
    }

    const form = document.querySelector('[data-product-wizard]');

    if (!form) {
        return;
    }

    const steps = Array.from(form.querySelectorAll('[data-product-step]'));
    const indicators = Array.from(form.querySelectorAll('[data-step-indicator]'));
    const progressLine = form.querySelector('[data-step-progress]');
    const nutritionToggle = form.querySelector('[name="nutrition_facts_enabled"]');
    const nutritionFields = form.querySelector('#nutrition-fields-wrapper');
    let activeStep = Number(form.dataset.startStep) || 1;

    function showStep(stepNumber) {
        activeStep = stepNumber;

        steps.forEach(function (step) {
            const isActive = Number(step.dataset.productStep) === activeStep;

            step.hidden = !isActive;
            step.classList.toggle('hidden', !isActive);
        });

        indicators.forEach(function (indicator) {
            const stepNumber = Number(indicator.dataset.stepIndicator);
            const isActive = stepNumber === activeStep;
            const isReached = stepNumber <= activeStep;
            const marker = indicator.querySelector('[data-step-marker]');

            indicator.setAttribute('aria-current', isActive ? 'step' : 'false');
            indicator.classList.toggle('text-brand-dark', isReached);
            indicator.classList.toggle('text-gray-500', !isReached);

            if (marker) {
                marker.classList.toggle('bg-brand-dark', isReached);
                marker.classList.toggle('text-white', isReached);
                marker.classList.toggle('bg-gray-100', !isReached);
                marker.classList.toggle('text-gray-600', !isReached);
            }
        });

        if (progressLine) {
            progressLine.classList.toggle('bg-gray-200', activeStep <= 1);
            progressLine.classList.toggle('bg-brand-dark', activeStep > 1);
        }
    }

    form.querySelectorAll('[data-step-next]').forEach(function (button) {
        button.addEventListener('click', function () {
            const currentPanel = steps.find(function (step) {
                return Number(step.dataset.productStep) === activeStep;
            });

            const invalidControl = Array.from(
                currentPanel.querySelectorAll('input, select, textarea')
            ).find(function (control) {
                return !control.disabled && !control.checkValidity();
            });

            if (invalidControl) {
                invalidControl.reportValidity();

                return;
            }

            showStep(activeStep + 1);
        });
    });

    form.querySelectorAll('[data-step-previous]').forEach(function (button) {
        button.addEventListener('click', function () {
            showStep(activeStep - 1);
        });
    });

    if (nutritionToggle && nutritionFields) {
        nutritionToggle.addEventListener('change', function () {
            nutritionFields.disabled = !nutritionToggle.checked;
            nutritionFields.classList.toggle('opacity-50', !nutritionToggle.checked);
        });
    }

    showStep(activeStep);
});
