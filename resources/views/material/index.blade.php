@extends('layouts.app', ['pageSlug' => 'Materials List'])
@section('content')
    <div class="sigma-config-page sigma-list-page">
        <div class="sigma-config-page-actions">
            <a href="{{ route('material-add') }}" class="btn btn-secondary sigma-config-add-btn">
                <i class="fa fa-plus-circle"></i> Add Material
            </a>
        </div>

        <div class="sigma-config-table-shell sigma-table-free">
            <table class="table-striped table-bordered compact sunriseTable sigma-list-table sigma-config-table" id="my-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="sigma-cell-center">ID</th>
                                    <th class="sigma-cell-left">Name</th>
                                    <th class="sigma-cell-center">Price</th>

                            </thead>
                            <tbody>
                                @foreach ($materials as $material)
                                    <tr id="{{ $material->id }}" class="odd clickable" data-toggle="modal"
                                        data-target="#actionsDialog{{ $material->id }}">
                                        <td class="sigma-cell-center"><span class="tabledit-span tabledit-identifier">{{ $material->id }}</span><input
                                                class="tabledit-input tabledit-identifier" type="hidden" name="id"
                                                value="1" disabled=""></td>
                                        <td class="tabledit-view-mode sigma-cell-left"><span
                                                class="tabledit-span">{{ $material->name }}</span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                        <td class="tabledit-view-mode sigma-cell-center"><span
                                                class="tabledit-span">{{ $material->price }}</span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="Doe" style="display: none;" disabled=""></td>

                                    </tr>
                                    <div class="modal sigma-action-dialog sigma-modal--material-actions" tabindex="-1" role="dialog" id="actionsDialog{{ $material->id }}">

                                        <input type="hidden" name="case_id" value="{{ $material->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Material Actions</h5>

                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group row mb-0">
                                                        <div class="form-group col-6 mb-0">
                                                            <label for="doctor">Name: </label>
                                                            <h5 id="doctor">{{ $material->name }}</h5>
                                                        </div>
                                                        <div class="form-group col-6 mb-0">
                                                            <label for="pat">Price: </label>
                                                            <h5 id="pat">{{ $material->price }}</h5>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                </div>
                                                <div class="modal-footer">
                                                    <div class="row sigma-actions-row">
                                                        <div class="col-12">
                                                            <a href="{{ route('edit-material-view', $material->id) }}"
                                                                class="btn btn-warning">
                                                                <i class="fa-solid fa-pen-to-square"></i> Edit Material
                                                            </a>
                                                        </div>
                                                        <div class="col-12">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Cancel</button>
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
