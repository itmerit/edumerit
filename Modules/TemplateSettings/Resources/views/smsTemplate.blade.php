@extends('backEnd.master')
@section('title') 
@lang('communicate.sms_template')
@endsection

@section('mainContent')
@push('css')
    <style>
        .custom_nav li a.active {
            background-color: #fbfbfb;
        }
    </style>
@endpush
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

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">    
        <div class="row">
            <div class="col-lg-4">
                <div class="white-box">
                    <div class="add-visitor">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav custom_nav flex-column" id="myTab" role="tablist">
                                    @foreach ($smsTemplates as $key=>$smsTemplate)
                                        @if (!$smsTemplate->module || moduleStatusCheck($smsTemplate->module)==TRUE)
                                            <li class="nav-item">
                                                <a class="nav-link {{$key==0 ? "active" : ""}}" id="{{$smsTemplate->purpose}}-tab" data-toggle="tab" href="#{{$smsTemplate->purpose}}" role="tab" aria-controls="{{$smsTemplate->purpose}}" aria-selected="{{$key==0 ? "true" : "false"}}">
                                                    @lang('communicate.'.$smsTemplate->purpose)
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
                                @foreach ($smsTemplates as $key=>$smsTemplate)
                                    @if (!$smsTemplate->module||moduleStatusCheck($smsTemplate->module)== TRUE)
                                        <div class="tab-pane fade white_box_30px {{$key==0 ? "active show" : ""}}" id="{{$smsTemplate->purpose}}" role="tabpanel">
                                            {{ Form::open(['class' => 'form-horizontal', 'route' => 'templatesettings.sms-template-update', 'method' => 'POST']) }}
                                                <div class="row">
                                                    <div class="col-lg-10 mb-20">
                                                        <label> <strong>@lang('communicate.variables') :</strong>  </label>
                                                        <span class="text-primary">
                                                            {{$smsTemplate->variable}}
                                                        </span>
                                                    </div>
                                                    <div class="col-lg-2 mb-20">
                                                        <div class="primary_input">
                                                            <input type="checkbox" id="email_enable{{$smsTemplate->id}}" class="common-checkbox exam-checkbox" name="status" value="1" {{isset($smsTemplate)? ($smsTemplate->status == 1? 'checked':''):''}}>
                                                            <label for="email_enable{{$smsTemplate->id}}">@lang('communicate.enable')</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <input type="hidden" name="id" value="{{$smsTemplate->id}}">
                                                        <input type="hidden" name="purpose" value="{{$smsTemplate->purpose}}">
                                        
                                                        <div class="primary_input mt-20">
                                                            <label class="primary_input_label" for="">@lang('communicate.body')</label>
                                                                <textarea class="primary_input_field form-control{{$errors->has('body') ? ' is-invalid' : '' }}" cols="0" rows="4" name="body" maxlength="500">{{isset($smsTemplate)? $smsTemplate->body: old($smsTemplate->body)}}</textarea>
                                                            
                                                            @if($errors->has('body'))
                                                                <span class="error text-danger">{{ $errors->first('body')}}</strong></span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                        
                                                <div class="row mt-40">
                                                    <div class="col-lg-12 text-center">
                                                        <button class="primary-btn fix-gr-bg" title="@lang('communicate.update')">
                                                            <span class="ti-check"></span>
                                                            @lang('communicate.update')
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
    <script>
        // back to top
        $(".custom_nav li").on("click", function () {
            $("body,html").animate({scrollTop: 0,},1000);
        });
    </script>
@endpush
