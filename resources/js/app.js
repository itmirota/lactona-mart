

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('admin-sidebar');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

    if (!sidebar || !sidebarToggle || !sidebarOverlay) {
        return;
    }

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
});
