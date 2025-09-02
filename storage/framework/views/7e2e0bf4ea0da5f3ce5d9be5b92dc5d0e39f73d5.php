<?php $__env->startSection('content'); ?>
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
                font-weight: 700;
                background-color:#F1F7ED;
            }
            .subHeaderRow{
                font-weight: 500;
                text-align:center;

            }
            .totalsCol{
                color:black;background-color:#f1f7ed;border-bottom: solid 1px #ddd; padding-left:15px;padding-right:15px;text-align: center;
            }
            .totalsRow{
                color:#404040;
                text-align:left;
                border-top:  1px solid #ddd;
                font-weight: 600;
                font-size: 0.95rem;
            }
            .doctorName{
                font-weight: bold;
            }

        </style>
    </div>

    <form class="kt-form filtersPanel bd-callout bd-callout-info sigmaPanel" method="GET" action="<?php echo e(route('job-types-report')); ?>" style="/*height:30%*/">

        <!-- FILTERS -->
        <div class="container">
        <div class="row " style="padding-left: 0;padding-top: 0;padding-bottom: 0px">
            <div class="col-lg-2 col-md-3 col-6 mb-2">
                <div class="kt-subheader__search" style="">
                    <label>From:</label>
                    <input type="date" class="form-control" name="from" value="<?php echo e($from ?? now()->subMonth()->format('Y-m-d')); ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6 mb-2">
                <div class="kt-subheader__search" style="">
                    <label>To:</label>
                    <input type="date" class="form-control" name="to" value="<?php echo e($to ?? now()->format('Y-m-d')); ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-2">

                    <div class="dropdown">
                        <label>Job Type:</label>
                        <select style="width:100%" class="selectpicker clearOnAll" multiple name="jobTypesInput[]"
                                id="jobTypesInput" data-live-search="true" title="All" data-hide-disabled="true">

                              <?php

                              ?>
                                <?php if($allJobTypesSelected): ?>
                                <option value="all" selected >All</option>
                                    <?php $__currentLoopData = $jobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" ><?php echo e($d->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php else: ?>
                                   <?php $idsOfSelectedJobTypes = $selectedJobTypes->pluck('id')->toArray(); ?>

                                    <option value="all">All</option>
                                <?php $__currentLoopData = $jobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d->id); ?>" <?php echo e((in_array($d->id ,$idsOfSelectedJobTypes)) ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                        </select>
                    </div>

            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-2">
                <?php if(isset($clients)): ?>
                    <div class="dropdown">
                        <label>Doctor:</label>
                        <select style="width:100%" class="selectpicker clearOnAll" multiple name="doctor[]"
                                id="doctor" data-live-search="true" title="All" data-hide-disabled="true">

                                <option value="all" <?php echo e((isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''); ?>>
                                    All
                                </option>
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d->id); ?>" <?php echo e((isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-3 col-3 mb-2">
                <div class="kt-subheader__search">
                    <label> Per :</label>
                    <div class="kt-form__actions">
                        <input name="perToggle" <?php echo e($perUnitTrigger ? "" : "checked"); ?> class="unstyled" id="toggle" type="checkbox"  data-toggle="toggle" data-on="UNITS" data-off="CASES" data-onstyle="success" data-offstyle="info" >
                    </div>
                </div>
            </div>
        </div>
        <div class="row actionButtonsRow" style="padding-left: 10px;padding-top: 0;padding-bottom: 0px;    padding-right: 0;">
            <div class="col-lg-3 col-md-3 col-3" style="padding-left: 0;">
                <div class="kt-subheader__search">

                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-3 " >
                <div class="kt-subheader__search">

                    <div class="kt-form__actions">
                        <button  class="btn btn-secondary printBtn">PRINT</button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>




    <div class="sigmaPanel" style="">
        <div class="col-lg-12 col-sm-12">
            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        <div id="totalsTableHolder"> </div>
                        <?php $__currentLoopData = $selectedMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <table border="1" class="xl649957 printable sunriseTable " style="border-collapse:collapse;">
                            <thead>
                            <!-- The Months row -->
                            <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                <th   style=""> </th>
                                <th colspan="<?php echo e(count($selectedJobTypes)+1); ?>"
                                    style="height:21.95pt;border-top:none"><?php echo e($month); ?></th>

                            </tr>
                            </thead>

                            <tbody>



                            <!--The MAIN row -->
                            <tr class=" border-bottom tableHeaderRow">
                                <td class="xl639957" style="height:21.95pt;border-top:none">
                                   Doctor

                                </td>
                                    <?php $__currentLoopData = $selectedJobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <td class="xl639957" style=""><?php echo e($d->name); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="totalsCol" style="">All</td>
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

                                        <?php $__currentLoopData = $selectedJobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jobTypeObject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php

                                                $currentTotal= $perUnitTrigger ?$client->numOfCasesByJobType($jobTypeObject->id,$month): $client->numOfUnitsByJobType($jobTypeObject->id,$month) ;
                                                $docTotalUnits += $currentTotal;
                                                $clientLevelTotal[$month][$jobTypeObject->id] += $currentTotal;
                                                $labLevelTotal[$month][$jobTypeObject->id] += $currentTotal;
                                                $totals[$client->id][$jobTypeObject->id]+= $currentTotal;
                                            ?>
                                            <td class="xl649957"><?php echo e($currentTotal); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td style="" class="totalsCol"><b><?php echo e($docTotalUnits); ?></b></td>
                                        <?php  ?>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <!-- Totals for whole lab Row -->
                            <tr style="">
                                <td class="xl669957">Totals</td>

                                    <?php $__currentLoopData = $labLevelTotal[$month]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="totalsRow" style=""><?php echo e($total); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td class="totalsRow" style="text-align:center; font-weight: 900;"><?php echo e(array_sum($labLevelTotal[$month])); ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div id="totalsTableTempHolder">
                            <table border="1" class="xl649957 printable sunriseTable " style="border-collapse:collapse;">
                                <thead>
                                <!-- The Months row -->
                                <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                    <th   style=""> </th>
                                    <th colspan="<?php echo e(count($selectedJobTypes)+1); ?>"
                                        style="height:21.95pt;border-top:none">All Time</th>

                                </tr>
                                </thead>

                                <tbody>



                                <!--The MAIN row -->
                                <tr class=" border-bottom tableHeaderRow">
                                    <td class="xl639957" style="height:21.95pt;border-top:none">
                                        Doctor

                                    </td>
                                    <?php $__currentLoopData = $selectedJobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="xl639957" style=""><?php echo e($d->name); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="totalsCol" style="">All</td>
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

                                        <?php $__currentLoopData = $selectedJobTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jobTypeObject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php

                                                $currentTotal=$totals[$client->id][$jobTypeObject->id];
                                                $docTotalUnits += $currentTotal;
                                                $totals2[$jobTypeObject->id] += $currentTotal;
                                            ?>
                                            <td class="xl649957"><?php echo e($currentTotal); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td style="" class="totalsCol"><b><?php echo e($docTotalUnits); ?></b></td>
                                        <?php $totals2[99] += $docTotalUnits; ?>

                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                <!-- Totals for whole lab Row -->
                                <tr style="">
                                    <td class="xl669957">Totals</td>

                                    <?php $__currentLoopData = $totals2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="totalsRow" style=""><?php echo e($total); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </tr>
                                </tbody>
                            </table>
                        </div>
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

        $("#totalsTableHolder").html($("#totalsTableTempHolder").html());
        $("#totalsTableTempHolder").html("");
    });

    function printData()
    {
        var tables = $('.printable');

        var styling=document.getElementById("style");
        newWin= window.open("");
        newWin.document.write(styling.innerHTML);
        newWin.document.write('<h3 style="float:left">Clients Consumptions Report <span style="color:#2b2b2b"> - by Job Type, per '+'<?php echo e($perUnitTrigger ? "Unit" : "Case"); ?>'+'</span></h3> ' +
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

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Job Types Report'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/reports/jobTypes.blade.php ENDPATH**/ ?>