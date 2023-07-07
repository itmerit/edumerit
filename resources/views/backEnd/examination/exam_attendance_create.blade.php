@extends('backEnd.master')
@section('title')
@lang('exam.exam_attendance_create')
@endsection
@section('mainContent')
@push('css')
    <style>
        .primary-btn.mr-40.fix-gr-bg.nowrap.submit {
            position: relative;
            left: -85px;
        }
    </style>
@endpush
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.exam_attendance')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="{{route('exam_attendance')}}">@lang('exam.exam_attendance')</a>
                <a href="{{route('exam_attendance_create')}}">@lang('exam.exam_attendance_create')</a>
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
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'exam_attendance_create', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            @if(moduleStatusCheck('University'))
                                <div class="col-lg-12">
                                    <div class="row">
                                        @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                        ['required' =>
                                            ['USN', 'UD', 'UA', 'US', 'USL', 'USEC'],'hide'=> ['USUB']
                                        ])

                                        <div class="col-lg-3 mt-30" id="select_exam_typ_subject_div">
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

                                        <div class="col-lg-3 mt-30" id="select_un_exam_type_subject_div">
                                            {{ Form::select('subject_id',[""=>__('exam.select_subject').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('subject_id') ? ' is-invalid' : ''), 'id'=>'select_un_exam_type_subject']) }}
                                            
                                            <div class="pull-right loader loader_style" id="select_exam_subject_loader">
                                                <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                            </div>
                                            @if ($errors->has('subject_id'))
                                                <span class="text-danger custom-error-message" role="alert">
                                                    {{ @$errors->first('subject_id') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                        <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *</option>
                                        @foreach($exams as $exam)
                                            <option value="{{ @$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{ @$exam->title}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('exam'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('exam') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="col-lg-3 mt-30-md">
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

                                <div class="col-lg-3 mt-30-md" id="select_class_subject_div">
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

                                <div class="col-lg-3 mt-30-md" id="m_select_subject_section_div">
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

        @if(isset($students))
        {{ Form::open(['class' => 'form-horizontal', 'route' => 'exam-attendance-store', 'method' => 'POST']) }}
            @if(moduleStatusCheck('University'))
                <input type="hidden" name="un_session_id"  value="{{ @$un_session->id}}">
                <input type="hidden" name="un_faculty_id"  value="{{ @$un_faculty->id}}">
                <input type="hidden" name="un_department_id"  value="{{ @$un_department->id}}">
                <input type="hidden" name="un_academic_id"  value="{{ @$un_academic->id}}">
                <input type="hidden" name="un_semester_id"  value="{{ @$un_semester->id}}">
                <input type="hidden" name="un_semester_label_id"  value="{{ @$un_semester_label->id}}">
                <input type="hidden" name="un_section_id"  value="{{ @$un_section->id}}">
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 no-gutters mb-30">
                                <div class="main-title">
                                    <h3>@lang('exam.exam_attendance') | <strong>@lang('exam.subject')</strong>: {{$subjectName->subject_name}}</h3>
                                    @includeIf('university::exam._university_info')
                                </div>
                            </div>
                        </div>
                        <input class="examId" type="hidden" name="exam_id" value="{{@$exam_id}}">
                        <input class="subjectId" type="hidden" name="un_subject_id" value="{{@$subject_id}}">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <table class="table school-table-style shadow-none p-0" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="25%">@lang('student.admission_no')</th>
                                                <th width="25%">@lang('student.student_name')</th>
                                                <th width="25%">@lang('student.roll_number')</th>
                                                <th width="25%">@lang('exam.attendance')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            @if(count($exam_attendance_childs) == 0)
                                                @foreach($students as $record)
                                                    <tr>
                                                        <td>{{@$record->studentDetail->admission_no}}</td>
                                                        <td>{{@$record->studentDetail->full_name}}</td>
                                                        <td>{{@$record->roll_no}}</td>
                                                        <td>
                                                            <div class="d-flex radio-btn-flex">
                                                                <div class="mr-20">
                                                                    <input type="hidden" name="attendance[{{@$record->student_record_id}}]" value="{{@$record->student_record_id}}">
                                                                    <input type="hidden" name="attendance[{{@$record->student_record_id}}][student]" value="{{@$record->student_id}}">
                                                                    <input type="radio" name="attendance[{{@$record->student_record_id}}][attendance_type]" id="attendanceP{{@$record->student_record_id}}" value="P" class="common-radio attd" checked>
                                                                    <label for="attendanceP{{$record->id}}">@lang('student.present')</label>
                                                                </div>
                                                                <div class="mr-20">
                                                                    <input type="radio" name="attendance[{{@$record->student_record_id}}][attendance_type]" id="attendanceL{{@$record->student_record_id}}" value="A" class="common-radio">
                                                                    <label for="attendanceL{{$record->student_record_id}}">@lang('student.absent')</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach($exam_attendance_childs as $student)
                                                    <tr>
                                                        <td>{{ @$student->studentInfo !=""? @$student->studentInfo->admission_no:""}}</td>
                                                        <td>{{@$student->studentInfo !="" ? @$student->studentInfo->full_name:""}}</td>
                                                        <td>{{@$student->studentRecord !="" ? @$student->studentRecord->roll_no:""}}</td>
                                                        <td>
                                                            <input type="hidden" name="attendance[{{@$student->student_record_id}}]" value="{{@$student->student_record_id}}">
                                                            <input type="hidden" name="attendance[{{@$student->student_record_id}}][student]" value="{{@$student->student_id}}">
                                                            <div class="d-flex radio-btn-flex">
                                                                <div class="mr-20">
                                                                    <input type="radio" name="attendance[{{@$student->student_record_id}}][attendance_type]" id="attendanceP{{@$student->student_record_id}}" value="P" class="common-radio" {{@$student->attendance_type == 'P'? 'checked': ''}}>
                                                                    <label for="attendanceP{{@$student->student_record_id}}">@lang('student.present')</label>
                                                                </div>
                                                                <div class="mr-20">
                                                                    <input type="radio" name="attendance[{{@$student->student_record_id}}][attendance_type]" id="attendanceL{{@$student->student_record_id}}" value="A" class="common-radio" {{@$student->attendance_type == 'A'? 'checked': ''}}>
                                                                    <label for="attendanceL{{@$student->student_record_id}}">@lang('student.absent')</label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="primary-btn fix-gr-bg nowrap submit">
                                            @lang('exam.save_attendance')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-40">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 no-gutters">
                                <div class="main-title">
                                    <h3 class="mb-30">@lang('exam.exam_attendance') | <small>@lang('common.class'): {{$search_info['class_name']}}, @lang('common.section'): {{$search_info['section_name']}},  @lang('common.subject'): {{$search_info['subject_name']}}</small></h3>
                                </div>
                            </div>
                        </div>

                        <input class="examId" type="hidden" name="exam_id" value="{{ @$exam_id}}">
                        <input class="subjectId" type="hidden" name="subject_id" value="{{ @$subject_id}}">
                        <input class="classId" type="hidden" name="class_id" value="{{ @$class_id}}">
                        <input class="sectionId" type="hidden" name="section_id" value="{{ @$section_id}}">

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="white-box">
                                    <table class="table school-table-style shadow-none p-0" cellspacing="0" width="100%">
                                        <thead>

                                            <tr>
                                                <th width="25%">@lang('student.admission_no')</th>
                                                <th width="25%">@lang('student.student_name')</th>
                                                <th width="25%">@lang('common.class_Sec')</th>
                                                <th width="25%">@lang('student.roll_number')</th>
                                                <th width="25%">@lang('exam.attendance')</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @if(count($exam_attendance_childs) == 0)

                                                @foreach($students as $record)
                                                <tr>
                                                    <td>{{@$record->studentDetail->admission_no}}</td>
                                                    <td>{{@$record->studentDetail->full_name}}</td>
                                                    <td>{{@$record->class->class_name}} ({{@$record->section->section_name}})</td>
                                                    <td>{{@$record->roll_no}}</td>
                                                    <td>
                                                        <div class="d-flex radio-btn-flex">
                                                            <div class="mr-20">
                                                                <input type="hidden" name="attendance[{{@$record->id}}]" value="{{@$record->id}}">
                                                                <input type="hidden" name="attendance[{{@$record->id}}][student]" value="{{@$record->student_id}}">
                                                                <input type="hidden" name="attendance[{{@$record->id}}][class]" value="{{@$record->class_id}}">
                                                                <input type="hidden" name="attendance[{{@$record->id}}][section]" value="{{@$record->section_id}}">
                                                                <input type="radio" name="attendance[{{@$record->id}}][attendance_type]" id="attendanceP{{@$record->id}}" value="P" class="common-radio attd" checked>
                                                                <label for="attendanceP{{$record->id}}">@lang('student.present')</label>
                                                            </div>
                                                            <div class="mr-20">
                                                                <input type="radio" name="attendance[{{@$record->id}}][attendance_type]" id="attendanceL{{@$record->id}}" value="A" class="common-radio">
                                                                <label for="attendanceL{{$record->id}}">@lang('student.absent')</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else

                                                @foreach($exam_attendance_childs as $student)
                                                <tr>
                                                    <td>
                                                        {{ @$student->studentInfo !=""? @$student->studentInfo->admission_no:""}}

                                                    </td>
                                                    <td>{{@$student->studentInfo !="" ? @$student->studentInfo->full_name:""}}</td>
                                                    <td>{{@$student->studentRecord->class->class_name}} ({{@$student->studentRecord->section->section_name}})</td>
                                                    <td>{{@$student->studentRecord !="" ? @$student->studentRecord->roll_no:""}}</td>
                                                    <td>
                                                        <input type="hidden" name="attendance[{{@$student->student_record_id}}]" value="{{@$student->student_record_id}}">
                                                        <input type="hidden" name="attendance[{{@$student->student_record_id}}][student]" value="{{@$student->student_id}}">
                                                        <input type="hidden" name="attendance[{{@$student->student_record_id}}][class]" value="{{@$student->class_id}}">
                                                        <input type="hidden" name="attendance[{{@$student->student_record_id}}][section]" value="{{@$student->section_id}}">
                                                        <div class="d-flex radio-btn-flex">
                                                            <div class="mr-20">
                                                                <input type="radio" name="attendance[{{@$student->student_record_id}}][attendance_type]" id="attendanceP{{@$student->student_record_id}}" value="P" class="common-radio" {{@$student->attendance_type == 'P'? 'checked': ''}}>
                                                                <label for="attendanceP{{@$student->student_record_id}}">@lang('student.present')</label>
                                                            </div>
                                                            <div class="mr-20">
                                                                <input type="radio" name="attendance[{{@$student->student_record_id}}][attendance_type]" id="attendanceL{{@$student->student_record_id}}" value="A" class="common-radio" {{@$student->attendance_type == 'A'? 'checked': ''}}>
                                                                <label for="attendanceL{{@$student->student_record_id}}">@lang('student.absent')</label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="primary-btn fix-gr-bg nowrap submit">
                                            @lang('exam.save_attendance')
                                        </button>
                                    </div>
                                </div>
                            {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        {{ Form::close() }}
        @endif
    </div>
</section>
@endsection
