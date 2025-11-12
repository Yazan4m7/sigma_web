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
            border-bottom: 1px solid #dee2e6 !important;
        }

        /* Doctor/Patient names styling */
        .patient-doctor-names {
            color: #2d5f6d;
            font-weight: 600;
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
                                            <input type="date" class="form-control" name="from" value="{{$from}}">
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-6 col-md-2 mb-3">
                                        <div class="kt-subheader__search">
                                            <label>To (End of):</label>
                                            <input type="date" class="form-control" name="to" value="{{$to}}">
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
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Case Actions</h5>

                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Sticky Doctor/Patient section -->
                                                        <div class="form-group row" style="margin-bottom: 0px">
                                                            <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                <label for="doctor">Doctor: </label>
                                                                <h5 id="doctor" class="patient-doctor-names">{{$case->client->name}}</h5>
                                                            </div>
                                                            <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                <label for="pat">Patient: </label>
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

                                                                        <span class="noteHeader" style="font-weight:600">{{'['. substr( $note->created_at,0,16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>
                                                                        <span class="noteText">{{$note->note}}</span>
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
                                                                       class="btn btn-info" style="width: 100%;"><i class="far fa-file-alt"></i> View Case</a>
                                                                </div>

                                                                <!-- Row 2: Lock Case, Delete Case, Edit Case -->
                                                                @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                                                                <div class="col-4" style="padding: 5px;">
                                                                    @if(!$case->locked)
                                                                        <a href="{{route('lock-case',$case->id)}}"
                                                                           class="btn btn-dark" style="width: 100%;"><i class="fas fa-lock"></i> Lock Case</a>
                                                                    @else
                                                                        <a href="{{route('unlock-case',$case->id)}}"
                                                                           class="btn btn-dark" style="width: 100%;"><i class="fas fa-lock-open"></i> Unlock Case</a>
                                                                    @endif
                                                                </div>
                                                                @endif
                                                                @if(Auth()->user()->is_admin && !$case->locked)
                                                                <div class="col-4" style="padding: 5px;">
                                                                    <a data-clientName="{{ $case->client->name }}"
                                                                       data-patientName="{{ $case->patient_name }}"
                                                                       style="color:white; width: 100%;"
                                                                       onclick="caseDelConfirmation(event)"
                                                                       href="{{route('delete-case',$case->id)}}"
                                                                       class="btn btn-danger"><i class="fas fa-trash"></i> Delete Case</a>
                                                                </div>
                                                                @endif
                                                                @if((Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))) || ($permissions && ((!isset($case->actual_delivery_date)&& $permissions->contains('permission_id', 115))) || ($case->jobs[0]->stage == 1 && $permissions->contains('permission_id', 1)))) && !$case->locked)
                                                                <div class="col-4" style="padding: 5px;">
                                                                    <a href="{{route('edit-case-view',$case->id)}}"
                                                                       class="btn btn-warning" style="width: 100%;"><i class="fa-solid fa-pen-to-square"></i> Edit Case</a>
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


                                                                <!-- Row 3 (for in-progress only): Redo button -->
                                                                @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 119)) && !$case->locked && !isset($case->actual_delivery_date))
                                                                <div class="col-4" style="padding: 5px;">
                                                                    <a href="{{route('redo-case-view',$case->id)}}"
                                                                       class="btn btn-outline-warning" style="width: 100%;"><i class="fa fa-broom"></i> Redo case</a>
                                                                </div>
                                                                @endif
                                                                <!-- Cancel Row: Before Redo -->
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

