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
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important;
            border: unset;
            background-color:unset;
            /* background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #585858), color-stop(100%, #111)); */
            /* background: -webkit-linear-gradient(top, #585858 0%, #111 100%); */

            /* background: linear-gradient(to bottom, #585858 0%, #111 100%); */
        }
        .master-report-container {
            font-family: 'Cairo', sans-serif;
            background: #f8fafc;
            margin-top: 100px;
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
            margin-bottom: 2px;
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
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24) !important;
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
            border-bottom: none;
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
            font-size: 14.3px;
        }

        .master-report-two-line-cell {
            display: block;
            line-height: 1.2;
            max-height: 2.4em;
            overflow: hidden;
            word-break: break-word;
            white-space: normal;
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
    font-family: 'Cairo', sans-serif;
    background: var(--surface);
    padding: 0  28px 28px 28px;
    margin-top: 100px;
}

.master-report-container.sigma-ui-preinit .select2-multiple {
    visibility: hidden;
}

.master-report-container.sigma-table-pending #master-report-table {
    visibility: hidden;
}


.modern-card {
    border-radius: 18px;
    border: 1px solid var(--stroke);
    box-shadow: var(--shadow-lg);
}
        .fa-print, .fa-eye {
           color: white;}
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
            margin-bottom: 2px;
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
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24) !important;
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
            overflow: visible;
            position: relative;
            margin-top: 16px;
            margin-bottom: 24px;
        }

        .filters-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background:linear-gradient(90deg, var(--table-header-elegant) 0%, var(--sigma-accent-light) 100%);
            border-radius: 16px 16px 0 0;
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
            margin-top: 6px;
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

                .filters-card .form-label,
                .filters-card .filter-label {
                    font-family: 'Tajawal', 'Cairo', sans-serif;
                    color: var(--ink);
                }

                .filters-card .form-label i,
                .filters-card .filter-label i {
                    color: rgb(43, 123, 125);
                }

                .filter-label span {
                    position: relative;
                    padding-right: 10px;
                }

                .filter-group.is-active .filter-label {
                    color: #2f80ed;
                }

                .filter-group.is-active .filter-label i {
                    color: #2f80ed;
                }

        .filter-reset-btn {
            border: none;
            background: none;
            color: #667b8991;
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
            gap: 18px;
        }

        .filter-group {
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            gap: 6px;
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
            color: #6b7280;
            height: 42px;
            padding: 10px 12px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            cursor: pointer;
            font-size: 12px;
        }

        .trigger-field:hover {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(28, 124, 84, 0.08);
            color: #4b5563;
        }

        .trigger-field__label {
            flex: 1;
            text-align: left;
            transition: color 0.2s ease;
        }

        .trigger-field__label--active {
            color: #6fa7f2;
            font-weight: 600;
        }

        .trigger-summary {
            font-size: 12px;
            margin-top: 2px;
        }

        .filters-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            width: 100%;
        }

        .filters-summary-card {
            display: flex;
            justify-content: flex-start;
        }

        .filters-applied-block {
            margin-top: 10px;
        }

        .filters-applied-divider {
            height: 1px;
            width: 100%;
            background: #e5e7eb;
            margin: 8px 0 10px;
        }

        .filters-applied-text {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.5;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 12px;
            font-weight: 500;
        }

        .filters-applied-text .filters-applied-label {
            font-weight: bold;
            color: #374151;
            margin-right: 6px;
        }

        .filters-applied-item {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border: 1px solid #dbe3ea;
            border-radius: 999px;
            background: #f6f9fc;
            color: #405062;
        }

        .filters-applied-item + .filters-applied-item::before {
            content: '';
            margin-right: 0;
        }

                .filters-summary-row {
                    display: inline-flex;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: flex-start;
                    gap: 8px;
                }

                .filter-summary-badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    border: 1px solid #e5e7eb;
                    color: #374151;
                    font-weight: 500;
                    padding: 6px 10px;
                    border-radius: 999px;
                    background: #f9fafb;
                    font-size: 12px;
                }

                .filter-summary-label {
                    font-weight: 600;
                    color: #1f2937;
                }

                .filter-badge--date {
                    background: #e8f5ee;
                    border-color: #c7e5d6;
                    color: #2f6f52;
                }

                .filter-badge--people {
                    background: #e8f0fb;
                    border-color: #c9d9f1;
                    color: #2b5f8a;
                }

                .filter-badge--devices {
                    background: #f1e9fb;
                    border-color: #dccff2;
                    color: #5a3e7a;
                }

                .filter-badge--metrics {
                    background: #fbeee3;
                    border-color: #f1d8c2;
                    color: #7a4d2c;
                }

                .filter-badge--workflow {
                    background: #e6f4f5;
                    border-color: #c7e3e5;
                    color: #23696b;
                }

                .filter-badge--other {
                    background: #f2f4f7;
                    border-color: #d9dee5;
                    color: #4b5563;
                }

                .completion-toggle {
                    display: inline-flex;
                    gap: 8px;
                    border: none;
                    background: transparent;
                    padding: 0;
                    width: 100%;
                }

                .completion-option {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 6px;
                    padding: 8px 14px;
                    border-radius: 8px;
                    font-size: 12px;
                    font-weight: 600;
                    color: var(--muted);
                    cursor: pointer;
                    user-select: none;
                    transition: all 0.2s ease;
                    flex: 1;
                    min-height: 36px;
                    background: #f1f4f7;
                    border: 1px solid #e1e6ec;
                }

                .completion-option input {
                    display: none;
                }

                .completion-option.active {
                    background: linear-gradient(135deg, rgba(43, 123, 125, 0.45) 0%, rgba(27, 100, 129, 0.65) 100%);
                    color: #ffffff;
                }

        .generate-btn i {
            margin-right: 6px;
        }

        .generate-btn {
            background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
            background-color: #4d9597 !important;
            border-color: #408385 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24) !important;
            min-height: 48px;
            min-width: 210px;
        }

        .generate-btn:hover {
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            background-color: #4a8d90 !important;
            border-color: #336f71 !important;
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24) !important;
        }

        .generate-btn:active,
        .generate-btn:not(:disabled):not(.disabled):active {
            background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
            background-color: #3d7678 !important;
            border-color: #285f61 !important;
            box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2) !important;
            transform: translateY(0);
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
        #master-report-table thead th,
        .sigma-report-table-container .dataTables_scrollHead th {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            font-size: 13px;
            padding: 12px 10px;
            vertical-align: middle;
            white-space: normal;
            overflow-wrap: normal;
            word-break: normal;
            min-height: 0;
            line-height: 1.2;
            position: relative; /* Default positioning for headers */
            border-radius: 0 !important;
            border-bottom: none !important;
            height: auto !important;
        }

        #master-report-table thead th:first-child,
        .sigma-report-table-container .dataTables_scrollHead th:first-child {
            border-radius: 12px 0 0 0 !important;
        }

        #master-report-table thead th:last-child,
        .sigma-report-table-container .dataTables_scrollHead th:last-child {
            border-radius: 0 12px 0 0 !important;
        }

        #master-report-table thead th:not(:first-child):not(:last-child),
        .sigma-report-table-container .dataTables_scrollHead th:not(:first-child):not(:last-child) {
            border-radius: 0 !important;
        }

        /* Body cells - normal size */
        #master-report-table tbody td {
            font-size: 13px;
            padding: 7px 12px;
            vertical-align: middle;
            min-height: 38px;
            white-space: wrap; /* ADDED: Ensure cells don't wrap */
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
        .dataTables_wrapper.no-footer .dataTables_scrollBody{
            border-bottom: 0;
        }
        /* Light headers */
        #master-report-table thead th.header-light,
        table#master-report-table thead th.header-light,
        .sigma-report-table-container .dataTables_scrollHead th.header-light {
            background-color: transparent !important;
            background: none !important;
            color: #408385 !important;
            border: none !important;
            border-bottom: none !important;
            font-weight: 600;
        }

        .header-light {
            font-family: 'Cairo', sans-serif !important;
            color:  #408385 !important;
        }

        #master-report-table thead th,
        .sigma-report-table-container .dataTables_scrollHead th {
            background-color: #408385 !important;
            background: #408385 !important;
            color: #ffffff !important;
            border: none !important;
        }

        #master-report-table thead th.header-light,
        table#master-report-table thead th.header-light,
        .sigma-report-table-container .dataTables_scrollHead th.header-light,
        .header-light {
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
            width: 100% !important;
            min-width: 100%;
            table-layout: auto;
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

        .sigma-report-table-container .dataTables_scrollHeadInner {
            width: auto !important;
            min-width: 100%;
            padding-right: 0 !important;
            box-sizing: border-box;
        }

        .sigma-report-table-container .dataTables_scrollHeadInner table {
            width: auto !important;
            min-width: 100%;
            margin: 0 !important;
        }

        .sigma-report-table-container .dataTables_scrollBody {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
        }

        #master-report-table_wrapper .dataTables_paginate {
            text-align: left;
            padding-top: 6px;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button {
            font-size: 0.85rem;
            padding: 3px 7px;
            min-width: 26px;
            border-radius: 4px;
            border: 1px solid #cddfe2;
            background-color: #ffffff;
            color: #1f6fb2 !important;
            margin: 0 2px;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button .page-link {
            padding: 3px 7px;
            border-radius: 4px;
            border: 1px solid #cddfe2;
            background-color: #ffffff;
            color: #1f6fb2 !important;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button:hover {
            border-color: #1b5f97;
            background-color: #eef5fb;
            color: #1f6fb2 !important;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button:hover .page-link {
            border-color: #1b5f97;
            background-color: #eef5fb;
            color: #1f6fb2 !important;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button.current,
        #master-report-table_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #dbe9f6 !important;
            border-color: #1f6fb2 !important;
            color: #1f6fb2 !important;
        }

        #master-report-table_wrapper .dataTables_paginate .paginate_button.current .page-link,
        #master-report-table_wrapper .dataTables_paginate .paginate_button.current:hover .page-link {
            background-color: #dbe9f6 !important;
            border-color: #1f6fb2 !important;
            color: #1f6fb2 !important;
        }

        .columns-dropdown {
            margin-right: 12px;
            position: relative;
            z-index: 1000 !important;
        }

        .columns-dropdown .dropdown-menu {
            z-index: 1000 !important;
        }

        .export-buttons {
            display: none;
        }

        .report-results-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: nowrap;
        }

        .report-results-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
            justify-content: flex-end;
            margin-left: auto;
            flex: 0 1 auto;
        }

        .report-results-search input {
            height: 36px;
            min-width: 220px;
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 12px;
            color: #374151;
            background: #fff;
        }

        .report-results-search input::placeholder {
            color: #9aa3af;
        }

        .export-orbit {
            position: relative;
            width: 52px;
            height: 52px;
            z-index: 1100;
        }

        .export-orbit .export-main {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: none;
            background: #2f7c7e;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(47, 124, 126, 0.28);
            letter-spacing: 0.02em;
        }

        .export-orbit .export-option {
            position: absolute;
            width: 52px;
            height: 52px;
            top: 50%;
            left: 50%;
            --x: 0px;
            --y: 0px;
            border-radius: 50%;
            border: none;
            background: #f1f5f9;
            color: #2f7c7e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(0.82);
            transition: opacity 0.09s ease-out, transform 0.09s ease-out;
            pointer-events: none;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
            z-index: 1101;
        }

        .export-orbit.is-open .export-option,
        .export-orbit:hover .export-option {
            opacity: 1;
            transform: translate(calc(-50% + var(--x)), calc(-50% + var(--y))) scale(1);
            pointer-events: auto;
        }

        .export-orbit .export-option.export-email {
            --x: 0px;
            --y: -58px;
        }

        .export-orbit .export-option.export-excel {
            --x: 58px;
            --y: 0px;
        }

        .export-orbit .export-option.export-pdf {
            --x: 0px;
            --y: 58px;
        }

        .export-orbit .export-option.export-print {
            --x: -58px;
            --y: 0px;
        }

        .report-results-totals {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: nowrap;
            min-width: 0;
        }

        .report-total-card {
            padding: 6px 18px !important;
            min-height: 44px;
            min-width: 96px;
        }

        .report-total-inline {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .report-total-inline .report-total-number {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }


        .report-total-card  {
            font-size: 11px;
        }

        #master-report-table tbody td.doctor-name-cell,
        #master-report-table tbody td.patient-name-cell {
            font-weight: 700;
        }

        .columns-dropdown {
            position: relative;
            z-index: 1;
        }

        .report-total-card  {
            font-size: 16px;
            line-height: 1.1;
        }

        .sigma-report-table-container .dataTables_filter {
            display: none;
        }

        @media (max-width: 1200px) {
            .report-results-header,
            .report-results-actions,
            .report-results-totals {
                flex-wrap: wrap;
            }

            .report-results-actions {
                justify-content: flex-start;
                margin-left: 0;
            }
        }

        @media (max-width: 900px) {
            .report-results-search input {
                min-width: 180px;
            }
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

        .sigma-modal--master-report-case-actions {
            position: fixed !important;
            inset: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            padding: 0 !important;
            box-sizing: border-box !important;
            display: none;
            overflow: hidden;
            z-index: 9998;
            background: rgba(15, 23, 42, 0.28);
        }

        .sigma-modal--master-report-case-actions.show {
            display: block !important;
        }

        .sigma-modal--master-report-case-actions .modal-dialog {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            z-index: 9999 !important;
            width: min(500px, calc(100vw - 32px)) !important;
            max-width: 500px !important;
            max-height: calc(100vh - 32px) !important;
            margin: 0 !important;
            transform: translate(-50%, -50%) !important;
            -webkit-transform: translate(-50%, -50%) !important;
            pointer-events: none !important;
        }

        .sigma-modal--master-report-case-actions .modal-content {
            display: flex !important;
            flex-direction: column !important;
            max-height: calc(100vh - 32px) !important;
            overflow: hidden !important;
            pointer-events: auto !important;
        }

        .sigma-modal--master-report-case-actions .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        .sigma-modal--master-report-case-actions .case-jobs-label {
            display: block;
            margin-bottom: 8px !important;
            color: #4d626d;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.08em;
        }

        .sigma-modal--master-report-case-actions .case-notes-label {
            display: block;
            margin-bottom: 8px !important;
            color: #5c6f7a;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .sigma-modal--master-report-case-actions .sigma-case-jobs-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 7px !important;
            width: 100%;
            margin: 0 !important;
        }

        .sigma-modal--master-report-case-actions .sigma-case-job-row {
            background: linear-gradient(180deg, #e6f4f7 0%, #f2fbfd 100%) !important;
            background-color: #e6f4f7 !important;
            display: flex !important;
            align-items: flex-start !important;
            width: 100%;
            min-width: 0;
            padding: 8px 11px !important;
            margin: 0 !important;
            border: 1px solid #d7e1e6;
            border-radius: 10px;
            color: #294450;
            font-size: 1rem;
            line-height: 1.45 !important;
            overflow: hidden !important;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
            box-shadow: 0 2px 5px rgba(41, 68, 80, 0.07) !important;
        }

        .sigma-modal--master-report-case-actions .sigma-case-job-row::-webkit-scrollbar {
            display: none;
        }

        .sigma-modal--master-report-case-actions .sigma-case-job-primary {
            display: block;
            width: 100%;
            color: #294450;
            font-weight: 600;
            line-height: 1.45 !important;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .sigma-modal--master-report-case-actions .scrollable-content {
            overflow-x: hidden;
        }

        @media (max-width: 767.98px) {
            .sigma-modal--master-report-case-actions .sigma-case-job-row {
                font-size: 0.9rem;
                padding: 8px 10px !important;
            }
        }

        .sigma-modal--report-devices-filter select option:disabled,
        .sigma-modal--report-employees-filter select option:disabled {
            color: #9aa3af !important;
            background: #eef1f5 !important;
            opacity: 0.55;
        }

        .sigma-modal--report-devices-filter select option:disabled,
        .sigma-modal--report-employees-filter select option:disabled {
            font-weight: 600;
        }

        .filter-group.is-disabled {
            opacity: 0.6;
        }

        .filter-group.is-disabled .select2-container--default .select2-selection--multiple,
        .filter-group.is-disabled .modern-select {
            background: #eef1f5 !important;
            cursor: not-allowed;
        }

        .filter-group.is-disabled .filter-label {
            color: #9aa3af;
        }

        html.master-report-case-modal-open,
        body.master-report-case-modal-open {
            overflow: hidden !important;
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


<div class="master-report-container sigma-ui-preinit sigma-table-pending">
    <div class="modern-card filters-card">
        <form class="modern-form" method="GET" action="{{route('master-report')}}" id="master-report-form" data-loading-screen-text="Processing...">
            <input type="hidden" name="generate_report" value="1">
            <div id="hidden-employee-filters"></div>
            <div id="hidden-device-filters"></div>
            <script>
                window.initialMaterialTypes = @json(request('material_type', []));
                window.initialEmployeeFilters = @json(array_values((array) request('employee_filters', [])));
                window.initialDeviceFilters = @json(array_values((array) request('device_filters', [])));
            </script>

            <div class="form-section basic-filters filters-surface">
                @php
                    $defaultFrom = \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d');
                    $defaultTo = \Carbon\Carbon::now()->format('Y-m-d');
                @endphp
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="form-label filter-label" for="master_from">
                            <span><i class="fas fa-calendar-alt"></i> From</span>
                            <button type="button" class="filter-reset-btn" data-reset="date-from">Reset</button>
                        </label>
                        <x-report-datetimepicker
                            name="from"
                            id="master_from"
                            :value="request('from', $from)"
                            mode="date"
                            :required="true"
                            :dataDefault="$defaultFrom"
                            :mutedYearDisplay="true"
                        />
                    </div>
                    <div class="filter-group">
                        <label class="form-label filter-label" for="master_to">
                            <span><i class="fas fa-calendar-alt"></i> To</span>
                            <button type="button" class="filter-reset-btn" data-reset="date-to">Reset</button>
                        </label>
                        <x-report-datetimepicker
                            name="to"
                            id="master_to"
                            :value="request('to', $to)"
                            mode="date"
                            :required="true"
                            :dataDefault="$defaultTo"
                            :mutedYearDisplay="true"
                        />
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-user-md"></i> Doctor</span>
                            <button type="button" class="filter-reset-btn" data-reset="doctor">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="doctor[]" id="doctor" data-default="all">
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
                        <select class="modern-select select2-multiple" multiple name="material[]" id="material" data-default="all">
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
                        <select class="modern-select select2-multiple" multiple name="job_type[]" id="job_type" data-default="all">
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
                        <select class="modern-select select2-multiple" multiple name="material_type[]" id="material_type" data-default="all">
                            <option value="all">All Material Types</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-exclamation-triangle"></i> Failure Type</span>
                            <button type="button" class="filter-reset-btn" data-reset="failure-type">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="failure_type[]" id="failure_type" data-default="all">
                            <option value="all" {{in_array('all', (array)request('failure_type', ['all'])) ? 'selected' : ''}}>All Failure Types</option>
                            @foreach($failureCauses as $failureCause)
                                <option value="{{$failureCause->id}}" {{in_array($failureCause->id, (array)request('failure_type', [])) ? 'selected' : ''}}>
                                    {{$failureCause->text ?? $failureCause->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-plug"></i> Abutments</span>
                            <button type="button" class="filter-reset-btn" data-reset="abutments">Reset</button>
                        </label>
                        <select class="modern-select select2-multiple" multiple name="abutments[]" id="abutments" data-default="all">
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
                        <select class="modern-select select2-multiple" multiple name="implants[]" id="implants" data-default="all">
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
                        <select class="modern-select select2-multiple" multiple name="status[]" id="status" data-default="all">
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
                                   placeholder="From JOD" value="{{request('amount_from')}}" min="0" step="1" inputmode="decimal" data-precision="1" data-default="">
                            <input type="number" class="modern-input" name="amount_to" id="amount_to"
                                   placeholder="To JOD" value="{{request('amount_to')}}" min="0" step="1" inputmode="decimal" data-precision="1" data-default="">
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
                                   placeholder="From" value="{{request('units_from')}}" min="0" step="1" inputmode="numeric" data-default="">
                            <input type="number" class="modern-input" name="units_to" id="units_to"
                                   placeholder="To" value="{{request('units_to')}}" min="0" step="1" inputmode="numeric" data-default="">
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
                        @php
                            $initialEmployeeFilterCount = collect((array) request('employee_filters', request('employees', [])))
                                ->filter(function ($filter) {
                                    return !empty($filter['stage']) && !empty($filter['employee_id'] ?? $filter['employee'] ?? null);
                                })
                                ->count();
                        @endphp
                        <button type="button" class="trigger-field" data-toggle="modal" data-target="#employeesFilterModal">
                            <span id="employees-filter-trigger-label" class="trigger-field__label{{ $initialEmployeeFilterCount > 0 ? ' trigger-field__label--active' : '' }}">{{ $initialEmployeeFilterCount > 0 ? $initialEmployeeFilterCount . ' ' . ($initialEmployeeFilterCount === 1 ? 'Employee' : 'Employees') : 'Configure Employee Filters' }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div id="employees-filter-summary" class="filter-summary filter-pill muted trigger-summary d-none">No employee filters applied</div>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-microchip"></i> Devices Filter</span>
                            <button type="button" class="filter-reset-btn" data-reset="devices">Reset</button>
                        </label>
                        @php
                            $initialDeviceFilterCount = collect((array) request('device_filters', request('devices', [])))
                                ->filter(function ($filter) {
                                    return !empty($filter['type'] ?? $filter['stage'] ?? null) && !empty($filter['device_id'] ?? $filter['device'] ?? null);
                                })
                                ->count();
                        @endphp
                        <button type="button" class="trigger-field" data-toggle="modal" data-target="#devicesFilterModal">
                            <span id="devices-filter-trigger-label" class="trigger-field__label{{ $initialDeviceFilterCount > 0 ? ' trigger-field__label--active' : '' }}">{{ $initialDeviceFilterCount > 0 ? $initialDeviceFilterCount . ' ' . ($initialDeviceFilterCount === 1 ? 'Device' : 'Devices') : 'Configure Device Filters' }}</span>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div id="devices-filter-summary" class="filter-summary filter-pill muted trigger-summary d-none">All devices included</div>
                    </div>

                    <div class="filter-group">
                        <label class="form-label filter-label">
                            <span><i class="fas fa-check-circle"></i> Case Completion</span>
                            <button type="button" class="filter-reset-btn" data-reset="completion">Reset</button>
                        </label>
                        @php
                            $completionSelections = request('show_completed', ['completed', 'in_progress']);
                            if (!is_array($completionSelections)) {
                                $completionSelections = [$completionSelections];
                            }
                            if (empty($completionSelections) || in_array('all', $completionSelections, true)) {
                                $completionSelections = ['completed', 'in_progress'];
                            }
                            $completionSelections = array_map('strval', $completionSelections);
                        @endphp
                        <div class="completion-toggle" id="completion_toggle" data-default="completed,in_progress">
                            <label class="completion-option">
                                <input type="checkbox" name="show_completed[]" value="completed"
                                       {{ in_array('completed', $completionSelections, true) ? 'checked' : '' }}>
                                <span>Completed</span>
                            </label>
                            <label class="completion-option">
                                <input type="checkbox" name="show_completed[]" value="in_progress"
                                       {{ in_array('in_progress', $completionSelections, true) ? 'checked' : '' }}>
                                <span>In Progress</span>
                            </label>
                        </div>
                    </div>
                </div>

                @php
                    $clientMap = $clients->pluck('name', 'id');
                    $materialMap = $materials->pluck('name', 'id');
                    $jobTypeMap = $jobTypes->pluck('name', 'id');
                    $failureCauseMap = $failureCauses->mapWithKeys(function ($failureCause) {
                        return [$failureCause->id => ($failureCause->text ?? $failureCause->name)];
                    });
                    $abutmentMap = $abutments->pluck('name', 'id');
                    $implantMap = $implants->pluck('name', 'id');
                    $employeeMap = collect($employeesByStage)
                        ->flatMap(function ($stageEmployees) {
                            return collect($stageEmployees);
                        })
                        ->unique('id')
                        ->mapWithKeys(function ($employee) {
                            return [$employee->id => trim((string) ($employee->first_name ?: $employee->last_name ?: $employee->id))];
                        });
                    $deviceMap = collect($devicesByType)
                        ->flatMap(function ($devices) {
                            return collect($devices);
                        })
                        ->unique('id')
                        ->mapWithKeys(function ($device) {
                            return [$device->id => $device->name];
                        });
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

                    $joinLogicalValues = function ($values, $map = null, string $glue = ' OR ') {
                        $normalized = collect((array) $values)
                            ->filter(function ($value) {
                                return $value !== null && $value !== '' && $value !== 'all';
                            })
                            ->map(function ($value) use ($map) {
                                return $map ? ($map[$value] ?? $value) : $value;
                            })
                            ->filter()
                            ->unique()
                            ->values();

                        return $normalized->implode($glue);
                    };

                    $summaryParts = [];
                    $appendBracketedSummary = function (string $label, $values, $map = null, string $glue = ' OR ') use (&$summaryParts, $joinLogicalValues) {
                        $names = $joinLogicalValues($values, $map, $glue);
                        if ($names !== '') {
                            $summaryParts[] = '[' . $label . ': ' . $names . ']';
                        }
                    };

                    $doctorSelections = array_filter((array) request('doctor', []), function ($value) {
                        return $value !== null && $value !== '' && $value !== 'all';
                    });
                    if (!empty($doctorSelections)) {
                        $appendBracketedSummary('Doctors', $doctorSelections, $clientMap);
                    }

                    $appendBracketedSummary('Material', request('material', []), $materialMap);
                    $appendBracketedSummary('Job Type', request('job_type', []), $jobTypeMap);
                    $appendBracketedSummary('Failure Type', request('failure_type', []), $failureCauseMap);
                    $appendBracketedSummary('Abutment', request('abutments', []), $abutmentMap);
                    $appendBracketedSummary('Implant', request('implants', []), $implantMap);

                    $materialTypeIds = collect((array) request('material_type', []))
                        ->filter(function ($value) {
                            return $value !== null && $value !== '' && $value !== 'all';
                        })
                        ->values();
                    if ($materialTypeIds->isNotEmpty()) {
                        $typeNames = \App\Type::query()
                            ->whereIn('id', $materialTypeIds)
                            ->pluck('name')
                            ->unique()
                            ->implode(' OR ');
                        if ($typeNames !== '') {
                            $summaryParts[] = '[Material Type: ' . $typeNames . ']';
                        }
                    }

                    $statusFilters = collect((array) request('status', []))
                        ->filter(function ($value) {
                            return $value !== null && $value !== '' && $value !== 'all';
                        })
                        ->map(function ($value) use ($stageLabels) {
                            return $stageLabels[$value] ?? $value;
                        })
                        ->filter()
                        ->unique()
                        ->values();
                    if ($statusFilters->isNotEmpty()) {
                        $summaryParts[] = '[Workflow: ' . $statusFilters->implode(' OR ') . ']';
                    }

                    $completionSelections = request('show_completed', ['completed', 'in_progress']);
                    if (!is_array($completionSelections)) {
                        $completionSelections = [$completionSelections];
                    }
                    $completionSelections = array_values(array_unique(array_filter($completionSelections, function ($value) {
                        return $value !== null && $value !== '' && $value !== 'all';
                    })));
                    $completionLabels = [];
                    if (in_array('completed', $completionSelections, true)) {
                        $completionLabels[] = 'Completed';
                    }
                    if (in_array('in_progress', $completionSelections, true)) {
                        $completionLabels[] = 'In Progress';
                    }
                    // Show completion label only when one mode is specifically selected.
                    if (count($completionLabels) === 1) {
                        $summaryParts[] = $completionLabels[0];
                    }

                    $amountFrom = request('amount_from');
                    $amountTo = request('amount_to');
                    if (($amountFrom !== null && $amountFrom !== '') || ($amountTo !== null && $amountTo !== '')) {
                        if ($amountFrom !== null && $amountFrom !== '' && $amountTo !== null && $amountTo !== '') {
                            $amountLabel = $amountFrom . ' - ' . $amountTo . ' JOD';
                        } elseif ($amountFrom !== null && $amountFrom !== '') {
                            $amountLabel = $amountFrom . ' JOD';
                        } else {
                            $amountLabel = $amountTo . ' JOD';
                        }
                        $summaryParts[] = '[Amount: ' . $amountLabel . ']';
                    }

                    $unitsFrom = request('units_from');
                    $unitsTo = request('units_to');
                    if (($unitsFrom !== null && $unitsFrom !== '') || ($unitsTo !== null && $unitsTo !== '')) {
                        if ($unitsFrom !== null && $unitsFrom !== '' && $unitsTo !== null && $unitsTo !== '') {
                            $unitsLabel = $unitsFrom . ' - ' . $unitsTo . ' units';
                        } elseif ($unitsFrom !== null && $unitsFrom !== '') {
                            $unitsLabel = $unitsFrom . ' units';
                        } else {
                            $unitsLabel = $unitsTo . ' units';
                        }
                        $summaryParts[] = '[Units: ' . $unitsLabel . ']';
                    }

                    $employeeSummaryFilters = collect((array) request('employee_filters', request('employees', [])))
                        ->filter(function ($filter) {
                            return !empty($filter['stage'])
                                && !empty($filter['employee_id'] ?? $filter['employee'] ?? null);
                        })
                        ->values();
                    if ($employeeSummaryFilters->isNotEmpty()) {
                        $employeeStageLabels = [
                            'design' => 'Design',
                            'milling' => 'Milling',
                            'printing' => '3D Printing',
                            'sintering' => 'Sintering',
                            'pressing' => 'Pressing',
                            'finishing' => 'Finishing',
                            'qc' => 'QC',
                            'delivery' => 'Delivery',
                        ];

                        $employeePairs = $employeeSummaryFilters
                            ->map(function ($filter) use ($employeeMap, $employeeStageLabels) {
                                $stageKey = (string) ($filter['stage'] ?? '');
                                $employeeId = $filter['employee_id'] ?? $filter['employee'] ?? null;
                                $employeeName = $employeeMap[(string) $employeeId] ?? null;

                                if ($stageKey === '' || !$employeeName) {
                                    return null;
                                }

                                $stageLabel = $employeeStageLabels[$stageKey] ?? ucfirst($stageKey);
                                return $stageLabel . ':' . $employeeName;
                            })
                            ->filter()
                            ->unique()
                            ->values();

                        if ($employeePairs->isNotEmpty()) {
                            $summaryParts[] = '[Emp: ' . $employeePairs->implode(' AND ') . ']';
                        }
                    }

                    $deviceSummaryFilters = collect((array) request('device_filters', request('devices', [])))
                        ->filter(function ($filter) {
                            return !empty($filter['type'] ?? $filter['stage'] ?? null)
                                && !empty($filter['device_id'] ?? $filter['device'] ?? null);
                        })
                        ->values();
                    if ($deviceSummaryFilters->isNotEmpty()) {
                        $appendBracketedSummary('Devices', $deviceSummaryFilters->map(function ($filter) {
                            return $filter['device_id'] ?? $filter['device'] ?? null;
                        })->all(), $deviceMap);
                    }

                    $summaryTextParts = collect($summaryParts)->filter()->values();
                @endphp

                @if(!empty($summaryParts))
                    <div class="filters-applied-block">
                        <div class="filters-applied-divider"></div>
                        <div class="filters-applied-text">
                            @foreach($summaryTextParts as $summaryPart)
                                <span class="filters-applied-item">{{ $summaryPart }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="filters-footer">
                    <button type="submit" class="modern-btn btn-primary generate-btn">
                        <i class="fas fa-chart-line"></i>
                        Generate Report
                    </button>
                    <button type="button" class="filters-master-reset" id="filters-master-reset">Reset All</button>
                </div>
            </div>
        </form>
    </div>



    @if($cases->count() > 0)
        <div class="modern-card" style="margin-top: 16px;">
            @php
                $totalCases = $cases->count();
                $totalAmount = $cases->sum(function ($case) {
                    return $case->invoice->amount ?? 0;
                });
                $totalUnits = 0;
                foreach ($cases as $case) {
                    foreach ($case->jobs as $job) {
                        $unitNum = trim((string) ($job->unit_num ?? ''));
                        if ($unitNum === '') {
                            $totalUnits += 1;
                            continue;
                        }
                        if (str_contains($unitNum, ',') || str_contains($unitNum, ' ')) {
                            $parts = preg_split('/[,\s]+/', $unitNum);
                            $count = count(array_filter(array_map('trim', $parts)));
                            $totalUnits += max(1, $count);
                        } else {
                            $totalUnits += 1;
                        }
                    }
                }
            @endphp
            <div class="card-header" style="border-bottom: 1px solid #e2e8f0; padding: 12px 24px; background: white;">
                <div class="report-results-header">
                    <div class="report-results-totals">
                        <div class="materials-total-card report-total-card">
                            <div class="report-total-inline">
                                <span class="report-total-number">{{ number_format($totalCases) }}</span>
                                <span class="report-total-label">Cases</span>
                            </div>
                        </div>
                        <div class="materials-total-card report-total-card">
                            <div class="report-total-inline">
                                <span class="report-total-number">{{ number_format($totalUnits) }}</span>
                                <span class="report-total-label">Units</span>
                            </div>
                        </div>

                        <div class="materials-total-card report-total-card">
                            <div class="report-total-inline">
                                <span class="report-total-number">{{ number_format($totalAmount) }}</span>
                                <span class="report-total-label">JOD</span>
                            </div>
                        </div>
                    </div>
                    <div class="report-results-actions">
                        <div class="report-results-search">
                            <input type="text" id="master-report-search" placeholder="Search cases..." autocomplete="off">
                        </div>
                        <div class="dropdown columns-dropdown">
                            <button class="btn" type="button" id="columnVisibilityDropdown" aria-haspopup="true" aria-expanded="false" style="background: white; border: 1px solid #e5e7eb; color: #374151; padding: 8px 12px; font-size: 13px; border-radius: 50%; transition: all 0.2s ease; width: 40px; height: 40px;">
                                <i class="fas fa-columns"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right p-3" aria-labelledby="columnVisibilityDropdown" style="min-width: 280px; max-height: 500px; overflow-y: auto;">
                                <h6 class="dropdown-header">Basic Information</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-case-id" data-column="0">
                                    <label class="form-check-label" for="col-case-id">Case ID</label>
                                </div>
                                <div class="form-check is-locked">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-doctor" data-column="1" checked disabled data-locked="true">
                                    <label class="form-check-label text-muted" for="col-doctor">Doctor (required)</label>
                                </div>
                                <div class="form-check is-locked">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-patient" data-column="2" checked disabled data-locked="true">
                                    <label class="form-check-label text-muted" for="col-patient">Patient (required)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-material" data-column="3" checked>
                                    <label class="form-check-label" for="col-material">Material</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-job-type" data-column="4" checked>
                                    <label class="form-check-label" for="col-job-type">Job Type</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-created" data-column="5">
                                    <label class="form-check-label" for="col-created">Created Date</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-delivery" data-column="6">
                                    <label class="form-check-label" for="col-delivery">Actual delivery D.</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-initial-delivery" data-column="7">
                                    <label class="form-check-label" for="col-initial-delivery">Initial delivery D.</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Devices</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle-group" type="checkbox" id="col-devices-all" data-columns="8,9,10,11">
                                    <label class="form-check-label" for="col-devices-all">All Devices</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-mill-device" data-column="8">
                                    <label class="form-check-label" for="col-mill-device">Mill Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-print-device" data-column="9">
                                    <label class="form-check-label" for="col-print-device">3D Print Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-sinter-device" data-column="10">
                                    <label class="form-check-label" for="col-sinter-device">Sinter Device</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-press-device" data-column="11">
                                    <label class="form-check-label" for="col-press-device">Press Device</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Employees</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle-group" type="checkbox" id="col-employees-all" data-columns="12,13,14,15,16,17,18,19">
                                    <label class="form-check-label" for="col-employees-all">All Employees</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-designer" data-column="12">
                                    <label class="form-check-label" for="col-designer">Designer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-miller" data-column="13">
                                    <label class="form-check-label" for="col-miller">Miller</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-printer" data-column="14">
                                    <label class="form-check-label" for="col-printer">3D Printer</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-sintered" data-column="15">
                                    <label class="form-check-label" for="col-sintered">Sintered</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-presser" data-column="16">
                                    <label class="form-check-label" for="col-presser">Presser</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-finisher" data-column="17">
                                    <label class="form-check-label" for="col-finisher">Finisher</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-qc" data-column="18">
                                    <label class="form-check-label" for="col-qc">QC</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-delivery-emp" data-column="19">
                                    <label class="form-check-label" for="col-delivery-emp">Delivery</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Status & Amount</h6>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-status" data-column="20" checked>
                                    <label class="form-check-label" for="col-status">Status</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-amount" data-column="21" checked>
                                    <label class="form-check-label" for="col-amount">Amount</label>
                                </div>
                                <div class="dropdown-divider"></div>
                                <button class="btn btn-sm btn-primary btn-block" id="selectAllColumns" type="button">Select All</button>
                                <button class="btn btn-sm btn-secondary btn-block mt-1" id="deselectAllColumns" type="button">Deselect All</button>
                            </div>
                        </div>
                        <div class="export-orbit" aria-label="Export options">
                            <button type="button" class="export-main">Export</button>
                            <button type="button" class="export-option export-email export-action" data-export="email" title="Email Report">
                                <i class="fas fa-envelope"></i>
                            </button>
                            <button type="button" class="export-option export-excel export-action" data-export="excel" title="Export Excel">
                                <i class="fas fa-file-excel"></i>
                            </button>
                            <button type="button" class="export-option export-pdf export-action" data-export="pdf" title="Export PDF">
                                <i class="fas fa-file-pdf"></i>
                            </button>
                            <button type="button" class="export-option export-print export-action" data-export="print" title="Print">
                                <i class="fas fa-print" style="color: #2f7c7e;font-size: 14px"></i>
                            </button>
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
                        <th class="header-light text-center">Actual delivery D.</th>
                        <th class="header-light text-center">Initial delivery D.</th>
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
                    @foreach($cases as $case)
                        @php
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
                            }

                            // Get unique values and format
                            $materialsStr = $materials->unique()->filter()->implode(', ') ?: '-';
                            $jobTypesStr = $jobTypes->unique()->filter()->implode(', ') ?: '-';
                            $stageDevices = $case->master_report_stage_devices ?? [];
                            $millingDevicesStr = !empty($stageDevices[2]) ? $stageDevices[2] : '-';
                            $printingDevicesStr = !empty($stageDevices[3]) ? $stageDevices[3] : '-';
                            $sinteringDevicesStr = !empty($stageDevices[4]) ? $stageDevices[4] : '-';
                            $pressingDevicesStr = !empty($stageDevices[5]) ? $stageDevices[5] : '-';

                            $formatTwoLineList = function (string $value) {
                                if ($value === '-') {
                                    return '-';
                                }

                                $parts = collect(explode(',', $value))
                                    ->map(function ($part) {
                                        return trim((string) $part);
                                    })
                                    ->filter()
                                    ->values();

                                if ($parts->count() <= 2) {
                                    return e($parts->implode(', '));
                                }

                                $firstLine = e($parts->slice(0, 2)->implode(', '));
                                $secondLine = e($parts->slice(2)->implode(', '));

                                return $firstLine . '<br>' . $secondLine;
                            };

                            $materialsDisplay = $formatTwoLineList($materialsStr);
                            $jobTypesDisplay = $formatTwoLineList($jobTypesStr);

                            $resolveLatestStageEmployeeInitials = function (int $baseStage) use ($case) {
                                $latestLog = $case->caseLogs
                                    ->filter(function ($log) use ($baseStage) {
                                        $stageValue = (float) $log->stage;

                                        return $stageValue >= $baseStage && $stageValue < ($baseStage + 1);
                                    })
                                    ->sort(function ($left, $right) {
                                        $leftStage = (float) $left->stage;
                                        $rightStage = (float) $right->stage;

                                        if ($leftStage !== $rightStage) {
                                            return $leftStage < $rightStage ? 1 : -1;
                                        }

                                        $leftCompletion = (int) ($left->is_completion ?? 0);
                                        $rightCompletion = (int) ($right->is_completion ?? 0);

                                        if ($leftCompletion !== $rightCompletion) {
                                            return $leftCompletion < $rightCompletion ? 1 : -1;
                                        }

                                        $leftCreatedAt = $left->created_at
                                            ? \Carbon\Carbon::parse($left->created_at)->getTimestamp()
                                            : 0;
                                        $rightCreatedAt = $right->created_at
                                            ? \Carbon\Carbon::parse($right->created_at)->getTimestamp()
                                            : 0;

                                        if ($leftCreatedAt !== $rightCreatedAt) {
                                            return $leftCreatedAt < $rightCreatedAt ? 1 : -1;
                                        }

                                        return (int) $left->id < (int) $right->id ? 1 : -1;
                                    })
                                    ->first();

                                return $latestLog?->user?->name_initials ?? '-';
                            };

                            $stageEmployees = [
                                1 => $resolveLatestStageEmployeeInitials(1),
                                2 => $resolveLatestStageEmployeeInitials(2),
                                3 => $resolveLatestStageEmployeeInitials(3),
                                4 => $resolveLatestStageEmployeeInitials(4),
                                5 => $resolveLatestStageEmployeeInitials(5),
                                6 => $resolveLatestStageEmployeeInitials(6),
                                7 => $resolveLatestStageEmployeeInitials(7),
                                8 => $resolveLatestStageEmployeeInitials(8),
                            ];

                            // Format dates
                            $createdAt = $case->created_at ? \Carbon\Carbon::parse($case->created_at) : null;
                            $createdDate = $createdAt ? $createdAt->format('d M, Y') : '-';
                            $createdTime = $createdAt ? $createdAt->format('h:i A') : '-';

                            $actualDeliveryAt = $case->actual_delivery_date ? \Carbon\Carbon::parse($case->actual_delivery_date) : null;
                            $actualDeliveryDate = $actualDeliveryAt ? $actualDeliveryAt->format('d M, Y') : '-';
                            $actualDeliveryTime = $actualDeliveryAt ? $actualDeliveryAt->format('h:i A') : '-';

                            $initialDeliveryAt = $case->initial_delivery_date ? \Carbon\Carbon::parse($case->initial_delivery_date) : null;
                            $initialDeliveryDate = $initialDeliveryAt ? $initialDeliveryAt->format('d M, Y') : '-';
                            $initialDeliveryTime = $initialDeliveryAt ? $initialDeliveryAt->format('h:i A') : '-';

                            $caseStatus = (string) $case->status();
                        @endphp
                        <tr class="master-report-row clickable" data-case-id="{{$case->id}}">
                            <td class="text-left"><strong>{{$case->id}}</strong></td>
                            <td class="text-left doctor-name-cell">{{$case->client->name ?? 'N/A'}}</td>
                            <td class="text-left patient-name-cell">{{$case->patient_name}}</td>
                            <td class="text-center"><span class="master-report-two-line-cell">{!! $materialsDisplay !!}</span></td>
                            <td class="text-center"><span class="master-report-two-line-cell">{!! $jobTypesDisplay !!}</span></td>
                            <td class="text-center">
                                <div class="report-date-line">{{$createdDate}}</div>
                                <div class="report-time-line">{{$createdTime}}</div>
                            </td>
                            <td class="text-center">
                                <div class="report-date-line">{{$actualDeliveryDate}}</div>
                                <div class="report-time-line">{{$actualDeliveryTime}}</div>
                            </td>
                            <td class="text-center">
                                <div class="report-date-line">{{$initialDeliveryDate}}</div>
                                <div class="report-time-line">{{$initialDeliveryTime}}</div>
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
                </table>
            </div>
        </div>

        @foreach($cases  as $case)
            <div class="modal sigma-modal--cases-index-actions sigma-modal--master-report-case-actions" tabindex="-1" role="dialog"
                 id="actionsDialog{{$case->id}}" data-backdrop="false" data-keyboard="true">

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
                                        <label class="case-completion-dialog-label case-jobs-label"><b>Jobs:</b></label>
                                        <div class="sigma-case-jobs-list">


                                        @php
                                            // Determine case's current stage (first job's stage)
                                            $currentStage = $case->jobs->first()->stage ?? null;
                                        @endphp

                                        @foreach( $case->jobs as $job)
                                            @php
                                                $unit = explode(', ',$job->unit_num);
                                                // Only show jobs that go through the current stage
                                                $showJob = $job->goesThroughStage($currentStage);
                                                $jobTypeName = $job->jobType->name ?? "No Job Type";
                                                $materialName = $job->material->name ?? "no material";
                                                $colorLabel = $job->color == '0' ? '' : $job->color;
                                                $styleLabel = $job->style == 'None' ? '' : $job->style;
                                                $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? 'Implant Type: ' . $job->implantR->name : '';
                                                $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? 'Abutment Type: ' . $job->abutmentR->name : '';
                                            @endphp

                                            @if($showJob)
                                                <div class="job-info-for-tooltip" style="display: none;">
                                                    <span class="job-type">{{ $job->jobType->name ?? "No Job Type" }}</span>
                                                    <span class="job-material">{{ $job->material->name ?? "no material" }}</span>
                                                    <span class="job-units">{{ $job->unit_num }}</span>
                                                </div>
                                                @php
                                                    $fullJobParts = array_values(array_filter([
                                                        trim((string) $job->unit_num),
                                                        trim((string) $jobTypeName),
                                                        trim((string) $materialName),
                                                        trim((string) $colorLabel),
                                                        trim((string) $styleLabel),
                                                        trim((string) $implantLabel),
                                                        trim((string) $abutmentLabel),
                                                    ], function ($value) {
                                                        return $value !== '';
                                                    }));
                                                @endphp
                                                <div class="sigma-case-job-row">
                                                    <span class="sigma-case-job-primary">{{ implode(' - ', $fullJobParts) }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                                @if(count($case->notes)>0)
                                    <hr>
                                    <label class="case-completion-dialog-label case-notes-label"><b>Notes:</b></label><br>
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
                                           class="btn btn-info sigma-action-btn"><span class="btn-icon"><i
                                                    class="fas fa-print"></i></span><span class="btn-text">Print Voucher</span></a>
                                        <a href="{{route('view-case',['id' =>$case->id ,'stage' =>-2 ])}}"
                                           class="btn btn-info sigma-action-btn"><span class="btn-icon"><i
                                                    class="far fa-file-alt"></i></span><span
                                                    class="btn-text">View</span></a>
                                    </div>

                                    <div class="sigma-actions-grid">
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                                            @if(!$case->locked)
                                                <a href="{{route('lock-case',$case->id)}}"
                                                   class="btn btn-dark sigma-action-btn"><span class="btn-icon"><i
                                                            class="fas fa-lock"></i></span><span
                                                            class="btn-text">Lock</span></a>
                                            @else
                                                <a href="{{route('unlock-case',$case->id)}}"
                                                   class="btn btn-dark sigma-action-btn"><span class="btn-icon"><i
                                                            class="fas fa-lock-open"></i></span><span
                                                            class="btn-text">Unlock</span></a>
                                            @endif
                                        @endif

                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 131))
                                            <a href="{{route('delete-case',$case->id)}}" onclick="caseDelConfirmation(event)"
                                               class="btn btn-danger sigma-action-btn" data-clientName="{{ $case->client->name ?? '' }}" data-patientName="{{ $case->patient_name ?? '' }}">
                                                <span class="btn-icon"><i class="fas fa-trash"></i></span><span class="btn-text">Delete</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 124))
                                            <a href="{{route('reject-case',$case->id)}}" class="btn btn-outline-danger sigma-action-btn">
                                                <span class="btn-icon"><i class="fas fa-times"></i></span><span class="btn-text">Reject case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 125))
                                            <a href="{{route('repeat-case',$case->id)}}" class="btn btn-outline-warning sigma-action-btn">
                                                <span class="btn-icon"><i class="fas fa-undo"></i></span><span class="btn-text">Repeat case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 126))
                                            <a href="{{route('modify-case',$case->id)}}" class="btn btn-outline-warning sigma-action-btn">
                                                <span class="btn-icon"><i class="fas fa-pen"></i></span><span class="btn-text">Modify case</span>
                                            </a>
                                        @endif
                                        @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 127))
                                            <a href="{{route('edit-case-view',$case->id)}}" class="btn btn-warning sigma-action-btn">
                                                <span class="btn-icon"><i class="fas fa-pen-to-square"></i></span><span class="btn-text">Edit</span>
                                            </a>
                                        @endif
                                    </div>

                                    <div class="sigma-actions-row sigma-actions-row--cancel">
                                        <button type="button" class="btn btn-secondary sigma-action-btn" data-dismiss="modal">Cancel</button>
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
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/fixedcolumns/4.0.2/js/dataTables.fixedColumns.min.js"></script>

            <script>
                // Initialize completion toggle (completed/in-progress; at least one selected)
                function initializeCompletionToggle() {
                    const $toggle = $('#completion_toggle');
                    const $options = $toggle.find('input[type="checkbox"]');
                    if ($options.length === 0) {
                        return;
                    }

                    function applyCompletionSelection(selectedValues) {
                        const normalized = (selectedValues || []).map(value => String(value));
                        $options.each(function() {
                            const isChecked = normalized.includes(String(this.value));
                            $(this).prop('checked', isChecked);
                            $(this).closest('.completion-option').toggleClass('active', isChecked);
                        });
                        if ($options.filter(':checked').length === 0) {
                            $options.first().prop('checked', true);
                            $options.first().closest('.completion-option').addClass('active');
                        }
                        updateFilterIndicators();
                    }

                    $options.off('change.completion').on('change.completion', function() {
                        const selected = $options.filter(':checked').map(function() { return this.value; }).get();
                        applyCompletionSelection(selected);
                    });

                    const initialSelected = $options.filter(':checked').map(function() { return this.value; }).get();
                    applyCompletionSelection(initialSelected);
                }

                function setCompletionValue(values) {
                    const $toggle = $('#completion_toggle');
                    const $options = $toggle.find('input[type="checkbox"]');
                    const normalized = Array.isArray(values) ? values.map(String) : [String(values)];
                    const applied = normalized.length === 0 ? ['completed', 'in_progress'] : normalized;
                    $options.each(function() {
                        const isChecked = applied.includes(String(this.value));
                        $(this).prop('checked', isChecked);
                        $(this).closest('.completion-option').toggleClass('active', isChecked);
                    });
                    if ($options.filter(':checked').length === 0) {
                        $options.first().prop('checked', true);
                        $options.first().closest('.completion-option').addClass('active');
                    }
                    updateFilterIndicators();
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
                        summary.className = 'filter-summary filter-pill muted trigger-summary d-none';
                    }
                    updateFilterTriggerLabel('employees', 0);
                    updateFilterIndicators();
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
                        summary.className = 'filter-summary filter-pill muted trigger-summary d-none';
                    }
                    updateFilterTriggerLabel('devices', 0);
                    updateFilterIndicators();
                }

                function updateFilterIndicators() {
                    const hasNonDefaultSelection = (selector) => {
                        const $select = $(selector);
                        if (!$select.length) {
                            return false;
                        }
                        const values = $select.val() || [];
                        const normalized = Array.isArray(values) ? values.map(String) : [String(values)];
                        const defaultValue = String($select.data('default') || 'all');

                        if (normalized.length === 0) {
                            return true;
                        }
                        if (normalized.length === 1 && normalized[0] === defaultValue) {
                            return false;
                        }
                        return true;
                    };

                    const hasRangeValue = (selector) => {
                        const input = document.querySelector(selector);
                        if (!input) {
                            return false;
                        }
                        return String(input.value || '').trim() !== '';
                    };

                    document.querySelectorAll('.filter-group').forEach((group) => {
                        const resetBtn = group.querySelector('.filter-reset-btn');
                        if (!resetBtn) {
                            return;
                        }
                        const key = resetBtn.getAttribute('data-reset');
                        let isActive = false;

                        switch (key) {
                            case 'date-from': {
                                const input = document.getElementById('master_from');
                                const defaultValue = input ? (input.getAttribute('data-default') || '') : '';
                                const currentValue = input ? (input.value || '') : '';
                                isActive = defaultValue !== '' && currentValue !== defaultValue;
                                break;
                            }
                            case 'date-to': {
                                const input = document.getElementById('master_to');
                                const defaultValue = input ? (input.getAttribute('data-default') || '') : '';
                                const currentValue = input ? (input.value || '') : '';
                                isActive = defaultValue !== '' && currentValue !== defaultValue;
                                break;
                            }
                            case 'doctor':
                                isActive = hasNonDefaultSelection('#doctor');
                                break;
                            case 'material':
                                isActive = hasNonDefaultSelection('#material');
                                break;
                            case 'job-type':
                                isActive = hasNonDefaultSelection('#job_type');
                                break;
                            case 'material-type':
                                isActive = hasNonDefaultSelection('#material_type');
                                break;
                            case 'failure-type':
                                isActive = hasNonDefaultSelection('#failure_type');
                                break;
                            case 'abutments':
                                isActive = hasNonDefaultSelection('#abutments');
                                break;
                            case 'implants':
                                isActive = hasNonDefaultSelection('#implants');
                                break;
                            case 'status':
                                isActive = hasNonDefaultSelection('#status');
                                break;
                            case 'amount':
                                isActive = hasRangeValue('#amount_from') || hasRangeValue('#amount_to');
                                break;
                            case 'units':
                                isActive = hasRangeValue('#units_from') || hasRangeValue('#units_to');
                                break;
                            case 'employees': {
                                const count = document.querySelectorAll('#hidden-employee-filters input').length;
                                isActive = count > 0;
                                break;
                            }
                            case 'devices': {
                                const count = document.querySelectorAll('#hidden-device-filters input').length;
                                isActive = count > 0;
                                break;
                            }
                            case 'completion': {
                                const toggle = document.getElementById('completion_toggle');
                                const checked = toggle ? toggle.querySelectorAll('input[type="checkbox"]:checked').length : 0;
                                isActive = checked === 1;
                                break;
                            }
                            default:
                                isActive = false;
                                break;
                        }

                        group.classList.toggle('is-active', isActive);
                    });
                }

                function formatAppliedFiltersText() {
                    const chips = document.querySelectorAll('.filters-applied-item');
                    if (!chips.length) {
                        return;
                    }

                    chips.forEach((chip) => {
                        const raw = (chip.textContent || '').trim();

                        const employeeMatch = raw.match(/^\[Emp:\s*(.*)\]$/i);
                        if (employeeMatch) {
                            const body = employeeMatch[1] || '';
                            const segments = body.split(/\bAND\b/i).map(s => s.trim()).filter(Boolean);
                            chip.innerHTML = '';
                            chip.append(document.createTextNode('[Emp: '));

                            segments.forEach((segment, index) => {
                                const colonIndex = segment.indexOf(':');
                                if (colonIndex > -1) {
                                    const stageLabel = segment.slice(0, colonIndex).trim();
                                    const employeeName = segment.slice(colonIndex + 1).trim();
                                    chip.append(document.createTextNode(`${stageLabel}: `));
                                    const strong = document.createElement('strong');
                                    strong.textContent = employeeName;
                                    chip.append(strong);
                                } else {
                                    const strong = document.createElement('strong');
                                    strong.textContent = segment;
                                    chip.append(strong);
                                }

                                if (index < segments.length - 1) {
                                    chip.append(document.createTextNode('\u00A0AND\u00A0'));
                                }
                            });

                            chip.append(document.createTextNode(']'));
                            return;
                        }

                        const deviceMatch = raw.match(/^\[Devices:\s*(.*)\]$/i);
                        if (deviceMatch) {
                            const body = deviceMatch[1] || '';
                            const tokens = body
                                .split(/(\bAND\b|\bOR\b)/i)
                                .map(token => token.trim())
                                .filter(token => token !== '');
                            chip.innerHTML = '';
                            chip.append(document.createTextNode('[Devices: '));

                            tokens.forEach((token) => {
                                if (/^(AND|OR)$/i.test(token)) {
                                    chip.append(document.createTextNode(`\u00A0${token.toUpperCase()}\u00A0`));
                                } else {
                                    const strong = document.createElement('strong');
                                    strong.textContent = token;
                                    chip.append(strong);
                                }
                            });

                            chip.append(document.createTextNode(']'));
                        }
                    });
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
                            setCompletionValue(['completed', 'in_progress']);
                            break;
                        default:
                            break;
                    }
                    updateFilterIndicators();
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
                        from: @json($defaultFrom),
                        to: @json($defaultTo)
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
                    updateFilterIndicators();
                }

                var deviceFilterCount = 0;
                var deviceTypes = @json($devicesByType);
                const MASTER_REPORT_FILTERS_STORAGE_KEY = 'masterReportFilters.v1';

                function getMasterReportContainer() {
                    return document.querySelector('.master-report-container');
                }

                function markSelectUiReady() {
                    const container = getMasterReportContainer();
                    if (container) {
                        container.classList.remove('sigma-ui-preinit');
                    }
                }

                function showMasterReportTable() {
                    const container = getMasterReportContainer();
                    if (container) {
                        container.classList.remove('sigma-table-pending');
                    }
                }

                function buildMasterReportQueryFromForm() {
                    const form = document.getElementById('master-report-form');
                    if (!form) {
                        return '';
                    }
                    const formData = new FormData(form);
                    const params = new URLSearchParams();

                    formData.forEach((value, key) => {
                        if (value !== null && String(value).trim() !== '') {
                            params.append(key, value);
                        }
                    });

                    return params.toString();
                }

                function persistMasterReportFilters() {
                    const query = buildMasterReportQueryFromForm();
                    if (query) {
                        localStorage.setItem(MASTER_REPORT_FILTERS_STORAGE_KEY, query);
                    }
                }

                function restoreMasterReportFiltersIfNeeded() {
                    const hasQueryString = window.location.search && window.location.search.length > 1;
                    if (hasQueryString) {
                        return false;
                    }

                    const savedQuery = localStorage.getItem(MASTER_REPORT_FILTERS_STORAGE_KEY);
                    if (!savedQuery) {
                        return false;
                    }

                    window.location.replace(`${window.location.pathname}?${savedQuery}`);
                    return true;
                }

                // Initialize modern components
                $(document).ready(function() {
                    if (restoreMasterReportFiltersIfNeeded()) {
                        return;
                    }

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
                        formatAppliedFiltersText();
                        persistMasterReportFilters();
                        setTimeout(function() {
                            markSelectUiReady();
                            showMasterReportTable();
                        }, 1200);
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

                        persistMasterReportFilters();

                        // Allow form to submit
                        return true;
                    });

                    $('#master-report-form').on('change input', 'input, select, textarea', function() {
                        persistMasterReportFilters();
                    });

                    // Ensure master report rows open the case preview modal
                    $(document).on('click', '#master-report-table tbody tr.master-report-row', function(e) {
                        if ($(e.target).closest('a, button, input, select, label').length) {
                            return;
                        }
                        e.preventDefault();
                        e.stopPropagation();
                        const caseId = $(this).data('case-id');
                        if (!caseId) {
                            return;
                        }
                        const modal = $('#actionsDialog' + caseId);
                        if (modal.length) {
                            masterReportScrollY = window.pageYOffset || document.documentElement.scrollTop || 0;
                            modal.modal('show');
                        }
                    });
                });

                // Initialize Select2 Dropdowns
                function initializeSelect2() {
                    if (typeof $.fn.select2 === 'undefined') {
                        $('.select2-multiple').addClass('modern-select');
                        markSelectUiReady();
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
                            $(this).off('change.filterIndicators').on('change.filterIndicators', function() {
                                updateFilterIndicators();
                            });
                        });

                        // Initialize material type dependency
                        initializeMaterialTypeDependency();

                        // Initialize exclusive "All" selection logic
                        initializeAllOptionLogic();

                        // Clean up "all" option from multi-select dropdowns on page load
                        cleanupAllOptionOnLoad();
                        updateFilterIndicators();
                        markSelectUiReady();
                    } catch (error) {
                        $('.select2-multiple').addClass('modern-select');
                        initializeMaterialTypeDependency();
                        initializeAllOptionLogic();
                        updateFilterIndicators();
                        markSelectUiReady();
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
                                updateMaterialTypeDropdown(data.types, selectedMaterials);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching material types:', error);
                        });
                }

                // Update material type dropdown
                function updateMaterialTypeDropdown(types, selectedMaterials) {
                    const $materialType = $('#material_type');
                    const currentValues = ($materialType.val() || []).map(String);
                    const normalizedMaterials = (selectedMaterials || []).map(String);
                    const hasAllMaterials = normalizedMaterials.length === 0 || normalizedMaterials.includes('all');
                    const hasTypes = Array.isArray(types) && types.length > 0;
                    let allLabel = 'All materials types';

                    if (!hasTypes) {
                        allLabel = 'No material types';
                    } else if (!hasAllMaterials) {
                        allLabel = 'All Selected M. types';
                    }

                    // Destroy select2 before updating options
                    if (typeof $.fn.select2 !== 'undefined' && $materialType.hasClass('select2-hidden-accessible')) {
                        $materialType.select2('destroy');
                    }

                    $materialType.empty();
                    const selectAll = currentValues.length === 0 || currentValues.includes('all') || !hasTypes;
                    $materialType.append(`<option value="all"${selectAll ? ' selected' : ''}>${allLabel}</option>`);

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
                    if (selectAll) {
                        $materialType.val(['all']);
                    }

                    if (window.initialMaterialTypes) {
                        window.initialMaterialTypes = null;
                    }
                    updateFilterIndicators();
                }

                // Initialize exclusive "All" option logic for dropdowns
                function initializeAllOptionLogic() {
                    const dropdownIds = ['doctor', 'material', 'job_type', 'failure_type', 'abutments', 'implants', 'status', 'material_type'];

                    function normalizeValues(values) {
                        return (values || []).map(value => String(value));
                    }

                    function enforceAllOptionState($dropdown, triggerSelect2) {
                        const selectedValues = normalizeValues($dropdown.val());

                        if (selectedValues.length === 0) {
                            $dropdown.val(['all']);
                            if (triggerSelect2) {
                                $dropdown.trigger('change.select2');
                            } else {
                                $dropdown.trigger('change');
                            }
                            return;
                        }

                        if (selectedValues.includes('all') && selectedValues.length > 1) {
                            const filteredValues = selectedValues.filter(val => val !== 'all');
                            $dropdown.val(filteredValues);
                            if (triggerSelect2) {
                                $dropdown.trigger('change.select2');
                            } else {
                                $dropdown.trigger('change');
                            }
                        }
                    }

                    dropdownIds.forEach(function(dropdownId) {
                        const $dropdown = $('#' + dropdownId);
                        if ($dropdown.length === 0) {
                            return;
                        }

                        let isApplying = false;

                        const applyValues = (values) => {
                            isApplying = true;
                            $dropdown.val(values);
                            if (typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible')) {
                                $dropdown.trigger('change.select2');
                            } else {
                                $dropdown.trigger('change');
                            }
                            isApplying = false;
                        };

                        $dropdown.on('select2:select', function(e) {
                            if (isApplying) return;
                            const selectedValues = normalizeValues($dropdown.val());
                            const selectedId = e && e.params && e.params.data ? String(e.params.data.id) : null;

                            if (selectedId === 'all') {
                                applyValues(['all']);
                                return;
                            }

                            if (selectedValues.includes('all')) {
                                applyValues(selectedValues.filter(val => val !== 'all'));
                            }
                        });

                        $dropdown.on('select2:unselect', function() {
                            if (isApplying) return;
                            const selectedValues = normalizeValues($dropdown.val());
                            if (selectedValues.length === 0) {
                                applyValues(['all']);
                                return;
                            }
                            if (selectedValues.includes('all') && selectedValues.length > 1) {
                                applyValues(selectedValues.filter(val => val !== 'all'));
                            }
                        });

                        $dropdown.on('change', function() {
                            if (isApplying) return;
                            const shouldTriggerSelect2 = typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible');
                            enforceAllOptionState($dropdown, shouldTriggerSelect2);
                        });

                        const shouldTriggerSelect2 = typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible');
                        enforceAllOptionState($dropdown, shouldTriggerSelect2);
                    });
                }

                // Clean up "all" option from dropdowns on page load if specific options are selected
                function cleanupAllOptionOnLoad() {
                    const dropdownIds = ['doctor', 'material', 'job_type', 'failure_type', 'abutments', 'implants', 'status', 'material_type'];

                    dropdownIds.forEach(function(dropdownId) {
                        const $dropdown = $('#' + dropdownId);
                        if ($dropdown.length === 0) {
                            return;
                        }
                        const currentValues = ($dropdown.val() || []).map(value => String(value));

                        if (currentValues.length === 0) {
                            $dropdown.val(['all']);
                            if (typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible')) {
                                $dropdown.trigger('change.select2');
                            } else {
                                $dropdown.trigger('change');
                            }
                            return;
                        }

                        if (currentValues.includes('all') && currentValues.length > 1) {
                            const filteredValues = currentValues.filter(val => val !== 'all');
                            $dropdown.val(filteredValues);

                            if (typeof $.fn.select2 !== 'undefined' && $dropdown.hasClass('select2-hidden-accessible')) {
                                $dropdown.trigger('change.select2');
                            } else {
                                $dropdown.trigger('change');
                            }
                        }
                    });
                }

                // Initialize range validation
                function initializeRangeValidation() {
                    $('#master_from, #master_to').on('change', function() {
                        updateFilterIndicators();
                    });
                    $('#amount_from, #amount_to').on('input', function() {
                        validateAmountRange();
                        const fromInput = $('#amount_from');
                        const toInput = $('#amount_to');
                        if (toInput.val() !== '' && fromInput.val() === '') {
                            fromInput.val('0').trigger('input');
                        }
                    });
                    $('#amount_from, #amount_to').on('blur change', function() {
                        normalizeAmountInput(this);
                        validateAmountRange();
                        updateFilterIndicators();
                    });
                    $('#units_from, #units_to').on('input', function() {
                        validateUnitsRange();
                        updateFilterIndicators();
                        const fromInput = $('#units_from');
                        const toInput = $('#units_to');
                        if (toInput.val() !== '' && fromInput.val() === '') {
                            fromInput.val('0').trigger('input');
                        }
                    });
                    $('#units_from, #units_to').on('blur change', function() {
                        normalizeUnitsInput(this);
                        validateUnitsRange();
                        updateFilterIndicators();
                    });
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

                function normalizeAmountInput(input) {
                    if (!input) {
                        return;
                    }
                    const raw = String(input.value || '').trim();
                    if (raw === '') {
                        return;
                    }
                    const parsed = parseFloat(raw);
                    if (Number.isNaN(parsed)) {
                        input.value = '';
                        return;
                    }
                    const rounded = Math.round(parsed * 10) / 10;
                    if (Number.isInteger(rounded)) {
                        input.value = String(rounded);
                    } else {
                        input.value = rounded.toFixed(1);
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

                function normalizeUnitsInput(input) {
                    if (!input) {
                        return;
                    }
                    const raw = String(input.value || '').trim();
                    if (raw === '') {
                        return;
                    }
                    const parsed = Math.floor(parseFloat(raw));
                    if (Number.isNaN(parsed)) {
                        input.value = '';
                        return;
                    }
                    input.value = String(Math.max(0, parsed));
                }

                // Initialize column visibility
                function initializeColumnVisibility() {
                    const $dropdown = $('.columns-dropdown');
                    const $toggleButton = $('#columnVisibilityDropdown');
                    const $menu = $dropdown.find('.dropdown-menu');

                    $toggleButton.off('click.columns').on('click.columns', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $dropdown.toggleClass('show');
                        $menu.toggleClass('show');
                        $toggleButton.attr('aria-expanded', $menu.hasClass('show') ? 'true' : 'false');
                    });

                    $(document).off('click.columns').on('click.columns', function(e) {
                        if (!$(e.target).closest('.columns-dropdown').length) {
                            $dropdown.removeClass('show');
                            $menu.removeClass('show');
                            $toggleButton.attr('aria-expanded', 'false');
                        }
                    });

                    $menu.off('click.columns').on('click.columns', function(e) {
                        e.stopPropagation();
                    });

                    applyLockedColumns();
                    loadColumnPreferences();
                    updateGroupToggles();

                    $('.column-toggle').off('change.columns').on('change.columns', function() {
                        const columnIndex = $(this).data('column');
                        const isVisible = $(this).is(':checked');
                        toggleColumn(columnIndex, isVisible);
                        updateGroupToggles();
                        saveColumnPreferences();
                    });

                    $('.column-toggle-group').off('change.columns').on('change.columns', function() {
                        const columns = parseColumnList($(this).data('columns'));
                        const isVisible = $(this).is(':checked');
                        columns.forEach(function(columnIndex) {
                            const $checkbox = $(`.column-toggle[data-column="${columnIndex}"]`);
                            if ($checkbox.length && !$checkbox.prop('disabled')) {
                                $checkbox.prop('checked', isVisible).trigger('change');
                            }
                        });
                        updateGroupToggles();
                    });

                    $('#selectAllColumns').off('click.columns').on('click.columns', function() {
                        $('.column-toggle').each(function() {
                            if (!$(this).prop('disabled')) {
                                $(this).prop('checked', true).trigger('change');
                            }
                        });
                    });

                    $('#deselectAllColumns').off('click.columns').on('click.columns', function() {
                        $('.column-toggle').each(function() {
                            if (!$(this).prop('disabled')) {
                                $(this).prop('checked', false).trigger('change');
                            }
                        });
                    });
                }

                function parseColumnList(columnsAttr) {
                    if (!columnsAttr) {
                        return [];
                    }
                    if (Array.isArray(columnsAttr)) {
                        return columnsAttr.map(Number).filter(Number.isFinite);
                    }
                    return String(columnsAttr)
                        .split(',')
                        .map(value => parseInt(value, 10))
                        .filter(value => !Number.isNaN(value));
                }

                function applyLockedColumns() {
                    const lockedColumns = [1, 2];
                    lockedColumns.forEach(function(columnIndex) {
                        const $checkbox = $(`.column-toggle[data-column="${columnIndex}"]`);
                        if ($checkbox.length) {
                            $checkbox.prop('checked', true);
                            $checkbox.prop('disabled', true);
                            $checkbox.attr('data-locked', 'true');
                        }
                    });
                }

                function updateGroupToggles() {
                    $('.column-toggle-group').each(function() {
                        const columns = parseColumnList($(this).data('columns'));
                        if (columns.length === 0) {
                            return;
                        }
                        const allChecked = columns.every(function(columnIndex) {
                            const $checkbox = $(`.column-toggle[data-column="${columnIndex}"]`);
                            return $checkbox.length ? $checkbox.is(':checked') : false;
                        });
                        $(this).prop('checked', allChecked);
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
                    localStorage.setItem('masterReportColumnPreferences.v2', JSON.stringify(preferences));
                }

                function loadColumnPreferences() {
                    const savedPreferences = localStorage.getItem('masterReportColumnPreferences.v2');
                    const defaultVisibleColumns = [1, 2, 3, 4, 5, 6, 8, 10, 11, 12, 13, 14, 19, 20];
                    let preferences = {};

                    if (savedPreferences) {
                        try {
                            preferences = JSON.parse(savedPreferences) || {};
                        } catch (error) {
                            console.warn('Error loading column preferences:', error);
                            preferences = {};
                        }
                    }

                    if (!savedPreferences || Object.keys(preferences).length === 0) {
                        defaultVisibleColumns.forEach(function(columnIndex) {
                            preferences[columnIndex] = true;
                        });
                    }

                    $('.column-toggle').each(function() {
                        const columnIndex = $(this).data('column');
                        const isLocked = $(this).data('locked') === true || $(this).attr('data-locked') === 'true';
                        const shouldShow = isLocked ? true : preferences[columnIndex] === true;
                        $(this).prop('checked', shouldShow);
                    });

                    saveColumnPreferences();
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

                const MASTER_REPORT_EXPORT_TITLE = 'Master Report - {{ now()->format("Y-m-d") }}';
                const MASTER_REPORT_EXPORT_FONT = '"Cairo", "Tajawal", "Noto Naskh Arabic", "Segoe UI", Tahoma, Arial, sans-serif';
                const MASTER_REPORT_WRAP_HEADERS = new Set([
                    'Doctor Name',
                    'Patient Name',
                    'Material',
                    'Job Type',
                    'Created Date',
                    'Actual delivery D.',
                    'Initial delivery D.'
                ]);

                function escapeHtml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                }

                function escapeXml(value) {
                    return String(value ?? '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&apos;');
                }

                function normalizeExportText(value) {
                    return String(value ?? '')
                        .replace(/\u00a0/g, ' ')
                        .replace(/\s*\n\s*/g, ' | ')
                        .replace(/[ \t]{2,}/g, ' ')
                        .trim();
                }

                function getMasterReportVisibleColumnIndexes() {
                    if (!window.masterReportTable) {
                        return [];
                    }

                    return window.masterReportTable.columns().indexes().toArray().filter(function(index) {
                        return window.masterReportTable.column(index).visible();
                    });
                }

                function extractMasterReportCellText(cellNode) {
                    if (!cellNode) {
                        return '';
                    }

                    return normalizeExportText(cellNode.innerText || cellNode.textContent || '');
                }

                function getMasterReportExportTableData() {
                    if (!window.masterReportTable) {
                        return { headers: [], rows: [], columnIndexes: [] };
                    }

                    const columnIndexes = getMasterReportVisibleColumnIndexes();
                    const headers = columnIndexes.map(function(columnIndex) {
                        return normalizeExportText(window.masterReportTable.column(columnIndex).header().innerText || '');
                    });

                    const rowIndexes = window.masterReportTable.rows({ search: 'applied', order: 'applied' }).indexes().toArray();
                    const rows = rowIndexes.map(function(rowIndex) {
                        return columnIndexes.map(function(columnIndex) {
                            return extractMasterReportCellText(window.masterReportTable.cell(rowIndex, columnIndex).node());
                        });
                    });

                    return { headers, rows, columnIndexes };
                }

                function getMasterReportFiltersText() {
                    const fromValue = normalizeExportText(document.getElementById('master_from')?.value || '');
                    const toValue = normalizeExportText(document.getElementById('master_to')?.value || '');
                    const dateText = `From: ${fromValue || '-'} | To: ${toValue || '-'}`;
                    const appliedFilters = Array.from(document.querySelectorAll('.filters-applied-item'))
                        .map(function(item) { return normalizeExportText(item.innerText); })
                        .filter(Boolean)
                        .join(' ');

                    return [dateText, appliedFilters].filter(Boolean).join(' | ');
                }

                function getMasterReportSummaryText() {
                    const summaryParts = Array.from(document.querySelectorAll('.report-total-card .report-total-inline'))
                        .map(function(card) {
                            const number = normalizeExportText(card.querySelector('.report-total-number')?.innerText || '');
                            const label = normalizeExportText(card.querySelector('.report-total-label')?.innerText || '');
                            return [number, label].filter(Boolean).join(' ');
                        })
                        .filter(Boolean);

                    return summaryParts.join('   ');
                }

                function getMasterReportExportStyles() {
                    return `
                        @page { size: A3 landscape; margin: 12mm; }
                        html, body {
                            margin: 0;
                            padding: 0;
                            background: #ffffff;
                            color: #15314b;
                            font-family: ${MASTER_REPORT_EXPORT_FONT};
                            -webkit-print-color-adjust: exact !important;
                            print-color-adjust: exact !important;
                        }
                        .master-report-export-shell {
                            width: 100%;
                            padding: 12px 14px 0;
                            box-sizing: border-box;
                        }
                        .master-report-export-title {
                            text-align: center;
                            font-size: 24px;
                            font-weight: 700;
                            color: #184e5b;
                            margin: 0 0 6px;
                        }
                        .master-report-export-filters {
                            text-align: center;
                            font-size: 11px;
                            line-height: 1.5;
                            color: #6b7b8c;
                            margin: 0 0 8px;
                        }
                        .master-report-export-summary {
                            text-align: center;
                            font-size: 13px;
                            font-weight: 700;
                            color: #2c5e69;
                            letter-spacing: 0;
                            margin: 0 0 14px;
                        }
                        .master-report-export-table {
                            width: 100%;
                            border-collapse: collapse;
                            table-layout: auto;
                        }
                        .master-report-export-table thead {
                            display: table-header-group;
                        }
                        .master-report-export-table tfoot {
                            display: table-footer-group;
                        }
                        .master-report-export-table tr {
                            page-break-inside: avoid;
                        }
                        .master-report-export-table th,
                        .master-report-export-table td {
                            border: 1px solid #d6e0e8;
                            padding: 6px 8px;
                            font-size: 10px;
                            line-height: 1.35;
                            vertical-align: top;
                            text-align: center;
                            white-space: nowrap;
                        }
                        .master-report-export-table th {
                            background: #2f7c7e;
                            color: #ffffff;
                            font-weight: 700;
                        }
                        .master-report-export-table td {
                            color: #1f3348;
                            background: #ffffff;
                        }
                        .master-report-export-table tbody tr:nth-child(even) td {
                            background: #f7fbfc;
                        }
                        .master-report-export-table th.wrap-col,
                        .master-report-export-table td.wrap-col {
                            white-space: normal;
                            word-break: break-word;
                            overflow-wrap: anywhere;
                        }
                        .master-report-export-table td.name-col {
                            text-align: left;
                        }
                    `;
                }

                function buildMasterReportExportTableHtml(exportData) {
                    const headersHtml = exportData.headers.map(function(header, index) {
                        const wrapClass = MASTER_REPORT_WRAP_HEADERS.has(header) ? ' wrap-col' : '';
                        const nameClass = index === 1 || index === 2 ? ' name-col wrap-col' : '';
                        return `<th class="${(wrapClass + nameClass).trim()}" dir="auto">${escapeHtml(header)}</th>`;
                    }).join('');

                    const rowsHtml = exportData.rows.map(function(row) {
                        const cellsHtml = row.map(function(cell, index) {
                            const header = exportData.headers[index] || '';
                            const wrapClass = MASTER_REPORT_WRAP_HEADERS.has(header) ? ' wrap-col' : '';
                            const nameClass = index === 1 || index === 2 ? ' name-col wrap-col' : '';
                            return `<td class="${(wrapClass + nameClass).trim()}" dir="auto">${escapeHtml(cell || '-')}</td>`;
                        }).join('');

                        return `<tr>${cellsHtml}</tr>`;
                    }).join('');

                    return `
                        <table class="master-report-export-table">
                            <thead>
                                <tr>${headersHtml}</tr>
                            </thead>
                            <tbody>
                                ${rowsHtml}
                            </tbody>
                        </table>
                    `;
                }

                function buildMasterReportExportDocumentHtml(exportData) {
                    const filtersText = getMasterReportFiltersText();
                    const summaryText = getMasterReportSummaryText();

                    return `
                        <!doctype html>
                        <html lang="en">
                        <head>
                            <meta charset="utf-8">
                            <title>${escapeHtml(MASTER_REPORT_EXPORT_TITLE)}</title>
                            <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
                            <style>${getMasterReportExportStyles()}</style>
                        </head>
                        <body>
                            <div class="master-report-export-shell">
                                <h1 class="master-report-export-title">${escapeHtml(MASTER_REPORT_EXPORT_TITLE)}</h1>
                                <div class="master-report-export-filters">${escapeHtml(filtersText)}</div>
                                <div class="master-report-export-summary">${escapeHtml(summaryText)}</div>
                                ${buildMasterReportExportTableHtml(exportData)}
                            </div>
                        </body>
                        </html>
                    `;
                }

                function createMasterReportExportElement(exportData) {
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'fixed';
                    wrapper.style.left = '-20000px';
                    wrapper.style.top = '0';
                    wrapper.style.width = '1600px';
                    wrapper.style.background = '#ffffff';
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'none';
                    wrapper.style.zIndex = '-1';
                    wrapper.setAttribute('aria-hidden', 'true');
                    wrapper.innerHTML = `
                        <style>${getMasterReportExportStyles()}</style>
                        <div class="master-report-export-shell">
                            <h1 class="master-report-export-title">${escapeHtml(MASTER_REPORT_EXPORT_TITLE)}</h1>
                            <div class="master-report-export-filters">${escapeHtml(getMasterReportFiltersText())}</div>
                            <div class="master-report-export-summary">${escapeHtml(getMasterReportSummaryText())}</div>
                            ${buildMasterReportExportTableHtml(exportData)}
                        </div>
                    `;

                    document.body.appendChild(wrapper);
                    return wrapper;
                }

                function getMasterReportExportFilename(extension) {
                    const base = `Master Report - {{ now()->format("Y-m-d") }}`;
                    return `${base}.${extension}`;
                }

                function emailMasterReport() {
                    const subject = MASTER_REPORT_EXPORT_TITLE;
                    const bodyParts = [
                        MASTER_REPORT_EXPORT_TITLE,
                        '',
                        getMasterReportFiltersText(),
                        getMasterReportSummaryText(),
                        '',
                        'Report link:',
                        window.location.href
                    ].filter(Boolean);

                    window.location.href = 'mailto:?subject='
                        + encodeURIComponent(subject)
                        + '&body='
                        + encodeURIComponent(bodyParts.join('\n'));
                }

                async function saveBlobWithPicker(blob, fileName, options) {
                    if (window.showSaveFilePicker) {
                        const handle = await window.showSaveFilePicker({
                            suggestedName: fileName,
                            types: [options]
                        });
                        const writable = await handle.createWritable();
                        await writable.write(blob);
                        await writable.close();
                        return;
                    }

                    const blobUrl = URL.createObjectURL(blob);
                    const anchor = document.createElement('a');
                    anchor.href = blobUrl;
                    anchor.download = fileName;
                    document.body.appendChild(anchor);
                    anchor.click();
                    anchor.remove();
                    setTimeout(function() {
                        URL.revokeObjectURL(blobUrl);
                    }, 1000);
                }

                function csvEscape(value) {
                    const normalized = String(value ?? '').replace(/"/g, '""');
                    return `"${normalized}"`;
                }

                function buildMasterReportCsv(exportData) {
                    const lines = [
                        csvEscape(MASTER_REPORT_EXPORT_TITLE),
                        csvEscape(getMasterReportFiltersText()),
                        csvEscape(getMasterReportSummaryText()),
                        exportData.headers.map(csvEscape).join(',')
                    ];

                    exportData.rows.forEach(function(row) {
                        lines.push(row.map(csvEscape).join(','));
                    });

                    return lines.join('\r\n');
                }

                function getSpreadsheetColumnWidth(header) {
                    if (header === 'Doctor Name' || header === 'Patient Name') {
                        return 140;
                    }

                    if (header === 'Material' || header === 'Job Type') {
                        return 120;
                    }

                    if (header.includes('Date')) {
                        return 90;
                    }

                    if (header.includes('Device')) {
                        return 90;
                    }

                    return 75;
                }

                function buildMasterReportExcelXml(exportData) {
                    const mergeAcross = Math.max(exportData.headers.length - 1, 0);
                    const columnMarkup = exportData.headers.map(function(header) {
                        return `<Column ss:AutoFitWidth="0" ss:Width="${getSpreadsheetColumnWidth(header)}"/>`;
                    }).join('');
                    const titleRow = `<Row ss:Height="28"><Cell ss:MergeAcross="${mergeAcross}" ss:StyleID="title"><Data ss:Type="String">${escapeXml(MASTER_REPORT_EXPORT_TITLE)}</Data></Cell></Row>`;
                    const filtersRow = `<Row ss:Height="22"><Cell ss:MergeAcross="${mergeAcross}" ss:StyleID="filters"><Data ss:Type="String">${escapeXml(getMasterReportFiltersText())}</Data></Cell></Row>`;
                    const summaryRow = `<Row ss:Height="24"><Cell ss:MergeAcross="${mergeAcross}" ss:StyleID="summary"><Data ss:Type="String">${escapeXml(getMasterReportSummaryText())}</Data></Cell></Row>`;
                    const spacerRow = '<Row ss:Height="8"></Row>';
                    const headerRow = `<Row ss:Height="24">${exportData.headers.map(function(header) {
                        return `<Cell ss:StyleID="header"><Data ss:Type="String">${escapeXml(header)}</Data></Cell>`;
                    }).join('')}</Row>`;
                    const bodyRows = exportData.rows.map(function(row, rowIndex) {
                        const styleId = rowIndex % 2 === 0 ? 'cellOdd' : 'cellEven';
                        return `<Row>${row.map(function(cell) {
                            return `<Cell ss:StyleID="${styleId}"><Data ss:Type="String">${escapeXml(cell)}</Data></Cell>`;
                        }).join('')}</Row>`;
                    }).join('');

                    return `<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Top" ss:WrapText="1"/>
   <Font ss:FontName="Cairo" ss:Size="10" ss:Color="#1F3348"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
   </Borders>
  </Style>
  <Style ss:ID="title">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Cairo" ss:Size="16" ss:Bold="1" ss:Color="#184E5B"/>
  </Style>
  <Style ss:ID="filters">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Font ss:FontName="Cairo" ss:Size="10" ss:Color="#6B7B8C"/>
  </Style>
  <Style ss:ID="summary">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Font ss:FontName="Cairo" ss:Size="11" ss:Bold="1" ss:Color="#2C5E69"/>
  </Style>
  <Style ss:ID="header">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Font ss:FontName="Cairo" ss:Size="10" ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#2F7C7E" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
   </Borders>
  </Style>
  <Style ss:ID="cellOdd">
   <Alignment ss:Horizontal="Center" ss:Vertical="Top" ss:WrapText="1"/>
   <Font ss:FontName="Cairo" ss:Size="10" ss:Color="#1F3348"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
   </Borders>
  </Style>
  <Style ss:ID="cellEven">
   <Alignment ss:Horizontal="Center" ss:Vertical="Top" ss:WrapText="1"/>
   <Font ss:FontName="Cairo" ss:Size="10" ss:Color="#1F3348"/>
   <Interior ss:Color="#F7FBFC" ss:Pattern="Solid"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#D6E0E8"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Master Report">
  <Table>
   ${columnMarkup}
   ${titleRow}
   ${filtersRow}
   ${summaryRow}
   ${spacerRow}
   ${headerRow}
   ${bodyRows}
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <Layout x:Orientation="Landscape"/>
   </PageSetup>
   <Selected/>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>`;
                }

                async function exportMasterReportCsv() {
                    const exportData = getMasterReportExportTableData();
                    const csvContent = buildMasterReportCsv(exportData);
                    const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8' });
                    await saveBlobWithPicker(blob, getMasterReportExportFilename('csv'), {
                        description: 'CSV file',
                        accept: { 'text/csv': ['.csv'] }
                    });
                }

                async function exportMasterReportExcel() {
                    const exportData = getMasterReportExportTableData();
                    const workbook = buildMasterReportExcelXml(exportData);
                    const blob = new Blob(['\ufeff' + workbook], { type: 'application/vnd.ms-excel;charset=utf-8' });
                    await saveBlobWithPicker(blob, getMasterReportExportFilename('xls'), {
                        description: 'Excel workbook',
                        accept: { 'application/vnd.ms-excel': ['.xls'] }
                    });
                }

                async function exportMasterReportPdfBlob() {
                    const exportData = getMasterReportExportTableData();
                    const wrapper = createMasterReportExportElement(exportData);
                    const exportShell = wrapper.querySelector('.master-report-export-shell');

                    try {
                        if (document.fonts && document.fonts.ready) {
                            await document.fonts.ready;
                        }
                        await new Promise(function(resolve) {
                            requestAnimationFrame(function() {
                                requestAnimationFrame(resolve);
                            });
                        });

                        const html2canvasLib = window.html2canvas;
                        const JsPdfCtor = window.jspdf && window.jspdf.jsPDF ? window.jspdf.jsPDF : null;

                        if (!html2canvasLib || !JsPdfCtor) {
                            throw new Error('PDF export libraries are not available.');
                        }

                        const canvas = await html2canvasLib(exportShell, {
                            scale: 2,
                            useCORS: true,
                            backgroundColor: '#ffffff',
                            scrollX: 0,
                            scrollY: 0,
                            width: exportShell.scrollWidth,
                            windowWidth: exportShell.scrollWidth
                        });

                        const pdf = new JsPdfCtor({
                            unit: 'mm',
                            format: 'a3',
                            orientation: 'landscape',
                            compress: true
                        });

                        const pageWidth = pdf.internal.pageSize.getWidth();
                        const pageHeight = pdf.internal.pageSize.getHeight();
                        const margin = 8;
                        const printableWidth = pageWidth - (margin * 2);
                        const printableHeight = pageHeight - (margin * 2);
                        const pageHeightPx = Math.max(1, Math.floor((printableHeight / printableWidth) * canvas.width));

                        let renderedHeight = 0;
                        let pageIndex = 0;

                        while (renderedHeight < canvas.height) {
                            const sliceCanvas = document.createElement('canvas');
                            sliceCanvas.width = canvas.width;
                            sliceCanvas.height = Math.min(pageHeightPx, canvas.height - renderedHeight);

                            const context = sliceCanvas.getContext('2d');
                            context.fillStyle = '#ffffff';
                            context.fillRect(0, 0, sliceCanvas.width, sliceCanvas.height);
                            context.drawImage(
                                canvas,
                                0,
                                renderedHeight,
                                canvas.width,
                                sliceCanvas.height,
                                0,
                                0,
                                sliceCanvas.width,
                                sliceCanvas.height
                            );

                            if (pageIndex > 0) {
                                pdf.addPage('a3', 'landscape');
                            }

                            const sliceHeightMm = (sliceCanvas.height / sliceCanvas.width) * printableWidth;
                            pdf.addImage(
                                sliceCanvas.toDataURL('image/jpeg', 0.96),
                                'JPEG',
                                margin,
                                margin,
                                printableWidth,
                                sliceHeightMm,
                                undefined,
                                'FAST'
                            );

                            renderedHeight += sliceCanvas.height;
                            pageIndex += 1;
                        }

                        return pdf.output('blob');
                    } finally {
                        wrapper.remove();
                    }
                }

                async function exportMasterReportPdf() {
                    const blob = await exportMasterReportPdfBlob();
                    await saveBlobWithPicker(blob, getMasterReportExportFilename('pdf'), {
                        description: 'PDF document',
                        accept: { 'application/pdf': ['.pdf'] }
                    });
                }

                function printMasterReport() {
                    const exportData = getMasterReportExportTableData();
                    const iframe = document.createElement('iframe');
                    iframe.style.position = 'fixed';
                    iframe.style.right = '0';
                    iframe.style.bottom = '0';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = '0';
                    iframe.setAttribute('aria-hidden', 'true');
                    document.body.appendChild(iframe);

                    if (!iframe.contentWindow) {
                        iframe.remove();
                        return;
                    }

                    const triggerPrint = function() {
                        iframe.contentWindow.focus();
                        iframe.contentWindow.print();
                        setTimeout(function() {
                            iframe.remove();
                        }, 1500);
                    };

                    const waitForPrintReady = function() {
                        const frameDocument = iframe.contentWindow?.document;
                        if (!frameDocument) {
                            return;
                        }
                        if (frameDocument.fonts && frameDocument.fonts.ready) {
                            frameDocument.fonts.ready.then(function() {
                                setTimeout(triggerPrint, 300);
                            }).catch(function() {
                                setTimeout(triggerPrint, 300);
                            });
                        } else {
                            setTimeout(triggerPrint, 300);
                        }
                    };

                    iframe.onload = waitForPrintReady;
                    iframe.srcdoc = buildMasterReportExportDocumentHtml(exportData);
                    setTimeout(waitForPrintReady, 500);
                }

                async function shareMasterReportToWhatsApp() {
                    const blob = await exportMasterReportPdfBlob();
                    const file = new File([blob], getMasterReportExportFilename('pdf'), { type: 'application/pdf' });
                    const shareTitle = MASTER_REPORT_EXPORT_TITLE;
                    const shareText = `${MASTER_REPORT_EXPORT_TITLE}\n${getMasterReportFiltersText()}\n${getMasterReportSummaryText()}`;

                    if (navigator.share && navigator.canShare && navigator.canShare({ files: [file] })) {
                        await navigator.share({
                            title: shareTitle,
                            text: shareText,
                            files: [file]
                        });
                        return;
                    }

                    const message = 'This browser cannot attach files directly to WhatsApp. The report will be saved as PDF so you can share it manually.';
                    alert(message);
                    await saveBlobWithPicker(blob, getMasterReportExportFilename('pdf'), {
                        description: 'PDF document',
                        accept: { 'application/pdf': ['.pdf'] }
                    });
                }

                async function handleMasterReportExport(action) {
                    if (!window.masterReportTable) {
                        return;
                    }

                    try {
                        if (action === 'email') {
                            emailMasterReport();
                        } else if (action === 'excel') {
                            await exportMasterReportExcel();
                        } else if (action === 'pdf') {
                            await exportMasterReportPdf();
                        } else if (action === 'print') {
                            printMasterReport();
                        }
                    } catch (error) {
                        if (error?.name === 'AbortError') {
                            return;
                        }
                        console.error('Master report export failed:', error);
                        alert('Unable to complete the export. Please try again.');
                    }
                }

                // Initialize DataTable
                function initializeDataTable() {
                    @if($cases->count() > 0)
                        window.masterReportTable = $('#master-report-table').DataTable({
                        dom: 'rtip',
                        pageLength: 25,
                        info: false,
                        responsive: false,
                        scrollX: false,
                        scrollY: false,
                        scrollCollapse: false,
                        autoWidth: false,
                        ordering: false,
                        fixedColumns: false,
                        columnDefs: [
                            { targets: '_all', className: 'text-center' },
                            { targets: [0, 1, 2], className: 'text-left' },
                            { targets: [0, 1, 2, 3, 4, 5, 6, 7, 20, 21], orderable: true },
                            { targets: [8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19], orderable: false }
                        ],
                        drawCallback: function() {
                            $('#master-report-table thead th.header-light, .sigma-report-table-container .dataTables_scrollHead th.header-light').css({
                                'background-color': '#408385',
                                'background': '#408385',
                                'border': 'none',
                                'border-bottom': 'none',
                                'color': '#ffffff'
                            });
                            $('#master-report-table thead th.header-dark, .sigma-report-table-container .dataTables_scrollHead th.header-dark').css({
                                'background-color': '#408385',
                                'background': '#408385',
                                'color': '#ffffff',
                                'border': 'none'
                            });
                        },
                        initComplete: function() {
                            const tableApi = this.api();
                            $('.column-toggle').each(function() {
                                const columnIndex = $(this).data('column');
                                const isVisible = $(this).is(':checked');
                                tableApi.column(columnIndex).visible(isVisible);
                            });
                            showMasterReportTable();
                        }
                    });

                    initializeReportHeaderActions();

                    setTimeout(() => {
                        $('#master-report-table thead th.header-light, .sigma-report-table-container .dataTables_scrollHead th.header-light').css({
                            'background-color': '#408385',
                            'background': '#408385',
                            'border': 'none',
                            'border-bottom': 'none',
                            'color': '#ffffff'
                        });
                        $('#master-report-table thead th.header-dark, .sigma-report-table-container .dataTables_scrollHead th.header-dark').css({
                            'background-color': '#408385',
                            'background': '#408385',
                            'color': '#ffffff',
                            'border': 'none'
                        });

                        loadColumnPreferences();
                        $('.column-toggle').each(function() {
                            const columnIndex = $(this).data('column');
                            const isVisible = $(this).is(':checked');
                            window.masterReportTable.column(columnIndex).visible(isVisible);
                        });
                        showMasterReportTable();
                    }, 100);
                    @else
                        showMasterReportTable();
                    @endif
                }

                function initializeReportHeaderActions() {
                    const $searchInput = $('#master-report-search');
                    if ($searchInput.length) {
                        $searchInput.off('input.masterSearch').on('input.masterSearch', function() {
                            if (window.masterReportTable) {
                                window.masterReportTable.search(this.value).draw();
                            }
                        });
                    }

                    const $exportOrbit = $('.export-orbit');
                    if ($exportOrbit.length) {
                        let exportOrbitTimer;
                        $exportOrbit.off('mouseenter.exportOrbit mouseleave.exportOrbit')
                            .on('mouseenter.exportOrbit', function() {
                                clearTimeout(exportOrbitTimer);
                                $(this).addClass('is-open');
                            })
                            .on('mouseleave.exportOrbit', function() {
                                const $orbit = $(this);
                                clearTimeout(exportOrbitTimer);
                                exportOrbitTimer = setTimeout(() => {
                                    $orbit.removeClass('is-open');
                                }, 180);
                            });
                    }

                    $('.export-action').off('click.export').on('click.export', async function() {
                        const action = $(this).data('export');
                        await handleMasterReportExport(action);
                    });
                }

                // Case viewing function
                function viewMasterReportCase(caseId) {
                    if (confirm(`View details for Case ID: ${caseId}?`)) {
                        window.open(`/cases/${caseId}`, '_blank');
                    }
                }

                let masterReportScrollY = 0;
                let masterReportScrollLocked = false;
                let masterReportScrollGuardActive = false;

                function isMasterReportModalScrollableTarget(target) {
                    return !!(target && target.closest && target.closest('.sigma-modal--master-report-case-actions .modal-body'));
                }

                function maintainMasterReportScrollPosition() {
                    if (!masterReportScrollLocked) {
                        return;
                    }
                    const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
                    if (Math.abs(currentScroll - masterReportScrollY) > 1) {
                        window.scrollTo(0, masterReportScrollY);
                    }
                }

                function preventMasterReportBackgroundWheel(event) {
                    if (!masterReportScrollLocked || isMasterReportModalScrollableTarget(event.target)) {
                        return;
                    }
                    event.preventDefault();
                }

                function preventMasterReportBackgroundTouch(event) {
                    if (!masterReportScrollLocked || isMasterReportModalScrollableTarget(event.target)) {
                        return;
                    }
                    event.preventDefault();
                }

                function preventMasterReportBackgroundKeys(event) {
                    if (!masterReportScrollLocked || isMasterReportModalScrollableTarget(event.target)) {
                        return;
                    }

                    const blockedKeys = ['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' ', 'Spacebar'];
                    if (blockedKeys.includes(event.key)) {
                        event.preventDefault();
                    }
                }

                function lockMasterReportScroll(scrollYOverride) {
                    if (masterReportScrollLocked) {
                        return;
                    }
                    const currentScroll = window.pageYOffset || document.documentElement.scrollTop || 0;
                    masterReportScrollY = typeof scrollYOverride === 'number' ? scrollYOverride : currentScroll;
                    if (!masterReportScrollGuardActive) {
                        window.addEventListener('scroll', maintainMasterReportScrollPosition, { passive: true });
                        document.addEventListener('wheel', preventMasterReportBackgroundWheel, { passive: false });
                        document.addEventListener('touchmove', preventMasterReportBackgroundTouch, { passive: false });
                        document.addEventListener('keydown', preventMasterReportBackgroundKeys, true);
                        masterReportScrollGuardActive = true;
                    }
                    masterReportScrollLocked = true;
                    requestAnimationFrame(function() {
                        window.scrollTo(0, masterReportScrollY);
                    });
                }

                function unlockMasterReportScroll() {
                    if (!masterReportScrollLocked) {
                        return;
                    }
                    if (masterReportScrollGuardActive) {
                        window.removeEventListener('scroll', maintainMasterReportScrollPosition, { passive: true });
                        document.removeEventListener('wheel', preventMasterReportBackgroundWheel, { passive: false });
                        document.removeEventListener('touchmove', preventMasterReportBackgroundTouch, { passive: false });
                        document.removeEventListener('keydown', preventMasterReportBackgroundKeys, true);
                        masterReportScrollGuardActive = false;
                    }
                    window.scrollTo(0, masterReportScrollY);
                    masterReportScrollLocked = false;
                }

                function cleanupMasterReportCaseModalArtifacts() {
                    if (document.querySelector('.sigma-modal--master-report-case-actions.show')) {
                        return;
                    }

                    document.documentElement.classList.remove('master-report-case-modal-open');
                    $('.modal-backdrop').remove();
                    $('body')
                        .removeClass('modal-open master-report-case-modal-open')
                        .css({
                            'padding-right': '',
                            'overflow': ''
                        });
                    unlockMasterReportScroll();
                }

                function mountMasterReportCaseModals() {
                    document.querySelectorAll('.sigma-modal--master-report-case-actions').forEach(function(modal) {
                        if (modal.parentNode !== document.body) {
                            document.body.appendChild(modal);
                        }
                    });
                }

                mountMasterReportCaseModals();

                $(document)
                    .off('show.bs.modal.masterReportCaseActions')
                    .on('show.bs.modal.masterReportCaseActions', '.sigma-modal--master-report-case-actions', function() {
                        if (!document.querySelector('.sigma-modal--master-report-case-actions.show')) {
                            $('.modal-backdrop').remove();
                        }
                        mountMasterReportCaseModals();
                        document.documentElement.classList.add('master-report-case-modal-open');
                        $('body')
                            .addClass('modal-open master-report-case-modal-open')
                            .css('overflow', 'hidden');
                        lockMasterReportScroll(masterReportScrollY);
                        $(this).removeAttr('aria-hidden');
                    });

                $(document)
                    .off('shown.bs.modal.masterReportCaseActions')
                    .on('shown.bs.modal.masterReportCaseActions', '.sigma-modal--master-report-case-actions', function() {
                        if (!masterReportScrollLocked) {
                            return;
                        }
                        requestAnimationFrame(function() {
                            window.scrollTo(0, masterReportScrollY);
                        });
                    });

                $(document)
                    .off('click.masterReportCaseActionsBackdrop')
                    .on('click.masterReportCaseActionsBackdrop', '.sigma-modal--master-report-case-actions', function(e) {
                        if (e.target === this) {
                            $(this).modal('hide');
                        }
                    });

                $(document)
                    .off('hidden.bs.modal.masterReportCaseActions')
                    .on('hidden.bs.modal.masterReportCaseActions', '.sigma-modal--master-report-case-actions', function() {
                        $(this)
                            .removeClass('show')
                            .css('display', 'none')
                            .attr('aria-hidden', 'true')
                            .removeAttr('aria-modal');
                        setTimeout(cleanupMasterReportCaseModalArtifacts, 0);
                    });

                $(document).on('click.masterReportCaseActionsLoading', '.sigma-modal--master-report-case-actions a.sigma-action-btn', function() {
                    const href = this.getAttribute('href');
                    const onclick = this.getAttribute('onclick') || '';
                    if (!href || href === '#' || this.hasAttribute('data-dismiss')) {
                        return;
                    }
                    if (onclick.includes('caseDelConfirmation')) {
                        return;
                    }
                    if (typeof window.showLoadingScreen === 'function') {
                        window.showLoadingScreen();
                    }
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
                            if (typeof window.showLoadingScreen === 'function') {
                                window.showLoadingScreen();
                            }
                            window.location = urlToRedirect;
                        }
                    });
                }

                // --- Employee/Device Filter Modal Logic ---
                let employeeFilterCount = 0;
                const employeesByStage = @json($employeesByStage);

                function setSelectValue(select, value) {
                    if (!select || value === undefined || value === null || value === '') {
                        return false;
                    }

                    const normalizedValue = String(value);
                    const matchingOption = Array.from(select.options).find(function(option) {
                        return String(option.value) === normalizedValue;
                    });

                    if (!matchingOption) {
                        return false;
                    }

                    select.value = matchingOption.value;
                    return true;
                }

                function buildEmployeeNameMap() {
                    const map = {};

                    Object.keys(employeesByStage || {}).forEach(function(stageKey) {
                        (employeesByStage[stageKey] || []).forEach(function(employee) {
                            if (!employee || employee.id === undefined || employee.id === null) {
                                return;
                            }

                            const firstName = (employee.first_name || '').trim();
                            const lastName = (employee.last_name || '').trim();
                            map[String(employee.id)] = (firstName || lastName || String(employee.id)).trim();
                        });
                    });

                    return map;
                }

                function buildDeviceNameMap() {
                    const map = {};

                    Object.keys(deviceTypes || {}).forEach(function(typeKey) {
                        (deviceTypes[typeKey] || []).forEach(function(device) {
                            if (!device || device.id === undefined || device.id === null) {
                                return;
                            }

                            map[String(device.id)] = device.name || String(device.id);
                        });
                    });

                    return map;
                }

                const employeeNameMap = buildEmployeeNameMap();
                const deviceNameMap = buildDeviceNameMap();

                function updateFilterTriggerLabel(kind, count) {
                    const label = document.getElementById(`${kind}-filter-trigger-label`);
                    if (!label) {
                        return;
                    }

                    const normalizedCount = Number(count || 0);
                    const isEmployee = kind === 'employees';
                    const defaultText = isEmployee ? 'Configure Employee Filters' : 'Configure Device Filters';
                    const singular = isEmployee ? 'Employee' : 'Device';
                    const plural = isEmployee ? 'Employees' : 'Devices';

                    if (normalizedCount > 0) {
                        label.textContent = `${normalizedCount} ${normalizedCount === 1 ? singular : plural}`;
                        label.classList.add('trigger-field__label--active');
                    } else {
                        label.textContent = defaultText;
                        label.classList.remove('trigger-field__label--active');
                    }
                }

                function addEmployeeFilterRow(initialFilter = null) {
                    employeeFilterCount++;
                    const container = document.getElementById('employee-filters-container');

                    const row = document.createElement('div');
                    row.className = 'row g-3 mb-3 employee-filter-row';
                    row.setAttribute('data-filter-id', employeeFilterCount);

                    row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Production Stage:</label>
                <select class="form-control stage-select" onchange="handleEmployeeStageChange(${employeeFilterCount})">
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
                <select class="form-control employee-select" disabled onchange="refreshAllEmployeeDropdowns()">
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

                    if (initialFilter) {
                        const stageSelect = row.querySelector('.stage-select');
                        const stageValue = initialFilter.stage || '';
                        const employeeValue = initialFilter.employee_id ?? initialFilter.employee ?? '';

                        if (stageValue && setSelectValue(stageSelect, stageValue)) {
                            updateEmployeeDropdown(employeeFilterCount, employeeValue);
                        }
                    }

                    refreshEmployeeStageOptions();
                    return employeeFilterCount;
                }

                function getSelectedEmployeeStages(currentFilterId = null) {
                    const selectedStages = new Set();

                    document.querySelectorAll('.employee-filter-row').forEach(row => {
                        const rowFilterId = row.getAttribute('data-filter-id');
                        if (currentFilterId !== null && String(rowFilterId) === String(currentFilterId)) {
                            return;
                        }

                        const stageSelect = row.querySelector('.stage-select');
                        if (stageSelect && stageSelect.value) {
                            selectedStages.add(stageSelect.value);
                        }
                    });

                    return selectedStages;
                }

                function refreshEmployeeStageOptions() {
                    document.querySelectorAll('.employee-filter-row').forEach(row => {
                        const filterId = row.getAttribute('data-filter-id');
                        const stageSelect = row.querySelector('.stage-select');
                        if (!stageSelect) {
                            return;
                        }

                        const currentValue = stageSelect.value;
                        const selectedStages = getSelectedEmployeeStages(filterId);

                        Array.from(stageSelect.options).forEach(option => {
                            if (!option.value) {
                                option.disabled = false;
                                return;
                            }

                            option.disabled = selectedStages.has(option.value) && option.value !== currentValue;
                        });
                    });
                }

                function handleEmployeeStageChange(filterId) {
                    refreshEmployeeStageOptions();
                    updateEmployeeDropdown(filterId);
                    refreshAllEmployeeDropdowns();
                }

                function getSelectedEmployeeIdsByStage(stage, currentFilterId = null) {
                    const selectedEmployeeIds = new Set();

                    document.querySelectorAll('.employee-filter-row').forEach(row => {
                        const rowFilterId = row.getAttribute('data-filter-id');
                        if (currentFilterId !== null && String(rowFilterId) === String(currentFilterId)) {
                            return;
                        }

                        const stageSelect = row.querySelector('.stage-select');
                        const employeeSelect = row.querySelector('.employee-select');

                        if (stageSelect && employeeSelect && stageSelect.value === stage && employeeSelect.value) {
                            selectedEmployeeIds.add(String(employeeSelect.value));
                        }
                    });

                    return selectedEmployeeIds;
                }

                function refreshAllEmployeeDropdowns() {
                    refreshEmployeeStageOptions();
                    document.querySelectorAll('.employee-filter-row').forEach(row => {
                        const filterId = row.getAttribute('data-filter-id');
                        const employeeSelect = row.querySelector('.employee-select');
                        updateEmployeeDropdown(filterId, employeeSelect ? employeeSelect.value : '');
                    });
                }

                function updateEmployeeDropdown(filterId, selectedEmployeeId = '') {
                    const stageSelect = document.querySelector(`[data-filter-id="${filterId}"] .stage-select`);
                    const employeeSelect = document.querySelector(`[data-filter-id="${filterId}"] .employee-select`);

                    const selectedStage = stageSelect.value;
                    const currentValue = selectedEmployeeId || employeeSelect.value || '';
                    const selectedEmployeeIds = getSelectedEmployeeIdsByStage(selectedStage, filterId);

                    employeeSelect.innerHTML = '<option value="">Select Employee</option>';

                    if (selectedStage && employeesByStage[selectedStage]) {
                        employeeSelect.disabled = false;

                        employeesByStage[selectedStage].forEach(employee => {
                            const option = document.createElement('option');
                            option.value = employee.id;
                            option.textContent = employee.first_name + ' ' + employee.last_name;

                            if (
                                selectedEmployeeIds.has(String(employee.id)) &&
                                String(employee.id) !== String(currentValue)
                            ) {
                                option.disabled = true;
                            }

                            employeeSelect.appendChild(option);
                        });

                        setSelectValue(employeeSelect, currentValue);
                    } else {
                        employeeSelect.disabled = true;
                    }
                }

                function removeEmployeeFilterRow(filterId) {
                    const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                    if (row) {
                        row.remove();
                        refreshAllEmployeeDropdowns();
                    }
                }

                document.getElementById('add-employee-filter').addEventListener('click', function() {
                    addEmployeeFilterRow();
                });

                // Device filter management
                function getSelectedDeviceTypes(currentFilterId = null) {
                    const selectedTypes = new Set();

                    document.querySelectorAll('.device-filter-row').forEach(row => {
                        const rowFilterId = row.getAttribute('data-filter-id');
                        if (currentFilterId !== null && String(rowFilterId) === String(currentFilterId)) {
                            return;
                        }

                        const typeSelect = row.querySelector('.device-type-select');
                        if (typeSelect && typeSelect.value) {
                            selectedTypes.add(typeSelect.value);
                        }
                    });

                    return selectedTypes;
                }

                function refreshDeviceTypeOptions() {
                    document.querySelectorAll('.device-filter-row').forEach(row => {
                        const filterId = row.getAttribute('data-filter-id');
                        const typeSelect = row.querySelector('.device-type-select');
                        if (!typeSelect) {
                            return;
                        }

                        const currentValue = typeSelect.value;
                        const selectedTypes = getSelectedDeviceTypes(filterId);

                        Array.from(typeSelect.options).forEach(option => {
                            if (!option.value) {
                                option.disabled = false;
                                return;
                            }

                            option.disabled = selectedTypes.has(option.value) && option.value !== currentValue;
                        });
                    });
                }

                function getSelectedDeviceIdsByType(deviceType, currentFilterId = null) {
                    const selectedDeviceIds = new Set();

                    document.querySelectorAll('.device-filter-row').forEach(row => {
                        const rowFilterId = row.getAttribute('data-filter-id');
                        if (currentFilterId !== null && String(rowFilterId) === String(currentFilterId)) {
                            return;
                        }

                        const typeSelect = row.querySelector('.device-type-select');
                        const deviceSelect = row.querySelector('.device-select');

                        if (typeSelect && deviceSelect && typeSelect.value === deviceType && deviceSelect.value) {
                            selectedDeviceIds.add(String(deviceSelect.value));
                        }
                    });

                    return selectedDeviceIds;
                }

                function refreshAllDeviceDropdowns() {
                    refreshDeviceTypeOptions();
                    document.querySelectorAll('.device-filter-row').forEach(row => {
                        const filterId = row.getAttribute('data-filter-id');
                        const deviceSelect = row.querySelector('.device-select');
                        updateDeviceDropdown(filterId, deviceSelect ? deviceSelect.value : '');
                    });
                }

                function handleDeviceTypeChange(filterId) {
                    refreshDeviceTypeOptions();
                    updateDeviceDropdown(filterId);
                    refreshAllDeviceDropdowns();
                }

                function addDeviceFilterRow(initialFilter = null) {
                    deviceFilterCount++;
                    const container = document.getElementById('device-filters-container');

                    const row = document.createElement('div');
                    row.className = 'row g-3 mb-3 device-filter-row';
                    row.setAttribute('data-filter-id', deviceFilterCount);

                    row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Device Type:</label>
                <select class="form-control device-type-select" onchange="handleDeviceTypeChange(${deviceFilterCount})">
                    <option value="">Select Device Type</option>
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
                <select class="form-control device-select" disabled onchange="refreshAllDeviceDropdowns()">
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

                    if (initialFilter) {
                        const typeSelect = row.querySelector('.device-type-select');
                        const typeValue = initialFilter.type ?? initialFilter.stage ?? '';
                        const deviceValue = initialFilter.device_id ?? initialFilter.device ?? '';

                        if (typeValue && setSelectValue(typeSelect, typeValue)) {
                            updateDeviceDropdown(deviceFilterCount, deviceValue);
                        }
                    }

                    refreshDeviceTypeOptions();
                    refreshAllDeviceDropdowns();
                    return deviceFilterCount;
                }

                function updateDeviceDropdown(filterId, selectedDeviceId = '') {
                    const typeSelect = document.querySelector(`[data-filter-id="${filterId}"] .device-type-select`);
                    const deviceSelect = document.querySelector(`[data-filter-id="${filterId}"] .device-select`);

                    if (!typeSelect || !deviceSelect) {
                        return;
                    }

                    const selectedType = typeSelect.value;
                    const currentValue = selectedDeviceId || deviceSelect.value || '';
                    const selectedDeviceIds = getSelectedDeviceIdsByType(selectedType, filterId);

                    deviceSelect.innerHTML = '<option value="">Select Device</option>';

                    if (selectedType && deviceTypes && deviceTypes[selectedType]) {
                        deviceSelect.disabled = false;

                        const devices = deviceTypes[selectedType];

                        if (devices && devices.length > 0) {
                            devices.forEach(device => {
                                const option = document.createElement('option');
                                option.value = device.id;
                                option.textContent = device.name;

                                if (
                                    selectedDeviceIds.has(String(device.id)) &&
                                    String(device.id) !== String(currentValue)
                                ) {
                                    option.disabled = true;
                                }

                                deviceSelect.appendChild(option);
                            });

                            setSelectValue(deviceSelect, currentValue);
                        } else {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = 'No devices available';
                            option.disabled = true;
                            deviceSelect.appendChild(option);
                        }
                    } else {
                        deviceSelect.disabled = true;
                    }
                }

                function removeDeviceFilterRow(filterId) {
                    const row = document.querySelector(`[data-filter-id="${filterId}"]`);
                    if (row) {
                        row.remove();
                        refreshAllDeviceDropdowns();
                    }
                }

                document.getElementById('add-device-filter').addEventListener('click', function() {
                    addDeviceFilterRow();
                });

                function initializeEmployeeFiltersState() {
                    const initialFilters = Array.isArray(window.initialEmployeeFilters) ? window.initialEmployeeFilters : [];
                    employeeFilterCount = 0;
                    const container = document.getElementById('employee-filters-container');
                    if (container) {
                        container.innerHTML = '';
                    }

                    if (initialFilters.length > 0) {
                        initialFilters.forEach(filter => {
                            addEmployeeFilterRow(filter);
                        });
                        applyEmployeeFilters();
                    } else {
                        addEmployeeFilterRow();
                    }
                }

                function initializeDeviceFiltersState() {
                    const initialFilters = Array.isArray(window.initialDeviceFilters) ? window.initialDeviceFilters : [];
                    deviceFilterCount = 0;
                    const container = document.getElementById('device-filters-container');
                    if (container) {
                        container.innerHTML = '';
                    }

                    if (initialFilters.length > 0) {
                        initialFilters.forEach(filter => {
                            addDeviceFilterRow(filter);
                        });
                        refreshAllDeviceDropdowns();
                        applyDeviceFilters();
                    } else {
                        addDeviceFilterRow();
                    }
                }

                function applyEmployeeFilters() {
                    const filterRows = document.querySelectorAll('.employee-filter-row');
                    const hiddenContainer = document.getElementById('hidden-employee-filters');
                    const stageNames = {
                        design: 'Design',
                        milling: 'Milling',
                        printing: '3D Printing',
                        sintering: 'Sintering',
                        pressing: 'Pressing',
                        finishing: 'Finishing',
                        qc: 'QC',
                        delivery: 'Delivery'
                    };

                    hiddenContainer.innerHTML = '';

                    let activeFilters = 0;
                    let filterIndex = 0;
                    const selectedStageEmployeePairs = [];

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

                            const employeeName = employeeNameMap[String(employeeSelect.value)];
                            const stageName = stageNames[stageSelect.value] || stageSelect.value;
                            if (employeeName && stageName) {
                                selectedStageEmployeePairs.push(`${stageName}:${employeeName}`);
                            }

                            activeFilters++;
                            filterIndex++;
                        }
                    });

                    const summary = document.getElementById('employees-filter-summary');
                    if (activeFilters > 0) {
                        const uniquePairs = Array.from(new Set(selectedStageEmployeePairs));
                        summary.textContent = `[Emp: ${uniquePairs.join(' AND ')}]`;
                        summary.className = 'filter-summary filter-pill active d-none';
                    } else {
                        summary.textContent = 'No employee filters applied';
                        summary.className = 'filter-summary filter-pill muted d-none';
                    }
                    updateFilterTriggerLabel('employees', activeFilters);
                    updateFilterIndicators();
                    persistMasterReportFilters();
                }

                function applyDeviceFilters() {
                    const filterRows = document.querySelectorAll('.device-filter-row');
                    const hiddenContainer = document.getElementById('hidden-device-filters');

                    hiddenContainer.innerHTML = '';

                    let activeFilters = 0;
                    let filterIndex = 0;
                    const selectedNames = [];

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

                            const deviceName = deviceNameMap[String(deviceSelect.value)];
                            if (deviceName) {
                                selectedNames.push(deviceName);
                            }

                            activeFilters++;
                            filterIndex++;
                        }
                    });

                    const summary = document.getElementById('devices-filter-summary');
                    if (activeFilters > 0) {
                        const uniqueNames = Array.from(new Set(selectedNames));
                        summary.textContent = `[Devices: ${uniqueNames.join(' AND ')}]`;
                        summary.className = 'filter-summary filter-pill active d-none';
                    } else {
                        summary.textContent = 'All devices included';
                        summary.className = 'filter-summary filter-pill muted d-none';
                    }
                    updateFilterTriggerLabel('devices', activeFilters);
                    updateFilterIndicators();
                    persistMasterReportFilters();
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




