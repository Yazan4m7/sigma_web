@extends('layouts.app' ,[ 'pageSlug' => "Cases List"])

@section('content')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');

        /* Customizations */
        .sigma-sticky-toolbar {
            top: var(--sigma-app-header-offset) !important;
            z-index: 110 !important;
            position: sticky !important;
            background: #f4f6fb !important;
            padding-top: 16px !important;
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
            isolation: isolate;
            transition: padding-top 0.22s ease;
            will-change: padding-top;
        }

        .sigma-sticky-toolbar.sigma-sticky-toolbar--stuck {
            padding-top: 0 !important;
        }

        .sigma-sticky-toolbar::before {
            display: none !important;
        }

        #casesTable {
            font-family: 'Roboto', sans-serif;
        }

        .dropdown-toggle .filter-option{ height: auto !important;}

        #casesTable tbody td:first-child {
            padding-left: 10px !important; /* Add more left padding */
        }

        #casesTable thead th {
            padding: 8px 16px !important;
        }

        #casesTable thead th:nth-child(1),
        #casesTable thead th:nth-child(2),
        #casesTable thead th:nth-child(7) {
            text-align: left !important;
        }

        /* Move sorting icons closer */
        #casesTable thead .sorting::after,
        #casesTable thead .sorting_asc::after,
        #casesTable thead .sorting_desc::after {
            right: 8px;
        }

        #casesTable thead .sorting::before,
        #casesTable thead .sorting_asc::before,
        #casesTable thead .sorting_desc::before {
            right: 16px;
        }


        /* Specific alignments for Tags and Status columns */
        .tagsHeader, .tagsTD { /* Tags column */
            text-align: left !important;
            direction: ltr !important;
        }

        #casesTable thead th:nth-child(6) { /* Status column Header */
            text-align: center !important;
        }

        #casesTable tbody td:nth-child(6) { /* Status column Body */
            text-align: center !important;
        }

        #casesTable tbody td:nth-child(6) .sigma-case-status-badge {
            margin: 0 auto !important;
        }

        /* End Customizations */

        #casesTable {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            border-collapse: separate; /* To respect border-radius */
            border-spacing: 0;
        }

        #casesTable td {
            padding: 14px 10px; /* Adjusted padding */
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        #casesTable tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Remove zebra-striping from dataTables to use our own hover */
        #casesTable.table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Regular.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Medium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'NewYorkSmall';
            src: url('/assets/fonts/newyork/NewYorkSmall-Semibold.otf') format('opentype');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Thin.otf') format('opentype');
            font-weight: 100;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Regular.otf') format('opentype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        @font-face {
            font-family: 'SF Pro Text';
            src: url('/assets/fonts/SF-Pro/SF-Pro-Text-Medium.otf') format('opentype');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }

        .cases-datetime-picker {
            font-family: 'NewYorkSmall', 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .cases-datetime-picker * {
            font-family: inherit;
        }

        .cases-datetime-picker .ios-picker-header, .cases-datetime-picker .ios-picker-btn, .cases-datetime-picker .ios-wheel li {
            font-family: 'NewYorkSmall', 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .cases-datetime-picker .ios-picker-btn {
            font-weight: 600;
        }

        .cases-datetime-picker .ios-wheel li.selected {
            font-weight: 700;
        }

        .sigma-case-status-badge {
            width: 8.5em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 0 auto;
            line-height: 1 !important;

            vertical-align: middle;
        }

        .sigma-case-status-badge.badge-primary {
            color: #ffffff;
        }

        .sigma-case-status-badge .tooltipX {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            line-height: 1;
        }

        .sigma-case-status-badge .sigma-badge-label {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #casesTable {
            table-layout: fixed;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box;
        }

        /* Ensure table wrapper allows horizontal scroll without clipping */
        .dataTables_wrapper {
            overflow: visible;
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .cases-table-scroll {
            overflow-x: hidden;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            max-width: 100%;
            position: relative;
            z-index: 1;
        }

        .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        #casesTable_wrapper .dataTables_scrollHead table thead th {
            background-color: #408385 !important;
            color: #ffffff !important;
            border-bottom: 0 !important;
            vertical-align: middle !important;
        }

        #casesTable_wrapper .dataTables_scrollBody thead th {
            height: 0 !important;
            line-height: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            border-top: 0 !important;
            border-bottom: 0 !important;
        }

        .dataTables_wrapper .dataTables_info {
            display: none;
        }

        .dataTables_wrapper .dataTables_paginate {
            overflow-x: visible !important;
            width: 100%;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            font-size: 0.85rem;
            padding: 3px 7px;
            min-width: 26px;
            border-radius: 4px;
            border: 1px solid #cddfe2;
            background-color: #ffffff;
            color: #1f6fb2 !important;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button .page-link {
            padding: 3px 7px;
            border-radius: 4px;
            border: 1px solid #cddfe2;
            background-color: #ffffff;
            color: #1f6fb2 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            border-color: #1b5f97;
            background-color: #eef5fb;
            color: #1f6fb2 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover .page-link {
            border-color: #1b5f97;
            background-color: #eef5fb;
            color: #1f6fb2 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #dbe9f6 !important;
            border-color: #1f6fb2 !important;
            color: #1f6fb2 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover .page-link {
            background-color: #dbe9f6 !important;
            border-color: #1f6fb2 !important;
            color: #1f6fb2 !important;
        }

        /* Ensure parent container doesn't clip */
        #casesTable_wrapper {
            overflow: visible;
            width: 100%;
            max-width: 100%;
        }

        /* Hide DataTables responsive control column (green button) */
        #casesTable td.dtr-control,
        #casesTable th.dtr-control,
        #casesTable td.dtr-control:before,
        #casesTable_wrapper .dtr-control {
            display: none !important;
        }

        /* Ensure no control column is added by DataTables responsive */
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before,
        table.dataTable.dtr-inline.collapsed > tbody > tr > th.dtr-control:before {
            display: none !important;
        }

        #casesTable {
            font-family: 'Cairo', sans-serif;
        }

        #casesTable .cases-cell-truncate {
            display: inline-block;
            max-width: 16rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: bottom;
            font-weight: 400;
        }

        .cases-header-icon {
            margin-left: 6px;
            font-size: 0.92em;
            vertical-align: baseline;
        }

        #casesTable tbody td:first-child,
        #casesTable tbody td:first-child .cases-cell-truncate {
            /*font-weight: 600 !important;*/
        }


        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 42px; /* 30% smaller than 60px */
            height: 23.8px; /* 30% smaller than 34px */
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18.2px; /* 30% smaller than 26px */
            width: 18.2px; /* 30% smaller than 26px */
            left: 2.8px; /* 30% smaller than 4px */
            bottom: 2.8px; /* 30% smaller than 4px */
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #317d7f; /* New background color */
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #317d7f; /* Updated shadow color */
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(18.2px); /* 30% smaller than 26px */
            -ms-transform: translateX(18.2px);
            transform: translateX(18.2px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 23.8px; /* 30% smaller than 34px */
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .tooltip-toggle-container {
            position: absolute;
            top: 8px;
            right: 50px;
            z-index: 10;
            display: flex;
            align-items: center;
            flex-direction: row-reverse; /* Label on left */
        }

        .tooltip-toggle-container label {
            margin-right: 10px; /* Adjusted margin */
            color: #495057;
            font-weight: 600;
            white-space: nowrap; /* Prevent label from wrapping */
        }

        /* Fix modal positioning - ensure modal is not affected by parent transforms */
        .sigma-modal--cases-index-actions {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            padding: 0 !important;
            box-sizing: border-box !important;
            transform: none !important;
            -webkit-transform: none !important;
            will-change: auto !important;
            display: none;
            overflow-x: hidden;
            overflow-y: hidden;
            z-index: 9998;
            background: rgba(15, 23, 42, 0.28);
        }

        .sigma-modal--cases-index-actions.show {
            display: block !important;
        }

        .sigma-modal--cases-index-actions .modal-dialog {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            z-index: 9999 !important;
            width: min(500px, calc(100vw - 32px)) !important;
            max-width: 500px !important;
            max-height: calc(100vh - 32px) !important;
            margin: 0 !important;
            transform: translate(-50%, -50%) !important;
            -webkit-transform: translate(-50%, -50%) !important;
            will-change: auto !important;
            pointer-events: none !important;
        }

        .sigma-modal--cases-index-actions .modal-content {
            position: relative !important;
            transform: none !important;
            -webkit-transform: none !important;
            will-change: auto !important;
            pointer-events: auto !important;
            display: flex !important;
            flex-direction: column !important;
            max-height: calc(100vh - 32px) !important;
            overflow: hidden !important;
        }

        .sigma-modal--cases-index-actions .modal-content,
        .sigma-modal--cases-index-actions .modal-content h1,
        .sigma-modal--cases-index-actions .modal-content h2,
        .sigma-modal--cases-index-actions .modal-content h3,
        .sigma-modal--cases-index-actions .modal-content h4,
        .sigma-modal--cases-index-actions .modal-content h5,
        .sigma-modal--cases-index-actions .modal-content h6,
        .sigma-modal--cases-index-actions .modal-content p,
        .sigma-modal--cases-index-actions .modal-content span,
        .sigma-modal--cases-index-actions .modal-content label,
        .sigma-modal--cases-index-actions .modal-content small,
        .sigma-modal--cases-index-actions .modal-content strong,
        .sigma-modal--cases-index-actions .modal-content b,
        .sigma-modal--cases-index-actions .modal-content div,
        .sigma-modal--cases-index-actions .modal-content a,
        .sigma-modal--cases-index-actions .modal-content li,
        .sigma-modal--cases-index-actions .modal-content td,
        .sigma-modal--cases-index-actions .modal-content th,
        .sigma-modal--cases-index-actions .modal-content input,
        .sigma-modal--cases-index-actions .modal-content textarea,
        .sigma-modal--cases-index-actions .modal-content select,
        .sigma-modal--cases-index-actions .modal-content button {
            font-family: 'Cairo', sans-serif !important;
        }

        .sigma-modal--cases-index-actions .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
        }

        /* Reset transforms on wrapper when modal is open */
        body.modal-open .wrapper,
        body.modal-open .main-panel {
            transform: none !important;
            -webkit-transform: none !important;
        }

        /* Modal dialog border radius - all corners uniform */

        .sigma-modal--cases-index-actions .modal-content {
            border-radius: 25px !important;
        }

        /* Modal title styling */

        .sigma-modal--cases-index-actions .modal-title {
            color: #2d5f6d;
            font-weight: 600;
            font-size: 18px;
        }

        .badge .badge-success {
            width: 7vw !important;
        }

        /* Modal header styling with divider */

        .sigma-modal--cases-index-actions .modal-header {
            border-bottom: 0 !important;
            padding-top: 16px;
            padding-bottom: 16px;
        }

        /* Doctor/Patient names styling */
        .patient-doctor-names {
            color: #2d5f6d;
            font-weight: 600;
            font-family: 'Cairo', sans-serif;
        }

        .patient-doctor-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6c757d;
            margin-bottom: 2px;
            display: block;
        }

        .cases-patient-name {
            font-weight: 700 !important;
        }

        .sigma-modal--cases-index-actions .case-summary-block {
            margin-bottom: 14px;
        }

        .sigma-modal--cases-index-actions .case-summary-row {
            margin-bottom: 0 !important;
        }

        .sigma-modal--cases-index-actions .case-summary-divider {
            margin: 10px 0 0;
        }

        #casesTable tbody td {
            font-size: 17px !important;
        }

        /* Scrollable section for jobs and notes */
        .scrollable-content {
            max-height: 40vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 2px;
        }

        /* Notes container styling */
        .sigma-modal--cases-index-actions .modal-body .form-control.note-container {

            border: 1px solid #b9d8e0 !important;
            color: #212529 !important;
            border-radius: 10px !important;
            box-shadow: none !important;
        }

        /* Modal footer rounded bottom corners */
        .sigma-modal--cases-index-actions .modal-footer {
            padding: 0 16px 14px 14px;
            border-bottom-left-radius: 25px !important;
            border-bottom-right-radius: 25px !important;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-modal-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-row,
        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-grid {
            width: 100%;
            display: grid;
            gap: 8px;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-row--top {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-row--secondary {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-row--secondary:empty,
        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-grid:empty {
            display: none;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-actions-row--cancel {
            grid-template-columns: 1fr;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-action-btn {
            width: 100%;
            min-width: 0;
            margin: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 6px 0 !important;
            line-height: 1.5 !important;
        }

        @media (max-width: 575.98px) {
            .sigma-modal--cases-index-actions .modal-footer .sigma-action-btn .btn-icon {
                display: none !important;
            }
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-action-btn .btn-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sigma-modal--cases-index-actions .modal-footer .sigma-action-btn .btn-text {
            white-space: nowrap;
            text-align: left;
        }

        .sigma-modal--cases-index-actions .case-jobs-section {
            margin-bottom: 8px !important;
            margin-top: 8px !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            width: 100%;
        }

        .sigma-modal--cases-index-actions .case-jobs-section > .col-12 {
            padding: 0 0;
        }
        .case-jobs-section  {
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
        .form-group.case-summary-row{
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
        .case-jobs-section > .form-group.col-6{
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .sigma-modal--cases-index-actions .case-jobs-label {
            display: block;
            margin-bottom: 8px !important;
            padding: 0;
            border-radius: 0;
            background: transparent;
            color: #4d626d;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.08em;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-jobs-list {
            display: flex !important;
            flex-direction: column !important;
            gap: 7px !important;
            width: 100%;
            margin: 0 !important;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row {

            background: linear-gradient(180deg, #e6f4f7 0%, #f2fbfd 100%) !important;
            background-color: #e6f4f7 !important;
            display: flex !important;
            align-items: flex-start !important;
            width: 100%;
            min-width: 0;
            padding: 8px 11px !important;
            margin: 0 !important;
            border: 1px solid #d7e1e6;
            border-radius: 10px;

            color: #294450;
            font-size: 13px;
            line-height: 1.45 !important;
            overflow: hidden !important;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
            box-shadow: 0 2px 5px rgba(41, 68, 80, 0.07) !important;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-row::-webkit-scrollbar {
            display: none;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-primary {
            align-items: baseline;
            display: flex;
            gap: 5px;
            width: 100%;
            color: #294450;
            font-size: 15px !important;
            font-weight: 600;
            line-height: 1.45 !important;
            min-width: 0;
            white-space: normal !important;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-units {
            flex: 0 0 auto;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-separator {
            flex: 0 0 auto;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-details {
            flex: 1 1 auto;
            min-width: 0;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .sigma-modal--cases-index-actions .case-notes-section {
            margin-top: 10px;
            padding: 0;
            border: 0;
            border-radius: 0;
            background: transparent;
        }

        .sigma-modal--cases-index-actions .case-notes-label {
            display: block;
            margin-bottom: 8px;
            color: #5c6f7a;
            font-size: 11px !important;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .sigma-modal--cases-index-actions .modal-body .sigma-case-job-extra {
            display: block;
            margin-top: 2px !important;
        }

        .content {

        }

        /* Tooltip styling */
        .case-jobs-tooltip {
            display: none;
            position: absolute;
            background-color: rgba(255, 255, 255, 0.98); /* Slightly transparent white */
            border: 1px solid #e0e0e0;
            padding: 12px; /* Increased padding a bit for a less cramped feel */
            z-index: 1000;
            width: max-content;
            min-width: 340px;
            max-width: calc(100vw - 32px);
            overflow-x: auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); /* Softer, more pronounced shadow */
            border-radius: 10px; /* Smoother radius */
            font-size: 14px; /* A more readable base font size */
            color: #2c3e50;
            backdrop-filter: blur(5px); /* Frosted glass effect */
            -webkit-backdrop-filter: blur(5px);
        }

        .case-jobs-tooltip table {
            width: max-content;
            min-width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .case-jobs-tooltip th, .case-jobs-tooltip td {
            border: none; /* No cell borders */
            padding: 10px 12px; /* More generous padding */
            text-align: left;
            border-bottom: 1px solid #ecf0f1; /* Light row separator */
            white-space: nowrap;
        }

        .case-jobs-tooltip th {
            background-color: #408385;
            font-weight: 600;
            font-size: 13px;
            color: #ffffff !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .case-jobs-tooltip tr:last-child td {
            border-bottom: none; /* No border for the last row */
        }

        .case-jobs-tooltip tr:hover {
            background-color: #f8f9fa; /* Very subtle hover effect */
        }

        /* Button improvements */
        .btn-outline-danger, .btn-outline-secondary {
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        /* Report-standard filter section */
.cases-filter-card.container.full-width {
    background: #ffffff !important;
    border: 1px solid rgba(188, 206, 216, 0.3);
    border-radius: 0 16px;
    box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
    margin-top: 0;
    margin-bottom: 24px;
    padding: 20px 20px 16px !important;
    position: relative;
    overflow: hidden !important;
    backdrop-filter: none !important;
        }

        .cases-filter-card.container.full-width::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
            border-radius: 16px 16px 0 0;
        }

        .cases-filter-row {
            --cases-filter-height: 38px;
            --cases-filter-font-size: 14px;
            --cases-filter-color: #243746;
            --cases-filter-gap: 12px;
            --cases-filter-control-width: 220px;
            --cases-filter-button-pad-y: 8px;
            --cases-filter-button-pad-x: calc(var(--cases-filter-button-pad-y) * 3.625);
            --cases-filter-radius: 10px;
            --cases-filter-border: 1px solid rgba(188, 206, 216, 0.4);
            --cases-filter-padding: 8px 14px;
            padding: 0 !important;
            margin: 0 -8px !important;
            align-items: flex-end;
            font-family: "Tajawal", "Cairo", "Noto Sans Arabic", "Segoe UI", Tahoma, sans-serif;
        }

        .cases-filter-row > [class*="col-"] {
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        .cases-filter-row .mb-2 {
            margin-bottom: 12px !important;
        }

        .cases-filter-row .filter-label {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #243746;
            letter-spacing: 0.01em;
            font-family: "Tajawal", "Cairo", "Noto Sans Arabic", "Segoe UI", Tahoma, sans-serif;
        }

        .cases-filter-row .filter-label i {
            color: #2b7b7d;
            font-size: 13px;
        }

        .cases-filter-row .form-control,
        .cases-filter-row .dtp-input,
        .cases-filter-row .bootstrap-select > .dropdown-toggle,
        .cases-filter-row .ios-dtp-trigger {
            min-height: var(--cases-filter-height) !important;
            height: var(--cases-filter-height) !important;
            font-size: var(--cases-filter-font-size) !important;
            padding: var(--cases-filter-padding) !important;
            border-radius: var(--cases-filter-radius);
            border: var(--cases-filter-border) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: none !important;
            font-weight: 500;
            line-height: 1.5;
            transition: all 0.3s ease;
        }

        .cases-filter-row .bootstrap-select,
        .cases-filter-row .ios-dtp-container,
        .cases-filter-row .filter-input-global,
        .cases-filter-row input.filter-input-global,
        .cases-filter-row select.filter-input-global,
        .cases-filter-row .filter-input-global .ios-dtp-trigger,
        .cases-filter-row select.filter-input-global + .bootstrap-select,
        .cases-filter-row select.filter-input-global + .bootstrap-select > .dropdown-toggle {
            width: 100% !important;
            max-width: 100% !important;
        }

        .cases-filter-row .bootstrap-select .filter-option-inner-inner,
        .cases-filter-row .ios-dtp-display {
            font-size: var(--cases-filter-font-size) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            font-weight: 500;
        }

        .cases-filter-row .form-control::placeholder,
        .cases-filter-row .bootstrap-select > .dropdown-toggle.bs-placeholder .filter-option-inner-inner {
            color: #6b7280 !important;
            opacity: 1;
        }

        .cases-filter-row .bootstrap-select > .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .cases-filter-row .form-control:focus,
        .cases-filter-row .dtp-input:focus,
        .cases-filter-row .bootstrap-select > .dropdown-toggle:focus,
        .cases-filter-row .ios-dtp-trigger:focus {
            border-color: #408385 !important;
            box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
            outline: 0;
        }

        .cases-filter-btn {
            width: auto;
            max-width: none;
            min-height: var(--cases-filter-height);
            height: auto;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: var(--cases-filter-button-pad-y) var(--cases-filter-button-pad-x) !important;
            border-radius: 12px !important;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
            line-height: 1.2;
            transition: all 0.3s ease;
        }

        .cases-filter-btn--search {
            min-width: 136px;
            background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
            background-color: #4d9597 !important;
            border: 1px solid #408385 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
        }

        .cases-filter-btn--search:hover,
        .cases-filter-btn--search:focus {
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            background-color: #4a8d90 !important;
            border-color: #336f71 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
        }

        .cases-filter-btn--search:active,
        .cases-filter-btn--search:not(:disabled):not(.disabled):active {
            background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
            background-color: #3d7678 !important;
            border-color: #285f61 !important;
            color: #ffffff !important;
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2);
        }

        .cases-filter-btn--trash {
            min-width: auto;
            width: auto;
            height: var(--cases-filter-height) !important;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            color: #dc3545 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
        }

        .cases-filter-btn--trash:hover,
        .cases-filter-btn--trash:focus {
            background: transparent !important;
            box-shadow: none !important;
            color: #bb2d3b !important;
            transform: none !important;
        }

        .cases-filter-btn--trash i {
            font-size: 18px;
            line-height: 1;
        }

        .cases-filter-row .cases-filter-actions-col {
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding-left: 8px !important;
        }

        .cases-filter-row .sigma-filter-secondary-col {
            justify-content: flex-end;
            padding-right: 0 !important;
        }

        @media screen and (min-width: 768px) {
            .cases-filter-row > [class*="col-"] {
                flex: 0 0 var(--cases-filter-control-width) !important;
                width: var(--cases-filter-control-width) !important;
                max-width: var(--cases-filter-control-width) !important;
            }

            .cases-filter-row .cases-filter-actions-col:not(.sigma-filter-secondary-col) {
                margin-left: 0 !important;
                flex: 0 0 auto !important;
                width: auto !important;
                max-width: none !important;
            }

            .cases-filter-row .cases-filter-actions-col--trash {
                margin-left: auto !important;
                flex: 0 0 64px !important;
                width: 64px !important;
                max-width: 64px !important;
            }
        }

        @media screen and (min-width: 576px) and (max-width: 767px) {
            .cases-filter-row > .col-sm-4 {
                flex: 0 0 33.333333% !important;
                width: 33.333333% !important;
                max-width: 33.333333% !important;
            }

            .cases-filter-row > .col-sm-6 {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
            }
        }

        @media screen and (max-width: 575px) {
            .cases-filter-row > .col-6.col-sm-4.col-md-2 {
                flex: 0 0 33.333333% !important;
                width: 33.333333% !important;
                max-width: 33.333333% !important;
            }

            .cases-filter-row > .col-6.col-sm-6.col-md-3,
            .cases-filter-row > .col-6.col-sm-6.col-md-2.cases-filter-actions-col,
            .cases-filter-row > .col-6.col-sm-6.col-md-1.cases-filter-actions-col--trash {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
            }
        }

        @media screen and (max-width: 599px) {
            .cases-filter-search-col .filter-label {
                display: none !important;
            }

            .cases-filter-search-col .form-control {
                margin-top: 0 !important;
            }

            .cases-filter-search-col {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
            }

            .cases-filter-apply-col {
                flex: 0 0 33.333333% !important;
                width: 33.333333% !important;
                max-width: 33.333333% !important;
            }

            .cases-filter-trash-col {
                flex: 0 0 16.666667% !important;
                width: 16.666667% !important;
                max-width: 16.666667% !important;
                padding-left: 4px !important;
            }

            .cases-filter-row .cases-filter-trash-col {
                justify-content: flex-start !important;
                align-items: flex-end !important;
            }
        }

        .cases-table-shell {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
            position: relative;
            z-index: 1;
        }

        .cases-table-shell .row,
        .cases-table-shell .col-12,
        .cases-table-scroll {
            background: transparent !important;
        }

        .sunriseTable tbody tr td {
            padding: 4px 0 !important;
        }

        /*.case-row{font-weight: 400 !important;}*/

        /* Better spacing */
        .filter-section {
            margin-bottom: 0;
            height: 12px;
            background: #f4f6fb;
        }

        /* Button groups styling */
        .btn-group .btn {
            margin-left: 5px;
        }

        div.modal-content {
            padding: 16px 14px;
        }

        /* Table actions styling */
        .table-actions {
            margin-bottom: 15px;
        }

        .dropdown-menu li a {
            padding: 0 20px 4px 12px !important;
        }

        /* Responsive adjustments */
        @media screen and (max-width: 991px) {
            #casesTable {
                min-width: 920px !important;
                width: 100% !important;
                table-layout: fixed !important;
                font-size: 12px !important;
            }

            /* Match header and body font size on mobile */
            #casesTable thead th,
            #casesTable tbody td {
                font-size: 17px !important;
                padding: 6px 4px !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Column width distribution for mobile */
            #casesTable thead th:nth-child(1),
            #casesTable tbody td:nth-child(1) {
                width: 150px;
            }

            /* Doctor */
            #casesTable thead th:nth-child(2),
            #casesTable tbody td:nth-child(2) {
                width: 170px;
            }

            /* Patient */
            #casesTable thead th:nth-child(3),
            #casesTable tbody td:nth-child(3) {
                width: 150px;
            }

            /* Initial Deli Date */
            #casesTable thead th:nth-child(4),
            #casesTable tbody td:nth-child(4) {
                width: 140px;
            }

            /* Date Delivered */
            #casesTable thead th:nth-child(5),
            #casesTable tbody td:nth-child(5) {
                width: 70px;
            }

            /* Status */
            #casesTable thead th:nth-child(6),
            #casesTable tbody td:nth-child(6) {
                width: 140px;
            }

            /* Tags */
            #casesTable thead th:nth-child(7),
            #casesTable tbody td:nth-child(7) {
                width: 100px;
            }

            .tooltip-toggle-container {
                display: none;
            }

            .tooltipX .tooltiptext,
            .case-jobs-tooltip {
                display: none !important;
            }

            .content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            .row {
                padding: 3px;
            }

            /* Allow horizontal scroll on small screens */
            .dataTables_wrapper {
                overflow: visible !important;
                width: 100%;
            }

            .cases-table-scroll {
                overflow-x: visible;
                overflow-y: visible;
                -webkit-overflow-scrolling: touch;
                width: 100%;
                max-width: 100%;
            }

            .pagination {
                flex-wrap: wrap;
            }

            /* Better button display on mobile */
            .btn-primary {
                width: 100%;

            }

            .cases-filter-row .cases-filter-actions-col {
                display: flex;
                align-items: flex-end;
            }

            .cases-filter-row .cases-filter-actions-col--trash {
                justify-content: flex-end;
            }

            /* Make action buttons more visible on mobile */
            .btn-sm {
                padding: 0.375rem 0.75rem;
                font-size: 1rem;
            }

            .bootstrap-select ul.dropdown-menu li:first-child {
                display: none;
            }

            /* Doctor picker uses `container: body`; mobile viewport fitting is handled in JS. */
            .bs-container.bootstrap-select.clearOnAll.filter-input-global > .dropdown-menu {
                min-width: 260px !important;
            }

            .bs-container.bootstrap-select.clearOnAll.filter-input-global .dropdown-menu.inner li a {
                display: flex !important;
                justify-content: flex-end !important;
                padding: 6px 60px 6px 16px !important;
                position: relative;
                text-align: right !important;
                direction: rtl !important;
            }

            .bs-container.bootstrap-select.clearOnAll.filter-input-global .dropdown-menu.inner li a .check-mark {
                left: auto !important;
                right: 16px !important;
            }

            .bs-container.bootstrap-select.clearOnAll.filter-input-global .dropdown-menu.inner li a .text {
                flex: 1 1 auto;
                min-width: 0;
                width: auto;
                max-width: 100%;
                margin-right: 0 !important;
                padding-right: 8px;
                overflow: visible !important;
                text-align: right !important;
                white-space: nowrap;
            }

            .dataTables_wrapper .dataTables_filter {
                text-align: center;
            }

            #casesTable .cases-cell-truncate {
                max-width: 100%;
                display: block;
            }

            /* Responsive button group on mobile */
            .btn-group {
                display: flex;
                width: 100%;
            }

            .btn-group .btn {
                flex: 1;
                margin-left: 2px;
                margin-right: 2px;
            }

            /* Smaller status badges on mobile */
            .sigma-case-status-badge {

                font-size: 11px !important;
                line-height: 1.2 !important;
            }
        }

        @media screen and (max-width: 767px) {
            .cases-filter-card.container.full-width {

                margin-bottom: 10px !important;
                padding: 10px 10px 8px !important;
            }

            .cases-filter-row {
                margin: 0 -4px !important;
                row-gap: 6px !important;
            }

            .cases-filter-row > [class*="col-"] {
                padding-left: 4px !important;
                padding-right: 4px !important;
            }

            .cases-filter-row .mb-2 {
                margin-bottom: 6px !important;
            }

            .cases-filter-row .filter-label {
                margin-bottom: 4px !important;
                font-size: 11px !important;
            }

            .cases-filter-row .form-control,
            .cases-filter-row .dtp-input,
            .cases-filter-row .bootstrap-select > .dropdown-toggle,
            .cases-filter-row .ios-dtp-trigger {
                min-height: 34px !important;
                height: 34px !important;
                padding: 6px 10px !important;
                font-size: 12px !important;
            }

            @supports (-webkit-touch-callout: none) {
                .cases-filter-row .form-control,
                .cases-filter-row .dtp-input,
                .cases-filter-row .bootstrap-select > .dropdown-toggle,
                .cases-filter-row .ios-dtp-trigger {
                    font-size: 16px !important;
                }
            }

            .cases-filter-btn {
                min-height: 34px !important;
                height: 34px !important;
                padding: 0 16px !important;
            }

            .cases-filter-row .cases-filter-actions-col .cases-filter-btn {
                width: 100% !important;
                min-width: 0 !important;
            }

            .cases-filter-btn--trash {
                height: 34px !important;
            }

            .cases-filter-trash-col {
                display: none !important;
            }

            .filter-section {
                display: none !important;
            }
        }

    </style>
    @php
        $permissions = safe_permissions();

    @endphp
    @if(!isset($isSearchResults))
        @php
            if (isset($trashedCases)) {
                $casesFiltersAction = route('deleted-cases');
            } elseif (isset($clients)) {
                $casesFiltersAction = route('cases-index');
            } else {
                $casesFiltersAction = route('dentist-cases', ['id' => $id]);
            }
        @endphp
        <form class="kt-form sigma-sticky-toolbar" method="GET" action="{{ $casesFiltersAction }}">
            @if(!isset($trashedCases) && !isset($clients))
                <input type="hidden" class="form-control" name="id" value="{{$id}}">
            @endif
            <div class="container full-width cases-filter-card">
                <div class="row cases-filter-row">
                    <!-- Date filtering section -->
                    <div class="col-6 col-sm-4 col-md-2 mb-2">
                        <label class="form-label filter-label" for="cases_from">
                            <i class="fa-regular fa-calendar"></i>
                            <span>From Date</span>
                        </label>
                        <x-ios-dtp
                                name="from"
                                id="cases_from"
                                class="filter-input-global"
                                :value="$from"
                                mode="date"
                        />
                    </div>
                    <div class="col-6 col-sm-4 col-md-2 mb-2">
                        <label class="form-label filter-label" for="cases_to">
                            <i class="fa-regular fa-calendar"></i>
                            <span>To Date</span>
                        </label>
                        <x-ios-dtp
                                name="to"
                                id="cases_to"
                                class="filter-input-global"
                                :value="$to"
                                mode="date"
                        />
                    </div>

                    <!-- Doctor selection -->
                    @if(isset($clients))
                        <div class="col-6 col-sm-4 col-md-2 mb-2">
                            <label class="form-label filter-label" for="doctor">
                                <i class="fas fa-user-md"></i>
                                <span>Doctor</span>
                            </label>
                            <select class="selectpicker clearOnAll greyBG filter-input-global"
                                    multiple
                                    name="doctor[]" id="doctor"
                                    data-live-search="true"
                                    data-container="body"
                                    title="All Doctors">
                                <option value="all" {{(isset($selectedClients) && in_array("all" ,$selectedClients)) ? 'selected' : ''}}>
                                    All
                                </option>
                                @foreach($clients as $d)
                                    <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Search and Apply -->
                    <div class="col-6 col-sm-6 col-md-3 mb-2 cases-filter-search-col">
                        <label class="form-label filter-label" for="tableSearch">
                            <i class="fas fa-search"></i>
                            <span>Search Cases</span>
                        </label>
                        <input type="text" class="form-control filter-input-global" id="tableSearch" placeholder="Search...">
                    </div>

                    <div class="col-6 col-sm-6 col-md-2 mb-2 cases-filter-actions-col cases-filter-apply-col">
                        <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search filter-apply-btn-global">
                            <i class="fas fa-search"></i>
                            <span>Apply</span>
                        </button>
                    </div>

                    <!-- Trash button -->
                    <div class="col-2 col-sm-2 col-md-1 mb-2 cases-filter-actions-col cases-filter-actions-col--trash cases-filter-trash-col sigma-filter-secondary-col">
                        <a href="{{route('deleted-cases')}}" class="cases-filter-btn--trash"
                           title="View Deleted Cases">
                            <i class="fa-regular fa-trash-can"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endif
    <div class="container full-width cases-table-shell">
        <div class="row" >
            <div class="col-12" style="padding:0">
                <br>
                <div class="cases-table-scroll">
                    <table id="casesTable"
                           class="table-striped compact sunriseTable sigma-no-header-radius"
                           role="grid"
                           style="width:100%">
                    <thead>
                    <tr role="row">
                        <th class="sigma-sticky-container sigma-col-shaded" style="text-align:left !important;padding-left:16px !important;">Doctor
                        </th>
                        <th class="sigma-sticky-container sigma-head-left">Patient</th>
                        <th class="initDeliDateHeader sigma-sticky-container">Initial Delivery <i class="fa-regular fa-calendar cases-header-icon" aria-hidden="true"></i></th>
                        <th class="sigma-sticky-container sigma-col-shaded">Actual Delivery <i class="fa-regular fa-calendar cases-header-icon" aria-hidden="true"></i></th>
                        <th class="sigma-sticky-container">Units</th>
                        <th class="sigma-sticky-container">Status</th>
                        <th class="tagsHeader sigma-sticky-container">Tags</th>

                    </tr>
                    </thead>

                    <tbody>

                    @foreach($cases  as $case)
                        @php
                            // Check if case is in-progress and initial_delivery_date has passed
                            $caseStatus = (string) $case->status();
                            $isOverdue = false;
                            if (!$case->actual_delivery_date && $case->initial_delivery_date) {
                                $now = \Carbon\Carbon::now();
                                $deliveryDate = \Carbon\Carbon::parse($case->initial_delivery_date);
                                $isOverdue = $deliveryDate->lt($now);
                            }
                            $rowStyle = $isOverdue ? 'color: #dc3545; font-weight: 400;' : 'font-weight: 400;';
                        @endphp

                        <tr role="row" class="odd clickable case-row" data-toggle="modal"
                            data-target="#actionsDialog" style="{{$rowStyle}}"
                            data-case-id="{{$case->id}}">
                            <td class="sigma-col-shaded sigma-body-left"><span class="cases-cell-truncate">{{$case->client->name ?? "x"}}</span></td>
                            <td class="sigma-body-left"><span class="cases-cell-truncate cases-patient-name">{{$case->patient_name ?? "x"}}</span></td>
                            <td class="initDeliDateTD sigma-body-center">{{$case->initDeliveryDate() ?? "x" }}
                                &nbsp;&nbsp; {{$case->initDeliveryTime() ?? "Unavailable"}}</td>
                            <td class="sigma-col-shaded sigma-body-center">{{$case->actualDeliveryDate()=="" ? "Not yet" : $case->actualDeliveryDate()}}
                                &nbsp;&nbsp; {{$case->actualDeliveryTime() ?? ""}}</td>
                            <td class="sigma-body-center">
                                @php
                                    $totalUnits = 0;
                                    foreach($case->jobs as $job) {
                                        // Count only if the material is flagged as countable
                                        if ($job->material && $job->material->count_as_unit && !empty($job->unit_num)) {
                                            // Split on commas or whitespace, trim, and drop empties
                                            $units = preg_split('/\s*,\s*|\s+/', trim($job->unit_num));
                                            $units = array_filter($units, function ($value) {
                                                return $value !== '';
                                            });
                                            $totalUnits += count($units);
                                        }
                                    }
                                @endphp
                                <span class="cases-cell-truncate">{{ $totalUnits }}</span>
                            </td>
                            <td class="sigma-body-center">
                                @if(str_contains($caseStatus, "Completed") )
                                    <span class="badge badge-success sigma-case-status-badge sigma-status-width">
                                                        <span class="sigma-badge-label">{{ $caseStatus }}</span>
                                                    </span>
                                @elseif(str_contains($caseStatus, "In-Progress") || str_contains($caseStatus, "Active"))
                                    @php
                                        $rawStatus = trim($caseStatus);

                                        // خذ فقط ما بعد "Active in" أو "In-Progress in"
                                        $stageText = $rawStatus;
                                        if (Str::contains($rawStatus, 'Active in')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                                        }

                                        // استخراج المرحلة والموظف
                                        $assigneeInitials = '';
                                        $jobAtStage = $case->jobs->first(function ($job) use ($case, $stageText) {
                                            return $job->assignee !== null && trim($case->stageToText((string) $job->stage)) === $stageText;
                                        });

                                        if (!$jobAtStage) {
                                            $jobAtStage = $case->jobs->first(function ($job) {
                                                return $job->assignee !== null && (string) $job->stage !== '-1';
                                            });
                                        }

                                        if ($jobAtStage && $jobAtStage->assignedTo) {
                                            $assigneeInitials = trim((string) (
                                                $jobAtStage->assignedTo->name_initials
                                                ?? $jobAtStage->assignedTo->first_name
                                                ?? ''
                                            ));
                                        }

                                        if (in_array($stageText, ['In-Progress', 'Active', ''], true) && $jobAtStage) {
                                            $stageText = trim($case->stageToText((string) $jobAtStage->stage));
                                        }

                                        $formattedStatus = $assigneeInitials !== ''
                                            ? (trim($stageText) . '/ ' . $assigneeInitials)
                                            : trim($stageText);
                                    @endphp

                                    <span class="badge badge-primary sigma-case-status-badge sigma-status-width">
                                                                            <span class="tooltipX">
                                                                                <span class="sigma-badge-label">{{ $formattedStatus }}</span>

                                                                </span>
                                                    </span>
                                @elseif(str_contains($caseStatus, "Waiting"))
                                    <span class="badge badge-danger sigma-case-status-badge sigma-status-width">
                                                                @php
                                                                    $status =  preg_replace('/' . "in" . '/', "", str_replace("Waiting","",$caseStatus), 1);
                                                                @endphp

                                                        <span class="sigma-badge-label">{{ trim($status) }}</span>
                                                    </span>
                                @else
                                    @php
                                        $isDeliveryAssigned = $case->jobs[0]->stage == 8 && $case->jobs[0]->assignee != null && $case->jobs[0]->delivery_accepted == null;

                                        // Format delivery assigned badge as "Deli/ [initials]"
                                        $deliveryBadgeText = $caseStatus;
                                        if ($isDeliveryAssigned && $case->jobs[0]->assignedTo) {
                                            $employeeInitials = trim((string) (
                                                $case->jobs[0]->assignedTo->name_initials
                                                ?? $case->jobs[0]->assignedTo->first_name
                                                ?? ''
                                            ));
                                            $deliveryBadgeText = 'Delivery/ ' . $employeeInitials;
                                        }
                                    @endphp
                                    <span class="badge badge-warning sigma-case-status-badge sigma-status-width">
                                                        @if($isDeliveryAssigned)
                                            <span class="sigma-badge-label">{{ $deliveryBadgeText }}</span>
                                        @else
                                            <span class="tooltipX">
                                                                <span class="sigma-badge-label">{{ $caseStatus }}</span>
                                                                <span class="tooltiptext">{!! $case->getStatusToolTipHTML() !!}</span>
                                                            </span>
                                        @endif
                                                    </span>
                                @endif

                            </td>
                            <td class="tagsTD sigma-body-left">

                                @foreach($case->tags as $tag)
                                    @if(isset($tag->originalTagRecord))
                                        <i title="{{$tag->originalTagRecord->text}}"
                                           style="color:{{$tag->originalTagRecord->color}}"
                                           class="{{$tag->originalTagRecord->icon}}  fa-lg"></i>
                                    @endif
                                @endforeach
                            </td>


                        </tr>

                    @endforeach
                    </tbody>

                    </table>
                </div>

                @php
                    $caseTooltipJobs = $cases->mapWithKeys(function ($case) {
                        $currentStage = optional($case->jobs->first())->stage;

                        $jobs = $case->jobs->filter(function ($job) use ($currentStage) {
                            return $job->goesThroughStage($currentStage);
                        })->map(function ($job) {
                            return [
                                'type' => $job->jobType->name ?? 'No Job Type',
                                'material' => $job->material->name ?? 'no material',
                                'units' => $job->unit_num,
                            ];
                        })->values();

                        return [$case->id => $jobs];
                    });
                @endphp

                <script id="cases-tooltip-data" type="application/json">@json($caseTooltipJobs)</script>

                <div class="modal sigma-modal--cases-index-actions" tabindex="-1" role="dialog"
                     id="actionsDialog" data-backdrop="false" data-keyboard="true">
                    <input type="hidden" name="case_id" value="">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="text-center py-4">Loading case details...</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div style="text-align:right">

            </div>
        </div>
    </div>

    </div>
    @push('js')
        @if(request()->boolean('__benchmark'))
            <script>
                window.sigmaPageBenchmark = ( function () {
                    var startedAt = (window.performance && typeof performance.now === 'function')
                        ? performance.now()
                        : Date.now();
                    var marks = [];
                    var flushed = false;

                    function now() {
                        return (window.performance && typeof performance.now === 'function')
                            ? performance.now()
                            : Date.now();
                    }

                    function mark(label, extra) {
                        var current = now();
                        marks.push( Object.assign( {
                                                      label: label,
                                                      elapsedMs: Number( current - startedAt ).toFixed( 2 )
                                                  }, extra || {} ) );
                    }

                    function navigation() {
                        if (!window.performance || typeof performance.getEntriesByType !== 'function') {
                            return null;
                        }

                        var nav = performance.getEntriesByType( 'navigation' )[0];
                        if (!nav) {
                            return null;
                        }

                        return {
                            requestMs: Number( nav.responseStart - nav.requestStart ).toFixed( 2 ),
                            responseMs: Number( nav.responseEnd - nav.responseStart ).toFixed( 2 ),
                            domInteractiveMs: Number( nav.domInteractive ).toFixed( 2 ),
                            domContentLoadedMs: Number( nav.domContentLoadedEventEnd ).toFixed( 2 ),
                            loadEventMs: Number( nav.loadEventEnd ).toFixed( 2 ),
                            decodedBodySize: nav.decodedBodySize || 0,
                            transferSize: nav.transferSize || 0
                        };
                    }

                    function domCounts() {
                        return {
                            nodes: document.getElementsByTagName( '*' ).length,
                            caseRows: document.querySelectorAll( '#casesTable tbody tr' ).length,
                            modals: document.querySelectorAll( '.sigma-modal--cases-index-actions' ).length,
                            scripts: document.querySelectorAll( 'script[src]' ).length,
                            stylesheets: document.querySelectorAll( 'link[rel=\"stylesheet\"]' ).length
                        };
                    }

                    function flush(reason) {
                        if (flushed) {
                            return;
                        }

                        flushed = true;

                        console.log(
                            '[page-benchmark] cases client',
                            JSON.stringify( {
                                                reason: reason,
                                                marks: marks,
                                                navigation: navigation(),
                                                dom: domCounts()
                                            } )
                        );
                    }

                    mark( 'client.bootstrap' );

                    document.addEventListener( 'DOMContentLoaded' , function () {
                        mark( 'client.dom-content-loaded' );
                    } );

                    window.addEventListener( 'load' , function () {
                        mark( 'client.window-load' );
                        window.requestAnimationFrame( function () {
                            mark( 'client.window-load.next-frame' );
                            flush( 'window.load' );
                        } );
                    } );

                    return {
                        mark: mark,
                        flush: flush
                    };
                } )();
            </script>
        @endif
        <style>
            .case-jobs-tooltip {
                display: none;
                position: absolute;
                background-color: #ffffff; /* White background for clean look */
                border: 1px solid #e3e3e3; /* Light border */
                padding: 8px; /* Reduced padding */
                z-index: 1000;
                width: max-content;
                min-width: 320px;
                max-width: calc(100vw - 32px);
                overflow-x: auto;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Softer, larger shadow */
                border-radius: 8px; /* Increased corner radius */
                font-size: 13px; /* Smaller font for compactness */
                color: #333;
            }

            .case-jobs-tooltip table {
                width: max-content;
                min-width: 100%;
                border-collapse: collapse;
                margin-top: 0; /* Remove extra space */
                margin-bottom: 0; /* Remove extra space */
            }

            .case-jobs-tooltip th, .case-jobs-tooltip td {
                border: 1px solid #eee; /* Light borders for cells */
                padding: 6px 8px; /* Compact padding */
                text-align: left;
                white-space: nowrap;
            }

            .case-jobs-tooltip th {
                background-color: #408385;
                font-weight: 700; /* Bolder header */
                color: #ffffff !important;
                text-transform: uppercase;
            }

            .case-jobs-tooltip tr:nth-child(even) {
                background-color: #fdfdfd; /* Light stripe */
            }

            .case-jobs-tooltip tr:hover {
                background-color: #f0f8ff; /* Subtle hover effect */
            }
        </style>
        <style>
            /* Scoped iOS-style date picker */
            .ios-picker-wrap {
                position: relative;
                width: 100%;
            }

            .ios-picker-panel {
                position: absolute;
                top: calc(100% + 8px);
                left: 0;
                z-index: 10;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
                width: 280px;
                padding: 12px;
                display: none;
            }

            .ios-picker-panel.open {
                display: block;
            }

            .ios-picker-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
                font-weight: 600;
                color: #0f172a;
            }

            .ios-picker-actions {
                display: flex;
                gap: 8px;
            }

            .ios-picker-btn {
                padding: 6px 10px;
                border-radius: 8px;
                border: 1px solid #d0d5dd;
                background: #fff;
                color: #111827;
                font-weight: 600;
                cursor: pointer;
                transition: background 0.2s ease, border-color 0.2s ease;
            }

            .ios-picker-btn.primary {
                background: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }

            .ios-picker-btn:hover {
                background: #f9fafb;
            }

            .ios-picker-btn.primary:hover {
                background: #1d4ed8;
            }

            .ios-wheels {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px;
                position: relative;
                height: 180px;
            }

            .ios-wheel {
                position: relative;
                height: 100%;
                overflow-y: scroll;
                scroll-snap-type: y mandatory;
                -webkit-overflow-scrolling: touch;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
            }

            .ios-wheel::-webkit-scrollbar {
                display: none;
            }

            .ios-wheel ul {
                list-style: none;
                padding: 70px 0;
                margin: 0;
                text-align: center;
            }

            .ios-wheel li {
                height: 36px;
                line-height: 36px;
                scroll-snap-align: center;
                color: #6b7280;
                font-weight: 500;
            }

            .ios-wheel li.selected {
                color: #111827;
                font-size: 16px;
                font-weight: 700;
            }

            .ios-highlight {
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 36px;
                margin-top: -18px;
                border-top: 1px solid #dbeafe;
                border-bottom: 1px solid #dbeafe;
                pointer-events: none;
                background: linear-gradient(90deg, rgba(37, 99, 235, 0.08), rgba(37, 99, 235, 0.02), rgba(37, 99, 235, 0.08));
            }
        </style>
        <script>
            (function () {
                const ITEM_HEIGHT = 36;

                function formatDisplay(dateStr) {
                    if (!dateStr) return 'Select date';
                    const parts = dateStr.split( '-' );
                    if (parts.length !== 3) return 'Select date';
                    const d = new Date( dateStr + 'T00:00:00' );
                    return d.toLocaleDateString( 'en-US' , {year: 'numeric' , month: 'long' , day: 'numeric'} );
                }

                function buildWheel(list , values , selected) {
                    list.innerHTML = '';
                    values.forEach( v => {
                        const li = document.createElement( 'li' );
                        li.textContent = v.label;
                        li.dataset.value = v.value;
                        if (v.value === selected) li.classList.add( 'selected' );
                        list.appendChild( li );
                    } );
                }

                function snap(wheel) {
                    const idx = Math.round( wheel.scrollTop / ITEM_HEIGHT );
                    const target = idx * ITEM_HEIGHT;
                    wheel.scrollTo( {top: target , behavior: 'auto'} );
                    wheel.querySelectorAll( 'li' ).forEach( (li , i) => {
                        li.classList.toggle( 'selected' , i === idx );
                    } );
                }

                function initPicker(host) {
                    const name = host.dataset.name;
                    const initial = host.dataset.initial || '';

                    const wrapper = document.createElement( 'div' );
                    wrapper.className = 'ios-picker-wrap';

                    const input = document.createElement( 'input' );
                    input.type = 'hidden';
                    input.name = name;
                    input.value = initial;

                    const display = document.createElement( 'button' );
                    display.type = 'button';
                    display.className = 'form-control';
                    display.textContent = formatDisplay( initial );

                    const panel = document.createElement( 'div' );
                    panel.className = 'ios-picker-panel';
                    panel.innerHTML = `
                                    <div class="ios-picker-header">
                                        <span>Select date</span>
                                        <div class="ios-picker-actions">
                                            <button type="button" class="ios-picker-btn js-cancel">Cancel</button>
                                            <button type="button" class="ios-picker-btn primary js-done">Done</button>
                                        </div>
                                    </div>
                                    <div class="ios-wheels">
                                        <div class="ios-wheel js-wheel-year"><div class="ios-highlight"></div><ul></ul></div>
                                        <div class="ios-wheel js-wheel-month"><div class="ios-highlight"></div><ul></ul></div>
                                        <div class="ios-wheel js-wheel-day"><div class="ios-highlight"></div><ul></ul></div>
                                    </div>
                                `;

                    wrapper.appendChild( input );
                    wrapper.appendChild( display );
                    wrapper.appendChild( panel );
                    host.replaceWith( wrapper );

                    const yearWheel = panel.querySelector( '.js-wheel-year' );
                    const monthWheel = panel.querySelector( '.js-wheel-month' );
                    const dayWheel = panel.querySelector( '.js-wheel-day' );
                    const yearList = yearWheel.querySelector( 'ul' );
                    const monthList = monthWheel.querySelector( 'ul' );
                    const dayList = dayWheel.querySelector( 'ul' );

                    const today = initial ? new Date( initial + 'T00:00:00' ) : new Date();
                    let selYear = today.getFullYear();
                    let selMonth = today.getMonth() + 1;
                    let selDay = today.getDate();

                    const years = [];
                    const currentYear = new Date().getFullYear();
                    for (let y = currentYear - 100; y <= currentYear + 10; y++) {
                        years.push( {label: y , value: y} );
                    }
                    const months = Array.from( {length: 12} , (_ , i) => ({
                        label: new Date( 2000 , i , 1 ).toLocaleString( 'en' , {month: 'short'} ) ,
                        value: i + 1
                    }) );

                    function rebuildDays() {
                        const max = new Date( selYear , selMonth , 0 ).getDate();
                        const days = Array.from( {length: max} , (_ , i) => ({label: i + 1 , value: i + 1}) );
                        if (selDay > max) selDay = max;
                        buildWheel( dayList , days , selDay );
                        dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;
                    }

                    buildWheel( yearList , years , selYear );
                    buildWheel( monthList , months , selMonth );
                    rebuildDays();

                    yearWheel.scrollTop = years.findIndex( y => y.value === selYear ) * ITEM_HEIGHT;
                    monthWheel.scrollTop = (selMonth - 1) * ITEM_HEIGHT;
                    dayWheel.scrollTop = (selDay - 1) * ITEM_HEIGHT;

                    const wheels = [
                        {
                            el: yearWheel , list: yearList , onChange: v => {
                                selYear = v;
                                rebuildDays();
                            }
                        } ,
                        {
                            el: monthWheel , list: monthList , onChange: v => {
                                selMonth = v;
                                rebuildDays();
                            }
                        } ,
                        {
                            el: dayWheel , list: dayList , onChange: v => {
                                selDay = v;
                            }
                        } ,
                    ];

                    wheels.forEach( ({el , list , onChange}) => {
                        let t;
                        el.addEventListener( 'scroll' , () => {
                            clearTimeout( t );
                            t = setTimeout( () => {
                                snap( el );
                                const idx = Math.round( el.scrollTop / ITEM_HEIGHT );
                                const li = list.children[idx];
                                if (li) {
                                    onChange( parseInt( li.dataset.value , 10 ) );
                                }
                            } , 80 );
                        } );
                    } );

                    function saveAndClose() {
                        const monthStr = String( selMonth ).padStart( 2 , '0' );
                        const dayStr = String( selDay ).padStart( 2 , '0' );
                        const newVal = `${selYear}-${monthStr}-${dayStr}`;
                        input.value = newVal;
                        display.textContent = formatDisplay( newVal );
                        panel.classList.remove( 'open' );
                    }

                    function cancelAndClose() {
                        panel.classList.remove( 'open' );
                    }

                    display.addEventListener( 'click' , () => {
                        panel.classList.add( 'open' );
                        panel.focus();
                    } );

                    panel.querySelector( '.js-done' ).addEventListener( 'click' , saveAndClose );
                    panel.querySelector( '.js-cancel' ).addEventListener( 'click' , cancelAndClose );

                    document.addEventListener( 'click' , (e) => {
                        if (!panel.classList.contains( 'open' )) return;
                        if (!panel.contains( e.target ) && e.target !== display) {
                            saveAndClose();
                        }
                    } );
                }

                document.querySelectorAll( '.ios-date-picker' ).forEach( initPicker );
            })();
        </script>
        {{--<script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>--}}
        <!-- Responsive and datable js -->
        <script type="text/javascript">
            $( document ).ready( function () {
                var isMobileLayout = window.matchMedia( '(max-width: 991px)' ).matches;
                var widthStore = window.sigmaTableWidthStore || null;
                var casesWidthScope = 'casesTable_widths';
                var lastCasesViewportWidth = null;
                var stickyToolbar = document.querySelector( '.sigma-sticky-toolbar' );
                var stickyToolbarTicking = false;

                function getCasesHeaderOffset() {
                    var rootStyles = window.getComputedStyle( document.documentElement );
                    var rawOffset = rootStyles.getPropertyValue( '--sigma-app-header-offset' ) || '0';
                    var parsedOffset = parseFloat( rawOffset );
                    return Number.isFinite( parsedOffset ) ? parsedOffset : 0;
                }

                function syncStickyToolbarState() {
                    if (!stickyToolbar) {
                        return;
                    }

                    var headerOffset = getCasesHeaderOffset();
                    var toolbarTop = stickyToolbar.getBoundingClientRect().top;
                    stickyToolbar.classList.toggle( 'sigma-sticky-toolbar--stuck', toolbarTop <= (headerOffset + 1) );
                }

                function requestStickyToolbarSync() {
                    if (stickyToolbarTicking) {
                        return;
                    }

                    stickyToolbarTicking = true;
                    window.requestAnimationFrame( function () {
                        syncStickyToolbarState();
                        stickyToolbarTicking = false;
                    } );
                }

                function applyCasesWidths(widths) {
                    if (!widths) {
                        return;
                    }
                    $( '#casesTable th' ).each( function (i) {
                        if (typeof widths[i] === 'undefined') {
                            return;
                        }
                        if (typeof widths[i] === 'string') {
                            $( this ).css( 'width', widths[i] );
                        } else {
                            $( this ).width( widths[i] );
                        }
                    } );
                }

                function syncCasesTableViewport() {
                    var scrollEl = document.querySelector( '.cases-table-scroll' );
                    var tableEl = document.getElementById( 'casesTable' );

                    if (!scrollEl || !tableEl) {
                        return;
                    }

                    if (window.matchMedia( '(max-width: 991px)' ).matches) {
                        scrollEl.style.overflowX = 'visible';
                        tableEl.style.width = '';
                        tableEl.style.maxWidth = '';
                        return;
                    }

                    scrollEl.style.overflowX = 'hidden';

                    var availableWidth = scrollEl.clientWidth;
                    if (availableWidth > 0) {
                        tableEl.style.width = availableWidth + 'px';
                        tableEl.style.maxWidth = availableWidth + 'px';
                    }

                    if ($.fn.DataTable.isDataTable( tableEl ) && lastCasesViewportWidth !== availableWidth) {
                        $( tableEl ).DataTable().columns.adjust();
                    }

                    lastCasesViewportWidth = availableWidth;
                }

                if (!isMobileLayout && widthStore && typeof widthStore.get === 'function') {
                    var preloadedWidths = widthStore.get(casesWidthScope);
                    if (preloadedWidths) {
                        applyCasesWidths(preloadedWidths);
                    }
                }

                var table = $( "#casesTable" ).DataTable( {
                                                              "colResize": !isMobileLayout,
                                                              "colReorder": !isMobileLayout ,
                                                              "responsive": false ,  // Disable responsive column hiding
                                                              "bLengthChange": false ,  // Disable "Show XX entries" dropdown
                                                              "iDisplayLength": 20 ,
                                                              "ordering": false ,
                                                              "order": [] ,  // Disable initial sorting to preserve server-side order
                                                              "dom": 'rtip' ,  // Hide default search box ('f' removed) but keep table, info, pagination
                                                              "info": false ,
                                                              "bProcessing": true ,
                                                              "searching": true ,  // Enable searching for real-time filter
                                                              "scrollX": isMobileLayout ,
                                                              "autoWidth": false ,
                                                              "initComplete": function () {
                                                                  var settings = this;
                                                                  if (isMobileLayout) {
                                                                      $( '#casesTable th' ).css( 'width' , '' );
                                                                      syncCasesTableViewport();
                                                                      return;
                                                                  }

                                                                  // Restore saved widths
                                                                  if (widthStore && typeof widthStore.whenReady === 'function') {
                                                                      widthStore.whenReady(function () {
                                                                          var widths = widthStore.get(casesWidthScope);
                                                                          if (widths) {
                                                                              applyCasesWidths(widths);
                                                                              // Recalc resize handle positions after width restore
                                                                              setTimeout(function() {
                                                                              if (settings.colResize && settings.colResize._recalcPositions) {
                                                                                  settings.colResize._recalcPositions();
                                                                              }
                                                                              syncCasesTableViewport();
                                                                          }, 50);
                                                                      }
                                                                  });
                                                              }

                                                                  // Save widths on column resize (only on resize handles)
                                                                  $( document ).on( 'mouseup', '.dt-colresizable-col', function (e) {
                                                                      // Don't stopPropagation - let it reach document for colResize cleanup
                                                                      setTimeout( function () {
                                                                          var newWidths = [];
                                                                          $( '#casesTable th' ).each( function () {
                                                                              newWidths.push( $( this ).width() );
                                                                          } );
                                                                          if (widthStore && typeof widthStore.set === 'function') {
                                                                              widthStore.set( casesWidthScope, newWidths );
                                                                          }
                                                                          syncCasesTableViewport();
                                                                      }, 100 );
                                                                  } );

                                                                  setTimeout( syncCasesTableViewport, 0 );
                                                              },
                                                              "columnDefs": isMobileLayout ? [
                                                                  {"orderable": false , "targets": [0 , 1 , 4 , 6]}
                                                              ] : [
                                                                  {"orderable": false , "targets": [0 , 1 , 4 , 6]} ,  // Disable sorting on Doctor, Patient, Units, and Tags columns
                                                                  {"width": "16.5%" , "targets": 0} ,  // Doctor column width
                                                                  {"width": "16.5%" , "targets": 1} ,  // Patient column width
                                                                  {"width": "18%" , "targets": 2} ,  // Initial Delivery Date
                                                                  {"width": "14.5%" , "targets": 3} ,  // Date Delivered
                                                                  {"width": "7%" , "targets": 4} ,   // Units (NEW)
                                                                  {"width": "14.5%" , "targets": 5} ,  // Status
                                                                  {"width": "10.5%" , "targets": 6}   // Tags
                                                              ]
                                                          } );

                table.on( 'draw', function () {
                    syncCasesTableViewport();
                } );

                $( window ).on( 'resize.casesTableViewport', function () {
                    syncCasesTableViewport();
                    requestStickyToolbarSync();
                } );

                syncCasesTableViewport();
                syncStickyToolbarState();

                window.addEventListener( 'scroll', requestStickyToolbarSync, {passive: true} );

                // Connect custom search field to DataTable for real-time search
                $( '#tableSearch' ).on( 'keyup' , function () {
                    table.search( this.value ).draw();
                } );

                // Keep the body-mounted Doctor filter dropdown within viewport on mobile.
                function fitCasesDoctorDropdown() {
                    if (!window.matchMedia( '(max-width: 991px)' ).matches) {
                        return;
                    }

                    var $select = $( '#doctor' );
                    var picker = $select.data( 'selectpicker' );
                    var $container = picker && picker.$bsContainer && picker.$bsContainer.length
                        ? picker.$bsContainer
                        : $( '.bs-container.bootstrap-select.show' ).first();

                    if (!$container.length) {
                        return;
                    }

                    var $menu = picker && picker.$menu && picker.$menu.length
                        ? picker.$menu
                        : $container.find( '> .dropdown-menu' );

                    if (!$menu.length) {
                        return;
                    }

                    var viewportPadding = 12;
                    var viewportWidth = document.documentElement.clientWidth || window.innerWidth || $( window ).width();
                    var $button = picker && picker.$button && picker.$button.length
                        ? picker.$button
                        : $( 'button[data-id="doctor"]' );
                    var fallbackButton = $select.closest( '.bootstrap-select' )[0];
                    var buttonRect = $button.length
                        ? $button[0].getBoundingClientRect()
                        : fallbackButton
                            ? fallbackButton.getBoundingClientRect()
                            : null;

                    if (!buttonRect) {
                        return;
                    }

                    var buttonWidth = buttonRect.width || $button.outerWidth();
                    var preferredMenuWidth = Math.max(buttonWidth || 0, 260);
                    var menuWidth = Math.min(
                        Math.max(preferredMenuWidth, $menu.outerWidth() || 0),
                        Math.max(viewportWidth - (viewportPadding * 2), 0)
                    );
                    var viewportLeft = window.pageXOffset || 0;
                    var viewportRight = viewportLeft + viewportWidth;
                    var containerLeft = buttonRect.right + viewportLeft - menuWidth;
                    var maxLeft = viewportRight - viewportPadding - menuWidth;
                    containerLeft = Math.max( viewportLeft + viewportPadding, Math.min( containerLeft, maxLeft ) );

                    $container.css( {
                        position: 'absolute',
                        top: buttonRect.bottom + window.pageYOffset,
                        left: containerLeft,
                        right: 'auto',
                        width: menuWidth,
                        maxWidth: menuWidth,
                        transform: 'none'
                    } );

                    $menu.css( {
                        minWidth: menuWidth,
                        maxWidth: menuWidth,
                        right: 'auto',
                        left: 0,
                        transform: 'none'
                    } );
                }

                function isCasesDoctorDropdownOpen() {
                    var $select = $( '#doctor' );
                    var picker = $select.data( 'selectpicker' );

                    if (picker && picker.$bsContainer && picker.$bsContainer.hasClass( 'show' )) {
                        return true;
                    }

                    if ($( '.bs-container.bootstrap-select.clearOnAll.filter-input-global.show' ).length) {
                        return true;
                    }

                    return $select.closest( '.bootstrap-select' ).hasClass( 'show' )
                        || $( 'button[data-id="doctor"]' ).attr( 'aria-expanded' ) === 'true';
                }

                function scheduleCasesDoctorDropdownFit() {
                    fitCasesDoctorDropdown();
                    window.requestAnimationFrame( fitCasesDoctorDropdown );
                    setTimeout( fitCasesDoctorDropdown, 50 );
                    setTimeout( fitCasesDoctorDropdown, 150 );
                }

                $( document ).on( 'click' , 'button[data-id="doctor"]' , scheduleCasesDoctorDropdownFit );
                $( document ).on( 'show.bs.select shown.bs.select rendered.bs.select' , '#doctor' , scheduleCasesDoctorDropdownFit );
                $( window ).on( 'resize.casesDoctorDropdown orientationchange.casesDoctorDropdown' , function () {
                    if (isCasesDoctorDropdownOpen()) {
                        scheduleCasesDoctorDropdownFit();
                    }
                } );
                $( '.main-panel, .content, .cases-table-scroll' ).on( 'scroll.casesDoctorDropdown' , function () {
                    if (isCasesDoctorDropdownOpen()) {
                        scheduleCasesDoctorDropdownFit();
                    }
                } );
                window.addEventListener( 'scroll', function () {
                    if (isCasesDoctorDropdownOpen()) {
                        scheduleCasesDoctorDropdownFit();
                    }
                }, {passive: true} );
                document.addEventListener( 'scroll', function () {
                    if (isCasesDoctorDropdownOpen()) {
                        scheduleCasesDoctorDropdownFit();
                    }
                }, {passive: true, capture: true} );

                function cleanupCasesIndexModalArtifacts() {
                    if (document.querySelector( '.modal.show' )) {
                        return;
                    }

                    $( '.modal-backdrop' ).remove();
                    $( 'body' )
                        .removeClass( 'modal-open' )
                        .css( {
                            'padding-right': ''
                        } );
                }

                var casesActionsModalCache = {};
                var casesActionsModalUrlTemplate = @json(route('cases-actions-modal', ['id' => '__CASE_ID__']));

                function getCasesActionsModalUrl(caseId) {
                    return casesActionsModalUrlTemplate.replace('__CASE_ID__', caseId);
                }

                function setCasesActionsModalLoading($modal) {
                    $modal.find('.modal-content').html(
                        '<div class="modal-body"><div class="text-center py-4">Loading case details...</div></div>'
                    );
                }

                function setCasesActionsModalError($modal) {
                    $modal.find('.modal-content').html(
                        '<div class="modal-body"><div class="text-center py-4 text-danger">Unable to load case details.</div></div>' +
                        '<div class="modal-footer"><button type="button" class="btn btn-secondary sigma-action-btn" data-dismiss="modal">Close</button></div>'
                    );
                }

                function loadCasesActionsModal(caseId, $modal) {
                    if (!caseId) {
                        setCasesActionsModalError($modal);
                        return;
                    }

                    $modal.data('currentCaseId', caseId);
                    $modal.find('input[name=\"case_id\"]').val(caseId);

                    if (casesActionsModalCache[caseId]) {
                        $modal.find('.modal-content').html(casesActionsModalCache[caseId]);
                        return;
                    }

                    setCasesActionsModalLoading($modal);

                    $.get(getCasesActionsModalUrl(caseId))
                        .done(function (html) {
                            casesActionsModalCache[caseId] = html;

                            if (String($modal.data('currentCaseId')) === String(caseId)) {
                                $modal.find('.modal-content').html(html);
                            }
                        })
                        .fail(function () {
                            if (String($modal.data('currentCaseId')) === String(caseId)) {
                                setCasesActionsModalError($modal);
                            }
                        });
                }

                function mountCasesIndexModals() {
                    var modal = document.getElementById('actionsDialog');
                    if (modal && modal.parentNode !== document.body) {
                        document.body.appendChild(modal);
                    }
                }

                mountCasesIndexModals();

                $( document )
                    .off( 'show.bs.modal.casesIndexActions' )
                    .on( 'show.bs.modal.casesIndexActions' , '.sigma-modal--cases-index-actions' , function (event) {
                        var $modal = $( this );
                        var caseId = $( event.relatedTarget ).data( 'case-id' ) || $modal.data( 'currentCaseId' );

                        if (!caseId) {
                            return false;
                        }

                        if (!document.querySelector( '.modal.show' )) {
                            $( '.modal-backdrop' ).remove();
                        }
                        mountCasesIndexModals();
                        $( 'body' ).addClass( 'modal-open' );
                        $modal.removeAttr( 'aria-hidden' );
                        loadCasesActionsModal( caseId, $modal );
                    } );

                $( document )
                    .off( 'click.casesIndexActionsBackdrop' )
                    .on( 'click.casesIndexActionsBackdrop' , '.sigma-modal--cases-index-actions' , function (e) {
                        if (e.target === this) {
                            $( this ).modal( 'hide' );
                        }
                    } );

                $( document )
                    .off( 'hidden.bs.modal.casesIndexActions' )
                    .on( 'hidden.bs.modal.casesIndexActions' , '.sigma-modal--cases-index-actions' , function () {
                        $( this )
                            .removeClass( 'show' )
                            .css( 'display' , 'none' )
                            .attr( 'aria-hidden' , 'true' )
                            .removeAttr( 'aria-modal' )
                            .removeData( 'currentCaseId' );
                        setTimeout( cleanupCasesIndexModalArtifacts , 0 );
                    } );

                function saveColumnWidths(tableId) {
                    const widths = [];
                    $( `#${tableId} th` ).each( function () {
                        widths.push( $( this ).width() );
                    } );
                    if (widthStore && typeof widthStore.set === 'function') {
                        widthStore.set( `table_${tableId}_widths`, widths );
                    }
                }

                // Restore on page load
                function restoreColumnWidths(tableId) {
                    const widths = widthStore && typeof widthStore.get === 'function'
                        ? widthStore.get( `table_${tableId}_widths` )
                        : null;
                    if (widths) {
                        $( `#${tableId} th` ).each( function (i) {
                            if (typeof widths[i] === 'undefined') {
                                return;
                            }
                            if (typeof widths[i] === 'string') {
                                $( this ).css( 'width', widths[i] );
                            } else {
                                $( this ).width( widths[i] );
                            }
                        } );
                    }
                }


            } );

            function caseDelConfirmation(ev) {
                ev.preventDefault();
                var urlToRedirect = ev.currentTarget.getAttribute( 'href' ); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
                var clientName = ev.currentTarget.getAttribute( 'data-clientName' );
                var patientName = ev.currentTarget.getAttribute( 'data-patientName' );
                var alert = window.Swal || window.swal;
                var $openCaseActionsModal = $( '.sigma-modal--cases-index-actions.show' );

                function showDeleteCaseAlert() {
                    if (!alert || typeof alert.fire !== 'function') {
                        return;
                    }

                    alert.fire( {
                                   title: "You sure You want to delete.. </br>" + clientName + " - " + patientName ,
                                   text: "This will also delete related info. (invoice, photos .. etc)?" ,
                                   icon: "warning" ,
                                   showCancelButton: true ,
                                   confirmButtonText: 'Delete Case' ,
                                   cancelButtonText: 'Cancel',
                                   target: document.body
                               } ).then( (result) => {
                        if (result.isConfirmed) {
                            window.location = urlToRedirect;
                        }
                    } );
                }

                if ($openCaseActionsModal.length) {
                    $openCaseActionsModal.one( 'hidden.bs.modal', function () {
                        setTimeout( showDeleteCaseAlert, 0 );
                    } );
                    $openCaseActionsModal.modal( 'hide' );
                    return;
                }

                showDeleteCaseAlert();

            }
        </script>
        <script>
            $( document ).ready( function () {
                var tooltip = $( '<div class="case-jobs-tooltip"></div>' ).appendTo( 'body' );
                var hoverTimeout;
                var tooltipDataNode = document.getElementById( 'cases-tooltip-data' );
                var casesTooltipJobs = {};

                if (tooltipDataNode) {
                    try {
                        casesTooltipJobs = JSON.parse( tooltipDataNode.textContent || '{}' );
                    } catch (error) {
                        casesTooltipJobs = {};
                    }
                }

                // Default to true if not set, and handle the string 'false'
                var tooltipEnabled = localStorage.getItem( 'tooltipEnabled' ) !== 'false';
                var shouldDisableTooltips = function () {
                    return window.matchMedia( '(hover: none), (pointer: coarse), (max-width: 991px)' ).matches;
                };
                var mobileTooltipDisabled = shouldDisableTooltips();
                if (mobileTooltipDisabled) {
                    tooltipEnabled = false;
                    tooltip.hide();
                }

                function positionCaseJobsTooltip(event) {
                    var offset = 15;
                    var viewportPadding = 12;
                    var viewportLeft = $( window ).scrollLeft();
                    var viewportTop = $( window ).scrollTop();
                    var viewportRight = viewportLeft + $( window ).width();
                    var viewportBottom = viewportTop + $( window ).height();
                    var tooltipWidth = tooltip.outerWidth();
                    var tooltipHeight = tooltip.outerHeight();
                    var left = event.pageX + offset;
                    var top = event.pageY + offset;

                    if (left + tooltipWidth > viewportRight - viewportPadding) {
                        left = event.pageX - tooltipWidth - offset;
                    }

                    if (top + tooltipHeight > viewportBottom - viewportPadding) {
                        top = event.pageY - tooltipHeight - offset;
                    }

                    tooltip.css( {
                        top: Math.max( viewportTop + viewportPadding, top ),
                        left: Math.max( viewportLeft + viewportPadding, left )
                    } );
                }

                // Set initial state of the toggle
                $( '#tooltip-toggle' ).prop( 'checked' , !mobileTooltipDisabled && tooltipEnabled );

                // Handle toggle change
                $( '#tooltip-toggle' ).on( 'change' , function () {
                    if (shouldDisableTooltips()) {
                        tooltipEnabled = false;
                        $( this ).prop( 'checked' , false );
                        tooltip.hide();
                        return;
                    }
                    tooltipEnabled = $( this ).is( ':checked' );
                    localStorage.setItem( 'tooltipEnabled' , tooltipEnabled );
                } );


                $( 'body' ).on( 'mouseenter' , '.case-row' , function (e) {
                    if (!tooltipEnabled || shouldDisableTooltips()) return;
                    var caseId = $( this ).data( 'case-id' );

                    clearTimeout( hoverTimeout );

                    hoverTimeout = setTimeout( function () {
                        var jobs = casesTooltipJobs[String( caseId )] || [];

                        if (jobs.length > 0) {
                            var table = '<table><thead><tr><th>Job Type</th><th>Material</th><th>Units</th></tr></thead><tbody>';
                            jobs.forEach( function (job) {
                                table += '<tr>';
                                table += '<td>' + job.type + '</td>';
                                table += '<td>' + job.material + '</td>';
                                table += '<td>' + job.units + '</td>';
                                table += '</tr>';
                            } );
                            table += '</tbody></table>';
                            tooltip.html( table );
                        } else {
                            tooltip.html( 'No jobs found for this case.' );
                        }

                        tooltip.css( {
                                         display: 'block',
                                         visibility: 'hidden'
                                     } );
                        positionCaseJobsTooltip( e );
                        tooltip.css( 'visibility', 'visible' );
                    } , 250 ); // Reduced delay
                } );

                $( 'body' ).on( 'mouseleave' , '.case-row' , function () {
                    clearTimeout( hoverTimeout );
                    tooltip.hide();
                } );

                $( 'body' ).on( 'mousemove' , '.case-row' , function (e) {
                    if (!tooltipEnabled || shouldDisableTooltips()) return;
                    positionCaseJobsTooltip( e );
                } );

                $( window ).on( 'resize orientationchange' , function () {
                    if (shouldDisableTooltips()) {
                        tooltip.hide();
                    }
                } );

                if (window.sigmaPageBenchmark) {
                    window.sigmaPageBenchmark.mark( 'cases.tooltips.ready' );
                }
            } );
        </script>

    @endpush

@endsection
