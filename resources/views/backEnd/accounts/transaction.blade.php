@extends('backEnd.master')
@section('title')
    @lang('accounts.transaction')
@endsection
@section('mainContent')
    @push('css')
        <style>
            #table_id_wrapper {
                margin-top: 50px;
            }
            
            table.dataTable{
                padding: 15px 30px !important;
            }

            table.dataTable thead .sorting_asc::after {
                top: 10px !important;
                left: 3px !important;
            }

            table.dataTable thead .sorting::after {
                top: 10px !important;
                left: 3px !important;
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
                <h1>@lang('accounts.transaction')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('accounts.accounts')</a>
                    <a href="#">@lang('reports.reports')</a>
                    <a href="#">@lang('accounts.transaction')</a>
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
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'transaction-search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{ URL::to('/') }}">
                            <div class="col-lg-6 mt-30-md">
                                <div class="no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">
                                                {{ __('common.date_range') }}
                                                <span class="text-danger"> *</span>
                                            </label>
                                            <input placeholder=""
                                                class="primary_input_field primary_input_field form-control" type="text"
                                                name="date_range" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label class="primary_input_label" for="">
                                    {{ __('common.type') }}
                                    <span class="text-danger"> *</span>
                                </label>
                                <select class="primary_select  form-control{{ $errors->has('type') ? ' is-invalid' : '' }}"
                                    name="type" id="account-type">
                                    <option data-display="@lang('common.search_type')" value="all">@lang('common.search_type')</option>
                                    <option value="In">@lang('accounts.income')</option>
                                    <option value="Ex">@lang('accounts.expense')</option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ @$errors->first('type') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-lg-3">
                                <div class="primary_input">
                                    <label class="primary_input_label" for="">
                                        {{ __('accounts.payment_method') }}
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select  form-control" name="payment_method" id="payment_method">
                                        <option data-display="@lang('common.all')" value="all">@lang('common.all')</option>
                                        @foreach ($payment_methods as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ isset($search_info) ? ($search_info['method_id'] == $value->id ? 'selected' : '') : '' }}>
                                                {{ $value->method }}</option>
                                        @endforeach
                                    </select>
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
            @if (isset($add_incomes))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('accounts.income_result')</h3>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="left: 10px;">@lang('common.date')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('accounts.payroll')</th>
                                            <th>@lang('accounts.payment_method')</th>
                                            <th style="right: 10px;">@lang('accounts.amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_income = 0;
                                        @endphp
                                        @foreach ($add_incomes as $add_income)
                                            @php
                                                @$total_income = @$total_income + @$add_income->amount;
                                            @endphp
                                            <tr>
                                                <td style="left: 10px;">{{ dateConvert(@$add_income->date) }}</td>
                                                <td>{{ @$add_income->name }}</td>
                                                <td>{{ @$add_income->ACHead->head }}</td>
                                                <td>
                                                    {{ @$add_income->paymentMethod->method }}
                                                    @if (@$add_income->payment_method_id == 3)
                                                        ({{ @$add_income->account->bank_name }})
                                                    @endif
                                                </td>
                                                <td style="right: 10px;">{{ @$add_income->amount }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">@lang('accounts.grand_total'):</th>
                                            <th>{{ currency_format($total_income) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (isset($add_expenses))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('accounts.expense_result')</h3>
                                </div>
                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="left: 10px;">@lang('common.date')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('accounts.expense_head')</th>
                                            <th>@lang('accounts.payment_method')</th>
                                            <th style="right: 10px;">@lang('accounts.amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total_expense = 0;
                                        @endphp
                                        @foreach ($add_expenses as $add_expense)
                                            @php
                                                @$total_expense = @$total_expense + @$add_expense->amount;
                                            @endphp
                                            <tr>
                                                <td style="left: 10px;">{{ dateConvert(@$add_expense->date) }}</td>
                                                <td>{{ @$add_expense->name }}</td>
                                                <td>{{ @$add_expense->ACHead->head }}</td>
                                                <td>
                                                    {{ @$add_expense->paymentMethod->method }}
                                                    @if (@$add_expense->payment_method_id == 3)
                                                        ({{ @$add_expense->account->bank_name }})
                                                    @endif
                                                </td>
                                                <td style="right: 10px;">{{ currency_format(@$add_expense->amount) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">@lang('accounts.grand_total'):</th>
                                            <th>{{ currency_format($total_expense) }}</th>
                                        </tr>
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
