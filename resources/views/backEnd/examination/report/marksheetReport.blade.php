@extends('backEnd.master')
    @section('title')
        @lang('exam.marksheet_report')
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
                <h1>@lang('exam.marksheet_report') </h1>
                <div class="bc-pages">
                    <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.exam')</a>
                    <a href="#">@lang('exam.marksheet_report')</a>
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
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'percent-marksheet-report', 'method' => 'POST']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        @if(moduleStatusCheck('University'))
                            <div class="col-lg-12">
                                <div class="row">
                                    @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                    ['required' => 
                                        ['USN', 'UD', 'UA', 'US', 'USL','USUB']
                                    ])

                                    <div class="col-lg-3 mt-30" id="select_exam_typ_subject_div">
                                        {{ Form::select('exam_type',[""=>__('exam.select_exam').'*'], null , ['class' => 'primary_select  form-control'. ($errors->has('exam_type') ? ' is-invalid' : ''), 'id'=>'select_exam_typ_subject']) }}
                                        
                                        <div class="pull-right loader loader_style" id="select_exam_type_loader">
                                            <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                        </div>
                                        @if ($errors->has('exam'))
                                            <span class="text-danger custom-error-message" role="alert">
                                                {{ @$errors->first('exam') }}
                                            </span>
                                        @endif
                                    </div>

                                   
                                </div>
                            </div>
                        @else
                            <div class="col-lg-3 mt-30-md md_mb_20">
                                <select class="primary_select form-control{{ $errors->has('exam') ? ' is-invalid' : '' }}" name="exam_type" id="examTypeId">
                                    <option data-display="@lang('reports.select_exam') *" value="">@lang('reports.select_exam')*</option>
                                    @foreach($exams as $exam)
                                        <option value="{{$exam->id}}" {{isset($exam_id)? ($exam_id == $exam->id? 'selected':''):''}}>{{$exam->title}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('exam_type'))
                                    <span class="text-danger invalid-select" role="alert">
                                        {{ $errors->first('exam_type') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-lg-3 mt-30-md md_mb_20" id="examTypeBaseSubjectDiv">
                                <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" id="examTypeBaseSubjectList" name="subject">
                                    <option data-display="@lang('exam.select_subject') *"value="">@lang('exam.select_subject') *</option>
                                </select>
                                <div class="pull-right loader loader_style" id="selectExamBaseSubjectLoader">
                                    <img class="loader_img_style" src="{{asset('public/backEnd/img/demo_wait.gif')}}" alt="loader">
                                </div>
                                @error('subject')
                                    <span class="text-danger invalid-select" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-3 mt-30-md md_mb_20">
                                <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="select_class" name="class">
                                    <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class')*</option>
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
        @if(moduleStatusCheck('University'))
       
            @includeIf('university::exam.mark_sheet_report')
        @else
        <style type="text/css">
            .table tbody td {
                padding: 5px;
                text-align: center;
                vertical-align: middle;
            }

            .table head th {
                padding: 5px;
                text-align: center;
                vertical-align: middle;
            }

            .table head tr th {
                padding: 5px;
                text-align: center;
                vertical-align: middle;
            }

            th, td {
                white-space: nowrap;
                text-align: center !important;
            }

            th.subject-list {
                white-space: inherit;
            }

            #main-content {
                width: auto !important;
            }

            .main-wrapper {
                display: inherit;
            }

            .table thead th {
                padding: 5px;
                vertical-align: middle;
            }

            .student_name, .subject-list {
                line-height: 12px;
            }

            .student_name b {
                min-width: 20%;
            }

            .gradeChart tbody td{
                padding: 0px;
                padding-left: 5px;
            }
            
            hr{
                margin: 0px;
            }

            .name_field{
                width: 200px;
            }

            .roll_field{
                width: 80px;
            }

            .large_spanTh{
                width: 500px;
            }

            .scrolled_table th,
            .scrolled_table td{
                padding: 6px 25px !important;
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
                text-align: left;
                background: #fff !important;
            }

            .single-report-admit table.summeryTable tbody tr:first-of-type td,
            .single-report-admit table.comment_table tbody tr:first-of-type td {
                border-top:0 !important;
            }

            .student_marks_table{
                width: 100%;
                margin: 0px auto 0 auto;
                padding-left: 10px;
                padding-right: 5px;
                padding: 30px;
            }

            thead{
                font-weight:bold;
                text-align:left;
                color: var(--base_color);
                font-size: 10px;
            }

            .student_info li p{
                font-size: 14px;
                font-weight: 500;
                color: #828bb2;
            }

            .student_info li p.bold_text{
                font-weight: 600;
                color: var(--base_color);
            }

            ul.student_info li {
                display: flex;
            }

            ul.student_info {
                padding: 0;
            }

            ul.student_info li p:first-child {
                flex: 55px 0 0;
            }
            ul.student_info.info2 li p:first-child {
                flex: 100px 0 0;
            }
        </style>
        <section class="student-details mt-20">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-30 mt-30"> 
                                @lang('exam.marksheet_report')
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-8 pull-right mt-20">
                        <div class="print_button pull-right">
                            {{ Form::open(['class' => 'form-horizontal', 'route' => 'percent-marksheet-print', 'method' => 'POST','target' => '_blank']) }}
                                <input type="hidden" name="exam" value="{{$examInfo->id}}">
                                <input type="hidden" name="subject" value="{{$subjectInfo->id}}">
                                <input type="hidden" name="class" value="{{$classInfo->id}}">
                                <input type="hidden" name="section" value="{{$sectionInfo->id}}">
                                <button type="submit" class="primary-btn small fix-gr-bg"><i class="ti-printer"></i>
                                    @lang('common.print')
                                </button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="single-report-admit">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-4">
                                        <img class="logo-img" src="{{ generalSetting()->logo }}" alt="{{ generalSetting()->school_name }}">
                                    </div>
                                    <div class="col-lg-4">
                                        <h3 class="text-white">@lang('exam.exam') : {{$examInfo->title}}</h3>
                                        <h3 class="text-white">@lang('exam.subject') : {{$subjectInfo->subject_name}}</h3>
                                        <h3 class="text-white">@lang('common.class') : {{$classInfo->class_name}} ({{$sectionInfo->section_name}})</h3>
                                    </div>
                                    <div class=" col-lg-4 text-left text-lg-right mt-30-md">
                                        <h3 class="text-white"> {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} </h3>
                                        <p class="text-white mb-0"> {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Adress'}} </p>
                                        <p class="text-white mb-0">
                                            @lang('common.email'): {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}} ,
                                            @lang('common.phone'): {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="white-box">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <h4 class="exam_title text-center text-uppercase">
                                            @lang('exam.marksheet_report')
                                        </h4>
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-7"></div>
                                            <div class="col-lg-5"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="single-report-admit">
                                            <div class="student_marks_table pt-0">
                                                <div class="table-responsive">
                                                    <table class="mt-30 mb-20 table table-bordered w-100 scrolled_table">
                                                        <thead>
                                                            <tr>
                                                                <th class="name_field">@lang('common.student_name')</th>
                                                                <th class="roll_field">@lang('student.admission_no')</th>
                                                                <th class="roll_field">@lang('student.roll_no')</th>
                                                                <th class="large_spanTh">@lang('exam.position')</th>
                                                                <th class="large_spanTh">@lang('exam.total_mark')</th>
                                                                <th class="large_spanTh">@lang('academics.pass_mark')</th>
                                                                <th class="large_spanTh">@lang('exam.obtained_mark')</th>
                                                                <th class="large_spanTh">@lang('exam.result')</th>
                                                                <th class="large_spanTh">@lang('exam.grade')</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                         
                                                            @foreach($mark_sheet as $data)
                                                                @php
                                                                    $totalMark = subjectPercentageMark(@$data->total_marks, @subjectFullMark($data->exam_type_id, $data->subject->id, $data->class_id, $data->section_id));
                                                                    
                                                                    $evaluation= markGpa($totalMark);
                                                                @endphp
                                                                
                                                                <tr> 
                                                                    <td>{{$data->studentRecords->student->full_name}}</td>
                                                                    <td>{{$data->studentRecords->student->admission_no}}</td>
                                                                    <td>{{$data->studentRecords->student->roll_no}}</td>
                                                                    <td>{{$loop->iteration}}</td>

                                                                    @if($exam_rule)
                                                                    <td>{{subject100PercentMark()}}</td>
                                                                    @else 
                                                                    <td>{{@subjectFullMark($data->exam_type_id, $data->subject->id)}}</td>
                                                                    @endif 
                                                                    <td>{{$pass_mark}}</td>
                                                                    
                                                                    <td>
                                                                    @if($exam_rule)
                                                                        {{$totalMark}}
                                                                    @else 
                                                                        {{@$data->total_marks}}
                                                                    @endif 
                                                                    </td>
                                                                    <td>
                                                                        @if ($pass_mark <= $totalMark)
                                                                            @lang('exam.pass')
                                                                        @else
                                                                            @lang('exam.fail')
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        {{$evaluation->description}}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
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
