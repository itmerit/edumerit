@extends('backEnd.master')
@section('title') 
@lang('bulkprint::bulk.fees_bulk_print')
@endsection
@section('mainContent')
@php  $setting = generalSetting();  if(!empty($setting->currency_symbol)){ $currency = $setting->currency_symbol; }else{ $currency = '$'; }   @endphp 

<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('bulkprint::bulk.fees_bulk_print')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('fees.fees_collection')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('bulkprint::bulk.fees_bulk_print')</a>
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
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'fees-bulk-print-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        <div class="col-lg-5 mt-30-md">
                            <select class="primary_select  {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                @foreach($classes as $class)
                                <option value="{{$class->id}}"  {{ isset($class_id)? ($class_id == $class->id? 'selected':''): (old("class") == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('class'))
                            <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                            @endif
                        </div>
                        <div class="col-lg-5 mt-30-md" id="select_section_div">
                            <select class="primary_select {{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                <option data-display="@lang('common.select_section') " value="">@lang('common.select_section') *</option>
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
                       
                       
                        <div class="col-lg-2 mt-30-md">
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
        @if(isset($fees_payments))
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('bulkprint::bulk.fees_collection_details')</h3>
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
                                    <th>@lang('bulkprint::bulk.mode')</th>
                                    <th>@lang('accounts.amount')</th>
                                    <th>@lang('fees.fine')</th>
                                    <th>@lang('exam.result')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grand_amount = 0;
                                    $grand_total = 0;
                                    $grand_discount = 0;
                                    $grand_fine = 0;
                                    $total = 0;
                                @endphp
                                @foreach($fees_payments as $students)
                                    @foreach($students as $fees_payment)
                                    @php $total = 0; @endphp
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
                                                $total =  $total + $fees_payment->amount;
                                                $grand_amount =  $grand_amount + $fees_payment->amount;
                                                echo currency_format($fees_payment->amount);
                                            @endphp
                                        </td>
                                        
                                        <td>
                                            @php
                                                $total =  $total + $fees_payment->fine;
                                                $grand_fine =  $grand_fine + $fees_payment->fine;
                                                echo currency_format($fees_payment->fine);
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                $grand_total =  $grand_total + $total;
                                                echo currency_format($total);
                                            @endphp
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>@lang('accounts.grand_total') </th>
                                <th>{{currency_format($grand_amount)}}</th>
                                <th>{{currency_format($grand_fine)}}</th>
                                <th>{{currency_format($grand_total)}}</th>
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
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_range_picker_css_js')

