@extends('backEnd.master')
@section('title') 
@lang('fees.search_fees_due')
@endsection
@section('mainContent')
<style>
    table.dataTable tfoot th, table.dataTable tfoot td {
        padding: 10px 30px 6px 30px;
    }
</style>
@php  $setting = generalSetting(); if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; } @endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('fees.search_fees_due')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees_collection')</a>
                <a href="#">@lang('fees.search_fees_due')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees_due_search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('fees_group') ? ' is-invalid' : '' }}" name="fees_group">
                                    <option data-display="@lang('fees.fees_group')*" value="">@lang('fees.fees_group') *</option>
                                    @foreach($fees_masters as $fees_master)
                                        <option value="" disabled>{{@$fees_master->feesGroups->name}}</option>
                                            @foreach($fees_master->feesTypeIds as $feesTypeId)
                                            <option value="{{$fees_master->fees_group_id.'-'.$feesTypeId->feesTypes->id}}" {{isset($fees_group_id)? ($fees_group_id == $feesTypeId->feesTypes->id? 'selected':''):''}}>{{$feesTypeId->feesTypes->name}}</option>
                                            @endforeach
                                    @endforeach
                                </select>
                                @if ($errors->has('fees_group'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('fees_group') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md" id="select_section_div">
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @if ($errors->has('section'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('section') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'route' => 'send-dues-fees-email', 'method' => 'POST']) }}
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="main-title d-flex align-items-center flex-wrap mb_sm_75">
                                <h3 class="mb-0 mb-2 mt-2 mr-2"> @lang('fees.fees_due_list')</h3>
                                <div class="fes_lag_btn d-flex align-items-center">
                                    <button name="send_email" type="submit" class="primary-btn small fix-gr-bg mr-2" value="1">
                                        <span class="ti-envelope pr-2"></span>
                                        @lang('communicate.send_email')
                                    </button>
                                    <button name="send_sms" type="submit" class="primary-btn small fix-gr-bg" value="1">
                                        <span class="ti-envelope pr-2"></span>
                                        @lang('communicate.send_sms')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 search_hide_md">
                            <x-table>
                            @if(moduleStatusCheck('University'))
                            @php
                                $totalAmount = 0;
                                $totalDiscount = 0;
                                $totalPaid = 0;
                                $totalBalance = 0;
                            @endphp
                             <table id="table_id" class="table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('student.admission_no')</th>
                                        <th> @lang('common.name')</th>
                                        <th> @lang('university::un.installment')</th>
                                        <th>@lang('fees.due_date')</th>
                                        <th>@lang('fees.amount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.paid') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.balance') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fees_dues as $fees_due)
                                        <tr>
                                            <td>{{$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->admission_no:""}}</td>
                                            <td>{{$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->full_name:""}}</td>
                                            <td>
                                                {{@$fees_due->installment->title}}
                                            </td>
                                            <td>
                                                {{@dateConvert($fees_due->due_date)}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalAmount += $fees_due->amount;
                                                @endphp
                                                {{$fees_due->amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalDiscount += $fees_due->discount_amount;
                                                @endphp
                                                {{$fees_due->discount_amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalPaid += $fees_due->paid_amount;
                                                @endphp
                                                {{$fees_due->paid_amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $balance = discountFeesAmount($fees_due->id) - $fees_due->paid_amount;
                                                    $totalBalance += $balance;
                                                @endphp
                                                {{$balance}}
                                            </td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    @if(userPermission('fees_collect_student_wise'))
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{route('fees_collect_student_wise', [$fees_due->recordDetail->id])}}">@lang('common.view')</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$totalAmount}}</td>
                                        <td>{{$totalDiscount}}</td>
                                        <td>{{$totalPaid}}</td>
                                        <td>{{$totalBalance}}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            @elseif(directFees())
                            @php
                                $totalAmount = 0;
                                $totalDiscount = 0;
                                $totalPaid = 0;
                                $totalBalance = 0;
                            @endphp
                             <table id="table_id" class="table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('student.admission_no')</th>
                                        <th> @lang('common.name')</th>
                                        <th> @lang('fees.installment')</th>
                                        <th>@lang('fees.due_date')</th>
                                        <th>@lang('fees.amount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.paid') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.balance') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fees_dues as $fees_due)
                                        <tr>
                                            <td>{{@$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->admission_no:""}}</td>
                                            <td>{{@$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->full_name:""}}</td>
                                            <td>
                                                {{@$fees_due->installment->title}}
                                            </td>
                                            <td>
                                                {{@dateConvert($fees_due->due_date)}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalAmount += $fees_due->amount;
                                                @endphp
                                                {{$fees_due->amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalDiscount += $fees_due->discount_amount;
                                                @endphp
                                                {{$fees_due->discount_amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $totalPaid += $fees_due->paid_amount;
                                                @endphp
                                                {{$fees_due->paid_amount}}
                                            </td>
                                            <td>
                                                @php
                                                    $balance = discountFees($fees_due->id) - $fees_due->paid_amount;
                                                    $totalBalance += $balance;
                                                @endphp
                                                {{$balance}}
                                            </td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    @if(userPermission('fees_collect_student_wise'))
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{route('fees_collect_student_wise', [$fees_due->recordDetail->id])}}">@lang('common.view')</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{$totalAmount}}</td>
                                        <td>{{$totalDiscount}}</td>
                                        <td>{{$totalPaid}}</td>
                                        <td>{{$totalBalance}}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>


                            @else
                            <table id="table_id" class="table " cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th> @lang('student.admission_no')</th>
                                        <th> @lang('student.roll_no')</th>
                                        <th> @lang('common.name')</th>
                                        <th>@lang('fees.due_date')</th>
                                        <th>@lang('fees.amount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.deposit') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.discount') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.fine') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('fees.balance') ({{generalSetting()->currency_symbol}})</th>
                                        <th>@lang('common.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fees_dues as $fees_due)
                                      
                                        <tr>
                                            <td>{{$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->admission_no:""}}</td>
                                            <td> {{$fees_due->recordDetail->studentDetail !=""? $fees_due->recordDetail->roll_no:""}}</td>
                                            <td>{{$fees_due->recordDetail->studentDetail !=""?$fees_due->recordDetail->studentDetail->full_name:""}}</td>
                                            <td>
                                                @if($fees_due->feesGroupMaster !="")
                                                    {{$fees_due->feesGroupMaster->date != ""? dateConvert($fees_due->feesGroupMaster->date):''}}
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    echo $fees_due->feesGroupMaster->amount;
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $amount = App\SmFeesAssign::discountSum($fees_due->student_id, $fees_due->feesGroupMaster->feesTypes->id, 'amount', $fees_due->recordDetail->id);
                                                    echo $amount;
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $discount_amount = $fees_due->applied_discount;
                                                    if ($discount_amount>0) {
                                                        echo $discount_amount;
                                                    } else {
                                                    echo 0.00;
                                                    }
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    $fine = App\SmFeesAssign::discountSum($fees_due->student_id, $fees_due->feesGroupMaster->feesTypes->id, 'fine', $fees_due->recordDetail->id);
                                                    echo $fine;
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                    echo $fees_due->feesGroupMaster->amount - $discount_amount - $amount+$fine;
                                                    $dues_amount = $fees_due->feesGroupMaster->amount - $discount_amount - $amount;
                                                @endphp
                                                <input type="hidden" name="dues_amount[{{$fees_due->recordDetail->id}}]" value="{{$dues_amount}}">
                                                <input type="hidden" name="student_list[]" value="{{$fees_due->recordDetail->student_id}}">
                                                <input type="hidden" name="fees_master" value="{{$fees_due->feesGroupMaster->id}}">
                                            </td>
                                            <td>
                                                <div class="dropdown CRM_dropdown">
                                                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                                                        @lang('common.select')
                                                    </button>
                                                    @if(userPermission('fees_collect_student_wise'))
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a class="dropdown-item" href="{{route('fees_collect_student_wise', [$fees_due->recordDetail->id])}}">@lang('common.view')</a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endif
                            </x-table>
                        </div>
                    </div> 
                </div>
            </div>
        {{ Form::close() }}
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_range_picker_css_js')

