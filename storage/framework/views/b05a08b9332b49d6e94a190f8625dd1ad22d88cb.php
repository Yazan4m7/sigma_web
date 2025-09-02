

<?php $__env->startSection('content'); ?>
    <head>
        <style>
            .nav-link{
                padding-left: 5px;
                padding-right: 0px;
            }
            @media  screen and (max-width: 768px){
                .main-panel .content {
                     padding-left: 0px;
                     padding-right: 0px;
                }
                table{
                    table-layout: fixed;
                }
                .deliveryDate{display:none}
            }
        </style>
    </head>
    <?php
        if(!isset($_COOKIE['deliMonitorDBTab']))
        $_COOKIE['deliMonitorDBTab']='#tab1';
    $permissions = Cache::get('user'.Auth()->user()->id);
    ?>
    <style>
        .tab-pane{padding:0}
    </style>
    <div class="row ">
    <div class="col-lg-12 col-sm-12">
        <div class="tab-2 m-b-30">
            <ul class="nav nav-tabs" style="margin-left:15px;flex-wrap: nowrap;">
                <li class="nav-item">
                    <a class="nav-link activeTabText <?php echo e($_COOKIE['deliMonitorDBTab'] == '#tab1' ? ' active show ' : ''); ?>" onclick="tabChanged(this)" href="#tab1" data-toggle="tab" aria-expanded="false">Active Cases <span class="badge bg-info m-1 activeBadge"><?php echo e($activeCases->count()); ?></span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waitingtabText <?php echo e($_COOKIE['deliMonitorDBTab'] == '#tab2' ? ' active show ' : ''); ?>" onclick="tabChanged(this)" href="#tab2" data-toggle="tab" aria-expanded="false">Waiting Cases <span class="badge  m-1 waitingBadge" style=""><?php echo e($waitingCases->count()); ?></span></a>
                </li>
            </ul>
            <div class="tab-content bg-white">
                <div class="tab-pane <?php echo e($_COOKIE['deliMonitorDBTab'] == '#tab1' ? ' active show ' : ''); ?> " id="tab1">
                    <div class="row" style="padding-top:0">
                        <div style="width:100%">

                            <form action="<?php echo e(route('receive-multiple-vouchers')); ?>" method="GET">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-primary  receiveSelectBtn" style="display:none; margin:5px;">Receive Selected</button>
                                    <table id="" class="deliveryTable compact stripe sunriseTable" style="width:100%">
                                        <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="selectAllCases" value="0" name="selectAllCases" onchange="selectAll(this)"/>
                                            </th>
                                            <th>ID</th>
                                            <th>Doctor</th>
                                            <th>Patient</th>
                                            <th>Assigned To</th>
                                            <th class="deliveryDate">Delivery Date</th>
                                            <th>Delivered</th>

                                        </tr>
                                        </thead>

                                        <tbody>
                                        <?php $__currentLoopData = $activeCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $color = $case->delivered_to_client ==1 ? "green" : "red";
                                            ?>
                                            <tr  >
                                                <td class="no-sort">
                                                    <?php if($case->delivered_to_client == 1): ?>
                                                        <input type="checkbox" class="custom-control-input multipleCB" value="<?php echo e($case->id); ?>" name="casesCheckBoxes[]" onchange="multiCBChanged()"/>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="clickable" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p  style="color:<?php echo e($color); ?> !important"><?php echo e($case->id); ?></p></td>
                                                <td class="clickable" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p class="text-primary" style="color:<?php echo e($color); ?> !important"><?php echo e($case->client ? $case->client->name : 'No Client'); ?></p></td>

                                                <td class="clickable" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e($case->patient_name); ?></p></td>
                                                <td class="clickable" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e($case->deliveryDriver() != null ? $case->deliveryDriver()->name_initials : 'NONE'); ?></p></td>
                                                <td class="clickable deliveryDate" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e(str_replace("T"," ",$case->actual_delivery_date)); ?></p></td>
                                                <td class="clickable" data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>"><p class="">
                                                        <?php if($case->delivered_to_client == 1): ?>
                                                            <span style='color:green'> YES </span>
                                                            <?php else: ?>
                                                            <span style='color:red'> NO </span>
                                                        <?php endif; ?>

                                                    </p></td>

                                            </tr>

                                            <div class="modal fade" id="actions<?php echo e($case->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelform" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Actions</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">


                                                            <div class="container">
                                                                <div class="row" style="background-color: transparent;">
                                                                    <div class="col-md-6"><strong>Doctor: </strong></div>
                                                                    <div class="col-md-6"><?php echo e($case->client->name); ?> </div>
                                                                </div>
                                                            </div>
                                                            <hr class="noMargin lightGrayTopBorder">
                                                            <div class="container">
                                                                <div class="row" style="background-color: transparent;">
                                                                    <div class="col-md-6"><strong>Patient: </strong></div>
                                                                    <div class="col-md-6"><?php echo e($case->patient_name); ?> </div>
                                                                </div>
                                                            </div>
                                                            <hr class=" lightGrayTopBorder" style="border-top: none;">
                                                            <?php if((Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 9)))&& isset($case->actual_delivery_date)): ?>
                                                                <a  href="<?php echo e(route('receive-voucher', $case->id )); ?>"><button type="button" class="btn btn-outline-secondary  btn-block">
                                                                        Receive Voucher
                                                                    </button></a>
                                                                <hr class="noMargin lightGrayTopBorder">
                                                            <?php endif; ?>
                                                            <a  href="<?php echo e(route('view-case', ['id' => $case->id,8])); ?>"><button type="button" class="btn btn-info btn-block unstyled" style="margin-top:5px">
                                                                    <i class="far fa-file-alt" ></i> View case
                                                                </button></a>


                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>

                                                        </div>
                                                        <small style="text-align:center;font-size: 60%;color: gray;">CASE ID : <?php echo e($case->id); ?></small>
                                                    </div>

                                                </div>
                                            </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>


                                </table>
                            </form>
                          </div>
                    </div>
               </div>
               <div class="tab-pane  <?php echo e($_COOKIE['deliMonitorDBTab'] == '#tab2' ? ' active show ' : ''); ?>" id="tab2">
                    <div class="row" style="padding-top:0">
                        <div class="col-lg-12 col-sm-12" style="padding-top:0">

                                    <div class=" bg-white m-b-30">
                                                <table id="" class="globalTable compact stripe sunriseTable" style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th >ID</th>
                                                        <th>Doctor</th>
                                                        <th>Patient</th>
                                                        <th>Assigned To</th>
                                                        <th>Delivery Date</th>

                                                    </tr>
                                                    </thead>

                                                    <tbody>
                                                    <?php $__currentLoopData = $waitingCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $color = $case->delivered_to_client ==1 ? "green" : "red";
                                                        ?>
                                                        <tr role="row" class="clickable " data-toggle="modal" data-target="#actions<?php echo e($case->id); ?>">
                                                            <td><p class="text-primary" style="color:<?php echo e($color); ?> !important"><?php echo e($case->id); ?></p></td>
                                                            <td><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e($case->client ? $case->client->name : 'No Client'); ?></p></td>
                                                            <td><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e($case->patient_name); ?></p></td>
                                                            <td>
                                                                <p class=""  style="color:<?php echo e($color); ?> !important"><?php echo e(($case->jobs->where('stage',8)->first() !== null && $case->jobs->where('stage',8)->first()->assignedTo !== null ) ?
                                                             $case->jobs->where('stage',8)->first()->assignedTo->name_initials : "None"); ?></p>
                                                            </td>
                                                            <td><p class="" style="color:<?php echo e($color); ?> !important"><?php echo e(str_replace("T"," ",$case->initial_delivery_date)); ?></p></td>
                                                        </tr>
                                                        <div class="modal fade" id="actions<?php echo e($case->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabelform" aria-hidden="true" >
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Actions</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">


                                                                        <div class="container">
                                                                            <div class="row">
                                                                                <div class="col-md-6"><strong>Doctor: </strong></div>
                                                                                <div class="col-md-6"><?php echo e($case->client->name); ?> </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="noMargin lightGrayTopBorder">
                                                                        <div class="container">
                                                                            <div class="row">
                                                                                <div class="col-md-6"><strong>Patient: </strong></div>
                                                                                <div class="col-md-6"><?php echo e($case->patient_name); ?> </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr class=" lightGrayTopBorder" style="">
                                                                        <br/>

                                                                        <?php if(Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 9)))): ?>
                                                                            <?php if($case->jobs[0]->assignee == null): ?>

                                                                                <a  data-dismiss="modal" data-toggle="modal" data-target="#myModal<?php echo e($case->id); ?> " >
                                                                                <button type="button" class="btn btn-warning  btn-block">
                                                                               Assign to..
                                                                                </button>
                                                                                </a>
                                                                            <?php else: ?>

                                                                                <button type="button" class="btn btn-warning btn-block" onclick="openModal('DeliveryDialog', false)">
                                                                               Re-Assign..
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>


                                                                        <a  href="<?php echo e(route('view-case', ['id' => $case->id,8])); ?>">
                                                                        <button type="button" class="btn btn-info btn-block unstyled" style="margin-top:5px;">
                                                                            <i class="far fa-file-alt" ></i> View case
                                                                        </button>
                                                                        </a>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary " data-dismiss="modal">Close</button>

                                                                        </div>
                                                                        <small style="text-align:center;font-size: 60%;color: gray;">CASE ID : <?php echo e($case->id); ?></small>
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
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
//        if ($('.multipleCB:checkbox:checked').length < 1)
//        $(".selectAllCases").attr("disabled", true);

    });
        var tables = $('.deliveryTable');
        if (tables) {
            tables.DataTable({
                "pageLength": 25,
                "searching": false,
                "lengthChange": false,
                "order": [[6, 'asc']],
                "sort": false,
                "columnDefs": [
                    {targets: [1], visible: false},
                    {
                        targets: 0, orderable: false
                    },
                    {targets: 'no-sort', orderable: false}
                ]
            });
        }




      function multiCBChanged_old() {
            console.log("Old version of Multi Select function called");
            if ($('.multipleCB:checkbox:checked').length > 0) {
                if (!$('.receiveSelectBtn').is(":visible")) {
                    $('.receiveSelectBtn').css({
                        "opacity": "0",
                        "display": "block"
                    }).show().animate({opacity: 1}, 500);
                }
            }
            else
                $('.receiveSelectBtn').css({
                    "opacity": "1",
                    "display": "block"
                }).animate({opacity: 0}, 500, function () {
                    $('.receiveSelectBtn').css({"display": "none"});
                });
        }
    function tabChanged(element) {
        var id = $(element).attr('href');
        setCookie('deliMonitorDBTab', id, 356);
    }

    function selectAll(ele){
            if ($(ele).prop('checked')) {
                $('.multipleCB').prop('checked', true);
            } else {
                $('.multipleCB').prop('checked', false);
            }
        if ($('.multipleCB:checkbox').length > 0)
        multiCBChanged();
    }
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Manage Delivery Cases'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Manage Delivery Cases'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/accountant/deliveryCases-accountant.blade.php ENDPATH**/ ?>