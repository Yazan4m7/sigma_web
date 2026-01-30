<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdn.datatables.net" crossorigin>
    <link rel="preconnect" href="https://fonts.cdnfonts.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.colResize.css') }}">
    <!-- Consolidated Google Fonts (Step 6 optimization) -->
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Noto+Naskh+Arabic:wght@400..700&family=Tajawal:wght@200;300;400;500;700;800;900&family=Open+Sans:wght@300;400;600&family=Rubik:wght@500&family=Raleway&family=Poppins:wght@200;300;400;600;700;800&family=Cairo:wght@400;700&display=swap" rel="stylesheet" crossorigin="anonymous">


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
<script src="{{ asset('custom-CSS-JS/animation.js') }}" defer></script>
<script src="{{ asset('custom-CSS-JS/script2.js') }}" defer></script>
<!-- ############################################################# -->



    <!-- Core JavaScript Libraries (Load jQuery first to prevent $ undefined errors) -->
    <script src="{{ asset('white') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('white') }}/js/core/popper.min.js"></script>

    <!-- Bootstrap & Bootstrap-Select loaded in footer.blade.php -->
    <!-- Reset/Base CSS -->


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
        .pageTitleContainer {
            background: linear-gradient(272deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0) 50%) !important;
            background: transparent;
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

    <!-- Montserrat from cdnfonts (separate CDN) -->
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">

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
    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet" media="print" onload="this.media='all'"/>
    <noscript><link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/></noscript>
    <link href="{{ asset('assets') }}/css/custom-styling.css" rel="stylesheet" media="print" onload="this.media='all'"/>
    <noscript><link href="{{ asset('assets') }}/css/custom-styling.css" rel="stylesheet"/></noscript>
    <link href="{{ asset('assets') }}/css/sidebar-fix.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-fullwidth-fix.css" rel="stylesheet"/>
    <link href="{{ asset('css/sidebar-collapse.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-layout-improvements.css" rel="stylesheet"/>

    <link href="{{ asset('css') }}/georgia-font.css" rel="stylesheet"/>
    <link href="{{ asset('css/ysh-custom-css/machine-images.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/processing-overlay.css') }}" rel="stylesheet"/>

    <link rel="icon" type="image/png" href="{{asset('assets/sigma_favico.png')}}"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <!-- Dynamic styling -->
    @include('layouts.dynamicStyling')

    <!-- Page-specific CSS -->
    @stack('css')
    <style>


        /* Disable Bootstrap tooltips globally */
        .tooltip {
            display: none !important;
        }

        /* Column resize handles - hidden by default, wide hit area */
        .dt-colresizable-col {
            width: 15px !important;
            margin-left: -7px;
            opacity: 0;
            transition: opacity 0.2s ease;
            pointer-events: none;
            background: transparent;
        }
        .dt-colresizable-col::before {
            content: '';
            position: absolute;
            top: 4px;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            border-radius: 3px;
            background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 0 6px rgba(59, 130, 246, 0.4);
            transition: all 0.15s ease;
        }
        .dt-colresizable-col:hover::before {
            width: 4px;
            background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
            box-shadow: 0 0 10px rgba(96, 165, 250, 0.6);
        }
        .dt-colresizable-col:active::before {
            width: 5px;
            background: #60a5fa;
        }
        /* Show handles when resize mode is active */
        body.col-resize-active .dt-colresizable-col {
            opacity: 1;
            pointer-events: auto;
        }
        /* Disable legacy overlays but keep the sidebar overlay available */
        [class*="overlay"]:not(.sidebar-overlay):not(.YSH-slide-overlay),
        [id*="overlay"]:not(#sidebarOverlay):not([id^="YSH-slide-overlay-"]) {
            display: none !important;
            pointer-events: none !important;
        }

        #loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
{{--<div class="overlay" id="overlay"></div>--}}@auth()
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
        <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>

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
        {{-- Overlay removed --}}
        <div class="full-page {{ $contentClass ?? '' }}">

            <div class="content">

                <div class="container">

                </div>
                @yield('content')
            </div>
        </div>
    </div>

@endauth

<script>
    // Disable Bootstrap tooltips completely
    if (typeof jQuery !== 'undefined') {
        jQuery.fn.tooltip = function() { return this; };
        jQuery.fn.popover = function() { return this; };
    }

    window.addEventListener('load', function() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.display = 'none';
        }
    });
    // F2: Toggle column resize handles (non-dashboard pages)
    // F3: Clear all saved column widths
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F2' && !document.getElementById('columnConfigPanel')) {
            // Only apply on non-dashboard pages
            e.preventDefault();
            document.body.classList.toggle('col-resize-active');
        } else if (e.key === 'F3' && !document.getElementById('columnConfigPanel')) {
            // F3 on non-dashboard pages - clear all widths
            e.preventDefault();
            var cleared = [];
            for (var key in localStorage) {
                if (key.endsWith('_widths')) {
                    cleared.push(key);
                    localStorage.removeItem(key);
                }
            }
            if (cleared.length > 0) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Reset Complete',
                        html: 'Cleared: <br>' + cleared.join('<br>') + '<br><br>Refresh to see default widths.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    alert('Cleared: ' + cleared.join(', ') + '\n\nRefresh to see defaults.');
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Saved Widths',
                        text: 'No saved column widths found.',
                        timer: 2000
                    });
                } else {
                    alert('No saved column widths found.');
                }
            }
        }
    });
</script>
 <script src="{{ asset('js/sidebar-collapse.js') }}"></script>
<script src="{{ asset('js/sigma-sticky-layout.js') }}"></script>
<script>
    // Robust Bootstrap Select (selectpicker) initialization
    // Ensures initialization happens once and correctly, including for dynamically added elements.
    function initializeSelectPicker() {
        // console.log('Attempting to initialize selectpickers...');

        // Check if jQuery and Bootstrap Select plugin are loaded
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.selectpicker === 'undefined') {
            // console.warn('jQuery or Bootstrap Select plugin not loaded. Deferring initialization.');
            // Try again after a short delay if dependencies aren't ready
            setTimeout(initializeSelectPicker, 100);
            return;
        }

        // Fix for Bootstrap 4/5 compatibility (if necessary)
        if (jQuery.fn.selectpicker.Constructor) {
            jQuery.fn.selectpicker.Constructor.BootstrapVersion = '4'; // Adjust if using Bootstrap 5
        }

        jQuery('.selectpicker').each(function() {
            const $select = jQuery(this);

            // Skip if already initialized - don't destroy existing instances
            if ($select.data('selectpicker')) {
                return; // Already initialized, skip
            }

            try {
                $select.selectpicker();
            } catch (e) {
                console.error('Failed to initialize selectpicker:', this.name || this.id || this, e);
                $select.addClass('form-control');
            }
        });
    }

    // Initialize selectpickers on document ready for initial page load
    jQuery(document).ready(function() {
        initializeSelectPicker();
    });

    // MutationObserver disabled - was causing double initialization issues
    // If you need dynamic selectpicker init, call initializeSelectPicker() manually after adding elements
</script>
</body>
@include('layouts.footer')
<script src="{{ asset('js/responsive-images.js') }}"></script>
<script>
    function showProcessingOverlay() {
        return; // overlays disabled
    }

    function showDoneAndReload() {
        return; // overlays disabled
    }

    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                showProcessingOverlay();
            });
        });

    });
</script>
</html>
