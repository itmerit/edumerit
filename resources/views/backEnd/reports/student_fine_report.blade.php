@extends('backEnd.master')
@section('title')
@lang('reports.student_fine_report')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.student_fine_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('reports.student_fine_report')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student_fine_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                <div class="col-lg-6 mt-30-md col-md-6">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date_from') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="date_from" value="{{date('m/d/Y')}}" readonly>
                                                    <label class="primary_input_label" for="">@lang('accounts.date_from')</label>
                                                    
                                                @if ($errors->has('date_from'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('date_from') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-30-md col-md-6">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('date_to') ? ' is-invalid' : '' }}" id="startDate" type="text"
                                                     name="date_to" value="{{date('m/d/Y')}}" readonly>
                                                    <label class="primary_input_label" for="">@lang('accounts.date_to')</label>
                                                    
                                                @if ($errors->has('date_to'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('date_to') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </button>
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
            
@if(isset($fees_payments))

            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('reports.student_fine_report')</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.date')</th>
                                        <th>@lang('common.name')</th>
                                        <th>@lang('common.class')</th>
                                        <th>@lang('fees.fees_type')</th>
                                        <th>@lang('fees.fine')</th>
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
                                        @foreach($fees_payments as $fees_payment)
                                        @php $total = 0; @endphp
                                        <tr>
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
                                                @php
                                                    $total =  $total + $fees_payment->fine;
                                                    $grand_fine =  $grand_fine + $fees_payment->fine;
                                                    echo $fees_payment->fine;
                                                @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                </tbody>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>@lang('accounts.grand_total') </th>
                                    <th>{{$grand_fine}}</th>
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
@include('backEnd.partials.date_picker_css_js')