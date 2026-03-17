<?php
namespace App\Http\Controllers;
use App\abutment;
use App\client;
use App\galleryMedia;
use App\implant;
use App\invoice;
use App\JobType;
use App\material;
use App\materialJobtype;
use App\MobileNotificationToken;
use App\payment;
use App\sCase;
use App\mobileJobModel;
use App\signInLog;
use App\User;
use DateTime;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Sodium\add;

class ApiController {

    // AUTHENTICATIONS
    public function login(Request $request){
        $phoneInput = $request->phoneNum ?? $request->phone ?? $request->phone_number ?? $request->phone_num;
        if (!$phoneInput) {
            return response()->json(['msg' => "phoneNum is required"], 422);
        }

        $phoneNumber = $this->normalizePhoneNum($phoneInput);
        $password = $request->password;
        $device = $request->device ?? $request->deviceId ?? $request->device_id ?? "0";
        $ipAddress = $request->ip();


        //code:
        $docAccount = client::where('phone', 'like', '%' . $phoneNumber . '%')->first();
        if(!$docAccount) {
            $clinicAccount = client::Where('clinic_phone', 'like', '%' . $phoneNumber . '%')->first();
            if(!$clinicAccount)
                return response()->json(['msg' => "Phone number not found"], 403);
            if(password_verify($password, $clinicAccount->clinic_password)){
                MobileNotificationToken::where('device_id', (string) $device)->delete();
                $this->safeLogMobileSignin($clinicAccount->id, true, $ipAddress, (string) $device);

                return $clinicAccount;
            }

            else
                return response()->json(['msg' => "Invalid clinic account password"], 403);
        }
        else{
            if(password_verify($password,$docAccount->doc_password)){
                MobileNotificationToken::where('device_id', (string) $device)->delete();
                $this->safeLogMobileSignin($docAccount->id, false, $ipAddress, (string) $device);
                return $docAccount;
            }
            else
                return response()->json(['msg' => "Invalid doctor account password"], 403);
        }
    }



    public function authenticatedClient(String $encryptedPhoneNum){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();

        return $client;
    }
    public function decryptedPhoneNum(String $encryptedPhoneNum)
    {
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $phoneNum = openssl_decrypt($encryptedPhoneNum, $method, $key, 0, $iv);
        $phoneNum = substr(trim($phoneNum), -7);
        return $phoneNum;// to remove +962
    }

    private function normalizePhoneNum($phoneInput): string
    {
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";

        $raw = trim((string) $phoneInput);
        $decrypted = openssl_decrypt($raw, $method, $key, 0, $iv);
        $phoneNum = ($decrypted === false || trim((string) $decrypted) === '') ? $raw : (string) $decrypted;
        $phoneNum = preg_replace('/\\D+/', '', $phoneNum);
        return substr(trim((string) $phoneNum), -7);
    }

    private function safeLogMobileSignin(int $clientId, bool $isClinic, string $ipAddress, string $device): void
    {


        try {
            $alreadyLoggedRecently = signInLog::where('client_id', $clientId)
                ->where('is_clinic', $isClinic ? 1 : 0)
                ->where('date', '>=', now()->subSeconds(30)->toDateTimeString())
                ->exists();

            if ($alreadyLoggedRecently) {
                \Log::info('safeLogMobileSignin multiple,returning');
                return;
            }

            $log = new signInLog();
            $log->client_id = $clientId;
            $log->ip_address = $ipAddress;
            $log->device = $device;
            $log->date = now();
            $log->is_clinic = $isClinic ? 1 : 0;
            $log->save();


        } catch (\Throwable $e) {
            \Log::info('safeLogMobileSignin Exception ' . $e->getMessage());

            // Never block mobile login if logging fails.
        }
    }
    public function clientInfo(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962

        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->orWhere('clinic_phone', 'like', '%' . $phoneNum . '%')->first();
        if(!isset($client)) return response()->json(['msg' => "No Client Found"], 403);
        return $client->toJson();
    }
    public function checkPhoneNumber(Request $request){
        $phoneNum = substr(trim($request->phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->orWhere('clinic_phone', 'like', '%' . $phoneNum . '%')->first();
        if(!$client)
            return false;
        return true;
    }
    public function setNotificationToken(Request $request)
    {
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum = openssl_decrypt($encryptedPhoneNum, $method, $key, 0, $iv);
        $phoneNum = substr(trim($phoneNum), -9); // to remove +962
        $deviceId = $request->device_id ?? 0;

       //  $docClient = DB::select('SELECT * FROM clients WHERE phone LIKE ? LIMIT 1', ['%' . $phoneNum . '%']);
       //   $clinicAccount = DB::select('SELECT * FROM clients WHERE clinic_phone LIKE ? LIMIT 1', ['%' . $phoneNum . '%']);

        $clinicAccount = client::where('clinic_phone', 'like', '%' . trim($phoneNum) . '%')->get()->first();
        $docClient = client::where('phone', 'like', '%' . trim($phoneNum) . '%')->get()->first();

       // return substr(trim($phoneNum), -9);
            if (isset($docClient)) {
                MobileNotificationToken::create([
                    'client_id' => $docClient->id,
                    'token' => $request->token,
                    'device_id' => $request->device_id,
                    'is_clinic' => 0
                ]);
            }
            if (isset($clinicAccount)) {
                MobileNotificationToken::create([
                    'client_id' => $clinicAccount->id,
                    'token' => $request->token,
                    'device_id' => $request->device_id,
                    'is_clinic' => 1
                ]);
            }

            if (isset($docClient))
                return response()->json(['msg' => "Doctor Account Token Updated"], 404);
            if (isset($clinicAccount))
                return response()->json(['msg' => "Clinic Account Token Updated"], 404);

    }
    public function removeNotificationToken (Request $request){
        MobileNotificationToken::where('device_id', $request->deviceId)->delete();
        return response()->json(['msg' => "Removed Not. Tokens, device id  :".  $request->deviceId], 200);
        //       $client = client::where("id",$request->docId)->first();
//        if($client){
//            if($request->accountType == 0 ||$request->accountType == "0")
//            {
//                MobileNotificationToken::where([
//
//                    'client_id' => $client->id,
//                    'device_id' => $request->deviceId ,
//                    'is_clinic' => 1
//                ])->delete();
//                return response()->json(['msg' => "Removed Clinic Not. Token, Doc :". $client->name], 404);}
//            else{
//                MobileNotificationToken::where([
//
//                    'client_id' => $client->id,
//                    'device_id' => $request->deviceId ,
//                    'is_clinic' => 0
//                ])->delete();
//                return response()->json(['msg' => "Removed Doctor Not. Token, Doc :". $client->name], 404);
//            }
//        }
    }

    ///////////// PERFORMANCE REPORTS///////////////
    // Filtration implemented
    public function QCReport(Request $request){
        if (!$request->month)
            return response()->json(['msg' => "No Date Provided"], 400);

        $client = $this->authenticatedClient($request->phoneNum);
        if(!$client)
            return response()->json(['msg' => "No Client Found"], 400);

        // REPORT CODE BELOW
        $successfulUnits = $client->getFailedUnitsCount($request->month,4,true);
        $repeatedUnits = $client->getFailedUnitsCount($request->month,1,true);
       // $successfulUnits -= $repeatedUnits;
        return json_encode([$successfulUnits,$repeatedUnits]);
    }
    public function QCResults(Request $request){
        if (!$request->month)
            return response()->json(['msg' => "No Date Provided"], 400);

        $client = $this->authenticatedClient($request->phoneNum);
        if(!$client)
            return response()->json(['msg' => "No Client Found"], 400);

        // REPORT CODE BELOW
        $successfulUnits = $client->getFailedUnitsCount($request->month,4,true);
        $repeatedUnits = $client->getFailedUnitsCount($request->month,1,true);
        // $successfulUnits -= $repeatedUnits;
        return json_encode([$successfulUnits,$repeatedUnits]);
    }
    // Filtration implemented
    public function jobTypesReport(Request $request, $isFiltered = false){
        if (!$request->month)
            return response()->json(['msg' => "No Date Provided"], 400);

        $client = $this->authenticatedClient($request->phoneNum);
        if(!$client)
            return response()->json(['msg' => "No Client Found"], 400);



        // REPORT CODE BELOW
        $jobTypes = [1,2,3,6];
        $counts = [];
        foreach($jobTypes as $jobType) {

            if ($isFiltered &&$jobType->material->count_in_job_types_report)
                array_push($counts, [$jobType => $client->numOfUnitsByJobType($jobType, $request->month, true)]);
            else
                array_push($counts, [$jobType => $client->numOfUnitsByJobType($jobType, $request->month, true)]);


        }
        return json_encode($counts);
    }

    // Filtration implemented
    public function unitsCountReport(Request $request){
        if (!$request->month)
            return response()->json(['msg' => "No Date Provided"], 400);
        $client = $this->authenticatedClient($request->phoneNum);
        if(!$client)
            return response()->json(['msg' => "No Client Found"], 400);


        $materials = material::all();
        $counts = [];
        foreach($materials as $material){
            if($material->count_in_units_counts_report)
            array_push($counts ,[$material->id => $client->numOfUnitsByMaterial($material->id,$request->month)]);
            else
            array_push($counts ,[$material->id => 0]);
        }
        $frameTitu = $counts[17]["18"];
        $barTitu = $counts[18]["19"];
        $total = $frameTitu + $barTitu;
        $counts[17] = [18 => $total];


        ////////////////////////
        $zircon = $counts[0]["1"];
        $zirconExpress = $counts[19]["20"];
        $zircons = $zircon + $zirconExpress;
        $counts[0] = [1 => $zircons];
        // Move E-max cad to index 18 :
        $counts[18] = [19=>$counts[22]["23"]];
        /////////////////////////////////////
        /// Acrylics
        ////////////////////////////
        $acrylic = $counts[2]["3"];
        $acrylic3D = $counts[5]["6"];

        $totalAcrylic = $acrylic + $acrylic3D;
        $counts[2] = [3 => $totalAcrylic];

      return json_encode($counts);
    }
    // Filtration implemented
    public function implantsReport(Request $request){

        if (!$request->month)
            return response()->json(['msg' => "No Date Provided"], 400);
        $client = $this->authenticatedClient($request->phoneNum);
        if(!$client)
            return response()->json(['msg' => "No Client Found"], 400);


        // REPORT CODE BELOW
        $implantsIds = implant::all()->pluck("id")->toArray();
        $abutmentTypes = abutment::all()->pluck("id")->toArray();
        $counts =[];


        // filters data based on count in implants report in the model itself
        foreach($abutmentTypes as $type){
           //  if($type ==2)
           //     dd($client->numOfUnitsBy_abutment_implants($type,$implantsIds,$request->month,false));
         array_push($counts ,[$type => $client->numOfUnitsBy_abutment_implants($type,$implantsIds,$request->month,true)]);
        }

        return json_encode($counts);
    }


    public function statementOfAccount( Request $request)
    {

        if ($request->month) {
            $from = $request->month . '-01 00:00:00' ;
            $date = new DateTime($request->month.'-01');
            $date->modify('last day of this month');
            $last_day_this_month = $date->format('Y-m-d');
            $to = $last_day_this_month. ' 23:59:59' ;
        }else
            return response()->json(['msg' => "No Date Provided"], 400);
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();

        $invoices = invoice::select('invoices.*', 'cases.patient_name')->where("invoices.doctor_id", $client->id)->where('status',1)->whereBetween('date_applied', [$from , $to ])
            ->join('cases', 'cases.id', '=', 'invoices.case_id')->get();

        $discounts =invoice::where("invoices.doctor_id", $client->id)->where("case_id",0)->whereBetween('date_applied', [$from , $to ])->get();
        $invoices =  $invoices->merge($discounts);
        $payments = payment::where("doctor_id", $client->id)->whereBetween('created_at', [$from . ' 00:00', $to . ' 23:59'])->get();
        // toBase() to prevent id overwriting.

        $transactions =  $invoices->toBase()->merge($payments)
            ->transform( function ($item) {
                if(!empty($item->date_applied)) {
                    $item->created_at = $item->date_applied;
                }
                else if(!empty($item->case->actual_delivery_date))
                {
                    $item->created_at = $item->case->actual_delivery_date;
                }
//                 if(!empty($item->case->actual_delivery_date)) {
//                     $item->created_at = $item->case->actual_delivery_date;
//                 }
                return $item;
            })->sortBy('created_at')->toJson();

        return $transactions;
    }
    public function getCurrentBalance(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962

        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();
        $currentBalance = $client->balanceAt(now());
        return $currentBalance;
    }
    public function openingBalance(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962

        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();

        $amountDuePreDate = invoice::where("doctor_id", $client->id)->where('date_applied','<',$request->month . '-01 00:00')->where('status',1)->sum('amount');
        $amountPaidPreDate =  payment::where("doctor_id", $client->id)->where('created_at','<',$request->month . '-01 00:00')->sum('amount');

        $openingBalance  =$amountDuePreDate - $amountPaidPreDate;
        return $openingBalance;
    }
    public function getJobs(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();

        if(!$client) return response()->json(['msg' => "No Client Found"], 403);

        $jobs = [];
        $case = sCase::find($request->case_id);
        foreach($case->jobs  as $job){
           $jobModel = new MobileJobModel();
           $jobModel->material = $job->material->name;
           $jobModel->jobType = $job->jobType->name;
            $jobModel->hasBeenRejected = $job->has_been_rejected;
            $jobModel->isRejection = $job->is_rejection;
           $jobModel->qty = count(explode(",",$job->unit_num));
           $jobModel->total =($job->material->price *$jobModel->qty) - $this->getDiscount($job,$case);
           array_push($jobs,$jobModel);

        }

        return json_encode($jobs);
    }
    public function getDiscount($job,$case){
        $discounts = $case->client->discounts;
        $discountOfMaterial = $discounts->where('material_id', $job->material_id)->first();
        // No Discount
        if(!$discountOfMaterial) return 0;

        //Fixed Discount
        if ($discountOfMaterial && $discountOfMaterial->type === 0) {
            return count(explode(',',$job->unit_num)) *$discountOfMaterial->discount;

            // Percentage Discount
        } else if ($discountOfMaterial->type) {
            return  (count(explode(',',$job->unit_num))) * ($job->material->price * ($discountOfMaterial->discount / 100));
        }
    }
    public function getInProgressCases(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->orWhere('clinic_phone', 'like', '%' . $phoneNum . '%')->first();

        if(!$client) return response()->json(['msg' => "No Client Found"], 403);
        $from =today()->modify("first day of this month")->subMonth(1)->toDateString(). ' 00:00:00';
        $to = today()->toDateString() . ' 23:59:00';
        $cases = sCase::where("doctor_id",$client->id)->whereNull("actual_delivery_date")->whereBetween("created_at",[$from,$to])->orderBy("initial_delivery_date","ASC")->get()->makeVisible(['created_at','updated_at']);
        return $cases->toJson();
    }
    public function getCompletedCases(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->orWhere('clinic_phone', 'like', '%' . $phoneNum . '%')->first();

        if(!$client) return response()->json(['msg' => "No Client Found"], 403);
        $from =today()->modify("first day of this month")->subMonth(1)->toDateString(). ' 00:00:00';
        $to = today()->toDateString() . ' 23:59:00';
        $cases = sCase::where("doctor_id",$client->id)->whereNotNull("actual_delivery_date")->whereBetween("created_at",[$from,$to])->orderBy("actual_delivery_date","DESC")->get()->makeVisible(['created_at','updated_at']);
        return $cases->toJson();
    }
    public function getEmployees(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962
        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->orWhere('clinic_phone', 'like', '%' . $phoneNum . '%')->first();

        if(!$client) return response()->json(['msg' => "No Client Found"], 403);
        $emps = User::get(['id','name_initials']);
        return $emps->toJson();
    }
    public function getGalleryItems(Request $request){
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $encryptedPhoneNum = $request->phoneNum;
        $phoneNum=  openssl_decrypt($encryptedPhoneNum,$method,$key,0,$iv);
        $phoneNum = substr(trim($phoneNum), -7); // to remove +962

        $client = client::where('phone', 'like', '%' . $phoneNum . '%')->first();
        if(!$client) return response()->json(['msg' => "No Client Found"], 403);

        $galleryItems = galleryMedia::all();
        return $galleryItems->toJson();
    }

    public function logSignin(Request $request)
    {
        $key = "SIGMA_Encryption_5ng853ld9f531g4";
        $iv = "gm5kmd9ek3mz9dmg";
        $method = "aes-256-cbc";
        $phoneInput = $request->phoneNum ?? $request->phone ?? $request->phone_number ?? $request->phone_num;
        if (!$phoneInput) {
            return response()->json(['success' => false, 'created' => 0, 'msg' => "phoneNum is required"], 200);
        }

        $decrypted = openssl_decrypt($phoneInput, $method, $key, 0, $iv);
        $phoneNum = ($decrypted === false || trim((string)$decrypted) === '') ? $phoneInput : $decrypted;
        $phoneNum = preg_replace('/\\D+/', '', (string)$phoneNum);
        $phoneNum = substr(trim((string)$phoneNum), -7); // to remove +962

        $device = $request->device ?? $request->deviceId ?? $request->device_id ?? "0";
        $ipAddress = $request->ip();


        $docClient = client::where('phone', 'like', '%' . $phoneNum . '%')->first();
        $clinicAccount = client::Where('clinic_phone', 'like', '%' . $phoneNum . '%')->first();
        if (!$docClient && !$clinicAccount)
            return response()->json(['msg' => "No Client Found"], 400);

        $created = 0;
        try {
            DB::transaction(function () use ($docClient, $clinicAccount, $ipAddress, $device, $created) {
                if (isset($docClient)) {
                    $log = new signInLog();
                    $log->client_id = $docClient->id;
                    $log->ip_address = $ipAddress;
                    $log->device = (string)$device;
                    $log->date = now();
                    $log->is_clinic = 0;
                    $log->save();
                    $created++;
                }
                if (isset($clinicAccount)) {
                    $log = new signInLog();
                    $log->client_id = $clinicAccount->id;
                    $log->ip_address = $ipAddress;
                    $log->device = (string)$device;
                    $log->date = now();
                    $log->is_clinic = 1;
                    $log->save();
                    $created++;
                }
            });
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'created' => 0], 200);
        }

        return response()->json(['success' => true, 'created' => $created], 200);
    }

    /**
     * Get materials used in specific cases for workflow stages
     */
    public function getCaseMaterials(Request $request)
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

            // Map stage names to database values for job filtering
            // Based on workflow stages: 1=Design, 2=Milling, 3=3D Printing, 4=Sintering, 5=Pressing, 6=Finishing, 7=QC, 8=Delivery
            $stageMapping = [
                'milling' => 2,
                '3dprinting' => 3,
                'sintering' => 4,
                'pressing' => 5
            ];
            
            $dbStage = $stageMapping[$stage] ?? $stage;

            // Get materials from jobs in the CURRENT STAGE only
            $materials = DB::table('jobs')
                ->join('cases', 'jobs.case_id', '=', 'cases.id')
                ->whereIn('cases.id', $caseIds)
                ->where('jobs.stage', $dbStage)
                ->whereNotNull('jobs.material_id')
                ->distinct()
                ->pluck('jobs.material_id')
                ->toArray();

            // Debug logging
//            \Log::info("Material validation debug", [
//                'stage' => $stage,
//                'dbStage' => $dbStage,
//                'caseIds' => $caseIds,
//                'materials' => $materials
//            ]);

            // Check if all materials are the same (validation for same-material requirement)
            $uniqueMaterials = array_unique($materials);
            $allSameMaterial = count($uniqueMaterials) <= 1;

            return response()->json([
                'success' => true,
                'material_ids' => array_map('intval', $materials),
                'unique_materials' => array_map('intval', $uniqueMaterials),
                'all_same_material' => $allSameMaterial,
                'stage_checked' => $dbStage
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting case materials: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCaseMaterialTypes(Request $request)
    {
        try {
            $materialId = $request->input('material_id');

            if (!$materialId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No material ID provided'
                ]);
            }

            // Get types for the specific material using the pivot table
            $materialTypes = DB::table('types')
                ->join('material_types', 'types.id', '=', 'material_types.type_id')
                ->join('materials', 'material_types.material_id', '=', 'materials.id')
                ->where('material_types.material_id', $materialId)
                ->where('types.is_enabled', true)
                ->select('types.id', 'types.name', 'materials.id as material_id', 'materials.name as material_name')
                ->distinct()
                ->orderBy('types.name')
                ->get();

            // Debug logging
//            \Log::info("Material types debug", [
//                'materialId' => $materialId,
//                'foundTypes' => $materialTypes->count(),
//                'types' => $materialTypes->toArray()
//            ]);

            $materialTypes = $materialTypes->map(function($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'material_id' => $type->material_id,
                        'material_name' => $type->material_name
                    ];
                });

            return response()->json([
                'success' => true,
                'types' => $materialTypes,
                'material_id' => $materialId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting material types: ' . $e->getMessage()
            ], 500);
        }
    }
}
