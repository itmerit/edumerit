@extends('backEnd.master')
@push('css')
<style type="text/css">
    .slider{
        top:7px !important;
    }
    table td {
        vertical-align: middle !important;
    }
    .switch_toggle {
        height: 28px;
    }
    table.dataTable thead th {
        padding-left: 30px !important;
    }
    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_asc::after {
        top: 10px !important;
        left: 15px !important;
    }
    table.dataTable tbody th, table.dataTable tbody td {
        padding: 20px 10px 20px 18px !important;
    }
</style>
@endpush
@section('mainContent')
@section('title') 
@lang('student.subject_wise_attendance')
@endsection
<link rel="stylesheet" href="{{asset('public/backEnd/css/login_access_control.css')}}"/>
<section class="sms-breadcrumb mb-40 up_breadcrumb white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('student.student_attendance')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('student.student_information')</a>
                <a href="#">@lang('student.student_attendance')</a>
            </div>
        </div>
    </div>
</section>
<style>
    .dataTables_wrapper .dataTables_paginate {
    text-align: right;
}
</style>
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
                {{-- <div class="col-lg-6 col-md-6">
                    <a href="{{url('student-attendance-import')}}" class="primary-btn small fix-gr-bg pull-right"><span class="ti-plus pr-2"></span>Import Attendance</a>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-lg-12">  
                    <div class="white-box">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subject-attendance-search', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_studentA']) }}
                            <div class="row">
                                <input type="hidden" name="url" id="url" value="{{URL::to('/')}}"> 
                                @if(moduleStatusCheck('University'))
                            @includeIf('university::common.session_faculty_depart_academic_semester_level',['required'=>['USN','UD', 'UA', 'US','USL','USUB']])
                            <div class="col-lg-3 mt-25">
                                <div class="row no-gutters input-right-icon">
                                    <div class="col">
                                        <div class="primary_input">
                                            <input class="primary_input_field  primary_input_field date form-control form-control{{ $errors->has('attendance_date') ? ' is-invalid' : '' }} {{isset($date)? 'read-only-input': ''}}" id="startDate" type="text"
                                                name="attendance_date" autocomplete="off" value="{{isset($date)? $date: date('m/d/Y')}}">
                                            <label for="startDate">@lang('student.attendance_date')<span class="text-danger"> *</span></label>
                                            

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
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class')*" value="">@lang('common.select_class') *</option>
                                    @foreach($classes as $class)
                                    <option value="{{$class->id}}"  {{isset($class_id)? ($class_id == $class->id? 'selected':''):''}}>{{$class->class_name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('class'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('class') }}
                                </span>
                                @endif
                            </div> 
                            <div class="col-lg-3 mt-30-md" id="select_section_div">
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                </select>
                                @if ($errors->has('section'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('section') }}
                                </span>
                                @endif
                            </div> 
                            <div class="col-lg-3 mt-30-md" id="select_subject_div">
                                <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_subject" name="subject">
                                    <option data-display="Select subject *" value="">Select subject *</option>
                                </select>
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
                                    <button class="" type="button">
                                        <i class="ti-calendar" id="start-date-icon"></i>
                                    </button>
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



                {{-- {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subject-attendance-store-second', 'method' => 'POST', 'enctype' => 'multipart/form-data'])}} --}}

 
                    <input class="subject_class" type="hidden" name="class" value="{{$input['class']}}">
                    <input class="subject_section" type="hidden" name="section" value="{{$input['section']}}">
                    <input class="subject" type="hidden" name="subject" value="{{$input['subject']}}">
                    <input class="subject_attendance_date" type="hidden" name="attendance_date" value="{{$input['attendance_date']}}">
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
                                            <b> @lang('common.class'): </b> {{$search_info['class_name']}}
                                        </div>
                                        <div class="col-lg-3">
                                            <b> @lang('common.section'): </b> {{$search_info['section_name']}}
                                        </div>
                                        <div class="col-lg-3">
                                            <b> @lang('common.subject'): </b> {{$search_info['subject_name']}}
                                        </div>
                                        <div class="col-lg-3">
                                            <b> @lang('common.date'): </b> {{dateConvert($search_info['date'])}}
                                        </div>
                                    @endif
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
                            <input type="hidden" name="date" value="{{isset($date)? $date: ''}}">
                            <div class="row ">
                                <div class="col-lg-12">
                                    <form name="frm-example" id="frm-example" method="POST">
                                        @csrf
                                        <table id="table_id" class="table" cellspacing="0" width="100%">
                                            <input type="hidden" name="attendance_date" value="{{$input['attendance_date']}}">
                                            <input type="hidden" name="subject" value="{{$input['subject']}}">
                                            <input type="hidden" name="class" value="{{$input['class']}}">
                                            <input type="hidden" name="section" value="{{$input['section']}}">
                                            <thead>
                                                <tr>
                                                    <th>@lang('common.sl')</th>
                                                    <th>@lang('student.admission_no')</th>
                                                    <th>@lang('student.student_name')</th>
                                                    <th>@lang('student.id_number')</th>
                                                    <th>@lang('student.attendance')</th>
                                                    <th>@lang('common.note')</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $count=1; @endphp

                                                @foreach($students as $student)
                                                <tr>
                                                    <td>{{$count++}}

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
                                                    <td>{{$student->studentDetail->admission_no}}</td>
                                                    <td>{{$student->studentDetail->first_name.' '.$student->studentDetail->last_name}}</td>
                                                    <td>{{$student->roll_no}}</td>
                                                    <td>
                                                    

                                                        <label class="switch_toggle">
                                                            <input type="checkbox" value="P" name="attendance[{{$student->id}}][attendance_type]" {{ $student->studentDetail->DateSubjectWiseAttendances !=null ? ($student->studentDetail->DateSubjectWiseAttendances->attendance_type == "P" ? 'checked' :'') : '' }}  class="switch-input11">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <div class="primary_input">
                                                            <textarea class="primary_input_field form-control note_{{$student->id}}" cols="0" rows="2" name="attendance[{{$student->id}}][note]" id="">{{$student->studentDetail->DateSubjectWiseAttendances !=null ? $student->studentDetail->DateSubjectWiseAttendances->notes :''}}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row mt-40">
                                            <div class="col-lg-12 text-center">
                                                <button type="submit" class="primary-btn fix-gr-bg save-template">
                                                    <span class="ti-check"></span>
                                                    @lang('common.save')
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- {{ Form::close() }} --}}

    </div>
</section>


@endsection
@include('backEnd.partials.data_table_js')
@include('backEnd.partials.date_picker_css_js')

@push('script')
<script>
  $(document).ready(function (){
   var table = $('#default_table').DataTable();
   
   // Handle form submission event 
   $('#frm-example').on('submit', function(e){
      e.preventDefault();
      $(".save-template").html('Saving...')

      // Serialize form data
      const formData = new FormData($('#frm-example')[0]);
      console.log(formData);
    //   var data = table.$('input,select,textarea').serialize();
      // Submit form data via Ajax
      $.ajax({
        url : "{{route('subject-attendance-store-second')}}",
        method : "POST",
        data: formData,
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false,
         success : function (result){
            $(".save-template").html('Save')
             console.log(result);
                    toastr.success('Attendance Has Been Saved', 'Successful', {
                    timeOut: 5000
            })
        }
      });
      
   });      
});
    </script>

    <script>
        $(document).on('change','.subject_attendance_type',function (){
            let studentId = $(this).data('id');
            let subjectAttendanceType ='';
            if ($(this).is(':checked'))
            {
                subjectAttendanceType = $(this).val();
            }
            let subjectClass = $('.subject_class').val();
            let subjectSection = $('.subject_section').val();
            let subject = $('.subject').val();
            let subjectAttendanceDate = $('.subject_attendance_date').val();
            let notes = $('.note_'+studentId).val();
            $.ajax({
                url : "{{route('subject-attendance-store-second')}}",
                method : "POST",
                data : {
                    class : subjectClass,
                    section : subjectSection,
                    subject : subject,
                    student_id : studentId,
                    attendance_type : subjectAttendanceType,
                    date : subjectAttendanceDate,
                    notes : notes,
                },
                success : function (result){
                    toastr.success('Attendance Has Been Saved', 'Successful', {
                        timeOut: 5000
                    })
                }
            })
        })
    </script>
    @endpush
