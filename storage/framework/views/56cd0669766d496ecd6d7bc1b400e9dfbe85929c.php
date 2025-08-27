

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
    $permissions = Cache::get('user' . Auth()->user()->id);
?>

<form class="kt-form" method="GET" action="<?php echo e(route('clients-index')); ?>">
    <div class="row">

        
        <div class="col-sm-3">
            <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>
                <label for="from">To:</label>
                <input class="form-control SDTP" name="from" type="text"
                       value="<?php echo e(old('from', $from ?? '')); ?>" required readonly />
            <?php endif; ?>
        </div>

        
        <div class="col-sm-3">
            <label for="active">Status:</label>
            <select name="active" id="active" class="form-control" onchange="this.form.submit()">
                <option value="1" <?php echo e((old('active', $status) == 1) ? 'selected' : ''); ?>>Enabled</option>
                <option value="0" <?php echo e((old('active', $status) == 0) ? 'selected' : ''); ?>>Disabled</option>
            </select>
        </div>

        
        <div class="col-sm-3">
            <label for="doctor">Doctor:</label>
            <select style="width:100%" class="selectpicker form-control clearOnAll" multiple
                    name="doctor[]" id="doctor" data-live-search="true"
                    title="All" data-hide-disabled="true">
                <option value="all"
                    <?php echo e((isset($selectedClients) && in_array('all', $selectedClients)) ? 'selected' : ''); ?>>
                    All
                </option>
                <?php $__currentLoopData = $allClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>"
                        <?php echo e((isset($selectedClients) && in_array($d->id, $selectedClients)) ? 'selected' : ''); ?>>
                        <?php echo e($d->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div class="col-sm-3">
            <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>
                <label>&nbsp;</label>
                <a href="<?php echo e(route('new-dentist-view')); ?>">
                    <button type="button" class="btn btn-secondary btn-lg btn-block">
                        <i class="fa fa-plus-circle"></i> Add Doctor
                    </button>
                </a>
            <?php endif; ?>
        </div>

        
        <div class="col-sm-3 mt-3">
            <label>&nbsp;</label>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
        </div>
    </div>

    
    <div class="row mt-3">
        <div class="col-md-3">
            <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>
                <h2 style="margin:0">
                    <span style="font-weight: bold;color:#a13030"><?php echo e(number_format($totalBalance)); ?></span>
                    <span style="font-weight: bold;font-size:18px;">JOD</span>
                </h2>
            <?php endif; ?>
        </div>

        <div class="col-md-6"></div>

        
        <div class="col-md-3">
            <?php if(Auth()->user()->is_admin): ?>
                <label>&nbsp;</label>
                <a href="<?php echo e(route('mobile-stats-configs')); ?>">
                    <button type="button" class="btn btn-secondary btn-lg btn-block">
                        Mobile Access & Stats
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>


            <hr>
                    <div class="">
                        <table class="globalTable nowrap compact stripe sunriseTable " id="my-table">
                            <thead>
                            <tr >
                                <th  style="font-weight: bold">ID</th>
                                <th  style="font-weight: bold">Name</th>
                                <th  style="font-weight: bold">Personal Phone</th>
                                <th  style="font-weight: bold">Clinic Phone</th>
                                <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>
                                <th>Balance</th>
                                <?php endif; ?>


                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr id="<?php echo e($client->id); ?>" class="odd clickable <?php echo e($client->active ? '' : 'table-secondary'); ?>" data-toggle="modal" data-target="#actionsDialog<?php echo e($client->id); ?>" style="<?php echo e($client->active ? '' : 'opacity: 0.6;'); ?>">
                                    <td>
                                        <span class="tabledit-span tabledit-identifier"><?php echo e($client->id); ?></span>
                                    </td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->name); ?> 
                                                <?php if(!$client->active): ?>
                                                    <span class="badge badge-secondary ml-1">Disabled</span>
                                                <?php endif; ?>
                                            </span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->phone); ?></span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e($client->clinic_phone); ?></span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    <?php if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin): ?>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span"><?php echo e(isset($from) ? $client->balanceAt($from) : $client->balance); ?></span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="Doe" style="display: none;" disabled=""></td>
                                        <?php endif; ?>

                                </tr>
                                <?php if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin): ?>
                                <div class="modal" tabindex="-1" role="dialog" id="myModal<?php echo e($client->id); ?>">
                                    <form action="<?php echo e(route('new-payment')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($client->id); ?>">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">New Payment balance</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4 style="color:#ff0000"><b><?php echo e($client->name); ?></b></h4>
                                                    <label>Payment amount</label>
                                                    <input type="number" class="form-control" name="amount" required>
                                                    <br/>
                                                    <label>Payment type:</label> <br/>

                                                    <input type="radio" id="cash<?php echo e($client->id); ?>"
                                                           onclick="paymentTypeChange(<?php echo e($client->id); ?>);"
                                                           name="payment_type" value="cash">
                                                    <label for="cash<?php echo e($client->id); ?>">دفعة نقدية</label><br>
                                                    <input type="radio" id="cheque<?php echo e($client->id); ?>"
                                                           onclick="paymentTypeChange(<?php echo e($client->id); ?>);"
                                                           name="payment_type" value="cheque">
                                                    <label for="cheque<?php echo e($client->id); ?>">شيك بنكي</label><br>
                                                    <input type="radio" id="transfer<?php echo e($client->id); ?>"
                                                           onclick="paymentTypeChange(<?php echo e($client->id); ?>);"
                                                           name="payment_type" value="transfer">
                                                    <label for="transfer<?php echo e($client->id); ?>">حوالة بنكية/ كليك</label><br>
                                                    <br/>
                                                    <div id="chequeDetailsInputs<?php echo e($client->id); ?>" style="display:none">
                                                        <label>Bank:</label>

                                                        <div class="kt-form__control">
                                                            <select class="form-control" id="bank" name="bank_id">
                                                                <?php $__currentLoopData = $banks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($bank->id); ?>"><?php echo e($bank->bank_name); ?></option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </div>
                                                        <br/>
                                                        <label>Cheque number:</label>
                                                        <input type="text" class="form-control" name="chequeNumber">
                                                        <br/>
                                                    </div>
                                                    <label>Extra details (Optional):</label>
                                                    <textarea name="note" class="form-control"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php endif; ?>
                                <?php if( Auth()->user()->is_admin): ?>
                                    <div class="modal" tabindex="-1" role="dialog" id="accountDiscount<?php echo e($client->id); ?>">
                                        <form action="<?php echo e(route('account-discount')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id" value="<?php echo e($client->id); ?>">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Doctor balance</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>Discount amount</label>
                                                        <input type="number" class="form-control" name="discountAmount" required>
                                                        <br/>
                                                        <label>Date of discount:  :</label>
                                                        <input type="datetime-local" name="discount_date" class="form-control"></input>
                                                        <br/>

                                                        <label>Details ( How it appears on account statement) :</label>
                                                        <input type="text" name="discount_title" class="form-control"></input>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif; ?>

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
                                                        <h5 id="pat"><b><?php echo e(isset($from) ? $client->balanceAt($from) : $client->balance); ?></b></h5>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">


                                            </div>
                                            <div class="modal-footer fullBtnsWidth" >
                                                <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">

                                                        <div class="row">
                                                            <!-------------------------
                                                                   ------ View Voucher ------
                                                                   -------------------------->
                                                            
                                                                
                                                                    
                                                                                
                                                                
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
                                                            <?php if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin): ?>
                                                                <div class="col-6 padding5px" >
                                                                    <a data-toggle="modal" data-target="#myModal<?php echo e($client->id); ?> "
                                                                       >
                                                                        <button type="button" class="btn btn-warning " data-dismiss="modal" >
                                                                            Add a payment </button></a>
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
                                                                <div class="col-6 padding5px" >
                                                                    <a data-toggle="modal" data-target="#accountDiscount<?php echo e($client->id); ?> ">
                                                                    <button type="button" class="btn btn-danger " data-dismiss="modal" >
                                                                            Create a discount </button></a>
                                                                </div>
                                                                <div class="col-6 padding5px" >
                                                                    <a href="<?php echo e(route('toggle-client-active', $client->id)); ?>" onclick="return confirm('Are you sure you want to <?php echo e($client->active ? 'disable' : 'enable'); ?> this doctor?');">
                                                                        <button type="button" class="btn <?php echo e($client->active ? 'btn-warning' : 'btn-success'); ?>">
                                                                            <?php echo e($client->active ? 'Disable' : 'Enable'); ?>

                                                                        </button>
                                                                    </a>
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
<?php $__env->startPush('js'); ?>
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
          $('.selectpicker').selectpicker('refresh');
        });

    </script>
    <script>
        function paymentTypeChange(id) {
            if (document.getElementById('cheque'.concat(id)).checked) {
                document.getElementById('chequeDetailsInputs'.concat(id)).style.display = 'block';
            }
            else document.getElementById('chequeDetailsInputs'.concat(id)).style.display = 'none';

        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/clients/index.blade.php ENDPATH**/ ?>