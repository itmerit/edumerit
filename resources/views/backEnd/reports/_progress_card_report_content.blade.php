
@php
    $academic_id = $studentDetails->academic_id;
@endphp
@if(resultPrintStatus('vertical_boarder'))
<style>
    .single-report-admit table tr td {
        border: 1px solid rgba(130, 139, 178, 0.15) !important;
    }
    .single-report-admit table thead tr th{
        border: 1px solid rgba(130, 139, 178, 0.15) !important;
    }
    .single-report-admit table tbody tr th{
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
        padding: 8px !important;
    }
    .text-center th{
        text-align: center !important;
    }
    .text-center td{
        text-align: center !important;
    }
    .mark_sheet_body tr:last-child th{
        text-align: left !important;
    }
    .mark_sheet_body tr td:first-child{
        text-align: left !important;
        font-weight: bold;
    }
    .center-table tr:first-child th:first-child{
        text-align: left !important;
    }
</style>
@endif
<style>
    .profile_100{
        width: 100px;
        height: 100px;
        background-size: cover;
        background-position: center center;
        border-radius: 5px;
        background-repeat: no-repeat;
    }
</style>


<section class="student-details">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12 no-gutters">
                <div class="main-title d-flex ">
                    <h3 class="mb-30 flex-fill">@lang('reports.progress_card_report')</h3>
                    <div class="print_button pull-right">
                        {{ Form::open(['class' => 'form-horizontal', 'files' => true, 'route' => 'progress-card/print', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'search_student', 'target' => '_blank']) }}

                        <input type="hidden" name="class_id" value="{{$class_id}}">
                        <input type="hidden" name="section_id" value="{{$section_id}}">
                        <input type="hidden" name="student_id" value="{{$studentDetails->id}}">
                        <input type="hidden" name="academic_id" value="{{$academic_id}}">
                        <input type="hidden" name="custom_mark_report" value="{{@$custom_mark_report}}">

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
                                            <div class="col-lg-8 text-center">
                                                <h3 class="text-white" style="font-size: 30px; margin-bottom: 0px;">
                                                    {{isset(generalSetting()->school_name)?generalSetting()->school_name:'Infix School Management ERP'}}
                                                </h3>
                                                <p class="text-white mb-0" style="font-size: 16px;">
                                                    {{isset(generalSetting()->address)?generalSetting()->address:'Infix School Address'}}
                                                </p>
                                                <p class="text-white mb-0" style="font-size: 16px;">
                                                    @lang('common.email'):  {{isset(generalSetting()->email)?generalSetting()->email:'admin@demo.com'}},   @lang('common.phone'):  {{isset(generalSetting()->phone)?generalSetting()->phone:'+8801841412141'}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="report-admit-img profile_100" style="background-image: url({{ file_exists(@$studentDetails->studentDetail->student_photo) ? asset($studentDetails->studentDetail->student_photo) : asset('public/uploads/staff/demo/staff.jpg') }})"></div>

                                    </div>
                                    <div class="card-body">
                                        <div class="student_marks_table">
                                            <div class="row">
                                                <div class="col-lg-7 text-black">
                                                    <h3>
                                                        {{$studentDetails->studentDetail->full_name}}
                                                    </h3>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <p class="mb-0">
                                                                @lang('common.academic_year') : &nbsp;<span class="primary-color fw-500">{{ @$studentDetails->academic->year }}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('common.class') :<span class="primary-color fw-500">{{ $studentDetails->class->class_name }}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('common.section') : <span class="primary-color fw-500">{{ $studentDetails->section->section_name }}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('student.admission_no') : <span class="primary-color fw-500">{{$studentDetails->studentDetail->admission_no}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('student.roll') :<span class="primary-color fw-500">{{$studentDetails->roll_no}}</span>
                                                            </p>
                                                            <p class="mb-0">
                                                                @lang('common.date_of_birth') :<span class="primary-color fw-500">{{$studentDetails->studentDetail->date_of_birth != ""? dateConvert($studentDetails->studentDetail->date_of_birth):''}}</span>
                                                            </p>


                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-lg-5 text-black">
                                                    @if(@$marks_grade)
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
                                                            @foreach($marks_grade as $grade_d)
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
                                                <div class="col-12 text-center mb-3">
                                                    <h3> <span style="border-bottom: 3px double;">@lang('reports.progress_report')</span></h3>
                                                </div>
                                                <table class="table mb-0 center-table">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th rowspan="2">@lang('common.subjects')</th>
                                                        @foreach($assinged_exam_types as $assinged_exam_type)
                                                            @php
                                                                $exam_type = App\SmExamType::examType($assinged_exam_type);
                                                            @endphp
                                                            <th colspan="{{(@generalSetting()->result_type == 'mark')? 2: 4}}">{{$exam_type->title}}</th>
                                                        @endforeach
                                                        <th rowspan="2">@lang('exam.result')</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        @foreach($assinged_exam_types as $assinged_exam_type)
                                                            <th>@lang('reports.full_mark')</th>
                                                            <th>@lang('exam.marks')</th>
                                                            @if (@generalSetting()->result_type != 'mark')
                                                                <th>@lang('exam.grade')</th>
                                                                <th>@lang('exam.gpa')</th>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    </thead>
                                                    <tbody class="mark_sheet_body">
                                                    @php
                                                        $total_fail = 0;
                                                        $total_marks = 0;
                                                        $gpa_with_optional_count=0;
                                                        $gpa_without_optional_count=0;
                                                        $value=0;
                                                        $total_subject = 0;
                                                        $totalGpa  = 0;
                                                        $all_exam_type_full_mark=0;
                                                        $total_additional_subject_gpa=0;
                                                    @endphp
                                                    @foreach($subjects as $data)
                                                        <tr class="text-center">
                                                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                                @if ($student_optional_subject->subject_id==$data->subject->id)
                                                                    <td>{{$data->subject !=""?$data->subject->subject_name:""}} (@lang('common.optional'))</td>
                                                                @else
                                                                    <td>{{$data->subject !=""?$data->subject->subject_name:""}}</td>
                                                                @endif
                                                            @else
                                                                <td>{{$data->subject !=""?$data->subject->subject_name:""}}</td>
                                                            @endif
                                                                <?php
                                                                $totalSumSub = 0;
                                                                $totalSubjectFail = 0;
                                                                $TotalSum = 0;
                                                            foreach($assinged_exam_types as $assinged_exam_type){
                                                                $mark_parts = App\SmAssignSubject::getNumberOfPart($data->subject_id, $class_id, $section_id, $assinged_exam_type);
                                                                $result = App\SmResultStore::GetResultBySubjectId($class_id, $section_id, $data->subject_id, $assinged_exam_type, $studentDetails->id);

                                                                if (!empty($result)) {
                                                                    $final_results = App\SmResultStore::GetFinalResultBySubjectId($class_id, $section_id, $data->subject_id, $assinged_exam_type, $studentDetails->id);

                                                                    $term_base = App\SmResultStore::termBaseMark($class_id, $section_id, $data->subject_id, $assinged_exam_type, $studentDetails->id);
                                                                }
                                                                $total_subject += $assinged_exam_type;
                                                                $subject_full_mark = subjectFullMark($assinged_exam_type, $data->subject_id, $class_id, $section_id);
                                                                $total_additional_subject_gpa += @$optional_subject_setup->gpa_above;
                                                            if($result->count() > 0){
                                                                ?>
                                                            <td>
                                                                @php
                                                                    if(@generalSetting()->result_type == 'mark'){
                                                                        $all_exam_type_full_mark += subject100PercentMark();
                                                                    }else{
                                                                        $all_exam_type_full_mark += $subject_full_mark;
                                                                    }
                                                                @endphp
                                                                @if (@generalSetting()->result_type == 'mark')
                                                                    {{subject100PercentMark()}}
                                                                @else
                                                                    {{$subject_full_mark}}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @php
                                                                    if($final_results != ""){
                                                                        if(@generalSetting()->result_type == 'mark'){
                                                                            $totalMark = subjectPercentageMark(@$final_results->total_marks, $subject_full_mark);
                                                                            echo $totalMark;
                                                                            $totalSumSub += $totalMark;
                                                                            $total_marks += $totalMark;
                                                                        }else{
                                                                            echo $final_results->total_marks;
                                                                            $totalSumSub += $final_results->total_marks;
                                                                            $total_marks += $final_results->total_marks;
                                                                        }
                                                                        $totalGpa += $final_results->total_gpa_point;
                                                                    }else{
                                                                        echo 0;
                                                                    }
                                                                @endphp
                                                            </td>
                                                            @if (@generalSetting()->result_type != 'mark')
                                                                <td>
                                                                    @php
                                                                        if($final_results != ""){
                                                                            if($final_results->total_gpa_grade == @$fail_grade_name->grade_name){
                                                                                $totalSubjectFail++;
                                                                                $total_fail++;
                                                                            }
                                                                            echo $final_results->total_gpa_grade;
                                                                        }else{
                                                                            echo '-';
                                                                        }
                                                                        if ($student_optional_subject!='') {
                                                                            if ($student_optional_subject->subject_id==$data->subject->id) {
                                                                                $optional_subject_mark=$final_results->total_marks;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                </td>
                                                                <td>{{number_format($final_results->total_gpa_point,2,'.','')}}</td>
                                                            @endif
                                                                <?php
                                                            }else{ ?>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                            <td>-</td>
                                                                <?php
                                                            }
                                                            }
                                                                ?>
                                                            <td>{{$totalSumSub}}</td>
                                                            @php
                                                                if($totalSubjectFail > 0){
                                                                }else{
                                                                    $totalSumSub = $totalSumSub / count($assinged_exam_types);
                                                                }
                                                            @endphp
                                                        </tr>
                                                    @endforeach
                                                    @php
                                                        $colspan = 4 + count($assinged_exam_types) * 2;
                                                        if ($optional_subject_setup!='') {
                                                        $col_for_result=3;
                                                        } else {
                                                            $col_for_result=2;
                                                        }
                                                    @endphp
                                                    <tr class="text-center">
                                                        @if (@generalSetting()->result_type != 'mark')
                                                            <th>@lang('reports.result')</th>
                                                        @endif
                                                        @php
                                                            $term_base_gpa  = 0;
                                                            $average_gpa  = 0;
                                                            $with_percent_average_gpa  = 0;
                                                            $optional_subject_total_gpa  = 0;
                                                            $optional_subject_total_above_gpa  = 0;
                                                            $without_additional_subject_total_gpa  = 0;
                                                            $with_additional_subject_addition  = 0;
                                                            $with_optional_percentage  = 0;
                                                            $total_with_optional_percentage  = 0;
                                                            $total_with_optional_subject_extra_gpa  = 0;
                                                            $optional_subject_mark= 0;
                                                        @endphp
                                                        @foreach($assinged_exam_types as $assinged_exam_type)
                                                            @php
                                                                $exam_type = App\SmExamType::examType($assinged_exam_type);
                                                                $term_base_gpa=termWiseGpa($assinged_exam_type, $studentDetails->id, null, $academic_id);
                                                                $with_percent_average_gpa +=$term_base_gpa;

                                                                $term_base_full_mark=termWiseTotalMark($assinged_exam_type, $studentDetails->id, null, $academic_id);
                                                                $average_gpa+=$term_base_full_mark;

                                                                if($optional_subject_setup!='' && $student_optional_subject!=''){

                                                                    $optional_subject_gpa = optionalSubjectFullMark($assinged_exam_type,$studentDetails->id,@$optional_subject_setup->gpa_above,"optional_sub_gpa", $academic_id);
                                                                    $optional_subject_total_gpa += $optional_subject_gpa;

                                                                    $optional_subject_above_gpa = optionalSubjectFullMark($assinged_exam_type,$studentDetails->id,@$optional_subject_setup->gpa_above,"with_optional_sub_gpa", $academic_id);
                                                                    $optional_subject_total_above_gpa += $optional_subject_above_gpa;

                                                                    $without_subject_gpa = optionalSubjectFullMark($assinged_exam_type,$studentDetails->id,@$optional_subject_setup->gpa_above,"without_optional_sub_gpa", $academic_id);
                                                                    $without_additional_subject_total_gpa += $without_subject_gpa;

                                                                    $with_additional_subject_gpa = termWiseAddOptionalMark($assinged_exam_type, $studentDetails->id, @$optional_subject_setup->gpa_above, $academic_id);
                                                                    $with_additional_subject_addition += $with_additional_subject_gpa;

                                                                $with_optional_percentages=termWiseGpa($assinged_exam_type, $studentDetails->id, $with_optional_subject_extra_gpa, $academic_id);
                                                                $total_with_optional_percentage += $with_optional_percentages;
                                                            }
                                                            @endphp
                                                            @if (@generalSetting()->result_type != 'mark')
                                                                <th colspan="4"> @lang('reports.average_gpa') :
                                                                    {{number_format($term_base_full_mark,2,'.','')}}
                                                                    </br>
                                                                    {{$exam_type->title}} ({{$exam_type->percentage}}%) : {{number_format($term_base_gpa,2,'.','')}}
                                                                    </br>
                                                                    @lang('exam.position') : {{getStudentMeritPosition($class_id, $section_id, $assinged_exam_type, $studentDetails->id) ?? "null"}}
                                                                    @if($optional_subject_setup!='' && $student_optional_subject!='')
                                                                        <hr>
                                                                        @lang('reports.with_optional') :
                                                                        {{number_format($with_optional_subject_extra_gpa,2,'.','')}}
                                                                        </br>
                                                                        @lang('reports.with_optional') ({{$exam_type->percentage}}%) :
                                                                        {{number_format($with_optional_percentages,2,'.','')}}
                                                                    @endif
                                                                </th>
                                                            @endif
                                                        @endforeach
                                                        @if (@generalSetting()->result_type != 'mark')
                                                            <th>
                                                                {{number_format($average_gpa,2,'.','')}}
                                                                </br>
                                                                {{number_format($with_percent_average_gpa, 2, '.', '')}}
                                                                @if($optional_subject_setup!='' && $student_optional_subject!='')
                                                                    <hr>
                                                                    {{number_format($total_with_optional_subject_extra_gpa, 2, '.', '')}}
                                                                    </br>
                                                                    {{number_format($total_with_optional_percentage, 2, '.', '')}}
                                                                @endif
                                                            </th>
                                                        @endif
                                                    </tr>

                                                    </tbody>
                                                </table>
                                                <table style="max-width: 400px; margin-bottom: 20px;" class="table border_table gray_header_table mb_30 max-width-400 ml-auto mr-auto report_table  @if(resultPrintStatus('vertical_boarder')) mt-5 @endif">
                                                    <tbody>
                                                    {{-- Total Marks Start --}}
                                                    <tr>
                                                        <td colspan="{{$colspan / $col_for_result - 1}}" >@lang('exam.total_marks')</td>
                                                        @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                            <td colspan="{{$colspan / $col_for_result + 7}}" style="padding:10px; font-weight:bold">{{$total_marks}} @lang('reports.out_of') {{$all_exam_type_full_mark}}</td>
                                                        @else
                                                            <td colspan="{{$colspan / $col_for_result + 9}}" style="padding:10px; font-weight:bold">{{$total_marks}} @lang('reports.out_of') {{$all_exam_type_full_mark}}</td>
                                                        @endif
                                                    </tr>
                                                    {{-- Total Marks End --}}
                                                    @if (@generalSetting()->result_type != 'mark')
                                                        <tr>
                                                            @if($optional_subject_setup!='' && $student_optional_subject!='')
                                                                <td colspan="{{$colspan / $col_for_result - 1}}" >
                                                                    @lang('reports.optional_total_gpa')
                                                                    <hr>
                                                                    @lang('reports.gpa_above') {{@$optional_subject_setup->gpa_above}}
                                                                </td>
                                                                <td colspan="{{$colspan / $col_for_result + 7}}"
                                                                    style="padding:10px; font-weight:bold">
                                                                    {{$optional_subject_total_gpa}}
                                                                    <hr>
                                                                    {{$optional_subject_total_above_gpa}}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        @php
                                                            if ($optional_subject_mark) {
                                                                $total_marks_without_optional=$total_marks-$optional_subject_mark;
                                                                $op_subject_count=count($subjects)-1;
                                                            }else{
                                                                $total_marks_without_optional=$total_marks;
                                                                $op_subject_count=count($subjects);
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td colspan="{{$colspan / $col_for_result - 1}}">@lang('reports.total_gpa')</td>

                                                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                                <td colspan="4"
                                                                    style="padding:10px; font-weight:bold">
                                                                    {{gradeName(number_format($total_with_optional_percentage,2,'.',''), $academic_id)}}
                                                                </td>
                                                                <td colspan="3" style="padding:10px;">@lang('reports.without_additional_gpa')</td>
                                                                <td colspan="2" style="padding:10px;">
                                                                    {{gradeName(number_format($with_percent_average_gpa,2,'.',''), $academic_id)}}
                                                                </td>
                                                            @else
                                                                <td colspan="{{$colspan / $col_for_result + 9}}"
                                                                    style="padding:10px; font-weight:bold">
                                                                    {{number_format(termWiseFullMark($assinged_exam_types, $studentDetails->id, $academic_id),2,'.','')}}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        {{-- Total Gpa Start --}}
                                                        <tr>
                                                            <td colspan="{{$colspan / $col_for_result - 1}}" >@lang('exam.total_grade')</td>
                                                            @if ($optional_subject_setup!='' && $student_optional_subject!='')
                                                                <td colspan="4" style="padding:10px;">
                                                                    {{number_format($total_with_optional_percentage,2,'.','')}}
                                                                </td>
                                                                <td colspan="3" style="padding:10px;">@lang('reports.without_additional_grade')</td>
                                                                <td colspan="2" style="padding:10px;">
                                                                    {{number_format($with_percent_average_gpa,2,'.','')}}
                                                                </td>
                                                            @else
                                                                <td colspan="{{$colspan / $col_for_result + 9}}" style="padding:10px;">
                                                                    {{gradeName(number_format(termWiseFullMark($assinged_exam_types, $studentDetails->id, $academic_id),2,'.',''), $academic_id)}}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        {{-- Total Gpa End --}}
                                                        {{-- Remark Start --}}
                                                        <tr>
                                                            @if($optional_subject_setup!='' && $student_optional_subject!='')
                                                                <td colspan="{{$colspan / $col_for_result - 1}}" >@lang('reports.remarks')</td>
                                                                <td colspan="{{$colspan / $col_for_result + 7}}"  style="padding:10px; font-weight:bold">
                                                                    {{remarks(number_format($total_with_optional_percentage,2,'.',''), $academic_id)}}
                                                                </td>
                                                            @else
                                                                <td colspan="{{$colspan / $col_for_result - 1}}" >@lang('reports.remarks')</td>
                                                                <td colspan="{{$colspan / $col_for_result + 9}}"  style="padding:10px; font-weight:bold">
                                                                    {{remarks(number_format(termWiseFullMark($assinged_exam_types, $studentDetails->id,$academic_id),2,'.',''), $academic_id)}}
                                                                </td>
                                                            @endif
                                                        </tr>
                                                        {{-- Remark End --}}
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            @php
                                                $examReportSignature = examReportSignatures();
                                            @endphp
                                            @if($examReportSignature->count() > 1 || !$exam_content)
                                                <div style="margin-top:auto; margin-bottom:20px; display: flex; justify-content: {{$examReportSignature->count() == 1 ? 'flex-end' : 'space-between'}}; flex-wrap: wrap; align-items: center;">
                                                    @foreach ($examReportSignature as $signature)
                                                        <div style="text-align: right; display: flex; align-items: center; justify-content: center; margin-right: 24px; flex-direction: column">
                                                            <div>
                                                                <img src="{{asset($signature->signature)}}" width="150px" height="40px"
                                                                     alt="{{$signature->title}}">
                                                            </div>
                                                            <p style="margin-top:8px; text-align: center; width: 100%; border-top: 1px solid rgba(67, 89, 187, 0.15) !important">
                                                                {{$signature->title}}
                                                            </p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($exam_content)
                                                <table style="width:100%;  @if($examReportSignature->count() > 1) border-top: 1px solid #000 !important; @else  margin-top: auto; @endif"
                                                       @if($examReportSignature->count() == 1)class="border-0 mt-auto" @endif>
                                                    <tbody>
                                                    <tr>
                                                        <td class="border-0" style="border: 0 !important;">
                                                            <p class="result-date"
                                                               style="text-align:left; float:left; @if($examReportSignature->count() == 1)  margin-top:50px; @endif display:inline-block; padding-left: 0; color: #000;">
                                                                @lang('exam.date_of_publication_of_result') :
                                                                <strong>
                                                                    {{dateConvert(@$exam_content->publish_date)}}
                                                                </strong>
                                                            </p>
                                                        </td>
                                                        <td class="border-0" style="border: 0 !important;">
                                                            @if($examReportSignature->count() == 1)
                                                                <div class="text-right d-flex flex-column justify-content-end">
                                                                    <div class="thumb text-right">
                                                                        <img src="{{asset(@$examReportSignature->first()->signature)}}" width="100px">
                                                                    </div>
                                                                    <p style="text-align:right; float:right; display:inline-block; margin-top:5px;">
                                                                        ({{@$examReportSignature->first()->title}})
                                                                    </p>
                                                                </div>
                                                            @endif
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
</section>