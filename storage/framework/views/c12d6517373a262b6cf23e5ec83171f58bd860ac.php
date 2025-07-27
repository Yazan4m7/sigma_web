<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/lightgallery.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/jquery.imagesloader.css')); ?>" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/lightgallery/1.3.9/css/lightgallery.min.css" rel="stylesheet">

    <style>
        #kt_repeater_1 {padding-left:15px}
        .noteform{padding:10px}
        .checked {
            filter: invert(26%) sepia(73%) saturate(492%) hue-rotate(133deg) brightness(94%) contrast(86%);
        }
        .hidden{
            display:none;
        }
        .noteHeader{color: #525252; font-size: 12px;}
        .noteText{color:black;font-weight: 500;}
        .bootstrap-select>.dropdown-toggle.bs-placeholder{
            color: #1a000d !important;

        }
        .historyTable{display:none;}
        .Timeline{display:block;}
        @media  screen and (max-width:760px) {
            .btnsRow{
                flex-direction: column;
            }
            .historyTable{display:block;}
            .Timeline{display:none !important;}
            .noteform{padding:0}
            #kt_repeater_1 {padding-left:0}
            .printMiniLabelBtn{margin-bottom: 5px;}
        }

    </style>
    <link href="<?php echo e(asset('assets')); ?>/css/timeline.css" rel="stylesheet"/>


    <div class="col-lg-12 col-sm-12 ">
        <div class="row btnsRow" style="padding-left: 10px;padding-top: 10px; background-color: transparent">
            <div class=" col-3 ">

                    <button class="btn btn-secondary printMiniLabelBtn" style=" background-color: #2b7b7d;   padding-left: 20px;
    padding-right: 20px;" onclick="PrintMinimizedLabel()" >Print Mini Label <i class="fa-solid fa-tag"></i></button>

            </div>
            <div class="col-2 ">

                    <button class="btn btn-secondary" style="background-color: #2b7b7d;    padding-left: 20px;
    padding-right: 20px;" onclick="PrintLabel()" >Print Label <i class="fa-solid fa-tag"></i></button>

            </div>
        </div>
        </div>

    <form style="" class="kt-form noteform" method="POST" enctype="multipart/form-data" action="#">
    <?php echo csrf_field(); ?>
    <div>
    <!-- CASE INFO -->

        <div class="row" style="padding-left: 10px;padding-top: 10px">
            <div class="col-md-3 col-xs-6 col-l-3 col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Doctor:</label></div>
                <div class="col-md-12 col-xs-12">


                    <select  class="selectpicker"  name="doctor"  data-container="body" data-live-search="true"  title="Select a doctor" disabled  >
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($client->id); ?>" <?php echo e($case->client->id == $client->id ? "selected" : ""); ?> ><?php echo e($client->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div> </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label >Patient name:</label></div>
                <div class="col-md-12 col-xs-12"><input class="form-control" type="text" name="patient_name" value="<?php echo e($case->patient_name); ?>" disabled /></div>
            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-6 col-xs-12"><label>Case ID:</label></div>
                <div class="col-md-12 col-xs-12">

                    <label ><?php echo e($case->case_id); ?></label>

                </div>

            </div>

        </div>

<br/>
        <div class="row">

            <div class="col-md-4  col-xs-6 col-l-2  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Delivery Date:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control SDTP" name="delivery_date"  type="text"   value="<?php echo e($case->initial_delivery_date); ?>" required disabled/>
                </div>
            </div>
            <div class="col-md-4  col-xs-6 col-l-2  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Tags:</label></div>
                <div class="col-md-12 col-xs-12">
                    <select class="select selectpicker" name="tags[]" multiple data-mdb-placeholder="Tags" multiple disabled>
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option style="color:<?php echo e($tag->color); ?>" value="<?php echo e($tag->id); ?>" <?php echo e(in_array($tag->id ,$tagsAsArray) ? 'selected' : ''); ?>><?php echo e($tag->text); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                </div>
            </div>
            <div class="col-md-4 col-xs-6 col-l-2 col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Impression Type:</label></div>
                <div class="col-md-12 col-xs-12"> <select  class="form-control" name="impression_type" type="text"  data-container="body" data-live-search="true" title="Select impression" data-hide-disabled="true" disabled >

                        <?php $__currentLoopData = $impressionTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $impression): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($impression->id); ?>" <?php echo e($impression->id == $case->impression_type ? ' selected' : ' '); ?>>
                                <?php echo e($impression->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select></div>
            </div>
        </div>


        <!-- JOB INFO ICON-->
        <br>
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h5 class="kt-portlet__head-title">
                    <i class="fa  fa-suitcase"  style="width:3%"></i> Job information
                </h5>
            </div>
        </div>
        <hr>


        <!-- JOBS REPEATER -->

        <div  id="kt_repeater_1" style=" padding-right: 15px">
            <div  data-repeater-list="repeat">
                <div data-repeater-item>
                    <div class="form-group form-group ">
                        <div data-repeater-list="repeat" class="col-12">
                            <?php
                            if($stage == -2 || $stage >5)
                            $jobs = $case->jobs;
                            else
                            $jobs = $case->jobs->where('stage',$stage);
                            ?>


                                <table id="tech-companies-1" class="table sunriseTable table-striped jobsTable">
                                    <thead>
                                    <tr>
                                        <th id="tech-companies-1-col-0">Unit Num</th>

                                        <th data-priority="3" id="tech-companies-1-col-2">Job Type</th>
                                        <th data-priority="1" id="tech-companies-1-col-3">Material</th>
                                        <th data-priority="3" id="tech-companies-1-col-4">Color</th>
                                        <th data-priority="3" id="tech-companies-1-col-5">Style</th>
                                        <th data-priority="3" id="tech-companies-1-col-5">Status</th>
                                        <th data-priority="6" id="tech-companies-1-col-6">Others</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <?php
                                            $unit = explode(', ',$job->unit_num);
                                        ?>
                                    <tr >

                                        <th colspan="1" data-columns="tech-companies-1-col-0"><?php echo e($job->unit_num); ?></th>

                                        <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-2"><?php echo e($job->jobType->name); ?></td>
                                        <td data-priority="1" colspan="1" data-columns="tech-companies-1-col-3"><?php echo e($job->material->name); ?></td>
                                        <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-4"><?php echo e($job->color =='0' ? "No color":$job->color); ?></td>
                                        <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-5"><?php echo e($job->style); ?></td>
                                        <td data-priority="3" colspan="1" data-columns="tech-companies-1-col-5">
                                            <b style="color:#2b7b7d"><?php echo e($job->status()); ?></b>
                                        </td>
                                        <td data-priority="6" colspan="1" data-columns="tech-companies-1-col-6">
                                            <?php if(isset($job->abutmentDelivery)): ?>
                                                <?php $__currentLoopData = $job->abutmentDelivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span><?php echo e($delivery->implant->name?? "None"); ?><?php echo e(' - ' . $delivery->abutment->name?? "None"); ?><?php echo e(' - ' . $delivery->code?? "None"); ?> </span>
                                                    <br>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span>"Err" </span>
                                            <?php endif; ?>

                                            <?php if(isset($job->originalJob)): ?>
                                            <?php if(isset($job->originalJob->abutmentDelivery)): ?>
                                                <?php $__currentLoopData = $job->originalJob->abutmentDelivery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span><?php echo e($delivery->implant->name?? "None"); ?><?php echo e(' - ' . $delivery->abutment->name?? "None"); ?><?php echo e(' - ' . $delivery->code?? "None"); ?> </span>
                                                    <br>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span>"Err" </span>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                           <?php if(isset($job->abutmentR)  && $job->jobType->id ==6): ?>
                                                <span>   Abutment Type:  <?php echo e($job->abutmentR->name); ?> <br></span>
                                            <?php endif; ?>
                                        <?php if($job->has_been_rejected): ?> <span style="color:red;font-size: 10px"><b>PARTIALLY/ FULLY</b></span> <span style="color:red"><b>REJECTED</b></span> <?php endif; ?>
                                                <?php if($job->is_repeat): ?>  <span style="color:red"><b>REPEAT</b></span> <?php endif; ?>
                                                <?php if($job->is_modification): ?><span style="color:red"><b>MODIFICATION</b></span><?php endif; ?>
                                                <?php if($job->is_redo): ?><span style="color:red"><b>REDO</b></span><?php endif; ?>
                                        <?php if(isset($job->redone_job_id)): ?><span style="color:red"><b>HAS A REDO JOB BELOW</b></span><?php endif; ?>
                                        </td>
                                        <?php if($job->is_rejection): ?> <td class="reOverlay">REJECTION</td> <?php endif; ?>
                                        <?php if(isset($job->modified_job_id)): ?> <td class="reOverlay">COMPLETED & UNDER MODIFICATION BELOW</td> <?php endif; ?>

                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>
            </div>
           <!-- <a href="javascript:;" data-repeater-create="" class="btn btn-info  btn-sm" id="addJobBtn" >
                <i class="fa fa-plus-square"></i> Add
            </a> -->
        </div>

        <br>
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h5 class="kt-portlet__head-title">
                    <i class="fa-solid fa-clock-rotate-left"  ></i> Case History
                </h5>
            </div>
        </div>
        <hr>
        <!-- HISTORY -->
        <div class="historyTable" style="padding:0 30px 0 30px ">
        <table class="sunriseTable table sunriseTable table-striped " >
            <thead>
            <tr>
                <th>Stage</th>
                <th>Employee</th>
                <th>Started On</th>
                <th>Finished On</th>
            </tr>
            </thead>
            <tbody>
            <?php
                $stages = array('Design','Milling','3D Printing','Sintering','Pressing','Finishing','QC'    );
            $i=1;
            ?>
            <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="stageName"><?php echo e($value); ?></td>
                    <?php $log = $case->logs->where('stage',$i)->where("is_completion",1)->first(); ?>
                    <?php if($case->logs->where('stage',$i)->where('stage',$i)->first() !== null): ?>
                    <td><?php echo e($case->logs->where('stage',$i)->where("is_completion",1)->first() ? $case->logs->where('stage',$i)->where("is_completion",1)->first()->user->fullName() : " - "); ?></td>
                    <td><?php echo e($case->logs->where('stage',$i)->where("is_completion",0)->first() ? substr($case->logs->where('stage',$i)->where("is_completion",0)->first()->created_at,0,16) : " - "); ?></td>
                    <td><?php echo e($case->logs->where('stage',$i)->where("is_completion",1)->first() ? substr($case->logs->where('stage',$i)->where("is_completion",1)->first()->created_at,0,16) : " - "); ?></td>
                    <?php else: ?>
                    <td>-</td><td>-</td><td>-</td>
                    <?php endif; ?>
                </tr>
                <?php $i = $i+1; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="stageName">Delivery</td>
                <?php $log = $case->logs->where('stage',8)->first(); ?>
                <?php if($case->logs->where('stage',8)->where("is_completion",1)->first() !== null): ?>
                    <td><?php echo e($case->logs->where('stage',$i)->where("is_completion",1)->first()->user->fullName()); ?></td>
                    <td><?php echo e($case->logs->where('stage',8)->where("is_completion",0)->first() ? substr($case->logs->where('stage',8)->where("is_completion",0)->first()->created_at,0,16) :  substr($case->logs->where('stage',8)->where("is_completion",3)->first()->created_at,0,16)); ?></td>
                    <td><?php echo e(substr($case->logs->where('stage',8)->where("is_completion",1)->first()->created_at,0,16)); ?></td>
                <?php else: ?>
                    <td>-</td><td>-</td><td>-</td>
                <?php endif; ?>
            </tr>


            </tbody>
        </table>
        </div>


        <div class="Timeline">

            <svg height="5" width="10">
                <line x1="0" y1="0" x2="10" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>

            <div class="event1">
                <div class="event1Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            DESIGN
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',1)->where("is_completion",1)->first() ? substr($case->logs->where('stage',1)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',1)->where("is_completion",1)->first() ? $case->logs->where('stage',1)->first()->user->name_initials : "-"); ?></div>
                </div>


                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>
                

            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event2">

                <div class="event2Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            MILLING
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',2)->where("is_completion",1)->first() ? substr($case->logs->where('stage',2)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',2)->where("is_completion",1)->first() ? $case->logs->where('stage',2)->first()->user->name_initials : "-"); ?></div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>

            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event1">
                <div class="event1Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            3D Printing
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',3)->where("is_completion",1)->first() ? substr($case->logs->where('stage',3)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',3)->where("is_completion",1)->first() ? $case->logs->where('stage',3)->first()->user->name_initials : "-"); ?></div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>


            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event2">

                <div class="event2Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            Sintering
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',4)->where("is_completion",1)->first() ? substr($case->logs->where('stage',4)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',4)->where("is_completion",1)->first() ? $case->logs->where('stage',4)->first()->user->name_initials : "-"); ?></div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>

            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event1">
                <div class="event1Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            Pressing
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',5)->where("is_completion",1)->first() ? substr($case->logs->where('stage',5)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',5)->where("is_completion",1)->first() ? $case->logs->where('stage',5)->first()->user->name_initials : "-"); ?></div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>


            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event2">

                <div class="event2Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            Finishing
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',6)->where("is_completion",1)->first() ? substr($case->logs->where('stage',6)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle">
                        <?php echo e($case->logs->where('stage',6)->where("is_completion",1)->first() ? $case->logs->where('stage',6)->first()->user->name_initials : "-"); ?></div>
                  </div>


                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>

            </div>

            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event1">
                <div class="event1Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            QC
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',7)->where("is_completion",1)->first() ? substr($case->logs->where('stage',7)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>
                    <div class="eventTitle"><?php echo e($case->logs->where('stage',7)->where("is_completion",1)->first() ? $case->logs->where('stage',7)->first()->user->name_initials : "-"); ?></div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>

            </div>
            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>
            <div class="event2">

                <div class="event2Bubble">
                    <div class="eventTime">

                        <div class="Day">
                            Delivery
                            <div class="MonthYear"><?php echo e($case->logs->where('stage',8)->where("is_completion",1)->first() ? substr($case->logs->where('stage',8)->where("is_completion",1)->first()->created_at,0,16) : "-"); ?></div>
                        </div>
                    </div>

                    <div class="eventTitle">
                        <?php if( $case->logs->where('stage',8)->where("is_completion",1)->first() !== null): ?>
                        <?php echo e($case->logs->where('stage',8)->where("is_completion",1)->first()->user->name_initials); ?>

                        <?php elseif($case->logs->where('stage',8)->where("is_completion",3)->first() !== null): ?>
                        <?php echo e($case->logs->where('stage',8)->where("is_completion",3)->first()->user->name_initials); ?>

                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </div>
                    <div class="eventTitle">
                        <?php if( $case->logs->where('stage',8)->where("is_completion",1)->first() !== null): ?>
                            <?php echo e($case->logs->where('stage',8)->where("is_completion",1)->first()->user->name_initials); ?>

                        <?php elseif($case->logs->where('stage',8)->where("is_completion",3)->first() !== null): ?>
                            <?php echo e($case->logs->where('stage',8)->where("is_completion",3)->first()->user->name_initials); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                    <div class="eventTitle">
                        <?php if( $case->logs->where('stage',8)->where("is_completion",1)->first() !== null): ?>
                            <?php echo e($case->logs->where('stage',8)->where("is_completion",1)->first()->user->name_initials); ?>

                        <?php elseif($case->logs->where('stage',8)->where("is_completion",3)->first() !== null): ?>
                            <?php echo e($case->logs->where('stage',8)->where("is_completion",3)->first()->user->name_initials); ?>

                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                </div>

                <svg height="20" width="20">
                    <circle cx="10" cy="11" r="5" fill="#004165" />
                </svg>

            </div>
            <svg height="5" width="100">
                <line x1="0" y1="0" x2="100" y2="0" style="stroke:#004165;stroke-width:5" />
                Sorry, your browser does not support inline SVG.
            </svg>

        </div>





        <!-- NOTES SECTION -->
        <br>
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h5 class="kt-portlet__head-title">
                    <i class="fa fa-sticky-note" style="width:2%"></i> Additional information
                </h5>
            </div>
        </div>
        <hr>
        <br>
        <div class="form-group form-group">
            <label >Notes:</label>

            <?php $__currentLoopData = $case->notes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="form-control" style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black" disabled>

                    <span class="noteHeader"><?php echo e('['. substr( $note->created_at,0,16) . '] [' . $note->writtenBy->name_initials . '] : '); ?></span><br> <span class="noteText"><?php echo e($note->note); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <form></form>
            <form  style="" class="noteform " method="POST" enctype="multipart/form-data"   action="<?php echo e(route('new-note')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row" style="padding:0px">
                    <input type="hidden" name="case_id_for_note" value ="<?php echo e($case->id); ?>">
                    <div class="col-md-6 col-xs-6">
                        <input class="form-control" type="text" name="newNote"  placeholder="Add a note"  />
                    </div>

                    <div class="col-md-3 col-xs-3" style="margin: 0px">
                    <button type="submit" class="btn btn-primary">Add note</button>
                    </div>


                </div>
                </form>
            <br><br>
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h5 class="kt-portlet__head-title">
                        <i class="fa fa-sticky-note" style="width:2%"></i>  Attachments:
                    </h5>
                </div>
            </div>
            <hr>
        <!-- Photos SECTION -->
        <div class="container" style="margin-top:10px;">

            <div class="demo-gallery">
                <ul id="lightgallery" class="list-unstyled row">
                    <?php $__currentLoopData = $case->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="col-xs-6 col-sm-4 col-md-2 col-lg-2" data-responsive="<?php echo e(asset($photo->path)); ?>" data-src="<?php echo e(asset($photo->path)); ?>">
                            <a href="">
                                <img class="img-responsive" src="<?php echo e(asset($photo->path)); ?>">
                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </ul>
            </div>
        </div>

        <br>
        
            
            
        
        <br>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary" disabled>Submit</button>
                <button type="reset" class="btn btn-danger" disabled>Reset</button>
            </div>
        </div>
        </div></div>
    </form>





<?php $__env->stopSection(); ?>
<?php $__env->startPush('js'); ?>


    <script>
        $(document).ready(function () {
            $('#lightgallery').lightGallery();
        });
        function PrintLabel()
        {

            //height=192,width=288
            var mywindow = window.open('', 'PRINT', 'height=600,width=800');
            mywindow.document.write(`

            <style>
            @media  all{
              #kt-invoice__head {}
              .kt-invoice__item {display:none;}

                        }

            body {
                font-family: Arial;
                font-weight : bold;
            }


            .tablesHeaders{
                font-size:12px;
                color:black;

            }


        .tableContent{
            font-size:13px;
            color:black;

        }

        hr.solid {
            border-top: 1px solid #bbb;
            width:95%;
        }

        .headerTitle{
            color:black;
        }

        #tableTail{
            padding-left:1px;
            padding-right:2px;
            width:100%;
            position: absolute;
            bottom: 3px;
        }
        .jobcolor
        {
            padding-left:0px;
            padding-right:5px;
        }
        .paddingLeft
        {
            padding-left:2px;

        }
        </style>
        </head>
        <body>

        <div id="kt-invoice__head" style="height:35px; overflow: hidden; position: relative;padding:0px;">


            <div style="float:right;text-align:right; padding-right:4px;padding-top:2px;width:35%">
            <p style="font-size: 8px;font-weight:bold;color:black;text-align:right;margin:0px"><?php echo e($case->case_id); ?></p>
            <?php
                $date = date("d-M * g:i a", strtotime(str_replace("T", " ",$case->initial_delivery_date)));
                $date = explode(' * ', $date);

            ?>
            <p style="font-size: 10px;font-weight:bolder;color:black;text-align:right;margin:0px;line-height: 1em;padding-bottom: 3px;padding-top: 4px;"><?php echo e($date[0]); ?></p>
            <p style="font-size: 10px;font-weight:bolder;color:black;text-align:right;margin:0px;line-height: 0.5em;"><?php echo e($date[1]); ?></p>
            <div style="padding-top:5px;">
                
            
                
                
            
                

            </div>

            </div>


            <div id="headerInfo" style="width:60%;color:black;float:left;">

            <table >
            <tr style="color:black;">
            <th style="width:20%;text-align:left;font-size:11px;">Dr:</th>
        <th style="width:80%;font-size:12px;text-align:left;font-weight:bold;"><b><?php echo e($case->client->name); ?></b> </th>
            </tr>
            <tr style="color:black;">
            <th style="width:20%;font-size:11px;text-align:left;">Patient:</th>
        <th style="width:70%;text-align:left;font-size:12px;font-weight:bold;"><?php echo e($case->patient_name); ?></th>
            </tr>
            </table>

            </div>



            </div>
                <hr style=" margin-top: 5px; margin-bottom: 0; border-color: white;">
            <div id="jobs" style="width:100%;display: flex;  ">
            <table class="table"  style="width: 100%;height: 100%;border-spacing: 1px 1px; margin: 0 auto;align-self: center;padding-top:5px;margin:0;padding-right:2px;">
            <thead>
            <tr>
            <th class="tablesHeaders" style="text-align:left" width="200"> Job Type</th>
        <th class="tablesHeaders" style="text-align:left" width="80;padding-left:0px">Material</th>
            <th class="tablesHeaders jobcolor" style="text-align:left;padding-left:0px" width="40">Color</th>
            <th class="tablesHeaders" style="text-align:left;" width="20">Qty</th>

            </tr>

            </thead>

            <tbody>

                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr style="text-align:center">
            <td class="tableContent" style="text-align:left;font-size:11px" width="200"> <?php echo e($job->jobType->name); ?></td>
            <td class="tableContent " style="text-align:left;font-size:11px"  width="80"><?php echo e($job->material->name); ?></td>
            <td class="tableContent jobcolor paddingLeft" style="text-align:left;font-size:11px" width="40"><?php echo e($job->color == null ? "-" : $job->color); ?></td>
            <td class="tableContent paddingLeft" style="text-align:left;font-size:11px" width="20"><?php echo e(count(explode(',', $job->unit_num))); ?></td>

            </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </tbody>
            </table>

            <div id="tableTail">


            </div>


            </div>

            </body></html>
            `);
  mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/
    setTimeout(function(){ mywindow.print(); /*mywindow.close();*/},1000);

    return true;
}
        function PrintMinimizedLabel()
        {

            //height=192,width=288
            var mywindow = window.open('', 'PRINT', 'height=600,width=800');
            mywindow.document.write(`

    <head>
    <style>
    @media  all{

      .kt-invoice__item {display:none;}
           }
        body {
            font-family: Arial;
            font-weight : bold;
        }
        .paddingLeft
        {padding-left:2px;}
        </style>
        </head>
        <body>
 <?php
                $date = date("d-M * g:i a", strtotime(str_replace("T", " ",$case->initial_delivery_date)));
                $date = explode(' * ', $date);
            ?>
            <div id="kt-invoice__head" style="text-align:center;height:100%; overflow: hidden; position: relative;padding:0px;">
            <p style="font-size: 29px;font-weight:bold;color:black;margin:0px"><?php echo e($case->client->name); ?></p>
            <p style="font-size: 29px;font-weight:bold;color:black;margin:0px"><?php echo e($case->patient_name); ?></p>
            <hr>
            <p style="font-size: 21px;font-weight:bold;color:black;margin:0px;"><?php echo e($date[0]); ?></p>
            <p style="font-size: 21px;font-weight:bold;color:black;margin:0px;"><?php echo e($date[1]); ?></p>
            </div>
            </body></html>
            `);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function(){ mywindow.print(); mywindow.close();},1000);

            return true;
        }
    </script>

<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => $viewCase], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/cases/viewOnly.blade.php ENDPATH**/ ?>