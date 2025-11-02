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
}
</style>
@php
    $permissions = Cache::get('user' . Auth()->user()->id);
@endphp

<form class="kt-form" method="GET" action="{{ route('clients-index') }}">
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card" style="box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-radius: 8px;">
                <div class="card-body">
                    <div class="row align-items-end">
                        {{-- Date Filter --}}
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 pr-2">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <label for="from" style="font-weight: 600; font-size: 13px; color: #525f7f;">From Date:</label>
                                <input class="form-control SDTP" name="from" type="text"
                                       value="{{ old('from', $from ?? '') }}" required readonly
                                       style="height: 38px;" />
                            @endif
                        </div>

                        {{-- Doctor Filter --}}
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 px-2">
                            <label for="doctor" style="font-weight: 600; font-size: 13px; color: #525f7f;">Filter by Doctor:</label>
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

                        {{-- Add Doctor Button --}}
                        <div class="col-lg-3 col-md-6 col-sm-6 mb-2 px-2">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <label style="font-weight: 600; font-size: 13px; color: #525f7f;">&nbsp;</label>
                                <a href="{{ route('new-dentist-view') }}" class="btn btn-success btn-block" style="height: 38px; font-weight: 600;">
                                    <i class="fa fa-plus-circle"></i> Add New Doctor
                                </a>
                            @endif
                        </div>

                        {{-- Mobile Access Button --}}
                        <div class="col-lg-2 col-md-6 col-sm-6 mb-2 px-2">
                            @if(Auth()->user()->is_admin)
                                <label style="font-weight: 600; font-size: 13px; color: #525f7f;">&nbsp;</label>
                                <a href="{{ route('mobile-stats-configs') }}" class="btn btn-info btn-block" style="height: 38px; font-weight: 600;">
                                    <i class="fa fa-mobile"></i> Mobile
                                </a>
                            @endif
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-lg-1 col-md-6 col-sm-6 mb-2 pl-2">
                            <label for="active" style="font-weight: 600; font-size: 13px; color: #525f7f;">Status:</label>
                            <select name="active" id="active" class="form-control" onchange="this.form.submit()" style="height: 38px;">
                                <option value="1" {{ (old('active', $status) == 1) ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ (old('active', $status) == 0) ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    {{-- Apply Filters and Total Balance Row --}}
                    <div class="row mt-3 pt-3 align-items-center" style="border-top: 1px solid #e9ecef;">
                        {{-- Apply Filters Button --}}
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2 pr-3">
                            <button type="submit" class="btn btn-primary btn-block" style="height: 42px; font-weight: 600;">
                                <i class="fa fa-search"></i> Apply Filters
                            </button>
                        </div>

                        {{-- Total Balance Display --}}
                        <div class="col-lg-2 col-md-4 col-sm-6 mb-2 pl-3">
                            @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <div style="background: #f7fafc; border: 2px solid #2d5f6d; border-radius: 8px; padding: 8px 12px; height: 42px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #525f7f; font-size: 12px; font-weight: 500; margin-right: 6px;">Balance:</span>
                                    <span style="color: #1a202c; font-size: 18px; font-weight: 700;">{{ number_format($totalBalance) }}</span>
                                    <span style="color: #2d5f6d; font-size: 14px; font-weight: 600; margin-left: 4px;">JOD</span>
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
                                <th  style="font-weight: bold">ID</th>
                                <th  style="font-weight: bold">Name</th>
                                <th  style="font-weight: bold">Personal Phone</th>
                                <th  style="font-weight: bold">Clinic Phone</th>
                                @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <th>Balance</th>
                                @endif


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr id="{{$client->id}}" class="odd clickable {{ $client->active ? '' : 'table-secondary' }}" data-toggle="modal" data-target="#actionsDialog{{$client->id}}" style="{{ $client->active ? '' : 'opacity: 0.6;' }}">
                                    <td>
                                        <span class="tabledit-span tabledit-identifier">{{$client->id}}</span>
                                    </td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->name}}
                                                @if(!$client->active)
                                                    <span class="badge badge-secondary ml-1">Disabled</span>
                                                @endif
                                            </span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->phone}}</span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->clinic_phone}}</span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="John" style="display: none;" disabled=""></td>
                                    @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{isset($from) ? $client->balanceAt($from) : $client->balance}}</span><input
                                                class="tabledit-input form-control input-sm" type="text" name="col1"
                                                value="Doe" style="display: none;" disabled=""></td>
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
                                                    <h4 style="color:#ff0000"><b>{{$client->name}}</b></h4>
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
                                                    <div id="chequeDetailsInputs{{$client->id}}" style="display:none">
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

                                                <div class="form-group row" style="margin-bottom: 0px">
                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                        <label for="doctor">Doctor: </label>
                                                        <h5 id="doctor"><b>{{$client->name}}</b></h5>
                                                    </div>
                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                        <label for="pat">Balance: </label>
                                                        <h5 id="pat"><b>{{isset($from) ? $client->balanceAt($from) : $client->balance}}</b></h5>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="form-group row">


                                            </div>
                                            <div class="modal-footer fullBtnsWidth" >
                                                <div class="row"  style=" margin-right: 0px; margin-left: 0px;width:100%">

                                                        <div class="row">
                                                            <!-------------------------
                                                                   ------ View Voucher ------
                                                                   -------------------------->
                                                            {{--<div class="col-6 padding5px" >--}}
                                                                {{--<a  href="{{route('view-voucher',$case->id)}}">--}}
                                                                    {{--<button type="button" class="btn btn-info "><i--}}
                                                                                {{--class="fas fa-print"></i> View Voucher </button>--}}
                                                                {{--</a></div>--}}
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
                                                        </div>


                                                    <div class="col-12 padding5px" >
                                                        <button type="button" class="btn btn-secondary " data-dismiss="modal" style="width:100%">Cancel</button>
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
            if (document.getElementById('cheque'.concat(id)).checked) {
                document.getElementById('chequeDetailsInputs'.concat(id)).style.display = 'block';
            }
            else document.getElementById('chequeDetailsInputs'.concat(id)).style.display = 'none';

        }
    </script>
@endpush
