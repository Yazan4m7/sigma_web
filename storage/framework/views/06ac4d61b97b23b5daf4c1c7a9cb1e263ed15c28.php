<?php $__env->startSection('content'); ?>
    <style>
        .image-picker {
            max-width: 250%;
            max-height: 250%;
            box-shadow: 0 0 10px 0;
            position: absolute;
        }

    </style>
    <form method="POST" action="<?php echo e(route('new-device')); ?>" class="card" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h6 class="kt-portlet__head-title">
                    <i class="fa  fa-suitcase" style="width:3%"></i> Device Info:
                </h6>
            </div>
        </div>
        <hr style="margin-top: 0;">
        <div class="row">

            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12 "><label >Device name:</label></div>
                <div class="col-md-12 col-xs-12">
                    <input class="form-control btn dropdown-toggle btn-light" type="text" name="device_name" required placeholder="Device name"/>
                    <span class="help-block text-muted"><small></small></span>
                </div>

            </div>
            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12"><label>Device Type:</label></div>
                <div class="col-md-12 col-xs-12">
                    <select class="form-control selectpicker  border: 1px solid grey;" id="dev" name="device_type" style="  border: 1px solid grey;">
                        <option value="3">3D Printer</option>
                        <option value="2">Milling Machine</option>
                        <option value="4">Sintering Furnace</option>
                        <option value="5">Pressing Furnace</option>
                    </select>
                </div>

            </div>
            <div class="col-md-2 col-l-2 col-xl-2">
                <div class="col-md-12 col-xs-12"><label>Device Order:</label></div>
                
                <div class="col-md-12 col-xs-12">
                    <input class="form-control btn dropdown-toggle btn-light" type="number" name="device_order" required />
                </div>

            </div>



            <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
                <div class="col-md-12 col-xs-12" style="padding-bottom: 0"><label>Device Image:</label></div>
                <label for="image-picker">
                    <img id="image-preview" class="image-picker" alt="" src=""/>
                </label>
                <input id="image-picker" type="file" accept="image/*" name="device_image"/>
            </div>
        </div>
        <br/>
        <br/>
        <div class=" form-group ">
            <div class="form-group mb-0">
                <div style="">
                    <button type="submit" class="btn btn-info waves-effect waves-light">
                        Submit
                    </button>
                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script type="text/javascript">

        $(document).ready(function () {
            $('form').parsley();
        });
        $("#image-picker").change(function (event) {
            readURL(this);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image-preview').attr('src', e.target.result);
                }
                $('#image-picker').css("display", "none");


                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script type="text/javascript" src="<?php echo e(asset('assets/plugins/parsleyjs/dist/parsley.min.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app' ,[ 'pageSlug' => 'Create ' . $device], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/devices/create.blade.php ENDPATH**/ ?>