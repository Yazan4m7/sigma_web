@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => '',
    'mode' => 'date',
    'required' => false,
    'class' => '',
    'icon' => null,
    'dataDefault' => null,
    'mutedYearDisplay' => false,
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
@endphp

@if ($labelText !== '')
    <label for="{{ $inputId }}">
        <i class="{{ $iconClass }}" style="color: rgb(43, 123, 125)"></i> {{ $labelText }}
    </label>
@endif
<x-ios-dtp
    name="{{ $name }}"
    id="{{ $inputId }}"
    :value="$value"
    mode="{{ $mode }}"
    :required="$required"
    class="{{ $class }}"
    :dataDefault="$dataDefault"
    :mutedYearDisplay="$mutedYearDisplay"
/>
