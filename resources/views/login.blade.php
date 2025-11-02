

<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 4 & Angular 8
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">

<!-- begin::Head -->

<head>
    <base href="../../../">
    <meta charset="utf-8" />
    <title>KPMG | Login</title>
    <meta name="description" content="Login page example">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!--end::Fonts -->

    <!--begin::Page Custom Styles(used by this page) -->
    <link href="assets/css/pages/login/login-3.css" rel="stylesheet" type="text/css" />

    <!--end::Page Custom Styles -->

    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />

    <!--end::Global Theme Styles -->

    <!--begin::Layout Skins(used by all pages) -->
    <link href="assets/css/skins/header/base/light.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/skins/brand/dark.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/skins/aside/dark.css" rel="stylesheet" type="text/css" />

    <!--end::Layout Skins -->
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    
    <!-- Custom Login Styling -->
    <style>
        /* Make logo smaller */
        .kt-login__logo img {
            max-width: 120px !important;
            height: auto !important;
        }
        
        /* Input group with icons */
        .input-group {
            position: relative !important;
            margin-bottom: 20px !important;
        }
        
        .input-group::before {
            position: absolute !important;
            left: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            z-index: 10 !important;
            color: #7c8db5 !important;
            font-family: "Font Awesome 5 Free" !important;
            font-weight: 900 !important;
            font-size: 16px !important;
            transition: color 0.3s ease !important;
        }
        
        /* Email input icon */
        .input-group:first-of-type::before {
            content: "\f007" !important; /* user icon */
        }
        
        /* Password input icon */
        .input-group:last-of-type::before {
            content: "\f023" !important; /* lock icon */
        }
        
        /* Keep icons visible when input is focused */
        .input-group:focus-within::before {
            color: #5d78ff !important;
        }
        
        /* Add padding to input to make room for icon */
        .input-group .form-control {
            padding-left: 40px !important;
            border: 2px solid #e2e5ec !important;
            border-radius: 6px !important;
            height: 45px !important;
            font-size: 14px !important;
            transition: all 0.3s ease !important;
        }
        
        /* Input focus styling */
        .input-group .form-control:focus {
            border-color: #5d78ff !important;
            box-shadow: 0 0 0 0.2rem rgba(93, 120, 255, 0.25) !important;
            outline: none !important;
        }
        
        /* Enhanced login container styling */
        .kt-login__container {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px) !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
</head>

<!-- end::Head -->

<!-- begin::Body -->

<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v3 kt-login--signin" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" style="background-image: url(assets/bg.jpg);">
            <div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
                <div class="kt-login__container">
                    <div class="kt-login__logo">
                        <img src="assets/sigma_favico.png">
                    </div>
                    <div class="kt-login__signin">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Employee Portal Access</h3>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-outline-danger fade show">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(session('nouser'))
                            <div class="alert alert-outline-danger fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">Wrong Email or Password</div>
                            </div>
                        @endif
                        @if(session('unauthorized'))
                            <div class="alert alert-outline-danger fade show" role="alert">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">Unauthorized to Enter admin panel</div>
                            </div>
                        @endif
                        <form class="kt-form" method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off" form="loginForm" required>
                            </div>
                            <div class="input-group">
                                <input class="form-control" type="password" placeholder="Password" name="password" form="loginForm" required>
                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_signin_submit" form="loginForm" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="kt-login__forgot">
                        <div class="kt-login__head">
                            <h3 class="kt-login__title">Forgotten Password ?</h3>
                            <div class="kt-login__desc">Enter your email to reset your password:</div>
                        </div>
                        <!-- <form class="kt-form" action="">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Email" name="email" id="kt_email" autocomplete="off">
                            </div>
                            <div class="kt-login__actions">
                                <button id="kt_login_forgot_submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Request</button>&nbsp;&nbsp;
                                <button id="kt_login_forgot_cancel" class="btn btn-light btn-elevate kt-login__btn-secondary">Cancel</button>
                            </div>
                        </form> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": [
                    "#c5cbe3",
                    "#a1a8c3",
                    "#3d4465",
                    "#3e4466"
                ],
                "shape": [
                    "#f0f3ff",
                    "#d9dffa",
                    "#afb4d4",
                    "#646c9a"
                ]
            }
        }
    };
</script>

<!-- end::Global Config -->

<!--begin::Global Theme Bundle(used by all pages) -->
<script src="assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
<script src="assets/js/scripts.bundle.js" type="text/javascript"></script>

<!--end::Global Theme Bundle -->

<!--begin::Page Scripts(used by this page) -->
<!-- <script src="assets/js/pages/custom/login/login-general.js" type="text/javascript"></script> -->

<!--end::Page Scripts -->
</body>

<!-- end::Body -->

</html>
