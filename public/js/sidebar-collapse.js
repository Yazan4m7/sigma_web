document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const wrapper = document.querySelector('.wrapper');
    const pinBtn = document.getElementById('sidebar-pin-btn');
    const overlay = document.getElementById('sidebarOverlay');
    const hamburgerBtn = document.getElementById('sidebar-hamburger');
    const MOBILE_BREAKPOINT = 991;
    const getDisableExpandPreference = () => localStorage.getItem('sigma_pref_disable_sidebar_expand') === 'true';

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
        const openSubmenus = sidebar.querySelectorAll('.sigma-submenu-open');
        openSubmenus.forEach((submenu) => {
            submenu.classList.remove('sigma-submenu-open');
            submenu.style.height = '0';

            // Reset toggle aria-expanded state
            if (submenu.id) {
                const toggler = sidebar.querySelector(`[data-target="#${submenu.id}"]`);
                if (toggler) {
                    toggler.setAttribute('aria-expanded', 'false');
                }
            }
        });
    };

    // Custom submenu toggle handler
    function toggleSubmenu(trigger, targetId) {
        const target = document.querySelector(targetId);
        if (!target) return;

        const isOpen = target.classList.contains('sigma-submenu-open');

        if (isOpen) {
            // Close
            target.style.height = target.scrollHeight + 'px';
            setTimeout(() => {
                target.style.height = '0';
            }, 10);
            target.classList.remove('sigma-submenu-open');
            trigger.setAttribute('aria-expanded', 'false');
        } else {
            // Open
            target.classList.add('sigma-submenu-open');
            target.style.height = target.scrollHeight + 'px';
            trigger.setAttribute('aria-expanded', 'true');

            // Remove height after transition completes
            setTimeout(() => {
                if (target.classList.contains('sigma-submenu-open')) {
                    target.style.height = 'auto';
                }
            }, 300);
        }
    }

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
        if (getDisableExpandPreference()) {
            wrapper.classList.remove('sidebar-pinned', 'sidebar-hover', 'sidebar-expanded');
            if (pinBtn) {
                pinBtn.classList.remove('pinned');
            }
            collapseExpandedSubmenus();
            toggleSidebarOverlay(false);
            lockBodyScroll(false);
            return;
        }
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
            // Always ensure sidebar is closed on mobile page load
            wrapper?.classList.remove('sidebar-expanded');
            document.body.classList.remove('sidebar-expanded');
            document.documentElement.classList.remove('nav-open');
            collapseExpandedSubmenus();
            lockBodyScroll(false);
            toggleSidebarOverlay(false);
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
            if (getDisableExpandPreference()) return;
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.add('sidebar-hover');
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if (isMobile()) return;
            if (getDisableExpandPreference()) return;
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.remove('sidebar-hover');
                collapseExpandedSubmenus();
            }
        });

        // Handle submenu toggle and mobile drawer
        sidebar.addEventListener('click', function (e) {
            // Fix for iOS: use closest('a') instead of closest('.nav li a')
            const link = e.target.closest('a');
            if (!link) return;

            // Additional check to ensure we're within .nav li
            if (!link.closest('.nav li')) return;

            const isSubmenuToggle = link.hasAttribute('data-sigma-toggle');

            if (isSubmenuToggle) {
                if (getDisableExpandPreference()) {
                    e.preventDefault();
                    e.stopPropagation();
                    const targetId = link.getAttribute('data-target');
                    if (targetId) {
                        const firstChildLink = document.querySelector(targetId + ' a[href]:not([href="#"])');
                        if (firstChildLink) {
                            window.location.href = firstChildLink.getAttribute('href');
                        }
                    }
                    return;
                }
                e.preventDefault();
                e.stopPropagation(); // Prevent event from bubbling on iOS
                const targetId = link.getAttribute('data-target');
                if (targetId) {
                    toggleSubmenu(link, targetId);
                }
                return;
            } else if (isMobile() && !wrapper.classList.contains('sidebar-pinned')) {
                // Only close mobile sidebar if a non-toggle link is clicked
                closeMobileSidebar();
            }
        });
    }

    // Pin button click handler
    if (pinBtn) {
        pinBtn.addEventListener('click', function (e) {
            if (getDisableExpandPreference()) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
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
    window.addEventListener('storage', function (event) {
        if (event.key === 'sigma_pref_disable_sidebar_expand') {
            syncSidebarForViewport();
        }
    });
    document.addEventListener('sigma:sidebar-pref-changed', syncSidebarForViewport);
    syncSidebarForViewport();
});
