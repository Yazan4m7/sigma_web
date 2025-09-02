<?php $__env->startSection('content'); ?>
    <link href="<?php echo e(asset('assets/css/picker.css')); ?>" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <!-- styles to carry on while printing -->
    <div id="style">
        <style>
            footer{display:none}
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
                /*background-color: #8e8e8e;*/
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

    <form class="kt-form filtersPanel bd-callout bd-callout-info sigmaPanel" method="GET" action="<?php echo e(route('QC-report')); ?>" style="/*height:30%*/">


        <!-- FILTERS -->
        <div class="container">
            <div class="row " style="padding-left: 0;padding-top: 0;padding-bottom: 0px">
                <div class="col-lg-3 col-md-3 col-6 mb-2">
                    <div class="kt-subheader__search" style="">
                        <label>Date Range:</label>
                        <input class="form-control dateRange" name="dateRange" autocomplete="off" readonly
                               value="<?php echo e($dateRangeValue ?? "Select Period"); ?>" style="cursor: pointer;">
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-6 mb-2">

                    <div class="dropdown">
                        <label>Failure Cause:</label>
                        <select style="width:100%" class="selectpicker clearOnAll" multiple name="causesInput[]"
                                id="causesInput" data-live-search="true" title="All" data-hide-disabled="true">


                                <?php if($allCausesSelected): ?>
                                    <option value="all" selected >All</option>
                                    <?php $__currentLoopData = $allFailureCauses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" ><?php echo e($d->text); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php else: ?>
                                    <?php $idsOfSelectedCauses = $selectedFailureCauses->pluck('id')->toArray(); ?>
                                    <option value="all">All</option>
                                    <?php $__currentLoopData = $allFailureCauses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($d->id); ?>" <?php echo e(in_array($d->id ,$idsOfSelectedCauses) ? 'selected' : ''); ?>><?php echo e($d->text); ?></option>
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
                <div class="col-lg-3 col-md-3 col-6 mb-2">

                    <div class="dropdown">
                        <label>Type of failure:</label>
                        <select style="width:100%" class="selectpicker clearOnAll" multiple name="failureTypeInput[]"
                                id="failureTypeInput" data-live-search="true" title="All" data-hide-disabled="true">

                                <option value="all" <?php echo e(in_array("all" , $typesSelected) ? 'selected' : ''); ?>>All</option>
                                <option value="0" <?php echo e(in_array(0 , $typesSelected) ? 'selected' : ''); ?> >Reject</option>
                                <option value="1" <?php echo e(in_array(1 , $typesSelected) ? 'selected' : ''); ?> >Repeat</option>
                                <option value="2" <?php echo e(in_array(2 , $typesSelected) ? 'selected' : ''); ?> >Modification</option>
                                <option value="3" <?php echo e(in_array(3 , $typesSelected) ? 'selected' : ''); ?> >Redo</option>

                        </select>
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
        <div class="row" >
            <div class="col-lg-12 col-sm-12  row" style="flex-direction: row;padding-bottom:0px">
                
                    
                        
                        
                        
                    
                
                <div class="col-lg-3 col-md-3 col-3 mb-3">
                    <div class= "vertical">
                        <span style="font-weight: bold;font-size:15px;">Total cases:</span><br>
                        <span style="font-weight:bold;font-size:19px; color:#3b8b45"><?php echo e(array_sum($amountOfCases)); ?></span>
                        <span style="font-size:13px;color:#3b8b45">Cases</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-3 mb-3">
                    <div class= "vertical">
                        <span style="font-weight: bold;font-size:15px;">Total units:</span><br>

                        <!-- Filled via Jquery because of the calculation is after the display(below span) -->
                        <span id="numOfUnitsFailed" style="font-weight: bold;font-size:19px;color:red"> </span>

                        <span style="font-size:13px;color:red">Units</span>
                    </div>
                </div>
        </div>
        <div class="col-lg-12 col-sm-12">

            <div class=" ">
                <div class="">
                    <p class="text-muted"></p>
                    <div class="" style="overflow-x:auto;">
                        <?php

                        $failuresDesc = [0 => "Rejection",1 => "Repeat", 2 => "Modification" , 3=> "Redo"];
                        $counterTest = 0;
                        $monthHasNoFailures = false;
                        ?>

                        <?php $__currentLoopData = $selectedMonths; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($monthHasNoFailures = false); ?>

                            <?php if ($amountOfCases[$month] == 0)
                            $monthHasNoFailures = true;
                            ?>
                            <table border="1" class="xl649957 printable sunriseTable" style="border-collapse:collapse;">
                                <thead>
                                <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                    <th class="" style="background-color: transparent !important;">Month:</th>
                                    <th colspan="5"
                                        style="height:21.95pt;border-top:none"><?php echo e($month); ?> <?php echo e($amountOfCases != 0 ? '('.$amountOfCases[$month] . ") Cases" : ""); ?></th>

                                </tr>
                                </thead>


                                <tbody>
                                <!-- The Months row -->

                                <?php if($monthHasNoFailures): ?>
                                    <tr  style="text-align: center;color:forestgreen"> <td colspan="2" class="xl639957" style="height:21.95pt;border-top:none">No Incidents</td></tr>
                                    <?php continue; ?>

                                <?php endif; ?>

                                <!--The MAIN row -->
                                <tr class=" border-bottom tableHeaderRow">
                                    <td class="xl639957" style="height:21.95pt;border-top:none">Dr Name</td>
                                    <td class="xl639957" style="">Patient</td>
                                    <td class="xl639957" style="">Status</td>
                                    <td class="xl639957" style="">Causes</td>
                                    <td class="xl639957" style=""># of Units</td>
                                    <td class="xl639957" style="">Date Failure Registered</td>
                                </tr>
                                <!-- Client ROWS -->

                                <?php $__currentLoopData = $failureLogs[$month]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $failLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!in_array('all' ,$selectedClients)): ?>
                                        <?php if(isset($selectedClients) && !in_array($failLog->case->client->id ,$selectedClients)): ?>
                                            <?php continue; ?>;
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <!-- if all is selected, dont check if client is selected or not, otherwise check each one by id -->
                                    
                                    
                                    
                                    
                                    

                                    <tr class="dataRow" style="">
                                        <td class="xl669957 doctorName"><?php echo e($failLog->case->client->name ?? "Case Not found"); ?></td>
                                        <td class="xl669957"><?php echo e($failLog->case->patient_name ?? "Case Not found"); ?></td>
                                        <td class="xl669957"><?php echo e($failuresDesc[$failLog->failure_type]); ?></td>
                                        <td class="xl669957"><?php echo e($failLog->causeObject->text); ?></td>

                                        <td class="xl669957">
                                            <?php
                                             if(isset($failLog->case) ){
                                                $numOfUnits= $failLog->case->failedUnitsAmount($failLog->failure_type);
                                                $counterTest= $counterTest + $failLog->case->failedUnitsAmount($failLog->failure_type);}
                                            else
                                                $numOfUnits = "Case Not found";

                                            ?>

                                                <?php echo e($numOfUnits); ?>

                                        </td>
                                        <td class="xl669957"><?php echo e(substr($failLog->created_at,0,-3)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                <!-- Totals for whole lab Row -->
                                
                                    

                                    
                                        
                                    
                                    
                                
                                </tbody>
                            </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        $("#numOfUnitsFailed").html(<?php echo $counterTest; ?>);

        console.log("Amount of units : " );
            console.log(<?php echo $counterTest; ?>);
    });

    function printData()
    {
        //        var table = $("#table1"),
        //            tableWidth = table.outerWidth(),
        //            pageWidth = 600,
        //            pageCount = Math.ceil(tableWidth / pageWidth),
        //            printWrap = $("<div></div>").insertAfter(table),
        //            i,
        //            printPage;
        //        for (i = 0; i < pageCount; i++) {
        //            printPage = $("<div></div>").css({
        //                "overflow": "hidden",
        //                "width": pageWidth,
        //                "page-break-before": i === 0 ? "auto" : "always"
        //            }).appendTo(printWrap);
        //            table.clone().removeAttr("id").appendTo(printPage).css({
        //                "position": "relative",
        //                "left": -i * pageWidth
        //            });
        //        }
        //        table.hide();
        //        $(this).prop("disabled", true);
        var tables = $('.printable');

        var styling=document.getElementById("style");
        newWin= window.open("");
        newWin.document.write(styling.innerHTML);
        newWin.document.write('<h3 style="float:left">Quality Control Report</h3> ' +
            ' <h4 style="float:right"> Date Printed :<?php echo date("d"); ?> - <?php echo date("M"); ?> - <?php echo date("Y"); ?> </h4>');
        $.each(tables, function(key, value) {
            newWin.document.write(value.outerHTML);
        });
        newWin.print();
        newWin.close();
    }
    $('.printBtn').on('click',function(){
        printData();
    })

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Quality Control Report'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/reports/QC.blade.php ENDPATH**/ ?>