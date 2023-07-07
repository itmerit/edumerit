@extends('backEnd.master')
@section('title')
    @lang('communicate.email_template')
@endsection
@section('mainContent')
@push('css')
<link rel="stylesheet" href="{{ asset('public/backEnd/vendors/editor/summernote-bs4.css') }}">
    <style>
        .custom_nav li a.active {
            background-color: #fbfbfb;
        }
        .input-right-icon button i {
            position: relative;
            top: 0px !important;
        }
        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
@endpush
<section class="sms-breadcrumb mb-40 white-box up_breadcrumb">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.email_template')</h1>
            <div class="bc-pages">
                <a href="{{url('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.email_template')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">       
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box">
                    <div class="add-visitor">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav custom_nav flex-column" id="myTab" role="tablist">
                                    @foreach ($emailTempletes as $key=>$emailTemplete)
                                        @if (!$emailTemplete->module || moduleStatusCheck($emailTemplete->module)==TRUE)

                                            <li class="nav-item">
                                                <a class="nav-link {{$key==0 ? "active" : ""}}" id="{{$emailTemplete->purpose}}-tab" data-toggle="tab" href="#{{$emailTemplete->purpose}}" role="tab" aria-controls="{{$emailTemplete->purpose}}" aria-selected="{{$key==0 ? "true" : "false"}}">
                                                    @lang('communicate.'.$emailTemplete->purpose)
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="white-box">
                            <div class="tab-content" id="myTabContent">
                                @foreach ($emailTempletes as $key=>$emailTemplete)
                                    @if (!$emailTemplete->module || moduleStatusCheck($emailTemplete->module)==TRUE)
                                        <div class="tab-pane fade white_box_30px {{$key==0 ? "active show" : ""}}" id="{{$emailTemplete->purpose}}" role="tabpanel">
                                            {{ Form::open(['class' => 'form-horizontal', 'route'=> 'templatesettings.email-template', 'method' => 'POST']) }}
                                                <div class="row">
                                                    <div class="col-lg-10 mb-20">
                                                        <label> <strong>@lang('communicate.variables') :</strong>  </label>
                                                        <span class="text-primary">
                                                            {{$emailTemplete->variable}}
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-2 mb-20">
                                                        <div class="primary_input">
                                                            <input type="checkbox" id="email_enable{{$emailTemplete->id}}" class="common-checkbox exam-checkbox" name="status" value="1" {{isset($emailTemplete)? ($emailTemplete->status == 1? 'checked':''):''}}>
                                                            <label for="email_enable{{$emailTemplete->id}}">@lang('communicate.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label class="primary_input_label" for="">@lang('common.subject') <span class="text-danger"> *</span></label>
                                                        <input type="hidden" name="id" value="{{$emailTemplete->id}}">
                                                        <input type="hidden" name="purpose" value="{{$emailTemplete->purpose}}">
                                                        @if($emailTemplete->subject)
                                                            <div class="primary_input">
                                                                <input class="primary_input_field form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" type="text" name="subject" value="{{isset($emailTemplete)? $emailTemplete->subject: old($emailTemplete->subject)}}">
                                                                
                                                                
                                                                @if ($errors->has('subject'))
                                                                    <span class="text-danger" >
                                                                        {{ $errors->first('subject') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        <div class="primary_input mt-20">
                                                            <label class="primary_input_label" for="">@lang('communicate.body')</label>
                                                                <textarea class="primary_input_field summer_note form-control{{$errors->has('body') ? ' is-invalid' : '' }}" 
                                                                    cols="0" rows="4" name="body" maxlength="500">
                                                                    {{isset($emailTemplete)? $emailTemplete->body : old($emailTemplete->body)}}
                                                                </textarea>
                                                            
                                                            @if($errors->has('body'))
                                                                <span class="error text-danger">{{ $errors->first('body')}}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                                <div class="row mt-40">
                                                    <div class="col-lg-12 text-center">
                                                        <button class="primary-btn fix-gr-bg" title="@lang('common.update')">
                                                            <span class="ti-check"></span>
                                                            @lang('common.update')
                                                        </button>
                                                    </div>
                                                </div>
                                            {{ Form::close() }}
                                        </div>
                                    @endif
                                @endforeach
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script src="{{asset('public/backEnd/')}}/vendors/editor/summernote-bs4.js"></script>
@endpush