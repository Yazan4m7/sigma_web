<?php

namespace App\Http\Controllers;
use App\abutmentDeliveryRecord;
use App\abutmentReceiveLogs;
use App\client;
use App\sCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\abutment;
use DB;


class AbutmentsController extends Controller
{
    public function index(){
        $abutments = abutment::all();
        return view('abutments.index',compact("abutments"));
    }

    public function returnCreate()
    {
        return view('abutments.create');
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'abutment_name'     => 'required|max:50',

        ]);

        $abutment = new abutment();

        try {
            $abutment->name = $request->abutment_name;
            $abutment->save();

            return back()->with('success', 'Abutment has been successfully created');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
    public function returnUpdate($id)
    {

        $abutment = abutment::findOrFail($id);
        return view('abutments.edit',compact('abutment'));
    }
    public function update(Request $request)
    {
        try {
            $abutment = abutment::where('id', $request->abutment_id)->first();
            if (!$abutment) {
                return back()->with('Implant Not found');
            }
            $abutment->name = $request->abutment_name;

            $abutment->save();


            return back()->with('success', 'Abutment has been successfully updated');
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }

        public function abutmentsDeliveryIndex(Request $request){

        // Time Filtration
        if ($request->from && $request->to) {
            $from = $request->from;
            $to = $request->to;
        } else {
            $from = date('Y-m-d', strtotime('-30 days'));
            $to = now()->toDateString();
        }

        $fromDate = Carbon::parse($from)->startOfDay();
        $toDate = Carbon::parse($to)->endOfDay();
        $from = $fromDate->toDateString();
        $to = $toDate->toDateString();

        $deliveryColumns = [
            'id',
            'case_id',
            'implant_id',
            'abutment_id',
            'ordered_by',
            'ordered_on',
            'status',
            'code',
            'qty',
            'remaining_qty',
            'created_at',
        ];

        $deliveryRelations = [
            'case:id,doctor_id,patient_name,initial_delivery_date',
            'case.client:id,name',
            'implant:id,name',
            'abutment:id,name',
            'orderedBy:id,first_name,last_name,name_initials',
            'logs:id,abut_delivery_id,user_id,qty,created_at',
            'logs.by:id,first_name,last_name,name_initials',
        ];

        $deliveriesInRange = abutmentDeliveryRecord::query()
            ->select($deliveryColumns)
            ->with($deliveryRelations)
            ->whereBetween('created_at', [$fromDate->format('Y-m-d H:i:s'), $toDate->format('Y-m-d H:i:s')])
            ->get();

        $deliveriesPending = $deliveriesInRange
            ->whereIn('status', [0, 1, 2])
            ->sortByDesc(function ($delivery) {
                return optional($delivery->case)->initial_delivery_date ?? $delivery->created_at;
            })
            ->values();

        $deliveriesReceived = $deliveriesInRange
            ->where('status', 3)
            ->sortByDesc(function ($delivery) {
                return optional($delivery->logs->sortBy('created_at')->last())->created_at ?? $delivery->created_at;
            })
            ->values();

        $deliveries = $deliveriesPending
            ->merge($deliveriesReceived)
            ->filter(function ($delivery) {
                return $delivery->case !== null;
            })
            ->values();

        $deliveryActionData = [];

        foreach ($deliveries as $delivery) {
            $case = $delivery->case;
            $caseDate = $case && $case->initial_delivery_date ? Carbon::parse($case->initial_delivery_date) : null;
            $receivedLogs = $delivery->logs->sortBy('created_at')->values();
            $orderedByName = $delivery->orderedBy ? $delivery->orderedBy->fullName() : 'None';
            $orderedOn = $delivery->ordered_on ? substr((string) $delivery->ordered_on, 0, 16) : 'Not yet';
            $implantName = $delivery->implant ? $delivery->implant->name : 'None';
            $abutmentName = $delivery->abutment ? $delivery->abutment->name : 'None';
            $receivedByFirst = optional(optional($receivedLogs->first())->by)->first_name;

            if ((int) $delivery->status === 0) {
                $statusText = 'Not Ordered';
                $statusColor = 'darkred';
            } elseif ((int) $delivery->status === 1) {
                $statusText = 'Ordered by ' . ($delivery->orderedBy->name_initials ?? 'N/A');
                $statusColor = 'lightseagreen';
            } elseif ((int) $delivery->status === 2) {
                $statusText = 'Partially Received';
                $statusColor = 'lawngreen';
            } else {
                $statusText = 'Fully Received' . ($receivedByFirst ? ' by ' . $receivedByFirst : '');
                $statusColor = 'green';
            }

            $delivery->setAttribute('display_doctor_name', optional($case->client)->name ?? '-');
            $delivery->setAttribute('display_patient_name', $case->patient_name ?? '-');
            $delivery->setAttribute('display_case_delivery_time', $caseDate ? $caseDate->format('M-d g:i a') : '-');
            $delivery->setAttribute('display_implant_name', $implantName);
            $delivery->setAttribute('display_abutment_name', $abutmentName);
            $delivery->setAttribute('display_received_qty', (int) $delivery->qty - (int) $delivery->remaining_qty);
            $delivery->setAttribute('display_status_text', $statusText);
            $delivery->setAttribute('display_status_color', $statusColor);

            $deliveryActionData[$delivery->id] = [
                'id' => (int) $delivery->id,
                'doctor_name' => optional($case->client)->name ?? '-',
                'patient_name' => $case->patient_name ?? '-',
                'abutment_label' => trim($implantName . ' ' . ($delivery->abutment ? $delivery->abutment->name : 'No Abutment') . ' ' . ($delivery->code ?? '')),
                'ordered_by' => $orderedByName,
                'ordered_on' => $orderedOn,
                'status' => (int) $delivery->status,
                'remaining_qty' => (int) $delivery->remaining_qty,
                'received_logs' => $receivedLogs->map(function ($log) {
                    return [
                        'qty' => (int) $log->qty,
                        'by' => $log->by ? $log->by->fullName() : 'Unknown',
                        'created_at' => substr((string) $log->created_at, 0, 16),
                    ];
                })->all(),
                'view_case_url' => route('view-case', ['id' => $case->id, 'stage' => -2]),
                'order_url' => route('order-abutments', $delivery->id),
            ];
        }

        return view('abutments.abutmentsDelivery', compact('deliveries', 'from', 'to', 'deliveryActionData'));
        }


        public function receiveAbutment(Request $request){
        $deliveryRecord = abutmentDeliveryRecord::where("id",$request->abutment_id)->first();
        if($request->qty > $deliveryRecord->qty)
            return back()->with("error","Quantity Entered too large");

        $qty = $deliveryRecord->remaining_qty - $request->qty;
        $deliveryRecord->remaining_qty = $qty;
        if ($qty == 0)
        $deliveryRecord->status = 3;
        else
            $deliveryRecord->status = 2;
        $deliveryRecord->save();


        $abutReceiveLog = new abutmentReceiveLogs();
        $abutReceiveLog->user_id = Auth()->user()->id;
        $abutReceiveLog->qty = $request->qty;
        $abutReceiveLog->abut_delivery_id = $request->abutment_id;
        $abutReceiveLog->save();
        return back()->with("success","abutments received successfully");
    }
    public function orderAbutment($id){
        $record = abutmentDeliveryRecord::where("id",$id)->first();
        $record->status = 1;
        $record->ordered_by = Auth()->user()->id;
        $record->ordered_on = now();
        $record->save();
        return back()->with("success","abutments marked as ordered successfully");
    }
}
