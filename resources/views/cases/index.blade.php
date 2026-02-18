@extends('layouts.app' ,[ 'pageSlug' => "Cases List"])

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        /* Customizations */
        .sigma-sticky-toolbar {
            top: 55px !important; /* Adjust if header height changes */
        }

        #casesTable {
            font-family: 'Roboto', sans-serif;
        }



        #casesTable thead th:first-child,
        #casesTable tbody td:first-child {
            padding-left: 10px !important; /* Add more left padding */
        }

        /* Move sorting icons closer */
        #casesTable thead .sorting::after,
        #casesTable thead .sorting_asc::after,
        #casesTable thead .sorting_desc::after {
            right: 8px;
        }

        #casesTable thead .sorting::before,
        #casesTable thead .sorting_asc::before,
        #casesTable thead .sorting_desc::before {
            right: 16px;
        }


        /* Specific alignments for Tags and Status columns */
        .tagsHeader, .tagsTD { /* Tags column */
            text-align: left !important;
            direction: ltr !important;
        }

        #casesTable thead th:nth-child(6) { /* Status column Header */
            text-align: center !important;
        }

        #casesTable tbody td:nth-child(6) { /* Status column Body */
            text-align: center !important;
        }

        #casesTable tbody td:nth-child(6) .sigma-case-status-badge {
            margin: 0 auto !important;
        }

        /* End Customizations */

        #casesTable {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-collapse: separate; /* To respect border-radius */
            border-spacing: 0;
        }

        #casesTable td {
            padding: 14px 10px; /* Adjusted padding */
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        #casesTable tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Remove zebra-striping from dataTables to use our own hover */
        #casesTable.table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Regular.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Medium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Semibold.otf') format('opentype');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Thin.otf') format('opentype');
            font-weight: 100;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Regular.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Medium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        .cases-datetime-picker {
            font-family: 'NewYorkSmall', 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .cases-datetime-picker * {
            font-family: inherit;
        }

        .cases-datetime-picker .ios-picker-header, .cases-datetime-picker .ios-picker-btn, .cases-datetime-picker .ios-wheel li {
            font-family: 'NewYorkSmall', 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .cases-datetime-picker .ios-picker-btn {
            font-weight: 600;
        }

        .cases-datetime-picker .ios-wheel li.selected {
            font-weight: 700;
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

        #casesTable {
            table-layout: auto;
            width: 100% !important;
        }

        /* Ensure table wrapper allows horizontal scroll without clipping */
        .dataTables_wrapper {
            overflow-x: auto;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .cases-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
        }

        .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        /* Ensure parent container doesn't clip */
        #casesTable_wrapper {
            overflow-x: auto;
            max-width: 100%;
        }

        /* Hide DataTables responsive control column (green button) */
        #casesTable td.dtr-control,
        #casesTable th.dtr-control,
        #casesTable td.dtr-control:before,
        #casesTable_wrapper .dtr-control {
            display: none !important;
        }

        /* Ensure no control column is added by DataTables responsive */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            display: none !important;
        }

        #casesTable {
            font-family: 'Cairo', sans-serif;
        }

        #casesTable .cases-cell-truncate {
            display: inline-block;
            max-width: 16rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            font-weight: 600;
        }


        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 42px; /* 30% smaller than 60px */
            height: 23.8px; /* 30% smaller than 34px */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18.2px; /* 30% smaller than 26px */
            width: 18.2px; /* 30% smaller than 26px */
            left: 2.8px; /* 30% smaller than 4px */
            bottom: 2.8px; /* 30% smaller than 4px */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #317d7f; /* New background color */
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #317d7f; /* Updated shadow color */
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(18.2px); /* 30% smaller than 26px */
            -ms-transform: translateX(18.2px);
            transform: translateX(18.2px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 23.8px; /* 30% smaller than 34px */
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .tooltip-toggle-container {
            position: absolute;
            top: 8px;
            right: 50px;
            z-index: 10;
            display: flex;
            align-items: center;
            flex-direction: row-reverse; /* Label on left */
        }

        .tooltip-toggle-container label {
            margin-right: 10px; /* Adjusted margin */
            color: #495057;
            font-weight: 600;
            white-space: nowrap; /* Prevent label from wrapping */
        }

        /* Fix modal positioning - ensure modal is not affected by parent transforms */
        .sigma-modal--cases-index-actions {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            transform: none !important;
            -webkit-transform: none !important;
            will-change: auto !important;
            display: none;
            overflow-x: hidden;
            overflow-y: auto;
            z-index: 1050;
            background: transparent;
        }

        .sigma-modal--cases-index-actions.show {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .sigma-modal--cases-index-actions .modal-dialog {
            position: relative !important;
            width: auto !important;
            max-width: 500px !important;
            margin: 1.75rem !important;
            transform: none !important;
            -webkit-transform: none !important;
            will-change: auto !important;
            pointer-events: none !important;
        }

        .sigma-modal--cases-index-actions .modal-content {
            position: relative !important;
            transform: none !important;
            -webkit-transform: none !important;
            will-change: auto !important;
            pointer-events: auto !important;
        }

        /* Reset transforms on wrapper when modal is open */
        body.modal-open .wrapper,
        body.modal-open .main-panel {
            transform: none !important;
            -webkit-transform: none !important;
        }

        /* Modal dialog border radius - all corners uniform */

        .sigma-modal--cases-index-actions .modal-content {
            border-radius: 25px !important;
        }

        /* Modal title styling */

        .sigma-modal--cases-index-actions .modal-title {
            color: #2d5f6d;
            font-weight: 600;
            font-size: 18px;
        }

        .badge .badge-success {
            width: 7vw !important;
        }

        /* Modal header styling with divider */

        .sigma-modal--cases-index-actions .modal-header {
            border-bottom: 0 !important;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        /* Doctor/Patient names styling */
        .patient-doctor-names {
            color: #2d5f6d;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
        }

        .patient-doctor-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6c757d;
            margin-bottom: 2px;
            display: block;
        }

        /* Scrollable section for jobs and notes */
        .scrollable-content {
            max-height: 40vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Notes container styling */
        .form-control.note-container {
            background-color: #e8f0f2;
            border: 1px solid #b8d4db;
            color: #212529;
        }

        /* Modal footer rounded bottom corners */

        .sigma-modal--cases-index-actions .modal-footer {

            border-bottom-left-radius: 25px !important;
            border-bottom-right-radius: 25px !important;
        }


        .sigma-modal--cases-index-actions .modal-footer .btn {
            min-width: 120px;
            margin: 0;
            display: flex;
            grid-template-columns: 50% 50%;
            align-items: center;
            column-gap: 8px;
            justify-content: center;

        }

        .sigma-modal--cases-index-actions .modal-footer .btn .btn-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sigma-modal--cases-index-actions .modal-footer .btn .btn-text {
            white-space: nowrap;
            text-align: left;
        }

        .content {

        }

        /* Tooltip styling */
        .case-jobs-tooltip {
            display: none;
            position: absolute;
            background-color: rgba(255, 255, 255, 0.98); /* Slightly transparent white */
            border: 1px solid #e0e0e0;
            padding: 12px; /* Increased padding a bit for a less cramped feel */
            z-index: 1000;
            width: 340px; /* Adjusted width */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); /* Softer, more pronounced shadow */
            border-radius: 10px; /* Smoother radius */
            font-size: 14px; /* A more readable base font size */
            color: #2c3e50;
            backdrop-filter: blur(5px); /* Frosted glass effect */
            -webkit-backdrop-filter: blur(5px);
        }

        .case-jobs-tooltip table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .case-jobs-tooltip th, .case-jobs-tooltip td {
            border: none; /* No cell borders */
            padding: 10px 12px; /* More generous padding */
            text-align: left;
            border-bottom: 1px solid #ecf0f1; /* Light row separator */
        }

        .case-jobs-tooltip th {
            background-color: transparent; /* No header background color */
            font-weight: 600;
            font-size: 13px;
            color: #34495e; /* Darker, more professional header text */
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .case-jobs-tooltip tr:last-child td {
            border-bottom: none; /* No border for the last row */
        }

        .case-jobs-tooltip tr:hover {
            background-color: #f8f9fa; /* Very subtle hover effect */
        }

        /* Button improvements */
        .btn-outline-danger, .btn-outline-secondary {
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        /* Compact filter section */
        .cases-filter-row {
            padding: 11px 8px !important;
            align-items: center;
        }

        .cases-filter-row .form-control,
        .cases-filter-row .dtp-input {
            height: 36px !important;
            font-size: 13px !important;
            padding: 4px 10px !important;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }

        .cases-filter-row .bootstrap-select > .dropdown-toggle {
            height: 36px !important;
            padding: 4px 10px !important;
            font-size: 13px !important;
            border-radius: 4px;
            display: flex;
            align-items: center;
        }

        .cases-filter-btn {
            width: 100%;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
        }

        .cases-filter-row .mb-2 {
            margin-bottom: 6px !important;
        }

        #cases_from.dtp-input, #cases_to.dtp-input {
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.5;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            border: 1px solid #ced4da;
            background-color: #fff;
            color: #495057;
            cursor: pointer;
        }

        #cases_from.dtp-input:focus, #cases_to.dtp-input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        /* Filter container with subtle shadow and border */
        .container.full-width {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            position: relative;
        }

        .sunriseTable tbody tr td {
            padding: 4px 0 !important;
        }

        /*.case-row{font-weight: 400 !important;}*/

        /* Better spacing */
        .filter-section {
            margin-bottom: 1.5rem;
        }

        /* Button groups styling */
        .btn-group .btn {
            margin-left: 5px;
        }

        div.modal-content {
            padding: 16px 3px;
        }

        /* Table actions styling */
        .table-actions {
            margin-bottom: 15px;
        }

        .dropdown-menu li a {
            padding: 0 20px 4px 12px !important;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 991px) {
            #casesTable {
                min-width: 640px !important;
                width: 100% !important;
                table-layout: fixed !important;
                font-size: 12px !important;
            }

            /* Match header and body font size on mobile */
            #casesTable thead th,
            #casesTable tbody td {
                font-size: 12px !important;
                padding: 6px 4px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Column width distribution for mobile */
            #casesTable thead th:nth-child(1),
            #casesTable tbody td:nth-child(1) {
                width: 19%;
            }

            /* Doctor */
            #casesTable thead th:nth-child(2),
            #casesTable tbody td:nth-child(2) {
                width: 19%;
            }

            /* Patient */
            #casesTable thead th:nth-child(3),
            #casesTable tbody td:nth-child(3) {
                width: 17%;
            }

            /* Initial Deli Date */
            #casesTable thead th:nth-child(4),
            #casesTable tbody td:nth-child(4) {
                width: 17%;
            }

            /* Date Delivered */
            #casesTable thead th:nth-child(5),
            #casesTable tbody td:nth-child(5) {
                width: 8%;
            }

            /* Status */
            #casesTable thead th:nth-child(6),
            #casesTable tbody td:nth-child(6) {
                width: 14%;
            }

            /* Tags */
            #casesTable thead th:nth-child(7),
            #casesTable tbody td:nth-child(7) {
                width: 6%;
            }

            .tooltip-toggle-container {
                display: none;
            }

            .tooltipX .tooltiptext,
            .case-jobs-tooltip {
                display: none !important;
            }

            .content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .row {
                padding: 3px;
            }

            /* Allow horizontal scroll on small screens */
            .dataTables_wrapper {
                overflow-x: auto;
                width: 100% !important;
            }

            .cases-table-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
            }

            .pagination {
                flex-wrap: wrap;
            }

            /* Better button display on mobile */
            .btn-primary {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            /* Fix filter layout on mobile */
            .justify-content-end {
                justify-content: space-between !important;
            }

            /* Make action buttons more visible on mobile */
            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
            }

            .bootstrap-select ul.dropdown-menu li:first-child {
                display: none;
            }

            /* Keep doctor picker menu inside viewport on mobile */
            .cases-filter-row .bootstrap-select #doctor ~ .dropdown-menu {
                left: auto !important;
                right: 0 !important;
                min-width: 100% !important;
                max-width: min(92vw, 320px) !important;
                transform: none !important;
            }

            .cases-filter-row .bootstrap-select #doctor ~ .dropdown-menu .inner {
                max-width: 100% !important;
            }

            .dataTables_wrapper .dataTables_filter {
                text-align: center;
            }

            #casesTable .cases-cell-truncate {
                max-width: 100%;
                display: block;
            }

            /* Responsive button group on mobile */
            .btn-group {
                display: flex;
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
                margin-left: 2px;
                margin-right: 2px;
            }

            /* Smaller status badges on mobile */
            .sigma-case-status-badge {
                padding: 2px 6px !important;
                font-size: 11px !important;
                line-height: 1.2 !important;
            }
        }

        .sigma-sticky-table-header thead th:first-child {
            border-top-left-radius: 5px;
        }

        .sigma-sticky-table-header thead th:last-child {
            border-top-right-radius: 5px;
        }

    </style>
    @php
        $permissions = safe_permissions();

    @endphp
    @if(!isset($isSearchResults))
        @php
            if (isset($trashedCases)) {
                $casesFiltersAction = route('deleted-cases');
            } elseif (isset($clients)) {
                $casesFiltersAction = route('cases-index');
            } else {
                $casesFiltersAction = route('dentist-cases', ['id' => $id]);
            }
        @endphp
        <form class="kt-form sigma-sticky-toolbar" method="GET" action="{{ $casesFiltersAction }}">
            @if(!isset($trashedCases) && !isset($clients))
                <input type="hidden" class="form-control" name="id" value="{{$id}}">
            @endif
            <div class="container full-width">
                <div class="row cases-filter-row">
                    <!-- Date filtering section -->
                    <div class="col-4 col-sm-3 col-md-2 mb-2">
                        <x-ios-dtp
                                name="from"
                                id="cases_from"
                                :value="$from"
                                mode="date"
                        />
                    </div>
                    <div class="col-4 col-sm-3 col-md-2 mb-2">
                        <x-ios-dtp
                                name="to"
                                id="cases_to"
                                :value="$to"
                                mode="date"
                        />
                    </div>

                    <!-- Doctor selection -->
                    <div class="col-4 col-sm-3 col-md-2 mb-2">
                        @if(isset($clients))
                            <select style="width:100%" class="selectpicker clearOnAll greyBG"
                                    multiple
                                    name="doctor[]" id="doctor"
                                    data-live-search="true"
                                    title="Doctor">
                                <option value="all" {{(isset($selectedClients) && in_array("all" ,$selectedClients)) ? 'selected' : ''}}>
                                    All
                                </option>
                                @foreach($clients as $d)
                                    <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <!-- Search, Apply, and Trash -->
                    <div class="col-12 col-md-4 mb-2">
                        <div class="d-flex">
                            <input type="text" class="form-control" id="tableSearch" placeholder="Search...">
                            <button type="submit" class="btn btn-primary cases-filter-btn ml-2"
                                    style="width: auto; min-width: 40px;">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{route('deleted-cases')}}" class="btn btn-danger cases-filter-btn ml-2"
                               title="View Deleted Cases" style="width: auto; min-width: 40px;">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Added better spacing between filters and table -->
            <div class="filter-section"></div>
        </form>
    @endif
    <div class="container full-width">
        <div class="row" style="">
            <div class="col-12">
                <br>
                <div class="cases-table-scroll">
                    <table id="casesTable"
                           class="table-striped compact sunriseTable sigma-sticky-table-header"
                           role="grid"
                           style="width:100%">
                    <thead>
                    <tr role="row">
                        <th class="sigma-sticky-container">Doctor
                        </th>
                        <th class="sigma-sticky-container">Patient</th>
                        <th class="initDeliDateHeader sigma-sticky-container">Initial Deli. Date</th>
                        <th class="sigma-sticky-container">Date Delivered</th>
                        <th class="sigma-sticky-container">Units</th>
                        <th class="sigma-sticky-container">Status</th>
                        <th class="tagsHeader sigma-sticky-container">Tags</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($cases  as $case)
                        @php
                            // Check if case is in-progress and initial_delivery_date has passed
                            $caseStatus = (string) $case->status();
                            $isOverdue = false;
                            if (!$case->actual_delivery_date && $case->initial_delivery_date) {
                                $now = \Carbon\Carbon::now();
                                $deliveryDate = \Carbon\Carbon::parse($case->initial_delivery_date);
                                $isOverdue = $deliveryDate->lt($now);
                            }
                            $rowStyle = $isOverdue ? 'color: #dc3545; font-weight: 600;' : 'font-weight: 400';
                        @endphp

                        <tr role="row" class="odd clickable case-row" data-toggle="modal"
                            data-target="#actionsDialog{{$case->id ?? "x"}}" style="{{$rowStyle}}"
                            data-case-id="{{$case->id}}">
                            <td><span class="cases-cell-truncate">{{$case->client->name ?? "x"}}</span></td>
                            <td><span class="cases-cell-truncate">{{$case->patient_name ?? "x"}}</span></td>
                            <td class="initDeliDateTD">{{$case->initDeliveryDate() ?? "x" }}
                                &nbsp;&nbsp; {{$case->initDeliveryTime() ?? "Unavailable"}}</td>
                            <td>{{$case->actualDeliveryDate()=="" ? "Not yet" : $case->actualDeliveryDate()}}
                                &nbsp;&nbsp; {{$case->actualDeliveryTime() ?? ""}}</td>
                            <td>
                                @php
                                    $totalUnits = 0;
                                    foreach($case->jobs as $job) {
                                        // Count only if the material is flagged as countable
                                        if ($job->material && $job->material->count_as_unit && !empty($job->unit_num)) {
                                            // Split on commas or whitespace, trim, and drop empties
                                            $units = preg_split('/\s*,\s*|\s+/', trim($job->unit_num));
                                            $units = array_filter($units, function ($value) {
                                                return $value !== '';
                                            });
                                            $totalUnits += count($units);
                                        }
                                    }
                                @endphp
                                <span class="cases-cell-truncate">{{ $totalUnits }}</span>
                            </td>
                            <td>
                                @if(str_contains($caseStatus, "Completed") )
                                    <span class="badge badge-success sigma-case-status-badge sigma-status-width">
                                                        <span class="sigma-badge-label">{{ $caseStatus }}</span>
                                                    </span>
                                @elseif(str_contains($caseStatus, "In-Progress") || str_contains($caseStatus, "Active"))
                                    @php
                                        $rawStatus = trim($caseStatus);

                                        // خذ فقط ما بعد "Active in" أو "In-Progress in"
                                        $stageText = $rawStatus;
                                        if (Str::contains($rawStatus, 'Active in')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                                        }

                                        // استخراج المرحلة والموظف
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
                                                                    $status =  preg_replace('/' . "in" . '/', "", str_replace("Waiting","",$caseStatus), 1);
                                                                @endphp

                                                        <span class="sigma-badge-label">{{ trim($status) }}</span>
                                                    </span>
                                @else
                                    @php
                                        $isDeliveryAssigned = $case->jobs[0]->stage == 8 && $case->jobs[0]->assignee != null && $case->jobs[0]->delivery_accepted == null;

                                        // Format delivery assigned badge as "Deli/ [initials]"
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
                            <td class="tagsTD">

                                @foreach($case->tags as $tag)
                                    @if(isset($tag->originalTagRecord))
                                        <i title="{{$tag->originalTagRecord->text}}"
                                           style="color:{{$tag->originalTagRecord->color}}"
                                           class="{{$tag->originalTagRecord->icon}}  fa-lg"></i>
                                    @endif
                                @endforeach
                            </td>


                        </tr>

                    @endforeach
                    </tbody>

                    </table>
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
                                        <div class="row" style="width: 100%; margin: 0;">
                                            <!-- Row 1: Print Voucher and View Case -->
                                            <div class="col-6" style="padding: 5px;">
                                                <a href="{{route('view-voucher',$case->id)}}"
                                                   class="btn btn-info" style="width: 100%;"><span class="btn-icon"><i
                                                                class="fas fa-print"></i></span><span class="btn-text">Print Voucher</span></a>
                                            </div>
                                            <div class="col-6" style="padding: 5px;">
                                                <a href="{{route('view-case',['id' =>$case->id ,'stage' =>-2 ])}}"
                                                   class="btn btn-info" style="width: 100%;"><span class="btn-icon"><i
                                                                class="far fa-file-alt"></i></span><span
                                                            class="btn-text">View</span></a>
                                            </div>

                                            <!-- Row 2: Lock Case, Delete Case -->
                                            @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                                                <div class="col-6" style="padding: 5px;">
                                                    @if(!$case->locked)
                                                        <a href="{{route('lock-case',$case->id)}}"
                                                           class="btn btn-dark" style="width: 100%;"><span
                                                                    class="btn-icon"><i
                                                                        class="fas fa-lock"></i></span><span
                                                                    class="btn-text">Lock</span></a>
                                                    @else
                                                        <a href="{{route('unlock-case',$case->id)}}"
                                                           class="btn btn-dark" style="width: 100%;"><span
                                                                    class="btn-icon"><i
                                                                        class="fas fa-lock-open"></i></span><span
                                                                    class="btn-text">Unlock</span></a>
                                                    @endif
                                                </div>
                                            @endif
                                            @if(Auth()->user()->is_admin && !$case->locked)
                                                <div class="col-6" style="padding: 5px;">
                                                    <a data-clientName="{{ $case->client->name ?? "-" }}"
                                                       data-patientName="{{ $case->patient_name }}"
                                                       style="color:white; width: 100%;"
                                                       onclick="caseDelConfirmation(event)"
                                                       href="{{route('delete-case',$case->id)}}"
                                                       class="btn btn-danger"><span class="btn-icon"><i
                                                                    class="fas fa-trash"></i></span><span
                                                                class="btn-text">Delete</span></a>
                                                </div>
                                            @endif

                                            <!-- Row 3: For completed cases only - Reject, Repeat, Modify -->
                                            @if (isset($case->actual_delivery_date))
                                                @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 116)) && !$case->locked)
                                                    <div class="col-4" style="padding: 5px;">
                                                        <a href="{{route('reject-case-view',$case->id )}}"
                                                           class="btn btn-outline-danger" style="width: 100%;"><span
                                                                    class="btn-icon"><i class="fas fa-times"></i></span><span
                                                                    class="btn-text">Reject case</span></a>
                                                    </div>
                                                @endif
                                                @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 117))&&!$case->locked)
                                                    <div class="col-4" style="padding: 5px;">
                                                        <a href="{{route('repeat-case-view',$case->id)}}"
                                                           class="btn btn-outline-warning" style="width: 100%;"><span
                                                                    class="btn-icon"><i
                                                                        class="fas fa-undo"></i></span><span
                                                                    class="btn-text">Repeat case</span></a>
                                                    </div>
                                                @endif
                                                @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 118)) && !$case->locked)
                                                    <div class="col-4" style="padding: 5px;">
                                                        <a href="{{route('modify-case-view',$case->id)}}"
                                                           class="btn btn-outline-warning" style="width: 100%;"><span
                                                                    class="btn-icon"><i
                                                                        class="fa fa-broom"></i></span><span
                                                                    class="btn-text">Modify case</span></a>
                                                    </div>
                                                @endif
                                            @endif


                                            <!-- Row 3: Redo case and Edit -->
                                            @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 119)) && !$case->locked && !isset($case->actual_delivery_date))
                                                <div class="col-6" style="padding: 5px;">
                                                    <a href="{{route('redo-case-view',$case->id)}}"
                                                       class="btn btn-outline-warning" style="width: 100%;"><span
                                                                class="btn-icon"><i class="fa fa-broom"></i></span><span
                                                                class="btn-text">Redo case</span></a>
                                                </div>
                                            @endif
                                            @if((Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))) || ($permissions && ((!isset($case->actual_delivery_date)&& $permissions->contains('permission_id', 115))) || (optional($case->jobs->first())->stage == 1 && $permissions->contains('permission_id', 1)))) && !$case->locked)
                                                <div class="col-6" style="padding: 5px;">
                                                    <a href="{{route('edit-case-view',$case->id)}}"
                                                       class="btn btn-warning" style="width: 100%;"><span
                                                                class="btn-icon"><i
                                                                    class="fa-solid fa-pen-to-square"></i></span><span
                                                                class="btn-text">Edit</span></a>
                                                </div>
                                            @endif
                                            <!-- Cancel Row -->
                                            <div class="col-12" style="padding: 5px;">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                        style="width: 100%;">Cancel
                                                </button>
                                            </div>

                                        </div>
                                    @else
                                        <a href="{{route('restore-case',$case->id)}}"
                                           class="btn btn-danger">Restore case</a>
                                    @endif
                                </div>


                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
            <div style="text-align:right">

            </div>
        </div>
    </div>

    </div>
    @push('js')
        <style>
            .case-jobs-tooltip {
                display: none;
                position: absolute;
                background-color: #ffffff; /* White background for clean look */
                border: 1px solid #e3e3e3; /* Light border */
                padding: 8px; /* Reduced padding */
                z-index: 1000;
                width: 320px; /* More compact width */
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Softer, larger shadow */
                border-radius: 8px; /* Increased corner radius */
                font-size: 13px; /* Smaller font for compactness */
                color: #333;
            }

            .case-jobs-tooltip table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 0; /* Remove extra space */
                margin-bottom: 0; /* Remove extra space */
            }

            .case-jobs-tooltip th, .case-jobs-tooltip td {
                border: 1px solid #eee; /* Light borders for cells */
                padding: 6px 8px; /* Compact padding */
                text-align: left;
            }

            .case-jobs-tooltip th {
                background-color: #f0f0f0; /* Slightly darker light header background */
                font-weight: 700; /* Bolder header */
                color: #333; /* Darker text for header */
                text-transform: uppercase;
            }

            .case-jobs-tooltip tr:nth-child(even) {
                background-color: #fdfdfd; /* Light stripe */
            }

            .case-jobs-tooltip tr:hover {
                background-color: #f0f8ff; /* Subtle hover effect */
            }
        </style>
        <style>
            /* Scoped iOS-style date picker */
            .ios-picker-wrap {
                position: relative;
                width: 100%;
            }

            .ios-picker-panel {
                position: absolute;
                top: calc(100% + 8px);
                left: 0;
                z-index: 10;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                width: 280px;
                padding: 12px;
                display: none;
            }

            .ios-picker-panel.open {
                display: block;
            }

            .ios-picker-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
                font-weight: 600;
                color: #0f172a;
            }

            .ios-picker-actions {
                display: flex;
                gap: 8px;
            }

            .ios-picker-btn {
                padding: 6px 10px;
                border-radius: 8px;
                border: 1px solid #d0d5dd;
                background: #fff;
                color: #111827;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s ease, border-color 0.2s ease;
            }

            .ios-picker-btn.primary {
                background: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }

            .ios-picker-btn:hover {
                background: #f9fafb;
            }

            .ios-picker-btn.primary:hover {
                background: #1d4ed8;
            }

            .ios-wheels {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px;
                position: relative;
                height: 180px;
            }

            .ios-wheel {
                position: relative;
                height: 100%;
                overflow-y: scroll;
                scroll-snap-type: y mandatory;
                -webkit-overflow-scrolling: touch;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
            }

            .ios-wheel::-webkit-scrollbar {
                display: none;
            }

            .ios-wheel ul {
                list-style: none;
                padding: 70px 0;
                margin: 0;
                text-align: center;
            }

            .ios-wheel li {
                height: 36px;
                line-height: 36px;
                scroll-snap-align: center;
                color: #6b7280;
                font-weight: 500;
            }

            .ios-wheel li.selected {
                color: #111827;
                font-size: 16px;
                font-weight: 700;
            }

            .ios-highlight {
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 36px;
                margin-top: -18px;
                border-top: 1px solid #dbeafe;
                border-bottom: 1px solid #dbeafe;
                pointer-events: none;
                background: linear-gradient(90deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.02), rgba(37, 99, 235, 0.08));
            }
        </style>
        <script>
            (function () {
                const ITEM_HEIGHT = 36;

                function formatDisplay(dateStr) {
                    if (!dateStr) return 'Select date';
                    const parts = dateStr.split( '-' );
                    if (parts.length !== 3) return 'Select date';
                    const d = new Date( dateStr + 'T00:00:00' );
                    return d.toLocaleDateString( 'en-US' , {year: 'numeric' , month: 'long' , day: 'numeric'} );
                }

                function buildWheel(list , values , selected) {
                    list.innerHTML = '';
                    values.forEach( v => {
                        const li = document.createElement( 'li' );
                        li.textContent = v.label;
                        li.dataset.value = v.value;
                        if (v.value === selected) li.classList.add( 'selected' );
                        list.appendChild( li );
                    } );
                }

                function snap(wheel) {
                    const idx = Math.round( wheel.scrollTop / ITEM_HEIGHT );
                    const target = idx * ITEM_HEIGHT;
                    wheel.scrollTo( {top: target , behavior: 'auto'} );
                    wheel.querySelectorAll( 'li' ).forEach( (li , i) => {
                        li.classList.toggle( 'selected' , i === idx );
                    } );
                }

                function initPicker(host) {
                    const name = host.dataset.name;
                    const initial = host.dataset.initial || '';

                    const wrapper = document.createElement( 'div' );
                    wrapper.className = 'ios-picker-wrap';

                    const input = document.createElement( 'input' );
                    input.type = 'hidden';
                    input.name = name;
                    input.value = initial;

                    const display = document.createElement( 'button' );
                    display.type = 'button';
                    display.className = 'form-control';
                    display.textContent = formatDisplay( initial );

                    const panel = document.createElement( 'div' );
                    panel.className = 'ios-picker-panel';
                    panel.innerHTML = `
                                    <div class="ios-picker-header">
                                        <span>Select date</span>
                                        <div class="ios-picker-actions">
                                            <button type="button" class="ios-picker-btn js-cancel">Cancel</button>
                                            <button type="button" class="ios-picker-btn primary js-done">Done</button>
                                        </div>
                                    </div>
                                    <div class="ios-wheels">
                                        <div class="ios-wheel js-wheel-year"><div class="ios-highlight"></div><ul></ul></div>
                                        <div class="ios-wheel js-wheel-month"><div class="ios-highlight"></div><ul></ul></div>
                                        <div class="ios-wheel js-wheel-day"><div class="ios-highlight"></div><ul></ul></div>
                                    </div>
                                `;

                    wrapper.appendChild( input );
                    wrapper.appendChild( display );
                    wrapper.appendChild( panel );
                    host.replaceWith( wrapper );

                    const yearWheel = panel.querySelector( '.js-wheel-year' );
                    const monthWheel = panel.querySelector( '.js-wheel-month' );
                    const dayWheel = panel.querySelector( '.js-wheel-day' );
                    const yearList = yearWheel.querySelector( 'ul' );
                    const monthList = monthWheel.querySelector( 'ul' );
                    const dayList = dayWheel.querySelector( 'ul' );

                    const today = initial ? new Date( initial + 'T00:00:00' ) : new Date();
                    let selYear = today.getFullYear();
                    let selMonth = today.getMonth() + 1;
                    let selDay = today.getDate();

                    const years = [];
                    const currentYear = new Date().getFullYear();
                    for (let y = currentYear - 100; y <= currentYear + 10; y++) {
                        years.push( {label: y , value: y} );
                    }
                    const months = Array.from( {length: 12} , (_ , i) => ({
                        label: new Date( 2000 , i , 1 ).toLocaleString( 'en' , {month: 'short'} ) ,
                        value: i + 1
                    }) );

                    function rebuildDays() {
                        const max = new Date( selYear , selMonth , 0 ).getDate();
                        const days = Array.from( {length: max} , (_ , i) => ({label: i + 1 , value: i + 1}) );
                        if (selDay > max) selDay = max;
                        buildWheel( dayList , days , selDay );
                        dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;
                    }

                    buildWheel( yearList , years , selYear );
                    buildWheel( monthList , months , selMonth );
                    rebuildDays();

                    yearWheel.scrollTop = years.findIndex( y => y.value === selYear ) * ITEM_HEIGHT;
                    monthWheel.scrollTop = (selMonth - 1) * ITEM_HEIGHT;
                    dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;

                    const wheels = [
                        {
                            el: yearWheel , list: yearList , onChange: v => {
                                selYear = v;
                                rebuildDays();
                            }
                        } ,
                        {
                            el: monthWheel , list: monthList , onChange: v => {
                                selMonth = v;
                                rebuildDays();
                            }
                        } ,
                        {
                            el: dayWheel , list: dayList , onChange: v => {
                                selDay = v;
                            }
                        } ,
                    ];

                    wheels.forEach( ({el , list , onChange}) => {
                        let t;
                        el.addEventListener( 'scroll' , () => {
                            clearTimeout( t );
                            t = setTimeout( () => {
                                snap( el );
                                const idx = Math.round( el.scrollTop / ITEM_HEIGHT );
                                const li = list.children[idx];
                                if (li) {
                                    onChange( parseInt( li.dataset.value , 10 ) );
                                }
                            } , 80 );
                        } );
                    } );

                    function saveAndClose() {
                        const monthStr = String( selMonth ).padStart( 2 , '0' );
                        const dayStr = String( selDay ).padStart( 2 , '0' );
                        const newVal = `${selYear}-${monthStr}-${dayStr}`;
                        input.value = newVal;
                        display.textContent = formatDisplay( newVal );
                        panel.classList.remove( 'open' );
                    }

                    function cancelAndClose() {
                        panel.classList.remove( 'open' );
                    }

                    display.addEventListener( 'click' , () => {
                        panel.classList.add( 'open' );
                        panel.focus();
                    } );

                    panel.querySelector( '.js-done' ).addEventListener( 'click' , saveAndClose );
                    panel.querySelector( '.js-cancel' ).addEventListener( 'click' , cancelAndClose );

                    document.addEventListener( 'click' , (e) => {
                        if (!panel.classList.contains( 'open' )) return;
                        if (!panel.contains( e.target ) && e.target !== display) {
                            saveAndClose();
                        }
                    } );
                }

                document.querySelectorAll( '.ios-date-picker' ).forEach( initPicker );
            })();
        </script>
        {{--<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>--}}
        <!-- Responsive and datable js -->
        <script type="text/javascript">
            $( document ).ready( function () {
                var isMobileLayout = window.matchMedia( '(max-width: 991px)' ).matches;

                var table = $( "#casesTable" ).DataTable( {
                                                              "colResize": !isMobileLayout,
                                                              "colReorder": !isMobileLayout ,
                                                              "responsive": false ,  // Disable responsive column hiding
                                                              "bLengthChange": false ,  // Disable "Show XX entries" dropdown
                                                              "iDisplayLength": 20 ,
                                                              "order": [] ,  // Disable initial sorting to preserve server-side order
                                                              "dom": 'rtip' ,  // Hide default search box ('f' removed) but keep table, info, pagination
                                                              "bProcessing": true ,
                                                              "searching": true ,  // Enable searching for real-time filter
                                                              "scrollX": false ,  // Disable horizontal scroll - let CSS handle it
                                                              "autoWidth": false ,
                                                              "initComplete": function () {
                                                                  var settings = this;
                                                                  if (isMobileLayout) {
                                                                      localStorage.removeItem( 'casesTable_widths' );
                                                                      $( '#casesTable th' ).css( 'width' , '' );
                                                                      return;
                                                                  }

                                                                  // Restore saved widths
                                                                  var widths = JSON.parse( localStorage.getItem( 'casesTable_widths' ) );
                                                                  if (widths) {
                                                                      $( '#casesTable th' ).each( function (i) {
                                                                          $( this ).width( widths[i] );
                                                                      } );
                                                                      // Recalc resize handle positions after width restore
                                                                      setTimeout(function() {
                                                                          if (settings.colResize && settings.colResize._recalcPositions) {
                                                                              settings.colResize._recalcPositions();
                                                                          }
                                                                      }, 50);
                                                                  }

                                                                  // Save widths on column resize (only on resize handles)
                                                                  $( document ).on( 'mouseup', '.dt-colresizable-col', function (e) {
                                                                      // Don't stopPropagation - let it reach document for colResize cleanup
                                                                      setTimeout( function () {
                                                                          var newWidths = [];
                                                                          $( '#casesTable th' ).each( function () {
                                                                              newWidths.push( $( this ).width() );
                                                                          } );
                                                                          localStorage.setItem( 'casesTable_widths', JSON.stringify( newWidths ) );
                                                                          console.log( "Saved widths", newWidths );
                                                                      }, 100 );
                                                                  } );
                                                              },
                                                              "columnDefs": isMobileLayout ? [
                                                                  {"orderable": false , "targets": [0 , 1 , 4 , 6]}
                                                              ] : [
                                                                  {"orderable": false , "targets": [0 , 1 , 4 , 6]} ,  // Disable sorting on Doctor, Patient, Units, and Tags columns
                                                                  {"width": "17%" , "targets": 0} ,  // Doctor column width
                                                                  {"width": "17%" , "targets": 1} ,  // Patient column width
                                                                  {"width": "18%" , "targets": 2} ,  // Initial Delivery Date
                                                                  {"width": "14.4%" , "targets": 3} ,  // Date Delivered
                                                                  {"width": "7%" , "targets": 4} ,   // Units (NEW)
                                                                  {"width": "14.4%" , "targets": 5} ,  // Status
                                                                  {"width": "12.2%" , "targets": 6}   // Tags
                                                              ]
                                                          } );

                // Connect custom search field to DataTable for real-time search
                $( '#tableSearch' ).on( 'keyup' , function () {
                    table.search( this.value ).draw();
                } );

                // Keep Doctor filter dropdown within viewport on mobile.
                $( document ).on( 'shown.bs.select' , '#doctor' , function () {
                    if (!window.matchMedia( '(max-width: 991px)' ).matches) {
                        return;
                    }

                    var $wrapper = $( this ).closest( '.bootstrap-select' );
                    var $menu = $wrapper.find( '> .dropdown-menu' );

                    if ($menu.length) {
                        $menu.css( {
                                       left: 'auto' ,
                                       right: '0' ,
                                       minWidth: '100%' ,
                                       maxWidth: 'calc(100vw - 24px)' ,
                                       transform: 'none'
                                   } );
                    }
                } );

                // Fix aria-hidden conflict on modals
                $( '.sigma-modal--cases-index-actions' ).on( 'show.bs.modal', function () {
                    $( this ).removeAttr( 'aria-hidden' );
                } );

                function saveColumnWidths(tableId) {
                    const widths = [];
                    $( `#${tableId} th` ).each( function () {
                        widths.push( $( this ).width() );
                    } );
                    localStorage.setItem( `table_${tableId}_widths` , JSON.stringify( widths ) );
                }

                // Restore on page load
                function restoreColumnWidths(tableId) {
                    const widths = JSON.parse( localStorage.getItem( `table_${tableId}_widths` ) );
                    if (widths) {
                        $( `#${tableId} th` ).each( function (i) {
                            $( this ).width( widths[i] );
                        } );
                    }
                }


            } );

            function caseDelConfirmation(ev) {
                ev.preventDefault();
                var urlToRedirect = ev.currentTarget.getAttribute( 'href' ); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
                var clientName = ev.currentTarget.getAttribute( 'data-clientName' );
                var patientName = ev.currentTarget.getAttribute( 'data-patientName' );

                //console.log(urlToRedirect); // verify if this is the right URL
                swal.fire( {
                               title: "You sure You want to delete.. </br>" + clientName + " - " + patientName ,
                               text: "This will also delete related info. (invoice, photos .. etc)?" ,
                               icon: "warning" ,
                               showDenyButton: true ,
                               confirmButtonText: 'Delete Case' ,
                               denyButtonText: 'Cancel'
                           } ).then( (result) => {
                    if (result.isConfirmed) {
                        window.location = urlToRedirect;
                    } else if (result.isDenied) {
                        swal.fire( "Case NOT deleted." );
                    }
                } );

            }
        </script>
        <script>
            $( document ).ready( function () {
                var tooltip = $( '<div class="case-jobs-tooltip"></div>' ).appendTo( 'body' );
                var hoverTimeout;
                // Default to true if not set, and handle the string 'false'
                var tooltipEnabled = localStorage.getItem( 'tooltipEnabled' ) !== 'false';
                var shouldDisableTooltips = function () {
                    return window.matchMedia( '(hover: none), (pointer: coarse), (max-width: 991px)' ).matches;
                };
                var mobileTooltipDisabled = shouldDisableTooltips();
                if (mobileTooltipDisabled) {
                    tooltipEnabled = false;
                    tooltip.hide();
                }

                // Set initial state of the toggle
                $( '#tooltip-toggle' ).prop( 'checked' , !mobileTooltipDisabled && tooltipEnabled );

                // Handle toggle change
                $( '#tooltip-toggle' ).on( 'change' , function () {
                    if (shouldDisableTooltips()) {
                        tooltipEnabled = false;
                        $( this ).prop( 'checked' , false );
                        tooltip.hide();
                        return;
                    }
                    tooltipEnabled = $( this ).is( ':checked' );
                    localStorage.setItem( 'tooltipEnabled' , tooltipEnabled );
                } );


                $( 'body' ).on( 'mouseenter' , '.case-row' , function (e) {
                    if (!tooltipEnabled || shouldDisableTooltips()) return;
                    var caseId = $( this ).data( 'case-id' );
                    var modal = $( '#actionsDialog' + caseId );

                    clearTimeout( hoverTimeout );

                    hoverTimeout = setTimeout( function () {
                        var jobs = [];
                        modal.find( '.job-info-for-tooltip' ).each( function () {
                            jobs.push( {
                                           type: $( this ).find( '.job-type' ).text() ,
                                           material: $( this ).find( '.job-material' ).text() ,
                                           units: $( this ).find( '.job-units' ).text()
                                       } );
                        } );

                        if (jobs.length > 0) {
                            var table = '<table><thead><tr><th>Job Type</th><th>Material</th><th>Units</th></tr></thead><tbody>';
                            jobs.forEach( function (job) {
                                table += '<tr>';
                                table += '<td>' + job.type + '</td>';
                                table += '<td>' + job.material + '</td>';
                                table += '<td>' + job.units + '</td>';
                                table += '</tr>';
                            } );
                            table += '</tbody></table>';
                            tooltip.html( table );
                        } else {
                            tooltip.html( 'No jobs found for this case.' );
                        }

                        tooltip.css( {
                                         top: e.pageY + 15 ,
                                         left: e.pageX + 15
                                     } ).show();
                    } , 250 ); // Reduced delay
                } );

                $( 'body' ).on( 'mouseleave' , '.case-row' , function () {
                    clearTimeout( hoverTimeout );
                    tooltip.hide();
                } );

                $( 'body' ).on( 'mousemove' , '.case-row' , function (e) {
                    if (!tooltipEnabled || shouldDisableTooltips()) return;
                    tooltip.css( {
                                     top: e.pageY + 15 ,
                                     left: e.pageX + 15
                                 } );
                } );

                $( window ).on( 'resize orientationchange' , function () {
                    if (shouldDisableTooltips()) {
                        tooltip.hide();
                    }
                } );
            } );
        </script>

    @endpush

@endsection
