<?php

namespace App\Http\Controllers;

//require 'vendor/autoload.php';

use App\abutment;
use App\abutmentDeliveryRecord;
use App\Build;
use App\device;
use App\discount;
use App\failureLog;
use App\implant;
use App\MobileNotificationToken;
use App\permission;
use App\UserPermission;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Traits\helperTrait;
use App\client;
use App\material;
use App\JobType;
use App\sCase;
use App\file;
use App\note;
use App\job;
use App\invoice;
use App\impressionType;
use App\materialJobtype;
use App\tag;
use App\Type;
use App\caseLog;
use App\User;
use App\lab;
use App\editLog;

use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;
use Log;
use App\Http\Controllers\OperationsUpgrade;


class CaseController extends Controller
{
    use helperTrait;

    public function __construct()
    {

    }
        use helperTrait;

    public function devicesPage()
    {
        $this->setUserPermissions();
        $permissions = Cache::get('user' . Auth::user()->id);
        $currentUserId = Auth()->user()->id;
        $isAdmin = Auth()->user()->is_admin == 1 || ($permissions && $permissions->contains('permission_id', 122));

        // Get only devices for stages that use devices, ordered by sorting_order
        $devices = device::whereIn('type', [2, 3, 4, 5])
            ->orderBy('sorting_order')
            ->orderBy('name') // Fallback for devices with same/null sorting_order
            ->get();

        // Get device counts using EXACT same logic as operations dashboard
        $deviceUnitsCounts = [];

        foreach ($devices as $device) {
            $deviceId = $device->id;
            $deviceType = $device->type;

            // Create device model instance for countOfUnits method (same as operations dashboard)
            $deviceModel = new device();
            $deviceModel->exists = true;
            $deviceModel->id = $deviceId;
            $deviceModel->type = $deviceType;

            // Count units for each stage (same as operations dashboard)
            foreach ([2, 3, 4, 5] as $stage) {
                if ($stage == $deviceType) { // Only calculate for the device's stage
                    $deviceUnitsCounts[$deviceId][$stage]['waiting'] = $deviceModel->countOfUnits($stage, false);
                    $deviceUnitsCounts[$deviceId][$stage]['active'] = $deviceModel->countOfUnits($stage, true);
                }
            }

            // Special handling for 3D printing builds (same as operations dashboard)
            if ($deviceType == 3) {
                $deviceUnitsCounts[$deviceId]['waitingBuilds'] = Build::where('device_used', $deviceId)
                    ->whereNotNull('set_at')->whereNull('finished_at')->whereNull('started_at')->count();

                $deviceUnitsCounts[$deviceId]['activeBuilds'] = Build::where('device_used', $deviceId)
                    ->whereNotNull('set_at')->whereNotNull('started_at')->whereNull('finished_at')->count();
            }
        }

        // Additional data needed for dialogs (similar to operations dashboard)
        $allCases = sCase::with([
            'client:id,name',
            'jobs' => function ($q) {
                $q->select('id', 'unit_num', 'case_id', 'stage', 'assignee', 'is_active', 'is_set', 'device_id', 'type', 'material_id', 'color', 'style', 'printing_build_id', 'delivery_accepted');
            },
            'jobs.material:id,name,count_as_unit',
            'jobs.jobType:id,name,a_secondary_item',
            'jobs.assignedTo:id,name_initials',
            'jobs.implantR:id,name',
            'jobs.abutmentR:id,name'
        ])
        ->whereHas('jobs', function ($q) {
            $q->whereIn('stage', [2, 3, 4, 5]); // Only device-using stages
        })
        ->get();

        // Add stage configuration for dialog components
        $stageConfig = OperationsUpgrade::STAGE_CONFIG;

        return view('devices.devices-page', compact('devices', 'deviceUnitsCounts', 'allCases', 'stageConfig'));
    }

    /**
     * Update device sort order
     */
    public function updateDeviceOrder(Request $request)
    {
        $deviceIds = $request->input('device_ids');

        if (!$deviceIds || !is_array($deviceIds)) {
            return response()->json(['error' => 'Invalid device IDs'], 400);
        }

        try {
            \DB::transaction(function () use ($deviceIds) {
                foreach ($deviceIds as $index => $deviceId) {
                    device::where('id', $deviceId)->update(['sorting_order' => $index + 1]);

                }
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update order'], 500);
        }
    }






    // Sub-stage action mapping for main manufacturing stages
    private array $stageActions = [
        // Milling
        'MILLING_SET'      => 2.1,
        'MILLING_START'    => 2.2,
        'MILLING_COMPLETE' => 2.3,
        // 3D Printing
        'PRINTING_SET'      => 3.1,
        'PRINTING_START'    => 3.2,
        'PRINTING_COMPLETE' => 3.3,
        // Sintering (2 logs: START, COMPLETE)
        'SINTERING_START'    => 4.1,
        'SINTERING_COMPLETE' => 4.2,
        // Pressing (3 logs: SET, START, COMPLETE)
        'PRESSING_SET'      => 5.1,
        'PRESSING_START'    => 5.2,
        'PRESSING_COMPLETE' => 5.3,
        // Delivery
        'DELIVERY_ASSIGN'   => 8.1,
        'DELIVERY_ACCEPT'   => 8.2,
        'DELIVERY_COMPLETE' => 8.3,
    ];

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        // Set date range
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = date('Y-m-d', strtotime('first day of this month'));
            $to = now()->toDateString();
        }
//        dd($from,$to);

        // Build query for IN-PROGRESS cases (NO date filter, only doctor filter)
        $inProgressQuery = sCase::select(['id', 'patient_name', 'initial_delivery_date', 'actual_delivery_date', 'doctor_id', 'created_at'])
            ->whereNull('actual_delivery_date');  // In-progress cases (stage != 8)

        // Apply doctor filter to in-progress cases if specified
        if (isset($request->doctor) && !(isset($request->doctor[0]) && $request->doctor[0] === 'all')) {
            $inProgressQuery->whereIn('doctor_id', $request->doctor);
        }

        // Build query for COMPLETED cases (filter by actual_delivery_date with date range)
        $completedQuery = sCase::select(['id', 'patient_name', 'initial_delivery_date', 'actual_delivery_date', 'doctor_id', 'created_at'])
            ->whereNotNull('actual_delivery_date')  // Completed cases (stage = 8)
            ->whereBetween('actual_delivery_date', [$from . ' 00:00', $to . ' 23:59']);  // Apply date range to completed cases

        // Apply doctor filter to completed cases if specified
        if (isset($request->doctor) && !(isset($request->doctor[0]) && $request->doctor[0] === 'all')) {
            $completedQuery->whereIn('doctor_id', $request->doctor);
        }

        // Get both sets of cases and load relationships immediately
        $inProgressCases = $inProgressQuery->get();
        $completedCases = $completedQuery->get();

        // Load relationships using eager loading with specific columns to reduce memory usage
        $inProgressCases->load([
            'notes:id,case_id,note,created_at,written_by',
            'tags:id,case_id,tag_id',
            'jobs.jobType:id,name',
            'jobs.material:id,name',
            'jobs.subType:id,name,material_id'
        ]);

        $completedCases->load([
            'notes:id,case_id,note,created_at,written_by',
            'tags:id,case_id,tag_id',
            'jobs.jobType:id,name',
            'jobs.material:id,name',
            'jobs.subType:id,name,material_id'
        ]);

        // Sort in-progress cases by initial_delivery_date (oldest first)
        $inProgressCases = $inProgressCases->sortBy('initial_delivery_date')->values();

        // Sort completed cases by actual_delivery_date (newest first)
        $completedCases = $completedCases->sortByDesc('actual_delivery_date')->values();

        // Merge: in-progress first, then completed
        $cases = $inProgressCases->merge($completedCases);

        // Limit to 500 total cases
        $cases = $cases->take(500);

        $selectedClients = $request->doctor;
        $clients = client::select(['id', 'name'])->get();

        // Pass all necessary data to the view
        return view('cases.index', compact('cases', 'from', 'to', 'selectedClients', 'clients'));
    }

    public function view($id, $stage = -2)
    {
        $case = sCase::with([
            'jobs.jobType:id,name',
            'jobs.material:id,name',
            'jobs.subType:id,name,material_id'
        ])->findOrFail($id);
        $materials = material::all();
        $clients = client::where('active', '!=', 0)->get();
        $types = JobType::all();
        $impressionTypes = impressionType::all();
        $jobTypeMaterials = materialJobtype::all();
        $implants = implant::all();
        $abutments = abutment::all();
        $tags = tag::where('hidden', 0)->get();
        $tagsAsArray = $case->tags->pluck('tag_id')->toArray();
        return view('cases.viewOnly', compact('case', 'clients',
            'implants', 'abutments', 'materials', 'types', 'impressionTypes', 'tags', 'tagsAsArray', 'jobTypeMaterials', 'stage'));
    }

    // returns the view to create the case
    public function create()
    {
        $doctors = client::where('active', '!=', 0)->get();
        $materials = material::all();
        $types = JobType::all();
        $jobTypeMaterials = materialJobtype::all();
        $tags = tag::where('hidden', 0)->get();
        $impressionTypes = impressionType::all();
        $implants = implant::all();
        $abutments = abutment::all();
        return view("cases.create", compact('doctors', 'materials', 'implants', 'abutments', 'types', 'jobTypeMaterials', 'tags', 'impressionTypes'));
    }

    // takes inputs and creates a new case
    public function returnCreate(Request $request)
    {
        // Simple validation for material_id
        if ($request->repeat) {
            foreach ($request->repeat as $index => $job) {
                if (isset($job["units"]) && empty($job["material_id"])) {
                    return back()->with('error', 'Please select a material for all jobs.');
                }
            }
        }

        try {
            /*
            *     CASE BASIC INFO
            */
            $case = new sCase();
            $case->case_id = $request->caseId1 . $request->caseId2 . $request->caseId3 . '_' . $request->caseId4;
            $case->patient_name = $request->patient_name;
            $case->doctor_id = $request->doctor;
            $case->impression_type = $request->impression_type;
            $case->initial_delivery_date = $request->delivery_date;
            $case->created_by = Auth()->user()->id;
            $case->save();


            /*  SAVING TAGS  */

            if ($request->tags)
                foreach ($request->tags as $tag) {
                    $this->createTag($case, $tag);

                }
            //dd($request->repeat);
            /*  STORING JOBS */
            if ($request->repeat)
                foreach ($request->repeat as $job) {
                    try {

                        //if(!isset($job["units"])) continue;
                        if (isset($job["units"])) {
                            $newJob = new job(['unit_num' => $job["units"],
                                'type' => $job["jobType"],
                                'color' => $job["color"],
                                'style' => $job["style"] ?? 'None',
                                'abutment' => $job["abutment"] ?? '0',
                                'implant' => $job["implant"] ?? '0',
                                'material_id' => $job["material_id"],
                                'type_id' => $job["type_id"] ?? null,
                                'case_id' => $case->id,
                                'doctor_id' => $request->doctor,
                                'stage' => 1]);
                            $newJob->save();

                            // Set unit price based on material
                            $newJob->unit_price = material::FindOrFail($job["material_id"])->price - ($this->getDiscount($newJob, $case) / count(explode(',', $newJob->unit_num)));
                            $newJob->save();
                        }
                        if (isset($job['abutments'])) { // dd($job);
                            foreach ($job['abutments'] as $abut) {
                                $record = new abutmentDeliveryRecord();
                                $record->case_id = $case->id;
                                $record->job_id = $newJob->id;
                                $record->abutment_id = $abut["abutment"] ?? '0';
                                $record->implant_id = $abut["implant"] ?? '0';
                                $record->code = $abut["abutmentCode"] ?? '0';
                                $units = implode(',', $abut["abutmentUnits"] ?? [0]);
                                $record->units = $units;
                                $record->qty = count($abut["abutmentUnits"] ?? []);
                                $record->remaining_qty = count($abut["abutmentUnits"] ?? []);
                                if ($record->abutment_id != '0')
                                    $record->save();
                            }
                        }

                    } catch (\Exception $e) {
                        return back()->with('error', "Error creating job: " . $e->getMessage());
                    }
                    if (isset($newJob))
                        if ($newJob->material && $newJob->material->id != 6) {
                            $newJob->implant = null;
                            $newJob->abutment = null;
                            $newJob->save();
                        }
                }
        } catch (\Exception $e) {
            return back()->with('error', "Error creating case: " . $e->getMessage());
        }


        /*
        *     STORING IMAGES
        */

        if ($files = $request->file('images')) {

            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move('caseImages/' . $case->id . '/', $name);


                $newFile = new file();
                $newFile->path = 'caseImages/' . $case->id . '/' . $name;
                $newFile->case_id = $case->id;
                $newFile->added_by = Auth()->user()->id;
                $newFile->save();

            }
            $this->createTag($case, 2);
        }

        if (isset($request->discountCB)) {
            $discount = new discount();
            $discount->discount = $request->discount_amount;
            $discount->case_id = $case->id;
            $discount->reason = $request->discount_reason;
            $discount->save();

            if ($discount->discount == 0) $discount->delete();
        }

        /*
        *     SAVING THE NOTE
        */

        if ($request->note) {
            $note = new note();
            $note->case_id = $case->id;
            $note->note = $request->note;
            $note->written_by = Auth()->user()->id;
            $note->save();
            $this->createTag($case, 5);
        }

        //DB::rollBack();
        DB::commit();
        return redirect('operations-dashboard')->with('success', "Case Saved Successfully!");
//        return redirect('operations-dashboard');
//        return back()->with('success', "Case Saved Successfully!");
    }

    public function returnEdit($id)
    {
        $case = sCase::with([
            'jobs.jobType:id,name',
            'jobs.material:id,name',
            'jobs.material.types:id,name,material_id',
            'jobs.subType:id,name,material_id'
        ])->findOrFail($id);
        $materials = material::with(['types' => function ($query) {
            $query->where('is_enabled', true);
        }])->get();
        $clients = client::where('active', '!=', 0)->get();
        $types = JobType::all();
        $subTypes = \App\Type::all();
        $impressionTypes = impressionType::all();
        $jobTypeMaterials = materialJobtype::all();
        $tags = tag::where('hidden', 0)->get();
        $tagsAsArray = $case->tags->pluck('tag_id')->toArray();
        $stage = -2;
        $implants = implant::all();
        $abutments = abutment::all();
        return view('cases.edit-case', compact('case', 'clients', 'implants', 'abutments', 'materials', 'types', 'subTypes', 'impressionTypes', 'tags', 'tagsAsArray', 'jobTypeMaterials', 'stage'));
    }

    public function edit(Request $request)
    {
        //dd($request);

        $debug = "";
        $doctor = client::where('id', $request->doctor)->first();
        $case = sCase::where('id', $request->id)->first();
        $permissions = Cache::get('user' . Auth()->user()->id);

        // if ($this->isCaseFinished($case->id))
        // abort(403, "Editing completed cases currently disabled - 12012022");

        if (isset($case->actual_delivery_date))
            if (!Auth()->user()->is_admin && !$permissions->contains('permission_id', 115))
                return back()->with('error', "You're not authorized to edit completed cases");

        if (!$doctor) {
            return back()->with('error', "Doctor not found");
        }
        $transaction = DB::transaction(function () use ($request, $doctor) {
            $case = sCase::where('id', $request->id)->first();
            if (!$case) {
                return false;
            }
            $case->case_id = $request->caseId1 . $request->caseId2 . $request->caseId3 . '_' . $request->caseId4;
            $case->patient_name = $request->patient_name;
            $case->doctor_id = $request->doctor;
            $case->impression_type = $request->impression_type;
            $case->initial_delivery_date = $request->delivery_date;

            $jobsLeftInTheForm = array();
            if ($request->repeat)
                foreach ($request->repeat as $job) {
                    $jobId = $job["job_id"];

                    array_push($jobsLeftInTheForm, $jobId);

                    // Validate material_id is present for existing jobs
                    if (empty($job["material_id" . $jobId])) {
                        throw new \Exception('Material selection is required for all jobs.');
                    }

                    if (isset($job["material_id" . $jobId])) {
                        $job2 = job::where('id', $jobId)->first();
                        $job2->update(['unit_num' => $job["units" . $jobId], 'type' => $job["jobType" . $jobId],
                            'color' => $job["color" . $jobId] ?? 'None', 'style' => $job["style" . $jobId] ?? 'None',
                            'abutment' => $job["abutment" . $jobId] ?? '0', 'implant' => $job["implant" . $jobId] ?? '0',
                            'material_id' => $job["material_id" . $jobId], 'type_id' => $job["type_id" . $jobId] ?? null, 'doctor_id' => $request->doctor,
                        ]);
                        $job2->unit_price = material::FindOrFail($job["material_id" . $jobId])->price - ($this->getDiscount($job2, $case) / count(explode(',', $job2->unit_num)));
                        $job2->save();
                    } else {
                        $job2 = job::where('id', $jobId)->first();
                        $job2->update(['unit_num' => $job["units" . $jobId], 'type' => $job["jobType" . $jobId], 'color' => $job["color" . $jobId], 'style' => $job["style" . $jobId] ?? 'None', 'abutment' => null, 'implant' => null, 'material_id' => $job["material_id" . $jobId], 'type_id' => $job["type_id" . $jobId] ?? null, 'doctor_id' => $request->doctor]);
                        $job2->unit_price = material::FindOrFail($job["material_id" . $jobId])->price - ($this->getDiscount($job2, $case) / count(explode(',', $job2->unit_num)));
                        $job2->save();
                    }
                }
            //dd( $case->jobs()->whereNotIn('id',$jobsLeftInTheForm)->get());
            // if no jobs left in the repeater, delete all jobs
            // if(!$request->repeat)
            //$case->jobs()->delete();
            // else
            // delete jobs and that was deleted using the "delete" repeater btn
            foreach ($case->jobs()->whereNotIn('id', $jobsLeftInTheForm)->get() as $job) {
                abutmentDeliveryRecord::where('job_id', $job->id)->delete();
                //$job->abutmentDelivery->each->delete();
                $job->delete();
            }


            /*
            *   NEW JOBS
            */
            $i = 0;
            // dd($request->repeat2);
            if ($request->repeat2)
                foreach ($request->repeat2 as $job) {
                    if (isset($job["units"])) {
                        $i++;

                        // Validate material_id is present before creating job
                        if (empty($job["material_id"])) {
                            throw new \Exception('Material selection is required for all new jobs.');
                        }

                        $newJob = new job();
                        $newJob->unit_num = $job["units"];
                        $newJob->type = $job["jobType"];
                        $newJob->color = $job["color"] ?? 'None';
                        $newJob->style = $job["style"] ?? 'None';
                        $newJob->abutment = $job["abutment"] ?? '0';
                        $newJob->implant = $job["implant"] ?? '0';
                        $newJob->material_id = $job["material_id"];
                        $newJob->type_id = $job["type_id"] ?? null;
                        $newJob->case_id = $case->id;

                        $newJob->save();
                        $newJob->unit_price = material::FindOrFail($job["material_id"])->price - ($this->getDiscount($newJob, $case) / count(explode(',', $newJob->unit_num)));
                        if ($this->isCaseFinished($case->id)) {
                            $newJob->stage = -1;
                            $newJob->save();
                        }
                        else {
                            // Always set new jobs to Design stage (stage 1)
                            $newJob->stage = 1;
                            $newJob->save();
                        }

                        if (isset($job['abutments'])) {
                            foreach ($job['abutments'] as $abut) {
                                $record = new abutmentDeliveryRecord();
                                $record->case_id = $case->id;
                                $record->job_id = $newJob->id;
                                $record->abutment_id = $abut["abutment"] ?? '0';
                                $record->implant_id = $abut["implant"] ?? '0';
                                $record->code = $abut["abutmentCode"] ?? '0';
                                $units = implode(',', $abut["abutmentUnits"] ?? [0]);
                                $record->units = $units;
                                $record->qty = count($abut["abutmentUnits"] ?? []);
                                $record->remaining_qty = count($abut["abutmentUnits"] ?? []);
                                if ($record->abutment_id != '0')
                                    $record->save();
                            }
                        }
                        if ($newJob->material->teeth_or_jaw == 1) {
                            $newJob->implant = null;
                            $newJob->abutment = null;
                            $newJob->save();
                        }
                        foreach ($case->jobs as $jobItem)
                            if ($jobItem->stage == $newJob->stage) {
                                $jobItem->assignee = null;
                                $jobItem->delivery_accepted = null;
                                // $jobItem->save();
                            }
                    }


                }


            if (isset($request->discountCB)) {
                $discount = discount::where('case_id', $case->id)->first() ?? new discount();
                $discount->discount = $request->discount_amount;
                $discount->case_id = $case->id;
                $discount->reason = $request->discount_reason;
                $discount->save();
                if ($discount->discount == 0) $discount->delete();
            } else if (isset($case->discount))
                discount::where('case_id', $case->discount->case_id)->forceDelete();

            if ($request->newNote) {
                $note = new note();
                $note->case_id = $case->id;
                $note->note = $request->newNote;
                $note->written_by = Auth()->user()->id;
                $note->save();
                $this->createTag($case, 5);
            }


            if ($request->tags) {
                //caseTag::where('case_id', $request->id)->delete();
                foreach ($request->tags as $tag) {
                    $this->createTag($case, $tag);
                }
            }
            if ($files = $request->file('images')) {

                foreach ($files as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move('caseImages/' . $case->id . '/', $name);


                    $newFile = new file();
                    $newFile->path = 'caseImages/' . $case->id . '/' . $name;
                    $newFile->case_id = $case->id;
                    $newFile->added_by = Auth()->user()->id;
                    $newFile->save();
                }
                $this->createTag($case, 2);
            }
            $case->save();
            return true;
        });
        if ($transaction) {
            if (isset($case->invoice)) {
                $this->reflectCaseChanges($request->id);
                $debug = "invoice updated";
            }
            return back()->with('success', 'Case has been updated successfully ');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function reflectCaseChanges($caseId)
    {
        $case = sCase::where('id', $caseId)->first();
        if (!$case) return back()->with('error', 'Case was not found while reflecting changes, err004');

        $oldInvoiceAmount = $case->invoice->amount;
        $invoiceAmount = 0;
        foreach ($case->jobs as $job) {
            if ($job->is_repeat == 1 || $job->is_repeat == '1' || $job->is_modification == 1 || $job->is_modification == '1')
                continue;
            $jobPrice = (count(explode(',', $job->unit_num)) * $job->material->price) - $this->getDiscount($job, $case);
            $invoiceAmount += $jobPrice;
        }
        $invoice = invoice::where('case_id', $caseId)->first();
        if (isset($case->discount)) {
            $invoice->amount_before_discount = $invoiceAmount;
            $invoice->amount = $invoiceAmount - $case->discount->discount;
        } else {
            $invoice->amount = $invoiceAmount;
            $invoice->amount_before_discount = $invoiceAmount;
        }
        $invoice->doctor_id = $case->doctor_id;
        $invoice->save();

        // if case is delivered, adjust doctor balance accordingly
        if ($invoice->status == 1) {
            $doctor = client::findOrFail($case->doctor_id);
            $doctor->balance -= $oldInvoiceAmount;
            $doctor->balance += $invoice->amount;
            $doctor->save();
        }
    }

    public function addNote(Request $request)
    {
        $note = new note();
        $note->case_id = $request->case_id_for_note;
        $note->note = $request->newNote;
        $note->written_by = Auth()->user()->id;
        $note->save();
        $case = sCase::findOrFail($request->case_id_for_note);
        $this->createTag($case, 5);
        return back()->with('success', 'Note added successfully ');
    }

    public function moveJobsToNextStage(Request $request)
    {
        $case = sCase::where('id', $request->case_id)->first();
        if (!$case) {
            return back()->with('Case Not found');
        }
    }

    public function employeeDashboard($stage)
    {

        $drivers = User::where('status', 1)->where(function($query) {
            $query->whereHas('permissions', function ($q) {
                $q->whereIn('permission_id', array(8));
            })->orWhere("is_admin", 1);
        })->get();

        // Delivery dashboard
        if ($stage == 8) {
            $jobs = job::select('case_id', 'assignee', 'delivery_accepted', 'stage')->whereIn("stage", [8, -1])->distinct()->get();
            //->where('voucher_status','!=',4)
            $activeCases = sCase::whereIn('id', $jobs->where("delivery_accepted", Auth()->user()->id)->where('stage', 8)->pluck("case_id")->toArray())->whereNull("voucher_recieved_by")->get();

            // Query waiting cases - get both cases where user is assigned via assignee field OR where jobs have this assignee
            $jobsAssignedToMe = $jobs->where('assignee', Auth()->user()->id)->whereNull("delivery_accepted")->where('stage', 8)->pluck("case_id")->toArray();

            // Add cases where a job has delivery_assignee equal to current user
            $jobsWithDeliveryAssignee = job::where('delivery_assignee', Auth()->user()->id)
                                          ->whereNull('delivery_accepted')
                                          ->where('stage', 8)
                                          ->pluck('case_id')
                                          ->toArray();

            // Combine both arrays and remove duplicates
            $assignedCaseIds = array_unique(array_merge($jobsAssignedToMe, $jobsWithDeliveryAssignee));

            $waitingCases = sCase::whereIn('id', $assignedCaseIds)->whereNull("voucher_recieved_by")->get();
            $deliveredCases = sCase::whereIn('id', $jobs->whereNull('assignee')->where("delivery_accepted", Auth()->user()->id)->where('stage', -1)->pluck("case_id")->toArray())->whereNull("voucher_recieved_by")->get();

            \Log::info('Driver dashboard - waiting cases query', [
                'driverId' => Auth()->user()->id,
                'jobsAssignedToMe' => $jobsAssignedToMe,
                'jobsWithDeliveryAssignee' => $jobsWithDeliveryAssignee,
                'waitingCasesCount' => $waitingCases->count()
            ]);

            return view('delivery.driver-dashboard', compact('activeCases', 'waitingCases', 'jobs', 'stage', 'drivers', 'deliveredCases'));
        }


        $jobs = job::select('case_id', 'assignee')->where("stage", $stage)->distinct()->get();
        $activeCases = sCase::whereIn('id', $jobs->where("assignee", Auth()->user()->id)->pluck("case_id")->toArray())->get();
        if ($stage == 1)
            $waitingCases = sCase::doesntHave('jobs')->orWhereIn('id', $jobs->whereNull("assignee")->pluck("case_id")->toArray())->get();
        else
            $waitingCases = sCase::WhereIn('id', $jobs->whereNull("assignee")->pluck("case_id")->toArray())->get();

        if (!$activeCases && !$waitingCases) {
            return back()->with('No Cases found for you :)');
        }

        if ($stage == 7) {
            $drivers = User::where('status', 1)->where(function($query) {
                $query->whereHas('permissions', function ($q) {
                    $q->whereIn('permission_id', array(8));
                })->orWhere("is_admin", 1);
            })->get();
            return view('generic.emp-cases', compact('activeCases', 'waitingCases', 'stage', 'jobs', 'drivers'));
        }
        if ($stage == 2) {
            $labs = lab::all();
            return view('generic.emp-cases', compact('activeCases', 'waitingCases', 'stage', 'jobs', 'drivers', 'labs'));
        }

        return view('generic.emp-cases', compact('activeCases', 'waitingCases', 'stage', 'jobs', 'drivers'));
    }

    function setUserPermissions()
    {

        if (!Cache::has('user' . Auth::user()->id)) {
            $permissions =  UserPermission::where('user_id', Auth::user()->id)->get();
            Cache::forever('user' . Auth::user()->id, $permissions);
        }
    }

    public function adminDashboard_v2()
    {
        /////////////////////////////////////////////
        //////// MAIN ENTRY POINT OF APPLICATION !!!!
        /// /////////////////////////////////////////

        $this->setUserPermissions();
        $permissions = Cache::get('user' . Auth::user()->id);
        $currentUserId = Auth()->user()->id;
        $isAdmin = Auth()->user()->is_admin == 1 || ($permissions && $permissions->contains('permission_id', 122));

        // Start a timer to measure execution time
        $startTime = microtime(true);

        // Cache key for dashboard data (5-minute cache)
        $cacheKey = 'dashboard_data_' . $currentUserId . '_' . ($isAdmin ? 'admin' : 'user');

        // Try to get data from cache first
        if (Cache::has($cacheKey)) {
            $dashboardData = Cache::get($cacheKey);

            // Extract variables from cached data
            extract($dashboardData);

            // Log cache hit
            \Log::info("Dashboard loaded from cache in " . (microtime(true) - $startTime) . " seconds");
        } else {

            // Optimized: Get all cases with all necessary relationships in one query
            $allCases = sCase::with([
                'client:id,name',
                'jobs' => function ($q) {
                    $q->select('id', 'unit_num', 'case_id', 'stage', 'assignee', 'is_active', 'is_set', 'device_id', 'type', 'material_id', 'color', 'style', 'printing_build_id', 'delivery_accepted', 'type_id');
                },
                'jobs.material:id,name,count_as_unit',
                'jobs.jobType:id,name,a_secondary_item',
                'jobs.subType:id,name,material_id',
                'jobs.assignedTo:id,name_initials',
                'jobs.implantR:id,name',
                'jobs.abutmentR:id,name',
                'abutmentsDeliveries:id,case_id,status',
                'tags.originalTagRecord:id,text,color,icon',
                'notes.writtenBy:id,name_initials'
            ])
            ->whereHas('jobs', function ($q) {
                $q->whereIn('stage', [1, 2, 3, 4, 5, 6, 7, 8]);
            })
            ->get();

            // Apply different queries for admin vs normal users
            if ($isAdmin) {
                // Optimized: Filter from single collection instead of multiple DB queries
                $aDesign = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 1)->whereNotNull('assignee')->isNotEmpty();
                });

                $aMilling = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 2)->where('is_set', 1)->isNotEmpty();
                });

                $aPrinting = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 3)->filter(function ($job) {
                        return $job->is_set == 1 || $job->is_active == 1 || !is_null($job->printing_build_id);
                    })->isNotEmpty();
                });

                $aSintering = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 4)->where('is_set', 1)->isNotEmpty();
                });

                $aPressing = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 5)->where('is_set', 1)->isNotEmpty();
                });

                $aFinishing = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 6)->whereNotNull('assignee')->isNotEmpty();
                });

                $aQC = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 7)->whereNotNull('assignee')->isNotEmpty();
                });

                $aDelivery = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 8)->whereNotNull('delivery_accepted')->isNotEmpty();
                });
            } else {
                // Optimized: User-specific queries using collection filtering
                $aDesign = $allCases->filter(function ($case) use ($currentUserId) {
                    return $case->jobs->where('stage', 1)->where('assignee', $currentUserId)->isNotEmpty();
                });

                $aMilling = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 2)->where('is_set', 1)->isNotEmpty();
                });

                $aPrinting = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 3)->where('is_set', 1)->isNotEmpty();
                });

                $aSintering = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 4)->where('is_set', 1)->isNotEmpty();
                });

                $aPressing = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 5)->where('is_set', 1)->isNotEmpty();
                });

                $aFinishing = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 6)->whereNotNull('assignee')->isNotEmpty();
                });

                $aQC = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 7)->whereNotNull('assignee')->isNotEmpty();
                });

                $aDelivery = $allCases->filter(function ($case) use ($currentUserId) {
                    return $case->jobs->where('stage', 8)->where('assignee', $currentUserId)->whereNotNull('delivery_accepted')->isNotEmpty();
                });
            }

            // Optimized: Waiting cases using collection filtering
            $wDesign = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 1)->whereNull('assignee')->isNotEmpty();
            });

            $wMilling = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 2)->filter(function ($job) {
                    return is_null($job->is_set) || $job->is_set == 0;
                })->isNotEmpty();
            });

            $wPrinting = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 3)->filter(function ($job) {
                    return (is_null($job->is_set) || $job->is_set == 0) &&
                           (is_null($job->is_active) || $job->is_active == 0) &&
                           is_null($job->printing_build_id);
                })->isNotEmpty();
            });

            $wSintering = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 4)->whereNull('is_active')->isNotEmpty();
            });

            $wPressing = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 5)->whereNull('is_active')->isNotEmpty();
            });

            $wFinishing = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 6)->whereNull('assignee')->isNotEmpty();
            });

            $wQC = $allCases->filter(function ($case) {
                return $case->jobs->where('stage', 7)->whereNull('assignee')->isNotEmpty();
            });

            // Optimized: Delivery cases using collection filtering
            if ($isAdmin || $permissions->contains('permission_id', 129)) {
                $wDelivery = $allCases->filter(function ($case) {
                    return $case->jobs->where('stage', 8)->whereNull('delivery_accepted')->isNotEmpty();
                });
            } else {
                $wDelivery = $allCases->filter(function ($case) use ($currentUserId) {
                    return $case->jobs->where('stage', 8)->where('assignee', $currentUserId)->whereNull('delivery_accepted')->isNotEmpty();
                });
            }

            // Use raw arrays to prevent serialization issues completely:
            $devices = collect(device::select('id', 'name', 'type', 'img', 'sorting_order', 'hidden')->get()->toArray());
            $deviceStats = job::selectRaw('device_id,
                                         SUM(CASE WHEN is_set = 0 THEN 1 ELSE 0 END) as waiting_count,
                                         SUM(CASE WHEN is_set = 1 THEN 1 ELSE 0 END) as set_count,
                                         SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_count')
                            ->whereIn('device_id', $devices->pluck('id'))
                            ->groupBy('device_id')
                            ->get()
                            ->keyBy('device_id');

            foreach ($devices as $device) {
                $deviceId = $device['id'];
                $deviceType = $device['type'];

                // Create a temporary Device model instance to call methods
                $deviceModel = new device();
                $deviceModel->fill($device);
                $deviceModel->exists = true;
                $deviceModel->id = $deviceId;
                $deviceModel->type = $deviceType;

                foreach ([ 2, 3, 4, 5,] as $stage) {
                    $deviceUnitsCounts[$deviceId][$stage]['waiting'] = $deviceModel->countOfUnits($stage, false);
                    $deviceUnitsCounts[$deviceId][$stage]['active'] = $deviceModel->countOfUnits($stage, true);
                }

                $deviceUnitsCounts[$deviceId]['waitingBuilds'] = Build::where('device_used', $deviceId)
                    ->whereNotNull('set_at')->whereNull('finished_at')->whereNull('started_at')->count() ;

                $deviceUnitsCounts[$deviceId]['activeBuilds'] =  Build::where('device_used', $deviceId)
                    ->whereNotNull('set_at')->whereNotNull('started_at')->whereNull('finished_at')->count() ;

                $stats = $deviceStats->get($deviceId);
                // Note: We can't set properties on arrays, so we'll skip these assignments
                // $device->jobsWaiting = $stats ? $stats->waiting_count : 0;
                // $device->jobsSet = $stats ? $stats->set_count : 0;
                // $device->jobsActive = $stats ? $stats->active_count : 0;
            }

            $drivers = User::where('status', 1)->whereHas('permissions', function ($q) {
                $q->where('permission_id', 8);
            })->whereHas('permissions', function ($q) {
                $q->where('permission_id', 131);
            })->get();

            $labs = lab::all();

            // Store all the dashboard data in the cache for 5 minutes
            $dashboardData = compact(
                'labs', 'wDesign', 'aDesign',
                'wMilling', 'aMilling', 'wPrinting', 'aPrinting',
                'wSintering', 'aSintering', 'wPressing', 'aPressing',
                'wFinishing', 'aFinishing', 'wQC', 'aQC', 'wDelivery',
                'aDelivery', 'drivers', 'devices', 'deviceUnitsCounts'
            );

            Cache::put($cacheKey, $dashboardData, now()->addMinutes(5));
        }

        $activeOuterTab = $_COOKIE['activeOuterTab'] ?? "";

        // Get stage configuration for components
        $stageConfig = OperationsUpgrade::STAGE_CONFIG;

        // Get material types for operation dialogs
        $types = \App\Type::enabled()->get();

        // Group types by material_id using the pivot table (material_types)
        $typesByMaterial = \DB::table('material_types')
            ->join('types', 'material_types.type_id', '=', 'types.id')
            ->join('materials', 'material_types.material_id', '=', 'materials.id')
            ->where('types.is_enabled', true)
            ->select('material_types.material_id', 'material_types.type_id', 'types.name as type_name', 'materials.name as material_name')
            ->get()
            ->groupBy('material_id')
            ->map(function($group) {
                return $group->map(function($item) {
                    return [
                        'id' => $item->type_id,
                        'name' => $item->type_name,
                        'material_id' => $item->material_id,
                        'material_name' => $item->material_name
                    ];
                });
            });

        // Log execution time - can be removed in production
        $executionTime = microtime(true) - $startTime;
        \Log::info("Dashboard loaded in {$executionTime} seconds");

        return view('cases.admin-dashboardv2', compact(
            'labs', 'wDesign', 'aDesign',
            'wMilling', 'aMilling', 'wPrinting', 'aPrinting',
            'wSintering', 'aSintering', 'wPressing', 'aPressing',
            'wFinishing', 'aFinishing', 'wQC', 'aQC', 'wDelivery',
            'aDelivery', 'drivers', 'activeOuterTab', 'devices','deviceUnitsCounts',
            'permissions', 'stageConfig', 'types', 'typesByMaterial'
        ));
    }

    public function numOfCasesBefore($dayToSubtract, $cases)
    {
        return $cases->whereBetween('created_at', [today()->subDays($dayToSubtract)->toDateString() . ' 00:00', today()->subDay($dayToSubtract - 1)->toDateString() . ' 23:59'])->count();

    }

    public function getCasesCompletedIn7Dys($casesLogs)
    {

        // dd($casesLogs->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00' ,today()->subDay(0)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count());
        $ar = array(
            array("Design",
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(6)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(5)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(4)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(3)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(2)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(1)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),
                $casesLogs->where('stage', 1)->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(0)->toDateString() . ' 23:59'])->distinct('case_id')->get()->count(),

            ), array("Milling", array(
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(6)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(5)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(4)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(3)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(2)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(1)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 2)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->toDateString() . ' 23:59'])->get()->count(),

            )), array(
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(6)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(5)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(4)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(3)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(2)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->subDay(1)->toDateString() . ' 23:59'])->get()->count(),
                $casesLogs->where('stage', 3)->distinct('case_id')->whereBetween('created_at', [today()->subDay(7)->toDateString() . ' 00:00', today()->toDateString() . ' 23:59'])->get()->count(),

            ));

        return $ar;
    }

    public function assignToMe($caseId, $stage, $returnMessages = true)
    {
        $jobs = job::where("case_id", $caseId)->where("stage", $stage)->whereNull('assignee')->get();
        if (!$jobs) return $this->getAssignmentRedirect()->with('error', 'Case has no jobs, add jobs first.');
        foreach ($jobs as $job) {
            if ($stage != 2 && $stage != 3)
                $job->is_active = 1;
            $job->is_set = 1;
            $job->assignee = Auth()->user()->id;
            $job->save();
        }
        // Sub-stage logic for main manufacturing stages
        $logStage = $stage;
        $isCompletion = 0;
        if ($stage == 2) { $logStage = $this->stageActions['MILLING_SET']; }
        if ($stage == 3) { $logStage = $this->stageActions['PRINTING_SET']; }
        if ($stage == 4) { $logStage = $this->stageActions['SINTERING_SET']; }
        if ($stage == 5) { $logStage = $this->stageActions['PRESSING_START']; }
        if ($stage == 8) { $logStage = $this->stageActions['DELIVERY_ASSIGN']; }
        $log = new caseLog(['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $logStage, 'is_completion' => $isCompletion]);
        $log->save();
        if ($returnMessages)
            return $this->getAssignmentRedirect()->with('success', "Case has been assigned to you!");
    }

    private function getAssignmentRedirect()
    {
        // Check if request came from operations dashboard
        $referer = request()->header('referer');
        if ($referer && (str_contains($referer, '/operations-dashboard') || str_contains($referer, 'admin-dashboard'))) {
            return redirect()->route('admin-dashboard-v2');
        }

        // Default to operations dashboard for better UX
        return redirect()->route('admin-dashboard-v2');
    }

    public function assignAndFinish($caseId, $stage)
    {
        $this->assignToMe($caseId, $stage, false);
        $this->finishCaseStage($caseId, $stage, false);
        return $this->getAssignmentRedirect()->with('success', "Case completed & sent to the next stage!");
    }

    public function sendCaseToDelivery($caseId)
    {
        $case = sCase::findOrFail($caseId);
        foreach ($case->jobs as $job)
            $job->update([
                'stage' => '8',
                'assignee' => null,
            ]);

        return back()->with('success', "Case has been sent to Delivery Stage");
    }

    public function finishCaseStage($caseId, $stage, $returnMessages = true, $jobs = [])
    {$returnMessages = true;
        Log::info('[finishCaseStage] called with parameters', [
            'caseId' => $caseId,
            'stage' => $stage,
            'returnMessages' => $returnMessages,
            'jobs' => count($jobs)
        ]);
        $jobs= empty($jobs) ? job::with('material')->where("case_id", $caseId)->where("stage", $stage)->get() : $jobs;

          Log::info('[finishCaseStage] firstJob case_id: ' . $caseId .' stage: ' . $stage .'  ', ['$jobs' => count($jobs)]);
      //  if($firstJob) return back()->with("Case's jobs are currently at different stage");

        $assignee = $jobs->first()->assignee;
        Log::info( '[finishCaseStage] assignee', ['assignee' => $assignee]);
        if (!$assignee) return back()->with('error', "Case Already Completed");

//        if (empty($jobs))
          $jobs = job::with('material')->where("case_id", $caseId)->where("stage", $stage)->where("assignee", $assignee ?? Auth()->user()->id)->get();
        $case = sCase::findOrFail($caseId);

        if (!$jobs) return back()->with('error', 'No Jobs found.');

        $nextStage = -3;
        foreach ($jobs as $job) {
            $nextStage = $this->getJobNextStage($job);
            Log::info('[finishCaseStage] job', ['job' => $job, 'nextStage' => $nextStage]);

            // before QC ( 7 = QC ) just send them to next stage

            if ($nextStage != 7) {
                    $job->assignee = null;
                    $job->stage = $nextStage;

                    $job->is_active =null;
                    $job->is_set = null;
                    $job->device_id = null;

                $job->save();
                //dd($job, $job->stage, $nextStage);

            } // If Next stage is QC check if all jobs are ready (in finishing) or not before sending them to QC
            else {
                if ($this->allJobsAreIn($case, 6)) {
                    $job->is_active =null;
                    $job->is_set = null;
                    $job->device_id = null;
                    $job->assignee = null;
                    $job->stage = $nextStage;
                    if ($nextStage != 8)
                    $this->issueInvoice($job);
                    $job->save();
                } else {
                    if ($returnMessages)
                        return back()->with('error', 'Not all jobs are in finishing stage');
                }
            }
        }


        // if next stage is Delivery, create invoice
        if ($nextStage == 8) $this->applyInvoice($job);

        // if all jobs are finished, apply invoice and set date delivered
        if ($nextStage == -1) {

            // Check for modification cases
            if ($case->contains_modification == 1) {
                // Look for failure log including soft-deleted records in case it was accidentally deleted
                $log = failureLog::where("case_id", $case->id)->withTrashed()->first();

                // if the failure log is found
                if ($log) {
                    // Preserve original delivery date from failure log
                    $case->actual_delivery_date = $log->old_delivery_date;
                    $note = new note();
                    $note->case_id = $case->id;
                    $logStatus = $log->trashed() ? " (recovered from deleted log)" : "";
                    $note->note = "Modification Delivered - Original delivery date preserved: " . ($log->old_delivery_date ?date('Y-m-d H:i:s', strtotime($log->old_delivery_date)) : 'N/A') . $logStatus;
                    $note->written_by = Auth()->user()->id;
                    $note->save();

                    // if contains modification and the failure was not found
                } else {
                    $case->actual_delivery_date = now();
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Modification Delivered - No previous delivery date found, using current time";
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                }
            }
            // Check for repeat cases (they don't use contains_modification flag)
            elseif ($case->first_case_if_repeated) {
                // This is a repeat case - find the original case's delivery date
                $originalCase = sCase::find($case->first_case_if_repeated);
                if ($originalCase && $originalCase->actual_delivery_date) {
                    // Preserve original delivery date from the first case
                    $case->actual_delivery_date = $originalCase->actual_delivery_date;
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Repeat Case Delivered - Original delivery date preserved from case #{$originalCase->id}: " . date('Y-m-d g:i a',strtotime($case->initial_delivery_date)) ;
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                } else {
                    // Original case has no delivery date, use current time
                    $case->actual_delivery_date = now();
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Repeat Case Delivered - Original case has no delivery date, using current time";
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                }
            } else {
                // Normal case - use current delivery time
                $case->actual_delivery_date = now();
            }


            $case->delivered_to_client = 1;
            $case->save();
            $this->applyInvoice($job);
        }
        // TODO: Fix this later
        // FIXME: Bug here

        // Get the device ID from the job if available
        $deviceId = null;
        if (!empty($jobs) && count($jobs) > 0) {
            $deviceId = $jobs[0]->device_id;
        }

        // Substage logic for main manufacturing stages
        $logStage = $stage;
        $isCompletion = 1;
        if ($stage == 2) {
            $logStage = $this->stageActions['MILLING_COMPLETE'];
        }
        if ($stage == 3) {
            $logStage = $this->stageActions['PRINTING_COMPLETE'];
        }
        if ($stage == 4) {
            $logStage = $this->stageActions['SINTERING_COMPLETE'];
        }
        if ($stage == 5) {
            $logStage = $this->stageActions['PRESSING_COMPLETE'];
        }
        if ($stage == 8) {
            $logStage = $this->stageActions['DELIVERY_COMPLETE'];
        }
        $log = new caseLog([
            'user_id' => Auth()->user()->id,
            'case_id' =>  $caseId,
            'stage' => $logStage,
            'device_id' => $deviceId,
            'action_type' => 3, // 3 = complete
            'is_completion' => $isCompletion
        ]);
        $log->save();

        if ($returnMessages)
            return back()->with('success', "Case have been marked as finished.");
    }

    public function deliveredInBox($caseId)
    {
        //$assignee is the employee currently working on the jobs
        $assignee = job::where("case_id", $caseId)->where("stage", 8)->first()->assignee;
        if (!$assignee) return back()->with('error', "Case Already Completed");
       // $assignee = job::where("case_id", $caseId)->where("stage", 8)->first()->assignee;
        $jobs = job::where("case_id", $caseId)->where("stage", 8)->where("assignee", $assignee)->get();
        $case = sCase::findOrFail($caseId);

        if (!$jobs) return back()->with('error', 'No Jobs found.');

        $nextStage = -3;
        foreach ($jobs as $job) {

            $nextStage = $this->getJobNextStage($job);

            // before QC ( 7 = QC ) just send them to next stage
            if ($nextStage != 7) {
                    $job->assignee = null;

                $job->stage = $nextStage;
                $job->save();
            } // If Next stage is QC check if all jobs are ready (in finishing) or not before sending them to QC
            else {
                if ($this->allJobsAreIn($case, 6)) {

                    $job->assignee = null;
                    $job->stage = $nextStage;
                    $job->save();
                } else
                    return back()->with('error', 'Not all jobs are in finishing stage');
            }
        }


        // if next stage is Delivery, create invoice
        if ($nextStage == 8){ $this->applyInvoice($job);
        $job->is_set=null; $job->assignee=$assignee;$job->is_set=null;}

        // if all jobs are finished, apply invoice and set date delivered
        if ($nextStage == -1) {
            $case->delivered_in_box = 1;
            $this->createTag($case, 15);
            // Check for modification cases
            if ($case->contains_modification == 1) {
                // Look for failure log including soft-deleted records in case it was accidentally deleted
                $log = failureLog::where("case_id", $case->id)->withTrashed()->first();

                // if the failure log is found
                if ($log) {
                    // Preserve original delivery date from failure log
                    $case->actual_delivery_date = $log->old_delivery_date;
                    $note = new note();
                    $note->case_id = $case->id;
                    $logStatus = $log->trashed() ? " (recovered from deleted log)" : "";
                    $note->note = "Modification Delivered (In Box) - Original delivery date preserved: " .  ($log->old_delivery_date ?date('Y-m-d H:i:s', strtotime($log->old_delivery_date))  : 'N/A') . $logStatus;
                    $note->written_by = Auth()->user()->id;
                    $note->save();

                    // if contains modification and the failure was not found
                } else {
                    $case->actual_delivery_date = now();
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Modification Delivered (In Box) - No previous delivery date found, using current time";
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                }
            }
            // Check for repeat cases (they don't use contains_modification flag)
            elseif ($case->first_case_if_repeated) {
                // This is a repeat case - find the original case's delivery date
                $originalCase = sCase::find($case->first_case_if_repeated);
                if ($originalCase && $originalCase->actual_delivery_date) {
                    // Preserve original delivery date from the first case
                    $case->actual_delivery_date = $originalCase->actual_delivery_date;
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Repeat Case Delivered (In Box) - Original delivery date preserved from case #{$originalCase->id}: " .date('Y-m-d g:i a',strtotime($case->initial_delivery_date)) ;
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                } else {
                    // Original case has no delivery date, use current time
                    $case->actual_delivery_date = now();
                    $note = new note();
                    $note->case_id = $case->id;
                    $note->note = "Repeat Case Delivered (In Box) - Original case has no delivery date, using current time";
                    $note->written_by = Auth()->user()->id;
                    $note->save();
                }
            } else {
                // Normal case - use current delivery time
                $case->actual_delivery_date = now();
            }
            $case->delivered_to_client = 1;
            $case->save();
            $this->applyInvoice($job);
        }


        $log = new caseLog(['user_id' => Auth()->user()->id , 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1]);
        $log->save();

        return back()->with('success', "Case have been marked as finished & delivered in box.");
    }


    public function getJobNextStage($job)
    {
        $material = $job->material;
        $currentStage = $job->stage;

        /*
         * 1 => Design
         * 2 => Milling
         * 3 => 3D Printing
         * 4 => Sintering Furnace
         * 5 => Press Furnace
         * 6 => Finishing
         * 7 => Quality Control
         * 8 => Delivery
         * -1 => Finished
         */

        if ($material->design && $currentStage < 1) return 1;
        if ($material->mill && $currentStage < 2) return 2;
        if ($material->print_3d && $currentStage < 3) return 3;
        if ($material->sinter_furnace && $currentStage < 4) return 4;
        if ($material->press_furnace && $currentStage < 5) return 5;
        if ($material->finish && $currentStage < 6) return 6;
        if ($material->qc && $currentStage < 7) return 7;
        if ($material->delivery && $currentStage < 8) return 8;

        return -1;
    }

    public function issueInvoice($job)
    {
        $case = sCase::findOrFail($job->case_id);

        if ($case->contains_modification) return;

        // Check if invoice already exists for this case
        $existingInvoice = invoice::where('case_id', $case->id)->first();

        if ($job->is_repeat) {
            // For repeat jobs, create/update invoice with zero amount
            if ($existingInvoice) {
                // Update existing invoice
                $existingInvoice->status = 0;
                $existingInvoice->amount = 0;
                $existingInvoice->save();
            } else {
                // Create new invoice
                $invoice = new invoice();
                $invoice->status = 0;
                $invoice->amount = 0;
                $invoice->case_id = $case->id;
                $invoice->doctor_id = $case->client->id;
                $invoice->save();
            }
            return;
        }

        // Calculate invoice amount
        $invoiceApplicable = true;
        $invoiceAmount = 0;
        foreach ($case->jobs as $job) {
            if ($job->is_repeat == 1 || $job->is_repeat == '1' || $job->is_modification == 1 || $job->is_modification == '1')
                continue;
            $jobPrice = (count(explode(',', $job->unit_num)) * $job->material->price) - $this->getDiscount($job, $case);
            $invoiceAmount += $jobPrice;
        }

        if ($invoiceApplicable) {
            if ($existingInvoice) {
                // Update existing invoice instead of creating a new one
                $invoice = $existingInvoice;
                $invoice->status = 0;
                $invoice->case_id = $case->id;
                $invoice->doctor_id = $case->client->id;
                if (isset($case->discount)) {
                    $invoice->amount_before_discount = $invoiceAmount;
                    $invoice->amount = $invoiceAmount - $case->discount->discount;
                } else {
                    $invoice->amount = $invoiceAmount;
                    $invoice->amount_before_discount = $invoiceAmount;
                }
                $invoice->save();
            } else {
                // Create new invoice only if one doesn't exist
                $invoice = new invoice();
                $invoice->status = 0;
                $invoice->case_id = $case->id;
                $invoice->doctor_id = $case->client->id;
                if (isset($case->discount)) {
                    $invoice->amount_before_discount = $invoiceAmount;
                    $invoice->amount = $invoiceAmount - $case->discount->discount;
                } else {
                    $invoice->amount = $invoiceAmount;
                    $invoice->amount_before_discount = $invoiceAmount;
                }
                $invoice->save();
            }
        }
    }

    public function applyInvoice($job)
    {
        $case = sCase::with('invoice')->where('id', $job->case_id)->get();
        $patientName = $case[0]->patient_name;
        $client = $case[0]->client;
        $clientTokens = MobileNotificationToken::where("client_id", $client->id)->get();
        foreach ($clientTokens as $token) {
            if ($case[0]->delivered_in_box)
                $this->sendCaseNotification($token->token, "Case has been delivered in box", "Case of $patientName has been delivered In-Box", "1");
            else
                $this->sendCaseNotification($token->token, "Case has been delivered", "Case of $patientName has been delivered", "1");
        }

//        if ($case[0]->delivered_in_box) {
//            $this->sendCaseNotification($client->doc_notification_token, "Case has been delivered in box", "Case of $patientName has been delivered In-Box", "1");
//            $this->sendCaseNotification($client->clinic_notification_token, "Case has been delivered in box", "Case of $patientName has been delivered In-Box", "1");
//        } else// 0 => open app, 1 => open case, 2 => open statement
//        {
//            $this->sendCaseNotification($client->doc_notification_token, "Case has been delivered", "Case of $patientName has been delivered", "1");
//            $this->sendCaseNotification($client->clinic_notification_token, "Case has been delivered", "Case of $patientName has been delivered", "1");
//        }
        if ($case[0]->contains_modification) return;
        $allJobsCompleted = true;
        foreach ($case[0]->jobs as $job)
            if ($job->stage != -1)
                $allJobsCompleted = false;

        if ($allJobsCompleted) {
            $client = $case[0]->client;
            $invoice = $case[0]->invoice;
            $client->balance = $client->balance + ($invoice->amount ?? 0);
            $invoice->status = 1;
            $invoice->date_applied = now();
            $invoice->save();
            $client->save();


        }
    }

    public function invoicesList(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = date('Y-m-d', strtotime('first day of this month'));
            $to = now()->toDateString();
        }
        $invoices = invoice::whereBetween('date_applied', [$from . ' 00:00', $to . ' 23:59']);


        if ($request->doctor && !in_array("all", $request->doctor))
            $invoices = $invoices->whereIn('doctor_id', $request->doctor);


        if ($request->patient_name) {
            $invoices = $invoices->whereHas('case', function ($q) use ($request) {
                $q->where('patient_name', 'like', '%' . $request->patient_name . '%');
            });
        }

        //dd($this->getQuery($invoices));
        $invoices = $invoices->get();


        $selectedClients = $request->doctor;
        $clients = client::all();

        return view('generic.invoices-list', compact('invoices', 'clients', 'to', 'from', 'selectedClients', 'clients'))->with('patientName', $request->patient_name);
    }

    public function getDiscount($job, $case)
    {
        $discounts = $case->client->discounts;
        $discountOfMaterial = $discounts->where('material_id', $job->material_id)->first();
        // No Discount
        if (!$discountOfMaterial) return 0;

        //Fixed Discount
        if ($discountOfMaterial && $discountOfMaterial->type === 0) {
            return count(explode(',', $job->unit_num)) * $discountOfMaterial->discount;

            // Percentage Discount
        } else if ($discountOfMaterial->type) {
            return (count(explode(',', $job->unit_num))) * ($job->material->price * ($discountOfMaterial->discount / 100));
        }
    }

    public function allJobsAreIn($case, $stage)
    {
        foreach ($case->jobs->where('stage', '!=', -1) as $job)
            if ($job->stage < $stage)
                return false;
        return true;
    }


    public function acceptCaseByDelivery($id)
    {

        foreach (job::where("case_id", $id)->get() as $job) {
            $job->assignee = Auth()->user()->id;
            $job->delivery_accepted = Auth()->user()->id;
            $job->save();
        }

        $DeliLog = new caseLog(['user_id' => Auth()->user()->id, 'case_id' => $id, 'stage' => $this->stageActions['DELIVERY_ACCEPT'], 'is_completion' => 0]);
        $DeliLog->save();
        return back()->with('success', "Case has been assigned to you!");
    }

    public function viewVoucher($id)
    {
        $case = sCase::where('id', $id)->first();
        return view('delivery.view-voucher', compact('case'));
    }

    public function externallyMilled(Request $request)
    {
        $jobs = job::where("case_id", $request->case_id)->where("stage", 2)->where("assignee", Auth()->user()->id)->get();

        if (!$jobs) return back()->with('error', 'No Jobs found.');

        foreach ($jobs as $job) {
            $nextStage = $this->getJobNextStage($job);
            $job->milling_lab = $request->lab_id;
            $job->assignee = null;
            $job->stage = $nextStage;
            $job->save();
        }

        $log = new caseLog(['user_id' => Auth()->user()->id, 'case_id' => $request->case_id, 'stage' => $this->stageActions['MILLING_COMPLETE'], 'is_completion' => 1]);
        $log->save();
        return back()->with('success', "Case have been marked as finished.");
    }

    public function deliverySchedule(Request $request)
    {
        if ($request->from && $request->to) {
            $data['from'] = $request->from;
            $data['to'] = $request->to;
            $cases = sCase::with('client')->whereBetween(
                'initial_delivery_date', array($data['from'], $data['to']))
                ->where('delivered_to_client', '=', 0)
                ->orderBy('cases.initial_delivery_date', 'ASC')->get();
        } else {
            $data['from'] = today()->subDays(356)->toDateString() . ' 00:00';
            $data['to'] = today()->addDays(1)->toDateString() . ' 23:59';
            $cases = sCase::with('client')->whereBetween(
                'initial_delivery_date', array($data['from'], $data['to']))
                ->where('delivered_to_client', '=', 0)
                ->orderBy('cases.initial_delivery_date', 'ASC')->get();
        }
        return view('delivery.delivery-schedule', compact('cases', 'data'));
    }

    public function updateDeliveryDate(Request $request)
    {
        $action = "";
        $transaction = DB::transaction(function () use ($request) {
            $case = sCase::where('id', $request->id)->first();
            if (!$case) {
                return false;
            }
            $action = "Updated delivery date from [" . str_replace('T', " ", $case->initial_delivery_date) . "] to  [" . $request->delivery_date . ']';
            $case->initial_delivery_date = $request->delivery_date;
            $case->save();
            $editLogRecord = new editLog();
            $editLogRecord->case_id = $request->id;
            $editLogRecord->user_id = Auth()->user()->id;
            $editLogRecord->action = $action;
            $editLogRecord->save();
            $note = new note();
            $note->case_id = $request->id;
            $note->note = $action;
            $note->written_by = Auth()->user()->id;
            $note->save();
            return true;
        });
        if ($transaction) {
            return back()->with('success', 'Case delivery date has been updated');
        } else {
            return back()->with('error', 'Something went wrong');
        }

    }

    public function viewSingleScreen()
    {
        $cases = sCase::whereNull('actual_delivery_date')->get();
        return view('generic.screen', compact('cases'));
    }

    public function deleteCase($id)
    {
        $case = sCase::where('id', $id)->first();
        DB::beginTransaction();
        $case->jobs()->delete();
        $case->notes()->delete();
        $case->photos()->delete();
        $case->tags()->delete();
        $case->discount()->delete();
        $case->invoice()->delete();
        $case->delete();
        caseLog::where('case_id', $id)->delete();

        DB::commit();

        return back()->with('success', 'Case and all its information deleted successfully.');
    }

    public function detectNewJobStage(Request $request)
    {
        $dummyJob = new job();
        $dummyJob->type = $request->jobType;
        $dummyJob->case_id = $request->case_id;
        $dummyJob->material_id = $request->materialId;
        $dummyJob->save();
        $stage = $this->lowestJobStageApplicable($dummyJob, $request->case_id);
        $case = sCase::findOrFail($request->case_id);
        job::where('id', $dummyJob->id)->forceDelete();

        if ($this->isCaseFinished($request->case_id))
            return response()->json(array('msg' => "Completed"), 200);


        else if ($this->caseHasNoJobs($request->case_id))
            return response()->json(array('msg' => "Design"), 200);
        else if (!$case->hasModels() && $request->jobType == 4)
            return response()->json(array('msg' => $this->stageToText(1)), 200);
        else
            return response()->json(array('msg' => $this->stageToText($stage)), 200);

    }

    public function viewInvoice($caseId)
    {
        $case = sCase::findOrFail($caseId);
        return view('generic.invoice-view', compact('case'));
    }

    public function deletedCases()
    {
        $cases = sCase::onlyTrashed()->paginate(10);
        $trashedCases = true;
        return view('cases.index', compact('cases', 'trashedCases'));
    }

    public function lockCase($caseId)
    {
        $case = sCase::findOrFail($caseId);
        $case->update(['locked' => 1]);
        return back()->with('success', 'Case locked successfully.');

    }

    public function unlockCase($caseId)
    {
        $case = sCase::findOrFail($caseId);
        $case->update(['locked' => 0]);
        $this->createTag($case, 14);

        return back()->with('success', 'Case un-locked successfully.');

    }

    public function restoreDeletedCase($id)
    {
        $case = sCase::withTrashed()->where('id', $id)->first();
        DB::beginTransaction();
        $case->jobs()->withTrashed()->restore();
        $case->notes()->withTrashed()->restore();
        $case->photos()->withTrashed()->restore();
        $case->tags()->withTrashed()->restore();
        $case->discount()->withTrashed()->restore();
        $case->invoice()->withTrashed()->restore();
        $case->restore();
        caseLog::where('case_id', $id)->withTrashed()->restore();;

        DB::commit();
        return back()->with('success', 'Case restored successfully.');
    }

    public function globalSearch(Request $request)
    {
        $cases = sCase::query();

        $searchText = $request->searchText;

        // split on 1+ whitespace & ignore empty (eg. trailing space)
        $searchValues = preg_split('/\s+/', $searchText, -1, PREG_SPLIT_NO_EMPTY);
        $cases = $cases->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('patient_name', 'like', "%{$value}%");
            }
        });

        $cases = $cases->orderByRaw('-`actual_delivery_date` ASC')->orderBy("initial_delivery_date", 'asc')->get();


        $isSearchResults = true;
        return view('cases.index', compact('cases', 'isSearchResults'));
    }

    public function rejectedCases(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = date('Y-m-d', strtotime('-30 days'));
            $to = now()->toDateString();
        }

        if ($request->doctor && !in_array("all", $request->doctor))
            $cases = sCase::whereHas('jobs', function ($q) {
                $q->where('is_rejection', 1);
            })->whereBetween('created_at', [$from . ' 00:00', $to . ' 23:59'])->whereIn('doctor_id', $request->doctor);

        else
            $cases = sCase::whereHas('jobs', function ($q) {
                $q->where('is_rejection', 1);
            })->whereBetween('created_at', [$from . ' 00:00', $to . ' 23:59']);

        if ($request->patient_name)
            $cases = $cases->where('patient_name', 'like', '%' . $request->patient_name . '%')->get();
        else
            $cases = $cases->get();

        $selectedClients = $request->doctor;
        $clients = client::all();
        return view('cases.rejected-cases', compact('cases', 'from', 'to', 'selectedClients', 'clients'))->with('patientName', $request->patient_name);
    }

    public function resetCaseToWaiting($id, $stage)
    {
        $caseJobs = job::where('case_id', $id)->where("stage", $stage)->get();

        if (!$caseJobs)
            return back()->with('error', 'Case jobs not found.');

        foreach ($caseJobs as $job) {
            $job->assignee = null;
            $job->delivery_accepted = null;
            $job->save();
        }

        return back()->with('success', 'Case has been reset successfully.');
    }

    public function completeByAdmin($id, $stage)
    {
        $this->finishCaseStage($id, $stage, false);
        return back()->with('success', 'Case has been overridden & completed successfully.');
    }

    public function testNotification($type = 2)
    {
        //   $docClient = DB::select('SELECT * FROM clients WHERE phone LIKE ? LIMIT 1', ['%' . "0788160088" . '%']);
        //   $clinicAccount = DB::select('SELECT * FROM clients WHERE clinic_phone LIKE ? LIMIT 1', ['%' . "0788160088" . '%']);

//        $docClient = client::where('phone', 'like', '%' . "0788160088" . '%')->get()->first();
//        $clinicAccount= client::where('clinic_phone', 'like', '%' . "0788160088" . '%')->get()->first();
//        dd($docClient, $clinicAccount);
        // print_r($docClient[0] ?? "NO DOC **" );
        // print_r("--------------");
        //   print_r($clinicAccount[0]);
        $client = client::where("id", 1)->first();
        $patient_name = "يزن شريتح";
        // 1=> inbox  2=> case delivered  3=> new payment

        echo("test $type");
        echo(" doc not : " . $client->doc_notification_token);
        echo(" clinic not : " . $client->clinic_notification_token);
        switch ($type) {
            case 1:
                if ($client->doc_notification_token)
                    $this->sendCaseNotification($client->doc_notification_token, "Case Delivered In-Box",
                        "Case of $patient_name has been delivered in box "
                    );
                if ($client->clinic_notification_token)
                    $this->sendCaseNotification($client->clinic_notification_token, "Case Delivered In-Box",
                        "Case of $patient_name has been delivered in box ");
                break;
            case 2:
                if ($client->doc_notification_token)
                    $this->sendCaseNotification($client->doc_notification_token,
                        "Case Delivered", "Case of $patient_name has been delivered");
                if ($client->clinic_notification_token)
                    $this->sendCaseNotification($client->clinic_notification_token,
                        "Case Delivered", "Case of $patient_name has been delivered");
                break;
            case 3:
                if ($client->doc_notification_token)
                    $this->sendPaymentNotification($client->doc_notification_token,
                        "Payment Received",
                        "100 " . "JOD has been received",
                    );
                break;
            default:
                dd("Enter Notification Type [ 0 => ");
        }
        echo("end switch");
    }

    public function finishCaseCompletely($caseId)
    {
        $case = sCase::findOrFail($caseId);
        foreach ($case->jobs as $job) {
            $job->stage = 8;
            $job->assignee = Auth()->user()->id;
            $job->delivery_accepted = Auth()->user()->id;
            $job->save();

        }
        caseLog::insert([
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['MILLING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRINTING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['SINTERING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRESSING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 6, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 7, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['MILLING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRINTING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['SINTERING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRESSING_START'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 6, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 7, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_ASSIGN'], 'is_completion' => 0],
        ]);
        $this->issueInvoice($case->jobs[0]);
        return back()->with('success', 'Case is Active at Delivery Stage.');
    }

    public function sendCaseToStage($caseId)
    {
        $case = sCase::findOrFail($caseId);
        foreach ($case->jobs as $job) {
            $job->stage = 8;
            $job->assignee = Auth()->user()->id;
            $job->delivery_accepted = Auth()->user()->id;
            $job->save();
        }
        caseLog::insert([
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['MILLING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRINTING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['SINTERING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRESSING_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 6, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 7, 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_COMPLETE'], 'is_completion' => 1],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 1, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['MILLING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRINTING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['SINTERING_SET'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['PRESSING_START'], 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 6, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => 7, 'is_completion' => 0],
            ['user_id' => Auth()->user()->id, 'case_id' => $caseId, 'stage' => $this->stageActions['DELIVERY_ASSIGN'], 'is_completion' => 0],
        ]);
        $this->issueInvoice($case->jobs[0]);
        return back()->with('success', 'Case is Active at Delivery Stage.');
    }

    public function createDummyCase($stage = 1, $amount =1)
    {
        if ($stage > 8 || $stage < 1) { dd("-_-");}
        DB::beginTransaction();
        $faker = Faker::create();

        try {
            $faker = \Faker\Factory::create('ar_SA');
            while ($amount != -1) {
                $case = new sCase();
                $case->case_id = $faker->unique()->numerify('Y####');
                $case->patient_name = $faker->name;
                $case->doctor_id = $faker->numberBetween(1, 10);
                $case->impression_type = $faker->randomElement([1, 2, 3]);
                $case->initial_delivery_date = $faker->dateTimeBetween('now', '+1 month');
                $case->created_by = Auth::id() ?? 1;
                $case->save();// Generating random tags
               // $tags = range(1, 4);
              //  shuffle($tags);
//                foreach (array_slice($tags, 0, rand(1, 3)) as $tag) {
//                    $this->createTag($case, $tag);
//               // }// Creating random jobs
                $teeth = $faker->randomElement([0, 1]);
                $units = ($teeth == 0 ? $faker->randomElement(['1', '1,2', '1,2,3']) : null ?? $faker->randomElement(['upper', "lower,upper", "lower"]));
                $jobCount = rand(1, 2);
                for ($i = 0; $i < $jobCount; $i++) {

                    $newJob = new job([
                        'unit_num' => $units,
                        'type' => 1,
                        'color' => "A1",
                        'style' => "Single",
                        'abutment' => $faker->randomElement([0, 1]),
                        'implant' => $faker->randomElement([0, 1]),
                        'material_id' => $faker->randomElement(['1', '2', '6']),
                        'case_id' => $case->id,
                        'doctor_id' => $case->doctor_id,
                        'stage' => $stage,
                    ]);
                    $newJob->save();


//                    $material = Material::find(1,2,6)->first();
//                    if ($material) {
//                        $newJob->unit_price = $material->price;
//                        $newJob->save();
//                    }
                }// Adding a dummy note

                $amount--;
            }
            DB::commit();
            return "Dummy case(s) created successfully!";
        } catch (\Exception $e) {
            DB::rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    public function getTypesByMaterial($material_id)
    {
        $material = material::find($material_id);
        if (!$material) {
            return response()->json(['error' => 'Material not found'], 404);
        }

        $types = $material->types()->get(['id', 'name', 'description']);
        return response()->json($types);
    }

    public function validateCaseMaterials(Request $request)
    {
        try {
            $caseIds = $request->input('case_ids', []);
            $stage = $request->input('stage', '');

            if (empty($caseIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No case IDs provided'
                ]);
            }

            // Get all cases with their jobs and materials
            $cases = sCase::whereIn('id', $caseIds)
                ->with(['jobs.material'])
                ->get();

            if ($cases->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid cases found'
                ]);
            }

            // Extract unique materials from all jobs across all cases
            $uniqueMaterials = [];
            $uniqueMaterialIds = [];

            foreach ($cases as $case) {
                foreach ($case->jobs as $job) {
                    if ($job->material) {
                        $materialName = $job->material->name;
                        $materialId = $job->material->id;

                        if (!in_array($materialName, $uniqueMaterials)) {
                            $uniqueMaterials[] = $materialName;
                            $uniqueMaterialIds[] = $materialId;
                        }
                    }
                }
            }

            return response()->json([
                'success' => true,
                'unique_materials' => $uniqueMaterials,
                'unique_material_ids' => $uniqueMaterialIds,
                'case_count' => $cases->count(),
                'stage' => $stage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating materials: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get material types for a specific stage and cases
     * Filters materials by stage column and returns types from material_types pivot table
     */
    public function getMaterialTypesForStage(Request $request)
    {
        try {
            $stage = $request->input('stage');
            $caseIds = $request->input('case_ids', []);

            \Log::info('[BACKEND] getMaterialTypesForStage called', ['stage' => $stage, 'case_ids' => $caseIds]);

            if (empty($caseIds)) {
                \Log::warning('[BACKEND] No cases provided');
                return response()->json([
                    'success' => false,
                    'message' => 'No cases provided'
                ], 400);
            }

            // Map stage to materials table column
            $stageColumnMap = [
                'design' => 'design',
                'milling' => 'mill',
                '3dprinting' => 'print_3d',
                'sintering' => 'sinter_furnace',
                'pressing' => 'press_furnace',
                'finishing' => 'finish',
                'qc' => 'qc',
                'delivery' => 'delivery'
            ];

            if (!isset($stageColumnMap[$stage])) {
                \Log::warning('[BACKEND] Invalid stage', ['stage' => $stage]);
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid stage'
                ], 400);
            }

            $stageColumn = $stageColumnMap[$stage];
            \Log::info('[BACKEND] Stage column mapped', ['stage' => $stage, 'column' => $stageColumn]);

            // Get all cases with their jobs and materials
            $cases = sCase::whereIn('id', $caseIds)
                ->with(['jobs.material'])
                ->get();

            \Log::info('[BACKEND] Cases retrieved', ['count' => $cases->count()]);

            if ($cases->isEmpty()) {
                \Log::warning('[BACKEND] No valid cases found');
                return response()->json([
                    'success' => false,
                    'message' => 'No valid cases found'
                ], 400);
            }

            // Find the shared material among all selected cases
            // Get unique material IDs from all jobs
            $materialIds = [];
            foreach ($cases as $case) {
                foreach ($case->jobs as $job) {
                    if ($job->material) {
                        $materialIds[] = $job->material->id;
                    }
                }
            }

            $materialIds = array_unique($materialIds);
            \Log::info('[BACKEND] Material IDs collected', ['material_ids' => $materialIds, 'count' => count($materialIds)]);

            if (empty($materialIds)) {
                \Log::warning('[BACKEND] No materials found');
                return response()->json([
                    'success' => false,
                    'message' => 'No materials found in selected cases'
                ], 400);
            }

            // FIRST: Filter materials by which ones go through this stage
            $validMaterialIds = [];
            $validMaterialNames = [];

            foreach ($materialIds as $materialId) {
                $material = \App\material::find($materialId);
                if (!$material) {
                    \Log::warning('[BACKEND] Material not found, skipping', ['material_id' => $materialId]);
                    continue;
                }

                \Log::info('[BACKEND] Checking material', [
                    'material_id' => $material->id,
                    'material_name' => $material->name,
                    'stage_column' => $stageColumn,
                    'stage_column_value' => $material->$stageColumn
                ]);

                // Only include materials that go through this stage
                if ($material->$stageColumn) {
                    $validMaterialIds[] = $materialId;
                    $validMaterialNames[] = $material->name;
                    \Log::info('[BACKEND] Material goes through this stage', ['material' => $material->name]);
                } else {
                    \Log::info('[BACKEND] Material does not go through this stage, filtering out', [
                        'material' => $material->name,
                        'stage' => $stage
                    ]);
                }
            }

            \Log::info('[BACKEND] Materials after stage filtering', [
                'valid_materials' => $validMaterialNames,
                'count' => count($validMaterialIds)
            ]);

            // SECOND: Now check if there's exactly one shared material for this stage
            if (empty($validMaterialIds)) {
                \Log::warning('[BACKEND] No materials go through this stage');
                return response()->json([
                    'success' => false,
                    'message' => 'No materials from selected cases go through this stage'
                ], 400);
            }

            if (count($validMaterialIds) > 1) {
                \Log::warning('[BACKEND] Multiple materials found for this stage', [
                    'materials' => $validMaterialNames,
                    'material_ids' => $validMaterialIds
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Multiple materials found in selected cases'
                ], 400);
            }

            // THIRD: We have exactly one material - get its types
            $sharedMaterialId = $validMaterialIds[0];
            $sharedMaterialName = $validMaterialNames[0];

            \Log::info('[BACKEND] Single shared material found', [
                'material_id' => $sharedMaterialId,
                'material_name' => $sharedMaterialName
            ]);

            // Get types for this material from material_types pivot table
            \Log::info('[BACKEND] Querying material_types pivot table', [
                'material_id' => $sharedMaterialId
            ]);

            $types = \DB::table('material_types')
                ->join('types', 'material_types.type_id', '=', 'types.id')
                ->where('material_types.material_id', $sharedMaterialId)
                ->where('types.is_enabled', true)
                ->select('types.id', 'types.name', 'material_types.material_id')
                ->get();

            \Log::info('[BACKEND] Types query result', [
                'types_count' => $types->count(),
                'types' => $types->toArray()
            ]);

            // Check if material type should be selected at this stage
            $material = \App\material::find($sharedMaterialId);
            $typeSelectionStage = $material->type_selection_stage;
            $shouldShowTypes = false;

            if (!empty($typeSelectionStage)) {
                // Material has specific stage configured - only show if current stage matches
                $shouldShowTypes = ($typeSelectionStage === $stage);
            } else {
                // No specific stage configured - show for default stages (milling, 3dprinting, pressing)
                $shouldShowTypes = in_array($stage, ['milling', '3dprinting', 'pressing']);
            }

            $response = [
                'success' => true,
                'material_id' => $sharedMaterialId,
                'material_name' => $sharedMaterialName,
                'stage' => $stage,
                'type_selection_stage' => $typeSelectionStage,
                'should_show_types' => $shouldShowTypes,
                'types' => $types
            ];

            \Log::info('[BACKEND] Returning response', ['response' => $response]);

            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('[BACKEND] Exception in getMaterialTypesForStage', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error getting material types: ' . $e->getMessage()
            ], 500);
        }
    }
}
