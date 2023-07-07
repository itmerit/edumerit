@extends('backEnd.master')
@section('title')
@lang('exam.subject_mark_sheet')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('reports.subject_mark_sheet')</h1>
                <div class="bc-pages">
                    <a href="{{url('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examination')</a>
                    <a href="#">@lang('exam.subject_mark_sheet')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria')</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subjectMarkSheetSearch', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                                @if(moduleStatusCheck('University'))
                                    <div class="col-lg-12">
                                        <div class="row">
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                            ['required' => 
                                                ['USN', 'UD', 'UA', 'US', 'USL', 'USEC','USUB']
                                            ])
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-4 mt-30-md">
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="class_subject" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                            <option value="{{$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('class'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('class') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-lg-4 mt-30-md" id="select_class_subject_div">
                                        <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_class_subject" name="subject">
                                            <option data-display="@lang('common.select_subject') *" value="">@lang('common.select_subject') *</option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_subject_loader">
                                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                        </div>
                                        @if ($errors->has('subject'))
                                            <span class="text-danger invalid-select" role="alert">
                                                {{ $errors->first('subject') }}
                                            </span>
                                        @endif
                                    </div>
    
                                    <div class="col-lg-4 mt-30-md" id="m_select_subject_section_div">
                                        <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} m_select_subject_section" id="m_select_subject_section" name="section">
                                            <option data-display="@lang('common.select_section') " value=" ">@lang('common.select_section') </option>
                                        </select>
                                        <div class="pull-right loader loader_style" id="select_section_loader">
                                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                        </div>
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