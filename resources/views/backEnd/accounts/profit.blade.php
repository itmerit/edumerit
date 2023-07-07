@extends('backEnd.master')
@section('title') 
@lang('accounts.profit_loss')
@endsection
@section('mainContent')
@php  $setting = generalSetting(); if(!empty(@$setting->currency_symbol)){ @$currency = @$setting->currency_symbol; }else{ @$currency = '$'; } @endphp
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.profit_&_loss')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.profit_&_loss')</a>
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
                       {!! Form::open(['route' => 'search_profit_by_date', 'method' => 'POST' ]) !!}
                       
                            <div class="row">
                                
                                <div class="col-md-6 offset-md-3 mt-30-md">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input placeholder="" class="primary_input_field primary_input_field form-control text-center" type="text" name="date_range" value="">
                                            </div>
                                            @if ($errors->has('date_range'))
                                                <span class="text-danger invalid-select" role="alert">
                                                    {{ $errors->first('date_range') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-20 text-center">
                                    <button type="submit" class="primary-btn small fix-gr-bg">
                                        <span class="ti-search pr-2"></span>
                                        @lang('common.search')
                                    </button>
                                </div>
                               
                            </div>
                       {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="row mt-40">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-0">@lang('accounts.profit_&_loss')</h3>
                            </div>
                        </div>
                    </div>                
                    <!-- </div> -->
                    <div class="row">
                        <div class="col-lg-12">
                            <x-table>
                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('common.time')</th>
                                        <th>@lang('accounts.income')</th>
                                        <th>@lang('accounts.expense')</th>
                                        <th>@lang('accounts.profit_/_loss')</th>
                                    </tr>
                                </thead>
                                <tbody>                                   
                                    <tr>
                                        <td >
                                            {{isset($date_time_from)? dateConvert($date_time_from).' - '.dateConvert($date_time_to): "All"}}  
                                        </td>
                                        <td>
                                            {{currency_format(@$total_income)}}
                                        </td>
                                        <td>
                                            {{currency_format(@$total_expense)}}
                                        </td>
                                        <td>
                                            @php
                                                $total=@$total_income-@$total_expense;
                                            @endphp
                                            
                                            {{currency_format(@$total)}}
                                        </td>
                                    </tr>
                                    
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
@include('backEnd.partials.date_range_picker_css_js')

