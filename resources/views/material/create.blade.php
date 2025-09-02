@extends('layouts.app', ['pageSlug' => 'New Material'])
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap4.min.css">

<style>
    /* Minimal Professional Material Types Interface */
    .types-display-box {
        border: 1px solid #ddd;
        background: #fff;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        font-size: 14px;
        color: #333;
    }

    .types-display-box:hover {
        border-color: #999;
    }

    .manage-types-btn {
        background: #333;
        color: white;
        border: none;
        padding: 4px 10px;
        font-size: 12px;
        cursor: pointer;
    }

    .manage-types-btn:hover {
        background: #555;
    }

    /* Clean Modal */
    .types-modal .modal-dialog {
        max-width: 800px;
    }

    .types-modal .modal-content {
        border: 1px solid #ddd;
    }

    .types-modal .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 12px 16px;
    }

    .types-modal .modal-title {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .types-modal .btn-close {
        background: none;
        border: none;
        color: #666;
        font-size: 16px;
        padding: 0;
        width: 20px;
        height: 20px;
    }

    .types-modal .modal-body {
        padding: 16px;
        background: white;
    }

    .types-modal .modal-footer {
        padding: 12px 16px;
        background: #f8f9fa;
        border-top: 1px solid #ddd;
    }

    /* Simple Table */
    .types-modal .card {
        border: 1px solid #ddd;
        margin-bottom: 16px;
    }

    .types-modal .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 10px 12px;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .types-modal .card-body {
        padding: 0;
    }

    #typesTable_wrapper .dataTables_filter,
    #typesTable_wrapper .dataTables_info,
    #typesTable_wrapper .dataTables_length {
        display: none;
    }

    #typesTable_wrapper .dt-buttons {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }

    #typesTable_wrapper .btn {
        background: #333;
        color: white;
        border: none;
        padding: 3px 8px;
        font-size: 11px;
        margin: 0;
    }

    #typesTable {
        font-size: 13px;
        margin: 0;
    }

    #typesTable thead th {
        background: #f8f9fa;
        color: #333;
        font-weight: 600;
        font-size: 12px;
        border: none;
        border-bottom: 1px solid #ddd;
        padding: 6px 10px;
        text-align: left;
    }

    #typesTable tbody tr:hover {
        background: #f8f9fa;
    }

    #typesTable tbody td {
        padding: 6px 10px;
        border: none;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }

    #typesTable tbody td input[type="checkbox"] {
        width: 14px;
        height: 14px;
        margin: 0;
    }

    #typesTable tbody td .btn {
        background: none;
        border: 1px solid #ddd;
        color: #666;
        padding: 2px 5px;
        font-size: 10px;
        margin-right: 3px;
        width: 22px;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    #typesTable tbody td .btn:hover {
        border-color: #999;
        color: #333;
    }

    #typesTable tbody td .btn-danger:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* Selected Types Panel */
    .selected-types-container {
        min-height: 120px;
        max-height: 200px;
        overflow-y: auto;
        padding: 8px;
        border: 1px solid #eee;
        background: #fafafa;
        font-size: 13px;
    }

    .selected-type-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        border: 1px solid #ddd;
        padding: 6px 6px 6px 8px;
        margin-bottom: 4px;
        font-size: 13px;
        word-wrap: break-word;
        overflow: hidden;
    }

    .selected-type-item span {
        flex: 1;
        margin-right: 6px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .remove-selected {
        background: none;
        border: 1px solid #ddd;
        color: #666;
        width: 16px;
        height: 16px;
        font-size: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        margin-left: auto;
    }

    .remove-selected:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }

    /* Simple Buttons */
    .btn {
        font-size: 13px;
        padding: 6px 12px;
        font-weight: 500;
    }

    .btn-secondary {
        background: #f8f9fa;
        border: 1px solid #ddd;
        color: #333;
    }

    .btn-secondary:hover {
        background: #e9ecef;
        color: #333;
    }

    .btn-success {
        background: #333;
        border-color: #333;
    }

    .btn-success:hover {
        background: #555;
        border-color: #555;
    }
</style>
<style>
    .option-div {
        display: flex;
        align-items: center;
    }

    .option-div .btn {
        margin-left: auto;
    }

    .option-div .btn-group {
        display: flex;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #ffffff;
        background-clip: border-box;
        border: 0.0625rem solid rgba(34, 42, 66, 0.05);
        border-radius: 0.2857rem;
        height: -webkit-fill-available;
    }

    .card .card-header {
        padding: 13px 16px !important;
    }
</style>


@section('content')
    <form method="POST" style="padding:10px" class="card" action="{{ route('material-add-post') }}">
        @csrf
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h6 class="kt-portlet__head-title">
                    <i class="fa  fa-suitcase" style="width:3%"></i> Material Info:
                </h6>
            </div>
        </div>
        <hr style="margin-top: 0;">
        <div class="row">

            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Material Name:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control" type="text" name="mat_name" required placeholder="Material Name" />
                    <span class="help-block text-muted"><small>E.g. : Zircon, E.max ..etc</small></span>
                </div>

            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Price:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control" type="number" name="price" required placeholder="Price (JOD)" />
                    <span class="help-block text-muted"><small></small></span>
                </div>
            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Job Type:</label></div>
                <div class="col-md-12 col-xs-12">

                    <select class="select selectpicker" id="jobTypes" name="jobTypes[]" multiple required>
                        @foreach ($jobTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <span class="help-block text-muted"><small></small></span>
                </div>
            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Count as unit:</label></div>
                <div class="col-md-12 col-xs-12">
                    <label class="switch">
                        <input type="checkbox" name="count_as_unit" checked>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 15px;">
            <div class="kt-portlet__head col-6 ">
                <div class="kt-portlet__head-label">
                    <h6 class="kt-portlet__head-title">
                        <i class="fa  fa-suitcase" style="width:3%"></i> Stages:
                    </h6>
                </div>
            </div>
            <div class="col-6 col-sm-6 col-md-3 mb-3">


                <label>Material Types:</label>
                <div class="types-display-box" onclick="openTypesModal()">
                    <span class="selected-types-text" id="selectedTypesDisplay">No types selected - Click to manage
                        types</span>
                    <button type="button" class="manage-types-btn">Manage Types</button>
                </div>
                <div id="selectedTypesInputs">
                    <!-- Hidden inputs for selected types will be added here -->
                </div>

            </div>
        </div>


        <br />

        <hr style="margin-top: 0;">
        <div class="form-group row">
            <label class="col-md-6 my-1 control-label">Design:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="design">
                        <input type="checkbox" id="design" name="design" value="1" checked>
                        <i class="fa"></i>

                    </label>
                </div>


            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 my-1 control-label">Manufacturing:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="noMilling">
                        <input type="radio" id="noMilling" name="manufacturing" value="0">
                        <i class="fa"></i>
                        None
                    </label>
                </div>
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="milling">
                        <input type="radio" id="milling" name="manufacturing" value="2">
                        <i class="fa"></i>
                        Milling
                    </label>
                </div>
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="3dPrinting">
                        <input type="radio" id="3dPrinting" name="manufacturing" value="3" required>
                        <i class="fa"></i>
                        3D Printing
                    </label>
                </div>

            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 my-1 control-label">Furnace:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="furnace0">
                        <input type="radio" id="furnace0" name="furnace" value="0">
                        <i class="fa"></i>
                        None
                    </label>
                </div>
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="furnace1">
                        <input type="radio" id="furnace1" name="furnace" value="4">
                        <i class="fa"></i>
                        Sintering Furnace
                    </label>
                </div>
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="furnace2">
                        <input type="radio" id="furnace2" name="furnace" value="5" required>
                        <i class="fa"></i>
                        Press Furnace
                    </label>
                </div>

            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 my-1 control-label">Finishing:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="finishing">
                        <input type="checkbox" id="finishing" name="finishing" value="6" checked>
                        <i class="fa"></i>

                    </label>
                </div>


            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 my-1 control-label">Quality Control:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="qc">
                        <input type="checkbox" id="qc" name="qc" value="7" checked>
                        <i class="fa"></i>

                    </label>
                </div>


            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-2 my-1 control-label">Delivery:</label>
            <div class="col-md-9">
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="delivery">
                        <input type="checkbox" id="delivery" name="delivery" value="8" checked>
                        <i class="fa"></i>

                    </label>
                </div>


            </div>
        </div>
        <br />
        <div class=" form-group ">
            <div class="form-group ">
                <div>
                    <button type="submit" class="btn btn-primary ">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary " style="margin:0 !important;">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        </div>
    </form>
@endsection

<!-- Material Types Modal -->
<div class="modal fade types-modal" id="typesModal" tabindex="-1" role="dialog" aria-labelledby="typesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typesModalLabel">
                    <i class="fas fa-tags"></i> Manage Material Types
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-list"></i> Available Types</h6>
                            </div>
                            <div class="card-body">
                                <table id="typesTable" class="table table-striped table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be loaded via DataTable -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-white">
                                <h6 class="mb-0"><i class="fas fa-check-circle"></i> Selected Types</h6>
                            </div>
                            <div class="card-body" style="height: 97.5%;">
                                <div id="selectedTypesList" class="selected-types-container">
                                    <p class="text-muted text-center">No types selected</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveSelectedTypes">
                    <i class="fas fa-save"></i> Save Selection
                </button>
            </div>
        </div>
    </div>
</div>

@push('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>

    <script>
        // Global variables
        var typesTable;
        var allTypes = @json($types);
        var selectedTypes = [];

        // Define global function immediately - make sure it's accessible
        window.openTypesModal = function() {
            console.log('openTypesModal called'); // Debug log
            if (!typesTable) {
                // Initialize DataTable if not already done
                typesTable = $('#typesTable').DataTable({
                    data: allTypes,
                    columns: [{
                            data: null,
                            orderable: false,
                            className: 'text-center',
                            width: '60px',
                            render: function(data, type, row) {
                                const isSelected = selectedTypes.some(t => t.id === row.id);
                                return `<input type="checkbox" class="type-checkbox" data-id="${row.id}" ${isSelected ? 'checked' : ''}>`;
                            }
                        },
                        {
                            data: 'name',
                            width: '200px'
                        },
                        {
                            data: null,
                            orderable: false,
                            className: 'text-center',
                            width: '100px',
                            render: function(data, type, row) {
                                return `
                            <button class="btn btn-sm btn-primary edit-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                            }
                        }
                    ],
                    dom: 'Brtip',
                    buttons: [{
                        text: '<i class="fas fa-plus"></i> Add New Type',
                        className: 'btn btn-success btn-sm',
                        action: function() {
                            // Will be defined later in document ready
                            if (window.showAddTypeModal) window.showAddTypeModal();
                        }
                    }],
                    paging: false,
                    searching: false,
                    info: false,
                    sorting: false,
                    responsive: true,
                    autoWidth: false
                });

                // Set up event handlers after table initialization
                $('#typesTable tbody').on('change', '.type-checkbox', function() {
                    const id = parseInt($(this).data('id'));
                    const type = allTypes.find(t => t.id === id);

                    if (this.checked) {
                        if (!selectedTypes.some(t => t.id === id)) {
                            selectedTypes.push(type);
                        }
                    } else {
                        selectedTypes = selectedTypes.filter(t => t.id !== id);
                    }

                    if (window.updateSelectedTypesList) window.updateSelectedTypesList();
                });
            }
            try {
                $('#typesModal').modal('show');
            } catch (e) {
                console.error('Error opening modal:', e);
            }
        };

        // Debug log to confirm function is defined
        console.log('openTypesModal function defined:', typeof window.openTypesModal);

        // Also define it directly on window with a simpler approach as a backup
        if (!window.openTypesModal) {
            window.openTypesModal = function() {
                console.log('Fallback openTypesModal called');
                $('#typesModal').modal('show');
            };
        }

        $(document).ready(function() {

            function initializeTypesTable() {
                typesTable = $('#typesTable').DataTable({
                    data: allTypes,
                    columns: [{
                            data: null,
                            orderable: false,
                            className: 'text-center',
                            width: '40px',
                            render: function(data, type, row) {
                                const isSelected = selectedTypes.some(t => t.id === row.id);
                                return `<input type="checkbox" class="type-checkbox" data-id="${row.id}" ${isSelected ? 'checked' : ''}>`;
                            }
                        },
                        {
                            data: 'name',
                            width: '100px',
                            render: function(data, type, row) {
                                return `<span class="editable-name" data-id="${row.id}">${data}</span>`;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            className: 'text-center',
                            width: '100px',
                            render: function(data, type, row) {
                                return `
                            <button class="btn btn-sm btn-primary edit-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-type" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-trash"></i>
                            </button>
                        `;
                            }
                        }
                    ],
                    dom: 'Brtip',
                    buttons: [{
                        text: '<i class="fas fa-plus"></i> Add New Type',
                        className: 'btn btn-success btn-sm',
                        action: function() {
                            showAddTypeModal();
                        }
                    }],
                    sorting: false,
                    paging: false,
                    searching: false,
                    info: false,
                    responsive: true,
                    autoWidth: false,
                    language: {
                        emptyTable: "No types available"
                    }
                });

                // Handle checkbox changes
                $('#typesTable tbody').on('change', '.type-checkbox', function() {
                    const id = parseInt($(this).data('id'));
                    const type = allTypes.find(t => t.id === id);

                    if (this.checked) {
                        if (!selectedTypes.some(t => t.id === id)) {
                            selectedTypes.push(type);
                        }
                    } else {
                        selectedTypes = selectedTypes.filter(t => t.id !== id);
                    }

                    updateSelectedTypesList();
                });

                // Handle edit buttons
                $('#typesTable tbody').on('click', '.edit-type', function() {
                    const id = parseInt($(this).data('id'));
                    const name = $(this).data('name');
                    editType(id, name);
                });

                // Handle delete buttons
                $('#typesTable tbody').on('click', '.delete-type', function() {
                    const id = parseInt($(this).data('id'));
                    const name = $(this).data('name');
                    deleteType(id, name);
                });
            }

            function updateSelectedTypesList() {
                window.updateSelectedTypesList = updateSelectedTypesList;
                const container = $('#selectedTypesList');

                if (selectedTypes.length === 0) {
                    container.html('<p class="text-muted text-center">No types selected</p>');
                    return;
                }

                let html = '';
                selectedTypes.forEach(type => {
                    html += `
                <div class="selected-type-item">
                    <span>${type.name}</span>
                    <button class="remove-selected" data-id="${type.id}">×</button>
                </div>
            `;
                });

                container.html(html);

                // Handle remove buttons
                container.find('.remove-selected').click(function() {
                    const id = parseInt($(this).data('id'));
                    selectedTypes = selectedTypes.filter(t => t.id !== id);
                    updateSelectedTypesList();
                    // Update checkbox in table
                    $(`.type-checkbox[data-id="${id}"]`).prop('checked', false);
                });
            }

            function editType(id, currentName) {
                const newName = prompt('Enter new type name:', currentName);
                if (newName && newName.trim() && newName.trim() !== currentName) {
                    updateTypeName(id, newName.trim());
                }
            }

            function updateTypeName(id, newName) {
                $.ajax({
                    url: `/admin/types/${id}`,
                    type: 'PUT',
                    data: {
                        name: newName,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update local data
                            const typeIndex = allTypes.findIndex(t => t.id === id);
                            if (typeIndex > -1) {
                                allTypes[typeIndex].name = newName;
                            }

                            // Update selected types if this type is selected
                            const selectedIndex = selectedTypes.findIndex(t => t.id === id);
                            if (selectedIndex > -1) {
                                selectedTypes[selectedIndex].name = newName;
                            }

                            // Refresh table and selected list
                            typesTable.clear().rows.add(allTypes).draw();
                            updateSelectedTypesList();

                            showAlert('success', 'Type updated successfully!');
                        } else {
                            showAlert('error', response.message || 'Failed to update type');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to update type';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('error', errorMessage);
                    }
                });
            }

            function deleteType(id, name) {
                if (confirm(`Are you sure you want to delete "${name}"?`)) {
                    $.ajax({
                        url: `/admin/types/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                // Remove from local data
                                allTypes = allTypes.filter(t => t.id !== id);
                                selectedTypes = selectedTypes.filter(t => t.id !== id);

                                // Refresh table and selected list
                                typesTable.clear().rows.add(allTypes).draw();
                                updateSelectedTypesList();

                                showAlert('success', 'Type deleted successfully!');
                            } else {
                                showAlert('error', response.message || 'Failed to delete type');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete type';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            showAlert('error', errorMessage);
                        }
                    });
                }
            }

            function showAddTypeModal() {
                window.showAddTypeModal = showAddTypeModal;
                const modalHtml = `
            <div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-white">
                            <h5 class="modal-title">Add New Material Type</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" style="background:none; border:none; color:white; font-size:1.5rem;">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form id="addTypeForm">
                                <div class="form-group mb-3">
                                    <label for="newTypeName">Type Name *</label>
                                    <input type="text" class="form-control" id="newTypeName" required placeholder="e.g. High Translucency">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success" id="saveNewTypeBtn">
                                <i class="fas fa-save"></i> Save Type
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('#addTypeModal').remove();
                $('body').append(modalHtml);

                const modal = new bootstrap.Modal(document.getElementById('addTypeModal'));
                modal.show();

                $('#saveNewTypeBtn').click(function() {
                    saveNewType();
                });
            }

            function saveNewType() {
                const name = $('#newTypeName').val().trim();

                if (!name) {
                    showAlert('error', 'Please enter a type name.');
                    return;
                }

                $('#saveNewTypeBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Saving...');

                $.ajax({
                    url: '{{ route('types.store') }}',
                    type: 'POST',
                    data: {
                        name: name,
                        is_enabled: 1,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            const newType = {
                                id: response.type.id,
                                name: response.type.name
                            };

                            allTypes.push(newType);
                            selectedTypes.push(newType);

                            // Refresh table and selected list
                            typesTable.clear().rows.add(allTypes).draw();
                            updateSelectedTypesList();

                            $('#addTypeModal').modal('hide');
                            showAlert('success', 'Type added successfully!');
                        } else {
                            showAlert('error', response.message || 'Failed to create type');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to create type';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showAlert('error', errorMessage);
                    },
                    complete: function() {
                        $('#saveNewTypeBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Save Type');
                    }
                });
            }

            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';

                const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">
                <i class="fas fa-${icon}"></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

                $('body').append(alert);
                setTimeout(() => alert.fadeOut(() => alert.remove()), 4000);
            }

            // Global functions
            window.openTypesModal = function() {
                if (!typesTable) {
                    initializeTypesTable();
                }
                $('#typesModal').modal('show');
            };

            // Save selected types
            $('#saveSelectedTypes').click(function() {
                updateSelectedTypesDisplay();
                $('#typesModal').modal('hide');
                showAlert('success', `Selected ${selectedTypes.length} type(s) for this material`);
            });

            function updateSelectedTypesDisplay() {
                const display = $('#selectedTypesDisplay');
                const inputsContainer = $('#selectedTypesInputs');

                // Clear existing inputs
                inputsContainer.empty();

                if (selectedTypes.length === 0) {
                    display.text('No types selected - Click to manage types');
                } else {
                    display.text(
                        `${selectedTypes.length} type(s) selected: ${selectedTypes.map(t => t.name).join(', ')}`
                    );

                    // Add hidden inputs for form submission
                    selectedTypes.forEach(type => {
                        inputsContainer.append(
                            `<input type="hidden" name="materialTypes[]" value="${type.id}">`);
                    });
                }
            }

            // Initialize display
            updateSelectedTypesDisplay();

            // Material Implants Functions (keep existing functionality)
            function renderImplantsInterface() {
                const container = $('#materialImplantsContainer');

                const html = `
            <div class="implants-interface">
                <div class="implants-header">
                    <h6 class="implants-title">
                        <i class="fas fa-tooth" style="margin-right: 5px;"></i>
                        Compatible Implants (${materialImplantsData.length})
                    </h6>
                    <button type="button" class="add-implant-btn" onclick="showAddImplantModal()">
                        <i class="fas fa-plus"></i>
                        Add Implant
                    </button>
                </div>
                <div class="implants-list" id="implantsList">
                    ${renderImplantsList()}
                </div>
            </div>
        `;

                container.html(html);
                updateImplantsFormInputs();
            }

            function renderImplantsList() {
                if (materialImplantsData.length === 0) {
                    return '<div class="empty-implants">No implants added yet. Click "Add Implant" to get started.</div>';
                }

                return materialImplantsData.map((implant, index) => `
            <div class="implant-card compatibility-${implant.compatibility_level}" data-index="${index}">
                <div class="implant-header">
                    <span class="implant-name">${implant.implant_name}</span>
                    <div class="implant-actions">
                        <button type="button" class="implant-action-btn implant-edit-btn"
                                onclick="editImplantRelation(${index})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="implant-action-btn implant-delete-btn"
                                onclick="removeImplantRelation(${index})">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
                <div class="implant-compatibility">
                    <span class="compatibility-label">Compatibility:</span>
                    <span class="compatibility-badge ${implant.compatibility_level}">
                        ${getCompatibilityLabel(implant.compatibility_level)}
                    </span>
                </div>
                ${implant.notes ? `<div class="implant-notes">${implant.notes}</div>` : ''}
            </div>
        `).join('');
            }

            function getCompatibilityLabel(level) {
                switch (level) {
                    case 'highly_compatible':
                        return 'Highly Compatible';
                    case 'compatible':
                        return 'Compatible';
                    case 'not_recommended':
                        return 'Not Recommended';
                    default:
                        return 'Compatible';
                }
            }

            function updateImplantsFormInputs() {
                $('input[name^="materialImplants["]').remove();
                materialImplantsData.forEach((implant, index) => {
                    $(`<input type="hidden" name="materialImplants[${index}][implant_id]" value="${implant.implant_id}">`)
                        .appendTo('#materialImplantsContainer');
                    $(`<input type="hidden" name="materialImplants[${index}][compatibility_level]" value="${implant.compatibility_level}">`)
                        .appendTo('#materialImplantsContainer');
                    $(`<input type="hidden" name="materialImplants[${index}][notes]" value="${implant.notes}">`)
                        .appendTo('#materialImplantsContainer');
                });
            }

            // Global implant functions
            window.showAddImplantModal = function() {
                const availableImplants = allImplants.filter(implant =>
                    !materialImplantsData.find(mi => mi.implant_id == implant.id)
                );

                if (availableImplants.length === 0) {
                    showAlert('error', 'All available implants have been added to this material.');
                    return;
                }

                const modalHtml = `
            <div class="modal fade" id="addImplantModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Add Implant Compatibility</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" style="background:none; border:none; color:white; font-size:1.5rem;">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="newImplantSelect">Select Implant *</label>
                                <select class="form-control" id="newImplantSelect" required>
                                    <option value="">Choose an implant...</option>
                                    ${availableImplants.map(implant =>
                                        `<option value="${implant.id}">${implant.name}</option>`
                                    ).join('')}
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Compatibility Level *</label>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="compatibility_level" value="compatible" checked>
                                        <label class="form-check-label">
                                            <strong>Compatible</strong> - Standard compatibility with good results
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="compatibility_level" value="highly_compatible">
                                        <label class="form-check-label">
                                            <strong>Highly Compatible</strong> - Excellent compatibility with optimal results
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="compatibility_level" value="not_recommended">
                                        <label class="form-check-label">
                                            <strong>Not Recommended</strong> - Limited compatibility, use with caution
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="implantNotes">Notes (Optional)</label>
                                <textarea class="form-control" id="implantNotes" rows="3" placeholder="Any specific notes about compatibility, preparation requirements, etc."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="saveImplantRelation()">
                                <i class="fas fa-save"></i> Add Implant
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('#addImplantModal').remove();
                $('body').append(modalHtml);
                $('#addImplantModal').modal('show');
            };

            window.saveImplantRelation = function() {
                const implantId = $('#newImplantSelect').val();
                const compatibility = $('input[name="compatibility_level"]:checked').val();
                const notes = $('#implantNotes').val().trim();

                if (!implantId) {
                    showAlert('error', 'Please select an implant.');
                    return;
                }

                const implant = allImplants.find(i => i.id == implantId);
                if (!implant) {
                    showAlert('error', 'Selected implant not found.');
                    return;
                }

                materialImplantsData.push({
                    implant_id: parseInt(implantId),
                    implant_name: implant.name,
                    compatibility_level: compatibility,
                    notes: notes
                });

                renderImplantsInterface();
                $('#addImplantModal').modal('hide');
                showAlert('success',
                    `Added ${implant.name} with ${getCompatibilityLabel(compatibility)} compatibility.`);
            };

            window.editImplantRelation = function(index) {
                const implantData = materialImplantsData[index];

                const modalHtml = `
            <div class="modal fade" id="editImplantModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title">Edit ${implantData.implant_name} Compatibility</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Compatibility Level *</label>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_compatibility_level" value="compatible" ${implantData.compatibility_level === 'compatible' ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            <strong>Compatible</strong> - Standard compatibility with good results
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_compatibility_level" value="highly_compatible" ${implantData.compatibility_level === 'highly_compatible' ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            <strong>Highly Compatible</strong> - Excellent compatibility with optimal results
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="edit_compatibility_level" value="not_recommended" ${implantData.compatibility_level === 'not_recommended' ? 'checked' : ''}>
                                        <label class="form-check-label">
                                            <strong>Not Recommended</strong> - Limited compatibility, use with caution
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="editImplantNotes">Notes (Optional)</label>
                                <textarea class="form-control" id="editImplantNotes" rows="3" placeholder="Any specific notes about compatibility, preparation requirements, etc.">${implantData.notes || ''}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-warning" onclick="updateImplantRelation(${index})">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

                $('#editImplantModal').remove();
                $('body').append(modalHtml);
                $('#editImplantModal').modal('show');
            };

            window.updateImplantRelation = function(index) {
                const compatibility = $('input[name="edit_compatibility_level"]:checked').val();
                const notes = $('#editImplantNotes').val().trim();

                materialImplantsData[index].compatibility_level = compatibility;
                materialImplantsData[index].notes = notes;

                renderImplantsInterface();
                $('#editImplantModal').modal('hide');
                showAlert('success', `Updated ${materialImplantsData[index].implant_name} compatibility.`);
            };

            window.removeImplantRelation = function(index) {
                const implantName = materialImplantsData[index].implant_name;

                if (confirm(`Are you sure you want to remove ${implantName} from this material?`)) {
                    materialImplantsData.splice(index, 1);
                    renderImplantsInterface();
                    showAlert('success', `Removed ${implantName} from material.`);
                }
            };
        });
    </script>
@endpush
