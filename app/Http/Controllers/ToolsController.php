<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sCase;
use App\client;
use App\job;
use App\JobType;
use App\material;
use App\materialJobtype;
use App\AuditLog;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
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
        $jobTypes = JobType::select('id', 'name', 'teeth_or_jaw')->orderBy('name')->get();
        $materials = material::select('id', 'name')->orderBy('name')->get();
        $jobTypeMaterials = materialJobtype::select('jobtype_id', 'material_id')->get();

        return view('tools.create-case', compact('jobTypes', 'materials', 'jobTypeMaterials'));
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
            'phase' => 'nullable|integer|min:0',
            'amount' => 'required|integer|min:1|max:50',
            'jaw_teeth' => 'required|string|in:jaw,teeth',
            'jaw_selection' => 'required_if:jaw_teeth,jaw|in:upper,lower,both',
            'units' => 'required_if:jaw_teeth,teeth|string|nullable',
            'job_type_id' => 'required|exists:job_types,id',
            'material_id' => 'required|exists:materials,id',
        ]);

        $stage = $request->input('stage');
        $amount = $request->input('amount');
        $jawTeeth = $request->input('jaw_teeth');
        $jobTypeId = (int) $request->input('job_type_id');
        $materialId = (int) $request->input('material_id');

        $jobType = JobType::find($jobTypeId);
        if (!$jobType) {
            return back()->with('error', 'Selected job type is invalid.');
        }

        // Enforce jaw vs teeth compatibility
        $expectedFlag = $jawTeeth === 'jaw' ? 1 : 0;
        if ((int) $jobType->teeth_or_jaw !== $expectedFlag) {
            return back()->with('error', 'Selected job type is not allowed for the chosen jaw/teeth option.');
        }

        // Enforce material belongs to job type
        $materialAllowed = materialJobtype::where('jobtype_id', $jobTypeId)
            ->where('material_id', $materialId)
            ->exists();
        if (!$materialAllowed) {
            return back()->with('error', 'Selected material is not linked to the chosen job type.');
        }

        $units = '';
        if ($jawTeeth === 'jaw') {
            $jaw = $request->input('jaw_selection');
            $units = $jaw === 'both' ? 'upper,lower' : $jaw;
        } else {
            $rawUnits = $request->input('units', '');
            $unitList = array_filter(array_map('trim', explode(',', $rawUnits)), function ($val) {
                return $val !== '';
            });
            if (count($unitList) === 0) {
                return back()->with('error', 'Please provide at least one tooth unit.');
            }
            $units = implode(',', $unitList);
        }

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

                $newJob = new job([
                    'unit_num' => $units,
                    'type' => $jobTypeId,
                    'color' => "A1",
                    'style' => "Single",
                    'abutment' => 0,
                    'implant' => 0,
                    'material_id' => $materialId,
                    'case_id' => $case->id,
                    'doctor_id' => $case->doctor_id,
                    'stage' => $stage,
                ]);
                $newJob->save();
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

    public function pageLoadTester(Request $request)
    {
        $pages = $this->pageLoadTestPages();

        $results = DB::table('page_load_tests as t')
            ->leftJoin('users as u', 't.tested_by', '=', 'u.id')
            ->select('t.*', 'u.name_initials', 'u.first_name', 'u.last_name')
            ->orderByDesc('t.id')
            ->paginate(50);

        return view('tools.page-load-tester', compact('pages', 'results'));
    }

    public function runPageLoadTest(Request $request)
    {
        @set_time_limit(90);
        $pages = $this->pageLoadTestPages();

        $request->validate([
            'page_key' => 'nullable|string|max:100',
            'custom_url' => 'nullable|string|max:2048',
            'timeout' => 'nullable|integer|min:5|max:120',
            'use_session' => 'nullable|boolean',
            'mode' => 'nullable|in:http,internal',
        ]);

        $pageKey = $request->input('page_key');
        $customUrl = trim((string) $request->input('custom_url'));

        $url = null;
        $label = null;

        if ($pageKey && isset($pages[$pageKey])) {
            $label = $pages[$pageKey]['label'];
            $url = $pages[$pageKey]['url'];
        }

        if ($customUrl !== '') {
            $url = $this->normalizeTestUrl($customUrl, $request);
            $label = $label ? $label . ' (Custom)' : 'Custom URL';
        }

        if (!$url) {
            return $this->pageLoadTesterRedirect($request, 'Please select a page or enter a URL.', 'error');
        }

        $allowedHost = parse_url(url('/'), PHP_URL_HOST);
        $targetHost = parse_url($url, PHP_URL_HOST);
        if ($targetHost && $allowedHost && strcasecmp($targetHost, $allowedHost) !== 0) {
            return $this->pageLoadTesterRedirect($request, 'Only URLs on this host are allowed.', 'error');
        }

        $mode = $request->input('mode', 'internal');
        $info = [];
        $error = null;
        $httpStatus = null;
        $totalMs = null;
        $sizeDownload = null;
        $startTransferMs = null;

        if ($mode === 'http') {
            if (!function_exists('curl_init')) {
                return $this->pageLoadTesterRedirect($request, 'cURL is not available on this server.', 'error');
            }

            $useSession = (bool) $request->input('use_session');
            $cookieHeader = '';
            if ($useSession) {
                $cookieHeader = $this->buildCookieHeader($request);
                Session::save();
                if (function_exists('session_write_close')) {
                    session_write_close();
                }
            } else {
                $token = Str::random(40);
                Cache::put('page_load_test_token:' . $token, ['user_id' => auth()->id()], now()->addMinutes(2));
                $url = $this->appendTestTokenToUrl($url, $token);
            }

            $timeout = (int) $request->input('timeout', 25);
            $timeout = max(5, min(120, $timeout));

            $ch = curl_init($url);
            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_CONNECTTIMEOUT => 8,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_USERAGENT => 'SigmaPageLoadTester/1.0',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ];
            if ($cookieHeader !== '') {
                $options[CURLOPT_COOKIE] = $cookieHeader;
            }
            curl_setopt_array($ch, $options);
            curl_exec($ch);
            $error = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            $this->restartSessionIfNeeded();

            $httpStatus = $info['http_code'] ?? null;
            $totalMs = $this->toMs($info['total_time'] ?? null);
            $sizeDownload = $info['size_download'] ?? null;
            $startTransferMs = $this->toMs($info['starttransfer_time'] ?? null);
        } else {
            $internal = $this->runInternalRequest($url, auth()->id());
            $httpStatus = $internal['status'];
            $totalMs = $internal['total_ms'];
            $sizeDownload = $internal['size_download'];
            $startTransferMs = $internal['total_ms'];
        }

        DB::table('page_load_tests')->insert([
            'mode' => $mode,
            'page_key' => $pageKey,
            'label' => $label ?? $url,
            'url' => $url,
            'http_status' => $httpStatus,
            'total_time_ms' => $totalMs,
            'namelookup_time_ms' => $this->toMs($info['namelookup_time'] ?? null),
            'connect_time_ms' => $this->toMs($info['connect_time'] ?? null),
            'appconnect_time_ms' => $this->toMs($info['appconnect_time'] ?? null),
            'pretransfer_time_ms' => $this->toMs($info['pretransfer_time'] ?? null),
            'starttransfer_time_ms' => $startTransferMs,
            'redirect_time_ms' => $this->toMs($info['redirect_time'] ?? null),
            'size_download' => $sizeDownload,
            'size_upload' => $info['size_upload'] ?? null,
            'speed_download' => $info['speed_download'] ?? null,
            'speed_upload' => $info['speed_upload'] ?? null,
            'primary_ip' => $info['primary_ip'] ?? null,
            'local_ip' => $info['local_ip'] ?? null,
            'error_message' => $error ?: null,
            'tested_by' => auth()->id(),
            'created_at' => now(),
        ]);

        if ($error) {
            return $this->pageLoadTesterRedirect($request, 'Test saved, but request failed: ' . $error, 'error');
        }

        return $this->pageLoadTesterRedirect($request, 'Load test completed and saved.', 'success');
    }

    public function deletePageLoadTest($id)
    {
        DB::table('page_load_tests')->where('id', $id)->delete();
        return $this->pageLoadTesterRedirect(request(), 'Result deleted.', 'success');
    }

    public function clearPageLoadTests()
    {
        DB::table('page_load_tests')->delete();
        return $this->pageLoadTesterRedirect(request(), 'All results cleared.', 'success');
    }

    private function pageLoadTestPages(): array
    {
        return [
            'cases-index' => ['label' => 'Cases List', 'url' => url('/cases')],
            'operations-dashboard' => ['label' => 'Operations Dashboard', 'url' => url('/operations-dashboard')],
            'users-index' => ['label' => 'Users', 'url' => url('/users/index')],
            'materials-index' => ['label' => 'Materials', 'url' => url('/materials')],
            'job-types-index' => ['label' => 'Job Types', 'url' => url('/Job-type/index')],
            'devices-index' => ['label' => 'Devices', 'url' => url('/device/index')],
            'configuration' => ['label' => 'System Configuration', 'url' => url('/admin/configuration')],
        ];
    }

    private function normalizeTestUrl(string $input, Request $request): string
    {
        if (preg_match('/^https?:\\/\\//i', $input)) {
            return $input;
        }

        $base = rtrim(url('/'), '/');
        if (strpos($input, '/') === 0) {
            return $base . $input;
        }

        return $base . '/' . ltrim($input, '/');
    }

    private function buildCookieHeader(Request $request): string
    {
        $pairs = [];
        foreach ($request->cookies->all() as $name => $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $pairs[] = $name . '=' . urlencode((string) $value);
        }
        return implode('; ', $pairs);
    }

    private function appendTestTokenToUrl(string $url, string $token): string
    {
        $fragment = '';
        if (strpos($url, '#') !== false) {
            [$url, $fragment] = explode('#', $url, 2);
            $fragment = '#' . $fragment;
        }

        $separator = parse_url($url, PHP_URL_QUERY) ? '&' : '?';
        return $url . $separator . '__plt=' . urlencode($token) . $fragment;
    }

    private function runInternalRequest(string $url, ?int $userId): array
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '/';
        $query = parse_url($url, PHP_URL_QUERY);
        $pathWithQuery = $query ? $path . '?' . $query : $path;

        $internalRequest = Request::create($pathWithQuery, 'GET');
        if ($userId) {
            Auth::onceUsingId($userId);
        }

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $start = microtime(true);
        $response = $kernel->handle($internalRequest);
        $durationMs = (int) round((microtime(true) - $start) * 1000);
        $kernel->terminate($internalRequest, $response);

        $content = method_exists($response, 'getContent') ? $response->getContent() : null;
        $size = is_string($content) ? strlen($content) : null;

        return [
            'status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
            'total_ms' => $durationMs,
            'size_download' => $size,
        ];
    }

    private function restartSessionIfNeeded(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            try {
                Session::start();
            } catch (\Throwable $e) {
                // ignore session restart failures
            }
        }
    }

    private function pageLoadTesterRedirect(Request $request, string $message, string $type)
    {
        $base = $request->getSchemeAndHttpHost();
        $url = rtrim($base, '/') . '/tools/page-load-tester';
        return redirect()->away($url)->with($type, $message);
    }

    private function toMs($seconds): ?int
    {
        if ($seconds === null) {
            return null;
        }
        return (int) round($seconds * 1000);
    }
}
