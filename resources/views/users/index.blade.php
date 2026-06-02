@extends('layouts.app' ,[ 'pageSlug' =>'Users List'])
@section('content')
    <div class="sigma-config-page sigma-list-page users-config-page">
        <div class="sigma-config-toolbar">
            <form class="sigma-config-toolbar-row sigma-config-toolbar-form" method="GET" action="{{ route('users-index') }}">
                <div class="sigma-config-field sigma-config-field--grow">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control sigma-config-control" onchange="this.form.submit()">
                        <option value="1" {{$status == 1? 'selected' : ''}}>Enabled</option>
                        <option value="0" {{ $status == 0? 'selected' : ''}}>Disabled</option>
                    </select>
                </div>
                <div class="sigma-config-toolbar-actions">
                    <a href="{{ route('new-user-view') }}" class="btn sigma-config-add-btn">
                        <i class="fa fa-plus-circle"></i> Add User
                    </a>
                </div>
            </form>
            <p class="text-muted mb-0 mt-3">Employees and their details.</p>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table class="table-striped table-bordered compact sunriseTable sigma-list-table sigma-config-table"
                   role="grid" aria-describedby="datatable_info"
                   style="width:100%">
                            <thead>
                            <tr>
                                <th class="sigma-cell-center">ID</th>
                                <th>Name</th>
                                <th class="sigma-cell-center">Phone</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr id="{{$user->id}}"  class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$user->id}}">
                                    <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{$user->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                    <td class="tabledit-view-mode"><span class="tabledit-span">{{$user->first_name . ' ' . $user->last_name}}</span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$user->phone}}</span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="Doe" style="display: none;" disabled=""></td>

                               </tr>
                                <div class="modal fade sigma-action-dialog sigma-modal--users-actions" tabindex="-1" role="dialog" id="actionsDialog{{$user->id}}">

                                    <input type="hidden" name="case_id" value="{{$user->id}}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">User Actions</h5>

                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group row mb-0">
                                                    <div class="form-group col-6 mb-0">
                                                        <label for="doctor">Name: </label>
                                                        <h5 id="doctor">{{$user->fullName()}}</h5>
                                                    </div>
                                                    <div class="form-group col-6 mb-0">
                                                        <label for="doctor">Name Initials: </label>
                                                        <h5 id="doctor">{{$user->name_initials}}</h5>
                                                    </div>
                                                </div>

                                                <div class="form-group row mb-0">
                                                    <div class="form-group col-6 mb-0">
                                                        <label for="doctor">Is Admin: </label>
                                                        <h5 id="doctor">{{$user->is_admin ? 'YES' : 'NO'}}</h5>
                                                    </div>
                                                    <div class="form-group col-6 mb-0">
                                                        <label for="doctor">Status: </label>
                                                        <h5 id="doctor">{{$user->status ? 'Active' : 'Disabled'}}</h5>
                                                    </div>
                                                </div>
                                                <hr>

                                            </div>
                                            <div class="modal-footer">
                                                <div class="row sigma-actions-row">
                                                    <div class="col-12 col-sm-6">
                                                        <a href="{{route('edit-user-view',$user->id)}}" class="btn btn-warning">
                                                            <i class="fa-solid fa-pen-to-square"></i> Edit User
                                                        </a>
                                                    </div>
                                                    <div class="col-12 col-sm-6">
                                                        <a href="{{route('soft-delete-user', $user->id)}}"
                                                            onclick="return confirm('Are you sure you want to delete this user?');"
                                                            class="btn btn-danger">
                                                            Delete
                                                        </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>

                                </div>
                            @endforeach

                            </tbody>
            </table>
        </div>
    </div>
@endsection



