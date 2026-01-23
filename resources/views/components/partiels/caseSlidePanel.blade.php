@props(['case', 'stageType' => '3dprinting'])

<div id="YSH-slide-overlay-{{$case->id}}" class="YSH-slide-overlay"
     onclick="if (event.target === this) YSH_closeSlidePanel({{$case->id}})">
    <div id="YSH-slide-panel-{{$case->id}}" class="YSH-slide-panel ysh-slide-panel--builds">
        <!-- Fixed Header -->
        <div class="YSH-slide-header">
            <div>
                <h5 style="margin-bottom: 0;">Case Completion</h5>
            </div>
            <button type="button" class="YSH-close-slide"
                    onclick="YSH_closeSlidePanel({{$case->id}})">&times;
            </button>
        </div>

        <!-- Fixed Doctor/Patient Info -->
        <div class="ysh-slide-info-header">
            <div class="form-group row" style="margin-bottom: 0px">
                <div class="form-group col-6" style="margin-bottom: 0px">
                    <label>Doctor:</label>
                    <h5><b>{{$case->client?->name}}</b></h5>
                </div>
                <div class="form-group col-6" style="margin-bottom: 0px">
                    <label>Patient:</label>
                    <h5><b>{{$case->patient_name}}</b></h5>
                </div>
            </div>
        </div>

        <div class="YSH-slide-grid">
            <!-- Scrollable Content -->
            <div class="YSH-slide-body">
                @php
                    // Convert stage type to stage number
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
                @endphp

                <label><b>Jobs ({{$stageLabel}}):</b></label>
                <div class="sigma-case-jobs-list">
                    @forelse($jobsAtStage as $job)
                        @php
                            $unitNumbers = $job->unit_num ?? '';
                            $unitLabel = $unitNumbers !== '' ? $unitNumbers : '-';
                            $jobTypeLabel = $job->jobType?->name ?? '-';
                            $materialLabel = $job->material?->name ?? '-';
                            $colorLabel = ($job->color && $job->color !== '0') ? $job->color : '';
                            $styleLabel = ($job->style && $job->style !== 'None') ? $job->style : '';
                        @endphp
                        <div class="sigma-case-job-row">
                            <span class="sigma-case-job-cell sigma-case-job-cell--teeth">{{$unitLabel}}</span>
                            <span class="sigma-case-job-cell sigma-case-job-cell--type">{{$jobTypeLabel}}</span>
                            <span class="sigma-case-job-cell sigma-case-job-cell--material">{{$materialLabel}}</span>
                            <span class="sigma-case-job-cell sigma-case-job-cell--color">{{$colorLabel}}</span>
                            <span class="sigma-case-job-cell sigma-case-job-cell--style">{{$styleLabel}}</span>
                        </div>
                    @empty
                        <div class="ysh-job-empty">No jobs for this stage yet.</div>
                    @endforelse
                </div>

                @if(count($case->notes) > 0)
                    <hr>
                    <label><b>Notes:</b></label><br>
                    @foreach($case->notes as $note)
                        <div class="form-control"
                             style="height:fit-content;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px">
                            <span class="noteHeader">{{ '[' . substr($note->created_at,0,16) . '] [' . $note->writtenBy->name_initials . '] :' }}</span><br>
                            <span class="noteText">{{$note->note}}</span>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="modal-footer fullBtnsWidth">
                <div class="row btnsRow"
                     style=" margin-right: 0px; margin-left: 0px;width:100%">
                    <div class="col-12 padding5px ysh-slide-actions">
                        <a href="{{route('view-case', ['id' => $case->id, 'stage' => 3  ])}}" class="ysh-slide-action-link">
                            <button type="button" class="btn btn-info ysh-slide-action-btn"><i
                                    class="fas fa-eye"></i> View
                            </button>
                        </a>

                        @php
                            $permissions = safe_permissions();
                            $canEditCase = false;
                            if(Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))))
                            $canEditCase = true;
                        @endphp
                        <a href="{{route('edit-case-view',$case->id)}}" class="ysh-slide-action-link">
                            <button type="button"
                                    class="btn btn-warning ysh-slide-action-btn" {{$canEditCase ? '' : 'disabled'}}>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </a>
                    </div>

                    <div class="col-12 padding5px ysh-slide-actions-stack">
                        <button type="button" class="btn btn-secondary "
                                onclick="YSH_closeSlidePanel({{$case->id}})" style="width:100%">
                            Cancel
                        </button>
                    </div>
                </div>


            </div>

        </div>

    </div>
</div>

@once
    @push('js')
        <script>
            (function () {
                function resolvePanel(caseId) {
                    var overlay = document.getElementById('YSH-slide-overlay-' + caseId);
                    var panel = document.getElementById('YSH-slide-panel-' + caseId);
                    if (!overlay || !panel) {
                        console.warn('Slide panel markup missing for case', caseId);
                        return null;
                    }
                    if (!overlay.dataset.boundToBody) {
                        document.body.appendChild(overlay);
                        overlay.dataset.boundToBody = '1';
                    }
                    return {overlay: overlay, panel: panel};
                }

                if (typeof window.YSH_openSlidePanel !== 'function') {
                    window.YSH_openSlidePanel = function (caseId) {
                        var refs = resolvePanel(caseId);
                        if (!refs) {
                            return;
                        }
                        var overlay = refs.overlay;
                        overlay.classList.remove('YSH-closing');
                        overlay.style.display = 'block';
                        requestAnimationFrame(function () {
                            overlay.classList.add('YSH-active');
                            if (typeof window.updateDialogScrollLock === 'function') {
                                window.updateDialogScrollLock();
                            }
                        });
                    };
                }

                if (typeof window.YSH_closeSlidePanel !== 'function') {
                    window.YSH_closeSlidePanel = function (caseId) {
                        var refs = resolvePanel(caseId);
                        if (!refs) {
                            return;
                        }
                        var overlay = refs.overlay;
                        var panel = refs.panel;
                        overlay.classList.remove('YSH-active');
                        overlay.classList.add('YSH-closing');

                        var done = false;
                        var cleanup = function () {
                            if (done) {
                                return;
                            }
                            done = true;
                            overlay.style.display = 'none';
                            overlay.classList.remove('YSH-closing');
                            panel.removeEventListener('transitionend', onTransitionEnd);
                            panel.removeEventListener('animationend', onAnimationEnd);
                            if (cleanupTimer) {
                                window.clearTimeout(cleanupTimer);
                            }
                            if (typeof window.updateDialogScrollLock === 'function') {
                                window.updateDialogScrollLock();
                            }
                        };

                        var onTransitionEnd = function (event) {
                            if (event.target !== panel) {
                                return;
                            }
                            if (event.propertyName && event.propertyName !== 'transform' && event.propertyName !== 'opacity') {
                                return;
                            }
                            cleanup();
                        };

                        var onAnimationEnd = function (event) {
                            if (event.target !== panel) {
                                return;
                            }
                            cleanup();
                        };

                        panel.addEventListener('transitionend', onTransitionEnd);
                        panel.addEventListener('animationend', onAnimationEnd);
                        var cleanupTimer = window.setTimeout(cleanup, 450);
                    };
                }
            })();
        </script>
    @endpush
@endonce

@once
    <style>
        /* Fixed info header (doctor/patient) */
        .ysh-slide-info-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            flex-shrink: 0;
        }

        /* Jobs list styling - matching sigma-case-jobs-list */
        .ysh-slide-panel--builds .sigma-case-jobs-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 8px;
        }

        .ysh-slide-panel--builds .sigma-case-job-row {
            display: flex;
            align-items: center;
            gap: 2px;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .ysh-slide-panel--builds .sigma-case-job-row:last-child {
            border-bottom: none;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell {
            font-size: 13px;
            color: #374151;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell--teeth {
            width: 70px;
            flex: 0 0 70px;
            font-weight: 600;
            color: #0f172a;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell--type {
            flex: 1;
            min-width: 0;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell--material {
            flex: 1;
            min-width: 0;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell--color {
            width: 60px;
            flex: 0 0 60px;
        }

        .ysh-slide-panel--builds .sigma-case-job-cell--style {
            width: 70px;
            flex: 0 0 70px;
        }

        .ysh-slide-panel--builds .ysh-slide-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .ysh-slide-panel--builds .ysh-slide-action-link {
            display: flex;
            flex: 1 1 0;
            min-width: 140px;
        }

        .ysh-slide-panel--builds .ysh-slide-action-btn {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ysh-job-empty {
            padding: 10px 12px;
            border: 1px dashed #e5e7eb;
            background: #f8fafc;
            border-radius: 8px;
            color: #6b7280;
            font-size: 13px;
        }
    </style>
@endonce
