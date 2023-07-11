@php 

$setting  = generalSetting()
@endphp
@extends('backEnd.master')

@section('title')
@lang('system_settings.header_option')
@endsection

@section('mainContent')
    <style type="text/css">
        #selectStaffsDiv, .forStudentWrapper {
            display: none;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 26px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background: linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px linear-gradient(90deg, var(--gradient_1) 0%, #c738d8 51%, var(--gradient_1) 100%);
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        /* .buttons_div_one{
        border: 4px solid #FFFFFF;
        border-radius:12px;

        padding-top: 0px;
        padding-right: 5px;
        padding-bottom: 0px;
        margin-bottom: 4px;
        padding-left: 0px;
         } */
        .buttons_div{
        border-radius:12px
        }
    </style>
    @php
    $settings = $setting;
    @endphp
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('system_settings.header_option')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('system_settings.system_settings')</a>
                    <a href="#">@lang('system_settings.header_option')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6  buttons_div_one mb-30">
                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'admin-dashboard', 'method' => 'GET', 'enctype' => 'multipart/form-data']) }}
                    @else
                        @if(userPermission('update-website-url'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-website-url', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        @endif
                    @endif
                    <div class="white-box">
                           
                            <div class="row p-0">
                                <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-left">
                                        <span style="font-size: 17px; padding-right: 15px;">@lang('system_settings.website')  </span>
                                        @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo "> 
                                            @php
                                                if(@$settings->website_btn == 0){
                                                        $permission_id='status-change';
                                                }else{
                                                        $permission_id='status-disable';
                                                }
                                            @endphp
                                            @if(userPermission($permission_id))
                                             <label class="switch_toggle">
                                                <input type="checkbox"
                                                    class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                            </label>
                                            @endif
                                        </span>
                                        @else
                                        @php
                                            if(@$settings->website_btn == 0){
                                                    $permission_id='status-change';
                                            }else{
                                                    $permission_id='status-disable';
                                            }
                                        @endphp
                                            @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-website_btn" {{@$settings->website_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            <p> @lang('system_settings.custom_url')</p>
                            <div class="row mt-20">
                            
                                <div class="col-lg-8">
                                    <div class="d-flex align-items-center justify-content-center mb-20">

                                        @php
                                                if($settings->website_url==''){
                                                    $website_url=URL('home');
                                                }else{
                                                    $website_url=$settings->website_url;
                                                }
                                            @endphp
                                            <input type="text" class="primary_input_field" name="website_url" value="{{@$website_url}}">
                                            @if ($errors->has('website_url'))
                                        
                                            <p class="text-danger">{{ $errors->first('website_url') }}</p>
                                            @endif
                                    </div>
                                </div>
                            
                                <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-center mb-20">
                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Disabled For Demo "> 
                                        <button  style="pointer-events: none;" class="primary-btn fix-gr-bg" type="button" > @lang('common.update') </button>
                                        </span>
                                    @else
                                        @if(userPermission('update-website-url'))
                                        <button type="submit" class="primary-btn fix-gr-bg" id="search_promote">
                                            <span class=" pr-2"></span>@lang('common.update')</button>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                    </div>
                
                </div>
                <div class="col-lg-6  ">
                <div class="buttons_div">
                    <div class="white-box">
                        
                            <div class="row">
                            
                            
                                <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-left">
                                        
                                        <span style="font-size: 17px; padding-right: 15px;">@lang('common.dashboard')  </span>

                                    
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo "> 
                                        @php
                                            if(@$settings->website_btn == 0){
                                                    $permission_id='status-change';
                                            }else{
                                                    $permission_id='status-disable';
                                            }
                                        @endphp
                                        @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                        </span>

                                    
                                    @else
                                    @php
                                        if(@$settings->dashboard_btn == 0){
                                                $permission_id='dashboard-enable';
                                        }else{
                                                $permission_id='dashboard-disable';
                                        }
                                    @endphp
                                        @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch_dashboard_btn" {{@$settings->dashboard_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                    @endif
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-left">

                                        <span style="font-size: 17px; padding-right: 15px;">@lang('system_settings.style')  </span>

                                        
                                    
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo ">
                                        @php
                                            if(@$settings->website_btn == 0){
                                                $permission_id='status-change';
                                            }else{
                                                $permission_id='status-disable';
                                            }
                                        @endphp
                                        @if(userPermission($permission_id)) 
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                        </span>

                                    
                                    @else
                                    @php
                                        if(@$settings->style_btn == 0){
                                                $permission_id=473;
                                        }else{
                                                $permission_id=474;
                                        }
                                    @endphp
                                        @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-style_btn" {{@$settings->style_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="row mt-20">
                                <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-left">

                                        <span style="font-size: 17px; padding-right: 15px;">@lang('reports.report')   </span>

                                    
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo "> 
                                        @php
                                            if(@$settings->website_btn == 0){
                                                    $permission_id='status-change';
                                            }else{
                                                    $permission_id='status-disable';
                                            }
                                        @endphp
                                        @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                        </span>

                                    
                                    @else
                                        @php
                                            if(@$settings->report_btn == 0){
                                                    $permission_id='report-enable';
                                            }else{
                                                    $permission_id='report-disable';
                                            }
                                        @endphp
                                        @if(userPermission($permission_id))
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch_report_btn" {{@$settings->report_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif
                                    @endif
                                </div>
                                {{-- <div class="col-lg-4">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span style="font-size: 17px; padding-right: 15px;">@lang('system_settings.ltl_rtl')  </span>
                                    
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                        
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo ">
                                        @php
                                            if(@$settings->website_btn == 0){
                                                    $permission_id='status-change';
                                            }else{
                                                    $permission_id='status-disable';
                                            }
                                        @endphp
                                        @if(userPermission($permission_id)) 
                                         <label class="switch_toggle">
                                            <input type="checkbox"
                                                class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                        @endif

                                        </span>

                                    
                                    @else
                                    @php
                                        if(@$settings->ltl_rtl_btn == 0){
                                                $permission_id=475;
                                        }else{
                                                $permission_id=476;
                                        }
                                    @endphp
                                        @if(userPermission($permission_id))
                                             <label class="switch_toggle">
                                                <input type="checkbox"
                                                    class="switch_ltl_rtl_btn" {{@$settings->ltl_rtl_btn == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                        @endif
                                </div> --}}
                                    <div class="col-lg-4">
                                        <div class="d-flex align-items-center justify-content-left">
    
                                            <span style="font-size: 17px; padding-right: 15px;">@lang('system_settings.language')   </span>
    
                                        
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        @if(Illuminate\Support\Facades\Config::get('app.app_sync'))
                                            
                                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="No action For Demo ">
                                            @php
                                                if(@$settings->website_btn == 0){
                                                        $permission_id='status-change';
                                                }else{
                                                        $permission_id='status-disable';
                                                }
                                            @endphp
                                            @if(userPermission($permission_id)) 
                                             <label class="switch_toggle">
                                                <input type="checkbox"
                                                    class="switch-website_btn_demo" {{@$settings->website_btn == 0? '':'checked'}}>
                                                <span class="slider round"></span>
                                            </label>
                                            @endif
                                            </span>
                                        @else
                                         <label class="switch_toggle">
                                            @php
                                                if(@$settings->lang_btn == 0){
                                                        $permission_id='lang-enable';
                                                }else{
                                                        $permission_id='lang-disable';
                                                }
                                            @endphp
                                            @if(userPermission($permission_id))
                                            <input type="checkbox"
                                                class="switch_lang_btn" {{@$settings->lang_btn == 0? '':'checked'}}>
                                            <span class="slider round"></span>
                                        </label>
                                            @endif
                                        @endif
                                    </div>
                            </div>
                    </div>
                    </div>
                
                </div>
            </div>
        </div>
    </section>
@endsection
