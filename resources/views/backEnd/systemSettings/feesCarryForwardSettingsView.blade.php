@extends('backEnd.master')
    @section('title') 
        @lang('fees.fees_carry_forward_settings')
    @endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('fees.fees_forward')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('fees.fees_carry_forward_settings')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('fees.fees_carry_forward_settings')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'route' => 'fees-carry-forward-settings-store', 'method' => 'POST']) }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('common.title') <span class="text-danger"> *</span></label>
                                        <input class="primary_input_field form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" type="text" name="title" autocomplete="off" value="{{isset($settings)? @$settings->title : old('title')}}">
                                        @error('title')
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('fees.fees_due_days') <span class="text-danger"> *</span></label>
                                        <input class="primary_input_field form-control{{ $errors->has('fees_due_days') ? ' is-invalid' : '' }}" type="text" name="fees_due_days" autocomplete="off" value="{{isset($settings)? @$settings->fees_due_days : old('fees_due_days')}}">
                                        @error('fees_due_days')
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $message }}
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="primary_input_label" for="">@lang('system_settings.select_a_payment_gateway')<span class="text-danger"> *</span></label>
                                    <select class="primary_select  form-control{{ $errors->has('payment_gateway') ? ' is-invalid' : '' }}" name="payment_gateway">
                                        @foreach($paymeny_gateway as $value)
                                            <option value="{{$value->method}}" {{old('payment_gateway') == $value->method? 'selected' : ($settings->payment_gateway == $value->method ? 'selected' : '')}}>{{@$value->method}}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_gateway')
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-12 mt-20 text-right">
                                    <div class="col-lg-12 text-center">
                                        <button class="primary-btn fix-gr-bg submit">
                                            <span class="ti-check"></span>
                                                @lang('common.update')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection