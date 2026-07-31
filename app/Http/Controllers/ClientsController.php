<?php
/**
 * User: Yazan
 * Date: 10/4/2021
 * Time: 8:36 PM
 */
namespace App\Http\Controllers;
use App\Http\Traits\helperTrait;
use App\material;
use App\JobType;
use App\client;
use App\invoice;
use App\MobileNotificationToken;
use App\payment;
use App\bank;
use App\clientDiscount;
use App\sCase;
use App\Services\DoctorStatementService;
use Illuminate\Http\Request;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\HandlerStack;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\HeaderUtils;

class ClientsController extends Controller
{
    use helperTrait;

    private DoctorStatementService $doctorStatementService;

    public function __construct(DoctorStatementService $doctorStatementService)
    {
        $this->doctorStatementService = $doctorStatementService;
    }
//    public function index(Request $request)
//    {
//
//
//        if ($request->doctor &&  !in_array( "all",$request->doctor) ) {
//            $clients = client::whereIn('id', $request->doctor)->get();
//            $selectedClients =  $request->doctor ;
//
//        } else {
//
//        $clients = client::all();
//        $selectedClients = null;
//        }
//        if ($request->from)
//            $from = $request->from;
//         else
//            $from = now()->toDateString() . ' 23:59';
//        $status = $request->active  == 1 ? 1: 0 ;
//       $clients =   $clients->where('active',$status) ;
//        $totalBalance=0;
//            foreach($clients as $client)
//            $totalBalance = $totalBalance + $client->balanceAt($from);
//
//         //dd($from);
//        $allClients = client::all();
//        $banks = bank::all();
//        return view('clients.index',compact('allClients',"clients",'banks','selectedClients','from','totalBalance'))->with('status', $status);
//    }
    public function index(Request $request)
    {
        $from = $request->from ?? now()->toDateString() . ' 23:59';
        $status = $request->has('active') ? (int) $request->active : 1;

        $query = Client::query();

        $query->where('active', $status);

        if ($request->doctor && !in_array('all', $request->doctor)) {
            $query->whereIn('id', $request->doctor);
            $selectedClients = $request->doctor;
        } else {
            $selectedClients = null;
        }

        $clients = $query->get(['id', 'name', 'active', 'balance']);

        $totalBalance = $this->attachClientBalances($clients, $from);

        $allClients = Client::query()->select(['id', 'name'])->orderBy('name')->get();
        $enabledClients = Client::query()
            ->where('active', 1)
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();
        $banks = Bank::query()->select(['id', 'bank_name'])->get();

        return view('clients.index', compact(
            'allClients',
            'enabledClients',
            'clients',
            'banks',
            'selectedClients',
            'from',
            'totalBalance'
        ))->with('status', $status);

    }

    private function attachClientBalances($clients, string $from): float
    {
        if ($clients->isEmpty()) {
            return 0;
        }

        $clientIds = $clients->pluck('id');

        $invoiceTotals = invoice::query()
            ->selectRaw('doctor_id, SUM(amount) as total_amount')
            ->where('status', 1)
            ->whereIn('doctor_id', $clientIds)
            ->where('date_applied', '<=', $from)
            ->groupBy('doctor_id')
            ->pluck('total_amount', 'doctor_id');

        $paymentTotals = payment::query()
            ->selectRaw('doctor_id, SUM(amount) as total_amount')
            ->whereIn('doctor_id', $clientIds)
            ->where('created_at', '<=', $from)
            ->groupBy('doctor_id')
            ->pluck('total_amount', 'doctor_id');

        $totalBalance = 0;

        foreach ($clients as $client) {
            $balance = (float) ($invoiceTotals[$client->id] ?? 0) - (float) ($paymentTotals[$client->id] ?? 0);
            $client->setAttribute('display_balance', $balance);
            $totalBalance += $balance;
        }

        return $totalBalance;
    }

    public function returnCreate()
    {
        $jobTypes =  JobType::all();
        $materials =  material::all();
        return view('clients.create',compact('jobTypes','materials'));
    }
    public function create(Request $request)
    {
    $this->validate($request, [
            'dentist_name'     => 'required|max:30',
            'phone_number'    => 'required',
            'address'  => 'required',
            //'discount' => 'required|numeric|min:0',
            //'type'     => 'required',
    ]);

        $dentist = new client();
        $dentist->name = $request->dentist_name;
        $dentist->phone = $request->phone_number;
        $dentist->clinic_phone = $request->clinic_phone;
        $dentist->address = $request->address;

        // Set passwords if provided
        if (!empty($request->doc_password)) {
            $dentist->doc_password = Hash::make($request->doc_password);
        }
        if (!empty($request->clinic_password)) {
            $dentist->clinic_password = Hash::make($request->clinic_password);
        }

        $dentist->save();
        foreach ($request->repeat as $rep) {
            if(isset($rep['discount'])){
                $discount = new clientDiscount();
                $discount->type = $rep['type'];
                $discount->discount = $rep['discount'];
                $discount->material_id = $rep['material'];
                $discount->client_id = $dentist->id;
                $discount->save();
            }
        }
        return back()->with('success', 'Doctor has been successfully created');
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:30',
            'phone'    => 'required',
            'address'  => 'required',
        ]);

        $doctor = client::where('id', $request->id)->first();
        if (!$doctor) {
            abort(404);
        }

        // Only update fields that have changed
        if ($doctor->name !== $request->name) {
            $doctor->name = $request->name;
        }
        if ($doctor->phone !== $request->phone) {
            $doctor->phone = $request->phone;
        }
        if ($doctor->clinic_phone !== $request->clinic_phone) {
            $doctor->clinic_phone = $request->clinic_phone;
        }
        if ($doctor->address !== $request->address) {
            $doctor->address = $request->address;
        }

        // Only update passwords if they are provided (not empty)
        if (!empty($request->doc_password)) {
            $doctor->doc_password = Hash::make($request->doc_password);
        }
        if (!empty($request->clinic_password)) {
            $doctor->clinic_password = Hash::make($request->clinic_password);
        }

        $doctor->save();
        clientDiscount::where('client_id', $request->id)->delete();
        if (is_array($request->ids)) {
            foreach ($request->ids as $mat) {
                $discount = new clientDiscount();
                $o_type = "old_type_".$mat;
                $o_discount = "old_discount_".$mat;
                $o_material = "old_material_".$mat;
                $discount->type = $request->$o_type[0];
                $discount->discount = $request->$o_discount[0];
                $discount->material_id = $request->$o_material[0];
                $discount->client_id = $request->id;
                $discount->save();
            }
        }
        if(is_array($request->repeat)){
            foreach ($request->repeat as $rep) {
                if(!isset($rep["type"]) || !isset($rep['discount']) || !isset($rep['material'])){
                    continue;
                }
                $discount = new clientDiscount();
                $discount->type = $rep['type'];
                $discount->discount = $rep['discount'];
                $discount->material_id = $rep['material'];
                $discount->client_id = $doctor->id;
                $discount->save();
            }
        }
        return back()->with('success', 'Doctor has been successfully updated');
    }
    public function view($id)
    {
        $user = client::with('discounts')->where('id', $id)->first();
        if (!$user) {
            abort(404);
        }
        $materials = material::all();
        return view('clients.view-edit')->with('user', $user)->with('materials', $materials);
    }
    public function statementOfAccount($id, Request $request)
        {
            if($request->allTime == 1){
                $from = date('Y-m-d', strtotime('01-01-2021'));
                $to = now()->toDateString();

            }
            else if ($request->from && $request->to) {
                $from = $request->from ;
                $to = $request->to ;
            }
            else {
                $from = date('Y-m-d', strtotime('first day of this month'));
                $to = now()->toDateString();
            }
        $client = client::findOrFail($id);

        return view('clients.statement', $this->doctorStatementService->build($client, $from, $to));
        }

    public function downloadStatementPdf($doctor, Request $request)
    {
        $validated = $request->validate([
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $client = client::query()
            ->whereKey($doctor)
            ->where('active', 1)
            ->firstOrFail();

        $statementData = $this->doctorStatementService->build(
            $client,
            $validated['from'],
            $validated['to']
        );
        $tempDirectory = storage_path('framework/cache/mpdf');
        File::ensureDirectoryExists($tempDirectory);

        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];
        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];
        $cairoFontDirectory = public_path('assets/fonts/cairo');
        $useCairoFont = File::exists($cairoFontDirectory . '/Cairo-Regular.ttf')
            && File::exists($cairoFontDirectory . '/Cairo-Bold.ttf');

        $mpdfConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => $tempDirectory,
            'default_font' => $useCairoFont ? 'cairo' : 'dejavusans',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 12,
        ];

        if ($useCairoFont) {
            $mpdfConfig['fontDir'] = array_merge($fontDirs, [
                $cairoFontDirectory,
            ]);
            $mpdfConfig['fontdata'] = $fontData + [
                'cairo' => [
                    'R' => 'Cairo-Regular.ttf',
                    'B' => 'Cairo-Bold.ttf',
                    'useOTL' => 0xFF,
                    'useKashida' => 75,
                ],
            ];
        }

        $pdf = new Mpdf($mpdfConfig);
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = false;
        $pdf->SetTitle('Statement of Account - ' . $client->name);
        $logoPath = public_path('assets/img/green-pdf.jpg');
        $logoMimeType = 'image/jpeg';
        if (!File::exists($logoPath)) {
            $logoPath = public_path('assets/img/green.png');
            $logoMimeType = 'image/png';
        }
        $logoSrc = File::exists($logoPath)
            ? 'data:' . $logoMimeType . ';base64,' . base64_encode(File::get($logoPath))
            : null;

        $pdf->SetHTMLHeader(view('clients.statement-pdf-header', [
            'client' => $client,
            'from' => $validated['from'],
            'to' => $validated['to'],
            'logoSrc' => $logoSrc,
        ])->render());
        $pdf->WriteHTML(view('clients.statement-pdf', $statementData)->render());

        $fileName = $this->doctorStatementService->fileName(
            $client,
            $validated['from'],
            $validated['to']
        );
        $fallbackName = "statement-doctor-{$client->id}-{$validated['from']}-to-{$validated['to']}.pdf";

        return response($pdf->Output('', Destination::STRING_RETURN), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => HeaderUtils::makeDisposition('attachment', $fileName, $fallbackName),
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }

    public function quickAccessDS(Request $request){
        $doctorQuery = client::query();
        $searchValues = preg_split('/\s+/', $request->docNameSearchText, -1, PREG_SPLIT_NO_EMPTY);
        $doctor = $doctorQuery->where(function ($q) use ($searchValues): void {
            foreach ($searchValues as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->first();

        if(!$doctor) return back()->with("error","no matching doctor found.");
        else
            return $this->statementOfAccount($doctor->id,$request);
    }
    public function statementOfAccountWithFilters(Request $request)
    {
        /*
        if ($request->from && $request->to)
        {}
        else {}
        $client = client::findOrFail($id);
        $invoices = invoice::where("doctor_id", $id)->get();
        $payments = payment::where("doctor_id", $id)->get();
        $transactions =  $invoices->merge($payments)->sortBy('created_at');
        return view("clients.statement",compact('invoices','client','payments','transactions'));
        */
    }
    public function newPayment(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'amount' => 'required|integer|min:0',
            'payment_type' => 'required|in:cash,cheque,transfer',
            'bank_id' => 'nullable|required_if:payment_type,cheque|exists:banks,id',
            'chequeNumber' => 'nullable|required_if:payment_type,cheque|string|max:255',
        ]);
        $amount = (int) $request->amount;
        $doctor = client::where('id', $request->id)->first();

        if(!$doctor){
            return back()->with('error', "Doctor not found");
        }

        $doctor->balance = $doctor->balance - $amount;
        $doctor->save();

        $payment = new payment();
        $payment->amount = $amount;
        $payment->collector = Auth()->user()->id;
        if($request->payment_type == 'cash'){
            $payment->notes = "دفعة نقدية";
        }
        else if($request->payment_type == 'transfer'){
            $payment->notes = "حوالة بنكية/ كليك";
        }
        else
        {
            $bank = bank::where('id' , $request->bank_id)->first();
            $payment->from_bank = $bank->id;
            $payment->notes =  $request->chequeNumber .' '. $bank->bank_abbrev  . ' شيك ';
        }
        $payment->doctor_id = $doctor->id;
        $payment->additional_notes = $request->note;
        $payment->save();
        $clientTokens = MobileNotificationToken::where("client_id",$doctor->id)->where("is_clinic",0)->get();
        foreach ($clientTokens as $token) {
            if (isset($token->token))
                $this->sendPaymentNotification($token->token,
                    "Payment Received",
                    $payment->amount . " JOD has been received"
                );
        }
        return back()->with('success', "Payment received successfully!");
    }
    public function paymentsIndex(Request $request){
        if ($request->from && $request->to) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';
        }
        else {
            $from = date('Y-m-d', strtotime('first day of this month')) . ' 00:00';
            $to = now()->toDateString(). ' 23:59';
        }
        if ($request->doctor && !in_array( "all",$request->doctor))
            $payments = payment::whereBetween('created_at', [$from, $to ])->whereIn('doctor_id',$request->doctor)->get();
        else
            $payments = payment::whereBetween('created_at', [$from, $to ])->get();
        $selectedClients = $request->doctor;
        $clients = client::all();

        return view('generic.payments-list',compact('payments','to','from','clients','selectedClients'));
    }

    public function sales(Request $request)
    {
        $from = $request->from ?: date('Y-m-d', strtotime('first day of this month'));
        $to = $request->to ?: now()->toDateString();

        $selectedClients = null;
        $selectedDoctorIds = [];

        if (is_array($request->doctor) && !in_array('all', $request->doctor)) {
            $selectedDoctorIds = array_values($request->doctor);
            $selectedClients = $selectedDoctorIds;
        }

        $invoiceTotals = invoice::query()
            ->selectRaw('doctor_id, SUM(amount) as total_invoice_amount')
            ->where('status', 1)
            ->whereNotNull('date_applied')
            ->whereBetween('date_applied', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('doctor_id', $selectedDoctorIds);
            })
            ->groupBy('doctor_id');

        $lastInvoiceDates = invoice::query()
            ->selectRaw('doctor_id, MAX(date_applied) as last_invoice_date')
            ->where('status', 1)
            ->whereNotNull('date_applied')
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('doctor_id', $selectedDoctorIds);
            })
            ->groupBy('doctor_id');

        $sales = client::query()
            ->select([
                'clients.id',
                'clients.name',
                'clients.balance',
                DB::raw('COALESCE(invoice_totals.total_invoice_amount, 0) as total_invoice_amount'),
                'last_invoice_dates.last_invoice_date',
            ])
            ->leftJoinSub($invoiceTotals, 'invoice_totals', function ($join) {
                $join->on('invoice_totals.doctor_id', '=', 'clients.id');
            })
            ->leftJoinSub($lastInvoiceDates, 'last_invoice_dates', function ($join) {
                $join->on('last_invoice_dates.doctor_id', '=', 'clients.id');
            })
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('clients.id', $selectedDoctorIds);
            })
            ->where('clients.active', 1)
            ->orderBy('clients.name')
            ->get();

        $this->attachClientBalances($sales, now()->toDateString() . ' 23:59:59');

        $latestInvoices = invoice::query()
            ->select(['id', 'doctor_id', 'date_applied', 'discount_title', 'case_id', 'amount'])
            ->where('status', 1)
            ->whereNotNull('date_applied')
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('doctor_id', $selectedDoctorIds);
            })
            ->orderBy('doctor_id')
            ->orderByDesc('date_applied')
            ->orderByDesc('id')
            ->get()
            ->unique('doctor_id')
            ->keyBy('doctor_id');

        foreach ($sales as $doctor) {
            $latestInvoice = $latestInvoices->get($doctor->id);
            $doctor->setAttribute('last_invoice_id', optional($latestInvoice)->id);
            $doctor->setAttribute('last_invoice_case_id', optional($latestInvoice)->case_id);
            $doctor->setAttribute(
                'last_invoice_is_discount',
                (bool) ($latestInvoice && $latestInvoice->isAccountDiscount())
            );
        }

        $allClients = client::query()
            ->select(['id', 'name'])
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        return view('clients.sales', compact(
            'sales',
            'allClients',
            'selectedClients',
            'from',
            'to'
        ));
    }

    public function salesByMonth(Request $request)
    {
        $now = now();
        $fromDate = $request->filled('from')
            ? \Carbon\Carbon::parse($request->from)->startOfMonth()
            : $now->copy()->startOfYear();

        $requestedTo = $request->filled('to')
            ? \Carbon\Carbon::parse($request->to)
            : $now->copy();

        $toDate = $requestedTo->isSameMonth($now)
            ? $now->copy()
            : $requestedTo->copy()->endOfMonth();

        if ($fromDate->gt($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfMonth(), $fromDate->copy()->endOfMonth()];
        }

        $from = $fromDate->toDateString();
        $to = $toDate->toDateString();

        $selectedClients = null;
        $selectedDoctorIds = [];

        if (is_array($request->doctor) && !in_array('all', $request->doctor)) {
            $selectedDoctorIds = array_values($request->doctor);
            $selectedClients = $selectedDoctorIds;
        }

        $startMonth = $fromDate->copy()->startOfMonth();
        $endMonth = $toDate->copy()->startOfMonth();

        $months = [];
        $monthCursor = $startMonth->copy();

        while ($monthCursor->lte($endMonth)) {
            $months[] = [
                'key' => $monthCursor->format('Y-m'),
                'label' => $monthCursor->format('n/Y'),
            ];
            $monthCursor->addMonth();
        }

        $monthlyInvoiceTotals = invoice::query()
            ->selectRaw("DATE_FORMAT(date_applied, '%Y-%m') as sales_month, SUM(amount) as total_invoice_amount")
            ->where('status', 1)
            ->whereNotNull('date_applied')
            ->whereBetween('date_applied', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('doctor_id', $selectedDoctorIds);
            })
            ->groupBy('sales_month')
            ->get()
            ->keyBy('sales_month');

        $selectedDoctors = client::query()
            ->select(['id', 'name'])
            ->when(!empty($selectedDoctorIds), function ($query) use ($selectedDoctorIds) {
                $query->whereIn('id', $selectedDoctorIds);
            })
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        $chartLabels = [];
        $chartValues = [];
        $grandTotal = 0;

        foreach ($months as &$month) {
            $amount = (float) optional($monthlyInvoiceTotals->get($month['key']))->total_invoice_amount;
            $month['value'] = $amount;
            $chartLabels[] = $month['label'];
            $chartValues[] = $amount;
            $grandTotal += $amount;
        }
        unset($month);

        $selectedDoctorsCount = $selectedDoctors->count();
        $monthsCount = count($months);

        $allClients = client::query()
            ->select(['id', 'name'])
            ->where('active', 1)
            ->orderBy('name')
            ->get();

        return view('clients.sales-by-month', compact(
            'months',
            'chartLabels',
            'chartValues',
            'grandTotal',
            'selectedDoctorsCount',
            'monthsCount',
            'allClients',
            'selectedClients',
            'from',
            'to'
        ));
    }

    public function accountDiscount(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'discountAmount' => 'required|integer|min:0',
        ]);
        $discountAmount = (int) $request->discountAmount;
        $doctor = client::where('id', $request->id)->first();
        if(!$doctor){
            return back()->with('error', "Doctor not found");
        }
            $invoice = new invoice();
            $invoice->status =1;
            $invoice->date_applied = $request->discount_date;;
            $invoice->created_at = $request->discount_date;
            $invoice->updated_at = $request->discount_date;
            $invoice->amount = $discountAmount * -1;
            $invoice->amount_before_discount = $discountAmount * -1;
            $invoice->case_id = 0;
            $invoice->doctor_id =$doctor->id;
            $invoice->discount_title =$request->discount_title;
            $invoice->save();
            $doctor->balance =  $doctor->balance + $invoice->amount;
            $doctor->save();
        return back()->with('success', "Discount applied successfully");
    }
    public function deletePayment($id){
        $payment = payment::where('id',$id)->first();
        if(!$payment)return back()->with('error', 'Payment not found.');
        $doc =client::where('id', $payment->doctor_id)->first();
        $doc->balance= $doc->balance + $payment->amount;
        $doc->save();
        $payment->delete();
        return back()->with('success', 'Payment removed.');
    }

    public function deleteDiscount($id)
    {
        return DB::transaction(function () use ($id) {
            $invoice = invoice::where('id', $id)->lockForUpdate()->first();

            if (!$invoice) {
                return back()->with('error', 'Discount invoice not found.');
            }

            if (!$invoice->isAccountDiscount()) {
                return back()->with('error', 'This is not a discount invoice.');
            }

            $doctor = client::where('id', $invoice->doctor_id)->lockForUpdate()->first();

            if (!$doctor) {
                return back()->with('error', 'Doctor not found.');
            }

            // Since the discount amount is negative, subtracting it reverses the balance effect.
            $doctor->balance = $doctor->balance - $invoice->amount;
            $doctor->save();

            $invoice->delete();

            return back()->with('success', 'Discount removed successfully. Doctor balance updated.');
        });
    }

    public function doctorInvoices(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';
        }
        else {
            $from = date('Y-m-d', strtotime('first day of this month')) . ' 00:00';
            $to = now()->toDateString()  . ' 23:59';
        }
        $invoices = Invoice::where('doctor_id', $request->id)->whereBetween('created_at', [$from, $to ])->get();
        return view('generic.invoices-list',compact('invoices','to','from'))->with('id',$request->id);
    }

    public function doctorCases(Request $request)
    {

        if ($request->from && $request->to) {
            $from = $request->from ;
            $to = $request->to ;
        }
        else {
            $from = date('Y-m-d', strtotime('first day of this month'));
            $to = now()->toDateString();
        }

        $cases = sCase::where('doctor_id', $request->id)->whereBetween('actual_delivery_date', [ $from. ' 00:00', $to . ' 23:59'])
            ->orWhereNull('actual_delivery_date');
        $cases = $cases->where('doctor_id', $request->id);
        $cases = $cases->orderByRaw('-`actual_delivery_date` ASC')->orderBy("initial_delivery_date",'asc')->get();

        return view ('cases.index',compact('cases','from','to'))->with('id',$request->id);

    }

    public function doctorPayments(Request $request)
    {
        if ($request->from && $request->to) {
            $from = $request->from ;
            $to = $request->to ;
        }
        else {
            $from = date('Y-m-d', strtotime('first day of this month')) . ' 00:00';
            $to = now()->toDateString(). ' 23:59';
        }

        $payments = payment::where('doctor_id', $request->id)->whereBetween('created_at', [$from, $to ])->get();



        return view('generic.payments-list',compact('payments','to','from'))->with('id',$request->id);

    }

    public function toggleActive($id)
    {
        try {
            $client = client::where('id', $id)->first();
            if (!$client) {
                return back()->with('error', 'Doctor not found');
            }

            // Toggle the active status
            $client->active = $client->active ? 0 : 1;
            $client->save();

            $status = $client->active ? 'enabled' : 'disabled';
            return back()->with('success', "Doctor has been {$status} successfully");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
