@extends('layouts.app' ,[ 'pageSlug' => "Create Case"])
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create Case</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tools.store-case') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Stage</label>
                                <input type="number" name="stage" class="form-control" placeholder="Enter stage (1-8)" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Phase</label>
                                <input type="number" name="phase" class="form-control" placeholder="Enter phase">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Jaw/Teeth</label>
                                <select class="form-control" name="jaw_teeth">
                                    <option value="jaw">Jaw</option>
                                    <option value="teeth">Teeth</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" name="amount" class="form-control" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Create Cases</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
