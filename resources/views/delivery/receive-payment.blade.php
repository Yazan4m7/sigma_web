@extends('layouts.app' ,[ 'pageSlug' =>'Receive Payments'])

@section('content')
<style>
    .modal.sigma-modal--delivery-receive-payment {
        z-index: 9999999;
    }

    .payments-with-collectors-page .payments-filter-form {
        width: 100%;
        margin-bottom: 24px;
    }

    .payments-with-collectors-page .cases-filter-card.container.full-width.delivery-filter-card {
        background: #ffffffa8 !important;
        border: 1px solid rgba(188, 206, 216, 0.3);
        border-radius: 16px;
        box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 20px 20px 16px !important;
        position: relative;
        overflow: hidden !important;
        backdrop-filter: blur(10px);
    }

    .payments-with-collectors-page .cases-filter-card.container.full-width.delivery-filter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
        border-radius: 16px 16px 0 0;
    }

    .payments-with-collectors-page .cases-filter-row {
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

    .payments-with-collectors-page .cases-filter-row > [class*="col-"] {
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-left: 8px !important;
        padding-right: 8px !important;
    }

    .payments-with-collectors-page .cases-filter-row .mb-3 {
        margin-bottom: 12px !important;
    }

    .payments-with-collectors-page .cases-filter-row .filter-label {
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

    .payments-with-collectors-page .cases-filter-row .filter-label i {
        color: #2b7b7d;
        font-size: 13px;
    }

    .payments-with-collectors-page .cases-filter-row .dtp-input,
    .payments-with-collectors-page .cases-filter-row .ios-dtp-trigger,
    .payments-with-collectors-page .cases-filter-row .form-control,
    .payments-with-collectors-page .cases-filter-row .bootstrap-select > .dropdown-toggle {
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

    .payments-with-collectors-page .cases-filter-row .bootstrap-select,
    .payments-with-collectors-page .cases-filter-row .ios-dtp-container,
    .payments-with-collectors-page .cases-filter-row .filter-input-global,
    .payments-with-collectors-page .cases-filter-row input.filter-input-global,
    .payments-with-collectors-page .cases-filter-row select.filter-input-global,
    .payments-with-collectors-page .cases-filter-row .filter-input-global .ios-dtp-trigger,
    .payments-with-collectors-page .cases-filter-row select.filter-input-global + .bootstrap-select,
    .payments-with-collectors-page .cases-filter-row select.filter-input-global + .bootstrap-select > .dropdown-toggle {
        width: 100% !important;
        max-width: 100% !important;
    }

    .payments-with-collectors-page .cases-filter-row .bootstrap-select .filter-option-inner-inner,
    .payments-with-collectors-page .cases-filter-row .ios-dtp-display {
        font-size: var(--cases-filter-font-size) !important;
        color: var(--cases-filter-color) !important;
        text-align: left;
        font-weight: 500;
    }

    .payments-with-collectors-page .cases-filter-row .dtp-input:focus,
    .payments-with-collectors-page .cases-filter-row .ios-dtp-trigger:focus,
    .payments-with-collectors-page .cases-filter-row .form-control:focus,
    .payments-with-collectors-page .cases-filter-row .bootstrap-select > .dropdown-toggle:focus {
        border-color: #408385 !important;
        box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
        outline: 0;
    }

    .payments-with-collectors-page .cases-filter-btn {
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

    .payments-with-collectors-page .cases-filter-btn--search {
        background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
        background-color: #4d9597 !important;
        border: 1px solid #408385 !important;
        color: #ffffff !important;
        box-shadow: none !important;
    }

    .payments-with-collectors-page .cases-filter-btn--search:hover,
    .payments-with-collectors-page .cases-filter-btn--search:focus {
        background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
        background-color: #4a8d90 !important;
        border-color: #336f71 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: none !important;
    }

    .payments-with-collectors-page .cases-filter-btn--search:active,
    .payments-with-collectors-page .cases-filter-btn--search:not(:disabled):not(.disabled):active {
        background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
        background-color: #3d7678 !important;
        border-color: #285f61 !important;
        color: #ffffff !important;
        transform: translateY(0);
        box-shadow: none !important;
    }

    .payments-with-collectors-page .sigma-table-free,
    .payments-with-collectors-page #datatable_wrapper,
    .payments-with-collectors-page #datatable_wrapper > .row,
    .payments-with-collectors-page #datatable_wrapper > .row > [class*="col-"] {
        background: transparent !important;
        border: 0 !important;
        box-shadow: none !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .payments-with-collectors-page table#datatable.dataTable {
        width: 100% !important;
        margin: 0 !important;
        table-layout: auto;
    }

    @media screen and (min-width: 768px) {
        .payments-with-collectors-page .cases-filter-row > [class*="col-"].sigma-filter-action-col {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
        }
    }

    @media screen and (min-width: 992px) {
        .payments-with-collectors-page .sigma-table-free {
            overflow-x: visible !important;
        }

        .payments-with-collectors-page table#datatable.dataTable thead th,
        .payments-with-collectors-page table#datatable.dataTable tbody td {
            white-space: normal !important;
        }
    }

    @media screen and (max-width: 991px) {
        .payments-with-collectors-page .sigma-table-free {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        .payments-with-collectors-page table#datatable.dataTable thead th,
        .payments-with-collectors-page table#datatable.dataTable tbody td {
            white-space: nowrap !important;
        }
    }
</style>

<div class="sigma-list-page payments-page-wrapper payments-with-collectors-page">
    <form class="kt-form payments-filter-form" method="GET" action="{{ route('payments-with-collectors') }}">
        <div class="container full-width cases-filter-card delivery-filter-card sigma-list-filter-card">
            <div class="row cases-filter-row sigma-list-filter-row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="kt-subheader__search">
                        <label class="filter-label" for="payments_collectors_from">
                            <i class="fas fa-calendar-alt"></i>
                            <span>From</span>
                        </label>
                        <x-ios-dtp
                            name="from"
                            id="payments_collectors_from"
                            class="filter-input-global"
                            :value="$from ?? ''"
                            mode="date"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    <div class="kt-subheader__search">
                        <label class="filter-label" for="payments_collectors_to">
                            <i class="fas fa-calendar-alt"></i>
                            <span>To</span>
                        </label>
                        <x-ios-dtp
                            name="to"
                            id="payments_collectors_to"
                            class="filter-input-global"
                            :value="$to ?? ''"
                            mode="date"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    @if(isset($clients))
                        <div class="kt-subheader__search" style="width:100%">
                            <label class="filter-label" for="doctor">
                                <i class="fas fa-user-md"></i>
                                <span>Doctor</span>
                            </label>
                            <select style="width:100%" class="selectpicker form-control clearOnAll filter-input-global" multiple name="doctor[]" id="doctor" data-live-search="true" title="All" data-hide-disabled="true" data-container="body">
                                <option value="all" {{ (isset($selectedClients) && $selectedClients == 'all') ? 'selected' : '' }}>All</option>
                                @foreach($clients as $d)
                                    <option value="{{ $d->id }}" {{ (isset($selectedClients) && is_array($selectedClients) && in_array($d->id, $selectedClients)) ? 'selected' : '' }}>{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3">
                    @if(isset($collectors))
                        <div class="kt-subheader__search" style="width:100%">
                            <label class="filter-label" for="collectors">
                                <i class="fas fa-user-tie"></i>
                                <span>Collector</span>
                            </label>
                            <select style="width:100%" class="selectpicker form-control clearOnAll filter-input-global" multiple name="collectors[]" id="collectors" data-live-search="true" title="All" data-hide-disabled="true" data-container="body">
                                <option value="all" {{ (isset($selectedCollectors) && $selectedCollectors == 'all') ? 'selected' : '' }}>All</option>
                                @foreach($collectors as $d)
                                    <option value="{{ $d->id }}" {{ (isset($selectedCollectors) && is_array($selectedCollectors) && in_array($d->id, $selectedCollectors)) ? 'selected' : '' }}>{{ $d->first_name . ' ' . $d->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-3 sigma-filter-action-col">
                    <button type="submit" class="btn btn-primary sigma-apply-btn cases-filter-btn cases-filter-btn--search">
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
                    <span class="materials-total-label">Total Amount</span>
                    <div class="materials-total-value">
                        <span class="materials-total-amount sigma-summary-value--neutral">{{ number_format($payments->sum('amount')) }}</span>
                        <span class="materials-total-currency">JOD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sigma-table-free">
        <table id="datatable" class="table sunriseTable order-column display compact cell-border dataTable no-footer sigma-list-table" role="grid" aria-describedby="datatable_info">
            <thead>
            <tr role="row">
                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending" style="width: 50.93px;">ID</th>
                <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Collector: activate to sort column ascending" style="width: 83.1445px;">Collector</th>
                <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Doctor: activate to sort column ascending" style="width: 240px;">Doctor</th>
                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Amount: activate to sort column ascending" style="width: 148.32px;">Amount</th>
                <th class="sorting sigma-head-left" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Type: activate to sort column ascending" style="width: 126.035px;">Type</th>
                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-label="Paid On: activate to sort column ascending" style="width: 160.664px;">Paid On</th>
            </tr>
            </thead>

            <tbody>
            @foreach($payments as $payment)
                <tr role="row" class="odd clickable" data-toggle="modal" data-target="#actionsDialog{{$payment->id}}">
                    <td class="sorting_1 sigma-body-center">{{ $payment->id }}</td>
                    <td class="sigma-body-left">{{ $payment->collectorUserRecord->name_initials }}</td>
                    <td class="sigma-body-left">{{ $payment->client->name }}</td>
                    <td class="sigma-body-center">{{ $payment->amount }} JOD</td>
                    <td class="sigma-body-left">{{ $payment->from_bank ? $payment->notes : 'Cash' }}</td>
                    <td class="sigma-body-center">{{ substr($payment->created_at, 0, 16) }}</td>
                </tr>

                <div class="modal sigma-modal--delivery-receive-payment" tabindex="-1" role="dialog" id="actionsDialog{{$payment->id}}">
                    <input type="hidden" name="case_id" value="{{ $payment->id }}">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Payment Actions</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="doctor">Doctor: </label>
                                        <h5 id="doctor"><b>{{ $payment->client->name }}</b></h5>
                                    </div>
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="pat">Price: </label>
                                        <h5 id="pat"><b>{{ $payment->amount }}</b></h5>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="doctor">Collector: </label>
                                        <h5 id="doctor"><b>{{ $payment->collectorUserRecord->name_initials }}</b></h5>
                                    </div>
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="pat">Paid On: </label>
                                        <h5 id="pat"><b>{{ substr($payment->created_at, 0, 16) }}</b></h5>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="doctor">Type: </label>
                                        <h5 id="doctor"><b>{{ $payment->from_bank ? $payment->notes : 'Cash' }}</b></h5>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="modal-footer fullBtnsWidth">
                                <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%">
                                    <div class="row">
                                        @if(!isset($payment->recieved_on))
                                            <div class="col-12 padding5px">
                                                <a href="{{ route('receive-payment', $payment->id) }}">
                                                    <button type="button" class="btn btn-success"><i class="fa-solid fa-hand-holding-dollar"></i> Receive </button>
                                                </a>
                                            </div>
                                        @endif
                                        @if(Auth()->user()->is_admin)
                                            <div class="col-12 padding5px">
                                                <a onclick="confirmation(event)" href="{{ route('delete-payment', $payment->id) }}" style="color:red">
                                                    <button type="button" class="btn btn-danger"><i class="fa-solid fa-trash-can"></i> Delete Payment</button>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-12 padding5px">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100%">Cancel</button>
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
</div>

@endsection

@push('js')

    <!-- Responsive and datatable js -->
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#datatable').DataTable(
                {
                    "pageLength": 25,
                    "searching": false,
                    "lengthChange": false,
                    "order": [[ 4, "desc" ]],
                }
            );
        } );
        function confirmation(ev) {
            ev.preventDefault();
            var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
            console.log(urlToRedirect); // verify if this is the right URL
            swal.fire({
                title: "Are you sure?",
                text: "Once deleted, Doctor balance will be updated accordingly!",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: 'Delete Payment',
                denyButtonText: `Cancel`,
            })
                .then((result) => {
                    // redirect with javascript here as per your logic after showing the alert using the urlToRedirect value
                    if (result.isConfirmed) {
                        window.location = urlToRedirect;
                        //swal.fire("Poof! Your imaginary file has been deleted!");
                    } else if (result.isDenied) {
                       swal.fire("Payment not deleted.");
                    }
                });
        }
    </script>
@endpush
