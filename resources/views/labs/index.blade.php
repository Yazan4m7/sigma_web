@extends('layouts.app' ,[ 'pageSlug' => 'EXTERNAL LABS'])

@section('content')
    <div class="sigma-config-page sigma-list-page labs-config-page">
        <div class="sigma-config-toolbar">
            <form class="sigma-config-toolbar-row sigma-config-toolbar-form" method="GET" action="{{route('labs-index')}}">
                <div class="sigma-config-field sigma-config-field--grow">
                    <label for="labs_from">From</label>
                    <input type="date" id="labs_from" class="form-control sigma-config-control" name="from" value="{{$from ?? ''}}">
                </div>
                <div class="sigma-config-field sigma-config-field--grow">
                    <label for="labs_to">To</label>
                    <input type="date" id="labs_to" class="form-control sigma-config-control" name="to" value="{{$to ?? ''}}">
                </div>
                @if(isset($labs))
                    <div class="sigma-config-field sigma-config-field--grow">
                        <label for="doctor">Lab</label>
                        <select style="width:100%" class="selectpicker form-control clearOnAll sigma-config-control" multiple name="labs[]" id="doctor" data-container="body" data-live-search="true" title="All" data-hide-disabled="true">
                            <option value="all">All</option>
                            @foreach($labs as $lab)
                                <option value="{{$lab->id}}" {{(isset($selectedLabsIds) && in_array($lab->id ,$selectedLabsIds)) ? 'selected' : ''}}>{{$lab->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="sigma-config-toolbar-actions">
                    <button type="submit" class="btn btn-primary sigma-config-submit-btn">Apply</button>
                    <a href="{{route('new-lab-view')}}" class="btn sigma-config-add-btn">
                        <i class="fa fa-plus-circle"></i> Add Lab
                    </a>
                </div>
            </form>
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
                                <th>Address</th>
                                <th class="sigma-cell-center">Units Milled</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($selectedLabs as $lab)
                            <tr id="{{$lab->id}}" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$lab->id}}">
                                <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{$lab->id}}</span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                <td class="tabledit-view-mode"><span class="tabledit-span">{{$lab->name}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$lab->phone ?? "N/A"}}</span></td>
                                <td class="tabledit-view-mode"><span class="tabledit-span">{{$lab->address ?? "N/A"}}</span></td>
                                <td class="tabledit-view-mode sigma-cell-center"><span class="tabledit-span">{{$lab->unitsMilled($from ?? -1,$to ?? -1)}}</span></td>


                               </tr>
                            <div class="modal fade sigma-action-dialog sigma-modal--labs-actions" tabindex="-1" role="dialog" id="actionsDialog{{$lab->id}}">

                                <input type="hidden" name="case_id" value="{{$lab->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">External Lab Actions</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Name: </label>
                                                    <h5 id="doctor"><b>{{$lab->name}}</b></h5>
                                                </div>
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Phone: </label>
                                                    <h5 id="doctor"><b>{{$lab->phone}}</b></h5>
                                                </div>
                                            </div>

                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Address: </label>
                                                    <h5 id="doctor"><b>{{$lab->address}}</b></h5>
                                                </div>
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Status: </label>
                                                    <h5 id="doctor"><b>{{$lab->unitsMilled($from ?? -1,$to ?? -1)}}</b></h5>
                                                </div>
                                            </div>
                                            <hr>

                                        </div>
                                        <div class="modal-footer">
                                            <div class="row sigma-actions-row">
                                                <div class="col-12">
                                                    <a href="{{route('edit-lab-view', $lab->id)}}"
                                                       class="btn btn-warning">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit Lab
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
