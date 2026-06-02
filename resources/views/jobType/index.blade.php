@extends('layouts.app' ,[ 'pageSlug' => 'Job Types' ])
@section('content')
    <div class="sigma-config-page sigma-list-page">
        <div class="sigma-config-toolbar sigma-config-card">
            <div class="sigma-config-toolbar-row">
                <div class="sigma-config-toolbar-spacer"></div>
                <div class="sigma-config-toolbar-actions">
                    <a href="{{route('new-job-type-view')}}" class="btn btn-secondary sigma-config-add-btn"><i class="fa fa-plus-circle"></i> Add Job Type</a>
                </div>
            </div>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table class="table-striped table-bordered compact sunriseTable sigma-list-table sigma-config-table"
                               role="grid" aria-describedby="datatable_info"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th class="sigma-cell-center">ID</th>
                                <th class="sigma-cell-left">Name</th>
                                <th class="sigma-cell-center">Teeth or Jaw</th>
                                <th class="sigma-cell-center">Materials #</th>

                               </tr>
                            </thead>
                            <tbody>
                            @foreach($jobTypes as $jobType)
                            <tr id="{{$jobType->id}}" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$jobType->id}}">
                                <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{$jobType->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                <td class="tabledit-view-mode sigma-cell-left"><span class="tabledit-span">{{$jobType->name}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$jobType->teeth_or_jaw == 0 ? "TEETH" : "JAW"}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{count($jobType->materials)}}</span>
                                </td>
                            </tr>
                            <div class="modal sigma-action-dialog sigma-modal--job-types-actions" tabindex="-1" role="dialog" id="actionsDialog{{$jobType->id}}">

                                <input type="hidden" name="case_id" value="{{$jobType->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Job Type Actions</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row mb-0">
                                                <div class="form-group col-6 mb-0">
                                                    <label for="doctor">Name: </label>
                                                    <h5 id="doctor">{{$jobType->name}}</h5>
                                                </div>
                                                <div class="form-group col-6 mb-0">
                                                    <label for="pat">Type: </label>
                                                    <h5 id="pat">{{$jobType->teeth_or_jaw == 0 ? "TEETH" : "JAW"}}</h5>
                                            </div>
                                            </div>
                                            <hr>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="row sigma-actions-row">
                                                <div class="col-12">
                                                    <a href="{{route('edit-job-type-view', $jobType->id)}}"
                                                        class="btn btn-warning">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit Job Type
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
