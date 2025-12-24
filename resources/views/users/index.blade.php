@extends('layouts.app' ,[ 'pageSlug' =>'Users List'])
@section('test')
    <div>
        <h1>Welcome to the Page!</h1>
        <p>This content is specific to this page.</p>
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class=" m-b-30">
                <div class="">
                    <div class="row">
                        <!-- Add User Button on the right -->
                        <div class="col-md-6"></div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('new-user-view') }}">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fa fa-plus-circle"></i> Add User
                                </button>
                            </a>
                        </div>
                    </div>


                    {{--                ----------------------------------------------
                    {{--                Header starts here
                    {{--                -----------------------------------------------}}
                    <div class="mt-4">
                        <div class=" col-12 row">
                        <div class = "col-6">
                        <p class="text-muted col6">Employees and their details.</p>
                        </div>
                    <!-- Sorting Form -->
                        <div class = "col-6">
                    <form class="kt-margin-s-1 " id="kt_subheader "  method="GET" action="{{ route('users-index') }}">
                        <strong style="color:black;">Show:</strong>

                        <div class="kt-subheader__search mt-2">

                            <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                                <option value="1" {{$status == 1? 'selected' : ''}}>Enabled</option>
                                <option value="0" {{ $status == 0? 'selected' : ''}}>Disabled</option>

                            </select>
                        </div>

                    </form>
                    </div>
                        </div>
                    <div class="">
                        <!-- Yield the 'content' section -->

                        <table class="table-striped table-bordered compact sunriseTable"

                               role="grid" aria-describedby="datatable_info"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>

                                </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr id="{{$user->id}}"  class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$user->id}}">
                                    <td><span class="tabledit-span tabledit-identifier">{{$user->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                    <td class="tabledit-view-mode"><span class="tabledit-span">{{$user->first_name . ' ' . $user->last_name}}</span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode"><span class="tabledit-span">{{$user->phone}}</span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="Doe" style="display: none;" disabled=""></td>

                               </tr>
                                <div class="modal fade sigma-action-dialog" tabindex="-1" role="dialog" id="actionsDialog{{$user->id}}">

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
            </div>
        </div>
    </div>
@endsection



