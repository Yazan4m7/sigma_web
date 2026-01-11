@props(['case', 'stageType' => '3dprinting'])

<div id="YSH-slide-overlay-{{$case->id}}" class="YSH-slide-overlay"
     onclick="if (event.target === this) YSH_closeSlidePanel({{$case->id}})">
    <div id="YSH-slide-panel-{{$case->id}}" class="YSH-slide-panel">
        <div class="YSH-slide-header">
            <h5>Case Completion</h5>
            <button type="button" class="YSH-close-slide"
                    onclick="YSH_closeSlidePanel({{$case->id}})">&times;
            </button>
        </div>
        <div class="YSH-slide-grid">
            <div class="YSH-slide-body">
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
                <hr>
                <div class="form-group row">
                    <div class="col-12">

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
                        <div class="ysh-jobs-meta">
                            <span class="ysh-jobs-stage">{{$stageLabel}}</span>
                            <span class="ysh-jobs-count">{{$jobsAtStage->count()}} job{{$jobsAtStage->count() === 1 ? '' : 's'}}</span>
                        </div>
                        <div class="ysh-job-list">
                            @forelse($jobsAtStage as $job)
                                @php
                                    $unitNumbers = $job->unit_num ?? '';
                                    $unitLabel = $unitNumbers !== '' ? $unitNumbers : '-';
                                    $jobTypeLabel = $job->jobType?->name ?? '-';
                                    $materialLabel = $job->material?->name ?? '-';
                                    $colorLabel = ($job->color && $job->color !== '0') ? $job->color : '-';
                                @endphp
                                <div class="sigma-case-item">
                                    <div class="info-case-row">
                                        <div>{{$jobTypeLabel}}</div>
                                        <div>{{$materialLabel}}</div>
                                        <div>{{$unitLabel}}</div>
                                        <div>{{$colorLabel}}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="ysh-job-empty">No jobs for this stage yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if(count($case->notes) > 0)
                    <hr>
                    <label><b>Notes:</b></label><br>
                    @foreach($case->notes as $note)
                        <div class="form-control"
                             style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px">
                            <span
                                class="noteHeader">{{ '[' . substr($note->created_at,0,16) . '] [' . $note->writtenBy->name_initials . '] :' }}</span><br>
                            <span class="noteText">{{$note->note}}</span>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="modal-footer fullBtnsWidth">
                <div class="row btnsRow"
                     style=" margin-right: 0px; margin-left: 0px;width:100%">
                    <div class="col-md-6 col-sm-12 padding5px order-1">
                        <a href="{{route('view-case', ['id' => $case->id, 'stage' => 3  ])}}">
                            <button type="button" class="btn btn-info "><i
                                    class="fas fa-eye"></i> View
                            </button>
                        </a>
                    </div>

                    @php
                        $permissions = safe_permissions();
                        $canEditCase = false;
                        if(Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))))
                        $canEditCase = true;
                    @endphp
                    <div class="col-md-6 col-sm-12 padding5px order-2"><a
                            href="{{route('edit-case-view',$case->id)}}">
                            <button type="button"
                                    class="btn btn-warning " {{$canEditCase ? '' : 'disabled'}}>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </a></div>

                    <div class="col-12 padding5px order-3">
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
        .ysh-jobs-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1f2937;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .ysh-jobs-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 6px 0 10px;
            color: #475569;
            font-size: 13px;
        }

        .ysh-jobs-stage {
            font-weight: 700;
            color: #111827;
        }

        .ysh-jobs-count {
            background: #eef2ff;
            color: #4338ca;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 700;
        }

        .ysh-job-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 6px;
        }

        .ysh-job-card {
            display: grid;
            grid-template-columns: minmax(48px, 72px) minmax(120px, 1.3fr) minmax(120px, 1.2fr) minmax(60px, 90px);
            align-items: center;
            padding: 6px 0;
            color: #1f2937;
        }

        .ysh-job-cell {
            position: relative;
            padding-right: 12px;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 13px;
        }

        .ysh-job-cell::after {
            content: "-";
            position: absolute;
            right: 4px;
            top: 0;
            color: #94a3b8;
        }

        .ysh-job-cell:last-child::after {
            content: "";
        }

        .ysh-job-cell--unit {
            font-weight: 600;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
        }

        .ysh-job-cell--color {
            font-weight: 600;
            color: #334155;
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
