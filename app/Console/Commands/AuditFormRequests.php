<?php

namespace App\Console\Commands;

use App\client;
use App\impressionType;
use App\JobType;
use App\material;
use App\materialJobtype;
use App\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Throwable;

class AuditFormRequests extends Command
{
    protected $signature = 'routes:audit-post-requests
        {--user=yazan : Username to load and submit as}
        {--json=storage/app/route-load-audit/post-requests-latest.json : Where to save the JSON report}';

    protected $description = 'POST requests audit for configured static forms and safe dummy submits.';

    public function handle(): int
    {
        $user = User::where('username', $this->option('user'))->first();
        if (!$user) {
            $this->error('User not found: ' . $this->option('user'));
            return self::FAILURE;
        }

        $results = [];
        foreach (config('form_request_audit.audits', []) as $key => $audit) {
            $results[] = $this->runAudit($key, $audit, $user);
        }

        $this->writeReport($results);
        $this->printTable($results);

        $failed = collect($results)->where('state', 'failed')->count();
        if ($failed > 0) {
            $this->error($failed . ' POST request audit(s) failed.');
            return self::FAILURE;
        }

        $this->info('No POST request audit failures found.');
        return self::SUCCESS;
    }

    private function runAudit(string $key, array $audit, User $user): array
    {
        $start = microtime(true);

        try {
            $pageUri = $this->uriForRoute($audit['page_route']);
            $submitUri = $this->uriForRoute($audit['submit_route']);
            $method = strtoupper($audit['method'] ?? 'POST');

            $formCheck = $this->checkFormContract($pageUri, $submitUri, $method, $audit, $user);
            if (!$formCheck['ok']) {
                return $this->result($key, $pageUri, $submitUri, null, 'failed', $formCheck['error'], $start);
            }

            $payload = $this->payload($audit['payload'] ?? null, $user);
            $response = $this->submitWithRollback($submitUri, $method, $payload, $user);
            $status = $response['status'];

            if (!in_array($status, $audit['expected_statuses'] ?? [200, 302], true)) {
                return $this->result($key, $pageUri, $submitUri, $status, 'failed', 'Unexpected response status.', $start);
            }

            if (($audit['fail_on_session_error'] ?? false) && $response['session_error']) {
                return $this->result($key, $pageUri, $submitUri, $status, 'failed', $response['session_error'], $start);
            }

            return $this->result($key, $pageUri, $submitUri, $status, 'ok', null, $start);
        } catch (Throwable $e) {
            return $this->result($key, null, null, 500, 'failed', get_class($e) . ': ' . $e->getMessage(), $start);
        }
    }

    private function checkFormContract(string $pageUri, string $submitUri, string $method, array $audit, User $user): array
    {
        $page = $this->dispatch($pageUri, 'GET', [], $user);
        if (($page['status'] ?? 500) >= 500) {
            return ['ok' => false, 'error' => 'Form page returned ' . $page['status'] . '.'];
        }

        $forms = $this->formsFromHtml((string) $page['content']);
        $expectedPath = parse_url($submitUri, PHP_URL_PATH) ?: $submitUri;
        $form = collect($forms)->first(function ($form) use ($expectedPath, $method) {
            $actionPath = parse_url($form['action'], PHP_URL_PATH) ?: $form['action'];
            return strtoupper($form['method']) === $method && $actionPath === $expectedPath;
        });

        if (!$form) {
            return ['ok' => false, 'error' => 'Matching form action/method not found.'];
        }

        $missing = array_values(array_diff($audit['expected_fields'] ?? [], $form['fields']));
        if ($missing) {
            return ['ok' => false, 'error' => 'Missing form fields: ' . implode(', ', $missing)];
        }

        return ['ok' => true, 'error' => null];
    }

    private function submitWithRollback(string $uri, string $method, array $payload, User $user): array
    {
        DB::beginTransaction();
        DB::beginTransaction();

        try {
            return $this->dispatch($uri, $method, $payload, $user);
        } finally {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }
    }

    private function dispatch(string $uri, string $method, array $payload, User $user): array
    {
        $session = app('session')->driver();
        if (!$session->isStarted()) {
            $session->start();
        }

        $payload['_token'] = $session->token();
        $request = Request::create($uri, $method, $payload);
        $request->setLaravelSession($session);

        Auth::onceUsingId($user->id);

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);
        $response = $kernel->handle($request);
        $kernel->terminate($request, $response);

        $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null;
        $content = method_exists($response, 'getContent') ? $response->getContent() : null;
        $sessionError = $session->get('error');
        $session->forget(['error', 'success']);
        Auth::logout();

        return [
            'status' => $status,
            'content' => $content,
            'session_error' => $sessionError,
        ];
    }

    private function formsFromHtml(string $html): array
    {
        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        $forms = [];

        foreach ($xpath->query('//form') as $form) {
            $fields = [];
            foreach ($xpath->query('.//*[@name]', $form) as $field) {
                $fields[] = $field->getAttribute('name');
            }

            $forms[] = [
                'action' => $form->getAttribute('action'),
                'method' => $form->getAttribute('method') ?: 'GET',
                'fields' => array_values(array_unique($fields)),
            ];
        }

        libxml_clear_errors();

        return $forms;
    }

    private function payload(?string $name, User $user): array
    {
        switch ($name) {
            case 'create_case':
                return $this->createCasePayload($user);
            case 'tools_create_case':
                return $this->toolsCreateCasePayload();
            case 'create_doctor':
                return $this->prefixedPayload([
                    'dentist_name' => 'Audit Doctor',
                    'phone_number' => '0790000000',
                    'address' => 'Audit Address',
                    'clinic_phone' => '065000000',
                    'doc_password' => 'audit123',
                    'clinic_password' => 'audit123',
                    'repeat' => [
                        [
                            'material' => optional(material::first())->id,
                            'discount' => 0,
                            'type' => 1,
                        ],
                    ],
                ]);
            case 'create_material':
                return $this->createMaterialPayload();
            case 'create_job_type':
                return $this->prefixedPayload([
                    'jobtype_name' => 'Audit Job Type',
                    'teeth_or_jaw' => 0,
                ]);
            case 'create_lab':
                return $this->prefixedPayload([
                    'lab_name' => 'Audit Lab',
                    'lab_phone' => '065000000',
                    'lab_address' => 'Audit Address',
                ]);
            case 'create_implant':
                return $this->prefixedPayload([
                    'implant_name' => 'Audit Implant',
                ]);
            case 'create_abutment':
                return $this->prefixedPayload([
                    'abutment_name' => 'Audit Abutment',
                ]);
            case 'create_tag':
                return $this->prefixedPayload([
                    'tag_text' => 'Audit Tag',
                    'tag_color' => '#0f766e',
                    'tag_icon' => 'fa fa-check',
                ]);
            case 'create_failure_cause':
                return $this->prefixedPayload([
                    'cause_text' => 'Audit Cause',
                ]);
            case 'create_device':
                return $this->prefixedPayload([
                    'device_name' => 'Audit Device',
                    'device_type' => 3,
                ]);
            case 'create_user':
                return $this->createUserPayload();
            case 'create_type':
                return $this->createTypePayload();
        }

        return [];
    }

    private function createCasePayload(User $user): array
    {
        $doctor = client::where('active', '!=', 0)->first() ?? client::first();
        $material = material::whereNotNull('price')->first() ?? material::first();
        $jobType = JobType::first();
        $impression = impressionType::first();

        if (!$doctor || !$material || !$jobType || !$impression) {
            throw new \RuntimeException('Missing doctor, material, job type, or impression type dummy data.');
        }

        return [
            'doctor' => $doctor->id,
            'patient_name' => 'Route Audit Dummy',
            'caseId1' => $user->id . '_' . now()->format('Y'),
            'caseId2' => now()->format('m'),
            'caseId3' => now()->format('d'),
            'caseId4' => '9999',
            'delivery_date' => now()->addDay()->format('d M, Y h:i a'),
            'impression_type' => $impression->id,
            'repeat' => [
                [
                    'units' => '11',
                    'jobType' => $jobType->id,
                    'material_id' => $material->id,
                    'color' => 'A1',
                    'style' => 'Single',
                ],
            ],
        ];
    }

    private function toolsCreateCasePayload(): array
    {
        $relation = materialJobtype::first();
        if (!$relation) {
            throw new \RuntimeException('Missing job type to material relation dummy data.');
        }

        $jobType = JobType::find($relation->jobtype_id);
        $jawTeeth = ((int) optional($jobType)->teeth_or_jaw) === 1 ? 'jaw' : 'teeth';

        return [
            'stage' => 1,
            'phase' => null,
            'amount' => 1,
            'jaw_teeth' => $jawTeeth,
            'jaw_selection' => 'upper',
            'units' => '11',
            'job_type_id' => $relation->jobtype_id,
            'material_id' => $relation->material_id,
        ];
    }

    private function createMaterialPayload(): array
    {
        $jobType = JobType::first();
        if (!$jobType) {
            throw new \RuntimeException('Missing job type dummy data.');
        }

        return $this->prefixedPayload([
            'mat_name' => 'Audit Material',
            'price' => 1,
            'jobTypes' => [$jobType->id],
            'count_as_unit' => 1,
            'design' => 1,
            'manufacturing' => 0,
            'furnace' => 0,
            'finishing' => 1,
            'qc' => 1,
            'delivery' => 1,
        ]);
    }

    private function createUserPayload(): array
    {
        $suffix = now()->format('YmdHis');

        return [
            'first_name' => 'Audit',
            'last_name' => 'User',
            'name_initials' => 'AU',
            'phone' => '0790000000',
            'email' => 'audit-' . $suffix . '@example.test',
            'username' => 'audit_' . $suffix,
            'password' => 'audit123',
            'password_confirmation' => 'audit123',
            'is_admin' => 'on',
        ];
    }

    private function createTypePayload(): array
    {
        $material = material::first();
        if (!$material) {
            throw new \RuntimeException('Missing material dummy data.');
        }

        return $this->prefixedPayload([
            'material_id' => $material->id,
            'name' => 'Audit Type',
            'description' => 'Audit description',
            'is_enabled' => 1,
        ]);
    }

    private function prefixedPayload(array $payload): array
    {
        if (isset($payload['dentist_name'])) {
            $payload['dentist_name'] .= ' ' . now()->format('YmdHis');
        }
        foreach (['mat_name', 'jobtype_name', 'lab_name', 'implant_name', 'abutment_name', 'tag_text', 'cause_text', 'device_name', 'name'] as $key) {
            if (isset($payload[$key])) {
                $payload[$key] .= ' ' . now()->format('YmdHis');
            }
        }

        return $payload;
    }

    private function uriForRoute(string $routeName): string
    {
        if (!Route::has($routeName)) {
            throw new \RuntimeException('Route name not found: ' . $routeName);
        }

        return route($routeName, [], false);
    }

    private function result(string $key, ?string $pageUri, ?string $submitUri, ?int $status, string $state, ?string $error, float $start): array
    {
        return [
            'audit' => $key,
            'page_uri' => $pageUri,
            'submit_uri' => $submitUri,
            'status' => $status,
            'state' => $state,
            'ms' => (int) round((microtime(true) - $start) * 1000),
            'error' => $error,
        ];
    }

    private function writeReport(array $results): void
    {
        $path = base_path($this->option('json'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode([
            'created_at' => now()->toDateTimeString(),
            'user' => $this->option('user'),
            'results' => $results,
        ], JSON_PRETTY_PRINT));
    }

    private function printTable(array $results): void
    {
        $this->table(
            ['State', 'Status', 'ms', 'Audit', 'Page', 'Submit', 'Error'],
            collect($results)->map(function ($row) {
                return [
                    $row['state'],
                    $row['status'],
                    $row['ms'],
                    $row['audit'],
                    $row['page_uri'],
                    $row['submit_uri'],
                    $row['error'],
                ];
            })->all()
        );
    }
}
