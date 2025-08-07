@extends('layouts.app' ,[ 'pageSlug' => 'View Invoice' ])
@section('head')
  <style>
      body{
          -webkit-print-color-adjust:exact !important;
      }
      p{
          margin-bottom: 0 !important;
      }
       tr:nth-child(even) {
          background-color: #ececec !important;
      }
       thead{
           background-color: lightsteelblue !important;
           border-radius: 20%;
       }
      #invlogo {

          line-height: 60px;


          z-index: 100;
          background-color: #fff;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          font-size: 22px;
          color: rgba(9, 17, 20, 0.97) !important;
          text-align:center;
          font-weight: 900;
      }
  </style>

@endsection
@section('content')

    <div class="row">
        <div class="col-12 m-b-30">
            <div class="">
                <div class="card-body invoice">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-right"><div id="invlogo">

                                </div></h4>
                        </div>
                        <div class="pull-right">
                            <h6>Invoice : #
                                <strong>{{$case->invoice->id}}</strong>
                            </h6>
                            <h6 class="pull-right">Date : {{substr(now(),0,16)}}</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left mt-4">
                                <address>
                                    <strong><b>SIGMA DENTAL LAB</b></strong><br>
                                    Abdallah Ghosheh St.<br>

                                </address>
                                <p><strong>Order Status: </strong>
                                    @if(isset($case->delivered_to_client))
                                        <span class="badge badge-success">Applied</span></p>
                                @else

                                    <span class="badge badge-warning">Pending</span></p>
                                @endif
                            </div>
                            <div class="pull-right mt-4">
                                <p><strong>Dentist: </strong><b>{{$case->client->name}}</b></p>
                                <p><strong>Patient: </strong><b>{{$case->patient_name}}</b></p>
                                <p><strong>Order Date: </strong>{{str_replace('T', ' ',$case->created_at)}}</p>
                                @if(isset($case->actual_delivery_date))
                                    <p><strong>Delivered on: </strong>{{substr($case->actual_delivery_date,0,16)}}</p>
                                @endif
                                <p><strong>Order ID: </strong>{{$case->case_id . '/'. $case->id}}</p>
                            </div>
                        </div>
                    </div><!--end row-->

                    <div class="h-50"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table mt-4">
                                    <THEAD>
                                    <th style=""><span style="">ID</span></th>
                                    <th style=""><span style="">Job Type</span></th>
                                    <th style=" "><span style="">Material</span></th>

                                    <th style=" "><span style="">Style</span></th>
                                    <th style=" "><span style="">Quantity</span></th>
                                    <th style=" "><span style="">Unit Price</span></th>
                                    <th style=" "><span style="">Amount</span></th>
                                    </THEAD>
                                    <tbody>
                                    @php
                                        $i=1;
                                    $totalInvoiceAmount=0;
                                    @endphp

                                    @foreach($case->jobs as $job)
                                        @php
                                        if($job->is_modification)
                                        continue;
                                        @endphp
                                        <tr>
                                            <td style="">{{$i}}
                                            @if ($job->is_rejection)
                                                <span style="color:red">REJECTION</span>
                                                @endif
                                            </td>
                                            <td style="">{{$job->jobType->name}}</td>
                                            <td style=" ">{{$job->material->name}}</td>

                                            <td style=" ">{{$job->style}}</td>
                                            @php

                                            $unitsAmount = count(explode(',', $job->unit_num));

                                             if (isset($job->unit_price) && $job->unit_price > 0)
                                            $totalJobPrice = $unitsAmount * $job->unit_price;
                                             else
                                             $totalJobPrice = $unitsAmount * $job->material->price;

                                            $totalInvoiceAmount += $totalJobPrice;

                                            @endphp
                                            <td style=" ">{{$unitsAmount}}</td>
                                            <td style=" ">{{$job->unit_price ?? $job->material->price}}</td>
                                            <td style=" ">{{$totalJobPrice}}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!--end row-->

                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-9">

                        </div>
                        <div class="col-md-3">
                            <h6 class="text-right">
                                @if(isset($case->discount))
                                Discount : {{$case->discount->discount}}
                                @endif
                            </h6>
                            <hr>
                            @if(isset($case->discount))
                            <h4 class="text-right">Total: <b>{{$totalInvoiceAmount - $case->discount->discount}}</b> JOD </h4>
                            @else
                                <h4 class="text-right">Total: <b>{{$totalInvoiceAmount }}</b> JOD </h4>
                                @endif
                        </div>
                    </div><!--end row-->

                    <hr>
                    <div class="hidden-print">
                        <div class="text-center text-muted"><small>Thank you for doing business with us!</small></div>
                        <div class="pull-right">

                            <button type="button" onclick="print()" class="btn btn-secondary"><i class="fa fa-print"></i></button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@push('js')
    <script>
        function print(){
            var mywindow = window.open('', 'PRINT', 'height=400,width=600');

            mywindow.document.write('<html><head><title>' + document.title + '</title>');
            mywindow.document.write(`
            <link href="{{asset('assets/css/slidebars.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
<link href="{{asset('assets/icons/css/themify-icons.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/menu.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/style.css')}}" rel="stylesheet">

<link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css')}}" media="all" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l')" crossorigin="anonymous">

  <style>
  body{
  -webkit-print-color-adjust:exact !important;
}
          p{
          margin-bottom: 0 !important;
      }
       tr:nth-child(even) {
        -webkit-print-color-adjust:exact !important;
          background-color: #ececec !important;

      }
       thead{
           background-color: lightsteelblue !important;
           border-radius: 20%;
       }
      #invlogo {

          line-height: 60px;


          z-index: 100;
          background-color: #fff !important;
          text-align: center;
          font-family: 'Orbitron', sans-serif;
          font-size: 22px;
          color: rgba(9, 17, 20, 0.97) !important;
          text-align:center;
          font-weight: 900;
      }
    </style>
    <div class="row">
        <div class="col-12 m-b-30">
            <div class="card">
                <div class="card-body invoice">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-right"><div id="invlogo">
                                    SIGMA LAB
                                </div></h4>
                        </div>
                        <div class="pull-right">
                            <h6>Invoice : #
                                <strong>{{$case->invoice->id}}</strong>
                            </h6>
                            <h6 class="pull-right">Date : {{substr(now(),0,16)}}</h6>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left mt-4">
                                <address>
                                    <strong><b>SIGMA DENTAL LAB</b></strong><br>
                                    Abdallah Ghosheh St.<br>

                                </address>
                                <p><strong>Order Status: </strong>
                                    @if(isset($case->delivered_to_client))
                <span class="badge badge-success">Applied</span></p>
@else

                <span class="badge badge-warning">Pending</span></p>
@endif
                </div>
                <div class="pull-right mt-4">
                    <p><strong>Dentist: </strong><b>{{$case->client->name}}</b></p>
                                <p><strong>Patient: </strong><b>{{$case->patient_name}}</b></p>
                                <p><strong>Order Date: </strong>{{str_replace('T', ' ',$case->created_at)}}</p>
                                @if(isset($case->actual_delivery_date))
                <p><strong>Delivered on: </strong>{{substr($case->actual_delivery_date,0,16)}}</p>
                                @endif
                <p><strong>Order ID: </strong>{{$case->case_id . '/'. $case->id}}</p>
                            </div>
                        </div>
                    </div>

                <div class="h-50"></div>
                <div class="row">
                <div class="col-md-12">
                <div class="table-responsive">
                <table class="table mt-4">
                <THEAD>
                <th style=""><span style="">ID</span></th>
                <th style=""><span style="">Job Type</span></th>
                <th style=" "><span style="">Material</span></th>

                <th style=" "><span style="">Style</span></th>
                <th style=" "><span style="">Quantity</span></th>
                <th style=" "><span style="">Unit Price</span></th>
                <th style=" "><span style="">Amount</span></th>
                </THEAD>
                <tbody>
            @php
                $i=1;
            $totalInvoiceAmount=0;
            @endphp
                    @foreach($case->jobs  as $job)
                    @php
                        if($job->is_modification)
                        continue;
                    @endphp
                <tr>
                <td style="">{{$i}}</td>
                <td style="">{{$job->jobType->name}}</td>
                <td style=" ">{{$job->material->name}}</td>

                <td style=" ">{{$job->style}}</td>
                    @php

                        $unitsAmount = count(explode(',',$job->unit_num));
                        $totalJobPrice = $unitsAmount * $job->unit_price ?? $job->material->price;
                        $totalInvoiceAmount += $totalJobPrice;
                    @endphp
                <td style=" ">{{$unitsAmount}}</td>
                <td style=" ">{{$job->unit_price ?? $job->material->price}}</td>
                <td style=" ">{{$totalJobPrice}}</td>
                </tr>
            @php
                $i++;
            @endphp
                    @endforeach
                </tbody>
                </table>
                </div>
                </div>
                </div>

                <div class="row" style="border-radius: 0px;">
                <div class="col-md-9">

                </div>
                <div class="col-md-3">
                   <h6 class="text-right">
                                @if(isset($case->discount))
                Discount : {{$case->discount->discount}}
                    @endif
                </h6>
                <hr>
               @if(isset($case->discount))
                <h4 class="text-right">Total: <b>{{$totalInvoiceAmount - $case->discount->discount}}</b> JOD </h4>
                            @else
                <h4 class="text-right">Total: <b>{{$totalInvoiceAmount }}</b> JOD </h4>
                                @endif
                </div>
                </div>

                <hr>
                <div class="hidden-print">
                <div class="text-center text-muted"><small>Thank you for doing business with us!</small></div>
                <div class="pull-right">


                </div>
                </div>
                </div>
                </div>
                </div>
                </div>
`);
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10*/
            setTimeout(function(){ mywindow.print(); /*mywindow.close();*/ },1000);

            return true;
        }
    </script>
@endpush
