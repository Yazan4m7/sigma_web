@extends('layouts.app' ,[ 'pageSlug' => "Abutments Delivery"])
@section('content')

    <style>
        :root {
            --surface-bg: #f4f6fb;
            --card-bg: #ffffff;
            --border-muted: #e3e8f0;
            --text-main: #1f2a37;
            --text-muted: #6b7280;
        }

        .abutments-delivery-page {
            background: var(--surface-bg);
            min-height: 100vh;
            padding: 1rem;
        }

        .abutments-delivery-page .delivery-filter-form {
            margin-bottom: 24px;
        }

        .abutments-delivery-page .cases-filter-card.delivery-filter-card {
            background: #ffffffa8 !important;
            border: 1px solid rgba(188, 206, 216, 0.3);
            border-radius: 16px;
            box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
            margin: 0 !important;
            padding: 20px 20px 16px !important;
            position: relative;
            overflow: hidden !important;
            backdrop-filter: blur(10px);
        }

        .abutments-delivery-page .cases-filter-card.delivery-filter-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%);
            border-radius: 16px 16px 0 0;
        }

        .abutments-delivery-page .cases-filter-row {
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

        @media screen and (min-width: 768px) {
            .abutments-delivery-page .cases-filter-row > [class*="col-"].sigma-filter-action-col {
                flex: 0 0 auto !important;
                width: auto !important;
                max-width: none !important;
            }
        }

        .abutments-delivery-page .cases-filter-row .mb-2,
        .abutments-delivery-page .cases-filter-row .mb-3 {
            margin-bottom: 12px !important;
        }

        .abutments-delivery-page .cases-filter-row .filter-label {
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

        .abutments-delivery-page .cases-filter-row .filter-label i {
            color: #2b7b7d;
            font-size: 13px;
        }

        .abutments-delivery-page .cases-filter-row .dtp-input,
        .abutments-delivery-page .cases-filter-row .ios-dtp-trigger,
        .abutments-delivery-page .cases-filter-row .form-control,
        .abutments-delivery-page .cases-filter-row .bootstrap-select > .dropdown-toggle {
            min-height: var(--cases-filter-height) !important;
            height: var(--cases-filter-height) !important;
            font-size: var(--cases-filter-font-size) !important;
            padding: var(--cases-filter-padding) !important;
            border-radius: var(--cases-filter-radius);
            border: var(--cases-filter-border) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            background: rgba(255, 255, 255, 0.88);
            box-shadow: none !important;
            font-weight: 500;
            line-height: 1.5;
            transition: all 0.3s ease;
        }

        .abutments-delivery-page .cases-filter-row .bootstrap-select,
        .abutments-delivery-page .cases-filter-row .ios-dtp-container,
        .abutments-delivery-page .cases-filter-row .filter-input-global,
        .abutments-delivery-page .cases-filter-row input.filter-input-global,
        .abutments-delivery-page .cases-filter-row select.filter-input-global,
        .abutments-delivery-page .cases-filter-row .filter-input-global .ios-dtp-trigger,
        .abutments-delivery-page .cases-filter-row select.filter-input-global + .bootstrap-select,
        .abutments-delivery-page .cases-filter-row select.filter-input-global + .bootstrap-select > .dropdown-toggle {
            width: 100% !important;
            max-width: 100% !important;
        }

        .abutments-delivery-page .cases-filter-row .ios-dtp-display,
        .abutments-delivery-page .cases-filter-row .bootstrap-select .filter-option-inner-inner {
            font-size: var(--cases-filter-font-size) !important;
            color: var(--cases-filter-color) !important;
            text-align: left;
            font-weight: 500;
        }

        .abutments-delivery-page .cases-filter-row .dtp-input:focus,
        .abutments-delivery-page .cases-filter-row .ios-dtp-trigger:focus,
        .abutments-delivery-page .cases-filter-row .form-control:focus,
        .abutments-delivery-page .cases-filter-row .bootstrap-select > .dropdown-toggle:focus {
            border-color: #408385 !important;
            box-shadow: 0 0 0 3px rgba(64, 131, 133, 0.15) !important;
            outline: 0;
        }

        .abutments-delivery-page .sigma-patient-name {
            font-weight: 700 !important;
        }

        .abutments-delivery-page #datatable tbody td {
            font-size: 17px !important;
            text-align: center !important;
        }

        .abutments-delivery-page table#datatable.dataTable thead th.sigma-head-left,
        .abutments-delivery-page table#datatable.dataTable thead th.sigma-head-center,
        .abutments-delivery-page table#datatable.dataTable tbody td.sigma-body-left,
        .abutments-delivery-page table#datatable.dataTable tbody td.sigma-body-center,
        .abutments-delivery-page table#datatable.dataTable tbody td.sigma-body-left span,
        .abutments-delivery-page table#datatable.dataTable tbody td.sigma-body-center span {
            text-align: center !important;
        }

        .abutments-delivery-page table#datatable.dataTable thead th:first-child,
        .abutments-delivery-page table#datatable.dataTable thead th:first-child.sigma-head-left,
        .abutments-delivery-page table#datatable.dataTable tbody td:first-child,
        .abutments-delivery-page table#datatable.dataTable tbody td:first-child.sigma-body-left,
        .abutments-delivery-page table#datatable.dataTable tbody td:first-child span {
            text-align: left !important;
        }

        .abutments-delivery-page .cases-filter-btn {
            width: auto;
            max-width: none;
            min-height: 38px;
            height: var(--cases-filter-height) !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 var(--cases-filter-button-pad-x) !important;
            border-radius: 12px !important;
            font-size: 14px !important;
            font-weight: 600;
            letter-spacing: 0.2px;
            line-height: 1;
            transition: all 0.3s ease;
        }

        .abutments-delivery-page .cases-filter-btn--search {
            background: linear-gradient(135deg, #408385 0%, #67aeb0 100%) !important;
            background-color: #4d9597 !important;
            border: 1px solid #408385 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(64, 131, 133, 0.24);
        }

        .abutments-delivery-page .cases-filter-btn--search:hover,
        .abutments-delivery-page .cases-filter-btn--search:focus {
            background: linear-gradient(135deg, #336f71 0%, #5ca0a2 100%) !important;
            background-color: #4a8d90 !important;
            border-color: #336f71 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(51, 111, 113, 0.24);
        }

        .abutments-delivery-page .cases-filter-btn--search:active,
        .abutments-delivery-page .cases-filter-btn--search:not(:disabled):not(.disabled):active {
            background: linear-gradient(135deg, #285f61 0%, #4c8587 100%) !important;
            background-color: #3d7678 !important;
            border-color: #285f61 !important;
            color: #ffffff !important;
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(40, 95, 97, 0.2);
        }

        .abutments-delivery-page .sigma-table-free,
        .abutments-delivery-page .sigma-table-free > .row,
        .abutments-delivery-page .sigma-table-free > .row > .col-12,
        .abutments-delivery-page #datatable_wrapper,
        .abutments-delivery-page #datatable_wrapper > .row {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .abutments-delivery-page #datatable.table-odd {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
            background: transparent !important;
        }

        .abutments-delivery-page #datatable.table-odd tbody > tr:nth-child(odd) {
            background-color: #ffffff !important;
        }

        .abutments-delivery-page #datatable.table-odd tbody > tr:nth-child(even) {
            background-color: #f5f8ff !important;
        }

        .abutments-delivery-page #datatable thead th {
            background: #d6ecee !important;
            color: #337374 !important;
            border-bottom: none !important;
            padding: 12px 14px !important;
            font-weight: 400 !important;
            text-align: center !important;
        }

        .abutments-delivery-page #datatable thead th:first-child,
        .abutments-delivery-page #datatable thead th:last-child {
            background: #408385 !important;
            color: #ffffff !important;
        }

        .abutments-delivery-page #datatable thead th:first-child {
            border-top-left-radius: 12px !important;
        }

        .abutments-delivery-page #datatable thead th:last-child {
            border-top-right-radius: 12px !important;
        }

        .tooltiptext {
            display: none;
        }
        th{
            white-space: nowrap;
        }

        @media screen and (max-width: 768px) {
            .content {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            .caseDeliTime , .doctor{
                display:none;
            }

            .row {
                padding: 3px;
            }
            table{
                table-layout: fixed;
            }
        }

        @media screen and (max-width: 767px) {
            .abutments-delivery-page .cases-filter-card.delivery-filter-card {
                padding: 12px 10px 10px !important;
            }

            .abutments-delivery-page .cases-filter-row {
                margin: 0 -4px !important;
                row-gap: 6px !important;
            }

            .abutments-delivery-page .cases-filter-row > [class*="col-"] {
                padding-left: 4px !important;
                padding-right: 4px !important;
                margin-bottom: 0 !important;
            }

            .abutments-delivery-page .cases-filter-row > div:nth-child(1),
            .abutments-delivery-page .cases-filter-row > div:nth-child(2),
            .abutments-delivery-page .cases-filter-row > div:nth-child(3),
            .abutments-delivery-page .cases-filter-row > div:nth-child(4) {
                flex: 0 0 50% !important;
                width: 50% !important;
                max-width: 50% !important;
            }

            .abutments-delivery-page .cases-filter-row .filter-label {
                font-size: 11px !important;
                margin-bottom: 4px !important;
            }

            .abutments-delivery-page .cases-filter-row .dtp-input,
            .abutments-delivery-page .cases-filter-row .ios-dtp-trigger,
            .abutments-delivery-page .cases-filter-row .form-control,
            .abutments-delivery-page .cases-filter-row .bootstrap-select > .dropdown-toggle {
                min-height: 34px !important;
                height: 34px !important;
                padding: 6px 10px !important;
                font-size: 12px !important;
            }

            .abutments-delivery-page .cases-filter-btn {
                width: 100% !important;
                min-height: 34px !important;
                height: 34px !important;
            }

            .abutments-delivery-page .sigma-table-free {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }

            .abutments-delivery-page #datatable {
                min-width: 720px !important;
                width: 720px !important;
                table-layout: auto !important;
            }

            .abutments-delivery-page #datatable thead th,
            .abutments-delivery-page #datatable tbody td {
                white-space: nowrap !important;
                overflow: visible !important;
                text-overflow: clip !important;
            }

            .abutments-delivery-page #datatable th:nth-child(2),
            .abutments-delivery-page #datatable td:nth-child(2) {
                min-width: 150px !important;
            }

            .abutments-delivery-page #datatable th:nth-child(4),
            .abutments-delivery-page #datatable td:nth-child(4) {
                min-width: 130px !important;
            }

            .abutments-delivery-page #datatable th:nth-child(5),
            .abutments-delivery-page #datatable td:nth-child(5) {
                min-width: 120px !important;
            }

            .abutments-delivery-page #datatable th:nth-child(6),
            .abutments-delivery-page #datatable td:nth-child(6) {
                min-width: 120px !important;
            }

            .abutments-delivery-page #datatable th:nth-child(7),
            .abutments-delivery-page #datatable td:nth-child(7) {
                min-width: 84px !important;
            }

            .abutments-delivery-page #datatable th:nth-child(8),
            .abutments-delivery-page #datatable td:nth-child(8) {
                min-width: 120px !important;
            }
        }
    </style>
    <div class="abutments-delivery-page sigma-list-page">
    <form class="kt-form delivery-filter-form" method="GET" action="{{route('abutments-delivery-index')}}">
        <div class="container full-width cases-filter-card delivery-filter-card sigma-list-filter-card">
            <div class="row cases-filter-row sigma-list-filter-row">
                <div class="col-6 col-md-3 mb-2">
                    <div class="kt-subheader__search">
                        <label class="filter-label" for="abutments_from">
                            <i class="fas fa-calendar-alt"></i>
                            <span>From Date</span>
                        </label>
                        <x-ios-dtp name="from" id="abutments_from" class="filter-input-global" :value=" \Carbon\Carbon::parse($from)->format('d M, YYYY') "  mode="date" :required="true" />
{{--                        <input class="form-control SDTP"--}}
{{--                               id="abutments_from"--}}
{{--                               name="from"--}}
{{--                               type="text"--}}
{{--                               value="{{ \Carbon\Carbon::parse($from)->format('d M, YYYY') }}"--}}
{{--                               required=""--}}
{{--                               readonly=""--}}
{{--                        >    --}}
  </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="kt-subheader__search">
                        <label class="filter-label" for="abutments_to">
                            <i class="fas fa-calendar-alt"></i>
                            <span>To Date</span>
                        </label>
                        <x-ios-dtp name="to" id="abutments_to" class="filter-input-global" :value="\Carbon\Carbon::parse($to)->format('d M, YYYY') "  mode="date" :required="true" />
{{--                        <input class="form-control SDTP"--}}
{{--                               id="abutments_to"--}}
{{--                               name="to"--}}
{{--                               type="text"--}}
{{--                               value="{{ \Carbon\Carbon::parse($to)->format('d M, YYYY') }}"--}}
{{--                               required=""--}}
{{--                               readonly=""--}}
{{--                        > --}}

                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2">
                    <div class="kt-subheader__search">
                        <label class="filter-label" for="abutments_table_search">
                            <i class="fas fa-search"></i>
                            <span>Search</span>
                        </label>
                        <input type="text"
                               id="abutments_table_search"
                               class="form-control filter-input-global"
                               placeholder="Search table..."
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-2 d-flex align-items-end sigma-filter-action-col">
                    <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                        <i class="fas fa-search"></i>
                        <span>Apply</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="container full-width sigma-table-free">
        <div class="row" style=" border-radius: 4px;">
            <div class="col-12">
                <br>
                <table id="datatable"
                       class="table table-bordered dataTable no-footer sunriseTable table-odd sigma-list-table"
                       role="grid"
                       style="width:100%">
                    <thead>
                    <tr role="row">
                        <th class="doctor sigma-col-shaded sigma-head-left">Doctor</th>
                        <th class="sigma-head-left">Patient</th>
                        <th class="caseDeliTime sigma-head-left">Case Delivery Time</th>
                        <th class="sigma-col-shaded sigma-head-left">Implant</th>
                        <th class="sigma-head-left">Abut.</th>
                        <th class="sigma-head-left">Code</th>

                        <th class="sigma-head-center">Qty</th>
                        <th class="sigma-col-shaded sigma-head-left">Status</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($deliveries  as $item)
                        @php
                            if(!$item->case) continue;
                        @endphp

                        <tr role="row" class="odd clickable" data-toggle="modal"
                            data-target="#deliveryActionsModal" data-delivery-id="{{ $item->id }}">

                            <td class="sorting_1 doctor sigma-col-shaded sigma-body-left">{{ $item->display_doctor_name }}</td>
                            <td class="sigma-body-left sigma-patient-name">{{ $item->display_patient_name }}</td>
                            <td class="caseDeliTime sigma-body-left">{{ $item->display_case_delivery_time }}</td>
                            <td class="sigma-col-shaded sigma-body-left">{{ $item->display_implant_name }}</td>
                            <td class="sigma-body-left">{{ $item->display_abutment_name }}</td>
                            <td class="sigma-body-left">{{$item->code }}</td>

                            <td class="sigma-body-center"><span>{{ $item->display_received_qty }}</span>/<span>{{$item->qty }}</span></td>
                            <td class="sigma-col-shaded sigma-body-left">
                                <span style="color:{{ $item->display_status_color }}">{{ $item->display_status_text }}</span>
                            </td>


                        </tr>
                    @endforeach
                    </tbody>

                </table>

                <div class="modal fade sigma-modal--abutments-delivery-actions" tabindex="-1" role="dialog" id="deliveryActionsModal">
                    <input type="hidden" name="case_id" id="delivery-actions-id" value="">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="delivery-actions-doctor">Doctor: </label>
                                        <h5 id="delivery-actions-doctor"><b>-</b></h5>
                                    </div>
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="delivery-actions-patient">Patient: </label>
                                        <h5 id="delivery-actions-patient"><b>-</b></h5>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-12" style="margin-bottom: 0px">
                                        <label for="delivery-actions-abutment">Abutment: </label>
                                        <h5 id="delivery-actions-abutment"><b>-</b></h5>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="delivery-actions-ordered-by">Ordered by: </label>
                                        <h5 id="delivery-actions-ordered-by"><b>-</b></h5>
                                    </div>
                                    <div class="form-group col-6" style="margin-bottom: 0px">
                                        <label for="delivery-actions-ordered-on">Ordered On: </label>
                                        <h5 id="delivery-actions-ordered-on"><b>-</b></h5>
                                    </div>
                                </div>
                                <div class="form-group row" style="margin-bottom: 0px; display:none;" id="delivery-actions-received-wrap">
                                    <div class="form-group col-12" style="margin-bottom: 0px">
                                        <label for="delivery-actions-received-list">Received by: </label>
                                        <h5 id="delivery-actions-received-list"></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer fullBtnsWidth">
                                <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%">
                                    <div class="row">
                                        <div class="col-6 padding5px">
                                            <a id="delivery-actions-view-case" href="#">
                                                <button type="button" class="btn btn-info"><i class="far fa-file-alt"></i> View Case</button>
                                            </a>
                                        </div>
                                        <div class="col-12 padding5px" id="delivery-actions-order-wrap" style="display:none;">
                                            <a id="delivery-actions-order-link" href="#">
                                                <button type="button" class="btn btn-primary"><i class="fa-solid fa-clipboard-check"></i> Mark as ordered</button>
                                            </a>
                                        </div>
                                        <div class="col-12 padding5px" id="delivery-actions-receive-wrap" style="display:none;">
                                            <button type="button"
                                                class="btn btn-primary"
                                                id="delivery-actions-receive-trigger"
                                                data-dismiss="modal"
                                                data-toggle="modal"
                                                data-target="#receiveAbutmentsModal">
                                                <i class="fa-solid fa-clipboard-check"></i>
                                                Receive
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade sigma-modal--abutments-delivery-receive" tabindex="-1" role="dialog" id="receiveAbutmentsModal">
                    <form action="{{ route('receive-abutments') }}" method="POST">
                        @csrf
                        <input type="hidden" name="abutment_id" id="receive-abutment-id" value="">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Receive Abutments</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row" style="margin-bottom: 0px">
                                        <div class="form-group col-6" style="margin-bottom: 0px">
                                            <label for="receive-abutment-doctor">Doctor: </label>
                                            <h5 id="receive-abutment-doctor"><b>-</b></h5>
                                        </div>
                                        <div class="form-group col-6" style="margin-bottom: 0px">
                                            <label for="receive-abutment-patient">Patient: </label>
                                            <h5 id="receive-abutment-patient"><b>-</b></h5>
                                        </div>
                                        <div class="form-group row" style="margin-bottom: 0px; background-color:transparent">
                                            <div class="form-group col-6" style="margin-bottom: 0px">
                                                <label for="receive-abutment-label">Abutment: </label>
                                                <h5 id="receive-abutment-label"><b>-</b></h5>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="margin-bottom: 0px;">
                                        <div class="form-group col-6" style="margin-bottom: 0px">
                                            <label for="receive-abutment-ordered-by">Ordered by: </label>
                                            <h5 id="receive-abutment-ordered-by"><b>-</b></h5>
                                        </div>
                                        <div class="form-group col-6" style="margin-bottom: 0px">
                                            <label for="receive-abutment-ordered-on">Ordered On: </label>
                                            <h5 id="receive-abutment-ordered-on"><b>-</b></h5>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row" style="margin-bottom: 0px; background-color:transparent; display:none;" id="receive-abutment-history-wrap">
                                        <div class="form-group col-12" style="margin-bottom: 0px">
                                            <label for="receive-abutment-history">Received by: </label>
                                            <h5 id="receive-abutment-history"></h5>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="margin-bottom: 0px">
                                        <div class="form-group col-2" style="margin-bottom: 0px">
                                            <label for="receive-abutment-qty" style="color:red">Quantity</label>
                                        </div>
                                        <div class="form-group col-3" style="margin-bottom: 0px">
                                            <input type="number" name="qty" id="receive-abutment-qty" class="form-control" style="width:100%;" min="1" required>
                                        </div>
                                        <div class="form-group col-4" style="margin-left: 5px">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa-solid fa-clipboard-check"></i> Receive
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer fullBtnsWidth">
                                    <div class="row" style="margin-right: 0px; margin-left: 0px; width:100%">
                                        <div class="row">
                                            <div class="col-12 padding5px">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div style="text-align:right">
                {{--{{$deliveries->onEachSide(3)->links()}}--}}
            </div>
        </div>
    </div>

    </div>
    @push('js')
    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- Responsive and datable js -->
    <script type="text/javascript">
        const abutmentDeliveryItems = @json($deliveryActionData ?? []);

        function escapeAbutmentDeliveryHtml(value) {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function buildAbutmentReceiveHistory(logs) {
            if (!Array.isArray(logs) || logs.length === 0) {
                return '';
            }

            return logs.map(function(log) {
                const qty = escapeAbutmentDeliveryHtml(log.qty);
                const by = escapeAbutmentDeliveryHtml(log.by);
                const createdAt = escapeAbutmentDeliveryHtml(log.created_at);
                return `<b>${qty}</b> Units by <b>${by}</b> on ${createdAt}<br>`;
            }).join('');
        }

        $(document).ready(function () {


                                var abutmentsTable = $('#datatable').DataTable({
                                    "pageLength": 25,
                                    "searching": true,
                                    "lengthChange": false,
                                    "dom": "rtip",
                                    "order":  []
                                    // "scrollX":       true,
                                    //stateSave: true,
                                });

                                $('#abutments_table_search').on('input', function () {
                                    abutmentsTable.search(this.value).draw();
                                });

                                $('#deliveryActionsModal').appendTo('body');
                                $('#receiveAbutmentsModal').appendTo('body');

        });

        $(document).on('show.bs.modal', '#deliveryActionsModal', function(event) {
            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const deliveryId = String(trigger.dataset.deliveryId || '');
            const item = abutmentDeliveryItems[deliveryId];
            if (!item) {
                return;
            }

            const receivedMarkup = buildAbutmentReceiveHistory(item.received_logs);

            document.getElementById('delivery-actions-id').value = item.id || '';
            document.getElementById('delivery-actions-doctor').textContent = item.doctor_name || '-';
            document.getElementById('delivery-actions-patient').textContent = item.patient_name || '-';
            document.getElementById('delivery-actions-abutment').textContent = item.abutment_label || '-';
            document.getElementById('delivery-actions-ordered-by').textContent = item.ordered_by || 'None';
            document.getElementById('delivery-actions-ordered-on').textContent = item.ordered_on || 'Not yet';
            document.getElementById('delivery-actions-view-case').href = item.view_case_url || '#';

            const receivedWrap = document.getElementById('delivery-actions-received-wrap');
            const receivedList = document.getElementById('delivery-actions-received-list');
            receivedList.innerHTML = receivedMarkup;
            receivedWrap.style.display = receivedMarkup ? '' : 'none';

            const orderWrap = document.getElementById('delivery-actions-order-wrap');
            const orderLink = document.getElementById('delivery-actions-order-link');
            const receiveWrap = document.getElementById('delivery-actions-receive-wrap');
            const receiveTrigger = document.getElementById('delivery-actions-receive-trigger');

            if (item.status === 0) {
                orderLink.href = item.order_url || '#';
                orderWrap.style.display = '';
                receiveWrap.style.display = 'none';
            } else if (item.status === 1 || item.status === 2) {
                orderWrap.style.display = 'none';
                receiveTrigger.dataset.deliveryId = item.id || '';
                receiveWrap.style.display = '';
            } else {
                orderWrap.style.display = 'none';
                delete receiveTrigger.dataset.deliveryId;
                receiveWrap.style.display = 'none';
            }
        });

        $(document).on('show.bs.modal', '#receiveAbutmentsModal', function(event) {
            const trigger = event.relatedTarget;
            if (!trigger || !trigger.dataset) {
                return;
            }

            const deliveryId = String(trigger.dataset.deliveryId || '');
            const item = abutmentDeliveryItems[deliveryId];
            if (!item) {
                return;
            }

            const historyMarkup = buildAbutmentReceiveHistory(item.received_logs);
            const historyWrap = document.getElementById('receive-abutment-history-wrap');
            const history = document.getElementById('receive-abutment-history');
            const qtyInput = document.getElementById('receive-abutment-qty');

            document.getElementById('receive-abutment-id').value = item.id || '';
            document.getElementById('receive-abutment-doctor').textContent = item.doctor_name || '-';
            document.getElementById('receive-abutment-patient').textContent = item.patient_name || '-';
            document.getElementById('receive-abutment-label').textContent = item.abutment_label || '-';
            document.getElementById('receive-abutment-ordered-by').textContent = item.ordered_by || 'None';
            document.getElementById('receive-abutment-ordered-on').textContent = item.ordered_on || 'Not yet';

            history.innerHTML = historyMarkup;
            historyWrap.style.display = historyMarkup ? '' : 'none';

            qtyInput.max = item.remaining_qty || 1;
            qtyInput.value = '';
        });

        function toggleColumnVisibilty(colNumber) {

            var selector = 'td:nth-child(' + colNumber + '),th:nth-child(' + colNumber + ')';
            console.log(selector);
            $(selector).toggle();
            // Get the column API object
//                                var column = table.column($(this).attr('data-column'));
//
//                                // Toggle the visibility
//                                column.visible(!column.visible());
        }
        function toggleCheckBox(ele) {
            var $tc = $(ele).find('input:checkbox:first'),
                tv = $tc.attr('checked');

            $tc.attr('checked', !tv);
        }

        function caseDelConfirmation(ev) {
            ev.preventDefault();
            var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
            var clientName = ev.currentTarget.getAttribute('data-clientName');
            var patientName = ev.currentTarget.getAttribute('data-patientName');

            //console.log(urlToRedirect); // verify if this is the right URL
            swal.fire({
                title: "You sure You want to delete.. </br>" + clientName + " - " + patientName,
                text: "This will also delete related info. (invoice, photos .. etc)?",
                icon: "warning",
                showDenyButton: true,
                confirmButtonText: 'Delete Case',
                denyButtonText: 'Cancel'
            })
                .then((willDelete) => {
                // redirect with javascript here as per your logic after showing the alert using the urlToRedirect value
                if (willDelete.isConfirmed
        )
            {
                window.location = urlToRedirect;
                //swal.fire("Poof! Your imaginary file has been deleted!");
            }
        else
            {
                swal.fire("Case NOT deleted.");
            }
        })
            ;
        }
    </script>
    @endpush

    </div>

@endsection

