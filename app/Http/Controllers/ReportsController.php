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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use DB;
use Carbon\Carbon;


class ReportsController extends Controller
{
    use helperTrait;

    public function implantsReport(Request $request){


        $clients = client::where('active', 1)->get();
        // Ensure $selectedClients is always an array
        $selectedClients = $request->doctor ? (is_array($request->doctor) ? $request->doctor : [$request->doctor]) : ["all"];
        $clients= $clients->keyBy('id');
        $perUnitTrigger= $request->perToggle ?  false : true;
        $implants = implant::all();
        $allImplantsSelected=true;



        if ($request->implantsInput && !in_array("all", (array)$request->implantsInput)){
            $selectedImplants =  implant::whereIn('id', (array)$request->implantsInput)->get();
            $allImplantsSelected=false;
        }
        else
        $selectedImplants =  $implants;

        $abutments = abutment::all();
        $allAbutmentsSelected=true;
        if ($request->abutmentsInput && !in_array("all", (array)$request->abutmentsInput)){
            $selectedAbutments =  abutment::whereIn('id', (array)$request->abutmentsInput)->get();
            $allAbutmentsSelected=false;
        }
        else
          $selectedAbutments =  $abutments;


        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

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
        $selectedClients = $request->doctor ?? ["all"];
        $clients = $clients->keyBy('id');
        $allFailureCauses = failureCause::all();
        $allCausesSelected = true;
        $typesSelected = array();
        $selectedCauses = $request->causesInput ?? ["all"];

        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

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
        $query = failureLog::query();

        // Filter the logs by user inputs
        if(isset($request->failureTypeInput) && !in_array('all', (array)$request->failureTypeInput)) {
            $query->whereIn('failure_type', (array)$request->failureTypeInput);
            $typesSelected = (array)$request->failureTypeInput;

            }
        if (isset($request->causesInput) && !in_array('all', (array)$request->causesInput)) {
            $query->whereIn('cause_id', (array)$request->causesInput);
            $selectedFailureCauses = failureCause::whereIn("id", (array)$request->causesInput)->get();
            $allCausesSelected=false;
            }
            else
            $selectedFailureCauses = $allFailureCauses;


            // Get the FILTERED RESULTS
            $results = $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])->get();
             //dd($results);
             // SEPARATE THEM BY MONTH
            foreach($selectedMonths as $month){
                $failureLogs[$month] = $results->whereBetween('created_at', [$month . '-01 00:00:00', $month . '-31 23:59:59']);

                // Total cases and units of every month
                $amountOfCases[$month] = $failureLogs[$month]->groupBy("case_id")->pluck("case_id")->count();
                //if(count($amountOfCases[$month])!= 0)
                //dd($amountOfCases[$month]);
                $amountOfUnits[$month] = 0;
                $labLevelTotal[$month] = 0;
            }


            //Get Total Counts Of All failed Units
                $amountOfUnitsFailed = 0;
            $failedJobs = job::whereIn('case_id' , $results->pluck('case_id')->toArray())->where("is_rejection", 1)->orWhere("is_repeat",1)->orWhere("is_modification",1)->orWhere("is_redo",1)->get() ;
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
        $selectedClients =  $request->doctor ?? ["all"];
        $clients= $clients->keyBy('id');
        $perUnitTrigger = $request->get('perToggle', 0) == 1;
        $jobTypes = JobType::all();
        if ($request->jobTypesInput && !in_array("all", (array)$request->jobTypesInput)){
        $selectedJobTypes =  JobType::whereIn('id', (array)$request->jobTypesInput)->get();
            $allJobTypesSelected=false;
        }
        else{
        $selectedJobTypes =  JobType::whereIn('id',[1,2,3,4])->get();
            $allJobTypesSelected=false;}

        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

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
        $selectedClients =  $request->doctor ?? ["all"];
        // Handle 'all' materials selection
        if (isset($request->material) && in_array('all', (array)$request->material)) {
            $selectedMaterials = $materials->pluck('id')->toArray();
        } else {
            // Default to first 4 materials on initial load (when no material filter is set)
            $selectedMaterials = $request->material ? (is_array($request->material) ? $request->material : [$request->material]) : $materials->take(4)->pluck('id')->toArray();
        }

        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

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
            'materials','selectedMaterials','selectedClients','selectedMonths','totalsArray','from','to'));
    }
    public function repeatsReport(Request $request)
    {
        $clients = client::where('active', 1)->get();
        $materials = material::all();
        $selectedClients =  $request->doctor ?? ["all"];
        $allFailureTypes = [0 => "Rejection",1 => "Repeat", 2 => "Modification" , 3=> "Redo", 4=>"Successful"];
        $selectedFailureTypes =  [0 => "Rejection",1 => "Repeat", 2 => "Modification" , 3=> "Redo", 4=>"Successful"];
        $allFailureTypesSelected = true;
        $clientsWithFailures = array();

        if(isset($request->failureTypeInput) && !in_array('all', (array)$request->failureTypeInput)) {
            $selectedFailureTypes = (array)$request->failureTypeInput;
            $allFailureTypesSelected = false;
        }
        $from = $request->from ?? now()->subMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

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


        $permissions = Cache::get('user'.Auth()->user()->id);

         if(Auth()->user()->is_admin == 1 ||($permissions && $permissions->contains('permission_id', 123)))
            return $this->adminHomeScreen();
         else
             return redirect('/operations-dashboard');

    }
    public function adminHomeScreen(){



        $last7DaysLabels = $this->getLastNDays(7,'Y-m-d');
        $last30DaysLabels = $this->getLastNDays(30,'Y-m-d');

        $compCasesObjectsIn30Days = $this->getCompletedCasesInLastNDays($last30DaysLabels);
        $collectionsInLast30Days = $this->getCollectionsInLastNDays($last30DaysLabels);
        $compCasesObjectsIn7Days = [
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[6] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[5] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[4] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[3] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[2] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[1] . '%')->get(),
            sCase::where('actual_delivery_date', 'like', '%' . $last7DaysLabels[0] . '%')->get(),
            ];


        // *** COMPLETED UNITS COUNT IN THE LAST 7 DAYS :: *** //
        // Index 6 of $compUnitsCount7Days and 0 of $compCasesObjectsIn7Days is today.
        $compUnitsCount7Days = [
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[6]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[5]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[4]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[3]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[2]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[1]),
            $this->getUnitsCountOfCasesObjects($compCasesObjectsIn7Days[0]),
        ];

        // *** COMPLETED CASES COUNT IN THE LAST 7 DAYS :: *** //
        $compCasesCount7Days = [];
        $compCasesCount30Days = [];
        $compUnitsCount30Days = [];
        $sales30Days = [];
        //dd($compCasesObjectsIn7Days);
        // Counting..
        foreach($compCasesObjectsIn7Days as $bunchOfCases)
            array_push ($compCasesCount7Days,count($bunchOfCases));
        foreach($compCasesObjectsIn30Days as $bunchOfCases){
            array_push ($compCasesCount30Days,count($bunchOfCases));
            array_push($compUnitsCount30Days,$this->getUnitsCountOfCasesObjects($bunchOfCases));
            array_push ($sales30Days,$this->getValueOfCasesObjects($bunchOfCases));
        }

        $startOfToday = now() . '00:00:00';
        $endOfToday = now()->subDays(1) . '23:59:59';

        // **  Doughnut Chart Counts ** //
        $waitingJobsToday = $this->getUnitsCountOfJobsObjects(job::whereNull('assignee')->where('stage','!=',-1)->get());

        $CompletedJobsToday = $compUnitsCount7Days[6];
        $ActiveJobsToday = $this->getUnitsCountOfJobsObjects(job::whereNotNull('assignee')->where('stage','!=',-1)->get());
        //dd(job::whereNotNull('assignee')->get());
        $DeliveriesToday = sCase::where('initial_delivery_date','like', '%' . $last7DaysLabels[6] . '%')->where('delivered_to_client',0)->orderBy('initial_delivery_date')->get();
        $paymentsReceivedToday = payment::where('created_at','like', '%' . $last7DaysLabels[6] . '%')->orderBy('created_at')->get();

        $labelToLookFor = substr($last30DaysLabels[29],0,8) . "01";
        $key = array_search($labelToLookFor, $last30DaysLabels);
        $last30DaysLabels[$key] = "** ".  $last30DaysLabels[$key] . " **";
//        dd($labelToLookFor);
        $compCasesCount7Days= array_reverse($compCasesCount7Days);
        return view('dashboard',compact('compUnitsCount7Days','compCasesCount7Days',
            'waitingJobsToday','CompletedJobsToday','ActiveJobsToday','DeliveriesToday',
            'paymentsReceivedToday','last7DaysLabels','compCasesObjectsIn30Days','compUnitsCount30Days',
            'collectionsInLast30Days','last30DaysLabels','compCasesCount30Days','sales30Days'));
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
            $from = date('Y-m-d', strtotime('-30 days'));
            $to = now()->toDateString();
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
        $selectedClients = $request->doctor;
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
            'invoice',
            'jobs.millingBuild.deviceUsed',
            'jobs.printingBuild.deviceUsed',
            'jobs.sinteringBuild.deviceUsed',
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
            $query->whereIn('doctor_id', (array)$request->doctor);
        }

        if ($request->filled('material') && !in_array('all', (array)$request->material)) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->whereIn('material_id', (array)$request->material);
            });
        }

        if ($request->filled('job_type') && !in_array('all', (array)$request->job_type)) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->whereIn('type', (array)$request->job_type);
            });
        }

        // Material Type filter (depends on material selection)
        if ($request->filled('material_type') && !in_array('all', (array)$request->material_type)) {
            $query->whereHas('jobs.material.types', function($q) use ($request) {
                $q->whereIn('material_types.id', (array)$request->material_type);
            });
        }

        // Failure Type filter
        if ($request->filled('failure_type') && !in_array('all', (array)$request->failure_type)) {
            $query->whereHas('failureLogs', function($q) use ($request) {
                $q->whereIn('cause_id', (array)$request->failure_type);
            });
        }

        // Abutments filter
        if ($request->filled('abutments') && !in_array('all', (array)$request->abutments)) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->whereIn('abutment', (array)$request->abutments);
            });
        }

        // Implants filter
        if ($request->filled('implants') && !in_array('all', (array)$request->implants)) {
            $query->whereHas('jobs', function($q) use ($request) {
                $q->whereIn('implant', (array)$request->implants);
            });
        }

        // Completion status filter (toggle: all, completed, in_progress)
        if ($request->filled('show_completed')) {
            $completionStatus = $request->show_completed;

            if ($completionStatus === 'completed') {
                // Completed cases: all jobs at stage -1 AND actual_delivery_date not null
                $query->whereNotNull('actual_delivery_date')
                      ->whereDoesntHave('jobs', function($q) {
                          $q->where('stage', '!=', -1);
                      });
            } elseif ($completionStatus === 'in_progress') {
                // In-progress cases: at least one job NOT at stage -1 OR actual_delivery_date is null
                $query->where(function($q) {
                    $q->whereNull('actual_delivery_date')
                      ->orWhereHas('jobs', function($jobQ) {
                          $jobQ->where('stage', '!=', -1);
                      });
                });
            }
            // 'all' - no filter applied
        }

        // Workflow stage filter - specific stages only (1-8)
        if ($request->filled('status') && !in_array('all', (array)$request->status)) {
            $stages = array_filter((array)$request->status, function($status) {
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
            $query->whereHas('jobs', function($q) use ($request) {
                if ($request->filled('units_from')) {
                    $q->havingRaw('COUNT(jobs.id) >= ?', [$request->units_from]);
                }
                if ($request->filled('units_to')) {
                    $q->havingRaw('COUNT(jobs.id) <= ?', [$request->units_to]);
                }
            });
        }

        // Employee filters (based on actual schema)
        if ($request->filled('employee_filters')) {
            foreach ($request->employee_filters as $filter) {
                if (isset($filter['stage']) && isset($filter['employee'])) {
                    $stage = $filter['stage'];
                    $employeeId = $filter['employee'];

                    // Apply employee filter based on available fields
                    switch ($stage) {
                        case 'assignee':
                            $query->whereHas('jobs', function($q) use ($employeeId) {
                                $q->where('assignee', $employeeId);
                            });
                            break;
                        case 'delivery':
                            $query->whereHas('jobs', function($q) use ($employeeId) {
                                $q->where('delivery_accepted', $employeeId);
                            });
                            break;
                    }
                }
            }
        }

        // Device filters (devices are linked through builds, not directly on jobs)
        if ($request->filled('device_filters')) {
            foreach ($request->device_filters as $filter) {
                if (isset($filter['type']) && isset($filter['device'])) {
                    $deviceType = $filter['type'];
                    $deviceId = $filter['device'];

                    // Apply device filter based on device type
                    // Devices are associated via builds: milling_build, printing_build, pressing_build
                    // Each build has device_id or device_used
                    $query->whereHas('jobs', function($q) use ($deviceId, $deviceType) {
                        switch ($deviceType) {
                            case 'mill': // Type 2 - Milling
                                $q->whereHas('millingBuild', function($buildQ) use ($deviceId) {
                                    $buildQ->where(function($q) use ($deviceId) {
                                        $q->where('device_id', $deviceId)
                                          ->orWhere('device_used', $deviceId);
                                    });
                                });
                                break;
                            case 'print': // Type 3 - 3D Printing
                                $q->whereHas('printingBuild', function($buildQ) use ($deviceId) {
                                    $buildQ->where(function($q) use ($deviceId) {
                                        $q->where('device_id', $deviceId)
                                          ->orWhere('device_used', $deviceId);
                                    });
                                });
                                break;
                            case 'sinter': // Type 4 - Sintering (uses device_id directly, no build)
                                $q->where('device_id', $deviceId);
                                break;
                            case 'press': // Type 5 - Pressing
                                $q->whereHas('pressingBuild', function($buildQ) use ($deviceId) {
                                    $buildQ->where(function($q) use ($deviceId) {
                                        $q->where('device_id', $deviceId)
                                          ->orWhere('device_used', $deviceId);
                                    });
                                });
                                break;
                            default:
                                // For 'other' devices, check all build types + device_id
                                $q->where(function($jobQ) use ($deviceId) {
                                    $jobQ->where('device_id', $deviceId) // Direct device_id (for sintering, etc.)
                                    ->orWhereHas('millingBuild', function($buildQ) use ($deviceId) {
                                        $buildQ->where('device_id', $deviceId)->orWhere('device_used', $deviceId);
                                    })
                                    ->orWhereHas('printingBuild', function($buildQ) use ($deviceId) {
                                        $buildQ->where('device_id', $deviceId)->orWhere('device_used', $deviceId);
                                    })
                                    ->orWhereHas('pressingBuild', function($buildQ) use ($deviceId) {
                                        $buildQ->where('device_id', $deviceId)->orWhere('device_used', $deviceId);
                                    });
                                });
                                break;
                        }
                    });
                }
            }
        }

        $cases = $query->orderBy('id', 'desc')->get();

        return view('reports.master-report', compact(
            'cases', 'from', 'to', 'clients', 'materials', 'jobTypes',
            'failureCauses', 'abutments', 'implants', 'employeesByStage', 'devicesByType'
        ));
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
