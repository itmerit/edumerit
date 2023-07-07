@extends('backEnd.master')
@section('title')
    @lang('reports.mark_sheet_report_student')
@endsection
@section('mainContent')
    <style>
        th {
            border: 1px solid black;
            text-align: center;
        }

        td {
            text-align: center;
        }

        td.subject-name {
            text-align: left;
            padding-left: 10px !important;
        }

        table.marksheet {
            width: 100%;
            border: 1px solid var(--border_color) !important;
        }

        table.marksheet th {
            border: 1px solid var(--border_color) !important;
        }

        table.marksheet td {
            border: 1px solid var(--border_color) !important;
        }

        table.marksheet thead tr {
            border: 1px solid var(--border_color) !important;
        }

        table.marksheet tbody tr {
            border: 1px solid var(--border_color) !important;
        }

        .studentInfoTable {
            width: 100%;
            padding: 0px !important;
        }

        .studentInfoTable td {
            padding: 0px !important;
            text-align: left;
            padding-left: 15px !important;
        }

        h4 {
            text-align: left !important;
        }

        hr {
            margin: 0px;
        }

        #grade_table th {
            border: 1px solid black;
            text-align: center;
            background: #351681;
            color: white;
        }

        #grade_table td {
            color: black;
            text-align: center !important;
            border: 1px solid black;
        }

        .single-report-admit table tr:last-child td {
            border-bottom: 0 !important ;
        }

        .single-report-admit table tr td {
            border-color: #dee2e6 !important;
        }

        .custom_table tbody tr th{
            border-color: #dee2e6 !important;
        }
        .spacing tr th{
            padding: 3px 10px !important;
            vertical-align: middle;
            border: 1px solid #dee2e6 !important;
        }

        .spacing tr td{
            padding: 0px 40px !important;
            vertical-align: middle;
        }

    </style>
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('reports.mark_sheet_report_student') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('reports.reports')</a>
                    <a href="#">@lang('reports.mark_sheet_report_student')</a>
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
                        @if(moduleStatusCheck('University'))
                            <div class="col-lg-12">
                                <div class="row">
                                    @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                    ['required' => 
                                        ['USN', 'UD', 'UA', 'US', 'USL'],'hide'=> ['USUB']
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

                                    <div class="col-lg-3 mt-30" id="select_un_student_div">
                                        {{ Form::select('student_id',[""=>__('common.select_student').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('student_id') ? ' is-invalid' : ''), 'id'=>'select_un_student']) }}

                                        <div class="pull-right loader loader_style" id="select_un_student_loader">
                                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                        </div>
                                        @if ($errors->has('student_id'))
                                            <span class="text-danger custom-error-message" role="alert">
                                                {{ @$errors->first('student_id') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-3 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="">{{ __('exam.exam') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}"
                                        name="exam">
                                    <option data-display="@lang('reports.select_exam') *" value="">@lang('reports.select_exam')
                                        *
                                    </option>
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
                            <div class="col-lg-3 mt-30-md md_mb_20">
                                <label class="primary_input_label" for="">{{ __('common.class') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}"
                                        id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')
                                        *
                                    </option>
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
                            <div class="col-lg-3 mt-30-md md_mb_20" id="select_section_div">
                                <label class="primary_input_label" for="">{{ __('common.section') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }}" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section') *" value="">@lang('common.select_section') *</option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_section_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @if($errors->has('section'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('section') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 mt-30-md md_mb_20" id="select_student_div">
                                <label class="primary_input_label" for="">{{ __('common.student') }}<span class="text-danger"> *</span></label>
                                <select class="primary_select form-control{{ $errors->has('student') ? ' is-invalid' : '' }}"
                                        id="select_student" name="student">
                                    <option data-display="@lang('reports.select_student') *"
                                            value="">@lang('reports.select_student') *
                                    </option>
                                </select>
                                <div class="pull-right loader loader_style" id="select_student_loader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @if ($errors->has('student'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('student') }}
                                    </span>
                                @endif
                            </div>
                        @endif

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
            * {
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 12px;
                font-family: 'Poppins', sans-serif;
            }

            .student_marks_table {
                width: 100%;
                margin: 0;
                padding: 30px;
            }

            .text_center {
                text-align: center;
            }

            p {
                margin: 0;
                font-size: 12px;
                text-transform: capitalize;
            }

            ul {
                margin: 0;
                padding: 0;
            }

            li {
                list-style: none;
            }

            td {
                border: 1px solid var(--border_color);
                padding: .3rem;
                text-align: center;
            }

            th {
                border: 1px solid var(--border_color);
                text-transform: capitalize;
                text-align: center;
                padding: .5rem;
                white-space: nowrap;
            }

            thead {
                font-weight: bold;
                text-align: center;
                color: var(--base_color);
                font-size: 10px
            }

            .custom_table {
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

            /* tr:last-child td {
                border: 0 !important;
            }
            tr:nth-last-child(2) td {
                border: 0 !important;
            }
            tr:nth-last-child(3) td {
                border: 0 !important;
            } */

            table.custom_table thead tr th .fees_title {
                font-size: 12px;
                font-weight: 600;
                border-top: 1px solid #726E6D;
                padding-top: 10px;
                margin-top: 10px;
            }

            .border-top {
                border-top: 0 !important;
            }

            .border_full {
                border: 1px solid black !important;
            }

            .border_bottom {
                border-bottom: 1px solid black !important;
            }

            .border_top {
                border-top: 1px solid black !important;
            }

            .custom_table th ul li {
            }

            .custom_table th ul li p {
                margin-bottom: 10px;
                font-weight: 500;
                font-size: 14px;
            }

            /* tbody td p{
              text-align: right;
            } */
            tbody td {
                padding: 0.8rem;
            }

            table {
                border-spacing: 10px;
                width: 65%;
                margin: auto;
            }

            .fees_pay {
                text-align: center;
            }

            .border-0 {
                border: 0 !important;
            }

            .copy_collect {
                text-align: center;
                font-weight: 500;
                color: #000;
            }

            .copyies_text {
                display: flex;
                justify-content: space-between;
                margin: 30px 0;
            }

            .copyies_text li {
                text-transform: capitalize;
                color: #000;
                font-weight: 500;
                border-top: 1px dashed #ddd;
            }

            .text_left {
                text-align: left;
            }

            .italic_text {
            }

            .student_info {

            }

            .student_info li {
                display: flex;
            }

            .info_details {
                display: flex;
                flex-wrap: wrap;
                margin-top: 30px;
                margin-bottom: 30px;
            }

            .info_details li > p {
                flex-basis: 20%;
            }

            .info_details li {
                display: flex;
                flex-basis: 50%;
            }

            .school_name {
                text-align: center;
            }

            .numbered_table_row {
                display: flex;
                justify-content: space-between;
                margin-top: 40px;
                align-items: center;
            }

            .numbered_table_row thead {
                border: 1px solid var(--base_color)
            }

            .numbered_table_row h3 {
                font-size: 24px;
                text-transform: uppercase;
                margin-top: 15px;
                font-weight: 500;
                display: inline-block;
                border-bottom: 2px solid var(--base_color);
            }

            .ingle-report-admit .numbered_table_row td {
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

            .devide_td {
                padding: 0;
            }

            .devide_td p {
                border-bottom: 1px solid var(--base_color);
            }

            .ssc_text {
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
                border-bottom: 1px solid #726E6D !important;
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
            .single-report-admit table tbody tr:last-of-type td {
                border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
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

            .profile_100{
                width: 100px;
                height: 100px;
                background-size: cover;
                background-position: center center;
                border-radius: 5px;
                background-repeat: no-repeat;
            }
        </style>
        @if(resultPrintStatus('vertical_boarder'))
            <style>
                .single-report-admit table tr td {
                    border: 1px solid rgba(130, 139, 178, 0.15) !important;
                }
                .single-report-admit table thead tr th{
                    border: 1px solid rgba(130, 139, 178, 0.15) !important;
                }

                .gray_header_table thead tr:first-child th {
                    border: 1px solid rgba(130, 139, 178, 0.15) !important;
                }
                .gray_header_table thead th{
                    padding-left: 10px !important;
                }
                .single-report-admit table tr td{
                    padding-left: 8px !important;
                }
                .single-report-admit table tr th{
                    border: 1px solid rgba(130, 139, 178, 0.15) !important;
                    padding: 8px !important;
                }
                .custom_table thead th{
                    text-align: center !important;
                }
                .custom_table tbody td{
                    text-align: center !important;
                }
                .custom_table thead tr:first-child th:first-child {
                    text-align: left !important;
                }
                .custom_table thead tr:first-child td:first-child {
                    text-align: center !important;
                }
                .custom_table tbody tr td:last-child{
                    text-align: left !important;
                }
            </style>
        @endif
        @if(moduleStatusCheck('University'))
            @includeIf('university::exam.mark_sheet_report')
        @else
            <section class="student-details">
                <div class="container-fluid p-0">
                    <div class="row mt-40">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30">@lang('reports.mark_sheet_report')</h3>
                            </div>
                        </div>
                        <div class="col-lg-8 pull-right">
                            <a href="{{route('mark_sheet_report_print', [$input['exam_id'], $input['class_id'], $input['section_id'], $input['student_id']])}}"
                               class="primary-btn small fix-gr-bg pull-right" target="_blank">
                                <i class="ti-printer"> </i>
                                @lang('reports.print')
                            </a>
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
                                                    <div class="d-flex">
                                                        <div class=" col-lg-2">
                                                            <img class="logo-img" src="{{ generalSetting()->logo }}" alt="{{generalSetting()->school_name }}">
                                                        </div>
                                                        <div class="col-lg-8 text-center">
                                                            <h3 class="text-white" style="font-size: 30px;margin-bottom: 0px;"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                                            <p class="text-white mb-0" style="font-size: 16px;">  {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                            <p class="text-white mb-0" style="font-size: 16px;">
                                                                @lang('common.email'): <span class="text-lowercase">{{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}}</span>,
                                                                @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}} </p>
                                                        </div>

                                                    </div>
                                                    <div class="report-admit-img profile_100" style="background-image: url({{ file_exists(@$studentDetails->studentDetail->student_photo) ? asset($studentDetails->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }})"></div>

                                                </div>
                                                {{--Start  Result Table --}}
                                                <div class="student_marks_table">
                                                    <table class="custom_table" >
                                                        <thead>
                                                        <tr>
                                                            <th colspan="1" class="text_left" style="border: 0px !important;">
                                                                <div class="main-title">
                                                                    <h2 class="mb-20">
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
                                                                                            @lang('common.academic_year')
                                                                                        </p>
                                                                                        <p>
                                                                                            <strong>
                                                                                                : {{ @$student_detail->academic->year }}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>
                                                                                    <li>
                                                                                        <p>
                                                                                            @lang('common.class')
                                                                                        </p>
                                                                                        <p class="italic_text">
                                                                                            <strong>
                                                                                                : {{$student_detail->class->class_name}}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>
                                                                                    <li>
                                                                                        <p>
                                                                                            @lang('common.section')
                                                                                        </p>
                                                                                        <p class="italic_text">
                                                                                            <strong>
                                                                                                : {{$student_detail->section->section_name}}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>
                                                                                    <li>
                                                                                        <p>
                                                                                            @lang('student.admission_no')
                                                                                        </p>
                                                                                        <p class="italic_text">
                                                                                            <strong>
                                                                                                : {{@$student_detail->student->admission_no}}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>

                                                                                    <li>
                                                                                        <p>
                                                                                            @lang('student.roll_no')
                                                                                        </p>
                                                                                        <p>
                                                                                            <strong>
                                                                                                : {{$student_detail->studentDetail->roll_no}}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>

                                                                                    <li>
                                                                                        <p>
                                                                                            @lang('common.date_of_birth')
                                                                                        </p>
                                                                                        <p>
                                                                                            <strong>
                                                                                                : {{$student_detail->studentDetail->date_of_birth != ""? dateConvert($student_detail->studentDetail->date_of_birth):''}}
                                                                                            </strong>
                                                                                        </p>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 text-black">
                                                                            @php
                                                                                $generalsettingsResultType = generalSetting()->result_type;
                                                                            @endphp
                                                                            @if(@$grades)
                                                                                <table class="table " id="grade_table">
                                                                                    <thead>
                                                                                    <tr>
                                                                                        <th>@lang('reports.staring')</th>
                                                                                        <th>@lang('reports.ending')</th>
                                                                                        @if (@$generalsettingsResultType != 'mark')
                                                                                            <th>@lang('exam.gpa')</th>
                                                                                            <th>@lang('exam.grade')</th>
                                                                                        @endif
                                                                                        <th>@lang('homework.evalution')</th>
                                                                                    </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                    @foreach($grades as $grade_d)
                                                                                        <tr>
                                                                                            <td>{{$grade_d->percent_from}}</td>
                                                                                            <td>{{$grade_d->percent_upto}}</td>
                                                                                            @if (@$generalsettingsResultType != 'mark')
                                                                                                <td>{{$grade_d->gpa}}</td>
                                                                                                <td>{{$grade_d->grade_name}}</td>
                                                                                            @endif
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
                                                    <div class="student_name_highlight" style="text-align: center; margin-bottom: 20px;">
                                                        <h3>{{$exam_details->title}}</h3>
                                                        <h4 style="text-align: center !important;"> <span style="border-bottom: 3px double;">@lang('reports.mark_sheet')</span></h4>
                                                    </div>



                                                    <table class="custom_table">
                                                        <thead>
                                                        <!--                                                            <tr>

                                                                <th colspan="5">{{$exam_details->title}}</th>
                                                            </tr>-->
                                                        <tr>
                                                            <th>@lang('reports.subject_name')</th>
                                                            <th>@lang('exam.total_mark')</th>
                                                            <th>@lang('reports.highest_marks')</th>
                                                            <th>@lang('reports.obtained_marks')</th>
                                                            @if (@$generalsettingsResultType != 'mark')
                                                                <th>@lang('reports.letter_grade')</th>
                                                            @endif
                                                            <th>@lang('reports.remarks')</th>
                                                            @if (@$generalsettingsResultType == 'mark')
                                                                <th>@lang('homework.evaluation')</th>
                                                                <th>@lang('exam.pass_fail')</th>
                                                            @endif

                                                        </tr>
                                                        </thead>
                                                        <tbody>

                                                        @php
                                                            $optional_countable_gpa = 0;
                                                            $main_subject_total_gpa=0;
                                                            $Optional_subject_count=0;
                                                                if($optional_subject!=''){
                                                                    $Optional_subject_count=$subjects->count()-1;
                                                                }else{
                                                                    $Optional_subject_count=$subjects->count();
                                                                }
                                                            $sum_gpa= 0;
                                                            $resultCount=1;
                                                            $subject_count=1;
                                                            $tota_grade_point=0;
                                                            $this_student_failed=0;
                                                            $count=1;
                                                            $total_mark=0;
                                                            $temp_grade=[];
                                                        @endphp
                                                        @foreach($mark_sheet as $data)
                                                            @php
                                                                $temp_grade[]=$data->total_gpa_grade;
                                                                if ($data->subject_id==$optional_subject) {
                                                                    continue;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <th>
                                                                    {{$data->subject->subject_name}}
                                                                </th>
                                                                <td>
                                                                    <p>
                                                                        @if (@$generalsettingsResultType == 'mark')
                                                                            {{subject100PercentMark()}}
                                                                        @else
                                                                            {{@subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id )}}
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                                <td>
                                                                    <p>
                                                                        @if (@$generalsettingsResultType == 'mark')
                                                                            {{subjectPercentageMark(@subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id), @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id))}}
                                                                        @else
                                                                            {{@subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id)}}
                                                                        @endif
                                                                    </p>
                                                                </td>
                                                                <td>

                                                                    <p>
                                                                        @if (@$generalsettingsResultType == 'mark')
                                                                            {{@singleSubjectMark($data->student_record_id,$data->subject_id,$data->exam_type_id)[0]}}
                                                                        @else
                                                                            {{@$data->total_marks}}
                                                                        @endif

                                                                        @php
                                                                            if(@$generalsettingsResultType == 'mark'){
                                                                                $total_mark+=subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                                                            }else{
                                                                                $total_mark+=@$data->total_marks;
                                                                            }
                                                                        @endphp
                                                                    </p>
                                                                </td>
                                                                @if (@$generalsettingsResultType != 'mark')
                                                                    <td>
                                                                        <p>
                                                                            @php
                                                                                $result = markGpa(@subjectPercentageMark(@$data->total_marks , @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                                                                $main_subject_total_gpa += $result->gpa;
                                                                            @endphp
                                                                            {{@$data->total_gpa_grade}}
                                                                        </p>
                                                                    </td>
                                                                @endif
                                                                <td>
                                                                    <p>
                                                                        {{@$data->teacher_remarks}}
                                                                    </p>
                                                                </td>
                                                                @if (@$generalsettingsResultType == 'mark')

                                                                    <td>
                                                                        <p>
                                                                            @php
                                                                                $evaluation= markGpa(subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                                                            @endphp
                                                                            {{@$evaluation->description}}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <p>
                                                                            @php
                                                                                $totalMark = subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                                                                $passMark = $data->subject->pass_mark;
                                                                            @endphp
                                                                            @if ($passMark <= $totalMark)
                                                                                @lang('exam.pass')
                                                                                    @else
                                                                                        @lang('exam.fail')
                                                                                    @endif
                                                                        </p>
                                                                    </td>
                                                                @endif

                                                                @php
                                                                    $count++
                                                                @endphp
                                                            </tr>
                                                        @endforeach
                                                        @if(@$optional_subject_setup->gpa_above)
                                                            <tr>
                                                                <td colspan="7" class="border-top text-center pl-3" style="text-align: center !important;">
                                                                    <strong>@lang('reports.additional_subject')</strong>
                                                                </td>
                                                            </tr>
                                                            @foreach($mark_sheet as $data)
                                                                @php
                                                                    if ($data->subject_id!=$optional_subject) {
                                                                        continue;
                                                                    }
                                                                @endphp
                                                                <tr>
                                                                    <th>
                                                                        {{$data->subject->subject_name}}
                                                                    </th>
                                                                    <td>
                                                                        <p>
                                                                            {{@subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id )}}
                                                                        </p>
                                                                    </td>
                                                                    <td>
                                                                        <p>{{@subjectHighestMark($exam_type_id, $data->subject->id, $class_id, $section_id)}}</p>
                                                                    </td>
                                                                    <td>
                                                                        <p>
                                                                            {{@$data->total_marks}}
                                                                            @php
                                                                                if(@$generalsettingsResultType == 'mark'){
                                                                                    $total_mark+=subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                                                                }else{
                                                                                    $total_mark+=@$data->total_marks;
                                                                                }
                                                                            @endphp
                                                                        </p>
                                                                    </td>
                                                                    @if (@$generalsettingsResultType != 'mark')
                                                                    <td>
                                                                        <p>
                                                                            {{@$data->total_gpa_grade}}
                                                                        </p>
                                                                    </td>
                                                                    @endif
                                                                    <td>
                                                                        <span>
                                                                            @php
                                                                                if($data->total_gpa_point > @$optional_subject_setup->gpa_above){
                                                                                    $optional_countable_gpa= $data->total_gpa_point - @$optional_subject_setup->gpa_above;
                                                                                }else{
                                                                                    $optional_countable_gpa=0;
                                                                                }
                                                                            @endphp
                                                                        </span>
                                                                        <p>
                                                                            {{@$data->teacher_remarks}}
                                                                        </p>
                                                                    </td>
                                                                    @if (@$generalsettingsResultType == 'mark')
                                                                        <td>
                                                                            <p>
                                                                                @php
                                                                                    $evaluation= markGpa(subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id)));
                                                                                @endphp
                                                                                {{@$evaluation->description}}
                                                                            </p>
                                                                        </td>
                                                                        <td>
                                                                            <p>
                                                                                @php
                                                                                    $totalMark = subjectPercentageMark(@$data->total_marks, @subjectFullMark($exam_details->id, $data->subject->id, $class_id, $section_id));
                                                                                    $passMark = $data->subject->pass_mark;
                                                                                @endphp
                                                                                @if ($passMark <= $totalMark)
                                                                                    @lang('exam.pass')
                                                                                @else
                                                                                    @lang('exam.fail')
                                                                                @endif
                                                                            </p>
                                                                        </td>
                                                                    @endif
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                    <div class="col-md-4 offset-md-4">
                                                        <table class="table @if(resultPrintStatus('vertical_boarder')) mt-5 @endif">
                                                            <tbody class="spacing">
                                                            <tr>
                                                                <td>@lang('reports.attendance')</td>
                                                                @if(isset($exam_content))
                                                                    <td class="nowrap">
                                                                        <p>{{@$student_attendance}} @lang('reports.of') {{@$total_class_days}}</p>
                                                                    </td>
                                                                @else
                                                                    <td class="nowrap">
                                                                        <p>@lang('reports.no_data_found')</p>
                                                                    </td>
                                                                @endif
                                                                <td>@lang('exam.total_mark')</td>
                                                                <td>{{@$total_mark}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="nowrap {{$generalsettingsResultType == 'mark' ? 'text-center' : ''}}" colspan="{{$generalsettingsResultType == 'mark' ? '2' : ''}}">@lang('reports.average_mark')</td>
                                                                <td colspan="{{$generalsettingsResultType == 'mark' ? '2' : ''}}">
                                                                    @php
                                                                        $average_mark=$total_mark/($Optional_subject_count);
                                                                    @endphp
                                                                    {{number_format(@$average_mark, 2, '.', '')}}
                                                                </td>

                                                                @if (@$generalsettingsResultType != 'mark')
                                                                    <td class="nowrap">@lang('reports.gpa_above') ( {{@$optional_subject_setup->gpa_above}} )</td>
                                                                    <td>
                                                                        <p>
                                                                            {{$optional_countable_gpa}}
                                                                        </p>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @if(@$generalsettingsResultType != 'mark')
                                                                <tr>
                                                                    <td class="nowrap">@lang('reports.without_optional')</td>
                                                                    <td>
                                                                        @php
                                                                            $without_optional=$main_subject_total_gpa/$Optional_subject_count;
                                                                        @endphp
                                                                        {{number_format($without_optional, 2,'.','')}}
                                                                    </td>
                                                                    <td>@lang('exam.gpa')</td>
                                                                    <td>
                                                                        @php
                                                                            $final_result = ($main_subject_total_gpa + $optional_countable_gpa) /$Optional_subject_count;
                                                                            if($final_result >= $maxGrade){
                                                                                echo number_format($maxGrade, 2,'.','');
                                                                            } else {
                                                                                echo number_format($final_result, 2,'.','');
                                                                            }
                                                                        @endphp
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>@lang('exam.grade')</td>
                                                                    <td>
                                                                        @php
                                                                            if(in_array($failgpaname->grade_name,$temp_grade)){
                                                                                echo $failgpaname->grade_name;
                                                                            }else{
                                                                                if($final_result >= $maxGrade){
                                                                                    $grade_details= App\SmResultStore::remarks($maxGrade);
                                                                                } else {
                                                                                    $grade_details= App\SmResultStore::remarks($final_result);
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        {{@$grade_details->grade_name}}
                                                                    </td>
                                                                    <td>@lang('homework.evaluation')</td>
                                                                    <td class="nowrap">
                                                                        @php
                                                                            if(in_array($failgpaname->grade_name,$temp_grade)){
                                                                                echo $failgpaname->description;
                                                                            }else{
                                                                                if($final_result >= $maxGrade){
                                                                                    $grade_details= App\SmResultStore::remarks($maxGrade);
                                                                                } else {
                                                                                    $grade_details= App\SmResultStore::remarks($final_result);
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        <p>{{@$grade_details->description}}</p>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2" style="text-align: center !important;">
                                                                        @lang('exam.position')
                                                                    </td>
                                                                    <td colspan="2" style="text-align: center !important;">{{getStudentMeritPosition($class_id, $section_id, $exam_type_id, $student_detail->id)}}</td>

                                                                </tr>
                                                            @endif

                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <script>
                                                        function myFunction(value, subject) {
                                                            if (value != "") {
                                                                var res = Number(value / subject).toFixed(2);
                                                            } else {
                                                                var res = 0;
                                                            }
                                                            // document.getElementById("main_subject_total_gpa").innerHTML = res;
                                                        }
                                                        function myFunction2(value, subject , maxGrade) {
                                                            if (value != "") {
                                                                var totalGrade = Number(value / subject).toFixed(2);
                                                                if(totalGrade >= maxGrade){
                                                                    var res = Number(maxGrade).toFixed(2);
                                                                } else {
                                                                    var res = totalGrade;
                                                                }
                                                            } else {
                                                                var res = 0;
                                                            }
                                                            // document.getElementById("final_result").innerHTML = res;
                                                        }
                                                        myFunction({{ $main_subject_total_gpa }}, {{ $Optional_subject_count }});
                                                        myFunction2( {{ $main_subject_total_gpa +$optional_countable_gpa  }}, {{ $Optional_subject_count }}, {{$maxGrade}});
                                                    </script>

                                                    @if(isset($exam_content))
                                                        <table style="width:100%" class="border-0">
                                                            <tbody>
                                                            <tr>
                                                                <td class="border-0" style="border: 0 !important;">
                                                                    <p class="result-date" style="text-align:left; float:left; display:inline-block; margin-top:50px; padding-left: 0;">
                                                                        @lang('reports.date_of_publication_of_result') :
                                                                        <strong>
                                                                            {{dateConvert(@$exam_content->publish_date)}}
                                                                        </strong>
                                                                    </p>
                                                                </td>
                                                                <td class="border-0" style="border: 0 !important;">
                                                                    <div class="text-right d-flex flex-column justify-content-end">
                                                                        <div class="thumb text-right">
                                                                            <img src="{{@$exam_content->file}}" width="100px">
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
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif
@endsection
@if(moduleStatusCheck('University'))
    @push('script')
        <script>
            $(document).ready(function() {
                $("#select_semester_label").on("change", function() {

                    var url = $("#url").val();
                    var i = 0;
                    let semester_id = $(this).val();
                    let academic_id = $('#select_academic').val();
                    let session_id = $('#select_session').val();
                    let faculty_id = $('#select_faculty').val();
                    let department_id = $('#select_dept').val();
                    let un_semester_label_id = $('#select_semester_label').val();

                    if (session_id =='') {
                        setTimeout(function() {
                            toastr.error(
                                "Session Not Found",
                                "Error ",{
                                    timeOut: 5000,
                                });}, 500);

                        $("#select_semester option:selected").prop("selected", false)
                        return ;
                    }
                    if (department_id =='') {
                        setTimeout(function() {
                            toastr.error(
                                "Department Not Found",
                                "Error ",{
                                    timeOut: 5000,
                                });}, 500);
                        $("#select_semester option:selected").prop("selected", false)

                        return ;
                    }
                    if (semester_id =='') {
                        setTimeout(function() {
                            toastr.error(
                                "Semester Not Found",
                                "Error ",{
                                    timeOut: 5000,
                                });}, 500);
                        $("#select_semester option:selected").prop("selected", false)

                        return ;
                    }
                    if (academic_id =='') {
                        setTimeout(function() {
                            toastr.error(
                                "Academic Not Found",
                                "Error ",{
                                    timeOut: 5000,
                                });}, 500);
                        return ;
                    }
                    if (un_semester_label_id =='') {
                        setTimeout(function() {
                            toastr.error(
                                "Semester Label Not Found",
                                "Error ",{
                                    timeOut: 5000,
                                });}, 500);
                        return ;
                    }

                    var formData = {
                        semester_id : semester_id,
                        academic_id : academic_id,
                        session_id : session_id,
                        faculty_id : faculty_id,
                        department_id : department_id,
                        un_semester_label_id : un_semester_label_id,
                    };

                    // Get Student
                    $.ajax({
                        type: "GET",
                        data: formData,
                        dataType: "json",
                        url: url + "/university/" + "get-university-wise-student",
                        beforeSend: function() {
                            $('#select_un_student_loader').addClass('pre_loader').removeClass('loader');
                        },
                        success: function(data) {
                            var a = "";
                            $.each(data, function(i, item) {
                                if (item.length) {
                                    $("#select_un_student").find("option").not(":first").remove();
                                    $("#select_un_student_div ul").find("li").not(":first").remove();

                                    $.each(item, function(i, students) {
                                        console.log(students);
                                        $("#select_un_student").append(
                                            $("<option>", {
                                                value: students.student.id,
                                                text: students.student.full_name,
                                            })
                                        );

                                        $("#select_un_student_div ul").append(
                                            "<li data-value='" +
                                            students.student.id +
                                            "' class='option'>" +
                                            students.student.full_name +
                                            "</li>"
                                        );
                                    });
                                } else {
                                    $("#select_un_student_div .current").html("SELECT STUDENT *");
                                    $("#select_un_student").find("option").not(":first").remove();
                                    $("#select_un_student_div ul").find("li").not(":first").remove();
                                }
                            });
                        },
                        error: function(data) {
                            console.log("Error:", data);
                        },
                        complete: function() {
                            i--;
                            if (i <= 0) {
                                $('#select_un_student_loader').removeClass('pre_loader').addClass('loader');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endif
