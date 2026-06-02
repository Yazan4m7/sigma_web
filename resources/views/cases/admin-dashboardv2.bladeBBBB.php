@extends('layouts.app', ['pageSlug' => config('site_vars.labWorkFlowLabel'), 'class' => ' ops-dashboard'])

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
    <!--suppress ALL -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets') }}/css/ysh-custom-css/dialog.css" rel="stylesheet" />
    {{--    <link href="{{ asset('assets') }}/css/devices-dialog-fix.css" rel="stylesheet"/> --}}
    <link href="{{ asset('assets') }}/css/ysh-custom-css/OperationsDashboardStyling.css?v={{ filemtime(public_path('assets/css/ysh-custom-css/OperationsDashboardStyling.css')) }}" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/active-cases.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/waiting-dialog.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/operations-dashboard-table-fix.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/v3styles.css" rel="stylesheet">
    <!-- Responsive CSS - Mobile-first approach for full device compatibility -->
    <link href="{{ asset('assets') }}/css/responsive.css" rel="stylesheet">
    <!-- Operations Dashboard Navigation - Responsive CSS for mobile stage icons -->
    <link href="{{ asset('assets') }}/css/operations-nav-responsive.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/waiting-dialog-merged.css" rel="stylesheet" />

    <style>
        .btn-outline-danger:hover{
            color:white !important;
        }
        .col-12 {
            padding: 0;
        }
        .input-group, .form-group {
            padding-left: 5px !important;
        }

        /* Scrollable jobs/notes section - applies to all screen sizes */
        .sigma-modal--cases-dashboard-case-completion .scrollable-content,
        .sigma-modal--cases-dashboard-case-completion-alt .scrollable-content {
            max-height: 40vh;
            overflow-y: auto;
            overflow-x: hidden;
        }

        @media (max-width: 480px){
            .sigma-modal--cases-dashboard-case-completion .sigma-workflow-dialog,
            .sigma-modal--cases-dashboard-case-completion-alt .sigma-workflow-dialog,
            .sigma-modal--cases-dashboard-loading .sigma-workflow-dialog,
            .sigma-modal--active-cases-preview .sigma-workflow-dialog,
            .sigma-modal--waiting-3d-printing .sigma-workflow-dialog,
            .sigma-modal--waiting-delivery .sigma-workflow-dialog,
            .sigma-modal--waiting-generic .sigma-workflow-dialog {
                max-width: none !important;
                width: auto !important;
                min-width: -webkit-fill-available;
                margin: 0 15px;
            }

            .sigma-modal--cases-dashboard-case-completion .modal-footer .col-12,
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .col-12,
            .sigma-modal--cases-dashboard-loading .modal-footer .col-12,
            .sigma-modal--active-cases-preview .modal-footer .col-12,
            .sigma-modal--waiting-3d-printing .modal-footer .col-12,
            .sigma-modal--waiting-delivery .modal-footer .col-12,
            .sigma-modal--waiting-generic .modal-footer .col-12 {
                padding-left: 0 !important;
            }

            @media (max-width: 700px){
                .sigma-modal--cases-dashboard-case-completion .sigma-workflow-dialog,
                .sigma-modal--cases-dashboard-case-completion-alt .sigma-workflow-dialog,
                .sigma-modal--cases-dashboard-loading .sigma-workflow-dialog,
                .sigma-modal--active-cases-preview .sigma-workflow-dialog,
                .sigma-modal--waiting-3d-printing .sigma-workflow-dialog,
                .sigma-modal--waiting-delivery .sigma-workflow-dialog,
                .sigma-modal--waiting-generic .sigma-workflow-dialog {
                    max-width: none !important;
                    width: 90% !important;
                }
            }

            /* Use Animate.css for Case Completion modal */

            .modal.fade.sigma-modal--cases-dashboard-case-completion .modal-dialog {
                /*  width: 90%; */
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--cases-dashboard-case-completion-alt .modal-dialog {
                width: 100%;
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--cases-dashboard-loading .modal-dialog {
                width: 90%;
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--active-cases-preview .modal-dialog {
                width: 90%;
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--waiting-3d-printing .modal-dialog {
                width: 90%;
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--waiting-delivery .modal-dialog {
                width: 90%;
                /* Will be animated by Animate.css classes */
            }
            .modal.fade.sigma-modal--waiting-generic .modal-dialog {
                width: 90%;
                /* Will be animated by Animate.css classes */
            }


            .sigma-modal--cases-dashboard-case-completion .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }

            .sigma-modal--cases-dashboard-case-completion .modal-content,
            .sigma-modal--cases-dashboard-case-completion-alt .modal-content {
                font-family: 'Cairo', sans-serif !important;
            }

            .sigma-modal--cases-dashboard-case-completion .modal-title,
            .sigma-modal--cases-dashboard-case-completion-alt .modal-title,
            .sigma-modal--cases-dashboard-case-completion .dialog-title-black-box,
            .sigma-modal--cases-dashboard-case-completion-alt .dialog-title-black-box,
            .sigma-modal--cases-dashboard-case-completion .patient-doctor-label,
            .sigma-modal--cases-dashboard-case-completion-alt .patient-doctor-label,
            .sigma-modal--cases-dashboard-case-completion .patient-doctor-names,
            .sigma-modal--cases-dashboard-case-completion-alt .patient-doctor-names,
            .sigma-modal--cases-dashboard-case-completion .case-completion-dialog-label,
            .sigma-modal--cases-dashboard-case-completion-alt .case-completion-dialog-label,
            .sigma-modal--cases-dashboard-case-completion .sigma-case-job-row,
            .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-row,
            .sigma-modal--cases-dashboard-case-completion .note-container,
            .sigma-modal--cases-dashboard-case-completion-alt .note-container,
            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn,
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn {
                font-family: 'Cairo', sans-serif !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }
            .sigma-modal--active-cases-preview .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }
            .sigma-modal--waiting-3d-printing .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }
            .sigma-modal--waiting-delivery .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }
            .sigma-modal--waiting-generic .modal-content {
                border-radius: 25px !important;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                border: none;
            }

            /* Modal footer rounded bottom corners */

            .sigma-modal--cases-dashboard-case-completion .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--active-cases-preview .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--waiting-3d-printing .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--waiting-delivery .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }
            .sigma-modal--waiting-generic .modal-footer {
                border-bottom-left-radius: 25px !important;
                border-bottom-right-radius: 25px !important;
            }

            /* Modal header styling with divider */

            .sigma-modal--cases-dashboard-case-completion .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--cases-dashboard-loading .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--active-cases-preview .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--waiting-3d-printing .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--waiting-delivery .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }
            .sigma-modal--waiting-generic .modal-header {
                display: none !important;
                border-bottom: 1px solid #dee2e6 !important;
                padding-bottom: 12px;
            }

            /* Modal title styling */

            .sigma-modal--cases-dashboard-case-completion .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--cases-dashboard-loading .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--active-cases-preview .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--waiting-3d-printing .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--waiting-delivery .modal-title {
                color: #2d5f6d;
                font-weight: 600;
                font-size: 18px;
                margin-bottom: 0;
            }
            .sigma-modal--waiting-generic .modal-title {
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

            .sigma-modal--cases-dashboard-case-completion .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--cases-dashboard-loading .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--active-cases-preview .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--waiting-3d-printing .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--waiting-delivery .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }
            .sigma-modal--waiting-generic .modal-header button.close {
                font-size: 32px;
                font-weight: 300;
                color: #000;
                opacity: 0.8;
                text-shadow: none;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--cases-dashboard-loading .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--active-cases-preview .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--waiting-3d-printing .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--waiting-delivery .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }
            .sigma-modal--waiting-generic .modal-header button.close:hover {
                opacity: 1;
                color: #000;
            }

            /* Doctor/Patient names styling */
            .patient-doctor-names {
                color: #2d5f6d;
                font-weight: 600;
            }

            .patient-doctor-label {
                font-size: 12px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                color: #6c757d;
                margin-bottom: 2px;
                display: block;
            }

            .sigma-modal--cases-dashboard-case-completion .patient-doctor-label,
            .sigma-modal--cases-dashboard-case-completion .case-completion-dialog-label,
            .sigma-modal--cases-dashboard-case-completion-alt .patient-doctor-label,
            .sigma-modal--cases-dashboard-case-completion-alt .case-completion-dialog-label {
                font-size: 12px;
                text-transform: uppercase;
                color: #6c757d;
                margin-bottom: 2px;
                display: block;
                font-weight: 400 !important;
                letter-spacing: normal !important;
            }

            .sigma-modal--cases-dashboard-case-completion .case-completion-dialog-label b,
            .sigma-modal--cases-dashboard-case-completion-alt .case-completion-dialog-label b {
                font-weight: 400 !important;
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


            .sigma-modal--cases-dashboard-case-completion .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--active-cases-preview .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--waiting-3d-printing .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--waiting-delivery .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }
            .sigma-modal--waiting-generic .modal-footer {
                display: block;
                padding: 1rem;
                border-top: 1px solid #dee2e6;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--active-cases-preview .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--waiting-delivery .modal-footer .row {
                margin: 0;
            }
            .sigma-modal--waiting-generic .modal-footer .row {
                margin: 0;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .col-6, .sigma-modal--cases-dashboard-case-completion .modal-footer .col-4, .sigma-modal--cases-dashboard-case-completion .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .col-6, .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .col-4, .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .col-6, .sigma-modal--cases-dashboard-loading .modal-footer .col-4, .sigma-modal--cases-dashboard-loading .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--active-cases-preview .modal-footer .col-6, .sigma-modal--active-cases-preview .modal-footer .col-4, .sigma-modal--active-cases-preview .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .col-6, .sigma-modal--waiting-3d-printing .modal-footer .col-4, .sigma-modal--waiting-3d-printing .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--waiting-delivery .modal-footer .col-6, .sigma-modal--waiting-delivery .modal-footer .col-4, .sigma-modal--waiting-delivery .modal-footer .col-12 {
                padding-left: 0 !important;
            }
            .sigma-modal--waiting-generic .modal-footer .col-6, .sigma-modal--waiting-generic .modal-footer .col-4, .sigma-modal--waiting-generic .modal-footer .col-12 {
                padding-left: 0 !important;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }
            .sigma-modal--waiting-generic .modal-footer .btn {
                width: 100%;
                margin: 3px;
                font-weight: 400;
                padding: 10px 12px;
                border: none;
                transition: all 0.3s ease;
                font-size: 14px;
            }

            /* Button color improvements with proper contrast */

            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-info {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-info:hover {
                background-color: #138496;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.4);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-success {
                background-color: #28a745;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-success:hover {
                background-color: #218838;
                box-shadow: 0 4px 8px rgba(40, 167, 69, 0.4);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }
            .sigma-modal--waiting-generic .modal-footer .btn-success:disabled {
                background-color: #6c757d;
                color: #ffffff !important;
                opacity: 0.6;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-warning {
                background-color: #ffc107;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-warning:hover {
                background-color: #e0a800;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(255, 193, 7, 0.4);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-dark {
                background-color: #343a40;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(52, 58, 64, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-dark:hover {
                background-color: #23272b;
                box-shadow: 0 4px 8px rgba(52, 58, 64, 0.4);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-info {
                border: 2px solid #17a2b8;
                background-color: transparent;
                color: #17a2b8 !important;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-info:hover {
                background-color: #17a2b8;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-danger {
                border: 2px solid #dc3545;
                background-color: transparent;
                color: #dc3545 !important;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-secondary {
                border: 2px solid #6c757d;
                background-color: transparent;
                color: #6c757d !important;
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-outline-secondary:hover {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-secondary {
                background-color: #6c757d;
                color: #ffffff !important;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }


            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--active-cases-preview .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--waiting-delivery .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
            .sigma-modal--waiting-generic .modal-footer .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }

            /* Icon spacing */

            .sigma-modal--cases-dashboard-case-completion .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--cases-dashboard-loading .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--active-cases-preview .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--waiting-3d-printing .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--waiting-delivery .modal-footer .btn i {
                margin-right: 6px;
            }
            .sigma-modal--waiting-generic .modal-footer .btn i {
                margin-right: 6px;
            }

            .YSH-button {
                text-decoration: none;
                line-height: 1;
                border-radius: 1.5rem;
                overflow: hidden;
                position: relative;
                box-shadow: 10px 10px 20px rgba(0, 0, 0, .05);
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
        }
        }


    </style>
    <style>
        .sigma-modal--cases-dashboard-case-completion .modal-body label.case-jobs-label,
        .sigma-modal--cases-dashboard-case-completion-alt .modal-body label.case-jobs-label,
        .sigma-modal--dashboard-waiting-actions .modal-body label.case-jobs-label,
        .sigma-modal--dashboard-active-case-actions .modal-body label.case-jobs-label,
        .ysh-case-slide-modal .modal-body label.case-jobs-label {
            display: block;
            margin-bottom: 8px !important;
            color: #444441 !important;
            font-size: 10px !important;
            font-weight: 500 !important;
            letter-spacing: 0.09em !important;
            line-height: 1.2 !important;
            text-transform: uppercase !important;
        }

        .sigma-modal--cases-dashboard-case-completion .modal-body label.case-jobs-label b,
        .sigma-modal--cases-dashboard-case-completion-alt .modal-body label.case-jobs-label b,
        .sigma-modal--dashboard-waiting-actions .modal-body label.case-jobs-label b,
        .sigma-modal--dashboard-active-case-actions .modal-body label.case-jobs-label b,
        .ysh-case-slide-modal .modal-body label.case-jobs-label b {
            color: inherit !important;
            font-weight: inherit !important;
        }

        .sigma-modal--cases-dashboard-case-completion .case-notes-label,
        .sigma-modal--cases-dashboard-case-completion-alt .case-notes-label,
        .sigma-modal--dashboard-waiting-actions .case-notes-label,
        .sigma-modal--dashboard-active-case-actions .case-notes-label,
        .ysh-case-slide-modal .case-notes-label {
            display: block;
            margin-bottom: 8px !important;
            color: #B4B2A9 !important;
            font-size: 10px !important;
            font-weight: 500 !important;
            letter-spacing: 0.09em !important;
            line-height: 1.2 !important;
            text-transform: uppercase !important;
        }

        .sigma-modal--cases-dashboard-case-completion .modal-body .sigma-case-jobs-list,
        .sigma-modal--cases-dashboard-case-completion-alt .modal-body .sigma-case-jobs-list,
        .sigma-modal--dashboard-waiting-actions .modal-body .sigma-case-jobs-list,
        .sigma-modal--dashboard-active-case-actions .modal-body .sigma-case-jobs-list,
        .ysh-case-slide-modal .modal-body .sigma-case-jobs-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 5px !important;
            width: 100%;
            margin: 0 0 1.375rem !important;
            padding: 0 !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }


        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-row,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-row,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-row,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-row,
        .ysh-case-slide-modal .sigma-case-job-row {
            background: #fff !important;
            background-color: #fff !important;
            display: flex !important;
            align-items: center !important;
            gap: 5px !important;
            width: 100%;
            min-width: 0;
            padding: 11px 14px !important;
            margin: 0 !important;
            border: 1px solid #e0e0da !important;
            border-radius: 10px !important;
            color: inherit;
            font-size: 13px;
            line-height: 1.25 !important;
            overflow: hidden !important;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
            box-shadow: none !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-primary,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-primary,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-primary,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-primary,
        .ysh-case-slide-modal .sigma-case-job-primary {
            display: block;
            max-width: 100%;
            width: 100%;
            color: #1a1a18 !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            line-height: 1.25 !important;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-icon,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-icon,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-icon,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-icon,
        .ysh-case-slide-modal .sigma-case-job-icon {
            flex-shrink: 0;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-info,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-info,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-info,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-info,
        .ysh-case-slide-modal .sigma-case-job-info {
            display: block;
            flex: 1 1 0%;
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-sub,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-sub,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-sub,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-sub,
        .ysh-case-slide-modal .sigma-case-job-sub {
            color: #888780;
            display: block;
            font-size: 11px;
            line-height: 1.25;
            margin-top: 2px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-tag,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-tag,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-tag,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-tag,
        .ysh-case-slide-modal .sigma-case-job-tag {
            border: 0.5px solid #9FE1CB;
            border-radius: 20px;
            color: #0F6E56;
            flex-shrink: 0;
            font-size: 10px;
            line-height: 1.2;
            max-width: 34%;
            overflow: hidden;
            padding: 3px 9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sigma-modal--cases-dashboard-case-completion .modal-footer .btn,
        .sigma-modal--cases-dashboard-case-completion-alt .modal-footer .btn,
        .sigma-modal--dashboard-waiting-actions .modal-footer .btn,
        .sigma-modal--dashboard-active-case-actions .modal-footer .btn,
        .ysh-case-slide-modal .waiting-actions .btn {
            align-items: center;
            display: inline-flex;
            height: 40px !important;
            justify-content: center;
            min-height: 40px !important;
            padding-top: 7px !important;
            padding-bottom: 7px !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-notes-list,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-notes-list,
        .sigma-modal--dashboard-waiting-actions .sigma-case-notes-list,
        .sigma-modal--dashboard-active-case-actions .sigma-case-notes-list,
        .ysh-case-slide-modal .sigma-case-notes-list {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.375rem;
        }

        .sigma-modal--cases-dashboard-case-completion .note-container,
        .sigma-modal--cases-dashboard-case-completion-alt .note-container,
        .sigma-modal--dashboard-waiting-actions .note-container,
        .sigma-modal--dashboard-active-case-actions .note-container,
        .ysh-case-slide-modal .note-container {
            align-items: flex-start;
            background: #fff !important;
            border: 0.5px solid #eaeae4 !important;
            border-top: none !important;
            box-shadow: none !important;
            color: inherit !important;
            display: flex;
            gap: 10px;
            padding: 9px 14px !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-notes-list > .note-container:first-child,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-notes-list > .note-container:first-child,
        .sigma-modal--dashboard-waiting-actions .sigma-case-notes-list > .note-container:first-child,
        .sigma-modal--dashboard-active-case-actions .sigma-case-notes-list > .note-container:first-child,
        .ysh-case-slide-modal .sigma-case-notes-list > .note-container:first-child {
            border-radius: 9px 9px 0 0 !important;
            border-top: 0.5px solid #eaeae4 !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-notes-list > .note-container:last-child,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-notes-list > .note-container:last-child,
        .sigma-modal--dashboard-waiting-actions .sigma-case-notes-list > .note-container:last-child,
        .sigma-modal--dashboard-active-case-actions .sigma-case-notes-list > .note-container:last-child,
        .ysh-case-slide-modal .sigma-case-notes-list > .note-container:last-child {
            border-radius: 0 0 9px 9px !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-left,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-left,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-left,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-left,
        .ysh-case-slide-modal .sigma-case-note-left {
            align-items: center;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            gap: 3px;
            padding-top: 2px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-icon,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-icon,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-icon,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-icon,
        .ysh-case-slide-modal .sigma-case-note-icon {
            color: #85B7EB;
            font-size: 12px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-line,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-line,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-line,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-line,
        .ysh-case-slide-modal .sigma-case-note-line {
            background: #daeaf7;
            flex: 1;
            min-height: 14px;
            width: 1px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-content,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-content,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-content,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-content,
        .ysh-case-slide-modal .sigma-case-note-content {
            min-width: 0;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-meta,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-meta,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-meta,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-meta,
        .ysh-case-slide-modal .sigma-case-note-meta {
            align-items: center;
            display: flex;
            gap: 5px;
            margin-bottom: 2px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-author,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-author,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-author,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-author,
        .ysh-case-slide-modal .sigma-case-note-author {
            color: #378ADD !important;
            font-size: 11px !important;
            font-weight: 500 !important;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-separator,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-separator,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-separator,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-separator,
        .ysh-case-slide-modal .sigma-case-note-separator {
            color: #daeaf7;
            font-size: 11px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-time,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-time,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-time,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-time,
        .ysh-case-slide-modal .sigma-case-note-time {
            color: #85B7EB;
            font-size: 10px;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-note-text,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-note-text,
        .sigma-modal--dashboard-waiting-actions .sigma-case-note-text,
        .sigma-modal--dashboard-active-case-actions .sigma-case-note-text,
        .ysh-case-slide-modal .sigma-case-note-text {
            color: #888780;
            display: block;
            font-size: 12px;
            line-height: 1.45;
        }

        .sigma-modal--cases-dashboard-case-completion .sigma-case-job-row::-webkit-scrollbar,
        .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-row::-webkit-scrollbar,
        .sigma-modal--dashboard-waiting-actions .sigma-case-job-row::-webkit-scrollbar,
        .sigma-modal--dashboard-active-case-actions .sigma-case-job-row::-webkit-scrollbar,
        .ysh-case-slide-modal .sigma-case-job-row::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 767.98px) {
            .sigma-modal--cases-dashboard-case-completion .sigma-case-job-row,
            .sigma-modal--cases-dashboard-case-completion-alt .sigma-case-job-row,
            .sigma-modal--dashboard-waiting-actions .sigma-case-job-row,
            .sigma-modal--dashboard-active-case-actions .sigma-case-job-row,
            .ysh-case-slide-modal .sigma-case-job-row {
                font-size: 13px;
                padding: 9px 6px !important;
            }
        }



        .dt-layout-row {
            margin: 0px !important;
        }

        .dt-center {
            text-align: center !important;
        }

        tr>th.dt-orderable-none.dt-type-numeric>div>span {
            text-align: center !important;
        }



        input[type="checkbox"], input[type="radio"] {
            transform: scale(1.3);
        }

        /* Device container styling */
        .device-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: {{ $deviceConfig['container_gap'] ?? '15px' }};
            width: 100%;
        }

        .device-item {
            width: 100%;
            max-width: {{ $deviceConfig['max_width'] ?? '150px' }};
            text-align: center;
            margin-bottom: {{ $deviceConfig['margin_bottom'] ?? '15px' }};
            transition: all 0.3s ease;
        }

        .device-item img {
            width: {{ $deviceConfig['width'] ?? '100%' }};
            height: {{ $deviceConfig['height'] ?? 'auto' }};
            max-width: {{ $deviceConfig['max_width'] ?? '150px' }};
            padding: {{ $deviceConfig['padding'] ?? '10px' }};
            border-radius: {{ $deviceConfig['border_radius'] ?? '8px' }};
            background: {{ $deviceConfig['background'] ?? 'transparent' }};
            object-fit: contain;
        }

        .device-item:hover {
            transform: {{ $deviceConfig['hover_effect'] ? 'scale(1.05)' : 'none' }};
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
        @media (max-width: 768px){
            .device-item {
                max-width: {{ $deviceConfig['responsive_sizes']['tablet'] ?? '120px' }};
            }

            .device-item img {
                max-width: {{ $deviceConfig['responsive_sizes']['tablet'] ?? '120px' }};
            }
        }

        @media (max-width: 576px){
            .device-item {
                max-width: {{ $deviceConfig['responsive_sizes']['mobile'] ?? '100px' }};
            }

            .device-item img {
                max-width: {{ $deviceConfig['responsive_sizes']['mobile'] ?? '100px' }};
            }
        }

        @media (max-width: 376px){
            max-width: 0 !important;
        }

        td>p {
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
        .sunriseTable {
            overflow: auto;
        }
    </style>

    <style>
        @keyframes shimmer {
            0% {
                background-position: -200px 0;
            }

            100% {
                background-position: 200px 0;
            }
        }

        .loading * {
            color: transparent !important;
            pointer-events: none;
            user-select: none;
        }

        .loading td, .loading th, .loading div, .loading button, .loading span, .loading li {
            position: relative;
            background: #e0e0e0;
            overflow: hidden;
        }

        .loading td::after, .loading th::after, .loading div::after, .loading button::after, .loading span::after, .loading li::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right,
            #e0e0e0 0%,
            #f6f6f6 20%,
            #e0e0e0 40%,
            #e0e0e0 100%);
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
    <script>
        window.enableModalPositioning = true;
    </script>
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

    {{--    @php --}}
    {{--        try { --}}
    {{--    @endphp --}}


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
            <defs><style>.fa-secondary {opacity:.4}</style>
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
            if (!($permissions && $permissions->contains('permission_id', 1))) {
                unset($stages['design']);
            }
            if (!($permissions && $permissions->contains('permission_id', 2))) {
                unset($stages['milling']);
            }
            if (!($permissions && $permissions->contains('permission_id', 3))) {
                unset($stages['3dprinting']);
            }
            if (!($permissions && $permissions->contains('permission_id', 4))) {
                unset($stages['sintering']);
            }
            if (!($permissions && $permissions->contains('permission_id', 5))) {
                unset($stages['pressing']);
            }
            if (!($permissions && $permissions->contains('permission_id', 6))) {
                unset($stages['finishing']);
            }
            if (!($permissions && $permissions->contains('permission_id', 7))) {
                unset($stages['qc']);
                unset($stages['QC']);
                unset($stages['Qc']);
            }
            if (!($permissions && $permissions->contains('permission_id', 8))) {
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
            <div class="macaw-tabs macaw-aurora-tabs notransition" style="position: relative;">

                <div role="tablist" class="stageSidebar" aria-orientation="vertical">
                    @foreach ($stages as $key => $stage)
                        @php
                            // For display and ID purposes, lowercase for all stages. 3dprinting
                            $keyId = strtolower($key);
                            $displayKey = $key;

                            $displayKey = $key == '3dprinting' ? 'Printing' : $displayKey;
                            $displayKey = $key == 'Qc' ? 'QC' : $displayKey;
                        @endphp
                        <button role="tab" aria-selected="false" aria-controls="{{ $keyId . 'label' }}"
                                id="{{ $keyId }}"  onclick="setOuterTab(this)">
                            <span class="iconSpan" style="display: flex;align-items: center;">{!! $stage['icon'] !!}
                                <span style=" padding-left:6px" class="stageName"> {{ $displayKey }}</span></span>
                            <div>
                                <span class="badge bg-info m-1 activeBadge">{{ count($stage['activeCases']) }}</span>
                                <span class="badge bg-info m-1 waitingBadge"
                                >{{ count($stage['waitingCases']) }} </span>
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
                                 id="{{ 'waiting-' . $key }}" class="stage-panel-pane">

                                @switch(strtolower($key))
                                    @case('milling')
                                        <x-waiting-dialog title="Choose Machine" btnText="NEST" type="milling" :devices="$devices"
                                                          :types="$types" :typesByMaterial="$typesByMaterial" stageId="2" />
                                        <button type="submit" class="btn btn-primary receiveSelectBtn milling"
                                                style="display:none; margin:5px;" onclick="openModal('milling',true)">SET
                                        </button>
                                        @break

                                    @case('3dprinting')
                                        @php $key = "3dprinting"; @endphp
                                        <x-waiting-3dprinting-dialog title="Choose Printer" btnText="SET" type="3dprinting"
                                                                     :devices="$devices" stageId="3" showBuildName="true" />
                                        <button type="submit" class="btn btn-primary receiveSelectBtn 3dprinting"
                                                style="display:none; margin:5px;" onclick="openModal('3dprinting',true)">SET
                                        </button>
                                        @break

                                    @case('sintering')
                                        <x-waiting-dialog title="Choose Furnace" btnText="SET" type="sintering" :devices="$devices"
                                                          :types="$types" :typesByMaterial="$typesByMaterial" stageId="4" />
                                        <button type="submit" class="btn btn-primary receiveSelectBtn sintering"
                                                style="display:none; margin:5px;" onclick="openModal('sintering',true)">SET
                                        </button>
                                        @break

                                    @case('pressing')
                                        <x-waiting-dialog title="Choose Furnace" btnText="SET" type="pressing" :devices="$devices"
                                                          :types="$types" :typesByMaterial="$typesByMaterial" stageId="5" />
                                        <button type="submit" class="btn btn-primary receiveSelectBtn pressing"
                                                style="display:none; margin:5px;" onclick="openModal('pressing',true)">SET
                                        </button>
                                        @break

                                    @case('delivery')
                                        @php
                                            // Define this BEFORE using it - includes admins and users with permission 129
                                            $isDeliveryAndAssignable =
                                                Auth()->user()->is_admin ||
                                                ($permissions && $permissions->contains('permission_id', 129));
                                        @endphp

                                        <x-waiting-delivery-dialog title="Assign to"
                                                                   btnText="{{ $isDeliveryAndAssignable ? 'ASSIGN TO' : 'ASSIGN' }}" :drivers="$drivers"
                                                                   stageId="5" />
                                        <button type="submit" class="btn btn-primary receiveSelectBtn delivery"
                                                style="display:none; margin:5px;"
                                                onclick="openModal('DeliveryDialog',false)">{{ $isDeliveryAndAssignable ? 'ASSIGN TO' : 'ASSIGN' }}
                                        </button>
                                        @break
                                @endswitch
                                <div class="stage-panel-scroll">
                                    @if (($key != 'delivery' || $isDeliveryAndAssignable) && ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery') && count($stage['waitingCases']) != 0)
                                        <div class="ops-case-list__bulk d-md-none">
                                            <label class="ops-case-list__bulk-toggle">
                                                <input type="checkbox"
                                                       class="selectAllCases {{ $key }}" value="0"
                                                       name="selectAllCases"
                                                       onchange="selectAll(this, '{{ $key }}')" />
                                                <span>Select all</span>
                                            </label>
                                        </div>
                                    @endif
                                    <div class="d-none d-md-block">
                                        <table class="waitingTable sunriseTable no-auto-colresize" style="width:100%;">
                                            <thead>
                                            <tr>
                                                {{-- Show checkboxes for all stages EXCEPT delivery without permission --}}
                                                @if ($key != 'delivery' || $isDeliveryAndAssignable)
                                                    @if ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery')
                                                        {{-- Checkbox column header --}}
                                                        @if (count($stage['waitingCases']) != 0)
                                                            <th class="no-sort text-center ops-col ops-col--select" style="width: 50px;">
                                                                <input type="checkbox"
                                                                       class="selectAllCases {{ $key }}" value="0"
                                                                       name="selectAllCases"
                                                                       onchange="selectAll(this, '{{ $key }}')" />
                                                            </th>
                                                        @endif
                                                    @endif
                                                @endif
                                                <th class="ops-col ops-col--doctor">Doctor</th>
                                                <th class="ops-col ops-col--patient">Patient</th>
                                                <th class="deliveryDateHeader ops-col ops-col--delivery"><span
                                                        class="innerSpan4Mobile">D.Date</span><span
                                                        class="innerSpan4DeskTop">Delivery Date</span></th>
                                                @if ($key == 'delivery')
                                                    <th class="ops-col ops-col--assigned"> Assigned To</th>
                                                @endif
                                                <th class="ops-col ops-col--count">#</th>
                                                <th class="ops-col ops-col--tags">Tags</th>
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
                                                            <td class="no-sort ops-col ops-col--select">
                                                                <input type="checkbox" data-type="{{ $key }}"
                                                                       data-group-id="{{ $key }}"
                                                                       class="custom-control-input multipleCB {{ $key }}   checkboxes-group-{{ $key }}"
                                                                       value="{{ $case->id }}"
                                                                       name="CheckBoxes{{ $key }}[]"
                                                                       onchange="multiCBChanged('{{ $key }}',this, '{{ $case->id }}')">
                                                            </td>
                                                        @endif
                                                    @endif
                                                    <td class="clickable ops-col ops-col--doctor" data-toggle="modal"
                                                        data-target="#waitingDialog{{ $key . $case->id }}">
                                                        <p class="">{{ $case->client?->name ?? 'Err404-1' }}</p>
                                                    </td>
                                                    <td class="clickable ops-col ops-col--patient" data-toggle="modal" dir="auto"
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
                                                    <td class="clickable ops-col ops-col--delivery" data-toggle="modal"
                                                        data-target="#waitingDialog{{ $key . $case->id }}">
                                                        <p class="">
                                                            {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}
                                                        </p>
                                                    </td>
                                                    <!-- Assigned to for delivery stage -->
                                                    @if ($key == 'delivery')
                                                        <td class="clickable ops-col ops-col--assigned" data-toggle="modal"
                                                            data-target="#waitingDialog{{ $key . $case->id }}">
                                                            <p class="">
                                                                {{ $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo
                                                                    ? $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo->name_initials
                                                                    : 'None' }}
                                                            </p>
                                                        </td>
                                                    @endif
                                                    <td class="clickable ops-col ops-col--count" data-toggle="modal"
                                                        data-target="#waitingDialog{{ $key . $case->id }}">
                                                        <p class="">{{ $case->unitsAmount($stage['numericStage']) }}</p>
                                                    </td>

                                                    <td class="clickable ops-col ops-col--tags" data-toggle="modal"
                                                        data-target="#waitingDialog{{ $key . $case->id }}">
                                                        <div>
                                                            @foreach ($case->tags as $tag)
                                                                <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                                   style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                                   class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }}  fa-lg"></i>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                                {{-- BEGIN WAITING DIALOG --}}
                                                <div class="modal fade sigma-modal--cases-dashboard-case-completion{{ $key == 'delivery' ? ' sigma-modal--case-completion-delivery' : '' }}{{ $key == 'qc' ? ' sigma-modal--case-completion-qc' : '' }}" tabindex="-1" role="dialog"
                                                     id="waitingDialog{{ $key . $case->id }}">
                                                    <div class="modal-dialog modal-dialog-centered fade-in-down-local" role="document">
                                                        <div class="modal-content">
                                                            <form
                                                                action="{{ $key == 'delivery' ? route('delivery-accept', $case->id) : route('assign-to-me', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                method="GET">
                                                                @csrf
                                                                <input type="hidden" name="case_id" value="{{ $case->id }}">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Case Completion</h5>
                                                                    @if (Auth()->user()->is_admin)
                                                                        <div class="tooltipY">
                                                                            <a
                                                                                href="{{ route('finish-case-completely', ['caseId' => $case->id]) }}">
                                                                                <i
                                                                                    class="fa-solid fa-forward-fast skip-to-delivery-icon"></i>
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
                                                                            <label for="doctor" class="patient-doctor-label">Doctor:</label>
                                                                            <h5 id="doctor" class="patient-doctor-names">
                                                                                {{ $case->client?->name }}</h5>
                                                                        </div>
                                                                        <div class="form-group col-6 "
                                                                             style="margin-bottom: 0px">
                                                                            <label for="pat" class="patient-doctor-label">Patient name:</label>
                                                                            <h5 id="pat" class="patient-doctor-names">
                                                                                {{ $case->patient_name }}</h5>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                                    <!-- Scrollable Jobs and Notes section -->
                                                                    <div class="scrollable-content">
                                                                        <div class="form-group row">
                                                                            <div class=" col-12 ">
                                                                                <label class="case-completion-dialog-label case-jobs-label"><b>Jobs:</b></label>
                                                                                <div class="sigma-case-jobs-list">
                                                                                    @foreach ($case->jobs as $job)
                                                                                        @php
                                                                                            $unit = explode(
                                                                                                ', ',
                                                                                                $job->unit_num,
                                                                                            );
                                                                                            // Check if this job goes through the current stage based on material
                                                                                            $showJob = $job->goesThroughStage($stage['numericStage']);
                                                                                        @endphp

                                                                                        @if ($showJob)
                                                                                            @php
                                                                                                $jobTypeName = $job->jobType->name ?? 'No Job Type';
                                                                                                $materialName = $job->material->name ?? 'no material';
                                                                                                $colorLabel = $job->color == '0' ? '' : $job->color;
                                                                                                $styleLabel = $job->style == 'None' ? '' : $job->style;
                                                                                                $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? 'Implant Type: ' . $job->implantR->name : '';
                                                                                                $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? 'Abutment Type: ' . $job->abutmentR->name : '';
                                                                                                $stageLabel = $stage['name'] ?? $key ?? 'Stage';
                                                                                                $jobTitleParts = array_values(array_filter([
                                                                                                    trim((string) $job->unit_num),
                                                                                                    trim((string) $jobTypeName),
                                                                                                    trim((string) $materialName),
                                                                                                    trim((string) $colorLabel),
                                                                                                    trim((string) $implantLabel),
                                                                                                    trim((string) $abutmentLabel),
                                                                                                ], function ($value) {
                                                                                                    return $value !== '';
                                                                                                }));
                                                                                                $jobUnits = trim((string) $job->unit_num);
                                                                                                $normalizedStyle = strtolower(trim((string) $styleLabel));
                                                                                                $jobTag = in_array($normalizedStyle, ['single', 'bridge'], true)
                                                                                                    ? ucfirst($normalizedStyle)
                                                                                                    : (strpos($jobUnits, ',') !== false ? 'Bridge' : 'Single');
                                                                                                $isModelJob = stripos($jobTypeName, 'model') !== false || stripos($materialName, 'model') !== false;
                                                                                            @endphp
                                                                                            <div class="sigma-case-job-row">
                                                                                                @if ($isModelJob)
                                                                                                    <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 7.5C5.1 14 7.8 18 12 18s6.9-4 7.5-10.5"></path><path d="M7.5 7.5c.5 4.1 2 6.4 4.5 6.4s4-2.3 4.5-6.4"></path><path d="M8.4 8v2.2"></path><path d="M12 8v3.2"></path><path d="M15.6 8v2.2"></path></svg>
                                                                                                @else
                                                                                                    <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C9 2 7 4 7 7c0 2 .5 3.5 1 5l1 5c.3 1.2 1 2 2 2h2c1 0 1.7-.8 2-2l1-5c.5-1.5 1-3 1-5 0-3-2-5-5-5z"></path><path d="M9 10c0 0 1 1 3 1s3-1 3-1"></path></svg>
                                                                                                @endif
                                                                                                <span class="sigma-case-job-info">
                                                                                                <span class="sigma-case-job-primary">{{ implode(' - ', $jobTitleParts) }}</span>
                                                                                            </span>
                                                                                                <span class="sigma-case-job-tag">{{ $jobTag }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @if (count($case->notes) > 0)
                                                                            <hr>
                                                                            <label class="case-completion-dialog-label case-notes-label"><b>Notes:</b></label><br>
                                                                            <div class="sigma-case-notes-list">
                                                                                @foreach ($case->notes as $note)
                                                                                    <div class="note-container">
                                                                                        <div class="sigma-case-note-left">
                                                                                            <i class="far fa-comment-alt sigma-case-note-icon" aria-hidden="true"></i>
                                                                                            @unless($loop->last)
                                                                                                <div class="sigma-case-note-line"></div>
                                                                                            @endunless
                                                                                        </div>
                                                                                        <div class="sigma-case-note-content">
                                                                                            <div class="sigma-case-note-meta">
                                                                                                <span class="noteHeader sigma-case-note-author">{{ $note->writtenBy->name_initials }}</span>
                                                                                                <span class="sigma-case-note-separator">&middot;</span>
                                                                                                <span class="sigma-case-note-time">
                                                                                            {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) }}
                                                                                                    {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}
                                                                                        </span>
                                                                                            </div>
                                                                                            <span class="noteText sigma-case-note-text">{{ $note->note }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <div class="row btnsRow">
                                                                        <!-- Row 1: View (25%) | Action (50%) | Edit (25%) -->
                                                                        <div class="col-3 padding5px" style="display: flex;">
                                                                            <a href="{{ route('view-case', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                               style="width:100%; display: flex;">
                                                                                <button type="button" class="btn btn-info"
                                                                                        style="width:100%; display: flex; align-items: center; justify-content: center;">View</button>
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-6 padding5px" style="display: flex;">
                                                                            @if ($key == 'milling')
                                                                                <button type="button" class="btn btn-success"
                                                                                        data-dismiss="modal"
                                                                                        onclick="openModal('milling',true,'{{ $case->id }}')"
                                                                                        style="width:100%; display: flex; align-items: center; justify-content: center;"><i
                                                                                        class="fas fa-user-plus"></i> Assign To
                                                                                    Me</button>
                                                                            @elseif ($key == '3dprinting' || $key == 'sintering' || $key == 'pressing')
                                                                                <button type="button" class="btn btn-success"
                                                                                        data-dismiss="modal"
                                                                                        onclick="openModal('{{ $key }}',true,'{{ $case->id }}')"
                                                                                        style="width:100%; display: flex; align-items: center; justify-content: center;"><i
                                                                                        class="fas fa-user-plus"></i> Assign To
                                                                                    Me</button>
                                                                            @else
                                                                                <button type="submit" class="btn btn-success"
                                                                                        style="width:100%; display: flex; align-items: center; justify-content: center;"><i
                                                                                        class="fas fa-user-plus"></i>
                                                                                    {{ $key == 'delivery' ? 'Take' : 'Assign To Me' }}</button>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-3 padding5px" style="display: flex;">
                                                                            <a href="{{ route('edit-case-view', $case->id) }}"
                                                                               style="width:100%; display: flex;">
                                                                                <button type="button"
                                                                                        class="btn btn-warning {{ $canEditCase ? '' : 'disabled' }}"
                                                                                        style="width:100%; display: flex; align-items: center; justify-content: center;">Edit</button>
                                                                            </a>
                                                                        </div>

                                                                        <!-- Row 2: QC Complete (100%) OR Delivery Assign (100%) -->
                                                                        @if ($key == 'qc')
                                                                            <div class="col-12 padding5px">
                                                                                <a href="{{ route('assign-and-finish', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                                   class="btn btn-info" style="width:100%;color:white"><i
                                                                                        class="fa-solid fa-arrow-trend-up"></i>
                                                                                    Assign & Complete</a>
                                                                            </div>
                                                                        @endif

                                                                        @if ($key == 'delivery')
                                                                            @if (Auth()->user()->is_admin || ($permissions && $permissions->contains('permission_id', 129)))
                                                                                @if ($case->jobs[0]->assignee == null)
                                                                                    <div class="col-12 padding5px">
                                                                                        <button type="button"
                                                                                                class="btn btn-warning"
                                                                                                onclick="closeModal({id:'waitingDialog{{ $key . $case->id }}'}); openModal('DeliveryDialog',false)"
                                                                                                style="width:100%">Assign
                                                                                            to..</button>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="col-12 padding5px">
                                                                                        <button type="button"
                                                                                                class="btn btn-warning"
                                                                                                onclick="closeModal({id:'waitingDialog{{ $key . $case->id }}'}); openModal('DeliveryDialog', false)"
                                                                                                style="width:100%">Re-Assign..</button>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                        <!-- Row 3: Delivery Print Voucher (100%) -->
                                                                        @if ($key == 'delivery')
                                                                            <div class="col-12 padding5px">
                                                                                <a href="{{ route('view-voucher', $case->id) }}"
                                                                                   class="btn btn-info" style="width:100%; color:white"><i
                                                                                        class="fas fa-print"></i> Print
                                                                                    Voucher</a>
                                                                            </div>
                                                                        @endif

                                                                        <!-- Row 4: Cancel (100%) -->
                                                                        <div class="col-12 padding5px">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                    data-dismiss="modal"
                                                                                    style="width:100%">Cancel</button>
                                                                        </div>
                                                                    </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (count($stage['waitingCases']) === 0)
                                        <div class="ops-empty-state d-md-none">No cases</div>
                                    @else
                                        <div class="ops-case-list ops-case-list--waiting d-md-none">
                                            @foreach ($stage['waitingCases'] as $case)
                                                @php
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
                                                <div class="ops-case-card ops-case-card--waiting {{ ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery') && ($key != 'delivery' || $isDeliveryAndAssignable) ? 'ops-case-card--selectable' : '' }}">
                                                    @if ($key != 'delivery' || $isDeliveryAndAssignable)
                                                        @if ($key == 'milling' || $key == '3dprinting' || $key == 'sintering' || $key == 'pressing' || $key == 'delivery')
                                                            <div class="ops-case-card__select">
                                                                <input type="checkbox" data-type="{{ $key }}"
                                                                       data-group-id="{{ $key }}"
                                                                       class="custom-control-input multipleCB {{ $key }} checkboxes-group-{{ $key }}"
                                                                       value="{{ $case->id }}"
                                                                       name="CheckBoxes{{ $key }}[]"
                                                                       onchange="multiCBChanged('{{ $key }}',this, '{{ $case->id }}')"
                                                                       onclick="event.stopPropagation()">
                                                            </div>
                                                        @endif
                                                    @endif
                                                    <div class="ops-case-card__content clickable" data-toggle="modal"
                                                         data-target="#waitingDialog{{ $key . $case->id }}">
                                                        <div class="ops-case-card__row ops-case-card__row--primary">
                                                            <div class="ops-case-card__doctor" dir="auto">{{ $case->client?->name ?? 'Err404-1' }}</div>
                                                            <div class="ops-case-card__patient" dir="auto">{{ $case->patient_name }}</div>
                                                            <div class="ops-case-card__units">
                                                                <span class="ops-case-card__units-pill">{{ $case->unitsAmount($stage['numericStage']) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="ops-case-card__row ops-case-card__row--secondary">
                                                            <div class="ops-case-card__badges">
                                                                @if ($key == 'finishing' && $notReadyA)
                                                                    <span class="ops-case-card__badge">Not Ready</span>
                                                                @endif
                                                                @if ($key == 'finishing' && !$abutmentsReceived)
                                                                    <span class="ops-case-card__badge">Abutment Missing</span>
                                                                @endif
                                                            </div>
                                                            <div class="ops-case-card__date">
                                                                {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}
                                                            </div>
                                                            <div class="ops-case-card__tags">
                                                                @foreach ($case->tags as $tag)
                                                                    <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                                       style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                                       class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }} fa-lg"></i>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            {{-- ----------------ACTIVE TABLE--------------- --}}
                            <div tabindex="0" role="tabpanel" aria-labelledby="{{ 'active-' . $key . 'label' }}"
                                 id="{{ 'active-' . $key }}" hidden class="stage-panel-pane">
                                @php
                                    $key = strtolower($key);

                                    $millingActiveDialogBuilt = false;
                                    $sinteringActiveDialogBuilt = false;
                                    $printingActiveDialogBuilt = false;
                                    $pressingActiveDialogBuilt = false;

                                @endphp
                                @if ($key == 'milling')
                                    <div class="stage-panel-scroll stage-panel-scroll--devices">
                                        <x-devices-block title="Milling" btnText="Start" type="milling" units="0"
                                                         :devices="$devices" stageId="2" :counts="$deviceUnitsCounts" />
                                    </div>
                                @elseif($key == '3dprinting')
                                    <div class="stage-panel-scroll stage-panel-scroll--devices">
                                        <x-devices-block title="3D Printing" btnText="Start" type="3dprinting"
                                                         units="3" :devices="$devices" stageId="3" :counts="$deviceUnitsCounts" />
                                    </div>
                                @elseif($key == 'sintering')
                                    <div class="stage-panel-scroll stage-panel-scroll--devices">
                                        <x-devices-block title="Sintering" btnText="Start" type="sintering" units="4"
                                                         :devices="$devices" stageId="4" :counts="$deviceUnitsCounts" />
                                    </div>
                                @elseif($key == 'pressing')
                                    <div class="stage-panel-scroll stage-panel-scroll--devices">
                                        <x-devices-block title="Pressing" btnText="Start" type="pressing" units="5"
                                                         :devices="$devices" stageId="5" :counts="$deviceUnitsCounts" />
                                    </div>
                                @else
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <!-- ACTIVE DELIVERY TABLES -->
                                    <div class="stage-panel-scroll">
                                        <div class="d-none d-md-block">
                                            <table class="activeTable sunriseTable no-auto-colresize" style="width:100%;">
                                                <thead>
                                                <tr>
                                                    <th class="ops-col ops-col--doctor">Doctor</th>
                                                    <th class="ops-col ops-col--patient">Patient</th>
                                                    <th class="deliveryToHeader ops-col ops-col--delivery">Delivery Date</th>
                                                    <th class="assignedToHeader ops-col ops-col--assigned">Assigned To</th>
                                                    <th class="ops-col ops-col--count">#</th>
                                                    <th class="ops-col ops-col--tags">Tags</th>
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
                                                        <td class="ops-col ops-col--doctor">
                                                            <p class="">
                                                                {{ $case->client ? $case->client->name : 'No Client' }}</p>
                                                        </td>
                                                        <td class="ops-col ops-col--patient" dir="auto">
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
                                                        <td class="ops-col ops-col--delivery">
                                                            <p class="">
                                                                {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}
                                                            </p>
                                                        </td>
                                                        <td class="ops-col ops-col--assigned">
                                                            <p class="">
                                                                {{ $case->jobs->where('stage', $stage['numericStage'])->first() ? ($case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo ? $case->jobs->where('stage', $stage['numericStage'])->first()->assignedTo->name_initials : 'None') : 'None' }}
                                                            </p>
                                                        </td>
                                                        <td class="ops-col ops-col--count">
                                                            <p class="">{{ $case->unitsAmount($stage['numericStage']) }}
                                                            </p>
                                                        </td>
                                                        <td class="ops-col ops-col--tags">
                                                            <div>
                                                                @foreach ($case->tags as $tag)
                                                                    <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                                       style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                                       class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }}  fa-lg"></i>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Active case actions Dialog -->
                                                    <div class="modal fade sigma-modal--cases-dashboard-case-completion-alt{{ $key == 'delivery' ? ' sigma-modal--case-completion-delivery' : '' }}{{ $key == 'qc' ? ' sigma-modal--case-completion-qc' : '' }}" tabindex="-1" role="dialog"
                                                         id="confirmCompletion{{ $key . $case->id }}">
                                                        <div class="modal-dialog modal-dialog-centered fade-in-down-local" role="document">
                                                            <div class="modal-content">
                                                                <form
                                                                    action="{{ $key == 'delivery' ? route('finish-case', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) : route('finish-case', ['caseId' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                    method="GET">
                                                                    @csrf
                                                                    <input type="hidden" name="case_id"
                                                                           value="{{ $case->id }}">
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
                                                                                <label for="doctor" class="patient-doctor-label">Doctor: </label>
                                                                                <h5 id="doctor"
                                                                                    class="patient-doctor-names">
                                                                                    {{ $case->client?->name }}</h5>
                                                                            </div>
                                                                            <div class="form-group col-6 "
                                                                                 style="margin-bottom: 0px">
                                                                                <label for="pat" class="patient-doctor-label">Patient name: </label>
                                                                                <h5 id="pat"
                                                                                    class="patient-doctor-names">
                                                                                    {{ $case->patient_name }}</h5>
                                                                            </div>
                                                                        </div>
                                                                        <hr>

                                                                        <!-- Scrollable Jobs and Notes section -->
                                                                        <div class="scrollable-content">
                                                                            <div class="form-group row">
                                                                                <div class=" col-12 ">
                                                                                    <label class="case-completion-dialog-label case-jobs-label"><b>Jobs:</b></label>
                                                                                    <div class="sigma-case-jobs-list">
                                                                                        @foreach ($case->jobs->where('stage', $stage['numericStage']) as $job)
                                                                                            @php
                                                                                                $unit = explode(
                                                                                                    ', ',
                                                                                                    $job->unit_num,
                                                                                                );
                                                                                                // Check if this job goes through the current stage based on material
                                                                                                $showJob = $job->goesThroughStage(
                                                                                                    $stage['numericStage'],
                                                                                                );
                                                                                            @endphp

                                                                                            @if ($showJob)
                                                                                                @php
                                                                                                    $jobTypeName = $job->jobType->name ?? 'No Job Type';
                                                                                                    $materialName = $job->material->name ?? 'no material';
                                                                                                    $colorLabel = $job->color == '0' ? '' : $job->color;
                                                                                                    $styleLabel = $job->style == 'None' ? '' : $job->style;
                                                                                                    $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? 'Implant Type: ' . $job->implantR->name : '';
                                                                                                    $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? 'Abutment Type: ' . $job->abutmentR->name : '';
                                                                                                    $stageLabel = $stage['name'] ?? $key ?? 'Stage';
                                                                                                    $jobTitleParts = array_values(array_filter([
                                                                                                        trim((string) $job->unit_num),
                                                                                                        trim((string) $jobTypeName),
                                                                                                        trim((string) $materialName),
                                                                                                        trim((string) $colorLabel),
                                                                                                        trim((string) $implantLabel),
                                                                                                        trim((string) $abutmentLabel),
                                                                                                    ], function ($value) {
                                                                                                        return $value !== '';
                                                                                                    }));
                                                                                                    $jobUnits = trim((string) $job->unit_num);
                                                                                                    $normalizedStyle = strtolower(trim((string) $styleLabel));
                                                                                                    $jobTag = in_array($normalizedStyle, ['single', 'bridge'], true)
                                                                                                        ? ucfirst($normalizedStyle)
                                                                                                        : (strpos($jobUnits, ',') !== false ? 'Bridge' : 'Single');
                                                                                                    $isModelJob = stripos($jobTypeName, 'model') !== false || stripos($materialName, 'model') !== false;
                                                                                                @endphp
                                                                                                <div class="sigma-case-job-row">
                                                                                                    @if ($isModelJob)
                                                                                                        <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4.5 7.5C5.1 14 7.8 18 12 18s6.9-4 7.5-10.5"></path><path d="M7.5 7.5c.5 4.1 2 6.4 4.5 6.4s4-2.3 4.5-6.4"></path><path d="M8.4 8v2.2"></path><path d="M12 8v3.2"></path><path d="M15.6 8v2.2"></path></svg>
                                                                                                    @else
                                                                                                        <svg class="sigma-case-job-icon" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#1D9E75" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2C9 2 7 4 7 7c0 2 .5 3.5 1 5l1 5c.3 1.2 1 2 2 2h2c1 0 1.7-.8 2-2l1-5c.5-1.5 1-3 1-5 0-3-2-5-5-5z"></path><path d="M9 10c0 0 1 1 3 1s3-1 3-1"></path></svg>
                                                                                                    @endif
                                                                                                    <span class="sigma-case-job-info">
                                                                                                    <span class="sigma-case-job-primary">{{ implode(' - ', $jobTitleParts) }}</span>
                                                                                                </span>
                                                                                                    <span class="sigma-case-job-tag">{{ $jobTag }}</span>
                                                                                                </div>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if (count($case->notes) > 0)
                                                                                <hr>
                                                                                <label class="case-completion-dialog-label case-notes-label"><b>Notes:</b></label><br>
                                                                                <div class="sigma-case-notes-list">
                                                                                    @foreach ($case->notes as $note)
                                                                                        <div class="note-container">
                                                                                            <div class="sigma-case-note-left">
                                                                                                <i class="far fa-comment-alt sigma-case-note-icon" aria-hidden="true"></i>
                                                                                                @unless($loop->last)
                                                                                                    <div class="sigma-case-note-line"></div>
                                                                                                @endunless
                                                                                            </div>
                                                                                            <div class="sigma-case-note-content">
                                                                                                <div class="sigma-case-note-meta">
                                                                                                    <span class="noteHeader sigma-case-note-author">{{ $note->writtenBy->name_initials }}</span>
                                                                                                    <span class="sigma-case-note-separator">&middot;</span>
                                                                                                    <span class="sigma-case-note-time">
                                                                                                {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) }}
                                                                                                        {{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}
                                                                                            </span>
                                                                                                </div>
                                                                                                <span class="noteText sigma-case-note-text">{{ $note->note }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>

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
                                                                                if (
                                                                                    $case->jobs
                                                                                        ->where(
                                                                                            'stage',
                                                                                            $stage['numericStage'],
                                                                                        )
                                                                                        ->first() &&
                                                                                    $case->jobs
                                                                                        ->where(
                                                                                            'stage',
                                                                                            $stage['numericStage'],
                                                                                        )
                                                                                        ->first()->assignee ==
                                                                                        Auth()->user()->id
                                                                                ) {
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
                                                                            <div class="col-3 padding5px"
                                                                                 style="display: flex;">
                                                                                <a href="{{ route('view-case', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                                   style="width:100%; display: flex;">
                                                                                    <button type="button"
                                                                                            class="btn btn-info"
                                                                                            style="width:100%; display: flex; align-items: center; justify-content: center;">View</button>
                                                                                </a>
                                                                            </div>

                                                                            <div class="col-6 padding5px"
                                                                                 style="display: flex;">
                                                                                @if ($isAdmin && $canBeFinished && !$isUserCase)
                                                                                    <a href="{{ route('complete-by-admin', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                                       style="width:100%; display: flex;">
                                                                                        <button type="button"
                                                                                                class="btn btn-success"
                                                                                                style="width:100%; display: flex; align-items: center; justify-content: center;">override
                                                                                            Complete</button>
                                                                                    </a>
                                                                                @else
                                                                                    <button type="submit"
                                                                                            class="btn btn-success"
                                                                                            style="width:100%; display: flex; align-items: center; justify-content: center;"
                                                                                        {{ $canComplete ? '' : 'disabled' }}>{{ $canComplete ? 'Complete' : 'Case cannot be completed' }}</button>
                                                                                @endif
                                                                            </div>

                                                                            <div class="col-3 padding5px"
                                                                                 style="display: flex;">
                                                                                <a href="{{ route('edit-case-view', $case->id) }}"
                                                                                   style="width:100%; display: flex;">
                                                                                    <button type="button"
                                                                                            class="btn btn-warning {{ $canEditCase ? '' : 'disabled' }}"
                                                                                            style="width:100%; display: flex; align-items: center; justify-content: center;">Edit</button>
                                                                                </a>
                                                                            </div>
                                                                            <!-- Row 1: Delivery status (100%) - Layout 3 only -->
                                                                            @if ($key == 'delivery')
                                                                                <div class="col-12 padding5px">
                                                                                    <a href="{{ route('delivered-in-box', $case->id) }}"
                                                                                       class="btn btn-outline-info"
                                                                                       style="width:100%">Delivered In Box</a>
                                                                                </div>
                                                                            @endif

                                                                            <!-- Row 3: Print Voucher (100%) - Layout 3 only -->
                                                                            @if ($key == 'delivery')
                                                                                <div class="col-12">
                                                                                    <a href="{{ route('view-voucher', $case->id) }}"
                                                                                       class="btn btn-outline-info"
                                                                                       style="width:100%">Print Voucher</a>
                                                                                </div>
                                                                            @endif

                                                                            <!-- Row 4: Externally Milled (100%) - Layout 5 only -->
                                                                            @if ($key == 'milling')
                                                                                <div class="col-12 padding5px">
                                                                                    <button type="button"
                                                                                            class="btn btn-dark"
                                                                                            data-toggle="modal"
                                                                                            data-target="#MEX{{ $case->id }}"
                                                                                            data-dismiss="modal"
                                                                                            style="width:100%">Externally
                                                                                        Milled</button>
                                                                                </div>
                                                                            @endif

                                                                            <!-- Row 5: Reset To Waiting (100%) -->
                                                                            <div class="col-12 padding5px ">
                                                                                <a href="{{ route('reset-to-waiting', ['id' => $case->id, 'stage' => $stage['numericStage']]) }}"
                                                                                   class="btn btn-outline-danger"
                                                                                   style="width:100%">Reset To Waiting</a>
                                                                            </div>

                                                                            <!-- Row 6: Cancel (100%) -->
                                                                            <div class="col-12 padding5px ">
                                                                                <button type="button"
                                                                                        class="btn btn-secondary "
                                                                                        data-dismiss="modal"
                                                                                        style="width:100%">Cancel</button>
                                                                            </div>
                                                                        </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    {{--                                            /////////// v2 DIALOG --}}
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if (count($stage['activeCases']) === 0)
                                            <div class="ops-empty-state d-md-none">No cases</div>
                                        @else
                                            <div class="ops-case-list ops-case-list--active d-md-none">
                                                @foreach ($stage['activeCases'] as $case)
                                                    @if ($key == 'finishing')
                                                        @php
                                                            $notReadyA = false;
                                                            $abutmentsReceived = $case->abutmentsReceived();
                                                            if (!$case->allUnitsAtFinishing()) {
                                                                $notReadyA = true;
                                                            }
                                                        @endphp
                                                    @endif
                                                    <div class="ops-case-card ops-case-card--active clickable"
                                                         data-toggle="modal"
                                                         data-target="#confirmCompletion{{ $key . $case->id }}">
                                                        <div class="ops-case-card__content">
                                                            <div class="ops-case-card__row ops-case-card__row--primary">
                                                                <div class="ops-case-card__doctor" dir="auto">
                                                                    {{ $case->client ? $case->client->name : 'No Client' }}
                                                                </div>
                                                                <div class="ops-case-card__patient" dir="auto">
                                                                    {{ $case->patient_name }}
                                                                </div>
                                                                <div class="ops-case-card__units">
                                                                    <span class="ops-case-card__units-pill">{{ $case->unitsAmount($stage['numericStage']) }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="ops-case-card__row ops-case-card__row--secondary">
                                                                <div class="ops-case-card__badges">
                                                                    @if ($key == 'finishing' && $notReadyA)
                                                                        <span class="ops-case-card__badge">Not Ready</span>
                                                                    @endif
                                                                    @if ($key == 'finishing' && !$abutmentsReceived)
                                                                        <span class="ops-case-card__badge">Abutment Missing</span>
                                                                    @endif
                                                                </div>
                                                                <div class="ops-case-card__date">
                                                                    {{ date_format(date_create($case->initDeliveryDate()), 'd-M') }}
                                                                </div>
                                                                <div class="ops-case-card__tags">
                                                                    @foreach ($case->tags as $tag)
                                                                        <i title="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->text : '-' }}"
                                                                           style="color:{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->color : '' }}"
                                                                           class="{{ $tag->originalTagRecord != null ? $tag->originalTagRecord->icon : '' }} fa-lg"></i>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach



                @foreach ($devices as $device)
                    @switch($device['type'])
                        @case(2)
                            <x-active-cases-dialog title="Milling Jobs" btnText="COMPLETE" type="milling" :deviceId="$device['id']"
                                                   :isBuilds="false" />
                            @break

                        @case(3)
                            <x-active-cases-dialog title="Printer Builds" btnText="COMPLETE" type="3dprinting" :deviceId="$device['id']"
                                                   :isBuilds="true" />
                            @break

                        @case(4)
                            <x-active-cases-dialog title="Sintering Jobs" btnText="COMPLETE" type="sintering" :deviceId="$device['id']"
                                                   :isBuilds="false" />
                            @break

                        @case(5)
                            <x-active-cases-dialog title="Pressing Jobs" btnText="COMPLETE" type="pressing" :deviceId="$device['id']"
                                                   :isBuilds="false" />
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
        <div id="loadingDialog" class="modal sigma-modal--cases-dashboard-loading" tabindex="-1" role="dialog"
             style="display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); z-index: 9999;">
            <div class="modal-dialog modal-dialog-centered " role="document">
                <div class="modal-content">
                    <div class="modal-body text-center p-4">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="mb-0 mt-2 sigma-processing-shimmer">Processing your request...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    @php --}}
    {{--        } catch (Exception $e) { --}}
    {{--            dd($e->getMessage(), $e->getTraceAsString()); --}}
    {{--        } --}}
    {{--    @endphp --}}

    <!-- Column Width Config Panel -->
    <div class="config-panel" id="columnConfigPanel" hidden aria-hidden="true">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h4 style="margin: 0; color: #1e293b; font-weight: 700;">Table Column Widths</h4>
                <p style="font-size: 12px; color: #64748b; margin: 4px 0 0 0;">
                    Adjust widths in pixels. Press <span class="reset-hint">F3</span> to reset all.
                </p>
            </div>
            <div style="display: flex; gap: 8px;">
                <button id="resetToAutoBtn" class="btn btn-sm btn-warning">Reset to Auto</button>
                <button type="button" id="columnConfigCloseBtn" class="btn btn-sm btn-outline-secondary">Close</button>
            </div>
        </div>
        <div id="columnWidthInputs"></div>
    </div>
@endsection


@push('js')
    <!-- Custom DataTables CSS fixes -->
    <style>
        /* Completely hide DataTables sorting arrows */
        .sunriseTable.dataTable thead th.sorting:before, .sunriseTable.dataTable thead th.sorting:after, .sunriseTable.dataTable thead th.sorting_asc:before, .sunriseTable.dataTable thead th.sorting_asc:after, .sunriseTable.dataTable thead th.sorting_desc:before, .sunriseTable.dataTable thead th.sorting_desc:after, .sunriseTable.dataTable thead .sorting:before, .sunriseTable.dataTable thead .sorting:after, .sunriseTable.dataTable thead .sorting_asc:before, .sunriseTable.dataTable thead .sorting_asc:after, .sunriseTable.dataTable thead .sorting_desc:before, .sunriseTable.dataTable thead .sorting_desc:after {
            display: none !important;
            content: none !important;
            background-image: none !important;
        }

        /* Force first column to not be sortable and fix alignment */
        .sunriseTable.dataTable thead th:first-child, .sunriseTable.dataTable thead th:first-child.sorting, .sunriseTable thead th:first-child {
            min-width: 25px !important;

            text-align: left !important;

            background-image: none !important;
            cursor: default !important;
            /*position: relative !important;*/
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

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
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
        .sunriseTable th:nth-child(8), .sunriseTable td:nth-child(8) {
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



        .waitingTable>thead {
            height: 4.9vh;
        }

        /* Dashboard table styling with Cairo font */
        .waitingTable, .activeTable {
            font-family: 'Cairo', sans-serif !important;
        }

        .waitingTable th, .waitingTable td,
        .activeTable th, .activeTable td {
            font-family: 'Cairo', sans-serif !important;
        }

        .ops-dashboard .waitingTable th.ops-col--doctor,
        .ops-dashboard .waitingTable td.ops-col--doctor,
        .ops-dashboard .activeTable th.ops-col--doctor,
        .ops-dashboard .activeTable td.ops-col--doctor,
        .ops-dashboard .sunriseTable th:first-child:not(.ops-col--select),
        .ops-dashboard .sunriseTable td:first-child:not(.ops-col--select) {
            padding-left: 0.9rem !important;
        }

        /* Prevent content wrapping in cells */
        .waitingTable tbody td p,
        .activeTable tbody td p {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin: 0 !important;
        }

        /* Fix tags column to prevent vertical stacking */
        .ops-col--tags > div {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 4px !important;
            overflow: hidden !important;
            align-items: center !important;
            line-height: normal !important;
        }

        .ops-col--tags i {
            line-height: 1 !important;
            display: inline-block !important;
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

        .sunriseTable tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .sunriseTable tbody tr:nth-child(even) {
            background-color: #f7f9fb;
        }

        .sunriseTable tbody tr:hover {
            background-color: #eef3ff !important;
        }

        @media (min-width: 992px){
            .stageSidebar {
                margin-top: 71px;
            }
        }

        /* Mobile: when columns hide, stretch remaining columns to fill width */
        @media (max-width: 768px){
            .sunriseTable.dataTable {
                table-layout: fixed !important;
            }

            .sunriseTable.dataTable th, .sunriseTable.dataTable td {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        .ops-mobile-badges {
            display: none;
        }

        .ops-mobile-inline-status {
            display: inline-block;
        }

        @media (max-width: 768px){
            .ops-dashboard .waitingTable.sunriseTable,
            .ops-dashboard .activeTable.sunriseTable {
                width: 100% !important;
                border: none !important;
                background: transparent !important;
                box-shadow: none !important;
                border-spacing: 0 !important;
            }

            .ops-dashboard .waitingTable thead,
            .ops-dashboard .activeTable thead {
                display: none;
            }

            .ops-dashboard .waitingTable tbody,
            .ops-dashboard .activeTable tbody {
                display: block;
            }

            .ops-dashboard .waitingTable tbody tr,
            .ops-dashboard .activeTable tbody tr {
                position: relative;
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) auto;
                grid-template-areas:
                    "doctor patient units"
                    "badges delivery tags";
                align-items: center;
                gap: 0.36rem 0.55rem;
                margin: 0 0 4px;
                padding: 0.62rem 0.8rem;
                border: 1px solid #e7eef2 !important;
                border-radius: 10px;
                background: #ffffff !important;
                box-shadow: 0 1px 8px rgba(18, 47, 70, 0.04);
                overflow: hidden;
            }

            .ops-dashboard .waitingTable tbody tr.ops-case-row--selectable {
                padding-left: 2.15rem;
            }

            .ops-dashboard .waitingTable tbody td,
            .ops-dashboard .activeTable tbody td {
                width: auto !important;
                min-width: 0 !important;
                max-width: none !important;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                white-space: normal !important;
                overflow: visible !important;
            }

            .ops-dashboard .waitingTable tbody td > p,
            .ops-dashboard .activeTable tbody td > p {
                margin: 0 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .ops-dashboard .ops-case-row td.ops-col--doctor {
                grid-area: doctor;
            }

            .ops-dashboard .ops-case-row td.ops-col--patient {
                grid-area: patient;
                justify-self: center;
                width: 100%;
                text-align: center;
            }

            .ops-dashboard .ops-case-row td.ops-col--delivery {
                grid-area: delivery;
                justify-self: center;
                text-align: center;
            }

            .ops-dashboard .ops-case-row td.ops-col--count {
                grid-area: units;
                display: flex !important;
                align-items: center;
                justify-self: end;
            }

            .ops-dashboard .ops-case-row td.ops-col--tags {
                display: contents;
            }

            .ops-dashboard .ops-case-row td.ops-col--assigned,
            .ops-dashboard .ops-case-row th.ops-col--assigned {
                display: none !important;
            }

            .ops-dashboard .ops-case-row td.ops-col--select {
                position: absolute;
                top: 0.78rem;
                left: 0.7rem;
                z-index: 3;
                display: flex;
                align-items: center;
                justify-content: center;
                width: auto !important;
            }

            .ops-dashboard .ops-case-row td.ops-col--select input {
                position: static !important;
                width: 1rem;
                height: 1rem;
                margin: 0;
            }

            .ops-dashboard .ops-mobile-doctor {
                font-weight: 700 !important;
                font-size: 0.98rem;
                color: #111827;
                text-align: left;
            }

            .ops-dashboard .ops-mobile-patient {
                font-weight: 500 !important;
                font-size: 0.92rem;
                color: #2f3947;
                text-align: center;
            }

            .ops-dashboard .ops-mobile-delivery-date {
                font-size: 0.92rem;
                color: #2f3947;
                text-align: center;
            }

            .ops-dashboard .ops-mobile-inline-status {
                display: none !important;
            }

            .ops-dashboard .ops-mobile-badges {
                display: flex;
                grid-area: badges;
                align-items: center;
                justify-self: start;
                gap: 0.28rem;
                flex-wrap: wrap;
                min-width: 0;
            }

            .ops-dashboard .ops-mobile-badges:empty {
                display: none;
            }

            .ops-dashboard .ops-mobile-status {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-height: 1.35rem;
                padding: 0.08rem 0.42rem;
                border: 1px solid rgba(255, 164, 0, 0.45);
                border-radius: 999px;
                background: rgba(255, 164, 0, 0.08);
                color: #ef9700;
                font-size: 0.64rem;
                font-weight: 700;
                line-height: 1.1;
                white-space: nowrap;
            }

            .ops-dashboard .ops-units-pill {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 1.95rem;
                height: 1.65rem;
                padding: 0 0.55rem !important;
                border-radius: 10px;
                background: #f1f4f6;
                color: #1e2934;
                font-weight: 700;
                font-size: 0.82rem;
                font-variant-numeric: tabular-nums;
                box-shadow: inset 0 0 0 1px rgba(44, 62, 80, 0.05);
            }

            .ops-dashboard .ops-tags-wrap {
                grid-area: tags;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: flex-end;
                justify-self: end;
                gap: 0.38rem !important;
                min-width: 0;
                width: auto;
            }

            .ops-dashboard .ops-tags-wrap i {
                margin-right: 0 !important;
                font-size: 1rem;
                line-height: 1;
            }

            .ops-dashboard .ops-tags-wrap i:nth-of-type(n + 3) {
                display: none;
            }
        }

        .ops-case-list {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            font-family: 'Cairo', sans-serif !important;
        }

        .ops-empty-state {
            margin: 1.1rem 0.65rem 1.4rem;
            padding: 1rem 1.1rem;
            border-radius: 16px;
            text-align: center;
            color: rgba(35, 68, 83, 0.78);
            font-weight: 700;
            letter-spacing: 0.01em;
            background:
                radial-gradient(circle closest-side at 20% 30%, rgba(53, 183, 194, 0.12), transparent 76%),
                linear-gradient(180deg, rgba(244, 250, 251, 0.98), rgba(255, 255, 255, 0.96));
            border: 1px dashed rgba(30, 112, 122, 0.25);
        }

        .ops-case-list__bulk {
            display: flex;
            justify-content: flex-start;
            margin: 0.08rem 0 0.92rem;
            padding-left: 0.08rem;
        }

        .ops-case-list__bulk-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.58rem 0.88rem;
            border: 1px solid transparent;
            border-radius: 16px;
            background:
                linear-gradient(180deg, rgba(236, 245, 248, 0.96), rgba(249, 252, 253, 0.98)) padding-box,
                linear-gradient(135deg, rgba(76, 133, 145, 0.28), rgba(255, 255, 255, 0.94) 42%, rgba(76, 133, 145, 0.12) 100%) border-box;
            color: #234453;
            font-size: 0.9rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.01em;
        }

        .ops-case-list__bulk-toggle input {
            width: 1rem;
            height: 1rem;
            margin: 0;
        }

        .ops-case-card {
            --ops-card-side-width: 38%;
            --ops-card-action-width: 4.35rem;
            --ops-card-accent: #1c8a91;
            --ops-card-accent-bright: #35b7c2;
            --ops-card-accent-deep: #0f6670;
            position: relative;
            margin-bottom: 0.5rem;
            border: 1px solid rgba(30, 112, 122, 0.26);
            border-radius: 17px;
            background:
                radial-gradient(circle closest-side at 17% 27%, rgba(53, 183, 194, 0.22), transparent 82%),
                radial-gradient(circle closest-side at 92% 35%, rgba(28, 138, 145, 0.16), transparent 78%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(232, 247, 249, 0.94));
            box-shadow:
                0 13px 28px rgba(15, 64, 76, 0.12),
                0 4px 11px rgba(15, 64, 76, 0.07),
                inset 0 1px 0 rgba(255, 255, 255, 0.92);
            overflow: hidden;
            isolation: isolate;
        }

        .ops-case-card::before {
            content: "";
            position: absolute;
            inset: 0.0625rem;
            border-radius: 16px;
            pointer-events: none;
            background:
                linear-gradient(90deg, transparent calc(var(--ops-card-side-width) - 1px), rgba(30, 112, 122, 0.16) var(--ops-card-side-width), transparent calc(var(--ops-card-side-width) + 1px)),
                linear-gradient(270deg, rgba(232, 240, 244, 0.86) 0 var(--ops-card-action-width), transparent var(--ops-card-action-width)),
                linear-gradient(90deg, rgba(226, 246, 248, 0.68) 0 var(--ops-card-side-width), rgba(255, 255, 255, 0.9) var(--ops-card-side-width)),
                linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(247, 252, 253, 0.94));
            z-index: 0;
        }

        .ops-case-card::after {
            content: "";
            position: absolute;
            width: 0.24rem;
            inset: 0.64rem auto 0.64rem 0.32rem;
            border-radius: 999px;
            transform: translateX(0.12rem);
            pointer-events: none;
            background: linear-gradient(to bottom, var(--ops-card-accent-bright), var(--ops-card-accent), var(--ops-card-accent-deep));
            box-shadow:
                0 0 16px rgba(53, 183, 194, 0.55),
                0 0 28px rgba(28, 138, 145, 0.26);
            z-index: 2;
        }

        .ops-case-card.clickable,
        .ops-case-card__content.clickable {
            cursor: pointer;
        }

        .ops-case-card__content {
            position: relative;
            z-index: 3;
            display: block;
            padding: 0.78rem 0.88rem;
        }

        .ops-case-card--selectable .ops-case-card__content {
            padding-left: 2.2rem;
        }

        .ops-case-card__select {
            position: absolute;
            /* Center against the primary row text height (feels more aligned than the full units pill). */
            top: calc(0.78rem + 0.68rem);
            transform: translateY(-50%);
            left: 0.72rem;
            z-index: 4;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ops-case-card__select input[type="checkbox"] {
            appearance: auto;
            -webkit-appearance: checkbox;
            opacity: 1 !important;
            position: static !important;
            width: 1rem;
            height: 1rem;
            margin: 0;
        }

        .ops-case-card__row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) 3.35rem;
            align-items: center;
            gap: 0.36rem 0.6rem;
        }

        .ops-case-card__row--secondary {
            margin-top: 0.32rem;
        }

        .ops-case-card__doctor,
        .ops-case-card__patient,
        .ops-case-card__date {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
            display: block;
            width: 100%;
        }

        .ops-case-card__doctor {
            font-size: 1rem;
            font-weight: 800;
            color: #0b2d37;
            text-align: left;
            line-height: 1.35;
        }

        .ops-case-card__patient {
            font-size: 1.04rem;
            font-weight: 700;
            color: #284457;
            text-align: center;
            justify-self: center;
            line-height: 1.35;
        }

        .ops-case-card__units {
            display: flex;
            justify-content: flex-end;
            justify-self: end;
            width: 3.35rem;
        }

        .ops-case-card__units-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.95rem;
            height: 1.7rem;
            padding: 0 0.58rem;
            border: 1px solid rgba(28, 102, 112, 0.1);
            border-radius: 11px;
            background:
                radial-gradient(circle at 50% 0%, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0) 54%),
                linear-gradient(180deg, #edf6f8 0%, #dce9ee 100%);
            color: #112f3b;
            font-size: 0.82rem;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.98),
                0 3px 8px rgba(31, 76, 88, 0.13);
        }

        .ops-case-card__badges {
            display: flex;
            align-items: center;
            gap: 0.28rem;
            flex-wrap: wrap;
            min-width: 0;
            align-self: start;
        }

        .ops-case-card__badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 1.35rem;
            padding: 0.08rem 0.42rem;
            border: 1px solid rgba(255, 164, 0, 0.45);
            border-radius: 999px;
            background: rgba(255, 164, 0, 0.08);
            color: #ef9700;
            font-size: 0.64rem;
            font-weight: 700;
            line-height: 1.1;
            white-space: nowrap;
        }

        .ops-case-card__date {
            font-size: 0.9rem;
            font-weight: 600;
            color: #506a7b;
            text-align: center;
            justify-self: center;
            line-height: 1.3;
        }

        .ops-case-card__tags {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            justify-self: end;
            gap: 0.38rem;
            min-width: 0;
            width: 3.35rem;
        }

        .ops-case-card__tags i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 0.95rem;
            margin-right: 0 !important;
            font-size: 1rem;
            line-height: 1;
            text-align: center;
        }

        .ops-case-card__tags i:nth-of-type(n + 4) {
            display: none;
        }

        .ops-case-card--waiting {
            --ops-card-accent: #24868e;
            --ops-card-rim: #34496d;
            --ops-card-ink: #1f2d3d;
            --ops-card-muted: #566779;
            --ops-card-waiting: #d99a31;
            margin-bottom: 0.82rem;
            border: 1px solid rgba(41, 57, 79, 0.14);
            border-radius: 20px;
            background: linear-gradient(105deg, #414854, #adf4f5 72%, rgba(36, 134, 142, 0.92));
            box-shadow:
                0 15px 25px rgba(25, 36, 55, 0.18),
                0 4px 9px rgba(25, 36, 55, 0.1);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .ops-case-card--waiting:hover {
            transform: translateY(-3px);
            border-color: rgba(36, 134, 142, 0.32);
            box-shadow:
                0 19px 30px rgba(25, 36, 55, 0.22),
                0 6px 12px rgba(25, 36, 55, 0.11);
        }

        .ops-case-card--waiting::before {
            inset: 0.42rem 0 0;

            background:
                radial-gradient(circle at 50% -26%, rgba(255, 255, 255, 0.94), transparent 43%),
                linear-gradient(168deg, #fbfbfc 0%, #f5f6f8 55%, #eaedf2 100%);
            border: 1px solid rgba(255, 255, 255, 0.86);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.98),
                inset 0 -1px 1px rgba(48, 61, 81, 0.06);
            z-index: 1;
        }

        .ops-case-card--waiting::after {
            width: auto;
            height: 0.48rem;
            inset: 0 0 auto;
            border-radius: 30px 30px 0 0;
            transform: none;
            /* background: linear-gradient(90deg, var(--ops-card-rim), #3c557b 72%, rgba(36, 134, 142, 0.92)); */
            box-shadow: none;
            z-index: 0;
        }

        .ops-case-card--waiting .ops-case-card__content {
            padding: 1.1rem 0.98rem 0.9rem;
        }

        .ops-case-card--waiting.ops-case-card--selectable .ops-case-card__content {
            padding-left: 2.72rem;
        }

        .ops-case-card--waiting .ops-case-card__select {
            top: 50%;
            left: 1rem;
        }

        .ops-case-card--waiting .ops-case-card__select input[type="checkbox"] {
            width: 1.02rem;
            height: 1.02rem;
            accent-color: var(--ops-card-accent);
        }

        .ops-case-card--waiting .ops-case-card__row {
            grid-template-columns: minmax(0, 40%) minmax(0, 1fr) 3.15rem;
            column-gap: 0.48rem;
        }

        .ops-case-card--waiting .ops-case-card__row--secondary {
            margin-top: 0.18rem;
            align-items: center;
        }

        .ops-case-card--waiting .ops-case-card__doctor,
        .ops-case-card--waiting .ops-case-card__patient {
            height: auto;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            text-shadow: none;
        }

        .ops-case-card--waiting .ops-case-card__doctor {
            color: #374657;
            font-size: 0.98rem;
            font-weight: 700;
            line-height: 1.38;
            text-align: center;
        }

        .ops-case-card--waiting .ops-case-card__patient {
            color: var(--ops-card-ink);
            font-size: 1.05rem;
            font-weight: 800;
            line-height: 1.38;
            text-align: center;
            letter-spacing: -0.01em;
        }

        .ops-case-card--waiting .ops-case-card__date {
            justify-self: center;
            width: auto;
            max-width: 100%;
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: var(--ops-card-muted);
            font-size: 0.88rem;
            font-weight: 700;
            letter-spacing: 0;
            box-shadow: none;
        }

        .ops-case-card--waiting .ops-case-card__units {
            width: 3.15rem;
            align-self: stretch;
            align-items: center;
            justify-content: center;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .ops-case-card--waiting .ops-case-card__units-pill {
            min-width: 2.42rem;
            height: 2.42rem;
            padding: 0;
            border: 1px solid rgba(54, 67, 87, 0.14);
            border-radius: 50%;
            background:
                radial-gradient(circle at 45% 28%, #ffffff 0%, #f4f5f8 49%, #e6e9ee 100%);
            color: #273545;
            font-size: 1rem;
            font-weight: 600;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.96),
                0 5px 11px rgba(29, 41, 59, 0.14);
        }

        .ops-case-card--waiting .ops-case-card__badges {
            gap: 0.3rem;
            padding-left: 0.08rem;
        }

        .ops-case-card--waiting .ops-case-card__badge {
            min-height: 1.16rem;
            padding: 0.09rem 0.44rem;
            border-color: rgba(229, 163, 58, 0.3);
            background: rgba(255, 247, 232, 0.8);
            color: #b97108;
            font-size: 0.56rem;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .ops-case-card--waiting .ops-case-card__tags {
            width: 3.15rem;
            min-height: 1.52rem;
            gap: 0.24rem;
            justify-content: center;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .ops-case-card--waiting .ops-case-card__tags i {
            width: 0.94rem;
            font-size: 0.9rem;
            filter: none;
        }

        .ops-case-card--waiting .ops-case-card__tags:not(:has(i)) {
            visibility: hidden;
        }

        .ops-case-card--active,
        .ops-case-card--waiting {
            --ops-card-accent: #168a92;
            --ops-card-ink: #263748;
            --ops-card-muted: #5d6f80;
            margin-bottom: 0.42rem;
            border: 1px solid rgba(22, 138, 146, 0.42);
            border-radius: 14px;
            background: #eef4f6;
            box-shadow: none;
            transition: border-color 0.16s ease, background-color 0.16s ease;
        }

        .ops-case-card--active:hover,
        .ops-case-card--waiting:hover {
            transform: none;
            border-color: rgba(22, 138, 146, 0.54);
            background: #edf3f5;
            box-shadow: none;
        }

        .ops-case-card--active::before,
        .ops-case-card--waiting::before {
            display: none;
        }

        .ops-case-card--active::after,
        .ops-case-card--waiting::after {
            width: auto;
            height: 0.22rem;
            inset: 0 0 auto;
            border-radius: 14px 14px 0 0;
            transform: none;
            background: linear-gradient(90deg, #137982, #24a4aa);
            box-shadow: none;
        }

        .ops-case-card--active .ops-case-card__content,
        .ops-case-card--waiting .ops-case-card__content {
            padding: 0.58rem 0.66rem 0.48rem;
        }

        .ops-case-card--waiting.ops-case-card--selectable .ops-case-card__content {
            padding-left: 2.05rem;
        }

        .ops-case-card--waiting .ops-case-card__select {
            left: 0.66rem;
        }

        .ops-case-card--active .ops-case-card__row,
        .ops-case-card--waiting .ops-case-card__row {
            grid-template-columns: minmax(0, 40%) minmax(0, 1fr) 2.45rem;
            gap: 0.12rem 0.42rem;
        }

        .ops-case-card--active .ops-case-card__row--secondary,
        .ops-case-card--waiting .ops-case-card__row--secondary {
            margin-top: 0.08rem;
        }

        .ops-case-card--active .ops-case-card__doctor,
        .ops-case-card--active .ops-case-card__patient,
        .ops-case-card--waiting .ops-case-card__doctor,
        .ops-case-card--waiting .ops-case-card__patient {
            height: auto;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
            color: var(--ops-card-ink);
            box-shadow: none;
            text-shadow: none;
            line-height: 1.28;
        }

        .ops-case-card--active .ops-case-card__doctor,
        .ops-case-card--waiting .ops-case-card__doctor {
            font-size: 0.88rem;
            font-weight: 700;
            text-align: center;
        }

        .ops-case-card--active .ops-case-card__patient,
        .ops-case-card--waiting .ops-case-card__patient {
            font-size: 0.94rem;
            font-weight: 800;
            text-align: center;
            letter-spacing: -0.01em;
        }

        .ops-case-card--active .ops-case-card__date,
        .ops-case-card--waiting .ops-case-card__date {
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: var(--ops-card-muted);
            font-size: 0.76rem;
            font-weight: 700;
            line-height: 1.2;
            box-shadow: none;
        }

        .ops-case-card--active .ops-case-card__units,
        .ops-case-card--waiting .ops-case-card__units,
        .ops-case-card--active .ops-case-card__tags,
        .ops-case-card--waiting .ops-case-card__tags {
            width: 2.45rem;
            min-height: 1.25rem;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
        }

        .ops-case-card--active .ops-case-card__units-pill,
        .ops-case-card--waiting .ops-case-card__units-pill {
            min-width: 1.86rem;
            height: 1.86rem;
            padding: 0;
            border-radius: 50%;
            background: rgba(247, 250, 252, 0.78);
            color: #243444;
            font-size: 0.78rem;
            font-weight: 700;
            box-shadow: none;
        }

        .ops-case-card--active .ops-case-card__badge,
        .ops-case-card--waiting .ops-case-card__badge {
            min-height: 1rem;
            padding: 0.05rem 0.34rem;
            font-size: 0.52rem;
            box-shadow: none;
        }

        .ops-case-card--active .ops-case-card__tags,
        .ops-case-card--waiting .ops-case-card__tags {
            gap: 0.2rem;
            justify-content: center;
        }

        .ops-case-card--active .ops-case-card__tags i,
        .ops-case-card--waiting .ops-case-card__tags i {
            width: 0.86rem;
            font-size: 0.84rem;
        }

        @media (max-width: 767.98px) {
            .stageSidebar .stageName {
                font-size: clamp(0.54rem, 2.3vw, 0.6rem) !important;
                letter-spacing: -0.01em;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .stage-inner-tabs {
                position: relative;
                display: flex;
                align-items: stretch;
                flex-wrap: nowrap;
                gap: 0;
                padding: 0 0.18rem;
                margin: 0 0 -0.18rem;
                z-index: 3;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .stage-inner-tabs::after {
                content: "";
                position: absolute;
                left: 0.18rem;
                right: 0.18rem;
                bottom: -0.28rem;
                height: 1.08rem;
                border-radius: 18px 18px 0 0;
                background: linear-gradient(180deg, rgba(220, 232, 237, 0.96), rgba(246, 250, 252, 0.4));
                z-index: -1;
                pointer-events: none;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .stage-inner-tabs .innerBtn {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
                min-width: 0 !important;
                justify-content: center;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerActiveBtn,
            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerWaitingBtn {
                position: relative;
                border: 1px solid transparent !important;
                border-radius: 18px 18px 0 0 !important;
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.86), rgba(247, 251, 253, 0.98)) padding-box,
                    linear-gradient(135deg, rgba(120, 152, 164, 0.24), rgba(255, 255, 255, 0.96) 38%, rgba(120, 152, 164, 0.12) 100%) border-box !important;
                padding: 0.72rem 1rem 0.88rem !important;
                min-height: 4.2rem;
                box-shadow: none !important;
                overflow: hidden;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerActiveBtn {
                border-radius: 18px 0 0 0 !important;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerWaitingBtn {
                border-radius: 0 18px 0 0 !important;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerActiveBtn[aria-selected="true"] {
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(240, 247, 255, 0.98)) padding-box,
                    linear-gradient(135deg, rgba(47, 111, 179, 0.34), rgba(255, 255, 255, 0.98) 36%, rgba(47, 111, 179, 0.14) 100%) border-box !important;
                color: #2f6fb3;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerWaitingBtn[aria-selected="true"] {
                background:
                    linear-gradient(180deg, rgba(255, 255, 255, 0.99), rgba(255, 244, 244, 0.98)) padding-box,
                    linear-gradient(135deg, rgba(198, 88, 88, 0.34), rgba(255, 255, 255, 0.98) 36%, rgba(198, 88, 88, 0.14) 100%) border-box !important;
                color: #c65858;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerActiveBtn[aria-selected="true"]::after,
            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerWaitingBtn[aria-selected="true"]::after {
                content: "";
                position: absolute;
                left: 14px;
                right: 14px;
                bottom: 0;
                height: 3px;
                border-radius: 999px 999px 0 0;
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerActiveBtn[aria-selected="true"]::after {
                background: linear-gradient(90deg, rgba(47, 111, 179, 0.7), rgba(97, 152, 214, 0.98), rgba(47, 111, 179, 0.7));
            }

            .ops-dashboard .macaw-tabs.macaw-silk-tabs .innerWaitingBtn[aria-selected="true"]::after {
                background: linear-gradient(90deg, rgba(198, 88, 88, 0.7), rgba(224, 102, 102, 0.98), rgba(198, 88, 88, 0.7));
            }

            .ops-dashboard .stage-panel-pane .stage-panel-scroll:not(.stage-panel-scroll--devices) {
                position: relative;
                margin-top: -0.16rem;
                padding: 0.96rem 0.46rem 0.46rem;
                border-radius: 0 20px 20px 20px;
                background:
                    radial-gradient(120% 100% at 0% 0%, rgba(61, 123, 136, 0.09) 0%, rgba(61, 123, 136, 0) 44%),
                    linear-gradient(180deg, rgba(245, 249, 251, 0.98), rgba(255, 255, 255, 0.96));
                overflow: hidden;
            }

            .ops-dashboard .stage-panel-pane .stage-panel-scroll:not(.stage-panel-scroll--devices)::before {
                content: "";
                position: absolute;
                inset: 0;
                pointer-events: none;
                background:
                    linear-gradient(135deg, rgba(124, 156, 168, 0.16), rgba(255, 255, 255, 0) 24%, rgba(124, 156, 168, 0.08) 100%);
            }

            .ops-dashboard .stage-panel-pane .stage-panel-scroll:not(.stage-panel-scroll--devices) > * {
                position: relative;
                z-index: 1;
            }

            .ops-case-list {
                max-width: none;
            }
        }

        @media (min-width: 992px) {
            .ops-case-list {
                max-width: 1120px;
            }

            .ops-case-card__content {
                padding: 0.85rem 1rem;
            }

            .ops-case-card__row {
                grid-template-columns: minmax(220px, 1fr) minmax(220px, 1fr) 74px;
            }

            .ops-case-card__doctor {
                font-size: 1.03rem;
            }

            .ops-case-card__patient,
            .ops-case-card__date {
                font-size: 0.95rem;
            }
        }



        /* Force equal column widths and prevent expansion of empty columns */


        .sunriseTable tbody:last-child {
            max-width: 10% !important;
            overflow: visible !important;
            text-overflow: clip !important;
            word-wrap: normal !important;


            p {
                /*margin: 2px !important;*/
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

        .ops-dashboard .waitingTable.sunriseTable,
        .ops-dashboard .activeTable.sunriseTable,
        .ops-dashboard table.sunriseTable {
            border-collapse: separate !important;
            border-spacing: 0 3px !important;
        }

        .ops-dashboard .sunriseTable th:first-child,
        .ops-dashboard .sunriseTable td:first-child {
            padding-left: 3px !important;
        }

        .ops-dashboard .waitingTable.sunriseTable th.ops-col--doctor,
        .ops-dashboard .activeTable.sunriseTable th.ops-col--doctor,
        .ops-dashboard .waitingTable.sunriseTable td.ops-col--doctor,
        .ops-dashboard .activeTable.sunriseTable td.ops-col--doctor {
            padding-left: 13px !important;
        }

        .ops-dashboard .waitingTable.sunriseTable td.ops-col--doctor > p,
        .ops-dashboard .activeTable.sunriseTable td.ops-col--doctor > p {
            padding-left: 0 !important;
        }
    </style>
    {{--            $('.sunriseTable').each(function() {--}}
    {{--                if (!$.fn.DataTable.isDataTable(this)) {--}}
    {{--                    $(this).DataTable();--}}
    {{--                }--}}
    {{--            });--}}
    {{--        });--}}
    {{--    </script>--}}


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
    <script src="{{ asset('https://cdn.jsdelivr.net/gh/htmlcssfreebies/macaw-tabs@v1.0.4/dist/js/macaw-tabs.js') }}">
    </script>

    <!-- Then load your custom scripts -->
    <script src="{{ asset('assets') }}/js/ysh-custom-js/v3scripts.js?v={{ filemtime(public_path('assets/js/ysh-custom-js/v3scripts.js')) }}"></script>
    <script src="{{ asset('assets') }}/js/ysh-custom-js/operationsDashboardJS.js?v={{ filemtime(public_path('assets/js/ysh-custom-js/operationsDashboardJS.js')) }}"></script>
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

        // function startLoading() {
        //     container.classList.add('loading');
        // }
        //
        // function stopLoading() {
        //     container.classList.remove('loading');
        // }
        //
        // // Simulate loading
        // startLoading();
        // setTimeout(stopLoading, 2000); // remove shimmer after 2s

        // Apply device image configuration when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            function syncManufacturingActiveTabHeights() {
                const dashboardTabs = document.querySelector('.ops-dashboard .macaw-tabs.macaw-aurora-tabs');
                const sidebarTab =
                    document.querySelector('.ops-dashboard .stageSidebar button[role="tab"][aria-selected="true"]') ||
                    document.querySelector('.ops-dashboard .stageSidebar button[role="tab"]');

                if (!dashboardTabs || !sidebarTab) {
                    return;
                }

                const sidebarTabHeight = Math.ceil(sidebarTab.getBoundingClientRect().height);
                if (sidebarTabHeight > 0) {
                    dashboardTabs.style.setProperty('--sigma-ops-sidebar-tab-height', `${sidebarTabHeight}px`);
                }
            }

            // Initialize Macaw Tabs if available
            if (typeof MacawTabs !== 'undefined') {
                MacawTabs.init();
            }

            syncManufacturingActiveTabHeights();
            window.addEventListener('load', syncManufacturingActiveTabHeights);
            window.addEventListener('resize', syncManufacturingActiveTabHeights);

            // Apply device image styling from configuration
            if (typeof jQuery !== 'undefined') {
                // Apply to all device images
                jQuery('.device-item img').css({
                                                   'width': '{{ $deviceConfig['width'] }}',
                                                   'max-width': '{{ $deviceConfig['max_width'] }}',
                                                   'height': '{{ $deviceConfig['height'] }}',
                                                   'padding': '{{ $deviceConfig['padding'] }}',
                                                   'border-radius': '{{ $deviceConfig['border_radius'] }}',
                                                   'background': '{{ $deviceConfig['background'] }}',
                                                   'object-fit': 'contain'
                                               });

                // Apply to device containers
                jQuery('.device-container').css({
                                                    'gap': '{{ $deviceConfig['container_gap'] ?? '15px' }}',
                                                    'width': '100%'
                                                });

                // Remove hover effects if disabled in config
                @if (!$deviceConfig['hover_effect'])
                jQuery('.device-item').hover(
                    function() {
                        jQuery(this).css({
                                             'box-shadow': 'none',
                                             'transform': 'none'
                                         });
                    },
                    function() {
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
                                               'max-width': '{{ $deviceConfig['max_width'] }}'
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
            jQuery('.modal').on('show.bs.modal', function() {
                var $modal = jQuery(this);

                // Some modals are rendered inside desktop-only wrappers (e.g. `.d-none d-md-block`).
                // On mobile, that makes the modal "open" (backdrop + body lock) but stay invisible because an ancestor is `display:none`.
                // Moving the modal to <body> ensures it can render and the backdrop click-to-dismiss works again.
                if (!$modal.parent().is('body')) {
                    $modal.appendTo(document.body);
                }

                // Store original button states
                $modal.find('button').each(function() {
                    var $btn = jQuery(this);
                    $btn.data('original-disabled', $btn.prop('disabled'));
                    $btn.data('original-text', $btn.text());
                    $btn.data('original-class', $btn.attr('class'));
                });

                // Store original input values
                $modal.find('input, textarea, select').each(function() {
                    var $input = jQuery(this);
                    $input.data('original-value', $input.val());
                    $input.data('original-checked', $input.prop('checked'));
                    $input.data('original-disabled', $input.prop('disabled'));
                });

                // Store original image states
                $modal.find('img').each(function() {
                    var $img = jQuery(this);
                    $img.data('original-src', $img.attr('src'));
                    $img.data('original-class', $img.attr('class'));
                    $img.data('original-style', $img.attr('style'));
                });

                // Store device selection states
                $modal.find('.device-item').each(function() {
                    var $device = jQuery(this);
                    $device.data('original-class', $device.attr('class'));
                    $device.data('original-style', $device.attr('style'));
                    $device.data('original-selected', $device.hasClass('selected'));
                });

                // Store checkbox states
                $modal.find('input[type="checkbox"]').each(function() {
                    var $checkbox = jQuery(this);
                    $checkbox.data('original-checked', $checkbox.prop('checked'));
                });
            });

            // Reset to original state when a modal is hidden
            jQuery('.modal').on('hidden.bs.modal', function() {
                var $modal = jQuery(this);

                // Reset buttons
                $modal.find('button').each(function() {
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
                $modal.find('input, textarea, select').each(function() {
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
                $modal.find('img').each(function() {
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
                $modal.find('.device-item').each(function() {
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
                $modal.find('input[type="checkbox"]').each(function() {
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


    </script>
    <style>

        /* Ensure badges are perfect circles on all screen sizes */
        .activeBadge, .waitingBadge {
            min-width: 26px !important;
            min-height: 26px !important;
            aspect-ratio: 1 !important;
            border-radius: 50% !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 5px !important;
            flex-shrink: 0 !important;
            line-height: 1 !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            color: #fff !important;
            vertical-align: middle !important;
            box-sizing: border-box !important;
            transition: none !important;
        }
        .activeBadge {
            background-color: #007bff !important; /* Deeper blue */
        }
        .waitingBadge {
            background-color: #dc3545 !important; /* Deeper red */
        }

        /* Ensure badge container aligns items horizontally */
        .stageSidebar button[role="tab"] > div {
            display: inline-flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 4px !important;
            vertical-align: middle !important;
        }

        @media (min-width: 992px){ /* Apply only on desktop */
            /* Sidebar and Inner Tabs should stick below the main navbar */
            .stageSidebar.kt-portlet--sticky-on, .macaw-silk-tabs > [role="tablist"].kt-portlet--sticky-on {
                top: 60px !important; /* Assuming main navbar height of 60px */
                z-index: 1020 !important; /* Ensure they are above content */
            }

            /* Table header should stick below the inner tabs */
            .sunriseTable > thead.kt-portlet--sticky-on {
                top: 100px !important; /* 60px (navbar) + 40px (inner tabs height) */
                z-index: 1000 !important; /* Below sidebar and inner tabs */
            }

            /* Ensure consistent badge positioning within the stageSidebar buttons */
            .stageSidebar button[role="tab"] {
                display: flex; /* Enable flexbox for horizontal alignment */
                justify-content: space-between; /* Space out items */
                align-items: center; /* Vertically align items */
            }

        }

        /* Column Width Config Panel */
        .config-panel {
            position: fixed;
            bottom: -400px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 250, 252, 0.95) 100%);
            border-radius: 16px 16px 0 0;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15), 0 -4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px 24px;
            width: 90%;
            max-width: 1200px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.6);
            backdrop-filter: blur(12px);
        }

        .config-panel[hidden] {
            display: none !important;
        }

        .config-panel.active {
            bottom: 20px;
            opacity: 1;
            visibility: visible;
        }

        .config-panel #columnWidthInputs {
            display: flex;
            flex-wrap: wrap;
            gap: 0;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Mobile optimization */
        @media (max-width: 768px) {
            .config-panel {
                width: 95%;
                padding: 12px 16px;
                max-height: 400px;
                border-radius: 12px 12px 0 0;
            }

            .config-panel h4 {
                font-size: 16px;
            }

            .config-panel p {
                font-size: 11px;
            }

            .config-panel #columnWidthInputs {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                border: none;
            }

            .config-panel .column-input-row {
                padding: 8px 10px;
                border: 1px solid #e2e8f0;
                border-radius: 6px;
                min-width: unset;
            }

            .config-panel .column-input-row label {
                font-size: 10px;
                margin-bottom: 4px;
            }

            .config-panel .column-input-row input {
                padding: 6px 8px;
                font-size: 13px;
            }

            .config-panel .btn-sm {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

        .config-panel .column-input-row {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            padding: 12px 16px;
            background: white;
            border-right: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
            min-width: 150px;
            transition: all 0.2s ease;
        }

        .config-panel .column-input-row:hover {
            background: #f8fafc;
        }

        .config-panel .column-input-row label {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .config-panel .column-input-row input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            background: white;
            text-align: center;
            color: #1e293b;
            -webkit-appearance: auto;
            -moz-appearance: textfield;
        }

        /* Show spinner controls on webkit */
        .config-panel .column-input-row input::-webkit-inner-spin-button,
        .config-panel .column-input-row input::-webkit-outer-spin-button {
            opacity: 1;
            height: 30px;
        }

        .config-panel .column-input-row input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .config-panel .reset-hint {
            display: inline-block;
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
    </style>

    <!-- Dashboard: Column width config panel -->
    <script>
        $(document).ready(function() {
            var $panel = $('#columnConfigPanel');
            var $inputsContainer = $('#columnWidthInputs');
            var storageKey = 'dashboard_master_widths';
            var widthStore = window.sigmaTableWidthStore || null;
            var panelHideTimer = null;
            var defaultColumnWidths = {
                '[Checkbox]': 5,
                '#': 40,
                'Assigned To': 40,
                'Delivery Date': 40,
                'Doctor': 60,
                'Patient': 80,
                'Tags': 40
            };
            var defaultColumnClassWidths = {
                'ops-col--select': 5,
                'ops-col--count': 40,
                'ops-col--assigned': 40,
                'ops-col--delivery': 40,
                'ops-col--doctor': 60,
                'ops-col--patient': 80,
                'ops-col--tags': 40
            };

            function getSavedWidths() {
                if (!widthStore || typeof widthStore.get !== 'function') {
                    return {};
                }
                var saved = widthStore.get(storageKey);
                return saved && typeof saved === 'object' ? saved : {};
            }

            function setSavedWidths(widths) {
                if (widthStore && typeof widthStore.set === 'function') {
                    widthStore.set(storageKey, widths || {});
                }
            }

            function clearSavedWidths() {
                if (widthStore && typeof widthStore.reset === 'function') {
                    widthStore.reset(storageKey);
                }
            }

            function getDefaultColumnWidth(colName) {
                return defaultColumnWidths[colName] || null;
            }

            function getDefaultColumnWeight(colName) {
                var key = (colName || '').toLowerCase();
                if (key.includes('patient') || key.includes('doctor') || key.includes('name')) return 2.8;
                if (key.includes('status') || key.includes('stage') || key.includes('material') || key.includes('type')) return 1.7;
                if (key.includes('date') || key.includes('time') || key.includes('delivery')) return 1.8;
                if (key.includes('id') || key.includes('unit') || key.includes('qty') || key.includes('count') || key.includes('tag')) return 0.9;
                if (key.includes('checkbox') || key === '[checkbox]') return 0.7;
                return 1.3;
            }

            function buildDefaultWidthPercentages(columnNames) {
                var weights = columnNames.map(getDefaultColumnWeight);
                var total = weights.reduce(function(sum, w) { return sum + w; }, 0) || 1;
                var percentages = weights.map(function(w) {
                    return Math.round((w / total) * 1000) / 10;
                });
                var allocated = percentages.reduce(function(sum, p) { return sum + p; }, 0);
                var delta = Math.round((100 - allocated) * 10) / 10;
                if (percentages.length) {
                    percentages[percentages.length - 1] = Math.max(4, Math.round((percentages[percentages.length - 1] + delta) * 10) / 10);
                }
                return percentages;
            }

            function applyDefaultWidthsToTable($table) {
                if (!$table || !$table.length) return;

                var saved = getSavedWidths();
                if (Object.keys(saved).length > 0) return;
                if ($table.attr('data-sigma-width-mode') === 'default') return;

                var names = [];
                $table.find('thead th').each(function() {
                    var desktopSpan = $(this).find('.innerSpan4DeskTop');
                    var text = desktopSpan.length > 0 ? desktopSpan.text().trim() : $(this).text().trim();
                    names.push(text || '[Checkbox]');
                });
                if (!names.length) return;

                var applied = false;
                $table.css('table-layout', 'fixed');

                Object.keys(defaultColumnClassWidths).forEach(function(className) {
                    var width = defaultColumnClassWidths[className];
                    var $cells = $table.find('thead th.' + className + ', tbody td.' + className);
                    if (!$cells.length || !width) return;
                    applied = true;
                    $cells.css({
                                   width: width + 'px',
                                   minWidth: width + 'px',
                                   maxWidth: width + 'px'
                               });
                });

                $table.find('thead th').each(function(i) {
                    var colName = names[i];
                    var width = getDefaultColumnWidth(colName);
                    if (!width) return;
                    applied = true;
                    $(this).css({
                                    width: width + 'px',
                                    minWidth: width + 'px',
                                    maxWidth: width + 'px'
                                });
                });

                $table.find('tbody tr').each(function() {
                    $(this).find('td').each(function(i) {
                        var colName = names[i];
                        var width = getDefaultColumnWidth(colName);
                        if (!width) return;
                        $(this).css({
                                        width: width + 'px',
                                        minWidth: width + 'px',
                                        maxWidth: width + 'px'
                                    });
                    });
                });

                if (applied) {
                    $table.attr('data-sigma-width-mode', 'default');
                }
            }

            // Lightweight perf helpers for dashboard load timing
            var __sigmaPerf = {
                marks: {},
                mark: function(label) {
                    this.marks[label] = (window.performance && performance.now)
                        ? performance.now()
                        : Date.now();
                },
                measure: function(label, start, end) {
                    return;
                }
            };

            function applyDefaultWidthsToAllTables() {
                __sigmaPerf.mark('default-widths-start');
                $('.waitingTable.sunriseTable, .activeTable.sunriseTable').each(function() {
                    applyDefaultWidthsToTable($(this));
                });
                __sigmaPerf.mark('default-widths-end');
                __sigmaPerf.measure('applyDefaultWidthsToAllTables()', 'default-widths-start', 'default-widths-end');
            }

            function runManagedWidthsPass() {
                __sigmaPerf.mark('managed-widths-start');
                var savedWidths = getSavedWidths();
                if (Object.keys(savedWidths).length > 0) {
                    applyWidths();
                    __sigmaPerf.mark('managed-widths-end');
                    __sigmaPerf.measure('runManagedWidthsPass()', 'managed-widths-start', 'managed-widths-end');
                    return;
                }
                applyDefaultWidthsToAllTables();
                __sigmaPerf.mark('managed-widths-end');
                __sigmaPerf.measure('runManagedWidthsPass()', 'managed-widths-start', 'managed-widths-end');
            }

            window.sigmaRunManagedTableWidths = runManagedWidthsPass;

            // Get unique column names from all tables
            function getColumnNames() {
                var columnNames = new Set();

                $('.waitingTable, .activeTable').each(function() {
                    $(this).find('thead th').each(function() {
                        // Prioritize desktop span text if it exists
                        var desktopSpan = $(this).find('.innerSpan4DeskTop');
                        var text = desktopSpan.length > 0 ? desktopSpan.text().trim() : $(this).text().trim();

                        if (text) {
                            columnNames.add(text);
                        } else {
                            // For checkbox columns or empty headers
                            columnNames.add('[Checkbox]');
                        }
                    });
                });

                var columnsArray = Array.from(columnNames);

                // Sort: Checkbox first, then alphabetically
                columnsArray.sort(function(a, b) {
                    if (a === '[Checkbox]') return -1;
                    if (b === '[Checkbox]') return 1;
                    return a.localeCompare(b);
                });

                return columnsArray;
            }

            // Populate panel with inputs
            function populatePanel() {
                var columnNames = getColumnNames();
                if (columnNames.length === 0) return;

                var savedWidths = getSavedWidths();
                $inputsContainer.empty();

                console.log('Available columns:', columnNames);

                columnNames.forEach(function(colName) {
                    var defaultWidth = savedWidths[colName] || getDefaultColumnWidth(colName) || '';
                    var displayName = colName === '[Checkbox]' ? '☑ Checkbox' : colName;
                    var html = '<div class="column-input-row">' +
                        '<label>' + displayName + '</label>' +
                        '<input type="number" class="width-input" data-col-name="' + colName + '" value="' + defaultWidth + '" placeholder="Auto" min="5" step="5">' +
                        '</div>';
                    $inputsContainer.append(html);
                });

                // Attach instant apply on input change
                $('.width-input').on('input change', function() {
                    applyWidths();
                });
            }

            // Apply widths to all tables
            function applyWidths() {
                __sigmaPerf.mark('apply-widths-start');
                var widths = {};
                $inputsContainer.find('input').each(function() {
                    var colName = $(this).data('col-name');
                    var value = parseInt($(this).val()) || null;
                    if (value) widths[colName] = value;
                });

                // Save widths to user preferences
                setSavedWidths(widths);

                // Apply to all waitingTable and activeTable
                $('.waitingTable, .activeTable').each(function() {
                    var $table = $(this);

                    // Force table-layout: fixed for pixel widths
                    $table.css('table-layout', 'fixed');

                    // Build column name to index mapping for this table
                    var colMap = {};
                    $table.find('thead th').each(function(i) {
                        // Prioritize desktop span text if it exists
                        var desktopSpan = $(this).find('.innerSpan4DeskTop');
                        var text = desktopSpan.length > 0 ? desktopSpan.text().trim() : $(this).text().trim();
                        if (!text) text = '[Checkbox]';
                        colMap[text] = i;
                    });

                    // Apply to thead th by matching column name
                    for (var colName in widths) {
                        if (colMap[colName] !== undefined) {
                            var idx = colMap[colName];
                            $table.find('thead th').eq(idx).css({
                                                                    'width': widths[colName] + 'px',
                                                                    'min-width': widths[colName] + 'px',
                                                                    'max-width': widths[colName] + 'px'
                                                                });
                        }
                    }

                    // Apply to tbody td
                    $table.find('tbody tr').each(function() {
                        for (var colName in widths) {
                            if (colMap[colName] !== undefined) {
                                var idx = colMap[colName];
                                $(this).find('td').eq(idx).css({
                                                                   'width': widths[colName] + 'px',
                                                                   'min-width': widths[colName] + 'px',
                                                                   'max-width': widths[colName] + 'px'
                                                               });
                            }
                        }
                    });
                    $table.attr('data-sigma-width-mode', 'custom');
                });
                __sigmaPerf.mark('apply-widths-end');
                __sigmaPerf.measure('applyWidths()', 'apply-widths-start', 'apply-widths-end');
            }

            // Reset to auto widths
            function resetToAuto() {
                // Clear saved widths
                clearSavedWidths();

                // Clear all custom widths from tables
                $('.waitingTable, .activeTable').each(function() {
                    $(this).css('table-layout', '');
                    $(this).find('th, td').css({
                                                   'width': '',
                                                   'min-width': '',
                                                   'max-width': ''
                                               });
                    $(this).removeAttr('data-sigma-width-mode');
                });

                // Repopulate panel with empty values (all Auto)
                populatePanel();
                applyDefaultWidthsToAllTables();

                // Show toast notification
                if (typeof showToast === 'function') {
                    showToast('All column widths reset to Auto', 'success');
                }
            }

            // Initialize panel on first load
            function initPanel() {
                __sigmaPerf.mark('panel-init-start');
                populatePanel();

                // Reset to Auto button
                $('#resetToAutoBtn').on('click', resetToAuto);
                $('#columnConfigCloseBtn').on('click', hideColumnConfigPanel);
                __sigmaPerf.mark('panel-init-end');
                __sigmaPerf.measure('initPanel()', 'panel-init-start', 'panel-init-end');
            }

            function showColumnConfigPanel() {
                clearTimeout(panelHideTimer);
                $panel.prop('hidden', false).attr('aria-hidden', 'false');

                requestAnimationFrame(function() {
                    $panel.addClass('active');
                });
            }

            function hideColumnConfigPanel() {
                clearTimeout(panelHideTimer);
                $panel.removeClass('active').attr('aria-hidden', 'true');
                panelHideTimer = setTimeout(function() {
                    if (!$panel.hasClass('active')) {
                        $panel.prop('hidden', true);
                    }
                }, 450);
            }

            function toggleColumnConfigPanel() {
                if ($panel.hasClass('active')) {
                    hideColumnConfigPanel();
                    return;
                }

                showColumnConfigPanel();
            }

            if (widthStore && typeof widthStore.whenReady === 'function') {
                widthStore.whenReady(initPanel);
            } else {
                initPanel();
            }

            // F2: Toggle panel
            // F3: Reset widths
            $(document).on('keydown', function(e) {
                if (e.key === 'F2') {
                    e.preventDefault();
                    toggleColumnConfigPanel();
                } else if (e.key === 'F3') {
                    e.preventDefault();
                    clearSavedWidths();
                    populatePanel();
                    $('.waitingTable, .activeTable').each(function() {
                        $(this).css('table-layout', '');
                        $(this).find('th, td').css({
                                                       'width': '',
                                                       'min-width': '',
                                                       'max-width': ''
                                                   });
                        $(this).removeAttr('data-sigma-width-mode');
                    });
                    applyDefaultWidthsToAllTables();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                                      icon: 'success',
                                      title: 'Reset Complete',
                                      text: 'Column widths cleared - using browser defaults',
                                      timer: 2000,
                                      showConfirmButton: false
                                  });
                    }
                }
            });

            // Single managed pass after initial render.
            if (widthStore && typeof widthStore.whenReady === 'function') {
                widthStore.whenReady(function() {
                    setTimeout(function() {
                        runManagedWidthsPass();
                    }, 180);
                });
            } else {
                setTimeout(function() {
                    runManagedWidthsPass();
                }, 180);
            }

            // Re-apply once each time a DataTable is initialized (prevents reload flicker).
            $(document).on('init.dt', function(e, settings) {
                __sigmaPerf.mark('datatable-init-start');
                var table = settings && settings.nTable ? settings.nTable : null;
                if (!table) return;
                var $table = $(table);
                if (!$table.hasClass('waitingTable') && !$table.hasClass('activeTable')) return;

                var applyForTable = function() {
                    var savedWidths = getSavedWidths();
                    if (Object.keys(savedWidths).length > 0) {
                        applyWidths();
                    } else {
                        $table.removeAttr('data-sigma-width-mode');
                        applyDefaultWidthsToTable($table);
                    }
                };

                if (widthStore && typeof widthStore.whenReady === 'function') {
                    widthStore.whenReady(applyForTable);
                } else {
                    applyForTable();
                }
                __sigmaPerf.mark('datatable-init-end');
                __sigmaPerf.measure('DataTable init.dt apply widths', 'datatable-init-start', 'datatable-init-end');
            });

            // Hidden tabs/tables should still get normalized widths once shown.
            $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function() {
                setTimeout(function() {
                    if (widthStore && typeof widthStore.whenReady === 'function') {
                        widthStore.whenReady(function() {
                            runManagedWidthsPass();
                        });
                    } else {
                        runManagedWidthsPass();
                    }
                }, 50);
            });

            $(document).on('click', '.sigma-modal--cases-dashboard-case-completion, .sigma-modal--cases-dashboard-case-completion-alt', function(e) {
                if (e.target === this) {
                    $(this).modal('hide');
                }
            });

            // Reset triggered from user settings page.
            document.addEventListener('sigma:table-widths-reset', function(event) {
                var removedKeys = event && event.detail && Array.isArray(event.detail.removedKeys) ? event.detail.removedKeys : [];
                if (removedKeys.length > 0 && removedKeys.indexOf(storageKey) === -1) {
                    return;
                }
                populatePanel();
                $('.waitingTable, .activeTable').removeAttr('data-sigma-width-mode');
                runManagedWidthsPass();
            });
        });
    </script>
@endpush
