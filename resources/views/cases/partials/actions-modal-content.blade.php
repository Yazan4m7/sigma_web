@php
    $permissions = $permissions ?? safe_permissions();
@endphp

<style>
    .sigma-modal--cases-index-actions,
    .sigma-modal--cases-index-action,
    .sigma-modal--cases-index-actions :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab),
    .sigma-modal--cases-index-action :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab) {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .sigma-modal--cases-index-actions .case-actions-footer,
    .sigma-modal--cases-index-action .case-actions-footer {
        position: relative;
        border-top: none !important;
    }

    .sigma-modal--cases-index-actions .case-actions-footer::before,
    .sigma-modal--cases-index-action .case-actions-footer::before {
        content: "";
        position: absolute;
        top: 0;
        left: 16px;
        right: 16px;
        border-top: 0px solid #dbeafe;
    }

    .case-summary-divider.lower-divider {
        width: 97%;
        align-self: center;
    }

    .sigma-modal--cases-index-actions .case-jobs-label,
    .sigma-modal--cases-index-action .case-jobs-label {
        display: block;
        margin-bottom: 8px !important;
        color: #4d626d;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 0.08em;
    }

    .sigma-modal--cases-index-actions .case-notes-label,
    .sigma-modal--cases-index-action .case-notes-label {
        display: block;
        margin-bottom: 8px !important;
        color: #5c6f7a;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
    }

    .sigma-modal--cases-index-actions .scrollable-content,
    .sigma-modal--cases-index-action .scrollable-content {
        scrollbar-width: thin;
        scrollbar-color: #1e9ba7 #eaf5f7;
    }

    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar,
    .sigma-modal--cases-index-action .scrollable-content::-webkit-scrollbar {
        width: 8px;
    }

    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-track,
    .sigma-modal--cases-index-action .scrollable-content::-webkit-scrollbar-track {
        background: #eaf5f7;
        border-radius: 999px;
        margin: 4px 0;
    }

    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-thumb,
    .sigma-modal--cases-index-action .scrollable-content::-webkit-scrollbar-thumb {
        min-height: 42px;
        border: 2px solid #eaf5f7;
        border-radius: 999px;
        background: linear-gradient(180deg, #2aa8b2, #1e7f8a);
    }

    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-thumb:hover,
    .sigma-modal--cases-index-action .scrollable-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #35b8c2, #176d77);
    }

    .sigma-modal--cases-index-actions .sigma-case-jobs-list,
    .sigma-modal--cases-index-action .sigma-case-jobs-list,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-jobs-list,
    .sigma-modal--cases-index-action .modal-body .sigma-case-jobs-list {
        display: grid !important;
        gap: 6px !important;
        width: 100% !important;
        margin: 6px 0 0 !important;
        padding: 0 !important;
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
    }

    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card {
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

    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell {
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

    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell {
        border-left: 1px solid #cfe4eb47 !important;
    }

    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead {
        font-weight: 700 !important;
        overflow: hidden !important;
        padding-left: 10px !important;
        text-align: left !important;
    }

    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
    .sigma-modal--cases-index-actions .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
    .sigma-modal--cases-index-action .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
    .sigma-modal--cases-index-action .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style {
        text-align: center !important;
    }

    .sigma-modal--cases-index-actions .sigma-case-job-tooltip,
    .sigma-modal--cases-index-action .sigma-case-job-tooltip {
        display: none !important;
    }
    .sigma-modal--cases-index-actions .form-control.note-container,
    .sigma-modal--cases-index-action .form-control.note-container {
        background: #fff !important;
        border: 0.5px solid #c7c7c7 !important;
        border-radius: 8px !important;
        box-shadow: none !important;
        color: #333333 !important;
    }

    .sigma-modal--cases-index-actions .sigma-case-note-text,
    .sigma-modal--cases-index-action .sigma-case-note-text {
        display: block !important;
        direction: ltr !important;
        text-align: left !important;
        unicode-bidi: isolate;
    }

    .sigma-modal--cases-index-actions .sigma-case-note-text--mixed,
    .sigma-modal--cases-index-action .sigma-case-note-text--mixed {
        direction: inherit !important;
        text-align: inherit !important;
        unicode-bidi: normal;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-box,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-box {
        display: block;
        width: 100%;
        margin: 0 0 8px;
        padding: 8px 10px;
        border: 1px solid #d4e0e0;
        border-radius: 8px;
        background: #f6f9f9;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-summary,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        cursor: pointer;
        list-style: none;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-summary::-webkit-details-marker,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-summary::-webkit-details-marker {
        display: none;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-list,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-list {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-top: 7px;
        padding-top: 7px;
        border-top: 1px solid #dbe7e7;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-note,
    .sigma-modal--cases-index-action .sigma-case-delivery-note {
        color: #516060;
        font-size: 12px;
        font-style: italic;
        font-weight: 600;
        line-height: 1.45;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-chevron,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-chevron {
        flex: 0 0 auto;
        margin-left: auto;
        color: #3f777b;
        font-size: 12px;
        transition: transform 180ms ease;
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-notes-box[open] .sigma-case-delivery-notes-chevron,
    .sigma-modal--cases-index-action .sigma-case-delivery-notes-box[open] .sigma-case-delivery-notes-chevron {
        transform: rotate(180deg);
    }

    .sigma-modal--cases-index-actions .sigma-case-delivery-note-arrow,
    .sigma-modal--cases-index-action .sigma-case-delivery-note-arrow {
        display: inline-block;
        margin: 0 5px;
        color: #3f777b;
        font-style: normal;
    }
</style>

<div class="modal-header case-preview-header">
    <x-sigma-close-button />
</div>
<div class="modal-body">
    <div class="case-summary-block">
        <div class="form-group row case-summary-row" style="margin-bottom: 0px">
            <div class="form-group col-6" style="margin-bottom: 0;padding-left: 0; padding-right: 0">
                <label for="doctor" class="patient-doctor-label">Doctor:</label>
                <h5 id="doctor" class="patient-doctor-names">{{ $case->client->name ?? '-' }}</h5>
            </div>
            <div class="form-group col-6" style="margin-bottom: 0; ">
                <label for="pat" class="patient-doctor-label">patient:</label>
                <h5 id="pat" class="patient-doctor-names">{{ $case->patient_name }}</h5>
            </div>
        </div>

    </div>
    <hr class="case-summary-divider">
    <div class="scrollable-content">
        @php
            $currentStage = optional($case->jobs->first())->stage;
        @endphp
        <div class="form-group row">
            <div class="  " style="width: 100%">
                <label class="case-completion-dialog-label case-jobs-label"><b>Jobs:</b></label>
                <div class="sigma-case-jobs-list">
                    @foreach ($case->jobs as $job)
                        @php
                            $showJob = $job->goesThroughStage($currentStage);
                        @endphp

                        @if ($showJob)
                            @php
                                $jobTypeName = $job->jobType->name ?? 'No Job Type';
                                $materialName = $job->material->name ?? 'no material';
                                $colorLabel = $job->color == '0' ? '' : $job->color;
                                $styleLabel = $job->style == 'None' ? '' : $job->style;
                                $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? 'Implant Type: ' . $job->implantR->name : '';
                                $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? 'Abutment Type: ' . $job->abutmentR->name : '';
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
                                <span class="sigma-case-job-cell sigma-case-job-cell--lead">{{ $jobLeadLine }}</span>
                                <span class="sigma-case-job-cell sigma-case-job-cell--type">{{ $jobTypeLine }}</span>
                                <span class="sigma-case-job-cell sigma-case-job-cell--material">{{ $jobMaterialLine }}</span>
                                <span class="sigma-case-job-cell sigma-case-job-cell--color">{{ $jobColorLine }}</span>
                                <span class="sigma-case-job-cell sigma-case-job-cell--style">{{ $jobStyleCode }}</span>
                                <span class="sigma-case-job-tooltip" aria-hidden="true">
                                    @foreach ($jobTooltipParts as $jobTooltipPart)
                                        <span>{{ $jobTooltipPart }}</span>
                                    @endforeach
                                </span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        @include('cases.dashboards-partials.case-dialog-notes', ['case' => $case])
    </div>
</div>

<div class="modal-footer case-actions-footer">

    @if(!isset($trashedCases))
        <div class="sigma-modal-actions">
            <hr class="case-summary-divider lower-divider">
            <div class="sigma-actions-row sigma-actions-row--top">

                <a href="{{ route('view-voucher', $case->id) }}"
                   class="btn btn-info sigma-action-btn"><span class="btn-icon"><i
                            class="fas fa-print"></i></span><span class="btn-text">Print Voucher</span></a>
                <a href="{{ route('view-case', ['id' => $case->id, 'stage' => -2]) }}"
                   class="btn btn-info sigma-action-btn"><span class="btn-icon"><i
                            class="far fa-file-alt"></i></span><span
                        class="btn-text">View</span></a>
            </div>

            <div class="sigma-actions-grid">
                @if(Auth()->user()->is_admin || $permissions->contains('permission_id', 130))
                    @if(!$case->locked)
                        <a href="{{ route('lock-case', $case->id) }}"
                           class="btn btn-dark sigma-action-btn"><span class="btn-icon"><i
                                    class="fas fa-lock"></i></span><span
                                class="btn-text">Lock</span></a>
                    @else
                        <a href="{{ route('unlock-case', $case->id) }}"
                           class="btn btn-dark sigma-action-btn"><span class="btn-icon"><i
                                    class="fas fa-lock-open"></i></span><span
                                class="btn-text">Unlock</span></a>
                    @endif
                @endif

                @if(Auth()->user()->is_admin && !$case->locked)
                    <a data-clientName="{{ $case->client->name ?? '-' }}"
                       data-patientName="{{ $case->patient_name }}"
                       onclick="caseDelConfirmation(event)"
                       href="{{ route('delete-case', $case->id) }}"
                       class="btn btn-danger sigma-action-btn"><span class="btn-icon"><i
                                class="fas fa-trash"></i></span><span
                            class="btn-text">Delete</span></a>
                @endif

                @if(isset($case->actual_delivery_date))
                    @if((Auth()->user()->is_admin || $permissions->contains('permission_id', 116)) && !$case->locked)
                        <a href="{{ route('reject-case-view', $case->id) }}"
                           class="btn btn-outline-danger sigma-action-btn"><span
                                class="btn-icon"><i class="fas fa-times"></i></span><span
                                class="btn-text">Reject case</span></a>
                    @endif
                    @if((Auth()->user()->is_admin || $permissions->contains('permission_id', 117)) && !$case->locked)
                        <a href="{{ route('repeat-case-view', $case->id) }}"
                           class="btn btn-outline-warning sigma-action-btn"><span
                                class="btn-icon"><i class="fas fa-undo"></i></span><span
                                class="btn-text">Repeat case</span></a>
                    @endif
                    @if((Auth()->user()->is_admin || $permissions->contains('permission_id', 118)) && !$case->locked)
                        <a href="{{ route('modify-case-view', $case->id) }}"
                           class="btn btn-outline-warning sigma-action-btn"><span
                                class="btn-icon"><i class="fa fa-broom"></i></span><span
                                class="btn-text">Modify case</span></a>
                    @endif
                @endif

                @if((Auth()->user()->is_admin || $permissions->contains('permission_id', 119)) && !$case->locked && !isset($case->actual_delivery_date))
                    <a href="{{ route('redo-case-view', $case->id) }}"
                       class="btn btn-outline-warning sigma-action-btn"><span
                            class="btn-icon"><i class="fa fa-broom"></i></span><span
                            class="btn-text">Redo case</span></a>
                @endif

                @if((Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))) || ($permissions && ((!isset($case->actual_delivery_date) && $permissions->contains('permission_id', 115))) || (optional($case->jobs->first())->stage == 1 && $permissions->contains('permission_id', 1)))) && !$case->locked)
                    <a href="{{ route('edit-case-view', $case->id) }}"
                       class="btn btn-warning sigma-action-btn"><span
                            class="btn-icon"><i class="fa-solid fa-pen-to-square"></i></span><span
                            class="btn-text">Edit</span></a>
                @endif
            </div>

            <div class="sigma-actions-row sigma-actions-row--cancel">
                <button type="button" class="btn btn-secondary sigma-action-btn" data-dismiss="modal">
                    Cancel
                </button>
            </div>
        </div>
    @else
        <a href="{{ route('restore-case', $case->id) }}"
           class="btn btn-danger sigma-action-btn">Restore case</a>
    @endif
</div>
