@extends('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ])

@section('content')
<style>
.dropdown-toggle::after {
    display: inline-block !important;
}
    .sigma-modal--adminhth-mobile-access .modal-content {
        border-radius: 25px !important;
    }

    .sigma-modal--adminhth-mobile-access .modal-footer {
        border-bottom-left-radius: 25px !important;
        border-bottom-right-radius: 25px !important;
        padding: 0 !important;
    }

    .sigma-modal--adminhth-mobile-access .modal-footer .btn {
        margin: 3px;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        width: 100%;
        margin: 0;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row > [class*="col-"] {
        padding: 0;
        max-width: 100%;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        font-size: 13px;
        padding: 8px 4px;
        text-decoration: none;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .sigma-modal--adminhth-mobile-access .client-actions-row > .col-12 {
        grid-column: span 2;
    }

    .dropdown-menu {
        color:inherit;
    }

    .row {
        overflow: visible !important;
    }

    .mobile-access-stats-page,
    .mobile-access-stats-page .row,
    .mobile-access-stats-page .col-lg-12,
    .mobile-access-stats-page form {
        overflow: visible !important;
    }

    .mobile-access-stats-page .bootstrap-select.open:not(.bs-container),
    .mobile-access-stats-page .bootstrap-select.show:not(.bs-container),
    .mobile-access-stats-page .bs-container.bootstrap-select.open,
    .mobile-access-stats-page .bs-container.bootstrap-select.show,
    .mobile-access-stats-page .bootstrap-select .dropdown-menu {
        z-index: 9999 !important;
    }

    .mobile-access-stats-page #my-table_wrapper,
    .mobile-access-stats-page table.dataTable,
    .mobile-access-stats-page .dataTables_wrapper {
        position: relative;
        z-index: 1;
    }
    @media screen and (max-width: 767px) {
        .mobile-access-stats-page .sunriseTable th:nth-child(2),
        .mobile-access-stats-page .sunriseTable td:nth-child(2),
        .mobile-access-stats-page .sunriseTable th:nth-child(4),
        .mobile-access-stats-page .sunriseTable td:nth-child(4),
        .mobile-access-stats-page .sunriseTable th:nth-child(6),
        .mobile-access-stats-page .sunriseTable td:nth-child(6) {
            display: none !important;
        }
    }
@media screen and (max-width: 768px){
    table {
        table-layout: fixed;
    }
}
</style>
    @php
        $permissions = safe_permissions();
    @endphp
    <div class="mobile-access-stats-page">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <form class="kt-form" method="GET" action="{{route('mobile-stats-configs')}}">
            <div class="row">

                <div class="col-4">
                    <label>Doctor:</label>
                    <select style="width:100%" class="selectpicker form-control clearOnAll" multiple data-container="body"
                            name="doctor[]" id="doctor"  data-live-search="true"
                            title="All" data-hide-disabled="true">
                        <option value="all" {{(isset($selectedClients) && in_array('all',$selectedClients) ? 'selected' : '')}}>All</option>
                        @foreach($allClients as $d)
                            <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4">
                    <label> &nbsp; </label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Submit</button>
                </div>
            </div>

            </form>


            <hr>
                    <div class="">
                        <table class=" nowrap compact stripe sunriseTable " id="my-table">
                            <thead>
                            <tr >

                                <th  style="font-weight: bold">Name</th>
                                {{--<th  style="font-weight: bold">Personal Phone</th>--}}
                                {{--<th  style="font-weight: bold">Clinic Phone</th>--}}
                                <th  style="font-weight: bold">Doc. 30 Days Access</th>
                                <th  style="font-weight: bold">Doc. Last sign-in</th>
                                <th  style="font-weight: bold">Clinic. 30 Days Access</th>
                                <th  style="font-weight: bold">Clinic. Last sign-in</th>
                                <th  style="font-weight: bold">Clinic. Device</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr id="{{$client->id}}" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$client->id}}">

                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->name}}</span></td>
                                    {{--<td class="tabledit-view-mode"><span--}}
                                                {{--class="tabledit-span">{{$client->phone}}</span></td>--}}
                                    {{--<td class="tabledit-view-mode"><span--}}
                                                {{--class="tabledit-span">{{$client->clinic_phone}}</span></td>--}}

                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->loginInLast30Days()}}</span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{substr($client->lastSignIn(),0,16)}}</span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->clinicLoginInLast30Days()}}</span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{substr($client->clinicLastSignIn(),0,16)}}</span></td>
                                    <td class="tabledit-view-mode"><span
                                                class="tabledit-span">{{$client->clinicDevice()}}</span></td>

                                </tr>



                                <div class="modal sigma-modal--adminhth-mobile-access" tabindex="-1" role="dialog" id="actionsDialog{{$client->id}}">

                                    <input type="hidden" name="case_id" value="{{$client->id}}">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Doctor Account</h5>

                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="padding: 25px 1rem;">

                                                <div class="form-group row mb-0">
                                                    <div class="col-6 col-md-6">
                                                        <label for="doctor">Doctor: </label>
                                                        <h5 id="doctor" class="mb-0"><b>{{$client->name}}</b></h5>
                                                    </div>
                                                    <div class="col-6 col-md-6">
                                                        <label for="pat">Balance: </label>
                                                        <h5 id="pat" class="mb-0"><b>{{ $client->balance}}</b></h5>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-0 mt-3">
                                                    <div class="col-6 col-md-6 mb-2">
                                                        <label class="mb-1">Doc. 30 Days Access:</label>
                                                        <div><b>{{ $client->loginInLast30Days() }}</b></div>
                                                    </div>
                                                    <div class="col-6 col-md-6 mb-2">
                                                        <label class="mb-1">Doc. Last sign-in:</label>
                                                        <div><b>{{ substr($client->lastSignIn(), 0, 16) }}</b></div>
                                                    </div>
                                                    <div class="col-6 col-md-6 mb-2">
                                                        <label class="mb-1">Clinic. 30 Days Access:</label>
                                                        <div><b>{{ $client->clinicLoginInLast30Days() }}</b></div>
                                                    </div>
                                                    <div class="col-6 col-md-6 mb-2">
                                                        <label class="mb-1">Clinic. Last sign-in:</label>
                                                        <div><b>{{ substr($client->clinicLastSignIn(), 0, 16) }}</b></div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="mb-1">Clinic. Device:</label>
                                                        <div><b>{{ $client->clinicDevice() }}</b></div>
                                                    </div>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="modal-footer fullBtnsWidth">
                                                <div class="row client-actions-row">
                                                    @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                                        <div class="col-6">
                                                            <a href="{{route('client-statement-admin', $client->id)}}" class="btn btn-info">
                                                                <span class="btn-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                                                                <span class="btn-text">Account Statement</span>
                                                            </a>
                                                        </div>

                                                        <div class="col-6">
                                                            <a href="{{route('client-view-edit',['id' =>$client->id])}}" class="btn btn-danger">
                                                                <span class="btn-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                                                <span class="btn-text">Edit Record</span>
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if(Auth()->user()->is_admin)
                                                        <div class="col-6">
                                                            <a href="{{route('dentist-cases',['id' =>$client->id])}}" class="btn btn-info">
                                                                <span class="btn-icon"><i class="far fa-file-alt"></i></span>
                                                                <span class="btn-text">View Cases</span>
                                                            </a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="{{route('dentist-invoices',['id' =>$client->id])}}" class="btn btn-info">
                                                                <span class="btn-icon"><i class="fas fa-file-invoice"></i></span>
                                                                <span class="btn-text">View Invoices</span>
                                                            </a>
                                                        </div>
                                                        <div class="col-6">
                                                            <a href="{{route('dentist-payments',['id' =>$client->id])}}" class="btn btn-info">
                                                                <span class="btn-icon"><i class="fas fa-credit-card"></i></span>
                                                                <span class="btn-text">View Payments</span>
                                                            </a>
                                                        </div>
                                                    @endif

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

                            @endforeach

                            </tbody>

                        </table>
                    </div>

        </div>
    </div>
    </div>
    <script>
        $(document).ready(function () {
            const table = $('#my-table');
            if (table.length && $.fn.DataTable && !$.fn.DataTable.isDataTable(table)) {
                table.DataTable({
                    pageLength: 25,
                    searching: false,
                    lengthChange: false,
                    order: [],
                    columnDefs: [
                        { targets: [], orderable: true }
                    ]
                });
            }
        });
    </script>

@endsection
