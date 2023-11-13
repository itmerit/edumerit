@extends('backEnd.master')
@section('title')
@lang('student.student_attendance')
@endsection

@section('mainContent')

@php
$breadCrumbs = [
'h1'=>__('student.student_attendance'),
'bgPages'=>[
'<a href="#">'.__('student.student_information').'</a>'
]
];
@endphp
<x-bread-crumb-component :breadCrumbs="$breadCrumbs" />

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12 d-flex align-items-center justify-content-between flex-wrap">
                <div class="main-title m-0">
                    <h3 class="mb-30">@lang('common.select_criteria') </h3>
                </div>
                <a href="{{route('student-attendance-import')}}"
                    class="primary-btn small fix-gr-bg pull-right mb-20"><span
                        class="ti-plus pr-2"></span>@lang('student.import_attendance')</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'student-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        @if(moduleStatusCheck('University'))
                        @includeIf('university::common.session_faculty_depart_academic_semester_level',['mt'=>'mt-30','hide'=>['USUB'],
                        'required'=>['USEC']])
                        <div class="col-lg-3 col-md-3 mt-30">
                            <div class="primary_input">
                                <label for="startDate">@lang('hr.attendance_date')<span class="text-danger">
                                        *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control"
                                                    id="startDate" type="text" name="attendance_date" autocomplete="off"
                                                    value="{{isset($date)? $date: date('m/d/Y')}}">
                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#attendance_date" type="button">
                                            <label class="m-0 p-0" for="startDate">
                                                <i class="ti-calendar" id="attendance_date"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('attendance_date') }}</span>
                            </div>
                        </div>
                        @else

                        @include('backEnd.common.search_criteria', [
                        'div'=>'col-lg-4',
                        'visiable'=>['class', 'section'],
                        'required'=>['class', 'section'],
                        ])

                        <div class="col-lg-4 col-md-4 mt-30-md">
                            <div class="primary_input">
                                <label for="startDate">@lang('hr.attendance_date')<span class="text-danger">
                                        *</span></label>
                                <div class="primary_datepicker_input">
                                    <div class="no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="">
                                                <input
                                                    class="primary_input_field  primary_input_field date form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }}"
                                                    id="attendance_date" type="text" name="attendance_date"
                                                    autocomplete="off" value="{{isset($date)? $date: date('m/d/Y')}}">
                                            </div>
                                        </div>
                                        <button class="btn-date" data-id="#attendance_date" type="button">
                                            <label class="m-0 p-0" for="attendance_date">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                                <span class="text-danger">{{ $errors->first('attendance_date') }}</span>
                            </div>
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
        @isset($students)
        <div class="row mt-40">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12 no-gutters">
                        <div class="main-title">
                            @isset($search_info)
                            <h3 class="mb-30">@lang('student.student_attendance') | <small>
                                    @if(moduleStatusCheck('University'))
                                    @lang('university::un.faculty_department')
                                    :
                                    {{ isset($unFaculty) ? $unFaculty->name .'('. (isset($unDepartment) ? $unDepartment->name:'').')':''}},
                                    @lang('university::un.semester(label)')
                                    :
                                    {{ isset($unSemester) ? $unSemester->name .'('. (isset($unSemesterLabel) ? $unSemesterLabel->name : '') .')' :''}},
                                    @lang('common.date')
                                    @else
                                    @lang('common.class')
                                    : {{$search_info['class_name']}}, @lang('common.section')
                                    : {{$search_info['section_name']}}, @lang('common.date')
                                    @endif
                                    : {{dateConvert($search_info['date'])}}</small></h3>
                            @endisset
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 no-gutters">
                        @if($attendance_type != "" && $attendance_type == "H")
                        <div class="alert alert-warning">@lang('student.attendance_already_submitted_as_holiday')</div>
                        @elseif($attendance_type != "" && $attendance_type != "H")
                        <div class="alert alert-success">@lang('student.attendance_already_submitted')</div>
                        @endif
                    </div>
                </div>
                <div class="row mb-20">
                    <div class="col-lg-6  col-md-6 no-gutters text-md-left mark-holiday ">
                        @if($attendance_type != "H")
                        <form action="{{route('student-attendance-holiday')}}" method="POST">
                            @csrf
                            <input type="hidden" name="purpose" value="mark">
                            <input type="hidden" name="class_id" value="{{$class_id}}">
                            <input type="hidden" name="section_id" value="{{$section_id}}">

                            <input type="hidden" name="attendance_date" value="{{$date}}">
                            @if(moduleStatusCheck('University'))

                            <input type="hidden" name="un_session_id" value="{{isset($unSession) ? $unSession->id:''}}">
                            <input type="hidden" name="un_faculty_id" value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                            <input type="hidden" name="un_department_id"
                                value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                            <input type="hidden" name="un_academic_id"
                                value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                            <input type="hidden" name="un_semester_id"
                                value="{{isset($unSemester) ? $unSemester->id:''}}">
                            <input type="hidden" name="un_section_id" value="{{isset($unSection) ? $unSection->id:''}}">
                            @endif
                            <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                @lang('student.mark_holiday')
                            </button>
                        </form>
                        @else
                        <form action="{{route('student-attendance-holiday')}}" method="POST">
                            @csrf
                            <input type="hidden" name="purpose" value="unmark">
                            <input type="hidden" name="class_id" value="{{$class_id}}">
                            <input type="hidden" name="section_id" value="{{$section_id}}">
                            <input type="hidden" name="attendance_date" value="{{$date}}">
                            @if(moduleStatusCheck('University'))

                            <input type="hidden" name="un_session_id" value="{{isset($unSession) ? $unSession->id:''}}">
                            <input type="hidden" name="un_faculty_id" value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                            <input type="hidden" name="un_department_id"
                                value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                            <input type="hidden" name="un_academic_id"
                                value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                            <input type="hidden" name="un_semester_id"
                                value="{{isset($unSemester) ? $unSemester->id:''}}">
                            <input type="hidden" name="un_semester_label_id"
                                value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                            <input type="hidden" name="un_section_id" value="{{isset($unSection) ? $unSection->id:''}}">
                            @endif
                            <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                @lang('student.unmark_holiday')
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                {{ Form::open(['class' => 'form-horizontal', 'route'=>'student-attendance-store','files' => true, 'method' => 'POST', 'enctype' => 'multipart/form-data'])}}
                <input type="hidden" name="date" class="attendance_date" value="{{isset($date)? $date: ''}}">
                <div class="row">
                    <div class="col-lg-12">
                        <x-table>
                            <table class="table school-table-style" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.student_name')</th>
                                        <th>@lang('student.roll_number')</th>
                                        <th>@lang('student.attendance')</th>
                                        <th>@lang('common.note')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)

                                    <tr>
                                        <td>{{$student->studentDetail->admission_no}}
                                            <input type="hidden" name="attendance[{{$student->id}}]"
                                                value="{{$student->id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][student]"
                                                value="{{$student->student_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][class]"
                                                value="{{$student->class_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][section]"
                                                value="{{$student->section_id}}">
                                            @if(moduleStatusCheck('University'))

                                            <input type="hidden" name="attendance[{{$student->id}}][un_session_id]"
                                                value="{{$student->un_session_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][un_faculty_id]"
                                                value="{{$student->un_faculty_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][un_department_id]"
                                                value="{{$student->un_department_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][un_academic_id]"
                                                value="{{$student->un_academic_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][un_semester_id]"
                                                value="{{$student->un_semester_id}}">
                                            <input type="hidden"
                                                name="attendance[{{$student->id}}][un_semester_label_id]"
                                                value="{{$student->un_semester_label_id}}">
                                            <input type="hidden" name="attendance[{{$student->id}}][un_section_id]"
                                                value="{{$student->un_section_id}}">
                                            @endif

                                        </td>
                                        <td>{{$student->studentDetail->first_name.' '.$student->studentDetail->last_name}}
                                        </td>
                                        <td>{{$student->roll_no}}</td>
                                        <td>
                                            <div class="d-flex radio-btn-flex">
                                                <div class="mr-20">
                                                    <input type="radio"
                                                        name="attendance[{{$student->id}}][attendance_type]"
                                                        id="attendanceP{{$student->id}}" value="P"
                                                        class="common-radio attendanceP attendance_type"
                                                        {{ $student->studentDetail->DateWiseAttendances !=null ? ($student->studentDetail->DateWiseAttendances->attendance_type == "P" ? 'checked' :'') : ($attendance_type != "" ? '' :'checked') }}>
                                                    <label
                                                        for="attendanceP{{$student->id}}">@lang('student.present')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio"
                                                        name="attendance[{{$student->id}}][attendance_type]"
                                                        id="attendanceL{{$student->id}}" value="L"
                                                        class="common-radio attendance_type"
                                                        {{ $student->studentDetail->DateWiseAttendances !=null ? ($student->studentDetail->DateWiseAttendances->attendance_type == "L" ? 'checked' :''):''}}>
                                                    <label
                                                        for="attendanceL{{$student->id}}">@lang('student.late')</label>
                                                </div>
                                                <div class="mr-20">
                                                    <input type="radio"
                                                        name="attendance[{{$student->id}}][attendance_type]"
                                                        id="attendanceA{{$student->id}}" value="A"
                                                        class="common-radio attendance_type"
                                                        {{$student->studentDetail->DateWiseAttendances !=null ? ($student->studentDetail->DateWiseAttendances->attendance_type == "A" ? 'checked' :''):''}}>
                                                    <label
                                                        for="attendanceA{{$student->id}}">@lang('student.absent')</label>
                                                </div>
                                                <div>
                                                    <input type="radio"
                                                        name="attendance[{{$student->id}}][attendance_type]"
                                                        id="attendanceH{{$student->id}}" value="F"
                                                        class="common-radio attendance_type"
                                                        {{$student->studentDetail->DateWiseAttendances !=null ? ($student->studentDetail->DateWiseAttendances->attendance_type == "F" ? 'checked' :'') : ''}}>
                                                    <label
                                                        for="attendanceH{{$student->id}}">@lang('student.half_day')</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="primary_input">
                                                <input class="primary_input_field form-control note_{{$student->id}}"
                                                    cols="0" rows="2" name="attendance[{{$student->id}}][note]"
                                                    id="" value="{{$student->studentDetail->DateWiseAttendances !=null ? $student->studentDetail->DateWiseAttendances->notes :''}}">
                                                <label class="primary_input_label"
                                                    for="">@lang('student.add_note_here')</label>

                                                {{-- <span class="text-danger">@lang('common.error')</span> --}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="submit" class="primary-btn mr-40 fix-gr-bg nowrap submit">
                                                @lang('student.save_attendance')
                                            </button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </x-table>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        @endisset
    </div>
</section>
@endsection

@include('backEnd.partials.date_picker_css_js')