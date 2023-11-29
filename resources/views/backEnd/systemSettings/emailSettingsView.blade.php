
@extends('backEnd.master')
@section('title')
    @lang('system_settings.email_settings')
@endsection
@section('mainContent')
<style type="text/css">
    .smtp_wrapper{
        display: none;
    }
    .smtp_wrapper_block{
        display: block;
    }
</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('system_settings.email_settings') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('system_settings.system_settings')</a>
                <a href="#">@lang('system_settings.email_settings') </a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="main-title">
                    <h3 class="mb-15"> @lang('system_settings.select_email_settings')</h3>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 text-right">
                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'send-test-mail', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
                    @csrf
                    <button class="primary-btn small fix-gr-bg" type="submit"> <i class="ti-email"></i> {{__('Send Test Mail')}} </button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</section>

<section class="mb-40 student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <!-- Start Sms Details -->
            <div class="col-lg-12">
                <ul class="nav nav-tabs tab_column" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link @if($active_mail_driver == 'smtp') active @endif " href="#smtp" role="tab" data-toggle="tab">Smtp @lang('system_settings.settings')</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link @if($active_mail_driver == 'php') active @endif" href="#php" role="tab" data-toggle="tab">Php @lang('system_settings.settings')</a>
                    </li>
                    </li>  
                </ul>
                <div class="tab-content">
                    <!-- Start Exam Tab -->
                    <div role="tabpanel" class="tab-pane fade @if ($active_mail_driver == 'smtp') show active @endif" id="smtp">
                        @if(userPermission('update-email-settings-data'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'update-email-settings-data', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <input type="hidden" name="email_settings_url" id="email_settings_url" value="update-email-settings-data">
                                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                                    <input type="hidden" name="engine_type" id="engine_type" value="smtp">
                                    <div class="row justify-content-center mb-15">
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.from_name')<span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                                type="text" name="from_name" id="from_name" autocomplete="off" value="{{isset($editData)? $editData->from_name : ''}}">
                                                
                                                
                                                @if ($errors->has('from_name'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('from_name') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.from_mail')<span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('from_email') ? ' is-invalid' : '' }}"
                                                type="text" name="from_email" id="from_email" autocomplete="off" value="{{isset($editData)? $editData->from_email : ''}}">
                                                
                                                
                                                @if ($errors->has('from_email'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('from_email') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_driver') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_driver') ? ' is-invalid' : '' }}"
                                                type="text" name="mail_driver" id="mail_driver" autocomplete="off" value="{{isset($editData)? $editData->mail_driver : ''}}">
                                               
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_host') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_host') ? ' is-invalid' : '' }}"
                                                type="text" name="mail_host" id="mail_host" autocomplete="off" value="{{isset($editData)? $editData->mail_host : ''}}">
                                             
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_port') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_port') ? ' is-invalid' : '' }}"
                                                type="text" name="mail_port" id="mail_port" autocomplete="off" value="{{isset($editData)? $editData->mail_port : ''}}">
                                              
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_username') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_username') ? ' is-invalid' : '' }}"
                                                type="text" name="mail_username" id="mail_username" autocomplete="off" value="{{isset($editData)? $editData->mail_username : ''}}">
                                                
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row justify-content-center">
                                        <div class="col-lg-6 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_password') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_password') ? ' is-invalid' : '' }}"
                                                type="password" name="mail_password" id="mail_password" autocomplete="off" value="{{isset($editData)? $editData->mail_password : ''}}">
                                                
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('system_settings.mail_encryption') <span class="text-danger"> *</span> </label>
                                                <input class="primary_input_field form-control{{ $errors->has('mail_encryption') ? ' is-invalid' : '' }}"
                                                type="text" name="mail_encryption" id="mail_encryption" autocomplete="off" value="{{isset($editData)? $editData->mail_encryption : ''}}">
                                                
                                                
                                                <span class="modal_input_validation red_alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 mb-15">
                                            <div class="primary_input">
                                                <label class="primary_input_label" for="">@lang('common.status') <span class="text-danger"> *</span> </label>
                                                <select class="primary_select form-control {{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="active_status" name="active_status">
                                                    <option data-display="@lang('system_settings.select_status') *" value="">@lang('system_settings.select_status') *</option>
                                                    <option @if($active_mail_driver == "smtp") selected @endif value="1">@lang('system_settings.enable')</option>
                                                    <option value="0" disabled>@lang('common.disable')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-30">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg">
                                                <span class="ti-check"></span>
                                                @lang('system_settings.update')
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    <div role="tabpanel" class="tab-pane fade @if ($active_mail_driver == 'php') show active @endif" id="php">
                        @if(userPermission('update-email-settings-data'))
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'method' => 'POST', 'url' => 'update-email-settings-data', 'id' => 'email_settings1', 'enctype' => 'multipart/form-data']) }}
                        @endif
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="white-box">
                                        <input type="hidden" name="email_settings_url" id="email_settings_url" value="update-email-settings-data">
                                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                                        <input type="hidden" name="engine_type" id="engine_type" value="php">
                                        <div class="row justify-content-center mb-15">
                                            <div class="col-lg-4">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('system_settings.from_name')<span class="text-danger"> *</span> </label>
                                                    <input class="primary_input_field form-control{{ $errors->has('from_name') ? ' is-invalid' : '' }}"
                                                    type="text" name="from_name" id="from_name" autocomplete="off" value="{{isset($editDataPhp)? $editDataPhp->from_name : ''}}">
                                                    
                                                    
                                                    @if ($errors->has('from_name'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('from_name') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('system_settings.from_mail')<span class="text-danger"> *</span> </label>
                                                    <input class="primary_input_field form-control{{ $errors->has('from_email') ? ' is-invalid' : '' }}"
                                                    type="text" name="from_email" id="from_email" autocomplete="off" value="{{isset($editDataPhp)? $editDataPhp->from_email : ''}}">
                                                    
                                                    
                                                    @if ($errors->has('from_email'))
                                                    <span class="text-danger" >
                                                        {{ $errors->first('from_email') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-15">
                                                <div class="primary_input">
                                                    <label class="primary_input_label" for="">@lang('common.status')<span class="text-danger"> *</span> </label>
                                                    <select class="primary_select form-control {{ $errors->has('active_status') ? ' is-invalid' : '' }}" id="active_status" name="active_status">
                                                        <option data-display="@lang('system_settings.select_status') *" value="">@lang('system_settings.select_status') *</option>
                                                        <option @if($active_mail_driver == "php") selected @endif value="1">@lang('system_settings.enable')</option>
                                                        <option value="0" disabled>@lang('common.disable')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-30">
                                            <div class="col-lg-12 text-center">
                                                <button class="primary-btn fix-gr-bg">
                                                    <span class="ti-check"></span>
                                                    @lang('system_settings.update')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>     
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

