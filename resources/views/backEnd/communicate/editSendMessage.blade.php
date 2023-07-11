@extends('backEnd.master')
@section('title') 
    @lang('common.edit_notice')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('communicate.edit_notice')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('communicate.communicate')</a>
                <a href="#">@lang('communicate.edit_notice')</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('communicate.edit_notice') </h3>
                </div>
            </div>
            <div class="offset-lg-6 col-lg-2 text-right col-md-6">
                <a href="{{route('notice-list')}}" class="primary-btn small fix-gr-bg">
                    @lang('communicate.notice_board')
                </a>
            </div>
        </div>
        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'update-notice-data', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
        <div class="row">
            <div class="col-lg-12">
                @if(session()->has('message-success'))
                <div class="alert alert-success">
                  {{ session()->get('message-success') }}
              </div>
              @elseif(session()->has('message-danger'))
              <div class="alert alert-danger">
                  {{ session()->get('message-danger') }}
              </div>
              @endif
              <div class="white-box">
                <div class="">
                    <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                    <input type="hidden" name="notice_id"  value="{{ @$noticeDataDetails->id}}">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="primary_input mb-30">
                                <input class="primary_input_field form-control{{ $errors->has('notice_title') ? ' is-invalid' : '' }}"
                                type="text" name="notice_title" autocomplete="off" value="{{isset($noticeDataDetails)? $noticeDataDetails->notice_title : ''}}">
                                <label class="primary_input_label" for="">@lang('common.title')<span class="text-danger"> *</span> </label>
                                
                                @if ($errors->has('notice_title'))
                                <span class="text-danger" >
                                    {{ $errors->first('notice_title') }}
                                </span>
                                @endif
                            </div>
                            <div class="primary_input mt-0">
                                <label class="primary_input_label" for="">@lang('communicate.notice') <span></span> </label>

                                <textarea class="primary_input_field form-control" cols="0" rows="5" name="notice_message" id="notice_message">{!! (isset($noticeDataDetails)) ? $noticeDataDetails->notice_message : '' !!}</textarea>

                            
                                

                            </div>
                                <div class="primary_input mt-40"> 
                                    <input type="checkbox" id="is_published" class="common-checkbox" value="1" @if(@$noticeDataDetails->is_published == 1) checked @endif name="is_published">
                                    <label for="is_published">@lang('communicate.is_published_web_site')</label> 
                                </div>
                            
                        </div>
                        <div class="col-lg-5">
                         <div class="no-gutters input-right-icon mb-30">
                            <div class="col">
                                <div class="primary_input">
                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('notice_date') ? ' is-invalid' : '' }}" id="notice_date" type="text" name="notice_date" value="{{(isset($noticeDataDetails)) ? date('m/d/Y', strtotime($noticeDataDetails->notice_date)) : ' ' }}">
                                    <label class="primary_input_label" for="">@lang('communicate.notice_date')<span class="text-danger"> *</span> </label>
                                    
                                    @if ($errors->has('notice_date'))
                                    <span class="text-danger" >
                                        {{ $errors->first('notice_date') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="submission_date_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="no-gutters input-right-icon">
                            <div class="col">
                                <div class="primary_input">
                                    <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('publish_on') ? ' is-invalid' : '' }}" id="publish_on" type="text"
                                    name="publish_on" value="{{(isset($noticeDataDetails)) ? date('m/d/Y', strtotime($noticeDataDetails->publish_on)) : ' ' }}">
                                
                                    <label class="primary_input_label" for="">@lang('communicate.publish_on') <span class="text-danger"> *</span> </label>
                                    
                                    @if ($errors->has('publish_on'))
                                    <span class="text-danger" >
                                        {{ $errors->first('publish_on') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="" type="button">
                                    <i class="ti-calendar" id="submission_date_icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-50">
                            <label class="primary_input_label" for="">@lang('communicate.message_to') </label><br>
                        @if(isset($noticeDataDetails))
                             @php 
                             $inform_to = explode(',' ,$noticeDataDetails->inform_to);
                             @endphp
                        @endif                            
                               @foreach($roles as $role)
                               <div class="">

                                 <input type="checkbox" id="role{{$role->id}}" class="common-checkbox" name="role[]" value="{{$role->id}}" 
                                    
                                    @foreach($inform_to as $value)
                                    @if(@$role->id == @$value)
                                    checked
                                    @endif
                                    @endforeach
                                    >
                                    <label for="role{{ @$role->id }}"> {{ @$role->name }}</label>
                                    
                                </div>
                                @endforeach
                                        @if($errors->has('role'))
                                        <span class="text-danger" >
                                            {{ $errors->first('role') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-40">
                            <div class="col-lg-12 text-center">
                                <button class="primary-btn fix-gr-bg">
                                    <span class="ti-check"></span>
                                    @lang('communicate.update_content')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</section>

@endsection
@include('backEnd.partials.date_picker_css_js')
@push('script')
<script>
    CKEDITOR.replace( 'notice_message' );
</script>
@endpush