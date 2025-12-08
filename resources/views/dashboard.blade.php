@extends('layouts.app', ['pageSlug' => 'Home'])

@push('css')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css"
          referrerpolicy="no-referrer">
@endpush

@section('content')
    <style>
        /**:not(.modal-backdrop):not(.modal) {*/
        /*    z-index: 1 !important;*/
        /*}*/


        .pageTitleContainer {

            background: rgba(62, 62, 62, 0);
        }
        #datatable_wrapper{
            

        }
        /*.modal{*/
        /*    position: absolute;*/
        /*    right:0;*/
        /*    left:0;*/
        /*    top: 0;*/
        /*    bottom: 0;*/
        /*    z-index: 99999999;*/
        /*    margin:0;*/
        /*}*/
        /*.modal{*/
        /*    z-index: 99999999;*/

        /*}*/

        /*.modal {*/
        /*    z-index: 99999999 !important;*/
        /*}*/

        /*.modal-backdrop {*/
        /*    z-index: 99999998 !important;*/
        /*}*/
        .modal{
            z-index: 99999999;
        }

        /* Generic dialog popup styling (used by payments, deliveries, etc.) */
        .dialog-popup-content {
            display: inline-block;
            width: auto;
            min-width: min(calc(100% - 24px), 486px);
            max-width: min(calc(100% - 24px), 720px);
            margin: 0 auto;
        }
        .dialog-mfp .mfp-content {
            text-align: center;
        }
        .dialog-popup-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            color: #32325d;
            position: relative;
            width: 100%;
        }
        .dialog-popup-header {
            display: none !important;
            padding: 14px 16px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
            padding-right: 56px;
        }
        .dialog-popup-close {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #ffffffbf;
            color: #000000;
            border: none;
            box-shadow: inset 0 0 0 1px #e3e3e3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
            line-height: 1;
            opacity: 1;
            padding: 0;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
            position: absolute;
            top: 10px;
            right: 12px;
            z-index: 2;
        }
        .dialog-popup-close:hover {
            background: #e5e7eb;
            color: #111827;
            transform: translateY(-1px);
        }
        .dialog-popup-body {
            padding: 27px 25px 11px 24px;
            max-height: 65vh;
            overflow-y: auto;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
        .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
        .dialog-popup-footer {
            padding: 12px 20px 18px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .dialog-popup-footer .btn {
            padding-left: calc(0.75rem + 25px);
            padding-right: calc(0.75rem + 25px);
        }
        .dialog-popup-meta {
            display: block;
            text-align: left;
            padding-left: 18px;
            font-size: 11px;
            color: #6c757d;
            padding-top: 1px;
            border-top: 1px solid #f1f3f5;
        }
        /* Better info layout */
        .payment-info-grid {
            display: flex;
            flex-direction: column;
            gap: 0;
        }
        .payment-info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 5px 0;
            border-bottom: 1px solid #f1f3f5;
        }
        .payment-info-row:last-child {
            border-bottom: 0;
        }
        .payment-info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #6c757d;
            font-weight: 700;
            text-align: left;
        }
        .payment-info-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }
        .payment-info-value-input {
            display: flex;
            justify-content: flex-end;
            width: 100%;
        }
        .payment-info-value-input .form-control {
            width: auto;
            max-width: 100%;
            /*min-width: 220px;*/
            text-align: right;
            font-weight: 600;
            color: #1f2937;
            background-color: #f8f9fa;
            border-color: #e5e7eb;
            border-radius: 12px;
            box-shadow: none;

        }

        /* iOS scroll containment */
        .mfp-wrap,
        .mfp-bg {
            overscroll-behavior: contain;
            touch-action: none;
        }
        body.mfp-open {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
        @supports (-webkit-touch-callout: none) {
            .mfp-wrap,
            .mfp-bg {
                position: fixed !important;
                width: 100%;
                height: 100%;
            }
        }


        .card {
            padding: 5px;
        }

        .row {
            padding: 5px;
        }

        .navbar .navbar-brand {
            /*font-family: 'Black Ops One', cursive !important;*/
            /*font-size: 2rem !important;*/
            margin-top: 0;
        }

        .pageTitleContainer {
            /*text-align: center;*/
            /*background:none;*/

        }

        .card-title {
            font-weight: bold !important;
        }

        /* Cases & Units Btns colors : */
        .btn-primary.bar.active {}

        @media screen and (max-width: 768px) {

            .main-panel,
            .content {
                padding-left: 0px !important;
                padding-right: 0px !important;
            }

            .main-panel>.content {
                margin: 0px;

            }

        }

        @media screen and (max-width: 991px) {
            .main-panel>.content {
                margin-top: 60px;
                height: fit-content;
            }
        }

        .barsBtns,
        .performanceBtns {
            background-color: #2b7b7d !important;
            border-color: #2b7b7d !important;
        }

        .barsBtns.active,
        .performanceBtns.active {
            background-color: #1e5253 !important;
            border-color: #1e5253 !important;
        }

        .barsBtns:hover,
        .performanceBtns:hover {
            background-color: #4daeb0 !important;
            border-color: #4daeb0 !important;
        }

        .barsBtns:focus,
        .performanceBtns:focus {
            /*box-shadow: 0 0 0 .2, shadow: rgba(89 141 142);*/
        }

        /* Device image container styles */
        .device-container {
            height: calc(100vh - 400px);
            /* Adjust height to match left menu */
            overflow-y: auto;
            padding: 15px;
        }

        .device-image {
            max-width: 250px;
            /* Limit image width */
            max-height: 200px;
            /* Limit image height */
            object-fit: contain;
            margin: 10px auto;
            display: block;
        }

        .device-card {
            margin-bottom: 15px;
            text-align: center;
        }
        /* Prevent horizontal scroll on small screens for summary tables */
        .sunriseTable {
            table-layout: fixed;
            width: 100%;
        }
        .sunriseTable th,
        .sunriseTable td {
            white-space: normal !important;
            word-break: break-word;
        }
        .summary-table-responsive {
            overflow-x: hidden;
        }
        .summary-table-responsive table {
            margin-bottom: 0;
        }
        .delivery-popup .dialog-popup-body .container {
            padding-left: 0;
            padding-right: 0;
        }
        .delivery-popup .dialog-popup-body .row + .row {
            margin-top: 10px;
        }
        .delivery-popup hr {
            margin: 12px 0;
            border-color: #e5e7eb;
        }
        /* Keep datetime picker above modal/backdrop */
        .bootstrap-datetimepicker-widget,
        .xdsoft_datetimepicker {
            z-index: 100000000 !important;
        }
    </style>
    {{-- <div class="row"  style="background-color: transparent"> --}}
    {{-- <h2 class="subheader-title"> --}}
    {{-- <i class="fa-solid fa-chart-area"></i><b> Main </b><span >Dashboard</span> --}}
    {{-- <small> --}}
    {{-- </small> --}}
    {{-- </h2> --}}
    {{-- </div> --}}
    <div class="row" style="background-color: transparent">
        <div class="col-lg-6 noLeftPadding">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent">
                        <div class="col-sm-7 text-left">
                            <h4 class="card-title" style="">Completed in 7 Days</h4>


                        </div>
                        <div class="col-sm-5">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple bar active barsBtns"
                                    id="completedChartCases">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple bar barsBtns" id="completedChartUnits">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Cases</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="completedChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent;padding:0">
                        <div class="col-sm-12 text-left">
                            <h4 class="card-title" style="">Cases/Units Currently in-work</h4>

                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <div id="chartContainer" style="height: 100%; width: 100%;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row noLeftPadding" style="background-color: transparent">
        <div class="col-12 noLeftPadding">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent">
                        <div class="col-sm-6 text-left">

                            <h4 class="card-title">Monthly Performance</h4>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active performanceBtns" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Cases</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="3">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Sales</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-money-bill-trend-up"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns" id="2">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Payments</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-regular fa-money-bill-1"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartBig1"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" >
        <div class="col-lg-6 col-md-12 noLeftPadding">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Payments Collected Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table id="datatable" class="datatable hover compact stripe sunriseTable" style="width:100%">
                            <colgroup>
                                <col style="width:30%">
                                <col style="width:15%">
                                <col style="width:15%">
                                <col style="width:20%">
                                <col style="width:20%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th>
                                        Doctor
                                    </th>
                                    <th>
                                        Payment
                                    </th>
                                    <th class="text-center">
                                        Collector
                                    </th>
                                    <th class="text-center">
                                        Time Collected
                                    </th>
                                    <th>
                                        Received by
                                    </th>
                                </tr>
                            </thead>
            <tbody>
                                @foreach ($paymentsReceivedToday as $payment)
                                    <tr class="clickable dialog-popup-trigger payment-popup-trigger"
                                        data-popup-id="payment-popup-{{ $payment->id }}">

                                        <td>
                                            {{ $payment->client->name }}
                                        </td>
                                        <td>
                                            {{ $payment->amount }} JOD
                                        </td>
                                        <td class="text-center">
                                            {{ $payment->collectorUserRecord->name_initials }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('g:i a', strtotime(substr(str_replace('T', ' ', $payment->recieved_on), 0, -3))) }}

                                        </td>
                                        <td>

                                            @if ($payment->receivedBy)
                                                <span style="color:green">{{ $payment->receivedBy->name_initials }}</span>
                                            @else
                                                <span style="color:red">NONE</span>
                                            @endif

                                        </td>
                                    </tr>

                                    <x-dialog-popup
                                        :id="'payment-popup-'.$payment->id"
                                        title="Receive Payment"
                                        :meta="'PAYMENT ID : '.$payment->id"
                                        closeAttrs='aria-label="Close"'
                                    >
                                        <div class="payment-info-grid">
                                            <div class="payment-info-row">
                                                <div class="payment-info-label">Doctor</div>
                                                <div class="payment-info-value">{{ $payment->client->name }}</div>
                                            </div>
                                            <div class="payment-info-row">
                                                <div class="payment-info-label">Collected from doctor by</div>
                                                <div class="payment-info-value">{{ $payment->collectorFullName() }}</div>
                                            </div>
                                            <div class="payment-info-row">
                                                <div class="payment-info-label">Payment Amount</div>
                                                <div class="payment-info-value">{{ $payment->amount }} JOD</div>
                                            </div>
                                            <div class="payment-info-row">
                                                <div class="payment-info-label">Collected On</div>
                                                <div class="payment-info-value">{{ $payment->created_at }}</div>
                                            </div>
                                            @if ($payment->isCollected())
                                                <div class="payment-info-row">
                                                    <div class="payment-info-label">Received On</div>
                                                    <div class="payment-info-value">{{ $payment->recieved_on }}</div>
                                                </div>
                                                <div class="payment-info-row">
                                                    <div class="payment-info-label">Received by</div>
                                                    <div class="payment-info-value">{{ $payment->receiverFullName() }}</div>
                                                </div>
                                            @endif
                                            <div class="payment-info-row">
                                                <div class="payment-info-label">Payment Method</div>
                                                <div class="payment-info-value">{{ $payment->notes }}</div>
                                            </div>
                                            @if ($payment->additional_notes)
                                                <div class="payment-info-row">
                                                    <div class="payment-info-label">Notes</div>
                                                    <div class="payment-info-value">{{ $payment->additional_notes }}</div>
                                                </div>
                                            @endif

                                        </div>
                    @isset($footer)
                        {{-- noop --}}
                    @endisset
                    <x-slot name="footer">
                        <button type="button" class="btn btn-secondary dialog-popup-dismiss">Close</button>
                        @if (!$payment->isCollected())
                            <a href="{{ route('receive-payment', $payment->id) }}" class="btn btn-danger">Receive</a>
                        @endif
                    </x-slot>
                </x-dialog-popup>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Deliveries Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table class="datatable compact hover stripe sunriseTable" id="datatable2">
                            <colgroup>
                                <col style="width:28%">
                                <col style="width:32%">
                                <col style="width:20%">
                                <col style="width:20%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th>
                                        Doctor
                                    </th>
                                    <th>
                                        Patient name
                                    </th>
                                    <th class="text-center">
                                        Delivery time
                                    </th>
                                    <th class="text-center">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($DeliveriesToday as $case)
                                    <tr class="clickable dialog-popup-trigger delivery-popup-trigger"
                                        data-popup-id="delivery-popup-{{ $case->id }}">

                                        <td>
                                            {{ $case->client->name }}
                                        </td>
                                        <td>
                                            {{ $case->patient_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('g:i a', strtotime(str_replace('T', ' ', $case->initial_delivery_date))) }}

                                        </td>
                                        <td>
                                            @php
                                                $status = $case->status();
                                                $active = true;
                                                if (str_contains($status, 'Waiting')) {
                                                    $active = false;
                                                }
                                                $stageLabel = trim(str_replace(['Waiting in', 'Waiting', 'Active in', 'Active'], '', $status));
                                                $deliveryJob = $case->jobs->where('stage', 8)->first();
                                                $assigned = $deliveryJob && $deliveryJob->assignedTo ? $deliveryJob->assignedTo->name_initials : null;
                                            @endphp

                                            @if ($active)
                                                <span style="width:auto; margin: auto; text-align: center"
                                                    class="badge badge-primary">
                                                    {{ $assigned ? $assigned . ' / ' : '' }}{{ $stageLabel ?: $status }}
                                                </span>
                                            @else
                                                <span style="width:auto; margin: auto; text-align: center"
                                                    class="badge badge-danger">
                                                    {{ $stageLabel ?: $case->status() }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($DeliveriesToday as $case)
        @php
            $time = date('Y-m-d g:i a', strtotime($case->initial_delivery_date));
            $formId = 'delivery-form-' . $case->id;
        @endphp
        <x-dialog-popup
            :id="'delivery-popup-'.$case->id"
            title="Update Delivery Date"
            :meta="'CASE ID : '.$case->id"
            wrapperClass="mfp-hide dialog-popup-content delivery-popup"
        >
            <form id="{{ $formId }}" action="{{ route('edit-delivery-date') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $case->id }}">
                <div class="payment-info-grid">
                    <div class="payment-info-row">
                        <div class="payment-info-label">Doctor</div>
                        <div class="payment-info-value">{{ $case->client->name }}</div>
                    </div>
                    <div class="payment-info-row">
                        <div class="payment-info-label">Patient Name</div>
                        <div class="payment-info-value">{{ $case->patient_name }}</div>
                    </div>
                    <div class="payment-info-row">
                        <div class="payment-info-label">Current Delivery Time</div>
                        <div class="payment-info-value payment-info-value-input">
                            <input class="form-control SDTP" name="delivery_date" type="text"
                                value="{{ $time }}" required readonly />
                        </div>
                    </div>
                </div>
            </form>
            <x-slot name="footer">
                <button type="button" class="btn btn-secondary dialog-popup-dismiss">Close</button>
                <button type="submit" class="btn btn-danger" form="{{ $formId }}">UPDATE</button>
            </x-slot>
        </x-dialog-popup>
    @endforeach
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/canvasjs.min.js"></script>
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" referrerpolicy="no-referrer"></script>


    <script>
        $(document).ready(function() {
            const hasChartJs = typeof Chart !== 'undefined';
            const hasCanvasJs = typeof CanvasJS !== 'undefined';

            if (hasCanvasJs && document.getElementById('chartContainer')) {
                initDoughnutChart();
            }

            if (hasChartJs && document.getElementById('completedChart')) {
                initComp7DaysChart();
            }

            if (hasChartJs && document.getElementById('chartBig1')) {
                initPerformanceChart();
            }

            // Magnific popup helper for all dialog triggers
            const applyDialogAnimation = (content, animationClass) => {
                if (!content || !content.length) {
                    return;
                }
                const card = content.find('.dialog-popup-card');
                if (!card.length) {
                    return;
                }
                card.removeClass('animate__fadeInDown animate__fadeOutDown')
                    .addClass('animate__animated ' + animationClass);
            };

            const registerPopupTriggers = (selector) => {
                $(selector).on('click', function(e) {
                    e.preventDefault();
                    const targetId = $(this).data('popup-id');
                    if (!targetId) {
                        return;
                    }
                    $.magnificPopup.open({
                        items: {
                            src: '#' + targetId,
                            type: 'inline'
                        },
                        mainClass: 'dialog-mfp',
                        closeBtnInside: true,
                        showCloseBtn: false,
                        fixedContentPos: true,
                        fixedBgPos: true,
                        overflowY: 'auto',
                        removalDelay: 280,
                        callbacks: {
                            open: function() {
                                applyDialogAnimation(this.content, 'animate__fadeInDown');
                            },
                            beforeClose: function() {
                                applyDialogAnimation(this.content, 'animate__fadeOutDown');
                            },
                            afterClose: function() {
                                if (this.content) {
                                    this.content.find('.dialog-popup-card')
                                        .removeClass('animate__animated animate__fadeInDown animate__fadeOutDown');
                                }
                            }
                        }
                    });
                });
            };

            registerPopupTriggers('.dialog-popup-trigger');

            $(document).on('click', '.dialog-popup-dismiss', function(e) {
                e.preventDefault();
                if (typeof $.magnificPopup !== 'undefined') {
                    $.magnificPopup.close();
                }
            });

            $('.datatable').DataTable({
                "pageLength": 50,
                "searching": false,
                "lengthChange": false,
                "ordering": false,
                "paging": false,
                "autoWidth": false,
                "columnDefs": [
                    { "targets": -1, "className": "text-center" }
                ]
            });
        });

        function initComp7DaysChart() {
            const completedChartElement = document.getElementById("completedChart");
            if (!completedChartElement || !completedChartElement.getContext) {
                return;
            }
            var completedChartData = {
                "Cases": ['{!! implode("','", $compCasesCount7Days) !!}'],
                "Units": ['{!! implode("','", $compUnitsCount7Days) !!}']
            };

            var barChartConfiguration = {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: '#f5f5f5',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest"
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.1)',
                            zeroLineColor: "transparent",
                        },
                        ticks: {
                            suggestedMin: 20,
                            suggestedMax: 0,
                            padding: 20,
                            fontColor: "#9e9e9e"
                        }
                    }],

                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9e9e9e"
                        }
                    }]
                }
            };

            var ctx = completedChartElement.getContext("2d");

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, 'rgba(29,140,248,0.2)');
            gradientStroke.addColorStop(0.4, 'rgba(29,140,248,0.0)');
            gradientStroke.addColorStop(0, 'rgba(29,140,248,0)'); //blue colors

            var options1 = {
                type: 'bar',
                responsive: true,
                legend: {
                    display: false
                },
                data: {
                    labels: ['{!! implode("','", $last7DaysLabels) !!}'],
                    datasets: [{
                        label: "Completed Units",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: '#1f8ef1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: completedChartData['Units']
                    }]
                },
                options: barChartConfiguration
            };
            var options2 = {
                type: 'bar',
                responsive: true,
                legend: {
                    display: false
                },
                data: {
                    labels: ['{!! implode("','", $last7DaysLabels) !!}'],
                    datasets: [{
                        label: "Completed Cases",
                        fill: true,
                        backgroundColor: gradientStroke,
                        hoverBackgroundColor: gradientStroke,
                        borderColor: '#1f8ef1',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 0.0,
                        data: completedChartData['Cases']
                    }]
                },
                options: barChartConfiguration
            };
            var completedChart = new Chart(ctx, options1);

            $("#completedChartCases").click(function() {

                completedChart.destroy();
                completedChart = new Chart(ctx, options1);
            });
            $("#completedChartUnits").click(function() {

                completedChart.destroy();
                completedChart = new Chart(ctx, options2);
            });
        }

        function initDoughnutChart() {
            const chartContainer = document.getElementById("chartContainer");
            if (!chartContainer || typeof CanvasJS === 'undefined') {
                return;
            }
            var doughnetChartData = {
                "Units": [{
                        y: {!! $CompletedJobsToday !!},
                        name: "Completed"
                    },
                    {
                        y: {!! $ActiveJobsToday !!},
                        name: "Active"
                    },
                    {
                        y: {!! $waitingJobsToday !!},
                        name: "Waiting"
                    }

                ]
            };
            CanvasJS.addColorSet("greenShades",
                [ //colorSet Array

                    "#37b44a",
                    "#007bff",
                    "#dc3545"
                ]);
            var options = {

                exportFileName: "Active/Waiting/Completed Chart",
                exportEnabled: false,
                animationEnabled: true,
                animationDuration: 800,
                colorSet: "greenShades",
                //                title:{
                //                    text: "Monthly Expense"
                //                },
                legend: {
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "doughnut",
                    innerRadius: 50,
                    indexLabelTextAlign: "center",
                    //indexLabelWrap: true,

                    indexLabelPlacement: "outside",
                    indexLabelFontColor: "black",
                    showInLegend: false,
                    toolTipContent: "<b>{name}</b>: {y} (#percent%)",
                    indexLabel: "{name}",
                    dataPoints: doughnetChartData["Units"]
                }]

            };

            var compWaitingChart = new CanvasJS.Chart("chartContainer",
                options);

            compWaitingChart.render();




            function explodePie(e) {
                if (typeof(e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e
                        .dataPointIndex].exploded) {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
                } else {
                    e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
                }
                e.chart.render();
            }

        }

        function initPerformanceChart() {
            const chartBig = document.getElementById("chartBig1");
            if (!chartBig || !chartBig.getContext || typeof Chart === 'undefined') {
                return;
            }

            gradientChartOptionsConfigurationWithTooltipPurple = {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },

                tooltips: {
                    backgroundColor: '#f5f5f5',
                    titleFontColor: '#333',
                    bodyFontColor: '#666',
                    bodySpacing: 4,
                    xPadding: 12,
                    mode: "nearest",
                    intersect: 0,
                    position: "nearest",
                    callbacks: {
                        label: function(tooltipItems, data) {
                            return tooltipItems.yLabel + ' ' + data.datasets[tooltipItems.datasetIndex].label;
                        }
                    }
                },
                responsive: true,
                scales: {
                    yAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(29,140,248,0.0)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            suggestedMin: 20,
                            suggestedMax: 0,
                            padding: 20,
                            fontColor: "#9a9a9a",

                        }
                    }],

                    xAxes: [{
                        barPercentage: 1.6,
                        gridLines: {
                            drawBorder: false,
                            color: 'rgba(225,78,202,0.1)',
                            zeroLineColor: "transparent"
                        },
                        ticks: {
                            padding: 20,
                            fontColor: "#9a9a9a",
                            fontStyle: 'bold'
                        }
                    }]
                }
            };
            var chart_labels = ['{!! implode("', '", $last30DaysLabels) !!}'];

            var performanceChartData = {
                "Cases": ['{!! implode("','", $compCasesCount30Days) !!}'],
                "Units": ['{!! implode("','", $compUnitsCount30Days) !!}'],
                "Income": ['{!! implode("','", $collectionsInLast30Days) !!}'],
                "Sales": ['{!! implode("','", $sales30Days) !!}']
            };


            var ctx = chartBig.getContext('2d');

            var gradientStroke = ctx.createLinearGradient(0, 230, 0, 50);

            gradientStroke.addColorStop(1, 'rgba(72,72,176,0.1)');
            gradientStroke.addColorStop(0.4, 'rgba(72,72,176,0.0)');
            gradientStroke.addColorStop(0, 'rgba(55, 180, 74,0)'); //purple colors
            var config = {
                type: 'line',
                data: {
                    labels: chart_labels,
                    datasets: [{
                        label: "Units",

                        fill: true,
                        backgroundColor: gradientStroke,
                        borderColor: '#31b72f',
                        borderWidth: 2,
                        borderDash: [],
                        borderDashOffset: 15.0,
                        pointBackgroundColor: '#226746',
                        pointBorderColor: 'rgba(255,255,255,0)',
                        //                       pointHoverBackgroundColor: '#d346b1',
                        pointBorderWidth: 20,
                        //                       pointHoverRadius: 4,
                        //                        pointHoverBorderWidth: 15,
                        pointRadius: 5,
                        data: performanceChartData["Units"]
                    }]
                },
                options: gradientChartOptionsConfigurationWithTooltipPurple
            };
            var myChartData = new Chart(ctx, config);
            $("#0").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Units"];
                data.datasets[0].label = "Units";

                myChartData.update();
            });
            $("#1").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Cases"];
                data.datasets[0].label = "Cases";

                myChartData.update();
            });

            $("#2").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Income"];
                data.datasets[0].label = "JOD Collected Payments";

                myChartData.update();
            });
            $("#3").click(function() {
                var data = myChartData.config.data;
                data.datasets[0].data = performanceChartData["Sales"];
                data.datasets[0].label = "JOD";
                myChartData.update();
            });

        }
    </script>


    <script>
        const animateCSS = (element, animation, prefix = 'animate__') =>
            // We create a Promise and return it
            new Promise((resolve, reject) => {
                const animationName = `${prefix}${animation}`;
                const node = document.querySelector(element);

                node.classList.add(`${prefix}animated`, animationName);

                // When the animation ends, we clean the classes and resolve the Promise
                function handleAnimationEnd(event) {
                    event.stopPropagation();
                    node.classList.remove(`${prefix}animated`, animationName);
                    resolve('Animation ended');
                }

                node.addEventListener('animationend', handleAnimationEnd, {once: true});
            });
    </script>
@endpush
