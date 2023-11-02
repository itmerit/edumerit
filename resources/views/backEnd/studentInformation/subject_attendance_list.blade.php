@extends('backEnd.master')
@section('title')
    @lang('student.subject_wise_attendance')
@endsection
@push('css')
    <style>
        .school-table-style td {
            vertical-align: middle;
        }
    </style>
@endpush
@section('mainContent')
    <section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('student.subject_wise_attendance')</h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('student.student_information')</a>
                    <a href="#">@lang('student.subject_wise_attendance')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area up_st_admin_visitor">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal','route' => 'subject-attendance-search', 'method' => 'GET', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            @if(moduleStatusCheck('University'))
                                @includeIf('university::common.session_faculty_depart_academic_semester_level',['required'=>['USN','UD', 'UA', 'US','USL','USUB']])
                                <div class="col-lg-3 mt-25">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field primary_input_field date form-control form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}"
                                                       id="startDate" type="text" data-date-format="yyyy-mm-dd"
                                                       name="attendance_date" autocomplete="off"
                                                       value="{{isset($date)? $date: date('Y-m-d')}}">

                                                @if ($errors->has('attendance_date'))
                                                    <span class="text-danger custom-error-message" role="alert">
                                                {{ $errors->first('attendance_date') }}
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                        <button class="" type="button">
                                            <i class="ti-calendar" id="start-date-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-3 mt-30-md">
                                    <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                            id="select_class" name="class">
                                        <option data-display="@lang('common.select_class')*"
                                                value="">@lang('common.select_class') *
                                        </option>
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}" {{isset($input['class'])? ($input['class'] == $class->id? 'selected':''):''}}>
                                                {{$class->class_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('class'))
                                        <span class="text-danger invalid-select" role="alert">
                                {{ $errors->first('class') }}
                            </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md" id="select_subject_div">
                                    <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject"
                                            id="select_subject" name="subject">
                                        <option data-display="{{__('student.select_subject')}} *"
                                                value="">{{__('student.select_subject')}} *
                                        </option>
                                        @isset($subjects)
                                            @foreach($subjects as $subject)
                                                @php
                                                    $type = $subject->subject->subject_type == 'T' ? 'Theory' : 'Practical';
                                                @endphp
                                                <option value="{{$subject->subject_id}}" {{isset($input['subject'])? ($input['subject'] == $subject->subject_id? 'selected':''):''}}>
                                                    {{$subject->subject->subject_name}}({{$type}})
                                                </option>
                                            @endforeach
                                        @endisset
                                    </select>
                                    <div class="pull-right loader loader_style" id="select_subject_loader">
                                        <img class="loader_img_style"
                                             src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                    </div>
                                    @if ($errors->has('subject'))
                                        <span class="text-danger invalid-select" role="alert">
                                            {{ $errors->first('subject') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="col-lg-3 mt-30-md">
                                    <div class="row no-gutters input-right-icon">
                                        <div class="col">
                                            <div class="primary_input">
                                                <input class="primary_input_field primary_input_field date form-control form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}"
                                                       id="startDate" type="text" data-date-format="yyyy-mm-dd"
                                                       name="attendance_date" autocomplete="off"
                                                       value="{{isset($date)? $date: date('Y-m-d')}}">
                                                <label for="startDate">
                                                    @lang('student.attendance_date')<span class="text-danger"> *</span>
                                                </label>
                                                @if ($errors->has('attendance_date'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('attendance_date') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button class="" type="button">
                                                <i class="ti-calendar" id="start-date-icon"></i>
                                            </button>
                                        </div>
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
            @if(isset($students))
                <div class="row mt-40">
                    <div class="col-lg-12 ">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 no-gutters">
                                @if($attendance_type != "" && $attendance_type == "H")
                                    <div class="alert alert-warning">@lang('student.attendance_already_submitted_as_holiday')</div>
                                @elseif($attendance_type != "" && $attendance_type != "H")
                                    <div class="alert alert-success">@lang('student.attendance_already_submitted')</div>
                                @endif
                            </div>
                        </div>
                        {{-- HoliDay Start --}}
                        <div class="row mb-20">
                            <div class="col-lg-6  col-md-6 no-gutters text-md-left mark-holiday ">
                                @if($attendance_type != "H")
                                    <form action="{{route('student-subject-holiday-store')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purpose" value="mark">
                                        <input type="hidden" name="class_id" value="{{$input['class']}}">
                                        <input type="hidden" name="section_id" value="{{$input['section']}}">
                                        <input type="hidden" name="subject_id" value="{{$input['subject']}}">
                                        @if(moduleStatusCheck('University'))
                                            <input type="hidden" name="un_session_id"
                                                   value="{{isset($unSession) ? $unSession->id:''}}">
                                            <input type="hidden" name="un_faculty_id"
                                                   value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                                            <input type="hidden" name="un_department_id"
                                                   value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                                            <input type="hidden" name="un_academic_id"
                                                   value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                                            <input type="hidden" name="un_semester_id"
                                                   value="{{isset($unSemester) ? $unSemester->id:''}}">
                                            <input type="hidden" name="un_semester_label_id"
                                                   value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                                            <input type="hidden" name="un_subject_id"
                                                   value="{{isset($unSubject) ? $unSubject->id :''}}">
                                        @endif
                                        <input type="hidden" name="attendance_date"
                                               value="{{$input['attendance_date']}}">
                                        <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                            @lang('student.mark_holiday')
                                        </button>
                                    </form>
                                @else
                                    <form action="{{route('student-subject-holiday-store')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purpose" value="unmark">
                                        <input type="hidden" name="class_id" value="{{$input['class']}}">
                                        <input type="hidden" name="section_id" value="{{$input['section']}}">
                                        <input type="hidden" name="subject_id" value="{{$input['subject']}}">
                                        @if(moduleStatusCheck('University'))
                                            <input type="hidden" name="un_session_id"
                                                   value="{{isset($unSession) ? $unSession->id:''}}">
                                            <input type="hidden" name="un_faculty_id"
                                                   value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                                            <input type="hidden" name="un_department_id"
                                                   value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                                            <input type="hidden" name="un_academic_id"
                                                   value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                                            <input type="hidden" name="un_semester_id"
                                                   value="{{isset($unSemester) ? $unSemester->id:''}}">
                                            <input type="hidden" name="un_semester_label_id"
                                                   value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                                            <input type="hidden" name="un_subject_id"
                                                   value="{{isset($unSubject) ? $unSubject->id :''}}">
                                        @endif
                                        <input type="hidden" name="attendance_date"
                                               value="{{$input['attendance_date']}}">
                                        <button type="submit" class="primary-btn fix-gr-bg mb-20">
                                            @lang('student.unmark_holiday')
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        {{-- HoliDay End --}}

                        {{ Form::open(['class' => 'form-horizontal', 'route' => 'subject-attendance-store', 'method' => 'POST'])}}
                        <input class="subject_class" type="hidden" name="class" value="{{$input['class']}}">
                        <input class="subject_section" type="hidden" name="section" value="{{$input['section']}}">
                        <input class="subject" type="hidden" name="subject" value="{{$input['subject']}}">
                        <input class="subject_attendance_date" type="hidden" name="attendance_date"
                               value="{{$input['attendance_date']}}">
                        <input type="hidden" name="un_semester_label_id"
                               value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                        <input type="hidden" name="un_subject_id" value="{{isset($unSubject) ? $unSubject->id :''}}">
                        <input type="hidden" name="date"
                               value="{{isset($input['attendance_date'])? $input['attendance_date']: ''}}">
                        <div class="row ">
                            <div class="col-lg-12">
                                <table class="table school-table-style" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>@lang('common.sl')</th>
                                        <th>@lang('student.admission_no')</th>
                                        <th>@lang('student.student_name')</th>
                                        <th>@lang('student.behaviour')</th>
                                        <th>@lang('student.attendance')</th>
                                        <th>@lang('student.grade')</th>
                                        <th>@lang('common.note')</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @php $count=1; @endphp

                                    @foreach($students as $student)
                                        @php
                                        $attendance = isset($student->todaySubjectAttendance[0])?$student->todaySubjectAttendance[0]:false
                                        @endphp
                                        <tr>
                                            <td>{{$count++}} </td>
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
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_session_id]"
                                                           value="{{$student->un_session_id}}">
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_faculty_id]"
                                                           value="{{$student->un_faculty_id}}">
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_department_id]"
                                                           value="{{$student->un_department_id}}">
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_academic_id]"
                                                           value="{{$student->un_academic_id}}">
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_semester_id]"
                                                           value="{{$student->un_semester_id}}">
                                                    <input type="hidden"
                                                           name="attendance[{{$student->id}}][un_semester_label_id]"
                                                           value="{{$student->un_semester_label_id}}">
                                                @endif
                                            </td>
                                            <td>{{$student->studentDetail->first_name.' '.$student->studentDetail->last_name}}</td>
                                            <td>
                                                <div class="d-flex radio-btn-flex">
                                                    @foreach(['R'=>['Red','red'], 'Y'=>['Yellow','#ebdb34'], 'G'=>['Green','green']] as $k=>$v)
                                                        <div class="mr-20">
                                                            <input type="radio"
                                                                   name="attendance[{{$student->id}}][behaviour_type]"
                                                                   id="behaviour{{$k}}{{$student->id}}" value="{{$k}}"
                                                                   class="common-radio subject_attendance_type"
                                                                    {{ $attendance ? ($attendance->behaviour_type == $k ? 'checked' :($k=='P'?'checked':'')) : ($k=='R'?'checked':'')}}>
                                                            <label for="behaviour{{$k}}{{$student->id}}"
                                                                   style="color: {{$v[1]}};">{{$v[0]}}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex radio-btn-flex">
                                                    @foreach(['P'=>'present','L' => 'late','A' => 'absent'] as $k=>$v)
                                                        <div class="mr-20">
                                                            <input type="radio"
                                                                   name="attendance[{{$student->id}}][attendance_type]"
                                                                   id="attendance{{$k}}{{$student->id}}" value="{{$k}}"
                                                                   class="common-radio subject_attendance_type"
                                                                    {{ $attendance ? ($attendance->attendance_type == $k ? 'checked' :($k=='P'?'checked':'')) : ($k=='P'?'checked':'') }}>
                                                            <label for="attendance{{$k}}{{$student->id}}">@lang('student.'.$v)</label>
                                                        </div>
                                                    @endforeach
                                                    {{--<div>--}}
                                                    {{--<input type="radio" name="attendance[{{$student->id}}][attendance_type]" id="attendanceH{{$student->id}}" value="F" class="common-radio subject_attendance_type" {{isset($student->todaySubjectAttendance[0]) ? ($student->todaySubjectAttendance[0]->attendance_type == "F" ? 'checked' :'') : ''}}>--}}
                                                    {{--<label for="attendanceH{{$student->id}}">@lang('student.half_day')</label>--}}
                                                    {{--</div>--}}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="primary_input">
                                                    <input class="primary_input_field form-control note_{{$student->id}}"
                                                           type="number" step="1"
                                                           name="attendance[{{$student->id}}][grade]"
                                                           value="{{ $attendance ? $attendance->grade :'' }}" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="primary_input">
                                                    <textarea
                                                            class="primary_input_field form-control note_{{$student->id}}"
                                                            cols="0" rows="2"
                                                            name="attendance[{{$student->id}}][note]">{{ $attendance ? $attendance->notes :''}}</textarea>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">
                                            <button type="submit" class="primary-btn mr-40 fix-gr-bg nowrap submit">
                                                @lang('student.attendance')
                                            </button>
                                        </td>
                                        <td></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
