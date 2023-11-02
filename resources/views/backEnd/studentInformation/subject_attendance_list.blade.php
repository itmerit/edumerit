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
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'subject-attendance-search', 'method' => 'POST', 'id' => 'search_studentA']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">

                        @if(moduleStatusCheck('University'))
                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['required'=>['USN','UD', 'UA', 'US','USL', 'USEC', 'USUB']])

                            <div class="col-lg-3 mt-25">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                   name="attendance_date" autocomplete="off" value="{{isset($date)? $date: date('m/d/Y')}}">
                                            <label for="startDate">@lang('student.attendance_date')<span class="text-danger"> *</span></label>


                                            @if ($errors->has('attendance_date'))
                                                <span class="text-danger" >
                                                    {{ $errors->first('attendance_date') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <button class="btn-date" type="button">
                                        <label class="m-0 p-0" for="startDate">
                                            <i class="ti-calendar" id="admission-date-icon"></i>
                                        </label>
                                    </button>
                                </div>

                            </div>
                        @else
                            @include('backEnd.common.search_criteria', [
                           'div'=>'col-lg-3',
                           'subject'=>true,
                           'required'=>['class', 'subject'],
                           'visiable'=>['class', 'subject'],
                           ])

                            <div class="col-lg-3 mt-30-md md_mb_20">

                                <div class="primary_input">
                                    <label for="startDate">@lang('student.attendance_date')<span class="text-danger"> *</span></label>
                                    <div class="primary_datepicker_input">
                                        <div class="no-gutters input-right-icon">
                                            <div class="col">
                                                <div class="">
                                                    <input class="primary_input_field  primary_input_field date form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }}" id="attendance_date" type="text"
                                                           name="attendance_date" autocomplete="off" value="{{isset($date)? $date: date('m/d/Y')}}">
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
@if(isset($students))
        <div class="row mt-40">
            <div class="col-lg-12 ">
                <div class=" white-box mb-40">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title">
                                <h3 class="mb-30 text-center">@lang('student.subject_wise_attendance') </h3>
                            </div>
                        </div>
                        @if(moduleStatusCheck('University'))
                            <div class="col-lg-3">
                                <strong> @lang('university::un.faculty_department'): </strong>
                                {{ isset($unFaculty) ? $unFaculty->name .'('. (isset($unDepartment) ? $unDepartment->name:'').')':''}}
                            </div>
                            <div class="col-lg-3">
                                <strong>  @lang('university::un.semester(label)'): </strong>
                                {{ isset($unSemester) ? $unSemester->name .'('. (isset($unSemesterLabel) ? $unSemesterLabel->name : '') .')' :''}}
                            </div>
                            <div class="col-lg-3">
                                <strong> @lang('common.subject'): </strong>
                                {{ isset($unSubject) ? $unSubject->subject_name :''}}
                            </div>
                        @else
                            <div class="col-lg-3">
                                <strong> @lang('common.class'): </strong> {{$search_info['class_name']}}
                            </div>
                            <div class="col-lg-3">
                                <strong> @lang('common.section'): </strong> {{$search_info['section_name']}}
                            </div>
                            <div class="col-lg-3">
                                <strong> @lang('common.subject'): </strong> {{$search_info['subject_name']}}
                            </div>
                        @endif
                        <div class="col-lg-3">
                            <strong> @lang('common.date'): </strong> {{dateConvert($input['attendance_date'])}}
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
                                        <input type="hidden" name="un_session_id" value="{{isset($unSession) ? $unSession->id:''}}">
                                        <input type="hidden" name="un_faculty_id" value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                                        <input type="hidden" name="un_department_id" value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                                        <input type="hidden" name="un_academic_id" value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                                        <input type="hidden" name="un_semester_id" value="{{isset($unSemester) ? $unSemester->id:''}}">
                                        <input type="hidden" name="un_semester_label_id" value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                                        <input type="hidden" name="un_subject_id" value="{{isset($unSubject) ? $unSubject->id :''}}">
                                    @endif
                                    <input type="hidden" name="attendance_date" value="{{$input['attendance_date']}}">
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
                                        <input type="hidden" name="un_session_id" value="{{isset($unSession) ? $unSession->id:''}}">
                                        <input type="hidden" name="un_faculty_id" value="{{isset($unFaculty) ? $unFaculty->id:''}}">
                                        <input type="hidden" name="un_department_id" value="{{isset($unDepartment) ? $unDepartment->id:''}}">
                                        <input type="hidden" name="un_academic_id" value="{{isset($unAcademic) ? $unAcademic->id:''}}">
                                        <input type="hidden" name="un_semester_id" value="{{isset($unSemester) ? $unSemester->id:''}}">
                                        <input type="hidden" name="un_semester_label_id" value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                                        <input type="hidden" name="un_subject_id" value="{{isset($unSubject) ? $unSubject->id :''}}">
                                    @endif
                                    <input type="hidden" name="attendance_date" value="{{$input['attendance_date']}}">
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
                    <input class="subject_attendance_date" type="hidden" name="attendance_date" value="{{$input['attendance_date']}}">
                    <input type="hidden" name="un_semester_label_id" value="{{isset($unSemesterLabel) ? $unSemesterLabel->id:''}}">
                    <input type="hidden" name="un_subject_id" value="{{isset($unSubject) ? $unSubject->id :''}}">
                    <input type="hidden" name="date" value="{{isset($input['attendance_date'])? $input['attendance_date']: ''}}">
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
                                    {{-- student means student record data--}}
                                        <tr>
                                            <td>{{$count++}} </td>
                                            <td>{{$student->studentDetail->admission_no}}
                                                <input type="hidden" name="attendance[{{$student->id}}]" value="{{$student->id}}">
                                                <input type="hidden" name="attendance[{{$student->id}}][student]" value="{{$student->student_id}}">
                                                <input type="hidden" name="attendance[{{$student->id}}][class]" value="{{$student->class_id}}">
                                                <input type="hidden" name="attendance[{{$student->id}}][section]" value="{{$student->section_id}}">

                                                @if(moduleStatusCheck('University'))
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_session_id]" value="{{$student->un_session_id}}">
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_faculty_id]" value="{{$student->un_faculty_id}}">
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_department_id]" value="{{$student->un_department_id}}">
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_academic_id]" value="{{$student->un_academic_id}}">
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_semester_id]" value="{{$student->un_semester_id}}">
                                                    <input type="hidden" name="attendance[{{$student->id}}][un_semester_label_id]" value="{{$student->un_semester_label_id}}">
                                                @endif
                                            </td>
                                            <td>{{$student->studentDetail->first_name.' '.$student->studentDetail->last_name}}</td>
                                            <td>
                                                <div class="d-flex radio-btn-flex">
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][behaviour_type]" id="behaviourR{{$student->id}}" value="R" class="common-radio attendanceP subject_attendance_type" {{ $student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->behaviour_type == "R" ? 'checked' :'') : ''}}>
                                                        <label for="behaviourR{{$student->id}}" style="color: red;">Red</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][behaviour_type]" id="behaviourY{{$student->id}}" value="Y" class="common-radio subject_attendance_type" {{ $student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->behaviour_type == "Y" ? 'checked' :''):'checked'}}>
                                                        <label for="behaviourY{{$student->id}}" style="color: #ebdb34;">Yellow</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][behaviour_type]" id="behaviourG{{$student->id}}" value="G" class="common-radio subject_attendance_type" {{$student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->behaviour_type == "G" ? 'checked' :''):''}}>
                                                        <label for="behaviourG{{$student->id}}" style="color: green;">Green</label>
                                                    </div>
                                                </div>


                                            </td>
                                            <td>
                                                <div class="d-flex radio-btn-flex">
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][attendance_type]" id="attendanceP{{$student->id}}" value="P" class="common-radio attendanceP subject_attendance_type" {{ $student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->attendance_type == "P" ? 'checked' :'') : 'checked' }}>
                                                        <label for="attendanceP{{$student->id}}">@lang('student.present')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][attendance_type]" id="attendanceL{{$student->id}}" value="L" class="common-radio subject_attendance_type" {{ $student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->attendance_type == "L" ? 'checked' :''):''}}>
                                                        <label for="attendanceL{{$student->id}}">@lang('student.late')</label>
                                                    </div>
                                                    <div class="mr-20">
                                                        <input type="radio" name="attendance[{{$student->id}}][attendance_type]" id="attendanceA{{$student->id}}" value="A" class="common-radio subject_attendance_type" {{$student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->attendance_type == "A" ? 'checked' :''):''}}>
                                                        <label for="attendanceA{{$student->id}}">@lang('student.absent')</label>
                                                    </div>
{{--                                                    <div>--}}
{{--                                                        <input type="radio" name="attendance[{{$student->id}}][attendance_type]" id="attendanceH{{$student->id}}" value="F" class="common-radio subject_attendance_type" {{$student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->attendance_type == "F" ? 'checked' :'') : ''}}>--}}
{{--                                                        <label for="attendanceH{{$student->id}}">@lang('student.half_day')</label>--}}
{{--                                                    </div>--}}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="primary_input">
                                                    <input class="primary_input_field form-control note_{{$student->id}}" type="number" step="1" name="attendance[{{$student->id}}][grade]" value="{{$student->studentDetail->DateSubjectWiseAttendances !=null ? $student->studentDetail->DateSubjectWiseAttendances->grades :''}}">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="primary_input">
                                                    <textarea class="primary_input_field form-control note_{{$student->id}}" cols="0" rows="2" name="attendance[{{$student->id}}][note]">{{$student->studentDetail->DateSubjectWiseAttendances !=null ? $student->studentDetail->DateSubjectWiseAttendances->notes :''}}</textarea>
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
                {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
@endif
    </div>
</section>
@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')
