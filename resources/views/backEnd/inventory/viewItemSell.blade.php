@extends('backEnd.master')
@section('title')
@lang('inventory.sells_details')
@endsection

@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.sells_details')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="{{route('item-sell-list')}}">@lang('inventory.item_sell_list')</a>
                <a href="#"> @lang('inventory.sells_details')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
<div class="container-fluid p-0">
    <div class="row">
            <div class="col-lg-12">
                
                <div class="white-box">
                   <div class="row mt-40">
                    <div class="col-lg-12">
                <!-- <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">Item Receive List</h3>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <div class="container-fluid">
                        <div id="purchaseInvoice">
                        <div class="row mb-20">
                            <div class="col-lg-4">
                                <div class="invoice-details-left">
                                    <div class="mb-20">
                                        @if(@$general_setting->logo)
                                        <label>
                                            <img src="{{ asset('/')}}{{ $general_setting->logo}}" alt="">
                                        </label>
                                        @else
                                        <label for="companyLogo" class="company-logo">
                                            <i class="ti-image"></i> 
                                            <img src="{{ asset('public/uploads/settings/logo.png')}}" alt="">
                                        </label>
                                        <input id="companyLogo" type="file"/>
                                        @endif
                                    </div>

                                    <div class="business-info">
                                    <h3>{{ $general_setting->site_title}}</h3>
                                        <p>{{ $general_setting->address}}</p>
                                        <p>{{ $general_setting->email}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="offset-lg-4 col-lg-4">
                                <div class="invoice-details-right">
                                    <h1 class="text-uppercase">@lang('inventory.sell_receipt')</h1>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('inventory.sell_date'):</p>

                                       
                                        <p>{{(isset($viewData)) ?  dateConvert($viewData->sell_date) : ''}}</p>

                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('inventory.reference_no'):</p>
                                        <p>#{{(isset($viewData)) ? $viewData->reference_no : ''}}</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="fw-500 primary-color">@lang('inventory.payment_status'):</p>
                                        <p>
                                            @if($viewData->paid_status == 'P')
                                            <strong>@lang('inventory.paid')</strong>
                                            @elseif($viewData->paid_status == 'PP')
                                            <strong>@lang('inventory.partial_paid')</strong>
                                    
                                            @elseif($viewData->paid_status == 'R')
                                            <strong>@lang('inventory.refund')</strong>
                                            @else
                                            <strong>@lang('inventory.unpaid')</strong>
                                            @endif
                                        </p>
                                    </div>

                                    <span class="primary-btn fix-gr-bg large mt-30">
                                        {{(isset($viewData)) ? currency_format((float) $viewData->grand_total) : ''}}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="customer-info">
                                    <h2>@lang('inventory.bill_to'):</h2>
                                </div>

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

                                <div class="client-info">

                                    <h3>{{$viewData->role_id == 3? $buyerDetails->fathers_name:$buyerDetails->full_name }}</h3>
                                    <p>
                                        
                                        @if($viewData->role_id == "3")
                                            {{$buyerDetails->guardians_address}}
                                        @else
                                            {{$buyerDetails->current_address}}
                                        @endif
                                    </p>
                                    <p></p>
                                    <p></p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row mt-30 mb-50">
                            <div class="col-lg-12">
                                <table class="d-table table-responsive custom-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="40%">@lang('inventory.item_name')</th>
                                            <th width="20%">@lang('inventory.quantity')</th>
                                            <th width="20%">@lang('inventory.price')</th>
                                            <th width="20%">@lang('accounts.amount')</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    @php $sub_totals = 0; @endphp
                                    @if(isset($editDataChildren))
                                    @foreach($editDataChildren as $value)
                                        <tr>
                                            <td>{{$value->items !=""?$value->items->item_name:""}}</td>
                                            <td>{{$value->quantity}}</td>
                                            <td>{{number_format( (float) $value->sell_price, 2, '.', '')}}</td>
                                            <td>{{number_format( (float) $value->sub_total, 2, '.', '')}}</td>
                                        </tr>
                                        @php $sub_totals += $value->sub_total; @endphp
                                    @endforeach
                                    @endif

                                        
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600 primary-color">@lang('inventory.sub_total')</td>
                                            <td>{{number_format( (float) $sub_totals, 2, '.', '')}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600">@lang('inventory.paid_amount')</td>
                                            <td class="fw-600">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->total_paid, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600">@lang('inventory.due_amount')</td>
                                            <td class="fw-600">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->total_due, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="fw-600 text-dark">@lang('exam.result')</td>
                                            <td class="fw-600 text-dark">
                                            {{(isset($viewData)) ? number_format( (float) $viewData->grand_total, 2, '.', '') : ''}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div>   
                        
                    <div class="row mt-40">
                        <div class="col-lg-12 text-center">
                           <a class="primary-btn fix-gr-bg" target="_blank" href="{{ route('view-item-sell-print',@$viewData->id) }}" onclick="javascript:printDiv('purchaseInvoice')">@lang('common.print')</a>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</section>
@endsection
