<!-- devices-block.blade.php -->
<?php $attributes = $attributes->exceptProps(['title', 'btnText', 'type', 'devices', 'stageId', 'showBuildName' => false, 'counts']); ?>
<?php foreach (array_filter((['title', 'btnText', 'type', 'devices', 'stageId', 'showBuildName' => false, 'counts']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php use App\Build; ?>


<div class="YSH-body">
    <div class="YSH-container">
        <div class="YSH-header"><?php echo e($title); ?></div>
        <div class="YSH-content">
            <?php if(isset($devices) && $devices->count() > 0): ?>
                <?php $__currentLoopData = $devices->where('type', $stageId); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $device): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($device && isset($device['id'])): ?>
                        <?php
                            try {
                                $builds = Build::where('printer_id', $device['id'])
                                            ->whereNull('finished_at')
                                            ->get();

                                $activeUnitsOrBuilds = $stageId==3 ?
                                    (isset($counts[$device['id']]['activeBuilds']) ? $counts[$device['id']]['activeBuilds'] : 0) :
                                    (isset($counts[$device['id']][$stageId]['active']) ? $counts[$device['id']][$stageId]['active'] : 0);

                                $waitingUnitsOrBuilds = $stageId==3 ?
                                    (isset($counts[$device['id']]['waitingBuilds']) ? $counts[$device['id']]['waitingBuilds'] : 0) :
                                    (isset($counts[$device['id']][$stageId]['waiting']) ? $counts[$device['id']][$stageId]['waiting'] : 0);

                                $hasJobs = $activeUnitsOrBuilds > 0 || $waitingUnitsOrBuilds > 0;
                                $hasActiveJobs = $activeUnitsOrBuilds > 0;
                                $hasWaitingJobs = $waitingUnitsOrBuilds > 0;
                                $isGrayScale = !$hasActiveJobs && $hasWaitingJobs;


                                \Log::info("Device {$device['id']} (" . (isset($device['name']) ? $device['name'] : 'Unknown') . ") - Type: {$type} - Active: {$activeUnitsOrBuilds}, Waiting: {$waitingUnitsOrBuilds}");
                            } catch (Exception $e) {
                                \Log::error("Error processing device: " . $e->getMessage());
                                $activeUnitsOrBuilds = 0;
                                $waitingUnitsOrBuilds = 0;
                                $hasJobs = false;
                            }
                        ?>

                        <div class="YSH-device <?php echo e($hasActiveJobs ? 'clickable' : 'inactive'); ?>"
                            onclick="<?php echo e($hasActiveJobs || $hasWaitingJobs ? "handleClick(this, '{$device['id']}', '{$type}')" : 'showNoJobsMessage()'); ?>">
                            <div class="">
                                <img class="<?php echo e(!$hasActiveJobs ? 'grayscale' : ''); ?> machine-img" alt="Some device :)"
                                    src="<?php echo e(asset(isset($device['img']) ? $device['img'] : 'devicesImages/no_device_img.PNG')); ?>" onerror="this.onerror=null; this.src='devicesImages/no_device_img.PNG';" />
                                <div class="YSH-badge-container" style="display: <?php echo e($hasJobs ? 'flex' : 'none'); ?>;">

                                    <div class="YSH-badge YSH-badge-blue" title="<?php echo e(isset($activeUnitsOrBuilds) ? $activeUnitsOrBuilds : '-'); ?> active jobs">
                                        <?php echo e(isset($activeUnitsOrBuilds) ? $activeUnitsOrBuilds : '-'); ?></div>
                                    <?php if($type!="sintering"): ?>
                                    <div class="YSH-badge YSH-badge-red" title="<?php echo e(isset($waitingUnitsOrBuilds) ? $waitingUnitsOrBuilds : '-'); ?> waiting jobs">
                                        <?php echo e(isset($waitingUnitsOrBuilds) ? $waitingUnitsOrBuilds : '-'); ?></div>
                                    <?php endif; ?>
                                </div>

                            </div>
                            <div class="YSH-device-name"><?php echo e(isset($device['name']) ? $device['name'] : 'Unknown Device'); ?> </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="no-devices">No devices available for this stage.</div>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/components/devices-block.blade.php ENDPATH**/ ?>