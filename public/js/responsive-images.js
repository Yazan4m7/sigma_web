document.addEventListener("DOMContentLoaded", function() {
    const lazyImages = document.querySelectorAll('.lazy-image');

    const imageObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                const lazyImage = entry.target;
                lazyImage.src = lazyImage.dataset.src;
                lazyImage.srcset = lazyImage.dataset.srcset;
                lazyImage.classList.add('loaded');
                lazyImage.removeAttribute('data-src');
                lazyImage.removeAttribute('data-srcset');
                observer.unobserve(lazyImage);
            }
        });
    });

    lazyImages.forEach(function(lazyImage) {
        imageObserver.observe(lazyImage);
    });
});
