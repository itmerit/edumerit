@extends('backEnd.master')
@section('title')
    @lang('system_settings.language_export')
@endsection
@push('style')
    <style>
        .primary_input {
            border-radius: 30px 0 0 30px;
            background: #ECEEF3;
            border: 0;
            color: #fff;
            text-transform: uppercase;
            padding-left: 27px;
            padding-right: 27px;
            font-size: 14px;
            font-weight: 500;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.language_export')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.language_export')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            {{ Form::open(['class' => 'form-horizontal', 'route' => 'file-export', 'method' => 'POST']) }}
            <input type="hidden" name="lang" value="{{ $lang }}">
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <input type="checkbox" id="checkAll" class="common-checkbox" name="checkAll">
                                <label for="checkAll">@lang('common.select_all')</label>
                             </div>
                             <div class="col-lg-6  text-left">
                                <button type="submit" class="primary-btn small fix-gr-bg" type="submit">
                                    <span class="ti-download pr-2"></span>
                                    @lang('common.download')
                                </button>
                            </div>
                        </div>
                        <label for="">{{ __('common.select_files') }} <span class="text-danger"> *</span></label>
                        <div class="row mb-25">
                            @php
                            $count= count($files);
                            $half = round($count / 2);
                            @endphp                            
                             
                            @foreach($files as $key=>$file)
                                @if($loop->iteration == 1 or $loop->iteration == $half+1)
                                <div class="col-lg-6">
                                    @endif
                                        <div class="">                                     
                                                <input type="checkbox" id="file{{@$key}}"
                                                    class="common-checkbox form-control{{ @$errors->has('file') ? ' is-invalid' : '' }}"
                                                    name="lang_files[]" value="{{$file}}">
                                                <label for="file{{@$key}}">{{ ucwords(str_replace('_',' ',basename($file, '.php'))) }}</label>
                                           
                                        </div>
                                        @if($loop->iteration == $half or $loop->iteration == $count)
                                
                                </div>
                                @endif
                            @endforeach                            
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </section>
@endsection
