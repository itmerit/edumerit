@extends('backEnd.master')
@section('title')
    @lang('accounts.payroll_report')
@endsection
@section('mainContent')
    @push('css')
        <style>
            #table_id_wrapper {
                margin-top: 50px;
            }

            table.dataTable tbody th,
            table.dataTable tbody td {
                padding: 20px 30px 20px 30px !important;
            }

            table.dataTable tfoot th,
            table.dataTable tfoot td {
                padding: 10px 30px 6px 30px;
            }
        </style>
    @endpush
    @php
        @$setting = generalSetting();
        if (!empty(@$setting->currency_symbol)) {
            @$currency = @$setting->currency_symbol;
        } else {
            @$currency = '$';
        }
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('accounts.payroll_report')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('accounts.accounts')</a>
                    <a href="#">@lang('accounts.reports')</a>
                    <a href="#">@lang('accounts.payroll_report')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'accounts-payroll-report-search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-6 offset-lg-3 mt-30-md">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input placeholder=""
                                                class="primary_input_field primary_input_field form-control text-center"
                                                type="text" name="date_range" value="">
                                        </div>
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
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
            @if (isset($payroll_infos))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('accounts.payroll_report')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('common.name')</th>
                                                <th>@lang('accounts.expense_head')</th>
                                                <th>@lang('accounts.payment_method')</th>
                                                <th>@lang('accounts.amount')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php 
                                                $total=0;
                                            @endphp
                                            @foreach($payroll_infos as $payroll_info)
                                            @php 
                                                $total= $total+ $payroll_info->amount
                                            @endphp
                                            <tr>
                                                <td>{{@$payroll_info->description}}</td>
                                                <td>{{@$payroll_info->ACHead->head}}</td>
                                                <td>
                                                {{@$payroll_info->paymentMethod->method}}
                                                @if(@$payroll_info->payment_method_id==3)
                                                ({{@$payroll_info->account->bank_name}})
                                                @endif
                                                </td>
                                                <td>{{currency_format(@$payroll_info->amount)}}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>@lang('accounts.total')</td>
                                                <td>{{currency_format($total)}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </x-table>
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
