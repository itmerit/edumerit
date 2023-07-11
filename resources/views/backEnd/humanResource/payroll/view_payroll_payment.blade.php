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
                    <a href="#">@lang('hr.view Payment')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            
         
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-4 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-0">{{$generatePayroll->payroll_month}} {{$generatePayroll->payroll_year}}
                                    {{ __('hr.partial_payment_list') }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <x-table>
                                    <table id="table_id" class="table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>@lang('hr.staff_no')</th>
                                                <th>@lang('common.name')</th>
                                                <th>@lang('hr.role')</th>
                                                <th>@lang('accounts.amount')</th>
                                                <th>@lang('accounts.payment_method')</th>
                                                <th>@lang('hr.payment_date')</th>
                                                <th>@lang('common.created_by')</th>                                         
                                                <th>@lang('common.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payrollPayments as $payment)
                                                <tr>
                                                    <td>
                                                    <a href="{{ route('viewStaff', $generatePayroll->staffDetails->id) }}" target="_blank" rel="noopener noreferrer">{{ $generatePayroll->staffDetails->staff_no }}</a>
                                                    </td>
                                                    <td>{{ $generatePayroll->staffDetails->full_name }}</td>
                                                    <td>{{ $generatePayroll->staffDetails->roles->name }}</td>
                                                    <td>{{ $payment->amount }}</td>
                                                    <td>{{ $payment->paymentMethod->method }}</td>
                                                    <td>{{ $payment->payment_date }}</td>
                                                    <td>{{ $payment->createdBy->full_name }} </td>
                                                    <td>
                                                        <x-drop-down>
                                                            <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal_{{ $payment->id }}"
                                                            href="#">@lang('common.delete')</a>
                                                            <a class="dropdown-item" target="_blank"
                                                            href="{{ route('print-payroll-payment', $payment->id)}}">@lang('common.print')</a>
                                                        </x-drop-down>
                                                    </td>
                                                </tr>
                                                <div class="modal fade admin-query" id="deleteModal_{{ $payment->id }}">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">@lang('common.delete')</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;
                                                                </button>
                                                            </div>
            
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <h4>@lang('common.are_you_sure_to_delete')</h4>
                                                                </div>
            
                                                                <div class="mt-40 d-flex justify-content-between">
                                                                    <button type="button" class="primary-btn tr-bg"
                                                                            data-dismiss="modal">@lang('common.cancel')</button>
                                                                    
                                                                    {!! Form::open(['route'=>'delete-payroll-payment', 'method'=>'POST']) !!}
                                                                    <input type="hidden" name="payroll_payment_id" value="{{ $payment->id }}">
                                                                        <button class="primary-btn fix-gr-bg"
                                                                                type="submit">@lang('common.delete')</button>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            </div>
            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
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
@include('backEnd.partials.date_picker_css_js')
