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
                        <label for="doctor" class="patient-doctor-label case-completion-dialog-label">Doctor:</label>
                        <h5 id="doctor" class="patient-doctor-names">{{$case->client?->name}}</h5>
                    </div>
                    <div class="form-group col-6" style="margin-bottom: 0px">
                        <label for="pat" class="patient-doctor-label case-completion-dialog-label">Patient name:</label>
                        <h5 id="pat" class="patient-doctor-names">{{$case->patient_name}}</h5>
                    </div>
                </div>
                <hr>

                <!-- Scrollable Jobs and Notes section -->
                <div class="scrollable-content">
                    <div class="form-group row">
                        <div class="col-12">
                            <label class="case-completion-dialog-label case-jobs-label"><b>Jobs:</b></label>
                            <div class="sigma-case-jobs-list">
                            @forelse($jobsAtStage as $job)
                                @php
                                    $showJob = $job->goesThroughStage($stageNumber);
                                    $jobTypeName = $job->jobType->name ?? "No Job Type";
                                    $materialName = $job->material->name ?? "no material";
                                    $colorLabel = $job->color == '0' ? "" : $job->color;
                                    $styleLabel = $job->style == 'None' ? "" : $job->style;
                                    $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? "Implant Type: " . $job->implantR->name : "";
                                    $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? "Abutment Type: " . $job->abutmentR->name : "";
                                @endphp
                                @if($showJob)
                                @php
                                    $jobTitleParts = array_values(array_filter([
                                        trim((string) $job->unit_num),
                                        trim((string) $jobTypeName),
                                        trim((string) $materialName),
                                        trim((string) $colorLabel),
                                        trim((string) $implantLabel),
                                        trim((string) $abutmentLabel),
                                    ], function ($value) {
                                        return $value !== '';
                                    }));
                                    $jobUnits = trim((string) $job->unit_num);
                                    $normalizedStyle = strtolower(trim((string) $styleLabel));
                                    $jobTag = in_array($normalizedStyle, ['single', 'bridge'], true)
                                        ? ucfirst($normalizedStyle)
                                        : (strpos($jobUnits, ',') !== false ? 'Bridge' : 'Single');
                                    $isModelJob = stripos($jobTypeName, 'model') !== false || stripos($materialName, 'model') !== false;
                                @endphp
                                <div class="sigma-case-job-row">
                                    @if($isModelJob)
                                        <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 7.5C5.1 14 7.8 18 12 18s6.9-4 7.5-10.5"></path><path d="M7.5 7.5c.5 4.1 2 6.4 4.5 6.4s4-2.3 4.5-6.4"></path><path d="M8.4 8v2.2"></path><path d="M12 8v3.2"></path><path d="M15.6 8v2.2"></path></svg>
                                    @else
                                        <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C9 2 7 4 7 7c0 2 .5 3.5 1 5l1 5c.3 1.2 1 2 2 2h2c1 0 1.7-.8 2-2l1-5c.5-1.5 1-3 1-5 0-3-2-5-5-5z"></path><path d="M9 10c0 0 1 1 3 1s3-1 3-1"></path></svg>
                                    @endif
                                    <span class="sigma-case-job-info">
                                        <span class="sigma-case-job-primary">{{ implode(' - ', $jobTitleParts) }}</span>
                                    </span>
                                    <span class="sigma-case-job-tag">{{ $jobTag }}</span>
                                </div>
                                @endif
                            @empty
                                <span class="text-muted">No jobs for this stage yet.</span>
                            @endforelse
                            </div>
                        </div>
                    </div>

                    @if(count($case->notes) > 0)
                    <hr>
                    <label class="case-completion-dialog-label case-notes-label"><b>Notes:</b></label><br>
                    <div class="sigma-case-notes-list">
                    @foreach($case->notes as $note)
                    <div class="note-container">
                        <div class="sigma-case-note-left">
                            <i class="far fa-comment-alt sigma-case-note-icon" aria-hidden="true"></i>
                            @unless($loop->last)
                                <div class="sigma-case-note-line"></div>
                            @endunless
                        </div>
                        <div class="sigma-case-note-content">
                            <div class="sigma-case-note-meta">
                                <span class="noteHeader sigma-case-note-author">{{ $note->writtenBy->name_initials }}</span>
                                <span class="sigma-case-note-separator">&middot;</span>
                                <span class="sigma-case-note-time">{{ substr($note->created_at, 0, 16) }}</span>
                            </div>
                            <span class="noteText sigma-case-note-text">{{$note->note}}</span>
                        </div>
                    </div>
                    @endforeach
                    </div>
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
        .ysh-case-slide-modal :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab),
        .sigma-workflow-modal.sigma-modal--active-cases-preview :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab),
        .sigma-modal--cases-dashboard-case-completion-alt :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab) {
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
        .ysh-case-slide-modal .sigma-case-notes-list {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.375rem;
        }
        .ysh-case-slide-modal .note-container {
            align-items: flex-start;
            background: #fff !important;
            border: 0.5px solid #eaeae4 !important;
            border-top: none !important;
            box-shadow: none !important;
            color: inherit !important;
            display: flex;
            gap: 10px;
            padding: 9px 14px !important;
        }
        .ysh-case-slide-modal .sigma-case-notes-list > .note-container:first-child {
            border-radius: 9px 9px 0 0 !important;
            border-top: 0.5px solid #eaeae4 !important;
        }
        .ysh-case-slide-modal .sigma-case-notes-list > .note-container:last-child {
            border-radius: 0 0 9px 9px !important;
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
            align-items: center;
            display: inline-flex;
            height: 40px !important;
            justify-content: center;
            min-height: 40px !important;
            padding-top: 7px !important;
            padding-bottom: 7px !important;
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
        .ysh-case-slide-modal .modal-body label.case-jobs-label,
        .ysh-case-slide-modal .modal-body label.case-notes-label {
            display: block;
            margin-bottom: 8px !important;
            font-size: 10px !important;
            font-weight: 500 !important;
            letter-spacing: 0.09em !important;
            line-height: 1.2 !important;
            text-transform: uppercase !important;
        }
        .ysh-case-slide-modal .modal-body label.case-jobs-label {
            color: #444441 !important;
        }
        .ysh-case-slide-modal .modal-body label.case-notes-label {
            color: #B4B2A9 !important;
        }
        .ysh-case-slide-modal .modal-body label.case-jobs-label b,
        .ysh-case-slide-modal .modal-body label.case-notes-label b {
            color: inherit !important;
            font-weight: inherit !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-jobs-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 5px !important;
            width: 100%;
            margin: 0 0 1.375rem !important;
            padding: 0 !important;
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
        }
        .ysh-case-slide-modal .sigma-case-job-row {
            background: #fff !important;
            border: 1px solid #e0e0da !important;
            border-radius: 10px !important;
            box-shadow: none !important;
            color: inherit !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            margin: 0 !important;
            min-width: 0;
            overflow: hidden !important;
            padding: 11px 14px !important;
            width: 100%;
        }
        .ysh-case-slide-modal .sigma-case-job-icon {
            flex-shrink: 0;
        }
        .ysh-case-slide-modal .sigma-case-job-info {
            display: block;
            flex: 1 1 0%;
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
        }
        .ysh-case-slide-modal .sigma-case-job-primary {
            color: #1a1a18 !important;
            display: block;
            font-size: 13px !important;
            font-weight: 500 !important;
            line-height: 1.25 !important;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .ysh-case-slide-modal .sigma-case-job-sub {
            color: #888780;
            display: block;
            font-size: 11px;
            line-height: 1.25;
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .ysh-case-slide-modal .sigma-case-job-tag {
            border: 0.5px solid #9FE1CB;
            border-radius: 20px;
            color: #0F6E56;
            flex-shrink: 0;
            font-size: 10px;
            font-weight: 400;
            line-height: 1.2;
            max-width: 34%;
            overflow: hidden;
            padding: 3px 9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .ysh-case-slide-modal .sigma-case-note-meta {
            align-items: center;
            display: flex;
            gap: 5px;
            margin-bottom: 2px;
            min-width: 0;
        }
        .ysh-case-slide-modal .sigma-case-note-left {
            align-items: center;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            gap: 3px;
            padding-top: 2px;
        }
        .ysh-case-slide-modal .sigma-case-note-icon {
            color: #85B7EB;
            font-size: 12px;
        }
        .ysh-case-slide-modal .sigma-case-note-line {
            background: #daeaf7;
            flex: 1;
            min-height: 14px;
            width: 1px;
        }
        .ysh-case-slide-modal .sigma-case-note-content {
            min-width: 0;
        }
        .ysh-case-slide-modal .sigma-case-note-author {
            color: #378ADD !important;
            font-size: 11px !important;
            font-weight: 500 !important;
        }
        .ysh-case-slide-modal .sigma-case-note-separator {
            color: #daeaf7;
            font-size: 11px;
        }
        .ysh-case-slide-modal .sigma-case-note-time {
            color: #85B7EB;
            font-size: 10px;
        }
        .ysh-case-slide-modal .sigma-case-note-text {
            color: #888780;
            display: block;
            font-size: 12px;
            line-height: 1.45;
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
