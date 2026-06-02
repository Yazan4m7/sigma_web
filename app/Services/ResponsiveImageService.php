<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ResponsiveImageService
{
    /**
     * Generate a resized image and return its path
     *
     * @param string $path Original image path relative to storage/app/public
     * @param int $width Target width
     * @param int|null $height Target height (null to maintain aspect ratio)
     * @param int $quality Image quality (1-100)
     * @param string|null $fit Fit mode: contain, cover, fill
     * @return string Public URL to resized image
     */
    public function resize(string $path, int $width, ?int $height = null, int $quality = 85, ?string $fit = 'contain'): string
    {
        // Normalize path
        $path = ltrim($path, '/');

        // Check if original image exists
        $originalPath = storage_path('app/public/' . $path);
        if (!file_exists($originalPath)) {
            return $this->getPlaceholderUrl($width, $height);
        }

        // Generate cache key and path
        $cacheKey = $this->getCacheKey($path, $width, $height, $quality, $fit);
        $resizedDir = storage_path('app/public/resized');
        $resizedPath = $resizedDir . '/' . $cacheKey . '.' . pathinfo($path, PATHINFO_EXTENSION);

        // Create resized directory if it doesn't exist
        if (!File::exists($resizedDir)) {
            File::makeDirectory($resizedDir, 0755, true);
        }

        // Return cached version if exists and is newer than original
        if (file_exists($resizedPath) && filemtime($resizedPath) >= filemtime($originalPath)) {
            return asset('storage/resized/' . basename($resizedPath));
        }

        try {
            // Load and resize image
            $image = Image::read($originalPath);

            // Apply fit mode
            if ($height) {
                switch ($fit) {
                    case 'cover':
                        $image->cover($width, $height);
                        break;
                    case 'fill':
                        $image->resize($width, $height);
                        break;
                    case 'contain':
                    default:
                        $image->scale($width, $height);
                        break;
                }
            } else {
                $image->scale($width);
            }

            // Encode and save
            $encoded = $image->encodeByExtension(quality: $quality);
            file_put_contents($resizedPath, $encoded);

            return asset('storage/resized/' . basename($resizedPath));
        } catch (\Exception $e) {
            \Log::error('Image resize failed: ' . $e->getMessage(), [
                'path' => $path,
                'width' => $width,
                'height' => $height
            ]);
            return $this->getPlaceholderUrl($width, $height);
        }
    }

    /**
     * Generate srcset string for responsive images
     *
     * @param string $path Image path
     * @param array $widths Array of widths
     * @param int $quality Image quality
     * @return string srcset attribute value
     */
    public function generateSrcset(string $path, array $widths, int $quality = 85): string
    {
        $srcset = [];
        foreach ($widths as $width) {
            $url = $this->resize($path, $width, null, $quality);
            $srcset[] = $url . ' ' . $width . 'w';
        }
        return implode(', ', $srcset);
    }

    /**
     * Generate cache key for resized image
     *
     * @param string $path
     * @param int $width
     * @param int|null $height
     * @param int $quality
     * @param string|null $fit
     * @return string
     */
    protected function getCacheKey(string $path, int $width, ?int $height, int $quality, ?string $fit): string
    {
        $parts = [
            md5($path),
            'w' . $width,
            $height ? 'h' . $height : '',
            'q' . $quality,
            $fit ?: 'contain'
        ];
        return implode('_', array_filter($parts));
    }

    /**
     * Get placeholder image URL
     *
     * @param int $width
     * @param int|null $height
     * @return string
     */
    protected function getPlaceholderUrl(int $width, ?int $height = null): string
    {
        $h = $height ?: $width;
        // Generate SVG placeholder
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $width . '" height="' . $h . '" xmlns="http://www.w3.org/2000/svg">
    <rect width="100%" height="100%" fill="#e0e0e0"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="16" fill="#999" text-anchor="middle" dy=".3em">
        Image Not Found
    </text>
</svg>';
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Clear all cached/resized images
     *
     * @return bool
     */
    public function clearCache(): bool
    {
        $resizedDir = storage_path('app/public/resized');
        if (File::exists($resizedDir)) {
            File::deleteDirectory($resizedDir);
            File::makeDirectory($resizedDir, 0755, true);
            return true;
        }
        return false;
    }
}
