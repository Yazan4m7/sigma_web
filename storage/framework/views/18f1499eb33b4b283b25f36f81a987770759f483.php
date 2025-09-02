<!-- machine-selection-dialog.blade.php -->
<?php $attributes = $attributes->exceptProps([
    'title',
    'btnText',
    'type',
    'devices',
    'stageId',
    'showBuildName' => true,
    'types' => null
]); ?>
<?php foreach (array_filter(([
    'title',
    'btnText',
    'type',
    'devices',
    'stageId',
    'showBuildName' => true,
    'types' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>
<?php $escapedType = preg_replace('/\W/', '', $type); ?>
<?php   $stageSpecs = ['milling' => ['route'=>'/set-multiple-cases','btnText'=>'NEST'],
     'sintering' => ['route'=>'/set-multiple-cases','btnText'=>"START"],
     'pressing' => ['route'=>'/set-multiple-cases','btnText'=>"SET"],
       '3dprinting' => ['route'=>'/','btnText'=>"SET"],
      ]; ?>




<div class="sigma-workflow-modal waiting-dialog animate__animated animate__bounc " id="<?php echo e($type); ?>-waiting" tabindex="-1" role="dialog">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <h2 class="sigma-workflow-title"><?php echo e($title); ?></h2>
            <button class="sigma-close-button" onclick="closeModal({id: '<?php echo e($type); ?>', isWaiting:true})">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>


        <!-- Machine selection grid -->
        <div class="sigma-workflow-body">
            <div class="sigma-machines-grid">
                <?php $__currentLoopData = $devices->where('type', $stageId); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="sigma-machine-card <?php echo e($type); ?>"
                         onclick="selectMachine(this, '<?php echo e($type); ?>', <?php echo e($device['id']); ?>)">
                        <div class="sigma-machine-image-container">
                            <img src="<?php echo e(asset($device['img'])); ?>" alt="<?php echo e($device['name']); ?>" class="sigma-machine-image">
                        </div>
                        <div class="sigma-machine-name"><?php echo e($device['name']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php
            $buildFieldName= ['milling' => 'Block', 'pressing' => 'Ring', 'delivery' => 'Assign','sintering' => 'START'  ];
                ?>

            <!-- Build name and Type selection inputs -->
            <div class="sigma-inputs-container" style="display: flex; gap: 10px; align-items: end;">
                
                <!-- Build name input -->
                <?php if($type != "sintering"): ?>
                    <div class="sigma-form-group" style="flex: 1;">
                        <label for="sigma-build-name-<?php echo e($type); ?>" class="sigma-form-label">
                            <?php echo e($buildFieldName[$type] ?? 'Build'); ?> Name
                        </label>
                        <input type="text"
                               id="sigma-build-name-<?php echo e($type); ?>"
                               class="sigma-form-control <?php echo e($stageConfig[$type]['multiple-waiting']?'multiple-choice' :'single-choice'); ?>"
                               placeholder="Enter <?php echo e($buildFieldName[$type] ?? 'Build'); ?> name"
                               oninput="validateAndSetBuildName('<?php echo e($type); ?>')">
                    </div>
                <?php else: ?>
                    <input type="hidden"
                           id="sigma-build-name-<?php echo e($type); ?>"
                           class="sigma-form-control"
                           placeholder="Enter <?php echo e($buildFieldName[$type] ?? 'Build'); ?> name"
                           oninput="validateAndSetBuildName('<?php echo e($type); ?>')">
                <?php endif; ?>

                <!-- Material Type selection for milling, pressing, sintering -->
                <?php if(in_array($type, ['milling', 'pressing', 'sintering']) && $types && $types->count() > 0): ?>
                    <div class="sigma-form-group" style="flex: 1;">
                        <label for="sigma-material-type-<?php echo e($type); ?>" class="sigma-form-label">
                            Material Type
                        </label>
                        <select id="sigma-material-type-<?php echo e($type); ?>" 
                                class="sigma-form-control"
                                onchange="validateMaterialTypeSelection('<?php echo e($type); ?>')">
                            <option value="">Select Material Type</option>
                            <?php $__currentLoopData = $types->where('is_enabled', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materialType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($materialType->id); ?>" data-material-id="<?php echo e($materialType->material_id); ?>">
                                    <?php echo e($materialType->material?->name ?? 'Unknown Material'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

        </div>


        <!-- Action button -->
        <div class="sigma-workflow-footer">
            <button type="button"
                    class="sigma-button  <?php echo e($escapedType); ?>"
                    id="sigma-action-button-<?php echo e($escapedType); ?>" style = "background-color: var(<?php echo e($type != "sintering" ? '--main-orange' :  '--main-blue'); ?>)"
                    disabled
                    onclick="submitWorkflow('<?php echo e($escapedType); ?>')">
                <?php echo e($stageSpecs[$type]['btnText']); ?>

            </button>
        </div>
    </div>
</div>

<form id="hidden-form-<?php echo e($type); ?>" method="POST" action="<?php echo e($stageSpecs[$type]['route']); ?>" class="d-none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="type" value="<?php echo e($type); ?>">
    <input type="hidden" name="deviceId" id="device-id-<?php echo e($type); ?>" value="">
    <input type="hidden" name="WaitingPopupCheckBoxes<?php echo e($type); ?>[]" id="case-ids-<?php echo e($type); ?>" value="">
    <input type="hidden" name="buildName" id="build-name-<?php echo e($type); ?>" value="">
    <input type="hidden" name="materialTypeId" id="material-type-id-<?php echo e($type); ?>" value="">
</form>


<script>

    // Function to handle material type selection
    function validateMaterialTypeSelection(type) {
        const materialTypeSelect = document.getElementById('sigma-material-type-' + type);
        const materialTypeHidden = document.getElementById('material-type-id-' + type);
        const actionButton = document.getElementById('sigma-action-button-' + type.replace(/\W/g, ''));
        
        if (materialTypeSelect && materialTypeHidden) {
            materialTypeHidden.value = materialTypeSelect.value;
        }
        
        // Update button state based on all required selections
        updateActionButtonState(type);
    }
    
    // Enhanced button state validation including material type
    function updateActionButtonState(type) {
        const deviceSelected = window.selectedMachineId;
        const buildNameInput = document.getElementById('sigma-build-name-' + type);
        const materialTypeSelect = document.getElementById('sigma-material-type-' + type);
        const actionButton = document.getElementById('sigma-action-button-' + type.replace(/\W/g, ''));
        
        let isValid = deviceSelected;
        
        // Check build name (except for sintering)
        if (type !== 'sintering' && buildNameInput) {
            isValid = isValid && buildNameInput.value.trim().length > 0;
        }
        
        // Check material type for specific stages
        if (materialTypeSelect && ['milling', 'pressing', 'sintering'].includes(type)) {
            isValid = isValid && materialTypeSelect.value.length > 0;
        }
        
        if (actionButton) {
            actionButton.disabled = !isValid;
        }
    }

    function setInnerTab(btnElement) {

        let id = btnElement.id;
        // Always use lowercase for 3dprinting
        if (id.toLowerCase().includes('3dprinting')) {
            id = id.replace(/3[dD][pP]rinting/i, '3dprinting');
        }
        Cookies.set('inner' + $(btnElement).attr('href'), id);
        console.log("set cookie for : " + 'inner' + $(btnElement).attr('href') + ' => ' + id);

        // Hide all inner tab panels for this stage
        const tablist = $(btnElement).closest('[role="tablist"]');
        const stageKey = $(btnElement).data('stageid');
        // Remove active/hidden from all panels for this stage
        $(`[aria-labelledby^='active-${stageKey}'], [aria-labelledby^='waiting-${stageKey}']`).attr('hidden', true).removeClass('active');
        // Show the selected panel
        $(`[aria-labelledby='${id}']`).removeAttr('hidden').addClass('active');

        // Update tab button states
        tablist.find('[role="tab"]').attr('aria-selected', false).attr('tabindex', -1);
        $(btnElement).attr('aria-selected', true).removeAttr('tabindex');


    }
</script>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/components/waiting-dialog.blade.php ENDPATH**/ ?>