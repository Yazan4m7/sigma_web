@extends('layouts.app', ['pageSlug' => 'Master Report'])

@push('css')
    <link href="{{ asset('assets/css/sigma-reports-master.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-master.css')) }}" rel="stylesheet">
    <link href="{{ asset('assets/css/sigma-reports-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-reports-theme.css')) }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/4.0.2/css/fixedColumns.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    @php
        $permissions = Cache::get('user' . Auth()->user()->id);
    @endphp
    <style>

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important;
            border: unset;
            background-color:unset;
            /* background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111)); */
            /* background: -webkit-linear-gradient(top, #585858 0%, #111 100%); */

            /* background: linear-gradient(to bottom, #585858 0%, #111 100%); */
        }
        .master-report-container {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;

            padding: 24px;
        }

        .modern-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }

        .card-header {

            border: none;
            padding: 24px;
            border-radius: 12px 12px 0 0;
            color: grey;
        }

        .card-title {
            font-size: 24px;
            font-weight: 600;
            color: white;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-section {
            padding: 12px;
            margin-bottom: 0;
        }

        .basic-filters {
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e2e8f0;
        }

        .section-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 6px;
            padding-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
        }

        .form-group {
            margin-bottom: 8px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 4px;
            color: #374151;
            display: block;
        }

        .modern-input,
        .modern-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
            background-color: white;
            min-height: 36px;
        }

        .modern-input:focus,
        .modern-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modern-btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            border: 1px solid #667eea;
        }

        .btn-primary:hover {
            background: #5a67d8;
            border-color: #5a67d8;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        button[type="submit"].modern-btn:hover {
            background: linear-gradient(135deg, #2d5f61 0%, #1f4547 100%) !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.4) !important;
            transform: translateY(-2px);
        }

        /* Modern 3-Way Toggle Styles */
        .modern-toggle-container {
            margin-top: 4px;
        }

        .modern-toggle-btn {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            height: 44px;
            background: #f1f5f9;
            background-image: none;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 4px;
            cursor: pointer;
            overflow: hidden;
            transition: border-color 0.2s ease;
        }

        .modern-toggle-btn:hover {
            border-color: #408385;
        }

        .toggle-option {
            position: relative;
            flex: 1;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            color: #64748b;
            transition: color 0.3s ease;
            z-index: 2;
            padding: 8px 4px;
            cursor: pointer;
        }

        .toggle-option.active {
            color: white;
        }

        .toggle-slider {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(33.333% - 4px);
            height: calc(100% - 8px);
            background: linear-gradient(135deg, #1c7c54 0%, #2f9e73 100%);
            border-radius: 6px;
            transition: transform 0.25s ease;
            box-shadow: none;
            z-index: 1;
        }

        .modern-toggle-btn[data-value="all"] .toggle-slider {
            transform: translateX(0);
        }

        .modern-toggle-btn[data-value="completed"] .toggle-slider {
            transform: translateX(calc(100% + 4px));
        }

        .modern-toggle-btn[data-value="in_progress"] .toggle-slider {
            transform: translateX(calc(200% + 8px));
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
            border: 1px solid #6b7280;
        }

        .btn-secondary:hover {
            background: #4b5563;
            border-color: #4b5563;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
        }

        .btn-outline:hover {
            border-color: #667eea;
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }

        .advanced-filters {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            border: 1px solid #e5e7eb;
        }

        .filter-button {
            width: 100%;
            text-align: left;
            justify-content: space-between;
            background: white;
            border: 1px solid #d1d5db;
            padding: 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #374151;
            font-weight: 500;
        }

        .filter-button:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
            transform: translateY(-1px);
        }
        .filter-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            background: #e5e7eb;
            color: #374151;
        }
        .filter-pill.active {
            background: #d1fae5;
            color: #065f46;
        }
        .filter-pill.muted {
            background: #f3f4f6;
            color: #6b7280;
        }
        .columns-dropdown .dropdown-menu {
            transform: none !important;
            transition: none !important;
            right: 0 !important;
            left: auto !important;
        }
        .flatpickr-wrapper {
            position: relative;
            display: initial !important;
        }
        .content{
            /*background-color: transparent;*/
            border: none;
        }

        .filter-summary {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
            font-weight: 400;


        }
        table.dataTable thead th, table.dataTable thead td {
            padding: 5px 5px;
            border-bottom: 1px solid #111;
        }

        .modern-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table {
            margin: 0;
            font-size: 14px;
        }

        .table th {
            /* background: #f8fafc; */ /* Kept as commented out */
            font-weight: 600;
            color: #374151;
            border: none;
            padding: 16px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e2e8f0;
        }

        .table td {
            padding: 16px;
            border-color: #f1f5f9;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {

            color: white;
        }


        .badge-warning {

            color: white;
        }

        .badge-primary {

            color: white;
        }

        /* Select2 Customization */
        .select2-container .select2-selection--single {
            height: 36px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 34px;
            padding-left: 12px;
            color: #374151;
            font-size: 13px;
        }

        .select2-container .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            min-height: 36px;
        }

        .select2-container--focus .select2-selection {
            border-color: #667eea !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        }


/* Master Report modern layout overrides */
:root {
    --ink: #0c1a1f;
    --muted: #6b7b86;
    --surface: #f7f9fb;
    --panel: #ffffff;
    --accent: #1c7c54;
    --accent-strong: #134f35;
    --stroke: #e3e8ee;
    --pill: #eef3f6;
    --shadow-lg: 0 10px 30px rgba(12, 26, 31, 0.08);
}

.master-report-container {
    background: var(--surface);
    padding: 28px;
}

.modern-card {
    border-radius: 18px;
    border: 1px solid var(--stroke);
    box-shadow: var(--shadow-lg);
}

.form-section {
    padding: 0;
}

.basic-filters {
    background: transparent;
    border: none;
}

.section-title {
    border-bottom: none;
    padding-bottom: 0;
}

.form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
}

.modern-input,
.modern-select {
    border: 1px solid var(--stroke);
    border-radius: 10px;
    min-height: 42px;
    padding: 10px 12px;
    background-color: #fff;
}

.modern-input:focus,
.modern-select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(28, 124, 84, 0.15);
}

.modern-btn {
    border-radius: 12px;
    font-weight: 600;
}

.btn-primary {
    background: var(--accent);
    color: #fff;
    border: 1px solid var(--accent);
    box-shadow: var(--shadow-lg);
}

.btn-primary:hover {
    background: var(--accent-strong);
    border-color: var(--accent-strong);
    transform: translateY(-1px);
    box-shadow: 0 12px 26px rgba(19, 79, 53, 0.3);
}

button[type="submit"].modern-btn:hover {
    background: var(--accent-strong) !important;
    box-shadow: 0 12px 26px rgba(19, 79, 53, 0.3) !important;
}

.report-hero {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 18px;
    background: linear-gradient(135deg, #f0f7f3 0%, #e8f1ff 100%);
    border-bottom: 1px solid var(--stroke);
}

.hero-copy .eyebrow {
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--muted);
    font-size: 0.75rem;
    margin: 0;
}

.hero-title {
    margin: 4px 0 2px;
    color: var(--ink);
    font-size: 1.6rem;
}

.hero-subtitle {
    margin: 0;
    color: var(--muted);
    font-size: 0.95rem;
}

.hero-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filters-card {
    overflow: hidden;
}

.filters-surface {
    padding: 18px;
    background: var(--panel);
}

.filters-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 12px;
}

.filters-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filters-icon {
    width: 38px;
    height: 38px;
    border-radius: 12px;
    background: var(--pill);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--accent-strong);
}

.filters-header h2 {
    margin: 0;
    color: var(--ink);
    font-size: 1.1rem;
}

.filters-hint {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 0.92rem;
}

.filters-header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.filters-action-btn {
    border: 1px solid var(--stroke);
    background: #fff;
    color: var(--ink);
    padding: 6px 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 12px;
}

.filters-action-btn i {
    margin-right: 6px;
}

.filters-master-reset {
    border: none;
    background: none;
    color: var(--accent-strong);
    font-weight: 600;
    font-size: 12px;
    padding: 6px 8px;
}

.filters-master-reset:hover {
    text-decoration: underline;
}

.filter-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.filter-reset-btn {
    border: none;
    background: none;
    color: var(--muted);
    font-size: 11px;
    font-weight: 600;
    padding: 0;
}

.filter-reset-btn:hover {
    color: var(--accent-strong);
    text-decoration: underline;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 14px;
}

.filter-group {
    margin-bottom: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group.span-2 {
    grid-column: span 2;
}

@media (max-width: 900px) {
    .filter-group.span-2 {
        grid-column: span 1;
    }
}

.range-pair {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 8px;
}

.range-error {
    display: none;
    font-size: 12px;
    margin-top: 2px;
}

.trigger-field {
    width: 100%;
    text-align: left;
    background: #fff;
    border: 1px solid var(--stroke);
    color: var(--ink);
    height: 42px;
    padding: 10px 12px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    cursor: pointer;
}

.trigger-field:hover {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(28, 124, 84, 0.08);
}

.trigger-summary {
    font-size: 12px;
    margin-top: 2px;
}

.filters-footer {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 12px;
    margin-top: 16px;
}

.filters-summary-card {
    display: flex;
    justify-content: flex-start;
}

.filters-summary-row {
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-start;
    gap: 8px;
}

.generate-btn i {
    margin-right: 6px;
}

.generate-btn {
    background: linear-gradient(135deg, #2f7c7e 0%, #bdcfd9 100%) !important;
    border-color: #2f7c7e !important;
    color: #ffffff !important;
    box-shadow: 0 6px 16px rgba(47, 124, 126, 0.35) !important;
}

.generate-btn:hover {
    background: linear-gradient(135deg, #266b6d 0%, #a9c3d0 100%) !important;
    border-color: #266b6d !important;
    box-shadow: 0 10px 24px rgba(38, 107, 109, 0.35) !important;
}

@media (max-width: 900px) {
    .hero-actions {
        display: none;
    }

    .filters-footer {
        display: flex;
    }
}

.modern-toggle-btn {
    background: #f1f5f9;
    background-image: none;
    border: 1px solid var(--stroke);
    height: 46px;
    overflow: hidden;
}

.modern-toggle-btn:hover {
    border-color: var(--accent);
}

.toggle-option {
    color: var(--muted);
}

.toggle-option.active {
    color: #fff;
}

.toggle-slider {
    background: linear-gradient(135deg, #1c7c54 0%, #2f9e73 100%);
    box-shadow: none;
}


        /* DataTable Export Buttons */
        .dt-buttons .btn {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            color: #374151 !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            border-radius: 6px !important;
            margin-right: 8px !important;
            transition: all 0.2s ease !important;
        }

        .dt-buttons .btn:hover {
            border-color: #667eea !important;
            color: #667eea !important;
            background: rgba(102, 126, 234, 0.05) !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        /* === MASTER REPORT TABLE STYLES === */

        /* Master Report Table Header Styles - Match other reports sizing */
        #master-report-table thead th {
            font-weight: 600;
            font-size: 13px;
            padding: 10px 12px;
            vertical-align: middle;
            white-space: nowrap;
            min-height: 40px;
            position: relative; /* Default positioning for headers */
        }

        /* Body cells - normal size */
        #master-report-table tbody td {
            font-size: 12.5px;
            padding: 10px 12px;
            vertical-align: middle;
            min-height: 38px;
            white-space: nowrap; /* ADDED: Ensure cells don't wrap */
        }

        /* Dark headers */
        #master-report-table thead th.header-dark,
        table#master-report-table thead th.header-dark,
        .sigma-report-table-container .dataTables_scrollHead th.header-dark {
            background-color: #408385 !important;
            background: #408385 !important;
            color: white !important;
            border: none !important;
        }

        /* Light headers */
        #master-report-table thead th.header-light,
        table#master-report-table thead th.header-light,
        .sigma-report-table-container .dataTables_scrollHead th.header-light {
            background-color: transparent !important;
            background: none !important;
            color: #408385 !important;
            border: none !important;
            border-bottom: 2px solid #408385 !important;
            font-weight: 600;
        }

        .header-light {
            font-family: calibari sans-serif !important;
            color:  #408385 !important;
        }

        #master-report-table thead,
        .sigma-report-table-container .dataTables_scrollHead {
            display: none;
        }

        .master-report-header-row {
            cursor: default;
            background-color: #f8fafc !important;
        }

        .master-report-header-row td {
            font-weight: 600;
            font-size: 12.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            background-color: #f8fafc !important;
            color: #408385 !important;
            border-bottom: 1px solid #e2e8f0;
        }

        .master-report-header-row td.header-dark {
            background-color: #408385 !important;
            color: #ffffff !important;
        }

        /* Table container - enable horizontal scroll */
.sigma-report-table-container {
    width: 100%;
    overflow-x: auto;
    overflow-y: visible;
    position: relative;
    max-width: 100%;
    padding: 12px 20px 20px;
    box-sizing: border-box;
}

        /* Ensure table doesn't inherit transforms that break layout */
        #master-report-table {
            position: relative;
            border-collapse: separate;
            border-spacing: 0;
            width: max-content !important;
            min-width: 100%;
        }

        /* Zebra striping for table rows */
        #master-report-table tbody tr:nth-child(even) {
            background-color: #f8fafb !important;
        }

        #master-report-table tbody tr:nth-child(odd) {
            background-color: #ffffff !important;
        }

        /* Ensure DataTables doesn't interfere with our layout */
#master-report-table_wrapper {
    width: 100% !important;
    max-width: 100% !important;
    overflow: visible !important;
}

        .sigma-report-table-container .dataTables_filter,
        .sigma-report-table-container .dataTables_length {
            padding: 6px 4px 10px;
        }

        .sigma-report-table-container .dataTables_filter {
            text-align: right;
        }

        .sigma-report-table-container .dataTables_scroll,
        .sigma-report-table-container .dataTables_scrollHead,
        .sigma-report-table-container .dataTables_scrollBody {
            width: 100% !important;
        }

        .sigma-report-table-container .dataTables_scrollBody {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        .columns-dropdown {
            margin-right: 12px;
        }

        .master-report-row {
            cursor: pointer;
        }

        .report-date-line {
            font-weight: 600;
            line-height: 1.2;
        }

        .report-time-line {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.2;
        }

        .sigma-case-status-badge {
            width: 7.5em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 0 auto;
            line-height: 1 !important;
            padding: 0.3rem 0.45rem !important;
            vertical-align: middle;
        }

        .sigma-case-status-badge.badge-primary {
            color: #ffffff;
        }

        .sigma-case-status-badge .tooltipX {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            line-height: 1;
        }

        .sigma-case-status-badge .sigma-badge-label {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .master-report-container {
                padding: 16px;
            }

            .form-section {
                padding: 16px;
            }

            .card-header {
                padding: 20px;
            }

            #master-report-table {
                min-width: 1200px;
            }
        }
    </style>

    
<div class="master-report-container">
    <div class="modern-card filters-card">
        <form class="modern-form" method="GET" action="{{route('master-report')}}" id="master-report-form">
            <input type="hidden" name="generate_report" value="1">
            <div id="hidden-employee-filters"></div>
            <div id="hidden-device-filters"></div>
            <script>
                window.initialMaterialTypes = @json(request('material_type', []));
                window.initialEmployeeFilters = @json(array_values((array) request('employee_filters', [])));
                window.initialDeviceFilters = @json(array_values((array) request('device_filters', [])));
            </script>

            <div class="form-section basic-filters filters-surface">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="form-label filter-label" for="master_from">
                            <span><i class="fas fa-calendar-alt"></i> From</span>
                            <button type="button" class="filter-reset-btn" data-reset="date-from">Reset</button>
                        </label>
                        <x-ios-dtp
                            name="from"
                            id="master_from"
                            :value="request('from', $from)"
                            mode="date"
                            :required="true"
                        />
                    </div>
                    <div class="filter-group">
                        <label class="form-label filter-label" for="master_to">
                            <span><i class="fas fa-calendar-alt"></i> To</span>
                            <button type="button" class="filter-reset-btn" data-reset="date-to">Reset</button>
                        </label>
                        <x-ios-dtp
                            name="to"
                            id="master_to"
                            :value="request('to', $to)"
                            mode="date"
                            :required="true"
                        />
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-user-md"></i> Doctor</span>
                            <button type="button" class="filter-reset-btn" data-reset="doctor">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="doctor[]" id="doctor">
                            <option value="all" {{in_array('all', (array)request('doctor', ['all'])) ? 'selected' : ''}}>All Doctors</option>
                            @foreach($clients as $client)
                                <option value="{{$client->id}}" {{in_array($client->id, (array)request('doctor', [])) ? 'selected' : ''}}>
                                    {{$client->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-tooth"></i> Material</span>
                            <button type="button" class="filter-reset-btn" data-reset="material">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="material[]" id="material">
                            <option value="all" {{in_array('all', (array)request('material', ['all'])) ? 'selected' : ''}}>All Materials</option>
                            @foreach($materials as $material)
                                <option value="{{$material->id}}" {{in_array($material->id, (array)request('material', [])) ? 'selected' : ''}}>
                                    {{$material->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-cog"></i> Job Type</span>
                            <button type="button" class="filter-reset-btn" data-reset="job-type">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="job_type[]" id="job_type">
                            <option value="all" {{in_array('all', (array)request('job_type', ['all'])) ? 'selected' : ''}}>All Job Types</option>
                            @foreach($jobTypes as $jobType)
                                <option value="{{$jobType->id}}" {{in_array($jobType->id, (array)request('job_type', [])) ? 'selected' : ''}}>
                                    {{$jobType->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-layer-group"></i> Material Type</span>
                            <button type="button" class="filter-reset-btn" data-reset="material-type">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="material_type[]" id="material_type">
                            <option value="all">All Material Types</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-exclamation-triangle"></i> Failure Type</span>
                            <button type="button" class="filter-reset-btn" data-reset="failure-type">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="failure_type[]" id="failure_type">
                            <option value="all" {{in_array('all', (array)request('failure_type', ['all'])) ? 'selected' : ''}}>All Failure Types</option>
                            @foreach($failureCauses as $failureCause)
                                <option value="{{$failureCause->id}}" {{in_array($failureCause->id, (array)request('failure_type', [])) ? 'selected' : ''}}>
                                    {{$failureCause->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-plug"></i> Abutments</span>
                            <button type="button" class="filter-reset-btn" data-reset="abutments">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="abutments[]" id="abutments">
                            <option value="all" {{in_array('all', (array)request('abutments', ['all'])) ? 'selected' : ''}}>All Abutments</option>
                            @foreach($abutments as $abutment)
                                <option value="{{$abutment->id}}" {{in_array($abutment->id, (array)request('abutments', [])) ? 'selected' : ''}}>
                                    {{$abutment->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-tooth"></i> Implants</span>
                            <button type="button" class="filter-reset-btn" data-reset="implants">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="implants[]" id="implants">
                            <option value="all" {{in_array('all', (array)request('implants', ['all'])) ? 'selected' : ''}}>All Implants</option>
                            @foreach($implants as $implant)
                                <option value="{{$implant->id}}" {{in_array($implant->id, (array)request('implants', [])) ? 'selected' : ''}}>
                                    {{$implant->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-tasks"></i> Workflow Stage</span>
                            <button type="button" class="filter-reset-btn" data-reset="status">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="status[]" id="status">
                            <option value="all" {{in_array('all', (array)request('status', ['all'])) ? 'selected' : ''}}>All Stages</option>
                            <option value="1" {{in_array('1', (array)request('status', [])) ? 'selected' : ''}}>Design</option>
                            <option value="2" {{in_array('2', (array)request('status', [])) ? 'selected' : ''}}>Milling</option>
                            <option value="3" {{in_array('3', (array)request('status', [])) ? 'selected' : ''}}>3D Printing</option>
                            <option value="4" {{in_array('4', (array)request('status', [])) ? 'selected' : ''}}>Sintering</option>
                            <option value="5" {{in_array('5', (array)request('status', [])) ? 'selected' : ''}}>Pressing</option>
                            <option value="6" {{in_array('6', (array)request('status', [])) ? 'selected' : ''}}>Finishing</option>
                            <option value="7" {{in_array('7', (array)request('status', [])) ? 'selected' : ''}}>QC</option>
                            <option value="8" {{in_array('8', (array)request('status', [])) ? 'selected' : ''}}>Delivery</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-dollar-sign"></i> Invoice Amount</span>
                            <button type="button" class="filter-reset-btn" data-reset="amount">Reset</button>
                        </label>
                        <div class="range-pair">
                            <input type="number" class="modern-input" name="amount_from" id="amount_from"
                                   placeholder="From JOD" value="{{request('amount_from')}}" min="0" step="0.01">
                            <input type="number" class="modern-input" name="amount_to" id="amount_to"
                                   placeholder="To JOD" value="{{request('amount_to')}}" min="0" step="0.01">
                        </div>
                        <small class="text-danger range-error" id="amount-range-error">
                            <i class="fas fa-exclamation-circle"></i> "From" amount cannot be greater than "To" amount
                        </small>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-cubes"></i> Number of Units</span>
                            <button type="button" class="filter-reset-btn" data-reset="units">Reset</button>
                        </label>
                        <div class="range-pair">
                            <input type="number" class="modern-input" name="units_from" id="units_from"
                                   placeholder="From" value="{{request('units_from')}}" min="0" step="1">
                            <input type="number" class="modern-input" name="units_to" id="units_to"
                                   placeholder="To" value="{{request('units_to')}}" min="0" step="1">
                        </div>
                        <small class="text-danger range-error" id="units-range-error">
                            <i class="fas fa-exclamation-circle"></i> "From" units cannot be greater than "To" units
                        </small>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-users"></i> Employees Filter</span>
                            <button type="button" class="filter-reset-btn" data-reset="employees">Reset</button>
                        </label>
                        <button type="button" class="trigger-field" data-toggle="modal" data-target="#employeesFilterModal">
                            Configure Employee Filters
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div id="employees-filter-summary" class="filter-summary filter-pill muted trigger-summary">No employee filters applied</div>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-microchip"></i> Devices Filter</span>
                            <button type="button" class="filter-reset-btn" data-reset="devices">Reset</button>
                        </label>
                        <button type="button" class="trigger-field" data-toggle="modal" data-target="#devicesFilterModal">
                            Configure Device Filters
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div id="devices-filter-summary" class="filter-summary filter-pill muted trigger-summary">All devices included</div>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-check-circle"></i> Case Completion</span>
                            <button type="button" class="filter-reset-btn" data-reset="completion">Reset</button>
                        </label>
                        <div class="modern-toggle-container">
                            <input type="hidden" name="show_completed" id="show_completed_hidden" value="{{request('show_completed', 'all')}}">
                            <button type="button" class="modern-toggle-btn" id="completion_toggle" data-value="{{request('show_completed', 'all')}}">
                                <span class="toggle-option" data-value="all">All Cases</span>
                                <span class="toggle-option" data-value="completed">Completed</span>
                                <span class="toggle-option" data-value="in_progress">In Progress</span>
                                <span class="toggle-slider"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="filters-footer">
                    <button type="button" class="filters-master-reset" id="filters-master-reset">Reset All</button>
                    <button type="submit" class="modern-btn btn-primary generate-btn">
                        <i class="fas fa-chart-line"></i>
                        Generate Report
                    </button>
                </div>
            </div>
        </form>
    </div>


    @php
        $summaryItems = [];
        $clientMap = $clients->pluck('name', 'id');
        $materialMap = $materials->pluck('name', 'id');
        $jobTypeMap = $jobTypes->pluck('name', 'id');
        $failureCauseMap = $failureCauses->pluck('name', 'id');
        $abutmentMap = $abutments->pluck('name', 'id');
        $implantMap = $implants->pluck('name', 'id');
        $stageLabels = [
            '1' => 'Design',
            '2' => 'Milling',
            '3' => '3D Printing',
            '4' => 'Sintering',
            '5' => 'Pressing',
            '6' => 'Finishing',
            '7' => 'QC',
            '8' => 'Delivery',
        ];

        $addSummary = function($label, $values, $map = null) use (&$summaryItems) {
            $arr = array_filter((array) $values, fn($v) => $v !== null && $v !== '');
            if (empty($arr) || in_array('all', $arr, true)) {
                return;
            }
            if ($map) {
                $names = collect($arr)->map(fn($id) => $map[$id] ?? $id)->implode(', ');
            } else {
                $names = implode(', ', $arr);
            }
            if ($names !== '') {
                $summaryItems[] = "{$label}: {$names}";
            }
        };

        // Date range
        $summaryItems[] = "Date: " . request('from', $from) . " → " . request('to', $to);

        // Core filters
        $addSummary('Doctor', request('doctor', []), $clientMap);
        $addSummary('Material', request('material', []), $materialMap);
        $addSummary('Job Type', request('job_type', []), $jobTypeMap);
        $addSummary('Failure Type', request('failure_type', []), $failureCauseMap);
        $addSummary('Abutment', request('abutments', []), $abutmentMap);
        $addSummary('Implant', request('implants', []), $implantMap);

        // Material type names (loaded on demand)
        $materialTypes = (array) request('material_type', []);
        if (!empty($materialTypes) && !in_array('all', $materialTypes, true)) {
            $typeNames = \App\Type::whereIn('id', $materialTypes)->pluck('name')->implode(', ');
            if ($typeNames) {
                $summaryItems[] = "Material Type: {$typeNames}";
            }
        }

        // Workflow stage
        $statusFilters = array_filter((array) request('status', []));
        if (!empty($statusFilters) && !in_array('all', $statusFilters, true)) {
            $labels = collect($statusFilters)->map(fn($id) => $stageLabels[$id] ?? $id)->implode(', ');
            if ($labels) {
                $summaryItems[] = "Workflow Stage: {$labels}";
            }
        }

        // Completion toggle
        $completion = request('show_completed', 'all');
        if ($completion === 'completed') $summaryItems[] = 'Case Completion: Completed';
        elseif ($completion === 'in_progress') $summaryItems[] = 'Case Completion: In Progress';

        // Amount and units ranges
        $amountFrom = request('amount_from');
        $amountTo = request('amount_to');
        if ($amountFrom !== null && $amountFrom !== '') {
            $summaryItems[] = 'Amount From: ' . $amountFrom;
        }
        if ($amountTo !== null && $amountTo !== '') {
            $summaryItems[] = 'Amount To: ' . $amountTo;
        }
        $unitsFrom = request('units_from');
        $unitsTo = request('units_to');
        if ($unitsFrom !== null && $unitsFrom !== '') {
            $summaryItems[] = 'Units From: ' . $unitsFrom;
        }
        if ($unitsTo !== null && $unitsTo !== '') {
            $summaryItems[] = 'Units To: ' . $unitsTo;
        }

        // Employee/device filters counts
        $employeeFilters = (array) request('employee_filters', []);
        if (!empty($employeeFilters)) {
            $summaryItems[] = count($employeeFilters) . ' employee filter(s)';
        }
        $deviceFilters = (array) request('device_filters', []);
        if (!empty($deviceFilters)) {
            $summaryItems[] = count($deviceFilters) . ' device filter(s)';
        }

        $summaryItems = array_filter($summaryItems);
    @endphp

    @if(!empty($summaryItems))
        <div class="modern-card filters-summary-card" style="margin-top: 8px; padding: 10px 14px;">
            <div class="filters-summary-row">
                @foreach($summaryItems as $item)
                    <span class="badge badge-light" style="border: 1px solid #e5e7eb; color: #374151; font-weight: 500; padding: 6px 10px; background: #f9fafb;">
                        {{ $item }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    @if($cases->count() > 0)
        <div class="modern-card" style="margin-top: 16px;">
            <div class="card-header" style="border-bottom: 1px solid #e2e8f0; padding: 12px 24px; background: white;">
                        <div class="d-flex justify-content-between align-items-center"> <h4 style="font-weight: 600; color: #1a202c; margin: 0;">Report Results</h4>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown columns-dropdown">
                            <button class="btn" type="button" id="columnVisibilityDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: white; border: 1px solid #e5e7eb; color: #374151; padding: 8px 16px; font-size: 13px; border-radius: 6px; transition: all 0.2s ease;">
                                <i class="fas fa-columns" style="margin-right: 6px;"></i>
                                Columns
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="columnVisibilityDropdown" style="min-width: 280px; max-height: 500px; overflow-y: auto;">
                                <h6 class="dropdown-header">Basic Information</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-case-id" data-column="0" checked>
                                    <label class="form-check-label" for="col-case-id">Case ID</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-doctor" data-column="1" checked>
                                    <label class="form-check-label" for="col-doctor">Doctor</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-patient" data-column="2" checked>
                                    <label class="form-check-label" for="col-patient">Patient</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-material" data-column="3">
                                    <label class="form-check-label" for="col-material">Material</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-job-type" data-column="4" checked>
                                    <label class="form-check-label" for="col-job-type">Job Type</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-created" data-column="5" checked>
                                    <label class="form-check-label" for="col-created">Created Date</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-delivery" data-column="6" checked>
                                    <label class="form-check-label" for="col-delivery">Delivery Date</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Devices</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-mill-device" data-column="7">
                                    <label class="form-check-label" for="col-mill-device">Mill Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-print-device" data-column="8">
                                    <label class="form-check-label" for="col-print-device">3D Print Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-sinter-device" data-column="9">
                                    <label class="form-check-label" for="col-sinter-device">Sinter Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-press-device" data-column="10">
                                    <label class="form-check-label" for="col-press-device">Press Device</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Employees</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-designer" data-column="11">
                                    <label class="form-check-label" for="col-designer">Designer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-miller" data-column="12">
                                    <label class="form-check-label" for="col-miller">Miller</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-printer" data-column="13">
                                    <label class="form-check-label" for="col-printer">3D Printer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-sintered" data-column="14">
                                    <label class="form-check-label" for="col-sintered">Sintered</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-presser" data-column="15">
                                    <label class="form-check-label" for="col-presser">Presser</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-finisher" data-column="16">
                                    <label class="form-check-label" for="col-finisher">Finisher</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-qc" data-column="17">
                                    <label class="form-check-label" for="col-qc">QC</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-delivery-emp" data-column="18">
                                    <label class="form-check-label" for="col-delivery-emp">Delivery</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Status & Amount</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-status" data-column="19" checked>
                                    <label class="form-check-label" for="col-status">Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-amount" data-column="20" checked>
                                    <label class="form-check-label" for="col-amount">Amount</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <button class="btn btn-sm btn-primary btn-block" id="selectAllColumns" type="button">Select All</button>
                                <button class="btn btn-sm btn-secondary btn-block mt-1" id="deselectAllColumns" type="button">Deselect All</button>
                            </div>
                        </div>
                        <div class="export-buttons">
                        </div>
                    </div>
                </div>
            </div>

            <div class="sigma-report-table-container">
                <table class="printable sigma-report-table table table-striped" id="master-report-table" style="width: 100%;">
                    <thead>
                    <tr>
                        <th class="header-dark">Case ID</th>
                        <th class="header-dark">Doctor Name</th>
                        <th class="header-dark">Patient Name</th>
                        <th class="header-light text-center">Material</th>
                        <th class="header-light text-center">Job Type</th>
                        <th class="header-light text-center">Created Date</th>
                        <th class="header-light text-center">Delivery Date</th>
                        <th class="header-light text-center">Mill Device</th>
                        <th class="header-light text-center">3D Print Device</th>
                        <th class="header-light text-center">Sinter Device</th>
                        <th class="header-light text-center">Press Device</th>
                        <th class="header-light text-center">Designer</th>
                        <th class="header-light text-center">Miller</th>
                        <th class="header-light text-center">3D Printer</th>
                        <th class="header-light text-center">Sintered</th>
                        <th class="header-light text-center">Presser</th>
                        <th class="header-light text-center">Finisher</th>
                        <th class="header-light text-center">QC</th>
                        <th class="header-light text-center">Delivery</th>
                        <th class="header-dark">Status</th>
                        <th class="header-dark">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="master-report-header-row">
                        <td class="header-dark text-left" data-order="-1" data-search="">Case ID</td>
                        <td class="header-dark text-left" data-order="-1" data-search="">Doctor Name</td>
                        <td class="header-dark text-left" data-order="-1" data-search="">Patient Name</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Material</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Job Type</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Created Date</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Delivery Date</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Mill Device</td>
                        <td class="header-light text-center" data-order="-1" data-search="">3D Print Device</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Sinter Device</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Press Device</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Designer</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Miller</td>
                        <td class="header-light text-center" data-order="-1" data-search="">3D Printer</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Sintered</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Presser</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Finisher</td>
                        <td class="header-light text-center" data-order="-1" data-search="">QC</td>
                        <td class="header-light text-center" data-order="-1" data-search="">Delivery</td>
                        <td class="header-dark text-center" data-order="-1" data-search="">Status</td>
                        <td class="header-dark text-center" data-order="-1" data-search="">Amount</td>
                    </tr>
                    @foreach($cases as $case)
                        @php
                            // Get ALL devices used by stage across ALL jobs in this case
                            $devicesByStage = [
                                2 => collect(), // Milling
                                3 => collect(), // 3D Printing
                                4 => collect(), // Sintering
                                5 => collect(), // Pressing
                            ];

                            // Get ALL materials and job types from jobs
                            $materials = collect();
                            $jobTypes = collect();

                            // Loop through ALL jobs to collect data
                            foreach($case->jobs as $job) {
                                // Collect materials
                                if($job->material) {
                                    $materials->push($job->material->name);
                                }

                                // Collect job types
                                if($job->jobType) {
                                    $jobTypes->push($job->jobType->name);
                                }

                                // Milling stage (stage 2)
                                if($job->millingBuild && $job->millingBuild->deviceUsed) {
                                    $devicesByStage[2]->push($job->millingBuild->deviceUsed->name);
                                }

                                // 3D Printing stage (stage 3)
                                if($job->printingBuild && $job->printingBuild->deviceUsed) {
                                    $devicesByStage[3]->push($job->printingBuild->deviceUsed->name);
                                }

                                // Sintering stage (stage 4)
                                if($job->sinteringBuild && $job->sinteringBuild->deviceUsed) {
                                    $devicesByStage[4]->push($job->sinteringBuild->deviceUsed->name);
                                }

                                // Pressing stage (stage 5)
                                if($job->pressingBuild && $job->pressingBuild->deviceUsed) {
                                    $devicesByStage[5]->push($job->pressingBuild->deviceUsed->name);
                                }
                            }

                            // Get unique values and format
                            $materialsStr = $materials->unique()->filter()->implode(', ') ?: '-';
                            $jobTypesStr = $jobTypes->unique()->filter()->implode(', ') ?: '-';
                            $millingDevicesStr = $devicesByStage[2]->unique()->filter()->implode(', ') ?: '-';
                            $printingDevicesStr = $devicesByStage[3]->unique()->filter()->implode(', ') ?: '-';
                            $sinteringDevicesStr = $devicesByStage[4]->unique()->filter()->implode(', ') ?: '-';
                            $pressingDevicesStr = $devicesByStage[5]->unique()->filter()->implode(', ') ?: '-';

                            // Get last employee for each stage from case logs
                            $stageLogs = $case->caseLogs->groupBy('stage');
                            $stageEmployees = [
                                1 => $stageLogs->get(1)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                2 => $stageLogs->get(2)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                3 => $stageLogs->get(3)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                4 => $stageLogs->get(4)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                5 => $stageLogs->get(5)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                6 => $stageLogs->get(6)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                7 => $stageLogs->get(7)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                                8 => $stageLogs->get(8)?->sortByDesc('created_at')->first()?->user->name_initials ?? '-',
                            ];

                            // Format dates
                            $createdAt = $case->created_at ? \Carbon\Carbon::parse($case->created_at) : null;
                            $createdDate = $createdAt ? $createdAt->format('d M, Y') : '-';
                            $createdTime = $createdAt ? $createdAt->format('h:i A') : '-';

                            $deliveryRaw = $case->actual_delivery_date ?: $case->initial_delivery_date;
                            $deliveryAt = $deliveryRaw ? \Carbon\Carbon::parse($deliveryRaw) : null;
                            $deliveryDate = $deliveryAt ? $deliveryAt->format('d M, Y') : '-';
                            $deliveryTime = $deliveryAt ? $deliveryAt->format('h:i A') : '-';

                            $caseStatus = (string) $case->status();
                        @endphp
                        <tr class="master-report-row clickable" data-toggle="modal" data-target="#actionsDialog{{$case->id}}" data-case-id="{{$case->id}}">
                            <td class="text-left"><strong>{{$case->id}}</strong></td>
                            <td class="text-left">{{$case->client->name ?? 'N/A'}}</td>
                            <td class="text-left">{{$case->patient_name}}</td>
                            <td class="text-center">{{$materialsStr}}</td>
                            <td class="text-center">{{$jobTypesStr}}</td>
                            <td class="text-center">
                                <div class="report-date-line">{{$createdDate}}</div>
                                <div class="report-time-line">{{$createdTime}}</div>
                            </td>
                            <td class="text-center">
                                <div class="report-date-line">{{$deliveryDate}}</div>
                                <div class="report-time-line">{{$deliveryTime}}</div>
                            </td>
                            <td class="text-center">{{$millingDevicesStr}}</td>
                            <td class="text-center">{{$printingDevicesStr}}</td>
                            <td class="text-center">{{$sinteringDevicesStr}}</td>
                            <td class="text-center">{{$pressingDevicesStr}}</td>
                            <td class="text-center">{{$stageEmployees[1]}}</td>
                            <td class="text-center">{{$stageEmployees[2]}}</td>
                            <td class="text-center">{{$stageEmployees[3]}}</td>
                            <td class="text-center">{{$stageEmployees[4]}}</td>
                            <td class="text-center">{{$stageEmployees[5]}}</td>
                            <td class="text-center">{{$stageEmployees[6]}}</td>
                            <td class="text-center">{{$stageEmployees[7]}}</td>
                            <td class="text-center">{{$stageEmployees[8]}}</td>
                            <td class="text-center">
                                @if(str_contains($caseStatus, "Completed"))
                                    <span class="badge badge-success sigma-case-status-badge sigma-status-width">
                                        <span class="sigma-badge-label">{{ $caseStatus }}</span>
                                    </span>
                                @elseif(str_contains($caseStatus, "In-Progress") || str_contains($caseStatus, "Active"))
                                    @php
                                        $rawStatus = trim($caseStatus);

                                        $stageText = $rawStatus;
                                        if (Str::contains($rawStatus, 'Active in')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                                        }

                                        $assigneeInitials = '';
                                        $jobAtStage = $case->jobs->first(function ($job) use ($case, $stageText) {
                                            return $job->assignee !== null && trim($case->stageToText((string) $job->stage)) === $stageText;
                                        });

                                        if (!$jobAtStage) {
                                            $jobAtStage = $case->jobs->first(function ($job) {
                                                return $job->assignee !== null && (string) $job->stage !== '-1';
                                            });
                                        }

                                        if ($jobAtStage && $jobAtStage->assignedTo) {
                                            $assigneeInitials = trim((string) (
                                                $jobAtStage->assignedTo->name_initials
                                                ?? $jobAtStage->assignedTo->first_name
                                                ?? ''
                                            ));
                                        }

                                        if (in_array($stageText, ['In-Progress', 'Active', ''], true) && $jobAtStage) {
                                            $stageText = trim($case->stageToText((string) $jobAtStage->stage));
                                        }

                                        $formattedStatus = $assigneeInitials !== ''
                                            ? (trim($stageText) . '/ ' . $assigneeInitials)
                                            : trim($stageText);
                                    @endphp

                                    <span class="badge badge-primary sigma-case-status-badge sigma-status-width">
                                        <span class="tooltipX">
                                            <span class="sigma-badge-label">{{ $formattedStatus }}</span>
                                        </span>
                                    </span>
                                @elseif(str_contains($caseStatus, "Waiting"))
                                    <span class="badge badge-danger sigma-case-status-badge sigma-status-width">
                                        @php
                                            $status = preg_replace('/' . "in" . '/', "", str_replace("Waiting", "", $caseStatus), 1);
                                        @endphp
                                        <span class="sigma-badge-label">{{ trim($status) }}</span>
                                    </span>
                                @else
                                    @php
                                        $isDeliveryAssigned = $case->jobs[0]->stage == 8 && $case->jobs[0]->assignee != null && $case->jobs[0]->delivery_accepted == null;
                                        $deliveryBadgeText = $caseStatus;
                                        if ($isDeliveryAssigned && $case->jobs[0]->assignedTo) {
                                            $employeeInitials = trim((string) (
                                                $case->jobs[0]->assignedTo->name_initials
                                                ?? $case->jobs[0]->assignedTo->first_name
                                                ?? ''
                                            ));
                                            $deliveryBadgeText = 'Delivery/ ' . $employeeInitials;
                                        }
                                    @endphp
                                    <span class="badge badge-warning sigma-case-status-badge sigma-status-width">
                                        @if($isDeliveryAssigned)
                                            <span class="sigma-badge-label">{{ $deliveryBadgeText }}</span>
                                        @else
                                            <span class="tooltipX">
                                                <span class="sigma-badge-label">{{ $caseStatus }}</span>
                                                <span class="tooltiptext">{!! $case->getStatusToolTipHTML() !!}</span>
                                            </span>
                                        @endif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center"><strong>{{abs($case->invoice->amount ?? 0)}}</strong></td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="totals-row">
                        <td colspan="20" class="text-right"><strong>Total Cases: {{$cases->count()}}</strong></td>
                        <td class="text-center"><strong>{{$cases->sum(function($case) { return abs($case->invoice->amount ?? 0); })}}</strong></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @foreach($cases  as $case)
            <div class="modal sigma-modal--cases-index-actions" tabindex="-1" role="dialog"
                 id="actionsDialog{{$case->id}}" data-backdrop="true" data-keyboard="true">

                <input type="hidden" name="case_id" value="{{$case->id}}">
                <div class="modal-dialog modal-dialog-centered   " role="document">
                    <div class="modal-content  ">

                        <div class="modal-body ">
                            <!-- Sticky Doctor/Patient section -->
                            <div class="form-group row" style="margin-bottom: 0px">
                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                    <label for="doctor" class="patient-doctor-label">Doctor:</label>
                                    <h5 id="doctor"
                                        class="patient-doctor-names">{{$case->client->name ?? "-"}}</h5>
                                </div>
                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                    <label for="pat" class="patient-doctor-label">patient:</label>
                                    <h5 id="pat" class="patient-doctor-names">{{$case->patient_name}}</h5>
                                </div>
                            </div>
                            <hr>

                            <!-- Scrollable Jobs and Notes section -->
                            <div class="scrollable-content">
                                <div class="form-group row">
                                    <div class=" col-12 ">
                                        <label><b>Jobs:</b></label><br>


                                        @php
                                            // Determine case's current stage (first job's stage)
                                            $currentStage = $case->jobs->first()->stage ?? null;
                                        @endphp

                                        @foreach( $case->jobs as $job)
                                            @php
                                                $unit = explode(', ',$job->unit_num);
                                                // Only show jobs that go through the current stage
                                                $showJob = $job->goesThroughStage($currentStage);
                                            @endphp

                                            @if($showJob)
                                                <div class="job-info-for-tooltip" style="display: none;">
                                                    <span class="job-type">{{ $job->jobType->name ?? "No Job Type" }}</span>
                                                    <span class="job-material">{{ $job->material->name ?? "no material" }}</span>
                                                    <span class="job-units">{{ $job->unit_num }}</span>
                                                </div>
                                                <span>{{$job->unit_num}} - {{$job->jobType->name ?? "No Job Type"}} - {{$job->material->name ?? "no material"}} {{$job->color =='0' ? "":" - " .$job->color}}
                                                            {{$job->style == 'None' ? "":" - " .$job->style}} {{isset($job->implantR) && $job->jobType->id ==6  ?( " - Implant Type: " . $job->implantR->name): "" }}<br>
                                                                            {{isset($job->abutmentR)  && $job->jobType->id ==6  ?( " Abutment Type: " . $job->abutmentR->name): "" }} </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @if(count($case->notes)>0)
                                    <hr>
                                    <label><b>Notes:</b></label><br>
                                    @foreach($case->notes as $note)
                                        <div class="form-control note-container"
                                             style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px"
                                             disabled>

                                            <span class="noteHeader" style="font-weight:600">{{ '[' . \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) . ' ' }}<b>{{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}</b>{{ '] [' . $note->writtenBy->name_initials . '] : ' }}</span><span
                                                    class="noteText">{{$note->note}}</span>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            @if(!isset($trashedCases))
                                <div class="sigma-modal-actions">
                                    <div class="sigma-actions-row sigma-actions-row--top">
                                        <a href="{{route('view-voucher',$case->id)}}"
                                           class="btn btn-info"><span class="btn-icon"><i
                                                    class="fas fa-print"></i></span><span class="btn-text">Print Voucher</span></a>
                                        <a href="{{route('view-case',['id' =>$case->id ,'stage' =>-2 ])}}"
                                           class="btn btn-info"><span class="btn-icon"><i
                                                    class="far fa-file-alt"></i></span><span
                                                    class="btn-text">View</span></a>
                                    </div>

                                    <div class="sigma-actions-grid">
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                                            @if(!$case->locked)
                                                <a href="{{route('lock-case',$case->id)}}"
                                                   class="btn btn-dark"><span class="btn-icon"><i
                                                            class="fas fa-lock"></i></span><span
                                                            class="btn-text">Lock</span></a>
                                            @else
                                                <a href="{{route('unlock-case',$case->id)}}"
                                                   class="btn btn-dark"><span class="btn-icon"><i
                                                            class="fas fa-lock-open"></i></span><span
                                                            class="btn-text">Unlock</span></a>
                                            @endif
                                        @endif

                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 131))
                                            <a href="{{route('delete-case',$case->id)}}" onclick="caseDelConfirmation(event)"
                                               class="btn btn-danger" data-clientName="{{ $case->client->name ?? '' }}" data-patientName="{{ $case->patient_name ?? '' }}">
                                                <span class="btn-icon"><i class="fas fa-trash"></i></span><span class="btn-text">Delete</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 124))
                                            <a href="{{route('reject-case',$case->id)}}" class="btn btn-outline-danger">
                                                <span class="btn-icon"><i class="fas fa-times"></i></span><span class="btn-text">Reject case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 125))
                                            <a href="{{route('repeat-case',$case->id)}}" class="btn btn-outline-warning">
                                                <span class="btn-icon"><i class="fas fa-undo"></i></span><span class="btn-text">Repeat case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 126))
                                            <a href="{{route('modify-case',$case->id)}}" class="btn btn-outline-warning">
                                                <span class="btn-icon"><i class="fas fa-pen"></i></span><span class="btn-text">Modify case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 127))
                                            <a href="{{route('edit-case',$case->id)}}" class="btn btn-warning">
                                                <span class="btn-icon"><i class="fas fa-pen-to-square"></i></span><span class="btn-text">Edit</span>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="sigma-actions-row sigma-actions-row--cancel">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="modern-card" style="text-align: center; padding: 48px;">
            <div style="color: #6b7280; font-size: 18px;">
                <i class="fas fa-search" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                <div>No cases found matching the selected criteria.</div>
                <div style="font-size: 14px; margin-top: 8px;">Try adjusting your filters and search again.</div>
            </div>
        </div>
        @endif
        </div>

        <div class="modal fade sigma-modal--report-employees-filter" id="employeesFilterModal" tabindex="-1" aria-labelledby="employeesFilterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="employeesFilterModalLabel">
                            <i class="fas fa-users"></i> Filter by Employees
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="employee-filters-container">
                        </div>
                        <button type="button" class="btn btn-sm btn-success" id="add-employee-filter">
                            <i class="fas fa-plus"></i> Add Employee Filter
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="apply-employee-filters">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade sigma-modal--report-devices-filter" id="devicesFilterModal" tabindex="-1" aria-labelledby="devicesFilterModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="devicesFilterModalLabel">
                            <i class="fas fa-microchip"></i> Filter by Devices
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="device-filters-container">
                        </div>
                        <button type="button" class="btn btn-sm btn-success" id="add-device-filter">
                            <i class="fas fa-plus"></i> Add Device Filter
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="apply-device-filters">Apply Filters</button>
                    </div>
                </div>
            </div>
        </div>

        @endsection

@push('js')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>

            <script>
                // Initialize completion toggle
                function initializeCompletionToggle() {
                    const $toggle = $('#completion_toggle');
                    const $hidden = $('#show_completed_hidden');
                    const values = ['all', 'completed', 'in_progress'];
                    const currentValue = $toggle.attr('data-value') || 'all';
                    setToggleValue(currentValue);

                    $('.toggle-option').off('click.completion').on('click.completion', function(e) {
                        e.preventDefault();
                        const value = $(this).attr('data-value');
                        setToggleValue(value);
                    });

                    $toggle.off('click.completion').on('click.completion', function(e) {
                        if ($(e.target).hasClass('toggle-option')) return;
                        const current = $toggle.attr('data-value') || 'all';
                        const next = values[(values.indexOf(current) + 1) % values.length];
                        setToggleValue(next);
                    });

                    function setToggleValue(value) {
                        $toggle.attr('data-value', value);
                        $hidden.val(value);
                        updateToggleState(value);
                    }

                    function updateToggleState(value) {
                        $('.toggle-option').removeClass('active');
                        $(`.toggle-option[data-value="${value}"]`).addClass('active');
                    }
                }

                function setCompletionValue(value) {
                    const $toggle = $('#completion_toggle');
                    const $hidden = $('#show_completed_hidden');
                    $toggle.attr('data-value', value);
                    $hidden.val(value);
                    $('.toggle-option').removeClass('active');
                    $(`.toggle-option[data-value="${value}"]`).addClass('active');
                }

                function setDtpValue(inputId, value) {
                    const input = document.getElementById(inputId);
                    if (!input) {
                        return;
                    }
                    input.value = value || '';
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }

                function resetSelectToAll(selector) {
                    const $select = $(selector);
                    if (!$select.length) {
                        return;
                    }
                    const hasAll = $select.find('option[value="all"]').length > 0;
                    const nextValue = hasAll ? ['all'] : [];
                    $select.val(nextValue);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.trigger('change.select2');
                    } else {
                        $select.trigger('change');
                    }
                }

                function resetEmployeeFilters() {
                    employeeFilterCount = 0;
                    const container = document.getElementById('employee-filters-container');
                    if (container) {
                        container.innerHTML = '';
                    }
                    const hiddenContainer = document.getElementById('hidden-employee-filters');
                    if (hiddenContainer) {
                        hiddenContainer.innerHTML = '';
                    }
                    addEmployeeFilterRow();
                    const summary = document.getElementById('employees-filter-summary');
                    if (summary) {
                        summary.textContent = 'No employee filters applied';
                        summary.className = 'filter-summary filter-pill muted trigger-summary';
                    }
                }

                function resetDeviceFilters() {
                    deviceFilterCount = 0;
                    const container = document.getElementById('device-filters-container');
                    if (container) {
                        container.innerHTML = '';
                    }
                    const hiddenContainer = document.getElementById('hidden-device-filters');
                    if (hiddenContainer) {
                        hiddenContainer.innerHTML = '';
                    }
                    addDeviceFilterRow();
                    const summary = document.getElementById('devices-filter-summary');
                    if (summary) {
                        summary.textContent = 'All devices included';
                        summary.className = 'filter-summary filter-pill muted trigger-summary';
                    }
                }

                function resetFilterByKey(key) {
                    switch (key) {
                        case 'date-from':
                            setDtpValue('master_from', window.masterReportDefaults?.from);
                            break;
                        case 'date-to':
                            setDtpValue('master_to', window.masterReportDefaults?.to);
                            break;
                        case 'doctor':
                            resetSelectToAll('#doctor');
                            break;
                        case 'material':
                            resetSelectToAll('#material');
                            break;
                        case 'job-type':
                            resetSelectToAll('#job_type');
                            break;
                        case 'material-type':
                            resetSelectToAll('#material_type');
                            break;
                        case 'failure-type':
                            resetSelectToAll('#failure_type');
                            break;
                        case 'abutments':
                            resetSelectToAll('#abutments');
                            break;
                        case 'implants':
                            resetSelectToAll('#implants');
                            break;
                        case 'status':
                            resetSelectToAll('#status');
                            break;
                        case 'amount':
                            $('#amount_from').val('').trigger('input');
                            $('#amount_to').val('').trigger('input');
                            break;
                        case 'units':
                            $('#units_from').val('').trigger('input');
                            $('#units_to').val('').trigger('input');
                            break;
                        case 'employees':
                            resetEmployeeFilters();
                            break;
                        case 'devices':
                            resetDeviceFilters();
                            break;
                        case 'completion':
                            setCompletionValue('all');
                            break;
                        default:
                            break;
                    }
                }

                function resetAllFilters() {
                    resetFilterByKey('date-from');
                    resetFilterByKey('date-to');
                    resetFilterByKey('doctor');
                    resetFilterByKey('material');
                    resetFilterByKey('job-type');
                    resetFilterByKey('material-type');
                    resetFilterByKey('failure-type');
                    resetFilterByKey('abutments');
                    resetFilterByKey('implants');
                    resetFilterByKey('status');
                    resetFilterByKey('amount');
                    resetFilterByKey('units');
                    resetFilterByKey('employees');
                    resetFilterByKey('devices');
                    resetFilterByKey('completion');
                }

                function initializeFilterResets() {
                    window.masterReportDefaults = {
                        from: @json($from),
                        to: @json($to)
                    };
                    document.querySelectorAll('.filter-reset-btn').forEach((btn) => {
                        btn.addEventListener('click', function() {
                            const key = this.getAttribute('data-reset');
                            resetFilterByKey(key);
                        });
                    });
                    const masterReset = document.getElementById('filters-master-reset');
                    if (masterReset) {
                        masterReset.addEventListener('click', function() {
                            resetAllFilters();
                        });
                    }
                }

                // Initialize modern components
                $(document).ready(function() {
                    function initializeComponents() {
                        initializeSelect2();
                        initializeFlatpickr();
                        initializeDataTable();
                        initializeEmployeeFiltersState();
                        initializeDeviceFiltersState();
                        initializeRangeValidation();
                        initializeColumnVisibility();
                        initializeCompletionToggle();
                        initializeFilterResets();
                    }

                    // Check if libraries are loaded
                    if (typeof $.fn.select2 !== 'undefined' || typeof flatpickr !== 'undefined') {
                        initializeComponents();
                    } else {
                        setTimeout(initializeComponents, 500);
                    }

                    // Form submission validation
                    $('#master-report-form').on('submit', function(e) {
                        const amountValid = validateAmountRange();
                        const unitsValid = validateUnitsRange();

                        if (!amountValid || !unitsValid) {
                            e.preventDefault();
                            alert('Please fix the validation errors before submitting the form.');
                            return false;
                        }

                        // Allow form to submit
                        return true;
                    });
                });

                // Initialize Select2 Dropdowns
                function initializeSelect2() {
                    if (typeof $.fn.select2 === 'undefined') {
                        $('.select2-multiple').addClass('modern-select');
                        return;
                    }

                    try {
                        $('.select2-multiple').each(function() {
                            const $parent = $(this).closest('.filter-group');
                            const dropdownParent = $parent.length ? $parent : $(this).closest('form');
                            $(this).select2({
                                placeholder: 'Select options...',
                                allowClear: true,
                                width: '100%',
                                closeOnSelect: false,
                                multiple: true,
                                dropdownParent: dropdownParent, // attach to nearest filter group for correct layering
                            });
                        });

                        // Initialize material type dependency
                        initializeMaterialTypeDependency();

                        // Initialize exclusive "All" selection logic
                        initializeAllOptionLogic();

                        // Clean up "all" option from multi-select dropdowns on page load
                        cleanupAllOptionOnLoad();
                    } catch (error) {
                        $('.select2-multiple').addClass('modern-select');
                        initializeMaterialTypeDependency();
                        initializeAllOptionLogic();
                    }
                }

                // Initialize Material Type dependency
                function initializeMaterialTypeDependency() {
                    loadMaterialTypes();
                    $('#material').on('change', function() {
                        loadMaterialTypes();
                    });
                }

                // Load material types based on selected materials
                function loadMaterialTypes() {
                    const selectedMaterials = $('#material').val() || [];
                    const url = new URL('/api/material-types', window.location.origin);

                    if (selectedMaterials.length > 0 && !selectedMaterials.includes('all')) { // FIX: Check for 'all'
                        selectedMaterials.forEach(id => {
                            url.searchParams.append('material_ids[]', id);
                        });
                    }

                    fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                updateMaterialTypeDropdown(data.types);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching material types:', error);
                        });
                }

                // Update material type dropdown
                function updateMaterialTypeDropdown(types) {
                    const $materialType = $('#material_type');
                    const currentValues = $materialType.val() || [];

                    // Destroy select2 before updating options
                    if (typeof $.fn.select2 !== 'undefined' && $materialType.hasClass('select2-hidden-accessible')) {
                        $materialType.select2('destroy');
                    }

                    $materialType.empty();
                    $materialType.append('<option value="all">All Material Types</option>');

                    types.forEach(type => {
                        const selected = currentValues.includes(type.id.toString()) ||
                            (window.initialMaterialTypes && window.initialMaterialTypes.includes(type.id.toString()));
                        $materialType.append(`<option value="${type.id}" ${selected ? 'selected' : ''}>${type.name}</option>`);
                    });

                    // Re-initialize select2
                    if (typeof $.fn.select2 !== 'undefined') {
                        $materialType.select2({
                            placeholder: 'Select options...',
                            allowClear: true,
                            width: '100%',
                            closeOnSelect: false,
                            multiple: true,
                            dropdownParent: ($materialType.closest('.filter-group').length ? $materialType.closest('.filter-group') : $materialType.closest('form'))
                        });
                    }

                    if (window.initialMaterialTypes) {
                        window.initialMaterialTypes = null;
                    }
                }

                // Initialize exclusive "All" option logic for dropdowns
                function initializeAllOptionLogic() {
                    const dropdownIds = ['doctor', 'material', 'job_type', 'failure_type', 'abutments', 'implants', 'status', 'material_type'];

                    dropdownIds.forEach(function(dropdownId) {
                        const $dropdown = $('#' + dropdownId);

                        // Use 'change.select2' to avoid recursion if available
                        $dropdown.on('change.select2', function(e) {
                            // Check if this event was triggered by our own logic
                            if (e.hasOwnProperty('originalEvent')) return;

                            const selectedValue = $(this).val();
                            if (!Array.isArray(selectedValue)) return;

                            const hasAll = selectedValue.includes('all');
                            const lastSelected = e.params?.data?.id;

                            if (lastSelected === 'all' && hasAll) {
                                // If "All" was just selected, deselect all others
                                $(this).val(['all']).trigger('change.select2.dont-recurse');
                            } else if (hasAll && selectedValue.length > 1) {
                                // If a specific option was selected while "All" was present, remove "All"
                                const filteredValues = selectedValue.filter(val => val !== 'all');
                                $(this).val(filteredValues).trigger('change.select2.dont-recurse');
                            } else if (selectedValue.length === 0) {
                                // If everything is deselected, re-select "All"
                                $(this).val(['all']).trigger('change.select2.dont-recurse');
                            }
                        });
                    });
                }

                // Clean up "all" option from dropdowns on page load if specific options are selected
                function cleanupAllOptionOnLoad() {
                    const dropdownIds = ['doctor', 'material', 'job_type', 'failure_type', 'abutments', 'implants', 'status', 'material_type'];

                    dropdownIds.forEach(function(dropdownId) {
                        const $dropdown = $('#' + dropdownId);
                        const currentValues = $dropdown.val() || [];

                        // If dropdown has both "all" and other specific values, remove "all"
                        if (currentValues.includes('all') && currentValues.length > 1) {
                            const filteredValues = currentValues.filter(val => val !== 'all');
                            $dropdown.val(filteredValues);

                            // Trigger change to update select2 UI
                            if (typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible')) {
                                $dropdown.trigger('change.select2');
                            }
                        }
                    });
                }

                // Initialize range validation
                function initializeRangeValidation() {
                    $('#amount_from, #amount_to').on('input', validateAmountRange);
                    $('#units_from, #units_to').on('input', validateUnitsRange);
                }

                function validateAmountRange() {
                    const fromAmount = parseFloat($('#amount_from').val()) || 0;
                    const toAmountInput = $('#amount_to');
                    const toAmount = parseFloat(toAmountInput.val()) || 0;

                    if (fromAmount > toAmount && toAmountInput.val() !== '') {
                        toAmountInput.css('border-color', 'red');
                        $('#amount-range-error').show();
                        return false;
                    } else {
                        toAmountInput.css('border-color', '#d1d5db');
                        $('#amount-range-error').hide();
                        return true;
                    }
                }

                function validateUnitsRange() {
                    const fromUnits = parseInt($('#units_from').val()) || 0;
                    const toUnitsInput = $('#units_to');
                    const toUnits = parseInt(toUnitsInput.val()) || 0;

                    if (fromUnits > toUnits && toUnitsInput.val() !== '') {
                        toUnitsInput.css('border-color', 'red');
                        $('#units-range-error').show();
                        return false;
                    } else {
                        toUnitsInput.css('border-color', '#d1d5db');
                        $('#units-range-error').hide();
                        return true;
                    }
                }

                // Initialize column visibility
                function initializeColumnVisibility() {
                    // Prevent dropdown from closing on click
                    $('#columnVisibilityDropdown').next('.dropdown-menu').on('click', function(e) {
                        e.stopPropagation();
                    });

                    loadColumnPreferences();

                    $('.column-toggle').on('change', function() {
                        const columnIndex = $(this).data('column');
                        const isVisible = $(this).is(':checked');
                        toggleColumn(columnIndex, isVisible);
                        saveColumnPreferences();
                    });

                    $('#selectAllColumns').on('click', function() {
                        $('.column-toggle').prop('checked', true).trigger('change');
                    });

                    $('#deselectAllColumns').on('click', function() {
                        $('.column-toggle').prop('checked', false).trigger('change');
                    });
                }

                function toggleColumn(columnIndex, isVisible) {
                    if (window.masterReportTable) {
                        const column = window.masterReportTable.column(columnIndex);
                        column.visible(isVisible);
                        window.masterReportTable.columns.adjust();
                    } else {
                        const table = $('#master-report-table');
                        if (isVisible) {
                            table.find(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`).show();
                        } else {
                            table.find(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`).hide();
                        }
                    }
                }

                function saveColumnPreferences() {
                    const preferences = {};
                    $('.column-toggle').each(function() {
                        const columnIndex = $(this).data('column');
                        preferences[columnIndex] = $(this).is(':checked');
                    });
                    localStorage.setItem('masterReportColumnPreferences', JSON.stringify(preferences));
                }

                function loadColumnPreferences() {
                    const savedPreferences = localStorage.getItem('masterReportColumnPreferences');
                    if (savedPreferences) {
                        try {
                            const preferences = JSON.parse(savedPreferences);
                            Object.keys(preferences).forEach(columnIndex => {
                                const checkbox = $(`.column-toggle[data-column="${columnIndex}"]`);
                                const isVisible = preferences[columnIndex];
                                checkbox.prop('checked', isVisible);
                                // Toggle logic is now in initializeDataTable's setTimeout
                            });
                        } catch (error) {
                            console.warn('Error loading column preferences:', error);
                        }
                    }
                }

                // Initialize Flatpickr
                function initializeFlatpickr() {
                    if (typeof flatpickr === 'undefined') {
                        return;
                    }

                    const dateRangeInput = document.getElementById('daterange');
                    if (!dateRangeInput) {
                        return;
                    }

                    try {
                        const fromDate = '{{request('from', $from)}}';
                        const toDate = '{{request('to', $to)}}';

                        flatpickr('#daterange', {
                            mode: 'range',
                            dateFormat: 'Y-m-d',
                            defaultDate: [fromDate, toDate],
                            showMonths: 2,
                            static: true,
                            onChange: function(selectedDates, dateStr, instance) {
                                if (selectedDates.length === 2) {
                                    // Use timezone-safe date formatting to avoid day shifting
                                    const formatDate = (date) => {
                                        const year = date.getFullYear();
                                        const month = String(date.getMonth() + 1).padStart(2, '0');
                                        const day = String(date.getDate()).padStart(2, '0');
                                        return `${year}-${month}-${day}`;
                                    };

                                    $('#from-date').val(formatDate(selectedDates[0]));
                                    $('#to-date').val(formatDate(selectedDates[1]));
                                }
                            }
                        });
                    } catch (error) {
                        console.error('Error initializing Flatpickr:', error);
                    }
                }

                // Initialize DataTable
                function initializeDataTable() {
                    @if($cases->count() > 0)
                        window.masterReportTable = $('#master-report-table').DataTable({
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: '<i class="fas fa-file-excel"></i> Excel',
                                className: 'btn btn-success btn-sm',
                                title: 'Master Report - {{date("Y-m-d")}}'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: '<i class="fas fa-file-pdf"></i> PDF',
                                className: 'btn btn-danger btn-sm',
                                title: 'Master Report - {{date("Y-m-d")}}',
                                orientation: 'landscape',
                                pageSize: 'A3'
                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fas fa-file-csv"></i> CSV',
                                className: 'btn btn-info btn-sm',
                                title: 'Master Report - {{date("Y-m-d")}}'
                            }
                        ],
                        pageLength: 25,
                        responsive: false,
                        scrollX: false,
                        scrollY: false,
                        scrollCollapse: false,
                        autoWidth: false,
                        ordering: false,
                        fixedColumns: false,
                        columnDefs: [
                            { targets: '_all', className: 'text-center' },
                            { targets: [0, 1, 2], className: 'text-left' }, // FIX: Only first 3 columns are text-left
                            { targets: [0, 1, 2, 3, 4, 5, 6, 19, 20], orderable: true },
                            { targets: [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18], orderable: false }
                        ],
                        drawCallback: function() {
                            // Apply header styling after DataTables draws
                            $('#master-report-table thead th.header-light, .sigma-report-table-container .dataTables_scrollHead th.header-light').css({
                                'background-color': 'transparent',
                                'background': 'none',
                                'border': 'none',
                                'border-bottom': '2px solid #408385',
                                'color': '#408385'
                            });
                            $('#master-report-table thead th.header-dark, .sigma-report-table-container .dataTables_scrollHead th.header-dark').css({
                                'background-color': '#408385',
                                'background': '#408385',
                                'color': 'white',
                                'border': 'none'
                            });
                        },
                        // FIX: Use initComplete to apply preferences and fix positions
                        "initComplete": function(settings, json) {
                            const tableApi = this.api();
                            // Apply column visibility preferences
                            $('.column-toggle').each(function() {
                                const columnIndex = $(this).data('column');
                                const isVisible = $(this).is(':checked');
                                tableApi.column(columnIndex).visible(isVisible);
                            });
                        }
                    });

                    window.masterReportTable.buttons().container().appendTo('.export-buttons');

                    // Apply header styling immediately after initialization
                    setTimeout(() => {
                        $('#master-report-table thead th.header-light, .sigma-report-table-container .dataTables_scrollHead th.header-light').css({
                            'background-color': 'transparent',
                            'background': 'none',
                            'border': 'none',
                            'border-bottom': '2px solid #408385',
                            'color': '#408385'
                        });
                        $('#master-report-table thead th.header-dark, .sigma-report-table-container .dataTables_scrollHead th.header-dark').css({
                            'background-color': '#408385',
                            'background': '#408385',
                            'color': 'white',
                            'border': 'none'
                        });

                        // Load preferences *after* table is built
                        loadColumnPreferences();
                        // Re-apply column visibility based on checkboxes after preferences load
                        $('.column-toggle').each(function() {
                            const columnIndex = $(this).data('column');
                            const isVisible = $(this).is(':checked');
                            window.masterReportTable.column(columnIndex).visible(isVisible);
                        });
                    }, 100);
                    @endif
                }

                // Case viewing function
                function viewMasterReportCase(caseId) {
                    if (confirm(`View details for Case ID: ${caseId}?`)) {
                        window.open(`/cases/${caseId}`, '_blank');
                    }
                }

                $(document).on('show.bs.modal', '.sigma-modal--cases-index-actions', function() {
                    $(this).removeAttr('aria-hidden');
                });

                function caseDelConfirmation(ev) {
                    ev.preventDefault();
                    var urlToRedirect = ev.currentTarget.getAttribute('href');
                    var clientName = ev.currentTarget.getAttribute('data-clientName');
                    var patientName = ev.currentTarget.getAttribute('data-patientName');

                    swal.fire({
                        title: "You sure You want to delete.. </br>" + clientName + " - " + patientName,
                        text: "This will also delete related info. (invoice, photos .. etc)?",
                        icon: "warning",
                        showDenyButton: true,
                        confirmButtonText: 'Delete Case',
                        denyButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = urlToRedirect;
                        }
                    });
                }

                // --- Employee/Device Filter Modal Logic (Unaltered) ---
                let employeeFilterCount = 0;
                const employeesByStage = @json($employeesByStage);

                function addEmployeeFilterRow() {
                    employeeFilterCount++;
                    const container = document.getElementById('employee-filters-container');

                    const row = document.createElement('div');
                    row.className = 'row g-3 mb-3 employee-filter-row';
                    row.setAttribute('data-filter-id', employeeFilterCount);

                    row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Production Stage:</label>
                <select class="form-control stage-select" onchange="updateEmployeeDropdown(${employeeFilterCount})">
                    <option value="">Select Stage</option>
                    <option value="design">Design</option>
                    <option value="milling">Milling</option>
                    <option value="printing">3D Printing</option>
                    <option value="sintering">Sintering</option>
                    <option value="pressing">Pressing</option>
                    <option value="finishing">Finishing</option>
                    <option value="qc">QC</option>
                    <option value="delivery">Delivery</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Employee:</label>
                <select class="form-control employee-select" disabled>
                    <option value="">Select Employee</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeEmployeeFilterRow(${employeeFilterCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

                    container.appendChild(row);
                    return employeeFilterCount;
                }

                function updateEmployeeDropdown(filterId) {
                    const stageSelect = document.querySelector(`[data-filter-id="${filterId}"] .stage-select`);
                    const employeeSelect = document.querySelector(`[data-filter-id="${filterId}"] .employee-select`);

                    const selectedStage = stageSelect.value;

                    employeeSelect.innerHTML = '<option value="">Select Employee</option>';

                    if (selectedStage && employeesByStage[selectedStage]) {
                        employeeSelect.disabled = false;

                        employeesByStage[selectedStage].forEach(employee => {
                            const option = document.createElement('option');
                            option.value = employee.id;
                            option.textContent = employee.first_name + ' ' + employee.last_name;
                            employeeSelect.appendChild(option);
                        });
                    } else {
                        employeeSelect.disabled = true;
                    }
                }

                function removeEmployeeFilterRow(filterId) {
                    const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                    if (row) {
                        row.remove();
                    }
                }

                document.getElementById('add-employee-filter').addEventListener('click', addEmployeeFilterRow);

                // Device filter management
                let deviceFilterCount = 0;
                const deviceTypes = @json($devicesByType);
                console.log('Device types loaded:', deviceTypes);

                function addDeviceFilterRow() {
                    deviceFilterCount++;
                    const container = document.getElementById('device-filters-container');

                    const row = document.createElement('div');
                    row.className = 'row g-3 mb-3 device-filter-row';
                    row.setAttribute('data-filter-id', deviceFilterCount);

                    row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Device Type:</label>
                <select class="form-control device-type-select" onchange="updateDeviceDropdown(${deviceFilterCount})">
                    <option value="" disabled selected>Select Device Type</option>
                    ${Object.keys(deviceTypes).filter(type => type !== 'other').map(type => {
                        const typeNames = {
                            'print': '3D Printing',
                            'mill': 'Milling',
                            'sinter': 'Sintering',
                            'press': 'Pressing'
                        };
                        return `<option value="${type}">${typeNames[type] || type}</option>`;
                    }).join('')}
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Device:</label>
                <select class="form-control device-select" disabled>
                    <option value="">Select Device</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger" onclick="removeDeviceFilterRow(${deviceFilterCount})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;

                    container.appendChild(row);
                    return deviceFilterCount;
                }

                function updateDeviceDropdown(filterId) {
                    const typeSelect = document.querySelector(`[data-filter-id="${filterId}"] .device-type-select`);
                    const deviceSelect = document.querySelector(`[data-filter-id="${filterId}"] .device-select`);

                    const selectedType = typeSelect.value;
                    console.log('Selected device type:', selectedType);
                    console.log('Available devices for type:', deviceTypes ? deviceTypes[selectedType] : 'deviceTypes is undefined');

                    deviceSelect.innerHTML = '<option value="" disabled>Select Device</option>';

                    if (selectedType && deviceTypes && deviceTypes[selectedType]) {
                        deviceSelect.disabled = false;

                        const devices = deviceTypes[selectedType];
                        console.log('Devices found:', devices.length);

                        if (devices && devices.length > 0) {
                            devices.forEach(device => {
                                const option = document.createElement('option');
                                option.value = device.id;
                                option.textContent = device.name;
                                deviceSelect.appendChild(option);
                                console.log('Added device:', device.name);
                            });
                        } else {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = 'No devices available';
                            option.disabled = true;
                            deviceSelect.appendChild(option);
                            console.log('No devices in array');
                        }
                    } else {
                        deviceSelect.disabled = true;
                        console.log('Condition failed - selectedType:', selectedType, 'deviceTypes exists:', !!deviceTypes);
                    }
                }

                function removeDeviceFilterRow(filterId) {
                    const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                    if (row) {
                        row.remove();
                    }
                }

                document.getElementById('add-device-filter').addEventListener('click', addDeviceFilterRow);

                function initializeEmployeeFiltersState() {
                    const initialFilters = Array.isArray(window.initialEmployeeFilters) ? window.initialEmployeeFilters : [];
                    if (initialFilters.length > 0) {
                        initialFilters.forEach(filter => {
                            const filterId = addEmployeeFilterRow();
                            const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                            if (!row) {
                                return;
                            }
                            const stageSelect = row.querySelector('.stage-select');
                            const employeeSelect = row.querySelector('.employee-select');
                            if (stageSelect && filter.stage) {
                                stageSelect.value = filter.stage;
                                updateEmployeeDropdown(filterId);
                            }
                            if (employeeSelect && filter.employee) {
                                employeeSelect.value = filter.employee;
                                employeeSelect.disabled = false;
                            }
                        });
                        applyEmployeeFilters();
                    } else {
                        addEmployeeFilterRow();
                    }
                }

                function initializeDeviceFiltersState() {
                    const initialFilters = Array.isArray(window.initialDeviceFilters) ? window.initialDeviceFilters : [];
                    if (initialFilters.length > 0) {
                        initialFilters.forEach(filter => {
                            const filterId = addDeviceFilterRow();
                            const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                            if (!row) {
                                return;
                            }
                            const typeSelect = row.querySelector('.device-type-select');
                            const deviceSelect = row.querySelector('.device-select');
                            if (typeSelect && filter.type) {
                                typeSelect.value = filter.type;
                                updateDeviceDropdown(filterId);
                            }
                            if (deviceSelect && filter.device) {
                                deviceSelect.value = filter.device;
                                deviceSelect.disabled = false;
                            }
                        });
                        applyDeviceFilters();
                    } else {
                        addDeviceFilterRow();
                    }
                }

                function applyEmployeeFilters() {
                    const filterRows = document.querySelectorAll('.employee-filter-row');
                    const hiddenContainer = document.getElementById('hidden-employee-filters');

                    hiddenContainer.innerHTML = '';

                    let activeFilters = 0;
                    let filterIndex = 0;

                    filterRows.forEach(row => {
                        const stageSelect = row.querySelector('.stage-select');
                        const employeeSelect = row.querySelector('.employee-select');

                        if (stageSelect.value && employeeSelect.value) {
                            const stageInput = document.createElement('input');
                            stageInput.type = 'hidden';
                            stageInput.name = `employee_filters[${filterIndex}][stage]`;
                            stageInput.value = stageSelect.value;

                            const employeeInput = document.createElement('input');
                            employeeInput.type = 'hidden';
                            employeeInput.name = `employee_filters[${filterIndex}][employee]`;
                            employeeInput.value = employeeSelect.value;

                            hiddenContainer.appendChild(stageInput);
                            hiddenContainer.appendChild(employeeInput);

                            activeFilters++;
                            filterIndex++;
                        }
                    });

                    const summary = document.getElementById('employees-filter-summary');
                    if (activeFilters > 0) {
                        summary.textContent = `${activeFilters} employee filter(s) applied`;
                        summary.className = 'filter-summary filter-pill active';
                    } else {
                        summary.textContent = 'No employee filters applied';
                        summary.className = 'filter-summary filter-pill muted';
                    }
                }

                function applyDeviceFilters() {
                    const filterRows = document.querySelectorAll('.device-filter-row');
                    const hiddenContainer = document.getElementById('hidden-device-filters');

                    hiddenContainer.innerHTML = '';

                    let activeFilters = 0;
                    let filterIndex = 0;

                    filterRows.forEach(row => {
                        const typeSelect = row.querySelector('.device-type-select');
                        const deviceSelect = row.querySelector('.device-select');

                        if (typeSelect.value && deviceSelect.value) {
                            const typeInput = document.createElement('input');
                            typeInput.type = 'hidden';
                            typeInput.name = `device_filters[${filterIndex}][type]`;
                            typeInput.value = typeSelect.value;

                            const deviceInput = document.createElement('input');
                            deviceInput.type = 'hidden';
                            deviceInput.name = `device_filters[${filterIndex}][device]`;
                            deviceInput.value = deviceSelect.value;

                            hiddenContainer.appendChild(typeInput);
                            hiddenContainer.appendChild(deviceInput);

                            activeFilters++;
                            filterIndex++;
                        }
                    });

                    const summary = document.getElementById('devices-filter-summary');
                    if (activeFilters > 0) {
                        summary.textContent = `${activeFilters} device filter(s) applied`;
                        summary.className = 'filter-summary filter-pill active';
                    } else {
                        summary.textContent = 'All devices included';
                        summary.className = 'filter-summary filter-pill muted';
                    }
                }

                document.getElementById('apply-employee-filters').addEventListener('click', function() {
                    applyEmployeeFilters();
                    $('#employeesFilterModal').modal('hide');
                });

                document.getElementById('apply-device-filters').addEventListener('click', function() {
                    applyDeviceFilters();
                    $('#devicesFilterModal').modal('hide');
                });

            </script>
        @endpush




