<?php $__env->startSection('content'); ?>
    <link href="<?php echo e(asset('assets/css/picker.css')); ?>" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- styles to carry on while printing -->
    <div id="style">
        <style>
            .sigmaPanel {
                padding-bottom:10px;
                padding-top:10px;
                margin-bottom:15px;
                margin-top:15px;
                background-color: white;
            }
            .row {
                background-color: transparent;
            }
            .no-left-top-border {
                border-top-color: transparent;
                border-top-style: solid;
                border-top-width: 1px;

                border-left-color: transparent;
                border-left-style: solid;
                border-left-width: 1px;
            }
            td, tr {
                width: fit-content;
                height: fit-content;
            }

            table {
                border-collapse: collapse;
                border-spacing: 0;
                width: 100%;
                border: 1px solid #ddd;
            }

            th, td {
                /*text-align: left;*/
                padding: 0px;
            }

            .dataRow:nth-child(even) {
                background-color: #d0d0d0
            }
            table, th, td {
                border-collapse: collapse;
                padding:4px;
            }
            td{
                border:1px solid #ddd;
                border-top: none;
                border-bottom: none;
            }
            .bottom-Border {
                border-bottom:  1px solid #ddd;
            }
            .tableHeaderRow{
                background-color: #F1F7ED;
                font-weight: 700;
            }
            .subHeaderRow{
                font-weight: 500;
                text-align:center;
                color:white;
                padding-top:5px;
                padding-botton:5px;
            }
            .totalsCol{
                color:black;background-color:#f1f7ed;border-bottom: solid 1px #ddd; padding-left:15px;padding-right:15px;text-align: center;
            }
            .totalsRow{
                color: #404040;
                text-align: left;
                border-top: 1px solid #ddd;
                font-weight: 600;
                font-size: 0.95rem;
            }
            .doctorName{
                font-weight: bold;
            }
        </style>
    </div>

    <form class="kt-form filtersPanel bd-callout bd-callout-info sigmaPanel" method="GET" action="<?php echo e(route('repeats-report')); ?>" style="/*height:30%*/">

        <!-- FILTERS -->
        <div class="container-fluid">
            <div class="row g-3" style="margin-bottom: 20px;">
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">From Date:</label>
                    <input class="form-control" type="date" name="from" 
                           value="<?php echo e(request('from', $from ?? '')); ?>" 
                           style="font-size: 14px; height: 38px;">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">To Date:</label>
                    <input class="form-control" type="date" name="to" 
                           value="<?php echo e(request('to', $to ?? '')); ?>" 
                           style="font-size: 14px; height: 38px;">
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Repeat:</label>
                    <select class="form-select selectpicker clearOnAll" multiple name="failureTypeInput[]"
                            id="failureTypeInput" data-live-search="true" title="All" data-hide-disabled="true"
                            style="font-size: 14px; height: 38px;">

                                <?php

                                        ?>
                                <?php if($allFailureTypesSelected): ?>
                                    <option value="all" selected >All</option>

                                    <option value="0"  >Reject</option>
                                    <option value="1">Repeat</option>
                                    <option value="2" >Modification</option>
                                    <option value="3">Redo</option>
                                    <option value="4" >Successful</option>

                                <?php else: ?>

                                    <option value="all">All</option>
                                    <option value="0" <?php echo e(in_array(0 , $selectedFailureTypes) ? 'selected' : ''); ?> >Reject</option>
                                    <option value="1" <?php echo e(in_array(1 , $selectedFailureTypes) ? 'selected' : ''); ?> >Repeat</option>
                                    <option value="2" <?php echo e(in_array(2 , $selectedFailureTypes) ? 'selected' : ''); ?> >Modification</option>
                                    <option value="3" <?php echo e(in_array(3 , $selectedFailureTypes) ? 'selected' : ''); ?> >Redo</option>
                                    <option value="4" <?php echo e(in_array(4 , $selectedFailureTypes) ? 'selected' : ''); ?> >Successful</option>

                                <?php endif; ?>

                        </select>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <?php if(isset($clients)): ?>
                        <label class="form-label fw-semibold">Doctor:</label>
                        <select class="form-select selectpicker clearOnAll" multiple name="doctor[]"
                                id="doctor" data-live-search="true" title="All" data-hide-disabled="true"
                                style="font-size: 14px; height: 38px;">

                                    <option value="all" <?php echo e((isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''); ?>>
                                        All
                                    </option>
                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" <?php echo e((isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </select>
                    <?php endif; ?>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Per:</label>
                    <div class="form-check form-switch" style="height: 38px; display: flex; align-items: center;">
                        <input class="form-check-input" type="checkbox" name="perToggle" <?php echo e($perUnitTrigger =="on" ?  "checked" : ""); ?> 
                               data-toggle="toggle" data-on="UNITS" data-off="CASES" data-onstyle="success" data-offstyle="info">
                        <label class="form-check-label ms-2">Units/Cases</label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Display:</label>
                    <div class="form-check form-switch" style="height: 38px; display: flex; align-items: center;">
                        <input class="form-check-input" type="checkbox" name="countOrPercentageToggle" <?php echo e($countOrPercentage ? "" : "checked"); ?> 
                               data-toggle="toggle" data-on="COUNT" data-off="PERCENTAGE" data-onstyle="success" data-offstyle="info">
                        <label class="form-check-label ms-2">Count/%</label>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-6">
                    <label class="form-label fw-semibold">Actions:</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4" style="font-size: 14px; height: 38px;">
                            <i class="fas fa-search me-1"></i>Submit
                        </button>
                        <button type="button" class="btn btn-secondary px-4 printBtn" style="font-size: 14px; height: 38px;">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <div class="sigmaPanel bg-white rounded shadow-sm p-4 mb-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        <?php echo e(isset($perUnitTrigger) ? "Repeats Report (Per Unit)" : "Repeats Report (Per Case)"); ?>

                    </h4>
                    <div class="badge bg-info fs-6">
                        <?php echo e($countOrPercentage ? 'Showing Counts' : 'Showing Percentages'); ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-sm-12">
            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        <div id="totalsTableHolder"> </div>
                        <?php $__currentLoopData = $selectedMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                              $labLevelTotal[$month]= array_fill_keys(array(0, 1, 2, 3, 4), 0);
                              $clientLevelTotal[$month]= array_fill_keys(array(0, 1, 2, 3, 4), 0);

                            ?>
                            <table border="1" class="xl649957 printable sunriseTable" style="border-collapse:collapse;">
                                <thead>
                                <!-- The Months row -->
                                <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                    <th class="" style="background-color: transparent !important;">Month:</th>
                                    <th colspan="<?php echo e(count($selectedFailureTypes)+1); ?>"
                                        style="height:21.95pt;border-top:none"><?php echo e($month); ?></th>

                                </tr>
                                </thead>


                                <tbody>



                                <!--The MAIN row -->
                                <tr class=" border-bottom tableHeaderRow">
                                    <td class="xl639957" style="height:21.95pt;border-top:none">Dr Name</td>

                                    <?php if($allFailureTypesSelected): ?>
                                        <td class="xl639957">Reject</td>
                                        <td class="xl639957">Repeat</td>
                                        <td class="xl639957">Modification</td>
                                        <td class="xl639957">Redo</td>
                                        <td class="xl639957">Successful</td>
                                        <?php if(!$countOrPercentage): ?>
                                        <td class="xl639957">All</td>
                                        <?php endif; ?>
                                    <?php else: ?>
                                       <?php if(in_array(0 , $selectedFailureTypes)): ?>
                                            <td class="xl639957">Reject</td>
                                        <?php endif; ?>
                                       <?php if(in_array(1 , $selectedFailureTypes)): ?>
                                        <td class="xl639957">Repeat</td>
                                        <?php endif; ?>
                                       <?php if(in_array(2 , $selectedFailureTypes)): ?>
                                       <td class="xl639957">Modification</td>
                                       <?php endif; ?>
                                       <?php if(in_array(3 , $selectedFailureTypes)): ?>
                                       <td class="xl639957">Redo</td>
                                       <?php endif; ?>
                                       <?php if(in_array(4 , $selectedFailureTypes)): ?>
                                       <td class="xl639957">Successful</td>
                                       <?php endif; ?>
                                       <?php if(!$countOrPercentage): ?>
                                       <td class="xl639957">All</td>
                                       <?php endif; ?>
                                    <?php endif; ?>
                                </tr>



                                <?php
                                    if(!in_array('all' ,$selectedClients))
                                     $filteredClients = $clients->filter(function ($value, $key) use ($selectedClients) {
                                    return in_array($key ,$selectedClients);
                                       });
                                    else
                                    $filteredClients = $clients;


                                ?>
                                <!-- Client ROWS -->

                                <?php $__currentLoopData = $filteredClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>


                                    <!-- if all is selected, dont check if client is selected or not, otherwise check each one by id -->
                                    
                                    
                                    
                                    
                                    

                                    <tr class="dataRow" style="">
                                        <td class="xl669957 doctorName"><?php echo e($client->name); ?></td>
                                        <?php
                                            $docTotalUnits = 0;
                                            $currentTotal = 0;
                                        ?>

                                        <?php $__currentLoopData = $selectedFailureTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $failureTypeId => $failureDescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                // Count by Units
                                                if (!$countOrPercentage){
                                                $currentTotal= isset($perUnitTrigger) ? $client->getFailedUnitsCount($month,$failureTypeId):$client->getFailedCasesCount($month,$failureTypeId);
                                                $clientLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                $labLevelTotal[$month][$failureTypeId] += $currentTotal;
                                                $docTotalUnits += $currentTotal;}
                                                // Count by Percentages
                                                else
                                                {$currentTotal= isset($perUnitTrigger) ? $client->getFailedUnitsPercentage($month,$failureTypeId) .'%':$client->getFailedCasesPercentage($month,$failureTypeId).'%';
                                                }
                                            ?>

                                            <td class="xl649957"><?php echo e($currentTotal); ?></td>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!$countOrPercentage): ?>
                                        <td style="" class="totalsCol"><b><?php echo e($docTotalUnits); ?></b></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if(!$countOrPercentage): ?>
                                <!-- Totals for whole lab Row -->
                                <tr style="">
                                    <td class="xl669957">Totals</td>

                                    <!-- if Not all types selected, then check if type exists in selected types array if so print it -->
                                    <?php $__currentLoopData = $labLevelTotal[$month]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(!$allFailureTypesSelected): ?>
                                        <?php if(in_array($key,$selectedFailureTypes)): ?>
                                        <td class="totalsRow" style=""><?php echo e($total); ?></td>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        <td class="totalsRow" style=""><?php echo e($total); ?></td>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="totalsRow" style=""><?php echo e(array_sum($labLevelTotal[$month])); ?></td>
                                </tr>
                                <?php endif; ?>
                                </tbody>

                            </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div id="totalsTableTempHolder"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script src="<?php echo e(asset('assets/js/tether.min.js')); ?>"></script>

<script>
    $(document).ready(function () {
        $(".toggle-group > *").addClass("unstyled");
        $(".toggle").addClass("unstyled");
        $(".toggle-group > label").addClass("toggleInnerBtns");
        $('input[name="perToggle"]').parent().addClass("toggleBtnGrandParent");
    });

    function printData()
    {
        var tables = $('.printable');

        var styling=document.getElementById("style");
        newWin= window.open("");
        newWin.document.write(styling.innerHTML);
        newWin.document.write('<h3 style="float:left">Cases Repeat Report <span style="color:#2b2b2b"> - by Repeat, per '+'<?php echo e($perUnitTrigger ? "Unit" : "Case"); ?>'+'</span></h3> ' +
            ' <h4 style="float:right"> Date Printed :<?php echo date("d"); ?> - <?php echo date("M"); ?> - <?php echo date("Y"); ?> </h4>');
        $.each(tables, function(key, value) {
            newWin.document.write(value.outerHTML);
        });
        newWin.print();
        newWin.close();
    }
    $('.printBtn').on('click',function(){
        printData();
    });

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => isset($perUnitTrigger) ? "Repeats Report (Per Unit)" :"Repeats Report (Per Case)" ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/reports/repeats.blade.php ENDPATH**/ ?>