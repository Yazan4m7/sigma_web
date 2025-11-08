
<div class="sidebar">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,200,1,0" />

    @php
        $permissions = Cache::get('user'.Auth()->user()->id);
    @endphp
    <div class="sidebar-wrapper">
        <!-- Logo Section -->
        <div class="logo" style="padding: 20px 15px; text-align: center;">
            <a href="{{ route('home') }}" class="simple-text logo-normal">
                <img src="{{ asset('assets') }}/images/logo_horiz.svg" alt="SIGMA Logo" style="width: 88%; max-width: 180px; height: auto;" />
            </a>
        </div>

        <ul class="nav">

                @if(($permissions && $permissions->contains('permission_id', 123)) || Auth()->user()->is_admin)
                    <div class="homePageOptionInSideBar" style="padding:0;">

                        <li class="{{Route::currentRouteName() == 'home' ? 'active' : ''}}" >
                                                            <a style="
                                    margin-right: 0px;
                                    padding-right: 0px !important;
                                     padding-left: 0px !important;
                                " href="{{route('home')}}">
                                <i class="fa-solid fa-house"></i>
                                <span>Home Screen</span>
                            </a>
                            <hr style="border-color:#b4b4b4;margin-top: 0.5rem;margin-bottom: 0.5rem;">
                    </div>

                @endif
                    @if(($permissions && $permissions->contains('permission_id', 106)) || Auth()->user()->is_admin)
                        <div class="" style="padding:0" >

                             <li class="{{Route::currentRouteName() == 'admin-dashboard-v2' ? 'active' : ''}}" >
                                <a href="{{route('admin-dashboard-v2')}}" style=" margin-right: 0px;">
                                    <span class="material-symbols-outlined googleIconInSideBar">
                                    dashboard
                                    </span>
                                    <span>OPERATIONS DASHBOARD</span>
                                </a>


                            {{--<li  class="{{Route::currentRouteName() == 'admin-dashboard' ? 'active' : ''}}">--}}
                            {{--<a href="{{route('admin-dashboard')}}" > <i class="tim-icons icon-chart-pie-36"></i><span>{{ _('Dashboard') }}</span></a>--}}
                            {{--</li>--}}
                        </div>

                        <hr style="border-color:#b4b4b4;margin-top: 0.5rem;margin-bottom: 0.5rem;">
                    @endif
            @if(($permissions && $permissions->contains('permission_id', 100)) || Auth()->user()->is_admin)
                <li class="{{Route::currentRouteName() == 'new-case-view' ? 'active' : ''}}">
                    <a href="{{route('new-case-view')}}"><i class="fa fa-plus-square"></i> <span>Create Case</span></a>
                </li>
            @endif



                @if(($permissions && $permissions->contains('permission_id', 103)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'cases-index' ? 'active' : ''}}">
                        <a href="{{route('cases-index')}}"><i class="fa fa-suitcase"></i> <span>Cases</span></a>
                    </li>
                @endif

                @if(($permissions && $permissions->contains('permission_id', 105)) )
                    <li class="{{Route::currentRouteName() == 'receivable-payments-index' ? 'active' : ''}}" ><a href="{{route('receivable-payments-index')}}">
                            <i class="fa fa-money" aria-hidden="true"></i> <span>Collect Payments</span></a>
                @endif

                @if(($permissions && $permissions->contains('permission_id', 109)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'delivery-schedule' ? 'active' : ''}}"><a href="{{route('delivery-schedule')}}"> <i class="fa-regular fa-clock"></i> <span>Delivery Schedule</span></a>
                @endif
                @if(($permissions && $permissions->contains('permission_id', 9)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'deli-cases-accountant-index' ? 'active' : ''}}"><a href="{{route('deli-cases-accountant-index')}}"><i class="fa fa-car" aria-hidden="true"></i><span>Delivery Monitor</span></a></li>
                @endif
                @if(($permissions && $permissions->contains('permission_id', 125)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'abutments-delivery-index' ? 'active' : ''}}"><a href="{{route('abutments-delivery-index')}}"><i class="fa-solid fa-bullseye"></i><span>Abutments Delivery</span></a></li>
                @endif
                @if(($permissions && $permissions->contains('permission_id', 113)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'view-cases-monitor' ? 'active' : ''}}"><a href="{{route('view-cases-monitor')}}"><i class="fa-solid fa-table-cells-large"></i><span>Cases Monitor</span></a></li>
                @endif
                @if(($permissions && $permissions->contains('permission_id', 133)) || Auth()->user()->is_admin)
                    <li class="{{Route::currentRouteName() == 'devices-page' ? 'active' : ''}}"><a href="{{route('devices-page')}}"><i class="fa-solid fa-desktop"></i><span>Devices Monitor</span></a></li>
                @endif
            @if(($permissions && ($permissions->contains('permission_id', 107))) || Auth()->user()->is_admin)
                <li class="{{Route::currentRouteName() == 'clients-index' ? 'active' : ''}}" ><a href="{{route('clients-index')}}"><i class="fa fa-user-md" style="color: white !important;"></i> <span>Doctors</span></a>
            @endif
                    @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                        <li class="{{Route::currentRouteName() == 'my-collections' ? 'active' : ''}}"><a href="{{route('my-collections')}}"> <i class="fa-solid fa-circle-dollar-to-slot"></i> <span>My Collections</span></a>
                    @endif

                @if(($permissions && $permissions->contains('permission_id', 124)) || Auth()->user()->is_admin)
                        <li class="{{Route::currentRouteName() == 'rejected-cases' ? 'active' : ''}}" ><a href="{{route('rejected-cases')}}"><i class="fa fa-times "></i> <span>Rejected Cases</span></a>
                    @endif


            @if(($permissions && $permissions->contains('permission_id', 120)) || Auth()->user()->is_admin)
                @php
                    $reportsExpanded = in_array(Route::currentRouteName(),
                                array('master-report',
                                 'num-of-units-report',
                                 'job-types-report',
                                 'QC-report',
                                 'repeats-report',
                                 'implants-report',
                                 'materials-report'))
                                ? 'true' : 'false';
                @endphp

                <li >
                    <a data-toggle="collapse" href="#laravel-examples"
                       aria-expanded="{{$reportsExpanded}}" >
                        <i class="fab fa-laravel" ></i>
                        <span class="nav-link-text">{{ __('Reports') }}</span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse{{$reportsExpanded == 'true' ? 'show' : ''}}" id="laravel-examples">
                        <ul class="nav pl-4">
                            <li class="{{Route::currentRouteName() == 'master-report' ? 'active' : ''}}" >
                                <a href="{{route('master-report')}}">
                                    <i class="fas fa-chart-line"></i>
                                    <p>{{ ('Master Report') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'num-of-units-report' ? 'active' : ''}}" >
                                <a href="{{route('num-of-units-report')}}">
                                    <i class="fas fa-calculator"></i>
                                    <p>{{ ('Number Of Units') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'job-types-report' ? 'active' : ''}}">
                                <a href="{{route('job-types-report')}}">
                                    <i class="fas fa-briefcase" style="color: white !important;"></i>
                                    <p>{{ ('Job Types') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'QC-report' ? 'active' : ''}}">
                                <a href="{{route('QC-report')}}">
                                    <i class="fas fa-check-circle"></i>
                                    <p>{{ __('QC') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'repeats-report' ? 'active' : ''}}">
                                <a href="{{route('repeats-report')}}">
                                    <i class="fas fa-redo"></i>
                                    <p>{{ ('Repeats') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'implants-report' ? 'active' : ''}}">
                                <a href="{{route('implants-report')}}">
                                    <i class="fas fa-tooth"></i>
                                    <p>{{ ('Implants') }}</p>
                                </a>
                            </li>
                            <li class="{{Route::currentRouteName() == 'materials-report' ? 'active' : ''}}">
                                <a href="{{route('materials-report')}}">
                                    <i class="fas fa-cubes"></i>
                                    <p>Materials Report</p>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif
            @if($permissions && $permissions->contains('permission_id', 104) ||$permissions && $permissions->contains('permission_id', 121) ||$permissions && $permissions->contains('permission_id', 111) || Auth()->user()->is_admin)
                @php
                    $accountancyExpanded  = in_array(Route::currentRouteName(),
                                array('invoices-index',
                                 'payments-index',
                                 'clients-index4payment','payments-with-collectors'));
                @endphp
                 <li>
                <a data-toggle="collapse" href="#accountancyList"
                   aria-expanded="{{$accountancyExpanded}}" >
                    <i class="fa-solid fa-dollar-sign"></i> <span class="nav-link-text">Accountancy</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse {{$accountancyExpanded == 'true' ? 'show' : ''}}" id="accountancyList">
                    <ul class="nav pl-4">
                        @if(($permissions && $permissions->contains('permission_id', 104)) || Auth()->user()->is_admin)
                            <li class="{{Route::currentRouteName() == 'payments-with-collectors' ? 'active' : ''}}" >
                                <a href="{{route('payments-with-collectors')}}"><i class="fa-solid fa-money-bill-transfer"></i> <span>Receive Payments</span></a>

                        @endif
                        @if(($permissions && $permissions->contains('permission_id', 104)) || Auth()->user()->is_admin)
                            <li class="{{Route::currentRouteName() == 'invoices-index' ? 'active' : ''}}" >
                                <a href="{{route('invoices-index')}}"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Invoices</span></a>

                        @endif
                        @if(($permissions && $permissions->contains('permission_id', 121)) || Auth()->user()->is_admin)
                            <li class="{{Route::currentRouteName() == 'payments-index' ? 'active' : ''}}"><a href="{{route('payments-index')}}"><i class="fa fa-credit-card"></i> <span>Payments</span></a>
                        @endif
                        @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                                <li class="{{Route::currentRouteName() == 'clients-index4payment' ? 'active' : ''}}" ><a href="{{route('clients-index4payment')}}"><i class="fa fa-user-md" style="color: white !important;"></i> <span>Take A Payment</span></a>
                        @endif
                    </ul>
                </div>
            </li>
                @endif
                @if(Auth()->user()->is_admin)
                @php
                    $configExpanded  = in_array(Route::currentRouteName(),
                                array('material-index',
                                 'job-type-index',
                                 'users-index',
                                 'labs-index',
                                 'implants-index',
                                 'abutments-index',
                                 'tags-index',
                                 'f-causes-index',
                                 'devices-index',
                                 'sys-config',
                                 'configuration.index',
                                 'media-index'))
                                ? 'true' : 'false';
                @endphp

                <li>
                    <a data-toggle="collapse" href="#configList"
                       aria-expanded="{{$configExpanded}}" >
                        <i class="fa-solid fa-gear" ></i>
                        <span class="nav-link-text">Configuration</span>
                        <b class="caret mt-1"></b>
                    </a>

                    <div class="collapse {{$configExpanded == 'true' ? 'show' : ''}}" id="configList">
                    <ul class="nav pl-4">
                    <li class="{{Route::currentRouteName() == ' media-index' ? 'active' : ''}}"><a href="{{route('media-index')}}"><i class="fa-solid fa-video"></i> <span>Gallery Media</span></a>
                    <li class="{{Route::currentRouteName() == 'material-index' ? 'active' : ''}}"><a href="{{route('material-index')}}"><i class="fa fa-cubes"></i> <span>Materials</span></a>
                    <li class="{{Route::currentRouteName() == 'job-type-index' ? 'active' : ''}}"><a href="{{route('job-type-index')}}"><i class="fa fa-object-group" style="color: white !important;" aria-hidden="true"></i> <span>Job Types</span></a>
                    <li class="{{Route::currentRouteName() == 'users-index' ? 'active' : ''}}"><a href="{{route('users-index')}}"><i class="fa fa-users"></i> <span>Users</span></a>
                    <li class="{{Route::currentRouteName() == 'labs-index' ? 'active' : ''}}"><a href="{{route('labs-index')}}"><i class="fa fa-building"></i> <span>External Labs</span></a>
                    <li class="{{Route::currentRouteName() == 'implants-index' ? 'active' : ''}}"><a href="{{route('implants-index')}}"><i class="fa-solid fa-tooth"></i> <span>Implants</span></a>
                    <li class="{{Route::currentRouteName() == 'abutments-index' ? 'active' : ''}}"><a href="{{route('abutments-index')}}"><i class="fa-brands fa-connectdevelop"></i><span>Abutments</span></a>
                    <li class="{{Route::currentRouteName() == 'tags-index' ? 'active' : ''}}"><a href="{{route('tags-index')}}"><i class="fa fa-tag"></i><span>Tags</span></a>
                    <li class="{{Route::currentRouteName() == 'f-causes-index' ? 'active' : ''}}"><a href="{{route('f-causes-index')}}"><i class="fa-solid fa-repeat"></i><span>Failure Causes</span></a>
                    <li class="{{Route::currentRouteName() == 'devices-index' ? 'active' : ''}}"><a href="{{route('devices-index')}}"><i class="fa-solid fa-tachograph-digital"></i><span>Devices</span></a>

                    </ul>
                    </div>
                </li>

           @endif

        </ul>
    </div>
</div>


