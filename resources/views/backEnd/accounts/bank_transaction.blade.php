@extends('backEnd.master')
@section('title') 
@lang('accounts.bank_transaction')
@endsection
@section('mainContent')
@push('css')
<style>
    table.dataTable thead th {
        padding: 10px 30px !important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
        padding: 20px 30px 20px 30px !important;
    }

    table.dataTable tfoot th, table.dataTable tfoot td {
        padding: 10px 30px 6px 30px;
    }
</style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('accounts.bank_transaction')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('accounts.accounts')</a>
                <a href="#">@lang('accounts.bank_transaction')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">        
        <div class="row ">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('accounts.bank_transaction') ({{$bank_name->bank_name .' '.'-'.' '. $bank_name->account_name}})</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="tableWithoutSort" class="table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('common.details')</th>
                                    <th>@lang('accounts.income')</</th>
                                    <th>@lang('accounts.expense')</</th>
                                    <th>@lang('fees.payment_date')</</th>
                                    <th><span class="text-right d-block">@lang('accounts.balance')</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>@lang('accounts.opening_balance')</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><span class="text-right d-block">{{currency_format($bank_name->opening_balance)}}</span></td>
                            </tr>
                                @php 
                                    $total_credit= 0;
                                    $total_debit = 0;
                                @endphp
                            @foreach($bank_transactions as $value)
                            @php
                                if($value->type==1){
                                    $total_credit = $total_credit + $value->amount;
                                }

                                if($value->type==0){
                                    $total_debit = $total_debit + $value->amount;
                                }
                            @endphp
                            <tr>
                                <td>{{$value->details}}</td>
                                <td>
                                @if($value->type==1)
                              {{currency_format($value->amount)}}
                                @endif
                                </td>
                                <td>
                                @if($value->type==0)
                              {{currency_format($value->amount)}}
                                @endif
                                </td>
                                <td>
                                {{ dateConvert($value->payment_date) }}
                                </td>
                                <td class="text-right">{{currency_format($value->after_balance)}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-right">@lang('exam.result'):</th>
                                    <th class="">{{currency_format($total_credit)}}</th>
                                    <th class="">{{currency_format($total_debit)}}</th>
                                    <th class="text-right">@lang('accounts.current_balance'):</th>
                                    <th class="text-right">{{currency_format($bank_name->current_balance)}}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection