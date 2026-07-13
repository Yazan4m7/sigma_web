@php
    $formatAmount = static fn ($value): string => number_format((float) $value, 0, '.', '');
@endphp
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

<table class="transactions-table" @if(!empty($statementTableId)) id="{{ $statementTableId }}" @endif>
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
        <col width="12%">
        <col width="20%">
        <col width="19%">
        <col width="17%">
        <col width="16%">
        <col width="16%">
    </colgroup>
    <tr>
        <td colspan="4" class="statement-total-spacer">&nbsp;</td>
        <td class="statement-total-label">Balance Due</td>
        <td class="balance-due-value"><strong>{{ $formatAmount($closingBalance) }} JOD</strong></td>
    </tr>
</table>
