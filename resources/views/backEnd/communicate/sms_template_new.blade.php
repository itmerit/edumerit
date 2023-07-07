@extends('backEnd.master')
@section('title') 
@lang('communicate.sms')/@lang('common.email_template')
@endsection

@section('mainContent')

<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.sms_template')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.sms_template')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="main-title">
                            <h3 class="mb-30">
                                    @lang('common.update')
                                @lang('communicate.sms_template')
                            </h3>
                        </div>
                          
                            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'sms-template-new', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        
                        <div class="white-box">
                            <div class="add-visitor">
                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">
                                        <span class="text-primary">[student_name] [parent_name] [due_amount] [fees_name] [due_date] [school_name]</span>
                                    </div>
                                    <div class="col-lg-8">
                                        
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.dues_fees_message') <span></span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('dues_fees_message') ? ' is-invalid' : '' }}" cols="0" rows="2" name="dues_fees_message_sms" maxlength="500">{{isset($template)? $template->dues_fees_message_sms: old('dues_fees_message_sms')}}</textarea>
                                            
                                            @if($errors->has('dues_fees_message_sms'))
                                                <span class="error text-danger">{{ $errors->first('dues_fees_message_sms') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class=" radio-btn-flex ml-20">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="dues_fees_message_sms_status" id="dues_fees_message_smsF" value="1" class="common-radio relationButton"  {{@$template->dues_fees_message_sms_status == 1? 'checked': ''}}>
                                                                <label for="dues_fees_message_smsF">@lang('communicate.enable')</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="dues_fees_message_sms_status" id="dues_fees_message_smsM" value="2" class="common-radio relationButton"  {{@$template->dues_fees_message_sms_status == 2? 'checked': ''}}>
                                                                <label for="dues_fees_message_smsM">@lang('common.disable')</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-25">
                                    <div class="col-lg-12 mb-20">
                                        <span class="text-primary">[student_name] [parent_name] [number_of_subject] [subject_list] [due_date]</span>
                                    </div>
                                    <div class="col-lg-8">
                                        
                                        <div class="primary_input">
                                            <label class="primary_input_label" for="">@lang('communicate.student_absent_notification_sms') <span></span></label>
                                            <textarea class="primary_input_field form-control{{ $errors->has('dues_fees_message') ? ' is-invalid' : '' }}" cols="0" rows="2" name="student_absent_notification_sms" maxlength="500">{{isset($template)? $template->student_absent_notification_sms: old('dues_fees_message_sms')}}</textarea>
                                            
                                            @if($errors->has('student_absent_notification_sms'))
                                                <span class="error text-danger">{{ $errors->first('student_absent_notification_sms') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class=" radio-btn-flex ml-20">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="student_absent_notification_sms_status" id="student_absent_notification_sms_smsF" value="1" class="common-radio relationButton"  {{@$template->student_absent_notification_sms_status == 1? 'checked': ''}}>
                                                                <label for="student_absent_notification_sms_smsF">@lang('communicate.enable')</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="">
                                                                <input type="radio" name="student_absent_notification_sms_status" id="student_absent_notification_sms_smsM" value="2" class="common-radio relationButton"  {{@$template->student_absent_notification_sms_status == 2? 'checked': ''}}>
                                                                <label for="student_absent_notification_sms_smsM">@lang('common.disable')</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
	                           @php 
                                  $tooltip = "";
                                  if(userPermission("sms-template-new-store")){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                                
                             
                                    
                                    <div class="row mt-40">
                                        <div class="col-lg-12 text-center">
                                            <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                                <span class="ti-check"></span>
                                                
                                                    @lang('common.update')
                                            </button>
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
