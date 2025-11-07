<?php

use App\Services\ResponsiveImageService;

if (!function_exists('responsive_image')) {
    /**
     * Generate a responsive image tag.
     *
     * @param string $path
     * @param array $widths
     * @param string $alt
     * @param string $class
     * @param string $sizes
     * @param int $quality
     * @param string $fit
     * @return string
     */
    function responsive_image(string $path, array $widths = [400, 800, 1200], string $alt = '', string $class = '', string $sizes = '(max-width: 768px) 100vw, 50vw', int $quality = 85, string $fit = 'contain'): string
    {
        $imageService = app(ResponsiveImageService::class);

        $src = $imageService->resize($path, max($widths), null, $quality, $fit);
        $srcset = $imageService->generateSrcset($path, $widths, $quality);

        return sprintf(
            '<img src="%s" srcset="%s" sizes="%s" class="%s" alt="%s" loading="lazy">',
            e($src),
            e($srcset),
            e($sizes),
            e($class),
            e($alt)
        );
    }
}
