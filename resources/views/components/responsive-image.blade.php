@props([
    'path',
    'alt' => '',
    'widths' => [400, 800, 1200],
    'sizes' => '(max-width: 768px) 100vw, 50vw',
    'class' => '',
    'quality' => 85,
    'fit' => 'contain'
])

@php
    $imageService = app(App\Services\ResponsiveImageService::class);
    $src = $imageService->resize($path, max($widths), null, $quality, $fit);
    $srcset = $imageService->generateSrcset($path, $widths, $quality);
@endphp

<img
    src="{{ $src }}"
    srcset="{{ $srcset }}"
    sizes="{{ $sizes }}"
    class="lazy-image {{ $class }}"
    alt="{{ $alt }}"
    loading="lazy"
>
