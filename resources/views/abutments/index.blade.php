@extends('layouts.app' ,[ 'pageSlug' => 'Abutments'])
@section('content')
    <div class="sigma-config-page sigma-list-page">
        <div class="sigma-config-page-actions">
            <a href="{{route('new-abutment-view')}}" class="btn btn-secondary sigma-config-add-btn"><i class="fa fa-plus-circle"></i> New Abutment</a>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table class="table-striped table-bordered compact sunriseTable sigma-list-table sigma-config-table"
                               role="grid" aria-describedby="datatable_info"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th class="sigma-cell-center">ID</th>
                                <th class="sigma-cell-left">Name</th>
                                <th class="sigma-cell-center">Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($abutments as $abutment)
                            <tr id="{{$abutment->id}}"  class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$abutment->id}}">
                                <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{$abutment->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                <td class="tabledit-view-mode sigma-cell-left"><span class="tabledit-span">{{$abutment->name}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$abutment->created_at }}</span></td>

                               </tr>


                            <div class="modal fade sigma-action-dialog sigma-modal--abutments-index-actions" tabindex="-1" role="dialog" id="actionsDialog{{$abutment->id}}">

                                <input type="hidden" name="case_id" value="{{$abutment->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Abutment Actions</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Name: </label>
                                                    <h5 id="doctor"><b>{{$abutment->name}}</b></h5>
                                                </div>

                                            </div>

                                            <hr>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="row sigma-actions-row">
                                                <div class="col-12">
                                                    <a href="{{route('edit-abutment-view', $abutment->id)}}"
                                                       class="btn btn-warning">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit Abutment
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
