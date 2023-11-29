@extends('backEnd.master')
@section('title')
@lang('exam.subject_wise_mark_sheet')
@endsection
@section('mainContent')
    <style type="text/css">
        .single-report-admit table tr th {
            border: 1px solid #a2a8c5 !important;
            vertical-align: middle;
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

        hr {
            margin: 0;
        }

        .table-bordered {
            border: 1px solid #a2a8c5;
        }

        .single-report-admit table tr th {
            font-weight: 500;
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
            padding-left: 9px;
        }

        .single-report-admit table tbody tr th {
            border: 0 !important;
            border-bottom: 1px solid rgba(67, 89, 187, 0.15) !important;
        }

        .single-report-admit table.summeryTable tbody tr:first-of-type td,
        .single-report-admit table.comment_table tbody tr:first-of-type td {
            border-top: 0 !important;
        }

        /* new  style  */
        .single-report-admit {
        }

        .single-report-admit .student_marks_table {
            background: -webkit-linear-gradient(
                    90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: -moz-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: -o-linear-gradient(90deg, #d8e6ff 0%, #ecd0f4 100%);
            background: linear-gradient(
                    90deg, #d8e6ff 0%, #ecd0f4 100%);
        }

</style>
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('reports.progress_card_report')</h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="#">@lang('exam.subject_wise_mark_sheet')</a>
            </div>
        </div>
    </section>
    <section class="admin-visitor-area mb-40">
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
                @if(session()->has('message-success') != "")
                    @if(session()->has('message-success'))
                        <div class="alert alert-success">
                            {{ session()->get('message-success') }}
                        </div>
                    @endif
                @endif
                @if(session()->has('message-danger') != "")
                    @if(session()->has('message-danger'))
                        <div class="alert alert-danger">
                            {{ session()->get('message-danger') }}
                        </div>
                    @endif
                @endif
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'subjectMarkSheetSearch', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
                    <div class="row">
                        <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                        @if(moduleStatusCheck('University'))
                            <div class="col-lg-12">
                                <div class="row">
                                    @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                            ['required' => 
                                                ['USN', 'UD', 'UA', 'US', 'USL', 'USEC','USUB']
                                            ])

                                </div>
                            </div>
                        @else
                        <div class="col-lg-4 mt-30-md">
                              <select class="primary_select form-control {{ $errors->has('class') ? ' is-invalid' : '' }}" id="class_subject" name="class">
                                  <option data-display="@lang('common.select_class') *" value="">@lang('common.select_class') *</option>
                                  @foreach($classes as $cls)
                                  <option value="{{$cls->id}}" {{isset($class)? ($class->id == $cls->id? 'selected':''):''}}>{{$cls->class_name}}</option>
                                  @endforeach
                              </select>
                              @if ($errors->has('class'))
                                  <span class="text-danger invalid-select" role="alert">
                                      {{ $errors->first('class') }}
                                  </span>
                              @endif
                          </div>
                          
                          <div class="col-lg-4 mt-30-md" id="select_class_subject_div">
                              <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_subject" id="select_class_subject" name="subject">
                                  <option data-display="@lang('common.select_subject') *" value="">@lang('common.select_subject') *</option>
                                  @if(isset($subject))
                                    <option value="{{$subject->id}}" selected>{{$subject->subject_name}}</option>
                                  @endif 
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

                          <div class="col-lg-4 mt-30-md" id="m_select_subject_section_div">
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

@if(isset($sm_mark_stores))
    @if(moduleStatusCheck('University'))
        @includeIf('university::exam.un_subject_mark_sheet')
    @else
        <section class="student-details">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-12 no-gutters">
                        <div class="main-title d-flex ">
                            <h3 class="mb-30 flex-fill">@lang('exam.subject_wise_mark_sheet')</h3>
                            <div class="print_button pull-right">
                                {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' =>'subjectMarkSheetPrint', 'method' => 'POST', 'enctype' => 'multipart/form-data','target' => '_blank']) }}

                                <input type="hidden" name="class" value="{{$class->id}}">
                                <input type="hidden" name="subject" value="{{$subject->id}}">
                                <input type="hidden" name="section" value="{{@$section->id}}">
                               
                                
                                <button type="submit" class="primary-btn small fix-gr-bg"><i class="ti-printer"> </i> @lang('common.print')
                                </button>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
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
                                                            <div class="col-lg-6 ml-30">
                                                                <h3 class="text-white"> 
                                                                    {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}} 
                                                                </h3> 
                                                                <p class="text-white mb-0">
                                                                    {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}} 
                                                                </p>
                                                                <p class="text-white mb-0">
                                                                    @lang('common.email'):  {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}},   @lang('common.phone'):  {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}} 
                                                                </p> 
                                                            </div>
                                                            <div class="offset-2">
                                                            </div>
                                                        </div>
                                                <div class="report-admit-img profile_100" style="background-image: url({{ file_exists(@$studentDetails->studentDetail->student_photo) ? asset($studentDetails->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }})"></div>

                                            </div>
                                        <div class="card-body">
                                            <div class="student_marks_table">
                                                <div class="row">
                                                    <div class="col-lg-12 text-black"> 
                                                        <h3 style="border-bottm:1px solid #ddd; padding: 15px; text-align:center"> 
                                                            @lang('exam.subject_wise_mark_sheet')
                                                            
                                                        </h3>
                                                        <div class="row mt-20">
                                                            <div class="col-lg-6">
                                                                <p class="mb-0">
                                                                    @lang('common.academic_year') : &nbsp;<span class="primary-color fw-500">{{ @generalSetting()->academic_year->year}} [{{@generalSetting()->academic_year->title}}]</span>
                                                                </p>

                                                                <p class="mb-0">
                                                                  @lang('student.class') : <span class="primary-color fw-500">{{@$class->class_name}}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('common.section') : 
                                                                        <span class="primary-color fw-500">
                                                                          @if(!is_null($section))
                                                                          {{$section->section_name}}
                                                                          @else 
                                                                              @if(@$class->groupclassSections)
                                                                                    @foreach ($class->groupclassSections as $section)
                                                                                    {{@$section->sectionName->section_name}} ,
                                                                                    @endforeach
                                                                              @endif
                                                                              @endif 
                                                                        </span>
                                                                </p>

                                                            </div>
                                                            <div class="col-lg-6">
                                                                  <p class="mb-0">
                                                                        @lang('exam.subject') : <span class="primary-color fw-500">{{@$subject->subject_name}} [{{@$subject->subject_code}}]</span>
                                                                    </p>
                                                                    <p class="mb-0">
                                                                        @lang('exam.pass_mark') : <span class="primary-color fw-500">{{@$subject->pass_mark}}</span>
                                                                    </p>

                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                    
                                                    <table class="table mb-0 mt-20">
                                                      <thead>
                                                            <tr>
                                                                  <th>@lang('common.name')</th>
                                                                  <th>@lang('student.admission_no')</th>
                                                                  <th>@lang('exam.id_no')</th>
                                                                  <th>@lang('exam.position')</th>
                                                               
                                                                  @if(count($result_setting) > 0) 
                                                                    @foreach($result_setting as $exam)
                                                                    <th>
                                                                            {{@$exam->examTypeName->title}}
                                                                            <br>
                                                                            {{@$exam->exam_percentage}} %
                                                                            
                                                                    </th>
                                                                    @endforeach
                                                                  @else 
                                                                
                                                                  @foreach(examTypes() as $exam)
                                                                    <th>
                                                                            {{@$exam->title}}
                                                                            <br>
                                                                            {{subjectFullMark($exam->id, $subject->id)}} 
                                                                            
                                                                    </th>
                                                                    @endforeach
                                                                  @endif 
                                                                  <th>@lang('exam.average')</th>
                                                                  <th>@lang('exam.result')</th>
                                                                  <th>@lang('exam.grade')</th>
                                                                 
                                                            </tr>  
                                                      </thead>
                                                      <tbody>
                                                     
                                                        @foreach($finalMarkSheets as $finalMarkSheet)
                                                            <tr>
                                                                <td>{{@$finalMarkSheet->get('student_name')}}</td>
                                                                <td>{{@$finalMarkSheet->get('admission_no')}}</td>
                                                                <td>{{@$finalMarkSheet->get('roll_no')}}</td>
                                                                <td>{{$loop->iteration}}</td>
                                                                @php
                                                                    $exam_count = 0;
                                                                    $total = 0;
                                                                @endphp
                                                                @foreach($finalMarkSheet->get('examTypeMarks') as $examMark)
                                                                    @php
                                                                        $total += $examMark->get('single_avg_mark');
                                                                        $exam_count += 1;
                                                                    @endphp
                                                                    <td>{{$examMark->get('single_avg_mark')}}</td>
                                                                @endforeach

                                                                @php
                                                                    if($exam_cont > 0){
                                                                       $avg = $total / $exam_count;
                                                                  } else{
                                                                   $avg = 0;
                                                                  }

                                                                @endphp

                                                                <td> {{$avg}}</td>
                                                                <td>
                                                                    @if($subject->pass_mark <= $avg)
                                                                        @lang('exam.pass')
                                                                    @else
                                                                        @lang('exam.fail')
                                                                    @endif
                                                                </td>

                                                                <td>{{getGrade($avg,true)}}</td>
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


