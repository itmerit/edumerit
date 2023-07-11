
@extends('backEnd.master')
@section('title')
@lang('exam.marks_register')
@endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.marks_register') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="#">@lang('exam.marks_register')</a>
            </div>
        </div>
    </div>
</section>
<section class="admin-visitor-area">
    <div class="container-fluid p-0">
            <div class="row">
                <div class="col-lg-8 col-md-6 col-sm-6">
                    <div class="main-title">
                        <h3 class="mb-30">@lang('common.select_criteria') </h3>
                    </div>
                </div>
            @if(userPermission('marks_register_create'))
                <div class="col-lg-4 text-md-right text-left col-md-6 mb-30-lg col-sm-6 text_sm_right">
                    <a href="{{route('marks_register_create')}}" class="primary-btn small fix-gr-bg">
                        <span class="ti-plus pr-2"></span>
                        @lang('exam.add_marks')
                    </a>
                </div>
            @endif
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">              
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'marks_register', 'method' => 'POST', 'id' => 'search_student']) }}
                        <div class="row">
                            <input type="hidden" name="url" id="url" value="{{URL::to('/')}}">
                            @if(moduleStatusCheck('University'))
                                <div class="col-lg-12">
                                    <div class="row">
                                        @includeIf('university::common.session_faculty_depart_academic_semester_level',
                                        ['required' => 
                                            ['USN', 'UD', 'UA', 'US', 'USL','USEC'],'hide'=> ['USUB']
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
                                        @foreach($exam_types as $exam_type)
                                            <option value="{{$exam_type->id}}" {{isset($exam_id)? ($exam_id == $exam_type->id? 'selected':''):''}}>{{$exam_type->title}}</option>
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
                                    <select class="primary_select form-control{{ $errors->has('subject') ? ' is-invalid' : '' }} select_class_subject" id="select_class_subject" name="subject">
                                        <option data-display="@lang('exam.select_subject') *" value="">@lang('exam.select_subject') *</option>
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
        @if(isset($marks_registers))
            @if(moduleStatusCheck('University'))
                <div class="row mt-40">
                    <div class="col-lg-12 no-gutters mb-30">
                        <div class="main-title">
                            <h3>@lang('exam.marks_register') | <strong>@lang('exam.subject')</strong>: {{$subjectName->subject_name}}</h3>
                            @includeIf('university::exam._university_info')
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th rowspan="2" >@lang('student.admission_no').</th>
                                    <th rowspan="2" >@lang('student.roll_no').</th>
                                    <th rowspan="2" >@lang('common.student')</th>
                                    <th colspan="{{@$number_of_exam_parts}}"> {{@$subjectName->subject_name}}</th>
                                </tr>
                                <tr>
                                    @foreach($marks_entry_form as $part)
                                        <th>{{@$part->exam_title}} ( {{@$part->exam_mark}} ) </th>
                                    @endforeach
                                    <th>@lang('common.teacher') @lang('reports.remarks')</th>
                                </tr>
                            </thead>
                            <tbody>                        
                                @php $colspan = 3; $counter = 0;  @endphp
                                @foreach($students as $student)
                                <tr>
                                    <td>{{$student->student->admission_no}}</td>
                                    <td>{{@$student->roll_no}}</td>
                                    <td>{{@$student->student->full_name}}</td>
                                    @php $entry_form_count=0; @endphp
                                    @foreach($marks_entry_form as $part)
                                    @php
                                        $search_mark = App\SmMarkStore::un_get_mark_by_part($student->student_id, $request, $exam_type, $subject_id, $part->id, $student->id);
                                    @endphp
                                        <td>{{$search_mark}}</td>
                                    @endforeach
                                    <?php 
                                        $teacher_remarks = App\SmMarkStore::un_teacher_remarks($student->student_id, $exam_type, $request, $subject_id, $student->id); 
                                    ?>
                                    <td>{{$teacher_remarks}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="row mt-40">
                    <div class="col-lg-4 no-gutters">
                        <div class="main-title">
                            <h3 class="mb-0">@lang('exam.marks_register')</h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">



                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th rowspan="2" >@lang('student.admission_no').</th>
                                    <th rowspan="2" >@lang('student.roll_no').</th>
                                    <th rowspan="2" >@lang('common.student')</th>
                                    <th colspan="{{@$number_of_exam_parts}}"> {{@$subjectNames->subject_name}}</th> 
                                    <th rowspan="2">@lang('exam.is_present')</th>
                                </tr>
                                <tr>
                                    @foreach($marks_entry_form as $part)
                                    <th>{{@$part->exam_title}} ( {{@$part->exam_mark}} ) </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>                        
                                @php $colspan = 3; $counter = 0;  @endphp
                                @foreach($students as $student)
                                <tr>
                                    <td>{{$student->admission_no}}
                                        <input type="hidden" name="student_ids[]" value="{{@$student->id}}">
                                        <input type="hidden" name="student_rolls[{{@$student->id}}]" value="{{@$student->roll_no}}">
                                        <input type="hidden" name="student_admissions[{{@$student->id}}]" value="{{@$student->admission_no}}">
                                    </td>
                                    <td>{{@$student->roll_no}}</td>
                                    <td>{{@$student->full_name}}</td>
                                    @php $entry_form_count=0; @endphp
                                    @foreach($marks_entry_form as $part)
                                    <td>
                                        <div class="primary_input mt-10">
                                        <input type="hidden" name="exam_setup_ids[]" value="{{@$part->id}}">
        
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field marks_input" type="text" name="marks[{{@$student->id}}][{{@$part->id}}]" value="0" max="100">
                                            <input class="primary_input_field marks_input" type="hidden" name="exam_Sids[{{@$student->id}}][{{@$entry_form_count++}}]" value="0">
                                            <label>{{@$part->exam_title}} @lang('exam.mark')</label>
                                            
                                        </div>                                
                                    </td>
                                    @endforeach
                                    <td>
                                        <div class="primary_input">
                                            <input type="checkbox" id="subject_{{@$student->id}}_{{@$student->admission_no}}" class="common-checkbox" name="abs[{{@$student->id}}]" value="1">
                                            <label for="subject_{{@$student->id}}_{{@$student->admission_no}}">@lang('common.yes')</label>
                                        </div>
                                            
                                    </td>

                                </tr>
                                @endforeach 
                            </tbody>
                        </table>

                        {{--
                        <table id="" class="school-table-data school-table shadow-none" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('student.admission_no')</th>
                                    <th>@lang('student.roll_no')</th>
                                    <th>@lang('exam.student')</th>
                                    <th>@lang('student.father_name')</th>
                                    @php
                                        $subjects = $marks_register->marksRegisterChilds;
                                    
                                    @endphp
                                    @foreach($subjects as $subject)
                                    <th>{{$subject->subject !=""?$subject->subject->subject_name:""}}</th>
                                    @endforeach

                                    <th>@lang('exam.grand_total')</th>
                                    <th>@lang('exam.percent')(%)</th>
                                    <th>@lang('exam.result')</th>

                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $registerer_ids = [];
                                @endphp
                                @foreach($marks_registers as $marks_register)
                                @php
                                    $registerer_ids[] = $marks_register->student_id;
                                @endphp
                                <tr>
                                    <td>{{$marks_register->studentInfo !=""?$marks_register->studentInfo->admission_no:""}}</td>
                                    <td>{{$marks_register->studentInfo !=""?$marks_register->studentInfo->roll_no:""}}</td>
                                    <td>{{$marks_register->studentInfo !=""?$marks_register->studentInfo->full_name:""}}</td>
                                    <td>{{$marks_register->studentInfo !=""?$marks_register->studentInfo->parents->fathers_name:""}}</td>
                                    @php
                                        $results = $marks_register->marksRegisterChilds;
                                        $grand_total = 0;
                                        $grand_total_marks = 0;
                                        $final_result = 0;
                                    @endphp
                                    @foreach($results as $result)
                                    @php
                                        $subjectDetails = App\SmMarksRegister::subjectDetails($marks_register->exam_id, $marks_register->class_id, $marks_register->section_id, $result->subject_id);
                                        $grand_total_marks += $subjectDetails->full_mark;

                                        if($result->abs == 0){
                                            $grand_total += $result->marks;
                                            if($result->marks < $subjectDetails->pass_mark){
                                                $final_result++;
                                            }

                                        }else{
                                            $final_result++;
                                        }
                                    @endphp
                                    <td>{{$result->abs == 0? $result->marks: 'ABS'}} </td>
                                    @endforeach
                                    <td>{{$grand_total.'/'.$grand_total_marks}}</td>
                                    <td>{{($grand_total==0)?0:number_format($grand_total/$grand_total_marks*100, 2)}}</td>
                                    <td>
                                        @if($final_result == 0)
                                            <button class="primary-btn small bg-success text-white border-0">Pass</button>
                                        @else
                                            <button class="primary-btn small bg-danger text-white border-0">Fail</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @foreach($all_students as $student)
                                    @if(!in_array($student->id, $registerer_ids))
                                        <tr>
                                            <td>{{$student->admission_no}}</td>
                                            <td>{{$student->roll_no}}</td>
                                            <td>{{$student->full_name}}</td>
                                            <td>{{$student->parents !=""?$student->parents->fathers_name:""}}</td>
                                            @php
                                                $results = $marks_register->marksRegisterChilds;
                                            @endphp
                                            @foreach($results as $result)
                                            <td>{{'N/A'}}</td>
                                            @endforeach
                                            <td>{{'N/A'}}</td>
                                            <td>{{'N/A'}}</td>
                                            <td>{{'N/A'}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        --}}
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
            

@endsection
