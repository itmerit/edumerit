@extends('backEnd.master')
@section('title')
@lang('exam.exam_schedule_create')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_schedule_create') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="{{route('exam_schedule')}}">@lang('exam.exam_schedule')</a>
                <a href="{{route('exam_schedule_create')}}">@lang('exam.exam_schedule_create')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_create', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                                @if(moduleStatusCheck('University'))
                                    <div class="col-lg-12">
                                        <div class="row">
                                            @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                            ['required' => 
                                                ['USN', 'UD', 'UA', 'US', 'USL', 'USEC'],'hide'=> ['USUB']
                                            ])

                                            <div class="col-lg-3 mt-25" id="select_exam_typ_subject_div">
                                                {{ Form::select('exam_type',["" =>__('exam.select_exam').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('exam_type') ? ' is-invalid' : ''), 'id'=>'select_exam_typ_subject']) }}
                                                
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
                                        <select class="primary_select form-control{{ $errors->has('exam_type') ? ' is-invalid' : '' }}" name="exam_type">
                                            <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *</option>
                                            @foreach($exam_types as $exam)
                                                <option value="{{@$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{@$exam->title}}</option>
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
                                        <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                            <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                            @foreach($classes as $class)
                                            <option value="{{@$class->id}}" {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{@$class->class_name}}</option>
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
                                        <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                            <option data-display="@lang('common.select_section') " value="">@lang('common.select_section') </option>
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
        @includeIf('university::exam.exam_routine')
    @else
        @if(isset($assign_subjects))
        <section class="mt-20">
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('exam.exam_schedule')</h3>
                        </div>
                    </div>
                </div>

            {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_schedule_store', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'exam_schedule_store']) }} 
                @if(!moduleStatusCheck('University'))
                    <input type="hidden" name="class_id" id="class_id" value="{{ @$class_id}}">
                    <input type="hidden" name="section_id" id="section_id" value="{{ @$section_id}}">
                @endif
                <input type="hidden" name="exam_id" id="exam_id" value="{{ @$exam_id}}"> 

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="10%">@lang('exam.subject')</th>
                                    <th width="10%">@lang('common.class_Sec')</th>
                                    @foreach($exam_periods as $exam_period)
                                        <th>{{ @$exam_period->period}}<br>{{date('h:i A', strtotime(@$exam_period->start_time)).'-'.date('h:i A', strtotime(@$exam_period->end_time))}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $section_id_all = $section_id;
                                @endphp
                                @foreach($assign_subjects as $assign_subject)
                                    <tr>
                                        <td>{{@$assign_subject->subject !=""?@$assign_subject->subject->subject_name:""}}</td>
                                        <td>{{@$assign_subject->class !=""? @$assign_subject->class->class_name:""}}({{@$assign_subject->section !=""?@$assign_subject->section->section_name:""}})</td>
                                            @foreach($exam_periods as $exam_period)
                                                @php
                                                    $assigned_routine = App\SmExamSchedule::assignedRoutine($class_id, $assign_subject->section_id, $exam_id, $assign_subject->subject_id, $exam_period->id);
                                                @endphp
                                            <td>
                                                @if(@$assigned_routine == "")
                                                    @if(@$assigned_routine_subject == "")
                                                        @if(userPermission('add-exam-routine-modal'))
                                                        <div class="col-lg-6">
                                                            <a href="{{route('add-exam-routine-modal', [$assign_subject->subject_id, $exam_period->id, $class_id, $assign_subject->section_id, $exam_id,$section_id_all])}}" class="primary-btn small tr-bg icon-only mr-10 modalLink" data-modal-size="modal-md" title="@lang('exam.create_exam_routine')">
                                                                <span class="ti-plus" id="addClassRoutine"></span>
                                                            </a>
                                                        </div>
                                                        @endif
                                                    @endif
                                                @else
                                                    <div class="col-lg-6">
                                                        <span class="">
                                                            {{@$assigned_routine->classRoom !=""?@$assigned_routine->classRoom->room_no:""}}</span>
                                                            <br>
                                                        <span class="">                                           
                                                            {{@$assigned_routine->date != ""? dateConvert($assigned_routine->date):''}}
                                                        </span>
                                                        </br>
                                                        <a href="{{route('edit-exam-routine-modal', [$assign_subject->subject_id, $exam_period->id, $class_id, $assign_subject->section_id, $exam_id, $assigned_routine->id,$section_id_all])}}" class="modalLink" data-modal-size="modal-md" title="@lang('common.edit_exam_routine')">
                                                            <span class="ti-pencil-alt" id="addClassRoutine"></span>
                                                        </a>
                                                        <a href="{{route('delete-exam-routine-modal', [$assigned_routine->id,$section_id_all])}}" class="modalLink" data-modal-size="modal-md" title="@lang('common.delete_exam_routine')">
                                                            <span class="ti-trash" id="addClassRoutine"></span>
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            {{ Form::close() }}    
            </div>
        </section>
        @endif
    @endif
@endsection