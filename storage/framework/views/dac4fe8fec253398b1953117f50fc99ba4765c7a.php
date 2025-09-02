<?php $__env->startSection('content'); ?>
    
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
        
        /* Professional Units Report Table Styling */
        .sunriseTable {
            width: 100%;
            margin-bottom: 2rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(43, 123, 125, 0.15);
            border: none;
        }
        
        .sunriseTable td, .sunriseTable th {
            padding: 12px 16px;
            text-align: center;
            border: 1px solid #e8f4f5;
            font-size: 14px;
            position: relative;
        }
        
        /* Enhanced month header */
        .subHeaderRow {
            background: linear-gradient(135deg, #2b7b7d 0%, #357a7c 100%);
            color: white;
            font-weight: 600;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px;
            text-align: center;
            border: none;
        }
        
        .subHeaderRow th {
            border: none;
            padding: 16px;
        }
        
        /* Column headers */
        .tableHeaderRow {
            background: linear-gradient(135deg, #f1f7ed 0%, #e8f0e5 100%);
            font-weight: 700;
            color: #2b7b7d;
            border-bottom: 3px solid #2b7b7d;
        }
        
        .tableHeaderRow td {
            padding: 14px 16px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-color: #d0e8d1;
        }
        
        /* Data rows */
        .dataRow {
            transition: all 0.3s ease;
        }
        
        .dataRow:hover {
            background-color: #f8fcfc;
            box-shadow: 0 2px 8px rgba(43, 123, 125, 0.1);
        }
        
        .dataRow:nth-child(even) {
            background-color: #f9fdfb;
        }
        
        .dataRow:nth-child(odd) {
            background-color: #ffffff;
        }
        
        /* Doctor names */
        .doctorName {
            font-weight: 600;
            color: #2b7b7d;
            text-align: left;
            padding-left: 20px;
            font-size: 14px;
        }
        
        /* Data cells */
        .xl649957 {
            font-weight: 500;
            color: #404040;
        }
        
        /* Enhanced totals column */
        .totalsCol {
            background: linear-gradient(135deg, #2b7b7d 0%, #357a7c 100%);
            color: white;
            font-weight: 700;
            font-size: 14px;
            border-left: 3px solid #1a5d5f;
            position: relative;
        }
        
        .totalsCol::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #4a9a9c, #2b7b7d);
        }
        
        /* Totals row */
        .totalsRow {
            background: #f1f7ed;
            color: #2b7b7d;
            font-weight: 700;
            font-size: 15px;
            border-top: 3px solid #2b7b7d;
            text-align: center;
        }
        
        /* First cell in totals row */
        .totalsRow:first-child,
        tr:last-child .xl669957 {
            background: linear-gradient(135deg, #2b7b7d 0%, #357a7c 100%);
            color: white;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Subtle borders */
        .bottom-Border {
            border-bottom: 2px solid #2b7b7d;
        }
        
        /* Number formatting */
        .xl649957:not(.doctorName) {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-variant-numeric: tabular-nums;
        }
        
        /* Add subtle animation to numbers on hover */
        .dataRow .xl649957:not(.doctorName):hover {
            transform: scale(1.05);
            font-weight: 600;
            color: #2b7b7d;
        }
    </style>
</div>
    <!-- styles for the view only -->
    <style>
      table{
          margin-bottom:5vh;}
    </style>
    <form class="kt-form filtersPanel bd-callout bd-callout-info sigmaPanel" method="GET" action="<?php echo e(route('num-of-units-report')); ?>" style="height:30%">
        <div class="row h-50" style="padding-left: 10px;padding-top: 0;padding-bottom: 0px">

            <div class="col-lg-2 col-md-3 col-6 mb-3">
                <div class="kt-subheader__search" style="">
                    <label>From:</label>
                    <input type="date" class="form-control" name="from" value="<?php echo e($from ?? now()->subMonth()->format('Y-m-d')); ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6 mb-3">
                <div class="kt-subheader__search" style="">
                    <label>To:</label>
                    <input type="date" class="form-control" name="to" value="<?php echo e($to ?? now()->format('Y-m-d')); ?>">
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-3">
                <?php if(isset($materials)): ?>
                    <div class="dropdown">
                        <label>Material:</label>
                        <select style="width:100%" class="selectpicker clearOnAll" multiple name="material[]"
                                id="material" data-live-search="true" title="All" data-hide-disabled="true">

                                <option value="all" <?php echo e((isset($selectedMaterials) && $selectedMaterials== 'all') ? 'selected' : ''); ?>>
                                    All
                                </option>
                                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($d->id); ?>" <?php echo e((isset($selectedMaterials) && in_array($d->id ,$selectedMaterials)) ? 'selected' : ''); ?>><?php echo e($d->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-3">
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
        </div>
        <div class="row h-50" style="padding-left: 10px;padding-top: 0;padding-bottom: 0px">
            <div class="col-lg-3 col-md-3 col-3 mb-3">
                <div class="kt-subheader__search">
                    <label></label>
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-3 mb-3">
                <div class="kt-subheader__search">
                    <label></label>
                    <div class="kt-form__actions">
                        <button  class="btn btn-secondary printBtn">Print</button>
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
                        <table border="1" class="xl649957 printable sunriseTable" style="border-collapse:collapse;">
                            <thead>
                            <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                <th class="" style="background-color: transparent !important;">Month:</th>

                                <th colspan="<?php echo e(count($selectedMaterials)+1); ?>"
                                    style="height:21.95pt;border-top:none"><?php echo e($month); ?></th>

                            </tr>
                            </thead>
                            <tbody>
                            <!-- The Months row -->

                            <!--The Materials row -->
                            <tr class=" border-bottom tableHeaderRow">
                                <td class="xl639957" style="height:21.95pt;border-top:none">Dr Name</td>

                                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(isset($selectedMaterials) && in_array($d->id ,$selectedMaterials)): ?>
                                            <td class="xl639957" style=""><?php echo e($d->name); ?></td>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="totalsCol" style="">All</td>

                            </tr>
                            <!-- Main ROWS -->
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!-- if all is selected, don't check if client is selected or not, otherwise check each one by id -->
                                <?php if(!in_array('all' ,$selectedClients)): ?>
                                    <?php if(isset($selectedClients) && !in_array($client->id ,$selectedClients)): ?>
                                        <?php continue; ?>;
                                    <?php endif; ?>
                                <?php endif; ?>

                                <tr class="dataRow" style="">
                                    <td class="xl669957 doctorName"><?php echo e($client->name); ?></td>

                                        <?php
                                            $docTotalUnits = 0;
                                            $currentTotal = 0;

                                        ?>

                                        <?php $__currentLoopData = $selectedMaterials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php

                                                    $currentTotal= $client->numOfUnitsByMaterial($matId,$month);
                                                    $docTotalUnits += $currentTotal;
                                                    $totalsArray[$month][$matId] += $currentTotal;
                                                    $totals[$client->id][$matId] += $currentTotal;
                                            ?>
                                            <td class="xl649957"><?php echo e($currentTotal); ?></td>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <td style="" class="totalsCol"><b><?php echo e($docTotalUnits); ?></b></td>
                                        <?php $totalsArray[$month][99] += $docTotalUnits; ?>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <!-- Totals for whole lab Row -->
                            <tr style="">
                                <td class="xl669957">Totals</td>

                                    <?php $__currentLoopData = $totalsArray[$month]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $total): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="totalsRow" style=""><?php echo e($total); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tr>
                            </tbody>
                        </table>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div id="totalsTableTempHolder">
                        <table border="1" class="xl649957 printable sunriseTable " style="border-collapse:collapse;">
                                <thead>
                                <tr class="bottom-Border subHeaderRow" style="mso-height-source:userset;">
                                    <th class="" style="background-color: transparent !important;">Month:</th>

                                    <th colspan="<?php echo e(count($selectedMaterials)+1); ?>"
                                        style="height:21.95pt;border-top:none">All Time</th>

                                </tr>
                                </thead>
                                <tbody>
                                <!-- The Months row -->

                                <!--The Materials row -->
                                <tr class=" border-bottom tableHeaderRow">
                                    <td class="xl639957" style="height:21.95pt;border-top:none">Dr Name</td>

                                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(isset($selectedMaterials) && in_array($d->id ,$selectedMaterials)): ?>
                                            <td class="xl639957" style=""><?php echo e($d->name); ?></td>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <td class="totalsCol" style="">All</td>

                                </tr>
                                <!-- Main ROWS -->
                                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <!-- if all is selected, don't check if client is selected or not, otherwise check each one by id -->
                                    <?php if(!in_array('all' ,$selectedClients)): ?>
                                        <?php if(isset($selectedClients) && !in_array($client->id ,$selectedClients)): ?>
                                            <?php continue; ?>;
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <tr class="dataRow" style="">
                                        <td class="xl669957 doctorName"><?php echo e($client->name); ?></td>

                                        <?php
                                            $docTotalUnits = 0;
                                            $currentTotal = 0;
                                        ?>

                                        <?php $__currentLoopData = $selectedMaterials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $matId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $currentTotal= $totals[$client->id][$matId];
                                                $docTotalUnits += $currentTotal;
                                                $totals2[$matId] += $currentTotal;

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

<script>
    $(document).ready(function () {


        $("#totalsTableHolder").html($("#totalsTableTempHolder").html());
        $("#totalsTableTempHolder").html("");
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
        newWin.document.write('<h3 style="float:left">Doctor Consumptions Report</h3>  <h4 style="float:right"> Date Printed :<?php echo date("d"); ?> - <?php echo date("M"); ?> - <?php echo date("Y"); ?> </h4>');
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
<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Number of units Report'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/reports/numOfUnits.blade.php ENDPATH**/ ?>