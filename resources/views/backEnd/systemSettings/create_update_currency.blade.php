@extends('backEnd.master')
@section('title')
@lang('system_settings.manage_currency')
@endsection 
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@if(isset($editData))
                    @lang('system_settings.edit_currency')
                @else
                    @lang('system_settings.add_currency')
                @endif</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.manage_currency')</a>
                    <a href="#">@if(isset($editData))
                        @lang('system_settings.edit_currency')
                    @else
                        @lang('system_settings.add_currency')
                    @endif</a>

                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
           
            <div class="row">                
                <div class="col-lg-12">
                    <div class="main-title">
                        <h3 class="mb-30">
                            @if(isset($editData))
                                @lang('system_settings.edit_currency')
                            @else
                                @lang('system_settings.add_currency')
                            @endif
                            
                        </h3>
                    </div>
                    @if(isset($editData))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'currency-update', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <input type="hidden" name="id" value="{{isset($editData)? @$editData->id: ''}}">
                    @else
                        @if(userPermission('currency-store'))
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'currency-store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                    @endif
                    <div class="white-box">
                        <div class="add-visitor">
                            <div class="row"> 
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('common.name') <span class="text-danger"> *</span></label>
                                        <input class="primary_input_field form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" autocomplete="off" value="{{isset($editData)? @$editData->name: old('name')}}" maxlength="25" >                                            
                                        
                                        
                                        @if ($errors->has('name'))
                                            <span class="text-danger" >
                                                {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('system_settings.code') <span class="text-danger"> *</span></label>
                                        <input class="primary_input_field form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" type="text" name="code" autocomplete="off" value="{{isset($editData)? @$editData->code: old('code')}}" maxlength="10" >                                            
                                        
                                        
                                        @if ($errors->has('code'))
                                            <span class="text-danger" >
                                                {{ $errors->first('code') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('system_settings.symbol') <span class="text-danger"> *</span></label>
                                        <input class="primary_input_field form-control{{ $errors->has('symbol') ? ' is-invalid' : '' }}" type="text" name="symbol" autocomplete="off" value="{{isset($editData)? @$editData->symbol: old('symbol')}}" maxlength="5" >                                            
                                      
                                        
                                        @if ($errors->has('symbol'))
                                            <span class="text-danger" >
                                                {{ $errors->first('symbol') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <label for="" class="mt-5"> {{ __('system_settings.Currency Format Setup') }}</label>
                            <div class="row mt-10"> 
                                <div class="col-lg-4">
                                    <label for="">{{ __('system_settings.Currency Show') }}</label>
                                    <div class="d-flex radio-btn-flex mt-10">
                                        
                                        <div class="mr-30">
                                            <input type="radio" name="currency_type" id="currency_code" value="C" class="common-radio relationButton" {{ isset($editData) ? $editData->currency_type == 'C' ? 'checked':'':'' }}>
                                            <label for="currency_code">@lang('system_settings.code')</label>
                                        </div>
                                        <div class="mr-30">
                                            <input type="radio" name="currency_type" id="currency_symbol" value="S" class="common-radio relationButton" {{ isset($editData) ? $editData->currency_type == 'S' ? 'checked':'':'checked' }}>
                                            <label for="currency_symbol">@lang('system_settings.symbol')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="">{{ __('system_settings.position') }}</label>
                                    <div class="d-flex radio-btn-flex mt-10">
                                        
                                        <div class="mr-30">
                                            <input type="radio" name="currency_position" id="currency_suffix" value="S" class="common-radio relationButton" {{ isset($editData) ? $editData->currency_position == 'S' ? 'checked':'':'checked' }}>
                                            <label for="currency_suffix">@lang('system_settings.suffix')</label>
                                        </div>
                                        <div class="mr-30">
                                            <input type="radio" name="currency_position" id="currency_prefix" value="P" class="common-radio relationButton" {{ isset($editData) ? $editData->currency_position == 'P' ? 'checked':'':'' }}>
                                            <label for="currency_prefix">@lang('system_settings.prefix')</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for=""> {{ __('system_settings.With Space') }}</label>
                                    <div class="d-flex radio-btn-flex mt-10">
                                        
                                        <div class="mr-30">
                                            <input type="radio" name="space" id="space_yes" value="1" class="common-radio relationButton" {{ isset($editData) ? $editData->space ? 'checked':'':'checked' }}>
                                            <label for="space_yes">@lang('common.yes')</label>
                                        </div>
                                        <div class="mr-30">
                                            <input type="radio" name="space" id="space_no" value="0" class="common-radio relationButton" {{ isset($editData) ? !$editData->space ? 'checked':'':'' }}>
                                            <label for="space_no">@lang('common.no')</label>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <div class="row mt-40"> 
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('system_settings.number of decimal digits') </label>
                                        <input class="primary_input_field form-control{{ $errors->has('decimal_digit') ? ' is-invalid' : '' }}" type="text" name="decimal_digit" autocomplete="off" value="{{isset($editData)? @$editData->decimal_digit: old('decimal_digit')}}" maxlength="5" >                                            
                                        
                                        
                                        @if ($errors->has('decimal_digit'))
                                            <span class="text-danger" >
                                                {{ $errors->first('decimal_digit') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('system_settings.Decimal point separator') </label>
                                        <input class="primary_input_field form-control{{ $errors->has('decimal_separator') ? ' is-invalid' : '' }}" type="text" name="decimal_separator" autocomplete="off" value="{{isset($editData)? @$editData->decimal_separator: old('decimal_separator')}}">                                            
                                        
                                        
                                        @if ($errors->has('decimal_separator'))
                                            <span class="text-danger" >
                                                {{ $errors->first('decimal_separator') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="primary_input">
                                        <label class="primary_input_label" for="">@lang('system_settings.Thousands Separator')</label>
                                        <input class="primary_input_field form-control{{ $errors->has('thousand_separator') ? ' is-invalid' : '' }}" type="text" name="thousand_separator" autocomplete="off" value="{{isset($editData)? @$editData->thousand_separator: old('thousand_separator')}}">                                            
                                       
                                        
                                        @if ($errors->has('thousand_separator'))
                                            <span class="text-danger" >
                                                {{ $errors->first('thousand_separator') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>

                            @php 
                                $tooltip = "";
                                if(userPermission('currency-store')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                            @endphp
                            <div class="row mt-40">
                                <div class="col-lg-12 text-center">
                                    <button class="primary-btn fix-gr-bg submit" data-toggle="tooltip" title="{{@$tooltip}}">
                                        <span class="ti-check"></span>
                                        @if(isset($editData))
                                            @lang('system_settings.update_currency')
                                        @else
                                            @lang('system_settings.save_currency')
                                        @endif
                                    
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>

@endsection
