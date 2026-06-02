@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'inputType' => 'radio',
    'hiddenId' => null,
    'class' => '',
    'icon' => null,
])

@php
    $labelText = is_string($label) ? trim($label) : '';
    $labelKey = strtolower(rtrim($labelText, ':'));
    $labelKey = preg_replace('/\s+/', ' ', $labelKey);

    $iconMap = [
        'from' => 'fas fa-calendar-alt',
        'to' => 'fas fa-calendar-alt',
        'from date' => 'fas fa-calendar-alt',
        'to date' => 'fas fa-calendar-alt',
        'doctors' => 'fas fa-user-md',
        'doctor' => 'fas fa-user-md',
        'status types' => 'fas fa-exclamation-circle',
        'status type' => 'fas fa-exclamation-circle',
        'view mode' => 'fas fa-toggle-on',
        'display' => 'fas fa-chart-bar',
        'failure cause' => 'fas fa-exclamation-triangle',
        'type of failure' => 'fas fa-exclamation-circle',
        'job type' => 'fas fa-briefcase',
        'material' => 'fas fa-cube',
        'implants' => 'fas fa-tooth',
        'abutments' => 'fas fa-cog',
    ];

    $iconClass = $icon ?? ($iconMap[$labelKey] ?? 'fas fa-filter');
    $normalizedOptions = is_array($options) ? $options : [];
    $firstOption = $normalizedOptions[0] ?? null;
    $defaultSelected = $firstOption['value'] ?? null;
    $activeValue = $selected !== null ? (string) $selected : (string) $defaultSelected;
    $containerId = $id ?? null;
@endphp

@if ($labelText !== '')
    <label>
        <i class="{{ $iconClass }}" style="color: rgb(43, 123, 125)"></i> {{ $labelText }}
    </label>
@endif
<div class="connected-toggle-container {{ $class }}" @if ($containerId) id="{{ $containerId }}" @endif>
    @if ($inputType === 'hidden')
        <input type="hidden" name="{{ $name }}" id="{{ $hiddenId ?? $name }}" value="{{ $activeValue }}">
    @endif
    @foreach ($normalizedOptions as $index => $option)
        @php
            $optLabel = $option['label'] ?? '';
            $optValue = $option['value'] ?? '';
            $optIdBase = $option['id'] ?? null;
            $buttonId = $option['buttonId'] ?? ($optIdBase ? $optIdBase . '-toggle' : null);
            $inputId = $option['inputId'] ?? ($optIdBase ? $optIdBase . '-radio' : null);
            $isActive = (string) $optValue === $activeValue;
        @endphp
        <button type="button" class="connected-toggle-btn {{ $isActive ? 'active' : '' }}" @if ($buttonId) id="{{ $buttonId }}" @endif>
            @if ($inputType === 'radio')
                <input type="radio" name="{{ $name }}" value="{{ $optValue }}" {{ $isActive ? 'checked' : '' }} style="display: none;" @if ($inputId) id="{{ $inputId }}" @endif>
            @endif
            {{ $optLabel }}
        </button>
    @endforeach
</div>
