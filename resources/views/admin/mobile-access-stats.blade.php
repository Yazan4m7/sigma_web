@extends('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ])

@section('content')
<style>
.dropdown-toggle::after {
    display: inline-block !important;
}
    .sigma-modal--admin-mobile-access .modal-content {
        border-radius: 25px !important;
    }

    .sigma-modal--admin-mobile-access .modal-footer {
        border-bottom-left-radius: 25px !important;
        border-bottom-right-radius: 25px !important;
        padding: 0 !important;
    }

    .sigma-modal--admin-mobile-access .modal-footer .btn {
        margin: 3px;
    }

    .sigma-modal--admin-mobile-access .client-actions-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        width: 100%;
        margin: 0;
    }

    .sigma-modal--admin-mobile-access .client-actions-row > [class*="col-"] {
        padding: 0;
        max-width: 100%;
    }

    .sigma-modal--admin-mobile-access .client-actions-row .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        font-size: 13px;
        padding: 8px 4px;
        text-decoration: none;
    }

    .sigma-modal--admin-mobile-access .client-actions-row .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
    }

    .sigma-modal--admin-mobile-access .client-actions-row .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: #fff;
    }

    .sigma-modal--admin-mobile-access .client-actions-row .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: #fff;
    }

    .sigma-modal--admin-mobile-access .client-actions-row > .col-12 {
        grid-column: span 2;
    }

    .mobile-access-stats-page .sigma-list-filter-card {
        background: transparent !important;
        border: 0 !important;
        border-radius: 0 !important;
        margin-top: 16px !important;
        margin-bottom: 24px !important;
        padding: 0 !important;
        box-shadow: none !important;
        overflow: visible !important;
    }
.sigma-standard-theme .sigma-list-page {
    padding-top: 10px;
}

    .mobile-access-stats-page .mobile-access-filter-row {
        background: #ffffffa8 !important;
        border: 1px solid rgba(188, 206, 216, 0.3) !important;
        border-radius: 16px !important;
        box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
        padding: 20px 12px 16px !important;
        position: relative;
        overflow: visible !important;
        backdrop-filter: blur(10px);
        isolation: isolate;
        margin: 0 !important;
        z-index: 20;
    }

    .mobile-access-stats-page .mobile-access-filter-row::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
        border-radius: 16px 16px 0 0;
        pointer-events: none;
    }

    .mobile-access-stats-page .filter-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #51646f;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select,
    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select > .dropdown-toggle {
        width: 100% !important;
        max-width: 100% !important;
    }

    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select > .dropdown-toggle,
    .mobile-access-stats-page .sigma-mobile-filter-submit {
        min-height: 38px !important;
        height: 38px !important;
    }

    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select > .dropdown-toggle {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
    }

    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select.open:not(.bs-container),
    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select.show:not(.bs-container) {
        position: relative !important;
        z-index: 9999 !important;
    }

    .mobile-access-stats-page .bs-container.bootstrap-select.open,
    .mobile-access-stats-page .bs-container.bootstrap-select.show,
    .mobile-access-stats-page .mobile-access-filter-row .bootstrap-select .dropdown-menu {
        z-index: 9999 !important;
    }

    .mobile-access-stats-page #my-table_wrapper,
    .mobile-access-stats-page table.dataTable,
    .mobile-access-stats-page .dataTables_wrapper {
        position: relative;
        z-index: 1;
    }
.sigma-standard-theme .sunriseTable thead th, .sigma-standard-theme:not(.sigma-preserve-table-headers) .sunriseTable.sigma-sticky-table-header thead th, .sigma-standard-theme:not(.sigma-preserve-table-headers) table.sunriseTable.dataTable thead th {
    background-color: var(--sigma-primary) !important;
    color: #ffffff !important;
}
.sigma-standard-theme:not(.sigma-preserve-table-headers) .sunriseTable thead th:not(:first-child):not(:last-child), .sigma-standard-theme:not(.sigma-preserve-table-headers) .sunriseTable.sigma-sticky-table-header thead th:not(:first-child):not(:last-child){
    background-color: var(--sigma-primary) !important;
    color: #ffffff !important;

}
.sunriseTable thead th:nth-child(1), .sunriseTable tbody td , td{

    color:var(--sigma-primary) !important;
}

    .mobile-access-stats-page .sigma-mobile-filter-submit {
        min-width: 120px;
        padding: 0 18px !important;
        white-space: nowrap;
    }

    @media screen and (min-width: 768px) {
        .mobile-access-stats-page .sigma-mobile-filter-action-col {
            flex: 0 0 auto !important;
            width: auto !important;
            max-width: none !important;
            padding-left: 8px !important;
        }
    }

    .dropdown-menu {
        color:inherit;
    }

        @media screen and (max-width: 768px){
            table {
                table-layout: fixed;
            }
        }

        /* cohesive table styling */
        .sunriseTable thead th {
            background: #0f8c8d;
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: none;
            border-bottom: 3px solid rgba(255, 255, 255, 0.4);
        }

        .sunriseTable tbody tr {
            transition: background 0.2s ease;
        }
        .main-con{
            padding: 0 !important;
        }

        .sunriseTable tbody tr:hover {
            background: rgba(15, 140, 141, 0.08);
        }

        .sunriseTable td {
            font-size: 0.97rem;
            font-weight: 500;
            color: #1f2937;
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

        .mobile-access-panel {
            padding: 18px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
        }
</style>
    @php
        $permissions = safe_permissions();
    @endphp
    <div class="mobile-access-stats-page sigma-list-page">
    <div class="row main-con">
        <div class="col-lg-12 col-sm-12">
            <form class="kt-form" method="GET" action="{{route('mobile-stats-configs')}}">
            <div class="container full-width sigma-list-filter-card">
            <div class="row mobile-access-filter-row">

                <div class="col-5 col-sm-8 col-md-6 col-lg-3 mb-2">
                    <label class="filter-label" for="doctor">
                        <i class="fas fa-user-md"></i>
                        <span>Doctor</span>
                    </label>
                    <select style="width:100%" class="selectpicker form-control clearOnAll" multiple data-container="body"
                            name="doctor[]" id="doctor"  data-live-search="true"
                            title="All" data-hide-disabled="true">
                        <option value="all" {{(isset($selectedClients) && in_array('all',$selectedClients) ? 'selected' : '')}}>All</option>
                        @foreach($allClients as $d)
                            <option value="{{$d->id}}" {{(isset($selectedClients) && in_array($d->id ,$selectedClients)) ? 'selected' : ''}}>{{$d->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-sm-auto mb-2 d-flex align-items-end sigma-mobile-filter-action-col">
                    <button type="submit" class="btn btn-primary sigma-mobile-filter-submit">Apply</button>
                </div>
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



                                <div class="modal sigma-modal--admin-mobile-access" tabindex="-1" role="dialog" id="actionsDialog{{$client->id}}">

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
