
<style>
    /* Compact, breakpoint-driven waiting dialog */
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-content { border-radius: 20px; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-header { border-bottom: 1px solid #dee2e6; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-footer { border-top: 1px solid #dee2e6; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .btn { width: 100%; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .patient-doctor-names { color: #2d5f6d; font-weight: 600; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .scrollable-content { max-height: 40vh; overflow-y: auto; }
    
.waiting-dialog.sigma-modal--dashboard-waiting-actions .note-container { background: #e8f0f2; border: 1px solid #b8d4db; }
    .waiting-actions { width: 100%; margin: 0; }
    .waiting-actions > [class*='col-'] { display: flex; }
    .waiting-actions .btn { flex: 1; }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog {
        margin: 1.25rem auto 1.75rem;
        padding-bottom: env(safe-area-inset-bottom, 16px);
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-content {
        position: relative;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 40px);
        border-radius: 20px;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-footer {
        padding: 0.75rem 1rem;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-body {
        flex: 1 1 auto;
        overflow: hidden;
        padding: 1rem 1.25rem;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .scrollable-content {
        flex: 1 1 auto;
        max-height: clamp(220px, 45vh, 420px);
        overflow-y: auto;
        overflow-x: hidden;
        margin-right: -4px;
        padding-right: 4px;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-bottom: 0.35rem;
        gap: 0.5rem;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-top-actions .modal-close {
        border: none;
        background: transparent;
        font-size: 1.75rem;
        line-height: 1;
        color: #000;
        opacity: 0.65;
        padding: 0;
    }

    
.case-action-dialog.sigma-modal--dashboard-waiting-actions .modal-top-actions .modal-close:hover {
        opacity: 1;
    }

    /* 1) <=400px */
    @media (max-width: 400px){
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog { width: 95vw; margin: 0.5rem auto; }
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-body { padding: 0.85rem; }
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-title { font-size: 16px; }
        .waiting-actions .col-3, .waiting-actions .col-6, .waiting-actions .col-12 { flex: 0 0 100%; max-width: 100%; }
        .waiting-actions .btn { margin-bottom: 8px; }
    }

    /* 2) 401-576px */
    @media (min-width: 401px) and (max-width: 576px){
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog { width: 92vw; margin: 0.75rem auto; }
        .waiting-actions .col-3 { flex: 0 0 50%; max-width: 50%; }
        .waiting-actions .col-6 { flex: 0 0 50%; max-width: 50%; }
        .waiting-actions .col-12 { flex: 0 0 100%; max-width: 100%; }
    }

    /* 3) 577-768px */
    @media (min-width: 577px) and (max-width: 768px){
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog { width: 88vw; }
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-body { padding: 1rem 1.25rem; }
        .waiting-actions .col-3 { flex: 0 0 33.333%; max-width: 33.333%; }
        .waiting-actions .col-6 { flex: 0 0 33.333%; max-width: 33.333%; }
    }

    /* 4) 769-992px */
    @media (min-width: 769px) and (max-width: 992px){
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog { width: 75vw; }
        .waiting-actions .col-3 { flex: 0 0 25%; max-width: 25%; }
        .waiting-actions .col-6 { flex: 0 0 50%; max-width: 50%; }
    }

    /* 5) >=1200px */
    @media (min-width: 1200px){
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-dialog { max-width: 640px; }
        
.waiting-dialog.sigma-modal--dashboard-waiting-actions .modal-body { padding: 1.25rem 1.5rem; }
    }
</style>
@php
// Check if user has permission to assign delivery cases (admins or users with permission 129)
$canAssignDelivery = (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 129)));
@endphp

<table class=" waitingTable sunriseTable" style="width:100%">
    <thead>
        <tr>
            <td class="no-sort">
                <span class="innerSpan4Mobile" style="position:absolute; left: 40% !important;  bottom:0px;">
                {{-- Show checkbox header for all stages EXCEPT delivery without permission --}}
                @if ($key != 'delivery' || $canAssignDelivery)
                @if ($key == "milling" || $key == "sintering" ||$key == "3dprinting" || $key == "pressing" || $key == "delivery")
                <input type="checkbox" class="selectAllCases {{$key}}" value="0" name="selectAllCases" onchange="selectAll(this, '{{$key}}')"  style="position:absolute; left: 40% !important;  bottom:0px; "/>
                @endif
                @endif
                </span>
            </td>
            <th>Doctor</th>
            <th>Patient</th>
            <th class="deliveryDateHeader"><span class="innerSpan4Mobile">D.Date</span><span
                    class="innerSpan4DeskTop">Delivery Date</span></th>
            @if ($key == "Delivery")
                <th> Assigned To</th>
            @endif
            <th>#</th>

            <th>Tags</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stage['waitingCases'] as $case)

        <tr style="color:{{$color}}">
            @if ($key == "Finishing")
            @php
            $notReadyA=false;
            $abutmentsReceived = $case->abutmentsReceived();
            if(!$case->allUnitsAtFinishing())
            $notReadyA=true;
            @endphp
            @endif
            <td>
                {{-- Show row checkbox for all stages EXCEPT delivery without permission --}}
                @if ($key != 'delivery' || $canAssignDelivery)
                @if ($key == "milling" || $key == "3dprinting" || $key == "sintering" || $key == "pressing" || $key == "delivery" )
                <input type="checkbox" class="custom-control-input multipleCB {{$key}}" value="{{$case->id}}" name="CheckBoxes{{$key}}[]" data-group-id="{{$key}}" onchange="multiCBChanged('{{$key}}', this)" />
                @endif
                @endif
            </td>
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">
                <p class="">{{$case->client ? $case->client->name : 'No Client'}}</p>
            </td>
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">
                <p class="">{{$case->patient_name}} @if ($key == "Finishing")
                    @if($notReadyA) <span style="margin: 4px 16px 1px 1px;float:right; line-height: 1;color:#ffa400;font-size: 10px;">
                        Not <br>
                        Ready
                    </span> @endif
                    @if(!$abutmentsReceived) <span style="margin: 4px 16px 1px 1px;float:right; line-height: 1;color:#ffa400;font-size: 10px;">
                        Abutment <br>
                        Missing
                    </span> @endif
                    @endif
                </p>
            </td>
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">
                <p class="">{{date_format(date_create($case->initDeliveryDate()),"d-M")}}</p>
            </td>
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">
                <p class="">{{$case->unitsAmount($stage['numericStage'])}}</p>
            </td>
            <!-- Assigned to for delivery stage -->
            @if ($key == "delivery")
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">
                <p class="">{{$case->jobs->where('stage',$stage['numericStage'])->first()->assignedTo ?
                             $case->jobs->where('stage',$stage['numericStage'])->first()->assignedTo->name_initials : "None"}}</p>
            </td>
            @endif
            <td class="clickable" data-toggle="modal"
                data-target="#waitingDialog{{$key. $case->id}}">

                @foreach($case->tags as $tag)
                    @if(isset($tag->originalTagRecord))
                        <i title="{{$tag->originalTagRecord->text}}"
                            style="color:{{$tag->originalTagRecord->color}}"
                            class="{{$tag->originalTagRecord->icon}}  fa-lg"></i>
                    @endif
                @endforeach
            </td>
        </tr>


        {{--BEGIN WAITING DIALOG --}}
        <div class="modal fade waiting-dialog case-action-dialog sigma-modal--dashboard-waiting-actions" tabindex="-1" role="dialog" id="waitingDialog{{$key.$case->id}}">
            <form action="{{$key=="Delivery" ? route('delivery-accept', $case->id) : route('assign-to-me',['caseId'=> $case->id,'stage'=>$stage["numericStage"]] )}}"
                method="GET">
                @csrf
                <input type="hidden" name="case_id" value="{{$case->id}}">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="modal-top-actions">
                                @if(Auth()->user()->is_admin )
                                <div class="tooltipY">
                                    <a href="{{route('finish-case-completely',['caseId' => $case->id])}}">
                                        <i class="fa-solid fa-forward-fast skip-to-delivery-icon"></i>
                                    </a>
                                    <span class="tooltiptextY">Send To Delivery</span>
                                </div>
                                @endif
                                <button type="button" class="close modal-close" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
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
                            <div class="row btnsRow waiting-actions">
                                <!-- Row 1: View (25%) | Action (50%) | Edit (25%) -->
                                <div class="col-3 padding5px">
                                    <a href="{{route('view-case', ['id' => $case->id, 'stage' => -2])}}" style="width:100%;">
                                        <button type="button" class="btn btn-info">View</button>
                                    </a>
                                </div>
                                <div class="col-6 padding5px">
                                    @if ($key == "milling")
                                        <button type="button" data-toggle="modal" class="btn btn-success" data-dismiss="modal" onclick="openModal('MillingDialog')"><i class="fa-solid fa-hexagon-nodes"></i> Nest</button>
                                    @else
                                        <button type="submit" class="btn btn-success"><i class="fas fa-user-plus"></i> {{$key == "delivery" ? 'Take' : 'Assign To Me'}}</button>
                                    @endif
                                </div>
                                <div class="col-3 padding5px">
                                    <a href="{{route('edit-case-view',$case->id)}}" style="width:100%;">
                                        <button type="button" class="btn btn-warning" {{$canEditCase ? '' : 'disabled'}}>Edit</button>
                                    </a>
                                </div>

                                <!-- Row 2: QC Complete (100%) OR Delivery Assign (100%) -->
                                @if ($key == "qc")
                                    <div class="col-12 padding5px">
                                        <a href="{{route('assign-and-finish',['caseId'=> $case->id,'stage'=>$stage["numericStage"]])}}">
                                            <button type="button" class="btn btn-info"><i class="fa-solid fa-arrow-trend-up"></i> Assign & Complete</button>
                                        </a>
                                    </div>
                                @endif

                                @if ($key == "delivery")
                                    @if($canAssignDelivery)
                                        @if($case->jobs[0]->assignee == null)
                                            <div class="col-12 padding5px">
                                                <button type="button" class="btn btn-warning" onclick="closeModal({id: 'waitingDialog{{$key.$case->id}}'}); openModal('DeliveryDialog', false)" style="width:100%">Assign to</button>
                                            </div>
                                        @else
                                            <div class="col-12 padding5px">
                                                <button type="button" class="btn btn-warning" onclick="closeModal({id: 'waitingDialog{{$key.$case->id}}'}); openModal('DeliveryDialog', false)" style="width:100%">Re-Assign</button>
                                            </div>
                                        @endif
                                    @endif
                                @endif

                                <!-- Row 3: Delivery Print Voucher (100%) -->
                                @if ($key == "delivery")
                                    <div class="col-12 padding5px">
                                        <a href="{{route('view-voucher',$case->id)}}">
                                            <button type="button" class="btn btn-info" style="width:100%"><i class="fas fa-print"></i> Print Voucher</button>
                                        </a>
                                    </div>
                                @endif

                                <!-- Row 4: Cancel (100%) -->
                                <div class="col-12 padding5px">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        @endforeach
        <!-- Begin Active tab -->


    </tbody>
</table>
