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
    @php
        $isReportRoute = request()->is('reports*');
        $preserveNativeTableHeaders = request()->routeIs('admin-dashboard-v2') || request()->is('operations-dashboard');
    @endphp

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

            /* Primary accent */
            --color-accent: #6366f1;
            --color-accent-light: #818cf8;
            --color-accent-muted: rgba(99, 102, 241, 0.12);

            /* Semantic */
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;

            /* Neutrals */
            --color-text-primary: #0f172a;
            --color-text-secondary: #64748b;
            --color-text-muted: #94a3b8;
            --color-surface: #ffffff;
            --color-surface-raised: #f8fafc;
            --color-border: rgba(0,0,0,0.08);

            --main-blue: #007bff;
            --main-orange: var(--color-warning);
            --main-green: var(--color-success);
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
            background: color-mix(in srgb, var(--color-text-primary) 50%, transparent);
            display: none;
            z-index: 5;
        }

        .overlay.active {
            display: flex;
        }

        .no-scroll {
            overflow: hidden;
        }

        /* Notes typography: metadata normal, note body bold */
        .noteHeader,
        .noteHeader b {
            font-weight: 400 !important;
        }

        .noteText {
            font-weight: 700 !important;
            white-space: wrap;
            text-align: right;
            font-family: 'Cairo', sans-serif;
            display: block !important;
        color:black;
            font-weight: 500;
        }


        /* Optional user-controlled font-size locks (disabled by default) */
        body.pref-lock-dialog-fonts .modal,
        body.pref-lock-dialog-fonts .modal *,
        body.pref-lock-dialog-fonts .dialog-popup-card,
        body.pref-lock-dialog-fonts .dialog-popup-card * {
            -webkit-text-size-adjust: 100% !important;
            text-size-adjust: 100% !important;
        }

        body.pref-lock-table-fonts table,
        body.pref-lock-table-fonts table * {
            -webkit-text-size-adjust: 100% !important;
            text-size-adjust: 100% !important;
        }

        body.pref-lock-other-fonts .content,
        body.pref-lock-other-fonts .content *:not(table):not(table *):not(.modal):not(.modal *) {
            -webkit-text-size-adjust: 100% !important;
            text-size-adjust: 100% !important;
        }

    </style>

    <!-- Montserrat from cdnfonts (separate CDN) -->
    <link href="https://fonts.cdnfonts.com/css/montserrat" rel="stylesheet">

    <!-- Core Framework CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" media="all">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-select/bootstrap-select.min.css') }}?v={{ filemtime(public_path('assets/plugins/bootstrap-select/bootstrap-select.min.css')) }}">

    <!-- Bootstrap Select Fixes -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select-fix.css') }}?v={{ filemtime(public_path('assets/css/bootstrap-select-fix.css')) }}">

    <!-- Third-party/Plugin CSS -->
    <link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/css/intel-dashboard-css/miscellaneous/lightgallery/lightgallery.bundle.css') }}" rel="stylesheet">
    <style>
        .lg-icon {
            font-family: "Segoe UI Symbol", "Segoe UI", Arial, sans-serif !important;
        }

        .lg-actions .lg-prev:after,
        .lg-actions .lg-next:before,
        .lg-toolbar .lg-close:after,
        .lg-toolbar .lg-download:after,
        .lg-toolbar .lg-fullscreen:after,
        .lg-autoplay-button:after,
        .lg-show-autoplay .lg-autoplay-button:after,
        #lg-zoom-in:after,
        #lg-zoom-out:after,
        #lg-actual-size:after,
        .lg-outer #lg-share:after {
            font-family: "Segoe UI Symbol", "Segoe UI", Arial, sans-serif !important;
            font-weight: 400 !important;
        }

        .lg-actions .lg-prev:after {
            content: "\2039" !important;
        }

        .lg-actions .lg-next:before {
            content: "\203A" !important;
        }

        .lg-toolbar .lg-close:after {
            content: "\00D7" !important;
            font-size: 1.7rem !important;
        }

        .lg-toolbar .lg-download:after {
            content: "\2193" !important;
        }

        .lg-toolbar .lg-fullscreen:after {
            content: "\26F6" !important;
        }

        .lg-autoplay-button:after {
            content: "\25B6" !important;
        }

        .lg-show-autoplay .lg-autoplay-button:after {
            content: "\23F8" !important;
        }

        #lg-zoom-in:after {
            content: "\002B" !important;
        }

        #lg-zoom-out:after {
            content: "\2212" !important;
        }

        #lg-actual-size:after {
            content: "\25A1" !important;
        }

        .lg-outer #lg-share:after {
            content: "\2197" !important;
        }

        .lg-outer .lg-item {
            background: none !important;
        }

        .lg-outer .lg-item::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 34px;
            height: 34px;
            margin-top: -17px;
            margin-left: -17px;
            border: 3px solid color-mix(in srgb, var(--color-surface) 26%, transparent);
            border-top-color: color-mix(in srgb, var(--color-surface) 92%, transparent);
            border-radius: 50%;
            animation: sigma-lg-spin 0.8s linear infinite;
            pointer-events: none;
        }

        .lg-outer .lg-item.lg-complete::after {
            display: none;
        }

        @keyframes sigma-lg-spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <link href="//cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets') }}/css/sweetalert2.min.css" rel="stylesheet"/>
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}?v={{ filemtime(public_path('assets/plugins/select2/select2.min.css')) }}" rel="stylesheet" />



    <!-- Theme CSS -->
    <link href="{{ asset('assets') }}/css/white-dashboard.css?v=1.0.0" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/theme.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet"/>

    <!-- Custom CSS -->
    @php
        $customStylingCssPath = public_path('assets/css/custom-styling.css');
        $customStylingCssUrl = asset('assets/css/custom-styling.css');
        if (file_exists($customStylingCssPath)) {
            $customStylingCssUrl .= '?v=' . filemtime($customStylingCssPath);
        }
    @endphp
    <link href="{{ asset('css/responsive-images.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/callouts.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
    <noscript><link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/></noscript>
    <link href="{{ $customStylingCssUrl }}" rel="stylesheet"/>
    <noscript><link href="{{ $customStylingCssUrl }}" rel="stylesheet"/></noscript>
    <link href="{{ asset('assets') }}/css/sidebar-fix.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-fullwidth-fix.css" rel="stylesheet"/>
    <link href="{{ asset('css/sidebar-collapse.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/sidebar-layout-improvements.css" rel="stylesheet"/>

    <link href="{{ asset('css') }}/georgia-font.css" rel="stylesheet"/>
    <link href="{{ asset('css/ysh-custom-css/machine-images.css') }}" rel="stylesheet"/>
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
            background: linear-gradient(180deg, var(--color-accent-light) 0%, var(--color-accent) 100%);
            box-shadow: 0 0 6px var(--color-accent-muted);
            transition: all 0.15s ease;
        }
        .dt-colresizable-col:hover::before {
            width: 4px;
            background: linear-gradient(180deg, var(--color-accent-light) 0%, var(--color-accent) 100%);
            box-shadow: 0 0 10px var(--color-accent-muted);
        }
        .dt-colresizable-col:active::before {
            width: 5px;
            background: var(--color-accent-light);
        }
        /* Show handles when resize mode is active */
        body.col-resize-active .dt-colresizable-col {
            opacity: 1;
            pointer-events: auto;
        }
        /* Keep legacy overlays non-interactive without removing valid modal overlays from layout */
        [class*="overlay"]:not(.sidebar-overlay):not(.YSH-slide-overlay):not(.sigma-dialog-overlay),
        [id*="overlay"]:not(#sidebarOverlay):not([id^="YSH-slide-overlay-"]) {
            pointer-events: none !important;
        }

    </style>
    @if (!$isReportRoute)
        <link href="{{ asset('assets/css/sigma-standard-theme.css') }}?v={{ filemtime(public_path('assets/css/sigma-standard-theme.css')) }}" rel="stylesheet"/>
    @endif
</head>
<div class="sigma-loading-screen" id="sigma-loading-screen" aria-hidden="true">
    <div class="sigma-loading-screen__content" role="status" aria-live="polite" aria-label="Loading">
        <div class="sigma-processing-indicator">
            <div class="sigma-pyramid-loader sigma-processing-indicator__loader" aria-hidden="true">
                <div class="sigma-pyramid-loader__wrapper">
                    <span class="sigma-pyramid-loader__side sigma-pyramid-loader__side--1"></span>
                    <span class="sigma-pyramid-loader__side sigma-pyramid-loader__side--2"></span>
                    <span class="sigma-pyramid-loader__side sigma-pyramid-loader__side--3"></span>
                    <span class="sigma-pyramid-loader__side sigma-pyramid-loader__side--4"></span>
                    <span class="sigma-pyramid-loader__shadow"></span>
                </div>
            </div>
            <div class="sigma-processing-indicator__text" data-text="Processing...">Processing...</div>
        </div>
    </div>
</div>
@include('components.sigma-toasts')
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
            <div class="content{{ $isReportRoute ? '' : ' sigma-standard-theme' }}{{ !$isReportRoute && $preserveNativeTableHeaders ? ' sigma-preserve-table-headers' : '' }}" {{--style="display:none;"  id="myDiv"--}}>
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

            <div class="content{{ $isReportRoute ? '' : ' sigma-standard-theme' }}{{ !$isReportRoute && $preserveNativeTableHeaders ? ' sigma-preserve-table-headers' : '' }}">

                <div class="container">

                </div>
                @yield('content')
            </div>
        </div>
    </div>

@endauth

<script>
    window.__sigmaTableWidthPrefs = @json($sigmaTableWidthPrefs ?? []);
    window.__sigmaTableWidthDefaults = @json($sigmaTableWidthDefaults ?? []);
</script>
<script>
    // Disable Bootstrap tooltips completely
    if (typeof jQuery !== 'undefined') {
        jQuery.fn.tooltip = function() { return this; };
        jQuery.fn.popover = function() { return this; };
    }

    window.addEventListener('load', function() {
        if (typeof window.hideLoadingScreen === 'function') {
            window.hideLoadingScreen();
        }
    });

    (function() {
        const screen = document.getElementById('sigma-loading-screen');

        function showLoadingScreen() {
            if (!screen) {
                if (typeof window.showLoadingIndicator === 'function') {
                    window.showLoadingIndicator();
                }
                return;
            }
            screen.classList.add('is-active');
            screen.setAttribute('aria-hidden', 'false');
            document.body.classList.add('sigma-loading-active');
        }

        function hideLoadingScreen() {
            if (screen) {
                screen.classList.remove('is-active');
                screen.setAttribute('aria-hidden', 'true');
            }

            const legacyIndicator = document.getElementById('loading-indicator');
            if (legacyIndicator) {
                legacyIndicator.style.display = 'none';
                legacyIndicator.style.pointerEvents = 'none';
            }

            document.body.classList.remove('sigma-loading-active');
        }

        window.showLoadingScreen = showLoadingScreen;
        window.hideLoadingScreen = hideLoadingScreen;

        function clearTransientPageBlockers() {
            hideLoadingScreen();

            const hasOpenModal = document.querySelector('.modal.show, .sigma-workflow-modal.active, .YSH-slide-overlay.YSH-active, .ysh-case-slide-modal.YSH-active');
            if (!hasOpenModal) {
                document.querySelectorAll('.modal-backdrop').forEach(function(backdrop) {
                    backdrop.remove();
                });
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            }

            const sidebarIsOpen = document.body.classList.contains('sidebar-expanded') || document.documentElement.classList.contains('nav-open');
            if (!sidebarIsOpen) {
                document.body.classList.remove('no-scroll');
                document.documentElement.classList.remove('no-scroll');
                const sidebarOverlay = document.getElementById('sidebarOverlay');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active', 'sidebar-overlay--visible');
                    sidebarOverlay.setAttribute('aria-hidden', 'true');
                }
            }
        }

        window.clearTransientPageBlockers = clearTransientPageBlockers;

        document.addEventListener('submit', function(event) {
            const form = event.target;
            if (!form || !form.matches || !form.matches('form')) {
                return;
            }
            if (event.defaultPrevented) {
                return;
            }
            if (form.hasAttribute('data-skip-loading-screen')) {
                return;
            }
            showLoadingScreen();
        });
    })();

    window.addEventListener('pageshow', function() {
        if (typeof window.clearTransientPageBlockers === 'function') {
            window.clearTransientPageBlockers();
            window.setTimeout(window.clearTransientPageBlockers, 250);
        } else if (typeof window.hideLoadingScreen === 'function') {
            window.hideLoadingScreen();
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
            var clearAction = window.sigmaTableWidthStore && typeof window.sigmaTableWidthStore.clearAll === 'function'
                ? window.sigmaTableWidthStore.clearAll()
                : Promise.resolve([]);

            clearAction.then(function (cleared) {
                if (!Array.isArray(cleared)) {
                    cleared = [];
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
            });
        }
    });
</script>
<script src="{{ asset('js/table-width-preferences.js') }}"></script>
<script src="{{ asset('js/user-preferences.js') }}"></script>
 <script src="{{ asset('js/sidebar-collapse.js') }}?v={{ filemtime(public_path('js/sidebar-collapse.js')) }}"></script>
<script src="{{ asset('js/sigma-sticky-layout.js') }}"></script>
<script>
    // Robust Bootstrap Select (selectpicker) initialization
    // Ensures initialization happens once and correctly, including for dynamically added elements.
    const sigmaBodyMountedSelectPickerSelector = '.selectpicker, select[data-container="body"]';

    function repositionBodyMountedSelectPicker(selectElement) {
        const $select = jQuery(selectElement);
        const picker = $select.data('selectpicker');

        if (!picker || !picker.options || picker.options.container !== 'body' || !picker.$bsContainer || !picker.$bsContainer.length) {
            return;
        }

        const $container = picker.$bsContainer;
        const $button = picker.$button && picker.$button.length
            ? picker.$button
            : picker.$newElement.find('> button');
        const $menu = picker.$menu && picker.$menu.length
            ? picker.$menu
            : $container.find('.dropdown-menu').first();

        if (!$button.length || !$menu.length || !$button.is(':visible')) {
            return;
        }

        const offset = $button.offset();
        if (!offset) {
            return;
        }

        const buttonHeight = $button.outerHeight();
        const buttonWidth = $button.outerWidth();
        const menuHeight = $menu.outerHeight();
        const viewportTop = jQuery(window).scrollTop();
        const viewportBottom = viewportTop + jQuery(window).height();
        const spaceBelow = viewportBottom - (offset.top + buttonHeight);
        const spaceAbove = offset.top - viewportTop;
        const openAbove = menuHeight > 0 && spaceBelow < menuHeight && spaceAbove > spaceBelow;
        const top = openAbove ? Math.max(viewportTop, offset.top - menuHeight) : offset.top + buttonHeight;

        $container.css({
            position: 'absolute',
            top: top,
            left: offset.left,
            width: buttonWidth,
            zIndex: 1060
        });

        $menu.css({
            minWidth: buttonWidth
        });

        $container.toggleClass('dropup', openAbove);
        $container.toggleClass('dropdown', !openAbove);
    }

    function scheduleBodyMountedSelectPickerReposition(selectElement) {
        const run = function () {
            repositionBodyMountedSelectPicker(selectElement);
        };

        if (window.requestAnimationFrame) {
            window.requestAnimationFrame(run);
            return;
        }

        setTimeout(run, 0);
    }

    function bindSelectPickerBodyPositioning() {
        if (window.__sigmaSelectPickerBodyPositioningBound) {
            return;
        }

        window.__sigmaSelectPickerBodyPositioningBound = true;

        jQuery(document)
            .on('loaded.bs.select shown.bs.select rendered.bs.select refreshed.bs.select', sigmaBodyMountedSelectPickerSelector, function() {
                scheduleBodyMountedSelectPickerReposition(this);
            })
            .on('hide.bs.select hidden.bs.select', sigmaBodyMountedSelectPickerSelector, function() {
                const picker = jQuery(this).data('selectpicker');
                if (picker && picker.$bsContainer) {
                    picker.$bsContainer.removeClass('dropup');
                }
            });

        jQuery(window).on('resize.sigmaSelectPickerBodyPosition scroll.sigmaSelectPickerBodyPosition', function() {
            jQuery(sigmaBodyMountedSelectPickerSelector).each(function() {
                const picker = jQuery(this).data('selectpicker');
                if (picker && picker.$bsContainer && picker.$bsContainer.hasClass('show')) {
                    scheduleBodyMountedSelectPickerReposition(this);
                }
            });
        });
    }

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

        bindSelectPickerBodyPositioning();

        jQuery('.selectpicker').each(function() {
            const $select = jQuery(this);

            // Skip if already initialized - don't destroy existing instances
            if ($select.data('selectpicker')) {
                scheduleBodyMountedSelectPickerReposition(this);
                return; // Already initialized, skip
            }

            try {
                $select.selectpicker();
                scheduleBodyMountedSelectPickerReposition(this);
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
        if (typeof window.showLoadingScreen === 'function') {
            window.showLoadingScreen();
        }
    }

    function showDoneAndReload() {
        if (typeof window.hideLoadingScreen === 'function') {
            window.hideLoadingScreen();
        }
    }
</script>
</html>
