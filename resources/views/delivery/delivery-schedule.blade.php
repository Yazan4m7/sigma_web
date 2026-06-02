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
            background: var(--surface-bg);
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
                background: var(--surface-bg) !important;
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
                width: auto !important;
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
            flex-direction: column;
            gap: 4px;
            width: 100%;
        }

        .delivery-page-wrapper .materials-total-label {
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: 0.6px !important;
            text-transform: uppercase !important;
            color: #6b7280 !important;
            margin-bottom: 0 !important;
            line-height: 1.05 !important;
        }

        .delivery-page-wrapper .materials-total-value {
            display: flex !important;
            align-items: baseline !important;
            gap: 8px !important;
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
                padding: 16px 14px 12px !important;
                margin-bottom: 0 !important;
                top: 60px;
            }

            .delivery-page-wrapper .cases-filter-row {
                display: flex !important;
                flex-wrap: wrap !important;
                margin: 0 -6px !important;
            }

            .delivery-page-wrapper .cases-filter-row > [class*="col-"] {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }

            .delivery-page-wrapper .delivery-filter-row > .col-6 {
                flex: 0 0 50% !important;
                max-width: 50% !important;
            }

            .delivery-page-wrapper .cases-filter-row .mb-2 {
                margin-bottom: 10px !important;
            }

            .delivery-page-wrapper .cases-filter-row .filter-label {
                font-size: 12px !important;
                margin-bottom: 6px !important;
            }

            .delivery-page-wrapper .delivery-summary-grid.delivery-counters > .delivery-summary-item {
                flex: 1 1 100% !important;
                max-width: none;
                min-width: 0;
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
                    <div class="col-6 mb-2">
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
                                :required="true"
                        />
                        @if ($errors->has('from'))
                            <span class="help-block" style="color: red">{{ $errors->first('from') }}</span>
                        @endif
                    </div>

                    <div class="col-6 mb-2">
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
                                :required="true"
                        />
                        @if ($errors->has('to'))
                            <span class="help-block" style="color: red">{{ $errors->first('to') }}</span>
                        @endif
                    </div>

                </div>

                <div class="row cases-filter-row sigma-list-filter-row delivery-filter-row delivery-filter-row--actions">
                    <div class="col-6 mb-2 d-flex align-items-end sigma-filter-action-col">
                        <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                            <i class="fas fa-search"></i>
                            <span>Apply</span>
                        </button>
                    </div>

                    <div class="col-6 mb-2 d-flex align-items-end justify-content-end sigma-filter-secondary-col">
                        <button type="button" onclick="printResult()" class="btn delivery-print-btn sigma-toolbar-icon-btn" title="Print">
                            <i class="fas fa-print me-1"></i>
                        </button>
                    </div>
                </div>
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
        <div class="modal sigma-modal--delivery-schedule-actions" tabindex="-1" role="dialog" id="deliveryActionsModal">
            <input type="hidden" name="case_id" id="delivery-actions-case-id" value="">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Case Actions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row" style="margin-bottom: 0px">
                            <div class="form-group col-6" style="margin-bottom: 0px">
                                <label for="delivery-actions-doctor">Doctor: </label>
                                <h5 id="delivery-actions-doctor"><b>-</b></h5>
                            </div>
                            <div class="form-group col-6" style="margin-bottom: 0px">
                                <label for="delivery-actions-patient">Patient: </label>
                                <h5 id="delivery-actions-patient"><b>-</b></h5>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row delivery-jobs-section">
                            <div class="col-12">
                                <label class="case-completion-dialog-label"><b>Jobs:</b></label>
                                <div class="delivery-jobs-list" id="delivery-actions-jobs"></div>
                            </div>
                        </div>
                        <div id="delivery-actions-notes-block" style="display:none;">
                            <hr>
                            <label class="case-completion-dialog-label"><b>Notes:</b></label><br>
                            <div id="delivery-actions-notes"></div>
                        </div>
                    </div>
                    <div class="modal-footer fullBtnsWidth">
                        <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%">
                            <div class="row">
                                <div class="col-6 padding5px">
                                    <a id="delivery-actions-view-voucher" href="#">
                                        <button type="button" class="btn btn-info"><i class="fas fa-print"></i> View Voucher</button>
                                    </a>
                                </div>
                                <div class="col-6 padding5px">
                                    <a id="delivery-actions-view-case" href="#">
                                        <button type="button" class="btn btn-info"><i class="far fa-file-alt"></i> View Case</button>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 padding5px" id="delivery-actions-edit-case-wrap" style="display:none;">
                                    <a id="delivery-actions-edit-case" href="#">
                                        <button type="button" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                                    </a>
                                </div>
                                <div class="col-6 padding5px" id="delivery-actions-edit-delivery-wrap" style="display:none;">
                                    <button type="button" class="btn btn-danger"
                                        id="delivery-actions-edit-delivery"
                                        data-dismiss="modal" data-toggle="modal"
                                        data-target="#deliveryEditModal">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit Delivery Date
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 padding5px">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (($permissions && $permissions->contains('permission_id', 110)) || optional(Auth()->user())->is_admin)
            <div class="modal sigma-modal--delivery-schedule-edit" tabindex="-1" role="dialog" id="deliveryEditModal">
                <form id="delivery-edit-form" action="{{ route('edit-delivery-date') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="delivery-edit-case-id" value="">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Deli. Date</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row">
                                    <div class="form-group col-6">
                                        <label for="delivery-edit-case-label">Case:</label>
                                        <h5 id="delivery-edit-case-label">-</h5>
                                        </br>
                                        <label for="delivery_edit_date_shared">Delivery Date</label>
                                        <x-ios-dtp name="delivery_date" id="delivery_edit_date_shared" :value="old('delivery_date', now()->format('Y-m-d\TH:i:s'))" :required="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                return '<div class="delivery-job-row delivery-job-empty">-</div>';
            }

            return jobs.map(function(job) {
                const parts = [
                    job.unit_num || '',
                    job.job_type_name || '',
                    job.material_name || '',
                    job.color && job.color !== '0' ? job.color : '',
                    job.style && job.style !== 'None' ? job.style : '',
                    job.implant_label || '',
                    job.abutment_label || ''
                ].filter(function(part) {
                    return String(part).trim() !== '';
                }).map(function(part) {
                    return escapeDeliveryHtml(part);
                });

                const primaryText = parts.length > 0 ? parts.join(' - ') : '-';

                return `
                    <div class="delivery-job-row">
                        <span class="delivery-job-primary" title="${primaryText}">${primaryText}</span>
                    </div>
                `;
            }).join('');
        }

        function buildDeliveryNotesMarkup(notes) {
            if (!Array.isArray(notes) || notes.length === 0) {
                return '';
            }

            return notes.map(function(note) {
                const header = escapeDeliveryHtml(note.header || '');
                const text = escapeDeliveryHtml(note.text || '');

                return `
                    <div class="form-control" style="height:fit-content;width:80%;background-color:#dcecfd59;margin-bottom:5px;color:black;font-size:12px" disabled>
                        <span class="noteHeader">${header}</span><br>
                        <span class="noteText">${text}</span>
                    </div>
                `;
            }).join('');
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
    </script>
@endpush
