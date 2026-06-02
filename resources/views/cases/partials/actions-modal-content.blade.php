@php
    $permissions = $permissions ?? safe_permissions();
@endphp

<style>
    .sigma-modal--cases-index-actions,
    .sigma-modal--cases-index-actions :not(i):not([class*="fa-"]):not(.fa):not(.fas):not(.far):not(.fab) {
        font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }
    .sigma-modal--cases-index-actions .case-actions-footer {
        position: relative;
        border-top: none !important;
    }

    .sigma-modal--cases-index-actions .case-actions-footer::before {
        content: "";
        position: absolute;
        top: 0;
        left: 16px;
        right: 16px;
        border-top: 0px solid #dbeafe;
    }


    .case-summary-divider.lower-divider{

        width: 97%;
        align-self: center;
    }
    .sigma-modal--cases-index-actions .sigma-case-notes-list {
        display: flex;
        flex-direction: column;
        margin-bottom: 1.375rem;
    }
    .sigma-modal--cases-index-actions .note-container {
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
    .sigma-modal--cases-index-actions .sigma-case-notes-list > .note-container:first-child {
        border-radius: 9px 9px 0 0 !important;
        border-top: 0.5px solid #eaeae4 !important;
    }
    .sigma-modal--cases-index-actions .sigma-case-notes-list > .note-container:last-child {
        border-radius: 0 0 9px 9px !important;
    }
    .sigma-modal--cases-index-actions .case-jobs-label,
    .sigma-modal--cases-index-actions .case-notes-label {
        display: block;
        margin-bottom: 8px !important;
        font-size: 10px !important;
        font-weight: 500 !important;
        letter-spacing: 0.09em !important;
        line-height: 1.2 !important;
        text-transform: uppercase !important;
    }
    .sigma-modal--cases-index-actions .case-jobs-label {
        color: #444441 !important;
        font-weight: 700 !important;
    }
    .sigma-modal--cases-index-actions .case-notes-label {
        color: #B4B2A9 !important;
    }
    .sigma-modal--cases-index-actions .case-jobs-label b,
    .sigma-modal--cases-index-actions .case-notes-label b {
        color: inherit !important;
        font-weight: inherit !important;
    }
    .sigma-modal--cases-index-actions .scrollable-content {
        scrollbar-color: #1f9bad #eef5f6;
        scrollbar-gutter: stable;
        scrollbar-width: thin;
    }
    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar {
        width: 8px;
    }
    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-track {
        background: #eef5f6;
        border-radius: 999px;
    }
    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #27aabc 0%, #178293 100%);
        border: 2px solid #f8fbfb;
        border-radius: 999px;
    }
    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #1f9bad 0%, #126f7d 100%);
    }
    .sigma-modal--cases-index-actions .scrollable-content::-webkit-scrollbar-button {
        display: none;
        height: 0;
        width: 0;
    }
    .sigma-modal--cases-index-actions .case-jobs-heading {
        align-items: center;
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
    }
    .sigma-modal--cases-index-actions .case-jobs-heading .case-jobs-label {
        margin-bottom: 0 !important;
    }
    .sigma-modal--cases-index-actions .sigma-case-units-counter {
        background: #E1F5EE;
        border-radius: 5px;
        color: #085041;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        line-height: 1.2;
        padding: 3px 9px;
        text-transform: uppercase;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-jobs-list {
        display: flex !important;
        flex-direction: column !important;
        gap: 0 !important;
        margin: 0 0 1.375rem !important;
        padding: 0 !important;
        width: 100%;
        background: #fff !important;
        border: 1px solid #e0e0da !important;
        border-radius: 10px !important;
        overflow: hidden !important;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row {
        align-items: center !important;
        background: #fff !important;
        border: 0 !important;
        border-bottom: 1px solid #e0e0da !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        color: inherit !important;
        display: flex !important;
        gap: 6px !important;
        margin: 0 !important;
        min-width: 0;
        overflow: hidden !important;
        padding: 10px 7px !important;
        width: 100%;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row:last-child {
        border-bottom: 0 !important;
    }
    .sigma-modal--cases-index-actions .sigma-case-job-icon {
        flex-shrink: 0;
    }
    .sigma-modal--cases-index-actions .sigma-case-job-info {
        display: block;
        flex: 1 1 0%;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-primary {
        color: #1a1a18 !important;
        align-items: baseline;
        display: flex;
        gap: 5px;
        font-size: 15px !important;
        font-weight: 600 !important;
        line-height: 1.25 !important;
        max-width: 100%;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: auto;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-units {
        flex: 0 0 auto;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-separator {
        flex: 0 0 auto;
    }
    .sigma-modal--cases-index-actions .modal-body .sigma-case-job-details {
        flex: 1 1 auto;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sigma-modal--cases-index-actions .sigma-case-job-sub {
        color: #888780;
        display: block;
        font-size: 11px;
        line-height: 1.25;
        margin-top: 2px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .sigma-modal--cases-index-actions .sigma-case-job-tag {
        background: #f1f0eb;
        border: 0 !important;
        border-radius: 5px;
        color: #444441;
        flex-shrink: 0;
        font-size: 10px;
        font-weight: 500;
        line-height: 1.2;
        max-width: 34%;
        overflow: hidden;
        padding: 4px 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-meta {
        align-items: center;
        display: flex;
        gap: 5px;
        margin-bottom: 2px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-left {
        align-items: center;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        gap: 3px;
        padding-top: 2px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-icon {
        color: #85B7EB;
        font-size: 12px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-line {
        background: #daeaf7;
        flex: 1;
        min-height: 14px;
        width: 1px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-content {
        min-width: 0;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-author {
        color: #378ADD !important;
        font-size: 11px !important;
        font-weight: 500 !important;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-separator {
        color: #daeaf7;
        font-size: 11px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-time {
        color: #85B7EB;
        font-size: 10px;
    }
    .sigma-modal--cases-index-actions .sigma-case-note-text {
        color: #888780;
        display: block;
        font-size: 12px;
        line-height: 1.45;
    }

</style>

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
            $visibleJobs = $case->jobs->filter(function ($job) use ($currentStage) {
                return $job->goesThroughStage($currentStage);
            });
            $jobsUnitsCount = $visibleJobs->reduce(function ($carry, $job) {
                if (!$job->material || $job->material->count_as_unit != 1) {
                    return $carry;
                }

                $unitCount = count(array_filter(explode(',', (string) $job->unit_num), function ($value) {
                    return trim($value) !== '';
                }));

                return $carry + max(1, $unitCount);
            }, 0);
        @endphp
        <div class="form-group row case-jobs-section">
            <div class="col-12">
                <div class="case-jobs-heading">
                    <label class="case-completion-dialog-label case-jobs-label" ><b>JOBS:</b></label>
                    <span class="sigma-case-units-counter">{{ $jobsUnitsCount }}X {{ $jobsUnitsCount === 1 ? 'UNIT' : 'UNITS' }}</span>
                </div>
                <div class="sigma-case-jobs-list">

                @foreach($case->jobs as $job)
                    @php
                        $showJob = $job->goesThroughStage($currentStage);
                        $jobTypeName = $job->jobType->name ?? 'No Job Type';
                        $materialName = $job->material->name ?? 'no material';
                        $colorLabel = $job->color == '0' ? '' : $job->color;
                        $styleLabel = $job->style == 'None' ? '' : $job->style;
                        $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? 'Implant Type: ' . $job->implantR->name : '';
                        $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? 'Abutment Type: ' . $job->abutmentR->name : '';
                        $jobUnits = trim((string) $job->unit_num);
                        $jobDetailParts = array_values(array_filter([
                            $jobTypeName,
                            $materialName,
                            $colorLabel,
                            $implantLabel,
                            $abutmentLabel,
                        ], function ($value) {
                            return filled(trim((string) $value));
                        }));
                        $normalizedStyle = strtolower(trim((string) $styleLabel));
                        $jobTag = in_array($normalizedStyle, ['single', 'bridge'], true)
                            ? ucfirst($normalizedStyle)
                            : (strpos($jobUnits, ',') !== false ? 'Bridge' : 'Single');
                        $isModelJob = stripos($jobTypeName, 'model') !== false || stripos($materialName, 'model') !== false;
                    @endphp

                    @if($showJob)
                        <div class="sigma-case-job-row">
                            @if($isModelJob)
                                <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 7.5C5.1 14 7.8 18 12 18s6.9-4 7.5-10.5"></path><path d="M7.5 7.5c.5 4.1 2 6.4 4.5 6.4s4-2.3 4.5-6.4"></path><path d="M8.4 8v2.2"></path><path d="M12 8v3.2"></path><path d="M15.6 8v2.2"></path></svg>
                            @else
                                <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C9 2 7 4 7 7c0 2 .5 3.5 1 5l1 5c.3 1.2 1 2 2 2h2c1 0 1.7-.8 2-2l1-5c.5-1.5 1-3 1-5 0-3-2-5-5-5z"></path><path d="M9 10c0 0 1 1 3 1s3-1 3-1"></path></svg>
                            @endif
                            <span class="sigma-case-job-info">
                                <span class="sigma-case-job-primary">
                                    <span class="sigma-case-job-units">{{ $jobUnits }}</span>
                                    <span class="sigma-case-job-separator">-</span>
                                    <span class="sigma-case-job-details">{{ implode(' - ', $jobDetailParts) }}</span>
                                </span>
                            </span>
                            <span class="sigma-case-job-tag">{{ $jobTag }}</span>
                        </div>
                    @endif
                @endforeach
                </div>
            </div>
        </div>
<hr>
        @if(count($case->notes) > 0)
            <div class="case-notes-section">
                <label class="case-completion-dialog-label case-notes-label"><b>NOTES:</b></label>
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
                                <span class="sigma-case-note-time">
                                    {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) }}
                                    {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}
                                </span>
                            </div>
                            <span class="noteText sigma-case-note-text">{{ $note->note }}</span>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        @endif
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
