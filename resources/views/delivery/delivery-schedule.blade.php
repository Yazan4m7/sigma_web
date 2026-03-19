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
            background-color: #f0f3f6 !important;
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

        /* Jobs list (table-like layout without table element) */
        .job-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 4px 10px;
            background: linear-gradient(180deg, #fdfefe 0%, #f4f6f8 100%);
            border: 1px solid #e3e6ea;
            border-radius: 8px;
            padding: 8px 10px;
            margin-top: 6px;
        }

        .job-grid-row {
            display: contents;
        }

        .job-grid-cell {
            font-size: 12px;
            color: #202733;
            padding: 4px 0;
            border-bottom: 1px solid #e8ecf0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .job-grid-row:last-of-type .job-grid-cell {
            border-bottom: none;
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

        .status-badge {
            width: auto !important;
            min-width: 130px !important;
            display: inline-block !important;
            white-space: nowrap !important;
            text-align: center !important;
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
                font-weight: 700 !important;
            }

            /* Mobile column width split */
            #datatable thead th:nth-child(1),
            #datatable tbody td:nth-child(1) {
                width: 28% !important; /* Doctor */
                direction: rtl;
            }

            #datatable thead th:nth-child(2),
            #datatable tbody td:nth-child(2) {
                width: 28% !important; /* Patient */

                direction: rtl;
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

            .status-badge {
                width: 97% !important;
                min-width: 97% !important;
                max-width: 97% !important;
                font-size: 11px !important;
                padding: 2.2px 5px !important;
                white-space: nowrap !important;
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

        .delivery-page-wrapper .cases-filter-card.delivery-filter-card {
            background: #ffffff !important;
            border: 1px solid rgba(188, 206, 216, 0.3);
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            margin: 0 !important;
            padding: 20px 20px 16px !important;
            position: sticky;
            top: 70px;
            z-index: 5;
            overflow: visible;
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
            --cases-filter-radius: 10px;
            --cases-filter-border: 1px solid rgba(188, 206, 216, 0.4);
            --cases-filter-padding: 8px 14px;
            padding: 0 !important;
            margin: 0 -8px !important;
            align-items: flex-end;
            font-family: "Tajawal", "Cairo", "Noto Sans Arabic", "Segoe UI", Tahoma, sans-serif;
            gap: 0 !important;
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
            width: 100%;
            min-height: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 16px !important;
            border-radius: 12px !important;
            font-size: 14px !important;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: all 0.3s ease;
        }

        .delivery-page-wrapper .cases-filter-btn--search {
            background: linear-gradient(135deg, #5f7688 0%, #8faab8 100%) !important;
            background-color: #7790a0 !important;
            border: 1px solid #5f7688 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(95, 118, 136, 0.28);
        }

        .delivery-page-wrapper .cases-filter-btn--search:hover,
        .delivery-page-wrapper .cases-filter-btn--search:focus {
            background: linear-gradient(135deg, #516879 0%, #7f9dab 100%) !important;
            background-color: #688392 !important;
            border-color: #516879 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(81, 104, 121, 0.26);
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
            background-color: #f0f3f6 !important;
        }

        .delivery-page-wrapper #datatable thead th {
            background: #d6ecee !important;
            color: #337374 !important;
            font-size: 14px !important;
            font-weight: 700 !important;
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
    </style>
    @php
        $permissions = safe_permissions();
    @endphp
    @php
        // Defensive defaults to prevent undefined variable errors when view is rendered without precomputed metrics
        $overdue = $overdue ?? 0;
        $numOfUnits = $numOfUnits ?? 0;
    @endphp
    <div class="delivery-page-wrapper sigma-list-page">
        <form class="kt-form delivery-filter-form" method="GET" action="{{ route('delivery-schedule') }}">
            @csrf
            <div class="container full-width cases-filter-card delivery-filter-card sigma-list-filter-card">
                <div class="row cases-filter-row sigma-list-filter-row">
                    <div class="col-6 col-md-3 mb-2">
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

                    <div class="col-6 col-md-3 mb-2">
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

                    <div class="col-6 col-md-3 mb-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                            <i class="fas fa-search"></i>
                            <span>Apply</span>
                        </button>
                    </div>

                    <div class="col-6 col-md-3 mb-2 d-flex align-items-end justify-content-end">
                        <button type="button" onclick="printResult()" class="btn delivery-print-btn sigma-toolbar-icon-btn" title="Print">
                            <i class="fas fa-print me-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @php

        $date = new DateTime();
        $date2 = $date->modify('+1 day');
        $date3 = $date->modify('+2 day');
        $endofToday = substr(now()->addDays(0), 0, 10) . 'T23:59:00';
        $endofTomorrow = substr(now()->addDays(1), 0, 10) . 'T23:59:00';
        $endofSeventhDay = substr(now()->addDays(7), 0, 10) . 'T23:59:00';

        // Pre-calc delivery metrics for reuse
        $overdue = 0;
        $numOfUnits = 0;
        foreach ($cases as $case) {
            $numOfUnits += $case->unitsAmount();
            if (strtotime($case->initial_delivery_date) < strtotime('now')) {
                $overdue++;
            }
        }
    @endphp

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
                                    <th><span>Doctor </span></th>
                                    <th><span>Patient</span></th>
                                    <th><span>Date</span></th>
                                    <th><span>#</span></th>
                                    <th class="statusCol"><span>Status</span></th>


                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($cases as $case)
                                    @php
                                        $status = $case->status();
                                        $isOverdue = strtotime($case->initial_delivery_date) < strtotime('now');
                                        $color = $isOverdue ? 'red' : '#595d6e';
                                        $rawStatus = trim((string) $status);

                                        $stageText = $rawStatus;
                                        if (Str::contains($rawStatus, 'Active in')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active in'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                                        } elseif (Str::contains($rawStatus, 'Active')) {
                                            $stageText = trim(Str::after($rawStatus, 'Active'));
                                        } elseif (Str::contains($rawStatus, 'In-Progress')) {
                                            $stageText = trim(Str::after($rawStatus, 'In-Progress'));
                                        }

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

                                        $formattedActiveStatus = $assigneeInitials !== ''
                                            ? (trim($stageText) . '/ ' . $assigneeInitials)
                                            : trim($stageText);
                                        if ($formattedActiveStatus === '') {
                                            $formattedActiveStatus = $rawStatus;
                                        }

                                        $stageLabelMobile = trim($stageText);
                                        if ($stageLabelMobile === '') {
                                            $stageLabelMobile = $rawStatus;
                                        }
                                        if (strcasecmp($stageLabelMobile, '3D Printing') === 0) {
                                            $stageLabelMobile = '3DPrint..';
                                        }

                                        $waitingStage = $rawStatus;
                                        if (Str::contains($rawStatus, 'Waiting in')) {
                                            $waitingStage = trim(Str::after($rawStatus, 'Waiting in'));
                                        } elseif (Str::contains($rawStatus, 'Waiting')) {
                                            $waitingStage = trim(Str::after($rawStatus, 'Waiting'));
                                        }
                                        $waitingStage = trim($waitingStage);
                                        if ($waitingStage === '') {
                                            $waitingStage = $rawStatus;
                                        }

                                        $waitingStageMobile = $waitingStage;
                                        if (strcasecmp($waitingStageMobile, '3D Printing') === 0) {
                                            $waitingStageMobile = '3DPrint..';
                                        }

                                        $deliveryAssigned = false;
                                        $firstJob = $case->jobs->first();
                                        if ($firstJob && (int) $firstJob->stage === 8 && $firstJob->assignee !== null && $firstJob->delivery_accepted === null) {
                                            $deliveryAssigned = true;
                                        }

                                    @endphp
                                    <tr data-row="{{ $case->id }}" class="odd clickable" data-toggle="modal"
                                        data-target="#actionsDialog{{ $case->id }}">

                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->client->name }}</span>
                                        </td>

                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->patient_name }}</span>
                                        </td>
                                        @php
                                            $date = explode('T', $case->initial_delivery_date);
                                            $timeMain = isset($date[1]) ? date('g:i', strtotime($date[1])) : '-';
                                            $timeMeridiem = isset($date[1]) ? date('A', strtotime($date[1])) : '';
                                            $dateFormatted = isset($date[0]) ? date('d M', strtotime($date[0])) : '-';
                                        @endphp
                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span class="delivery-datetime-single">
                                                <span class="delivery-time-part">
                                                    <span class="delivery-time-main">{{ $timeMain }}</span>
                                                    @if ($timeMeridiem !== '')
                                                        <span class="delivery-time-ampm">{{ $timeMeridiem }}</span>
                                                    @endif
                                                </span>
                                                <span class="delivery-date-part">{{ $dateFormatted }}</span>
                                            </span>
                                        </td>
                                        <td class="{{ $isOverdue ? 'text-overdue' : '' }}" style="color:{{ $color }} !important">
                                            <span>{{ $case->unitsAmount() }}</span>
                                        </td>
                                        <td>
                                            @if (str_contains($status, 'Completed'))
                                                <span class="badge badge-success middle status-badge sigma-status-width status-badge--desktop">Completed</span>
                                            @elseif(str_contains($status, 'Active'))
                                                <span class="badge badge-primary middle status-badge sigma-status-width status-badge--desktop">{{ $formattedActiveStatus }}</span>
                                            @elseif(str_contains($status, 'In-Progress'))
                                                <span class="badge badge-primary middle status-badge sigma-status-width status-badge--desktop">{{ $formattedActiveStatus }}</span>
                                            @elseif(str_contains($status, 'Waiting'))
                                                <span class="badge badge-danger middle status-badge sigma-status-width status-badge--desktop">{{ $waitingStage }}</span>
                                            @else
                                                <span class="badge badge-warning middle status-badge sigma-status-width status-badge--desktop">{{ $status }}</span>
                                            @endif

                                            @if ($deliveryAssigned)
                                                <span class="badge middle status-badge sigma-status-width status-badge--mobile" style="background:#ffc414;color:#1f2a37;">Delivery</span>
                                            @elseif (str_contains($status, 'Waiting'))
                                                <span class="badge badge-danger middle status-badge sigma-status-width status-badge--mobile">{{ $waitingStageMobile }}</span>
                                            @else
                                                <span class="badge badge-primary middle status-badge sigma-status-width status-badge--mobile">{{ $stageLabelMobile }}</span>
                                            @endif
                                        </td>

                                    </tr>
                                    @if (($permissions && $permissions->contains('permission_id', 110)) || Auth()->user()->is_admin)
                                        <div class="modal sigma-modal--delivery-schedule-edit" tabindex="-1" role="dialog" id="myModal{{ $case->id }}">
                                            <form action="{{ route('edit-delivery-date') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $case->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Deli. Date</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group row">
                                                                <div class="form-group col-6">
                                                                    <label for="milled">Case:</label>
                                                                    <h5>{{ $case->client->name }} -
                                                                        {{ $case->patient_name }}</h5>
                                                                    </br>
                                                                    <label for="milled">Delivery Date</label>
                                                                    @php
                                                                        $time = $case->initial_delivery_date;
                                                                        $time = str_replace(' ', 'T', $time);
                                                                    @endphp
{{--                                                                    <input class="form-control SDTP" name="delivery_date"--}}
{{--                                                                        type="text" value="{{ $time }}"--}}
{{--                                                                        required readonly />--}}
                                                                    <x-ios-dtp name="delivery_date" id="delivery_date_{{ $case->id }}" :value="  $time  ?? ''  " :required="true" />

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Save
                                                                changes</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                    <div class="modal sigma-modal--delivery-schedule-actions" tabindex="-1" role="dialog"
                                        id="actionsDialog{{ $case->id }}">

                                        <input type="hidden" name="case_id" value="{{ $case->id }}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Case Actions</h5>

                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                    <div class="form-group row" style="margin-bottom: 0px">
                                                        <div class="form-group col-6 " style="margin-bottom: 0px">
                                                            <label for="doctor">Doctor: </label>
                                                            <h5 id="doctor"><b>{{ $case->client->name }}</b></h5>
                                                        </div>
                                                        <div class="form-group col-6 " style="margin-bottom: 0px">
                                                            <label for="pat">Patient: </label>
                                                            <h5 id="pat"><b>{{ $case->patient_name }}</b></h5>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="form-group row">
                                                        <div class=" col-12 ">
                                                            <label><b>Jobs:</b></label><br>

                                                            <div class="job-grid">
                                                                @foreach ($case->jobs as $job)
                                                                    <div class="job-grid-row">
                                                                        <span class="job-grid-cell" title="{{ $job->unit_num ?? '-' }}">{{ $job->unit_num ?? '-' }}</span>
                                                                        @php $jobTypeName = optional($job->jobType)->name ?? ($job->type ? 'Type '.$job->type : '-'); @endphp
                                                                        <span class="job-grid-cell" title="{{ $jobTypeName }}">{{ $jobTypeName }}</span>
                                                                        <span class="job-grid-cell" title="{{ $job->material->name ?? '-' }}">{{ $job->material->name ?? '-' }}</span>
                                                                        @php $jobColor = is_null($job->color) ? '' : trim($job->color); @endphp
                                                                        <span class="job-grid-cell" title="{{ $jobColor !== '' ? $jobColor : '-' }}">{{ $jobColor !== '' ? $jobColor : '-' }}</span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if (count($case->notes) > 0)
                                                        <hr>
                                                        <label><b>Notes:</b></label><br>
                                                        @foreach ($case->notes as $note)
                                                            <div class="form-control"
                                                                style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px"
                                                                disabled>

                                                                <span
                                                                    class="noteHeader">{{ '[' . substr($note->created_at, 0, 16) . '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br>
                                                                <span class="noteText">{{ $note->note }}</span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                                <div class="modal-footer fullBtnsWidth">
                                                    <div class="row"
                                                        style=" margin-right: 0px; margin-left: 0px;width:100%">


                                                        <div class="row">
                                                            <!-------------------------
                                                                                           ------ View Voucher ------
                                                                                           -------------------------->
                                                            <div class="col-6 padding5px">
                                                                <a href="{{ route('view-voucher', $case->id) }}">
                                                                    <button type="button" class="btn btn-info "><i
                                                                            class="fas fa-print"></i> View Voucher
                                                                    </button>
                                                                </a>
                                                            </div>

                                                            <!-------------------------
                                                                                    -------- View Case --------
                                                                                    -------------------------->
                                                            <div class="col-6 padding5px">
                                                                <a
                                                                    href="{{ route('view-case', ['id' => $case->id, 'stage' => -2]) }}">
                                                                    <button type="button" class="btn btn-info "><i
                                                                            class="far fa-file-alt"></i> View Case
                                                                    </button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                            <!-------------------------
                                                                                                  -------- Edit CASE --------
                                                                                                  -------------------------->
                                                            @if (Auth()->user()->is_admin ||
                                                                    ($permissions && $permissions->contains('permission_id', 102)) ||
                                                                    (($permissions && (!isset($case->actual_delivery_date) && $permissions->contains('permission_id', 115))) ||
                                                                        (isset($case->jobs[0]) && $case->jobs[0]->stage == 1 && $permissions->contains('permission_id', 1))))
                                                                @if (!$case->locked)
                                                                    <div class="col-6 padding5px">
                                                                        <a
                                                                            href="{{ route('edit-case-view', $case->id) }}">
                                                                            <button type="button"
                                                                                class="btn btn-warning "><i
                                                                                    class="fa-solid fa-pen-to-square"></i>
                                                                                Edit</button>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                            @if (($permissions && $permissions->contains('permission_id', 110)) || Auth()->user()->is_admin)
                                                                <div class="col-6 padding5px">

                                                                    <button type="button" class="btn btn-danger "
                                                                        data-dismiss="modal" data-toggle="modal"
                                                                        data-target="#myModal{{ $case->id }}"><i
                                                                            class="fa-solid fa-pen-to-square"></i> Edit
                                                                        Delivery Date</button>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="col-12 padding5px">
                                                            <button type="button" class="btn btn-secondary "
                                                                data-dismiss="modal" style="width:100%">Cancel</button>
                                                        </div>
                                                    </div>


                                                </div>



                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </tbody>
                        </table>
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
                $status = $case->status();
                $isOverdue = strtotime($case->initial_delivery_date) < strtotime('now');
                $color = $isOverdue ? 'red' : '#595d6e';
                $rawStatus = trim((string) $status);

                $stageText = $rawStatus;
                if (Str::contains($rawStatus, 'Active in')) {
                    $stageText = trim(Str::after($rawStatus, 'Active in'));
                } elseif (Str::contains($rawStatus, 'In-Progress in')) {
                    $stageText = trim(Str::after($rawStatus, 'In-Progress in'));
                } elseif (Str::contains($rawStatus, 'Active')) {
                    $stageText = trim(Str::after($rawStatus, 'Active'));
                } elseif (Str::contains($rawStatus, 'In-Progress')) {
                    $stageText = trim(Str::after($rawStatus, 'In-Progress'));
                }

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

                $formattedActiveStatus = $assigneeInitials !== ''
                    ? (trim($stageText) . '/ ' . $assigneeInitials)
                    : trim($stageText);
                if ($formattedActiveStatus === '') {
                    $formattedActiveStatus = $rawStatus;
                }

                $waitingStage = $rawStatus;
                if (Str::contains($rawStatus, 'Waiting in')) {
                    $waitingStage = trim(Str::after($rawStatus, 'Waiting in'));
                } elseif (Str::contains($rawStatus, 'Waiting')) {
                    $waitingStage = trim(Str::after($rawStatus, 'Waiting'));
                }
                $waitingStage = trim($waitingStage);
                if ($waitingStage === '') {
                    $waitingStage = $rawStatus;
                }

            @endphp
              <tr data-row="{{ $case->id }}" class="kt-datatable__row" style="color:{{ $color }}">

                                            <td ><span>{{ $case->client->name }}</span></td>

                                            <td ><span>{{ $case->patient_name }}</span></td>
            @php
                $date = explode('T', $case->initial_delivery_date);
                $timeMain = isset($date[1]) ? date('g:i', strtotime($date[1])) : '-';
                $timeMeridiem = isset($date[1]) ? date('A', strtotime($date[1])) : '';
                $dateFormatted = isset($date[0]) ? date('D d, M', strtotime($date[0])) : '-';
            @endphp
              <td style="color:{{ $color }};">
                  <span style="font-weight:700;font-size:15px;">{{ $timeMain }}</span>
                  @if ($timeMeridiem !== '')
                      <span style="font-weight:700;font-size:13px;margin-left:2px;">{{ $timeMeridiem }}</span>
                  @endif
                  <span style="font-weight:500;font-size:12px;"> / {{ $dateFormatted }}</span>
                </td>

                                            <td >
                                                    @if (str_contains($status, 'Completed'))
              <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-success middle">Completed</span>
@elseif (str_contains($status, 'In-Progress') || str_contains($status, 'Active'))
              <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-primary middle">{{ $formattedActiveStatus }}</span>
@elseif (str_contains($status, 'Waiting'))
              <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-danger middle">{{ $waitingStage }}</span>
                                                                                                            @else
              <span style="font-size:12px !important;width: 160px; margin: auto; text-align: center" class="badge badge-danger middle">Unknown</span>
@endif</td> </tr>
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
        });



    </script>
@endpush
