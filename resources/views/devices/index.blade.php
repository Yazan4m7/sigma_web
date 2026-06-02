@extends('layouts.app' ,[ 'pageSlug' => 'Devices List' ])
@section('content')
    <div class="sigma-config-page sigma-list-page">
        <div class="sigma-config-page-actions">
            <a href="{{route('new-device-view')}}" class="btn btn-secondary sigma-config-add-btn"><i class="fa fa-plus-circle"></i> New Machine</a>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table class="table-striped table-bordered compact sunriseTable sigma-list-table sigma-config-table"
                               role="grid" aria-describedby="datatable_info"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th class="sigma-cell-center">ID</th>
                                <th class="sigma-cell-left">Name</th>
                                <th class="sigma-cell-center">Enabled</th>
                                <th class="sigma-cell-center">Units Manufactured</th>
                                <th class="sigma-cell-center">Date Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($devices as $device)
                            <tr id="{{$device->id}}" style="{{$device->hidden == '1' ? "color:#c3c3c3 !important" : "" }}" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$device->id}}">
                                <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{$device->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                <td class="tabledit-view-mode sigma-cell-left"><span class="tabledit-span">{{$device->name}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$device->hidden == '0' ? "YES" : "NO"}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$device->units}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$device->created_at }}</span></td>


                            </tr>

                            <div class="modal fade sigma-action-dialog sigma-modal--devices-actions" tabindex="-1" role="dialog" id="actionsDialog{{$device->id}}">

                                <input type="hidden" name="case_id" value="{{$device->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Machine Details</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Name: </label>
                                                    <h5 id="doctor"><b>{{$device->name}}</b></h5>
                                                </div>
                                                <div class="form-group col-6 " style="margin-bottom: 0px;max-height: 60%">
                                                    <label for="doctor">Device Image: </label>
                                                    <h5 id="doctor">        <img id="image-preview" class="image-picker" src="{{ asset($device->img) }}" alt="Machine/Device"/></h5>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Enabled: </label>
                                                    <h5 id="doctor"><b>{{$device->hidden == '0' ? "YES" : "NO"}}</b></h5>
                                                </div>

                                            </div>
                                            <hr>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="row sigma-actions-row">
                                                <div class="col-12">
                                                    <a href="{{route('edit-device-view', $device->id)}}"
                                                       class="btn btn-warning">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit Device
                                                    </a>
                                                </div>

                                                <div class="col-12">
                                                    <a href="{{route('toggle-device-visibility', $device->id)}}"
                                                       class="btn btn-outline-danger">
                                                        {{$device->hidden == '0' ? "Hide" : "Show"}}
                                                    </a>
                                                </div>

                                                <div class="col-12">
                                                    <a href="{{route('soft-delete-device', $device->id)}}"
                                                       class="btn btn-danger"
                                                       onclick="return confirm('Are you sure you want to delete this device?');">
                                                        Delete
                                                    </a>
                                                </div>

                                                <div class="col-12">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        Cancel
                                                    </button>
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
