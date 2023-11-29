<!DOCTYPE html>
<html>
<head>
    <title>@lang('inventory.sell_receipt')</title>
    <style>
      
        .school-table-style {
            padding: 10px 0px!important;
        }
        .school-table-style tr th {
            font-size: 6px!important;
            text-align: left!important;
        }
        .school-table-style tr td {
            font-size: 7px!important;
            text-align: left!important;
            padding: 10px 0px!important;
        }
        .logo-image {
            width: 10%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/vendors/css/bootstrap.css" />
    <link rel="stylesheet" href="{{asset('public/backEnd/')}}/css/style.css" />
</head>
<body>
    @php  $setting = generalSetting();  if(!empty($setting->currency_symbol)){ generalSetting()->currency_symbol = $setting->currency_symbol; }else{ generalSetting()->currency_symbol = '$'; }   @endphp 
 
    <table style="width: 100%;" tyle="width: 100%; table-layout: fixed">
        <tr>
             
            <td style="width: 30%"> 
                <img height="60px" width="120px" src="{{url($setting->logo)}}" alt="{{url($setting->logo)}}"> 
            </td> 
            <td  style="width: 70%">  
                <h3>{{$setting->school_name}}</h3>
                <h4>{{$setting->address}}</h4>
            </td> 
        </tr> 
    </table>
    <hr>

                        <div class="row">
                            <div class=" col-lg-12">
                                @php
                                    $buyerDetails = '';
                                    if($viewData->role_id == 2){
                                        $buyerDetails = $viewData->studentDetails;
                                    }elseif($viewData->role_id == 3){
                                        $buyerDetails = $viewData->parentsDetails;
                                    }else{
                                        $buyerDetails = $viewData->staffDetails;
                                    }
                                @endphp
                                <div class="row">
                                <div class="col-lg-6">
                                    <div class="customer-info">
                                        <h2>@lang('inventory.bill_to'):</h2>
                                    </div>
                                

                                
                                
                                    <div class="client-info">

                                        <h3>{{$viewData->role_id == 3 ? $buyerDetails->fathers_name : $buyerDetails->full_name }}</h3>
                                        <p>
                                            
                                            @if($viewData->role_id == "3")
                                                {{$buyerDetails->guardians_address}}
                                            @else
                                                {{$buyerDetails->current_address}}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="invoice-details-right">
                                        <h1 class="text-uppercase">@lang('inventory.sell_receipt')</h1>
                                        <div class="d-flex justify-content-between">
                                            <p class="fw-500 primary-color">@lang('inventory.sell_date'):

                                        
                                            {{(isset($viewData)) ?  dateConvert($viewData->sell_date) : ''}}</p>

                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="fw-500 primary-color">@lang('inventory.reference_no'):#{{(isset($viewData)) ? $viewData->reference_no : ''}}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="fw-500 primary-color">@lang('inventory.payment_status'):
                                                @if($viewData->paid_status == 'P')
                                                <strong>@lang('fees.submit')</strong>
                                                @elseif($viewData->paid_status == 'PP')
                                                <strong>@lang('inventory.partial_paid')</strong>
                                        
                                                @elseif($viewData->paid_status == 'R')
                                                <strong>@lang('inventory.refund')</strong>
                                                @else
                                                <strong>@lang('fees.unpaid')</strong>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <hr>
                            </div>
                        </div>
        {{-- </div> --}}
	<table class="d-table table-responsive custom-table" cellspacing="0" width="100%" style="width: 100%; table-layout: fixed">
        <thead>
            <tr>
                <th width="40%">Description</th>
                <th width="20%">Quantity</th>
                <th width="20%">Price</th>
                <th width="20%">Amount</th>
            </tr>
        </thead>

        <tbody>

        @php $sub_totals = 0; @endphp
        @if(isset($editDataChildren))
        @foreach($editDataChildren as $value)
            <tr>
                <td>{{$value->items !=""?$value->items->item_name:""}}</td>
                <td>{{$value->quantity}}</td>
                <td>{{number_format( (float) $value->sell_price, 2, '.', '')}} </td>
                <td>{{number_format( (float) $value->sub_total, 2, '.', '')}}</td>
            </tr>
            @php $sub_totals += $value->sub_total; @endphp
        @endforeach
        @endif

            
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600 primary-color">Subtotal</td>
                <td>{{number_format( (float) $sub_totals, 2, '.', '')}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600">Paid Amount</td>
                <td class="fw-600">
                {{(isset($viewData)) ? number_format( (float) $viewData->total_paid, 2, '.', '') : ''}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600">Due Amount</td>
                <td class="fw-600">
                {{(isset($viewData)) ? number_format( (float) $viewData->total_due, 2, '.', '') : ''}}
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td class="fw-600 text-dark">Total</td>
                <td class="fw-600 text-dark">
                {{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>
