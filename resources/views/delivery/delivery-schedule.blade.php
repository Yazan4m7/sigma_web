@extends('layouts.app', ['pageSlug' => 'Delivery Schedule'])


@section('content')
    <style>
        :root {
            --surface-bg: #f4f6fb;
            --card-bg: #ffffff;
            --border-muted: #e3e8f0;
            --text-main: #1f2a37;
            --text-muted: #6b7280;
        }

        .delivery-page-wrapper {
            background: transparent;
            min-height: 100vh;
            padding: 1rem;
        }

        .delivery-section-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
            border: 1px solid var(--border-muted);
        }

        .row {
            margin: 0 !important;
        }

        .table-odd tbody>tr:nth-of-type(odd) {
            background-color: #ffffff !important;
        }

        .table-odd tbody>tr:nth-of-type(even) {
            background-color: #f5f8ff !important;
        }

        .mb-3, .my-3 {
            margin-bottom: 0rem !important;
        }

        .vertical {
            padding-left: 5px;
            border-left: 1px solid #aaaaaa;
        }

        .delivery-time-part {
            font-weight: 700;
            font-size: 18px;
            color: inherit;
            white-space: nowrap;
            letter-spacing: -0.7px;
        }
        @media screen and (min-width:1000px){
            .delivery-time-part {
                font-size: 21px;
                letter-spacing: 0;
            }
            .delivery-date-part {
                font-size: 14px !important;
            }
        }


        .delivery-time-ampm {
            font-size: 12px;
            font-weight: 700;

        }

        .delivery-date-part {
            font-weight: 500;
            font-size: 11px;
            color: inherit;
        }

        .delivery-date-part::before {
            content: "  ";
        }

        .delivery-datetime-single {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sigma-modal--delivery-schedule-actions .delivery-jobs-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-top: 6px;
            width: 100%;
        }

        .sigma-modal--delivery-schedule-actions .delivery-jobs-section {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .sigma-modal--delivery-schedule-actions .delivery-jobs-section > .col-12 {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .sigma-modal--delivery-schedule-actions .delivery-job-row {
            display: flex;
            align-items: flex-start;
            width: 100%;
            min-width: 0;
            padding: 8px 11px;
            border-radius: 10px;
            border: 1px solid #d9e4e8;
            background: #f8fbfc;
            color: #294450;
            font-size: 13px;
            line-height: 1.45;
            overflow: hidden;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
            box-shadow: none;
        }

        .sigma-modal--delivery-schedule-actions .delivery-job-row::-webkit-scrollbar {
            display: none;
        }

        .sigma-modal--delivery-schedule-actions .delivery-job-primary {
            display: block;
            width: 100%;
            color: #294450;
            font-weight: 600;
            line-height: 1.45;
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .sigma-modal--delivery-schedule-actions .delivery-job-empty {
            color: #6b7280;
        }

        .text-overdue, .text-overdue .delivery-time-value, .text-overdue .delivery-date-time {
            color: red !important;
        }

        /* Modal doctor/patient names */
        #doctor, #pat, .modal-body h5 {
            font-family: 'Cairo', sans-serif;
        }
        .delivery-counter-card > .value {
            color: #3b8b45;
            font-size: 1.4rem;
            letter-spacing: -3px;
            font-weight: 700;
            display: block !important;
        }

        /* Keep delivery counters in single row on mobile */
        .delivery-counters {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto;
            align-items: flex-end !important;
        }

        .delivery-counters > div {
            flex: 1 1 33.333% !important;
            min-width: 0 !important;
            display: flex;
        }

        .delivery-counter-card {
            flex: 1 1 auto;
            height: 100%;
        }

        .delivery-counter-card > .label {
            min-height: 2.4em;
        }

        .status-badge--mobile {
            display: none !important;
        }


        /* Shrink # of units column */
        #datatable thead th:nth-child(4),
        #datatable tbody td:nth-child(4) {
            width: 10% !important;
            max-width: 80px;
        }

        /* Responsive modal dialog sizing */
        @media screen and (max-width: 991px){
            /* Tablets */

.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: 90% !important;
                margin: 1rem auto !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: 90% !important;
                margin: 1rem auto !important;
            }
        }


        @media screen and (max-width: 700px){
            /* Large phones */

.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: calc(100% - 24px) !important;
                margin: 12px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: calc(100% - 24px) !important;
                margin: 12px !important;
            }
        }

        @media screen and (max-width: 480px){
            /* Small phones */

.sigma-modal--delivery-schedule-edit .modal-dialog {
                max-width: calc(100% - 16px) !important;
                margin: 8px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-dialog {
                max-width: calc(100% - 16px) !important;
                margin: 8px !important;
            }


.sigma-modal--delivery-schedule-edit .modal-content {
                border-radius: 8px !important;
            }
.sigma-modal--delivery-schedule-actions .modal-content {
                border-radius: 8px !important;
            }
        }



            .table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child,
            .table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child {
                padding-left: 10px !important;
            }

            .table.dataTable.dtr-inline.collapsed>tbody>tr>td,
            .table.dataTable.dtr-inline.collapsed>tbody>tr>th {
                padding: 8px 5px !important;
            }

            .table.dataTable>tbody>tr>td,
            .table.dataTable>tbody>tr>th {
                /*white-space: nowrap;*/
                /* Prevent wrapping in cells */
            }

            #datatable {
                table-layout: fixed !important;
                width: 100% !important;
            }

            #datatable {
                font-family: 'Cairo', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
            }

        #datatable thead th,
        #datatable tbody td {
            padding: 4px 6px !important;

                overflow: hidden !important;
                text-overflow: ellipsis !important;
                /*white-space: nowrap !important;*/
            line-height: 1.3 !important;
            max-height: 32px !important;
            height: 25px !important;
        }

        #datatable tbody td {
            font-size: 17px !important;
        }

        #datatable tbody td:nth-child(2) {
            font-weight: 700 !important;
        }

        /* Remove header separator lines */
        #datatable thead th {
            border-left: none !important;
            border-right: none !important;
        }

            #datatable tbody td {
                color: #2c3e50 !important;
                font-family: 'Cairo', 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif !important;
            }

            #datatable thead th:nth-child(1),
            #datatable tbody td:nth-child(1) {
                width: 22% !important;
            }

            #datatable thead th:nth-child(2),
            #datatable tbody td:nth-child(2) {
                width: 22% !important;
            }

        #datatable thead th:nth-child(3),
        #datatable tbody td:nth-child(3) {
            width: 25% !important;
        }

            #datatable thead th:nth-child(4),
            #datatable tbody td:nth-child(4) {
                width: 12% !important;
            }

        #datatable thead th:nth-child(5),
        #datatable tbody td:nth-child(5) {
            width: 24% !important;
        }

            /* Keep Status column visible and allow wrapping */
            #datatable thead th:nth-child(5),
            #datatable tbody td:nth-child(5) {
                display: table-cell !important;
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }

            #datatable tbody td:nth-child(6) {
                text-align: center !important;
            }

        .delivery-page-wrapper #datatable td .status-badge {
            width: 8.5em !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            white-space: nowrap !important;
            text-align: center !important;
            margin: 0 auto !important;
            line-height: 1 !important;
            vertical-align: middle !important;
            box-sizing: border-box !important;
        }

        @media screen and (min-width: 768px) {
            .status-badge--mobile {
                display: none !important;
            }

            .status-badge--desktop {
                display: inline-block !important;
            }
        }

            .table-action-btn {
                padding: .15rem .3rem;
                font-size: .75rem;
            }

            .table-responsive {
                padding: 0 !important;
            }

            .input-group,
            .form-group {
                margin-bottom: 0px;
                position: relative;
            }
            .btn {
                width: auto;
                min-width: 40px;
            }

            .row {
                padding: 2px;
            }}
        /* Custom CSS for filter row matching cases page */
        .cases-filter-row {
            padding: 8px 0 !important;
            align-items: center;
            display: flex;
            gap: 16px; /* Bootstrap default was too small */
        }
        .filter-label {
            font-weight: 600;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cases-filter-btn {
            width: 100%;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
        }

        /* Responsive adjustments for cases-filter-row on smaller screens */
        @media screen and (max-width: 767px) {
            /* Page padding reduction by 50% */
            .content {
                padding: 0 !important;
            }

            /* Extend counter cards */
            .delivery-counter-card > .value {
                font-size: 1.6rem !important;
                letter-spacing: -2px;
            }
            .delivery-counter-card > .label {
                font-size: 0.85rem !important;
            }

            /* Mobile card adjustments */
            .delivery-page-wrapper {
                padding:  8px 6px !important;
                background: transparent !important;
            }

            .delivery-section-card {
                border-radius: 12px !important;
                padding: 10px 6px !important;
                margin-bottom: 8px !important;
            }

            /* Fix table horizontal scroll */
            .table-responsive, .table-responsive.row, .dataTables_wrapper {

                overflow: hidden !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            #datatable {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                table-layout: fixed !important;
            }

            .delivery-section-card {
                overflow: hidden !important;
            }

            /* Bold header text */
            #datatable thead th {
                font-weight: 700 !important;
            }

            /* Mobile redesign for top counters - Separate cards */
            .delivery-counters {
                gap: 6px !important;
                padding: 6px 0 !important;
                overflow-x: hidden !important;
                align-items: stretch !important;
            }

            .delivery-counters > div {
                padding: 0 !important;
                margin-bottom: 0 !important;
            }

            .delivery-counter-card {
                background: #f8fafc !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 8px !important;
                padding: 10px 6px !important;
                text-align: center !important;
                box-shadow: none !important;
                flex: 1 1 0 !important;
                min-width: 0 !important;
            }

            .delivery-counter-card > .value {
                font-size: 1.5rem !important;
                font-weight: 700 !important;
                letter-spacing: -0.5px !important;
                line-height: 1 !important;
                margin-bottom: 4px !important;
            }

            .delivery-counter-card > .label {
                font-weight: 500;
                font-size: 0.62rem !important;
                line-height: 1.2;
                text-transform: uppercase;
                letter-spacing: 0.02em;
                color: #64748b !important;
            }

            /* Keep value colors */
            .delivery-counters > div:nth-child(3) .value {
                color: #3b82f6 !important;
            }

            .vertical {
                border-left: none !important;
                padding-left: 0 !important;
            }

            /* Add padding to first column */
            #datatable thead th:nth-child(1),
            #datatable tbody td:nth-child(1) {
                padding-left: 6px !important;
            }

            /* Filter row - 2 dates + button in one row */
            .cases-filter-row {
                flex-wrap: nowrap !important;
                gap: 6px !important;
                padding: 0 !important;
            }

            .cases-filter-row > div:nth-child(1),
            .cases-filter-row > div:nth-child(2) {
                flex: 1 1 auto !important;
                max-width: none !important;
            }

            .cases-filter-row > div:nth-child(3) {
                flex: 0 0 auto !important;
                max-width: none !important;
                display: flex !important;
                align-items: flex-end !important;
            }

            .cases-filter-btn {
                width: 100% !important;
                min-width: 60px !important;
                height: 36px !important;
            }

            .cases-filter-row > div:nth-child(3) {
                flex: 0 0 20% !important;
                max-width: 20% !important;
            }

            .cases-filter-row label {
                font-size: 10px !important;
                margin-bottom: 3px !important;
                color: #64748b !important;
            }

            .cases-filter-row .form-control,
            .cases-filter-row .x-ios-dtp {
                height: 36px !important;
                font-size: 12px !important;
                padding: 4px 6px !important;
                border-radius: 6px !important;
            }

            /* Hide print button on mobile */
            .ml-auto {
                display: none !important;
            }

            /* Prevent horizontal scroll on mobile */
            .table-responsive {
                overflow-x: hidden !important;
            }

            .table-responsive.row {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            #datatable_wrapper,
            #datatable {
                width: 100% !important;
            }

            /* Show date under time on mobile */
            .delivery-datetime-single {
                display: inline-flex;
                flex-direction: column;
                align-items: center;
                white-space: normal;
            }

            .delivery-date-part {
                display: block !important;
                font-size: 11px !important;
                line-height: 1.2;
                margin-top: 2px;
                white-space: nowrap;
            }

            .delivery-date-part::before {
                content: "";
            }

            /* Reduce doctor and patient name font sizes */
            #datatable tbody td:nth-child(1),
            #datatable tbody td:nth-child(2) {
                font-size: 0.80rem !important;
            }

            /* Reduce # of units column width to half */
            #datatable thead th:nth-child(4),
            #datatable tbody td:nth-child(4) {
                width: 7% !important;
                max-width: 48px !important;
                font-size: 14px !important;
                font-weight: 400 !important;
            }

            /* Mobile column width split */
            #datatable thead th:nth-child(1),
            #datatable tbody td:nth-child(1) {
                width: 28% !important; /* Doctor */
                direction: ltr;
                text-align: left !important;
            }

            #datatable thead th:nth-child(2),
            #datatable tbody td:nth-child(2) {
                width: 28% !important; /* Patient */
                direction: ltr;
                text-align: left !important;
            }

            #datatable thead th:nth-child(3),
            #datatable tbody td:nth-child(3) {
                text-align: center;
                width:  21%  !important; /* Date */
            }

            #datatable thead th:nth-child(5),
            #datatable tbody td:nth-child(5) {
                text-align: center;
                width: 21% !important; /* Status */
            }

            /* Tighten table density so all columns fit */
            #datatable thead th,
            #datatable tbody td {
                font-size: 12px !important;
                padding: 3px 0 2px 1px !important;
            }

            .delivery-page-wrapper #datatable td .status-badge {
                font-size: 11px !important;
                line-height: 1.2 !important;
            }

            .status-badge--desktop {
                display: none !important;
            }

            .status-badge--mobile {
                display: inline-block !important;
            }

            .delivery-datetime-single {
                max-width: 100%;
            }
        }
        /* End Custom CSS for filter row matching cases page */

        @media screen and (max-width: 767px) {
            .delivery-page-wrapper #datatable td .status-badge--desktop {
                display: none !important;
            }

            .delivery-page-wrapper #datatable thead th:nth-child(5),
            .delivery-page-wrapper #datatable tbody td:nth-child(5) {
                width: 23% !important;
                overflow: visible !important;
                padding-right: 4px !important;
            }

            .delivery-page-wrapper #datatable td .status-badge--mobile {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
                padding-left: 0.45rem !important;
                padding-right: 0.45rem !important;
                box-sizing: border-box !important;
            }

            .delivery-page-wrapper #datatable td .status-badge--mobile {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
            }
        }

        @media screen and (min-width: 768px) {
            .delivery-page-wrapper #datatable td .status-badge--desktop {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
            }

            .delivery-page-wrapper #datatable td .status-badge--mobile {
                display: none !important;
            }
        }

        /* Remove conflicting original styles */

        /* Center # of units column */
        #datatable thead th:nth-child(4),
        #datatable tbody td:nth-child(4) {
            text-align: center !important;
        }

        /* Left align Status column */
        #datatable thead th:nth-child(5),
        #datatable tbody td:nth-child(5) {
            text-align: center !important;
        }


        .modal-dialog{
            height: 100%;
            align-items: center;
            display: flex;
        }

        /* Sticky filters bar */
        .delivery-controls {
            position: sticky;
            top: 70px;
            z-index: 5;
            background: #ffffff;
        }

        /* DataTables pagination + info polish */
        #datatable_wrapper .dataTables_info {
            display: block !important;
            font-size: 12px;
            color: #6b7280;
            text-align: left;
            padding-top: 8px;
        }

        #datatable_wrapper .dataTables_paginate {
            text-align: right;
            padding-top: 6px;
        }

        #datatable_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e2e8f0 !important;
            background: #f8fafc !important;
            color: #334155 !important;
            border-radius: 8px !important;
            padding: 4px 10px !important;
            margin-left: 6px !important;
            font-size: 12px !important;
        }

        #datatable_wrapper .dataTables_paginate .paginate_button.current,
        #datatable_wrapper .dataTables_paginate .paginate_button:hover {
            background: #3c9aff !important;
            color: #ffffff !important;
            border-color: #0ea5e9 !important;
        }

        /* Delivery schedule restyle to match cases filters and report cards */
        .delivery-page-wrapper .delivery-filter-form {
            margin-bottom: 24px;
        }

        .sigma-modal--delivery-schedule-actions .modal-content,
        .sigma-modal--delivery-schedule-actions .modal-content h1,
        .sigma-modal--delivery-schedule-actions .modal-content h2,
        .sigma-modal--delivery-schedule-actions .modal-content h3,
        .sigma-modal--delivery-schedule-actions .modal-content h4,
        .sigma-modal--delivery-schedule-actions .modal-content h5,
        .sigma-modal--delivery-schedule-actions .modal-content h6,
        .sigma-modal--delivery-schedule-actions .modal-content p,
        .sigma-modal--delivery-schedule-actions .modal-content span,
        .sigma-modal--delivery-schedule-actions .modal-content label,
        .sigma-modal--delivery-schedule-actions .modal-content small,
        .sigma-modal--delivery-schedule-actions .modal-content strong,
        .sigma-modal--delivery-schedule-actions .modal-content b,
        .sigma-modal--delivery-schedule-actions .modal-content div,
        .sigma-modal--delivery-schedule-actions .modal-content a,
        .sigma-modal--delivery-schedule-actions .modal-content li,
        .sigma-modal--delivery-schedule-actions .modal-content td,
        .sigma-modal--delivery-schedule-actions .modal-content th,
        .sigma-modal--delivery-schedule-actions .modal-content input,
        .sigma-modal--delivery-schedule-actions .modal-content textarea,
        .sigma-modal--delivery-schedule-actions .modal-content select,
        .sigma-modal--delivery-schedule-actions .modal-content button,
        .sigma-modal--delivery-schedule-edit .modal-content,
        .sigma-modal--delivery-schedule-edit .modal-content h1,
        .sigma-modal--delivery-schedule-edit .modal-content h2,
        .sigma-modal--delivery-schedule-edit .modal-content h3,
        .sigma-modal--delivery-schedule-edit .modal-content h4,
        .sigma-modal--delivery-schedule-edit .modal-content h5,
        .sigma-modal--delivery-schedule-edit .modal-content h6,
        .sigma-modal--delivery-schedule-edit .modal-content p,
        .sigma-modal--delivery-schedule-edit .modal-content span,
        .sigma-modal--delivery-schedule-edit .modal-content label,
        .sigma-modal--delivery-schedule-edit .modal-content small,
        .sigma-modal--delivery-schedule-edit .modal-content strong,
        .sigma-modal--delivery-schedule-edit .modal-content b,
        .sigma-modal--delivery-schedule-edit .modal-content div,
        .sigma-modal--delivery-schedule-edit .modal-content a,
        .sigma-modal--delivery-schedule-edit .modal-content li,
        .sigma-modal--delivery-schedule-edit .modal-content td,
        .sigma-modal--delivery-schedule-edit .modal-content th,
        .sigma-modal--delivery-schedule-edit .modal-content input,
        .sigma-modal--delivery-schedule-edit .modal-content textarea,
        .sigma-modal--delivery-schedule-edit .modal-content select,
        .sigma-modal--delivery-schedule-edit .modal-content button {
            font-family: 'Cairo', sans-serif !important;
        }

        .sigma-modal--delivery-schedule-actions {
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
            overflow-x: hidden !important;
            overflow-y: hidden !important;
            background: var(--sigma-dialog-overlay-color);
            z-index: 9998 !important;
        }

        .sigma-modal--delivery-schedule-actions.show {
            display: block !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-dialog {
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

        .sigma-modal--delivery-schedule-actions .modal-content {
            position: relative !important;
            will-change: auto !important;
            pointer-events: auto !important;
            display: flex !important;
            flex-direction: column !important;
            max-height: calc(100vh - 32px) !important;
            overflow: hidden !important;
            border: none !important;
            border-radius: 25px !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .sigma-modal--delivery-schedule-actions .modal-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        body.modal-open .wrapper,
        body.modal-open .main-panel {
            transform: none !important;
            -webkit-transform: none !important;
        }

        .sigma-modal--delivery-schedule-actions .case-summary-block {
            margin-bottom: 14px;
        }

        .sigma-modal--delivery-schedule-actions .case-summary-row {
            margin-bottom: 0 !important;
        }

        .sigma-modal--delivery-schedule-actions .patient-doctor-label {
            display: block;
            margin-bottom: 2px;
            color: #6c757d;
            font-size: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .sigma-modal--delivery-schedule-actions .patient-doctor-names {
            color: #2d5f6d;
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            margin-bottom: 0;
        }

        .sigma-modal--delivery-schedule-actions .case-jobs-section {
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

        .sigma-modal--delivery-schedule-actions .case-jobs-section > .col-12 {
            padding: 0 !important;
        }

        .sigma-modal--delivery-schedule-actions .case-jobs-label {
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

        .sigma-modal--delivery-schedule-actions .case-notes-label {
            display: block;
            margin-bottom: 8px !important;
            color: #5c6f7a;
            font-size: 11px !important;
            font-weight: 700;
            letter-spacing: 0.06em;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-jobs-list {
            display: grid !important;
            gap: 6px !important;
            width: 100% !important;
            margin: 6px 0 0 !important;
            padding: 0 !important;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card {
            display: grid !important;
            grid-template-columns: minmax(68px, 0.95fr) minmax(88px, 1.2fr) minmax(80px, 0.1fr) minmax(22px, 0.35fr) minmax(20px, 0.1fr) !important;
            align-items: start !important;
            column-gap: 2px !important;
            width: 100% !important;
            min-width: 0 !important;
            min-height: 30px !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #eef6fa !important;
            background-color: #eef6fa !important;
            border: 0 !important;
            border-left: 2px solid #17a2b8 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            color: #294450 !important;
            font-size: 15px !important;
            font-weight: 500 !important;
            line-height: 1.45 !important;
            overflow: hidden !important;
            white-space: normal !important;
            overflow-wrap: normal !important;
            word-break: normal !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell {
            display: block !important;
            box-sizing: border-box !important;
            max-width: 100% !important;
            min-width: 0 !important;
            min-height: 30px !important;
            border: 0 !important;
            color: inherit !important;
            font-size: 15px !important;
            font-weight: 500 !important;
            line-height: 30px !important;
            text-align: left !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell + .sigma-case-job-cell {
            border-left: 1px solid #cfe4eb47 !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--lead {
            font-weight: 700 !important;
            padding-left: 10px !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--color,
        .sigma-modal--delivery-schedule-actions .modal-body .sigma-case-job-row.sigma-case-job-card > .sigma-case-job-cell--style {
            text-align: center !important;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-job-tooltip {
            display: none !important;
        }

        .sigma-modal--delivery-schedule-actions .form-control.note-container {
            background: #fff !important;
            border: 0.5px solid #c7c7c7 !important;
            border-radius: 8px !important;
            box-shadow: none !important;
            color: #333333 !important;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-note-text {
            display: block !important;
            direction: ltr !important;
            text-align: left !important;
            unicode-bidi: isolate;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-note-text--mixed {
            direction: inherit !important;
            text-align: inherit !important;
            unicode-bidi: normal;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-box {
            display: block;
            width: 100%;
            margin: 0 0 8px;
            padding: 8px 10px;
            border: 1px solid #d4e0e0;
            border-radius: 8px;
            background: #f6f9f9;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            cursor: pointer;
            list-style: none;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-summary::-webkit-details-marker {
            display: none;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 7px;
            padding-top: 7px;
            border-top: 1px solid #dbe7e7;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-note {
            color: #516060;
            font-size: 12px;
            font-style: italic;
            font-weight: 600;
            line-height: 1.45;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-chevron {
            flex: 0 0 auto;
            margin-left: auto;
            color: #3f777b;
            font-size: 12px;
            transition: transform 180ms ease;
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-notes-box[open] .sigma-case-delivery-notes-chevron {
            transform: rotate(180deg);
        }

        .sigma-modal--delivery-schedule-actions .sigma-case-delivery-note-arrow {
            display: inline-block;
            margin: 0 5px;
            color: #3f777b;
            font-style: normal;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer {
            padding: 0 16px 14px 14px !important;
            border-top: none !important;
            border-bottom-left-radius: 25px !important;
            border-bottom-right-radius: 25px !important;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-modal-actions {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-row,
        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-grid {
            width: 100%;
            display: grid;
            gap: 8px;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-row--top,
        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-grid:empty {
            display: none;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-actions-row--cancel {
            grid-template-columns: 1fr;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-action-slot {
            min-width: 0;
        }

        .sigma-modal--delivery-schedule-actions .modal-footer .sigma-action-btn {
            width: 100%;
            min-width: 0;
            margin: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 6px 0 !important;
            line-height: 1.5 !important;
        }

        @media (max-width: 575.98px) {
            .sigma-modal--delivery-schedule-actions .modal-footer .sigma-action-btn .btn-icon {
                display: none !important;
            }
        }

        .sigma-modal--delivery-schedule-edit {
            background: rgba(15, 23, 42, 0.28);
            z-index: 9998 !important;
        }

        body.sigma-delivery-modal-replacing::before {
            content: "";
            position: fixed;
            inset: 0;
            z-index: 9997;
            pointer-events: none;
            background: rgba(15, 23, 42, 0.28);
        }

        body.sigma-delivery-modal-replacing .sigma-modal--delivery-schedule-actions,
        body.sigma-delivery-modal-replacing .sigma-modal--delivery-schedule-edit {
            background: transparent;
        }

        .sigma-modal--delivery-schedule-edit.fade .modal-dialog,
        .sigma-modal--delivery-schedule-edit.show .modal-dialog {
            transform: none !important;
        }

        .sigma-modal--delivery-schedule-edit.fade .modal-content {
            opacity: 0;
            transform: scale(0.98) !important;
            transition: opacity 150ms ease, transform 150ms ease;
        }

        .sigma-modal--delivery-schedule-edit.show .modal-content {
            opacity: 1;
            transform: scale(1) !important;
        }

        .sigma-modal--delivery-schedule-edit.show {
            display: block !important;
        }

        .sigma-modal--delivery-schedule-edit .delivery-edit-form {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100%;
            padding: 16px;
        }

        .sigma-modal--delivery-schedule-edit .modal-dialog {
            width: min(420px, calc(100vw - 32px)) !important;
            max-width: 420px !important;
            max-height: calc(100vh - 32px);
            margin: 0 auto !important;
        }

        .sigma-modal--delivery-schedule-edit .modal-content {
            max-height: calc(100vh - 32px);
            overflow: hidden;
            border: 0 !important;
            border-radius: 22px !important;
            background: #ffffff;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.24);
        }

        .sigma-modal--delivery-schedule-edit .modal-header {
            display: flex;
            align-items: center;
            padding: 14px 18px 10px !important;
            border-bottom: 0 !important;
        }

        .sigma-modal--delivery-schedule-edit .modal-title {
            margin: 0;
            color: #2d5f6d;
            font-size: 18px;
            font-weight: 800;
            line-height: 1.2;
        }

        .sigma-modal--delivery-schedule-edit .modal-header button.close {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            margin: -2px -4px -2px auto;
            padding: 0;
            border: 1px solid #d1d5db;
            border-radius: 50%;
            background: #f3f4f6;
            color: #5b6874;
            opacity: 1;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            text-shadow: none;
        }

        .sigma-modal--delivery-schedule-edit .modal-body {
            padding: 6px 18px 14px !important;
        }

        .sigma-delivery-edit-panel {
            display: grid;
            gap: 12px;
            padding: 14px;
            border: 1px solid rgba(188, 206, 216, 0.65);
            border-radius: 14px;
            background: #f7fbfb;
        }

        .sigma-delivery-edit-field {
            min-width: 0;
        }

        .sigma-delivery-edit-label {
            display: block;
            margin: 0 0 6px;
            color: #60717c;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .sigma-delivery-edit-case {
            display: block;
            margin: 0;
            padding: 0;
            border: 0;
            background: transparent;
            color: #294450;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.35;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sigma-modal--delivery-schedule-edit .ios-dtp-container,
        .sigma-modal--delivery-schedule-edit .ios-dtp-trigger {
            width: 100%;
        }

        .sigma-modal--delivery-schedule-edit .modal-footer {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 8px;
            padding: 0 18px 18px !important;
            border-top: 0 !important;
        }

        .sigma-modal--delivery-schedule-edit .modal-footer .btn {
            flex: 1 1 0;
            width: auto;
            min-height: 40px;
            margin: 0 !important;
            border-radius: 10px !important;
            font-size: 14px;
            font-weight: 800;
        }

        .sigma-modal--delivery-schedule-edit .modal-footer .btn-primary {
            background: #408385 !important;
            border-color: #408385 !important;
            color: #ffffff !important;
        }

        .sigma-modal--delivery-schedule-edit .modal-footer .btn-secondary {
            background: #f3f6f7 !important;
            border-color: #d7e2e5 !important;
            color: #3f555f !important;
        }

        @media (max-width: 575.98px) {
            .sigma-modal--delivery-schedule-edit .delivery-edit-form {
                padding: 12px;
            }

            .sigma-modal--delivery-schedule-edit .modal-dialog {
                width: calc(100vw - 24px) !important;
                max-width: calc(100vw - 24px) !important;
                max-height: calc(100vh - 24px);
                margin: 0 auto !important;
            }

            .sigma-modal--delivery-schedule-edit .modal-content {
                max-height: calc(100vh - 24px);
                border-radius: 18px !important;
            }

            .sigma-modal--delivery-schedule-edit .modal-header {
                padding: 12px 14px 8px !important;
            }

            .sigma-modal--delivery-schedule-edit .modal-body {
                padding: 4px 14px 12px !important;
            }

            .sigma-modal--delivery-schedule-edit .modal-footer {
                padding: 0 14px 14px !important;
            }
        }

        .delivery-page-wrapper .cases-filter-card.delivery-filter-card {
            background: #ffffffa8 !important;
            border: 1px solid rgba(188, 206, 216, 0.3);
            border-radius: 16px;
            box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
            margin: 0 !important;
            padding: 20px 20px 16px !important;
            position: sticky;
            top: 70px;
            z-index: 5;
            overflow: hidden !important;
            backdrop-filter: blur(10px);
        }

        .delivery-page-wrapper .cases-filter-card.delivery-filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
            border-radius: 16px 16px 0 0;
        }

        .delivery-page-wrapper .cases-filter-row {
            --cases-filter-height: 38px;
            --cases-filter-font-size: 14px;
            --cases-filter-color: #243746;
            --cases-filter-gap: 12px;
            --cases-filter-button-pad-y: 8px;
            --cases-filter-button-pad-x: calc(var(--cases-filter-button-pad-y) * 3.625);
            --cases-filter-radius: 10px;
            --cases-filter-border: 1px solid rgba(188, 206, 216, 0.4);
            --cases-filter-padding: 8px 14px;
            padding: 0 !important;
            margin: 0 -8px !important;
            align-items: flex-end;
            font-family: "Tajawal", "Cairo", "Noto Sans Arabic", "Segoe UI", Tahoma, sans-serif;
            gap: 0 !important;
            background-color:transparent;
        }

        .delivery-page-wrapper .delivery-filter-row + .delivery-filter-row {
            margin-top: 2px !important;
        }

        .delivery-page-wrapper .delivery-filter-row--dates {
            display: flex !important;
            flex-wrap: nowrap !important;
            align-items: flex-end !important;
            justify-content: flex-start !important;
            gap: 12px !important;
            padding-right: 42px !important;
        }

        .delivery-page-wrapper .delivery-filter-date-col {
            flex: 0 0 190px !important;
            width: 190px !important;
            max-width: 190px !important;
            min-width: 0;
        }

        .delivery-page-wrapper .delivery-filter-apply-col {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
            display: flex;
            align-items: flex-end;
        }

        .delivery-page-wrapper .delivery-filter-row--actions {
            margin-left: -15px !important;
            margin-right: -15px !important;
        }

        .delivery-page-wrapper .delivery-filter-row--actions .sigma-filter-action-col {
            justify-content: flex-start !important;
        }

        .delivery-page-wrapper .delivery-filter-row--actions .sigma-filter-secondary-col {
            justify-content: flex-end !important;
        }

        @media screen and (min-width: 768px) {
            .delivery-page-wrapper .cases-filter-row > [class*="col-"].sigma-filter-action-col {
                flex: 0 0 auto !important;
                width: auto !important;
                max-width: none !important;
            }
        }

        .delivery-page-wrapper .cases-filter-row .mb-2 {
            margin-bottom: 12px !important;
        }

        .delivery-page-wrapper .cases-filter-row .filter-label {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #243746;
            letter-spacing: 0.01em;
            text-transform: none;
            font-family: "Tajawal", "Cairo", "Noto Sans Arabic", "Segoe UI", Tahoma, sans-serif;
        }

        .delivery-page-wrapper .cases-filter-row .filter-label i {
            color: #2b7b7d;
            font-size: 13px;
        }

        .delivery-page-wrapper .cases-filter-row .dtp-input,
        .delivery-page-wrapper .cases-filter-row .ios-dtp-trigger,
        .delivery-page-wrapper .cases-filter-row .form-control {
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

        .delivery-page-wrapper .cases-filter-row .ios-dtp-display {
            font-size: var(--cases-filter-font-size) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            font-weight: 500;
        }

        .delivery-page-wrapper .cases-filter-row .dtp-input:focus,
        .delivery-page-wrapper .cases-filter-row .ios-dtp-trigger:focus,
        .delivery-page-wrapper .cases-filter-row .form-control:focus {
            border-color: #408385 !important;
            box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
            outline: 0;
        }

        .delivery-page-wrapper .cases-filter-btn {
            width: auto;
            max-width: none;
            min-height: 38px;
            height: var(--cases-filter-height) !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 var(--cases-filter-button-pad-x) !important;
            border-radius: 12px !important;
            font-size: 14px !important;
            font-weight: 600;
            letter-spacing: 0.2px;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .delivery-page-wrapper .cases-filter-btn--search {
            background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
            background-color: #4d9597 !important;
            border: 1px solid #408385 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
        }

        .delivery-page-wrapper .cases-filter-btn--search:hover,
        .delivery-page-wrapper .cases-filter-btn--search:focus {
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            background-color: #4a8d90 !important;
            border-color: #336f71 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
        }

        .delivery-page-wrapper .cases-filter-btn--search:active,
        .delivery-page-wrapper .cases-filter-btn--search:not(:disabled):not(.disabled):active {
            background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
            background-color: #3d7678 !important;
            border-color: #285f61 !important;
            color: #ffffff !important;
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2);
        }

        .delivery-page-wrapper .delivery-print-btn {

            position: absolute;
            top: 12px;
            right: 14px;
            z-index: 2;
            min-width: auto !important;
            border: none !important;
            background: transparent !important;
            color: #337374 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .delivery-page-wrapper .delivery-print-btn:hover,
        .delivery-page-wrapper .delivery-print-btn:focus {
            color: #2b6e70 !important;
            background: transparent !important;
        }

        .delivery-page-wrapper .status-badge.status-badge--desktop {
            line-height: 1.2 !important;
        }

        .delivery-page-wrapper .delivery-summary-grid.delivery-counters {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 16px !important;
            justify-content: flex-start !important;
            align-items: stretch !important;
            overflow: visible !important;
            padding: 0 !important;
            margin: 0 0 15px !important;
        }

        .delivery-page-wrapper .delivery-summary-grid.delivery-counters > .delivery-summary-item {
            flex: 0 1 200px !important;
            max-width: 280px;
            min-width: 200px;
            padding: 0 !important;
            margin-bottom: 0 !important;
            display: block;
        }

        .delivery-page-wrapper .materials-total-card.report-total-card.delivery-counter-card {
            background: #ffffff !important;
            border: 1px solid rgba(188, 206, 216, 0.65) !important;
            border-radius: 14px !important;
            padding: 12px 14px 12px 18px !important;
            box-shadow: 0 6px 18px rgba(44, 87, 102, 0.08) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: flex-start !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .delivery-page-wrapper .materials-total-card.report-total-card.delivery-counter-card::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 5px;
            background: #d6ecee;
        }

        .delivery-page-wrapper .delivery-counter-copy {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            min-width: 0;
        }
        .delivery-page-wrapper .materials-total-label {
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: 0.6px !important;
            text-transform: uppercase !important;
            color: #6b7280 !important;
            margin-bottom: 0 !important;
            line-height: 1.05 !important;
            white-space: nowrap !important;
        }
        .delivery-page-wrapper .materials-total-value {
            display: flex !important;
            align-items: baseline !important;
            gap: 8px !important;
            flex: 0 0 auto !important;
        }
        .delivery-page-wrapper .materials-total-amount {
            font-size: 23px !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            letter-spacing: -0.02em !important;
            color: #1f2937 !important;
        }

        .delivery-page-wrapper .delivery-counter-card--total .materials-total-amount,
        .delivery-page-wrapper .delivery-counter-card--units .materials-total-amount {
            color: #3b8b45 !important;
        }

        .delivery-page-wrapper .delivery-counter-card--overdue .materials-total-amount {
            color: #dc2626 !important;
        }

        .delivery-page-wrapper #datatable_wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }

        .delivery-page-wrapper #datatable_wrapper > .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .delivery-page-wrapper #datatable.table-odd {
            width: 100% !important;
            margin: 0 !important;
            background: #ffffff;
            border-collapse: separate !important;
            border-spacing: 0;
        }

        .delivery-page-wrapper #datatable.table-odd tbody > tr:nth-of-type(odd) {
            background-color: #ffffff !important;
        }

        .delivery-page-wrapper #datatable.table-odd tbody > tr:nth-of-type(even) {
            background-color: #f5f8ff !important;
        }

        .delivery-page-wrapper #datatable thead th {
            background: #d6ecee !important;
            color: #337374 !important;
            font-size: 14px !important;
            font-weight: 400 !important;
            text-align: center !important;
            vertical-align: middle !important;
            padding: 10px 12px !important;
            border-color: rgba(188, 206, 216, 0.65) !important;
        }

        .delivery-page-wrapper #datatable thead th:first-child,
        .delivery-page-wrapper #datatable thead th:last-child {
            background: #408385 !important;
            color: #ffffff !important;
        }

        .delivery-page-wrapper #datatable thead th:first-child {
            border-top-left-radius: 12px;
        }

        .delivery-page-wrapper #datatable thead th:last-child {
            border-top-right-radius: 12px;
        }

        .delivery-page-wrapper #datatable tbody td:first-child {
            font-weight: 700 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .delivery-page-wrapper #datatable tbody td:first-child > span {
            display: block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media screen and (max-width: 991px) {
            .delivery-page-wrapper .delivery-summary-grid.delivery-counters > .delivery-summary-item {
                flex: 1 1 calc(50% - 8px) !important;
                max-width: none;
                min-width: 0;
            }
        }

        @media screen and (max-width: 767px) {
            .delivery-page-wrapper {
                padding: 8px !important;
            }

            .delivery-page-wrapper .delivery-filter-form {
                margin-bottom: 16px;
            }

            .delivery-page-wrapper .cases-filter-card.delivery-filter-card {
                padding: 16px 14px 12px 20px !important;
                margin-bottom: 0 !important;
                top: 60px;
            }

            .delivery-page-wrapper .cases-filter-row {
                display: flex !important;
                flex-wrap: nowrap !important;
                margin: 0 -6px !important;
            }

            .delivery-page-wrapper .delivery-filter-row--dates {
                gap: 8px !important;
                padding-right: 0 !important;
            }

            .delivery-page-wrapper .delivery-filter-date-col {
                flex: 0 0 90px !important;
                width: 90px !important;
                max-width: 90px !important;
            }

            .delivery-page-wrapper .delivery-filter-apply-col {
                flex: 1 1 auto !important;
                width: auto !important;
                max-width: none !important;
                min-width: 0 !important;
            }

            .delivery-page-wrapper .delivery-filter-row--dates > .delivery-filter-apply-col {
                flex: 1 1 auto !important;
                width: auto !important;
                max-width: none !important;
            }

            .delivery-page-wrapper .delivery-filter-apply-col .cases-filter-btn {
                width: 100% !important;
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .delivery-page-wrapper .delivery-print-btn {
                display: none !important;
            }

            .delivery-page-wrapper .cases-filter-row .mb-2 {
                margin-bottom: 10px !important;
            }

            .delivery-page-wrapper .cases-filter-row .filter-label {
                font-size: 12px !important;
                margin-bottom: 6px !important;
            }

            .delivery-page-wrapper .delivery-summary-grid.delivery-counters {
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 6px !important;
            }

            .delivery-page-wrapper .delivery-summary-grid.delivery-counters > .delivery-summary-item {
                flex: 1 1 0 !important;
                max-width: none;
                min-width: 0;
            }

            .delivery-page-wrapper .materials-total-card.report-total-card.delivery-counter-card {
                padding: 8px 7px 8px 20px !important;
                min-width: 0 !important;
            }

            .delivery-page-wrapper .delivery-counter-copy {
                gap: 5px;
            }

            .delivery-page-wrapper .delivery-counter-card--total .delivery-counter-copy {
                align-items: center !important;
            }

            .delivery-page-wrapper .delivery-counter-card--total .materials-total-label {
                flex: 0 1 min-content;
                white-space: normal !important;
                text-align: left;
            }

            .delivery-page-wrapper .materials-total-label {
                font-size: clamp(8px, 2.35vw, 10px) !important;
                letter-spacing: 0.02em !important;
            }

            .delivery-page-wrapper .materials-total-amount {
                font-size: clamp(15px, 4.6vw, 19px) !important;
            }

            .delivery-page-wrapper #datatable thead th:nth-child(1),
            .delivery-page-wrapper #datatable tbody td:nth-child(1) {
                width: 19% !important;
            }

            .delivery-page-wrapper #datatable thead th:nth-child(2),
            .delivery-page-wrapper #datatable tbody td:nth-child(2) {
                width: 25% !important;
            }

            .delivery-page-wrapper #datatable thead th:nth-child(3),
            .delivery-page-wrapper #datatable tbody td:nth-child(3) {
                width: 26% !important;
            }
        }

        .delivery-page-wrapper #datatable td .status-badge {
            overflow: hidden !important;
        }

        .delivery-page-wrapper #datatable td .status-badge .sigma-badge-label,
        .delivery-page-wrapper #datatable td .status-badge .delivery-status-badge-label {
            display: block;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.2;
        }

        @media screen and (min-width: 768px) {
            .delivery-page-wrapper #datatable thead th:nth-child(1),
            .delivery-page-wrapper #datatable tbody td:nth-child(1) {
                width: calc(22% - 4px) !important;
            }

            .delivery-page-wrapper #datatable thead th:nth-child(5),
            .delivery-page-wrapper #datatable tbody td:nth-child(5) {
                width: calc(24% + 4px) !important;
                padding-left: 8px !important;
                padding-right: 8px !important;
            }

            .delivery-page-wrapper #datatable td .status-badge--desktop {
                width: 8.5em !important;
                min-width: 8.5rem !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                box-sizing: border-box !important;
            }
        }
    </style>
    @php
        $permissions = $permissions ?? safe_permissions();
    @endphp
    @php
        // Defensive defaults to prevent undefined variable errors when view is rendered without precomputed metrics
        $overdue = $deliveryMetrics['overdue'] ?? $overdue ?? 0;
        $numOfUnits = $deliveryMetrics['numOfUnits'] ?? $numOfUnits ?? 0;
    @endphp
    <div class="delivery-page-wrapper sigma-list-page">
        <form class="kt-form delivery-filter-form" method="GET" action="{{ route('delivery-schedule') }}">
            @csrf
            <div class="container full-width cases-filter-card delivery-filter-card sigma-list-filter-card">
                <div class="row cases-filter-row sigma-list-filter-row delivery-filter-row delivery-filter-row--dates">
                    <div class="delivery-filter-date-col mb-2">
                        <label for="delivery_from" class="form-label filter-label">
                            <i class="fas fa-calendar-alt"></i>
                            <span>From Date</span>
                        </label>
                        <x-ios-dtp
                                name="from"
                                id="delivery_from"
                                class="filter-input-global"
                                :value=" isset($data['from']) && !empty($data['from']) ? \Carbon\Carbon::parse($data['from'])->format('d M, Y') : '' "
                                mode="date"
                                :short-year-display="true"
                                :required="true"
                        />
                        @if ($errors->has('from'))
                            <span class="help-block" style="color: red">{{ $errors->first('from') }}</span>
                        @endif
                    </div>

                    <div class="delivery-filter-date-col mb-2">
                        <label for="delivery_to" class="form-label filter-label">
                            <i class="fas fa-calendar-alt"></i>
                            <span>To Date</span>
                        </label>
                        <x-ios-dtp
                                name="to"
                                id="delivery_to"
                                class="filter-input-global"
                                :value=" isset($data['to']) && !empty($data['to']) ? \Carbon\Carbon::parse($data['to'])->format('d M, Y') : '' "
                                mode="date"
                                :short-year-display="true"
                                :required="true"
                        />
                        @if ($errors->has('to'))
                            <span class="help-block" style="color: red">{{ $errors->first('to') }}</span>
                        @endif
                    </div>

                    <div class="delivery-filter-apply-col mb-2">
                        <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                            <span>Apply</span>
                        </button>
                    </div>
                </div>

                <button type="button" onclick="printResult()" class="btn delivery-print-btn " title="Print">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </form>
        <div class="delivery-summary-grid delivery-counters sigma-summary-grid">
            <div class="delivery-summary-item sigma-summary-item">
                <div class="materials-total-card report-total-card delivery-counter-card delivery-counter-card--total sigma-compact-summary-card">
                    <div class="delivery-counter-copy">
                        <span class="materials-total-label">Total Cases</span>
                        <div class="materials-total-value">
                            <span class="materials-total-amount sigma-summary-value--positive">{{ count($cases) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="delivery-summary-item sigma-summary-item">
                <div class="materials-total-card report-total-card delivery-counter-card delivery-counter-card--overdue sigma-compact-summary-card">
                    <div class="delivery-counter-copy">
                        <span class="materials-total-label">Overdue</span>
                        <div class="materials-total-value">
                            <span class="materials-total-amount sigma-summary-value--danger">{{ $overdue }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="delivery-summary-item sigma-summary-item">
                <div class="materials-total-card report-total-card delivery-counter-card delivery-counter-card--units sigma-compact-summary-card">
                    <div class="delivery-counter-copy">
                        <span class="materials-total-label"># of Units</span>
                        <div class="materials-total-value">
                            <span class="materials-total-amount sigma-summary-value--positive">{{ $numOfUnits }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <table id="datatable" class="table table-bordered dataTable no-footer sunriseTable table-odd sigma-list-table" role="grid"
                            aria-describedby="datatable_info">
                            <thead>
                                <tr class="" style="left: 0px;  !important;">
                                    <th class="sigma-col-shaded sigma-head-left"><span>Doctor </span></th>
                                    <th class="sigma-head-left"><span>Patient</span></th>
                                    <th class="sigma-col-shaded"><span>Date</span></th>
                                    <th><span>#</span></th>
                                    <th class="statusCol"><span>Status</span></th>


                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cases as $case)
                                    @php
                                        $isOverdue = (bool) ($case->schedule_is_overdue ?? false);
                                        $color = $case->schedule_color ?? '#595d6e';
                                    @endphp
                                    <tr data-row="{{ $case->id }}" class="odd clickable" data-toggle="modal"
                                        data-target="#deliveryActionsModal" data-case-id="{{ $case->id }}">

                                        <td class="sigma-col-shaded sigma-body-left {{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->client->name }}</span>
                                        </td>

                                        <td class="sigma-body-left {{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->patient_name }}</span>
                                        </td>
                                        <td class="sigma-col-shaded sigma-body-center {{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span class="delivery-datetime-single">
                                                <span class="delivery-time-part">
                                                    <span class="delivery-time-main">{{ $case->schedule_time_main ?? '-' }}</span>
                                                    @if (!empty($case->schedule_time_meridiem))
                                                        <span class="delivery-time-ampm">{{ $case->schedule_time_meridiem }}</span>
                                                    @endif
                                                </span>
                                                <span class="delivery-date-part">{{ $case->schedule_date_formatted ?? '-' }}</span>
                                            </span>
                                        </td>
                                        <td class="sigma-body-center {{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->schedule_units_amount ?? 0 }}</span>
                                        </td>
                                        <td class="sigma-body-center">
                                            <span class="badge {{ $case->schedule_status_desktop_class ?? 'badge-warning' }} middle status-badge sigma-status-width status-badge--desktop">
                                                <span class="sigma-badge-label delivery-status-badge-label">{{ $case->schedule_status_desktop ?? '-' }}</span>
                                            </span>
                                            @if (($case->schedule_status_mobile_class ?? '') === 'status-badge--delivery')
                                                <span class="badge middle status-badge sigma-status-width status-badge--mobile" style="background:#ffc414;color:#1f2a37;">
                                                    <span class="sigma-badge-label delivery-status-badge-label">{{ $case->schedule_status_mobile ?? 'Delivery' }}</span>
                                                </span>
                                            @else
                                                <span class="badge {{ $case->schedule_status_mobile_class ?? 'badge-primary' }} middle status-badge sigma-status-width status-badge--mobile">
                                                    <span class="sigma-badge-label delivery-status-badge-label">{{ $case->schedule_status_mobile ?? '-' }}</span>
                                                </span>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
        <div class='modal sigma-modal--delivery-schedule-actions sigma-modal--delivery-schedule-action sigma-dialog-overlay' tabindex='-1' role='dialog' id='deliveryActionsModal' data-backdrop='false' data-keyboard='true'>
            <input type='hidden' name='case_id' id='delivery-actions-case-id' value=''>
            <div class='modal-dialog modal-dialog-centered' role='document'>
                <div class='modal-content'>
                    <div class='modal-header case-preview-header'>
                        <x-sigma-close-button />
                    </div>
                    <div class='modal-body'>
                        <div class='case-summary-block'>
                            <div class='form-group row case-summary-row' style='margin-bottom: 0px'>
                                <div class='form-group col-6' style='margin-bottom: 0;padding-left: 0; padding-right: 0'>
                                    <label for='delivery-actions-doctor' class='patient-doctor-label'>Doctor:</label>
                                    <h5 id='delivery-actions-doctor' class='patient-doctor-names'>-</h5>
                                </div>
                                <div class='form-group col-6' style='margin-bottom: 0'>
                                    <label for='delivery-actions-patient' class='patient-doctor-label'>Patient:</label>
                                    <h5 id='delivery-actions-patient' class='patient-doctor-names'>-</h5>
                                </div>
                            </div>
                        </div>

                        <div class='form-group row delivery-jobs-section case-jobs-section'>
                            <div class='col-12'>
                                <label class='case-completion-dialog-label case-jobs-label'><b>Jobs:</b></label> <hr>
                                <div class='delivery-jobs-list sigma-case-jobs-list' id='delivery-actions-jobs'></div>
                            </div>
                        </div>
                        <div id='delivery-actions-notes-block' class='case-notes-section' style='display:none;'>
                            <hr>
                            <label class='case-completion-dialog-label case-notes-label'><b>Notes:</b></label>
                            <div id='delivery-actions-notes'></div>
                        </div>
                    </div>
                    <div class='modal-footer case-actions-footer'>
                        <div class='sigma-modal-actions'>
                            <hr class='case-summary-divider lower-divider'>
                            <div class='sigma-actions-row sigma-actions-row--top'>
                                <a id='delivery-actions-view-voucher' href='#' class='btn btn-info sigma-action-btn'>
                                    <span class='btn-icon'><i class='fas fa-print'></i></span><span class='btn-text'>Print Voucher</span>
                                </a>
                                <a id='delivery-actions-view-case' href='#' class='btn btn-info sigma-action-btn'>
                                    <span class='btn-icon'><i class='far fa-file-alt'></i></span><span class='btn-text'>View</span>
                                </a>
                            </div>
                            <div class='sigma-actions-grid'>
                                <div class='sigma-action-slot' id='delivery-actions-edit-case-wrap' style='display:none;'>
                                    <a id='delivery-actions-edit-case' href='#' class='btn btn-warning sigma-action-btn'>
                                        <span class='btn-icon'><i class='fa-solid fa-pen-to-square'></i></span><span class='btn-text'>Edit</span>
                                    </a>
                                </div>
                                <div class='sigma-action-slot' id='delivery-actions-edit-delivery-wrap' style='display:none;'>
                                    <button type='button' class='btn btn-danger sigma-action-btn'
                                        id='delivery-actions-edit-delivery'>
                                        <span class='btn-icon'><i class='fa-solid fa-pen-to-square'></i></span><span class='btn-text'>Edit Delivery Date</span>
                                    </button>
                                </div>
                            </div>
                            <div class='sigma-actions-row sigma-actions-row--cancel'>
                                <button type='button' class='btn btn-secondary sigma-action-btn' data-dismiss='modal'>Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (($permissions && $permissions->contains('permission_id', 110)) || optional(Auth()->user())->is_admin)
            <div class="modal fade sigma-modal--delivery-schedule-edit" tabindex="-1" role="dialog" id="deliveryEditModal" data-backdrop="false">
                <form id="delivery-edit-form" class="delivery-edit-form" action="{{ route('edit-delivery-date') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="delivery-edit-case-id" value="">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Delivery Time</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="sigma-delivery-edit-panel">
                                    <div class="sigma-delivery-edit-field">
                                        <label class="sigma-delivery-edit-label" for="delivery-edit-case-label">Case</label>
                                        <h5 id="delivery-edit-case-label" class="sigma-delivery-edit-case">-</h5>
                                    </div>
                                    <div class="sigma-delivery-edit-field">
                                        <label class="sigma-delivery-edit-label" for="delivery_edit_date_shared">Delivery Date</label>
                                        <x-ios-dtp name="delivery_date" id="delivery_edit_date_shared" :value="old('delivery_date', now()->format('Y-m-d\TH:i:s'))" :required="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <script>
        function printResult() {
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write( '<html><head><title>' + document.title + '</title>' );
            //noinspection JSAnnotator
            mywindow.document.write
            ( `<style>
                .kt-datatable__table, h2 {font-size:17px;font-weight: bold;  padding: 10px;width:100%;text-align:center;}
                .kt-datatable__body {font-size:17px;font-weight: normal;}
                body {padding:50px;}
                th, td {padding:8px;}
                table {border-collapse: collapse;}
                tr:nth-child(even) {background-color: #f2f2f2;}
                th {
                      background-color: #353535;
                      color: white;
                    }
                </style>
                 <body>
                <h1> Delivery Schedule </h1>

                @if (isset($data) && $data['from'] && $data['to'])
              <p>From <b>{{ $data['from'] }}</b> To <b>{{ $data['to'] }}</b> <br>  <b>{{ count($cases) }}</b> Cases</p>
                @endif

              <table border="1" class="kt-datatable__table">
                              <thead class="kt-datatable__head">
                              <tr class="kt-datatable__row" style="left: 0px;">
                                  <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Doctor Name</span></th>
                                  <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Patient Name</span></th>
                                  <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Delivery Date</span></th>
                                 <th class="kt-datatable__cell"><span class="middle" style="width: 33%; margin: auto; text-align: center">Status at print time</span></th>
                              </tr>
                              </thead>
                              <tbody  class="kt-datatable__body">
@foreach ($cases as $case)
            @php
                $color = $case->schedule_color ?? '#595d6e';
            @endphp
              <tr data-row="{{ $case->id }}" class="kt-datatable__row" style="color:{{ $color }}">

                                            <td ><span>{{ $case->client->name }}</span></td>

                                            <td ><span>{{ $case->patient_name }}</span></td>
              <td style="color:{{ $color }};">
                  <span style="font-weight:400;font-size:15px;">{{ $case->schedule_time_main ?? '-' }}</span>
                  @if (!empty($case->schedule_time_meridiem))
                      <span style="font-weight:400;font-size:13px;margin-left:2px;">{{ $case->schedule_time_meridiem }}</span>
                  @endif
                  <span style="font-weight:400;font-size:12px;"> / {{ $case->schedule_date_formatted ?? '-' }}</span>
                </td>

                                            <td >
              <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge {{ $case->schedule_status_desktop_class ?? 'badge-warning' }} middle">{{ $case->schedule_status_desktop ?? '-' }}</span></td> </tr>
                                @endforeach
              </tbody>
          </table>
          </body>
` );
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout( function () {
                mywindow.print();
                mywindow.close();
            } , 1000 );

            return true;
        }
    </script>

@endsection

@push('js')
    <!-- DataTables JS loaded globally in footer.blade.php -->
    <script type="text/javascript">
        const deliveryScheduleCases = @json($scheduleCaseData ?? []);

        function escapeDeliveryHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildDeliveryJobsMarkup(jobs) {
            if (!Array.isArray(jobs) || jobs.length === 0) {
                return '<div class="sigma-case-job-row sigma-case-job-card"><span class="sigma-case-job-cell sigma-case-job-cell--lead">-</span></div>';
            }

            return jobs.map(function(job) {
                const unit = String(job.unit_num || '').trim();
                const type = String(job.job_type_name || '').trim();
                const material = String(job.material_name || '').trim();
                const color = String(job.color && job.color !== '0' ? job.color : '').trim();
                const style = String(job.style && job.style !== 'None' ? job.style : '').trim();
                const styleParts = [style, job.implant_label || '', job.abutment_label || ''].filter(function(part) {
                    return String(part).trim() !== '';
                });
                const styleText = styleParts.join(' / ');
                const styleCode = style.toLowerCase().includes('bridge')
                    ? 'B'
                    : (style.toLowerCase().includes('single') || !unit.includes(',') ? 'S' : 'B');
                const tooltipParts = [
                    `Units: ${unit || '-'}`,
                    type ? `Type: ${type}` : '',
                    material ? `Material: ${material}` : '',
                    color ? `Color: ${color}` : '',
                    styleText ? `Style: ${styleText}` : ''
                ].filter(function(part) {
                    return String(part).trim() !== '';
                });
                const tooltipText = escapeDeliveryHtml(tooltipParts.join(' | '));

                return `
                    <div class="sigma-case-job-row sigma-case-job-card" tabindex="0" aria-label="${tooltipText}" data-job-tooltip="${tooltipText}">
                        <span class="sigma-case-job-cell sigma-case-job-cell--lead">${escapeDeliveryHtml(unit || '-')}</span>
                        <span class="sigma-case-job-cell sigma-case-job-cell--type">${escapeDeliveryHtml(type || '-')}</span>
                        <span class="sigma-case-job-cell sigma-case-job-cell--material">${escapeDeliveryHtml(material || '-')}</span>
                        <span class="sigma-case-job-cell sigma-case-job-cell--color">${escapeDeliveryHtml(color)}</span>
                        <span class="sigma-case-job-cell sigma-case-job-cell--style">${escapeDeliveryHtml(styleCode)}</span>
                        <span class="sigma-case-job-tooltip" aria-hidden="true">${tooltipText}</span>
                    </div>
                `;
            }).join('');
        }

        function getDeliveryNoteDirectionClass(text) {
            const noteValue = String(text || '');
            const hasArabic = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/u.test(noteValue);
            const hasLatin = /[A-Za-z]/u.test(noteValue);
            return hasArabic && hasLatin ? 'sigma-case-note-text--mixed' : 'sigma-case-note-text--ltr';
        }

        function getDeliveryNoteEmployee(header) {
            const matches = String(header || '').match(/\[([^\]]*)\]/g) || [];
            if (matches.length < 2) {
                return '-';
            }

            return matches[1].replace(/^\[/, '').replace(/\]$/, '') || '-';
        }

        function buildDeliveryDateNoteMarkup(note) {
            const deliveryDateMatch = String(note.text || '').match(/^Updated delivery date from \[(.*?)\] to \[(.*?)\]$/);
            const oldDeliveryDate = escapeDeliveryHtml(deliveryDateMatch && deliveryDateMatch[1] ? deliveryDateMatch[1] : '-');
            const newDeliveryDate = escapeDeliveryHtml(deliveryDateMatch && deliveryDateMatch[2] ? deliveryDateMatch[2] : '-');
            const deliveryEmployee = escapeDeliveryHtml(getDeliveryNoteEmployee(note.header));

            return `${deliveryEmployee} Updated D.Date [<span>${oldDeliveryDate}</span>] <span class="sigma-case-delivery-note-arrow" aria-hidden="true">&rarr;</span> [<span>${newDeliveryDate}</span>]`;
        }

        function buildDeliveryNotesMarkup(notes) {
            if (!Array.isArray(notes) || notes.length === 0) {
                return '';
            }

            const deliveryDateNotes = [];
            const regularNotes = [];

            notes.forEach(function(note) {
                if (/^Updated delivery date from \[(.*?)\] to \[(.*?)\]$/.test(String(note.text || ''))) {
                    deliveryDateNotes.push(note);
                    return;
                }

                regularNotes.push(note);
            });

            const regularNotesMarkup = regularNotes.map(function(note) {
                const header = escapeDeliveryHtml(note.header || '');
                const text = escapeDeliveryHtml(note.text || '');
                const noteDirectionClass = getDeliveryNoteDirectionClass(note.text);

                return `
                    <div class="form-control note-container"
                        style="height:fit-content;width:100%;margin-bottom: 8px;font-size:12px;padding:10px;display:block !important"
                        disabled>
                        <span class="noteHeader"
                            style="font-weight:600;display:block !important">${header}</span>
                        <span class="noteText sigma-case-note-text ${noteDirectionClass}"
                            style="display:block !important">${text}</span>
                    </div>
                `;
            }).join('');

            let deliveryDateNotesMarkup = '';
            if (deliveryDateNotes.length > 0) {
                const latestDeliveryDateNote = deliveryDateNotes[deliveryDateNotes.length - 1];
                const deliveryNotesListMarkup = deliveryDateNotes.map(function(note) {
                    return `<div class="sigma-case-delivery-note">${buildDeliveryDateNoteMarkup(note)}</div>`;
                }).join('');

                deliveryDateNotesMarkup = `
                    <details class="sigma-case-delivery-notes-box">
                        <summary class="sigma-case-delivery-notes-summary">
                            <span class="sigma-case-delivery-note">${buildDeliveryDateNoteMarkup(latestDeliveryDateNote)}</span>
                            <i class="fas fa-chevron-down sigma-case-delivery-notes-chevron" aria-hidden="true"></i>
                        </summary>
                        <div class="sigma-case-delivery-notes-list">
                            ${deliveryNotesListMarkup}
                        </div>
                    </details>
                `;
            }

            return regularNotesMarkup + deliveryDateNotesMarkup;
        }

        $(document).ready(function() {
            $('#datatable').DataTable({
                "colResize": true,
                "ordering": false,
                "pageLength": 25,
                "searching": false,
                "lengthChange": false,
                "columnDefs": [
                    {
                        "width": "10%",
                        "targets": 3
                    },
                    {
                        "width": "20%",
                        "targets": 4
                    }
                ]
            });

            $('#deliveryActionsModal').appendTo('body');
            $('#deliveryEditModal').appendTo('body');
        });

        $(document).on('show.bs.modal', '#deliveryActionsModal', function(event) {
            document.body.classList.add('sigma-dialog-scroll-unlocked');

            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const caseId = String(trigger.dataset.caseId || '');
            const caseData = deliveryScheduleCases[caseId];
            if (!caseData) {
                return;
            }

            document.getElementById('delivery-actions-case-id').value = caseData.id || '';
            document.getElementById('delivery-actions-doctor').textContent = caseData.doctor_name || '-';
            document.getElementById('delivery-actions-patient').textContent = caseData.patient_name || '-';
            document.getElementById('delivery-actions-jobs').innerHTML = buildDeliveryJobsMarkup(caseData.jobs);

            const notesBlock = document.getElementById('delivery-actions-notes-block');
            const notesContainer = document.getElementById('delivery-actions-notes');
            const notesMarkup = buildDeliveryNotesMarkup(caseData.notes);
            notesContainer.innerHTML = notesMarkup;
            notesBlock.style.display = notesMarkup ? '' : 'none';

            document.getElementById('delivery-actions-view-voucher').href = caseData.view_voucher_url || '#';
            document.getElementById('delivery-actions-view-case').href = caseData.view_case_url || '#';

            const editCaseWrap = document.getElementById('delivery-actions-edit-case-wrap');
            const editCaseLink = document.getElementById('delivery-actions-edit-case');
            if (caseData.can_edit_case && caseData.edit_case_url) {
                editCaseLink.href = caseData.edit_case_url;
                editCaseWrap.style.display = '';
            } else {
                editCaseLink.href = '#';
                editCaseWrap.style.display = 'none';
            }

            const editDeliveryWrap = document.getElementById('delivery-actions-edit-delivery-wrap');
            const editDeliveryButton = document.getElementById('delivery-actions-edit-delivery');
            if (caseData.can_edit_delivery) {
                editDeliveryButton.dataset.caseId = caseData.id || '';
                editDeliveryButton.dataset.caseLabel = `${caseData.doctor_name || '-'} - ${caseData.patient_name || '-'}`;
                editDeliveryButton.dataset.deliveryDate = caseData.delivery_date_iso || '';
                editDeliveryWrap.style.display = '';
            } else {
                delete editDeliveryButton.dataset.caseId;
                delete editDeliveryButton.dataset.caseLabel;
                delete editDeliveryButton.dataset.deliveryDate;
                editDeliveryWrap.style.display = 'none';
            }
        });

        $(document).on('hidden.bs.modal', '#deliveryActionsModal', function() {
            document.body.classList.remove('sigma-dialog-scroll-unlocked');
        });

        $(document).on('show.bs.modal', '#deliveryEditModal', function(event) {
            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const data = trigger.dataset;
            const input = document.getElementById('delivery_edit_date_shared');

            document.getElementById('delivery-edit-case-id').value = data.caseId || '';
            document.getElementById('delivery-edit-case-label').textContent = data.caseLabel || '-';

            if (input) {
                input.value = data.deliveryDate || '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });

        $(document).on('click', '#deliveryActionsModal, #deliveryEditModal', function(event) {
            if ($(event.target).closest('.modal-dialog').length === 0) {
                $(this).modal('hide');
            }
        });

        $(document).on('click', '#delivery-actions-edit-delivery', function() {
            const trigger = this;
            const actionsModal = $('#deliveryActionsModal');
            const editModal = $('#deliveryEditModal');

            $('body').addClass('sigma-delivery-modal-replacing');

            actionsModal.one('hidden.bs.modal.deliveryEdit', function() {
                editModal
                    .one('shown.bs.modal.deliveryEdit', function() {
                        $('body').removeClass('sigma-delivery-modal-replacing');
                    })
                    .one('hidden.bs.modal.deliveryEdit', function() {
                        $('body').removeClass('sigma-delivery-modal-replacing');
                    })
                    .modal('show', trigger);
            });

            actionsModal.modal('hide');
        });
    </script>
@endpush
