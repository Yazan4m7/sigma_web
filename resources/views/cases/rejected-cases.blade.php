@extends('layouts.app' ,[ 'pageSlug' => "Cases List"])
@section('content')

    <style>
        .rejected-cases-page .sigma-list-filter-card,
        .rejected-cases-page .container.full-width.sigma-list-filter-card.cases-filter-card.delivery-filter-card {
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            margin-top: 16px !important;
            margin-bottom: 24px !important;
            padding: 0 !important;
            position: relative;
            overflow: visible !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            isolation: auto;
        }

        .rejected-cases-page .row.sigma-list-filter-row.cases-filter-row {
            background: #ffffffa8 !important;
            border: 1px solid rgba(188, 206, 216, 0.3) !important;
            border-radius: 16px !important;
            box-shadow: 0px 2px 20px 0px rgb(0 0 0 / 6%) !important;
            padding: 20px 12px 16px !important;
            position: relative;
            overflow: hidden !important;
            backdrop-filter: blur(10px);
            isolation: isolate;
            margin: 0 !important;
        }

        .rejected-cases-page .row.sigma-list-filter-row.cases-filter-row::after {
            content: "" !important;
            display: block !important;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px !important;
            background: linear-gradient(90deg, #d6ecee 0%, #e7f4f5 100%) !important;
            border-radius: 16px 16px 0 0 !important;
            pointer-events: none;
            z-index: 2;
        }

        .rejected-cases-page .sigma-list-filter-row {
            margin: 0 !important;
        }

        .rejected-cases-page .sigma-list-filter-row > [class*="col-"] {
            min-width: 0;
        }

        @media screen and (min-width: 768px) {
            .rejected-cases-page .sigma-list-filter-row > [class*="col-"].sigma-filter-action-col {
                flex: 0 0 auto !important;
                width: auto !important;
                max-width: none !important;
                padding-left: 8px !important;
                margin-left: 0 !important;
            }
        }

        .rejected-cases-page .sigma-list-filter-row .bootstrap-select,
        .rejected-cases-page .sigma-list-filter-row .bootstrap-select > .dropdown-toggle {
            width: 100% !important;
            max-width: 100% !important;
        }

        .rejected-cases-page .sigma-list-filter-row .bootstrap-select > .dropdown-toggle {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .rejected-cases-page .sigma-columns-toolbar {
            width: 100%;
            min-height: 38px;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
        }

        .rejected-cases-page .rejected-columns-btn::after {
            display: none !important;
        }

        .rejected-cases-page .sigma-columns-menu {
            min-width: 220px;
            padding: 8px 0 !important;
            border-radius: 12px !important;
        }

        .rejected-cases-page .sigma-columns-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            color: #243746;
        }

        .rejected-cases-page .sigma-columns-menu .dropdown-item .form-check-input {
            position: static;
            margin: 0;
            flex: 0 0 auto;
        }

        .rejected-cases-page .rejected-table-shell,
        .rejected-cases-page .rejected-table-shell > .row,
        .rejected-cases-page .rejected-table-shell > .row > .col-12,
        .rejected-cases-page #datatable_wrapper,
        .rejected-cases-page #datatable_wrapper > .row {
            background: transparent !important;
            border: 0 !important;
            box-shadow: none !important;
            padding: 0 !important;
        }

        .rejected-cases-page #datatable {
            width: 100% !important;
            margin: 0 !important;
            background: transparent !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        .rejected-cases-page #datatable.table-bordered,
        .rejected-cases-page #datatable.table-bordered th,
        .rejected-cases-page #datatable.table-bordered td,
        .rejected-cases-page #datatable.dataTable.no-footer {
            border: 0 !important;
        }

        .rejected-cases-page #datatable thead th {
            background: #d6ecee !important;
            color: #337374 !important;
            padding: 12px 10px !important;
            border-bottom: 0 !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        .rejected-cases-page #datatable thead th.sigma-visible-start {
            background: #408385 !important;
            color: #ffffff !important;
            border-top-left-radius: 12px !important;
        }

        .rejected-cases-page #datatable thead th.sigma-visible-end {
            background: #408385 !important;
            color: #ffffff !important;
            border-top-right-radius: 12px !important;
        }

        .rejected-cases-page #datatable tbody td {
            padding: 14px 10px !important;
            border-top: 0 !important;
            border-bottom: 0 !important;
            background: transparent !important;
            vertical-align: middle !important;
        }

        .rejected-cases-page #datatable.table-striped tbody tr:nth-of-type(odd) {
            background-color: transparent !important;
        }

        .rejected-cases-page #datatable tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .rejected-cases-page .sigma-status-width {
            min-width: 120px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

.sigma-modal--cases-rejected-actions .modal-footer {
            flex-wrap: wrap;
            justify-content: flex-start;
        }
        
.sigma-modal--cases-rejected-actions .modal-footer .btn {
            margin: 5px;
        }
        .tooltiptext {
            display: none;
        }
    </style>
    <div class="rejected-cases-page sigma-list-page">
    @if(!isset($isSearchResults))
    @if(!isset($trashedCases))
        @if(isset($clients))
            <form class="kt-form sigma-list-page" method="GET" action="{{route('rejected-cases')}}">
                @else
                    <form class="kt-form sigma-list-page" method="GET" action="{{route('dentist-cases',['id' =>$id])}}">
                        <input type="hidden" class="form-control" name="id" value="{{$id}}">
                        @endif
                        <div class="container full-width sigma-list-filter-card cases-filter-card delivery-filter-card">
                            <div class="row sigma-list-filter-row cases-filter-row">
                                <div class="col-12 col-sm-6 col-lg-2 mb-2">
                                    <div class="kt-subheader__search">
                                        <label class="filter-label" for="rejected_from">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>From Date</span>
                                        </label>
                                        <x-ios-dtp name="from" id="rejected_from" class="filter-input-global" :value="$from" mode="date" :required="true" />
{{--                                        <input class="form-control SDTP"--}}
{{--                                               id="rejected_from"--}}
{{--                                               name="from"--}}
{{--                                               type="text"--}}
{{--                                               value="{{ \Carbon\Carbon::parse($from)->format('d M, YYYY') }}"--}}
{{--                                               required=""--}}
{{--                                               readonly=""--}}
{{--                                        >   --}}
{{--                                    --}}
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2 mb-2">
                                    <div class="kt-subheader__search">
                                        <label class="filter-label" for="rejected_to">
                                            <i class="fas fa-calendar-alt"></i>
                                            <span>To Date</span>
                                        </label>
                                        <x-ios-dtp name="to" id="rejected_to" class="filter-input-global" :value="$to" mode="date" :required="true" />
{{--                                        <input class="form-control SDTP"--}}
{{--                                               id="rejected_to"--}}
{{--                                               name="to"--}}
{{--                                               type="text"--}}
{{--                                               value="{{ \Carbon\Carbon::parse($to)->format('d M, YYYY') }}"--}}
{{--                                               required=""--}}
{{--                                               readonly=""--}}
{{--                                        >               --}}

                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-2 mb-2">

                                    @if(isset($clients))
                                        <div class="dropdown" style="text-align: left;">
                                            <label class="filter-label" for="doctor">
                                                <i class="fas fa-user-md"></i>
                                                <span>Doctor</span>
                                            </label>

                                            <select style="width:100%" class="selectpicker clearOnAll filter-input-global" multiple
                                                    name="doctor[]" id="doctor"
                                                    data-live-search="true"
                                                    data-container="body"
                                                    title="All Doctors">

                                                    <option value="all" {{(isset($selectedClients) && in_array('all', $selectedClients ?? [], true)) ? 'selected' : ''}}>
                                                        All
                                                    </option>
                                                    @foreach($clients as $d)
                                                        <option value="{{$d->id}}" {{(isset($selectedClients) && in_array((string) $d->id, array_map('strval', $selectedClients ?? []), true)) ? 'selected' : ''}}>{{$d->name}}</option>
                                                    @endforeach

                                            </select>

                                        </div>
                                    @endif

                                </div>
                                <div class="col-12 col-sm-6 col-lg-2 mb-2">

                                    @if(isset($clients))
                                        <div class="kt-subheader__search">
                                            <label class="filter-label" for="patient_name">
                                                <i class="fas fa-search"></i>
                                                <span>Patient</span>
                                            </label>
                                            <input type="text" name="patient_name" value="{{$patientName ?? ''}}"
                                                   class="form-control filter-input-global" id="patient_name" placeholder="Search patient">
                                        </div>
                                    @endif

                                </div>
                                <div class="col-12 col-sm-6 col-lg-2 mb-2 d-flex align-items-end sigma-filter-action-col">
                                    <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                                        <i class="fas fa-search"></i>
                                        <span>Apply</span>
                                    </button>
                                </div>

                                    <div class="col-12 col-sm-6 col-lg-2 mb-2 d-flex align-items-end justify-content-lg-end sigma-filter-secondary-col">
                                        <div class="dropdown sigma-columns-toolbar">
                                            <button class="btn sigma-toolbar-icon-btn rejected-columns-btn" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" title="Show or hide columns" aria-label="Show or hide columns">
                                                <i class="fas fa-table-columns"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right sigma-columns-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="toggle-vis dropdown-item" data-column="0" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"/>
                                                    ID</a>
                                                <a class="toggle-vis dropdown-item" data-column="1" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"/> Case
                                                    ID</a>
                                                <a class="toggle-vis dropdown-item" data-column="2" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Doctor</a>
                                                <a class="toggle-vis dropdown-item" data-column="3" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Patient name</a>
                                                <a class="toggle-vis dropdown-item" data-column="4" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Initial Deli.
                                                    Date</a>
                                                <a class="toggle-vis dropdown-item" data-column="5" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Date Delivered</a>
                                                <a class="toggle-vis dropdown-item" data-column="6" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Status</a>
                                                <a class="toggle-vis dropdown-item" data-column="7" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"
                                                                                         checked/> Tags</a>
                                                <a class="toggle-vis dropdown-item" data-column="8" href="#"
                                                   onclick="toggleCheckBox(this)"><input type="checkbox"
                                                                                         class="form-check-input"/> Date
                                                    Created </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    </form>
                @endif
                    @endif
                            <div class="container full-width sigma-table-free rejected-table-shell">
                                <div class="row">
                                    <div class="col-12">
                                        <br>
                                        <table id="datatable"
                                               class="table-striped compact sunriseTable sigma-list-table"
                                               role="grid" aria-describedby="datatable_info"
                                               style="width:100%">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc hiddenByDefault" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-sort="ascending"
                                                    aria-label="Name: activate to sort column descending"
                                                    style="">ID
                                                </th>
                                                <th class="sorting hiddenByDefault" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Position: activate to sort column ascending"
                                                    style="">Case ID
                                                </th>
                                                <th class="sorting sigma-col-shaded" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Office: activate to sort column ascending"
                                                    style="">Doctor
                                                </th>
                                                <th class="sorting sigma-head-right" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Age: activate to sort column ascending"
                                                    style="">Patient Name
                                                </th>
                                                <th class="sorting" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Start date: activate to sort column ascending"
                                                    style="">Initial Delivery <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </th>
                                                <th class="sorting sigma-col-shaded" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Salary: activate to sort column ascending"
                                                    style="">Actual Delivery <i class="fa fa-calendar" aria-hidden="true"></i>
                                                </th>
                                                <th class="sorting statusCol" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Start date: activate to sort column ascending"
                                                    style="">Status
                                                </th>
                                                <th class="sorting" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Start date: activate to sort column ascending"
                                                    style="">Tags
                                                </th>
                                                <th class="sorting" tabindex="0"
                                                    aria-controls="datatable" rowspan="1" colspan="1"
                                                    aria-label="Salary: activate to sort column ascending"
                                                    style="">Date Created
                                                </th>

                                            </tr>
                                            </thead>

                                            <tbody>

                                            @foreach($cases  as $case)
                                                <tr role="row" class="odd clickable"  data-toggle="modal" data-target="#actionsDialog{{$case->id}}">
                                                    <td class="sorting_1 ">{{$case->id}}</td>
                                                    <td>{{$case->case_id}}</td>
                                                    <td class="sigma-col-shaded sigma-body-right">{{$case->client->name}}</td>
                                                    <td class="sigma-body-right">{{$case->patient_name}}</td>
                                                    <td class="sigma-body-center">{{$case->initDeliveryDate() }}
                                                        &nbsp;&nbsp; {{$case->initDeliveryTime()}}</td>
                                                    <td class="sigma-col-shaded sigma-body-center">{{$case->actualDeliveryDate()=="" ? "Not yet" : $case->actualDeliveryDate()}}
                                                        &nbsp;&nbsp; {{$case->actualDeliveryTime() ?? ""}}</td>
                                                    <td class="sigma-body-center">
                                                        @if(str_contains($case->status(), "Completed") )
                                                            <span class="badge badge-success sigma-status-width">
                                                                           {{$case->status()}} </span>
                                                        @elseif(str_contains($case->status(), "In-Progress") || str_contains($case->status(), "Active"))
                                                            <span style="width:auto; margin: auto; text-align: center"
                                                                  class="badge badge-primary sigma-status-width">
                                                                           <span class="tooltipX"> {{$case->status()}}
                                                                               <span class="tooltiptext">{!!  $case->getStatusToolTipHTML() !!}</span>
                                                                </span></span>
                                                        @elseif(str_contains($case->status(), "Waiting"))
                                                            <span style="width:auto; margin: auto; text-align: center"
                                                                  class="badge badge-danger sigma-status-width">
                                                                     {{$case->status()}} </span>
                                                        @else
                                                            <span style="width:auto; margin: auto; text-align: center"
                                                                  class="badge badge-warning sigma-status-width">
                                                                           <span class="tooltipX"> {{$case->status()}}
                                                                               <span class="tooltiptext">{!!  $case->getStatusToolTipHTML() !!}</span>
                                                                </span></span>

                                                        @endif

                                                    </td>
                                                    <td class="sigma-body-left">

                                                        @foreach($case->tags as $tag)
                                                            @if(isset($tag->originalTagRecord))
                                                                <i title="{{$tag->originalTagRecord->text}}"
                                                                   style="color:{{$tag->originalTagRecord->color}}"
                                                                   class="{{$tag->originalTagRecord->icon}}  fa-lg"></i>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td class="sigma-body-center">{{$case->createdAtDate()}}
                                                        &nbsp;&nbsp; {{$case->createdAtTime() }}</td>


                                                </tr>
                                                <div class="modal sigma-modal--cases-rejected-actions" tabindex="-1" role="dialog" id="actionsDialog{{$case->id}}">

                                                    <input type="hidden" name="case_id" value="{{$case->id}}">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">.</h5>

                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">

                                                                <div class="form-group row" style="margin-bottom: 0px">
                                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                        <label for="doctor">Doctor: </label>
                                                                        <h5 id="doctor"><b>{{$case->client->name}}</b></h5>
                                                                    </div>
                                                                    <div class="form-group col-6 " style="margin-bottom: 0px">
                                                                        <label for="pat">Patient: </label>
                                                                        <h5 id="pat"><b>{{$case->patient_name}}</b></h5>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="form-group row">
                                                                    <div class=" col-12 ">
                                                                        <label class="case-completion-dialog-label"><b>Jobs:</b></label><br>
                                                                        <div class="sigma-case-jobs-list">



                                                                        @foreach( $case->jobs as $job)

                                                                            @php
                                                                                $unit = explode(', ',$job->unit_num);
                                                                                $jobTypeName = $job->jobType->name ?? "No Job Type";
                                                                                $materialName = $job->material->name ?? "no material";
                                                                                $colorLabel = $job->color =='0' ? "" : $job->color;
                                                                                $styleLabel = $job->style == 'None' ? "" : $job->style;
                                                                                $implantLabel = isset($job->implantR) && optional($job->jobType)->id == 6 ? "Implant Type: " . $job->implantR->name : "";
                                                                                $abutmentLabel = isset($job->abutmentR) && optional($job->jobType)->id == 6 ? "Abutment Type: " . $job->abutmentR->name : "";
                                                                            @endphp

                                                                            <div class="sigma-case-job-row">
                                                                                <span class="sigma-case-job-cell sigma-case-job-cell--teeth">{{$job->unit_num}}</span>
                                                                                <span class="sigma-case-job-cell sigma-case-job-cell--type">{{$jobTypeName}}</span>
                                                                                <span class="sigma-case-job-cell sigma-case-job-cell--mat">{{$materialName}}</span>
                                                                                <span class="sigma-case-job-cell sigma-case-job-cell--shade">{{$colorLabel}}</span>
                                                                                <span class="sigma-case-job-cell sigma-case-job-cell--unit">
                                                                                    {{$styleLabel}}
                                                                                    @if($implantLabel || $abutmentLabel)
                                                                                        <span class="sigma-case-job-extra">
                                                                                            @if($implantLabel)
                                                                                                <span>{{$implantLabel}}</span>
                                                                                            @endif
                                                                                            @if($abutmentLabel)
                                                                                                <span>{{$abutmentLabel}}</span>
                                                                                            @endif
                                                                                        </span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        @endforeach
                                                                        </div>
                                                                    </div></div>
                                                                @if(count($case->notes)>0)
                                                                    <hr>
                                                                    <label class="case-completion-dialog-label"><b>Notes:</b></label><br>
                                                                    @foreach($case->notes as $note)
                                                                        <div class="form-control" style="height:fit-content;width:80%;background-color: #dcecfd59;margin-bottom: 5px; color:black;font-size:12px" disabled>

                                                                            <span class="noteHeader">{{ '[' . \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.date_only')) . ' ' }}<b>{{ \Carbon\Carbon::parse($note->created_at)->format(config('app_config.timestamp_format.time_only')) }}</b>{{ '] [' . $note->writtenBy->name_initials . '] : ' }}</span><br> <span class="noteText">{{$note->note}}</span>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                @if(!isset($trashedCases))
                                                                    <a href="{{route('view-voucher',$case->id)}}" class="btn btn-info"><i class="fas fa-print"></i> View Voucher</a>
                                                                    <a href="{{route('view-case',['id' =>$case->id ,'stage' =>-2 ])}}" class="btn btn-info"><i class="far fa-file-alt"></i> View Case</a>
                                                                    @if(Auth()->user()->is_admin)
                                                                        @if(!$case->locked)
                                                                            <a href="{{route('lock-case',$case->id)}}" class="btn btn-dark"><i class="fas fa-lock"></i> Lock Case</a>
                                                                        @else
                                                                            <a href="{{route('unlock-case',$case->id)}}" class="btn btn-dark"><i class="fas fa-lock-open"></i> Unlock Case</a>
                                                                        @endif
                                                                        @if(!$case->locked)
                                                                            <a data-clientName="{{ $case->client->name }}" data-patientName="{{ $case->patient_name }}" style="color:white;" onclick="caseDelConfirmation(event)" href="{{route('delete-case',$case->id)}}" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Case</a>
                                                                        @endif
                                                                    @endif
                                                                    @if((Auth()->user()->is_admin || ($permissions && ($permissions->contains('permission_id', 102))) || ($permissions && ((!isset($case->actual_delivery_date)&& $permissions->contains('permission_id', 115))) || (optional($case->jobs->first())->stage == 1 && $permissions->contains('permission_id', 1)))) && !$case->locked)
                                                                        <a href="{{route('edit-case-view',$case->id)}}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i> Edit </a>
                                                                    @endif
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 116)) && !$case->locked)
                                                                        <a href="{{route('reject-case-view',$case->id )}}" class="btn btn-outline-danger"><i class="fas fa-times x2"></i> Reject case</a>
                                                                    @endif
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 117))&&!$case->locked)
                                                                        <a href="{{route('repeat-case-view',$case->id)}}" class="btn btn-outline-warning"><i class="fas fa-undo"></i> Repeat case</a>
                                                                    @endif
                                                                    @if ((Auth()->user()->is_admin  || $permissions->contains('permission_id', 118)) && !$case->locked)
                                                                        <a href="{{route('modify-case-view',$case->id)}}" class="btn btn-outline-warning"><i class="fa fa-broom"></i> Modify case</a>
                                                                    @endif
                                                                    @if(!$case->delivered_to_client && !$case->locked)
                                                                        @if (Auth()->user()->is_admin  || $permissions->contains('permission_id', 119))
                                                                            <a href="{{route('redo-case-view',$case->id)}}" class="btn btn-outline-warning"><i class="fa fa-broom"></i> Redo case</a>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <a href="{{route('restore-case',$case->id)}}" class="btn btn-danger">Restore case</a>
                                                                @endif
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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


                    @push('js')
                    <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                    <script src="//cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
                    <!-- Responsive and datable js -->
                    <script type="text/javascript">
                        $(document).ready(function () {
                            function applyRejectedHeaderCaps() {
                                var $headers = $('#datatable thead th:visible');
                                $('#datatable thead th').removeClass('sigma-visible-start sigma-visible-end');

                                if ($headers.length) {
                                    $headers.first().addClass('sigma-visible-start');
                                    $headers.last().addClass('sigma-visible-end');
                                }
                            }

                            function syncRejectedColumnMenu() {
                                $('a.toggle-vis').each(function () {
                                    var columnIndex = $(this).attr('data-column');
                                    var isVisible = table.column(columnIndex).visible();
                                    $(this).find('input:checkbox:first').prop('checked', isVisible);
                                });
                            }

                            var table =
                                $('#datatable').DataTable({
                                    "pageLength": 25,
                                    "searching": false,
                                    "lengthChange": false,
                                    "columnDefs": [
                                        {targets: [0, 1, 8], visible: false},
                                    ],
                                    "order": [[5, "desc"], [4, "asc"]],
                                    // "scrollX":       true,
                                    //stateSave: true,
                                });

                            applyRejectedHeaderCaps();
                            syncRejectedColumnMenu();

                            table.on('column-visibility.dt draw.dt responsive-resize.dt', function () {
                                applyRejectedHeaderCaps();
                                syncRejectedColumnMenu();
                            });

                            $('a.toggle-vis').on('click', function (e) {
                                e.preventDefault();

                                // Get the column API object
                                var column = table.column($(this).attr('data-column'));

                                // Toggle the visibility
                                column.visible(!column.visible());
                            });

                            $(document).on('shown.bs.select', '#doctor', function () {
                                if (!window.matchMedia('(max-width: 991px)').matches) {
                                    return;
                                }

                                var $select = $(this);
                                var picker = $select.data('selectpicker');
                                if (!picker || !picker.$bsContainer || !picker.$bsContainer.length) {
                                    return;
                                }

                                var $container = picker.$bsContainer;
                                var $menu = picker.$menu && picker.$menu.length
                                    ? picker.$menu
                                    : $container.find('> .dropdown-menu');

                                if (!$menu.length) {
                                    return;
                                }

                                var viewportPadding = 12;
                                var viewportWidth = window.innerWidth || $(window).width();
                                var buttonWidth = picker.$button && picker.$button.length
                                    ? picker.$button.outerWidth()
                                    : $select.closest('.bootstrap-select').outerWidth();
                                var menuWidth = Math.min(
                                    Math.max(buttonWidth || 0, $menu.outerWidth() || 0),
                                    Math.max(viewportWidth - (viewportPadding * 2), 0)
                                );
                                var containerLeft = parseFloat($container.css('left')) || 0;
                                var maxLeft = viewportWidth - viewportPadding - menuWidth;
                                containerLeft = Math.max(viewportPadding, Math.min(containerLeft, maxLeft));

                                $container.css({
                                    left: containerLeft,
                                    width: menuWidth
                                });

                                $menu.css({
                                    minWidth: menuWidth,
                                    maxWidth: menuWidth,
                                    right: 'auto',
                                    left: 0,
                                    transform: 'none'
                                });
                            });
                        });

                        function toggleCheckBox(ele) {
                            var $tc = $(ele).find('input:checkbox:first');
                            $tc.prop('checked', !$tc.prop('checked'));
                        }
                        function caseDelConfirmation(ev) {
                            ev.preventDefault();
                            var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
                            console.log(urlToRedirect); // verify if this is the right URL
                            swal.fire({
                                title: "Are you sure?",
                                text: "This will also delete related info. (invoice, photos .. etc)",
                                icon: "warning",
                                showDenyButton: true,
                                confirmButtonText: 'Delete Case',
                                denyButtonText: 'Cancel',
                            })
                                .then((willDelete) => {
                                // redirect with javascript here as per your logic after showing the alert using the urlToRedirect value
                                if (willDelete.isConfirmed)
                            {
                                window.location = urlToRedirect;
                                //swal.fire("Poof! Your imaginary file has been deleted!");
                            }
                        else
                            {
                                swal.fire("Deletion Canceled.");
                            }
                        })
                            ;
                        }
                    </script>
            @endpush


@endsection
