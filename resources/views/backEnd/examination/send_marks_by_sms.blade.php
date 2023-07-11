@extends('backEnd.master')
@section('title')
@lang('exam.send_marks_by_sms')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.send_marks_by_sms') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examinations')</a>
                <a href="#">@lang('exam.send_marks_by_sms')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="main-title">
                    <h3 class="mb-30">@lang('exam.send_marks_via_SMS')</h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
               
                <div class="white-box">
                     @if(userPermission('send_marks_by_sms'))

                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'send_marks_by_sms_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        @endif
                    <div class="row">
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('common.select_exam') *" value="">@lang('common.select_exam')*</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exams!=''?$exam->id:''}}">{{$exams!=''?$exam->title:''}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('exam') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}"  {{( old('class') == $class->id ? "selected":"")}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('receiver') ? ' is-invalid' : '' }}" name="receiver">
                                    <option data-display="@lang('exam.select_receiver') *" value="">@lang('exam.select_receiver') *</option>
                                    
                                    <option value="students"  {{( old('receiver') == 'students' ? "selected":"")}}>@lang('student.students')</option>
                                    @if(generalSetting()->with_guardian)
                                        <option value="parents"  {{( old('receiver') == 'parents' ? "selected":"")}}>@lang('student.parents')</option>
                                    @endif 
                                </select>
                                @if ($errors->has('receiver'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('receiver') }}
                                </span>
                                @endif
                            </div>
                 				@php 
                                  $tooltip = "";
                                  if(userPermission('send_marks_by_sms')){
                                        $tooltip = "";
                                    }else{
                                        $tooltip = "You have no permission to add";
                                    }
                                @endphp
                            <div class="col-lg-12 mt-40 text-center">
                               <button class="primary-btn fix-gr-bg" data-toggle="tooltip" title="{{$tooltip}}">
                                    <span class="ti-check"></span>
                                    @lang('exam.send_marks_via_SMS')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
