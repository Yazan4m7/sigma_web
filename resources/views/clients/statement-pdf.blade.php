@php
    $formatAmount = static fn ($value): string => number_format((float) $value, 0, '.', '');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            color: #25343b;
            font-family: cairo, sans-serif;
            font-size: 9.5pt;
        }

        h1, h2, p {
            margin: 0;
        }

        .summary-layout,
        .summary-table,
        .transactions-table,
        .statement-total {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-layout {
            margin-bottom: 7mm;
        }

        .summary-layout td {
            padding: 0;
            vertical-align: top;
        }

        .summary-title {
            padding: 2.5mm 3mm;
            background: #e8f2f2;
            color: #337374;
            font-size: 10pt;
            font-weight: 700;
        }

        .summary-table td {
            padding: 1.4mm 3mm;
            border-bottom: 0.2mm solid #d9e3e5;
        }

        .summary-table td:first-child {
            font-weight: 600;
        }

        .summary-table td:last-child {
            font-weight: 700;
            text-align: right;
        }

        .summary-table .summary-total td {
            border-top: 0.5mm solid #408385;
            border-bottom: 0;
            color: #2d5f6d;
            font-size: 11pt;
        }

        .balance-due-value {
            font-weight: 800;
        }

        .transactions-table {
            page-break-inside: auto;
        }

        .transactions-table thead {
            display: table-header-group;
        }

        .transactions-table tr {
            page-break-inside: avoid;
        }

        .transactions-table th {
            padding: 2.2mm 2mm;
            border: 0.2mm solid #347779;
            background: #408385;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: 700;
            text-align: left;
        }

        .transactions-table th:nth-child(4),
        .transactions-table th:nth-child(5),
        .transactions-table th:nth-child(6) {
            font-weight: 800;
            text-align: left;
        }

        .transactions-table td {
            padding: 2mm;
            border: 0.2mm solid #d7e2e5;
            vertical-align: top;
        }

        .transactions-table tbody tr:nth-child(even) {
            background: #f5f9f9;
        }

        .transactions-table .number {
            font-weight: 700;
            white-space: nowrap;
            text-align: left;
        }

        .transactions-table .empty-row {
            padding: 8mm;
            color: #6b7c85;
            text-align: center;
        }

        .statement-total {
            margin-top: 4mm;
        }

        .statement-total td {
            padding: 2mm;
            border-top: 0.6mm solid #408385;
            font-size: 11pt;
            font-weight: 700;
        }

        .statement-total td:last-child {
            color: #2d5f6d;
            font-weight: 800;
            text-align: left;
        }

        .statement-total-label {
            text-align: left;
        }

        .statement-total-spacer {
            color: transparent;
        }

        .discount-note {
            margin-top: 3mm;
            color: #657780;
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <table class="summary-layout">
        <tr>
            <td width="58%"></td>
            <td width="42%">
                <table class="summary-table">
                    <tr>
                        <td colspan="2" class="summary-title">Account Summary</td>
                    </tr>
                    <tr>
                        <td>Opening Balance</td>
                        <td>{{ $formatAmount($openingBalance) }} JOD</td>
                    </tr>
                    <tr>
                        <td>Invoices Amount</td>
                        <td>{{ $formatAmount($invoicesAmount) }} JOD</td>
                    </tr>
                    <tr>
                        <td>Amount Paid</td>
                        <td>{{ $formatAmount($amountPaid) }} JOD</td>
                    </tr>
                    @if($discounts != 0.0)
                        <tr>
                            <td>Discounts</td>
                            <td>{{ $formatAmount($discounts) }} JOD</td>
                        </tr>
                    @endif
                    <tr class="summary-total">
                        <td>Balance Due</td>
                        <td class="balance-due-value"><strong>{{ $formatAmount($closingBalance) }} JOD</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="transactions-table">
        <colgroup>
            <col width="12%">
            <col width="20%">
            <col width="19%">
            <col width="17%">
            <col width="16%">
            <col width="16%">
        </colgroup>
        <thead>
            <tr>
                <th>Date</th>
                <th>Transaction</th>
                <th>Description</th>
                <th>Payment</th>
                <th>Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($statementRows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['transaction'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td class="number">{{ is_null($row['payment']) ? '-' : $formatAmount($row['payment']) }}</td>
                    <td class="number">{{ is_null($row['amount']) ? '-' : $formatAmount($row['amount']) }}{{ $row['has_discount'] ? '*' : '' }}</td>
                    <td class="number">{{ $formatAmount($row['balance']) }}</td>
                </tr>
            @empty
                <tr>
                    <td class="empty-row" colspan="6">No transactions in this date range.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($discountExist)
        <p class="discount-note">* Discount applied</p>
    @endif

    <table class="statement-total">
        <colgroup>
            <col width="72%">
            <col width="16%">
            <col width="12%">
        </colgroup>
        <tr>
            <td width="72%" class="statement-total-label">Balance Due</td>
            <td width="16%" class="statement-total-spacer">&nbsp;</td>
            <td width="12%" class="balance-due-value"><strong>{{ $formatAmount($closingBalance) }} JOD</strong></td>
        </tr>
    </table>
</body>
</html>
