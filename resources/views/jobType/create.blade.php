@extends('layouts.app' ,[ 'pageSlug' => 'New Job Type' ])
@section('content')
    <div class="row">
        <div class="col-lg-8 col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">New Job Type</h4>
                    <p class="card-category mb-0">Create a new job type</p>
                </div>

                <form id="jobTypeCreateForm" method="POST" action="{{route('new-job-type')}}">
                    @csrf

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="jobtype_name">Job type name *</label>
                            <input
                                id="jobtype_name"
                                class="form-control @error('jobtype_name') is-invalid @enderror"
                                type="text"
                                name="jobtype_name"
                                value="{{ old('jobtype_name') }}"
                                required
                                placeholder="e.g., Crown, Veneer"
                            />
                            @error('jobtype_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">E.g.: Crown, Veneer</small>
                        </div>

                        <div class="form-group">
                            <label class="d-block mb-2">Section *</label>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    id="teeth"
                                    name="teeth_or_jaw"
                                    value="0"
                                    required
                                    {{ (string) old('teeth_or_jaw') === '0' ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="teeth">Teeth</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    id="jaw"
                                    name="teeth_or_jaw"
                                    value="1"
                                    required
                                    {{ (string) old('teeth_or_jaw') === '1' ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="jaw">Jaw</label>
                            </div>
                            @error('teeth_or_jaw')
                                <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Job Type
                        </button>
                        <a href="{{ route('job-type-index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{asset('assets/plugins/parsleyjs/dist/parsley.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#jobTypeCreateForm').parsley();
        });
    </script>
@endpush
