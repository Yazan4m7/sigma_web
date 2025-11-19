<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sCase;
use App\client;
use App\job;
use App\materialJobtype;
use App\AuditLog;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\CaseController;

class ToolsController extends Controller
{
    public function invoiceCheck(Request $request)
    {
        $clients = client::all();
        // Include cases that either don't have an invoice or have an invoice created but not applied (status = 0)
        // Include cases that are in later stages (>=7), completed (stage = -1),
        // or have an actual_delivery_date set (completed)
        $casesQuery = sCase::where(function ($mainQ) {
            $mainQ->whereHas('jobs', function ($query) {
                $query->where(function($jq) {
                    $jq->where('stage', '>=', 7)->orWhere('stage', -1);
                });
            })->orWhereNotNull('actual_delivery_date');
        })->where(function ($q) {
            // Include cases that don't have an invoice OR have an invoice but date_applied is NULL (not applied yet)
            $q->whereDoesntHave('invoice')
              ->orWhereHas('invoice', function ($iq) {
                  $iq->whereNull('date_applied')->orWhere('date_applied', '');
              });
        });

        $from = $request->from ? $request->from : now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ? $request->to : now()->format('Y-m-d');

        // include full day for 'to' date by adding time range
        $casesQuery->whereBetween('created_at', [$from . ' 00:00', $to . ' 23:59']);

        if ($request->has('doctor') && is_array($request->doctor)) {
            if (!in_array('all', $request->doctor)) {
                $casesQuery->whereIn('doctor_id', $request->doctor);
            }
        }

        $cases = $casesQuery->get();

        return view('tools.invoice-check', compact('cases', 'clients', 'from', 'to'));
    }

    public function createCaseTool()
    {
        return view('tools.create-case');
    }

    public function issueInvoiceForCase(Request $request)
    {
        $request->validate([
            'case_id' => 'required|integer|exists:cases,id'
        ]);

        $case = sCase::with(['jobs' => function ($q) {
            $q->orderBy('id');
        }])->find($request->case_id);

        if (!$case) {
            return back()->with('error', 'Case not found.');
        }

        if ($case->jobs->isEmpty()) {
            return back()->with('error', 'Case has no jobs to calculate invoice.');
        }

        // Prefer a non-repeat, non-modification job if available
        $job = $case->jobs->first(function ($j) {
            return empty($j->is_repeat) && empty($j->is_modification);
        }) ?? $case->jobs->first();

        try {
            $caseController = app(CaseController::class);
            $caseController->issueInvoice($job);
            return back()->with('success', "Invoice issued for case #{$case->id}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to issue invoice: ' . $e->getMessage());
        }
    }

    public function applyInvoiceForCase(Request $request)
    {
        $request->validate([
            'case_id' => 'required|integer|exists:cases,id'
        ]);

        $case = sCase::with('invoice', 'client')->find($request->case_id);

        if (!$case) {
            return back()->with('error', 'Case not found.');
        }

        $invoice = $case->invoice;
        if (!$invoice) {
            return back()->with('error', 'No invoice exists for this case. Please issue an invoice first.');
        }

        if (!empty($invoice->date_applied)) {
            return back()->with('error', 'Invoice already applied.');
        }

        try {
            // Use the case actual_delivery_date if available, otherwise fallback to now()
            $appliedAt = $case->actual_delivery_date ? date('Y-m-d H:i:s', strtotime($case->actual_delivery_date)) : now();
            $invoice->date_applied = $appliedAt;
            $invoice->status = 1;
            $invoice->save();

            // Update client balance similarly to CaseController::applyInvoice
            $client = $case->client;
            if ($client) {
                $client->balance = $client->balance + ($invoice->amount ?? 0);
                $client->save();
            }

            return back()->with('success', "Invoice applied for case #{$case->id}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to apply invoice: ' . $e->getMessage());
        }
    }

    public function storeCaseTool(Request $request)
    {
        $request->validate([
            'stage' => 'required|integer|min:1|max:8',
            'amount' => 'required|integer|min:1',
            'jaw_teeth' => 'required|string|in:jaw,teeth',
        ]);

        $stage = $request->input('stage');
        $amount = $request->input('amount');
        $jawTeeth = $request->input('jaw_teeth');

        DB::beginTransaction();
        try {
            $faker = Faker::create();
            for ($i = 0; $i < $amount; $i++) {
                $case = new sCase();
                $case->case_id = $faker->unique()->numerify('Y####');
                $case->patient_name = $faker->name;
                $case->doctor_id = $faker->numberBetween(1, 10);
                $case->impression_type = $faker->randomElement([1, 2, 3]);
                $case->initial_delivery_date = $faker->dateTimeBetween('now', '+1 month');
                $case->created_by = auth()->id() ?? 1;
                $case->save();

                $units = ($jawTeeth == 'teeth')
                    ? $faker->randomElement(['1', '1,2', '1,2,3'])
                    : $faker->randomElement(['upper', "lower,upper", "lower"]);

                $jobCount = rand(1, 2);
                for ($j = 0; $j < $jobCount; $j++) {
                    $materialJobType = materialJobtype::inRandomOrder()->first();

                    $newJob = new job([
                        'unit_num' => $units,
                        'type' => $materialJobType->jobtype_id,
                        'color' => "A1",
                        'style' => "Single",
                        'abutment' => $faker->randomElement([0, 1]),
                        'implant' => $faker->randomElement([0, 1]),
                        'material_id' => $materialJobType->material_id,
                        'case_id' => $case->id,
                        'doctor_id' => $case->doctor_id,
                        'stage' => $stage,
                    ]);
                    $newJob->save();
                }
            }
            DB::commit();
            return back()->with('success', "$amount dummy case(s) created successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Error: " . $e->getMessage());
        }
    }

    public function auditLogs(Request $request)
    {
        $filters = [
            'action' => $request->input('action'),
            'user_id' => $request->input('user_id'),
            'search' => $request->input('search'),
            'from' => $request->input('from'),
            'to' => $request->input('to'),
        ];

        $logsQuery = AuditLog::with('user')->orderByDesc('created_at');

        if (!empty($filters['action'])) {
            $logsQuery->where('action', $filters['action']);
        }

        if (!empty($filters['user_id'])) {
            $logsQuery->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $logsQuery->where(function ($query) use ($term, $filters) {
                $query->where('description', 'like', $term)
                    ->orWhere('subject_type', 'like', $term)
                    ->orWhere('properties', 'like', $term);

                if (is_numeric($filters['search'])) {
                    $query->orWhere('subject_id', (int) $filters['search']);
                }
            });
        }

        if (!empty($filters['from'])) {
            try {
                $from = Carbon::parse($filters['from'])->startOfDay();
                $logsQuery->where('created_at', '>=', $from);
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        }

        if (!empty($filters['to'])) {
            try {
                $to = Carbon::parse($filters['to'])->endOfDay();
                $logsQuery->where('created_at', '<=', $to);
            } catch (\Throwable $e) {
                // ignore invalid date
            }
        }

        $logs = $logsQuery->paginate(40)->withQueryString();
        $actions = AuditLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users = User::orderBy('first_name')->orderBy('last_name')->get(['id', 'first_name', 'last_name', 'name_initials', 'username']);

        return view('tools.audit-logs', compact('logs', 'actions', 'users', 'filters'));
    }
}
