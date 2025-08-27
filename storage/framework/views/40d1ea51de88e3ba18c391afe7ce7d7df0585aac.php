<!-- waiting-3dprinting-dialog.blade.php -->
<?php $attributes = $attributes->exceptProps([
    'title',
    'devices',
    'stageId'
]); ?>
<?php foreach (array_filter(([
    'title',
    'devices',
    'stageId'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="sig1ma-workflo1w-mo1dal waiting-dialog modal fade animate__animated animate__bounc"  id="3dprinting-waiting" tabindex="-1" role="dialog">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <h2 class="sigma-workflow-title waiting"><?php echo e($title); ?></h2>
            <button class="sigma-close-button" onclick="closeModal({id: '3dprinting', isWaiting:true})">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Machine selection grid -->
        <div class="sigma-workflow-body">
            <div class="sigma-machines-grid">
                <?php if(isset($devices) && $devices->count() > 0): ?>
                    <?php $__currentLoopData = $devices->where('type', $stageId); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sigma-machine-card 3dprinting"
                             onclick="selectMachine(this, '3dprinting', <?php echo e($device['id']); ?>)">
                            <div class="sigma-machine-image-container">
                                <img src="<?php echo e(asset(isset($device['img']) ? $device['img'] : 'images/default-device.png')); ?>"
                                     alt="<?php echo e(isset($device['name']) ? $device['name'] : 'Device'); ?>" class="sigma-machine-image">
                            </div>
                            <div class="sigma-machine-name"><?php echo e(isset($device['name']) ? $device['name'] : 'Unknown Device'); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="no-devices-message">No devices available for this stage.</div>
                <?php endif; ?>
            </div>

            <!-- Build name input (only for 3D printing) -->
            <div class="sigma-form-group">
                <label for="sigma-build-name-3dprinting">Build Name</label>
                <input type="text"
                       id="sigma-build-name-3dprinting"
                       class="sigma-form-control   <?php echo e($stageConfig['3dprinting']['multiple-waiting']?'multiple-choice' :'single-choice'); ?>"
                       placeholder="Enter Build name"

                       oninput="validateAndSetBuildName('3dprinting')">

            </div>

        </div>

        <!-- Action button -->
        <div class="sigma-workflow-footer">
            <button type="button"
                    class="sigma-button 3dprinting"
                    id="sigma-action-button-3dprinting" style = "background-color: var(--main-orange)"
                    disabled
                    onclick="submitWorkflow('3dprinting')">
                SET
            </button>

        </div>
    </div>
</div>

<form id="hidden-form-3dprinting" method="POST" action="/set-cases-on-printer" class="d-none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="type" value="3dprinting">
    <input type="hidden" name="deviceId" id="device-id-3dprinting" value="">
    <input type="hidden" name="WaitingPopupCheckBoxes3dprinting[]" id="case-ids-3dprinting" value="">
    <input type="hidden" name="buildName" id="build-name-3dprinting" value="">
</form>


<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/components/waiting-3dprinting-dialog.blade.php ENDPATH**/ ?>