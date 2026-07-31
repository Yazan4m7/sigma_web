@extends('layouts.app' ,[ 'pageSlug' => 'Invoices List' ])


@section('content')
    <head>

    </head>
<style>
    @media screen and (max-width: 991px){
        #datatable_wrapper {
            overflow: auto;
        }
    }

    .invoices-page-wrapper .invoice-filter-form {
        margin-bottom: 24px;
    }

    .invoices-page-wrapper .cases-filter-card.delivery-filter-card {
        background: #ffffffa8 !important;
        border: 1px solid rgba(188, 206, 216, 0.3);
        border-radius: 16px;
        box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
        margin-bottom: 0 !important;
        padding: 20px 20px 16px !important;
        position: relative;
        overflow: hidden !important;
        backdrop-filter: blur(10px);
    }

    .invoices-page-wrapper .cases-filter-card.delivery-filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
        border-radius: 16px 16px 0 0;
    }

    .invoices-page-wrapper .cases-filter-row {
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
    }

    .invoices-page-wrapper .cases-filter-row > [class*="col-"] {
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .invoices-page-wrapper .cases-filter-row .mb-3 {
        margin-bottom: 12px !important;
    }

    .invoices-page-wrapper .cases-filter-row .filter-label {
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

    .invoices-page-wrapper .cases-filter-row .filter-label i {
        color: #2b7b7d;
        font-size: 13px;
    }

    .invoices-page-wrapper .cases-filter-row .dtp-input,
    .invoices-page-wrapper .cases-filter-row .ios-dtp-trigger,
    .invoices-page-wrapper .cases-filter-row .form-control,
    .invoices-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle {
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

    .invoices-page-wrapper .cases-filter-row .bootstrap-select,
    .invoices-page-wrapper .cases-filter-row .ios-dtp-container,
    .invoices-page-wrapper .cases-filter-row .filter-input-global,
    .invoices-page-wrapper .cases-filter-row input.filter-input-global,
    .invoices-page-wrapper .cases-filter-row select.filter-input-global,
    .invoices-page-wrapper .cases-filter-row .filter-input-global .ios-dtp-trigger,
    .invoices-page-wrapper .cases-filter-row select.filter-input-global + .bootstrap-select,
    .invoices-page-wrapper .cases-filter-row select.filter-input-global + .bootstrap-select > .dropdown-toggle {
        width: 100% !important;
        max-width: 100% !important;
    }

    .invoices-page-wrapper .cases-filter-row .bootstrap-select .filter-option-inner-inner,
    .invoices-page-wrapper .cases-filter-row .ios-dtp-display {
        font-size: var(--cases-filter-font-size) !important;
        color: var(--cases-filter-color) !important;
        text-align: left;
        font-weight: 500;
    }

    .invoices-page-wrapper .cases-filter-row .form-control::placeholder,
    .invoices-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle.bs-placeholder .filter-option-inner-inner {
        color: #6b7280 !important;
        opacity: 1;
    }

    .invoices-page-wrapper .cases-filter-row .dtp-input:focus,
    .invoices-page-wrapper .cases-filter-row .ios-dtp-trigger:focus,
    .invoices-page-wrapper .cases-filter-row .form-control:focus,
    .invoices-page-wrapper .cases-filter-row .bootstrap-select > .dropdown-toggle:focus {
        border-color: #408385 !important;
        box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
        outline: 0;
    }

    .invoices-page-wrapper .cases-filter-btn {
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

    .invoices-page-wrapper .cases-filter-btn--search {
        background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
        background-color: #4d9597 !important;
        border: 1px solid #408385 !important;
        color: #ffffff !important;
        box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
    }

    .invoices-page-wrapper .cases-filter-btn--search:hover,
    .invoices-page-wrapper .cases-filter-btn--search:focus {
        background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
        background-color: #4a8d90 !important;
        border-color: #336f71 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
    }

    .invoices-page-wrapper .cases-filter-btn--search:active,
    .invoices-page-wrapper .cases-filter-btn--search:not(:disabled):not(.disabled):active {
        background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
        background-color: #3d7678 !important;
        border-color: #285f61 !important;
        color: #ffffff !important;
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2);
    }

    .invoices-page-wrapper .cases-filter-btn--excel {
        min-width: auto;
        width: auto;
        height: var(--cases-filter-height) !important;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        color: #198754 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-decoration: none !important;
    }

    .invoices-page-wrapper .cases-filter-btn--excel:hover,
    .invoices-page-wrapper .cases-filter-btn--excel:focus {
        background: transparent !important;
        box-shadow: none !important;
        color: #157347 !important;
        transform: none !important;
    }

    .invoices-page-wrapper .cases-filter-btn--excel i {
        font-size: 18px;
        line-height: 1;
    }

    .invoices-page-wrapper .cases-filter-actions-col {
        display: flex;
        align-items: flex-end;
        justify-content: flex-start;
    }

    .invoices-page-wrapper .bootstrap-select.open:not(.bs-container),
    .invoices-page-wrapper .bootstrap-select.show:not(.bs-container) {
        position: relative !important;
        z-index: 10001 !important;
    }

    .invoices-page-wrapper .bs-container.bootstrap-select.open,
    .invoices-page-wrapper .bs-container.bootstrap-select.show,
    .invoices-page-wrapper .bootstrap-select .dropdown-menu {
        z-index: 10002 !important;
    }

    .invoices-page-wrapper table#datatable.dataTable thead th,
    .invoices-page-wrapper table#datatable.dataTable tbody td {
        text-align: left !important;
    }

    .invoices-page-wrapper table#datatable.dataTable thead th:last-child,
    .invoices-page-wrapper table#datatable.dataTable tbody td:last-child,
    .invoices-page-wrapper table#datatable.dataTable tbody td.sigma-body-center {
        text-align: center !important;
    }

    .invoices-page-wrapper .sigma-filter-secondary-col {
        justify-content: flex-end;
        padding-right: 0 !important;
    }

    .invoices-page-wrapper #datatable_wrapper .dataTables_filter,
    .invoices-page-wrapper #datatable_wrapper .dt-buttons,
    .invoices-page-wrapper #datatable_wrapper .dataTables_length {
        display: none !important;
    }

    .invoices-page-wrapper {
        padding-bottom: 48px;
    }

    .invoices-page-wrapper table#datatable.dataTable tbody td:first-child {
        padding-left: 14px !important;
    }

    @media screen and (min-width: 768px) {
        .invoices-page-wrapper .cases-filter-row > [class*="col-"].sigma-filter-action-col {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
        }

        .invoices-page-wrapper .cases-filter-row > [class*="col-"].sigma-filter-secondary-col {
            flex: 0 0 56px !important;
            width: 56px !important;
            max-width: 56px !important;
            margin-left: auto !important;
        }
    }

    .card-body{
        padding: 0;
    }
    .row, .container-fluid{
        padding-left:0px;
        padding-right:0px;
    }
    /*.col-sm-12 {*/
        /*padding-right:0px;*/
        /*padding-left:0px;*/
    /*}*/
    tr { cursor: pointer; }
    td {border : 0 !important;}
</style>
<div class="sigma-list-page invoices-page-wrapper">
    @if(isset($clients))
        <form  class="kt-form invoice-filter-form" method="GET" action="{{route('invoices-index')}}">
            @else

     <form  class="kt-form invoice-filter-form" method="GET" action="{{route('dentist-invoices',['id' =>$id])}}">
     <input type="hidden" class="form-control" name="id" value="{{$id}}">
     @endif

    <div class="container full-width cases-filter-card delivery-filter-card sigma-list-filter-card">

        <div class="row cases-filter-row sigma-list-filter-row">

            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                <div class="kt-subheader__search">
                    <label class="filter-label" for="invoice_from">
                        <i class="fas fa-calendar-alt"></i>
                        <span>From</span>
                    </label>
                    <x-ios-dtp
                        name="from"
                        id="invoice_from"
                        :value="$from"
                        mode="date"
                        :required="true"
                    />
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                <div class="kt-subheader__search">
                    <label class="filter-label" for="invoice_to">
                        <i class="fas fa-calendar-alt"></i>
                        <span>To</span>
                    </label>
                    <x-ios-dtp
                        name="to"
                        id="invoice_to"
                        :value="$to"
                        mode="date"
                        :required="true"
                    />
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                @if(isset($clients))
                    <div class="dropdown">
                        <label class="filter-label" for="doctor">
                            <i class="fas fa-user-md"></i>
                            <span>Doctor</span>
                        </label>
                        <select style="width:100%"  class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor" data-live-search="true" title="All" data-hide-disabled="true" data-container="body">

                            <option value="all" {{(isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''}}>All</option>
                            @foreach($clients as $d)
                                <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                            @endforeach

                        </select>

                    </div>
                    @endif

            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                <div class="kt-subheader__search">
                    <label class="filter-label" for="invoiceTableSearch">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </label>
                    <input type="text" class="form-control filter-input-global" id="invoiceTableSearch" placeholder="Search invoices">
                </div>
            </div>
            {{--<div class="col-lg-2 col-md-3 ">--}}
                {{--<div class="kt-subheader__search">--}}
                    {{--<label>Patient Name:</label>--}}
                    {{--<br>--}}
                    {{--<input type="text" name="patient_name" value="{{$patientName ?? ''}}"--}}
                           {{--class="form-control">--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3 sigma-filter-action-col cases-filter-actions-col">
                <button type="submit" class="btn btn-primary sigma-apply-btn cases-filter-btn cases-filter-btn--search">
                    <i class="fas fa-search"></i>
                    <span>Apply</span>
                </button>
            </div>
            <div class="col-6 col-md-2 col-lg-1 mb-3 d-flex align-items-end justify-content-end sigma-filter-secondary-col cases-filter-actions-col">
                <button type="button" class="btn cases-filter-btn cases-filter-btn--excel" id="invoiceExportBtn" title="Export Excel" aria-label="Export Excel">
                    <i class="fas fa-file-excel"></i>
                </button>
            </div>
        </div>
    </div>
    </form>

    <div class="sigma-summary-grid">
        <div class="sigma-summary-item">
            <div class="materials-total-card report-total-card sigma-compact-summary-card">
                <div>
                    <span class="materials-total-label">Invoices Total</span>
                    <div class="materials-total-value">
                        <span class="materials-total-amount sigma-summary-value--neutral">{{ number_format($invoices->sum('amount')) }}</span>
                        <span class="materials-total-currency">JOD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <div class="sigma-table-free">
                                <table id="datatable" class="dataTable no-footer order-column display nowrap compact cell-border sunriseTable sigma-list-table" role="grid" aria-describedby="datatable_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 240px;">Doctor</th>
                                        <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 148.32px;">Patient Name</th>
                                        <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 83.1445px;">Amount</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 160.664px;">Actual Delivery <i class="fas fa-calendar-alt" aria-hidden="true"></i></th>

                      </tr>
                                    </thead>


                                    <tbody>
                                        @foreach($invoices as $invoice)
                                        @if($invoice->isAccountDiscount())
                                        {{-- This is a discount invoice --}}
                                        <tr role="row" class="odd discount-invoice-row" data-invoice-id="{{$invoice->id}}" style="background-color: #f8f9fa; border-left: 3px solid #6c757d; cursor: pointer;" title="Click to delete this discount">
                                            <td class="sorting_1 sigma-col-shaded sigma-body-left">{{$invoice->client->name}}</td>
                                            <td class="sigma-body-left"><i class="fa fa-tag" style="color: #6c757d; margin-right: 5px;"></i>{{$invoice->discount_title}}</td>
                                            <td class="sigma-col-shaded sigma-body-left">{{$invoice->amount}} JOD</td>
                                            <td class="sigma-body-center">-</td>
                                        </tr>
                                        @elseif(isset($invoice->case))
                                        <tr role="row" class="odd" onclick="window.location='{{route('view-invoice', $invoice->case->id)}}';" style="cursor: pointer;">
                                            <td class="sorting_1 sigma-col-shaded sigma-body-left">{{$invoice->client->name}}</td>
                                            <td class="sigma-body-left">{{$invoice->case->patient_name}}</td>
                                            <td class="sigma-col-shaded sigma-body-left">{{$invoice->amount}} JOD</td>
                                            @if (isset($invoice->case->actual_delivery_date))
                                            <td class="sigma-body-center">{{$invoice->case->actualDeliveryDate()}}&nbsp;&nbsp;&nbsp;&nbsp;{{$invoice->case->actualDeliveryTime()}}</td>
                                            @else
                                            <td class="sigma-body-center">-</td>
                                            @endif
                                        </tr>
                                        @else
                                        <tr role="row" class="odd" title="Linked case unavailable">
                                            <td class="sorting_1 sigma-col-shaded sigma-body-left">{{$invoice->client->name}}</td>
                                            <td class="sigma-body-left"><i class="fa fa-exclamation-triangle text-warning" style="margin-right: 5px;"></i>Linked case unavailable</td>
                                            <td class="sigma-col-shaded sigma-body-left">{{$invoice->amount}} JOD</td>
                                            <td class="sigma-body-center">-</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
            </div>

    </div>





    @endsection




@push('js')

    <script type="text/javascript">
//        $(document).ready(function() {
//            $('#datatable').DataTable({
//                dom: 'Bfrtip',
//                buttons: [ 'csv', 'excel', 'pdf', 'print' ],
//                "pageLength": 25,
//                "searching": false,
//                "lengthChange": false,
//                "order": [[ 4, "desc" ]]
//            });
//        });
$(document).ready(function() {

        const table = $('#datatable').DataTable({
            "fixedHeader": true,
            "colReorder": true,
            "responsive": true,
            "pagingType": "simple_numbers",
            "lengthChange": false,
            "iDisplayLength": 20,
            "order": [[ 3, "desc" ]],
            "dom": 'Brtip',
            "bProcessing": true,
            "autoWidth": false,
            buttons: [
                {extend: 'excel',text: 'Export Excel'}

            ]
            //{ dom: 'Bfrtip', buttons: ['colvis', 'excel', 'print'] }
            //  "bJQueryUI": true
            // "sDom": 'l<"H"Rf>t<"F"ip>'
        });

        $('#invoiceTableSearch').on('input', function() {
            table.search(this.value).draw();
        });

        $('#invoiceExportBtn').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        // Handle discount invoice row clicks with SweetAlert
        $(document).on('click', '.discount-invoice-row', function(e) {
            e.stopPropagation();
            const invoiceId = $(this).data('invoice-id');
            const deleteUrl = '{{route("delete-discount", ":id")}}'.replace(':id', invoiceId);

            Swal.fire({
                title: 'Delete Discount?',
                html: '<p>Are you sure you want to delete this discount?</p><p class="text-muted">This will update the doctor\'s balance.</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa fa-trash"></i> Yes, delete it',
                cancelButtonText: '<i class="fa fa-times"></i> Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        });

        // Add hover effect for discount rows
        $(document).on('mouseenter', '.discount-invoice-row', function() {
            $(this).css({
                'background-color': '#e9ecef',
                'border-left-color': '#dc3545'
            });
        }).on('mouseleave', '.discount-invoice-row', function() {
            $(this).css({
                'background-color': '#f8f9fa',
                'border-left-color': '#6c757d'
            });
        });
});
    </script>
    @endpush
