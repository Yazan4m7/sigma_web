<div class="wrapper wrapper-full-page ">
    <?php echo $__env->make('loginLayout.navbars.navs.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="fue" filter-color="black" data-image="<?php echo e(asset('assets/bg.png')); ?>">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo $__env->make('loginLayout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/loginLayout/page_template/guest.blade.php ENDPATH**/ ?>