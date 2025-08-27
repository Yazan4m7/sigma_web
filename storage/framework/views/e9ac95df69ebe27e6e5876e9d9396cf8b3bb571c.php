
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class=" m-b-30">
                <div class="">
                    <div class="row">
                        <div class="col-md-6">   </div>
                        <div class="col-md-6" style="text-align: right">  <a href="<?php echo e(route('material-add')); ?>" ><button type="button"  class="btn btn-secondary"><i class="fa fa-plus-circle" ></i> Add Material</button></a>   </div>
                    </div>

                    <p class="text-muted"></p>
                    <div class="">
                        <table class="table-striped table-bordered compact sunriseTable" id="my-table" style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>

                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr id="<?php echo e($material->id); ?>" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog<?php echo e($material->id); ?>">
                                <td><span class="tabledit-span tabledit-identifier"><?php echo e($material->id); ?></span><input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="1" disabled=""></td>
                                <td class="tabledit-view-mode"><span class="tabledit-span"><?php echo e($material->name); ?></span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="John" style="display: none;" disabled=""></td>
                                <td class="tabledit-view-mode"><span class="tabledit-span"><?php echo e($material->price); ?></span><input class="tabledit-input form-control input-sm" type="text" name="col1" value="Doe" style="display: none;" disabled=""></td>

                               </tr>
                            <div class="modal" tabindex="-1" role="dialog" id="actionsDialog<?php echo e($material->id); ?>">

                                <input type="hidden" name="case_id" value="<?php echo e($material->id); ?>">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Material Actions</h5>

                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="form-group row" style="margin-bottom: 0px">
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="doctor">Name: </label>
                                                    <h5 id="doctor"><b><?php echo e($material->name); ?></b></h5>
                                                </div>
                                                <div class="form-group col-6 " style="margin-bottom: 0px">
                                                    <label for="pat">Price: </label>
                                                    <h5 id="pat"><b><?php echo e($material->price); ?></b></h5>
                                                </div>
                                            </div>
                                            <hr>

                                        </div>
                                        <div class="modal-footer fullBtnsWidth" >
                                            <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">


                                                    <div class="row">
                                                    <!-------------------------
                                                     -------- Edit CASE --------
                                                     -------------------------->
                                                                <div class="col-12 padding5px" >
                                                                    <a  href="<?php echo e(route('edit-material-view', $material->id)); ?>">
                                                                        <button type="button" class="btn btn-warning "><i class="fa-solid fa-pen-to-square"></i> Edit Material</button>
                                                                    </a></div>
                                                    </div>

                                                <div class="col-12 padding5px" >
                                                    <button type="button" class="btn btn-secondary " data-dismiss="modal" style="width:100%">Cancel</button>
                                                </div>
                                            </div>


                                        </div>



                                    </div>
                                </div>

                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Materials List' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/material/index.blade.php ENDPATH**/ ?>