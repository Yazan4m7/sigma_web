@extends('layouts.app', ['pageSlug' => 'Home'])

@push('css')
    @php
        $dashboardHomeCssPath = public_path('assets/css/pages/dashboard-home.css');
        $dashboardHomeCssUrl = asset('assets/css/pages/dashboard-home.css');
        if (file_exists($dashboardHomeCssPath)) {
            $dashboardHomeCssUrl .= '?v=' . filemtime($dashboardHomeCssPath);
        }
    @endphp
    <link rel="stylesheet" href="{{ $dashboardHomeCssUrl }}" />
@endpush

@section('content')

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
                                <label class="btn btn-sm btn-primary btn-simple bar active barsBtns main-dashboard-toggle-btn"
                                    id="completedChartCases">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple bar barsBtns main-dashboard-toggle-btn"
                                    id="completedChartUnits">
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
        <div class="col-lg-6 noLeftPadding">
            <div class="card card-chart" style="height: 100% !important;">
                <div class="card-header ">
                    <div class="row" style="background-color: transparent;padding:0">
                        <div class="col-sm-12 text-left">
                            <h4 class="card-title" style="">Cases/Units Currently in-work</h4>

                        </div>
                    </div>

                </div>
                <div class="card-body dashboard-inwork-card-body">
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
                                <label class="btn btn-sm btn-primary btn-simple active performanceBtns main-dashboard-toggle-btn"
                                    id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Units</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-boxes-stacked"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns main-dashboard-toggle-btn"
                                    id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Cases</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-box"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns main-dashboard-toggle-btn"
                                    id="3">
                                    <input type="radio" class="d-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Sales</span>
                                    <span class="d-block d-sm-none">
                                        <i class="fa-solid fa-money-bill-trend-up"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple performanceBtns main-dashboard-toggle-btn"
                                    id="2">
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
<div class="row" style="background-color: transparent">
        <div class="col-lg-6 col-md-12 noLeftPadding" style="background-color: transparent">
            <div class="card card-chart dashboard-summary-panel">
                <div class="card-header">
                    <h4 class="card-title">Payments Collected Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table id="datatable" class="datatable hover compact stripe sunriseTable main-dashboard-summary-table" style="width:100%">
                            <colgroup>
                                <col style="width:23.4%">
                                <col style="width:18.3%">
                                <col style="width:18.3%">
                                <col style="width:20%">
                                <col style="width:20%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Doctor</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Payment</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Collector</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Time Collected</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Received by</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paymentsReceivedToday as $payment)
                                    <tr class="clickable" data-toggle="modal"
                                        data-target="#payment-modal-shared"
                                        data-payment-id="{{ $payment->id }}"
                                        data-payment-doctor="{{ $payment->client->name }}"
                                        data-payment-collector="{{ $payment->collectorFullName() }}"
                                        data-payment-amount="{{ $payment->amount }}"
                                        data-payment-collected-on="{{ $payment->created_at }}"
                                        data-payment-is-collected="{{ $payment->isCollected() ? '1' : '0' }}"
                                        data-payment-received-on="{{ $payment->recieved_on }}"
                                        data-payment-receiver="{{ $payment->receiverFullName() }}"
                                        data-payment-method="{{ str_replace(["\r", "\n"], ' ', (string) $payment->notes) }}"
                                        data-payment-notes="{{ str_replace(["\r", "\n"], ' ', (string) $payment->additional_notes) }}"
                                        data-payment-receive-url="{{ $payment->isCollected() ? '' : route('receive-payment', $payment->id) }}">

                                        <td class="text-center">
                                            {{ $payment->client->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $payment->amount }} JOD
                                        </td>
                                        <td class="text-center">
                                            {{ $payment->collectorUserRecord->name_initials }}
                                        </td>
                                        <td class="text-center">
                                            {{ optional($payment->created_at)->format('g:i a') ?? '-' }}

                                        </td>
                                        <td class="text-center">

                                            @if ($payment->receivedBy)
                                                <span style="color:green">{{ $payment->receivedBy->name_initials }}</span>
                                            @else
                                                <span style="color:red">NONE</span>
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
        <div class="col-lg-6 col-md-12">
            <div class="card card-chart dashboard-summary-panel">
                <div class="card-header">
                    <h4 class="card-title">Deliveries Today</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive summary-table-responsive">
                        <table class="datatable compact hover stripe sunriseTable main-dashboard-summary-table" id="datatable2">
                            <colgroup>
                                <col style="width:28%">
                                <col style="width:24%">
                                <col style="width:22%">
                                <col style="width:26%">
                            </colgroup>
                            <thead>
                                <tr>

                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Doctor</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Patient name</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Delivery time</span>
                                    </th>
                                    <th class="text-center" style="background:#408385 !important;color:#ffffff !important;border-color:#408385 !important;">
                                        <span class="summary-table-heading">Status</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($DeliveriesToday as $case)
                                    <tr class="clickable" data-toggle="modal"
                                        data-target="#delivery-modal-shared"
                                        data-case-id="{{ $case->id }}"
                                        data-case-doctor="{{ $case->client->name }}"
                                        data-case-patient="{{ $case->patient_name }}"
                                        data-case-view-url="{{ route('view-case', $case->id) }}"
                                        data-delivery-date="{{ \Carbon\Carbon::parse($case->initial_delivery_date)->format('Y-m-d\TH:i:s') }}">

                                        <td class="text-center">
                                            {{ $case->client->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $case->patient_name }}
                                        </td>
                                        <td class="text-center">
                                            {{ date('g:i a', strtotime(str_replace('T', ' ', $case->initial_delivery_date))) }}

                                        </td>
                                        <td class="main-dashboard-summary-status-cell">
                                            <span
                                                class="badge {{ $case->dashboard_status_class ?? 'badge-warning' }} sigma-status-width main-dashboard-summary-status-badge">
                                                {{ $case->dashboard_status_text ?? $case->status() }}
                                            </span>
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
    @if ($paymentsReceivedToday->isNotEmpty())
        <div class="modal fade" id="payment-modal-shared" tabindex="-1" role="dialog"
            aria-labelledby="paymentModalLabelShared" aria-hidden="true" style="z-index: 1009999 !important">
            <div class="modal-dialog modal-dialog-centered" role="document" style="z-index: 1009999 !important">
                <div class="modal-content" style="z-index: 1009999 !important">
                    <div class="modal-header" style="z-index: 1009999 !important">
                        <h5 class="modal-title" id="paymentModalLabelShared">Receive Payment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="z-index: 1009999 !important">
                        <div class="payment-info-grid">
                            <div class="payment-info-row">
                                <div class="payment-info-label">Doctor</div>
                                <div class="payment-info-value" id="shared-payment-doctor">-</div>
                            </div>
                            <div class="payment-info-row">
                                <div class="payment-info-label">Collected from doctor by</div>
                                <div class="payment-info-value" id="shared-payment-collector">-</div>
                            </div>
                            <div class="payment-info-row">
                                <div class="payment-info-label">Payment Amount</div>
                                <div class="payment-info-value" id="shared-payment-amount">-</div>
                            </div>
                            <div class="payment-info-row">
                                <div class="payment-info-label">Collected On</div>
                                <div class="payment-info-value" id="shared-payment-collected-on">-</div>
                            </div>
                            <div class="payment-info-row d-none" id="shared-payment-received-on-row">
                                <div class="payment-info-label">Received On</div>
                                <div class="payment-info-value" id="shared-payment-received-on">-</div>
                            </div>
                            <div class="payment-info-row d-none" id="shared-payment-receiver-row">
                                <div class="payment-info-label">Received by</div>
                                <div class="payment-info-value" id="shared-payment-receiver">-</div>
                            </div>
                            <div class="payment-info-row">
                                <div class="payment-info-label">Payment Method</div>
                                <div class="payment-info-value" id="shared-payment-method">-</div>
                            </div>
                            <div class="payment-info-row d-none" id="shared-payment-notes-row">
                                <div class="payment-info-label">Notes</div>
                                <div class="payment-info-value" id="shared-payment-notes">-</div>
                            </div>
                        </div>
                        <small class="text-muted">PAYMENT ID : <span id="shared-payment-id">-</span></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="#" id="shared-payment-receive-link" class="btn btn-danger d-none">Receive</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($DeliveriesToday->isNotEmpty())
        <div class="modal fade" id="delivery-modal-shared" tabindex="-1" role="dialog"
            aria-labelledby="deliveryModalLabelShared" aria-hidden="true" style="z-index: 1009999">
            <div class="modal-dialog modal-dialog-centered" role="document" style="z-index: 1009999">
                <div class="modal-content" style="z-index: 1009999">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deliveryModalLabelShared">Update Delivery Date</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="z-index: 1009999">
                        <form id="delivery-form-shared" action="{{ route('edit-delivery-date') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="shared-delivery-case-id" value="">
                            <div class="payment-info-grid">
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Doctor</div>
                                    <div class="payment-info-value" id="shared-delivery-doctor">-</div>
                                </div>
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Patient Name</div>
                                    <div class="payment-info-value" id="shared-delivery-patient">-</div>
                                </div>
                                <div class="payment-info-row">
                                    <div class="payment-info-label">Current Delivery Time</div>
                                    <div class="payment-info-value payment-info-value-input">
                                        <x-ios-dtp name="delivery_date" id="dashboard_delivery_date_shared"
                                            :value="old('delivery_date', now()->format('Y-m-d\TH:i:s'))" :required="true" />
                                    </div>
                                </div>
                            </div>
                        </form>
                        <small class="text-muted">CASE ID : <span id="shared-delivery-case-label">-</span></small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="#" id="shared-delivery-view-link" class="btn btn-info">View</a>
                        <button type="submit" class="btn btn-danger" form="delivery-form-shared">UPDATE</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/canvasjs.min.js"></script>
    <script src="{{ asset('white') }}/js/plugins/chartjs.min.js"></script>

    <script>
        $(document).ready(function() {
            const hasChartJs = typeof Chart !== 'undefined';
            const hasCanvasJs = typeof CanvasJS !== 'undefined';

            const bootDashboardCharts = function() {
                if (hasCanvasJs && document.getElementById('chartContainer')) {
                    initDoughnutChart();
                }

                if (hasChartJs && document.getElementById('completedChart')) {
                    initComp7DaysChart();
                }

                if (hasChartJs && document.getElementById('chartBig1')) {
                    initPerformanceChart();
                }
            };

            if ('requestIdleCallback' in window) {
                window.requestIdleCallback(bootDashboardCharts, { timeout: 300 });
            } else {
                window.requestAnimationFrame(function() {
                    window.setTimeout(bootDashboardCharts, 0);
                });
            }

            $('.datatable').DataTable({
                "pageLength": 50,
                "searching": false,
                "lengthChange": false,
                "ordering": false,
                "paging": false,
                "autoWidth": false,
                "columnDefs": [{
                    "targets": -1,
                    "className": "text-center"
                }]
            });

            // iOS fix: Move modals to body to escape stacking context
            $('[id^="payment-modal-"], [id^="delivery-modal-"]').appendTo('body');
        });

        $(document).on('show.bs.modal', '#payment-modal-shared', function(event) {
            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const data = trigger.dataset;
            const isCollected = data.paymentIsCollected === '1';
            const notes = data.paymentNotes || '';
            const receiveLink = document.getElementById('shared-payment-receive-link');

            document.getElementById('shared-payment-id').textContent = data.paymentId || '-';
            document.getElementById('shared-payment-doctor').textContent = data.paymentDoctor || '-';
            document.getElementById('shared-payment-collector').textContent = data.paymentCollector || '-';
            document.getElementById('shared-payment-amount').textContent = (data.paymentAmount || '-') + ' JOD';
            document.getElementById('shared-payment-collected-on').textContent = data.paymentCollectedOn || '-';
            document.getElementById('shared-payment-method').textContent = data.paymentMethod || '-';
            document.getElementById('shared-payment-received-on').textContent = data.paymentReceivedOn || '-';
            document.getElementById('shared-payment-receiver').textContent = data.paymentReceiver || '-';
            document.getElementById('shared-payment-notes').textContent = notes || '-';

            document.getElementById('shared-payment-received-on-row').classList.toggle('d-none', !isCollected);
            document.getElementById('shared-payment-receiver-row').classList.toggle('d-none', !isCollected);
            document.getElementById('shared-payment-notes-row').classList.toggle('d-none', notes === '');

            if (receiveLink) {
                receiveLink.href = data.paymentReceiveUrl || '#';
                receiveLink.classList.toggle('d-none', isCollected || !data.paymentReceiveUrl);
            }
        });

        $(document).on('show.bs.modal', '#delivery-modal-shared', function(event) {
            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const data = trigger.dataset;
            const input = document.getElementById('dashboard_delivery_date_shared');

            document.getElementById('shared-delivery-case-id').value = data.caseId || '';
            document.getElementById('shared-delivery-case-label').textContent = data.caseId || '-';
            document.getElementById('shared-delivery-doctor').textContent = data.caseDoctor || '-';
            document.getElementById('shared-delivery-patient').textContent = data.casePatient || '-';
            document.getElementById('shared-delivery-view-link').href = data.caseViewUrl || (data.caseId ? '/view/' + data.caseId : '#');

            if (input) {
                input.value = data.deliveryDate || '';
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.dispatchEvent(new Event('change', { bubbles: true }));
            }
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
                    labels: @json($last7DaysChartLabels),
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
                    labels: @json($last7DaysChartLabels),
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
            var isSmallScreen = window.matchMedia('(max-width: 768px)').matches;
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
                    innerRadius: isSmallScreen ? 38 : 50,
                    indexLabelTextAlign: "center",
                    //indexLabelWrap: true,

                    indexLabelPlacement: "outside",
                    indexLabelFontColor: "black",
                    indexLabelFontSize: isSmallScreen ? 11 : 12,
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
            var chart_labels = @json($last30DaysChartLabels);

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

                node.addEventListener('animationend', handleAnimationEnd, {
                    once: true
                });
            });
    </script>
@endpush
