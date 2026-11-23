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
            const isActive = Number(indicator.dataset.stepIndicator) === activeStep;

            indicator.setAttribute('aria-current', isActive ? 'step' : 'false');
            indicator.classList.toggle('border-brand-dark', isActive);
            indicator.classList.toggle('bg-brand-light', isActive);
            indicator.classList.toggle('text-brand-dark', isActive);
            indicator.classList.toggle('text-gray-500', !isActive);
        });
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
