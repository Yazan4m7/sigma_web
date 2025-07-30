
<?php $__env->startSection('head'); ?>
  <style>
      body{
          -webkit-print-color-adjust:exact !important;
      }
      p{
          margin-bottom: 0 !important;
      }
       tr:nth-child(even) {
          background-color: #ececec !important;
      }
       thead{
           background-color: lightsteelblue !important;
           border-radius: 20%;
       }
      #invlogo {

          line-height: 60px;


          z-index: 100;
          background-color: #fff;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          font-size: 22px;
          color: rgba(9, 17, 20, 0.97) !important;
          text-align:center;
          font-weight: 900;
      }
  </style>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-12 m-b-30">
            <div class="">
                <div class="card-body invoice">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-right"><div id="invlogo">

                                </div></h4>
                        </div>
                        <div class="pull-right">
                            <h6>Invoice : #
                                <strong><?php echo e($case->invoice->id); ?></strong>
                            </h6>
                            <h6 class="pull-right">Date : <?php echo e(substr(now(),0,16)); ?></h6>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left mt-4">
                                <address>
                                    <strong><b>SIGMA DENTAL LAB</b></strong><br>
                                    Abdallah Ghosheh St.<br>

                                </address>
                                <p><strong>Order Status: </strong>
                                    <?php if(isset($case->delivered_to_client)): ?>
                                        <span class="badge badge-success">Applied</span></p>
                                <?php else: ?>

                                    <span class="badge badge-warning">Pending</span></p>
                                <?php endif; ?>
                            </div>
                            <div class="pull-right mt-4">
                                <p><strong>Dentist: </strong><b><?php echo e($case->client->name); ?></b></p>
                                <p><strong>Patient: </strong><b><?php echo e($case->patient_name); ?></b></p>
                                <p><strong>Order Date: </strong><?php echo e(str_replace('T', ' ',$case->created_at)); ?></p>
                                <?php if(isset($case->actual_delivery_date)): ?>
                                    <p><strong>Delivered on: </strong><?php echo e(substr($case->actual_delivery_date,0,16)); ?></p>
                                <?php endif; ?>
                                <p><strong>Order ID: </strong><?php echo e($case->case_id . '/'. $case->id); ?></p>
                            </div>
                        </div>
                    </div><!--end row-->

                    <div class="h-50"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table mt-4">
                                    <THEAD>
                                    <th style=""><span style="">ID</span></th>
                                    <th style=""><span style="">Job Type</span></th>
                                    <th style=" "><span style="">Material</span></th>

                                    <th style=" "><span style="">Style</span></th>
                                    <th style=" "><span style="">Quantity</span></th>
                                    <th style=" "><span style="">Unit Price</span></th>
                                    <th style=" "><span style="">Amount</span></th>
                                    </THEAD>
                                    <tbody>
                                    <?php
                                        $i=1;
                                    $totalInvoiceAmount=0;
                                    ?>

                                    <?php $__currentLoopData = $case->jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        if($job->is_modification)
                                        continue;
                                        ?>
                                        <tr>
                                            <td style=""><?php echo e($i); ?>

                                            <?php if($job->is_rejection): ?>
                                                <span style="color:red">REJECTION</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style=""><?php echo e($job->jobType->name); ?></td>
                                            <td style=" "><?php echo e($job->material->name); ?></td>

                                            <td style=" "><?php echo e($job->style); ?></td>
                                            <?php

                                            $unitsAmount = count(explode(',', $job->unit_num));
                                            {{ print_r($unitsAmount . ' <= Units amount  '); }}
                                            {{ print_r($job->unit_price . ' <=  $job->unit_price'  ); }}
                                            {{ print_r($job->material->price . ' =<<=  $job->material->price'); }}


                                             if (isset($job->unit_price) && $job->unit_price > 0)
                                            $totalJobPrice = $unitsAmount * $job->unit_price;
                                             else
                                             $totalJobPrice = $unitsAmount * $job->material->price;

                                            $totalInvoiceAmount += $totalJobPrice;

                                            ?>
                                            <td style=" "><?php echo e($unitsAmount); ?></td>
                                            <td style=" "><?php echo e($job->unit_price ?? $job->material->price); ?></td>
                                            <td style=" "><?php echo e($totalJobPrice); ?></td>
                                        </tr>
                                        <?php
                                            $i++;
                                        ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--end row-->

                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-9">

                        </div>
                        <div class="col-md-3">
                            <h6 class="text-right">
                                <?php if(isset($case->discount)): ?>
                                Discount : <?php echo e($case->discount->discount); ?>

                                <?php endif; ?>
                            </h6>
                            <hr>
                            <?php if(isset($case->discount)): ?>
                            <h4 class="text-right">Total: <b><?php echo e($totalInvoiceAmount - $case->discount->discount); ?></b> JOD </h4>
                            <?php else: ?>
                                <h4 class="text-right">Total: <b><?php echo e($totalInvoiceAmount); ?></b> JOD </h4>
                                <?php endif; ?>
                        </div>
                    </div><!--end row-->

                    <hr>
                    <div class="hidden-print">
                        <div class="text-center text-muted"><small>Thank you for doing business with us!</small></div>
                        <div class="pull-right">

                            <button type="button" onclick="print()" class="btn btn-secondary"><i class="fa fa-print"></i></button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>
    <script>
        function print(){
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write(`
            <link href="<?php echo e(asset('assets/css/slidebars.min.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/icons.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/icons/css/themify-icons.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('assets/css/menu.css')); ?>" rel="stylesheet" type="text/css">
<link href="<?php echo e(asset('assets/css/style.css')); ?>" rel="stylesheet">

<link rel="stylesheet" href="<?php echo e(asset('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css')); ?>" media="all" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l')" crossorigin="anonymous">

  <style>
  body{
  -webkit-print-color-adjust:exact !important;
}
          p{
          margin-bottom: 0 !important;
      }
       tr:nth-child(even) {
        -webkit-print-color-adjust:exact !important;
          background-color: #ececec !important;

      }
       thead{
           background-color: lightsteelblue !important;
           border-radius: 20%;
       }
      #invlogo {

          line-height: 60px;


          z-index: 100;
          background-color: #fff !important;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          font-size: 22px;
          color: rgba(9, 17, 20, 0.97) !important;
          text-align:center;
          font-weight: 900;
      }
    </style>
    <div class="row">
        <div class="col-12 m-b-30">
            <div class="card">
                <div class="card-body invoice">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-right"><div id="invlogo">
                                    SIGMA LAB
                                </div></h4>
                        </div>
                        <div class="pull-right">
                            <h6>Invoice : #
                                <strong><?php echo e($case->invoice->id); ?></strong>
                            </h6>
                            <h6 class="pull-right">Date : <?php echo e(substr(now(),0,16)); ?></h6>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left mt-4">
                                <address>
                                    <strong><b>SIGMA DENTAL LAB</b></strong><br>
                                    Abdallah Ghosheh St.<br>

                                </address>
                                <p><strong>Order Status: </strong>
                                    <?php if(isset($case->delivered_to_client)): ?>
                <span class="badge badge-success">Applied</span></p>
<?php else: ?>

                <span class="badge badge-warning">Pending</span></p>
<?php endif; ?>
                </div>
                <div class="pull-right mt-4">
                    <p><strong>Dentist: </strong><b><?php echo e($case->client->name); ?></b></p>
                                <p><strong>Patient: </strong><b><?php echo e($case->patient_name); ?></b></p>
                                <p><strong>Order Date: </strong><?php echo e(str_replace('T', ' ',$case->created_at)); ?></p>
                                <?php if(isset($case->actual_delivery_date)): ?>
                <p><strong>Delivered on: </strong><?php echo e(substr($case->actual_delivery_date,0,16)); ?></p>
                                <?php endif; ?>
                <p><strong>Order ID: </strong><?php echo e($case->case_id . '/'. $case->id); ?></p>
                            </div>
                        </div>
                    </div>

                <div class="h-50"></div>
                <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                <table class="table mt-4">
                <THEAD>
                <th style=""><span style="">ID</span></th>
                <th style=""><span style="">Job Type</span></th>
                <th style=" "><span style="">Material</span></th>

                <th style=" "><span style="">Style</span></th>
                <th style=" "><span style="">Quantity</span></th>
                <th style=" "><span style="">Unit Price</span></th>
                <th style=" "><span style="">Amount</span></th>
                </THEAD>
                <tbody>
            <?php
                $i=1;
            $totalInvoiceAmount=0;
            ?>
                    <?php $__currentLoopData = $case->jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        if($job->is_modification)
                        continue;
                    ?>
                <tr>
                <td style=""><?php echo e($i); ?></td>
                <td style=""><?php echo e($job->jobType->name); ?></td>
                <td style=" "><?php echo e($job->material->name); ?></td>

                <td style=" "><?php echo e($job->style); ?></td>
                    <?php

                        $unitsAmount = count(explode(',',$job->unit_num));
                        $totalJobPrice = $unitsAmount * $job->unit_price ?? $job->material->price;
                        $totalInvoiceAmount += $totalJobPrice;
                    ?>
                <td style=" "><?php echo e($unitsAmount); ?></td>
                <td style=" "><?php echo e($job->unit_price ?? $job->material->price); ?></td>
                <td style=" "><?php echo e($totalJobPrice); ?></td>
                </tr>
            <?php
                $i++;
            ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
                </table>
                </div>
                </div>
                </div>

                <div class="row" style="border-radius: 0px;">
                <div class="col-md-9">

                </div>
                <div class="col-md-3">
                   <h6 class="text-right">
                                <?php if(isset($case->discount)): ?>
                Discount : <?php echo e($case->discount->discount); ?>

                    <?php endif; ?>
                </h6>
                <hr>
               <?php if(isset($case->discount)): ?>
                <h4 class="text-right">Total: <b><?php echo e($totalInvoiceAmount - $case->discount->discount); ?></b> JOD </h4>
                            <?php else: ?>
                <h4 class="text-right">Total: <b><?php echo e($totalInvoiceAmount); ?></b> JOD </h4>
                                <?php endif; ?>
                </div>
                </div>

                <hr>
                <div class="hidden-print">
                <div class="text-center text-muted"><small>Thank you for doing business with us!</small></div>
                <div class="pull-right">


                </div>
                </div>
                </div>
                </div>
                </div>
                </div>
`);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function(){ mywindow.print(); /*mywindow.close();*/ },1000);

            return true;
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'View Invoice' ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/generic/invoice-view.blade.php ENDPATH**/ ?>