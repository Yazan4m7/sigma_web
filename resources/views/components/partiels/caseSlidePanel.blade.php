@props(['case', 'stageType' => '3dprinting'])

@php
    $stageNumber = match($stageType) {
        'milling' => 2,
        '3dprinting' => 3,
        'sintering' => 4,
        'pressing' => 5,
        'delivery' => 8,
        default => 3
    };
    $stageLabel = match($stageType) {
        'milling' => 'Milling',
        '3dprinting' => '3D Printing',
        'sintering' => 'Sintering',
        'pressing' => 'Pressing',
        'delivery' => 'Delivery',
        default => 'Workflow'
    };
    $jobsAtStage = $case->jobs->where('stage', $stageNumber);
    $permissions = safe_permissions();
    $canEditCase = (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 102)));
@endphp

<div class="modal fade waiting-dialog case-action-dialog sigma-modal--dashboard-waiting-actions ysh-case-slide-modal"
     tabindex="-1" role="dialog" id="YSH-slide-overlay-{{$case->id}}"
     onclick="if (event.target === this) YSH_closeSlidePanel({{$case->id}})">
    <div class="modal-dialog modal-dialog-centered" role="document" id="YSH-slide-panel-{{$case->id}}" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-top-actions">
                    <span class="ysh-modal-title">CASE COMPLETION</span>
                    <button type="button" class="close modal-close" aria-label="Close"
                            onclick="event.stopPropagation(); YSH_closeSlidePanel({{$case->id}})">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Doctor/Patient section - two columns -->
                <div class="form-group row" style="margin-bottom: 0px">
                    <div class="form-group col-6" style="margin-bottom: 0px">
                        <label for="doctor">Doctor:</label>
                        <h5 id="doctor" class="patient-doctor-names">{{$case->client?->name}}</h5>
                    </div>
                    <div class="form-group col-6" style="margin-bottom: 0px">
                        <label for="pat">Patient name:</label>
                        <h5 id="pat" class="patient-doctor-names">{{$case->patient_name}}</h5>
                    </div>
                </div>
                <hr>

                <!-- Scrollable Jobs and Notes section -->
                <div class="scrollable-content">
                    <div class="form-group row">
                        <div class="col-12">
                            <label><b>Jobs:</b></label><br>
                            @forelse($jobsAtStage as $job)
                                @php
                                    $showJob = $job->goesThroughStage($stageNumber);
                                @endphp
                                @if($showJob)
                                <span>{{$job->unit_num}}
                                    - {{$job->jobType->name ?? "No Job Type"}}
                                    - {{$job->material->name ?? "no material"}} {{$job->color == '0' ? "" : " - " . $job->color}}
                                    {{$job->style == 'None' ? "" : " - " . $job->style}} {{isset($job->implantR) && $job->jobType->id == 6 ? (" - Implant Type: " . $job->implantR->name) : ""}}
                                    <br>
                                    {{isset($job->abutmentR) && $job->jobType->id == 6 ? (" Abutment Type: " . $job->abutmentR->name) : ""}}</span>
                                @endif
                            @empty
                                <span class="text-muted">No jobs for this stage yet.</span>
                            @endforelse
                        </div>
                    </div>

                    @if(count($case->notes) > 0)
                    <hr>
                    <label><b>Notes:</b></label><br>
                    @foreach($case->notes as $note)
                    <div class="form-control note-container"
                         style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px"
                         disabled>
                        <span class="noteHeader" style="font-weight:600">{{'['. substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] :'}}</span><br>
                        <span class="noteText">{{$note->note}}</span>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <div class="modal-footer fullBtnsWidth">
                <div class="row btnsRow waiting-actions">
                    <!-- Row 1: View | Edit -->
                    <div class="col-6 padding5px">
                        <a href="{{route('view-case', ['id' => $case->id, 'stage' => $stageNumber])}}" style="width:100%;">
                            <button type="button" class="btn btn-info">View</button>
                        </a>
                    </div>
                    <div class="col-6 padding5px">
                        <a href="{{route('edit-case-view', $case->id)}}" style="width:100%;">
                            <button type="button" class="btn btn-warning" {{$canEditCase ? '' : 'disabled'}}>Edit</button>
                        </a>
                    </div>

                    <!-- Row 2: Cancel (100%) -->
                    <div class="col-12 padding5px">
                        <button type="button" class="btn btn-secondary"
                                onclick="event.stopPropagation(); YSH_closeSlidePanel({{$case->id}})" style="width:100%">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@once
    @push('js')
        <script>
            // Fallback/override for YSH slide panel functions
            (function() {
                window.YSH_closeSlidePanel = function(caseId) {
                    var overlay = document.getElementById('YSH-slide-overlay-' + caseId);
                    if (!overlay) {
                        console.warn('Cannot close: overlay not found for case', caseId);
                        return;
                    }

                    overlay.classList.remove('YSH-active');
                    overlay.classList.add('YSH-closing');

                    setTimeout(function() {
                        overlay.classList.remove('YSH-active', 'YSH-closing');
                        if (typeof window.updateDialogScrollLock === 'function') {
                            window.updateDialogScrollLock();
                        }
                    }, 300);
                };

                window.YSH_openSlidePanel = function(caseId, stageType) {
                    var overlay = document.getElementById('YSH-slide-overlay-' + caseId);
                    if (!overlay) {
                        console.warn('Slide panel missing for case', caseId);
                        return;
                    }

                    // Move to body if not already
                    if (!overlay.dataset.movedToBody) {
                        document.body.appendChild(overlay);
                        overlay.dataset.movedToBody = '1';
                    }

                    overlay.classList.remove('YSH-closing');
                    overlay.style.display = 'block';

                    requestAnimationFrame(function() {
                        overlay.classList.add('YSH-active');
                        if (typeof window.updateDialogScrollLock === 'function') {
                            window.updateDialogScrollLock();
                        }
                    });
                };
            })();
        </script>
    @endpush
@endonce

@once
    <style>
        /* Cairo font for all dialogs */
        .ysh-case-slide-modal,
        .sigma-workflow-modal.sigma-modal--active-cases-preview,
        .sigma-modal--cases-dashboard-case-completion-alt {
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        .ysh-case-slide-modal *,
        .sigma-workflow-modal.sigma-modal--active-cases-preview *,
        .sigma-modal--cases-dashboard-case-completion-alt * {
            font-family: inherit;
        }

        /* YSH Case Slide Modal - Custom overlay/panel visibility via YSH-active class */
        .ysh-case-slide-modal {
            display: block !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0);
            z-index: 1050;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        .ysh-case-slide-modal.YSH-active {
            opacity: 1;
            background: rgba(0, 0, 0, 0.5);
            pointer-events: auto;
        }
        .ysh-case-slide-modal.YSH-closing {
            opacity: 0;
            background: rgba(0, 0, 0, 0);
            pointer-events: none;
        }

        /* Modal dialog - slide in from right to left */
        .ysh-case-slide-modal .modal-dialog {
            position: fixed !important;
            right: -600px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            margin: 0 !important;
            max-width: 520px !important;
            width: 90vw !important;
            height: auto !important;
            max-height: 85vh !important;
            transition: right 0.3s ease !important;
            z-index: 1051 !important;
            pointer-events: auto !important;
        }
        .ysh-case-slide-modal.YSH-active .modal-dialog {
            right: 0 !important;
        }
        .ysh-case-slide-modal.YSH-closing .modal-dialog {
            right: -600px !important;
        }

        /* Modal content styling - slide panel from right, rounded left corners only */
        .ysh-case-slide-modal .modal-content {
            border-radius: 20px 0 0 20px;
            box-shadow: -8px 0 32px rgba(0, 0, 0, 0.15);
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .ysh-case-slide-modal .modal-top-actions {
            display: flex;
            align-items: center;
            margin-bottom: 0.35rem;
            gap: 0.5rem;
        }
        .ysh-case-slide-modal .ysh-modal-title {
            flex: 1;
            font-size: 18px;
            font-weight: 700;
            color: #2d5f6d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .ysh-case-slide-modal .modal-top-actions .modal-close {
            border: none;
            background: transparent;
            font-size: 1.75rem;
            line-height: 1;
            color: #000;
            opacity: 0.65;
            padding: 0;
            margin-left: auto;
        }
        .ysh-case-slide-modal .modal-top-actions .modal-close:hover {
            opacity: 1;
        }
        .ysh-case-slide-modal .patient-doctor-names {
            color: #2d5f6d;
            font-weight: 600;
        }
        .ysh-case-slide-modal .note-container {
            background: #e8f0f2;
            border: 1px solid #b8d4db;
        }
        .ysh-case-slide-modal .scrollable-content {
            flex: 1;
            overflow-y: auto;
            min-height: 0;
        }
        .ysh-case-slide-modal .waiting-actions {
            width: 100%;
            margin: 0;
        }
        .ysh-case-slide-modal .waiting-actions > [class*='col-'] {
            display: flex;
        }
        .ysh-case-slide-modal .waiting-actions .btn {
            flex: 1;
            width: 100%;
        }
        .ysh-case-slide-modal .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
        }
        .ysh-case-slide-modal .modal-body {
            padding: 1rem 1.25rem;
            flex: 1;
            overflow-y: auto;
        }

        /* Responsive adjustments - maintain slide behavior */
        @media (max-width: 400px) {
            .ysh-case-slide-modal .modal-body { padding: 0.85rem; }
            .ysh-case-slide-modal .ysh-modal-title { font-size: 16px; }
            .ysh-case-slide-modal .waiting-actions .col-3,
            .ysh-case-slide-modal .waiting-actions .col-6,
            .ysh-case-slide-modal .waiting-actions .col-12 { flex: 0 0 100%; max-width: 100%; }
            .ysh-case-slide-modal .waiting-actions .btn { margin-bottom: 8px; }
        }
        @media (min-width: 401px) and (max-width: 576px) {
            .ysh-case-slide-modal .waiting-actions .col-3 { flex: 0 0 50%; max-width: 50%; }
            .ysh-case-slide-modal .waiting-actions .col-6 { flex: 0 0 50%; max-width: 50%; }
            .ysh-case-slide-modal .waiting-actions .col-12 { flex: 0 0 100%; max-width: 100%; }
        }
        @media (min-width: 577px) and (max-width: 768px) {
            .ysh-case-slide-modal .modal-body { padding: 1rem 1.25rem; }
            .ysh-case-slide-modal .waiting-actions .col-3 { flex: 0 0 33.333%; max-width: 33.333%; }
            .ysh-case-slide-modal .waiting-actions .col-6 { flex: 0 0 33.333%; max-width: 33.333%; }
        }
        @media (min-width: 769px) and (max-width: 992px) {
            .ysh-case-slide-modal .waiting-actions .col-3 { flex: 0 0 25%; max-width: 25%; }
            .ysh-case-slide-modal .waiting-actions .col-6 { flex: 0 0 50%; max-width: 50%; }
        }
        @media (min-width: 1200px) {
            .ysh-case-slide-modal .modal-body { padding: 1.25rem 1.5rem; }
        }
    </style>
@endonce
