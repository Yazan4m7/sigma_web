@extends('layouts.app' ,[ 'pageSlug' => 'Client Sales' ])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/doctors-index.css?v={{ filemtime(public_path('assets/css/pages/doctors-index.css')) }}" />
    <style>
        .sales-by-month-page-wrapper .doctor-filter-form {
            margin-bottom: 24px;
        }

        .sales-by-month-page-wrapper .doctor-filter-shell-col,
        .sales-by-month-page-wrapper .chart-shell-col {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .sales-by-month-page-wrapper .doctor-filters-shell.sigma-list-filter-card,
        .sales-by-month-page-wrapper .sales-chart-card {
            background: #ffffffa8 !important;
            border: 1px solid rgba(188, 206, 216, 0.3) !important;
            border-radius: 16px !important;
            box-shadow: 0 2px 20px 0 rgb(0 0 0 / 6%) !important;
            position: relative !important;
            overflow: hidden !important;
            backdrop-filter: blur(10px);
        }

        .sales-by-month-page-wrapper .doctor-filters-shell.sigma-list-filter-card {
            margin-bottom: 0 !important;
            padding: 20px 20px 16px !important;
        }

        .sales-by-month-page-wrapper .doctor-filters-shell.sigma-list-filter-card::before,
        .sales-by-month-page-wrapper .sales-chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
            border-radius: 16px 16px 0 0;
        }

        .sales-by-month-page-wrapper .doctor-card-body {
            padding: 0 !important;
        }

        .sales-by-month-page-wrapper .doctor-filter-layout {
            display: block;
        }

        .sales-by-month-page-wrapper .doctor-filter-fields {
            width: 100%;
            margin-right: 0;
        }

        .sales-by-month-page-wrapper .cases-filter-row {
            --cases-filter-height: 38px;
            --cases-filter-font-size: 14px;
            --cases-filter-color: #243746;
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
        }

        .sales-by-month-page-wrapper .cases-filter-row > [class*="col-"] {
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            padding-left: 8px !important;
            padding-right: 8px !important;
        }

        .sales-by-month-page-wrapper .filter-label {
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

        .sales-by-month-page-wrapper .filter-label i {
            color: #2b7b7d;
            font-size: 13px;
        }

        .sales-by-month-page-wrapper .cases-filter-row .dtp-input,
        .sales-by-month-page-wrapper .cases-filter-row .ios-dtp-trigger,
        .sales-by-month-page-wrapper .cases-filter-row .form-control,
        .sales-by-month-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle {
            min-height: var(--cases-filter-height) !important;
            height: var(--cases-filter-height) !important;
            font-size: var(--cases-filter-font-size) !important;
            padding: var(--cases-filter-padding) !important;
            border-radius: var(--cases-filter-radius) !important;
            border: var(--cases-filter-border) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            background: rgba(255, 255, 255, 0.88) !important;
            box-shadow: none !important;
            font-weight: 500;
            line-height: 1.5;
            transition: all 0.3s ease;
        }

        .sales-by-month-page-wrapper .cases-filter-row .bootstrap-select,
        .sales-by-month-page-wrapper .cases-filter-row .ios-dtp-container,
        .sales-by-month-page-wrapper .cases-filter-row .filter-input-global,
        .sales-by-month-page-wrapper .cases-filter-row input.filter-input-global,
        .sales-by-month-page-wrapper .cases-filter-row select.filter-input-global,
        .sales-by-month-page-wrapper .cases-filter-row .filter-input-global .ios-dtp-trigger,
        .sales-by-month-page-wrapper .cases-filter-row select.filter-input-global + .bootstrap-select,
        .sales-by-month-page-wrapper .cases-filter-row select.filter-input-global + .bootstrap-select > .dropdown-toggle {
            width: 100% !important;
            max-width: 100% !important;
        }

        .sales-by-month-page-wrapper .cases-filter-row .bootstrap-select .filter-option-inner-inner,
        .sales-by-month-page-wrapper .cases-filter-row .ios-dtp-display {
            font-size: var(--cases-filter-font-size) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            font-weight: 500;
        }

        .sales-by-month-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle.bs-placeholder .filter-option-inner-inner {
            color: #6b7280 !important;
            opacity: 1;
        }

        .sales-by-month-page-wrapper .cases-filter-row .dtp-input:focus,
        .sales-by-month-page-wrapper .cases-filter-row .ios-dtp-trigger:focus,
        .sales-by-month-page-wrapper .cases-filter-row .form-control:focus,
        .sales-by-month-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle:focus {
            border-color: #408385 !important;
            box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
            outline: 0;
        }

        .sales-by-month-page-wrapper .cases-filter-btn {
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
            font-size: 14px !important;
            font-weight: 600;
            letter-spacing: 0.2px;
            line-height: 1.2;
            transition: all 0.3s ease;
        }

        .sales-by-month-page-wrapper .cases-filter-btn--search {
            background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
            background-color: #4d9597 !important;
            border: 1px solid #408385 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
        }

        .sales-by-month-page-wrapper .cases-filter-btn--search:hover,
        .sales-by-month-page-wrapper .cases-filter-btn--search:focus {
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            background-color: #4a8d90 !important;
            border-color: #336f71 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
        }

        .sales-by-month-page-wrapper .sigma-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .sales-by-month-page-wrapper .sigma-summary-item {
            min-width: 0;
        }

        .sales-by-month-page-wrapper .sales-summary-card {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.94) 0%, rgba(240, 247, 248, 0.96) 100%);
            border: 1px solid rgba(188, 206, 216, 0.42);
            border-radius: 14px;
            padding: 16px 18px;
            box-shadow: 0 10px 22px rgba(45, 95, 109, 0.08);
        }

        .sales-by-month-page-wrapper .sales-summary-label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7c86;
        }

        .sales-by-month-page-wrapper .sales-summary-value {
            display: flex;
            align-items: baseline;
            gap: 6px;
            color: #184e57;
            line-height: 1;
        }

        .sales-by-month-page-wrapper .sales-summary-number {
            font-size: 28px;
            font-weight: 800;
        }

        .sales-by-month-page-wrapper .sales-summary-unit {
            font-size: 13px;
            font-weight: 700;
            color: #6b7c86;
            text-transform: uppercase;
        }

        .sales-by-month-page-wrapper .sales-chart-card {
            padding: 24px 24px 20px !important;
            margin-bottom: 16px;
        }

        .sales-by-month-page-wrapper .sales-chart-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .sales-by-month-page-wrapper .sales-chart-title {
            margin: 0;
            color: #214954;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .sales-by-month-page-wrapper .sales-chart-subtitle {
            margin: 6px 0 0;
            color: #6a7d88;
            font-size: 13px;
            font-weight: 500;
        }

        .sales-by-month-page-wrapper .sales-chart-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(64, 131, 133, 0.12);
            color: #2c7b7d;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .sales-by-month-page-wrapper .sales-chart-canvas-wrap {
            position: relative;
            min-height: 420px;
            padding: 10px 6px 0;
        }

        .sales-by-month-page-wrapper #clientSalesChart {
            width: 100% !important;
            height: 420px !important;
        }

        .sales-by-month-page-wrapper .sales-chart-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 260px;
            border: 1px dashed rgba(64, 131, 133, 0.3);
            border-radius: 14px;
            background: rgba(245, 250, 250, 0.8);
            color: #6a7d88;
            font-size: 15px;
            font-weight: 600;
        }

        @media screen and (max-width: 991px) {
            .sales-by-month-page-wrapper .sigma-summary-grid {
                grid-template-columns: 1fr;
            }
        }

        @media screen and (max-width: 767px) {
            .sales-by-month-page-wrapper .doctor-filters-shell.sigma-list-filter-card,
            .sales-by-month-page-wrapper .sales-chart-card {
                padding: 14px 14px 12px !important;
            }

            .sales-by-month-page-wrapper .doctor-filter-fields {
                display: flex !important;
                flex-wrap: wrap !important;
                align-items: end;
                gap: 0 !important;
                row-gap: 8px !important;
                margin-left: -4px;
                margin-right: -4px;
            }

            .sales-by-month-page-wrapper .doctor-filter-fields > [class*="col-"] {
                padding-left: 4px !important;
                padding-right: 4px !important;
                margin-bottom: 0 !important;
            }

            .sales-by-month-page-wrapper .doctor-filter-fields > .doctor-filter-col {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
            }

            .sales-by-month-page-wrapper .doctor-filter-fields > .sigma-filter-action-col {
                flex: 0 0 100% !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .sales-by-month-page-wrapper .sales-chart-header {
                flex-direction: column;
            }

            .sales-by-month-page-wrapper .sales-chart-canvas-wrap,
            .sales-by-month-page-wrapper #clientSalesChart {
                min-height: 320px;
                height: 320px !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="doctor-page-wrapper sigma-list-page sales-by-month-page-wrapper">
        <form class="kt-form doctor-filter-form" method="GET" action="{{ route('sales-by-month-index') }}">
            <div class="col-lg-12 mb-3 doctor-filter-shell-col">
                <div class="sigma-list-filter-card doctor-filters-shell cases-filter-card delivery-filter-card">
                    <div class="doctor-card-body py-3">
                        <div class="doctor-filter-layout">
                            <div class="row align-items-end filters-row cases-filter-row sigma-list-filter-row doctor-filter-fields">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
                                    <label for="sales_month_from" class="filter-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>From</span>
                                    </label>
                                    <x-ios-dtp name="from" id="sales_month_from" class="filter-input-global" :value="$from ?? ''" :required="true" mode="month" />
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
                                    <label for="sales_month_to" class="filter-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>To</span>
                                    </label>
                                    <x-ios-dtp name="to" id="sales_month_to" class="filter-input-global" :value="$to ?? ''" :required="true" mode="month" />
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
                                    <label for="doctor" class="filter-label">
                                        <i class="fas fa-user-md"></i>
                                        <span>Doctor</span>
                                    </label>
                                    <select class="selectpicker clearOnAll filter-input-global"
                                            multiple
                                            data-container="body"
                                            name="doctor[]"
                                            id="doctor"
                                            data-live-search="true"
                                            title="All Doctors"
                                            data-hide-disabled="true">
                                        <option value="all" {{ (isset($selectedClients) && in_array('all', $selectedClients)) ? 'selected' : '' }}>
                                            All Doctors
                                        </option>
                                        @foreach($allClients as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ (isset($selectedClients) && in_array($doctor->id, $selectedClients)) ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 sigma-filter-action-col">
                                    <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                                        <i class="fas fa-search"></i>
                                        <span>Apply</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="sigma-summary-grid">
            <div class="sigma-summary-item">
                <div class="sales-summary-card">
                    <span class="sales-summary-label">Doctors Selected</span>
                    <div class="sales-summary-value">
                        <span class="sales-summary-number">{{ number_format($selectedDoctorsCount) }}</span>
                        <span class="sales-summary-unit">Doctors</span>
                    </div>
                </div>
            </div>
            <div class="sigma-summary-item">
                <div class="sales-summary-card">
                    <span class="sales-summary-label">Months In Range</span>
                    <div class="sales-summary-value">
                        <span class="sales-summary-number">{{ number_format($monthsCount) }}</span>
                        <span class="sales-summary-unit">Months</span>
                    </div>
                </div>
            </div>
            <div class="sigma-summary-item">
                <div class="sales-summary-card">
                    <span class="sales-summary-label">Invoices Total</span>
                    <div class="sales-summary-value">
                        <span class="sales-summary-number">{{ number_format($grandTotal, 0) }}</span>
                        <span class="sales-summary-unit">JOD</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 chart-shell-col">
            <div class="sales-chart-card">
                <div class="sales-chart-header">
                    <div>
                        <h3 class="sales-chart-title">Monthly Client Sales</h3>
                        <p class="sales-chart-subtitle">Combined invoice totals for all selected doctors by month.</p>
                    </div>
                    <div class="sales-chart-badge">Totals By Month</div>
                </div>

                @if(count($months))
                    <div class="sales-chart-canvas-wrap">
                        <canvas id="clientSalesChart"></canvas>
                    </div>
                @else
                    <div class="sales-chart-empty">No months available for the selected range.</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script>
        $(document).ready(function () {
            const chartCanvas = document.getElementById('clientSalesChart');

            if (!chartCanvas || typeof Chart === 'undefined') {
                return;
            }

            const chartLabels = @json($chartLabels);
            const chartValues = @json($chartValues);

            const valueLabelPlugin = {
                id: 'sigmaValueLabelPlugin',
                afterDatasetsDraw(chart) {
                    const {ctx} = chart;
                    const dataset = chart.data.datasets[0];
                    const meta = chart.getDatasetMeta(0);

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    ctx.fillStyle = '#1f4f59';
                    ctx.font = '700 12px "Cairo", "Segoe UI", sans-serif';

                    meta.data.forEach(function(bar, index) {
                        const value = dataset.data[index];
                        const label = Number(value).toLocaleString() + ' JOD';
                        ctx.fillText(label, bar.x, bar.y - 8);
                    });

                    ctx.restore();
                }
            };

            const context = chartCanvas.getContext('2d');
            const barGradient = context.createLinearGradient(0, 0, 0, 420);
            barGradient.addColorStop(0, 'rgba(103, 174, 176, 0.98)');
            barGradient.addColorStop(1, 'rgba(64, 131, 133, 0.92)');

            new Chart(context, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Invoices Total',
                        data: chartValues,
                        backgroundColor: barGradient,
                        borderColor: '#2f7b7d',
                        borderWidth: 1,
                        borderRadius: 14,
                        borderSkipped: false,
                        maxBarThickness: 64,
                        hoverBackgroundColor: '#2f7b7d'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 700,
                        easing: 'easeOutQuart'
                    },
                    layout: {
                        padding: {
                            top: 34,
                            right: 12,
                            bottom: 0,
                            left: 4
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1f4f59',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + Number(context.parsed.y).toLocaleString() + ' JOD';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#4c6871',
                                font: {
                                    size: 12,
                                    weight: '700'
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(140, 168, 177, 0.18)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7c86',
                                font: {
                                    size: 11,
                                    weight: '600'
                                },
                                callback: function(value) {
                                    return Number(value).toLocaleString();
                                }
                            }
                        }
                    }
                },
                plugins: [valueLabelPlugin]
            });
        });
    </script>
@endpush
