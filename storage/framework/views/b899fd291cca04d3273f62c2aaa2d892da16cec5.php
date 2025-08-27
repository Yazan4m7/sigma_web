<?php $attributes = $attributes->exceptProps(['case', 'stageType' => '3dprinting']); ?>
<?php foreach (array_filter((['case', 'stageType' => '3dprinting']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div id="YSH-slide-overlay-<?php echo e($case->id); ?>" class="YSH-slide-overlay"
     onclick="YSH_closeSlidePanel(<?php echo e($case->id); ?>)">
    <div id="YSH-slide-panel-<?php echo e($case->id); ?>" class="YSH-slide-panel">
        <div class="YSH-slide-header">
            <h5>Case Completion</h5>
            <button type="button" class="YSH-close-slide"
                    onclick="YSH_closeSlidePanel(<?php echo e($case->id); ?>)">&times;
            </button>
        </div>
        <div class="YSH-slide-grid">
            <div class="YSH-slide-body">
                <div class="form-group row" style="margin-bottom: 0px">
                    <div class="form-group col-6" style="margin-bottom: 0px">
                        <label>Doctor:</label>
                        <h5><b><?php echo e($case->client?->name); ?></b></h5>
                    </div>
                    <div class="form-group col-6" style="margin-bottom: 0px">
                        <label>Patient:</label>
                        <h5><b><?php echo e($case->patient_name); ?></b></h5>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-12">
                        <label><b>Jobs:</b></label><br>
                        <?php
                            // Convert stage type to stage number
                            $stageNumber = match($stageType) {
                                'milling' => 2,
                                '3dprinting' => 3,
                                'sintering' => 4,
                                'pressing' => 5,
                                'delivery' => 8,
                                default => 3
                            };
                        ?>
                        <?php $__currentLoopData = $case->jobs->where('stage', $stageNumber); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $unit = explode(', ',$job->unit_num);
                            ?>
                            <span>
                        <?php echo e($job->unit_num); ?> - <?php echo e($job->jobType->name ?? "No Job Type"); ?> - <?php echo e($job->material->name ?? "no material"); ?><?php echo e(isset($job->subType) && $job->subType->name ? " (" . $job->subType->name . ")" : ""); ?>

                                <?php echo e($job->color == '0' ? "" : " - " . $job->color); ?>

                                <?php echo e($job->style == 'None' ? "" : " - " . $job->style); ?>

                                <?php echo e(isset($job->implantR) && $job->jobType->id == 6 ? (" - Implant Type: " . $job->implantR->name) : ""); ?>

                                <?php echo e(isset($job->abutmentR) && $job->jobType->id == 6 ? (" Abutment Type: " . $job->abutmentR->name) : ""); ?>

                        <br>
                    </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <?php if(count($case->notes) > 0): ?>
                    <hr>
                    <label><b>Notes:</b></label><br>
                    <?php $__currentLoopData = $case->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-control"
                             style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px">
                                                                    <span
                                                                        class="noteHeader"><?php echo e('[' . substr($note->created_at,0,16) . '] [' . $note->writtenBy->name_initials . '] :'); ?></span><br>
                            <span class="noteText"><?php echo e($note->note); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </div>
            <div class="modal-footer fullBtnsWidth">
                <div class="row btnsRow"
                     style=" margin-right: 0px; margin-left: 0px;width:100%">
                    <div class="col-md-6 col-sm-12 padding5px">
                        <a href="<?php echo e(route('view-case', ['id' => $case->id, 'stage' => 3  ])); ?>">
                            <button type="button" class="btn btn-info "><i
                                    class="fas fa-eye"></i> View
                            </button>
                        </a>
                    </div>

                    <?php
                        $permissions = Cache::get('user'.Auth()->user()->id);
                        $canEditCase = false;
                        if(Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))))
                        $canEditCase = true;
                    ?>
                    <div class="col-md-6 col-sm-12 padding5px"><a
                            href="<?php echo e(route('edit-case-view',$case->id)); ?>">
                            <button type="button"
                                    class="btn btn-warning " <?php echo e($canEditCase ? '' : 'disabled'); ?>>
                                <i class="fas fa-edit"></i> Edit Case
                            </button>
                        </a></div>

                    <div class="col-12 padding5px">
                        <button type="button" class="btn btn-secondary "
                                data-dismiss="modal" style="width:100%">
                            Cancel
                        </button>
                    </div>
                </div>


            </div>

        </div>

    </div>
</div>

<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/components/partiels/caseSlidePanel.blade.php ENDPATH**/ ?>