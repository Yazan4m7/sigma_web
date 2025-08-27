<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400..700&display=swap" rel="stylesheet">


    <title><?php echo e($pageSlug ?? config('site_vars.projectNameShort')); ?></title>

<!-- CSS -->
<link rel="stylesheet" href="<?php echo e(asset('custom-CSS-JS/style1.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('custom-CSS-JS/style2.css')); ?>">





<!-- ############################################################# -->

<!--  -----------------------ANIMATIONS------------------------ -->

<!-- ############################################################# -->


<!-- JS -->
<script src="<?php echo e(asset('custom-CSS-JS/animation.js')); ?>"></script>
<script src="<?php echo e(asset('custom-CSS-JS/script2.js')); ?>"></script>
<!-- ############################################################# -->


    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Noto+Naskh+Arabic:wght@400..700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
    <!-- Reset/Base CSS -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <style>
        :root {
            --font-family-sans-serif: "Nunito", sans-serif;
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;

            --main-blue: #2CA8FF;
            --main-orange: #FFA500;
            --main-green: green;
        }

        @font-face {
            font-family: SegoeUI;
            src: local("Segoe UI Bold"),
            url(//c.s-microsoft.com/static/fonts/segoe-ui/west-european/bold/latest.woff2) format("woff2"),
            url(//c.s-microsoft.com/static/fonts/segoe-ui/west-european/bold/latest.woff) format("woff"),
            url(//c.s-microsoft.com/static/fonts/segoe-ui/west-european/bold/latest.ttf) format("truetype");
            font-weight: 600;
        }

        .noto-naskh-arabic {
            font-family: "Noto Naskh Arabic", serif;
            font-optical-sizing: auto;
            font-weight: 700;
            font-style: normal;
        }

        .noto-naskh-arabic {
            font-family: "Noto Naskh Arabic", serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            z-index: 5;
        }

        .overlay.active {
            display: flex;
        }

        .no-scroll {
            overflow: hidden;
        }

    </style>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,300" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@500&display=swap"
          rel="stylesheet">
    <link href="http://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet"/>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

    <!-- Core Framework CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          media="all"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l')"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Third-party/Plugin CSS -->
    <link href="<?php echo e(asset('assets/css/jquery.datetimepicker.min.css')); ?>" rel="stylesheet">
    <link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo e(asset('assets')); ?>/css/sweetalert2.min.css" rel="stylesheet"/>

    <!-- Theme CSS -->
    <link href="<?php echo e(asset('assets')); ?>/css/white-dashboard.css?v=1.0.0" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/theme.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/nucleo-icons.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="<?php echo e(asset('assets')); ?>/css/callouts.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/custom-styling.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('assets')); ?>/css/sidebar-fix.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('css')); ?>/georgia-font.css" rel="stylesheet"/>
    <link href="<?php echo e(asset('css/ysh-custom-css/machine-images.css')); ?>" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/sigma_favico.png')); ?>"/>

    <!-- Dynamic styling -->
    <?php echo $__env->make('layouts.dynamicStyling', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- Page-specific CSS -->
    <?php echo $__env->yieldPushContent('css'); ?>

</head>

<body  class="white-content<?php echo e($class ?? ''); ?>">


<?php if(auth()->guard()->check()): ?>
    <!-- Loading Overlay -->
    <!-- Loading Spinner Overlay -->
    
    
    
    
    
    
    
    
    
    
    <div class="wrapper" >
        <?php echo $__env->make('layouts.navbars.leftsidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="main-panel">
            <?php echo $__env->make('layouts.navbars.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            
            <div class="content" >
                <?php if(session()->has('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e(session()->get('error')); ?>

                    </div>
                <?php endif; ?>
                <?php if(session()->has('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e(session()->get('success')); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>

            </div>
        </div>

    </div>
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" >
        <?php echo csrf_field(); ?>
    </form>
<?php else: ?>

    <?php echo $__env->make('layouts.navbars.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <div class="wrapper wrapper-full-page animate-bottom"  >
        <div class="overlay" id="overlay"></div>
        <div class="full-page <?php echo e($contentClass ?? ''); ?>">

            <div class="content">

                <div class="container">

                </div>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

<?php endif; ?>

</body>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</html>
<?php /**PATH C:\Users\Yazan\Desktop\sigma\staging\resources\views/layouts/app.blade.php ENDPATH**/ ?>