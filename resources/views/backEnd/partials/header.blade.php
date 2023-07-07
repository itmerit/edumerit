
<!DOCTYPE html>
@php
    $generalSetting = generalSetting();
@endphp
<html lang="{{ app()->getLocale() }}" @if(userRtlLtl()==1) dir="rtl" class="rtl" @endif>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    @if( ! is_null(schoolConfig() ))
        <link rel="icon" href="{{asset(schoolConfig()->favicon)}}" type="image/png"/>
    @else
        <link rel="icon" href="{{asset('public/uploads/settings/favicon.png')}}" type="image/png"/>
    @endif
    <title>{{@schoolConfig()->school_name ? @schoolConfig()->school_name : 'Infix Edu ERP'}} |
        @yield('title')
    </title>

    <meta name="_token" content="{!! csrf_token() !!}"/>
    @if(userRtlLtl() ==1)
        <link rel="stylesheet" href="{{ asset('public/backEnd/css/rtl/bootstrap.rtl.min.css') }}" />
    @else
        <link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap.min.css') }}" />
    @endif
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/jquery-ui.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/bootstrap-datepicker.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/font_awesome/css/all.min.css') }}" />
<link rel="stylesheet" href="{{asset('public/backEnd/vendors/themefy_icon/themify-icons.css')}}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/flaticon.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/fnt.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/nice-select.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/css/toastr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/style.css')}}" />
@if(\Request::route()->getName() == "fees.fees-invoice-settings")
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/select2/css/select2.min.css')}}" />
@endif 
@include('backEnd.partials.css')
@if(userRtlLtl() ==1)
    <style>
        .demo_addons{
            float: left!important;
            margin-left: 30px!important;
        }    
    </style>
@endif
    <x-root-css/>
    @stack('css')
    <script src="{{asset('public/backEnd/')}}/vendors/js/jquery-3.2.1.min.js"></script>
    <script>
        window.Laravel = {
            "baseUrl": '{{ url('/') }}' + '/',
            "current_path_without_domain": '{{request()->path()}}'
        }

        window._locale = '{{ app()->getLocale() }}';
        window._rtl = {{ userRtlLtl()==1 ? "true" : "false" }};
        window._translations = {!! cache('translations') !!};
    </script>
</head>


@php
if (empty(color_theme())) {
 $css = "background: url('".asset('/public/backEnd/img/body-bg.jpg')."')  no-repeat center; background-size: cover ; ";
} else {
 if (!empty(color_theme()->background_type == 'image')) {
     $css = "background: url('" . asset(color_theme()->background_image) . "')  no-repeat center; background-size: cover; background-attachment: fixed; background-position: top; ";
 } else {
     $css = "background:" . color_theme()->background_color;
 }
}

@endphp
@php
    if(session()->has('homework_zip_file')){
        $file_path='public/uploads/homeworkcontent/'.session()->get('homework_zip_file');
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
@endphp

<body class="admin"  style="{!! $css !!} ">
          @include('backEnd.preloader')
          @php
            $chat_method = app('general_settings')->get('chatting_method');
        @endphp
        <input type="hidden" id="chat_settings" value="{{ $chat_method }}">
        @if($chat_method == 'pusher')
            <input type="hidden" id="pusher_app_key" value="{{ app('general_settings')->get('pusher_app_key') }}">
            <input type="hidden" id="pusher_app_cluster" value="{{ app('general_settings')->get('pusher_app_cluster') }}">
        @endif
        <input type="hidden" id="demoMode" value="{{ config('app.app_sync') }}">
    @php
        if (file_exists($generalSetting->logo)) {
            $tt = file_get_contents(base_path($generalSetting->logo));
        } else {
            $tt = file_get_contents(base_path('public/uploads/settings/logo.png'));
        }
    @endphp
<input type="text" hidden value="{{ base64_encode($tt) }}" id="logo_img">
<input type="text" hidden value="{{ $generalSetting->school_name }}" id="logo_title">

<div class="main-wrapper" style="min-height: 600px">
    <input type="hidden" id="nodata" value="@lang('common.no_data_available_in_table')">
    <!-- Sidebar  -->
@if(isSubscriptionEnabled())
    @if(\Modules\Saas\Entities\SmPackagePlan::isSubscriptionAutheticate())
        @include('backEnd.partials.sidebar')
    @else
        @include('saas::menu.SaasSubscriptionSchool_trial')
    @endif
@else
    @include('backEnd.partials.sidebar')
@endif
<!-- Page Content  -->
    <div id="main-content">
        <input type="hidden" name="url" id="url" value="{{url('/')}}">
@include('backEnd.partials.menu')