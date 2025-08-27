@extends('layouts.app', ['pageSlug' => 'Types Management'])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Types Management</h4>
                            <p class="card-category">Manage material sub-types</p>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('types.create') }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-plus"></i> Add New Type
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table tablesorter" id="typesTable">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Type Name</th>
                                    <th>Material</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Jobs Count</th>
                                    <th>Created</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $type)
                                <tr>
                                    <td>{{ $type->id }}</td>
                                    <td><strong>{{ $type->name }}</strong></td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $type->material->name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>{{ $type->description ?? '-' }}</td>
                                    <td>
                                        @if($type->is_enabled)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Enabled
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-pause"></i> Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $type->jobs->count() }}
                                        </span>
                                    </td>
                                    <td>{{ $type->created_at->format('M d, Y') }}</td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" 
                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('types.edit', $type) }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                
                                                <form action="{{ route('types.toggle-status', $type) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    @if($type->is_enabled)
                                                        <button type="submit" class="dropdown-item text-warning">
                                                            <i class="fas fa-pause"></i> Disable
                                                        </button>
                                                    @else
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-play"></i> Enable
                                                        </button>
                                                    @endif
                                                </form>
                                                
                                                @if($type->jobs->count() == 0)
                                                    <div class="dropdown-divider"></div>
                                                    <form action="{{ route('types.destroy', $type) }}" method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this type?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="dropdown-divider"></div>
                                                    <span class="dropdown-item text-muted">
                                                        <i class="fas fa-lock"></i> Cannot delete (has jobs)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#typesTable').DataTable({
            "pageLength": 25,
            "order": [[ 2, "asc" ], [ 1, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    });
</script>
@endpush