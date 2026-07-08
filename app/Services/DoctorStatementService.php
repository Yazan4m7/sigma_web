<?php

namespace App\Services;

use App\client;
use App\invoice;
use App\payment;
use Illuminate\Support\Collection;

class DoctorStatementService
{
    public function build(client $client, string $from, string $to): array
    {
        $invoices = invoice::query()
            ->with(['case', 'discount'])
            ->where('doctor_id', $client->id)
            ->where('status', 1)
            ->whereBetween('date_applied', [$from . ' 00:00', $to . ' 23:59'])
            ->get();

        $payments = payment::query()
            ->where('doctor_id', $client->id)
            ->whereBetween('created_at', [$from . ' 00:00', $to . ' 23:59'])
            ->get();

        $transactions = $invoices
            ->toBase()
            ->merge($payments)
            ->map(function ($transaction) {
                $transaction->statement_date = $this->transactionDate($transaction);

                return $transaction;
            })
            ->sortBy('statement_date')
            ->values();

        $amountDuePreDate = invoice::query()
            ->where('doctor_id', $client->id)
            ->where('status', 1)
            ->where('date_applied', '<', $from . ' 00:00')
            ->sum('amount');

        $amountPaidPreDate = payment::query()
            ->where('doctor_id', $client->id)
            ->where('created_at', '<', $from . ' 00:00')
            ->sum('amount');

        $openingBalance = (float) $amountDuePreDate - (float) $amountPaidPreDate;
        $statementRows = $this->buildRows($transactions, $openingBalance);
        $invoicesAmount = (float) $transactions
            ->filter(fn ($transaction) => $this->isInvoice($transaction) && empty($transaction->discount_title))
            ->sum('amount');
        $discounts = (float) $transactions
            ->filter(fn ($transaction) => $this->isInvoice($transaction) && !empty($transaction->discount_title))
            ->sum('amount');
        $amountPaid = (float) $transactions
            ->reject(fn ($transaction) => $this->isInvoice($transaction))
            ->sum('amount');
        $closingBalance = $statementRows->isEmpty()
            ? $openingBalance
            : (float) $statementRows->last()['balance'];

        return compact(
            'amountPaidPreDate',
            'amountDuePreDate',
            'invoices',
            'client',
            'payments',
            'transactions',
            'to',
            'from',
            'openingBalance',
            'statementRows',
            'invoicesAmount',
            'discounts',
            'amountPaid',
            'closingBalance'
        ) + [
            'discountExist' => $statementRows->contains('has_discount', true),
        ];
    }

    public function fileName(client $client, string $from, string $to): string
    {
        $safeName = preg_replace('/[<>:"\/\\\\|?*\x00-\x1F]/u', '-', trim((string) $client->name));
        $safeName = trim(preg_replace('/\s+/u', ' ', $safeName), ". \t\n\r\0\x0B");

        if ($safeName === '') {
            $safeName = 'doctor-' . $client->id;
        }

        return "statement-{$safeName}-{$from}-to-{$to}.pdf";
    }

    private function buildRows(Collection $transactions, float $openingBalance): Collection
    {
        $balance = $openingBalance;

        return $transactions->map(function ($transaction) use (&$balance): array {
            $isInvoice = $this->isInvoice($transaction);
            $amount = (float) $transaction->amount;
            $balance += $isInvoice ? $amount : -$amount;

            return [
                'date' => substr((string) $transaction->statement_date, 0, 10),
                'transaction' => $isInvoice
                    ? ($transaction->case ? 'Invoice' : 'Discount')
                    : 'Payment',
                'description' => $this->transactionDescription($transaction, $isInvoice),
                'payment' => $isInvoice ? null : $amount,
                'amount' => $isInvoice ? $amount : null,
                'balance' => $balance,
                'has_discount' => $isInvoice && $transaction->discount !== null,
            ];
        });
    }

    private function transactionDate($transaction): string
    {
        if (!empty($transaction->date_applied)) {
            return (string) $transaction->date_applied;
        }

        if ($transaction->case && !empty($transaction->case->actual_delivery_date)) {
            return (string) $transaction->case->actual_delivery_date;
        }

        return (string) $transaction->created_at;
    }

    private function transactionDescription($transaction, bool $isInvoice): string
    {
        if (!$isInvoice) {
            return (string) ($transaction->notes ?? '');
        }

        if (!$transaction->case) {
            return (string) ($transaction->discount_title ?? '');
        }

        $patientName = (string) $transaction->case->patient_name;

        return (int) $transaction->rejection_invoice === 1
            ? $patientName . ' / مرتجع'
            : str_replace('/ تعديل', '', $patientName);
    }

    private function isInvoice($transaction): bool
    {
        return !is_null($transaction->case_id ?? null);
    }
}
