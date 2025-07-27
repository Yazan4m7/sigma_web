

<?php $__env->startSection('content'); ?>
<style>
.dropdown-toggle::after {
    display: inline-block !important;
}
    .dropdown-menu{
        color:inherit;
    }
.modal-footer{
    padding: 0 !important;
}
@media  screen and (max-width: 768px) {
    table {
        table-layout: fixed;
    }
}
</style>
    <?php
        $permissions = Cache::get('user'.Auth()->user()->id);
    ?>
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <form class="kt-form" method="GET" action="<?php echo e(route('mobile-stats-configs')); ?>">
            <div class="row">

                <div class="col-4">
                    <label>Doctor:</label>
                    <select style="width:100%" class="selectpicker form-control clearOnAll" multiple
                            name="doctor[]" id="doctor"  data-live-search="true"
                            title="All" data-hide-disabled="true">
                        <option value="all" <?php echo e((isset($selectedClients) && in_array('all',$selectedClients) ? 'selected' : '')); ?>>All</option>
                        <?php $__currentLoopData = $allClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($d->id); ?>" <?php echo e((isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-4">
                    <label> &nbsp; </label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                </div>
            </div>

            </form>


            <hr>
                    <div class="">
                        <table class=" nowrap compact stripe sunriseTable " id="my-table">
                            <thead>
                            <tr >

                                <th  style="font-weight: bold">Name</th>
                                
                                
                                <th  style="font-weight: bold">Doc. 30 Days Access</th>
                                <th  style="font-weight: bold">Doc. Last sign-in</th>
                                <th  style="font-weight: bold">Doc. Device</th>
                                <th  style="font-weight: bold">Clinic. 30 Days Access</th>
                                <th  style="font-weight: bold">Clinic. Last sign-in</th>
                                <th  style="font-weight: bold">Clinic. Device</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="<?php echo e($client->id); ?>" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog<?php echo e($client->id); ?>">

                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->name); ?></span></td>
                                    
                                                
                                    
                                                

                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->loginInLast30Days()); ?></span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e(substr($client->lastSignIn(),0,16)); ?></span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->docDevice()); ?></span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->clinicLoginInLast30Days()); ?></span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e(substr($client->clinicLastSignIn(),0,16)); ?></span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->clinicDevice()); ?></span></td>

                                </tr>



                                <div class="modal" tabindex="-1" role="dialog" id="actionsDialog<?php echo e($client->id); ?>">

                                    <input type="hidden" name="case_id" value="<?php echo e($client->id); ?>">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Doctor Account</h5>

                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-group row" style="margin-bottom: 0px">
                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                        <label for="doctor">Doctor: </label>
                                                        <h5 id="doctor"><b><?php echo e($client->name); ?></b></h5>
                                                    </div>
                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                        <label for="pat">Balance: </label>
                                                        <h5 id="pat"><b><?php echo e($client->balance); ?></b></h5>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">


                                            </div>
                                            <div class="modal-footer fullBtnsWidth" >
                                                <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">

                                                        <div class="row">

                                                            <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>

                                                                <div class="col-6 padding5px" >
                                                                    <a href="<?php echo e(route('client-statement-admin', $client->id)); ?>">
                                                                        <button type="button" class="btn btn-primary ">
                                                                            Account Statement</button></a>
                                                                </div>

                                                                <div class="col-6 padding5px" >
                                                                    <a href="<?php echo e(route('client-view-edit',['id' =>$client->id])); ?>">
                                                                        <button type="button" class="btn btn-danger ">
                                                                            Edit Record</button></a>
                                                                </div>

                                                            <?php endif; ?>

                                                            <?php if( Auth()->user()->is_admin): ?>
                                                                <div class="col-6 padding5px" >
                                                                <a href="<?php echo e(route('dentist-cases',['id' =>$client->id])); ?>">
                                                                    <button type="button" class="btn btn-info ">
                                                                    View Cases </button></a>
                                                                </div>
                                                                <div class="col-6 padding5px" >
                                                                    <a  href="<?php echo e(route('dentist-invoices',['id' =>$client->id])); ?>">
                                                                    <button type="button" class="btn btn-info ">
                                                                        View Invoices </button></a>
                                                                </div>
                                                                <div class="col-6 padding5px" >
                                                                    <a href="<?php echo e(route('dentist-payments',['id' =>$client->id])); ?>">
                                                                        <button type="button" class="btn btn-info ">
                                                                            View Payments </button></a>
                                                                </div>

                                                            <?php endif; ?>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/admin/mobile-access-stats.blade.php ENDPATH**/ ?>