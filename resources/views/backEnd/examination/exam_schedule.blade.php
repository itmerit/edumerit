@extends('backEnd.master')
@section('title')
@lang('exam.exam_schedule')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('exam.exam_schedule')</h1>
                <div class="bc-pages">
                    <a href="{{url('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examinations')</a>
                    <a href="#">@lang('exam.exam_schedule')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
                @if(userPermission('exam_schedule_create'))
                    <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg">
                        <a href="{{route('exam_schedule_create')}}" class="primary-btn small fix-gr-bg">
                            <span class="ti-plus pr-2"></span>
                            @lang('exam.add_exam_schedule')
                        </a>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_report_search', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                            @if(moduleStatusCheck('University'))
                                <div class="col-lg-12">
                                    <div class="row">
                                        @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                        ['required' => 
                                            ['USN', 'UD', 'UA', 'US', 'USL','USEC'],'hide'=> ['USUB']
                                        ])

                                        <div class="col-lg-3 mt-25" id="select_exam_typ_subject_div">
                                            {{ Form::select('exam_type',[""=>__('exam.select_exam').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('exam_type') ? ' is-invalid' : ''), 'id'=>'select_exam_typ_subject']) }}
                                            
                                            <div class="pull-right loader loader_style" id="select_exam_type_loader">
                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                            </div>
                                            @if ($errors->has('exam_type'))
                                                <span class="text-danger custom-error-message" role="alert">
                                                    {{ @$errors->first('exam_type') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.exam') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('exam_type') ? ' is-invalid' : '' }}"
                                            name="exam_type">
                                        <option data-display="Select Exam *"
                                                value="">@lang('common.select_exam') *
                                        </option>
                                        @foreach($exam_types as $exam_type)
                                            <option value="{{@$exam_type->id}}" {{isset($exam_type_id) ? ($exam_type_id == $exam_type->id? 'selected':''):''}}>{{@$exam_type->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam_type'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('exam_type') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.class') }}
                                            <span class="text-danger"> *</span>
                                    </label>
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                            id="select_class" name="class">
                                        <option data-display="@lang('common.select_class') *"
                                                value="">@lang('common.select_class') *
                                        </option>
                                        @foreach($classes as $class)
                                            <option value="{{@$class->id}}" {{isset($class_id) ? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('class') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-4 mt-30-md" id="select_section_div">
                                    <label class="primary_input_label" for="">
                                        {{ __('common.section') }}
                                            <span class="text-danger"> </span>
                                    </label>
                                    <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}"
                                            id="select_section" name="section">
                                        <option data-display="@lang('common.select_section') "
                                                value="">@lang('common.select_section') 
                                        </option>
                                    </select>
                                    @if ($errors->has('section'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('section') }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search pr-2"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(moduleStatusCheck('University'))
        @includeIf('university::exam.exam_routine_view')
    @endif
@endsection