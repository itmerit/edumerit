@extends('backEnd.master')
@section('title')
@lang('inventory.item_sell_list')
@endsection
@section('mainContent')
@php  $setting = generalSetting(); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } @endphp

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('inventory.item_sell_list')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('inventory.inventory')</a>
                <a href="#">@lang('inventory.item_sell_list')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-6">
                
            </div>
             @if(userPermission('save-item-sell-data'))
            <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                <a href="{{route('item-sell')}}" class="primary-btn small fix-gr-bg">
                    <span class="ti-plus pr-2"></span>
                    @lang('inventory.new_item_sell')
                </a>
            </div>
            @endif
        </div>

 <div class="row mt-40">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-4 no-gutters">
                    <div class="main-title">
                        <h3 class="mb-0">@lang('inventory.item_sell_list')</h3>
                    </div>
                </div>
            </div>

         <div class="row">
                <div class="col-lg-12">
                    <x-table>
                        <table id="table_id" class="table" cellspacing="0" width="100%">
                            <thead>
                            
                                <tr>
                                    <th>@lang('common.sl')</th>
                                    <th>@lang('inventory.reference_no')</th>
                                    <th>@lang('inventory.role_name')</th>
                                    <th>@lang('inventory.buyer_name')</th>
                                    <th>@lang('common.date')</th>
                                    <th>@lang('inventory.grand_total')</th>
                                    <th>@lang('inventory.total_quantity')</th>
                                    <th>@lang('inventory.paid')</th>
                                    <th>@lang('inventory.balance') ({{generalSetting()->currency_symbol}})</th>
                                    <th>@lang('common.status')</th>
                                    <th>@lang('common.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(isset($allItemSellLists))
                                @foreach($allItemSellLists as $key=>$value)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$value->reference_no}}</td>
                                    <td>{{$value->roles->name}}</td>
                                    @if($value->role_id == 2)
                                    @php
                                    $getBuyerDetails = $value->studentDetails;
                                    @endphp


                                    @elseif($value->role_id == 3)

                                    @php
                                    $getBuyerDetails = $value->parentsDetails;
                                    @endphp

                                    @else

                                    @php
                                    $getBuyerDetails = $value->staffDetails;
                                    @endphp
                                    @endif

                                    <td>
                                    @if(!empty($getBuyerDetails))
                                    {{$value->role_id == 3? $getBuyerDetails->fathers_name:$getBuyerDetails->full_name}}
                                    @endif
                                    </td>
                                    <td  data-sort="{{strtotime($value->sell_date)}}">
                                    {{$value->sell_date != ""? dateConvert($value->sell_date):''}}
                                    </td>
                                    
                                    <td>{{number_format( (float) $value->grand_total, 2, '.', '')}}</td>
                                    <td>{{$value->total_quantity}}</td>
                                    <td>{{number_format( (float) $value->total_paid, 2, '.', '')}}</td>
                                    <td>{{number_format( (float) $value->total_due, 2, '.', '')}}</td>
                                    <td>
                                        @if($value->paid_status == 'P')
                                        <button class="primary-btn small bg-success text-white border-0">@lang('inventory.paid')</button>
                                        @elseif($value->paid_status == 'PP')
                                        <button class="primary-btn small bg-warning text-white border-0">@lang('inventory.partial')</button>
                                        @elseif($value->paid_status == 'U')
                                        <button class="primary-btn small bg-danger text-white border-0">@lang('inventory.unpaid')</button>
                                        @else
                                        <button class="primary-btn small bg-info text-white border-0">@lang('inventory.refund')</button>
                                        @endif
                                    </td>
                                    <td>
                                        <x-drop-down>
                                                <a class="dropdown-item" href="{{route('view-item-sell', $value->id)}}">@lang('common.view')</a>
                                                @php
                                                $itemPaymentdetails = App\SmInventoryPayment::itemPaymentdetails($value->id);
                                                @endphp

                                                @if($value->paid_status != 'R')
                                                @if($itemPaymentdetails == 0)
                                                @if(userPermission('edit-item-sell'))
                                                <a class="dropdown-item" href="{{route('edit-item-sell', 
                                                $value->id)}}">@lang('common.edit')</a>
                                                @endif
                                                @endif
                                                @endif

                                                @if($value->paid_status != 'R')
                                                @if($value->total_due > 0)
                                                @if(userPermission('add-payment-sell'))
                                                <a class="dropdown-item modalLink" title="@lang('inventory.add_payment')" data-modal-size="modal-md" href="{{route('add-payment-sell', $value->id)}}">@lang('common.add_payment')</a>
                                                @endif
                                                @endif
                                                @endif

                                                @if($value->paid_status != 'P')
                                                @if(userPermission('view-sell-payments'))
                                                <a class="dropdown-item modalLink" data-modal-size="modal-lg" title="@lang('inventory.view_payment')" href="{{route('view-sell-payments', $value->id)}}">@lang('inventory.view_payment')</a>
                                                @endif
                                                @endif

                                                    @if($value->paid_status != 'R')
                                                    @if($value->total_paid == 0)
                                                    @if(userPermission('delete-item-sale-view'))
                                                    <a class="dropdown-item deleteUrl" data-modal-size="modal-md" title="@lang('inventory.delete_sold_item')" href="{{route('delete-item-sale-view', $value->id)}}">@lang('common.delete')</a>
                                                    @endif
                                                    @endif
                                                    @endif

                                                    @if($value->paid_status != 'R')
                                                    @if($value->total_paid>0)

                                                    <a class="dropdown-item deleteUrl" data-modal-size="modal-md" title="Cancel Item Sell" href="{{route('cancel-item-sell-view', $value->id)}}">@lang('common.cancel')</a>
                                                    @endif
                                                    @endif

                                            
                                        </x-drop-down>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection
@include('backEnd.partials.data_table_js')