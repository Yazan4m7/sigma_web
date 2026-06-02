@extends('layouts.app' ,[ 'pageSlug' => $clientTitle .'s List' ])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets') }}/css/pages/doctors-index.css?v={{ filemtime(public_path('assets/css/pages/doctors-index.css')) }}" />
<style>
    #my-table_wrapper {
        padding: 0 !important;
    }
    .btn-secondary{
        padding: 0.375rem 1.8rem !important;
    }
    #doctor-actions-name{
        margin-top: 10px !important;
    }
</style>
@endpush

@section('content')

@php
    $permissions = Cache::get('user' . Auth()->user()->id);
    $isTakePaymentsPage = request()->routeIs('clients-index4payment');
    $showUntilFilter = ($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin;
@endphp

<div class="doctor-page-wrapper sigma-list-page{{ $isTakePaymentsPage ? ' take-payments-page' : '' }}">
<form class="kt-form doctor-filter-form" method="GET" action="{{ route('clients-index') }}">

        <div class="col-lg-12 mb-3 doctor-filter-shell-col">
            <div class="sigma-list-filter-card doctor-filters-shell cases-filter-card delivery-filter-card">
                <div class="doctor-card-body py-3">
                    <div class="doctor-filter-layout">
                        <div class="row align-items-end filters-row cases-filter-row sigma-list-filter-row doctor-filter-fields">
                            @if($showUntilFilter)
                            <div class="col-lg-3 col-md-4 col-sm-5 col-5 mb-2 doctor-filter-col">
                                <label for="from" class="filter-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Until</span>
                                </label>
                                <x-ios-dtp name="from" id="from" class="filter-input-global" :value="old('from', $from ?? '')" :required="true" mode="month" />
                            </div>
                            @endif

                            <div class="col-lg-3 col-md-4 col-sm-5 {{ $showUntilFilter ? 'col-6' : 'col-5' }} mb-2 doctor-filter-col">
                                <label for="doctor" class="filter-label">
                                    <i class="fas fa-user-md"></i>
                                    <span>Doctor</span>
                                </label>
                                <select class="selectpicker clearOnAll filter-input-global" multiple data-container="body"
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

                            <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2 sigma-filter-action-col">
                                <button type="submit" class="btn btn-primary cases-filter-btn cases-filter-btn--search sigma-apply-btn filter-apply-btn-global">
                                    <i class="fas fa-search"></i>
                                    <span>Apply</span>
                                </button>
                            </div>
                        </div>

                        <div class="doctor-filter-side">
                            <div class="doctor-actions">
                                @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                    <a href="{{ route('new-dentist-view') }}" class="icon-action icon-action--success" aria-label="Add New Doctor">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                @endif
                                @if(Auth()->user()->is_admin)
                                    <a href="{{ route('mobile-stats-configs') }}" class="icon-action" aria-label="Mobile">
                                        <i class="fa fa-phone"></i>
                                    </a>
                                @endif
                            </div>

                            <div class="status-tab">
                                <span class="status-label mb-0">Status:</span>
                                <label class="status-toggle">
                                    <input type="hidden" name="active" value="0">
                                    <input type="checkbox" id="active" name="active" value="1" {{ (old('active', $status) == 1) ? 'checked' : '' }}>
                                    <span class="toggle-text toggle-text--on">Enabled</span>
                                    <span class="toggle-text toggle-text--off">Disabled</span>
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

</form>

{{-- Total Balance Card (Moved Outside Filter Form) --}}
@if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
<div class="sigma-summary-grid col" >
    <div class="sigma-summary-item">
        <div class="materials-total-card report-total-card sigma-compact-summary-card doctor-balance-card">
            <div>
                <span class="materials-total-label">Total Balance</span>
                <div class="materials-total-value">
                    <span class="materials-total-amount sigma-summary-value--positive">{{ number_format($totalBalance) }}</span>
                    <span class="materials-total-currency">JOD</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif



                    <div class="container full-width doctor-table-shell">
                        <table class="table table-bordered dataTable no-footer globalTable nowrap compact sunriseTable table-odd sigma-list-table" id="my-table">
                            <thead>
                            <tr >
                                <th class="table-head">ID</th>
                                <th class="table-head">Name</th>
                                @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                <th class="balance-col sigma-head-left">Balance</th>

                                @endif


                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr id="{{$client->id}}" class="odd clickable {{ $client->active ? '' : 'table-secondary client-row--inactive' }}"
                                    data-toggle="modal"
                                    data-target="#doctorActionsModal"
                                    data-client-id="{{ $client->id }}"
                                    data-client-name="{{ $client->name }}"
                                    data-client-balance-label="{{ number_format($client->display_balance ?? $client->balance) }}"
                                    data-client-active="{{ $client->active ? '1' : '0' }}">
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
                                    <td class="tabledit-view-mode balance-col sigma-body-left"><span
                                                class="tabledit-span">{{ number_format($client->display_balance ?? $client->balance) }}</span><input
                                                class="tabledit-input form-control input-sm d-none" type="text" name="col1"
                                                value="Doe" disabled=""></td>

                                        @endif

                                </tr>

                            @endforeach

                            </tbody>

                        </table>
                    </div>

                    <div class="modal sigma-modal--clients-delete" tabindex="-1" role="dialog" id="doctorActionsModal">
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
                                            <label for="doctor-actions-name">Doctor:</label>
                                            <h5 id="doctor-actions-name" class="mb-0"><b>-</b></h5>
                                        </div>
                                        @if(Auth()->user()->is_admin)
                                            <div class="col-6 col-md-6">
                                                <label for="doctor-actions-balance">Balance:</label>
                                                <h5 id="doctor-actions-balance" class="mb-0"><b>-</b></h5>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                                <div class="modal-footer fullBtnsWidth">
                                    <div class="row client-actions-row">
                                        @if(($permissions && $permissions->contains('permission_id', 107)) || Auth()->user()->is_admin)
                                            <div class="col-6">
                                                <a id="doctor-statement-link" href="#" class="btn btn-info">
                                                    <span class="btn-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                                                    <span class="btn-text">Account Statement</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a id="doctor-edit-link" href="#" class="btn btn-danger">
                                                    <span class="btn-icon"><i class="fa-solid fa-pen-to-square"></i></span>
                                                    <span class="btn-text">Edit Record</span>
                                                </a>
                                            </div>
                                        @endif
                                        @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                                            <div class="col-6">
                                                <a id="doctor-payment-link" data-toggle="modal" data-target="#doctorPaymentModal" class="btn btn-warning" data-dismiss="modal">
                                                    <span class="btn-icon"><i class="fas fa-plus"></i></span>
                                                    <span class="btn-text">Add a payment</span>
                                                </a>
                                            </div>
                                        @endif
                                        @if(Auth()->user()->is_admin)
                                            <div class="col-6">
                                                <a id="doctor-cases-link" href="#" class="btn btn-info">
                                                    <span class="btn-icon"><i class="far fa-file-alt"></i></span>
                                                    <span class="btn-text">View Cases</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a id="doctor-invoices-link" href="#" class="btn btn-info">
                                                    <span class="btn-icon"><i class="fas fa-file-invoice"></i></span>
                                                    <span class="btn-text">View Invoices</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a id="doctor-payments-link" href="#" class="btn btn-info">
                                                    <span class="btn-icon"><i class="fas fa-credit-card"></i></span>
                                                    <span class="btn-text">View Payments</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a id="doctor-discount-link" data-toggle="modal" data-target="#doctorDiscountModal" class="btn btn-danger" data-dismiss="modal">
                                                    <span class="btn-icon"><i class="fas fa-percent"></i></span>
                                                    <span class="btn-text">Create a discount</span>
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <a id="doctor-toggle-link" href="#" class="btn btn-warning">
                                                    <span class="btn-icon"><i id="doctor-toggle-icon" class="fas fa-times-circle"></i></span>
                                                    <span id="doctor-toggle-label" class="btn-text">Disable</span>
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

                    @if(($permissions && $permissions->contains('permission_id', 111)) || Auth()->user()->is_admin)
                        <div class="modal sigma-modal--clients-actions" tabindex="-1" role="dialog" id="doctorPaymentModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="doctor-payment-form" action="{{route('new-payment')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="doctor-payment-client-id">
                                        <div class="modal-header">
                                            <h5 class="modal-title">New Payment</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="client-name-highlight"><b id="doctor-payment-client-name">-</b></h4>
                                            <div class="doctor-payment-field">
                                                <label class="doctor-payment-field-label" for="doctor-payment-amount">Payment amount</label>
                                                <input id="doctor-payment-amount" type="number" class="form-control doctor-payment-amount" name="amount" min="0" step="1" inputmode="numeric" pattern="[0-9]*" required>
                                            </div>
                                            <div class="doctor-payment-field">
                                                <div class="doctor-payment-field-label">Payment type</div>
                                                <div class="doctor-payment-type-group" role="radiogroup" aria-label="Payment type">
                                                    <label class="doctor-payment-option" for="doctor-payment-cash">
                                                        <input class="doctor-payment-option__input" type="radio" id="doctor-payment-cash" onchange="paymentTypeChange();" name="payment_type" value="cash">
                                                        <span class="doctor-payment-option__body">
                                                            <span class="doctor-payment-option__value">دفعة نقدية</span>
                                                        </span>
                                                    </label>
                                                    <label class="doctor-payment-option" for="doctor-payment-cheque">
                                                        <input class="doctor-payment-option__input" type="radio" id="doctor-payment-cheque" onchange="paymentTypeChange();" name="payment_type" value="cheque">
                                                        <span class="doctor-payment-option__body">
                                                            <span class="doctor-payment-option__value">شيك بنكي</span>
                                                        </span>
                                                    </label>
                                                    <label class="doctor-payment-option" for="doctor-payment-transfer">
                                                        <input class="doctor-payment-option__input" type="radio" id="doctor-payment-transfer" onchange="paymentTypeChange();" name="payment_type" value="transfer">
                                                        <span class="doctor-payment-option__body">
                                                            <span class="doctor-payment-option__value">حوالة بنكية/ كليك</span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="doctorPaymentChequeDetails" class="cheque-details d-none doctor-payment-field">
                                                <label class="doctor-payment-field-label" for="doctor-payment-bank">Bank</label>
                                                <div class="kt-form__control">
                                                    <select class="form-control" id="doctor-payment-bank" name="bank_id">
                                                        @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="doctorPaymentChequeNumberField" class="doctor-payment-field cheque-details d-none">
                                                <label class="doctor-payment-field-label" for="doctor-payment-cheque-number">Cheque number</label>
                                                <input id="doctor-payment-cheque-number" type="text" class="form-control" name="chequeNumber">
                                            </div>
                                            <div class="doctor-payment-field doctor-payment-field--last">
                                                <label class="doctor-payment-field-label" for="doctor-payment-note">Extra details (Optional)</label>
                                                <textarea id="doctor-payment-note" name="note" class="form-control doctor-payment-note-input"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn dialog-submit-btn">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if( Auth()->user()->is_admin)
                        <div class="modal sigma-modal--clients-add" tabindex="-1" role="dialog" id="doctorDiscountModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form id="doctor-discount-form" action="{{route('account-discount')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="doctor-discount-client-id">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Doctor Balance Discount</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h4 class="client-name-highlight"><b id="doctor-discount-client-name">-</b></h4>
                                            <div class="doctor-payment-field">
                                                <label class="doctor-payment-field-label" for="doctor-discount-amount">Discount amount</label>
                                                <input id="doctor-discount-amount" type="number" class="form-control doctor-discount-amount" name="discountAmount" min="0" step="1" inputmode="numeric" pattern="[0-9]*" required>
                                            </div>
                                            <div class="doctor-payment-field">
                                                <label class="doctor-payment-field-label" for="doctor-discount-date">Date of discount</label>
                                                <input id="doctor-discount-date" type="datetime-local" name="discount_date" class="form-control">
                                            </div>
                                            <div class="doctor-payment-field doctor-payment-field--last">
                                                <label class="doctor-payment-field-label" for="doctor-discount-title">Details (How it appears on account statement)</label>
                                                <input id="doctor-discount-title" type="text" name="discount_title" class="form-control">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn dialog-submit-btn">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

        </div>
    </div>
</div>

@endsection
@push('js')
    <script>
        const doctorRouteTemplates = {
            statement: @json(route('client-statement-admin', '__CLIENT__')),
            edit: @json(route('client-view-edit', ['id' => '__CLIENT__'])),
            cases: @json(route('dentist-cases', ['id' => '__CLIENT__'])),
            invoices: @json(route('dentist-invoices', ['id' => '__CLIENT__'])),
            payments: @json(route('dentist-payments', ['id' => '__CLIENT__'])),
            toggle: @json(route('toggle-client-active', '__CLIENT__')),
        };

        function doctorRoute(type, clientId) {
            const template = doctorRouteTemplates[type] || '#';
            return template.replace('__CLIENT__', clientId);
        }

        function enforceNonNegativeWholeAmount(input) {
            if (!input || input.value === '') {
                return;
            }

            const amount = Number(input.value);
            if (Number.isNaN(amount)) {
                input.value = '';
                return;
            }

            input.value = String(Math.max(0, Math.trunc(amount)));
        }

        function preventDecimalAmountInput(event) {
            if (event.key === '.' || event.key === ',' || event.key === 'e' || event.key === 'E' || event.key === '-') {
                event.preventDefault();
            }
        }

        function paymentTypeChange() {
            const cheque = document.getElementById('doctor-payment-cheque');
            const detailSections = [
                document.getElementById('doctorPaymentChequeDetails'),
                document.getElementById('doctorPaymentChequeNumberField'),
            ].filter(Boolean);
            const bankField = document.getElementById('doctor-payment-bank');
            const chequeNumberField = document.getElementById('doctor-payment-cheque-number');

            if (!cheque || detailSections.length === 0) {
                return;
            }
            if (cheque.checked) {
                detailSections.forEach(function(section) {
                    section.classList.remove('d-none');
                });
                if (bankField) {
                    bankField.required = true;
                }
                if (chequeNumberField) {
                    chequeNumberField.required = true;
                }
            } else {
                detailSections.forEach(function(section) {
                    section.classList.add('d-none');
                });
                if (bankField) {
                    bankField.required = false;
                }
                if (chequeNumberField) {
                    chequeNumberField.required = false;
                }
            }
        }

        function populateDoctorActionsModal(trigger) {
            if (!trigger) {
                return;
            }

            const data = trigger.dataset;
            const clientId = data.clientId || '';
            const isActive = data.clientActive === '1';
            const nameNode = document.getElementById('doctor-actions-name');
            const balanceNode = document.getElementById('doctor-actions-balance');

            if (nameNode) {
                nameNode.textContent = data.clientName || '-';
            }
            if (balanceNode) {
                balanceNode.textContent = data.clientBalanceLabel || data.clientBalance || '-';
            }

            [
                ['doctor-statement-link', doctorRoute('statement', clientId)],
                ['doctor-edit-link', doctorRoute('edit', clientId)],
                ['doctor-cases-link', doctorRoute('cases', clientId)],
                ['doctor-invoices-link', doctorRoute('invoices', clientId)],
                ['doctor-payments-link', doctorRoute('payments', clientId)],
            ].forEach(function(entry) {
                const id = entry[0];
                const value = entry[1];
                const element = document.getElementById(id);
                if (!element) {
                    return;
                }

                element.setAttribute('href', value || '#');
            });

            const modalTriggerData = {
                clientId: data.clientId || '',
                clientName: data.clientName || '-',
            };

            ['doctor-payment-link', 'doctor-discount-link'].forEach(function(id) {
                const element = document.getElementById(id);
                if (!element) {
                    return;
                }

                element.dataset.clientId = modalTriggerData.clientId;
                element.dataset.clientName = modalTriggerData.clientName;
            });

            const toggleLink = document.getElementById('doctor-toggle-link');
            const toggleLabel = document.getElementById('doctor-toggle-label');
            const toggleIcon = document.getElementById('doctor-toggle-icon');

            if (toggleLink) {
                toggleLink.href = doctorRoute('toggle', clientId);
                toggleLink.className = 'btn ' + (isActive ? 'btn-warning' : 'btn-success');
                toggleLink.onclick = function() {
                    return confirm('Are you sure you want to ' + (isActive ? 'disable' : 'enable') + ' this doctor?');
                };
            }
            if (toggleLabel) {
                toggleLabel.textContent = isActive ? 'Disable' : 'Enable';
            }
            if (toggleIcon) {
                toggleIcon.className = isActive ? 'fas fa-times-circle' : 'fas fa-check-circle';
            }
        }

        $(document).on('show.bs.modal', '#doctorActionsModal', function(event) {
            populateDoctorActionsModal(event.relatedTarget);
        });

        $(document).on('show.bs.modal', '#doctorPaymentModal', function(event) {
            const trigger = event.relatedTarget;
            const form = document.getElementById('doctor-payment-form');

            if (form) {
                form.reset();
            }

            document.getElementById('doctor-payment-client-id').value = trigger?.dataset?.clientId || '';
            document.getElementById('doctor-payment-client-name').textContent = trigger?.dataset?.clientName || '-';
            paymentTypeChange();
        });

        $(document).on('input change', '.doctor-payment-amount, .doctor-discount-amount', function() {
            enforceNonNegativeWholeAmount(this);
        });

        $(document).on('keydown', '.doctor-payment-amount, .doctor-discount-amount', function(event) {
            preventDecimalAmountInput(event);
        });

        $(document).on('show.bs.modal', '#doctorDiscountModal', function(event) {
            const trigger = event.relatedTarget;
            const form = document.getElementById('doctor-discount-form');

            if (form) {
                form.reset();
            }

            document.getElementById('doctor-discount-client-id').value = trigger?.dataset?.clientId || '';
            document.getElementById('doctor-discount-client-name').textContent = trigger?.dataset?.clientName || '-';
        });
    </script>
@endpush
