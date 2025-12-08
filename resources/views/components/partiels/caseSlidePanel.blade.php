@props(['case', 'stageType' => '3dprinting'])

<div id="YSH-slide-overlay-{{$case->id}}" class="YSH-slide-overlay"
     onclick="YSH_closeSlidePanel({{$case->id}})">
    <div id="YSH-slide-panel-{{$case->id}}" class="YSH-slide-panel">
        <button type="button" class="YSH-slide-floating-close" aria-label="Close"
                onclick="YSH_closeSlidePanel({{$case->id}})">×</button>
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
                                    $unitCount = $unitNumbers ? count(explode(',', $unitNumbers)) : 0;
                                    $metaChips = array_filter([
                                        $job->material->name ?? null,
                                        ($job->color && $job->color !== '0') ? $job->color : null,
                                        ($job->style && $job->style !== 'None') ? $job->style : null,
                                        (isset($job->implantR) && $job->jobType->id == 6) ? 'Implant: ' . $job->implantR->name : null,
                                        (isset($job->abutmentR) && $job->jobType->id == 6) ? 'Abutment: ' . $job->abutmentR->name : null,
                                    ]);
                                @endphp
                                <div class="ysh-job-card">
                                    <div class="ysh-job-main">
                                        <div class="ysh-job-type">{{$job->jobType->name ?? "No Job Type"}}</div>
                                        <div class="ysh-job-badges">
                                            <span class="ysh-chip ysh-chip-quiet">{{$unitCount ?: 'n/a'}} unit{{$unitCount === 1 ? '' : 's'}}</span>
                                            @if($unitNumbers)
                                                <span class="ysh-chip ysh-chip-code">{{$unitNumbers}}</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if(count($metaChips) > 0)
                                        <div class="ysh-job-meta">
                                            @foreach($metaChips as $chip)
                                                <span class="ysh-chip">{{$chip}}</span>
                                            @endforeach
                                        </div>
                                    @endif
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
                    <div class="col-md-6 col-sm-12 padding5px">
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
                    <div class="col-md-6 col-sm-12 padding5px"><a
                            href="{{route('edit-case-view',$case->id)}}">
                            <button type="button"
                                    class="btn btn-warning " {{$canEditCase ? '' : 'disabled'}}>
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </a></div>

                    <div class="col-12 padding5px">
                        <button type="button" class="btn btn-secondary "
                                data-dismiss="modal" style="width:100%">
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
                        var panel = refs.panel;
                        overlay.classList.remove('YSH-closing');
                        overlay.style.display = 'block';
                        requestAnimationFrame(function () {
                            overlay.classList.add('YSH-active');
                            panel.style.right = '0';
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
                        panel.style.right = '-100%';

                        var cleanup = function () {
                            overlay.style.display = 'none';
                            overlay.classList.remove('YSH-closing');
                            overlay.removeEventListener('transitionend', cleanup);
                            overlay.removeEventListener('animationend', cleanup);
                        };

                        overlay.addEventListener('transitionend', cleanup);
                        overlay.addEventListener('animationend', cleanup);
                    };
                }
            })();
        </script>
    @endpush
@endonce

@once
    <style>
        .YSH-slide-floating-close {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .YSH-slide-floating-close:hover {
            background: #f3f4f6;
        }

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
            gap: 8px;
        }

        .ysh-job-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 12px;
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        }

        .ysh-job-main {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .ysh-job-type {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
        }

        .ysh-job-badges {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .ysh-job-meta {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .ysh-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #e5e7eb;
            color: #334155;
            font-size: 12px;
            line-height: 1.2;
        }

        .ysh-chip-quiet {
            background: #e0f2fe;
            color: #0ea5e9;
            font-weight: 700;
        }

        .ysh-chip-code {
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
            background: #0f172a;
            color: #e2e8f0;
            letter-spacing: 0.02em;
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
