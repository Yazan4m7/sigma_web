<?php

namespace App\Http\Controllers;
use App\abutment;
use App\abutmentDeliveryRecord;
use App\client;
use App\failureCause;
use App\failureLog;
use App\Http\Traits\helperTrait;
use App\implant;
use App\job;
use App\JobType;
use App\material;
use App\payment;
use App\sCase;
use App\Support\UserPermissionsCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use DB;
use Carbon\Carbon;


class ReportsController extends Controller
{
    use helperTrait;

    private function normalizedMultiSelectIds(Request $request, string $key, array $default = ['all']): array
    {
        $values = collect((array) $request->input($key, $default))
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->map(function ($value) {
                return $value === 'all' ? 'all' : (int) $value;
            })
            ->values()
            ->all();

        return $values === [] ? $default : $values;
    }

    public function implantsReport(Request $request){


        $clients = client::where('active', 1)->get();
        $totals = [];
        $totals2 = [];
        $clientLevelTotal = [];
        $labLevelTotal = [];
        // Ensure doctor selection persists cleanly and matches the dropdown option types.
        $selectedClients = collect((array) $request->input('doctor', ['all']))
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->map(function ($value) {
                return $value === 'all' ? 'all' : (int) $value;
            })
            ->values()
            ->all();

        if (empty($selectedClients)) {
            $selectedClients = ['all'];
        }
        $clients= $clients->keyBy('id');
        $perUnitTrigger = $request->get('perToggle', '1') == '1';
        $implants = implant::all();
        $allImplantsSelected=true;
        $abutments = abutment::all();
        $allAbutmentsSelected = true;



        if ($request->implantsInput && !in_array("all", (array)$request->implantsInput)){
            $selectedImplants =  implant::whereIn('id', (array)$request->implantsInput)->get();
            $allImplantsSelected=false;
        }
        else
        $selectedImplants =  $implants;

        if ($request->abutmentsInput && !in_array("all", (array)$request->abutmentsInput)){
            $selectedAbutments =  abutment::whereIn('id', (array)$request->abutmentsInput)->get();
            $allAbutmentsSelected=false;
        } else {
            $selectedAbutments = $abutments;
        }

        $from = $request->from ? Carbon::parse($request->from)->startOfDay()->format('Y-m-d H:i:s') : now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
        $to = $request->to ? Carbon::parse($request->to)->endOfDay()->format('Y-m-d H:i:s') : now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');

        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();

        $selectedMonths = [];
        for ($date = $start; $date->lte($end); $date->addMonth()) {
            $selectedMonths[] = $date->format('Y-m');
        }

        foreach($selectedMonths as $month)
            foreach($selectedAbutments as $abutment){
                $clientLevelTotal[$month][$abutment->id] = 0;
                $labLevelTotal[$month][$abutment->id] = 0;

            }

        foreach($clients as $client)
        {
            foreach($selectedAbutments as $abutment)
                $totals[$client->id][$abutment->id] = 0;
        }
        foreach($selectedAbutments as $abutment)
            $totals2[$abutment->id] = 0;

        // Calculate actual implants data from database
        $implantsIds = $selectedImplants->pluck('id')->toArray();

        foreach($selectedMonths as $month) {
            foreach($selectedAbutments as $abutment) {
                if (in_array("all", $selectedClients)) {
                    // Count for all clients
                    foreach($clients as $client) {
                        if ($perUnitTrigger) {
                            // Count units
                            $count = $client->numOfUnitsBy_abutment_implants($abutment->id, $implantsIds, $month, true);
                        } else {
                            // Count cases
                            $count = $client->numOfCasesBy_abutment_implants($abutment->id, $implantsIds, $month);
                        }
                        $totals[$client->id][$abutment->id] += $count;
                        $clientLevelTotal[$month][$abutment->id] += $count;
                        $labLevelTotal[$month][$abutment->id] += $count;
                    }
                } else {
                    // Count for selected clients only
                    foreach($selectedClients as $clientId) {
                        if (isset($clients[$clientId])) {
                            $client = $clients[$clientId];
                            if ($perUnitTrigger) {
                                // Count units
                                $count = $client->numOfUnitsBy_abutment_implants($abutment->id, $implantsIds, $month, true);
                            } else {
                                // Count cases
                                $count = $client->numOfCasesBy_abutment_implants($abutment->id, $implantsIds, $month);
                            }
                            $totals[$client->id][$abutment->id] += $count;
                            $clientLevelTotal[$month][$abutment->id] += $count;
                            $labLevelTotal[$month][$abutment->id] += $count;
                        }
                    }
                }
            }
        }

        // Compute totals across all selected months/clients from the lab-level aggregation
        $totals2 = [];
        foreach ($selectedAbutments as $abutment) {
            $sum = 0;
            foreach ($selectedMonths as $month) {
                $sum += $labLevelTotal[$month][$abutment->id] ?? 0;
            }
            $totals2[$abutment->id] = $sum;
        }

        $selectedMonths=array_reverse($selectedMonths);

        return view('reports.implants',compact('totals','totals2','clients','selectedClients',
            'implants','selectedImplants','allImplantsSelected',
            'abutments', 'selectedAbutments', 'allAbutmentsSelected',
            'selectedMonths','clientLevelTotal',
            'perUnitTrigger','labLevelTotal','from','to'));


    }
    public function QCReport(Request $request)
    {
        $clients = client::where('active', 1)->get();
        $selectedClients = $this->normalizedMultiSelectIds($request, 'doctor');
        $clients = $clients->keyBy('id');
        $allFailureCauses = failureCause::all();
        $allCausesSelected = true;
        $typesSelected = array();
        $validFailureTypes = [0, 1, 2, 3];
        $selectedCauses = $request->causesInput ?? ["all"];

        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->endOfMonth()->format('Y-m-d');
        $fromDateTime = Carbon::parse($from)->startOfDay()->format('Y-m-d H:i:s');
        $toDateTime = Carbon::parse($to)->endOfDay()->format('Y-m-d H:i:s');

        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();

        $selectedMonths = [];
        for ($date = $start; $date->lte($end); $date->addMonth()) {
            $selectedMonths[] = $date->format('Y-m');
        }
        // reverse array to make new to old
        $selectedMonths=array_reverse($selectedMonths);


        // get failure logs
        $failureLogs = array();
        $selectedFailureTypes = collect((array) $request->failureTypeInput)
            ->filter(function ($value) {
                return $value !== null && $value !== '' && $value !== 'all';
            })
            ->map(function ($value) {
                return is_numeric($value) ? (int) $value : $value;
            })
            ->filter(function ($value) use ($validFailureTypes) {
                return in_array($value, $validFailureTypes, true);
            })
            ->values()
            ->all();

        $query = failureLog::query()
            ->with(['case.client'])
            ->whereIn('failure_type', $validFailureTypes);

        // Filter the logs by user inputs
        if ($selectedFailureTypes !== []) {
            $query->whereIn('failure_type', $selectedFailureTypes);
            $typesSelected = $selectedFailureTypes;

            }
        if (isset($request->causesInput) && !in_array('all', (array)$request->causesInput)) {
            $query->whereIn('cause_id', (array)$request->causesInput);
            $selectedFailureCauses = failureCause::whereIn("id", (array)$request->causesInput)->get();
            $allCausesSelected=false;
            }
            else
            $selectedFailureCauses = $allFailureCauses;


            // Get the FILTERED RESULTS (match repeats report date basis)
            $results = $query->whereHas('case', function ($q) use ($fromDateTime, $toDateTime): void {
                $q->whereBetween('actual_delivery_date', [$fromDateTime, $toDateTime]);
            })->get();
             //dd($results);
             // SEPARATE THEM BY MONTH
            foreach($selectedMonths as $month){
                $failureLogs[$month] = $results->filter(function ($log) use ($month): bool {
                    $case = $log->case;
                    if (!$case || !$case->actual_delivery_date) {
                        return false;
                    }
                    return Carbon::parse($case->actual_delivery_date)->format('Y-m') === $month;
                });

                // Total cases and units of every month
                $amountOfCases[$month] = $failureLogs[$month]->groupBy("case_id")->pluck("case_id")->count();
                //if(count($amountOfCases[$month])!= 0)
                //dd($amountOfCases[$month]);
                $amountOfUnits[$month] = 0;
                $labLevelTotal[$month] = 0;
            }


            //Get Total Counts Of All failed Units
                $amountOfUnitsFailed = 0;
            $failedJobs = job::whereIn('case_id', $results->pluck('case_id')->toArray())
                ->where(function ($q): void {
                    $q->where("is_rejection", 1)
                        ->orWhere("is_repeat", 1)
                        ->orWhere("is_modification", 1)
                        ->orWhere("is_redo", 1);
                })
                ->get();
            //dd($failedJobs);
            foreach($failedJobs as $job)
                $amountOfUnitsFailed+= count(explode(',',$job->unit_num));

        return view('reports.QC',compact('clients',
            'failureLogs','selectedMonths','selectedClients', 'allCausesSelected',
            'allFailureCauses','selectedFailureCauses','typesSelected','amountOfCases','labLevelTotal','amountOfUnitsFailed','from','to'));
    }
    public function jobTypeReport(Request $request)
    {

        $allJobTypesSelected = true;

        $clients = client::where('active', 1)->get();
        $selectedClients = $this->normalizedMultiSelectIds($request, 'doctor');
        $clients= $clients->keyBy('id');
        $perUnitTrigger = $request->get('perToggle', 0) == 1;
        $jobTypes = JobType::all();
        $requestedJobTypes = collect((array) $request->input('jobTypesInput', []))
            ->filter(function ($value) {
                return $value !== null && $value !== '';
            })
            ->map(function ($value) {
                return $value === 'all' ? 'all' : (int) $value;
            })
            ->values()
            ->all();

        if ($request->has('jobTypesInput') && in_array('all', $requestedJobTypes, true)) {
            $selectedJobTypes = $jobTypes;
        } elseif ($request->has('jobTypesInput') && $requestedJobTypes !== []) {
            $selectedJobTypes = JobType::whereIn('id', array_filter($requestedJobTypes, 'is_int'))->get();
            $allJobTypesSelected = false;
        } else {
            $selectedJobTypes = JobType::whereIn('id', [1, 2, 3, 4])->get();
            $allJobTypesSelected = false;
        }

        $from = $request->from ? Carbon::parse($request->from)->startOfDay()->format('Y-m-d H:i:s') : now()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
        $to = $request->to ? Carbon::parse($request->to)->endOfDay()->format('Y-m-d H:i:s') : now()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');

        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();

        $selectedMonths = [];
        for ($date = $start; $date->lte($end); $date->addMonth()) {
            $selectedMonths[] = $date->format('Y-m');
        }
        // Initialize arrays
        foreach($selectedMonths as $month)
            foreach($selectedJobTypes as $jobType){
            $clientLevelTotal[$month][$jobType->id] = 0;
            $labLevelTotal[$month][$jobType->id] = 0;
        }
        foreach($clients as $client)
        {
            foreach($selectedJobTypes as $type)
                $totals[$client->id][$type->id] = 0;
        }
        foreach($selectedJobTypes as $type)
            $totals2[$type->id] = 0;
        $totals2[99] = 0;

        // Let the view calculate the actual totals using client methods
        // This prevents double counting issues
        $selectedMonths=array_reverse($selectedMonths);

        return view('reports.jobTypes',compact('clients','totals','totals2',
            'jobTypes','selectedJobTypes','allJobTypesSelected','labLevelTotal',
         'selectedClients','selectedMonths','clientLevelTotal',
            'allJobTypesSelected','perUnitTrigger','from','to'));

    }
    public function numOfUnitsReport(Request $request)
    {

        $clients = client::where('active', 1)->get();
        $materials = material::all();
        $selectedClients = $this->normalizedMultiSelectIds($request, 'doctor');
        $allMaterialsSelected = false;
        // Handle 'all' materials selection
        if (isset($request->material) && in_array('all', (array)$request->material)) {
            $selectedMaterials = $materials->pluck('id')->toArray();
            $allMaterialsSelected = true;
        } else {
            // Default to first 4 materials on initial load (when no material filter is set)
            $selectedMaterials = $request->material
                ? collect((array) $request->material)->map(function ($value) { return (int) $value; })->values()->all()
                : $materials->take(4)->pluck('id')->toArray();
        }

        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->endOfMonth()->format('Y-m-d');

        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();

        $selectedMonths = [];
        for ($date = $start; $date->lte($end); $date->addMonth()) {
            $selectedMonths[] = $date->format('Y-m');
        }
       // dd($selectedMonths);

        /*
         * SelectedMonths is yyyy-mm or an array of yyyyy-mm
         */


        foreach($selectedMonths as $month)
            {
                foreach($selectedMaterials as $matId)
                $totalsArray[$month][$matId] = 0;

            // for totals column
            $totalsArray[$month][99] = 0;
            }
        // Initialize and populate totals arrays with actual data
        foreach($clients as $client)
        {
            foreach($selectedMaterials as $matId) {
                $totals[$client->id][$matId] = 0;
                // Calculate total across all selected months for this client and material
                foreach($selectedMonths as $month) {
                    $totals[$client->id][$matId] += $client->numOfUnitsByMaterial($matId, $month);
                }
            }
        }
        foreach($selectedMaterials as $matId)
            $totals2[$matId] = 0;
        $totals2[99] = 0;

        $selectedMonths=array_reverse($selectedMonths);

        return view('reports.numOfUnits',compact('clients','totals','totals2',
            'materials','selectedMaterials','selectedClients','selectedMonths','totalsArray','from','to','allMaterialsSelected'));
    }
    public function repeatsReport(Request $request)
    {
        $clients = client::where('active', 1)->get();
        $materials = material::all();
        $selectedClients = $this->normalizedMultiSelectIds($request, 'doctor');
        $allFailureTypes = [0 => "Rejection",1 => "Repeat", 2 => "Modification" , 3=> "Redo", 4=>"Successful"];
        $selectedFailureTypes = array_keys($allFailureTypes);
        $allFailureTypesSelected = true;
        $clientsWithFailures = array();

        if(isset($request->failureTypeInput) && !in_array('all', (array)$request->failureTypeInput)) {
            $selectedFailureTypes = collect((array) $request->failureTypeInput)
                ->filter(function ($value) {
                    return $value !== null && $value !== '' && $value !== 'all';
                })
                ->map(function ($value) {
                    return (int) $value;
                })
                ->values()
                ->all();
            $allFailureTypesSelected = false;
        }
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->endOfMonth()->format('Y-m-d');

        $start = Carbon::parse($from)->startOfMonth();
        $end = Carbon::parse($to)->endOfMonth();

        $selectedMonths = [];
        for ($date = $start; $date->lte($end); $date->addMonth()) {
            $selectedMonths[] = $date->format('Y-m');
        }

        // reverse array to make new to old
        $selectedMonths=array_reverse($selectedMonths);

        // Handle perUnitTrigger: 1 = units, 0 or null = cases, default to cases (false)
        $perUnitTrigger = $request->get('perToggle', '0') == '1';

        // Handle countOrPercentage: 1 = count, 0 or null = percentage, default to count (true)
        $countOrPercentage = $request->get('countOrPercentageToggle', '1') == '1';

        return view('reports.repeats',compact('clients',
            'materials','selectedMonths','selectedClients','selectedFailureTypes',
            'clientsWithFailures','perUnitTrigger','countOrPercentage','allFailureTypesSelected','allFailureTypes','from','to'));
    }
    public function homeScreen(){

         if((int) Auth()->user()->is_admin === 1)
            return $this->adminHomeScreen();

        $permissions = UserPermissionsCache::get((int) Auth()->id());

         if($permissions->contains('permission_id', 123))
            return $this->adminHomeScreen();

         return redirect('/operations-dashboard');

    }
    public function adminHomeScreen(){
        $last7DaysLabels = $this->getLastNDays(7,'Y-m-d');
        $last30DaysLabels = $this->getLastNDays(30,'Y-m-d');
        $start30 = Carbon::parse($last30DaysLabels[0])->startOfDay();
        $end30 = Carbon::parse($last30DaysLabels[count($last30DaysLabels) - 1])->endOfDay();
        $todayLabel = $last7DaysLabels[count($last7DaysLabels) - 1];

        $completedCasesLast30 = sCase::query()
            ->select(['id', 'actual_delivery_date'])
            ->with([
                'jobs' => function ($query) {
                    $query->select(['id', 'case_id', 'material_id', 'unit_num']);
                },
                'jobs.material:id,count_as_unit',
                'invoice:id,case_id,amount',
            ])
            ->whereNotNull('actual_delivery_date')
            ->whereBetween('actual_delivery_date', [$start30, $end30])
            ->get();

        $completedBuckets = [];
        foreach ($last30DaysLabels as $day) {
            $completedBuckets[$day] = [
                'cases' => collect(),
                'case_count' => 0,
                'unit_count' => 0,
                'sales' => 0.0,
            ];
        }

        foreach ($completedCasesLast30 as $case) {
            $day = substr((string) $case->getRawOriginal('actual_delivery_date'), 0, 10);

            if (!isset($completedBuckets[$day])) {
                continue;
            }

            $completedBuckets[$day]['cases']->push($case);
            $completedBuckets[$day]['case_count']++;
            $completedBuckets[$day]['sales'] += (float) optional($case->invoice)->amount;

            foreach ($case->jobs as $job) {
                if (!$job->material || (int) $job->material->count_as_unit !== 1) {
                    continue;
                }

                $completedBuckets[$day]['unit_count'] += count(explode(',', (string) $job->unit_num));
            }
        }

        $compCasesObjectsIn30Days = [];
        $compCasesCount30Days = [];
        $compUnitsCount30Days = [];
        $sales30Days = [];

        foreach ($last30DaysLabels as $day) {
            $bucket = $completedBuckets[$day];

            $compCasesObjectsIn30Days[] = $bucket['cases']->values();
            $compCasesCount30Days[] = $bucket['case_count'];
            $compUnitsCount30Days[] = $bucket['unit_count'];
            $sales30Days[] = $bucket['sales'];
        }

        $collectionsByDay = payment::query()
            ->selectRaw('DATE(created_at) as payment_day, SUM(amount) as total_amount')
            ->whereBetween('created_at', [$start30, $end30])
            ->groupBy('payment_day')
            ->pluck('total_amount', 'payment_day');

        $collectionsInLast30Days = [];
        foreach ($last30DaysLabels as $day) {
            $collectionsInLast30Days[] = (float) ($collectionsByDay[$day] ?? 0);
        }

        $compCasesObjectsIn7Days = [
            $completedBuckets[$last7DaysLabels[6]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[5]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[4]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[3]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[2]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[1]]['cases']->values(),
            $completedBuckets[$last7DaysLabels[0]]['cases']->values(),
        ];

        $compUnitsCount7Days = [
            $completedBuckets[$last7DaysLabels[6]]['unit_count'],
            $completedBuckets[$last7DaysLabels[5]]['unit_count'],
            $completedBuckets[$last7DaysLabels[4]]['unit_count'],
            $completedBuckets[$last7DaysLabels[3]]['unit_count'],
            $completedBuckets[$last7DaysLabels[2]]['unit_count'],
            $completedBuckets[$last7DaysLabels[1]]['unit_count'],
            $completedBuckets[$last7DaysLabels[0]]['unit_count'],
        ];

        // *** COMPLETED CASES COUNT IN THE LAST 7 DAYS :: *** //
        $compCasesCount7Days = [];
        foreach($compCasesObjectsIn7Days as $bunchOfCases) {
            $compCasesCount7Days[] = count($bunchOfCases);
        }

        // **  Doughnut Chart Counts ** //
        $waitingJobsToday = $this->getUnitsCountOfJobsObjects(
            job::query()
                ->select(['id', 'material_id', 'unit_num'])
                ->with('material:id,count_as_unit')
                ->whereNull('assignee')
                ->where('stage','!=',-1)
                ->get()
        );

        $CompletedJobsToday = $compUnitsCount7Days[6];
        $ActiveJobsToday = $this->getUnitsCountOfJobsObjects(
            job::query()
                ->select(['id', 'material_id', 'unit_num'])
                ->with('material:id,count_as_unit')
                ->whereNotNull('assignee')
                ->where('stage','!=',-1)
                ->get()
        );

        $DeliveriesToday = sCase::query()
            ->select(['id', 'doctor_id', 'patient_name', 'initial_delivery_date', 'delivered_to_client'])
            ->with([
                'client:id,name',
                'jobs' => function ($query) {
                    $query->select(['id', 'case_id', 'stage', 'assignee', 'delivery_accepted']);
                },
                'jobs.assignedTo:id,name_initials,first_name',
            ])
            ->whereDate('initial_delivery_date', $todayLabel)
            ->where('delivered_to_client',0)
            ->orderBy('initial_delivery_date')
            ->get();

        $DeliveriesToday->each(function (sCase $case) {
            $statusMeta = $this->buildDashboardDeliveryStatusMeta($case);
            $deliveryDate = Carbon::parse($case->getRawOriginal('initial_delivery_date'));

            $case->setAttribute('dashboard_delivery_time', $deliveryDate->format('g:i a'));
            $case->setAttribute('dashboard_delivery_date_iso', $deliveryDate->format('Y-m-d\TH:i:s'));
            $case->setAttribute('dashboard_status_text', $statusMeta['text']);
            $case->setAttribute('dashboard_status_class', $statusMeta['class']);
        });

        $paymentsReceivedToday = payment::query()
            ->select([
                'id',
                'doctor_id',
                'collector',
                'received_by',
                'amount',
                'created_at',
                'recieved_on',
                'notes',
                'additional_notes',
            ])
            ->with([
                'client:id,name',
                'collectorUserRecord:id,name_initials,first_name,last_name',
                'receivedBy:id,name_initials,first_name,last_name',
            ])
            ->whereDate('created_at', $todayLabel)
            ->orderBy('created_at')
            ->get();

        $labelToLookFor = substr($last30DaysLabels[29],0,8) . "01";
        $key = array_search($labelToLookFor, $last30DaysLabels);
        $last30DaysLabels[$key] = "** ".  $last30DaysLabels[$key] . " **";
        $last7DaysChartLabels = $this->formatDashboardChartDateLabels($last7DaysLabels);
        $last30DaysChartLabels = $this->formatDashboardChartDateLabels($last30DaysLabels);
//        dd($labelToLookFor);
        $compCasesCount7Days= array_reverse($compCasesCount7Days);
        return view('dashboard',compact('compUnitsCount7Days','compCasesCount7Days',
            'waitingJobsToday','CompletedJobsToday','ActiveJobsToday','DeliveriesToday',
            'paymentsReceivedToday','last7DaysLabels','compCasesObjectsIn30Days','compUnitsCount30Days',
            'collectionsInLast30Days','last30DaysLabels','last7DaysChartLabels','last30DaysChartLabels',
            'compCasesCount30Days','sales30Days'));
    }

    private function formatDashboardChartDateLabels(array $labels): array
    {
        return array_map(function ($label) {
            $date = trim(str_replace('*', '', $label));

            return Carbon::parse($date)->format('j, M');
        }, $labels);
    }

    private function buildDashboardDeliveryStatusMeta(sCase $case): array
    {
        $rawStatus = trim((string) $case->status());

        $stageText = $rawStatus;
        if (str_contains($rawStatus, 'Active in')) {
            $stageText = trim(substr($rawStatus, strlen('Active in')));
        } elseif (str_contains($rawStatus, 'In-Progress in')) {
            $stageText = trim(substr($rawStatus, strlen('In-Progress in')));
        } elseif (str_contains($rawStatus, 'Active')) {
            $stageText = trim(substr($rawStatus, strlen('Active')));
        } elseif (str_contains($rawStatus, 'In-Progress')) {
            $stageText = trim(substr($rawStatus, strlen('In-Progress')));
        }

        $jobAtStage = $case->jobs->first(function ($job) use ($case, $stageText) {
            return $job->assignee !== null && trim($case->stageToText((string) $job->stage)) === $stageText;
        });

        if (!$jobAtStage) {
            $jobAtStage = $case->jobs->first(function ($job) {
                return $job->assignee !== null && (string) $job->stage !== '-1';
            });
        }

        $assigneeInitials = '';
        if ($jobAtStage && $jobAtStage->assignedTo) {
            $assigneeInitials = trim((string) ($jobAtStage->assignedTo->name_initials ?? $jobAtStage->assignedTo->first_name ?? ''));
        }

        if (in_array($stageText, ['In-Progress', 'Active', ''], true) && $jobAtStage) {
            $stageText = trim($case->stageToText((string) $jobAtStage->stage));
        }

        $formattedActiveStatus = $assigneeInitials !== ''
            ? (trim($stageText) . '/ ' . $assigneeInitials)
            : trim($stageText);

        if ($formattedActiveStatus === '') {
            $formattedActiveStatus = $rawStatus;
        }

        $waitingStage = $rawStatus;
        if (str_contains($rawStatus, 'Waiting in')) {
            $waitingStage = trim(substr($rawStatus, strlen('Waiting in')));
        } elseif (str_contains($rawStatus, 'Waiting')) {
            $waitingStage = trim(substr($rawStatus, strlen('Waiting')));
        }
        $waitingStage = trim($waitingStage) !== '' ? trim($waitingStage) : $rawStatus;

        $deliveryJob = $case->jobs->first(function ($job) {
            return (int) $job->stage === 8;
        });

        $deliveryAssigned = $deliveryJob
            && $deliveryJob->assignee !== null
            && $deliveryJob->delivery_accepted === null;

        if (str_contains($rawStatus, 'Completed')) {
            return [
                'text' => 'Completed',
                'class' => 'badge-success',
            ];
        }

        if (str_contains($rawStatus, 'Active') || str_contains($rawStatus, 'In-Progress')) {
            return [
                'text' => $formattedActiveStatus,
                'class' => 'badge-primary',
            ];
        }

        if (str_contains($rawStatus, 'Waiting')) {
            return [
                'text' => $waitingStage,
                'class' => 'badge-danger',
            ];
        }

        if ($deliveryAssigned && $deliveryJob && $deliveryJob->assignedTo) {
            $deliveryInitials = trim((string) ($deliveryJob->assignedTo->name_initials ?? $deliveryJob->assignedTo->first_name ?? ''));

            return [
                'text' => $deliveryInitials !== '' ? ('Delivery/ ' . $deliveryInitials) : 'Delivery',
                'class' => 'badge-warning',
            ];
        }

        return [
            'text' => $rawStatus,
            'class' => 'badge-warning',
        ];
    }
    public function handleEmployeeRedirection(){
        return redirect('/operations-dashboard');
    }
    public function blankPage(){

        return view('blank');
    }
    public function materialReport(Request $request){

        // Time Filtration
        if ($request->from && $request->to) {
            $from = $request->from ;
            $to = $request->to ;
        }
        else {
            $from = now()->startOfMonth()->toDateString();
            $to = now()->endOfMonth()->toDateString();
        }
        $cases = sCase::where(function ($cases) use($from,$to): void{
            $cases->whereBetween('actual_delivery_date', [ $from. ' 00:00', $to . ' 23:59'])
                // ->orWhereNull('actual_delivery_date')
            ;});

        // Client Filtration
        if ($request->doctor && !in_array("all", (array)$request->doctor)){
            $cases=$cases->whereIn('doctor_id', (array)$request->doctor);
        }

        $cases = $cases->orderByRaw('-`actual_delivery_date` ASC')->get();
        //$cases = $cases->filter->hasMaterial([1,20,2,3,4,6,7,9,10])->values();
        $totalAmount = 0;

        foreach($cases as $case) {
           // if (!isset($case->invoice))
             //   print_r($case->id);
            $totalAmount += isset($case->invoice) ? $case->invoice->amount : 0;
        }
        $selectedClients = $this->normalizedMultiSelectIds($request, 'doctor');
        $clients = client::where('active', 1)->without(['discounts','cases'])->get();
        return view ('reports.case-materials-report',compact('totalAmount','cases','from','to','selectedClients','clients'))->with('patientName',$request->patient_name);

    }

    // Master Reports functionality
    public function masterReport(Request $request)
    {
        // Set default date range to first of current month to today
        $from = $request->get('from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', Carbon::now()->format('Y-m-d'));

        // Add end-of-day time to 'to' date to include entire day (23:59:59)
        $toEndOfDay = Carbon::parse($to)->endOfDay()->format('Y-m-d H:i:s');

        // Get all data for filters - sorted by ID as requested
        $clients = client::where('active', 1)->orderBy('id')->get();
        $materials = material::orderBy('id')->get();
        $jobTypes = JobType::orderBy('id')->get();
        $failureCauses = failureCause::orderBy('id')->get();
        $abutments = abutment::orderBy('id')->get();
        $implants = implant::orderBy('id')->get();

        // Get users filtered by stage permissions
        // Permission IDs: 1=Design, 2=Milling, 3=3D Printing, 4=Sintering, 5=Pressing, 6=Finishing, 7=QC, 8=Delivery
        $employeesByStage = [
            'design' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 1);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'milling' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 2);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'printing' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 3);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'sintering' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 4);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'pressing' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 5);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'finishing' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 6);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'qc' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 7);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name']),
            'delivery' => \App\User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function($q) {
                    $q->where('permission_id', 8);
                })->orWhere('is_admin', 1);
            })->get(['id', 'first_name', 'last_name'])
        ];

        // Get devices by type for device filters
        // Device types: 2=Milling, 3=3D Printing, 4=Sintering, 5=Pressing
        $devicesByType = [
            'mill' => \App\device::where('type', 2)->orderBy('sorting_order')->get(),
            'print' => \App\device::where('type', 3)->orderBy('sorting_order')->get(),
            'sinter' => \App\device::where('type', 4)->orderBy('sorting_order')->get(),
            'press' => \App\device::where('type', 5)->orderBy('sorting_order')->get(),
            'other' => \App\device::whereNotIn('type', [2, 3, 4, 5])->orderBy('sorting_order')->get()
        ];

        // Always load cases based on current filters and date range
        // Use conditional date filtering: actual_delivery_date for completed cases, initial_delivery_date for others
        $query = sCase::with([
            'client',
            'jobs.material',
            'jobs.jobType',
            'jobs.device',
            'invoice',
            'jobs.millingBuild.device',
            'jobs.millingBuild.deviceUsed',
            'jobs.printingBuild.device',
            'jobs.printingBuild.deviceUsed',
            'jobs.sinteringBuild.device',
            'jobs.sinteringBuild.deviceUsed',
            'jobs.pressingBuild.device',
            'jobs.pressingBuild.deviceUsed',
            'caseLogs.user'
        ])
                      ->where(function($q) use ($from, $toEndOfDay) {
                          // Completed cases (all jobs at stage -1 AND actual_delivery_date not null) - filter by actual_delivery_date
                          $q->where(function($subQ) use ($from, $toEndOfDay) {
                              $subQ->whereNotNull('actual_delivery_date')
                                   ->whereBetween('actual_delivery_date', [$from, $toEndOfDay])
                                   ->whereDoesntHave('jobs', function($jobQ) {
                                       $jobQ->where('stage', '!=', -1);
                                   });
                          })
                          // OR not completed cases - filter by initial_delivery_date
                          ->orWhere(function($subQ) use ($from, $toEndOfDay) {
                              $subQ->where(function($innerQ) {
                                      // Cases without actual_delivery_date OR cases with jobs not at stage -1
                                      $innerQ->whereNull('actual_delivery_date')
                                             ->orWhereHas('jobs', function($jobQ) {
                                                 $jobQ->where('stage', '!=', -1);
                                             });
                                   })
                                   ->whereNotNull('initial_delivery_date')
                                   ->whereBetween('initial_delivery_date', [$from, $toEndOfDay]);
                          });
                      });

        // Apply basic filters
        if ($request->filled('doctor') && !in_array('all', (array)$request->doctor)) {
            $doctorIds = (array) $request->doctor;

            $query->whereHas('client', function($q) use ($doctorIds) {
                // Primary: filter by client id (matches UI doctor select)
                $q->whereIn('id', $doctorIds);
                // Legacy fallback: if rep_doctor exists in some environments, keep it OR-ed
                if (Schema::hasColumn('clients', 'rep_doctor')) {
                    $q->orWhereIn('rep_doctor', $doctorIds);
                }
            });
        }

        if ($request->filled('material') && !in_array('all', (array)$request->material)) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->whereIn('material_id', (array)$request->material);
            });
        }

        if ($request->filled('job_type') && !in_array('all', (array)$request->job_type)) {
            $query->whereHas('jobs', function($q) use ($request) {
                // Schema uses 'type' as the job type FK on jobs
                $q->whereIn('type', (array)$request->job_type);
            });
        }

        // Material Type filter (matches types.id from the UI select)
        if ($request->filled('material_type') && !in_array('all', (array)$request->material_type)) {
            $typeIds = array_values(array_filter((array)$request->material_type, function ($value) {
                return $value !== 'all' && $value !== null && $value !== '';
            }));

            if (!empty($typeIds)) {
                $query->whereHas('jobs', function($q) use ($typeIds) {
                    $q->whereIn('type_id', $typeIds);
                });
            }
        }

        // Failure Type filter
        if ($request->filled('failure_type') && !in_array('all', (array)$request->failure_type)) {
            $query->whereHas('failureLogs', function($q) use ($request) {
                $q->whereIn('cause_id', (array)$request->failure_type);
            });
        }

        // Abutments filter
        if ($request->filled('abutments')) {
            $selectedAbutmentIds = array_values(array_filter((array) $request->abutments, function ($value) {
                return $value !== null && $value !== '' && $value !== 'all';
            }));

            // jobs.abutment still uses legacy zero-based values for the standard four
            // abutments, while the report dropdown is built from abutments.id.
            $selectedAbutmentValues = array_values(array_unique(array_map(function ($value) {
                $stringValue = (string) $value;
                $legacyValueMap = [
                    '1' => '0',
                    '2' => '1',
                    '3' => '2',
                    '4' => '3',
                ];

                return $legacyValueMap[$stringValue] ?? $stringValue;
            }, $selectedAbutmentIds)));

            $allAbutmentIds = $abutments
                ->pluck('id')
                ->map(function ($id) {
                    return (string) $id;
                })
                ->values()
                ->all();

            $allAbutmentsSelectedIndividually = !empty($selectedAbutmentIds)
                && empty(array_diff($allAbutmentIds, array_map('strval', $selectedAbutmentIds)));

            if (!empty($selectedAbutmentValues) && !$allAbutmentsSelectedIndividually) {
                $query->where(function($caseQuery) use ($selectedAbutmentIds, $selectedAbutmentValues) {
                    $caseQuery->whereHas('abutmentsDeliveries', function($q) use ($selectedAbutmentIds) {
                        $q->whereIn('abutment_id', $selectedAbutmentIds);
                    })->orWhere(function($fallbackQuery) use ($selectedAbutmentValues) {
                        $fallbackQuery->whereDoesntHave('abutmentsDeliveries')
                            ->whereHas('jobs', function($q) use ($selectedAbutmentValues) {
                                $q->whereIn('abutment', $selectedAbutmentValues);
                            });
                    });
                });
            }
        }

        // Implants filter
        if ($request->filled('implants') && !in_array('all', (array)$request->implants)) {
            $selectedImplantIds = array_values(array_filter((array) $request->implants, function ($value) {
                return $value !== null && $value !== '' && $value !== 'all';
            }));

            if (!empty($selectedImplantIds)) {
                $query->where(function($caseQuery) use ($selectedImplantIds) {
                    $caseQuery->whereHas('abutmentsDeliveries', function($q) use ($selectedImplantIds) {
                        $q->whereIn('implant_id', $selectedImplantIds);
                    })->orWhere(function($fallbackQuery) use ($selectedImplantIds) {
                        $fallbackQuery->whereDoesntHave('abutmentsDeliveries')
                            ->whereHas('jobs', function($q) use ($selectedImplantIds) {
                                $q->whereIn('implant', $selectedImplantIds);
                            });
                    });
                });
            }
        }

        // Completion status filter (completed/in_progress; both selected => no filter)
        if ($request->filled('show_completed')) {
            $completionStatus = array_values(array_filter((array) $request->show_completed, function ($value) {
                return $value !== null && $value !== '' && $value !== 'all';
            }));
            $completionStatus = array_map('strval', $completionStatus);

            $hasCompleted = in_array('completed', $completionStatus, true);
            $hasInProgress = in_array('in_progress', $completionStatus, true);

            if ($hasCompleted && !$hasInProgress) {
                // Completed cases: all jobs at stage -1 AND actual_delivery_date not null
                $query->whereNotNull('actual_delivery_date')
                      ->whereDoesntHave('jobs', function($q) {
                          $q->where('stage', '!=', -1);
                      });
            } elseif ($hasInProgress && !$hasCompleted) {
                // In-progress cases: at least one job NOT at stage -1
                $query->whereHas('jobs', function($jobQ) {
                    $jobQ->where('stage', '!=', -1);
                });
            }
            // Both selected (or none) => no filter applied
        }

        // Workflow stage filter - specific stages only (1-8) or completed
        if ($request->filled('status') && !in_array('all', (array)$request->status)) {
            $statuses = (array)$request->status;

            // Check if 'completed' is in the status array
            if (in_array('completed', $statuses)) {
                // Completed cases: all jobs at stage -1 AND actual_delivery_date not null
                $query->whereNotNull('actual_delivery_date')
                      ->whereDoesntHave('jobs', function($q) {
                          $q->where('stage', '!=', -1);
                      });
            }

            // Numeric stages only (1-8)
            $stages = array_filter($statuses, function($status) {
                return is_numeric($status);
            });
            if (!empty($stages)) {
                // Cases that have at least one job in any of the selected stages
                $query->whereHas('jobs', function($q) use ($stages) {
                    $q->whereIn('stage', $stages);
                });
            }
        }

        // Invoice amount range filter
        if ($request->filled('amount_from') || $request->filled('amount_to')) {
            $query->whereHas('invoice', function($q) use ($request) {
                if ($request->filled('amount_from')) {
                    $q->where('amount', '>=', $request->amount_from);
                }
                if ($request->filled('amount_to')) {
                    $q->where('amount', '<=', $request->amount_to);
                }
            });
        }

        // Number of units range filter
        if ($request->filled('units_from') || $request->filled('units_to')) {
            $unitsFrom = $request->filled('units_from') ? $request->units_from : 0;
            $unitsTo = $request->filled('units_to') ? $request->units_to : 999999;

            $query->where(function($q) use ($unitsFrom, $unitsTo) {
                $q->whereHas('jobs', function($jobQuery) use ($unitsFrom, $unitsTo) {
                    $jobQuery->selectRaw('case_id')
                             ->groupBy('case_id')
                             ->havingRaw(
                                 "SUM(CASE
                                     WHEN unit_num IS NULL OR unit_num = '' THEN 1
                                     WHEN unit_num LIKE '%,%' THEN LENGTH(unit_num) - LENGTH(REPLACE(unit_num, ',', '')) + 1
                                     WHEN unit_num LIKE '% %' THEN LENGTH(unit_num) - LENGTH(REPLACE(unit_num, ' ', '')) + 1
                                     ELSE 1
                                 END) BETWEEN ? AND ?",
                                 [$unitsFrom, $unitsTo]
                             );
                });
            });
        }

        // Employee filters (filter by case logs based on stage)
        $employeeFilters = $request->input('employee_filters', $request->input('employees', []));
        if (!empty($employeeFilters)) {
            $stageMap = [
                'design' => 1,
                'milling' => 2,
                'printing' => 3,
                'sintering' => 4,
                'pressing' => 5,
                'finishing' => 6,
                'qc' => 7,
                'delivery' => 8
            ];

            $normalizedEmployeeFilters = collect($employeeFilters)
                ->map(function($filter) use ($stageMap) {
                    $stage = $filter['stage'] ?? null;
                    $employeeId = $filter['employee_id'] ?? $filter['employee'] ?? null;

                    if (!$stage || !$employeeId || !isset($stageMap[$stage])) {
                        return null;
                    }

                    return [
                        'stage' => $stageMap[$stage],
                        'employee_id' => (int) $employeeId,
                    ];
                })
                ->filter()
                ->values();

            if ($normalizedEmployeeFilters->isNotEmpty()) {
                // Employee filters are ANDed: case must match every selected stage/employee pair.
                foreach ($normalizedEmployeeFilters as $filter) {
                    $query->whereHas('caseLogs', function($q) use ($filter) {
                        $this->applyEmployeeFilterCondition(
                            $q,
                            $filter['stage'],
                            $filter['employee_id'],
                            'where'
                        );
                    });
                }
            }
        }

        // Device filters (devices are linked through builds, not directly on jobs)
        $deviceFilters = $request->input('device_filters', $request->input('devices', []));
        if (!empty($deviceFilters)) {
            $stageMap = [
                'design' => 'design',
                'milling' => 'mill',
                'printing' => 'print',
                'sintering' => 'sinter',
                'pressing' => 'press',
                'finishing' => 'finishing',
                'qc' => 'qc',
                'delivery' => 'delivery'
            ];

            $normalizedDeviceFilters = collect($deviceFilters)
                ->map(function($filter) use ($stageMap) {
                    $stage = $filter['stage'] ?? $filter['type'] ?? null;
                    $deviceId = $filter['device_id'] ?? $filter['device'] ?? null;

                    if (!$stage || !$deviceId) {
                        return null;
                    }

                    return [
                        'type' => $stageMap[$stage] ?? $stage,
                        'device_id' => (int) $deviceId,
                    ];
                })
                ->filter()
                ->values();

            if ($normalizedDeviceFilters->isNotEmpty()) {
                // Device filters are ANDed: case must match every selected stage/device pair.
                foreach ($normalizedDeviceFilters as $filter) {
                    $query->whereHas('jobs', function($q) use ($filter) {
                        $this->applyDeviceFilterCondition(
                            $q,
                            $filter['type'],
                            $filter['device_id'],
                            'where'
                        );
                    });
                }
            }
        }

        $cases = $query->orderBy('id', 'desc')->get();

        $cases->each(function (sCase $case) {
            $case->setAttribute('master_report_stage_devices', [
                2 => $this->resolveCaseStageDeviceNames($case, 'millingBuild', 2),
                3 => $this->resolveCaseStageDeviceNames($case, 'printingBuild', 3),
                4 => $this->resolveCaseStageDeviceNames($case, 'sinteringBuild', 4),
                5 => $this->resolveCaseStageDeviceNames($case, 'pressingBuild', 5),
            ]);
        });

        return view('reports.master-report', compact(
            'cases', 'from', 'to', 'clients', 'materials', 'jobTypes',
            'failureCauses', 'abutments', 'implants', 'employeesByStage', 'devicesByType'
        ));
    }

    private function resolveCaseStageDeviceNames(sCase $case, string $buildRelation, int $stageNumber): string
    {
        return $case->jobs
            ->map(function ($job) use ($buildRelation, $stageNumber) {
                $build = $job->{$buildRelation} ?? null;
                $deviceUsedName = optional(optional($build)->deviceUsed)->name;

                if ($stageNumber === 2) {
                    return $deviceUsedName;
                }

                return $deviceUsedName ?: optional(optional($build)->device)->name;
            })
            ->filter(function ($name) {
                return !empty($name);
            })
            ->unique()
            ->implode(', ');
    }

    private function applyEmployeeFilterCondition($query, int $stageNumber, int $employeeId, string $boolean = 'where')
    {
        $query->{$boolean}(function($matchQ) use ($stageNumber, $employeeId) {
            // Match only the latest furthest-progressed log inside the stage bucket
            // (e.g. 2.3 over 2.2/2.1, then newest created_at within that sub-stage).
            $matchQ->where('user_id', $employeeId)
                   ->whereRaw(
                       'case_logs.id = (
                            select cl2.id
                            from case_logs as cl2
                            where cl2.case_id = case_logs.case_id
                              and cl2.deleted_at is null
                              and cl2.stage >= ?
                              and cl2.stage < ?
                            order by cl2.stage desc,
                                     coalesce(cl2.is_completion, 0) desc,
                                     cl2.created_at desc,
                                     cl2.id desc
                            limit 1
                        )',
                       [$stageNumber, $stageNumber + 1]
                   );
        });
    }

    private function applyDeviceFilterCondition($query, string $deviceType, int $deviceId, string $boolean = 'where')
    {
        $query->{$boolean}(function($jobQ) use ($deviceType, $deviceId) {
            switch ($deviceType) {
                case 'mill': // Type 2 - Milling
                    $jobQ->where('device_id', $deviceId)
                         ->orWhereHas('millingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         });
                    break;
                case 'print': // Type 3 - 3D Printing
                    $jobQ->where('device_id', $deviceId)
                         ->orWhereHas('printingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         });
                    break;
                case 'sinter': // Type 4 - Sintering
                    $jobQ->where('device_id', $deviceId)
                         ->orWhereHas('sinteringBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where('device_used', $deviceId);
                         });
                    break;
                case 'press': // Type 5 - Pressing
                    $jobQ->where('device_id', $deviceId)
                         ->orWhereHas('pressingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         });
                    break;
                default:
                    $jobQ->where('device_id', $deviceId)
                         ->orWhereHas('millingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         })
                         ->orWhereHas('printingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         })
                         ->orWhereHas('sinteringBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where('device_used', $deviceId);
                         })
                         ->orWhereHas('pressingBuild', function($buildQ) use ($deviceId) {
                             $buildQ->where(function($q) use ($deviceId) {
                                 $q->where('device_id', $deviceId)
                                   ->orWhere('device_used', $deviceId);
                             });
                         });
                    break;
            }
        });
    }

    // API endpoint for dynamic material types loading
    public function getMaterialTypes(Request $request)
    {
        try {
            $materialIds = $request->input('material_ids', []);

            if (empty($materialIds)) {
                // Return all material types if no materials selected
                $types = \App\Type::orderBy('name')->get(['id', 'name']);
            } else {
                // Get material types for selected materials
                $types = \App\Type::whereHas('materials', function($query) use ($materialIds) {
                    $query->whereIn('materials.id', $materialIds);
                })->orderBy('name')->get(['id', 'name']);
            }

            return response()->json([
                'success' => true,
                'types' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading material types: ' . $e->getMessage()
            ], 500);
        }
    }
}
