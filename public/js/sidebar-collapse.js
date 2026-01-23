document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const wrapper = document.querySelector('.wrapper');
    const pinBtn = document.getElementById('sidebar-pin-btn');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburgerBtn = document.getElementById('sidebar-hamburger');
    const MOBILE_BREAKPOINT = 991;

    const isMobile = () => window.innerWidth <= MOBILE_BREAKPOINT;

    function toggleSidebarOverlay(isVisible) {
        if (!overlay) return;
        const showOverlay = isVisible && isMobile();
        overlay.classList.toggle('sidebar-overlay--visible', showOverlay);
        overlay.setAttribute('aria-hidden', showOverlay ? 'false' : 'true');
    }

    function lockBodyScroll(shouldLock) {
        document.body.classList.toggle('no-scroll', shouldLock);
        document.documentElement.classList.toggle('no-scroll', shouldLock);
    }

    const collapseExpandedSubmenus = () => {
        if (!sidebar) return;
        const openSubmenus = sidebar.querySelectorAll('.collapse.show');
        openSubmenus.forEach((submenu) => {
            // Prefer Bootstrap collapse if available to keep states in sync
            if (window.jQuery && typeof jQuery(submenu).collapse === 'function') {
                jQuery(submenu).collapse('hide');
            } else {
                submenu.classList.remove('show');
                submenu.style.height = null;
            }

            // Reset toggle aria-expanded state if we can find it
            if (submenu.id) {
                const toggler = sidebar.querySelector(`[data-target="#${submenu.id}"], [href="#${submenu.id}"]`);
                if (toggler) {
                    toggler.setAttribute('aria-expanded', 'false');
                }
            }
        });
    };

    function closeMobileSidebar() {
        if (!wrapper) return;
        wrapper.classList.remove('sidebar-expanded');
        document.body.classList.remove('sidebar-expanded');
        document.documentElement.classList.remove('nav-open');
        collapseExpandedSubmenus();
        lockBodyScroll(false);
        toggleSidebarOverlay(false);
    }

    function openMobileSidebar() {
        if (!wrapper) return;
        wrapper.classList.add('sidebar-expanded');
        document.body.classList.add('sidebar-expanded');
        document.documentElement.classList.add('nav-open');
        lockBodyScroll(true);
        toggleSidebarOverlay(true);
    }

    // Check if sidebar should be pinned from localStorage
    let userPinnedPreference = localStorage.getItem('sidebarPinned') === 'true';

    function applyPinnedState() {
        if (!wrapper) return;
        if (!isMobile() && userPinnedPreference) {
            wrapper.classList.add('sidebar-pinned');
            if (pinBtn) {
                pinBtn.classList.add('pinned');
            }
        } else {
            wrapper.classList.remove('sidebar-pinned');
            if (pinBtn) {
                pinBtn.classList.remove('pinned');
            }
        }
    }

    function syncSidebarForViewport() {
        if (isMobile()) {
            // Force overlay mode on mobile even if user pinned on desktop
            wrapper?.classList.remove('sidebar-pinned');
            pinBtn?.classList.remove('pinned');
            if (!wrapper?.classList.contains('sidebar-expanded')) {
                lockBodyScroll(false);
                toggleSidebarOverlay(false);
                document.documentElement.classList.remove('nav-open');
            }
        } else {
            closeMobileSidebar();
            applyPinnedState();
        }
    }

    if (sidebar && wrapper) {
        // Apply pinned state on load (desktop only)
        applyPinnedState();

        // If not pinned, start with all submenus closed so navigation to a new page doesn't re-open them
        if (!wrapper.classList.contains('sidebar-pinned')) {
            collapseExpandedSubmenus();
        }

        // Hover functionality (only when not pinned)
        sidebar.addEventListener('mouseenter', function () {
            if (isMobile()) return;
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.add('sidebar-hover');
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if (isMobile()) return;
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.remove('sidebar-hover');
                collapseExpandedSubmenus();
            }
        });

        // Close submenus and the mobile drawer when a non-toggle link is clicked
        sidebar.addEventListener('click', function (e) {
            if (wrapper.classList.contains('sidebar-pinned') && !isMobile()) return;
            const link = e.target.closest('.nav li a');
            if (!link) return;

            const isCollapseToggle = link.hasAttribute('data-toggle') && (link.getAttribute('data-target') || link.getAttribute('href'));

            if (isCollapseToggle) {
                // Let Bootstrap handle the collapse toggle naturally
                // Just don't close the sidebar when clicking collapse toggles
                return;
            } else if (isMobile()) {
                // Only close mobile sidebar if a non-toggle link is clicked
                closeMobileSidebar();
            }
        });
    }

    // Pin button click handler
    if (pinBtn) {
        pinBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            userPinnedPreference = !userPinnedPreference;
            localStorage.setItem('sidebarPinned', userPinnedPreference);
            applyPinnedState();
            if (!userPinnedPreference) {
                collapseExpandedSubmenus();
            }
            toggleSidebarOverlay(false);
            lockBodyScroll(false);
        });
    }

    // Hamburger mobile open/close overlay logic
    if (hamburgerBtn && wrapper) {
        hamburgerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!isMobile()) return;
            const expanded = wrapper.classList.contains('sidebar-expanded');
            if (expanded) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        });
    }

    // Overlay tap closes sidebar on mobile
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            e.preventDefault();
            closeMobileSidebar();
        });
    }

    window.addEventListener('resize', syncSidebarForViewport);
    syncSidebarForViewport();
});
