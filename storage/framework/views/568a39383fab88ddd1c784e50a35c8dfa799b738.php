<div role="tablist" aria-label="Fashion Trends" style="margin-left: 1%;">
    <button href="<?php echo e($stage['numericStage']); ?>" role="tab" class="innerActiveBtn innerBtn"
            aria-selected="false" aria-controls="<?php echo e('active-'.$key); ?>"
            id="<?php echo e('active-'.$key .'label'); ?>"
            tabindex="-1" onclick="setInnerTab(this)" data-stageid="<?php echo e($key); ?>">

                                    <span
                                        class="badge bg-info m-1 activeBadge"><?php echo e(count($stage['activeCases'])); ?> </span>
        <span class="phaselabel activeTabText"> Active</span>
    </button>
    <button href="<?php echo e($stage['numericStage']); ?>" role="tab" class="innerWaitingBtn innerBtn"
            aria-selected="false" aria-controls="<?php echo e('waiting-'.$key); ?>"
            id="<?php echo e('waiting-'.$key .'label'); ?>"
            tabindex="-1" onclick="setInnerTab(this)" data-stageid="<?php echo e($key); ?>">
                                    <span
                                        class="badge bg-info m-1 waitingBadge"><?php echo e(count($stage['waitingCases'])); ?> </span>
        <span
            class="phaselabel waitingtabText"> Waiting</span></button>
</div>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/cases/dashboards-partials/tabs.blade.php ENDPATH**/ ?>