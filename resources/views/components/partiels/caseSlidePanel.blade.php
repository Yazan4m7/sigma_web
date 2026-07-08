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
                                    $jobLeadLine = trim((string) $job->unit_num);
                                    $jobTypeLine = trim((string) $jobTypeName);
                                    $jobMaterialLine = trim((string) $materialName);
                                    $jobColorLine = trim((string) $colorLabel);
                                    $jobStyleLine = trim(implode(' / ', array_filter([
                                        trim((string) $styleLabel),
                                        trim((string) $implantLabel),
                                        trim((string) $abutmentLabel),
                                    ], function ($value) {
                                        return $value !== '';
                                    })));
                                    $jobStyleCode = stripos($styleLabel, 'bridge') !== false
                                        ? 'B'
                                        : (stripos($styleLabel, 'single') !== false || strpos($jobLeadLine, ',') === false ? 'S' : 'B');
                                    $jobTooltipParts = array_values(array_filter([
                                        'Units: ' . ($jobLeadLine !== '' ? $jobLeadLine : '-'),
                                        $jobTypeLine !== '' ? 'Type: ' . $jobTypeLine : null,
                                        $jobMaterialLine !== '' ? 'Material: ' . $jobMaterialLine : null,
                                        $jobColorLine !== '' ? 'Color: ' . $jobColorLine : null,
                                        $jobStyleLine !== '' ? 'Style: ' . $jobStyleLine : null,
                                    ]));
                                @endphp
                                <div class="sigma-case-job-row sigma-case-job-card" tabindex="0" aria-label="{{ implode(', ', $jobTooltipParts) }}" data-job-tooltip="{{ implode(' | ', $jobTooltipParts) }}">
                                    <span class="sigma-case-job-cell sigma-case-job-cell--lead">{{ $jobLeadLine !== '' ? $jobLeadLine : '-' }}</span>
                                    <span class="sigma-case-job-cell sigma-case-job-cell--type">{{ $jobTypeLine !== '' ? $jobTypeLine : '-' }}</span>
                                    <span class="sigma-case-job-cell sigma-case-job-cell--material">{{ $jobMaterialLine !== '' ? $jobMaterialLine : '-' }}</span>
                                    <span class="sigma-case-job-cell sigma-case-job-cell--color">{{ $jobColorLine }}</span>
                                    <span class="sigma-case-job-cell sigma-case-job-cell--style">{{ $jobStyleCode }}</span>
                                    <span class="sigma-case-job-tooltip" aria-hidden="true">{{ implode(' | ', $jobTooltipParts) }}</span>
                                </div>
                                @endif
                            @empty
                                <div class="sigma-case-job-row sigma-case-job-card">
                                    <span class="sigma-case-job-cell sigma-case-job-cell--lead">-</span>
                                </div>
                            @endforelse
                            </div>
                        </div>
                    </div>

                    @include('cases.dashboards-partials.case-dialog-notes', ['case' => $case])
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
            background: #fff !important;
            border: 0.5px solid #c7c7c7 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            color: #333333 !important;
        }
        .ysh-case-slide-modal .form-control.note-container {
            display: block !important;
            height: fit-content !important;
            width: 100% !important;
            margin-bottom: 8px !important;
            padding: 10px !important;
            font-size: 12px !important;
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
            color: #4d626d !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 0.08em !important;
        }
        .ysh-case-slide-modal .modal-body label.case-notes-label {
            color: #5c6f7a !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: 0.06em !important;
        }
        .ysh-case-slide-modal .modal-body label.case-jobs-label b,
        .ysh-case-slide-modal .modal-body label.case-notes-label b {
            color: inherit !important;
            font-weight: inherit !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-jobs-list {
            display: grid !important;
            gap: 6px !important;
            width: 100% !important;
            margin: 6px 0 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card {
            display: grid !important;
            grid-template-columns: minmax(68px, 0.95fr) minmax(88px, 1.2fr) minmax(80px, 0.1fr) minmax(22px, 0.35fr) minmax(20px, 0.1fr) !important;
            align-items: start !important;
            column-gap: 2px !important;
            width: 100% !important;
            min-width: 0 !important;
            min-height: 30px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #eef6fa !important;
            background-color: #eef6fa !important;
            border: 0 !important;
            border-left: 2px solid #17a2b8 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            color: #294450 !important;
            font-size: 15px !important;
            font-weight: 500 !important;
            line-height: 1.45 !important;
            overflow: hidden !important;
            white-space: normal !important;
            overflow-wrap: normal !important;
            word-break: normal !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell {
            display: block !important;
            box-sizing: border-box !important;
            max-width: 100% !important;
            min-width: 0 !important;
            min-height: 30px !important;
            border: 0 !important;
            color: inherit !important;
            font-size: 15px !important;
            font-weight: 500 !important;
            line-height: 30px !important;
            text-align: left !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell {
            border-left: 1px solid #cfe4eb47 !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead {
            font-weight: 700 !important;
            padding-left: 10px !important;
        }
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
        .ysh-case-slide-modal .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style {
            text-align: center !important;
        }
        .ysh-case-slide-modal .sigma-case-job-tooltip {
            display: none !important;
        }
        .ysh-case-slide-modal .sigma-case-note-text {
            display: block !important;
            direction: ltr !important;
            text-align: left !important;
            unicode-bidi: isolate;
        }
        .ysh-case-slide-modal .sigma-case-note-text--mixed {
            direction: inherit !important;
            text-align: inherit !important;
            unicode-bidi: normal;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-box {
            display: block;
            width: 100%;
            margin: 0 0 8px;
            padding: 8px 10px;
            border: 1px solid #d4e0e0;
            border-radius: 8px;
            background: #f6f9f9;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            cursor: pointer;
            list-style: none;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-summary::-webkit-details-marker {
            display: none;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 7px;
            padding-top: 7px;
            border-top: 1px solid #dbe7e7;
        }
        .ysh-case-slide-modal .sigma-case-delivery-note {
            color: #516060;
            font-size: 12px;
            font-style: italic;
            font-weight: 600;
            line-height: 1.45;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-chevron {
            flex: 0 0 auto;
            margin-left: auto;
            color: #3f777b;
            font-size: 12px;
            transition: transform 180ms ease;
        }
        .ysh-case-slide-modal .sigma-case-delivery-notes-box[open] .sigma-case-delivery-notes-chevron {
            transform: rotate(180deg);
        }
        .ysh-case-slide-modal .sigma-case-delivery-note-arrow {
            display: inline-block;
            margin: 0 5px;
            color: #3f777b;
            font-style: normal;
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
