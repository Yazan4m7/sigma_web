@extends('layouts.app', ['pageSlug' => 'Delivery Schedule'])


@section('content')
    <style>
        .row {
            margin: 0 !important;
            ;
        }

        .table-odd tbody>tr:nth-of-type(odd) {
            background-color: #ffffff !important;
        }

        .table-odd tbody>tr:nth-of-type(even) {
            background-color: #f0f3f6 !important;
        }

        .mb-3, .my-3 {
            margin-bottom: 0rem !important;
        }

        .vertical {
            padding-left: 5px;
            border-left: 1px solid #aaaaaa;
        }

        .delivery-time-value {
            display: block;
            font-weight: 700;
            font-size: 14px;
            color: #202733;
        }

        .delivery-date-time {
            display: block;
            font-size: 11px;
            margin-top: 3px;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        /* Jobs list (table-like layout without table element) */
        .job-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 4px 10px;
            background: linear-gradient(180deg, #fdfefe 0%, #f4f6f8 100%);
            border: 1px solid #e3e6ea;
            border-radius: 8px;
            padding: 8px 10px;
            margin-top: 6px;
        }

        .job-grid-row {
            display: contents;
        }

        .job-grid-cell {
            font-size: 12px;
            color: #202733;
            padding: 4px 0;
            border-bottom: 1px solid #e8ecf0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-grid-row:last-of-type .job-grid-cell {
            border-bottom: none;
        }

        .text-overdue, .text-overdue .delivery-time-value, .text-overdue .delivery-date-time {
            color: red !important;
        }
        .delivery-counter-card > .value {
            color: #3b8b45;
            font-size: 1.4rem;
            letter-spacing: -3px;
            font-weight: 700;
            display: block !important;

        }

        /* Shrink # of units column */
        #datatable thead th:nth-child(4),
        #datatable tbody td:nth-child(4) {
            width: 10% !important;
            max-width: 80px;
        }

        /* Responsive modal dialog sizing */
        @media screen and (max-width: 991px){
            /* Tablets */
            
.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: 90% !important;
                margin: 1rem auto !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: 90% !important;
                margin: 1rem auto !important;
            }
        }


        @media screen and (max-width: 767px){
            .delivery-filters {
            gap: 16px;
            display: flex;
            justify-content: center !important;
            flex-direction: row;
            align-items: flex-end;
            flex-wrap: wrap;
        }
            /* Large phones */
            
.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: calc(100% - 24px) !important;
                margin: 12px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: calc(100% - 24px) !important;
                margin: 12px !important;
            }
        }

        @media screen and (max-width: 480px){
            /* Small phones */
            
.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: calc(100% - 16px) !important;
                margin: 8px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: calc(100% - 16px) !important;
                margin: 8px !important;
            }

            
.sigma-modal--delivery-schedule-edit .modal-content {
                border-radius: 8px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-content {
                border-radius: 8px !important;
            }
        }

        /* Custom CSS for tighter spacing on mobile */
        @media screen and (max-width: 767px) {

            .delivery-filters .col-3,
            .delivery-actions .col-3 {

            }
            table{
                margin: 0px 1px;
                width: 90%;
            }

            .delivery-filters label {
                font-size: 12px !important;
                margin-bottom: 4px !important;
            }

            .delivery-filters .form-control {
                height: 38px !important;
                font-size: 13px !important;
                padding: 6px 8px !important;
            }

            .delivery-actions .btn {
                width: 100% !important;
                height: 38px !important;
                font-size: 13px !important;
                padding: 6px 8px !important;
            }

            .delivery-counters {
                padding: 0 8px !important;
                margin-top: 8px !important;
                display: flex !important;
                flex-wrap: wrap !important;
                gap: 8px !important;
            }

            .delivery-counters .col-3 {
                flex: 0 0 calc(33.333% - 8px) !important;
                max-width: calc(33.333% - 8px) !important;
                padding: 0 !important;
            }

            .delivery-counter-card {
                background: #f7f8fa !important;
                border: 1px solid #e1e4e8 !important;
                border-radius: 6px !important;
                padding: 4px 6px !important;
                text-align: center !important;
                min-height: 60px !important;
            }

            .delivery-counter-card .label {
                margin-top: 4px !important;
                font-size: 11px !important;
                font-weight: 600 !important;
                display: block !important;
                color: #555 !important;
                line-height: 1.2 !important;
            }

            .delivery-counter-card > .value {
                color: #3b8b45;
                font-size: 1.4rem;
                letter-spacing: -3px;
                font-weight: 700;
                display: block !important;

            }

            .table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child,
            .table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child {
                padding-left: 10px !important;
            }

            .table.dataTable.dtr-inline.collapsed>tbody>tr>td,
            .table.dataTable.dtr-inline.collapsed>tbody>tr>th {
                padding: 8px 5px !important;
            }

            .table.dataTable>tbody>tr>td,
            .table.dataTable>tbody>tr>th {
                white-space: nowrap;
                /* Prevent wrapping in cells */
            }

            #datatable {
                table-layout: fixed !important;
                width: 100% !important;
            }

            #datatable thead th,
            #datatable tbody td {
                padding: 8px 6px !important;
                font-size: 13px !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            #datatable thead th:nth-child(1),
            #datatable tbody td:nth-child(1) {
                width: 22% !important;
            }

            #datatable thead th:nth-child(2),
            #datatable tbody td:nth-child(2) {
                width: 22% !important;
            }

            #datatable thead th:nth-child(3),
            #datatable tbody td:nth-child(3) {
                width: 20% !important;
            }

            #datatable thead th:nth-child(4),
            #datatable tbody td:nth-child(4) {
                width: 12% !important;
            }

            #datatable thead th:nth-child(5),
            #datatable tbody td:nth-child(5) {
                width: 24% !important;
            }

            /* Keep Status column visible and allow wrapping */
            #datatable thead th:nth-child(5),
            #datatable tbody td:nth-child(5) {
                display: table-cell !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }

            #datatable tbody td:nth-child(6) {
                text-align: center !important;
            }

            .status-badge {
                width: auto !important;
                min-width: 80px !important;
                display: inline-block !important;
                white-space: normal !important;
                text-align: center !important;
            }

            .table-action-btn {
                padding: .15rem .3rem;
                font-size: .75rem;
            }

            .table-responsive {
                padding: 0 !important;
            }

            .input-group,
            .form-group {
                margin-bottom: 0px;
                position: relative;
            }

            .row {
                padding: 2px;
            }}
        .filter-btn{width: 10rem;}
        .print-btn{width: 7rem;}
        .delivery-filters{
            gap: 16px;
            display: flex;
            justify-content: flex-start;
            flex-direction: row;
            align-items: flex-end;
        }
    </style>
    @php
        $permissions = safe_permissions();
    @endphp
    @php
        // Defensive defaults to prevent undefined variable errors when view is rendered without precomputed metrics
        $overdue = $overdue ?? 0;
        $numOfUnits = $numOfUnits ?? 0;
    @endphp
    <div class="row">

        <div class="col-lg-12 col-sm-12 ">

            <form class="kt-form" method="GET" action="{{ route('delivery-schedule') }}">
                @csrf
                <div class="kt-portlet__body">
                    <div class="form-group">
                        <div class=" delivery-filters">

                            <div class="  noLeftPadding">
                                <div class="">
                                    <label for="delivery_from">FROM</label>
                                    <x-date-time-picker
                                        id="delivery_from"
                                        name="from"
                                        label=""
                                        mode="date"
                                        display-format="DD MMM, YYYY"
                                        submit-format="YYYY-MM-DD"
                                        value="{{ $data['from'] ?? '' }}"
                                        required
                                    />


                                    @if ($errors->has('from'))
                                        <span class="help-block" style="color: red">{{ $errors->first('from') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class=" ">
                                <label for="delivery_to">TO</label><br>
                                <x-date-time-picker
                                        id="delivery_to"
                                        name="to"
                                        label=""
                                        mode="date"
                                        display-format="DD MMM, YYYY"
                                        submit-format="YYYY-MM-DD"
                                        value="{{ $data['to'] ?? '' }}"
                                        required
                                />

                                @if ($errors->has('to'))
                                    <span class="help-block" style="color: red">{{ $errors->first('to') }}</span>
                                @endif
                            </div>

                                <div class=" noLeftPadding">
                                    <button type="submit" class="btn btn-primary fillWidth filter-btn">Filter</button>
                                </div>
                                <div class=" ">
                                    <button type="button" onclick="printResult()"
                                            class="btn btn-secondary fillWidth print-btn">Print</button>
                                </div>

                        </div>

                    </div>


                </div>
            </form>
        </div>
    </div>
    @php

        $date = new DateTime();
        $date2 = $date->modify('+1 day');
        $date3 = $date->modify('+2 day');
        $endofToday = substr(now()->addDays(0), 0, 10) . 'T23:59:00';
        $endofTomorrow = substr(now()->addDays(1), 0, 10) . 'T23:59:00';
        $endofSeventhDay = substr(now()->addDays(7), 0, 10) . 'T23:59:00';

        // Pre-calc delivery metrics for reuse
        $overdue = 0;
        $numOfUnits = 0;
        foreach ($cases as $case) {
            $numOfUnits += $case->unitsAmount();
            if (strtotime($case->initial_delivery_date) < strtotime('now')) {
                $overdue++;
            }
        }
    @endphp

    <hr style="margin:0">

    <div class=" table-responsive row">
        <div class="col-lg-12 col-sm-12  row delivery-counters" style="flex-direction: row;padding-bottom:0px">
            <div class="col-lg-3 col-md-3 col-3 mb-3">

                <div class="vertical delivery-counter-card">
                    <span class="value" style="color:#3b8b45">{{ count($cases) }}</span>
                    <span class="label">Total deliveries</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-3 mb-3">
                <div class="vertical delivery-counter-card">
                    <span class="value" style="color:red">{{ $overdue }}</span>
                    <span class="label">Overdue deliveries</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-3 mb-3">
                <div class="vertical delivery-counter-card">
                    <span class="value" style="color:#3b8b45">{{ $numOfUnits }}</span>
                    <span class="label"># of Units</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-3 mb-3">
                {{-- <div class= "vertical"> --}}
                {{-- <span style="font-weight: bold;font-size:15px;"></span><br> --}}
                {{-- <span style="font-weight:bold;font-size:19px; color:#3b8b45"></span> --}}
                {{-- <span style="font-size:13px;">Cases</span> --}}
                {{-- </div> --}}
            </div>
        </div>
        <p class="text-muted"></p>
        <div class="table-odd" style="width: 100%;">
            <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer"
                style="padding:0;margin:0;">
                <div class="row">
                    <div class="col-sm-12" style="padding:0;margin:0;">

                        <table id="datatable" class="table table-bordered dataTable no-footer sunriseTable" role="grid"
                            aria-describedby="datatable_info">
                            <thead>
                                <tr class="" style="left: 0px;  !important;">
                                    <th><span>Doctor Name</span></th>
                                    <th><span>Patient Name</span></th>
                                    <th><span>Delivery Date</span></th>
                                    <th><span># Of units</span></th>
                                    <th class="statusCol"><span>Status</span></th>


                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cases as $case)
                                    @php
                                        $status = $case->status();
                                        $isOverdue = strtotime($case->initial_delivery_date) < strtotime('now');
                                        $color = $isOverdue ? 'red' : '#595d6e';
                                        $rawStatus = trim((string) $status);

                                        $stageText = $rawStatus;
                                        if (Str::contains($rawStatus, 'Active in')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                                        } elseif (Str::contains($rawStatus, 'Active')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress'));
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

                                        $formattedActiveStatus = $assigneeInitials !== ''
                                            ? (trim($stageText) . '/ ' . $assigneeInitials)
                                            : trim($stageText);
                                        if ($formattedActiveStatus === '') {
                                            $formattedActiveStatus = $rawStatus;
                                        }

                                        $waitingStage = $rawStatus;
                                        if (Str::contains($rawStatus, 'Waiting in')) {
                                            $waitingStage = trim(Str::after($rawStatus, 'Waiting in'));
                                        } elseif (Str::contains($rawStatus, 'Waiting')) {
                                            $waitingStage = trim(Str::after($rawStatus, 'Waiting'));
                                        }
                                        $waitingStage = trim($waitingStage);
                                        if ($waitingStage === '') {
                                            $waitingStage = $rawStatus;
                                        }

                                    @endphp
                                    <tr data-row="{{ $case->id }}" class="odd clickable" data-toggle="modal"
                                        data-target="#actionsDialog{{ $case->id }}">

                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->client->name }}</span>
                                        </td>

                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->patient_name }}</span>
                                        </td>
                                        @php
                                            $date = explode('T', $case->initial_delivery_date);
                                        @endphp
                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span class="delivery-time-value">
                                                {{ isset($date[1]) ? date('g:i a', strtotime($date[1])) : '-' }}
                                            </span>
                                            <div class="delivery-date-time">
                                                {{ isset($date[0]) ? $date[0] : '-' }}
                                            </div>
                                        </td>
                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->unitsAmount() }}</span>
                                        </td>
                                        <td>
                                            @if (str_contains($status, 'Completed'))
                                                <span class="badge badge-success middle status-badge sigma-status-width">Completed</span>
                                            @elseif(str_contains($status, 'Active'))
                                                <span
                                                    class="badge badge-primary middle status-badge sigma-status-width">{{ $formattedActiveStatus }}</span>
                                            @elseif(str_contains($status, 'In-Progress'))
                                                <span class="badge badge-primary middle status-badge sigma-status-width">{{ $formattedActiveStatus }}</span>
                                            @elseif(str_contains($status, 'Waiting'))
                                                <span
                                                    class="badge badge-danger middle status-badge sigma-status-width">{{ $waitingStage }}</span>
                                            @else
                                                <span
                                                    class="badge badge-warning middle status-badge sigma-status-width">{{ $status }}</span>
                                            @endif
                                        </td>

                                    </tr>
                                    @if (($permissions && $permissions->contains('permission_id', 110)) || Auth()->user()->is_admin)
                                        <div class="modal sigma-modal--delivery-schedule-edit" tabindex="-1" role="dialog" id="myModal{{ $case->id }}">
                                            <form action="{{ route('edit-delivery-date') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $case->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delivery Date</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group row">
                                                                <div class="form-group col-6">
                                                                    <label for="milled">Case:</label>
                                                                    <h5>{{ $case->client->name }} -
                                                                        {{ $case->patient_name }}</h5>
                                                                    </br>
                                                                    <label for="milled">Delivery Date</label>
                                                                    @php
                                                                        $time = $case->initial_delivery_date;
                                                                        $time = str_replace(' ', 'T', $time);
                                                                    @endphp
                                                                    <input class="form-control SDTP" name="delivery_date"
                                                                        type="text" value="{{ $time }}"
                                                                        required readonly />

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <div class="modal sigma-modal--delivery-schedule-actions" tabindex="-1" role="dialog"
                                        id="actionsDialog{{ $case->id }}">

                                        <input type="hidden" name="case_id" value="{{ $case->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Case Actions</h5>

                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group row" style="margin-bottom: 0px">
                                                        <div class="form-group col-6 " style="margin-bottom: 0px">
                                                            <label for="doctor">Doctor: </label>
                                                            <h5 id="doctor"><b>{{ $case->client->name }}</b></h5>
                                                        </div>
                                                        <div class="form-group col-6 " style="margin-bottom: 0px">
                                                            <label for="pat">Patient: </label>
                                                            <h5 id="pat"><b>{{ $case->patient_name }}</b></h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <div class=" col-12 ">
                                                            <label><b>Jobs:</b></label><br>

                                                            <div class="job-grid">
                                                                @foreach ($case->jobs as $job)
                                                                    <div class="job-grid-row">
                                                                        <span class="job-grid-cell" title="{{ $job->unit_num ?? '-' }}">{{ $job->unit_num ?? '-' }}</span>
                                                                        @php $jobTypeName = optional($job->jobType)->name ?? ($job->type ? 'Type '.$job->type : '-'); @endphp
                                                                        <span class="job-grid-cell" title="{{ $jobTypeName }}">{{ $jobTypeName }}</span>
                                                                        <span class="job-grid-cell" title="{{ $job->material->name ?? '-' }}">{{ $job->material->name ?? '-' }}</span>
                                                                        @php $jobColor = is_null($job->color) ? '' : trim($job->color); @endphp
                                                                        <span class="job-grid-cell" title="{{ $jobColor !== '' ? $jobColor : '-' }}">{{ $jobColor !== '' ? $jobColor : '-' }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (count($case->notes) > 0)
                                                        <hr>
                                                        <label><b>Notes:</b></label><br>
                                                        @foreach ($case->notes as $note)
                                                            <div class="form-control"
                                                                style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px"
                                                                disabled>

                                                                <span
                                                                    class="noteHeader">{{ '[' . substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>
                                                                <span class="noteText">{{ $note->note }}</span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="modal-footer fullBtnsWidth">
                                                    <div class="row"
                                                        style=" margin-right: 0px; margin-left: 0px;width:100%">


                                                        <div class="row">
                                                            <!-------------------------
                                                                                           ------ View Voucher ------
                                                                                           -------------------------->
                                                            <div class="col-6 padding5px">
                                                                <a href="{{ route('view-voucher', $case->id) }}">
                                                                    <button type="button" class="btn btn-info "><i
                                                                            class="fas fa-print"></i> View Voucher
                                                                    </button>
                                                                </a>
                                                            </div>

                                                            <!-------------------------
                                                                                    -------- View Case --------
                                                                                    -------------------------->
                                                            <div class="col-6 padding5px">
                                                                <a
                                                                    href="{{ route('view-case', ['id' => $case->id, 'stage' => -2]) }}">
                                                                    <button type="button" class="btn btn-info "><i
                                                                            class="far fa-file-alt"></i> View Case
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                            <!-------------------------
                                                                                                  -------- Edit CASE --------
                                                                                                  -------------------------->
                                                            @if (Auth()->user()->is_admin ||
                                                                    ($permissions && $permissions->contains('permission_id', 102)) ||
                                                                    (($permissions && (!isset($case->actual_delivery_date) && $permissions->contains('permission_id', 115))) ||
                                                                        (isset($case->jobs[0]) && $case->jobs[0]->stage == 1 && $permissions->contains('permission_id', 1))))
                                                                @if (!$case->locked)
                                                                    <div class="col-6 padding5px">
                                                                        <a
                                                                            href="{{ route('edit-case-view', $case->id) }}">
                                                                            <button type="button"
                                                                                class="btn btn-warning "><i
                                                                                    class="fa-solid fa-pen-to-square"></i>
                                                                                Edit</button>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            @if (($permissions && $permissions->contains('permission_id', 110)) || Auth()->user()->is_admin)
                                                                <div class="col-6 padding5px">

                                                                    <button type="button" class="btn btn-danger "
                                                                        data-dismiss="modal" data-toggle="modal"
                                                                        data-target="#myModal{{ $case->id }}"><i
                                                                            class="fa-solid fa-pen-to-square"></i> Edit
                                                                        Delivery Date</button>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="col-12 padding5px">
                                                            <button type="button" class="btn btn-secondary "
                                                                data-dismiss="modal" style="width:100%">Cancel</button>
                                                        </div>
                                                    </div>


                                                </div>



                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
@endsection

@push('js')
    <!-- Responsive and datatable js -->
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable({
                "ordering": false,
                "pageLength": 25,
                "searching": false,
                "lengthChange": false,
                "columnDefs": [
                    {
                        "width": "10%",
                        "targets": 3
                    },
                    {
                        "width": "20%",
                        "targets": 4
                    }
                ]
            });
        });


        function printResult() {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            //noinspection JSAnnotator
            mywindow.document.write(`
                <style>
                .kt-datatable__table, h2 {font-size:17px;font-weight: bold;  padding: 10px;width:100%;text-align:center;}
                .kt-datatable__body {font-size:17px;font-weight: normal;}
                body {padding:50px;}
                th, td {padding:8px;}
                table {border-collapse: collapse;}
                tr:nth-child(even) {background-color: #f2f2f2;}
                th {
                      background-color: #353535;
                      color: white;
                    }
                </style>
                 <body>
                <h1> Delivery Schedule </h1>

                @if (isset($data) && $data['from'] && $data['to'])
                <p>From <b>{{ $data['from'] }}</b> To <b>{{ $data['to'] }}</b> <br>  <b>{{ count($cases) }}</b> Cases</p>
                @endif

                <table border="1" class="kt-datatable__table" ">
                                <thead class="kt-datatable__head">
                                <tr class="kt-datatable__row" style="left: 0px;">
                                    <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Doctor Name</span></th>
                                    <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Patient Name</span></th>
                                    <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Delivery Date</span></th>
                                   <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Status at print time</span></th>
                                </tr>
                                </thead>
                                <tbody  class="kt-datatable__body">
                                  @foreach ($cases as $case)
                    @php
                        $status = $case->status();
                        $isOverdue = strtotime($case->initial_delivery_date) < strtotime('now');
                        $color = $isOverdue ? 'red' : '#595d6e';
                        $rawStatus = trim((string) $status);

                        $stageText = $rawStatus;
                        if (Str::contains($rawStatus, 'Active in')) {
                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                        } elseif (Str::contains($rawStatus, 'Active')) {
                            $stageText = trim(Str::after($rawStatus, 'Active'));
                        } elseif (Str::contains($rawStatus, 'In-Progress')) {
                            $stageText = trim(Str::after($rawStatus, 'In-Progress'));
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

                        $formattedActiveStatus = $assigneeInitials !== ''
                            ? (trim($stageText) . '/ ' . $assigneeInitials)
                            : trim($stageText);
                        if ($formattedActiveStatus === '') {
                            $formattedActiveStatus = $rawStatus;
                        }

                        $waitingStage = $rawStatus;
                        if (Str::contains($rawStatus, 'Waiting in')) {
                            $waitingStage = trim(Str::after($rawStatus, 'Waiting in'));
                        } elseif (Str::contains($rawStatus, 'Waiting')) {
                            $waitingStage = trim(Str::after($rawStatus, 'Waiting'));
                        }
                        $waitingStage = trim($waitingStage);
                        if ($waitingStage === '') {
                            $waitingStage = $rawStatus;
                        }

                    @endphp
                <tr data-row="{{ $case->id }}" class="kt-datatable__row" style="color:{{ $color }}">

                                            <td ><span >{{ $case->client->name }}</span></td>

                                            <td ><span >{{ $case->patient_name }}</span></td>
                                            @php
                                                $date = explode('T', $case->initial_delivery_date);

                                            @endphp
                <td >
                    <span style="display:block;font-weight:700;font-size:14px;color:{{ $color }};">{{ isset($date[1]) ? date('g:i a', strtotime($date[1])) : '-' }}</span>
                    <span style="display:block;font-size:11px;color:{{ $color }};margin-top:3px;">{{ isset($date[0]) ? $date[0] : '-' }}</span>
                </td>

                                            <td >
                                                    @if (str_contains($status, 'Completed'))
                <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-success middle">Completed</span>
@elseif (str_contains($status, 'In-Progress') || str_contains($status, 'Active'))
                <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-primary middle">{{ $formattedActiveStatus }}</span>
                                                                                                        @elseif (str_contains($status, 'Waiting'))
                <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-danger middle">{{ $waitingStage }}</span>
                                                                                                            @else
                <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-danger middle">Unknown</span>
@endif</td> </tr>
                                @endforeach
                </tbody>
            </table>
            </body>
`);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function() {
                mywindow.print();
                mywindow.close();
            }, 1000);

            return true;
        }
    </script>
@endpush
