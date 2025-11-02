@extends('layouts.app', ['pageSlug' => 'Master Report'])

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css" rel="stylesheet">
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
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
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
            background: linear-gradient(135deg, #408385 0%, #2d5f61 100%);
            border-radius: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(64, 131, 133, 0.3);
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
            background: #408284;
            color: white;
        }


        .badge-warning {
            background: #408284;
            color: white;
        }

        .badge-primary {
            background: #408284;
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

        /* === TABLE STICKY FIXES START === */

        /* Master Report Table Header Styles - Match other reports sizing */
        #master-report-table thead th {
            font-weight: 600;
            font-size: 16px;
            padding: 18px 16px;
            vertical-align: middle;
            white-space: nowrap;
            min-height: 50px;
            position: relative; /* Default positioning for non-sticky headers */
        }

        /* Body cells - normal size */
        #master-report-table tbody td {
            font-size: 15px;
            padding: 14px 16px;
            vertical-align: middle;
            min-height: 45px;
            white-space: nowrap; /* ADDED: Ensure cells don't wrap */
        }

        /* Dark headers */
        #master-report-table thead th.header-dark,
        table#master-report-table thead th.header-dark {
            background-color: #408385 !important;
            background: #408385 !important;
            color: white !important;
            border: none !important;
        }

        /* Light headers */
        #master-report-table thead th.header-light,
        table#master-report-table thead th.header-light {
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

        /* Sticky positioning for first 2 columns (Case ID, Doctor) */
        #master-report-table thead th:nth-child(1),
        #master-report-table tbody td:nth-child(1) {
            position: sticky !important;
            left: 0 !important;
            z-index: 10 !important;
            /* FIX: Background must be applied for sticky to cover content */
            background-color: #408385 !important;
        }

        #master-report-table thead th:nth-child(2),
        #master-report-table tbody td:nth-child(2) {
            position: sticky !important;
            /* left position set by JavaScript */
            z-index: 10 !important;
            /* FIX: Background must be applied for sticky to cover content */
            background-color: #408385 !important;
        }

        /* FIX: Removed rules for :nth-child(3) */

        /* Maintain proper styling for sticky columns */
        #master-report-table tbody tr:nth-child(even) td:nth-child(1),
        #master-report-table tbody tr:nth-child(even) td:nth-child(2) {
            background-color: #f8fafb !important;
        }

        #master-report-table tbody tr:nth-child(odd) td:nth-child(1),
        #master-report-table tbody tr:nth-child(odd) td:nth-child(2) {
            background-color: #ffffff !important;
        }

        #master-report-table tbody tr:hover td:nth-child(1),
        #master-report-table tbody tr:hover td:nth-child(2) {
            background-color: rgba(64, 131, 133, 0.08) !important;
        }

        /* Ensure header cells for sticky columns have proper background */
        #master-report-table thead th:nth-child(1).header-dark,
        #master-report-table thead th:nth-child(2).header-dark {
            background-color: #408385 !important;
            color: white !important;
        }

        /* Table container - enable horizontal scroll */
        .sigma-report-table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
            position: relative;
            max-width: 100%;
        }

        /* Ensure table doesn't inherit transforms that break position:sticky */
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
            overflow: visible !important;
        }

        #master-report-table thead {
            display: table-header-group !important;
        }

        /* === TABLE STICKY FIXES END === */


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
        }
    </style>

    <div class="master-report-container">
        <div class="modern-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line"></i>
                    Master Report
                </h3>
            </div>

            <form class="modern-form" method="GET" action="{{route('master-report')}}" id="master-report-form">
                <input type="hidden" name="generate_report" value="1">

                <div id="hidden-employee-filters"></div>

                <div id="hidden-device-filters"></div>

                <script>
                    window.initialMaterialTypes = @json(request('material_type', []));
                </script>

                <div class="form-section basic-filters">
                    <h2 class="section-title">
                        <i class="fas fa-filter"></i>
                        Basic Filters
                    </h2>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Date Range
                                </label>
                                <input type="text" class="modern-input flatpickr-input" id="daterange" readonly style="width: 100%;">
                                <input type="hidden" name="from" id="from-date" value="{{request('from', $from)}}">
                                <input type="hidden" name="to" id="to-date" value="{{request('to', $to)}}">
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-user-md"></i>
                                    Doctor
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
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tooth"></i>
                                    Material
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
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-cog"></i>
                                    Job Type
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
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-layer-group"></i>
                                    Material Type
                                </label>
                                <select class="modern-select select2-multiple" multiple name="material_type[]" id="material_type">
                                    <option value="all">All Material Types</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Failure Type
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
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-plug"></i>
                                    Abutments
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
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tooth"></i>
                                    Implants
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
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-tasks"></i>
                                    Workflow Stage
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
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign"></i>
                                    Invoice Amount
                                </label>
                                <div class="row" style="padding-left: 0">
                                    <div class="col-6" style="padding-left: 0;">
                                        <input type="number" class="modern-input" name="amount_from" id="amount_from"
                                               placeholder="From JOD" value="{{request('amount_from')}}" min="0" step="0.01">
                                    </div>

                                    <div class="col-6" style="padding-left: 0;">
                                        <input type="number" class="modern-input" name="amount_to" id="amount_to"
                                               placeholder="To JOD" value="{{request('amount_to')}}" min="0" step="0.01">
                                    </div>
                                </div>
                                <small class="text-danger" id="amount-range-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i> "From" amount cannot be greater than "To" amount
                                </small>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-cubes"></i>
                                    Number of Units
                                </label>
                                <div class="row" style="padding-left: 0;">
                                    <div class="col-6" style="padding-left: 0;">
                                        <input type="number" class="modern-input" name="units_from" id="units_from"
                                               placeholder="From" value="{{request('units_from')}}" min="0" step="1">
                                    </div>
                                    <div class="col-6" style="padding-left: 0;">
                                        <input type="number" class="modern-input" name="units_to" id="units_to"
                                               placeholder="To" value="{{request('units_to')}}" min="0" step="1">
                                    </div>
                                </div>
                                <small class="text-danger" id="units-range-error" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i> "From" units cannot be greater than "To" units
                                </small>
                            </div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-users"></i>
                                    Employees Filter
                                </label>
                                <button type="button" class="modern-input" style="width: 100%; text-align: left; background: white; border: 1px solid #d1d5db; color: #374151; height: 36px; padding: 8px 12px; border-radius: 6px;" data-toggle="modal" data-target="#employeesFilterModal">
                                    Configure Employee Filters
                                </button>
                                <div id="employees-filter-summary" class="filter-summary" style="font-size: 12px; color: #6b7280; margin-top: 4px;">No employee filters applied</div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-microchip"></i>
                                    Devices Filter
                                </label>
                                <button type="button" class="modern-input" style="width: 100%; text-align: left; background: white; border: 1px solid #d1d5db; color: #374151; height: 36px; padding: 8px 12px; border-radius: 6px;" data-toggle="modal" data-target="#devicesFilterModal">
                                    Configure Device Filters
                                </button>
                                <div id="devices-filter-summary" class="filter-summary" style="font-size: 12px; color: #6b7280; margin-top: 4px;">All devices included</div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-check-circle"></i>
                                    Case Completion
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

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="form-group" style="display: flex; align-items: flex-end; height: 100%;">
                                <button type="submit" class="modern-btn btn-primary" style="width: 100%; height: 40px; font-size: 14px; font-weight: 600; background: linear-gradient(135deg, #408385 0%, #2d5f61 100%); border: none; box-shadow: 0 4px 12px rgba(64, 131, 133, 0.3); transition: all 0.3s ease; border-radius: 6px;">
                                    <i class="fas fa-chart-line" style="margin-right: 6px;"></i>
                                    Generate Report
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
        </div>




        </form>
    </div> @if($cases->count() > 0)
        <div class="modern-card" style="margin-top: 16px;">
            <div class="card-header" style="border-bottom: 1px solid #e2e8f0; padding: 12px 24px; background: white;">
                <div class="d-flex justify-content-between align-items-center"> <h4 style="font-weight: 600; color: #1a202c; margin: 0;">Report Results</h4>
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
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
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-material" data-column="3" checked>
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
                                <div class="form-check">
                                    <input class="form-check-input column-toggle" type="checkbox" id="col-actions" data-column="21" checked>
                                    <label class="form-check-label" for="col-actions">Actions</label>
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
                        <th class="header-dark">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
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
                            $createdDate = $case->created_at ? date('Y-m-d', strtotime($case->created_at)): '-';
                            $deliveryDate =date('Y-m-d', strtotime($case->actual_delivery_date))  ?? date('Y-m-d', strtotime($case->initial_delivery_date ))?? '-';
                        @endphp
                        <tr>
                            <td class="text-left"><strong>{{$case->id}}</strong></td> <td class="text-left">{{$case->client->name ?? 'N/A'}}</td> <td class="text-left">{{$case->patient_name}}</td> <td class="text-center">{{$materialsStr}}</td>
                            <td class="text-center">{{$jobTypesStr}}</td>
                            <td class="text-center">{{$createdDate}}</td>
                            <td class="text-center">{{$deliveryDate}}</td>
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
                                @php
                                    $stageNames = [
                                        1 => 'Design',
                                        2 => 'Milling',
                                        3 => '3D Printing',
                                        4 => 'Sintering',
                                        5 => 'Pressing',
                                        6 => 'Finishing',
                                        7 => 'QC',
                                        8 => 'Delivery',
                                        -1 => 'Completed'
                                    ];

                                    // Check if case is completed
                                    $isCompleted = $case->actual_delivery_date && $case->jobs->every(function($job) {
                                        return $job->stage == -1;
                                    });

                                    if ($isCompleted) {
                                        $stageName = 'Completed';
                                        $badgeClass = 'success';
                                    } else {
                                        $maxStage = $case->jobs->max('stage');
                                        $stageName = $stageNames[$maxStage] ?? 'Unknown';
                                        $badgeClass = $maxStage > 7 ? 'success' : ($maxStage < 3 ? 'warning' : 'primary');
                                    }
                                @endphp
                                <span class="status-badge badge-{{$badgeClass}}">
                                            {{$stageName}}
                                        </span>
                            </td>
                            <td class="text-center"><strong>{{abs($case->invoice->amount ?? 0)}}</strong></td>
                            <td class="text-center">
                                <a href="/cases/{{$case->id}}" target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="totals-row">
                        <td colspan="21" class="text-right"><strong>Total Cases: {{$cases->count()}}</strong></td>
                        <td class="text-center"><strong>{{$cases->sum(function($case) { return abs($case->invoice->amount ?? 0); })}}</strong></td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
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

        <div class="modal fade" id="employeesFilterModal" tabindex="-1" aria-labelledby="employeesFilterModalLabel" aria-hidden="true">
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

        <div class="modal fade" id="devicesFilterModal" tabindex="-1" aria-labelledby="devicesFilterModalLabel" aria-hidden="true">
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

            <script>
                // Initialize completion toggle
                function initializeCompletionToggle() {
                    const $toggle = $('#completion_toggle');
                    const $hidden = $('#show_completed_hidden');
                    const currentValue = $toggle.attr('data-value') || 'all';

                    // Set initial active state
                    updateToggleState(currentValue);

                    // Handle clicks on toggle options
                    $('.toggle-option').on('click', function() {
                        const value = $(this).attr('data-value');
                        $toggle.attr('data-value', value);
                        $hidden.val(value);
                        updateToggleState(value);
                    });

                    function updateToggleState(value) {
                        // Update active class on options
                        $('.toggle-option').removeClass('active');
                        $(`.toggle-option[data-value="${value}"]`).addClass('active');
                    }
                }

                // Initialize modern components
                $(document).ready(function() {
                    function initializeComponents() {
                        initializeSelect2();
                        initializeFlatpickr();
                        initializeDataTable(); // This will now initialize *without* FixedColumns
                        addEmployeeFilterRow(); // Initialize with one employee filter row
                        addDeviceFilterRow(); // Initialize with one device filter row
                        initializeRangeValidation();
                        initializeColumnVisibility();
                        initializeCompletionToggle();
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
                            $(this).select2({
                                placeholder: 'Select options...',
                                allowClear: true,
                                width: '100%',
                                closeOnSelect: false,
                                multiple: true,
                                dropdownParent: $(this).closest('.form-group'), // Attach to form-group
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
                            dropdownParent: $materialType.closest('.form-group')
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
                    } else {
                        const table = $('#master-report-table');
                        if (isVisible) {
                            table.find(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`).show();
                        } else {
                            table.find(`th:nth-child(${columnIndex + 1}), td:nth-child(${columnIndex + 1})`).hide();
                        }
                    }
                    // FIX: Recalculate sticky positions after toggling
                    fixStickyColumnPositions();
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
                        scrollX: false, // FIX: Set to false. Scrolling is handled by the CSS on .sigma-report-table-container
                        scrollY: false,
                        scrollCollapse: false,
                        autoWidth: false,
                        order: [[0, 'asc']],
                        orderCellsTop: true,
                        columnDefs: [
                            { targets: '_all', className: 'text-center' },
                            { targets: [0, 1], className: 'text-left' }, // FIX: Only first 2 columns are text-left
                            { targets: [0, 1, 2, 3, 4, 5, 6, 19, 20], orderable: true },
                            { targets: [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 21], orderable: false }
                        ],
                        drawCallback: function() {
                            // Apply header styling after DataTables draws
                            $('#master-report-table thead th.header-light').css({
                                'background-color': 'transparent',
                                'background': 'none',
                                'border': 'none',
                                'border-bottom': '2px solid #408385',
                                'color': '#408385'
                            });
                            $('#master-report-table thead th.header-dark').css({
                                'background-color': '#408385',
                                'background': '#408385',
                                'color': 'white',
                                'border': 'none'
                            });
                        },
                        // FIX: Use initComplete to apply preferences and fix positions
                        "initComplete": function(settings, json) {
                            // Apply column visibility preferences
                            $('.column-toggle').each(function() {
                                const columnIndex = $(this).data('column');
                                const isVisible = $(this).is(':checked');
                                window.masterReportTable.column(columnIndex).visible(isVisible);
                            });
                            // Fix sticky column positions after table is drawn and visibility is set
                            fixStickyColumnPositions();
                        }
                    });

                    window.masterReportTable.buttons().container().appendTo('.export-buttons');

                    // Apply header styling immediately after initialization
                    setTimeout(() => {
                        $('#master-report-table thead th.header-light').css({
                            'background-color': 'transparent',
                            'background': 'none',
                            'border': 'none',
                            'border-bottom': '2px solid #408385',
                            'color': '#408385'
                        });
                        $('#master-report-table thead th.header-dark').css({
                            'background-color': '#408385',
                            'background': '#408385',
                            'color': 'white',
                            'border': 'none'
                        });

                        // Fix sticky column positions
                        fixStickyColumnPositions();
                        // Load preferences *after* table is built
                        loadColumnPreferences();
                    }, 100);
                    @endif
                }

                // FIX: Modified this function to only handle 2 columns
                function fixStickyColumnPositions() {
                    let leftOffset = 0;
                    const $table = $('#master-report-table');

                    // Handle Column 1
                    const $col1 = $table.find('thead th:nth-child(1)');
                    if ($col1.is(':visible')) {
                        $table.find('thead th:nth-child(1), tbody td:nth-child(1)').css('left', '0px');
                        leftOffset = $col1.outerWidth();
                    } else {
                        $table.find('thead th:nth-child(1), tbody td:nth-child(1)').css('left', 'auto');
                    }

                    // Handle Column 2
                    const $col2 = $table.find('thead th:nth-child(2)');
                    if ($col2.is(':visible')) {
                        $table.find('thead th:nth-child(2), tbody td:nth-child(2)').css('left', leftOffset + 'px');
                    } else {
                        $table.find('thead th:nth-child(2), tbody td:nth-child(2)').css('left', 'auto');
                    }

                    // Recalculate on window resize
                    $(window).off('resize.stickyColumns').on('resize.stickyColumns', function() {
                        // Use a timeout to ensure widths are correct after resize
                        setTimeout(fixStickyColumnPositions, 0);
                    });
                }

                // Case viewing function
                function viewMasterReportCase(caseId) {
                    if (confirm(`View details for Case ID: ${caseId}?`)) {
                        window.open(`/cases/${caseId}`, '_blank');
                    }
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
                        summary.className = 'filter-summary text-success';
                    } else {
                        summary.textContent = 'No employee filters applied';
                        summary.className = 'filter-summary text-muted';
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
                        summary.className = 'filter-summary text-success';
                    } else {
                        summary.textContent = 'All devices included';
                        summary.className = 'filter-summary text-muted';
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
