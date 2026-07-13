@extends('layouts.app', ['pageSlug' => 'Statement Of Account'])

@push('css')
<style>
    .statement-page {
        color: #25343b;
        font-family: "Cairo", sans-serif;
    }

    .statement-filter-card {
        margin-bottom: 1.25rem;
    }

    .statement-filter-card .filter-label {
        display: block;
        margin-bottom: 6px;
        color: #6c757d;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .statement-sheet {
        width: 100%;
        margin: 0 auto;
        padding: 32px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2eaec;
        border-radius: 10px;
        box-shadow: 0 8px 28px rgba(37, 52, 59, .08);
    }

    .statement-screen-header {
        display: grid;
        grid-template-columns: 76px minmax(0, 1fr);
        gap: 18px;
        align-items: center;
        margin-bottom: 2px;
        padding: 0 0 22px;
        border-bottom: 1px solid #d8e6e7;
    }

    .statement-screen-logo {
        display: block;
        width: 64px;
        height: auto;
    }

    .statement-screen-title {
        margin: 0;
        color: #2d5f6d;
        font-size: 26px;
        font-weight: 700;
        line-height: 1.25;
    }

    .statement-screen-client {
        margin: 2px 0 0;
        color: #25343b;
        font-size: 17px;
        font-weight: 700;
        line-height: 1.35;
    }

    .statement-screen-range {
        margin: 3px 0 0;
        color: #6b7c85;
        direction: ltr;
        font-size: 14px;
        line-height: 1.35;
        unicode-bidi: embed;
    }

    .statement-document-scroll {
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
    }

    .statement-document {
        min-width: 780px;
        padding-top: 28px;
    }

    @include('clients.partials.statement-document-styles')

    .statement-document .transactions-table th,
    .statement-document .transactions-table td {
        line-height: 1.45;
    }

    .statement-document .transactions-table,
    .statement-document .transactions-table th {
        border-radius: 0 !important;
    }

    .statement-document .transactions-table th,
    .statement-document .transactions-table th:nth-child(4),
    .statement-document .transactions-table th:nth-child(5),
    .statement-document .transactions-table th:nth-child(6) {
        font-size: 16px;
        font-weight: 400;
    }

    .statement-document .summary-title {
        font-size: 15px;
    }

    .statement-document .summary-table .summary-total td,
    .statement-document .statement-total td {
        font-size: 17px;
    }

    #statement-transactions-table_wrapper .statement-total {
        clear: both;
        margin-top: 0;
    }

    #statement-transactions-table_wrapper .dataTables_paginate {
        float: none;
        padding-top: 8px;
        text-align: right;
    }

    @media (max-width: 767px) {
        .summary-layout,
        .summary-layout > tbody {
            display: block;
            width: 100%;
        }

        .summary-layout > tbody > tr {
            display: flex;
            width: 100%;
        }

        .summary-layout > tbody > tr > td:nth-child(1) {
            display: none;
        }

        .summary-layout > tbody > tr > td:nth-child(2) {
            display: block;
            flex: 0 0 50%;
            width: 50% !important;
            max-width: 50%;
        }

        .statement-document-scroll {
            overflow-x: auto;
        }
        .statement-filter-card .filter-label {
            margin-bottom: 4px;
        }

        .statement-sheet {
            padding: 20px 0 22px;
            border-radius: 8px;
        }

        .statement-screen-header {
            grid-template-columns: 52px minmax(0, 1fr);
            gap: 12px;
            margin: 0 18px;
            padding-bottom: 17px;
        }

        .statement-screen-logo {
            width: 46px;
        }

        .statement-screen-title {
            font-size: 20px;
        }

        .statement-screen-client {
            font-size: 14px;
        }

        .statement-screen-range {
            font-size: 12px;
        }

        .statement-document {
            padding: 22px 18px 0;
        }
    }
</style>
@endpush

@section('content')
    @php
        $fromLabel = \Carbon\Carbon::parse($from)->format('d M Y');
        $toLabel = \Carbon\Carbon::parse($to)->format('d M Y');
        $pdfUrl = route('doctor-statement-pdf', [
            'doctor' => $client->id,
            'from' => substr($from, 0, 10),
            'to' => substr($to, 0, 10),
        ]);
    @endphp

    <div class="statement-page">
        <form class="kt-form sigma-list-page" method="GET" action="{{ route('client-statement-admin', $client->id) }}">
            <div class="col-12 card sigma-list-filter-card statement-filter-card">
                <input type="hidden" name="id" value="{{ $client->id }}">
                <div class="row d-flex align-items-end mb-3 sigma-list-filter-row">
                    <div class="col-lg-3 col-md-3 col-12 mb-3">
                        <label class="filter-label" for="from">
                            <i class="fas fa-calendar-alt"></i>
                            <span>From</span>
                        </label>
                        <x-ios-dtp name="from" id="from" :value="$from" mode="month" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 mb-3">
                        <label class="filter-label" for="to">
                            <i class="fas fa-calendar-alt"></i>
                            <span>To</span>
                        </label>
                        <x-ios-dtp name="to" id="to" :value="$to" mode="month" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 mb-3 sigma-filter-action-col">
                        <button type="submit" class="btn btn-primary sigma-apply-btn" title="Filter">
                            <i class="fas fa-search"></i>
                            <span>Apply</span>
                        </button>
                    </div>
                    <div class="col-lg-3 col-md-3 col-12 mb-3 sigma-filter-secondary-col">
                        <div class="sigma-filter-toolbar-end">
                            <a class="btn sigma-toolbar-icon-btn" title="All-time" href="{{ route('client-statement-admin', ['id' => $client->id, 'allTime' => 1]) }}">
                                <i class="fas fa-clock"></i>
                            </a>
                            <a class="btn sigma-toolbar-icon-btn" title="Download PDF" href="{{ $pdfUrl }}">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <article class="statement-sheet" aria-labelledby="statement-title">
            <header class="statement-screen-header">
                <img src="{{ asset('assets/img/green-pdf.jpg') }}" class="statement-screen-logo" alt="SIGMA">
                <div>
                    <h1 class="statement-screen-title" id="statement-title">Statement of Account</h1>
                    <p class="statement-screen-client">Dr. {{ $client->name }}</p>
                    <p class="statement-screen-range">{{ $fromLabel }} to {{ $toLabel }}</p>
                </div>
            </header>

            <div class="statement-document-scroll" tabindex="0" aria-label="Account statement details">
                <div class="statement-document">
                    @include('clients.partials.statement-document', ['statementTableId' => 'statement-transactions-table'])
                </div>
            </div>
        </article>
    </div>
@endsection

@push('js')
<script>
    $(function () {
        const statementTable = $('#statement-transactions-table');

        if (statementTable.length && $.fn.DataTable && !$.fn.DataTable.isDataTable(statementTable[0])) {
            statementTable.DataTable({
                pageLength: 25,
                searching: false,
                lengthChange: false,
                ordering: false,
                info: false,
                autoWidth: false,
                pagingType: 'simple_numbers',
                dom: 'tp'
            });

            const tableWrapper = statementTable.closest('.dataTables_wrapper');
            const pagination = tableWrapper.find('.dataTables_paginate');
            const balanceDue = statementTable.closest('.statement-document').children('.statement-total');

            if (pagination.length && balanceDue.length) {
                balanceDue.detach().insertBefore(pagination);
            }
        }
    });
</script>
@endpush
