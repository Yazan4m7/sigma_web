@extends('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ])

@section('content')
<style>
/* Modal dialog border radius - all corners uniform */
.modal-content {
    border-radius: 25px !important;
}

/* Modal footer rounded bottom corners */
.modal-footer {
    border-bottom-left-radius: 25px !important;
    border-bottom-right-radius: 25px !important;
}

#my-table_wrapper{
    padding: 0 16px;
    box-sizing: border-box;
    max-width: 100%;
}
.bootstrap-select>select {
    position: absolute !important;
    bottom: 0;
    left: 0;
}
@media screen and (max-width: 991px) {
    .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col, .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm, .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md, .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg, .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl, .col-xl-auto {
         padding: 0;
    }
}
.dataTables_wrapper .row {
    margin-left: 0;
    margin-right: 0;
    width: 100%;
    box-sizing: border-box;
}

.modal-footer .btn {
    margin: 3px;
}

.dropdown-toggle::after {
    display: inline-block !important;
}
    .dropdown-menu{
        color:inherit;
    }
.modal-footer{
    padding: 0 !important;
}
@media screen and (max-width: 768px) {
    table {
        table-layout: fixed;
    }
    .filters-row > div {
        margin-bottom: 10px !important;
    }
    .filters-row .btn-block,
    .filters-row .form-control,
    .filters-row .selectpicker {
        width: 100% !important;
    }
    .apply-row > div {
        margin-bottom: 10px !important;
    }
    .apply-row button {
        width: 100%;
    }
}
.client-actions-row {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    margin-left: 0;
    margin-right: 0;
}
.client-actions-row > [class*="col-"] {
    flex: 1 1 50%;
    max-width: 50%;
    padding: 5px;
}
@media (max-width: 576px) {
    .client-actions-row > [class*="col-"] {
        flex: 1 1 100%;
        max-width: 100%;
    }
}

.filters-row > [class*="col-"],
.apply-row > [class*="col-"] {
    min-width: 0;
}

.filters-row .selectpicker,
.filters-row .form-control,
.filters-row .btn,
.apply-row .btn {
    width: 100%;
}

.filters-row .bootstrap-select,
.apply-row .bootstrap-select {
    width: 100% !important;
    max-width: 100%;
    min-width: 0;
    box-sizing: border-box;
}

.filters-row .bootstrap-select > .dropdown-toggle,
.apply-row .bootstrap-select > .dropdown-toggle {
    width: 100%;
}

.balance-summary {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.balance-summary span {
    white-space: nowrap;
}

.globalTable {
    width: 100% !important;
    max-width: 100%;
    box-sizing: border-box;
}

.balance-col {
    text-align: center;
}

.apply-button-col {
    display: flex;
    justify-content: center;
}

.apply-button-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
}

.apply-button-wrapper .btn {
    width: auto;
}

.filters-card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.filter-label {
    font-weight: 600;
    font-size: 13px;
    color: #525f7f;
}

.filter-input {
    height: 38px;
}

.filter-apply-btn {
    height: 42px;
    font-weight: 600;
}

.apply-row {
    border-top: 1px solid #e9ecef;
}

.balance-summary-box {
    background: #f7fafc;
    border: 2px solid #2d5f6d;
    border-radius: 8px;
    padding: 8px 12px;
    min-height: 42px;
}

.balance-summary__label {
    color: #525f7f;
    font-size: 12px;
    font-weight: 500;
    margin-right: 6px;
}

.balance-summary__value {
    color: #1a202c;
    font-size: 18px;
    font-weight: 700;
}

.balance-summary__currency {
    color: #2d5f6d;
    font-size: 14px;
    font-weight: 600;
    margin-left: 4px;
}

.table-head {
    font-weight: 700;
}

.client-row--inactive {
    opacity: 0.6;
}

.client-name-highlight {
    color: #dc3545;
}

@media (max-width: 575.98px) {
    .apply-button-col {
        margin-left: auto;
        margin-right: auto;
        max-width: 320px;
        width: 100%;
    }
}
/* Actions aligned top-right */
.doctor-card-body {
    position: relative;
}

.card-actions-row {
    position: absolute;
    top: 1.25rem;
    right: 1.25rem;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: auto;
    margin: 0;
    z-index: 2;
}

.doctor-actions {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-left: auto;
}

.status-tab {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.35rem 0.6rem;
    border: 1px solid #d7e0e7;
    border-radius: 12px;
    background: #f8fbfd;
    box-shadow: 0 2px 8px rgba(45, 95, 109, 0.08);
}

.status-label {
    font-weight: 600;
    font-size: 12px;
    color: #3f5a6d;
    margin: 0;
}

.status-toggle {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    margin: 0;
}

.status-toggle input[type="checkbox"] {
    appearance: none;
    width: 38px;
    height: 20px;
    border: 1px solid #b9c7d3;
    border-radius: 999px;
    background: #e6eef3;
    position: relative;
    cursor: pointer;
    box-shadow: inset 0 0 0 1px rgba(45, 95, 109, 0.1);
}

.status-toggle input[type="checkbox"]::after {
    content: "";
    position: absolute;
    top: 1px;
    left: 1px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #2d5f6d;
    transition: transform 0.2s ease, background 0.2s ease;
}

.status-toggle input[type="checkbox"]:checked {
    border-color: #28a745;
    background: #28a745;
}

.status-toggle input[type="checkbox"]:checked::after {
    transform: translateX(18px);
    background: #fff;
}

.status-toggle input[type="checkbox"]:focus-visible {
    outline: 2px solid #2d5f6d;
    outline-offset: 2px;
}

.toggle-text {
    font-weight: 600;
    font-size: 12px;
    color: #3f5a6d;
}

.status-toggle input[type="checkbox"]:checked ~ .toggle-text--on {
    display: inline;
}

.status-toggle input[type="checkbox"]:checked ~ .toggle-text--off {
    display: none;
}

.status-toggle input[type="checkbox"]:not(:checked) ~ .toggle-text--on {
    display: none;
}

.status-toggle input[type="checkbox"]:not(:checked) ~ .toggle-text--off {
    display: inline;
}

.icon-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1px solid #d7e0e7;
    border-radius: 10px;
    background: #ffffff;
    color: #2d5f6d;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(45, 95, 109, 0.08);
    transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}

.icon-action i {
    font-size: 15px;
    line-height: 1;
}

.icon-action:hover {
    background: #2d5f6d;
    border-color: #2d5f6d;
    color: #ffffff;
    transform: translateY(-1px);
}

.icon-action:focus-visible {
    outline: 2px solid #2d5f6d;
    outline-offset: 2px;
}

.icon-action--success {
    border-color: #28a745;
    color: #28a745;
}

.icon-action--success:hover {
    background: #28a745;
    border-color: #28a745;
    color: #ffffff;
}

@media (max-width: 991.98px) {
    .card-actions-row {
        position: static;
        width: 100%;
        margin-bottom: 0.75rem;
    }
}

</style>
@php
    $permissions = Cache::get('user' . Auth()->user()->id);
@endphp

<form class="kt-form" method="GET" action="{{ route('clients-index') }}">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card filters-card">
                <div class="card-body doctor-card-body">
                    <div class="card-actions-row">
                        <div class="doctor-actions">
                            <div class="status-tab">
                                <span class="status-label">Status:</span>
                                <label class="status-toggle">
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" id="active" name="active" value="1" {{ (old('active', $status) == 1) ? 'checked' : '' }}>
                                    <span class="toggle-text toggle-text--on">Enabled</span>
                                    <span class="toggle-text toggle-text--off">Disabled</span>
                                </label>
                            </div>
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <a href="{{ route('new-dentist-view') }}" class="icon-action icon-action--success" aria-label="Add New Doctor">
                                    <i class="fa fa-plus"></i>
                                    <span class="sr-only">Add New Doctor</span>
                                </a>
                            @endif
                            @if(Auth()->user()->is_admin)
                                <a href="{{ route('mobile-stats-configs') }}" class="icon-action" aria-label="Mobile">
                                    <i class="fa fa-phone"></i>
                                    <span class="sr-only">Mobile</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row align-items-end filters-row">
                        {{-- Date Filter --}}
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-2 pr-2">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <label for="from" class="filter-label">From Date:</label>
                                <input id="from" class="form-control SDTP filter-input" name="from" type="text"
                                       value="{{ old('from', $from ?? '') }}" required readonly
                                />
                            @endif
                        </div>

                        {{-- Doctor Filter --}}
                        <div class="col-lg-3 col-md-6 col-sm-8 col-8 mb-2 px-2">
                            <label for="doctor" class="filter-label">Filter by Doctor:</label>
                            <select class="selectpicker form-control clearOnAll" multiple
                                    name="doctor[]" id="doctor" data-live-search="true"
                                    title="All Doctors" data-hide-disabled="true">
                                <option value="all"
                                    {{ (isset($selectedClients) && in_array('all', $selectedClients)) ? 'selected' : '' }}>
                                    All Doctors
                                </option>
                                @foreach($allClients as $d)
                                    <option value="{{ $d->id }}"
                                        {{ (isset($selectedClients) && in_array($d->id, $selectedClients)) ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Apply Filters Button --}}
                        <div class="col-lg-2 col-md-6 col-sm-4 col-4 mb-2 pl-2 apply-button-col">
                            <div class="apply-button-wrapper">
                                <button type="submit" class="btn btn-primary filter-apply-btn">
                                    <i class="fa fa-search"></i> Apply Filters
                                </button>
                            </div>
                        </div>

                    </div>

                    {{-- Apply Filters and Total Balance Row --}}
                    <div class="row mt-3 pt-3 align-items-center apply-row">
                        {{-- Total Balance Display --}}
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2 pl-3">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <div class="balance-summary balance-summary-box">
                                    <span class="balance-summary__label">Balance:</span>
                                    <span class="balance-summary__value">{{ number_format($totalBalance) }}</span>
                                    <span class="balance-summary__currency">JOD</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-lg-8 col-md-4 col-sm-12 mb-2">
                            {{-- Empty space on the right --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


            <hr>
                    <div class="">
                        <table class="globalTable nowrap compact stripe sunriseTable " id="my-table">
                            <thead>
                            <tr >
                                <th class="table-head">ID</th>
                                <th class="table-head">Name</th>
                                @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <th class="balance-col">Balance</th>

                                @endif


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr id="{{$client->id}}" class="odd clickable {{ $client->active ? '' : 'table-secondary client-row--inactive' }}" data-toggle="modal" data-target="#actionsDialog{{$client->id}}">
                                    <td>
                                        <span class="tabledit-span tabledit-identifier">{{$client->id}}</span>
                                    </td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->name}}
                                                @if(!$client->active)
                                                    <span class="badge badge-secondary ml-1">Disabled</span>
                                                @endif
                                            </span><input
                                                class="tabledit-input form-control input-sm d-none" type="text" name="col1"
                                                value="John" disabled=""></td>
                                    @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                    <td class="tabledit-view-mode balance-col"><span
                                                class="tabledit-span">{{ number_format(isset($from) ? $client->balanceAt($from) : $client->balance) }}</span><input
                                                class="tabledit-input form-control input-sm d-none" type="text" name="col1"
                                                value="Doe" disabled=""></td>

                                        @endif

                                </tr>
                                @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                                <div class="modal" tabindex="-1" role="dialog" id="myModal{{$client->id}}">
                                    <form action="{{route('new-payment')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$client->id}}">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">New Payment balance</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4 class="client-name-highlight"><b>{{$client->name}}</b></h4>
                                                    <label>Payment amount</label>
                                                    <input type="number" class="form-control" name="amount" required>
                                                    <br/>
                                                    <label>Payment type:</label> <br/>

                                                    <input type="radio" id="cash{{$client->id}}"
                                                           onclick="paymentTypeChange({{$client->id}});"
                                                           name="payment_type" value="cash">
                                                    <label for="cash{{$client->id}}">دفعة نقدية</label><br>
                                                    <input type="radio" id="cheque{{$client->id}}"
                                                           onclick="paymentTypeChange({{$client->id}});"
                                                           name="payment_type" value="cheque">
                                                    <label for="cheque{{$client->id}}">شيك بنكي</label><br>
                                                    <input type="radio" id="transfer{{$client->id}}"
                                                           onclick="paymentTypeChange({{$client->id}});"
                                                           name="payment_type" value="transfer">
                                                    <label for="transfer{{$client->id}}">حوالة بنكية/ كليك</label><br>
                                                    <br/>
                                                    <div id="chequeDetailsInputs{{$client->id}}" class="cheque-details d-none">
                                                        <label>Bank:</label>

                                                        <div class="kt-form__control">
                                                            <select class="form-control" id="bank" name="bank_id">
                                                                @foreach($banks as $bank)
                                                                    <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <br/>
                                                        <label>Cheque number:</label>
                                                        <input type="text" class="form-control" name="chequeNumber">
                                                        <br/>
                                                    </div>
                                                    <label>Extra details (Optional):</label>
                                                    <textarea name="note" class="form-control"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if( Auth()->user()->is_admin)
                                    <div class="modal" tabindex="-1" role="dialog" id="accountDiscount{{$client->id}}">
                                        <form action="{{route('account-discount')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$client->id}}">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Doctor balance</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label>Discount amount</label>
                                                        <input type="number" class="form-control" name="discountAmount" required>
                                                        <br/>
                                                        <label>Date of discount:  :</label>
                                                        <input type="datetime-local" name="discount_date" class="form-control"></input>
                                                        <br/>

                                                        <label>Details ( How it appears on account statement) :</label>
                                                        <input type="text" name="discount_title" class="form-control"></input>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <div class="modal" tabindex="-1" role="dialog" id="actionsDialog{{$client->id}}">

                                    <input type="hidden" name="case_id" value="{{$client->id}}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Doctor Account</h5>

                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group row mb-0">
                                                    <div class="col-12 col-md-6">
                                                        <label for="doctor">Doctor:</label>
                                                        <h5 id="doctor" class="mb-0"><b>{{$client->name}}</b></h5>
                                                    </div>
                                                    @if(Auth()->user()->is_admin)
                                                        <div class="col-12 col-md-6">
                                                            <label for="pat">Balance:</label>
                                                            <h5 id="pat" class="mb-0">
                                                                <b>{{ isset($from) ? $client->balanceAt($from) : $client->balance }}</b>
                                                            </h5>
                                                        </div>
                                                    @endif
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="modal-footer fullBtnsWidth">
                                                <div class="row client-actions-row">
                                                    @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                                        <div class="col-6 padding5px" >
                                                            <a href="{{route('client-statement-admin', $client->id)}}">
                                                                <button type="button" class="btn btn-primary ">
                                                                    Account Statement</button></a>
                                                        </div>

                                                        <div class="col-6 padding5px" >
                                                            <a href="{{route('client-view-edit',['id' =>$client->id])}}">
                                                                <button type="button" class="btn btn-danger ">
                                                                    Edit Record</button></a>
                                                        </div>
                                                    @endif
                                                    @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                                                        <div class="col-6 padding5px" >
                                                            <a data-toggle="modal" data-target="#myModal{{$client->id}} "
                                                               >
                                                                <button type="button" class="btn btn-warning " data-dismiss="modal" >
                                                                    Add a payment </button></a>
                                                        </div>
                                                    @endif
                                                    @if( Auth()->user()->is_admin)
                                                        <div class="col-6 padding5px" >
                                                        <a href="{{route('dentist-cases',['id' =>$client->id])}}">
                                                            <button type="button" class="btn btn-info ">
                                                            View Cases </button></a>
                                                        </div>
                                                        <div class="col-6 padding5px" >
                                                            <a  href="{{route('dentist-invoices',['id' =>$client->id])}}">
                                                            <button type="button" class="btn btn-info ">
                                                                View Invoices </button></a>
                                                        </div>
                                                        <div class="col-6 padding5px" >
                                                            <a href="{{route('dentist-payments',['id' =>$client->id])}}">
                                                                <button type="button" class="btn btn-info ">
                                                                    View Payments </button></a>
                                                        </div>
                                                        <div class="col-6 padding5px" >
                                                            <a data-toggle="modal" data-target="#accountDiscount{{$client->id}} ">
                                                            <button type="button" class="btn btn-danger " data-dismiss="modal" >
                                                                    Create a discount </button></a>
                                                        </div>
                                                        <div class="col-6 padding5px" >
                                                            <a href="{{route('toggle-client-active', $client->id)}}" onclick="return confirm('Are you sure you want to {{ $client->active ? 'disable' : 'enable' }} this doctor?');">
                                                                <button type="button" class="btn {{ $client->active ? 'btn-warning' : 'btn-success' }}">
                                                                    {{ $client->active ? 'Disable' : 'Enable' }}
                                                                </button>
                                                            </a>
                                                        </div>

                                                    @endif

                                                    <div class="col-12 padding5px" >
                                                        <button type="button" class="btn btn-secondary btn-block" data-dismiss="modal">Cancel</button>
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
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('.selectpicker').selectpicker();
          $('.selectpicker').selectpicker('refresh');
        });

    </script>
    <script>
        function paymentTypeChange(id) {
            const cheque = document.getElementById('cheque' + id);
            const details = document.getElementById('chequeDetailsInputs' + id);
            if (!cheque || !details) {
                return;
            }
            if (cheque.checked) {
                details.classList.remove('d-none');
            } else {
                details.classList.add('d-none');
            }
        }
    </script>
@endpush
