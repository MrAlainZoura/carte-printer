document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');

    if (!sidebar) {
        return;
    }

    const openButtons = document.querySelectorAll('[data-drawer-target="sidebar"][data-drawer-show="sidebar"]');
    const closeButtons = document.querySelectorAll('[data-drawer-hide="sidebar"]');
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 z-30 hidden bg-gray-900/50 md:hidden';
    overlay.setAttribute('aria-hidden', 'true');
    document.body.append(overlay);

    const closeSidebar = () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        openButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));
    };

    openButtons.forEach((button) => {
        button.setAttribute('aria-expanded', 'false');
        button.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            button.setAttribute('aria-expanded', 'true');
        });
    });

    closeButtons.forEach((button) => button.addEventListener('click', closeSidebar));
    overlay.addEventListener('click', closeSidebar);
    sidebar.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeSidebar));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeSidebar();
        }
    });
    window.addEventListener('resize', () => {
        if (window.matchMedia('(min-width: 768px)').matches) {
            closeSidebar();
        }
    });
});
