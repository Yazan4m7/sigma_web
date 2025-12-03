@extends('layouts.app' ,[ 'pageSlug' => "Create Case"])
@section('content')
<style>
    .tool-card {
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 24px;
    }
    .badge-summary {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .badge-summary .item {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 10px 14px;
        min-width: 160px;
    }
    .badge-summary .label {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #6b7280;
        display: block;
        margin-bottom: 4px;
    }
    .badge-summary .value {
        font-weight: 700;
        font-size: 18px;
        color: #111827;
    }
    .form-helper {
        font-size: 12px;
        color: #6b7280;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card tool-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Create Case (Tools)</h4>
                <small class="text-muted">Generate admin test cases with valid job type & material pairing</small>
            </div>
            <div class="card-body">
                <div class="badge-summary">
                    <div class="item">
                        <span class="label">Cases to create</span>
                        <span class="value" id="summary-cases">1</span>
                    </div>
                    <div class="item">
                        <span class="label">Units per job</span>
                        <span class="value" id="summary-units">0</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('tools.store-case') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Stage</label>
                                <select name="stage" class="form-control" required>
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">Stage {{ $i }}</option>
                                    @endfor
                                </select>
                                <div class="form-helper">Workflow stage for all created jobs.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phase (optional)</label>
                                <input type="number" name="phase" class="form-control" placeholder="Phase # (optional)">
                                <div class="form-helper">Leave blank to skip.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Number of cases</label>
                                <input type="number" min="1" max="50" name="amount" id="amount-input" class="form-control" value="1" required>
                                <div class="form-helper">How many cases to generate.</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jaw vs. Teeth</label>
                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <label class="mb-0"><input type="radio" name="jaw_teeth" value="jaw" checked> Jaw</label>
                                    <label class="mb-0"><input type="radio" name="jaw_teeth" value="teeth"> Teeth</label>
                                </div>
                                <div class="form-helper">Jaw = full arch (upper/lower). Teeth = specific units.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4" id="jaw-picker">
                            <div class="form-group">
                                <label>Jaw selection</label>
                                <select class="form-control" name="jaw_selection" id="jaw-selection">
                                    <option value="upper">Upper</option>
                                    <option value="lower">Lower</option>
                                    <option value="both">Upper + Lower</option>
                                </select>
                                <div class="form-helper">Only jaw-compatible job types will be allowed.</div>
                            </div>
                        </div>
                        <div class="col-md-4" id="teeth-picker" style="display:none;">
                            <div class="form-group">
                                <label>Teeth units (comma-separated)</label>
                                <input type="text" name="units" id="units-input" class="form-control" placeholder="e.g. 11,12,13">
                                <div class="form-helper">List tooth numbers separated by commas.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Job type</label>
                                <select class="form-control" name="job_type_id" id="job-type-select" required>
                                    @foreach ($jobTypes as $jt)
                                        <option value="{{ $jt->id }}" data-teeth="{{ $jt->teeth_or_jaw }}">{{ $jt->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-helper">Filtered by jaw/teeth selection.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Material</label>
                                <select class="form-control" name="material_id" id="material-select" required>
                                    @foreach ($materials as $m)
                                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-helper">Only materials linked to the job type are allowed.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-round px-4">Create Cases</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        const JOB_TYPES = @json($jobTypes);
        const MATERIALS = @json($materials);
        const JOBTYPE_MATERIALS = @json($jobTypeMaterials);

        function parseUnits(raw) {
            if (!raw) return [];
            return raw.split(',').map(function(v){ return v.trim(); }).filter(function(v){ return v.length; });
        }

        function updateSummary() {
            const casesVal = parseInt(document.getElementById('amount-input').value || '0', 10) || 0;
            document.getElementById('summary-cases').innerText = casesVal;

            const mode = document.querySelector('input[name="jaw_teeth"]:checked').value;
            let units = [];
            if (mode === 'jaw') {
                const jaw = document.getElementById('jaw-selection').value;
                units = (jaw === 'both') ? ['upper','lower'] : [jaw];
            } else {
                units = parseUnits(document.getElementById('units-input').value);
            }
            document.getElementById('summary-units').innerText = units.length;
        }

        function refreshJobTypes() {
            const mode = document.querySelector('input[name="jaw_teeth"]:checked').value;
            const requiredFlag = mode === 'jaw' ? 1 : 0;
            const select = document.getElementById('job-type-select');
            const current = select.value;
            select.innerHTML = '';
            const filtered = JOB_TYPES.filter(function(jt){ return jt.teeth_or_jaw == requiredFlag; });
            filtered.forEach(function(jt){
                const opt = document.createElement('option');
                opt.value = jt.id;
                opt.dataset.teeth = jt.teeth_or_jaw;
                opt.textContent = jt.name;
                select.appendChild(opt);
            });
            if (filtered.some(j => String(j.id) === String(current))) {
                select.value = current;
            }
            refreshMaterials();
        }

        function refreshMaterials() {
            const jobTypeId = document.getElementById('job-type-select').value;
            const select = document.getElementById('material-select');
            const current = select.value;
            select.innerHTML = '';
            const allowedMaterialIds = JOBTYPE_MATERIALS
                .filter(function(rel){ return String(rel.jobtype_id) === String(jobTypeId); })
                .map(function(rel){ return rel.material_id; });
            MATERIALS.forEach(function(mat){
                if (allowedMaterialIds.includes(mat.id)) {
                    const opt = document.createElement('option');
                    opt.value = mat.id;
                    opt.textContent = mat.name;
                    select.appendChild(opt);
                }
            });
            if (allowedMaterialIds.includes(Number(current))) {
                select.value = current;
            }
        }

        document.addEventListener('DOMContentLoaded', function(){
            document.querySelectorAll('input[name="jaw_teeth"]').forEach(function(radio){
                radio.addEventListener('change', function(){
                    const mode = this.value;
                    document.getElementById('jaw-picker').style.display = (mode === 'jaw') ? 'block' : 'none';
                    document.getElementById('teeth-picker').style.display = (mode === 'teeth') ? 'block' : 'none';
                    refreshJobTypes();
                    updateSummary();
                });
            });
            document.getElementById('jaw-selection').addEventListener('change', updateSummary);
            document.getElementById('units-input').addEventListener('input', updateSummary);
            document.getElementById('amount-input').addEventListener('input', updateSummary);
            document.getElementById('job-type-select').addEventListener('change', refreshMaterials);

            refreshJobTypes();
            refreshMaterials();
            updateSummary();
        });
    </script>
@endpush
@endsection
