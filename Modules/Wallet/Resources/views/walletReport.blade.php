@extends('backEnd.master')
    @section('title') 
        @lang('wallet::wallet.wallet_report')
    @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('wallet::wallet.wallet_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('wallet::wallet.my_wallet')</a>
                <a href="#">@lang('wallet::wallet.wallet_report')</a>
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
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'wallet.wallet-report-search', 'method' => 'POST']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            <div class="col-md-4">
                                <label class="primary_input_label" for="">
                                    {{ __('common.date_range') }}
                                        <span class="text-danger"> *</span>
                                </label>
                                <input placeholder="" class="primary_input_field primary_input_field form-control" type="text" name="date_range" value="">
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <label class="primary_input_label" for="">
                                    {{ __('common.type') }}
                                        <span class="text-danger"> *</span>
                                </label>
                                <select class="primary_select  form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" name="type">
                                    <option data-display="@lang('common.select_type')*" value="">@lang('common.select_type')*</option>
                                    <option value="diposit">@lang('wallet::wallet.diposit')</option>
                                    <option value="refund">@lang('wallet::wallet.refund')</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <label class="primary_input_label" for="">
                                    {{ __('common.status') }}
                                        <span class="text-danger"> </span>
                                </label>
                                <select class="primary_select  form-control" name="status">
                                    <option data-display="@lang('common.select_status')" value="">@lang('common.select_status')</option>
                                    <option value="pending">@lang('common.pending')</option>
                                    <option value="approve">@lang('wallet::wallet.approve')</option>
                                    <option value="reject">@lang('wallet::wallet.reject')</option>
                                </select>
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
    </div>
</section>
<section class="admin-visitor-area up_st_admin_visitor mt-40">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <x-table>
                    <table id="table_id" class="table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('common.sl')</th>
                                <th>@lang('common.name')</th>
                                <th>@lang('common.status')</th>
                                <th>@lang('wallet::wallet.apply_date')</th>
                                <th>@lang('wallet::wallet.approve_date')</th>
                            </tr>
                        </thead>
                        @if (isset($walletReports))
                            <tbody>
                                @foreach ($walletReports as $key=>$walletReport)
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td>{{@$walletReport->userName->full_name}}</td>
                                        <td>
                                            @if ($walletReport->status == 'pending')
                                                <button class="primary-btn small bg-warning text-white border-0">@lang('common.pending')</button> 
                                            @elseif ($walletReport->status == 'approve')
                                                <button class="primary-btn small bg-success text-white border-0">@lang('wallet::wallet.approve')</button>
                                            @else
                                                <button class="primary-btn small bg-danger text-white border-0">@lang('wallet::wallet.reject')</button>
                                            @endif
                                        </td>
                                        <td>{{dateConvert($walletReport->created_at)}}</td>
                                        <td>{{dateConvert($walletReport->updated_at)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @endif
                    </table>
                </x-table>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_range_picker_css_js')
