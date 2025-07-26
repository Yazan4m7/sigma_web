<?php $attributes = $attributes->exceptProps(['title', 'btnText', 'type', 'drivers', 'stageId']); ?>
<?php foreach (array_filter((['title', 'btnText', 'type', 'drivers', 'stageId']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="sigma-workflow-modal waiting-dialog" id="DeliveryDialog" tabindex="-1" role="dialog">
    <div class="sigma-workflow-dialog">
        <!-- Header with close button -->
        <div class="sigma-workflow-header">
            <h2 class="sigma-workflow-title"><?php echo e($title); ?></h2>
            <button class="sigma-close-button" onclick="closeModal({id: 'DeliveryDialog', isWaiting:false})">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- Driver selection grid -->
        <div class="sigma-workflow-body">
            <div class="sigma-drivers-grid">
                <!-- Add "ME" option -->
                <div class="sigma-driver-card"
                     onclick="selectDeliveryDriver(this, <?php echo e(auth()->user()->id); ?>)">
                    <div class="sigma-driver-image-container">
                        <img src="<?php echo e(asset('/users/me_silhouette.png')); ?>"
                             alt="Me (Self Assign)"
                             class="sigma-driver-image grayscale">
                    </div>
                    <div class="sigma-driver-name">ME</div>
                </div>

                <!-- Show all delivery drivers -->
                <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="sigma-driver-card"
                         onclick="selectDeliveryDriver(this, <?php echo e($driver->id); ?>)">
                        <div class="sigma-driver-image-container">
                            <img src="<?php echo e($driver->has_photo ? asset('/users/'.$driver->id.'/profile_picture.png') : asset('/users/no_profile_picture.png')); ?>"
                                 alt="<?php echo e($driver->first_name); ?> <?php echo e($driver->last_name); ?>"
                                 class="sigma-driver-image grayscale">
                        </div>
                        <div class="sigma-driver-name"><?php echo e($driver->name_initials ?? $driver->first_name); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>


        </div>

        <!-- Action button -->
        <div class="sigma-workflow-footer">
            <button type="button"
                    class="sigma-button "
                    id="action-button-delivery"
                    style="background-color: var(--main-orange)"
                    disabled
                    onclick="submitDeliveryAssignment()">
                <?php echo e($btnText); ?>

            </button>
        </div>
    </div>
</div>

<form id="delivery-form" method="POST" action="<?php echo e(route('assign-multiple-deliveries')); ?>" class="d-none">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="deviceId-delivery" id="driver-id-input" value="">
    <input type="hidden" name="WaitingPopupCheckBoxesdelivery" id="case-ids-input" value="">
</form>

<script>
// Helper function to close the modal properly
function closeModal(options) {
    const {id, isWaiting = false} = options;
    const modalId = id + (isWaiting ? "-waiting" : "");
    const modal = document.getElementById(modalId);

    if (!modal) {
        console.error(`Modal not found: ${modalId}`);
        return;
    }

    // Add fade-out animation
    const dialogContent = modal.querySelector('.sigma-workflow-dialog') || modal.querySelector('.modal-content');
    if (dialogContent) {
        dialogContent.classList.add('fade-out');
    }

    // Hide dialog after animation completes
    setTimeout(() => {
        modal.classList.remove('active');
        modal.style.display = 'none';
        if (dialogContent) {
            dialogContent.classList.remove('fade-out');
        }
    }, 300);
}
</script>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/components/waiting-delivery-dialog.blade.php ENDPATH**/ ?>