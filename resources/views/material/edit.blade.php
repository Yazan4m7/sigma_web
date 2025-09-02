@extends('layouts.app', ['pageSlug' => 'Edit Material'])

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.bootstrap4.min.css">

<style>
    /* Minimal Professional Material Types Interface */
    .btn-outline-dark {
        border: 1px solid #333;
        color: #333;
        background: white;
        padding: 4px 12px;
        font-size: 12px;
    }

    .btn-outline-dark:hover {
        background: #333;
        border-color: #333;
        color: white;
    }
    
    /* Clean Modal - Simplified */
    .types-modal .modal-dialog {
        max-width: 700px;
    }

    .types-modal .modal-content {
        border: 1px solid #ddd;
    }

    .types-modal .modal-header {
        display: none;
    }

    .types-modal .modal-body {
        padding: 0;
        background: white;
    }

    .types-modal .modal-footer {
        padding: 12px 16px;
        background: #f8f9fa;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
    }

    .types-modal .btn-close {
        position: absolute;
        top: 8px;
        right: 8px;
        background: none;
        border: none;
        color: #666;
        font-size: 18px;
        width: 24px;
        height: 24px;
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .types-modal .btn-close:hover {
        color: #333;
    }
    
    /* Simple Table - Remove card wrapper */
    .types-modal .table-container {
        margin: 0;
        padding: 0;
        position: relative;
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

@section('content')
    <form method="POST" class="card" style="padding:20px" action="{{ route('edit-material') }}">
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
                    <input value="{{ $material->id }}" type="hidden" name="mat_id" />
                    <input class="form-control" value="{{ $material->name }}" type="text" name="mat_name" required
                        placeholder="Material Name" />
                    <span class="help-block text-muted"><small>E.g. : Zircon, E.max ..etc</small></span>
                </div>

            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Price:</label></div>
                <div class="col-md-12 col-xs-12"><input class="form-control" value="{{ $material->price }}" type="number"
                        name="price" required placeholder="Price (JOD)" />
                    <span class="help-block text-muted"><small>in JOD</small></span>
                </div>
            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Job Type:</label></div>
                <div class="col-md-12 col-xs-12">

                    <select class="select selectpicker" id="jobTypes" name="jobTypes[]" multiple required>
                        @foreach ($jobTypes as $type)
                            <option value="{{ $type->id }}" {{ in_array($type->id, $matJobTypes) ? 'selected' : '' }}>
                                {{ $type->name }}</option>
                        @endforeach
                    </select>
                    <span class="help-block text-muted"><small></small></span>
                </div>
            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Count as unit:</label></div>
                <div class="col-md-12 col-xs-12">
                    <label class="switch">
                        <input type="checkbox" name="count_as_unit" {{ $material->count_as_unit == 1 ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>

        </div>

        <div class="row" style="margin-top: 15px;">
            <div class="col-md-6 col-xs-12" style="align-self: flex-start;">
                <div class="kt-portlet__head-label">
                    <h6 class="kt-portlet__head-title">
                        <i class="fa fa-suitcase" style="width:3%"></i> Stages:
                    </h6>
                </div>
                <hr style="margin-top: 8px; margin-bottom: 15px;">
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Design:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="design">
                                <input type="checkbox" id="design" name="design" value="1"
                                    {{ $material->design ? 'checked' : '' }}>
                                <i class="fa"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Manufacturing:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="noMilling">
                                <input type="radio" id="noMilling" name="manufacturing"
                                    value="0"{{ !$material->mill ? 'checked' : '' }}>
                                <i class="fa"></i>
                                None
                            </label>
                        </div>
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="milling">
                                <input type="radio" id="milling" name="manufacturing" value="2"
                                    {{ $material->mill ? 'checked' : '' }}>
                                <i class="fa"></i>
                                Milling
                            </label>
                        </div>
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="3dPrinting">
                                <input type="radio" id="3dPrinting" name="manufacturing" value="3" required
                                    {{ $material->print_3d ? 'checked' : '' }}>
                                <i class="fa"></i>
                                3D Printing
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Furnace:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="furnace0">
                                <input type="radio" id="furnace0" name="furnace" value="0"
                                    {{ !$material->sinter_furnace ? 'checked' : '' }}>
                                <i class="fa"></i>
                                None
                            </label>
                        </div>
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="furnace1">
                                <input type="radio" id="furnace1" name="furnace" value="4"
                                    {{ $material->sinter_furnace ? 'checked' : '' }}>
                                <i class="fa"></i>
                                Sintering Furnace
                            </label>
                        </div>
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="furnace2">
                                <input type="radio" id="furnace2" name="furnace" value="5" required
                                    {{ $material->press_furnace ? 'checked' : '' }}>
                                <i class="fa"></i>
                                Press Furnace
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Finishing:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="finishing">
                                <input type="checkbox" id="finishing" name="finishing" value="6"
                                    {{ $material->finish ? 'checked' : '' }}>
                                <i class="fa"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Quality Control:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="qc">
                                <input type="checkbox" id="qc" name="qc" value="7"
                                    {{ $material->qc ? 'checked' : '' }}>
                                <i class="fa"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-6 my-1 control-label">Delivery:</label>
                    <div class="col-md-6">
                        <div class="form-check-inline my-1">
                            <label class="cr-styled" for="delivery">
                                <input type="checkbox" id="delivery" name="delivery" value="8"
                                    {{ $material->delivery ? 'checked' : '' }}>
                                <i class="fa"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xs-12 mb-3">
                <label>Material Types:</label>
                <button type="button" class="btn btn-outline-dark btn-sm" onclick="openTypesModal()">
                    <i class="fas fa-tags"></i> Manage Types
                </button>
                
                <!-- Selected Types Display -->
                <div id="selectedTypesDisplay" style="margin-top: 8px;">
                    <div class="selected-types-container">
                        <p class="text-muted text-center">Loading types...</p>
                    </div>
                </div>
                
                <div id="selectedTypesInputs">
                    @foreach($selectedTypes as $typeId)
                        <input type="hidden" name="materialTypes[]" value="{{ $typeId }}">
                    @endforeach
                </div>
            </div>
        </div>
        <br />
        <div class=" form-group ">
            <div class="form-group mb-0">
                <div>
                    <button type="submit" class="btn btn-info waves-effect waves-light">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </form>
    </div>
@endsection

<!-- Material Types Modal - Simplified -->
<div class="modal fade types-modal" id="typesModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <div class="table-container">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveSelectedTypes">
                    <i class="fas fa-save"></i> Save
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
let typesTable;
let allTypes = @json($types);
let selectedTypes = [];

// Define global function immediately
window.openTypesModal = function() {
    if (!typesTable) {
        // Initialize DataTable if not already done
        typesTable = $('#typesTable').DataTable({
            data: allTypes,
            columns: [
                {
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
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> Add New Type',
                    className: 'btn btn-success btn-sm',
                    action: function() { 
                        if (window.showAddTypeModal) window.showAddTypeModal();
                    }
                }
            ],
            pageLength: 8,
            lengthChange: false,
            searching: false,
            info: false,
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
    $('#typesModal').modal('show');
};

$(document).ready(function() {
    // Load pre-selected types from the material
    let preSelectedTypeIds = @json($selectedTypes);
    preSelectedTypeIds.forEach(typeId => {
        const type = allTypes.find(t => t.id === typeId);
        if (type) {
            selectedTypes.push(type);
        }
    });
    
    function initializeTypesTable() {
        typesTable = $('#typesTable').DataTable({
            data: allTypes,
            columns: [
                {
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
                    width: '200px',
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
            buttons: [
                {
                    text: '<i class="fas fa-plus"></i> Add New Type',
                    className: 'btn btn-success btn-sm',
                    action: function() {
                        showAddTypeModal();
                    }
                }
            ],
            pageLength: 8,
            lengthChange: false,
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
        const container = $('#selectedTypesDisplay .selected-types-container');
        
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
            updateSelectedTypesDisplay();
            // Update checkbox in table if table is initialized
            if (typesTable) {
                $(`.type-checkbox[data-id="${id}"]`).prop('checked', false);
            }
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
                    const typeIndex = allTypes.findIndex(t => t.id === id);
                    if (typeIndex > -1) {
                        allTypes[typeIndex].name = newName;
                    }
                    
                    const selectedIndex = selectedTypes.findIndex(t => t.id === id);
                    if (selectedIndex > -1) {
                        selectedTypes[selectedIndex].name = newName;
                    }
                    
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
                        allTypes = allTypes.filter(t => t.id !== id);
                        selectedTypes = selectedTypes.filter(t => t.id !== id);
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
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Add New Material Type</h5>
                            <button type="button" class="btn-close" data-dismiss="modal" style="background:none; border:none; color:white; font-size:1.5rem;">&times;</button>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        
        $('#saveNewTypeBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
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
                $('#saveNewTypeBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Type');
            }
        });
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 350px;">
                <i class="fas fa-${icon}"></i> ${message}
                <button type="button" class="btn-close" data-dismiss="alert"></button>
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
        updateSelectedTypesList();
        $('#typesModal').modal('hide');
        showAlert('success', `Selected ${selectedTypes.length} type(s) for this material`);
    });
    
    function updateSelectedTypesDisplay() {
        const inputsContainer = $('#selectedTypesInputs');
        
        // Clear existing inputs
        inputsContainer.empty();
        
        // Add hidden inputs for form submission
        selectedTypes.forEach(type => {
            inputsContainer.append(`<input type="hidden" name="materialTypes[]" value="${type.id}">`);
        });
    }
    
    // Initialize display
    updateSelectedTypesDisplay();
    updateSelectedTypesList();
});

</script>
@endpush
