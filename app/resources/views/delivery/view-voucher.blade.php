@extends('layouts.app' ,[ 'pageSlug' => 'View' . ' '. $voucher ])



@section('content')
    <style>
        .jobs th{
            color:white;
            background-color: rgba(41, 41, 41, 0.87);
        }
        .jobs tr:nth-child(even) {
            background-color: #ececec;
        }
        .jobs th,td{ padding:5px}
        thead {display: table-header-group;}
        tfoot {display: table-footer-group;}
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="col-md-3  col-xs-6 col-l-3  col-xl-3">
        <button class="btn btn-primary" style="width:100%" onclick="PrintInvoice()">PRINT </button>
        </div>
    </div>
    <div class="card">

    <h1 style="text-align: center;"><span style=""><strong>SIGMA</strong></span></h1>
    <p style="text-align: center;"><span style=""><strong>Digital Lab Solutions</strong></span></p>
    <p style="text-align: center;"> </p>
    <h3 style="text-align: center;"><span style=""><strong>Receipt Voucher</strong></span></h3>

        <br>
        <br>
    <table style="" width="100%" >
        <tbody>
        <tr>
            <td style=""><span style="margin-right: 15px;font-weight: bold">Dentist:</span>{{$case->client->name}} </td>
            <td style=""><span style="margin-right: 15px;font-weight: bold">Date:</span> {{now()}} </td>
        </tr>
        <tr>
            <td style=""><span style="margin-right: 15px;font-weight: bold">Patient Name:</span>{{$case->patient_name}}</td>
            <td style=""> </td>
        </tr>
        </tbody>
    </table>
    <p><span style=""><strong> </strong></span></p>




    <table class="jobs" style="height: 100%; width: 100%;border: 1px solid #dddddd;  border-collapse: collapse;">
        <THEAD>
        <th style=""><span style="">ID</span></th>
        <th style=""><span style="">Job Type</span></th>
        <th style=" "><span style="">Material</span></th>
        <th style=" "><span style="">Color</span></th>
        <th style=" "><span style="">Style</span></th>
        <th style=" "><span style="">Quantity</span></th>
        </THEAD>
        <tbody>


            @php
            $i=1;
            @endphp
            @foreach($case->jobs  as $job)
                <tr>
            <td style="">{{$i}}</td>
            <td style="">{{$job->jobType->name}}</td>
            <td style=" ">{{$job->material->name}}</td>
            <td style=" ">{{$job->color == 0 ? "None" : $job->color }}</td>
            <td style=" ">{{$job->style}}</td>
            <td style=" ">{{count(explode(',',$job->unit_num))}}</td>
             </tr>
    @php
        $i++;
    @endphp
@endforeach
</tbody>
</table>
<p> </p>
<p> </p>
<table style="height: 32px; margin-left: auto; margin-right: auto; " width="100%" cellspacing="5" cellpadding="0">
<tbody>
<tr>
<td style="font-weight: bold">Recieved By</td>
<td style="font-weight: bold">Stamp/ Signture</td>
</tr>
<tr style="height: 50px"></tr>

<tr style="height: 50px">
<td style=" "> </td>
<td style="position:absolute;right:15px;color:grey">Delivered By: {{isset($case->jobs[0]) ? ($case->jobs[0]->deliveryDriver == null ? " N/A" : $case->jobs[0]->deliveryDriver->name_initials ) : "-" }}</td>
</tr>
</tbody>
</table>
</div>
<div>

</div>
@endsection
@push('js')
<script type="text/javascript">
    $(document).ready(function() {
        PrintInvoice();
    });

function PrintInvoice() {
var mywindow = window.open('', 'PRINT', 'height=400,width=600');

mywindow.document.write('<html><head><title>' + document.title + '</title>');
//noinspection JSAnnotator
    mywindow.document.write( `
    <style>

.jobs th{
    color:white;
    background-color: rgb(78 78 78 / 87%);
}
.jobs tr:nth-child(even) {
    background-color: #ececec;
}
.jobs th,td{ padding:5px}

thead {display: table-header-group;}
tfoot {display: table-footer-group;}

#pageborder {
      position:fixed;
      left: 0;
      right: 0;
      top: 0;
      bottom: 23px;

    }
</style>
<div class="card"  style="text-align: center;height:320px;width:380px">
<div id="pageborder">
  </div>
   <span style="font-size:28px"><strong>SIGMA</strong></span><br>
    <span style="text-align: center;font-size:10px;"><strong>Digital Lab Solutions</strong></span><br><br>

    <span style="text-align: center;"><strong>Receipt Voucher</strong></span>

        <br>
        <br>
    <table  style="border-collapse:separate; border-spacing:2px;font-size:12px" width="100%" >
        <tbody>
        <tr>
            <td style="padding:0px"><span style="margin-right: 15px;">Dentist:</span><b>{{$case->client->name}} </b></td>
            <td style="padding:0px;text-align:right;"><span style="text-align:right;">Date:</span> {{substr(now(),0,16)}} </td>
        </tr>
        <tr>
            <td style="padding:0px"><span style="margin-right: 15px;">Patient:</span><b>{{$case->patient_name}}</b></td>
            <td style="padding:0px"> </td>
        </tr>
        </tbody>
    </table>
    <p><span style=""><strong> </strong></span></p>




    <table class="jobs" style="height: 100%; width: 100%;border: 1px solid #dddddd;  border-collapse: collapse;font-size:11px">
        <THEAD>
        <th style=""><span style="">ID</span></th>
        <th style=""><span style="">Job Type</span></th>
        <th style=" "><span style="">Material</span></th>
        <th style=" "><span style="">Color</span></th>
        <th style=" "><span style="">Style</span></th>
        <th style="text-align:center"><span style="">Quantity</span></th>
        </THEAD>
        <tbody>


            @php
        $i=1;
    @endphp
            @foreach($case->jobs  as $job)
        <tr>
    <td style="">{{$i}}</td>
            <td style="">{{$job->jobType->name}}</td>
            <td style=" ">{{$job->material->name}}</td>
            <td style=" ">{{$job->color == 0 ? "None" : $job->color }}</td>
            <td style=" ">{{$job->style}}</td>
            <td style="text-align:center">{{count(explode(',',$job->unit_num))}}</td>
             </tr>
    @php
        $i++;
    @endphp
            @endforeach
        @for ($i; $i < 8; $i++)
        <tr>
            <td style="">{{$i}}</td>
            <td style=""></td>
            <td style=" "></td>
            <td style=" "></td>
            <td style=" "></td>
            <td style="text-align:center"></td>
             </tr>
        @endfor


              </tbody>
              </table>
              <p> </p>

              <table style="height: 32px; margin-left: auto; margin-right: auto; " width="100%" cellspacing="5" cellpadding="0">
              <tbody>
              <tr>
              <td style="font-weight: bold;font-size:11px;">Received By:</td>
              <td style="font-weight: bold;font-size:11px;">Stamp/ Signature:</td>
              </tr>

        </tr>
        </tbody>
        </table>
@if($case->jobs->count() <13)
      <div id="onePageDeliveryName" style="position:absolute;right:10px;bottom:28px;color:grey;margin-top:1000px;font-size:8px;">Delivered By: {{isset($case->jobs[0]) ? ($case->jobs[0]->deliveryDriver == null ? " N/A" : $case->jobs[0]->deliveryDriver->name_initials):"-"}}</div>
      @else
            <div id="multiPageDeliveryName" style="float:right;color:grey;margin-top:100px;font-size:8px;">Delivered By: {{isset($case->jobs[0]) ? ($case->jobs[0]->deliveryDriver == null ? " N/A" : $case->jobs[0]->deliveryDriver->name_initials):"-"}}</div>
@endif
</div>
<div>

</div>
 `);

mywindow.print();
mywindow.close(); // necessary for IE >= 10
mywindow.focus();
history.back();// necessary for IE >= 10*/
}
</script>
@endpush
