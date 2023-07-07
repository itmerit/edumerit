@extends('backEnd.master')
@section('title')
    @lang('reports.merit_list_report')
@endsection
@section('mainContent')
    @push('css')
        <style>
            #grade_table th{
                border: 1px solid black;
                text-align: center;
                background: #351681;
                color: white;
            }

            #grade_table td{
                color: black;
                text-align: center;
                border: 1px solid black;
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

            .subjectList{
                display: grid;
                grid-template-columns: repeat(2,1fr);
                grid-column-gap: 50px;
                margin: 0;
                padding: 0;
            }

            .subjectList li{
                list-style: none
            }

            .subjectList li span{
                color: #828bb2;
                font-size: 14px;
            }

            .font-14{
                font-size: 14px;
            }

            .line_grid {
                display: grid;
                grid-template-columns: 120px auto;
                grid-gap: 10px;
            }
            @if(resultPrintStatus('vertical_boarder'))
            .single-report-admit table tr td, .single-report-admit table tr th{
                border: 1px solid rgba(130, 139, 178, 0.15) !important;
                padding: 5px
            }
            .single-report-admit table thead tr th{
                border: 1px solid rgba(130, 139, 178, 0.15) !important;
            }
            @endif
        </style>
    @endpush
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('reports.merit_list_report') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('reports.reports')</a>
                    <a href="#">@lang('reports.merit_list_report')</a>
                </div>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'merit_list_report', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
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
                            <div class="col-lg-4 mt-30-md">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam">
                                    <option data-display="@lang('common.select_exam')*" value="">@lang('common.select_exam') *</option>
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
                            <div class="col-lg-4 mt-30-md">
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
                            <div class="col-lg-4 mt-30-md" id="select_section_div">
                                <select class="primary_select form-control{{ $errors->has('section') ? ' is-invalid' : '' }} select_section" id="select_section" name="section">
                                    <option data-display="@lang('common.select_section')*" value="">@lang('common.select_section') *</option>
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
    </section>

    @if(isset($allresult_data))
        @if(moduleStatusCheck('University'))
            @includeIf('university::exam.merit_list_report')
        @else
            <section class="student-details">
                <div class="container-fluid p-0">
                    <div class="row mt-40">
                        <div class="col-lg-4 no-gutters">
                            <div class="main-title">
                                <h3 class="mb-30 mt-0">@lang('reports.merit_list_report')</h3>
                            </div>
                        </div>
                        <div class="col-lg-8 pull-right">
                            <a href="{{route('merit-list/print', [$InputExamId, $InputClassId, $InputSectionId])}}" class="primary-btn small fix-gr-bg pull-right" target="_blank"><i class="ti-printer"> </i> @lang('common.print')</a>
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
                                                        <div class="col-lg-2">
                                                            <img class="logo-img" src="{{ generalSetting()->logo }}" alt="{{generalSetting()->school_name}}">
                                                        </div>
                                                        <div class="col-lg-8 text-center">
                                                            <h3 class="text-white" style="font-size: 30px;margin-bottom: 0px;"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                                            <p class="text-white mb-0" style="font-size: 16px;"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} </p>
                                                            <p class="text-white mb-0" style="font-size: 16px;">@lang('common.email'):  {{isset(generalSetting()->email)? generalSetting()->email:'admin@demo.com'}} ,   @lang('common.phone'):  {{isset(generalSetting()->phone)?generalSetting()->phone:'admin@demo.com'}} </p>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h3>@lang('reports.order_of_merit_list')</h3>
                                                                        <p class="mb-0 font-14 line_grid">
                                                                            @lang('common.academic_year')  <span class="primary-color fw-500 "> : {{ @$class->academic->year }}</span>
                                                                        </p>
                                                                        <p class="mb-0 font-14 line_grid">
                                                                            @lang('exam.exam') <span class="primary-color fw-500">: {{$exam_name}}</span>
                                                                        </p>
                                                                        <p class="mb-0 font-14 line_grid">
                                                                            @lang('common.class') <span class="primary-color fw-500">: {{$class_name}}</span>
                                                                        </p>
                                                                        <p class="mb-0 font-14 line_grid">
                                                                            @lang('common.section') <span class="primary-color fw-500">: {{$section->section_name}}</span>
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h3>@lang('common.subjects')</h3>
                                                                        <ul class="subjectList">
                                                                            @foreach($assign_subjects as $subject)
                                                                                <li>
                                                                                    <p class="mb-0">
                                                                                        <span class="primary-color fw-500">{{$subject->subject->subject_name}}</span>
                                                                                    </p>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="w-100 mt-30 mb-20 table table-bordered meritList">
                                                            <thead>
                                                            <tr>
                                                                <th>@lang('common.name')</th>
                                                                <th>@lang('student.admission_no')</th>
                                                                <th>@lang('student.roll_no')</th>
                                                                <th>@lang('reports.position')</th>
                                                                {{-- <th>@lang('common.total_mark')</th> --}}
                                                                {{-- <th>@lang('common.obtained_marks')</th> --}}
                                                                <th>@lang('exam.total_mark')</th>
                                                                @if(generalSetting()->result_type == 'mark')
                                                                <th>@lang('exam.average')</th>
                                                                @else 
                                                                <th>@lang('reports.gpa')</th>
                                                                @endif 
                                                                @foreach($subjectlist as $subject)
                                                                    <th>{{$subject}}</th>
                                                                @endforeach
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($allresult_data as $key => $row)
                                                                @php
                                                                    $total_student_mark = 0;
                                                                    $total = 0;
                                                                    $markslist = explode(',',$row->marks_string);
                                                                @endphp
                                                                <tr>
                                                                    <td>{{$row->student_name}}</td>
                                                                    <td>{{$row->admission_no}}</td>
                                                                    <td>{{$row->studentinfo->roll_no}}</td>
                                                                    <td>{{@getStudentMeritPosition($InputClassId, $InputSectionId, $InputExamId, $row->studentinfo->studentRecord->id)}}</td>
                                                                    <td>{{$row->total_marks}}</td>
                                                                    @if(generalSetting()->result_type == 'mark')
                                                                    <td>{{ number_format(($row->total_marks / count($markslist)),2) }}</td>
                                                                    @else 
                                                                    <td>{{$row->gpa_point}}</td>
                                                                    @endif 
                                                                    @if(!empty($markslist))
                                                                        @foreach($markslist as $mark)
                                                                            @php
                                                                                $subject_mark[]= $mark;
                                                                                $total_student_mark = $total_student_mark + $mark;
                                                                                $total = $total + $subject_total_mark;
                                                                            @endphp
                                                                            <td> {{!empty($mark)? $mark:0}}</td>
                                                                        @endforeach
                                                                    @endif
                                                                    {{-- <td>{{$total}}</td> --}}
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    @if($exam_content)
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