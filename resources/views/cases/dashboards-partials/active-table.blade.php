<style>
    /* Modal footer button styling with proper contrast */
    .fullBtnsWidth .btn {
        font-weight: 400;
        padding: 10px 12px;
        border: none;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    /* Modal dialog border radius - all corners uniform */
    .modal-content {
        border-radius: 25px !important;
    }

    /* Modal footer rounded bottom corners */
    .modal-footer {
        border-bottom-left-radius: 25px !important;
        border-bottom-right-radius: 25px !important;
    }

    /* Modal header styling with divider */
    .modal-header {
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 12px;
    }

    /* Modal title styling */
    .modal-title {
        color: #2d5f6d;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 0;
    }

    /* Skip to delivery icon styling */
    .skip-to-delivery-icon {
        font-size: 20px;
        color: #2d5f6d;
        transition: color 0.3s ease;
    }
    .skip-to-delivery-icon:hover {
        color: #1a3d47;
    }

    /* Close button styling - more visible */
    .modal-header button.close {
        font-size: 32px;
        font-weight: 300;
        color: #000;
        opacity: 0.8;
        text-shadow: none;
    }
    .modal-header button.close:hover {
        opacity: 1;
        color: #000;
    }

    /* Doctor/Patient names styling */
    .patient-doctor-names {
        color: #2d5f6d;
        font-weight: 600;
    }

    /* Scrollable section for jobs and notes only */
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

    .fullBtnsWidth {
        border-top: 1px solid #dee2e6;
    }

    .fullBtnsWidth .btn-info {
        background-color: #17a2b8;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
        margin: 3px;
    }
    .fullBtnsWidth .btn-info:hover {
        background-color: #138496;
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
    }

    .fullBtnsWidth .btn-success {
        background-color: #28a745;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        margin: 3px;
    }
    .fullBtnsWidth .btn-success:hover {
        background-color: #218838;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    }
    .fullBtnsWidth .btn-success:disabled {
        background-color: #6c757d;
        color: #ffffff !important;
        opacity: 0.6;
    }

    .fullBtnsWidth .btn-warning {
        background-color: #ffc107;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
        margin: 3px;
    }
    .fullBtnsWidth .btn-warning:hover {
        background-color: #e0a800;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
    }

    .fullBtnsWidth .btn-dark {
        background-color: #343a40;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
        margin: 3px;
    }
    .fullBtnsWidth .btn-dark:hover {
        background-color: #23272b;
        box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
    }

    .fullBtnsWidth .btn-outline-info {
        border: 2px solid #17a2b8;
        background-color: transparent;
        color: #17a2b8 !important;
        margin: 3px;
    }
    .fullBtnsWidth .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #ffffff !important;
    }

    .fullBtnsWidth .btn-outline-danger {
        border: 2px solid #dc3545;
        background-color: transparent;
        color: #dc3545 !important;
        margin: 3px;
    }
    .fullBtnsWidth .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #ffffff !important;
    }

    .fullBtnsWidth .btn-secondary {
        background-color: #6c757d;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
        margin: 3px;
    }
    .fullBtnsWidth .btn-secondary:hover {
        background-color: #5a6268;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
    }

    .fullBtnsWidth .btn i {
        margin-right: 6px;
    }
</style>

<table class=" activeTable sunriseTable" style="width:100%;">
    <thead>
        <tr>
            <th>Doctor</th>
            <th>Patient</th>
            <th class="deliveryToHeader">Delivery Date</th>
            <th class="assignedToHeader">Assigned To</th>
            <th class="">#</th>
            <th class="">Tags</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stage['activeCases'] as $case)
        <tr class="clickable" style="color:{{$color}}" data-toggle="modal"
            data-target="#confirmCompletion{{$key.$case->id}}">
            @if ($key == "finishing")
            @php
            $notReadyA=false;
            $abutmentsReceived = $case->abutmentsReceived();
            if(!$case->allUnitsAtFinishing())
            $notReadyA=true;
            @endphp
            @endif
            <td>
                <p class="">{{$case->client ? $case->client->name : 'No Client'}}</p>
            </td>
            <td>
                <p class="">{{$case->patient_name}} @if ($key == "finishing")
                    @if($notReadyA) <span
                        style="float:right;margin-left: 5px; line-height: 1;color:#ffa400;font-size: 9px;">
                        Not <br>
                        Ready
                    </span> @endif

                    @if(!$abutmentsReceived) <span
                        style="float:right; line-height: 1;color:#ffa400;font-size: 9px;">
                        Abutment <br>
                        Missing
                    </span> @endif
                    @endif

                </p>
            </td>
            <td class="">
                <p class="">{{date_format(date_create($case->initDeliveryDate()),"d-M")}}</p>
            </td>
            <td>
                <p class="">{{$case->jobs->where('stage',$stage["numericStage"])->first() ? ($case->jobs->where('stage',$stage["numericStage"])->first()->assignedTo? $case->jobs->where('stage',$stage["numericStage"])->first()->assignedTo->name_initials : "None") : "None"}}</p>
            </td>
            <td class="">
                <p class="">{{$case->unitsAmount($stage["numericStage"])}}</p>
            </td>
            <td class="">

                @foreach($case->tags as $tag)
                    @if(isset($tag->originalTagRecord))
                        <i title="{{$tag->originalTagRecord->text}}"
                            style="color:{{$tag->originalTagRecord->color}}"
                            class="{{$tag->originalTagRecord->icon}}  fa-lg"></i>
                    @endif
                @endforeach
            </td>
        </tr>
        <!-- End Active tab -->

        <!-- External Milling Dialog -->
        @if ($key == "milling")
        <div class="modal fade" tabindex="-1" role="dialog"
            id="MEX{{$case->id}}">
            <form action="{{route('externally-milled')}}"
                method="POST">
                @csrf
                <input type="hidden" name="case_id"
                    value="{{$case->id}}">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Case milling
                                information</h5>
                            <button type="button" class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="form-group col-6 lab_id">
                                    <label for="lab_id">Lab
                                        name: </label>
                                    <select class="form-control"
                                        id="lab_id"
                                        name="lab_id">
                                        <option selected>Select
                                            your lab
                                        </option>
                                        @foreach($labs as $lab)
                                        <option value="{{$lab->id}}">{{$lab->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer fullBtnsWidth">
                            <button type="submit"
                                class="btn btn-primary">Save
                                changes
                            </button>
                            <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal">Close
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endif
        <!-- Active case actions Dialog -->
        <div class="modal fade" tabindex="-1" role="dialog"
            id="confirmCompletion{{$key.$case->id}}">
            <form action="{{$key == "delivery" ? route('delivery-accept', $case->id) : route('finish-case',['caseId'=> $case->id,'stage'=>$stage["numericStage"]] )}}"
                method="GET">
                @csrf
                <input type="hidden" name="case_id" value="{{$case->id}}">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Case Completion</h5>

                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <!-- Sticky Doctor/Patient section -->
                            <div class="form-group row" style="margin-bottom: 0px">
                                <div class="form-group col-6 "
                                    style="margin-bottom: 0px">
                                    <label for="doctor">Doctor: </label>
                                    <h5 id="doctor" class="patient-doctor-names">{{$case->client->name}}</h5>
                                </div>
                                <div class="form-group col-6 "
                                    style="margin-bottom: 0px">
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


                                        @foreach( $case->jobs->where('stage',$stage["numericStage"]) as $job)

                                        @php
                                        $unit = explode(', ',$job->unit_num);
                                        // Check if this job goes through the current stage based on material
                                        $showJob = $job->goesThroughStage($stage["numericStage"]);
                                        @endphp

                                        @if($showJob)
                                        <span>{{$job->unit_num}}
                                            - {{$job->jobType->name ?? "No Job Type"}}
                                            - {{$job->material->name ?? "no material"}} {{$job->color =='0' ? "":" - " .$job->color}}
                                            {{$job->style == 'None' ? "":" - " .$job->style}} {{isset($job->implantR) && $job->jobType->id ==6  ?( " - Implant Type: " . $job->implantR->name): "" }}
                                            <br>
                                            {{isset($job->abutmentR) && $job->jobType->id ==6  ?( " Abutment Type: " . $job->abutmentR->name): "" }} </span>
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
                        <div class="modal-footer fullBtnsWidth">
                            <div class="row btnsRow"
                                style=" margin-right: 0px; margin-left: 0px;width:100%">
                                @php
                                $isAdmin = Auth()->user()->is_admin;
                                $canBeFinished= true;
                                $isUserCase = false;
                                $canComplete = false;
                                if($case->jobs->where('stage',$stage["numericStage"])->first() && $case->jobs->where('stage',$stage["numericStage"])->first()->assignee == Auth()->user()->id)
                                {$canComplete = true;
                                $isUserCase= true; }
                                if($key == "finishing")
                                if ($notReadyA || !$abutmentsReceived){
                                $canComplete= false;
                                $canBeFinished = false;
                                }
                                @endphp

                                <!-- Row 1: Delivery status (100%) - Layout 3 only -->
                                @if($key == "delivery")
                                <div class="col-12 padding5px">
                                    <a class="dropdown-item" href="{{route('delivered-in-box',$case->id)}}">
                                        <button type="button" class="btn btn-outline-info" style="width:100%">Delivered In Box</button>
                                    </a>
                                </div>
                                @endif

                                <!-- Row 2: View (25%) | Complete (50%) | Edit (25%) -->
                                <div class="col-3 padding5px" style="display: flex;">
                                    <a href="{{route('view-case', ['id' => $case->id, 'stage' =>$stage["numericStage"]])}}" style="width:100%; display: flex;">
                                        <button type="button" class="btn btn-info" style="width:100%; display: flex; align-items: center; justify-content: center;">View</button>
                                    </a>
                                </div>

                                <div class="col-6 padding5px" style="display: flex;">
                                    @if ($isAdmin && $canBeFinished && !$isUserCase)
                                    <a href="{{route('complete-by-admin', ['id'=>$case->id,'stage'=>$stage["numericStage"]] )}}" style="width:100%; display: flex;">
                                        <button type="button" class="btn btn-success" style="width:100%; display: flex; align-items: center; justify-content: center;">Complete</button>
                                    </a>
                                    @else
                                    <button type="submit" class="btn btn-success"
                                        style="width:100%; display: flex; align-items: center; justify-content: center;" {{$canComplete ? '' : 'disabled'}}>{{$canComplete ? 'Complete' : 'Case cannot be completed'}}</button>
                                    @endif
                                </div>

                                <div class="col-3 padding5px" style="display: flex;">
                                    <a href="{{route('edit-case-view',$case->id)}}" style="width:100%; display: flex;">
                                        <button type="button" class="btn btn-warning" {{$canEditCase ? '' : 'disabled'}} style="width:100%; display: flex; align-items: center; justify-content: center;">Edit Case</button>
                                    </a>
                                </div>

                                <!-- Row 3: Print voucher (100%) - Layout 3 only -->
                                @if ($key == "delivery")
                                <div class="col-12 padding5px">
                                    <a class="dropdown-item" href="{{route('view-voucher',$case->id)}}">
                                        <button type="button" class="btn btn-outline-info" style="width:100%">Print voucher</button>
                                    </a>
                                </div>
                                @endif

                                <!-- Row 4: Externally Milled (100%) - Layout 5 only -->
                                @if ($key == "milling")
                                <div class="col-12 padding5px">
                                    <button type="button" class="btn btn-dark"
                                        data-toggle="modal"
                                        data-target="#MEX{{$case->id}}"
                                        data-dismiss="modal" style="width:100%">
                                        Externally Milled
                                    </button>
                                </div>
                                @endif

                                <!-- Row 5: Reset To Waiting (100%) -->
                                <div class="col-12 padding5px">
                                    <a href="{{route('reset-to-waiting', ['id'=>$case->id,'stage'=>$stage["numericStage"]] )}}">
                                        <button type="button" class="btn btn-outline-danger" style="width:100%">Reset To Waiting</button>
                                    </a>
                                </div>

                                <!-- Row 6: Cancel (100%) -->
                                <div class="col-12 padding5px">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal" style="width:100%">Cancel
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </form>

            /////////// v2 DIALOG

            @endforeach
    </tbody>
</table>
