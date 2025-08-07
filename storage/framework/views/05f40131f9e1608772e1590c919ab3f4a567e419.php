
<div class="sidebar">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,200,1,0" />

    <?php
        $permissions = Cache::get('user'.Auth()->user()->id);
    ?>
    <div class="sidebar-wrapper">

        <ul class="nav">

                <?php if(($permissions && $permissions->contains('permission_id', 123)) || Auth()->user()->is_admin): ?>
                    <div class="homePageOptionInSideBar" style="padding:0;">

                        <li class="<?php echo e(Route::currentRouteName() == 'home' ? 'active' : ''); ?>" >
                                                            <a style="
                                    margin-right: 0px;
                                    padding-right: 0px !important;
                                     padding-left: 0px !important;
                                " href="<?php echo e(route('home')); ?>">
                                <i class="fa-solid fa-house"></i>
                                <span>Home Screen</span>
                            </a>
                            <hr style="border-color:#b4b4b4;margin-top: 0.5rem;margin-bottom: 0.5rem;">
                    </div>

                <?php endif; ?>
                    <?php if(($permissions && $permissions->contains('permission_id', 106)) || Auth()->user()->is_admin): ?>
                        <div class="" style="padding:0" >

                             <li class="<?php echo e(Route::currentRouteName() == 'admin-dashboard-v2' ? 'active' : ''); ?>" >
                                <a href="<?php echo e(route('admin-dashboard-v2')); ?>" style=" margin-right: 0px;">
                                    <span class="material-symbols-outlined googleIconInSideBar">
                                    dashboard
                                    </span>
                                    <span>OPERATIONS DASHBOARD</span>
                                </a>


                            
                            
                            
                        </div>

                        <hr style="border-color:#b4b4b4;margin-top: 0.5rem;margin-bottom: 0.5rem;">
                    <?php endif; ?>
            <?php if(($permissions && $permissions->contains('permission_id', 100)) || Auth()->user()->is_admin): ?>
                <li class="<?php echo e(Route::currentRouteName() == 'new-case-view' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('new-case-view')); ?>"><i class="fa fa-plus-square"></i> <span>Create Case</span></a>
                </li>
            <?php endif; ?>



                <?php if(($permissions && $permissions->contains('permission_id', 103)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'cases-index' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('cases-index')); ?>"><i class="fa fa-suitcase"></i> <span>Cases</span></a>
                    </li>
                <?php endif; ?>

                <?php if(($permissions && $permissions->contains('permission_id', 105)) ): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'receivable-payments-index' ? 'active' : ''); ?>" ><a href="<?php echo e(route('receivable-payments-index')); ?>">
                            <i class="fa fa-money" aria-hidden="true"></i> <span>Collect Payments</span></a>
                <?php endif; ?>

                <?php if(($permissions && $permissions->contains('permission_id', 109)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'delivery-schedule' ? 'active' : ''); ?>"><a href="<?php echo e(route('delivery-schedule')); ?>"> <i class="fa-regular fa-clock"></i> <span>Delivery Schedule</span></a>
                <?php endif; ?>
                <?php if(($permissions && $permissions->contains('permission_id', 9)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'deli-cases-accountant-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('deli-cases-accountant-index')); ?>"><i class="fa fa-car" aria-hidden="true"></i>Delivery Monitor</a></li>
                <?php endif; ?>
                <?php if(($permissions && $permissions->contains('permission_id', 125)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'abutments-delivery-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('abutments-delivery-index')); ?>"><i class="fa-solid fa-bullseye"></i>Abutments Delivery</a></li>
                <?php endif; ?>
                <?php if(($permissions && $permissions->contains('permission_id', 113)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'view-cases-monitor' ? 'active' : ''); ?>"><a href="<?php echo e(route('view-cases-monitor')); ?>"><i class="fa-solid fa-table-cells-large"></i>Cases Monitor</a></li>
                <?php endif; ?>
                <?php if(($permissions && $permissions->contains('permission_id', 133)) || Auth()->user()->is_admin): ?>
                    <li class="<?php echo e(Route::currentRouteName() == 'devices-page' ? 'active' : ''); ?>"><a href="<?php echo e(route('devices-page')); ?>"><i class="fa-solid fa-desktop"></i>Devices Monitor</a></li>
                <?php endif; ?>
            <?php if(($permissions && ($permissions->contains('permission_id', 107))) || Auth()->user()->is_admin): ?>
                <li class="<?php echo e(Route::currentRouteName() == 'clients-index' ? 'active' : ''); ?>" ><a href="<?php echo e(route('clients-index')); ?>"><i class="fa fa-user-md"></i> <span>Doctors</span></a>
            <?php endif; ?>
                    <?php if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin): ?>
                        <li class="<?php echo e(Route::currentRouteName() == 'my-collections' ? 'active' : ''); ?>"><a href="<?php echo e(route('my-collections')); ?>"> <i class="fa-solid fa-circle-dollar-to-slot"></i> <span>My Collections</span></a>
                    <?php endif; ?>

                <?php if(($permissions && $permissions->contains('permission_id', 124)) || Auth()->user()->is_admin): ?>
                        <li class="<?php echo e(Route::currentRouteName() == 'rejected-cases' ? 'active' : ''); ?>" ><a href="<?php echo e(route('rejected-cases')); ?>"><i class="fa fa-times "></i> <span>Rejected Cases</span></a>
                    <?php endif; ?>


            <?php if(($permissions && $permissions->contains('permission_id', 120)) || Auth()->user()->is_admin): ?>
                <?php
                    $reportsExpanded = in_array(Route::currentRouteName(),
                                array('num-of-units-report',
                                 'job-types-report',
                                 'QC-report',
                                 'repeats-report',
                                 'implants-report'))
                                ? 'true' : 'false';
                ?>

                <li >
                    <a data-toggle="collapse" href="#laravel-examples"
                       aria-expanded="<?php echo e($reportsExpanded); ?>" >
                        <i class="fab fa-laravel" ></i>
                        <span class="nav-link-text"><?php echo e(__('Reports')); ?></span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse<?php echo e($reportsExpanded == 'true' ? 'show' : ''); ?>" id="laravel-examples">
                        <ul class="nav pl-4">
                            <li class="<?php echo e(Route::currentRouteName() == 'num-of-units-report' ? 'active' : ''); ?>" >
                                <a href="<?php echo e(route('num-of-units-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p><?php echo e(('Number Of Units')); ?></p>
                                </a>
                            </li>
                            <li class="<?php echo e(Route::currentRouteName() == 'job-types-report' ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('job-types-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p><?php echo e(('Job Types')); ?></p>
                                </a>
                            </li>
                            <li class="<?php echo e(Route::currentRouteName() == 'QC-report' ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('QC-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p><?php echo e(__('QC')); ?></p>
                                </a>
                            </li>
                            <li class="<?php echo e(Route::currentRouteName() == 'repeats-report' ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('repeats-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p><?php echo e(('Repeats')); ?></p>
                                </a>
                            </li>
                            <li class="<?php echo e(Route::currentRouteName() == 'implants-report' ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('implants-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p><?php echo e(('Implants')); ?></p>
                                </a>
                            </li>
                            <li class="<?php echo e(Route::currentRouteName() == 'materials-report' ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('materials-report')); ?>">
                                    <i class="tim-icons icon-bullet-list-67"></i>
                                    <p>Materials Report</p>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            <?php endif; ?>
            <?php if($permissions && $permissions->contains('permission_id', 104) ||$permissions && $permissions->contains('permission_id', 121) ||$permissions && $permissions->contains('permission_id', 111) || Auth()->user()->is_admin): ?>
                <?php
                    $accountancyExpanded  = in_array(Route::currentRouteName(),
                                array('invoices-index',
                                 'payments-index',
                                 'clients-index4payment','payments-with-collectors'));
                ?>
                 <li>
                <a data-toggle="collapse" href="#accountancyList"
                   aria-expanded="<?php echo e($accountancyExpanded); ?>" >
                    <i class="fa-solid fa-dollar-sign"></i> <span class="nav-link-text">Accountancy</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse <?php echo e($accountancyExpanded == 'true' ? 'show' : ''); ?>" id="accountancyList">
                    <ul class="nav pl-4">
                        <?php if(($permissions && $permissions->contains('permission_id', 104)) || Auth()->user()->is_admin): ?>
                            <li class="<?php echo e(Route::currentRouteName() == 'payments-with-collectors' ? 'active' : ''); ?>" >
                                <a href="<?php echo e(route('payments-with-collectors')); ?>"><i class="fa-solid fa-money-bill-transfer"></i> <span>Receive Payments</span></a>

                        <?php endif; ?>
                        <?php if(($permissions && $permissions->contains('permission_id', 104)) || Auth()->user()->is_admin): ?>
                            <li class="<?php echo e(Route::currentRouteName() == 'invoices-index' ? 'active' : ''); ?>" >
                                <a href="<?php echo e(route('invoices-index')); ?>"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Invoices</span></a>

                        <?php endif; ?>
                        <?php if(($permissions && $permissions->contains('permission_id', 121)) || Auth()->user()->is_admin): ?>
                            <li class="<?php echo e(Route::currentRouteName() == 'payments-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('payments-index')); ?>"><i class="fa fa-credit-card"></i> <span>Payments</span></a>
                        <?php endif; ?>
                        <?php if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin): ?>
                                <li class="<?php echo e(Route::currentRouteName() == 'clients-index4payment' ? 'active' : ''); ?>" ><a href="<?php echo e(route('clients-index4payment')); ?>"><i class="fa fa-user-md"></i> <span>Take A Payment</span></a>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
                <?php endif; ?>
                <?php if(Auth()->user()->is_admin): ?>
                <?php
                    $configExpanded  = in_array(Route::currentRouteName(),
                                array('material-index',
                                 'job-type-index',
                                 'users-index',
                                 'labs-index',
                                 'implants-index',
                                 'abutments-index',
                                 'tags-index',
                                 'f-causes-index',
                                 'devices-index',
                                 'sys-config',
                                 'media-index'))
                                ? 'true' : 'false';
                ?>

                <li>
                    <a data-toggle="collapse" href="#configList"
                       aria-expanded="<?php echo e($configExpanded); ?>" >
                        <i class="fa-solid fa-gear" ></i>
                        <span class="nav-link-text">Configuration</span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse <?php echo e($configExpanded == 'true' ? 'show' : ''); ?>" id="configList">
                    <ul class="nav pl-4">
                    <li class="<?php echo e(Route::currentRouteName() == ' media-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('media-index')); ?>"><i class="fa-solid fa-video"></i> <span>Gallery Media</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'material-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('material-index')); ?>"><i class="fa fa-cubes"></i> <span>Materials</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'job-type-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('job-type-index')); ?>"><i class="fa fa-object-group" aria-hidden="true"></i> <span>Job Types</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'users-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('users-index')); ?>"><i class="fa fa-users"></i> <span>Users</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'labs-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('labs-index')); ?>"><i class="fa fa-building"></i> <span>External Labs</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'implants-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('implants-index')); ?>"><i class="fa-solid fa-tooth"></i> <span>Implants</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'abutments-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('abutments-index')); ?>"><i class="fa-brands fa-connectdevelop"></i><span>Abutments</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'tags-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('tags-index')); ?>"><i class="fa fa-tag"></i><span>Tags</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'f-causes-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('f-causes-index')); ?>"><i class="fa-solid fa-repeat"></i><span>Failure Causes</span></a>
                    <li class="<?php echo e(Route::currentRouteName() == 'devices-index' ? 'active' : ''); ?>"><a href="<?php echo e(route('devices-index')); ?>"><i class="fa-solid fa-tachograph-digital"></i><span>Devices</span></a>
                    

                    </ul>
                    </div>
                </li>

           <?php endif; ?>

        </ul>
    </div>
</div>


<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/layouts/navbars/leftsidebar.blade.php ENDPATH**/ ?>