document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const wrapper = document.querySelector('.wrapper');

    if (sidebar && wrapper) {
        sidebar.addEventListener('mouseenter', function () {
            wrapper.classList.add('sidebar-hover');
        });

        sidebar.addEventListener('mouseleave', function () {
            wrapper.classList.remove('sidebar-hover');
        });
    }
});
