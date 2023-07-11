@extends('backEnd.master')
@section('title') 
@lang('accounts.fine_report')
@endsection
@section('mainContent')
@php  $setting = generalSetting();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp 
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.fine_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.reports')</a>
                <a href="#">@lang('accounts.fine_report')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
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
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fine-report-search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-md-6">
                                <input placeholder="" class="primary_input_field primary_input_field form-control" type="text" name="date_range" value="">
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select  form-control{{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class')" value="">@lang('common.select_class')</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}">{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="primary_select  form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section')" value="">@lang('common.select_section')</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
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
        @if(isset($fine_info))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('accounts.fine_report')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="table_id_al" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('fees.payment_id')</th>
                                    <th>@lang('common.date')</th>
                                    <th>@lang('common.name')</th>
                                    <th>@lang('common.class')</th>
                                    <th>@lang('fees.fees_type')</th>
                                    <th>@lang('accounts.mode')</th>
                                    <th>@lang('accounts.fine')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grand_amount = 0;
                                    $grand_total = 0;
                                    $grand_fine = 0;
                                @endphp
                                @foreach($fine_info as $students)
                                    @foreach($students as $fees_payment)
                                    @php $total = 0; @endphp
                                    @if($fees_payment->fine>0)
                                    <tr>
                                        <td>{{$fees_payment->fees_type_id.'/'.$fees_payment->id}}</td>
                                        <td  data-sort="{{strtotime($fees_payment->payment_date)}}" >
                                        {{$fees_payment->payment_date != ""? dateConvert($fees_payment->payment_date):''}}
                                        </td>
                                        <td>{{$fees_payment->studentInfo !=""?$fees_payment->studentInfo->full_name:""}}</td>
                                        <td>
                                        @if($fees_payment->studentInfo!="" && $fees_payment->studentInfo->class!="")
                                            {{$fees_payment->studentInfo->class->class_name}}
                                            @endif
                                        </td>
                                        <td>{{$fees_payment->feesType!=""?$fees_payment->feesType->name:""}}</td>
                                        <td>
                                        {{@$fees_payment->payment_mode}}
                                        </td>
                                        <td>
                                            @php
                                                $total =  $total + $fees_payment->fine;
                                                $grand_fine =  $grand_fine + $fees_payment->fine;
                                                echo currency_format($fees_payment->fine);
                                            @endphp
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>@lang('fees.grand_total') </th>
                                <th>{{currency_format($grand_fine)}}</th>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
@include('backEnd.partials.date_range_picker_css_js')
@include('backEnd.partials.data_table_js')