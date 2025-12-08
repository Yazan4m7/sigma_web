@props([
    'id' => null,
    'title' => '',
    'meta' => null,
    'partial' => false,
    'wrapperClass' => 'mfp-hide dialog-popup-content',
    'cardClass' => 'dialog-popup-card',
    'closeAttrs' => 'aria-label="Close"',
])

@php
    $card = function () use ($title, $slot, $footer, $meta, $cardClass, $closeAttrs) {
        return <<<'HTML'
        HTML;
    };
@endphp

@if($partial)
    <div class="{{ $cardClass }}">
        <div class="dialog-popup-header">
            <h5 class="modal-title mb-0">{{ $title }}</h5>
            <button type="button" class="dialog-popup-close mfp-close" {!! $closeAttrs !!}>&times;</button>
        </div>
        <div class="dialog-popup-body">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="dialog-popup-footer">
                {{ $footer }}
            </div>
        @endisset
        @if($meta)
            <span class="dialog-popup-meta">{{ $meta }}</span>
        @endif
    </div>
@else
    <div id="{{ $id }}" class="{{ $wrapperClass }}">
        <div class="{{ $cardClass }}">
            <div class="dialog-popup-header">
                <h5 class="modal-title mb-0">{{ $title }}</h5>
                <button type="button" class="dialog-popup-close mfp-close" {!! $closeAttrs !!}>&times;</button>
            </div>
            <div class="dialog-popup-body">
                {{ $slot }}
            </div>
            @isset($footer)
                <div class="dialog-popup-footer">
                    {{ $footer }}
                </div>
            @endisset
            @if($meta)
                <span class="dialog-popup-meta">{{ $meta }}</span>
            @endif
        </div>
    </div>
@endif
