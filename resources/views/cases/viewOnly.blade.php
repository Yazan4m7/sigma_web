@extends('layouts.app', ['pageSlug' => $viewCase])

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/lightgallery.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.imagesloader.css') }}" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/lightgallery/1.3.9/css/lightgallery.min.css" rel="stylesheet">

    <style>
        #kt_repeater_1 {
            padding-left: 15px
        }

        .noteform {
            padding: 25px;
            padding-left: 30px;
            padding-right: 30px;
        }

        .checked {
            filter: invert(26%) sepia(73%) saturate(492%) hue-rotate(133deg) brightness(94%) contrast(86%);
        }

        .hidden {
            display: none;
        }

        .noteHeader {
            color: #525252;
            font-size: 12px;
        }

        .noteText {
            color: black;
            font-weight: 500;
        }

        .bootstrap-select>.dropdown-toggle.bs-placeholder {
            color: #1a000d !important;

        }

        .historyTable {
            display: none;
        }

        .Timeline {
            display: block;
        }

        /* Modern patient info styling */
        .patient-info-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8eef0;
        }

        .patient-info-section label {
            font-weight: 600;
            color: #2b7b7d;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            display: block;
        }

        .patient-info-section .form-control,
        .patient-info-section .bootstrap-select .dropdown-toggle,
        .patient-info-section select.form-control {
            font-size: 14px;
            font-weight: 500;
            color: #2b7b7d;
            padding: 10px 14px;
            background-color: #f0f8f8;
            border-radius: 6px;
            border-left: 3px solid #2b7b7d;
            border-top: 1px solid #d0e5e6;
            border-right: 1px solid #d0e5e6;
            border-bottom: 1px solid #d0e5e6;
            transition: all 0.2s ease;
        }

        .patient-info-section .form-control:focus {
            border-left-color: #1f6163;
            box-shadow: 0 0 0 3px rgba(43, 123, 125, 0.08);
            background-color: #ffffff;
        }

        .case-id-display {
            font-size: 15px;
            font-weight: 600;
            color: #2b7b7d;
            padding: 10px 14px;
            background-color: #f0f8f8;
            border-radius: 6px;
            border-left: 3px solid #2b7b7d;
        }

        /* Second row styling */
        .info-row-secondary {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8eef0;
        }

        .info-row-secondary label {
            font-weight: 600;
            color: #2b7b7d;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            display: block;
        }

        .info-row-secondary .form-control,
        .info-row-secondary .bootstrap-select .dropdown-toggle,
        .info-row-secondary select.form-control {
            font-size: 14px;
            font-weight: 500;
            color: #2b7b7d;
            padding: 10px 14px;
            background-color: #f0f8f8;
            border-radius: 6px;
            border-left: 3px solid #2b7b7d;
            border-top: 1px solid #d0e5e6;
            border-right: 1px solid #d0e5e6;
            border-bottom: 1px solid #d0e5e6;
            transition: all 0.2s ease;
        }

        /* Section headers */
        .kt-portlet__head {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .kt-portlet__head-title {
            font-size: 16px;
            font-weight: 700;
            color: #2b7b7d;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kt-portlet__head-title i {
            color: #2b7b7d;
            margin-right: 12px;
            font-size: 18px;
        }

        /* Horizontal divider */
        hr {
            border: 0;
            height: 2px;
            background: linear-gradient(to right, #2b7b7d, transparent);
            margin: 15px 0 25px 0;
        }

        /* Jobs table styling */
        .jobsTable {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8eef0;
        }

        .jobsTable thead {
            background: linear-gradient(135deg, #2b7b7d 0%, #237577 100%);
        }

        .jobsTable thead th {
            color: #ffffff;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 12px;
            border: none;
        }

        .jobsTable tbody tr {
            transition: background-color 0.2s ease;
        }

        .jobsTable tbody tr:hover {
            background-color: #f0f8f8;
        }

        .jobsTable tbody td,
        .jobsTable tbody th {
            padding: 12px;
            border-bottom: 1px solid #e8eef0;
            font-size: 13px;
        }

        .jobsTable tbody tr:last-child td,
        .jobsTable tbody tr:last-child th {
            border-bottom: none;
        }

        /* Remove extra spacing */
        br {
            display: none;
        }

        .row {
            margin-bottom: 19px;
        }

        @media screen and (max-width:760px) {
            .btnsRow {
                flex-direction: column;
            }

            .historyTable {
                display: block;
            }

            .Timeline {
                display: none !important;
            }

            .noteform {
                padding: 15px
            }

            #kt_repeater_1 {
                padding-left: 0
            }

            .printMiniLabelBtn {
                margin-bottom: 5px;
            }

            .patient-info-section,
            .info-row-secondary {
                padding: 15px;
            }
        }
    </style>
    <link href="{{ asset('assets') }}/css/timeline.css" rel="stylesheet" />


    <div class="col-lg-12 col-sm-12 ">
        <div class="row btnsRow" style="padding-left: 10px;padding-top: 10px; background-color: transparent">
            <div class=" col-3 ">

                <button class="btn btn-secondary printMiniLabelBtn"
                    style=" background-color: #2b7b7d;   padding-left: 20px;
    padding-right: 20px;"
                    onclick="PrintMinimizedLabel()">Print Mini Label <i class="fa-solid fa-tag"></i></button>

            </div>
            <div class="col-2 ">

                <button class="btn btn-secondary"
                    style="background-color: #2b7b7d;    padding-left: 20px;
    padding-right: 20px;"
                    onclick="PrintLabel()">Print Label <i class="fa-solid fa-tag"></i></button>

            </div>
        </div>
    </div>

    <form style="" class="kt-form noteform" method="POST" enctype="multipart/form-data" action="#">
        @csrf
        <div>
            <!-- CASE INFO -->

            <div class="row patient-info-section">
                <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
                    <div class="col-md-12 col-xs-12"><label>Doctor:</label></div>
                    <div class="col-md-12 col-xs-12">


                        <select class="selectpicker" name="doctor" data-container="body" data-live-search="true"
                            title="Select a doctor" disabled>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ $case->client->id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach

                        </select>

                    </div>
                </div>
                <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                    <div class="col-md-12 col-xs-12"><label>Patient name:</label></div>
                    <div class="col-md-12 col-xs-12"><input class="form-control" type="text" name="patient_name"
                            value="{{ $case->patient_name }}" disabled /></div>
                </div>
                <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                    <div class="col-md-6 col-xs-12"><label>Case ID:</label></div>
                    <div class="col-md-12 col-xs-12">

                        <div class="case-id-display">{{ $case->case_id }}</div>

                    </div>

                </div>

            </div>

            <div class="row info-row-secondary">

                <div class="col-md-4  col-xs-6 col-l-2  col-xl-3">
                    <div class="col-md-12 col-xs-12"><label>Delivery Date:</label></div>
                    <div class="col-md-12 col-xs-12">
                        <input class="form-control SDTP" name="delivery_date" type="text"
                            value="{{ $case->initial_delivery_date }}" required disabled />
                    </div>
                </div>
                <div class="col-md-4  col-xs-6 col-l-2  col-xl-3">
                    <div class="col-md-12 col-xs-12"><label>Tags:</label></div>
                    <div class="col-md-12 col-xs-12">
                        <select class="select selectpicker" name="tags[]" multiple data-mdb-placeholder="Tags" multiple
                            disabled>
                            @foreach ($tags as $tag)
                                <option style="color:{{ $tag->color }}" value="{{ $tag->id }}"
                                    {{ in_array($tag->id, $tagsAsArray) ? 'selected' : '' }}>{{ $tag->text }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-md-4 col-xs-6 col-l-2 col-xl-3">
                    <div class="col-md-12 col-xs-12"><label>Impression Type:</label></div>
                    <div class="col-md-12 col-xs-12"> <select class="form-control" name="impression_type" type="text"
                            data-container="body" data-live-search="true" title="Select impression"
                            data-hide-disabled="true" disabled>

                            @foreach ($impressionTypes as $impression)
                                <option value="{{ $impression->id }}"
                                    {{ $impression->id == $case->impression_type ? ' selected' : ' ' }}>
                                    {{ $impression->name }}
                                </option>
                            @endforeach
                        </select></div>
                </div>
            </div>


            <!-- JOB INFO ICON-->
            <br>
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h5 class="kt-portlet__head-title">
                        <i class="fa  fa-suitcase" style="width:3%"></i> Job information
                    </h5>
                </div>
            </div>
            <hr>


            <!-- JOBS REPEATER -->

            <div id="kt_repeater_1" style=" padding-right: 15px">
                <div data-repeater-list="repeat">
                    <div data-repeater-item>
                        <div class="form-group form-group ">
                            <div data-repeater-list="repeat" class="col-12">
                                @php
                                    if ($stage == -2 || $stage > 5) {
                                        $jobs = $case->jobs;
                                    } else {
                                        $jobs = $case->jobs->where('stage', $stage);
                                    }
                                @endphp


                                <table id="tech-companies-1" class="table sunriseTable table-striped jobsTable">
                                    <thead>
                                        <tr>
                                            <th id="tech-companies-1-col-0">Unit Num</th>

                                            <th data-priority="3" id="tech-companies-1-col-2">Job Type</th>
                                            <th data-priority="1" id="tech-companies-1-col-3">Material</th>
                                            <th data-priority="2" id="tech-companies-1-col-3a">Type</th>
                                            <th data-priority="3" id="tech-companies-1-col-4">Color</th>
                                            <th data-priority="3" id="tech-companies-1-col-5">Style</th>
                                            <th data-priority="3" id="tech-companies-1-col-5">Status</th>
                                            <th data-priority="6" id="tech-companies-1-col-6">Others</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jobs as $job)
                                            @php
                                                $unit = explode(', ', $job->unit_num);
                                            @endphp
                                            <tr>

                                                <th colspan="1" data-columns="tech-companies-1-col-0">
                                                    {{ $job->unit_num }}</th>

                                                <td data-priority="3" colspan="1"
                                                    data-columns="tech-companies-1-col-2">{{ $job->jobType->name }}</td>
                                                <td data-priority="1" colspan="1"
                                                    data-columns="tech-companies-1-col-3">{{ $job->material->name }}</td>
                                                <td data-priority="2" colspan="1"
                                                    data-columns="tech-companies-1-col-3a">
                                                    {{ $job->subType->name ?? 'No Type' }}</td>
                                                <td data-priority="3" colspan="1"
                                                    data-columns="tech-companies-1-col-4">
                                                    {{ $job->color == '0' ? 'No color' : $job->color }}</td>
                                                <td data-priority="3" colspan="1"
                                                    data-columns="tech-companies-1-col-5">{{ $job->style }}</td>
                                                <td data-priority="3" colspan="1"
                                                    data-columns="tech-companies-1-col-5">
                                                    <b style="color:#2b7b7d">{{ $job->status() }}</b>
                                                </td>
                                                <td data-priority="6" colspan="1"
                                                    data-columns="tech-companies-1-col-6">
                                                    @if (isset($job->abutmentDelivery))
                                                        @foreach ($job->abutmentDelivery as $delivery)
                                                            <span>{{ $delivery->implant->name ?? 'None' }}{{ ' - ' . $delivery->abutment->name ?? 'None' }}{{ ' - ' . $delivery->code ?? 'None' }}
                                                            </span>
                                                            <br>
                                                        @endforeach
                                                    @else
                                                        <span>"Err" </span>
                                                    @endif

                                                    @if (isset($job->originalJob))
                                                        @if (isset($job->originalJob->abutmentDelivery))
                                                            @foreach ($job->originalJob->abutmentDelivery as $delivery)
                                                                <span>{{ $delivery->implant->name ?? 'None' }}{{ ' - ' . $delivery->abutment->name ?? 'None' }}{{ ' - ' . $delivery->code ?? 'None' }}
                                                                </span>
                                                                <br>
                                                            @endforeach
                                                        @else
                                                            <span>"Err" </span>
                                                        @endif
                                                    @endif

                                                    @if (isset($job->abutmentR) && $job->jobType->id == 6)
                                                        <span> Abutment Type: {{ $job->abutmentR->name }} <br></span>
                                                    @endif
                                                    @if ($job->has_been_rejected)
                                                        <span style="color:red;font-size: 10px"><b>PARTIALLY/
                                                                FULLY</b></span> <span
                                                            style="color:red"><b>REJECTED</b></span>
                                                    @endif
                                                    @if ($job->is_repeat)
                                                        <span style="color:red"><b>REPEAT</b></span>
                                                    @endif
                                                    @if ($job->is_modification)
                                                        <span style="color:red"><b>MODIFICATION</b></span>
                                                    @endif
                                                    @if ($job->is_redo)
                                                        <span style="color:red"><b>REDO</b></span>
                                                    @endif
                                                    @if (isset($job->redone_job_id))
                                                        <span style="color:red"><b>HAS A REDO JOB BELOW</b></span>
                                                    @endif
                                                </td>
                                                @if ($job->is_rejection)
                                                    <td class="reOverlay">REJECTION</td>
                                                @endif
                                                @if (isset($job->modified_job_id))
                                                    <td class="reOverlay">COMPLETED & UNDER MODIFICATION BELOW</td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- <a href="javascript:;" data-repeater-create="" class="btn btn-info  btn-sm" id="addJobBtn" >
                    <i class="fa fa-plus-square"></i> Add
                </a> -->
            </div>

            <br>
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h5 class="kt-portlet__head-title">
                        <i class="fa-solid fa-clock-rotate-left"></i> Case History
                    </h5>
                </div>
            </div>
            <hr>
            <!-- HISTORY -->
            <div class="historyTable" style="padding:0 30px 0 30px ">
                <table class="sunriseTable table sunriseTable table-striped ">
                    <thead>
                        <tr>
                            <th>Stage</th>
                            <th>Employee</th>
                            <th>Started On</th>
                            <th>Finished On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $stageConfig = [
                                ['name' => 'Design', 'stage' => 1, 'type' => '2-phase'], // is_completion: 0=start, 1=complete
                                ['name' => 'Milling', 'stage' => 2, 'type' => '3-phase'], // 2.1=nest, 2.2=start, 2.3=complete
                                ['name' => '3D Printing', 'stage' => 3, 'type' => '3-phase'], // 3.1=set, 3.2=start, 3.3=complete
                                ['name' => 'Sintering', 'stage' => 4, 'type' => '2-phase'], // 4.1=set, 4.2=start, 4.3=complete
                                ['name' => 'Pressing', 'stage' => 5, 'type' => '3-phase'], // 5.1=start, 5.2=complete
                                ['name' => 'Finishing', 'stage' => 6, 'type' => '2-phase'], // is_completion: 0=start, 1=complete
                                ['name' => 'QC', 'stage' => 7, 'type' => '2-phase'], // is_completion: 0=start, 1=complete
                            ];
                        @endphp
                        @foreach ($stageConfig as $config)
                            @php
                                $stage = $config['stage'];
                                $stageName = $config['name'];
                                $stageType = $config['type'];

                                if ($stageType === '3-phase') {
                                    // 3-phase: uses decimal stages (e.g., 2.1, 2.2, 2.3)
                                    $startLog = $case->logs->where('stage', $stage + 0.2)->first();
                                    $completeLog = $case->logs->where('stage', $stage + 0.3)->first();
                                    $employee = $startLog ? $startLog->user->fullName() : ($completeLog ? $completeLog->user->fullName() : null);
                                } else {
                                    // 2-phase: uses integer stage with is_completion flag (0=start, 1=complete)
                                    $startLog = $case->logs->where('stage', $stage)->where('is_completion', 0)->first();
                                    $completeLog = $case->logs->where('stage', $stage)->where('is_completion', 1)->first();
                                    $employee = $startLog ? $startLog->user->fullName() : ($completeLog ? $completeLog->user->fullName() : null);
                                }
                            @endphp
                            <tr>
                                <td class="stageName">{{ $stageName }}</td>
                                @if ($employee)
                                    <td>{{ $employee }}</td>
                                    <td>{{ $startLog ? substr($startLog->created_at, 0, 16) : '-' }}</td>
                                    <td>{{ $completeLog ? substr($completeLog->created_at, 0, 16) : '-' }}</td>
                                @else
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td class="stageName">Delivery</td>
                            @php
                                // Delivery uses 3 decimal stages: 8.1=assign, 8.2=accept, 8.3=complete
                                $deliveryStartLog = $case->logs->where('stage', 8.2)->first(); // Accept
                                $deliveryCompleteLog = $case->logs->where('stage', 8.3)->first(); // Complete
                                $deliveryEmployee = $deliveryStartLog ? $deliveryStartLog->user->fullName() : ($deliveryCompleteLog ? $deliveryCompleteLog->user->fullName() : null);
                            @endphp
                            @if ($deliveryEmployee)
                                <td>{{ $deliveryEmployee }}</td>
                                <td>{{ $deliveryStartLog ? substr($deliveryStartLog->created_at, 0, 16) : '-' }}</td>
                                <td>{{ $deliveryCompleteLog ? substr($deliveryCompleteLog->created_at, 0, 16) : '-' }}</td>
                            @else
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            @endif
                        </tr>


                    </tbody>
                </table>
            </div>


            <div class="Timeline">

                @php
                    function renderLog($log, $actionText, $actionClass) {
                        if (!$log || !isset($log->user)) return;
                        $formattedDate = \Carbon\Carbon::parse($log->created_at)->format('g:i A') . ' - ' . strtoupper(\Carbon\Carbon::parse($log->created_at)->format('j-M'));
                        echo '<div class="history-log-entry">
                                <span class="employee-initials">' . $log->user->name_initials . '</span>
                                <div class="action-info">
                                    <span class="action-label ' . $actionClass . '">' . $actionText . '</span>
                                    <span class="action-date">' . $formattedDate . '</span>
                                </div>
                                <div class="tooltip-content">
                                    <span class="tooltip-employee">' . $log->user->name . '</span>
                                    <span class="tooltip-action ' . $actionClass . '">' . $actionText . '</span>
                                    <span class="tooltip-date">' . $formattedDate . '</span>
                                </div>
                            </div>';
                    }
                @endphp

                <svg height="5" width="13">
                    <line x1="0" y1="0" x2="13" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>

                <div class="event1">
                    <div class="event1Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                DESIGN
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $designLogs = $case->logs->where('stage', 1);
                                $startLog = $designLogs->where('is_completion', 0)->sortByDesc('created_at')->first();
                                $completeLog = $designLogs->where('is_completion', 1)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>


                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event2">

                    <div class="event2Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                MILLING
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $millingLogs = $case->logs->where('stage', '>=', 2)->where('stage', '<', 3);
                                $nestLog = $millingLogs->where('stage', 2.1)->sortByDesc('created_at')->first();
                                $startLog = $millingLogs->where('stage', 2.2)->sortByDesc('created_at')->first();
                                $completeLog = $millingLogs->where('stage', 2.3)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($nestLog)
                                {!! renderLog($nestLog, 'NEST', 'action-nest') !!}
                            @endif
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$nestLog && !$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event1">
                    <div class="event1Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                3D Printing
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $printingLogs = $case->logs->where('stage', '>=', 3)->where('stage', '<', 4);
                                $setLog = $printingLogs->where('stage', 3.1)->sortByDesc('created_at')->first();
                                $startLog = $printingLogs->where('stage', 3.2)->sortByDesc('created_at')->first();
                                $completeLog = $printingLogs->where('stage', 3.3)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($setLog)
                                {!! renderLog($setLog, 'SET', 'action-set') !!}
                            @endif
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$setLog && !$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>


                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event2">

                    <div class="event2Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                Sintering
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $sinteringLogs = $case->logs->where('stage','>', 4)->where('stage', '<', 5);;
//
//
                               $startLog = $sinteringLogs->where('stage','<=', 4.1)->sortByDesc('created_at')->first();
                                $completeLog = $sinteringLogs->where('stage','>=', 4.2)->sortByDesc('created_at')->first();
//
                            @endphp
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event1">
                    <div class="event1Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                Pressing
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            <div class="eventTitle"
                                 style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                                @php
                                    $pressingLogs = $case->logs->where('stage', '>=', 5)->where('stage', '<', 6);

                                    // Match the 3-phase pattern of other stages (e.g., 3.1, 3.2, 3.3)
                                    $setLog = $pressingLogs->where('stage', 5.1)->sortByDesc('created_at')->first();
                                    $startLog = $pressingLogs->where('stage', 5.2)->sortByDesc('created_at')->first();
                                    $completeLog = $pressingLogs->where('stage', 5.3)->sortByDesc('created_at')->first();
                                @endphp

                                @if ($setLog)
                                    {!! renderLog($setLog, 'SET', 'action-set') !!}
                                @endif
                                @if ($startLog)
                                    {!! renderLog($startLog, 'START', 'action-start') !!}
                                @endif
                                @if ($completeLog)
                                    {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                                @endif
                                @if (!$setLog && !$startLog && !$completeLog)
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>


                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event2">

                    <div class="event2Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                Finishing
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $finishingLogs = $case->logs->where('stage', 6);
                                $startLog = $finishingLogs->where('is_completion', 0)->sortByDesc('created_at')->first();
                                $completeLog = $finishingLogs->where('is_completion', 1)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>


                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>

                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event1">
                    <div class="event1Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                QC
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $qcLogs = $case->logs->where('stage', 7);
                                $startLog = $qcLogs->where('is_completion', 0)->sortByDesc('created_at')->first();
                                $completeLog = $qcLogs->where('is_completion', 1)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($startLog)
                                {!! renderLog($startLog, 'START', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$startLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>
                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>
                <div class="event2">

                    <div class="event2Bubble">
                        <div class="eventTime">
                            <div class="Day">
                                Delivery
                            </div>
                        </div>
                        <div class="eventTitle"
                            style="text-align: left; font-size: 10px; line-height: 1.4; padding: 5px;">
                            @php
                                $deliveryLogs = $case->logs->where('stage', '>=', 8);
                                $assignLog = $deliveryLogs->where('stage', 8.1)->sortByDesc('created_at')->first();
                                $takeLog = $deliveryLogs->where('stage', 8.2)->sortByDesc('created_at')->first();
                                $completeLog = $deliveryLogs->where('stage', 8.3)->sortByDesc('created_at')->first();
                            @endphp
                            @if ($assignLog)
                                {!! renderLog($assignLog, 'ASSIGN', 'action-take') !!}
                            @endif
                            @if ($takeLog)
                                {!! renderLog($takeLog, 'TAKE', 'action-start') !!}
                            @endif
                            @if ($completeLog)
                                {!! renderLog($completeLog, 'COMPLETE', 'action-complete') !!}
                            @endif
                            @if (!$assignLog && !$takeLog && !$completeLog)
                                -
                            @endif
                        </div>
                    </div>

                    <svg height="20" width="20">
                        <circle cx="10" cy="11" r="5" fill="#004165" />
                    </svg>

                </div>
                <svg height="5" width="135">
                    <line x1="0" y1="0" x2="135" y2="0"
                        style="stroke:#004165;stroke-width:5" />
                    Sorry, your browser does not support inline SVG.
                </svg>

            </div>





            <!-- NOTES SECTION -->
            <br>
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h5 class="kt-portlet__head-title">
                        <i class="fa fa-sticky-note" style="width:2%"></i> Additional information
                    </h5>
                </div>
            </div>
            <hr>
            <br>
            <div class="form-group form-group">
                <label>Notes:</label>

                @foreach ($case->notes as $note)
                    <div class="form-control"
                        style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black"
                        disabled>

                        <span
                            class="noteHeader">{{ '[' . substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>
                        <span class="noteText">{{ $note->note }}</span>
                    </div>
                @endforeach

                <form></form>
                <form style="" class="noteform " method="POST" enctype="multipart/form-data"
                    action="{{ route('new-note') }}">
                    @csrf
                    <div class="row" style="padding:0px">
                        <input type="hidden" name="case_id_for_note" value ="{{ $case->id }}">
                        <div class="col-md-6 col-xs-6">
                            <input class="form-control" type="text" name="newNote" placeholder="Add a note" />
                        </div>

                        <div class="col-md-3 col-xs-3" style="margin: 0px">
                            <button type="submit" class="btn btn-primary">Add note</button>
                        </div>


                    </div>
                </form>
                <br><br>
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h5 class="kt-portlet__head-title">
                            <i class="fa fa-sticky-note" style="width:2%"></i> Attachments:
                        </h5>
                    </div>
                </div>
                <hr>
                <!-- Photos SECTION -->
                <div class="container" style="margin-top:10px;">

                    <div class="demo-gallery">
                        <ul id="lightgallery" class="list-unstyled row">
                            @foreach ($case->photos as $photo)
                                <li class="col-xs-6 col-sm-4 col-md-2 col-lg-2"
                                    data-responsive="{{ asset($photo->path) }}" data-src="{{ asset($photo->path) }}">
                                    <a href="">
                                        <img class="img-responsive" src="{{ asset($photo->path) }}">
                                    </a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>

                <br>
                {{-- <div class="form-group form-group-last"> --}}
                {{-- <label for="images">Add Photos:</label> --}}
                {{-- <input required type="file" id="images" class="form-control" name="images[]" placeholder="address" multiple disabled> --}}
                {{-- </div> --}}
                <br>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary" disabled>Submit</button>
                        <button type="reset" class="btn btn-danger" disabled>Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </form>





@endsection
@push('js')
    <script>
        jQuery(document).ready(function($) {
            // Initialize lightGallery
            $('#lightgallery').lightGallery();

            // Parse and format the delivery date for use in print labels
            var deliveryDate = "{{ $case->initial_delivery_date }}";
            var deliveryDateParts = {
                part1: '',
                part2: ''
            };

            if (deliveryDate) {
                try {
                    // Parse the delivery date using moment.js (already loaded in the app)
                    var momentDate = moment(deliveryDate);

                    if (momentDate.isValid()) {
                        // Format part1 as day and month (e.g., "15 Nov")
                        deliveryDateParts.part1 = momentDate.format('DD MMM');
                        // Format part2 as time only (e.g., "10:30 AM")
                        deliveryDateParts.part2 = momentDate.format('hh:mm A');
                    } else {
                        // Fallback if date is invalid
                        deliveryDateParts.part1 = deliveryDate;
                        deliveryDateParts.part2 = '';
                    }
                } catch (e) {
                    console.error('Error parsing delivery date:', e);
                    deliveryDateParts.part1 = deliveryDate;
                    deliveryDateParts.part2 = '';
                }
            }

            // Define the PrintLabel function and attach to window
            window.PrintLabel = function() {
                //height=192,width=288
                var mywindow = window.open('', 'PRINT', 'height=600,width=800');
                mywindow.document.write(`

            <style>
            @media all{
              #kt-invoice__head {}
              .kt-invoice__item {display:none;}

                        }

            body {
                font-family: Arial;
                font-weight : bold;
            }


            .tablesHeaders{
                font-size:12px;
                color:black;

            }


        .tableContent{
            font-size:13px;
            color:black;

        }

        hr.solid {
            border-top: 1px solid #bbb;
            width:95%;
        }

        .headerTitle{
            color:black;
        }

        #tableTail{
            padding-left:1px;
            padding-right:2px;
            width:100%;
            position: absolute;
            bottom: 3px;
        }
        .jobcolor
        {
            padding-left:0px;
            padding-right:5px;
        }
        .paddingLeft
        {
            padding-left:2px;

        }
        </style>
        </head>
        <body style="margin:0;padding:0;">

        <div id="kt-invoice__head" style="height:35px; overflow: hidden; position: relative;padding:0px;">


            <div style="float:right;text-align:right; padding-right:4px;padding-top:2px;width:35%">
            <p style="font-size: 8px;font-weight:bold;color:black;text-align:right;margin:0px">{{ $case->case_id }}</p>
            <p style="font-size: 10px;font-weight:bolder;color:black;text-align:right;margin:0px;line-height: 1em;padding-bottom: 3px;padding-top: 4px;">${deliveryDateParts.part1}</p>
            <p style="font-size: 10px;font-weight:bolder;color:black;text-align:right;margin:0px;line-height: 0.5em;">${deliveryDateParts.part2}</p>
            <div style="padding-top:5px;">
                {{-- @if ($isRemake) --}}
            {{-- <text style="border:1px; border-style:solid;padding:1px;">RM</text> --}}
                {{-- @endif --}}
                {{-- @if ($isRedo) --}}
            {{-- <text style="border:1px; border-style:solid;padding:1px;">RD</text> --}}
                {{-- @endif --}}

            </div>

            </div>


            <div id="headerInfo" style="width:60%;color:black;float:left;">

            <table >
            <tr style="color:black;">
            <th style="width:20%;text-align:left;font-size:11px;">Dr:</th>
        <th style="width:80%;font-size:12px;text-align:left;font-weight:bold;"><b>{{ $case->client->name }}</b> </th>
            </tr>
            <tr style="color:black;">
            <th style="width:20%;font-size:11px;text-align:left;">Patient:</th>
        <th style="width:70%;text-align:left;font-size:12px;font-weight:bold;">{{ $case->patient_name }}</th>
            </tr>
            </table>

            </div>



            </div>
                <hr style=" margin-top: 5px; margin-bottom: 0; border-color: white;">
            <div id="jobs" style="width:100%;display: flex;  ">
            <table class="table"  style="width: 100%;height: 100%;border-spacing: 1px 1px; margin: 0 auto;align-self: center;padding-top:5px;margin:0;padding-right:2px;">
            <thead>
            <tr>
            <th class="tablesHeaders" style="text-align:left" width="200"> Job Type</th>
        <th class="tablesHeaders" style="text-align:left" width="80;padding-left:0px">Material</th>
            <th class="tablesHeaders jobcolor" style="text-align:left;padding-left:0px" width="40">Color</th>
            <th class="tablesHeaders" style="text-align:left;" width="20">Qty</th>

            </tr>

            </thead>

            <tbody>

                @foreach ($jobs as $job)
            <tr style="text-align:center">
            <td class="tableContent" style="text-align:left;font-size:11px" width="200"> {{ $job->jobType->name }}</td>
            <td class="tableContent " style="text-align:left;font-size:11px"  width="80">{{ $job->material->name }}</td>
            <td class="tableContent jobcolor paddingLeft" style="text-align:left;font-size:11px" width="40">{{ $job->color == null ? '-' : $job->color }}</td>
            <td class="tableContent paddingLeft" style="text-align:left;font-size:11px" width="20">{{ count(explode(',', $job->unit_num)) }}</td>

            </tr>
                @endforeach


            </tbody>
            </table>

            <div id="tableTail">


            </div>


            </div>

            </body></html>
            `);
                mywindow.document.close(); // necessary for IE >= 10
                mywindow.focus(); // necessary for IE >= 10*/
                setTimeout(function() {
                    mywindow.print(); /*mywindow.close();*/
                }, 1000);

                return true;
            };

            // Define the PrintMinimizedLabel function and attach to window
            window.PrintMinimizedLabel = function() {
                // open new window for printing
                var mywindow = window.open('', 'PRINT', 'height=600,width=800');

                mywindow.document.write(`
            <html>
            <head>
                <style>
                    @media all {
                        .kt-invoice__item {display:none;}
                    }
                    body {
                        font-family: Arial, sans-serif;
                        font-weight: bold;
                        background-color: black;
                        margin: 0;
                        padding: 0;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        min-height: 100vh;
                    }
                    .paddingLeft {padding-left:2px;}
                </style>
            </head>
            <body>
                <div id="kt-invoice__head" style="text-align:center;display:flex;flex-direction:column;justify-content:center;align-items:center;height:100vh;overflow:hidden;position:relative;padding:0;">
                    <p style="font-size:20.3px;font-weight:bold;color:white;margin:0;">{{ $case->client->name }}</p>
                    <p style="font-size:20.3px;font-weight:bold;color:white;margin:0;">{{ $case->patient_name }}</p>
                    <p style="font-size:14.7px;font-weight:bold;color:white;margin:0;">${deliveryDateParts.part1}</p>
                    <p style="font-size:14.7px;font-weight:bold;color:white;margin:0;">${deliveryDateParts.part2}</p>
                </div>
            </body>
            </html>
        `);

                mywindow.document.close(); // finish writing
                mywindow.focus(); // focus the window

                setTimeout(function() {
                    mywindow.print();
                    mywindow.close();
                }, 1000);

                return true;
            };
        });
    </script>
@endpush
