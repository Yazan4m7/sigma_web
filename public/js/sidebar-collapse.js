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
});