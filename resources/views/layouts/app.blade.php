<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400..700&display=swap" rel="stylesheet" crossorigin="anonymous">


    <title>{{ $pageSlug ?? config('site_vars.projectNameShort') }}</title>

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('custom-CSS-JS/style1.css') }}">
<link rel="stylesheet" href="{{ asset('custom-CSS-JS/style2.css') }}">
    <!-- Font Awesome 6+ -->
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />


    <!-- Georgia is a system font, not a Google Font - removed invalid link that was causing 403 -->


<!-- ############################################################# -->

<!--  -----------------------ANIMATIONS------------------------ -->

<!-- ############################################################# -->


<!-- JS -->
<script src="{{ asset('custom-CSS-JS/animation.js') }}"></script>
<script src="{{ asset('custom-CSS-JS/script2.js') }}"></script>
<!-- ############################################################# -->


    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Noto+Naskh+Arabic:wght@400..700&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet" crossorigin="anonymous">

    <!-- Core JavaScript Libraries (Load jQuery first to prevent $ undefined errors) -->
    <script src="{{ asset('white') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('white') }}/js/core/popper.min.js"></script>

    <!-- Yaz 29 sep 2025 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js')}}" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script> -->
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
        .dropdown-menu .dropdown-menu-right {
            transform: translate3d(0px, 34px, 0px) !important;
        }


        .noto-naskh-arabic {
            font-family: "Noto Naskh Arabic", serif;
            font-optical-sizing: auto;
            font-weight: 600;
            font-style: normal;
        }
        .dtr-control::after{display: none !important;}
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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,300" rel="stylesheet" type="text/css" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@500&display=swap" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Raleway&family=Rubik:wght@500&display=swap"
          rel="stylesheet" crossorigin="anonymous">
    <link href="http://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" crossorigin="anonymous"/>
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">

    <!-- Core Framework CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          media="all"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Bootstrap Select Fixes -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select-fix.css') }}">

    <!-- Third-party/Plugin CSS -->
    <link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
    <link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets') }}/css/sweetalert2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- Theme CSS -->
    <link href="{{ asset('assets') }}/css/white-dashboard.css?v=1.0.0" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/theme.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    <link href="{{ asset('css/responsive-images.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/callouts.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/custom-styling.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-fix.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-fullwidth-fix.css" rel="stylesheet"/>
    <link href="{{ asset('css/sidebar-collapse.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-layout-improvements.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/impersonation-banner.css" rel="stylesheet"/>
    <link href="{{ asset('css') }}/georgia-font.css" rel="stylesheet"/>
    <link href="{{ asset('css/ysh-custom-css/machine-images.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/processing-overlay.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/impersonate-button.css') }}" rel="stylesheet"/>
    <link rel="icon" type="image/png" href="{{asset('assets/sigma_favico.png')}}"/>

    <!-- Dynamic styling -->
    @include('layouts.dynamicStyling')

    <!-- Page-specific CSS -->
    @stack('css')

</head>
{{--<div class="overlay" id="overlay"></div>--}}
<body {{--onload="myFunction()"--}} class="white-content{{$class ?? ''}}">
<!-- Floating buttons container -->
<div class="floating-buttons-container">
    @if(in_array(request()->getHost(), ['localhost', '127.0.0.1']))
        <div class="floating-badge localhost-badge">
            LOCAL HOST
        </div>
    @endif
    
    @auth()
        @if(auth()->user()->is_admin && isset($activeEmployees) && $activeEmployees->count() > 0)
            <!-- Impersonation Button -->
            <div class="impersonate-button-wrapper">
                <button class="impersonate-button" id="impersonateButton" title="Sign in as User">
                    <i class="fas fa-user-secret"></i>
                    <span class="impersonate-button-text">Sign In As</span>
                </button>
                
                <!-- Employee List Dropdown -->
                <div class="employee-list-dropdown" id="employeeListDropdown">
                    <div class="employee-list-header">
                        <i class="fas fa-users"></i>
                        <span>Select Employee</span>
                    </div>
                    <div class="employee-list-search">
                        <input type="text" id="employeeSearchInput" placeholder="Search employees..." autocomplete="off">
                    </div>
                    <div class="employee-list-items" id="employeeListItems">
                        @foreach($activeEmployees as $employee)
                            <a href="{{ route('impersonate.start', $employee->id) }}" class="employee-item" data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}">
                                <div class="employee-item-avatar">
                                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                                </div>
                                <div class="employee-item-info">
                                    <div class="employee-item-name">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                    @if($employee->name_initials)
                                        <div class="employee-item-initials">{{ $employee->name_initials }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if($activeEmployees->isEmpty())
                        <div class="employee-list-empty">
                            No employees available
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endauth
</div>
<div class="processing-overlay" id="processingOverlay">
    <div class="processing-spinner"></div>
    <div class="processing-done">
        <i class="fa fa-check-circle"></i>
    </div>
</div>


@auth()
    <!-- Impersonation Banner -->
    @if(session()->has('impersonator_id'))
        <div class="impersonation-banner">
            <div class="impersonation-banner-content">
                <div class="impersonation-info">
                    <svg class="impersonation-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <div class="impersonation-text">
                        <span class="impersonation-label">Viewing as</span>
                        <span class="impersonation-user">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    </div>
                </div>
                <a href="{{ route('impersonate.leave') }}" class="impersonation-return-btn">
                    <svg class="return-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    Return to Admin Account
                </a>
            </div>
        </div>
    @endif

    <!-- Loading Overlay -->
    <!-- Loading Spinner Overlay -->
    {{--    <div class="YSH-spinner-overlay" id="loadingOverlay" style="display: none;">--}}
    {{--        <div class="YSH-spinner">--}}
    {{--            <div></div>--}}
    {{--            <div></div>--}}
    {{--            <div></div>--}}
    {{--            <div></div>--}}
    {{--            <div></div>--}}
    {{--            <div></div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="wrapper" {{--onload="myFunction()"--}}>
        @include('layouts.navbars.leftsidebar')

        <div class="main-panel">
            @include('layouts.navbars.navbar')


            {{--<div id="loader"></div>--}}
            <div class="content" {{--style="display:none;"  id="myDiv"--}}>
                @if (session()->has('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session()->get('error') }}
                    </div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session()->get('success') }}
                    </div>
                @endif

                @yield('content')

            </div>
        </div>

    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" {{-- style="display: none;"  id="myDiv"--}}>
        @csrf
    </form>
@else

    @include('layouts.navbars.navbar')


    <div class="wrapper wrapper-full-page animate-bottom" {{-- style="display:none;"--}} >
        <div class="overlay" id="overlay"></div>
        <div class="full-page {{ $contentClass ?? '' }}">

            <div class="content">

                <div class="container">

                </div>
                @yield('content')
            </div>
        </div>
    </div>

@endauth

<script src="{{ asset('js/sidebar-collapse.js') }}"></script>
</body>
@include('layouts.footer')
<script src="{{ asset('js/responsive-images.js') }}"></script>
<script>
    function showProcessingOverlay() {
        const processingOverlay = document.getElementById('processingOverlay');
        if (processingOverlay) {
            processingOverlay.classList.add('active');
        }
    }

    function showDoneAndReload() {
        const processingOverlay = document.getElementById('processingOverlay');
        if (processingOverlay) {
            processingOverlay.classList.add('done');
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                showProcessingOverlay();
            });
        });

        // Impersonate Button Functionality
        const impersonateButton = document.getElementById('impersonateButton');
        const employeeListDropdown = document.getElementById('employeeListDropdown');
        const employeeSearchInput = document.getElementById('employeeSearchInput');
        const employeeItems = document.querySelectorAll('.employee-item');

        if (impersonateButton && employeeListDropdown) {
            // Toggle dropdown on button click
            impersonateButton.addEventListener('click', function(e) {
                e.stopPropagation();
                employeeListDropdown.classList.toggle('active');
                
                // Focus search input when dropdown opens
                if (employeeListDropdown.classList.contains('active') && employeeSearchInput) {
                    setTimeout(() => {
                        employeeSearchInput.focus();
                    }, 100);
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!impersonateButton.contains(e.target) && !employeeListDropdown.contains(e.target)) {
                    employeeListDropdown.classList.remove('active');
                }
            });

            // Search functionality
            if (employeeSearchInput) {
                employeeSearchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    
                    employeeItems.forEach(item => {
                        const employeeName = item.getAttribute('data-name') || '';
                        if (employeeName.includes(searchTerm)) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                });
            }

            // Prevent dropdown from closing when clicking inside
            employeeListDropdown.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>
</html>