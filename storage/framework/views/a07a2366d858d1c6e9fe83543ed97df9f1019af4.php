

<style>
.custom-dropdown-container {
    position: relative;
    width: 100%;
}

.custom-dropdown {
    position: relative;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: white;
}

.dropdown-header {
    display: flex;
    align-items: center;
    padding: 0;
    cursor: pointer;
}

.dropdown-search {
    border: none;
    outline: none;
    padding: 8px 12px;
    width: 100%;
    font-size: 14px;
    background: transparent;
}

.dropdown-arrow {
    padding: 8px 12px;
    color: #666;
    pointer-events: none;
}

.dropdown-content {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 150px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ccc;
    border-top: none;
    z-index: 1000;
    display: none;
    border-radius: 0 0 4px 4px;
}

.dropdown-content.show {
    display: block;
}

.dropdown-item {
    padding: 6px 8px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13px;
}

.dropdown-item:hover {
    background-color: #f5f5f5;
}

.dropdown-item.selected {
    background-color: #e3f2fd;
}

.dropdown-item.add-new {
    background-color: #2196F3;
    color: white;
    font-weight: bold;
}

.dropdown-item.add-new:hover {
    background-color: #1976D2;
}

.item-actions {
    display: flex;
    gap: 5px;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 12px;
}

.edit-btn {
    color: #2196F3;
}

.edit-btn:hover {
    background-color: #e3f2fd;
}

.delete-btn {
    color: #f44336;
}

.delete-btn:hover {
    background-color: #ffebee;
}
</style>

<?php $__env->startSection('content'); ?>
    <form  method="POST" style="padding:10px" class="card"  action="<?php echo e(route('material-add-post')); ?>">
        <?php echo csrf_field(); ?>
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h6  class="kt-portlet__head-title">
                <i class="fa  fa-suitcase"  style="width:3%"></i> Material Info:
            </h6>
        </div>
    </div>
    <hr style="margin-top: 0;">
    <div class="row">

    <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
        <div class="col-md-12 col-xs-12"><label >Material Name:</label></div>
        <div class="col-md-12 col-xs-12">
            <input class="form-control" type="text" name="mat_name" required placeholder="Material Name"/>
            <span class="help-block text-muted"><small>E.g. : Zircon, E.max ..etc</small></span>
        </div>

    </div>
    <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
        <div class="col-md-12 col-xs-12"><label >Price:</label></div>
        <div class="col-md-12 col-xs-12">
            <input class="form-control" type="number" name="price" required placeholder="Price (JOD)" />
            <span class="help-block text-muted"><small></small></span>
        </div>
    </div>
    <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
            <div class="col-md-12 col-xs-12"><label >Job Type:</label></div>
            <div class="col-md-12 col-xs-12">

                <select class="select selectpicker" id="jobTypes" name="jobTypes[]" multiple required>
                    <?php $__currentLoopData = $jobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <span class="help-block text-muted"><small></small></span>
            </div>
        </div>
        <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
            <div class="col-md-12 col-xs-12"><label >Count as unit:</label></div>
            <div class="col-md-12 col-xs-12">
        <label class="switch">
            <input type="checkbox" name="count_as_unit" checked>
            <span class="slider round"></span>
        </label>
        </div>
        </div>
    </div>
    
    <div class="row" style="margin-top: 15px;">
        <div class="col-md-6 col-xs-12">
            <div class="col-md-12 col-xs-12"><label>Material Types:</label></div>
            <div class="col-md-12 col-xs-12">
                <div class="custom-dropdown-container">
                    <div class="custom-dropdown" id="materialTypesDropdown">
                        <div class="dropdown-header" id="dropdownHeader">
                            <input type="text" id="dropdownSearch" placeholder="Search or add types..." 
                                   class="dropdown-search">
                            <span class="dropdown-arrow">▼</span>
                        </div>
                        <div class="dropdown-content" id="dropdownContent">
                            <!-- Types will be loaded here -->
                        </div>
                    </div>
                    <input type="hidden" name="materialTypes[]" id="selectedTypes" value="">
                </div>
            </div>
        </div>
    </div>
    <br/>
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h6 class="kt-portlet__head-title">
                <i class="fa  fa-suitcase"  style="width:3%"></i> Stages:
            </h6>
        </div>
    </div>
    <hr style="margin-top: 0;">
    <div class="form-group row">
        <label class="col-md-2 my-1 control-label">Design:</label>
        <div class="col-md-9">
            <div class="form-check-inline my-1">
                <label class="cr-styled" for="design">
                    <input type="checkbox" id="design" name="design" value="1"  checked>
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
                        <input type="radio" id="noMilling" name="manufacturing" value="0"  >
                        <i class="fa"></i>
                        None
                    </label>
                </div>
                <div class="form-check-inline my-1">
                    <label class="cr-styled" for="milling">
                        <input type="radio" id="milling" name="manufacturing" value="2"  >
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
                    <input type="checkbox" id="finishing" name="finishing" value="6"  checked>
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
                    <input type="checkbox" id="qc" name="qc" value="7"  checked>
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
                    <input type="checkbox" id="delivery" name="delivery" value="8"  checked>
                    <i class="fa"></i>

                </label>
            </div>


        </div>
    </div>
        <br/>
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
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script type="text/javascript" src="<?php echo e(asset('assets/plugins/parsleyjs/dist/parsley.min.js')); ?>"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script type="text/javascript">
        let allTypes = <?php echo json_encode($types, 15, 512) ?>;
        
        // Add some test data if no types exist
        if (allTypes.length === 0) {
            allTypes = [
                {id: 999, name: 'High Translucency', material: {id: 1, name: 'Zirconia'}},
                {id: 998, name: 'Standard', material: {id: 1, name: 'Zirconia'}},
                {id: 997, name: 'Layered', material: {id: 2, name: 'E.max'}}
            ];
        }
        
        let filteredTypes = [...allTypes];
        let selectedTypes = [];
        let isDropdownOpen = false;

        $(document).ready(function() {
            $('form').parsley();
            
            // Debug: Check if we have types data
            console.log('Types data loaded:', allTypes.length, 'types');
            if (allTypes.length === 0) {
                console.warn('No types data found! Check controller and database.');
            }
            
            loadTypes();
            
            // Event handlers
            $('#dropdownHeader').click(toggleDropdown);
            $('#dropdownSearch').on('click', function(e) { 
                e.stopPropagation(); 
                if (!isDropdownOpen) openDropdown();
            });
            $('#dropdownSearch').on('input', function() { 
                if (!isDropdownOpen) openDropdown();
                filterTypes($(this).val()); 
            });
            $('#dropdownSearch').on('keydown', handleKeydown);
            
            // Event delegation for dynamic buttons
            $(document).on('click', '.edit-btn', function() {
                const typeId = $(this).closest('.dropdown-item').data('type-id');
                const typeName = $(this).closest('.dropdown-item').find('span').text().split(' - ')[1];
                editType(typeId, typeName);
            });
            
            $(document).on('click', '.delete-btn', function() {
                const typeId = $(this).closest('.dropdown-item').data('type-id');
                const typeName = $(this).closest('.dropdown-item').find('span').text().split(' - ')[1];
                deleteType(typeId, typeName);
            });
            
            // Close dropdown when clicking outside
            $(document).click(function(event) {
                if (!$(event.target).closest('.custom-dropdown').length) {
                    closeDropdown();
                }
            });
        });

        function toggleDropdown() {
            console.log('Toggle dropdown, currently open:', isDropdownOpen);
            if (isDropdownOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        function openDropdown() {
            console.log('Opening dropdown');
            loadTypes(); // Reload types when opening
            $('#dropdownContent').addClass('show');
            $('#dropdownSearch').focus();
            isDropdownOpen = true;
        }

        function closeDropdown() {
            console.log('Closing dropdown');
            $('#dropdownContent').removeClass('show');
            isDropdownOpen = false;
        }

        function loadTypes() {
            const searchValue = $('#dropdownSearch').val().toLowerCase();
            const content = $('#dropdownContent');
            content.empty();
            
            console.log('Loading types, search:', searchValue);
            console.log('All types:', allTypes);

            // Filter types based on search
            filteredTypes = allTypes.filter(type => 
                !searchValue || 
                type.name.toLowerCase().includes(searchValue) ||
                type.material.name.toLowerCase().includes(searchValue)
            );
            
            console.log('Filtered types:', filteredTypes);

            // Add existing types
            filteredTypes.forEach(type => {
                const isSelected = selectedTypes.some(selected => selected.id === type.id);
                const item = $(`
                    <div class="dropdown-item ${isSelected ? 'selected' : ''}" data-type-id="${type.id}">
                        <span>${type.material.name} - ${type.name}</span>
                        <div class="item-actions">
                            <button type="button" class="action-btn edit-btn">✏️</button>
                            <button type="button" class="action-btn delete-btn">🗑️</button>
                        </div>
                    </div>
                `);
                
                item.click(function(e) {
                    if (!$(e.target).hasClass('action-btn')) {
                        toggleTypeSelection(type);
                    }
                });
                
                content.append(item);
            });

            // Add "Add this type" option if no exact match and search has value
            if (searchValue && !filteredTypes.some(type => type.name.toLowerCase() === searchValue)) {
                const addItem = $(`
                    <div class="dropdown-item add-new">
                        <span>+ Add "${searchValue}"</span>
                    </div>
                `);
                
                addItem.click(function() {
                    addNewType(searchValue);
                });
                
                content.append(addItem);
            }
            
            // Show "No types found" if empty
            if (filteredTypes.length === 0 && !searchValue) {
                content.append('<div class="dropdown-item" style="color: #999;">No types available</div>');
            }
        }

        function filterTypes(searchValue) {
            loadTypes();
        }

        function handleKeydown(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const searchValue = $('#dropdownSearch').val().trim();
                if (searchValue && !filteredTypes.some(type => type.name.toLowerCase() === searchValue.toLowerCase())) {
                    addNewType(searchValue);
                }
            }
        }

        function toggleTypeSelection(type) {
            const index = selectedTypes.findIndex(selected => selected.id === type.id);
            
            if (index > -1) {
                // Remove from selection
                selectedTypes.splice(index, 1);
            } else {
                // Add to selection
                selectedTypes.push(type);
            }
            
            updateSelectedTypesInput();
            loadTypes(); // Refresh to update selected state
        }

        function updateSelectedTypesInput() {
            const values = selectedTypes.map(type => type.id);
            $('#selectedTypes').val(values.join(','));
        }

        function editType(typeId, currentName) {
            const newName = prompt('Edit type name:', currentName);
            if (newName && newName.trim() && newName.trim() !== currentName) {
                updateTypeName(typeId, newName.trim());
            }
        }

        function deleteType(typeId, typeName) {
            if (confirm(`Are you sure you want to delete "${typeName}"?`)) {
                performDeleteType(typeId);
            }
        }

        function updateTypeName(typeId, newName) {
            $.ajax({
                url: `/admin/types/${typeId}`,
                type: 'PUT',
                data: {
                    name: newName,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update local data
                        const typeIndex = allTypes.findIndex(t => t.id === typeId);
                        if (typeIndex > -1) {
                            allTypes[typeIndex].name = newName;
                        }
                        
                        // Update selected types if this type is selected
                        const selectedIndex = selectedTypes.findIndex(t => t.id === typeId);
                        if (selectedIndex > -1) {
                            selectedTypes[selectedIndex].name = newName;
                        }
                        
                        loadTypes();
                        alert('Type updated successfully!');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to update type'));
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to update type';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert('Error: ' + errorMessage);
                }
            });
        }

        function performDeleteType(typeId) {
            $.ajax({
                url: `/admin/types/${typeId}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Remove from local data
                        allTypes = allTypes.filter(t => t.id !== typeId);
                        selectedTypes = selectedTypes.filter(t => t.id !== typeId);
                        
                        updateSelectedTypesInput();
                        loadTypes();
                        alert('Type deleted successfully!');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to delete type'));
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to delete type';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert('Error: ' + errorMessage);
                }
            });
        }

        function addNewType(typeName) {
            // Get current material if we're editing, or prompt for material selection
            showAddTypeModal(typeName);
        }

        function showAddTypeModal(typeName = '') {
            const modalHtml = `
                <div class="modal fade" id="addTypeModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Material Type</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addTypeForm">
                                    <div class="form-group">
                                        <label for="newTypeName">Type Name</label>
                                        <input type="text" class="form-control" id="newTypeName" name="name" required 
                                               placeholder="e.g. High Translucency" value="${typeName}">
                                    </div>
                                    <div class="form-group">
                                        <label for="newTypeMaterial">Base Material</label>
                                        <select class="form-control" id="newTypeMaterial" name="material_id" required>
                                            <option value="">Select Material...</option>
                                            <?php
                                                $materials = \App\material::all();
                                            ?>
                                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($material->id); ?>"><?php echo e($material->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveNewTypeBtn">Save Type</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#addTypeModal').remove();
            $('body').append(modalHtml);
            $('#addTypeModal').modal('show');
            
            $('#saveNewTypeBtn').click(function() {
                saveNewType();
            });
        }

        function saveNewType() {
            const name = $('#newTypeName').val().trim();
            const materialId = $('#newTypeMaterial').val();
            
            if (!name || !materialId) {
                alert('Please fill in all fields.');
                return;
            }
            
            $('#saveNewTypeBtn').prop('disabled', true).text('Saving...');
            
            $.ajax({
                url: '<?php echo e(route("types.store")); ?>',
                type: 'POST',
                data: {
                    name: name,
                    material_id: materialId,
                    is_enabled: 1,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Add new type to local data
                        const materialName = $('#newTypeMaterial option:selected').text();
                        const newType = {
                            id: response.type.id,
                            name: response.type.name,
                            material: {
                                id: materialId,
                                name: materialName
                            }
                        };
                        
                        allTypes.push(newType);
                        selectedTypes.push(newType);
                        
                        updateSelectedTypesInput();
                        loadTypes();
                        
                        $('#addTypeModal').modal('hide');
                        $('#dropdownSearch').val('');
                        alert('Type added successfully!');
                    } else {
                        alert('Error: ' + (response.message || 'Failed to create type'));
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to create type';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    alert('Error: ' + errorMessage);
                },
                complete: function() {
                    $('#saveNewTypeBtn').prop('disabled', false).text('Save Type');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'New Material' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/material/create.blade.php ENDPATH**/ ?>