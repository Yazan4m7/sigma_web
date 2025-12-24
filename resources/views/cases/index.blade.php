@extends('layouts.app' ,[ 'pageSlug' => "Cases List"])
@section('content')

    <style>
        /* Modal dialog border radius - all corners uniform */
        .modal-content {
            border-radius: 25px !important;
        }

        /* Modal title styling */
        .modal-title {
            color: #2d5f6d;
            font-weight: 600;
            font-size: 18px;
        }

        /* Modal header styling with divider */
        .modal-header {
            border-bottom: 0 !important;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        /* Doctor/Patient names styling */
        .patient-doctor-names {
            color: #2d5f6d;
            font-weight: 600;
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
        .modal-footer {

            border-bottom-left-radius: 25px !important;
            border-bottom-right-radius: 25px !important;
        }

        .modal-footer .btn {
            flex: 1;
            min-width: 120px;
            margin: 0;
            white-space: nowrap;
        }

        .content {
            background: #ffffff00;
        }

        /* Tooltip styling */
        .tooltiptext {
            display: none;
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

        /* Filter section improvements */
        .kt-subheader__search label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .kt-subheader__search .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, .075);
        }

        .kt-subheader__search .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        /* Filter container with subtle shadow and border */
        .container.full-width {
            background-color: #f8f9fa;
            border-radius: 5px;
            /*padding: 4px;*/
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            position: relative;
        }

        /* Trash can icon in corner */
        .trash-icon-corner {
            position: absolute;
            top: 8px;
            right: 12px;
            z-index: 10;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc3545;
            font-size: 18px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .trash-icon-corner:hover {
            color: #c82333;
            transform: scale(1.1);
        }

        /* Better spacing */
        .filter-section {
            margin-bottom: 1.5rem;
        }

        /* Button groups styling */
        .btn-group .btn {
            margin-left: 5px;
        }

        /* Table actions styling */
        .table-actions {
            margin-bottom: 15px;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            table {
                table-layout: fixed;
            }

            .content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .row {
                padding: 3px;
            }

            .initDeliDateHeader, .initDeliDateTD, .tagsHeader, .tagsTD {
                display: none;
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

            .dataTables_wrapper .dataTables_filter {
                text-align: center;
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
        }
    </style>
    @php
        $permissions = safe_permissions();

    @endphp
    @if(!isset($isSearchResults))
        @if(!isset($trashedCases))
            @if(isset($clients))
                <form class="kt-form" method="GET" action="{{route('cases-index')}}">
                    @else
                        <form class="kt-form" method="GET" action="{{route('dentist-cases',['id' =>$id])}}">
                            <input type="hidden" class="form-control" name="id" value="{{$id}}">
                            @endif
                            <div class="container full-width">
                                <!-- Trash can icon in top-right corner -->
                                <a href="{{route('deleted-cases')}}" class="trash-icon-corner" title="View Deleted Cases">
                                    <i class="fa-regular fa-trash-can"></i>
                                </a>

                                <div class="row " style="padding-bottom:0;">
                                    <!-- Date filtering section -->
                                    <div class="col-6 col-sm-6 col-md-2 mb-3">
                                        <div class="kt-subheader__search">
                                            <label>From (Start of):</label>
                                            <div class="ios-date-picker" data-name="from" data-initial="{{$from}}"></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-2 mb-3">
                                        <div class="kt-subheader__search">
                                            <label>To (End of):</label>
                                            <div class="ios-date-picker" data-name="to" data-initial="{{$to}}"></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-2 mb-3">
                                        <div class="kt-subheader__search">
                                            <label>To (End of):</label>
                                            <x-date-time-picker
                                                    name="meeting_time"
                                                    label="Meeting Time"
                                                    value="Monday, 2024-Dec-25 02:30 PM"
                                            />
                                        </div>
                                    </div>




                                    <!-- Doctor selection -->
                                    <div class="col-6 col-sm-6 col-md-3 mb-3">
                                        @if(isset($clients))
                                            <div class="dropdown" style="text-align: left;">
                                                <label>Doctor:</label>
                                                <br>
                                                <select style="width:100%" class="selectpicker clearOnAll greyBG"
                                                        multiple
                                                        name="doctor[]" id="doctor"
                                                        data-live-search="true">
                                                    <option
                                                        value="all" {{(isset($selectedClients) && in_array("All" ,$selectedClients)) ? 'selected' : ''}}>
                                                        All
                                                    </option>
                                                    @foreach($clients as $d)
                                                        <option
                                                            value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Search field -->
                                    <div class="col-6 col-sm-6 col-md-4 mb-3">
                                        <div class="kt-subheader__search">
                                            <label>Search:</label>
                                            <input type="text" class="form-control" id="tableSearch" placeholder="Search cases...">
                                        </div>
                                    </div>

                                    <!-- Apply Filters button -->
                                    <div class="col-6 col-sm-6 col-md-1 mb-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary" style="width: 100%; height: 38px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Added better spacing between filters and table -->
                            <div class="filter-section"></div>
                        </form>
                    @endif
                    @endif
                    <div class="container full-width">
                        <div class="row" style="">
                            <div class="col-12">
                                <br>
                                <table id="casesTable"
                                       class="table-striped compact sunriseTable"
                                       role="grid"
                                       style="width:100%">
                                    <thead>
                                    <tr role="row">
                                        <th>Doctor
                                        </th>
                                        <th>Patient</th>
                                        <th class="initDeliDateHeader">Initial Deli. Date</th>
                                        <th>Date Delivered</th>
                                        <th>Status</th>
                                        <th class="tagsHeader">Tags</th>

                                    </tr>
                                    </thead>

                                    <tbody>

                                    @foreach($cases  as $case)
                                        @php
                                            // Check if case is in-progress and initial_delivery_date has passed
                                            $isOverdue = false;
                                            if (!$case->actual_delivery_date && $case->initial_delivery_date) {
                                                $now = \Carbon\Carbon::now();
                                                $deliveryDate = \Carbon\Carbon::parse($case->initial_delivery_date);
                                                $isOverdue = $deliveryDate->lt($now);
                                            }
                                            $rowStyle = $isOverdue ? 'color: #dc3545; font-weight: 600;' : '';
                                        @endphp

                                        <tr role="row" class="odd clickable" data-toggle="modal"
                                            data-target="#actionsDialog{{$case->id ?? "x"}}" style="{{$rowStyle}}">
                                            <td>{{$case->client->name ?? "x"}}</td>
                                            <td>{{$case->patient_name ?? "x"}}</td>
                                            <td class="initDeliDateTD">{{$case->initDeliveryDate() ?? "x" }}
                                                &nbsp;&nbsp; {{$case->initDeliveryTime() ?? "x"}}</td>
                                            <td>{{$case->actualDeliveryDate()=="" ? "Not yet" : $case->actualDeliveryDate()}}
                                                &nbsp;&nbsp; {{$case->actualDeliveryTime() ?? ""}}</td>
                                            <td>
                                                @if(str_contains($case->status(), "Completed") )
                                                    <span class="badge badge-success">
                                                                           {{$case->status()}} </span>
                                                @elseif(str_contains($case->status(), "In-Progress") || str_contains($case->status(), "Active"))
                                                    <span style="width:auto; margin: auto; text-align: center"
                                                          class="badge badge-primary">
                                                                           <span class="tooltipX"> {{str_replace("Active in","",$case->status())}}
                                                                               <span
                                                                                   class="tooltiptext">{!!  $case->getStatusToolTipHTML() !!}</span>
                                                                </span></span>
                                                @elseif(str_contains($case->status(), "Waiting"))
                                                    <span style="width:auto; margin: auto; text-align: center"
                                                          class="badge badge-danger">
                                                                @php
                                                                    $status =  preg_replace('/' . "in" . '/', "", str_replace("Waiting","",$case->status()), 1);
                                                                @endphp

                                                        {{$status}} </span>
                                                @else
                                                    <span style="width:auto; margin: auto; text-align: center"
                                                          class="badge badge-warning">
                                                                           <span class="tooltipX"> {{$case->status()}}
                                                                               <span
                                                                                   class="tooltiptext">{!!  $case->getStatusToolTipHTML() !!}</span>
                                                                </span></span>

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
                                        <div class="modal" tabindex="-1" role="dialog" id="actionsDialog{{$case->id}}">

                                            <input type="hidden" name="case_id" value="{{$case->id}}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">

                                                    <div class="modal-body">
                                                        <!-- Sticky Doctor/Patient section -->
                                                        <div class="form-group row" style="margin-bottom: 0px">
                                                            <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                <label for="doctor" class="patient-doctor-label">Doctor:</label>
                                                                <h5 id="doctor" class="patient-doctor-names">{{$case->client->name ?? "-"}}</h5>
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

                                                                        <span class="noteHeader" style="font-weight:600">{{ '[' . \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) . ' ' }}<b>{{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}</b>{{ '] [' . $note->writtenBy->name_initials . '] : ' }}</span><span class="noteText">{{$note->note}}</span>
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
                                                                       class="btn btn-info" style="width: 100%;"><i class="fas fa-print"></i> Print Voucher</a>
                                                                </div>
                                                                <div class="col-6" style="padding: 5px;">
                                                                    <a href="{{route('view-case',['id' =>$case->id ,'stage' =>-2 ])}}"
                                                                       class="btn btn-info" style="width: 100%;"><i class="far fa-file-alt"></i> View </a>
                                                                </div>

                                                                <!-- Row 2: Lock Case, Delete Case -->
                                                                @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                                                                <div class="col-6" style="padding: 5px;">
                                                                    @if(!$case->locked)
                                                                        <a href="{{route('lock-case',$case->id)}}"
                                                                           class="btn btn-dark" style="width: 100%;"><i class="fas fa-lock"></i> Lock </a>
                                                                    @else
                                                                        <a href="{{route('unlock-case',$case->id)}}"
                                                                           class="btn btn-dark" style="width: 100%;"><i class="fas fa-lock-open"></i> Unlock </a>
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
                                                                       class="btn btn-danger"><i class="fas fa-trash"></i> Delete </a>
                                                                </div>
                                                                @endif

                                                                <!-- Row 3: For completed cases only - Reject, Repeat, Modify -->
                                                                @if (isset($case->actual_delivery_date))
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 116)) && !$case->locked)
                                                                    <div class="col-4" style="padding: 5px;">
                                                                        <a href="{{route('reject-case-view',$case->id )}}"
                                                                           class="btn btn-outline-danger" style="width: 100%;"><i class="fas fa-times x2"></i> Reject case</a>
                                                                    </div>
                                                                    @endif
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 117))&&!$case->locked)
                                                                    <div class="col-4" style="padding: 5px;">
                                                                        <a href="{{route('repeat-case-view',$case->id)}}"
                                                                           class="btn btn-outline-warning" style="width: 100%;"><i class="fas fa-undo"></i> Repeat case</a>
                                                                    </div>
                                                                    @endif
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 118)) && !$case->locked)
                                                                    <div class="col-4" style="padding: 5px;">
                                                                        <a href="{{route('modify-case-view',$case->id)}}"
                                                                           class="btn btn-outline-warning" style="width: 100%;"><i class="fa fa-broom"></i> Modify case</a>
                                                                    </div>
                                                                    @endif
                                                                @endif


                                                                <!-- Row 3: Redo case and Edit -->
                                                                @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 119)) && !$case->locked && !isset($case->actual_delivery_date))
                                                                <div class="col-6" style="padding: 5px;">
                                                                    <a href="{{route('redo-case-view',$case->id)}}"
                                                                       class="btn btn-outline-warning" style="width: 100%;"><i class="fa fa-broom"></i> Redo case</a>
                                                                </div>
                                                                @endif
                                                                @if((Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))) || ($permissions && ((!isset($case->actual_delivery_date)&& $permissions->contains('permission_id', 115))) || (optional($case->jobs->first())->stage == 1 && $permissions->contains('permission_id', 1)))) && !$case->locked)
                                                                <div class="col-6" style="padding: 5px;">
                                                                    <a href="{{route('edit-case-view',$case->id)}}"
                                                                       class="btn btn-warning" style="width: 100%;"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                                                </div>
                                                                @endif
                                                                <!-- Cancel Row -->
                                                                <div class="col-12" style="padding: 5px;">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: 100%;">Cancel</button>
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
                                    </tbody>

                                </table>

                            </div>
                            <div style="text-align:right">

                            </div>
                        </div>
                    </div>

                    </div>
                </form>
                @push('js')
                    <style>
                        /* Scoped iOS-style date picker */
                        .ios-picker-wrap {
                            position: relative;
                            width: 100%;
                        }

                        .ios-picker-input {
                            width: 100%;
                            padding: 10px 12px;
                            border: 1px solid #d0d5dd;
                            border-radius: 10px;
                            background: #fff;
                            color: #111827;
                            font-size: 14px;
                            text-align: left;
                            cursor: pointer;
                            transition: border-color 0.2s ease, box-shadow 0.2s ease;
                        }

                        .ios-picker-input:focus {
                            outline: none;
                            border-color: #2563eb;
                            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
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
                        (function() {
                            const ITEM_HEIGHT = 36;

                            function formatDisplay(dateStr) {
                                if (!dateStr) return 'Select date';
                                const parts = dateStr.split('-');
                                if (parts.length !== 3) return 'Select date';
                                const d = new Date(dateStr + 'T00:00:00');
                                return d.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                            }

                            function buildWheel(list, values, selected) {
                                list.innerHTML = '';
                                values.forEach(v => {
                                    const li = document.createElement('li');
                                    li.textContent = v.label;
                                    li.dataset.value = v.value;
                                    if (v.value === selected) li.classList.add('selected');
                                    list.appendChild(li);
                                });
                            }

                            function snap(wheel) {
                                const idx = Math.round(wheel.scrollTop / ITEM_HEIGHT);
                                const target = idx * ITEM_HEIGHT;
                                wheel.scrollTo({ top: target, behavior: 'auto' });
                                wheel.querySelectorAll('li').forEach((li, i) => {
                                    li.classList.toggle('selected', i === idx);
                                });
                            }

                            function initPicker(host) {
                                const name = host.dataset.name;
                                const initial = host.dataset.initial || '';

                                const wrapper = document.createElement('div');
                                wrapper.className = 'ios-picker-wrap';

                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = name;
                                input.value = initial;

                                const display = document.createElement('button');
                                display.type = 'button';
                                display.className = 'ios-picker-input';
                                display.textContent = formatDisplay(initial);

                                const panel = document.createElement('div');
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

                                wrapper.appendChild(input);
                                wrapper.appendChild(display);
                                wrapper.appendChild(panel);
                                host.replaceWith(wrapper);

                                const yearWheel = panel.querySelector('.js-wheel-year');
                                const monthWheel = panel.querySelector('.js-wheel-month');
                                const dayWheel = panel.querySelector('.js-wheel-day');
                                const yearList = yearWheel.querySelector('ul');
                                const monthList = monthWheel.querySelector('ul');
                                const dayList = dayWheel.querySelector('ul');

                                const today = initial ? new Date(initial + 'T00:00:00') : new Date();
                                let selYear = today.getFullYear();
                                let selMonth = today.getMonth() + 1;
                                let selDay = today.getDate();

                                const years = [];
                                const currentYear = new Date().getFullYear();
                                for (let y = currentYear - 100; y <= currentYear + 10; y++) {
                                    years.push({ label: y, value: y });
                                }
                                const months = Array.from({ length: 12 }, (_, i) => ({
                                    label: new Date(2000, i, 1).toLocaleString('en', { month: 'short' }),
                                    value: i + 1
                                }));

                                function rebuildDays() {
                                    const max = new Date(selYear, selMonth, 0).getDate();
                                    const days = Array.from({ length: max }, (_, i) => ({ label: i + 1, value: i + 1 }));
                                    if (selDay > max) selDay = max;
                                    buildWheel(dayList, days, selDay);
                                    dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;
                                }

                                buildWheel(yearList, years, selYear);
                                buildWheel(monthList, months, selMonth);
                                rebuildDays();

                                yearWheel.scrollTop = years.findIndex(y => y.value === selYear) * ITEM_HEIGHT;
                                monthWheel.scrollTop = (selMonth - 1) * ITEM_HEIGHT;
                                dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;

                                const wheels = [
                                    { el: yearWheel, list: yearList, onChange: v => { selYear = v; rebuildDays(); } },
                                    { el: monthWheel, list: monthList, onChange: v => { selMonth = v; rebuildDays(); } },
                                    { el: dayWheel, list: dayList, onChange: v => { selDay = v; } },
                                ];

                                wheels.forEach(({ el, list, onChange }) => {
                                    let t;
                                    el.addEventListener('scroll', () => {
                                        clearTimeout(t);
                                        t = setTimeout(() => {
                                            snap(el);
                                            const idx = Math.round(el.scrollTop / ITEM_HEIGHT);
                                            const li = list.children[idx];
                                            if (li) {
                                                onChange(parseInt(li.dataset.value, 10));
                                            }
                                        }, 80);
                                    });
                                });

                                function saveAndClose() {
                                    const monthStr = String(selMonth).padStart(2, '0');
                                    const dayStr = String(selDay).padStart(2, '0');
                                    const newVal = `${selYear}-${monthStr}-${dayStr}`;
                                    input.value = newVal;
                                    display.textContent = formatDisplay(newVal);
                                    panel.classList.remove('open');
                                }

                                function cancelAndClose() {
                                    panel.classList.remove('open');
                                }

                                display.addEventListener('click', () => {
                                    panel.classList.add('open');
                                    panel.focus();
                                });

                                panel.querySelector('.js-done').addEventListener('click', saveAndClose);
                                panel.querySelector('.js-cancel').addEventListener('click', cancelAndClose);

                                document.addEventListener('click', (e) => {
                                    if (!panel.classList.contains('open')) return;
                                    if (!panel.contains(e.target) && e.target !== display) {
                                        saveAndClose();
                                    }
                                });
                            }

                            document.addEventListener('DOMContentLoaded', () => {
                                document.querySelectorAll('.ios-date-picker').forEach(initPicker);
                            });
                        })();
                    </script>
                    {{--<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>--}}
                    <!-- Responsive and datable js -->
                    <script type="text/javascript">
                        $(document).ready(function () {

                            var table = $("#casesTable").DataTable({
                                "fixedHeader": true,
                                "colReorder": true,
                                "responsive": true,
                                "bLengthChange": false,  // Disable "Show XX entries" dropdown
                                "iDisplayLength": 20,
                                "order": [],  // Disable initial sorting to preserve server-side order
                                "dom": 'rtip',  // Hide default search box ('f' removed) but keep table, info, pagination
                                "bProcessing": true,
                                "searching": true,  // Enable searching for real-time filter
                                "columnDefs": [
                                    { "orderable": false, "targets": [0, 1, 5] }  // Disable sorting on Doctor, Patient, and Tags columns
                                ]
                            });

                            // Connect custom search field to DataTable for real-time search
                            $('#tableSearch').on('keyup', function() {
                                table.search(this.value).draw();
                            });

                        });

                        function caseDelConfirmation(ev) {
                            ev.preventDefault();
                            var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
                            var clientName = ev.currentTarget.getAttribute('data-clientName');
                            var patientName = ev.currentTarget.getAttribute('data-patientName');

                            //console.log(urlToRedirect); // verify if this is the right URL
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
                                } else if (result.isDenied) {
                                    swal.fire("Case NOT deleted.");
                                }
                            });

                        }
                    </script>

                @endpush


                @endsection
