@extends('layouts.app', ['pageSlug' => config('site_vars.labWorkFlowLabel')])
@php
    // Load global configuration
    $deviceConfig = config('app_config.device_images', [
        'width' => '100%',
        'max_width' => '180px',
        'height' => 'auto',
        'padding' => '10px',
        'border_radius' => '8px',
        'hover_effect' => true,
        'background' => 'transparent',
    ]);
@endphp


@push('css')
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/5.0.4/css/fixedColumns.dataTables.css" rel="stylesheet">


    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet"/>
{{--    <link href="{{ asset('assets') }}/css/devices-dialog-fix.css" rel="stylesheet"/>--}}
    <link href="{{ asset('assets') }}/css/ysh-custom-css/OperationsDashboardStyling.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/active-cases.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/waiting-dialog.css" rel="stylesheet"/>
    <link href="{{ asset('assets') }}/css/v3styles.css" rel="stylesheet">
    <!-- Responsive CSS - Mobile-first approach for full device compatibility -->
    <link href="{{ asset('assets') }}/css/responsive.css" rel="stylesheet">
    <!-- Operations Dashboard Navigation - Responsive CSS for mobile stage icons -->
    <link href="{{ asset('assets') }}/css/operations-nav-responsive.css" rel="stylesheet"/>

   <style>
       @media  (max-width: 480px){
       .sigma-workflow-dialog {
           max-width: none !important;
           width: auto !important;
           min-width: -webkit-fill-available;
           margin: 0 15px;
           /* Minimum width for proper machine display */
       }}
       @media  (max-width: 700px){
       .sigma-workflow-dialog {
           max-width: none !important;
           width: auto !important;

           /* Minimum width for proper machine display */
       }}
       @media  (min-width: 700px){
           .sigma-workflow-dialog {
               max-width: none !important;
               width: 45%

               /* Minimum width for proper machine display */
           }}

    /* Use Animate.css for Case Completion modal */
    .modal.fade .modal-dialog {
        transform: none !important;
        /* Will be animated by Animate.css classes */
    }

    .modal-content {
        border-radius: 25px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        border: none;
    }

    /* Modal footer rounded bottom corners */
    .modal-footer {
        border-bottom-left-radius: 25px !important;
        border-bottom-right-radius: 25px !important;
    }

    /* Modal header styling with divider */
    .modal-header {
        border-bottom: 1px solid #dee2e6 !important;
        padding-bottom: 12px;
    }

    /* Modal title styling */
    .modal-title {
        color: #2d5f6d;
        font-weight: 600;
        font-size: 18px;
        margin-bottom: 0;
    }

    /* Skip to delivery icon styling */
    .skip-to-delivery-icon {
        font-size: 20px;
        color: #2d5f6d;
        transition: color 0.3s ease;
    }
    .skip-to-delivery-icon:hover {
        color: #1a3d47;
    }

    /* Close button styling - more visible */
    .modal-header button.close {
        font-size: 32px;
        font-weight: 300;
        color: #000;
        opacity: 0.8;
        text-shadow: none;
    }
    .modal-header button.close:hover {
        opacity: 1;
        color: #000;
    }

    /* Doctor/Patient names styling */
    .patient-doctor-names {
        color: #2d5f6d;
        font-weight: 600;
    }

    /* Scrollable section for jobs and notes only */
    .scrollable-content {
        max-height: 40vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    /* Notes container styling */
    .form-control.note-container {
        background-color: #e8f0f2;
        border: 1px solid #b8d4db;
        color: #212529;
    }

    .modal-footer {
        display: block;
        padding: 1rem;
        border-top: 1px solid #dee2e6;
    }
    .modal-footer .row {
        margin: 0;
    }
    .modal-footer .col-6,
    .modal-footer .col-4,
    .modal-footer .col-12 {
        padding: 5px;
    }
    .modal-footer .btn {
        width: 100%;
        margin: 3px;
        font-weight: 400;
        padding: 10px 12px;
        border: none;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    /* Button color improvements with proper contrast */
    .modal-footer .btn-info {
        background-color: #17a2b8;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
    }
    .modal-footer .btn-info:hover {
        background-color: #138496;
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
    }

    .modal-footer .btn-success {
        background-color: #28a745;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }
    .modal-footer .btn-success:hover {
        background-color: #218838;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
    }
    .modal-footer .btn-success:disabled {
        background-color: #6c757d;
        color: #ffffff !important;
        opacity: 0.6;
    }

    .modal-footer .btn-warning {
        background-color: #ffc107;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
    }
    .modal-footer .btn-warning:hover {
        background-color: #e0a800;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
    }

    .modal-footer .btn-dark {
        background-color: #343a40;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
    }
    .modal-footer .btn-dark:hover {
        background-color: #23272b;
        box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
    }

    .modal-footer .btn-outline-info {
        border: 2px solid #17a2b8;
        background-color: transparent;
        color: #17a2b8 !important;
    }
    .modal-footer .btn-outline-info:hover {
        background-color: #17a2b8;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
    }

    .modal-footer .btn-outline-danger {
        border: 2px solid #dc3545;
        background-color: transparent;
        color: #dc3545 !important;
    }
    .modal-footer .btn-outline-danger:hover {
        background-color: #dc3545;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .modal-footer .btn-outline-secondary {
        border: 2px solid #6c757d;
        background-color: transparent;
        color: #6c757d !important;
    }
    .modal-footer .btn-outline-secondary:hover {
        background-color: #6c757d;
        color: #ffffff !important;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    }

    .modal-footer .btn-secondary {
        background-color: #6c757d;
        color: #ffffff !important;
        box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
    }
    .modal-footer .btn-secondary:hover {
        background-color: #5a6268;
        box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
    }

    /* Icon spacing */
    .modal-footer .btn i {
        margin-right: 6px;
    }
    .YSH-button {
    text-decoration: none;
    line-height: 1;
    border-radius: 1.5rem;
    overflow: hidden;
    position: relative;
    box-shadow: 10px 10px 20px rgba(0,0,0,.05);
    background-color: #fff;
    color: #121212;
    border: none;
    cursor: pointer;
    }

    .YSH-button-decor {
    position: absolute;
    inset: 0;
    background-color: var(--clr);
    transform: translateX(-100%);
    transition: transform .3s;
    z-index: 0;
    }

    .YSH-button-content {
    display: flex;
    align-items: center;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    }

    .YSH-button__icon {
    width: 48px;
    height: 40px;
    background-color: var(--clr);
    display: grid;
    place-items: center;
    }

    .YSH-button__text {
    display: inline-block;
    transition: color .2s;
    padding: 2px 1.5rem 2px;
    padding-left: .75rem;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    max-width: 150px;
    }

    .YSH-button:hover .YSH-button__text {
    color: #fff;
    }

    .YSH-button:hover .YSH-button-decor {
    transform: translate(0);
    }
</style>

    <style>
        .dt-layout-row{
            margin: 0px !important;
        }

        .dt-center {
            text-align: center !important;
        }
        tr > th.dt-orderable-none.dt-type-numeric > div > span{
            text-align: center !important;
        }



        input[type="checkbox"],
        input[type="radio"] {
            transform: scale(1.3);
        }

        /* Device container styling */
        .device-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: {{ $deviceConfig["container_gap"] ?? '15px' }};
            width: 100%;
        }

        .device-item {
            width: 100%;
            max-width: {{ $deviceConfig["max_width"] ?? '150px' }};
            text-align: center;
            margin-bottom: {{ $deviceConfig["margin_bottom"] ?? '15px' }};
            transition: all 0.3s ease;
        }

        .device-item img {
            width: {{ $deviceConfig["width"] ?? '100%' }};
            height: {{ $deviceConfig["height"] ?? 'auto' }};
            max-width: {{ $deviceConfig["max_width"] ?? '150px' }};
            padding: {{ $deviceConfig["padding"] ?? '10px' }};
            border-radius: {{ $deviceConfig["border_radius"] ?? '8px' }};
            background: {{ $deviceConfig["background"] ?? 'transparent' }};
            object-fit: contain;
        }

        .device-item:hover {
            transform: {{ $deviceConfig["hover_effect"] ? 'scale(1.05)' : 'none' }};
            box-shadow: none !important;
        }

        .device-item .device-name {
            margin-top: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .device-item .device-status {
            font-size: 12px;
            color: #6c757d;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .device-item {
                max-width: {{ $deviceConfig["responsive_sizes"]["tablet"] ?? '120px' }};
            }

            .device-item img {
                max-width: {{ $deviceConfig["responsive_sizes"]["tablet"] ?? '120px' }};
            }
        }

        @media (max-width: 576px) {
            .device-item {
                max-width: {{ $deviceConfig["responsive_sizes"]["mobile"] ?? '100px' }};
            }

            .device-item img {
                max-width: {{ $deviceConfig["responsive_sizes"]["mobile"] ?? '100px' }};
            }
        }

        @media (max-width: 376px) {
            max-width: 0 !important;
        }

        td > p {
            margin-bottom: 5px;
        !important;
        }

    </style>

    <style>
        /* Smoother, simpler dialog animation to replace glitchy version */
        @keyframes fadeInDownSimple {
            from {
                opacity: 0;
                transform: translate3d(0, -20px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .slide-in-blurred-top {
            animation-name: fadeInDownSimple;
            animation-duration: 0.4s;
            animation-fill-mode: both;
        }
    </style>

    <style>
        @keyframes shimmer {
            0% { background-position: -200px 0; }
            100% { background-position: 200px 0; }
        }

        .loading * {
            color: transparent !important;
            pointer-events: none;
            user-select: none;
        }

        .loading td,
        .loading th,
        .loading div,
        .loading button,
        .loading span,
        .loading li {
            position: relative;
            background: #e0e0e0;
            overflow: hidden;
        }

        .loading td::after,
        .loading th::after,
        .loading div::after,
        .loading button::after,
        .loading span::after,
        .loading li::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(
                to right,
                #e0e0e0 0%,
                #f6f6f6 20%,
                #e0e0e0 40%,
                #e0e0e0 100%
            );
            background-size: 800px 100%;
            animation: shimmer 1.5s infinite linear;
        }
        /* Loading shimmer overlay */
        /*.dashboard-shimmer-overlay {*/
        /*    position: absolute;*/
        /*    top: 0;*/
        /*    left: 0;*/
        /*    width: 100%;*/
        /*    height: 100%;*/
        /*    background: linear-gradient(*/
        /*        90deg,*/
        /*        rgba(240, 240, 240, 0.8) 0%,*/
        /*        rgba(255, 255, 255, 0.9) 50%,*/
        /*        rgba(240, 240, 240, 0.8) 100%*/
        /*    );*/
        /*    background-size: 1000px 100%;*/
        /*    animation: shimmer 2s infinite linear;*/
        /*    z-index: 9999;*/
        /*    pointer-events: none;*/
        /*}*/

        /* Hide shimmer when loaded */
        .dashboard-shimmer-overlay.loaded {
            display: none;
        }
    </style>

@endpush



@section('content')
    {{-- Define setOuterTab early to prevent race condition errors when clicking tabs before page fully loads --}}
    <script>
        // Early placeholder function - will be overridden by full implementation in footer
        function setOuterTab(element) {
            // Check if jQuery is loaded yet
            if (typeof jQuery === 'undefined') {
                console.warn('setOuterTab called before jQuery loaded, deferring...');
                // Retry after a short delay
                setTimeout(function() {
                    setOuterTab(element);
                }, 100);
                return;
            }

            // Check if Cookies library is loaded
            if (typeof Cookies === 'undefined') {
                console.warn('Cookies library not loaded yet, loading basic functionality...');
            }

            try {
                // Basic implementation that works even during page load
                var $ = jQuery;

                // Remove active class from all tab buttons
                $('.stageSidebar button[role="tab"]').attr('aria-selected', 'false');

                // Set current tab as selected
                $(element).attr('aria-selected', 'true');

                // Hide all tab panels
                $('div[role="tabpanel"]').attr('hidden', true);

                // Get the target panel ID
                var targetPanelId = $(element).attr('aria-controls');

                // Show the corresponding panel
                $('#' + targetPanelId).removeAttr('hidden');

                // Save the selected outer tab to cookies if Cookies is available
                var tabId = $(element).attr('id');
                if (tabId && typeof Cookies !== 'undefined') {
                    Cookies.set('activeOuterTab', tabId);
                    console.log("Saved outer tab to cookie:", tabId);
                }

                // Reinitialize Macaw Tabs if available
                if (typeof MacawTabs !== 'undefined') {
                    MacawTabs.init();
                }

                console.log('setOuterTab executed successfully for:', tabId);
            } catch (error) {
                console.error('Error in setOuterTab:', error);
            }
        }
    </script>

    {{--    @php--}}
    {{--        try {--}}
    {{--    @endphp--}}


    @php
        $color = '#01292b';
            //dd($devices);

            $permissions = Cache::get('user' . Auth()->user()->id);
            $canEditCase = false;
            if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 102))) {
                $canEditCase = true;
            }
    @endphp
    @php
        $stages = [
            'design' => [
                'activeCases' => $aDesign,
                'waitingCases' => $wDesign,
                'numericStage' => 1,
                'icon' => "<i class='fa-solid fa-desktop'></i>",
            ],
            '3dprinting' => [
                'activeCases' => $aPrinting,
                'waitingCases' => $wPrinting,
                'numericStage' => 3,
                'icon' => "
            <svg version='1.1' class='printingIcon' id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'
             viewBox='0 0 367.579 213.624' style='enable-background:new 0 0 367.579 213.624;' xml:space='preserve'>
            <g id='XMLID_80_'>
            <path id='XMLID_81_' d='M54.962,85.176h21.863c12.45,0,20.9-2.581,25.355-7.743c4.453-5.162,6.681-10.678,6.681-16.549
                c0-6.579-2.456-12.424-7.364-17.537c-4.911-5.11-11.767-7.667-20.573-7.667c-16.803,0-27.382,8.858-31.732,26.57L6.225,55.417
                C9.767,39.426,18.345,26.19,31.96,15.714C45.573,5.238,62.35,0,82.292,0c20.851,0,38.387,4.965,52.609,14.891
                c14.22,9.926,21.332,23.5,21.332,40.719c0,22.589-11.843,38.038-35.528,46.344c27.834,7.385,41.753,23.771,41.753,49.159
                c0,18.208-7.112,33.177-21.332,44.911c-14.222,11.734-33.834,17.601-58.834,17.601c-23.989,0-42.994-6.033-57.012-18.099
                C11.259,183.46,2.833,168.488,0,150.615l44.031-6.377c4.251,21.358,16.599,32.036,37.046,32.036c9.513,0,17.232-2.522,23.154-7.57
                c5.921-5.046,8.882-11.809,8.882-20.288c0-8.984-2.709-15.698-8.123-20.139c-5.416-4.441-15.767-6.662-31.049-6.662H54.962V85.176z
                '/>
            <path id='XMLID_83_' d='M197.682,3.188h63.256c25.788,0,45.002,3.568,57.643,10.704c12.641,7.136,23.967,18.423,33.979,33.858
                c10.012,15.437,15.02,34.947,15.02,58.53c0,29.659-8.75,54.431-26.242,74.32c-17.496,19.89-41.615,29.834-72.359,29.834h-71.295
                V3.188z M245.356,41.297v130.118h19.999c17.677,0,30.808-6.604,39.392-19.814c8.586-13.209,12.881-28.719,12.881-46.536
                c0-12.55-2.451-24.165-7.35-34.845c-4.9-10.678-10.984-18.167-18.258-22.471c-7.273-4.301-16.009-6.453-26.21-6.453H245.356z'/>
            </g>
            </svg>
            ",
            ],
            'milling' => [
                'activeCases' => $aMilling,
                'waitingCases' => $wMilling,
                'numericStage' => 2,
                'icon' => "<svg class='millingIcon' version='1.1' id='Layer_1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px'
             viewBox='0 0 219.296 416.891' style='enable-background:new 0 0 219.296 416.891;' xml:space='preserve'>
            <path id='XMLID_96_'  d='M83.523,285.071
            c-8.936,0-17.387-0.009-25.838,0.002c-18.806,0.023-29.419-10.595-29.401-29.402c0.014-14.833-0.005-29.665,0.01-44.498
            c0.016-15.216,6.395-23.871,20.709-28.584c1.27-0.418,2.911-2.281,2.937-3.503c0.228-10.983,0.133-21.974,0.133-33.931
            c-6.659,0-13.105,0.414-19.467-0.14c-4.52-0.393-9.315-1.333-13.297-3.374c-6.953-3.564-10.608-9.93-11.158-17.792
            c-0.267-3.816-0.089-51.173-0.126-55.005c-0.039-4.011,1.898-6.506,5.923-6.479c4.169,0.028,5.652,2.911,5.665,6.735
            c0.008,2.333-0.048,48.178-0.011,50.511c0.152,9.463,4.396,13.745,13.798,13.749c50.664,0.021,101.328,0.018,151.992,0.001
            c9.887-0.003,14.027-4.111,14.03-13.927c0.015-47.664,0.002-54.688,0.006-102.352c0-1.496-0.504-3.464,0.245-4.389
            c1.598-1.976,3.891-4.731,5.849-4.692c1.897,0.038,3.856,3.071,5.52,5.018c0.499,0.584,0.113,1.935,0.113,2.934
            c-0.001,48.164,0.019,55.688-0.021,103.852c-0.013,16.046-9.434,25.329-25.55,25.365c-5.95,0.013-11.901,0.002-18.142,0.002
            c0,12.556,0,24.485,0,36.328c3.596,1.459,7.364,2.457,10.568,4.39c8.888,5.365,12.844,13.639,12.863,23.894
            c0.03,15.999,0.112,31.998,0.031,47.997c-0.081,16.03-11.432,27.205-27.559,27.28c-8.982,0.042-17.965,0.008-27.537,0.008
            c-0.12,2.405-0.316,4.492-0.315,6.579c0.007,28.831,0.009,57.661,0.106,86.491c0.014,4.045-1.001,7.294-4.025,10.249
            c-5.715,5.585-11.001,11.606-16.559,17.356c-4.076,4.216-6.825,4.203-10.946-0.073c-6.011-6.239-12.218-12.334-17.687-19.025
            c-1.956-2.393-2.785-6.337-2.807-9.581c-0.186-28.664-0.075-57.329-0.052-85.994C83.524,289.277,83.523,287.485,83.523,285.071z
             M96.692,294.324c-0.469,0.283-0.937,0.567-1.406,0.85c0,3.786,0.243,7.591-0.068,11.351c-0.316,3.831,1.056,6.487,3.816,9.093
            c7.003,6.614,13.707,13.545,20.548,20.331c1.076,1.067,2.241,2.045,4.245,3.863c0-5.612,0.096-9.891-0.05-14.162
            c-0.054-1.586-0.161-3.641-1.12-4.651C114.113,312.002,105.374,303.19,96.692,294.324z M96.59,329.452
            c-0.428,0.236-0.857,0.472-1.285,0.708c0,1.957,0.271,3.96-0.048,5.864c-1.276,7.614,1.425,13.266,7.321,18.203
            c5.594,4.684,10.458,10.239,15.648,15.406c1.543,1.536,3.099,3.06,5.565,5.493c0-6.143,0.101-10.776-0.062-15.399
            c-0.049-1.384-0.441-3.121-1.342-4.054C113.871,346.854,105.21,338.173,96.59,329.452z M116.706,386.799
            c-7.345-7.452-14.241-14.449-20.966-21.272c-2.12,11.971,1.972,20.231,14.419,28.222
            C112.486,391.28,114.851,388.769,116.706,386.799z M106.012,285.297c5.899,5.949,11.817,11.916,17.377,17.523
            c0-5.319,0-11.342,0-17.523C117.143,285.297,111.367,285.297,106.012,285.297z'/>
            <path id='XMLID_93_' d='M163.2,66.867c0.003-13.2-0.054-6.892,0.028-20.092c0.04-6.383,2.412-9.25,7.259-9.047
            c5.681,0.237,6.988,4.26,6.997,8.851c0.057,26.603-0.012,33.698-0.026,60.301c-0.003,4.996-2.164,8.607-7.374,8.444
            c-5.177-0.162-6.987-3.838-6.935-8.856C163.289,93.269,163.197,80.068,163.2,66.867z'/>
            <path id='XMLID_83_' style='fill:#FFFFFF;' d='M96.692,294.324c8.682,8.866,17.422,17.678,25.965,26.676
            c0.959,1.01,1.066,3.065,1.12,4.651c0.146,4.271,0.05,14.833,0.05,20.445c-2.004-1.818-3.169-2.796-4.245-3.863
            c-6.841-6.787-13.545-13.717-20.548-20.331c-2.76-2.607-4.132-5.262-3.816-9.093c0.311-3.76,0.068-13.849,0.068-17.634
            C95.754,294.89,96.223,294.607,96.692,294.324z'/>
            <path id='XMLID_82_' style='fill:#FFFFFF;' d='M116.706,375.126c-1.855,1.97-4.22,16.153-6.547,18.624
            c-12.447-7.991-16.539-27.924-14.419-39.895C102.465,360.678,109.361,367.675,116.706,375.126z'/>
            <path id='XMLID_81_' style='fill:#FFFFFF;' d='M123.895,244.73c10.552,0,21.104-0.043,31.655,0.017
            c6.287,0.036,9.415,2.403,9.209,7.09c-0.26,5.919-4.436,7.163-9.32,7.172c-21.302,0.037-42.604,0.06-63.906,0.008
            c-4.776-0.012-8.647-1.797-8.584-7.259c0.062-5.416,3.757-7.081,8.685-7.049C102.387,244.777,113.141,244.729,123.895,244.73z'/>
            <path id='XMLID_80_' style='fill:#FFFFFF;' d='M96.462,165.605c-5.129,0-10.259,0.075-15.387-0.024
            c-4.105-0.079-6.799-2.359-6.76-6.389c0.038-3.964,2.628-6.336,6.784-6.353c10.259-0.043,20.519-0.042,30.778,0.017
            c4.142,0.024,6.756,2.289,6.733,6.364c-0.022,4.051-2.594,6.308-6.766,6.342c-5.127,0.041-10.255,0.01-15.383,0.01
            C96.462,165.583,96.462,165.594,96.462,165.605z'/>
            </svg>
            ",
            ],
            'sintering' => [
                'activeCases' => $aSintering,
                'waitingCases' => $wSintering,
                'numericStage' => 4,
                'icon' => "<i class='fa-solid fa-fire-flame-curved'></i>",
            ],
            'pressing' => [
                'activeCases' => $aPressing,
                'waitingCases' => $wPressing,
                'numericStage' => 5,
                'icon' => "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 384 512'>
            <defs><style>.fa-secondary{opacity:.4}</style>
            </defs><path class='fa-primary' d='M350 206.6c3.781 8.803 1.984 19.03-4.594 26l-136 144.1c-9.062 9.601-25.84 9.601-34.91 0l-136-144.1C31.97 225.7 30.17 215.4 33.95 206.6C37.75 197.8 46.42 192.1 56 192.1L128 192.1V64.03c0-17.69 14.33-32.02 32-32.02h64c17.67 0 32 14.34 32 32.02v128.1l72 .0314C337.6 192.1 346.3 197.8 350 206.6z'/>
            <path class='fa-secondary' d='M352 416H31.1C14.33 416 0 430.3 0 447.1S14.33 480 31.1 480H352C369.7 480 384 465.7 384 448S369.7 416 352 416z'/></svg>",
            ],
            'finishing' => [
                'activeCases' => $aFinishing,
                'waitingCases' => $wFinishing,
                'numericStage' => 6,
                'icon' => "<i class='fa-solid fa-broom'></i>",
            ],
            'QC' => [
                'activeCases' => $aQC,
                'waitingCases' => $wQC,
                'numericStage' => 7,
                'icon' => "<i class='fa-solid fa-magnifying-glass'></i>",
            ],
            'delivery' => [
                'activeCases' => $aDelivery,
                'waitingCases' => $wDelivery,
                'numericStage' => 8,
                'icon' => "<i class='fa-solid fa-truck'></i>",
            ],
        ];
        if (!Auth()->user()->is_admin) {
            if (!$permissions->contains('permission_id', 1)) {
                unset($stages['design']);
            }
            if (!$permissions->contains('permission_id', 2)) {
                unset($stages['milling']);
            }
            if (!$permissions->contains('permission_id', 3)) {
                unset($stages['3dprinting']);
            }
            if (!$permissions->contains('permission_id', 4)) {
                unset($stages['sintering']);
            }
            if (!$permissions->contains('permission_id', 5)) {
                unset($stages['pressing']);
            }
            if (!$permissions->contains('permission_id', 6)) {
                unset($stages['finishing']);
            }
            if (!$permissions->contains('permission_id', 7)) {
                 unset($stages['qc']);
                 unset($stages['QC']);
                  unset($stages['Qc']);
            }
            if (!$permissions->contains('permission_id', 8)) {
                unset($stages['delivery']);
            }
        }
    @endphp
        <!-- Begin .site-wrapper -->
    <div class="site-wrapper">
        <!-- Begin waiting milling dialog -->
        <!-- Begin Main -->
        <main style="background-color: white">
            <!-- Begin .macaw-tabs -->
            <div class="macaw-tabs macaw-aurora-tabs notransition" style="position: relative;min-height: 133vw">
                <!-- Simple shimmer loading overlay -->
                <div class="dashboard-shimmer-overlay loading" id="dashboardShimmer"></div>

                <div role="tablist" class="stageSidebar" aria-orientation="vertical">
                    @foreach ($stages as $key => $stage)
                        @php
                            // For display and ID purposes, lowercase for all stages. 3dprinting
                            $keyId = strtolower($key);
                            $displayKey = $key;

                        $displayKey=  ( $key == "3dprinting")? "Printing" : $displayKey;
                        $displayKey=  ( $key == "Qc")? "QC" : $displayKey;
                        @endphp
                        <button role="tab" aria-selected="false" aria-controls="{{ $keyId . 'label' }}"
                                id="{{ $keyId }}" style="" onclick="setOuterTab(this)">
                            <span class="iconSpan" style="display: flex;align-items: center;">{!! $stage['icon'] !!}
                                <span style=" padding-left:6px" class="stageName"> {{ $displayKey }}</span></span>
                            <div>
                                <span class="badge bg-info m-1 activeBadge"
                                      style="padding: 0.25em 0.4em;">{{ count($stage['activeCases']) }}</span>
                                <span class="badge bg-info m-1 waitingBadge"
                                      style="padding: 0.25em 0.4em;">{{ count($stage['waitingCases']) }} </span>
                            </div>
                        </button>
                    @endforeach
                </div>
                @foreach ($stages as $key => $stage)
                    @php
                        // Standardize key format
                        $key = strtolower($key);

                    @endphp
                    {{--                <h1>{{$key}}</h1> --}}
                    <div class="notransition" tabindex="0" role="tabpanel" aria-labelledby="{{ $key }}"
                         id="{{ $key . 'label' }}" hidden>
                        <!-- Begin .macaw-tabs -->
                        <div class="macaw-tabs macaw-silk-tabs notransition">
                            @include('cases.dashboards-partials.tabs', ['key' => $key, 'stage' => $stage])
                            {{-- ----------------waiting TABLE--------------- --}}
                            {{-- ----------------waiting TABLE--------------- --}}
                            {{-- ----------------waiting TABLE--------------- --}}
                            {{-- ----------------waiting TABLE--------------- --}}
                            <div tabindex="0" role="tabpanel" hidden aria-labelledby="{{ 'waiting-' . $key . 'label' }}"
                                 id="{{ 'waiting-' . $key }}">

                                @switch(strtolower($key))
                                    @case('milling')
                                        <x-waiting-dialog title="Choose Machine" btnText="NEST" type="milling"
                                                          :devices="$devices"
                                                          :types="$types"
                                                          :typesByMaterial="$typesByMaterial"
                                                          stageId="2"/>
                                        <button type="submit" class="btn btn-primary receiveSelectBtn milling"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('milling',true)">SET
                                        </button>
                                        @break

                                    @case('3dprinting')
                                        @php $key = "3dprinting"; @endphp
                                        <x-waiting-3dprinting-dialog title="Choose Printer" btnText="SET"
                                                                     type="3dprinting"
                                                                     :devices="$devices"
                                                                     stageId="3" showBuildName="true"/>
                                        <button type="submit" class="btn btn-primary receiveSelectBtn 3dprinting"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('3dprinting',true)">SET
                                        </button>
                                        @break

                                    @case('sintering')
                                        <x-waiting-dialog title="Choose Furnace" btnText="SET" type="sintering"
                                                          :devices="$devices"
                                                          :types="$types"
                                                          :typesByMaterial="$typesByMaterial"
                                                          stageId="4"/>
                                        <button type="submit" class="btn btn-primary receiveSelectBtn sintering"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('sintering',true)">SET
                                        </button>
                                        @break

                                    @case('pressing')
                                        <x-waiting-dialog title="Choose Furnace" btnText="SET" type="pressing"
                                                          :devices="$devices"
                                                          :types="$types"
                                                          :typesByMaterial="$typesByMaterial"
                                                          stageId="5"/>
                                        <button type="submit" class="btn btn-primary receiveSelectBtn pressing"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('pressing',true)">SET
                                        </button>
                                        @break

                                    @case('delivery')
                                        @php
                                        // Define this BEFORE using it - includes admins and users with permission 129
                                        $isDeliveryAndAssignable = (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 129)));
                                        @endphp

                                        <x-waiting-delivery-dialog title="Assign to" btnText="{{$isDeliveryAndAssignable ? 'ASSIGN TO' : 'ASSIGN'}}"
                                                                   :drivers="$drivers" stageId="5"/>
                                        <button type="submit" class="btn btn-primary receiveSelectBtn delivery"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('DeliveryDialog',false)">{{$isDeliveryAndAssignable ? "ASSIGN TO" : "ASSIGN"}}
                                        </button>
                                        @break
                                @endswitch
                                <table class="{{ $key }} waitingTable sunriseTable" style="width:100%">
                                    <thead>
                                    <tr>
                                        {{-- Show checkboxes for all stages EXCEPT delivery without permission --}}
                                        @if ($key != 'delivery' || $isDeliveryAndAssignable)
                                        @if ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery')
                                            {{-- Checkbox column header --}}

                                            @if (count($stage['waitingCases']) != 0)

                                                <th class="no-sort text-center" style="width: 50px;">
                                                    <input type="checkbox" class="selectAllCases {{$key}}" value="0"
                                                           name="selectAllCases"
                                                           onchange="selectAll(this, '{{ $key }}')"/>
                                                </th>
                                            @endif
                                        @endif
                                     @endif
                                        <th>Doctor</th>
                                        <th>Patient</th>
                                        <th class="deliveryDateHeader"><span
                                                class="innerSpan4Mobile">D.Date</span><span
                                                class="innerSpan4DeskTop">Delivery Date</span></th>
                                            @if ($key == 'delivery')
                                                <th> Assigned To</th>
                                            @endif
                                        <th>#</th>

                                        <th>Tags</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($stage['waitingCases'] as $case)
                                        <tr style="color:{{ $color }}">
                                            @php
                                                // Normalize key case
                                                $key = strtolower($key);

                                            @endphp



                                            @if ($key == 'finishing')
                                                @php
                                                    $notReadyA = false;
                                                    $abutmentsReceived = $case->abutmentsReceived();
                                                    if (!$case->allUnitsAtFinishing()) {
                                                        $notReadyA = true;
                                                    }

                                                @endphp
                                            @endif
                                            {{-- Show checkboxes for all stages EXCEPT delivery without permission --}}
                                            @if ($key != 'delivery' || $isDeliveryAndAssignable)
                                            @if ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery')
                                                <td class="no-sort">
                                                    <input type="checkbox"
                                                           data-type="{{$key}}"
                                                           data-group-id="{{$key}}"
                                                           class="custom-control-input multipleCB {{ $key }}   checkboxes-group-{{$key}}"
                                                           value="{{ $case->id }}"
                                                           name="CheckBoxes{{ $key }}[]"
                                                           onchange="multiCBChanged('{{ $key }}',this, '{{ $case->id }}')">

                                                </td>
                                            @endif
                                            @endif
                                            <td class="clickable" data-toggle="modal"
                                                data-target="#waitingDialog{{ $key . $case->id }}">
                                                <p class="">{{ $case->client?->name ?? 'Err404-1' }}</p>
                                            </td>
                                            <td class="clickable" data-toggle="modal"
                                                data-target="#waitingDialog{{ $key . $case->id }}">
                                                <p class="">{{ $case->patient_name }}
                                                    @if ($key == 'finishing')
                                                        @if ($notReadyA)
                                                            <span
                                                                style="margin: 4px 16px 1px 1px;float:right; line-height: 1;color:#ffa400;font-size: 10px;">
                                                                    Not <br>
                                                                    Ready
                                                                </span>
                                                        @endif
                                                        @if (!$abutmentsReceived)
                                                            <span
                                                                style="margin: 4px 16px 1px 1px;float:right; line-height: 1;color:#ffa400;font-size: 10px;">
                                                                    Abutment <br>
                                                                    Missing
                                                                </span>
                                                        @endif
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="clickable" data-toggle="modal"
                                                data-target="#waitingDialog{{ $key . $case->id }}">
                                                <p class="">
                                                    {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}</p>
                                            </td>
                                            <!-- Assigned to for delivery stage -->
                                            @if ($key == 'delivery')
                                                <td class="clickable" data-toggle="modal"
                                                    data-target="#waitingDialog{{ $key . $case->id }}">
                                                    <p class="">
                                                        {{ $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo
                                                            ? $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo->name_initials
                                                            : 'None' }}
                                                    </p>
                                                </td>
                                            @endif
                                            <td class="clickable" data-toggle="modal"
                                                data-target="#waitingDialog{{ $key . $case->id }}">
                                                <p class="">{{ $case->unitsAmount($stage['numericStage']) }}</p>
                                            </td>

                                            <td class="clickable" data-toggle="modal"
                                                data-target="#waitingDialog{{ $key . $case->id }}">
                                                <div style="display:flex; flex-wrap: wrap; gap: 3px; align-items: center;">
                                                    @foreach ($case->tags as $tag)
                                                        <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                            style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                            class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }}  fa-lg"></i>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                        {{--                                        {{dd("key  is  " . $key)}} --}}
                                        {{-- BEGIN WAITING DIALOG --}}
                                        <div class="modal fade" tabindex="-1" role="dialog"
                                             id="waitingDialog{{ $key . $case->id }}">
                                            <form
                                                action="{{ $key == 'delivery' ? route('delivery-accept', $case->id) : route('assign-to-me', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                method="GET">
                                                @csrf
                                                <input type="hidden" name="case_id" value="{{ $case->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Case Completion</h5>
                                                            @if (Auth()->user()->is_admin)
                                                                <div class="tooltipY">
                                                                    <a
                                                                        href="{{ route('finish-case-completely', ['caseId' => $case->id]) }}">
                                                                        <i class="fa-solid fa-forward-fast skip-to-delivery-icon"></i>
                                                                    </a>
                                                                    <span class="tooltiptextY">Skip To Delivery
                                                                            Stage</span>
                                                                </div>
                                                            @endif
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="false">&times;</span>
                                                            </button>

                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Sticky Doctor/Patient section -->
                                                            <div class="form-group row" style="margin-bottom: 0px">
                                                                <div class="form-group col-6 "
                                                                     style="margin-bottom: 0px">
                                                                    <label for="doctor">Doctor: </label>
                                                                    <h5 id="doctor" class="patient-doctor-names">{{ $case->client?->name }}</h5>
                                                                </div>
                                                                <div class="form-group col-6 "
                                                                     style="margin-bottom: 0px">
                                                                    <label for="pat">Patient: </label>
                                                                    <h5 id="pat" class="patient-doctor-names">{{ $case->patient_name }}</h5>
                                                                </div>
                                                            </div>
                                                            <hr>

                                                            <!-- Scrollable Jobs and Notes section -->
                                                            <div class="scrollable-content">
                                                                <div class="form-group row">
                                                                    <div class=" col-12 ">
                                                                        <label><b>Jobs:</b></label><br>


                                                                        @foreach ($case->jobs->where('stage', $stage['numericStage']) as $job)
                                                                            @php
                                                                                $unit = explode(', ', $job->unit_num);
                                                                                // Check if this job goes through the current stage based on material
                                                                                $showJob = $job->goesThroughStage($stage['numericStage']);
                                                                            @endphp

                                                                            @if($showJob)
                                                                            <span>{{ $job->unit_num }}
                                                                                    -
                                                                                    {{ $job->jobType->name ?? 'No Job Type' }}
                                                                                    -
                                                                                    {{ $job->material->name ?? 'no material' }}
                                                                                {{ $job->color == '0' ? '' : ' - ' . $job->color }}
                                                                                {{ $job->style == 'None' ? '' : ' - ' . $job->style }}
                                                                                {{ isset($job->implantR) && $job->jobType->id == 6 ? ' - Implant Type: ' . $job->implantR->name : '' }}
                                                                                    <br>
                                                                                    {{ isset($job->abutmentR) && $job->jobType->id == 6 ? ' Abutment Type: ' . $job->abutmentR->name : '' }}
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                                @if (count($case->notes) > 0)
                                                                    <hr>
                                                                    <label><b>Notes:</b></label><br>
                                                                    @foreach ($case->notes as $note)
                                                                        <div class="form-control note-container"
                                                                             style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px"
                                                                             disabled>

                                                                                <span
                                                                                    class="noteHeader" style="font-weight:600">{{ '[' . substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>

                                                                            <span
                                                                                class="noteText">{{ $note->note }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="row btnsRow">
                                                                <!-- Row 1: View (25%) | Action (50%) | Edit (25%) -->
                                                                <div class="col-3 padding5px" style="display: flex;">
                                                                    <a href="{{ route('view-case', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}" style="width:100%; display: flex;">
                                                                        <button type="button" class="btn btn-info" style="width:100%; display: flex; align-items: center; justify-content: center;">View</button>
                                                                    </a>
                                                                </div>
                                                                <div class="col-6 padding5px" style="display: flex;">
                                                                    @if ($key == 'milling')
                                                                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="openModal('milling',true,'{{ $case->id }}')" style="width:100%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-plus"></i> Assign To Me</button>
                                                                    @elseif ($key == '3dprinting' || $key == 'sintering' || $key == 'pressing')
                                                                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="openModal('{{ $key }}',true,'{{ $case->id }}')" style="width:100%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-plus"></i> Assign To Me</button>
                                                                    @else
                                                                        <button type="submit" class="btn btn-success" style="width:100%; display: flex; align-items: center; justify-content: center;"><i class="fas fa-user-plus"></i> {{ $key == 'delivery' ? 'Take' : 'Assign To Me' }}</button>
                                                                    @endif
                                                                </div>
                                                                <div class="col-3 padding5px" style="display: flex;">
                                                                    <a href="{{ route('edit-case-view', $case->id) }}" style="width:100%; display: flex;">
                                                                        <button type="button" class="btn btn-warning {{ $canEditCase ? '' : 'disabled' }}" style="width:100%; display: flex; align-items: center; justify-content: center;">Edit Case</button>
                                                                    </a>
                                                                </div>

                                                                <!-- Row 2: QC Complete (100%) OR Delivery Assign (100%) -->
                                                                @if ($key == 'qc')
                                                                    <div class="col-12">
                                                                        <a href="{{ route('assign-and-finish', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}" class="btn btn-info" style="width:100%"><i class="fa-solid fa-arrow-trend-up"></i> Assign & Complete</a>
                                                                    </div>
                                                                @endif

                                                                @if ($key == 'delivery')
                                                                    @if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 129)))
                                                                        @if ($case->jobs[0]->assignee == null)
                                                                            <div class="col-12">
                                                                                <button type="button" class="btn btn-warning" onclick="closeModal({id:'waitingDialog{{ $key . $case->id }}'}); openModal('DeliveryDialog',false)" style="width:100%">Assign to..</button>
                                                                            </div>
                                                                        @else
                                                                            <div class="col-12">
                                                                                <button type="button" class="btn btn-warning" onclick="closeModal({id:'waitingDialog{{ $key . $case->id }}'}); openModal('DeliveryDialog', false)" style="width:100%">Re-Assign..</button>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif

                                                                <!-- Row 3: Delivery Print Voucher (100%) -->
                                                                @if ($key == 'delivery')
                                                                    <div class="col-12">
                                                                        <a href="{{ route('view-voucher', $case->id) }}" class="btn btn-info" style="width:100%"><i class="fas fa-print"></i> Print Voucher</a>
                                                                    </div>
                                                                @endif

                                                                <!-- Row 4: Cancel (100%) -->
                                                                <div class="col-12">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endforeach


                                    <!-- Begin Active tab -->
                                    </tbody>
                                </table>
                            </div>

                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            <div tabindex="0" role="tabpanel" aria-labelledby="{{ 'active-' . $key . 'label' }}"
                                 id="{{ 'active-' . $key }}" hidden>
                                @php
                                    $key = strtolower($key);

                                    $millingActiveDialogBuilt = false;
                                    $sinteringActiveDialogBuilt = false;
                                    $printingActiveDialogBuilt = false;
                                    $pressingActiveDialogBuilt = false;

                                @endphp
                                @if ($key == 'milling')

                                    <x-devices-block title="Milling" btnText="Start" type="milling" units="0"
                                                     :devices="$devices" stageId="2" :counts="$deviceUnitsCounts"/>

                                @elseif($key == '3dprinting')

                                    <x-devices-block title="3D Printing" btnText="Start" type="3dprinting"
                                                     units="3" :devices="$devices" stageId="3"
                                                     :counts="$deviceUnitsCounts"/>

                                @elseif($key == 'sintering')
                                    <x-devices-block title="Sintering" btnText="Start" type="sintering" units="4"
                                                     :devices="$devices" stageId="4" :counts="$deviceUnitsCounts"/>

                                @elseif($key == 'pressing')
                                    <x-devices-block title="Pressing" btnText="Start" type="pressing" units="5"
                                                     :devices="$devices" stageId="5" :counts="$deviceUnitsCounts"/>

                                @else
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <table class=" activeTable sunriseTable" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>Doctor</th>
                                            <th>Patient</th>
                                            <th class="deliveryToHeader">Delivery Date</th>
                                            <th class="assignedToHeader">Assigned To</th>
                                            <th class="">#</th>
                                            <th class="">Tags</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($stage['activeCases'] as $case)
                                            <tr class="clickable" style="color:{{ $color }}"
                                                data-toggle="modal"
                                                data-target="#confirmCompletion{{ $key . $case->id }}">
                                                @if ($key == 'finishing')
                                                    @php
                                                        $notReadyA = false;
                                                        $abutmentsReceived = $case->abutmentsReceived();
                                                        if (!$case->allUnitsAtFinishing()) {
                                                            $notReadyA = true;
                                                        }
                                                    @endphp
                                                @endif
                                                <td>
                                                    <p class="">{{ $case->client ? $case->client->name : 'No Client' }}</p>
                                                </td>
                                                <td>
                                                    <p class="">{{ $case->patient_name }} @if ($key == 'finishing')
                                                            @if ($notReadyA)
                                                                <span
                                                                    style="float:right;margin-left: 5px; line-height: 1;color:#ffa400;font-size: 9px;">
                                                                        Not <br>
                                                                        Ready
                                                                    </span>
                                                            @endif

                                                            @if (!$abutmentsReceived)
                                                                <span
                                                                    style="float:right; line-height: 1;color:#ffa400;font-size: 9px;">
                                                                        Abutment <br>
                                                                        Missing
                                                                    </span>
                                                            @endif
                                                        @endif

                                                    </p>
                                                </td>
                                                <td class="">
                                                    <p class="">
                                                        {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="">
                                                        {{ $case->jobs->where('stage', $stage['numericStage'])->first() ? ($case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo ? $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo->name_initials : 'None') : 'None' }}
                                                    </p>
                                                </td>
                                                <td class="">
                                                    <p class="">{{ $case->unitsAmount($stage['numericStage']) }}
                                                    </p>
                                                </td>
                                                <td class="">
                                                    <div style="display:flex; flex-wrap: wrap; gap: 3px; align-items: center;">
                                                        @foreach ($case->tags as $tag)
                                                            <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                                style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                                class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }}  fa-lg"></i>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
  <!-- Active case actions Dialog -->
                                            <div class="modal fade" tabindex="-1" role="dialog"
                                                 id="confirmCompletion{{ $key . $case->id }}">
                                                <form


                                                    action="{{ $key == 'delivery' ? route('finish-case', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) : route('finish-case', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}"


                                                    method="GET">
                                                    @csrf
                                                    <input type="hidden" name="case_id"
                                                            value="{{ $case->id }}">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Case Completion</h5>

                                                                <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="false">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Sticky Doctor/Patient section -->
                                                                <div class="form-group row"
                                                                     style="margin-bottom: 0px">
                                                                    <div class="form-group col-6 "
                                                                         style="margin-bottom: 0px">
                                                                        <label for="doctor">Doctor: </label>
                                                                        <h5 id="doctor" class="patient-doctor-names">{{ $case->client?->name }}</h5>
                                                                    </div>
                                                                    <div class="form-group col-6 "
                                                                         style="margin-bottom: 0px">
                                                                        <label for="pat">Patient: </label>
                                                                        <h5 id="pat" class="patient-doctor-names">{{ $case->patient_name }}</h5>
                                                                    </div>
                                                                </div>
                                                                <hr>

                                                                <!-- Scrollable Jobs and Notes section -->
                                                                <div class="scrollable-content">
                                                                    <div class="form-group row">
                                                                        <div class=" col-12 ">
                                                                            <label><b>Jobs:</b></label><br>


                                                                            @foreach ($case->jobs->where('stage', $stage['numericStage']) as $job)
                                                                                @php
                                                                                    $unit = explode(
                                                                                        ', ',
                                                                                        $job->unit_num,
                                                                                    );
                                                                                    // Check if this job goes through the current stage based on material
                                                                                    $showJob = $job->goesThroughStage($stage['numericStage']);
                                                                                @endphp

                                                                                @if($showJob)
                                                                                <span>{{ $job->unit_num }}
                                                                                        -
                                                                                        {{ $job->jobType->name ?? 'No Job Type' }}
                                                                                        -
                                                                                        {{ $job->material->name ?? 'no material' }}
                                                                                    {{ $job->color == '0' ? '' : ' - ' . $job->color }}
                                                                                    {{ $job->style == 'None' ? '' : ' - ' . $job->style }}
                                                                                    {{ isset($job->implantR) && $job->jobType->id == 6 ? ' - Implant Type: ' . $job->implantR->name : '' }}
                                                                                        <br>
                                                                                        {{ isset($job->abutmentR) && $job->jobType->id == 6 ? ' Abutment Type: ' . $job->abutmentR->name : '' }}
                                                                                    </span>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                    @if (count($case->notes) > 0)
                                                                        <hr>
                                                                        <label><b>Notes:</b></label><br>
                                                                        @foreach ($case->notes as $note)
                                                                            <div class="form-control note-container"
                                                                                 style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px"
                                                                                 disabled>

                                                                                    <span
                                                                                        class="noteHeader" style="font-weight:600">{{ '[' . substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>
                                                                                <span
                                                                                    class="noteText">{{ $note->note }}</span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="row">
                                                                    @php
                                                                        $isAdmin = Auth()->user()->is_admin;
                                                                        $canBeFinished = true;
                                                                        $isUserCase = false;
                                                                        $canComplete = false;
                                                                        if ($case->jobs->where('stage', $stage['numericStage'])->first() && $case->jobs->where('stage', $stage['numericStage'])->first()->assignee == Auth()->user()->id) {
                                                                            $canComplete = true;
                                                                            $isUserCase = true;
                                                                        }
                                                                        if ($key == 'finishing') {
                                                                            if ($notReadyA || !$abutmentsReceived) {
                                                                                $canComplete = false;
                                                                                $canBeFinished = false;
                                                                            }
                                                                        }
                                                                    @endphp


                                                                    <!-- Row 2: View (25%) | Complete (50%) | Edit (25%) -->
                                                                    <div class="col-3 padding5px" style="display: flex;">
                                                                        <a href="{{ route('view-case', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}" style="width:100%; display: flex;">
                                                                            <button type="button" class="btn btn-info" style="width:100%; display: flex; align-items: center; justify-content: center;">View</button>
                                                                        </a>
                                                                    </div>

                                                                    <div class="col-6 padding5px" style="display: flex;">
                                                                        @if ($isAdmin && $canBeFinished && !$isUserCase)
                                                                            <a href="{{ route('complete-by-admin', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}" style="width:100%; display: flex;">
                                                                                <button type="button" class="btn btn-success" style="width:100%; display: flex; align-items: center; justify-content: center;">overide omplete</button>
                                                                            </a>
                                                                        @else
                                                                            <button type="submit" class="btn btn-success" style="width:100%; display: flex; align-items: center; justify-content: center;" {{ $canComplete ? '' : 'disabled' }}>{{ $canComplete ? 'Complete' : 'Case cannot be completed' }}</button>
                                                                        @endif
                                                                    </div>

                                                                    <div class="col-3 padding5px" style="display: flex;">
                                                                        <a href="{{ route('edit-case-view', $case->id) }}" style="width:100%; display: flex;">
                                                                            <button type="button" class="btn btn-warning {{ $canEditCase ? '' : 'disabled' }}" style="width:100%; display: flex; align-items: center; justify-content: center;">Edit Case</button>
                                                                        </a>
                                                                    </div>
                                                                    <!-- Row 1: Delivery status (100%) - Layout 3 only -->
                                                                    @if ($key == 'delivery')
                                                                        <div class="col-12">
                                                                            <a href="{{ route('delivered-in-box', $case->id) }}" class="btn btn-outline-info" style="width:100%">Delivered In Box</a>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 3: Print Voucher (100%) - Layout 3 only -->
                                                                    @if ($key == 'delivery')
                                                                        <div class="col-12">
                                                                            <a href="{{ route('view-voucher', $case->id) }}" class="btn btn-outline-info" style="width:100%">Print Voucher</a>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 4: Externally Milled (100%) - Layout 5 only -->
                                                                    @if ($key == 'milling')
                                                                        <div class="col-12">
                                                                            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#MEX{{ $case->id }}" data-dismiss="modal" style="width:100%">Externally Milled</button>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Row 5: Reset To Waiting (100%) -->
                                                                    <div class="col-12">
                                                                        <a href="{{ route('reset-to-waiting', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}" class="btn btn-outline-danger" style="width:100%">Reset To Waiting</a>
                                                                    </div>

                                                                    <!-- Row 6: Cancel (100%) -->
                                                                    <div class="col-12">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>

                                                </form>
                                            </div>
                                            {{--                                            /////////// v2 DIALOG --}}
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach



                @foreach ($devices as $device)
                    @switch($device['type'])
                        @case(2)
                            <x-active-cases-dialog title="Milling Jobs" btnText="COMPLETE" type="milling"
                                                   :deviceId="$device['id']"
                                                   :isBuilds="false"/>
                            @break

                        @case(3)
                            <x-active-cases-dialog title="Printer Builds" btnText="COMPLETE" type="3dprinting"
                                                   :deviceId="$device['id']"
                                                   :isBuilds="true"/>
                            @break

                        @case(4)
                            <x-active-cases-dialog title="Sintering Jobs" btnText="COMPLETE" type="sintering"
                                                   :deviceId="$device['id']"
                                                   :isBuilds="false"/>
                            @break

                        @case(5)
                            <x-active-cases-dialog title="Pressing Jobs" btnText="COMPLETE" type="pressing"
                                                   :deviceId="$device['id']"
                                                   :isBuilds="false"/>
                            @break

                        @default
                            @break
                    @endswitch
                @endforeach
            </div>
        </main>
    </div>



    <!-- Updated hidden forms for all operations -->
    <div class="d-none">
        @php $stagesWithDialogs = ["3dprinting", "milling", "sintering", "pressing", "delivery"]; @endphp
        @foreach ($stagesWithDialogs as $stage)
            <form id="hiddenForm{{ $stage }}" action="#" method="POST">
                @csrf
                <input type="hidden" name="deviceId-{{ $stage }}" id="deviceId-{{ $stage }}"
                       value="">
                <input type="hidden" name="type" value="{{ $stage }}">
                <input type="hidden" name="WaitingPopupCheckBoxes{{ $stage }}[]"
                       id="WaitingPopupCheckBoxes{{ $stage }}" value="">

                @if ($stage == '3dprinting')
                    <input type="hidden" name="buildName" id="hidden3dprintingBuildName" value="">
                @endif
            </form>
        @endforeach

        <!-- Hidden form for case ID from waiting dialog -->
        <input type="hidden" id="caseIdFromWaitingDialog" name="caseIdFromWaitingDialog" value="">

        <!-- Generic loading dialog -->
        <div id="loadingDialog" class="modal" tabindex="-1" role="dialog"
             style="display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="mb-0 mt-2">Processing your request...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    @php--}}
    {{--        } catch (Exception $e) {--}}
    {{--            dd($e->getMessage(), $e->getTraceAsString());--}}
    {{--        }--}}
    {{--    @endphp--}}
@endsection


@push('js')
    <!-- Make sure jQuery is loaded first -->
    <script src="{{ asset('white') }}/js/core/jquery.min.js"></script>

    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Custom DataTables CSS fixes -->
    <style>
        /* Completely hide DataTables sorting arrows */
        .sunriseTable.dataTable thead th.sorting:before,
        .sunriseTable.dataTable thead th.sorting:after,
        .sunriseTable.dataTable thead th.sorting_asc:before,
        .sunriseTable.dataTable thead th.sorting_asc:after,
        .sunriseTable.dataTable thead th.sorting_desc:before,
        .sunriseTable.dataTable thead th.sorting_desc:after,
        .sunriseTable.dataTable thead .sorting:before,
        .sunriseTable.dataTable thead .sorting:after,
        .sunriseTable.dataTable thead .sorting_asc:before,
        .sunriseTable.dataTable thead .sorting_asc:after,
        .sunriseTable.dataTable thead .sorting_desc:before,
        .sunriseTable.dataTable thead .sorting_desc:after {
            display: none !important;
            content: none !important;
            background-image: none !important;
        }

        /* Force first column to not be sortable and fix alignment */
        .sunriseTable.dataTable thead th:first-child,
        .sunriseTable.dataTable thead th:first-child.sorting,
        .sunriseTable thead th:first-child {
            min-width: 25px !important;

            text-align: left !important;

            background-image: none !important;
            cursor: default !important;
            position: relative !important;
        }

        /* Remove any sorting arrows specifically from first column */

        /* Compact DataTables pagination styling */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 8px !important;
            margin-bottom: 5px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 2px 6px !important;
            margin: 0 1px !important;
            font-size: 10px !important;
            min-width: 20px !important;
            border-radius: 3px !important;
            border: 1px solid #ddd !important;
            background: #f8f9fa !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            border-color: #adb5bd !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #007bff !important;
            color: white !important;
            border-color: #007bff !important;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 10px !important;
            color: #6c757d !important;
            padding-top: 6px !important;
        }

        /* Table enhancements (removable) */
        .table-enhancement {
            /* Subtle row hover effect */
            .sunriseTable tbody tr:hover {
                background-color: #f8f9fa !important;
                transition: background-color 0.2s ease !important;
            }

            /* Alternating row colors - reduced opacity */
            .sunriseTable tbody tr:nth-child(even) {
                background-color: rgba(253, 253, 253, 0.9) !important;
            }

            /* Enhanced table borders */
            .sunriseTable {
                border-collapse: separate !important;
                border-spacing: 0 !important;
                border: 1px solid #e9ecef !important;
                border-radius: 6px !important;
                overflow: hidden !important;
            }

            /* Column borders */
            .sunriseTable th,
            .sunriseTable td {
                border-right: 1px solid #f1f3f4 !important;
                border-bottom: 1px solid #f1f3f4 !important;
            }

            .sunriseTable th:last-child,
            .sunriseTable td:last-child {
                border-right: none !important;
            }

            /* Header styling */
            .sunriseTable thead th {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
                color: #495057 !important;
                font-weight: 600 !important;
                text-transform: uppercase !important;
                font-size: 11px !important;
                letter-spacing: 0.5px !important;
            }
        }

        /* Tags column width - make narrower */
        .sunriseTable th:nth-child(8),
        .sunriseTable td:nth-child(8) {
            width: 80px !important;
            max-width: 80px !important;
            min-width: 80px !important;
        }

        /* Doctor name font weight (stronger than patient by 100) */
        .sunriseTable .doctor-name {
            font-weight: 600 !important;
        }

        .sunriseTable .patient-name {
            font-weight: 500 !important;
        }

        /* Fix checkbox positioning in header */



        .waitingTable > thead {
            height: 4.9vh;
        }

        /* Fix the span container around checkbox */

        /*.number-circle , .badge {*/
        /*    width: 24px;*/
        /*    height: 24px;*/
        /*    border-radius: 50%;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    justify-content: center;*/
        /*    color: white;*/
        /*    font-weight: normal;*/
        /*    font-size: 14px;*/
        /*}*/
        /* Make pagination buttons smaller */


        /* Ensure DataTables doesn't mess with table layout */
        .sunriseTable.dataTable {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
            width: 100% !important;
        }



        /* Force equal column widths and prevent expansion of empty columns */


        .sunriseTable tbody:last-child {
            max-width: 10% !important;
            overflow: visible !important;
            text-overflow: clip !important;
            word-wrap: normal !important;


            p {
                margin: 2px !important;
            }

            /* Patient and Doctor column spacing */

            .sunriseTable td:nth-child(2),
            .sunriseTable td:nth-child(3) {
                padding-right: 8px !important;
            }

            /* Tags column alignment - prevent sticking to right */

            .sunriseTable td:nth-child(4) {
                text-align: left !important;
                padding-left: 8px !important;
            }

            /* Assigned To column for delivery tables */

            .sunriseTable td:nth-child(6) {
                font-size: 12px !important;
                text-align: center !important;
            }

            /* Completely disable sorting on no-sort class */

            .sunriseTable thead th.no-sort,
            .sunriseTable.dataTable thead th.no-sort {
                background-image: none !important;
                cursor: default !important;
            }


        }
    </style>
    <!-- ✅ jQuery (must be first) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- ✅ Bootstrap Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ DataTables Core -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

    <!-- ✅ DataTables CSS (classic) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- ✅ DataTables core JS -->
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>


    <!-- ✅ Your Initialization Script -->
    <script>
        $(document).ready(function () {
            if (typeof initializeVisibleTables === 'function') {
                initializeVisibleTables();
            } else {
                console.warn('initializeVisibleTables is not defined');
            }
        });
    </script>

    <script>
        // Simple shimmer loading controller
        // $(document).ready(function() {
        //     var shimmerOverlay = document.getElementById('dashboardShimmer');
        //
        //     if (!shimmerOverlay) {
        //         return;
        //     }
        //
        //     // Function to hide shimmer
        //     function hideShimmer() {
        //         shimmerOverlay.classList.add('loaded');
        //     }
        //
        //     // Wait for DataTables to finish initializing
        //     var checkTablesInterval = setInterval(function() {
        //         var allTablesReady = true;
        //
        //         // Check if DataTables are initialized
        //         if (typeof $.fn.DataTable !== 'undefined') {
        //             var tableCount = $('.sunriseTable').length;
        //             var initializedCount = $('.sunriseTable').filter(function() {
        //                 return $.fn.DataTable.isDataTable(this);
        //             }).length;
        //
        //             // If we have tables but none are initialized yet, keep waiting
        //             if (tableCount > 0 && initializedCount === 0) {
        //                 allTablesReady = false;
        //             }
        //         }
        //
        //         // If all tables are ready, hide shimmer
        //         if (allTablesReady) {
        //             clearInterval(checkTablesInterval);
        //             setTimeout(hideShimmer, 200);
        //         }
        //     }, 100);
        //
        //     // Failsafe: Always hide shimmer after 2.5 seconds
        //     setTimeout(function() {
        //         clearInterval(checkTablesInterval);
        //         hideShimmer();
        //     }, 2500);
        // });
    </script>

    <!-- Then load Macaw Tabs -->
    <script
        src="{{ asset('https://cdn.jsdelivr.net/gh/htmlcssfreebies/macaw-tabs@v1.0.4/dist/js/macaw-tabs.js') }}"></script>

    <!-- Then load your custom scripts -->
    <script src="{{ asset('assets') }}/js/ysh-custom-js/v3scripts.js"></script>
    <script src="{{ asset('assets') }}/js/ysh-custom-js/operationsDashboardJS.js"></script>
    <script>
        // Load scripts sequentially to ensure proper dependency order
        function loadScript(src) {
            return new Promise((resolve, reject) => {
                const script = document.createElement('script');
                script.src = src;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }



        // Start loading scripts
        function setOuterTab(element) {
            // Remove active class from all tab buttons
            $('.stageSidebar button[role="tab"]').attr('aria-selected', 'false');

            // Set current tab as selected
            $(element).attr('aria-selected', 'true');

            // Hide all tab panels
            $('div[role="tabpanel"]').attr('hidden', true);

            // Get the target panel ID
            var targetPanelId = $(element).attr('aria-controls');

            // Show the corresponding panel
            $('#' + targetPanelId).removeAttr('hidden');

            // Save the selected outer tab to cookies
            var tabId = $(element).attr('id');
            if (tabId) {
                Cookies.set('activeOuterTab', tabId);
                console.log("Saved outer tab to cookie:", tabId);
            }

            // Reinitialize Macaw Tabs to ensure proper functionality
            if (typeof MacawTabs !== 'undefined') {
                MacawTabs.init();
            }

            // Restore previously selected inner tab or default to waiting
            setTimeout(() => {
                const stageKey = targetPanelId.replace('label', '');
                const stageMapping = {
                    'design': 1,
                    'milling': 2,
                    '3dprinting': 3,
                    'sintering': 4,
                    'pressing': 5,
                    'finishing': 6,
                    'qc': 7,
                    'delivery': 8
                };

                const stageNumber = stageMapping[stageKey];
                let activeInnerTab = null;

                if (stageNumber) {
                    activeInnerTab = Cookies.get('inner' + stageNumber);
                }

                // If no saved tab or first time, default to waiting
                if (!activeInnerTab) {
                    activeInnerTab = 'waiting-' + stageKey + 'label';
                    console.log("Defaulting to waiting tab for stage:", stageKey);
                }

                // Normalize for 3D printing
                if (activeInnerTab && activeInnerTab.toLowerCase().includes('3dprinting')) {
                    activeInnerTab = activeInnerTab.replace(/3[dD][pP]rinting/i, '3dprinting');
                }

                const innerTabButton = $('#' + activeInnerTab);
                if (innerTabButton.length) {
                    console.log("Restoring inner tab:", activeInnerTab);
                    innerTabButton.trigger('click');
                } else {
                    // Fallback to waiting tab
                    const waitingButton = $(`#waiting-${stageKey}label`);
                    if (waitingButton.length) {
                        console.log("Fallback to waiting tab:", `waiting-${stageKey}label`);
                        waitingButton.trigger('click');
                    }
                }



                // Initialize only visible tables for better performance
                if (typeof initializeVisibleTables === 'function' && typeof $.fn.DataTable !== 'undefined') {
                    initializeVisibleTables();
                }


            }, 100);
        }

    </script>
    <script>


        const container = document.getElementById('dashboardShimmer');

        function startLoading() {
            container.classList.add('loading');
        }

        function stopLoading() {
            container.classList.remove('loading');
        }

        // Simulate loading
        startLoading();
        setTimeout(stopLoading, 2000); // remove shimmer after 2s

        // Apply device image configuration when document is ready
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Macaw Tabs if available
            if (typeof MacawTabs !== 'undefined') {
                MacawTabs.init();
            }


            // Apply device image styling from configuration
            if (typeof jQuery !== 'undefined') {
                // Apply to all device images
                jQuery('.device-item img').css({
                    'width': '{{ $deviceConfig["width"] }}',
                    'max-width': '{{ $deviceConfig["max_width"] }}',
                    'height': '{{ $deviceConfig["height"] }}',
                    'padding': '{{ $deviceConfig["padding"] }}',
                    'border-radius': '{{ $deviceConfig["border_radius"] }}',
                    'background': '{{ $deviceConfig["background"] }}',
                    'object-fit': 'contain'
                });

                // Apply to device containers
                jQuery('.device-container').css({
                    'gap': '{{ $deviceConfig["container_gap"] ?? "15px" }}',
                    'width': '100%'
                });

                // Remove hover effects if disabled in config
                @if(!$deviceConfig["hover_effect"])
                jQuery('.device-item').hover(
                    function () {
                        jQuery(this).css({
                            'box-shadow': 'none',
                            'transform': 'none'
                        });
                    },
                    function () {
                        jQuery(this).css({
                            'box-shadow': 'none',
                            'transform': 'none'
                        });
                    }
                );
                @endif

                // Ensure images don't break out of containers
                jQuery('.device-item').css({
                    'overflow': 'hidden',
                    'max-width': '{{ $deviceConfig["max_width"] }}'
                });

                // Setup dialog reset functionality
                setupDialogResetHandlers();
            }
        });

        /**
         * Setup handlers to reset dialogs to their original state when closed
         */
        function setupDialogResetHandlers() {
            // Store original states when a modal is opened
            jQuery('.modal').on('show.bs.modal', function () {
                var $modal = jQuery(this);

                // Store original button states
                $modal.find('button').each(function () {
                    var $btn = jQuery(this);
                    $btn.data('original-disabled', $btn.prop('disabled'));
                    $btn.data('original-text', $btn.text());
                    $btn.data('original-class', $btn.attr('class'));
                });

                // Store original input values
                $modal.find('input, textarea, select').each(function () {
                    var $input = jQuery(this);
                    $input.data('original-value', $input.val());
                    $input.data('original-checked', $input.prop('checked'));
                    $input.data('original-disabled', $input.prop('disabled'));
                });

                // Store original image states
                $modal.find('img').each(function () {
                    var $img = jQuery(this);
                    $img.data('original-src', $img.attr('src'));
                    $img.data('original-class', $img.attr('class'));
                    $img.data('original-style', $img.attr('style'));
                });

                // Store device selection states
                $modal.find('.device-item').each(function () {
                    var $device = jQuery(this);
                    $device.data('original-class', $device.attr('class'));
                    $device.data('original-style', $device.attr('style'));
                    $device.data('original-selected', $device.hasClass('selected'));
                });

                // Store checkbox states
                $modal.find('input[type="checkbox"]').each(function () {
                    var $checkbox = jQuery(this);
                    $checkbox.data('original-checked', $checkbox.prop('checked'));
                });
            });

            // Reset to original state when a modal is hidden
            jQuery('.modal').on('hidden.bs.modal', function () {
                var $modal = jQuery(this);

                // Reset buttons
                $modal.find('button').each(function () {
                    var $btn = jQuery(this);
                    if ($btn.data('original-disabled') !== undefined) {
                        $btn.prop('disabled', $btn.data('original-disabled'));
                    }
                    if ($btn.data('original-text')) {
                        $btn.text($btn.data('original-text'));
                    }
                    if ($btn.data('original-class')) {
                        $btn.attr('class', $btn.data('original-class'));
                    }
                });

                // Reset inputs
                $modal.find('input, textarea, select').each(function () {
                    var $input = jQuery(this);
                    if ($input.data('original-value') !== undefined) {
                        $input.val($input.data('original-value'));
                    }
                    if ($input.data('original-checked') !== undefined) {
                        $input.prop('checked', $input.data('original-checked'));
                    }
                    if ($input.data('original-disabled') !== undefined) {
                        $input.prop('disabled', $input.data('original-disabled'));
                    }
                });

                // Reset images
                $modal.find('img').each(function () {
                    var $img = jQuery(this);
                    if ($img.data('original-src')) {
                        $img.attr('src', $img.data('original-src'));
                    }
                    if ($img.data('original-class')) {
                        $img.attr('class', $img.data('original-class'));
                    }
                    if ($img.data('original-style')) {
                        $img.attr('style', $img.data('original-style'));
                    }
                });

                // Reset device selections
                $modal.find('.device-item').each(function () {
                    var $device = jQuery(this);
                    if ($device.data('original-class')) {
                        $device.attr('class', $device.data('original-class'));
                    }
                    if ($device.data('original-style')) {
                        $device.attr('style', $device.data('original-style'));
                    }

                    // Handle selected state
                    if ($device.data('original-selected') === true) {
                        $device.addClass('selected');
                    } else {
                        $device.removeClass('selected');
                    }

                });

                // Reset checkboxes
                $modal.find('input[type="checkbox"]').each(function () {
                    var $checkbox = jQuery(this);
                    if ($checkbox.data('original-checked') !== undefined) {
                        $checkbox.prop('checked', $checkbox.data('original-checked'));
                    }
                });

                // Reset any "Select All" checkboxes
                $modal.find('.selectAllCases').prop('checked', false);

                // Clear any error messages
                $modal.find('.alert, .error-message').remove();

                // Reset any custom form data
                if (typeof resetCustomFormData === 'function') {
                    resetCustomFormData($modal);
                }
            });
        }

        /**
         /**
         * Reset custom form data specific to certain dialogs
         * This can be extended for specific dialog types
         */
        function resetCustomFormData($modal) {
            // Reset build name for 3D printing
            if ($modal.find('#hidden3dprintingBuildName').length) {
                $modal.find('#hidden3dprintingBuildName').val('');
            }

            // Reset device selection
            if (typeof selectedMachineId !== 'undefined') {
                selectedMachineId = null;
            }

            // Reset any selected cases arrays
            if (typeof selectedCases !== 'undefined') {
                selectedCases = [];
            }

            // Reset any waiting popup checkboxes
            jQuery('[id^="WaitingPopupCheckBoxes"]').val('');

            // Reset any device-specific selections
            jQuery('.device-item').removeClass('selected');
            jQuery('.device-item img').removeClass('selected');
        }
    </script>
@endpush
