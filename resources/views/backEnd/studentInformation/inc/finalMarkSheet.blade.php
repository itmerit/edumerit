@php 
$subjects = $record->assign_subject;
$all_subject_ids = $subjects->pluck('subject_id')->toArray();
$is_result_available = App\SmResultStore::where([
                    ['class_id', $record->class], 
                    ['section_id', $record->section], 
                    ['student_id', $record->student]])
                    ->where('school_id',Auth::user()->school_id)
                    ->get();
$studentDetails = $record;
$record_id =  $record->id;
@endphp

@if(isset($is_result_available))
    @if(moduleStatusCheck('University'))
        {{-- @includeIf('university::exam.progress_card_report') --}}
    @else
        <section class="student-details">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-lg-12 no-gutters">
                        <div class="main-title d-flex ">
                            <h3 class="mb-30 flex-fill">
                              @if(moduleStatusCheck('University'))
                                    {{$record->semesterLabel->name}} ({{$record->unSection->section_name}}) - {{@$record->unAcademic->name}}
                              @else 
                                    {{$record->class->class_name}} ({{$record->section->section_name}}) 
                              @endif
                              @lang('exam.final_mark_sheet')
                            </h3>

                        </div>
                    </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <div class="single-report-admit">
                                        <div class="card">
                                            <div class="card-header">
                                                    <div class="d-flex">
                                                            <div class="col-lg-2">
                                                            <img class="logo-img" src="{{ asset(generalSetting()->logo) }}" alt="{{generalSetting()->school_name}}">
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
                                                    <div class="col-lg-7 text-black"> 
                                                        <h3 style="border-bottm:1px solid #ddd; padding: 15px; text-align:center"> 
                                                            @lang('exam.student_final_mark_sheet')
                                                        </h3>
                                                        <h3>
                                                            {{$studentDetails->studentDetail->full_name}}
                                                        </h3>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <p class="mb-0">
                                                                    @lang('common.academic_year') : &nbsp;<span class="primary-color fw-500">{{ @$studentDetails->academic->year }}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('common.section') : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span class="primary-color fw-500">{{ $studentDetails->section->section_name }}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('common.class') : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <span class="primary-color fw-500">{{ $studentDetails->class->class_name }}</span>
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <p class="mb-0">
                                                                    @lang('student.admission_no') : <span class="primary-color fw-500">{{$studentDetails->studentDetail->admission_no}}</span>
                                                                </p>
                                                                <p class="mb-0">
                                                                    @lang('student.roll') : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<span class="primary-color fw-500">{{$studentDetails->roll_no}}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 text-black">
                                                        @if(@$grades)
                                                            <table class="table" id="grade_table">
                                                                <thead>
                                                                <tr>
                                                                    <th>@lang('reports.staring')</th>
                                                                    <th> @lang('reports.ending')</th>
                                                                    @if (@generalSetting()->result_type != 'mark')
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
                                                                        @if (@generalSetting()->result_type != 'mark')
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
                                                    <table class="table mb-0">
                                                        <thead>
                                                        <tr class="text-center">
                                                            <th class="text-center">@lang('common.subjects')</th>
                                                            <th class="text-center">@lang('exam.total_mark')</th>
                                                            <th class="text-center">@lang('exam.pass_mark')</th>
                                                            @foreach($result_setting as $assinged_exam)
                                                                <th class="text-center">{{@$assinged_exam->examTypeName->title}} ({{@$assinged_exam->exam_percentage}}%)</th>
                                                            @endforeach
                                                            <th class="text-center">@lang('exam.average')</th>
                                                            <th class="text-center">@lang('exam.result')</th>
                                                            <th class="text-center">@lang('exam.grade')</th>
                                                        </tr>

                                                        </thead>

                                                        <tbody class="mark_sheet_body">
                                                            @foreach($subjects as $assignsubject)
                                                                  <tr>
                                                                        <td class="text-center">{{@$assignsubject->subject->subject_name}}</td>
                                                                        <td class="text-center">100</td>
                                                                        <td class="text-center">{{@$assignsubject->subject->pass_mark}}</td>
                                                                        @foreach($result_setting as $examRule)
                                                                        <td class="text-center">{{singleSubjectMark($record_id,$assignsubject->subject->id,$examRule->exam_type_id)[0]}}</td>
                                                                        @endforeach
                                                                        <td class="text-center">{{subjectAverageMark($record_id,$assignsubject->subject->id)[0]}}</td>
                                                                        <td class="text-center"></td>
                                                                        <td class="text-center">{{getGrade(subjectAverageMark($record_id,$assignsubject->subject->id)[0],true)}}</td>
                                                                  </tr>
                                                      
                                                            @endforeach
                                                        </tbody>
                                                            <tfoot>
                                                                  <tr>
                                                                        <th class="text-center">@lang('exam.total_average')</th>
                                                                        <th class="text-center">100</th>
                                                                        <th class="text-center">{{avgSubjectPassMark($all_subject_ids)}}</th>
                                                                        @foreach($result_setting as $exam)
                                                                        <th class="text-center">{{allExamSubjectMark($record_id,$exam->id)[0]}}</th>
                                                                        @endforeach
                                                                        <th class="text-center">{{allExamSubjectMarkAverage($record_id,$all_subject_ids)}}</th>
                                                                        <th class="text-center">
                                                                              @if( allExamSubjectMarkAverage($record_id,$all_subject_ids) >= avgSubjectPassMark($all_subject_ids))
                                                                              @lang('exam.pass')
                                                                              @else
                                                                              @lang('exam.fail')
                                                                              @endif

                                                                        </th>
                                                                        <th class="text-center">{{getGrade(allExamSubjectMarkAverage($record_id, $all_subject_ids),true)}}</th>
                                                                        
                                                                       
                                                                  </tr>
                                                            </tfoot>
                                                        
                                                    </table>
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