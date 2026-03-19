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
<div class="bg-white sigma-list-page">
    @if(isset($clients))
        <form  class="kt-form" method="GET" action="{{route('invoices-index')}}">
            @else

     <form  class="kt-form" method="GET" action="{{route('dentist-invoices',['id' =>$id])}}">
     <input type="hidden" class="form-control" name="id" value="{{$id}}">
     @endif

    <div class="container full-width sigma-list-filter-card">

        <div class="row sigma-list-filter-row">

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
                        <select style="width:100%"  class="selectpicker clearOnAll" multiple name="doctor[]" id="doctor" data-live-search="true" title="All" data-hide-disabled="true">

                            <option value="all" {{(isset($selectedClients) && $selectedClients== 'all') ? 'selected' : ''}}>All</option>
                            @foreach($clients as $d)
                                <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                            @endforeach

                        </select>

                    </div>
                    @endif

            </div>
            {{--<div class="col-lg-2 col-md-3 ">--}}
                {{--<div class="kt-subheader__search">--}}
                    {{--<label>Patient Name:</label>--}}
                    {{--<br>--}}
                    {{--<input type="text" name="patient_name" value="{{$patientName ?? ''}}"--}}
                           {{--class="form-control">--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3 sigma-filter-action-col">
                <button type="submit" class="btn btn-primary sigma-apply-btn">
                    <i class="fas fa-search"></i>
                    <span>Apply</span>
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
                                        <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 50.93px;">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 240px;">Doctor</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 148.32px;">Patient name</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 83.1445px;">Amount</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 160.664px;">Delivered on</th>

                      </tr>
                                    </thead>


                                    <tbody>
                                    @foreach($invoices as $invoice)
                                        @if(isset($invoice->case))
                                        <tr role="row" class="odd" onclick="window.location='{{route('view-invoice', $invoice->case->id)}}';" style="cursor: pointer;">
                                            <td class="sorting_1">{{$invoice->id}}</td>
                                            <td>{{$invoice->client->name}}</td>
                                            <td>{{$invoice->case->patient_name}}</td>
                                            <td>{{$invoice->amount}} JOD</td>
                                            @if (isset($invoice->case->actual_delivery_date))
                                            <td>{{$invoice->case->actualDeliveryDate()}}&nbsp;&nbsp;&nbsp;&nbsp;{{$invoice->case->actualDeliveryTime()}}</td>
                                            @else
                                            <td>-</td>
                                            @endif
                                        </tr>
                                        @else
                                        {{-- This is a discount invoice --}}
                                        <tr role="row" class="odd discount-invoice-row" data-invoice-id="{{$invoice->id}}" style="background-color: #f8f9fa; border-left: 3px solid #6c757d; cursor: pointer;" title="Click to delete this discount">
                                            <td class="sorting_1">{{$invoice->id}}</td>
                                            <td>{{$invoice->client->name}}</td>
                                            <td><i class="fa fa-tag" style="color: #6c757d; margin-right: 5px;"></i>{{$invoice->discount_title}}</td>
                                            <td>{{$invoice->amount}} JOD</td>
                                            <td>-</td>
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

        $('#datatable').dataTable({
            "fixedHeader": true,
            "colReorder": true,
            "responsive": true,
            "sPaginationType": "full_numbers",
            "bLengthChange": true,
            "aLengthMenu": [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]],
            "iDisplayLength": 20,
            "order": [[ 4, "desc" ]],
            "dom": 'Bfrtip',
            "bProcessing": true,
            buttons: [
                {extend: 'excel',text: 'Export Excel'}

            ]
            //{ dom: 'Bfrtip', buttons: ['colvis', 'excel', 'print'] }
            //  "bJQueryUI": true
            // "sDom": 'l<"H"Rf>t<"F"ip>'
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
