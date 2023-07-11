@extends('backEnd.master')
@section('title')
    @lang('hr.generate_payroll')
@endsection
@push('css')
    <style>
        element.style {
            width: 190px !important;
        }

        table.dataTable thead th {
            /* padding: 10px 30px !important; */
            padding-left: 25px !important;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 20px 10px 20px 15px !important;
        }

        table.dataTable thead .sorting::after {
            left: 10px !important;
            top: 10px;
        }

        table.dataTable thead .sorting_asc::after {
            left: 10px !important;
            top: 10px;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('hr.generate_payroll')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('hr.human_resource')</a>
                    <a href="#">@lang('hr.generate_payroll')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            @if (userPermission('payroll-search'))
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('common.select_criteria')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            {{ Form::open(['class' => 'form-horizontal', 'route' => 'payroll', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                            <div class="row">
                                <div class="col-lg-4 mt-30-md">
                                    <select
                                        class="primary_select  form-control {{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                        name="role_id" id="role_id">
                                        <option data-display="@lang('hr.role') *" value="">@lang('hr.select_role') *
                                        </option>
                                        @foreach ($roles as $key => $value)
                                            <option value="{{ $value->id }}"
                                                {{ isset($role_id) ? ($role_id == $value->id ? 'selected' : '') : '' }}>
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('role_id'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('role_id') }}
                                        </span>
                                    @endif
                                </div>
                                @php $month = date('F'); @endphp
                                <div class="col-lg-4 mt-30-md">
                                    <select
                                        class="primary_select  form-control {{ $errors->has('payroll_month') ? 'is-invalid' : '' }}"
                                        name="payroll_month" id="payroll_month">
                                        <option data-display="@lang('student.select_month') *" value="">@lang('student.select_month') *
                                        </option>
                                        <option value="January"
                                            {{ isset($payroll_month) ? ($payroll_month == 'January' ? 'selected' : '') : ($month == 'January' ? 'selected' : '') }}>
                                            @lang('student.january')</option>
                                        <option value="February"
                                            {{ isset($payroll_month) ? ($payroll_month == 'February' ? 'selected' : '') : ($month == 'February' ? 'selected' : '') }}>
                                            @lang('student.february')</option>
                                        <option value="March"
                                            {{ isset($payroll_month) ? ($payroll_month == 'March' ? 'selected' : '') : ($month == 'March' ? 'selected' : '') }}>
                                            @lang('student.march')</option>
                                        <option value="April"
                                            {{ isset($payroll_month) ? ($payroll_month == 'April' ? 'selected' : '') : ($month == 'April' ? 'selected' : '') }}>
                                            @lang('student.april')</option>
                                        <option value="May"
                                            {{ isset($payroll_month) ? ($payroll_month == 'May' ? 'selected' : '') : ($month == 'May' ? 'selected' : '') }}>
                                            @lang('student.may')</option>
                                        <option value="June"
                                            {{ isset($payroll_month) ? ($payroll_month == 'June' ? 'selected' : '') : ($month == 'June' ? 'selected' : '') }}>
                                            @lang('student.june')</option>
                                        <option value="July"
                                            {{ isset($payroll_month) ? ($payroll_month == 'July' ? 'selected' : '') : ($month == 'July' ? 'selected' : '') }}>
                                            @lang('student.july')</option>
                                        <option value="August"
                                            {{ isset($payroll_month) ? ($payroll_month == 'August' ? 'selected' : '') : ($month == 'August' ? 'selected' : '') }}>
                                            @lang('student.august')</option>
                                        <option value="September"
                                            {{ isset($payroll_month) ? ($payroll_month == 'September' ? 'selected' : '') : ($month == 'September' ? 'selected' : '') }}>
                                            @lang('student.september')</option>
                                        <option value="October"
                                            {{ isset($payroll_month) ? ($payroll_month == 'October' ? 'selected' : '') : ($month == 'October' ? 'selected' : '') }}>
                                            @lang('student.october')</option>
                                        <option value="November"
                                            {{ isset($payroll_month) ? ($payroll_month == 'November' ? 'selected' : '') : ($month == 'November' ? 'selected' : '') }}>
                                            @lang('student.november')</option>
                                        <option value="December"
                                            {{ isset($payroll_month) ? ($payroll_month == 'December' ? 'selected' : '') : ($month == 'December' ? 'selected' : '') }}>
                                            @lang('student.december')</option>
                                    </select>
                                    @if ($errors->has('payroll_month'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('payroll_month') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <select
                                        class="primary_select form-control{{ $errors->has('payroll_year') ? ' is-invalid' : '' }}"
                                        name="payroll_year" id="payroll_year">
                                        <option data-display="@lang('hr.select_year') *" value="">@lang('hr.select_year') *
                                        </option>
                                        @php
                                            $year = date('Y');
                                            $ini = date('y');
                                            $limit = $ini + 30;
                                        @endphp
                                        @for ($i = $ini; $i <= $limit; $i++)
                                            <option value="{{ $year }}"
                                                {{ isset($payroll_year) ? ($payroll_year == $year ? 'selected' : '') : (date('Y') == $year ? 'selected' : '') }}>
                                                {{ $year-- }}</option>
                                        @endfor
                                    </select>
                                    @if ($errors->has('payroll_year'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('payroll_year') }}
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
            @endif
            @if (isset($staffs))
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">@lang('hr.staff_list')</h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table id="table_id" class="table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>@lang('hr.staff_no')</th>
                                            <th>@lang('common.name')</th>
                                            <th>@lang('hr.role')</th>
                                            <th>@lang('hr.department')</th>
                                            <th>@lang('common.description')</th>
                                            <th>@lang('common.mobile')</th>
                                            <th>@lang('hr.paid')</th>
                                            <th style="width: 190px !important;">@lang('common.status')</th>
                                            <th>@lang('common.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($staffs as $value)
                                                @php
                                                    $getPayrollDetails = $value->payrollStatus;
                                                @endphp
                                            <tr>
                                                <td>{{ $value->staff_no }}</td>
                                                <td>{{ $value->first_name }}&nbsp;{{ $value->last_name }}</td>
                                                <td>{{ $value->roles != '' ? $value->roles->name : '' }}</td>
                                                <td>{{ $value->departments != '' ? $value->departments->name : '' }}</td>
                                                <td>{{ $value->designations != '' ? $value->designations->title : '' }}
                                                </td>
                                                <td>{{ $value->mobile }}</td>
                                                <td>
                                                    {{ $getPayrollDetails ?  $getPayrollDetails->payrollPayments->sum('amount') : __('hr.not_generated')}}
                                                </td>
                                                <td>
                                                    
                                                    @if (!empty($getPayrollDetails))
                                                        @if ($getPayrollDetails->payroll_status == 'G')
                                                      
                                                            @if(count($getPayrollDetails->payrollPayments) > 0)
                                                                <button
                                                                class="primary-btn small bg-warning text-white border-0">
                                                                @lang('fees.partial')</button>
                                                            @else
                                                                <button
                                                                    class="primary-btn small bg-warning text-white border-0">
                                                                    @lang('hr.generated')</button>
                                                            @endif
                                                        @endif
                                                        @if ($getPayrollDetails->payroll_status == 'P')
                                                            <button
                                                                class="primary-btn small bg-success text-white border-0">
                                                                @lang('fees.paid') </button>
                                                        @endif
                                                    @else
                                                        
                                                        <button
                                                            class="primary-btn small bg-danger text-white border-0 nowrap">@lang('hr.not_generated')</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <x-drop-down>
                                                        @if (!empty($getPayrollDetails))
                                                            @if ($getPayrollDetails->payroll_status == 'G')
                                                                @if (userPermission('pay-payroll'))
                                                                    <a class="dropdown-item modalLink"
                                                                        data-modal-size="modal-lg"
                                                                        title="@lang('hr.proceed_to_pay')"
                                                                        href="{{ route('pay-payroll', [$getPayrollDetails->id, $value->role_id]) }}">@lang('hr.proceed_to_pay')</a>
                                                                @endif
                                                                <a class="dropdown-item"
                                                                    href="{{ route('print-payslip', $getPayrollDetails->id) }}">@lang('hr.print')</a>
                                                            @endif
                                                            @if ($getPayrollDetails->payroll_status == 'P')
                                                                @if (userPermission('view-payslip'))
                                                                    <a class="dropdown-item modalLink"
                                                                        data-modal-size="modal-md"
                                                                        title="@lang('hr.view_payslip')"
                                                                        href="{{ route('view-payslip', $getPayrollDetails->id) }}">@lang('hr.view_payslip')</a>
                                                                @endif
                                                            @endif
                                                            @if($getPayrollDetails->payrollPayments)
                                                                <a class="dropdown-item modalLink" title="View Payroll Payment"
                                                                data-modal-size="modal-xl"
                                                              
                                                            href="{{ route('view-payroll-payment', $getPayrollDetails->id) }}">@lang('hr.view Payment')</a>

                                                            
                                                            @endif
                                                        @else
                                                            @if (userPermission('generate-Payroll'))
                                                                <a class="dropdown-item"
                                                                    href="{{ route('generate-Payroll', [@$value->id, @$payroll_month, @$payroll_year]) }}">@lang('hr.generate_payroll')</a>
                                                            @endif
                                                        @endif
                                                    </x-drop-down>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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
