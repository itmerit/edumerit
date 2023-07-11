@extends('backEnd.master')
    @section('title')
        @lang('exam.add_marks')
    @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-40 white-box">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>@lang('exam.add_marks') </h1>
            <div class="bc-pages">
                <a href="{{route('dashboard')}}">@lang('common.dashboard')</a>
                <a href="#">@lang('exam.examination')</a>
                <a href="{{route('marks_register')}}">@lang('exam.marks_register')</a>
                <a href="#">@lang('exam.add_marks')</a>
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
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="white-box">
                    {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'marks_register_create', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student']) }}
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
                                        <option data-display="@lang('common.select_subject') *" value="">@lang('common.select_subject') *</option>
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
</section>
<section>
<section class="mt-20">
    @if (isset($students))
        @if(moduleStatusCheck('University'))
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-12 col-md-6 mb-30">
                        <div class="main-title">
                            <h3>@lang('exam.add_marks') | @lang('exam.exam'): {{@$exam_type->title}}</h3>
                            @includeIf('university::exam._university_info')
                        </div>
                    </div>
                </div>

                {{ Form::open(['class' => 'form-horizontal', 'route' => 'marks_register_store', 'method' => 'POST', 'id' => 'marks_register_store']) }}
                <input type="hidden" name="exam_id" value="{{$exam_id}}">
                <input type="hidden" name="exam_type_id" value="{{$exam_type_id}}">
                <input type="hidden" name="subject_id" value="{{$subject_id}}">
                <input type="hidden" name="un_session_id"  value="{{ @$un_session->id}}">
                <input type="hidden" name="un_faculty_id"  value="{{ @$un_faculty->id}}">
                <input type="hidden" name="un_department_id"  value="{{ @$un_department->id}}">
                <input type="hidden" name="un_academic_id"  value="{{ @$un_academic->id}}">
                <input type="hidden" name="un_semester_id"  value="{{ @$un_semester->id}}">
                <input type="hidden" name="un_semester_label_id"  value="{{ @$un_semester_label->id}}">
                <input type="hidden" name="un_section_id"  value="{{ @$un_section->id}}">

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th rowspan="2" >@lang('student.admission_no').</th>
                                    <th rowspan="2" >@lang('student.roll_no').</th>
                                    <th rowspan="2" >@lang('common.student')</th>
                                    <th class="text-center" colspan="{{$number_of_exam_parts + 1}}">{{$subjectName->subject_name}}</th>
                                    <th rowspan="2">@lang('exam.is_present')</th>
                                </tr>
                                <tr>
                                    @foreach($marks_entry_form as $part)
                                    <th>{{$part->exam_title}} ( {{$part->exam_mark}} ) </th>
                                    @endforeach
                                    <th>@lang('common.teacher') @lang('reports.remarks')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $colspan = 3;
                                    $counter = 0;
                                    $request = (object)[
                                        'un_session_id'=>$un_session->id,
                                        'un_faculty_id'=>@$un_faculty->id,
                                        'un_department_id'=>$un_department->id,
                                        'un_academic_id'=>$un_academic->id,
                                        'un_semester_id'=>$un_semester->id,
                                        'un_section_id' => null,
                                        'un_semester_label_id'=>$un_semester_label->id,
                                    ];
                                @endphp
                                @foreach($students as $record)
                                @php
                                    $absent_check = App\SmMarksRegister::un_is_absent_check($exam_id, $request, $subject_id, $record->student_id, $record->student_record_id);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="markStore[{{$record->student_record_id}}]" value="{{$record->student_record_id}}">
                                        <input type="hidden" name="markStore[{{$record->student_record_id}}][student]" value="{{$record->student_id}}">
                                        <input type="hidden" name="markStore[{{$record->student_record_id}}][roll_no]" value="{{$record->roll_no}}">
                                        <input type="hidden" name="markStore[{{$record->student_record_id}}][adimission_no]" value="{{$record->studentDetail->admission_no}}">
                                        @if(@$absent_check->attendance_type != 'P')                                    
                                        <input type="hidden" name="markStore[{{$record->student_record_id}}][absent_students]" value="{{$record->student_record_id}}">                                    
                                        @endif 
                                        {{$record->studentDetail->admission_no}}
                                    </td>
                                    <td>{{$record->roll_no}}</td>
                                    <td>{{$record->studentDetail->full_name}}</td>
                                    @php $entry_form_count=0; @endphp
                                    @foreach($marks_entry_form as $part)
                                    @php $d = 5 + rand()%5;   @endphp
                                    <td>
                                        <div class="primary_input mt-10">
                                        <input type="hidden" name="exam_setup_ids[]" value="{{$part->id}}">
                                        <?php
                                        $search_mark = App\SmMarkStore::un_get_mark_by_part($record->student_id, $request, $part->exam_term_id, $part->un_subject_id, $part->id, $record->id);
                                        ?>
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field marks_input" type="text" step="any" max="{{@$part->exam_mark}}"
                                            name="markStore[{{$record->student_record_id}}][marks][{{$part->id}}]" value="{{!empty($search_mark)?$search_mark:0}}">
                                            <input class="primary_input_field" type="hidden" name="markStore[{{$record->student_record_id}}][exam_Sids][{{$entry_form_count++}}]" value="{{$part->id}}">
                                            <input type="hidden" id="markStore[{{$record->student_record_id}}][part_marks][{{$part->id}}]" name="part_marks" value="{{$part->exam_mark}}">
                                            <label>{{$part->exam_title}} Mark</label>
                                            
                                        </div>
                                    </td>
                                    @endforeach
                                        <?php 
                                            $teacher_remarks = App\SmMarkStore::un_teacher_remarks($record->student_id, $exam_type_id, $request, $subject_id, $record->student_record_id); 
                                        ?>
                                    <td>
                                        <div class="primary_input mt-10">
                                            <input class="primary_input_field" type="text" name="markStore[{{$record->id}}][teacher_remarks]" value="{{$teacher_remarks}}">
                                            <label class="primary_input_label" for="">@lang('teacher') @lang('remarks')</label>
                                            
                                        </div>
                                    </td>
                                        <?php $is_absent_check = App\SmMarkStore::un_is_absent_check($record->student_id, $part->exam_term_id, $request, $part->subject_id , $record->student_record_id); ?>
                                    <td>
                                        <div class="primary_input">
                                            @if(@$absent_check->attendance_type == 'P')
                                                <button class="primary-btn small fix-gr-bg" type="button">@lang('exam.present')</button>
                                            @else
                                                <button class="primary-btn small bg-danger text-white border-0" type="button">@lang('exam.absent')</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                    @if(userPermission('marks_register_create'))
                                        <tr>
                                            <td colspan="{{count($marks_entry_form) + 5}}" class="text-center">
                                                <button type="submit" class="primary-btn fix-gr-bg mt-20 submit">
                                                    <span class="ti-check"></span>
                                                    @lang('exam.save_marks')
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        @else
            <div class="container-fluid p-0">
                <div class="row mt-40">
                    <div class="col-lg-6 col-md-6">
                        <div class="main-title">
                            <h3 class="mb-30">@lang('exam.add_marks') |
                            <small>@lang('exam.exam'): {{$search_info['exam_name']}}, @lang('common.class'): {{$search_info['class_name']}}, @lang('common.section'): {{$search_info['section_name']}}
                            </h3>
                        </div>
                    </div>
                </div>

                {{ Form::open(['class' => 'form-horizontal', 'route' => 'marks_register_store', 'method' => 'POST', 'id' => 'marks_register_store']) }}
                <input type="hidden" name="exam_id" value="{{$exam_id}}">
                <input type="hidden" name="class_id" value="{{$class_id}}">
                <input type="hidden" name="section_id" value="{{$section_id}}">
                <input type="hidden" name="subject_id" value="{{$subject_id}}">

                <div class="row">
                    <div class="col-lg-12">
                        <table class="table school-table-style" cellspacing="0" width="100%" >
                            <thead>
                                <tr>
                                    <th rowspan="2" >@lang('student.admission_no').</th>
                                    <th rowspan="2" >@lang('student.roll_no').</th>
                                    <th rowspan="2" >@lang('common.class_Sec')</th>
                                    <th rowspan="2" >@lang('common.student')</th>
                                    <th class="text-center" colspan="{{$number_of_exam_parts + 1}}"> {{$subjectNames->subject_name}}</th>
                                    <th rowspan="2">@lang('exam.is_present')</th>
                                </tr>
                                <tr>
                                    @foreach($marks_entry_form as $part)
                                        <th>{{$part->exam_title}} ( {{$part->exam_mark}} ) </th>
                                    @endforeach
                                    <th>@lang('common.teacher') @lang('reports.remarks')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $colspan = 3; $counter = 0;  @endphp
                                @foreach($students as $record)
                                @php
                                    $absent_check = App\SmMarksRegister::is_absent_check($exam_id, $class_id, $record->section_id, $subject_id, $record->student_id, $record->id);
                                @endphp
                                <tr>
                                    <td>
                                        <input type="hidden" name="markStore[{{$record->id}}]" value="{{$record->id}}">
                                        <input type="hidden" name="markStore[{{$record->id}}][student]" value="{{$record->student_id}}">
                                        <input type="hidden" name="markStore[{{$record->id}}][class]" value="{{$record->class_id}}">
                                        <input type="hidden" name="markStore[{{$record->id}}][section]" value="{{$record->section_id}}">
                                        <input type="hidden" name="markStore[{{$record->id}}][roll_no]" value="{{$record->roll_no}}">
                                        <input type="hidden" name="markStore[{{$record->id}}][adimission_no]" value="{{$record->studentDetail->admission_no}}">
                                        @if(@$absent_check->attendance_type != 'P' && !isSkip('exam_attendance'))
                                        <input type="hidden" name="markStore[{{$record->id}}][absent_students]" value="{{$record->id}}">
                                        @endif
                                        {{$record->studentDetail->admission_no}}
                                    </td>
                                    <td>{{$record->roll_no}}</td>
                                    <td>{{$record->class->class_name.'('.$record->section->section_name .')' }}</td>
                                    <td>{{$record->studentDetail->full_name}}</td>
                                    @php $entry_form_count=0; @endphp
                                    @foreach($marks_entry_form as $part)

                                    @php $d = 5 + rand()%5;   @endphp
                                    <td>
                                        <div class="primary_input mt-10">
                                        <input type="hidden" name="exam_setup_ids[]" value="{{$part->id}}">
                                        <?php
                                        $search_mark = App\SmMarkStore::get_mark_by_part($record->student_id, $part->exam_term_id, $part->class_id, $part->section_id, $part->subject_id, $part->id, $record->id);
                                        ?>
                                            <input oninput="numberCheckWithDot(this)" class="primary_input_field marks_input" type="text" step="any" max="{{@$part->exam_mark}}"
                                            name="markStore[{{$record->id}}][marks][{{$part->id}}]" value="{{!empty($search_mark)?$search_mark:0}}" 
                                            {{@($absent_check->attendance_type == 'A' || @$absent_check->attendance_type == '') && !isSkip('exam_attendance') ? 'readonly':''}}>

                                            <input class="primary_input_field" type="hidden" name="markStore[{{$record->id}}][exam_Sids][{{$entry_form_count++}}]" value="{{$part->id}}">

                                            <input type="hidden" id="markStore[{{$record->id}}][part_marks][{{$part->id}}]" name="part_marks" value="{{$part->exam_mark}}">

                                         
                                            
                                        </div>
                                    </td>
                                    @endforeach
                                    <?php
                                    $teacher_remarks = App\SmMarkStore::teacher_remarks($record->student_id, $exam_id, $record->class_id, $record->section_id, $subject_id, $record->id);
                                    ?>
                                    <td>

                                        <div class="primary_input mt-10">
                                          
                                        <input class="primary_input_field" type="text" name="markStore[{{$record->id}}][teacher_remarks]" value="{{$teacher_remarks}}" {{@($absent_check->attendance_type == 'A' || @$absent_check->attendance_type == '') && !isSkip('exam_attendance')? 'readonly':''}} >
                                       
                                        
                                    </div>
                                    </td>

                                <?php $is_absent_check = App\SmMarkStore::is_absent_check($record->student_id, $part->exam_term_id, $part->class_id, $part->section_id, $part->subject_id , $record->id); ?>

                                    <td>
                                        <div class="primary_input">
                                            @if(@$absent_check->attendance_type == 'P')
                                            <button class="primary-btn small fix-gr-bg" type="button">@lang('exam.present')</button>
                                            @else

                                            <button class="primary-btn small bg-danger text-white border-0" type="button">@lang('exam.absent')</button>
                                            @endif



                                        </div>

                                    </td>

                                </tr>
                                @endforeach
                                @if(userPermission('marks_register_create'))
                                <tr>
                                    <td colspan="{{count($marks_entry_form) + 5}}" class="text-center">
                                        <button type="submit" class="primary-btn fix-gr-bg mt-20 submit">
                                            <span class="ti-check"></span>
                                            @lang('exam.save_marks')
                                        </button>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        @endif
    @endif
</section>
@endsection
