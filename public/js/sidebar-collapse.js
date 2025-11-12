document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const wrapper = document.querySelector('.wrapper');
    const pinBtn = document.getElementById('sidebar-pin-btn');

    // Check if sidebar should be pinned from localStorage
    const isPinned = localStorage.getItem('sidebarPinned') === 'true';

    if (sidebar && wrapper) {
        // Apply pinned state on load
        if (isPinned) {
            wrapper.classList.add('sidebar-pinned');
            if (pinBtn) {
                pinBtn.classList.add('pinned');
            }
        }

        // Pin button click handler
        if (pinBtn) {
            console.log('Pin button found, attaching event listener');
            pinBtn.addEventListener('click', function (e) {
                console.log('Pin button clicked!');
                e.preventDefault();
                e.stopPropagation();

                const isPinnedNow = wrapper.classList.toggle('sidebar-pinned');
                this.classList.toggle('pinned');

                console.log('Sidebar pinned:', isPinnedNow);
                // Save to localStorage
                localStorage.setItem('sidebarPinned', isPinnedNow);
            });
        } else {
            console.log('Pin button NOT found');
        }

        // Hover functionality (only when not pinned)
        sidebar.addEventListener('mouseenter', function () {
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.add('sidebar-hover');
            }
        });

        sidebar.addEventListener('mouseleave', function () {
            if (!wrapper.classList.contains('sidebar-pinned')) {
                wrapper.classList.remove('sidebar-hover');
            }
        });
    }

    // Only update overlay when explicit pin/hamburger opening
    function updateSidebarOverlayExplicit() {
        const overlay = document.getElementById('sidebarOverlay');
        const isMobile = window.innerWidth < 990;
        const isSidebarOpen = wrapper.classList.contains('sidebar-pinned') || wrapper.classList.contains('sidebar-expanded');
        if (overlay && isMobile) {
            if (isSidebarOpen) {
                document.body.classList.add('sidebar-expanded');
                wrapper.classList.add('sidebar-expanded');
                overlay.style.display = 'block';
            } else {
                document.body.classList.remove('sidebar-expanded');
                wrapper.classList.remove('sidebar-expanded');
                overlay.style.display = 'none';
            }
        } else if (overlay) {
            overlay.style.display = 'none';
            document.body.classList.remove('sidebar-expanded');
            wrapper.classList.remove('sidebar-expanded');
        }
    }

    // Explicit sidebar open (hamburger or pin),
    // Remove overlay updates from hover events and nav link clicks.
    if (pinBtn) {
        pinBtn.addEventListener('click', function () {
            // Toggle "sidebar-pinned" state
            const isPinnedNow = wrapper.classList.toggle('sidebar-pinned');
            this.classList.toggle('pinned');
            localStorage.setItem('sidebarPinned', isPinnedNow);
            updateSidebarOverlayExplicit();
        });
    }
    // TODO: If you have a hamburger or mobile sidebar open button, add click event for that here:
    // document.getElementById('sidebar-hamburger').addEventListener('click', ...)
    // Call updateSidebarOverlayExplicit() when opened or closed
    window.addEventListener('resize', updateSidebarOverlayExplicit);
    // Initial call
    updateSidebarOverlayExplicit();

    // Hamburger mobile open/close overlay logic
    var hamburgerBtn = document.getElementById('sidebar-hamburger');
    if (hamburgerBtn && wrapper) {
        hamburgerBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.innerWidth < 990) {
                var expanded = wrapper.classList.toggle('sidebar-expanded');
                if(expanded) {
                    document.body.classList.add('sidebar-expanded');
                    if (overlay) overlay.style.display = 'block';
                } else {
                    document.body.classList.remove('sidebar-expanded');
                    if (overlay) overlay.style.display = 'none';
                }
            }
        });
    }

    // Overlay click closes sidebar on mobile
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay) {
        overlay.addEventListener('click', function () {
            const sidebarEl = document.querySelector('.sidebar');
            if (sidebarEl) {
                sidebarEl.classList.add('sidebar-sliding-out');
            }
            overlay.style.display = 'none';
            setTimeout(function () {
                wrapper.classList.remove('sidebar-expanded');
                document.body.classList.remove('sidebar-expanded');
                if (sidebarEl) {
                    sidebarEl.classList.remove('sidebar-sliding-out');
                }
            }, 340); // Match the CSS transition duration
        });
    }
});