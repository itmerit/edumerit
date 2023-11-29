@extends('backEnd.master')
@section('title')
@lang('reports.mark_sheet_report_student')
@endsection
@section('mainContent')
<style>
    th{
        border: 1px solid black;
        text-align: center;
    }
    td{
        text-align: center;
    }
    td.subject-name{
        text-align: left;
        padding-left: 10px !important;
    }
    table.marksheet{
        width: 100%;
        border: 1px solid var(--border_color) !important;
    }
    table.marksheet th{
        border: 1px solid var(--border_color) !important;
    }
    table.marksheet td{
        border: 1px solid var(--border_color) !important;
    }
    table.marksheet thead tr{
        border: 1px solid var(--border_color) !important;
    }
    table.marksheet tbody tr{
        border: 1px solid var(--border_color) !important;
    }

    .studentInfoTable{
        width: 100%;
        padding: 0px !important;
    }

    .studentInfoTable td{
        padding: 0px !important;
        text-align: left;
        padding-left: 15px !important;
    }
    h4{
        text-align: left !important;
    }
    hr{
        margin: 0px;
    }
    #grade_table th {
        border: 1px solid #dee2e6;
        border-top-style: solid;
        border-top-width: 1px;
        text-align: left;
        background: #351681;
        color: white;
        background: #f2f2f2;
        color: var(--base_color);
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        border-top: 0px;
        padding: 5px 4px;
        background: transparent;
        border-bottom: 1px solid rgba(130, 139, 178, 0.15) !important;
    }

    #grade_table td {
        color: #828bb2;
        padding: 0 7px;
        font-weight: 400;
        background-color: transparent;
        border-right: 0;
        border-left: 0;
        text-align: left !important;
        border-bottom: 1px solid rgba(130, 139, 178, 0.15) !important;
    }
    .single-report-admit table tr th {
        border: 0;
        border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
        text-align: left
    }
    .single-report-admit table thead tr th {
        border: 0 !important;
    }
    .single-report-admit table tbody tr:first-of-type td {
        border-top: 1px solid rgba(67, 89, 187, 0.15) !important;
    }
    .single-report-admit table tr td {
        vertical-align: middle;
        font-size: 12px;
        color: #828BB2;
        font-weight: 400;
        border: 0;
        border-bottom: 1px solid rgba(130, 139, 178, 0.15) !important;
        text-align: left
    }

    .single-report-admit table.summeryTable tbody tr:first-of-type td,
    .single-report-admit table.comment_table tbody tr:first-of-type td {
        border-top:0 !important;
    }


</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.mark_sheet_report_student') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('reports.reports')</a>
                <a href="#">@lang('exam.mark_sheet_report_student')</a>
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
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'mark_sheet_report_student', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('exam.select_exam') *" value="">@lang('exam.select_exam') *</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('exam') }}
                                </span>
                                @endif
                            </div>
                            <div class="col-lg-3 mt-30-md">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
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
                            <div class="col-lg-3 mt-30-md" id="select_student_div">
                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}" id="select_student" name="student">
                                    <option data-display="@lang('common.select_student') *" value="">@lang('common.select_student') *</option>
                                </select>
                                @if ($errors->has('student'))
                                <span class="text-danger invalid-select" role="alert">
                                    {{ $errors->first('student') }}
                                </span>
                                @endif
                            </div>

                            
                            <div class="col-lg-12 mt-20 text-right">
                                <button type="submit" class="primary-btn small fix-gr-bg">
                                    <span class="ti-search"></span>
                                    @lang('common.search')
                                </button>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
</section>


@if(isset($mark_sheet))
<style>
        *{
          margin: 0;
          padding: 0;
        }
        body{
          font-size: 12px;
          font-family: 'Poppins', sans-serif;
        }
        .student_marks_table{
          width: 100%;
          margin: 0px auto 0 auto;
          padding-left: 10px;
          padding-right: 5px;
          padding: 30px;
        }
        .text_center{
          text-align: center;
        }
        p{
          margin: 0;
          font-size: 12px;
          text-transform: capitalize;
        }
        ul{
          margin: 0;
          padding: 0;
        }
        li{
          list-style: none;
        }
      th{
        border: 1px solid var(--border_color);
        text-transform: capitalize;
        text-align: center;
        padding: .5rem;
        white-space: nowrap;
      }
      thead{
        font-weight:bold;
        text-align:left;
        color: var(--base_color);
        font-size: 10px
      }
      .custom_table{
        width: 100%;
      }
      table.custom_table thead th {
        padding-right: 0;
        padding-left: 0;
      }
     
      table.custom_table thead tr > th {
        border: 0;
        padding: 0;
    }
    table.custom_table thead tr th .fees_title{
      font-size: 12px;
      font-weight: 600;
      border-top: 1px solid #726E6D;
      padding-top: 10px;
      margin-top: 10px;
    }
    .border-top{
      border-top: 0 !important;
    }
      .custom_table th ul li p {
        margin-bottom: 10px;
        font-weight: 500;
        font-size: 14px;
    }
    tbody td{
      padding: 0.8rem;
    }
    table{
      border-spacing: 10px;
      width: 65%;
      margin: auto;
    }
    .fees_pay{
      text-align: center;
    }
    .border-0{
      border: 0 !important;
    }
    .copy_collect{
      text-align: center;
      font-weight: 500;
      color: #000;
    }
    
    .copyies_text{
      display: flex;
      justify-content: space-between;
      margin: 30px 0;
    }
    .copyies_text li{
      text-transform: capitalize;
      color: #000;
      font-weight: 500;
      border-top: 1px dashed #ddd;
    }
    .text_left{
        text-align: left;
    }
    .student_info li{
        display: flex;
    }
    .student_info li p{
        font-size: 12px;
        font-weight: 400;
        color: #828bb2;
    }
    .student_info li p.bold_text{
        font-weight: 600;
        color: var(--base_color);
    }
    .info_details{
        display: flex;
        flex-wrap: wrap;
        margin-top: 30px;
        margin-bottom: 30px;
    }
    .info_details li > p{
        flex-basis: 20%;
    }
    .info_details li{
        display: flex;
        flex-basis: 50%;
    }
    .school_name{
        text-align: center;
    }
    .numbered_table_row{
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
        align-items: center;
    }
    .numbered_table_row thead{
        border: 1px solid var(--base_color)
    }
    .numbered_table_row h3{
        font-size: 24px;
        text-transform: uppercase;
        margin-top: 15px;
        font-weight: 500;
        display: inline-block;
        border-bottom: 2px solid var(--base_color);
    }
    .ingle-report-admit .numbered_table_row td{
       border: 1px solid var(--border_color);
       padding: .4rem;
       font-weight: 400;
       color: var(--base_color);
    }
    
    .table#grade_table_new th {
        border: 1px solid #726E6D !important;
        padding: .6rem !important;
        font-weight: 600;
        color: var(--base_color);
        font-size: 10px;
    }
    td.border-top.border_left_hide {
        border-left: 0;
        text-align: left;
        font-weight: 600;
    }
    .devide_td{
        padding: 0;
    }
    .devide_td p{
        border-bottom: 1px solid var(--base_color);
    }
    .ssc_text{
        font-size: 20px;
        font-weight: 500;
        color: var(--base_color);
        margin-bottom: 20px;
    }
    table#grade_table_new td {
    padding: 0 !important;
    font-size: 8px;
    }
    table#grade_table_new {
        border-bottom: 1px solid #726E6D !important ;
    }
   
    .marks_wrap_area {
        display: flex;
        align-items: center;
    }
    .numbered_table_row {
        display: flex;
        justify-content: center;
        margin-top: 40px;
        align-items: center;
    }
    tbody.mark_sheet_body tr:last-child {
        border-bottom: 1px solid #c1c6d9;
    }
    tbody.mark_sheet_body td{
        padding: 8px 4px;
    }

    .spacing tr th{
        padding: 3px 10px !important;
        vertical-align: middle;
    }

    .spacing tr td{
        padding: 3px 40px !important;
        vertical-align: middle;
    }
    /* new  style  */
    .single-report-admit{}
    .single-report-admit .student_marks_table{
        background: -webkit-linear-gradient(
        90deg
        , #d8e6ff 0%, #ecd0f4 100%);
            background: -moz-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: -o-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: linear-gradient(
        90deg
        , #d8e6ff 0%, #ecd0f4 100%);
    }

        @if(resultPrintStatus('vertical_boarder'))
        .single-report-admit table tr td {
            border: 1px solid rgba(130, 139, 178, 0.15) !important;
        }
        .border_table thead tr th {
            border: 1px solid #000;
        }
        .gray_header_table thead tr:first-child th {
            border: 1px solid #000 !important;
        }
        .gray_header_table thead th{
            padding-left: 10px !important;
        }
    @endif
</style>
<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row mt-40">
            <div class="col-lg-4 no-gutters">
                <div class="main-title">
                    <h3 class="mb-30">@lang('exam.mark_sheet_report')</h3>
                </div>
            </div>
            <div class="col-lg-8 pull-right">
                <a href="{{route('mark_sheet_report_print', [$input['exam_id'], $input['class_id'], $input['section_id'], $input['student_id']])}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> @lang('common.print')</a>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    <div class="row justify-content-center">
                        <div class="col-lg-12">
                            <div class="single-report-admit">
                                <div class="card">
                                    <div class="card-header">
                                            <div class="d-flex d-flex align-items-center">
                                                    <div class="col-lg-2">
                                                        <img class="logo-img" src="{{ generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                                    </div>
                                                    <div class="col-lg-6 ml-30">
                                                        <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                                        <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                        <p class="text-white mb-0">
                                                            @lang('common.email'): <span class="text-lowercase">{{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}}</span>,
                                                            @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}</p>
                                                    </div>
                                                    <div class="offset-2">
        
                                                    </div>
                                                </div>
                                        <div>
                                            <img class="report-admit-img"  src="{{ file_exists(@$studentDetails->studentDetail->student_photo) ? asset($studentDetails->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }}" width="100" height="100" alt="{{asset($studentDetails->studentDetail->student_photo)}}">
                                        </div>
                                    </div>
                                    <div class="student_marks_table">
                                            <table class="custom_table">
                                                <thead>
                                                    <tr class="numbered_table_row" >
                                                        <td class="border-0" >
                                                            <div class="school_mark">
                                                                {{-- <h3>@lang('lang.academic_transcript')</h3> --}}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th colspan="1" class="text_left">
                                                            <div class="main-title">
                                                                <h2 class="mb-20 text-capitalize">
                                                                    {{$student_detail->studentDetail->full_name}}
                                                                </h2>
                                                            </div>
                                                            <div class="marks_wrap_area d-block">
                                                            <div class="row">
                                                               <div class="col-lg-8">
                                                                <div class="d-flex flex-wrap" style="grid-gap: 30px;">
                                                                    <ul class="student_info">
                                                                        <li>
                                                                            <p>
                                                                                @lang('common.class')
                                                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                                            </p> &nbsp; 
                                                                            <p class="bold_text">   
                                                                            : {{$student_detail->class->class_name}}
                                                                            </p>
                                                                        </li>
                                                                        <li>
                                                                            <p>
                                                                                @lang('common.section')
                                                                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                                                            </p> &nbsp; 
                                                                            <p class="bold_text"> 
                                                                            : {{$student_detail->section->section_name}}
                                                                            </p>
                                                                        </li>
                                                                        <li>
                                                                            <p>
                                                                                @lang('student.admission_no') &nbsp;  &nbsp; 
                                                                            </p> &nbsp; 
                                                                            <p class="bold_text">
                                                                            : {{$student_detail->studentDetail->admission_no}}
                                                                            </p>
                                                                        </li>
                                                                    </ul>
                                                                    <ul class="student_info pull-right">
                                                                            <li>
                                                                                <p>
                                                                                   @lang('student.roll_no')&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                                                                </p>&nbsp;
                                                                                <p class="bold_text">
                                                                                    : {{$student_detail->roll_no}}
                                                                                </p>
                                                                            </li>
                                                                            <li>
                                                                                <p>
                                                                                    @lang('exam.exam')&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                                                                                </p>&nbsp; 
                                                                                <p class="bold_text">  
                                                                                        : {{$exam_details->title}} 
                                                                                </p>
                                                                            </li>
                                                                            <li>
                                                                                <p>
                                                                                    @lang('common.date_of_birth')
                                                                                </p>&nbsp; 
                                                                                <p class="bold_text"> 
                                                                                        : {{$student_detail->studentDetail->date_of_birth != ""? dateConvert($student_detail->studentDetail->date_of_birth):''}}
                                                                                    
                                                                                </p>
                                                                            </li>
                                                                    </ul>
                                                                </div>
                                                               </div>
                                                                <div class="col-lg-4 text-black"> 
                                                                    @if(@$grades)
                                                                        <table class="table" id="grade_table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>@lang('reports.staring')</th>
                                                                                    <th>@lang('reports.ending')</th>
                                                                                    <th>@lang('exam.gpa')</th>
                                                                                    <th>@lang('exam.grade')</th>
                                                                                    <th>@lang('homework.evaluation')</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($grades as $grade_d)
                                                                                    <tr>
                                                                                        <td>{{$grade_d->percent_from}}</td>
                                                                                        <td>{{$grade_d->percent_upto}}</td>
                                                                                        <td>{{$grade_d->gpa}}</td>
                                                                                        <td>{{$grade_d->grade_name}}</td>
                                                                                        <td class="text-left">{{$grade_d->description}}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    @endif 
                                                                </div>
                                                            </div>
                                                                {{--end sm_marks_grades --}}
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="main-title">
                                                <h2 class="mb-20 text-capitalize">
                                                    @lang('exam.mark_sheet_report')
                                                </h2>
                                            </div>

                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <!-- first header  -->
                                                        <th >@lang('academics.subject_name')</th>
                                                        <th colspan="5">{{$exam_details->title}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th>@lang('exam.total_mark')</th>
                                                        <th>@lang('exam.highest_marks')</th>
                                                        <th>@lang('exam.obtained_marks')</th>
                                                        <th>@lang('exam.letter_grade')</th>
                                                        <th>@lang('reports.remarks')</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="mark_sheet_body">
                                                    @php
                                                        $main_subject_total_gpa=0;
                                                        $Optional_subject_count=$subjects->count();
                                                        $total_mark=0;
                                                        $persentage_mark=0;
                                                        $sum_gpa= 0;  
                                                        $resultCount=1; 
                                                        $subject_count=1; 
                                                        $tota_grade_point=0; 
                                                        $this_student_failed=0; 
                                                        $count=1;
                                                        $temp_grade=[];
                                                        $student_full_mark=0;

                                                        function differences($array1, $array2){
                                                            return array_merge(array_diff($array1,$array2),array_diff($array2,$array1));
                                                        }
                                                    @endphp
                                                    @foreach($mark_sheet as $key=>$data)
                                                    @php
                                                        $temp_grade[]=$data->total_gpa_grade;
                                                        $student_full_mark+=@$data->total_marks;
                                                    @endphp
                                                    <tr>
                                                        <td style="padding-left: 15px;">
                                                            <p>
                                                                {{@$data->subject->subject_name}}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                {{@subjectFullMark($exam_details->id, $data->subject->id , $class_id, $section_id)}}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>{{@subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id)}}</p>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                {{@$data->total_marks}}
                                                            </p>
                                                            @php
                                                                $total_mark+=@$data->total_marks;
                                                            @endphp                                                            
                                                        </td>
                                                        <td>
                                                            <p>
                                                                {{@$data->total_gpa_grade}}
                                                                @php
                                                                    $result = markGpa(@subjectPercentageMark(@$data->total_marks , @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                                                    $main_subject_total_gpa += $result->gpa;
                                                                @endphp
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                {{@$data->teacher_remarks}}
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    @php
                                                        $remainSubjects = differences($mark_sheet->pluck('subject_id')->toArray(), $subjects->pluck('subject_id')->toArray());
                                                    @endphp
                                                    @if(count($remainSubjects) > 0)
                                                        @foreach($remainSubjects as $reaminSubject)
                                                            @php
                                                                $subject = App\SmSubject::find($reaminSubject);
                                                            @endphp
                                                            <tr>
                                                                <td style="padding-left: 15px;">{{$subject->subject_name}}</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>@lang('exam.mark_not_stored')</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>

                                            <div class="col-md-4 offset-md-3">
                                                <table class="table summeryTable">
                                                    <tbody class="spacing">
                                                        <tr>
                                                            <td>@lang('student.attendance')</td>
                                                            <td class="nowrap"> {{@$student_attendance}} @lang('common.of') {{@$total_class_days}}</td>
                                                            <td>@lang('exam.total_mark')</td>
                                                            <td>{{@$total_mark}}</td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <td class="nowrap">@lang('exam.average_mark')</td>
                                                            <td>
                                                                @php
                                                                    $average_mark=$student_full_mark/$Optional_subject_count;
                                                                @endphp
                                                                {{number_format(@$average_mark, 2, '.', '')}}
                                                            </td>
                                                            <td>@lang('exam.gpa')</td>
                                                            <td>
                                                                @php
                                                                    $total_gpa=$main_subject_total_gpa/$Optional_subject_count;
                                                                @endphp
                                                                <p>
                                                                    {{number_format(@$total_gpa, 2, '.', '')}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>@lang('exam.grade')</td>
                                                            <td>
                                                                @php
                                                                    if(in_array($failgpaname->grade_name,$temp_grade)){
                                                                        echo $failgpaname->grade_name;
                                                                    }else{
                                                                        $grade_details= App\SmResultStore::remarks($total_gpa);
                                                                    }
                                                                @endphp
                                                                <p>
                                                                    {{@$grade_details->grade_name}}
                                                                </p>
                                                            </td>  
                                                            <td>@lang('homework.evaluation')</td>
                                                                <td class="nowrap">
                                                                    @php
                                                                        if(in_array($failgpaname->grade_name,$temp_grade)){
                                                                                echo $failgpaname->description;
                                                                            }else{
                                                                                $grade= App\SmResultStore::remarks($total_gpa);
                                                                            }
                                                                    @endphp
                                                                    <p>{{@$grade->description}}</p>
                                                                </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" style="text-align: center !important;">
                                                                @lang('exam.position')
                                                            </td>
                                                            <td colspan="2" style="text-align: center !important;">{{getStudentMeritPosition($class_id, $section_id, $exam_type_id, $student_detail->id)}}</td>

                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            @if(isset($exam_content))
                                            <table style="width:100%" class="border-0 comment_table">
                                                <tbody>
                                                    <tr> 
                                                        <td class="border-0">
                                                            <p class="result-date" style="text-align:left; float:left; display:inline-block; margin-top:50px; padding-left: 0;">
                                                                @lang('exam.date_of_publication_of_result') : 
                                                                <strong> 
                                                                    {{dateConvert(@$exam_content->publish_date)}}
                                                                </strong>
                                                            </p>
                                                        </td>
                                                        <td class="border-0"> 
                                                            <div class="text-right d-flex flex-column justify-content-end">
                                                            <div class="thumb text-right">
                                                                @if (@$exam_content->file)
                                                                    <img src="{{@$exam_content->file}}" width="100px">
                                                                @endif
                                                            </div>
                                                                <p style="text-align:right; float:right; display:inline-block; margin-top:5px;">
                                                                ({{@$exam_content->title}})
                                                                </p> 
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
