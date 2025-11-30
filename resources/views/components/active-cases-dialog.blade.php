@php use App\Build;use App\job;use App\sCase;use App\Http\Controllers\OperationsUpgrade; @endphp

@props([
    'title',
    'btnText',
    'type',
    'deviceId',
    'isBuilds' => false
])

@php

    // Get stage configuration
    $stageConfig = OperationsUpgrade::STAGE_CONFIG;

    // Get all builds for this device that have not been finished
    $builds = Build::where('device_used', $deviceId)
        ->whereNotNull('set_at')
        ->whereNull('finished_at')
        ->get();

    // Create an array to store job data for each build
    $buildData = [];

    // For each build, get its jobs and cases
    foreach ($builds as $build) {
        // Get all jobs with this build ID based on workflow type
        $buildJobs = [];

        if ($type == 'milling') {
            $buildJobs = job::where('milling_build_id', $build->id)->with(['jobType', 'subType'])->get();
        } else if ($type == '3dprinting') {
            $buildJobs = job::where('printing_build_id', $build->id)->with(['jobType', 'subType'])->get();
        } else if ($type == 'sintering') {
            $buildJobs = job::where('sintering_build_id', $build->id)->with(['jobType', 'subType'])->get();
        } else if ($type == 'pressing') {
            $buildJobs = job::where('pressing_build_id', $build->id)->with(['jobType', 'subType'])->get();
        }

        // Count the jobs
        $jobCount = count($buildJobs);
        Log::info("Active cases dialog : jobCount: ".$jobCount);
        // Create data structure for this build
        $buildInfo = [
            'build' => $build,
            'jobCount' => $jobCount,
            'cases' => [],
            'hasJobs' => $jobCount > 0
        ];

        // Group jobs by case
        $jobsByCaseId = [];
        foreach ($buildJobs as $job) {
            $caseId = $job->case_id;
            if (!isset($jobsByCaseId[$caseId])) {
                $jobsByCaseId[$caseId] = [];
            }
            $jobsByCaseId[$caseId][] = $job;
        }
         Log::info("Active cases dialog : jobsByCaseId count : ".count($jobsByCaseId));

        // For each case, get case details and job info
        foreach ($jobsByCaseId as $caseId => $jobs) {
            $case = sCase::find($caseId);
              Log::info("Active cases dialog : case : ". json_encode($case));
            if (!$case) continue;

            // Count units
            $unitCount = 0;
            $jobTypes = [];

            foreach ($jobs as $job) {
                // Count units
                if (!empty($job->unit_num)) {
                    $units = explode(',', $job->unit_num);
                    $unitCount += count($units);
                } else {
                    $unitCount += 1;
                }

                // Get job type and type (sub-material)
                if ($job->jobType) {
                    $jobTypeText = $job->jobType->name;
                    $jobTypes[] = $jobTypeText;
                }
            }

            // Deduplicate job types
            $jobTypes = array_unique($jobTypes);

            // Add case to build data
            $buildInfo['cases'][] = [
                'case' => $case,
                'jobs' => $jobs,
                'jobCount' => count($jobs),
                'unitCount' => $unitCount,
                'jobTypes' => implode(', ', $jobTypes)
            ];
      Log::info("Active cases dialog device id : ".$deviceId." buildInfo : ". count($buildInfo['cases']));
Log::info("--------------------------------------------");
        }

        // Add build data to collection
        $buildData[] = $buildInfo;
    }
$hasActiveJobs =false;
foreach($buildData as $data)
                        {
                        Log::info("BUILD DATA: " .$data['build']->started_at);


 }

@endphp

@php
    $hasActiveJobs = collect($buildData)->contains(fn($data) => $data['build']->started_at !== null);
Log::info("-----------Dialog has Active Jobs -------: ".$hasActiveJobs);
@endphp
<style>
    .row.info-case-row{

    }

    .animated-button {
        position: relative;
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 16px 36px;
        border: 4px solid;
        border-color: transparent;
        font-size: 16px;
        background-color: inherit;
        border-radius: 100px;
        font-weight: 600;
        color: greenyellow;
        box-shadow: 0 0 0 2px greenyellow;
        cursor: pointer;
        overflow: hidden;
        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .animated-button svg {
        position: absolute;
        width: 24px;
        fill: greenyellow;
        z-index: 9;
        transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .animated-button .arr-1 {
        right: 16px;
    }

    .animated-button .arr-2 {
        left: -25%;
    }

    .animated-button .circle {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -55%);
        width: 20px;
        height: 20px;
        background-color: greenyellow;
        border-radius: 50%;
        opacity: 0;
        transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .animated-button .text {
        position: relative;
        z-index: 1;
        transform: translateX(-12px);
        transition: all 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .animated-button:hover {
        box-shadow: 0 0 0 12px transparent;
        color: #212121;
        border-radius: 12px;
    }

    .animated-button:hover .arr-1 {
        right: -25%;
    }

    .animated-button:hover .arr-2 {
        left: 16px;
    }

    .animated-button:hover .text {
        transform: translateX(12px);
    }

    .animated-button:hover svg {
        fill: #212121;
    }

    .animated-button:active {
        scale: 0.95;
        box-shadow: 0 0 0 4px greenyellow;
    }

    .animated-button:hover .circle {
        width: 220px;
        height: 220px;
        opacity: 1;
    }

    /* Active Cases Dialog Layout Styles */
    .sigma-build-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        gap: 12px;
    }

    .sigma-job-checkbox {
        flex-shrink: 0;
    }

    .sigma-build-title {
        flex: 1;
        font-weight: 700;
        font-size: 18px;
        text-align: left;
        min-width: 0; /* Allows text to wrap/truncate */
        /*font-family: 'Nunito', 'Segoe UI', 'Tahoma', 'Arial Unicode MS', Arial, sans-serif;*/
        /*direction: rtl;*/
        /*unicode-bidi: bidi-override;*/
        line-height: 1.5;
    }

    .sigma-build-title.sigma-date-title {
        direction: ltr;
        unicode-bidi: normal;
        text-align: left;
    }

    .sigma-build-units {
        flex-shrink: 0;
        font-weight: 600;
        font-size: 16px;
        color: white;
        margin-right: 8px;
        background-color: rgba(0, 0, 0, 0.4);
        padding: 4px 8px;
        border-radius: 4px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    .sigma-build-toggle {
        flex-shrink: 0;
        cursor: pointer;
        padding: 4px;
    }

    /* Case Items Table-like Layout */
    .sigma-build-details {
        width: 100%;
    }

    .sigma-build-cases {
        width: 100%;
    }

    .sigma-case-item {
        width: 100%;
    }

    .sigma-case-info-row {
        display: grid;
        grid-template-columns: 1fr 1fr !important;
        gap: 16px;
        align-items: center;
        padding: 8px 16px;
        border-bottom: 1px solid #eee;
        width: 100%;
        box-sizing: border-box;
    }


    .sigma-case-patient,
    .sigma-case-units {
        font-size: 16px;
        text-align: left;
        overflow: hidden;
        text-overflow: ellipsis;

        line-height: 1.5;
    }

    .sigma-case-doctor {
        font-weight: 700;
        color: #333;
    }

    .sigma-case-patient {
        color: #555;
    }

    .sigma-case-units {
        color: white;
        text-align: center;
        background-color: rgba(0, 0, 0, 0.3);
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    .sigma-case-view {
        text-align: center;
    }

    .sigma-case-view-btn {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background-color 0.2s;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sigma-case-view-btn:hover {
        background-color: #f8f9fa;
        color: #0056b3;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sigma-case-info-row {
            grid-template-columns: 1fr 1fr auto;
            gap: 8px;
        }

        .sigma-case-units {
            display: none; /* Hide units on small screens */
        }

        .sigma-build-title {
            font-size: 16px;
        }

        .sigma-build-units {
            font-size: 13px;
            padding: 3px 6px;
        }
    }

    /* Improved Submit Button Styling */
    .sigma-animated-submit-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 14px 36px;
        border: none;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        min-width: 170px;
        height: 62px;
        outline: none;
        box-shadow: 0 22px 36px rgba(20, 108, 115, 0.3);
    }

    .sigma-animated-submit-button.start-mode {
        background: linear-gradient(135deg, #1a8b92 0%, #0f6d73 100%);
        color: white;
    }

    .sigma-animated-submit-button.complete-mode {
        background: linear-gradient(135deg, #239347 0%, #167737 100%);
        color: white;
    }

    .sigma-animated-submit-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .sigma-animated-submit-button:active:not(:disabled) {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .sigma-animated-submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .sigma-animated-submit-button .button-text {
        position: relative;
        z-index: 2;
        transition: opacity 0.3s;
    }

    .sigma-animated-submit-button .button-ripple {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -55%);
        transition: width 0.6s, height 0.6s;
    }

    .sigma-animated-submit-button:active .button-ripple:not(:disabled) {
        width: 300px;
        height: 300px;
    }

    .sigma-animated-submit-button .button-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -55%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .sigma-animated-submit-button.loading .button-text {
        opacity: 0;
    }

    .sigma-animated-submit-button.loading .button-loader {
        opacity: 1;
    }

    .sigma-animated-submit-button .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Dialog Dismissal Enhancements */
    .sigma-workflow-modal {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    .sigma-workflow-modal.active {
        animation: fadeIn 0.3s ease-out !important;
    }

    .sigma-workflow-modal.closing {
        animation: fadeOut 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
        }
        to {
            opacity: 1;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        to {
            opacity: 0;
            backdrop-filter: blur(0px);
            -webkit-backdrop-filter: blur(0px);
        }
    }

</style>
{{--{{collect($buildData)--}}
{{--    ->flatMap(fn($data) => $data['cases'])}}--}}
<div class="sigma-workflow-modal animate__animated" id="{{$deviceId}}casesListDialog" tabindex="-1" role="dialog"
     onclick="handleDialogBackdropClick(event, '{{ $deviceId }}')">
    <div class="sigma-workflow-dialog" onclick="event.stopPropagation()" style="will-change: transform, opacity;">
        <div class="sigma-workflow-header">
            <span class="sigma-workflow-title">{{ $title }}</span>
            <button class="sigma-close-button" onclick="closeDeviceDialog('{{ $deviceId }}')">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="sigma-workflow-body">
            <div class="sigma-dialog-intro">
                <h3>{{ $title }}</h3>
                <p>Select a build to review its cases, then start or complete the batch when ready.</p>
            </div>

            <div class="sigma-jobs-container">


                @php
                    $stageIcons = [
                        'milling' => 'fa-cogs',
                        '3dprinting' => 'fa-print',
                        'sintering' => 'fa-fire',
                        'pressing' => 'fa-hand-paper',
                    ];
                @endphp

                <div class="sigma-builds-list">
                    @forelse($buildData as $data)
                        @php
                            $caseActive = ($data['build']->started_at != null);
                            $totalUnits = collect($data['cases'])->sum('unitCount');
                            $caseCount = count($data['cases']);
                            $buildLabel = $type === 'sintering'
                                ? ($data['build']->created_at ? $data['build']->created_at->format('M d, Y') : 'Recent Build')
                                : ($data['build']->name ?: 'Build');
                            $iconClass = $stageIcons[$type] ?? 'fa-layer-group';
                        @endphp
                        <div class="sigma-build-row {{ $caseActive ? 'build-active' : 'build-waiting' }}">
                            <div class="tile-header {{ $caseActive ? 'tile-active' : 'tile-waiting' }}" onclick="toggleBuildDetails(this)">
                                <div class="tile-title">
                                    <label class="tile-checkbox" onclick="event.stopPropagation();">
                                        @if($caseActive)
                                            <input type="checkbox"
                                                   name="jobId[]"
                                                   value="{{$data['build']->id }}"
                                                   data-group-id="{{$deviceId}}"
                                                   class="sigma-checkbox {{ $deviceId }} checkboxes-group-{{$deviceId}} {{ $stageConfig[$type]['multiple-active'] ? 'multiple-choice' : 'single-choice' }} {{ $type }} active-blue-row"
                                                   checked
                                                   disabled>
                                            <input type="hidden" name="jobId[]" value="{{$data['build']->id }}"
                                                   class="value-holder checkboxes-group-{{$deviceId}} active-values-holder-{{$deviceId}} sigma-checkbox {{$type}}"
                                                   checked/>
                                        @else
                                            <input type="checkbox"
                                                   name="jobId[]"
                                                   data-group-id="{{$deviceId}}"
                                                   value="{{$data['build']->id }}"
                                                   class="sigma-checkbox {{ $deviceId }} {{ $type }} checkboxes-group-{{$deviceId}} {{ $stageConfig[$type]['multiple-active'] ? 'multiple-choice' : 'single-choice' }} inactive-orange-row"
                                                   {{$hasActiveJobs ? 'disabled' : ''}}>
                                        @endif
                                        <span class="checkmark"></span>
                                    </label>

                                    <div class="tile-text">
                                        <div class="tile-name">{{ $buildLabel }}</div>
                                        <div class="tile-subtext">{{ $caseCount }} cases • {{ $totalUnits }} units</div>
                                    </div>
                                </div>
                                <div class="tile-controls">
                                    <span class="tile-badge">{{ $data['jobCount'] }}</span>
                                    <span class="sigma-build-toggle tile-arrow"><i class="fas fa-chevron-down"></i></span>
                                </div>
                            </div>

                            <div class="sigma-build-details">
                                <div class="sigma-build-cases">
                                    @if($caseCount === 0)
                                        <div class="sigma-empty-case-message">
                                            No cases found in this build
                                        </div>
                                    @else
                                        @foreach($data['cases'] as $caseData)
                                            @php
                                                $tooltipParts = [];
                                                if ($caseData['case']->client?->name) {
                                                    $tooltipParts[] = 'Doctor: ' . $caseData['case']->client->name;
                                                }
                                                if ($caseData['case']->patient_name) {
                                                    $tooltipParts[] = 'Patient: ' . $caseData['case']->patient_name;
                                                }
                                                $tooltipParts[] = 'Units: ' . $caseData['unitCount'];
                                                if (!empty($caseData['jobTypes'])) {
                                                    $tooltipParts[] = 'Jobs: ' . $caseData['jobTypes'];
                                                }
                                                $caseTooltip = implode(' | ', $tooltipParts);
                                            @endphp
                                            <div class="sigma-case-item tile-child" title="{{ $caseTooltip }}">
                                                <div class="case-grid">
                                                    <div class="case-grid-cell case-name">
                                                        {{ $caseData['case']->client ? $caseData['case']->client->name : 'No Client' }}
                                                    </div>
                                                    <div class="case-grid-cell case-patient">
                                                        {{ $caseData['case']->patient_name }}
                                                    </div>
                                                    <div class="case-grid-cell case-units">
                                                        {{ $caseData['unitCount'] }} units
                                                    </div>
                                                    <div class="case-grid-cell case-action">
                                                        <button type="button"
                                                                class="sigma-case-view-btn"
                                                                onclick="event.stopPropagation(); YSH_openSlidePanel({{ $caseData['case']->id }}, '{{ $type }}'); return false;">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="sigma-empty-case-message" style="margin: 12px;">
                            No active builds available for this device.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="sigma-workflow-footer">


{{--            <button type="button"  {{$hasActiveJobs ? '' : 'disabled'}} --}}
{{--            class="neon-action-button" id="actionXX-button--{{ $deviceId }}"--}}
{{--            onclick="submitDeviceDialog('{{ $deviceId }}', '{{ $type }}', '{{ $isBuilds ? 'build' : 'jobs' }}'--}}
{{--                    ,'{{ $hasActiveJobs ? 'complete' : 'start' }}')">--}}
{{--                {{ $hasActiveJobs ? 'COMPLETE' : 'START' }}--}}
{{--            </button>--}}

            <button type="button"
                    class="sigma-animated-submit-button {{ $hasActiveJobs ? 'complete-mode' : 'start-mode' }}"
                    id="actionXX-button-{{ $deviceId }}"
                    {{$hasActiveJobs ? '' : 'disabled'}}
                    onclick="submitDeviceDialog('{{ $deviceId }}', '{{ $type }}', '{{ $isBuilds ? 'build' : 'jobs' }}'
                    ,'{{ $hasActiveJobs ? 'complete' : 'start' }}')">
                <span class="button-text">{{ $hasActiveJobs ? 'COMPLETE' : 'START' }}</span>
                <div class="button-ripple"></div>
                <div class="button-loader">
                    <div class="spinner"></div>
                </div>
            </button>
        </div>
    </div>
</div>

<!--  TODO: remove id from inputs and keep it in forms ID, target class, IDs would have duplicates
     مع اطيب المتنيات و احر التعازي
     -->

<form id="process-form-{{ $deviceId }}" method="POST" action="{{ route('operations-upgrade') }}" class="d-none">
    @csrf
    <input type="hidden" name="deviceId" value="{{ $deviceId }}">
    <input type="hidden" name="items" id="selected-items-{{ $deviceId }}" value="">
    <input type="hidden" name="action" id="action-type-{{ $deviceId }}" value="">
    <input type="hidden" name="type" id="action-type-{{ $deviceId }}" value="{{ $type }}">
    <input type="hidden" class="buildsIdsHiddenInput{{$deviceId}}" name="buildsIdsHiddenInput{{$deviceId}}"
           id="action-buildsIds-{{ $deviceId }}" value="">
</form>




@foreach($buildData as $data)
    @foreach($data['cases'] as $caseData)
        <x-partiels.caseSlidePanel :case="$caseData['case']" :stageType="$type"/>
    @endforeach
@endforeach


<style>
    /* Scoped palette */
    .sigma-workflow-modal {
        background: radial-gradient(circle at top, rgba(229, 234, 243, 0.9), rgba(208, 214, 223, 0.95));
    }

    .sigma-workflow-dialog {
        --main-blue: #1d4ed8;
        --main-orange: #d97706;
        --main-green: #15803d;
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.05);
        box-shadow: 0 40px 80px rgba(15, 23, 42, 0.18);
        overflow: hidden;
    }

    /* Empty state styling */
    .sigma-empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 1rem;
    }

    .sigma-empty-icon {
        font-size: 3rem;
        color: #adb5bd;
        margin-bottom: 1rem;
    }

    .sigma-empty-message {
        font-size: 1.1rem;
        color: #6c757d;
        font-weight: 500;
    }

    .sigma-empty-case-message {
        padding: 15px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 10px 0;
        border: 1px dashed #ced4da;
    }

    .sigma-empty-case-message {

    /* Build list styling */
        padding: 15px;
        text-align: center;
        color: #6c757d;
        font-style: italic;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin: 10px 0;
        border: 1px dashed #ced4da;
    }

    /* Build list styling - modern tiles */
    .sigma-builds-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 18px;
        padding: 10px 6px 24px;
    }

    .sigma-build-row {
        border-radius: 18px;
        background: transparent;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        padding: 2px;
    }

    .tile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 18px 28px;
        background: #fff;
        cursor: pointer;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 18px;
        position: relative;
        min-height: 74px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    }

    .sigma-build-row:hover .tile-header {
        box-shadow: 0 26px 55px rgba(15, 23, 42, 0.18);
        transform: translateY(-2px);
    }

    .tile-header::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 6px;
        border-top-left-radius: 18px;
        border-bottom-left-radius: 18px;
        background: linear-gradient(180deg, rgba(29, 78, 216, 0.9), rgba(29, 78, 216, 0.4));
    }

    .sigma-build-row.build-active .tile-header::before {
        background: linear-gradient(180deg, rgba(29, 78, 216, 0.9), rgba(29, 78, 216, 0.4));
    }

    .sigma-build-row.build-waiting .tile-header::before {
        background: linear-gradient(180deg, rgba(217, 119, 6, 0.9), rgba(217, 119, 6, 0.4));
    }

    .tile-title {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .tile-checkbox {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #0f172a;
    }

    .sigma-build-row.build-active .tile-checkbox {
        color: var(--main-blue, #1d4ed8);
    }

    .sigma-build-row.build-waiting .tile-checkbox {
        color: var(--main-orange, #d97706);
    }

    .tile-checkbox input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border: 2px solid currentColor;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #fff;
        position: relative;
    }

    .tile-checkbox input[type="checkbox"]:checked {
        background: currentColor;
        border-color: currentColor;
    }

    .tile-checkbox input[type="checkbox"]:checked::after {
        content: '✓';
        position: absolute;
        color: #fff;
        font-size: 13px;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -55%);
    }

    .tile-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .tile-name {
        font-weight: 700;
        font-size: 18px;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .tile-subtext {
        font-size: 15px;
        color: #6b7280;
    }

    .sigma-build-row.build-active .tile-name,
    .sigma-build-row.build-active .tile-subtext {
        color: var(--main-blue, #1d4ed8);
    }

    .sigma-build-row.build-waiting .tile-name,
    .sigma-build-row.build-waiting .tile-subtext {
        color: var(--main-orange, #d97706);
    }

    .tile-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sigma-dialog-intro {
        padding: 8px 12px 24px;
        text-align: left;
    }

    .sigma-dialog-intro h3 {
        margin: 0;
        font-size: 1.35rem;
        color: #134a4d;
        font-weight: 600;
    }

    .sigma-dialog-intro p {
        margin: 6px 0 0;
        color: #6c7a89;
        font-size: 0.95rem;
    }

    .sigma-workflow-header {
        background: linear-gradient(180deg, #f4fafb 0%, #eef2f5 100%);
        border-bottom: 1px solid rgba(15, 23, 42, 0.05);
        box-shadow: none;
        color: #0f6468;
        padding: 24px 28px 12px;
    }

    .sigma-workflow-title {
        font-size: 2rem;
        letter-spacing: 0.03em;
        font-weight: 700;
    }

    .sigma-workflow-body {
        padding: 20px 28px 32px;
        background: #f9fbfd;
    }

    .tile-badge {
        background: #f1f5f9;
        color: #1f2937;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 999px;
        min-width: auto;
        text-align: center;
        box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.08);
    }

    .sigma-build-toggle i {
        color: #9ca3af;
        transition: transform 0.3s ease;
    }

    .sigma-build-details {
        max-height: 0;
        overflow: hidden;
        padding: 0 20px;
        background: #f4f6fb;
        border-radius: 16px;
        transform: translate3d(0, 0, 0);
        transition: max-height 0.35s ease, padding 0.35s ease, opacity 0.25s ease;
        opacity: 0;
        will-change: max-height, opacity;
    }

    .sigma-build-row.expanded .sigma-build-details {
        max-height: 600px;
        padding: 0 20px 16px;
        opacity: 1;
    }

    .sigma-build-row.expanded .sigma-build-toggle i {
        transform: rotate(180deg);
    }

    .sigma-build-cases {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 12px;
    }

    .sigma-case-item {
        background: #ffffff;
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.1);
        padding: 10px 14px;
        border-radius: 12px;
        border-left: 6px solid transparent;
        border: 1px solid rgba(15, 23, 42, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .sigma-build-row.build-active .sigma-case-item {
        border-left-color: var(--main-blue, #1d4ed8);
    }

    .sigma-build-row.build-waiting .sigma-case-item {
        border-left-color: var(--main-orange, #d97706);
    }

    .sigma-case-item.tile-child {
        background: #fbfcff;
        border-left-color: inherit;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08);
    }

    .case-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(120px, 1fr));
        align-items: center;
        gap: 12px;
    }

    .case-grid-cell {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .case-grid-cell.case-units {
        color: var(--main-orange, #c2410c);
        font-weight: 700;
    }

    .case-grid-cell.case-action {
        text-align: right;
        justify-self: end;
    }

    .sigma-case-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.12);
    }

    .case-main {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .case-title {
        font-weight: 800;
        color: #1f2937;
        font-size: 17px;
    }

    .case-subtitle {
        color: #6b7280;
        font-size: 16px;
    }

    .case-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .case-units {
        font-weight: 800;
        color: var(--main-orange, #eab308);
        font-size: 15px;
    }

    .sigma-case-view-btn {
        background: var(--main-blue, #3b82f6);
        border: none;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        color: #fff;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .sigma-case-view-btn:active,
    .sigma-case-view-btn:focus {
        background: var(--main-green, #16a34a);
        color: #fff;
    }

    /* Regular jobs list styling */
    .sigma-jobs-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 16px;
    }

    .sigma-job-row {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 12px;
    }

    .sigma-job-header {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .sigma-job-checkbox {
        flex-shrink: 0;
    }

    .sigma-job-main-info {
        flex-grow: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sigma-job-title {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sigma-job-doctor {
        font-weight: 500;
        color: #333;
    }

    .sigma-job-patient {
        font-size: 0.9em;
        color: #666;
    }

    .sigma-job-details {
        text-align: right;
    }

    .sigma-job-type {
        font-size: 0.9em;
        color: #666;
    }

    .sigma-job-units {
        font-weight: 500;
        color: #333;
    }

    .sigma-job-actions {
        flex-shrink: 0;
    }

    .sigma-job-view-btn {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background-color 0.2s;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sigma-job-view-btn:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

</style>

</div>
</div>
</div>
</div>
</div>
