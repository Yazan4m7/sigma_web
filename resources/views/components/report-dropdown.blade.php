@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'selected' => null,
    'allSelected' => null,
    'title' => 'All',
    'multiple' => true,
    'liveSearch' => true,
    'disabled' => false,
    'class' => '',
    'icon' => null,
])

@php
    $inputId = $id ?? preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
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
    $selectedValues = is_array($selected) ? $selected : (is_null($selected) ? [] : [$selected]);

    if ($allSelected === null) {
        $allSelected = empty($selectedValues) || in_array('all', $selectedValues, true);
    }

    $optionsList = is_array($options) ? $options : [];
    $useOptionsList = !empty($optionsList);
    $effectiveSelectedValues = $allSelected ? [] : $selectedValues;
@endphp

@if ($labelText !== '')
    <label for="{{ $inputId }}">
        <i class="{{ $iconClass }}" style="color: rgb(43, 123, 125)"></i> {{ $labelText }}
    </label>
@endif
<select
    class="selectpicker clearOnAll {{ $class }}"
    name="{{ $name }}"
    id="{{ $inputId }}"
    data-container="body"
    data-live-search="{{ $liveSearch ? 'true' : 'false' }}"
    data-hide-disabled="true"
    title="{{ $title }}"
    {{ $multiple ? 'multiple' : '' }}
    {{ $disabled ? 'disabled' : '' }}
>
    <option value="all" {{ $allSelected ? 'selected' : '' }}>All</option>
    @if ($useOptionsList)
        @foreach ($optionsList as $key => $option)
            @php
                if (is_array($option)) {
                    $optValue = $option['value'] ?? $key;
                    $optLabel = $option['label'] ?? ($option['text'] ?? $optValue);
                    $optSelected = $option['selected'] ?? null;
                } else {
                    $optValue = $key;
                    $optLabel = $option;
                    $optSelected = null;
                }
                $isSelected = $optSelected !== null ? $optSelected : in_array($optValue, $effectiveSelectedValues, true);
            @endphp
            <option value="{{ $optValue }}" {{ $isSelected ? 'selected' : '' }}>{{ $optLabel }}</option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
