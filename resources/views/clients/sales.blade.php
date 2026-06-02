@extends('layouts.app' ,[ 'pageSlug' => 'Sales' ])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/doctors-index.css?v={{ filemtime(public_path('assets/css/pages/doctors-index.css')) }}" />
    <style>
        #my-table_wrapper {
            padding: 0 !important;
        }

        .sales-page-wrapper .doctor-filter-layout {
            display: block;
        }

        .sales-page-wrapper .doctor-filter-fields {
            width: 100%;
            margin-right: 0;
        }

        .sales-page-wrapper .doctor-table-shell {
            margin-bottom: 14px;
            border-radius: 12px;
            box-shadow: 0 14px 24px -14px rgba(25, 58, 68, 0.55);
            overflow: visible !important;
        }

        .sales-page-wrapper #my-table_wrapper {
            border-radius: 12px;
            box-shadow: 1px 5px 20px 0px rgb(11 11 11 / 9%);
            overflow: visible !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th {
            text-align: left !important;
            white-space: normal !important;
            vertical-align: bottom;
        }

        .sales-page-wrapper table#my-table.dataTable thead th .header__sub,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th .header__sub {
            display: inline;
            font-size: 0.8em;
        }

        .sales-page-wrapper table#my-table.dataTable thead th:not(:first-child),
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th:not(:first-child) {
            padding-left: 0 !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th.sorting::before,
        .sales-page-wrapper table#my-table.dataTable thead th.sorting_asc::before,
        .sales-page-wrapper table#my-table.dataTable thead th.sorting_desc::before,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting::before,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_asc::before,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_desc::before {
            display: none !important;
            content: none !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th.sorting::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting::after {
            content: " \2195" !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th.sorting_asc::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_asc::after {
            content: " \2191" !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th.sorting_desc::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_desc::after {
            content: " \2193" !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th.sorting::after,
        .sales-page-wrapper table#my-table.dataTable thead th.sorting_asc::after,
        .sales-page-wrapper table#my-table.dataTable thead th.sorting_desc::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_asc::after,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_desc::after {
            position: static !important;
            display: inline-block !important;
            margin-left: 6px !important;
            color: #ffffff !important;
            opacity: 1 !important;
            font-family: Arial, sans-serif !important;
            font-size: 12px !important;
            line-height: 1 !important;
            vertical-align: middle !important;
        }

        .sales-page-wrapper table#my-table.dataTable thead th:first-child,
        .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th:first-child {
            padding-left: 24px !important;
        }

        .sales-page-wrapper table#my-table.dataTable tbody td.sales-doctor-col {
            padding-left: 24px !important;
        }

        .sales-page-wrapper table#my-table.dataTable tbody td.sales-value-col,
        .sales-page-wrapper table#my-table.dataTable tbody td.sales-value-col .tabledit-span,
        .sales-page-wrapper table#my-table.dataTable tbody td.sales-value-col .sales-value-number {
            font-size: 18px ;

            font-weight: 700 !important;
        }

        .sales-page-wrapper .sales-value-unit {
            font-size: 0.75em;
            font-weight: 400 !important;
            margin-left: 4px;
            line-height: 1;
            vertical-align: baseline;
        }

        .sales-page-wrapper .sales-value-number {
            font-size: inherit;
            font-weight: inherit;
            line-height: inherit;
        }

        .sales-page-wrapper #my-table tbody tr {
            cursor: pointer;
        }

        .sales-page-wrapper #my-table tbody td,
        .sales-page-wrapper #my-table tbody td .tabledit-span,
        .sales-page-wrapper #my-table tbody td .sales-value-number,
        .sales-page-wrapper #my-table tbody td .sales-value-unit {
            color: #000000 !important;
        }

        .sales-page-wrapper #my-table tbody tr.sales-aging-orange td,
        .sales-page-wrapper #my-table tbody tr.sales-aging-orange td .tabledit-span,
        .sales-page-wrapper #my-table tbody tr.sales-aging-orange td .sales-value-number,
        .sales-page-wrapper #my-table tbody tr.sales-aging-orange td .sales-value-unit {
            color: #ff7910 !important;
        }

        .sales-page-wrapper #my-table tbody tr.sales-aging-red td,
        .sales-page-wrapper #my-table tbody tr.sales-aging-red td .tabledit-span,
        .sales-page-wrapper #my-table tbody tr.sales-aging-red td .sales-value-number,
        .sales-page-wrapper #my-table tbody tr.sales-aging-red td .sales-value-unit {
            color: #dc3545 !important;
        }

        .sales-page-wrapper .sales-discount-flag {
            display: inline-block;
            margin-left: 6px;
            font-size: 11px;
            line-height: 1;
            color: #8b1e2d;
            font-weight: 600;
            vertical-align: middle;
        }

        .sales-page-wrapper .btn.btn-disabled-link {
            pointer-events: none;
            opacity: 0.6;
        }

        .sales-page-wrapper #doctor-last-invoice-link {
            background: #2a7c63 !important;
            border-color: #2a7c63 !important;
            color: #fff !important;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .modal-body {
            padding: 1.25rem 1.25rem 1rem;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .modal-header {
            border-bottom: 0;
            padding-top: 16px;
            padding-bottom: 0px;
            align-items: center;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .modal-title {
            color: #2d5f6d;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 0;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .doctor-actions-summary-row {
            align-items: stretch;
            row-gap: 0.875rem;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .doctor-actions-summary-col {
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .doctor-actions-summary-value {
            display: flex;
            align-items: flex-end;
            margin-top: auto;
            line-height: 1.0;
            word-break: break-word;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .doctor-actions-balance-value {
            color: #1f7a4d !important;
        }

        .sales-page-wrapper .sales-doctor-actions-modal .doctor-actions-balance-number {
            display: inline-block;
            font-size: 21px;
            line-height: 1;
        }

        @media (max-width: 575.98px) {
            .sales-page-wrapper table#my-table.dataTable thead th .header__sub,
            .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th .header__sub {
                display: block;
                line-height: 1;
            }

            .sales-page-wrapper table#my-table.dataTable thead th.sorting::after,
            .sales-page-wrapper table#my-table.dataTable thead th.sorting_asc::after,
            .sales-page-wrapper table#my-table.dataTable thead th.sorting_desc::after,
            .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting::after,
            .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_asc::after,
            .sales-page-wrapper #my-table_wrapper .dataTables_scrollHead table thead th.sorting_desc::after {
                display: none !important;
                content: none !important;
            }

            .sales-page-wrapper .sales-doctor-actions-modal .modal-body {
                padding: 1rem;
            }
        }
        @media (min-width: 992px) {
            .sales-page-wrapper .container.full-width.doctor-table-shell {
                width: calc(100% - 30px) !important;
                margin-left: 15px !important;
                margin-right: 15px !important;
            }
        }

        .sales-value-number{
            font-size: 20px !important;
        }
    </style>
@endpush

@section('content')
    @php
        $permissions = Cache::get('user' . Auth()->user()->id);
        $showSalesDiscountLabel = false;
    @endphp
    <div class="doctor-page-wrapper sigma-list-page sales-page-wrapper">
        <form class="kt-form doctor-filter-form" method="GET" action="{{ route('sales-index') }}">
            <div class="col-lg-12 mb-3 doctor-filter-shell-col">
                <div class="sigma-list-filter-card doctor-filters-shell cases-filter-card delivery-filter-card">
                    <div class="doctor-card-body py-3">
                        <div class="doctor-filter-layout">
                            <div class="row align-items-end filters-row cases-filter-row sigma-list-filter-row doctor-filter-fields">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
                                    <label for="sales_from" class="filter-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>From</span>
                                    </label>
                                    <x-ios-dtp name="from" id="sales_from" class="filter-input-global" :value="$from ?? ''" :required="true" mode="date" />
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
                                    <label for="sales_to" class="filter-label">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>To</span>
                                    </label>
                                    <x-ios-dtp name="to" id="sales_to" class="filter-input-global" :value="$to ?? ''" :required="true" mode="date" />
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

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 doctor-filter-col">
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

        <div class="container full-width doctor-table-shell">
            <table class="table table-bordered dataTable no-footer nowrap compact sunriseTable table-odd sigma-list-table" id="my-table">
                <thead>
                <tr>
                    <th class="table-head sigma-head-left">Doctor</th>
                    <th class="table-head sigma-head-left">Invoices Total</th>
                    <th class="table-head sigma-head-left">Days Since <span class="header__sub">Last Invoice</span></th>
                </tr>
                </thead>
                <tbody>
                @foreach($sales as $doctor)
                    @php
                        $lastInvoiceDate = $doctor->last_invoice_date
                            ? \Carbon\Carbon::parse($doctor->last_invoice_date)->format('Y-m-d')
                            : '';
                        $daysSinceLastInvoice = $doctor->last_invoice_date
                            ? \Carbon\Carbon::parse($doctor->last_invoice_date)->startOfDay()->diffInDays(now()->startOfDay())
                            : null;
                        $salesAgingClass = '';
                        if ($daysSinceLastInvoice !== null && $daysSinceLastInvoice >= 30) {
                            $salesAgingClass = 'sales-aging-red';
                        } elseif ($daysSinceLastInvoice !== null && $daysSinceLastInvoice >= 15) {
                            $salesAgingClass = 'sales-aging-orange';
                        }
                    @endphp
                    <tr class="odd clickable {{ $salesAgingClass }}"
                        data-toggle="modal"
                        data-target="#doctorActionsModal"
                        data-client-id="{{ $doctor->id }}"
                        data-client-name="{{ $doctor->name }}"
                        data-client-balance-label="{{ number_format((float) ($doctor->display_balance ?? $doctor->balance), 2) }}"
                        data-client-active="1"
                        data-last-invoice-case-id="{{ $doctor->last_invoice_case_id ?? '' }}"
                        data-last-invoice-date="{{ $lastInvoiceDate }}"
                        data-last-invoice-is-discount="{{ $doctor->last_invoice_is_discount ? '1' : '0' }}">
                        <td class="tabledit-view-mode sigma-body-left sales-doctor-col">
                            <span class="tabledit-span">{{ $doctor->name }}</span>
                        </td>
                        <td class="tabledit-view-mode sigma-body-left sales-value-col" data-order="{{ (float) $doctor->total_invoice_amount }}">
                            <span class="tabledit-span"><span class="sales-value-number">{{ number_format((float) $doctor->total_invoice_amount, 0) }}</span><span class="sales-value-unit">JOD</span></span>
                        </td>
                        <td class="tabledit-view-mode sigma-body-left sales-value-col" data-order="{{ $daysSinceLastInvoice ?? 999999 }}">
                            <span class="tabledit-span">
                                @if($daysSinceLastInvoice !== null)
                                    <span class="sales-value-number">{{ $daysSinceLastInvoice }}</span><span class="sales-value-unit">days</span>
                                @else
                                    -
                                @endif
                                @if($showSalesDiscountLabel && $doctor->last_invoice_date && $doctor->last_invoice_is_discount)
                                    <span class="sales-discount-flag">(Discount)</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal sigma-modal--clients-delete sales-doctor-actions-modal" tabindex="-1" role="dialog" id="doctorActionsModal">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Doctor Account</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-0 doctor-actions-summary-row">
                            <div class="{{ Auth()->user()->is_admin ? 'col-8 col-md-6' : 'col-12' }} doctor-actions-summary-col">
                                <label for="doctor-actions-name" class="doctor-payment-field-label">Doctor</label>
                                <h5 id="doctor-actions-name" class="mb-0 doctor-actions-summary-value client-name-highlight"><b>-</b></h5>
                            </div>
                            @if(Auth()->user()->is_admin)
                                <div class="col-4 col-md-6 doctor-actions-summary-col">
                                    <label for="doctor-actions-balance" class="doctor-payment-field-label">Balance</label>
                                    <h5 id="doctor-actions-balance" class="mb-0 doctor-actions-summary-value doctor-actions-balance-value"><b class="doctor-actions-balance-number">-</b></h5>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer fullBtnsWidth">
                        <div class="row client-actions-row">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <div class="col-6">
                                    <a id="doctor-statement-link" href="#" class="btn btn-info">
                                        <span class="btn-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                                        <span class="btn-text">Account Statement</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a id="doctor-edit-link" href="#" class="btn btn-danger">
                                        <span class="btn-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                        <span class="btn-text">Edit Record</span>
                                    </a>
                                </div>
                            @endif
                            @if(Auth()->user()->is_admin)
                                <div class="col-6">
                                    <a id="doctor-cases-link" href="#" class="btn btn-info">
                                        <span class="btn-icon"><i class="far fa-file-alt"></i></span>
                                        <span class="btn-text">View Cases</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a id="doctor-invoices-link" href="#" class="btn btn-info">
                                        <span class="btn-icon"><i class="fas fa-file-invoice"></i></span>
                                        <span class="btn-text">View Invoices</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a id="doctor-payments-link" href="#" class="btn btn-info">
                                        <span class="btn-icon"><i class="fas fa-credit-card"></i></span>
                                        <span class="btn-text">View Payments</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a id="doctor-toggle-link" href="#" class="btn btn-warning">
                                        <span class="btn-icon"><i id="doctor-toggle-icon" class="fas fa-times-circle"></i></span>
                                        <span id="doctor-toggle-label" class="btn-text">Disable</span>
                                    </a>
                                </div>
                            @endif
                            <div class="col-12">
                                <a id="doctor-last-invoice-link" href="#" class="btn btn-block">
                                    <span class="btn-icon"><i class="fas fa-receipt"></i></span>
                                    <span class="btn-text">View Last Invoice</span>
                                </a>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">
                                    <span class="btn-icon"><i class="fas fa-ban"></i></span>
                                    <span class="btn-text">Cancel</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            const salesTable = $('#my-table');
            const doctorRouteTemplates = {
                statement: @json(route('client-statement-admin', '__CLIENT__')),
                edit: @json(route('client-view-edit', ['id' => '__CLIENT__'])),
                cases: @json(route('dentist-cases', ['id' => '__CLIENT__'])),
                invoices: @json(route('dentist-invoices', ['id' => '__CLIENT__'])),
                payments: @json(route('dentist-payments', ['id' => '__CLIENT__'])),
                toggle: @json(route('toggle-client-active', '__CLIENT__')),
                invoiceView: @json(route('view-invoice', '__CASE__')),
            };

            function doctorRoute(type, value) {
                const template = doctorRouteTemplates[type] || '#';
                return template.replace(type === 'invoiceView' ? '__CASE__' : '__CLIENT__', value);
            }

            function buildLastInvoiceLink(data) {
                const caseId = parseInt(data.lastInvoiceCaseId || '0', 10);
                const date = data.lastInvoiceDate || '';

                if (caseId > 0) {
                    return doctorRoute('invoiceView', caseId);
                }

                if (date && data.clientId) {
                    return doctorRoute('statement', data.clientId) + '?from=' + encodeURIComponent(date) + '&to=' + encodeURIComponent(date);
                }

                return '';
            }

            function populateDoctorActionsModal(trigger) {
                if (!trigger) {
                    return;
                }

                const data = trigger.dataset;
                const clientId = data.clientId || '';
                const isActive = data.clientActive === '1';
                const nameNode = document.getElementById('doctor-actions-name');
                const balanceNode = document.getElementById('doctor-actions-balance');
                const nameValueNode = nameNode ? nameNode.querySelector('b') : null;
                const balanceValueNode = balanceNode ? balanceNode.querySelector('b') : null;

                if (nameValueNode) {
                    nameValueNode.textContent = data.clientName || '-';
                } else if (nameNode) {
                    nameNode.textContent = data.clientName || '-';
                }
                if (balanceValueNode) {
                    balanceValueNode.textContent = data.clientBalanceLabel || '-';
                } else if (balanceNode) {
                    balanceNode.textContent = data.clientBalanceLabel || '-';
                }

                [
                    ['doctor-statement-link', doctorRoute('statement', clientId)],
                    ['doctor-edit-link', doctorRoute('edit', clientId)],
                    ['doctor-cases-link', doctorRoute('cases', clientId)],
                    ['doctor-invoices-link', doctorRoute('invoices', clientId)],
                    ['doctor-payments-link', doctorRoute('payments', clientId)],
                ].forEach(function(entry) {
                    const element = document.getElementById(entry[0]);
                    if (element) {
                        element.setAttribute('href', entry[1] || '#');
                    }
                });

                const lastInvoiceLink = document.getElementById('doctor-last-invoice-link');
                if (lastInvoiceLink) {
                    const href = buildLastInvoiceLink(data);
                    lastInvoiceLink.setAttribute('href', href || '#');
                    lastInvoiceLink.classList.toggle('btn-disabled-link', !href);
                }

                const toggleLink = document.getElementById('doctor-toggle-link');
                const toggleLabel = document.getElementById('doctor-toggle-label');
                const toggleIcon = document.getElementById('doctor-toggle-icon');

                if (toggleLink) {
                    toggleLink.href = doctorRoute('toggle', clientId);
                    toggleLink.className = 'btn ' + (isActive ? 'btn-warning' : 'btn-success');
                    toggleLink.onclick = function() {
                        return confirm('Are you sure you want to ' + (isActive ? 'disable' : 'enable') + ' this doctor?');
                    };
                }
                if (toggleLabel) {
                    toggleLabel.textContent = isActive ? 'Disable' : 'Enable';
                }
                if (toggleIcon) {
                    toggleIcon.className = isActive ? 'fas fa-times-circle' : 'fas fa-check-circle';
                }
            }

            if (salesTable.length && !$.fn.DataTable.isDataTable(salesTable[0])) {
                const salesSortStorageKey = 'sigma.sales.table.sortOrder.v1';
                const salesDefaultOrder = [[2, 'desc']];

                function getSavedSalesOrder() {
                    try {
                        const savedOrder = JSON.parse(localStorage.getItem(salesSortStorageKey) || 'null');
                        const order = Array.isArray(savedOrder) ? savedOrder[0] : null;
                        const column = order ? parseInt(order[0], 10) : null;
                        const direction = order ? String(order[1]).toLowerCase() : '';

                        if ([0, 1, 2].includes(column) && ['asc', 'desc'].includes(direction)) {
                            return [[column, direction]];
                        }
                    } catch (error) {
                        localStorage.removeItem(salesSortStorageKey);
                    }

                    return salesDefaultOrder;
                }

                const salesDataTable = salesTable.DataTable({
                    paging: false,
                    info: false,
                    searching: false,
                    lengthChange: false,
                    responsive: true,
                    autoWidth: false,
                    order: getSavedSalesOrder()
                });

                salesTable.on('order.dt', function() {
                    const currentOrder = salesDataTable.order();
                    if (currentOrder && currentOrder.length) {
                        localStorage.setItem(salesSortStorageKey, JSON.stringify(currentOrder));
                    }
                });
            }

            $(document).on('show.bs.modal', '#doctorActionsModal', function(event) {
                populateDoctorActionsModal(event.relatedTarget);
            });
        });
    </script>
@endpush
