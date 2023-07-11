@extends('backEnd.master')
@section('title')
    @lang('reports.result')
@endsection
@section('mainContent')
    <section class="sms-breadcrumb mb-40 white-box">
        <div class="container-fluid">
            <div class="row justify-content-between">
                <h1>@lang('reports.result')</h1>
                <div class="bc-pages">
                    <a href="{{ route('dashboard') }}">@lang('common.dashboard')</a>
                    <a href="#">@lang('exam.examinations')</a>
                    <a href="{{ route('student_result') }}">@lang('reports.result')</a>
                </div>
            </div>
        </div>
    </section>

    <section class="student-details">
        @if(moduleStatusCheck('University'))
            {{-- @includeIf('university::exam.partials._exam_report') --}}
            @includeIf('university::exam.student_exam_tab')
        @else
            <ul class="nav nav-tabs tabs_scroll_nav ml-0" role="tablist">

                @foreach ($records as $key => $record)
                    <li class="nav-item">
                        <a class="nav-link @if ($record->is_default == 1) active @endif " href="#tab{{ $key }}" role="tab"
                            data-toggle="tab">{{ $record->class->class_name }} ({{ $record->section->section_name }}) </a>
                    </li>
                @endforeach

            </ul>
            <div class="tab-content mt-40">
                @foreach ($records as $key => $record)
                    <div role="tabpanel" class="tab-pane fade  @if ($record->is_default == 1) active show @endif" id="tab{{ $key }}">
                        <div class="container-fluid p-0 ">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="no-search no-paginate no-table-info mb-2">
                                        @foreach ($exam_terms as $exam)
                                            @php
                                                $today = date('Y-m-d H:i:s');
                                                $get_results = App\SmStudent::getExamResult(@$exam->id, @$record);
                                            @endphp
                                            @if ($get_results)
                                                <div class="main-title">
                                                    <h3 class="mb-0">{{ @$exam->title }}</h3>
                                                </div>
                                                @php
                                                    $grand_total = 0;
                                                    $grand_total_marks = 0;
                                                    $result = 0;
                                                    $temp_grade = [];
                                                    $total_gpa_point = 0;
                                                    $total_subject = count($get_results);
                                                    $optional_subject = 0;
                                                    $optional_gpa = 0;
                                                @endphp
                                                @isset($exam->examSettings->publish_date)
                                                    @if ($exam->examSettings->publish_date <= $today)
                                                    <x-table>
                                                        <table id="table_id" class="table mb-5" cellspacing="0"
                                                            width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>@lang('common.date')</th>
                                                                    <th>@lang('exam.subject_full_marks')</th>
                                                                    <th>@lang('exam.obtained_marks')</th>
                                                                    @if (@generalSetting()->result_type == 'mark')
                                                                        <th>@lang('exam.pass_fail')</th>
                                                                    @else
                                                                        <th>@lang('exam.grade')</th>
                                                                        <th>@lang('exam.gpa')</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($get_results as $mark)
                                                                    @php
                                                                        if (!is_null($optional_subject_setup) && !is_null($student_optional_subject)) {
                                                                            if ($mark->subject_id != @$student_optional_subject->subject_id) {
                                                                                $temp_grade[] = $mark->total_gpa_grade;
                                                                            }
                                                                        } else {
                                                                            $temp_grade[] = $mark->total_gpa_grade;
                                                                        }
                                                                        $total_gpa_point += $mark->total_gpa_point;
                                                                        if (!is_null(@$student_optional_subject)) {
                                                                            if (@$student_optional_subject->subject_id == $mark->subject->id && $mark->total_gpa_point < @$optional_subject_setup->gpa_above) {
                                                                                $total_gpa_point = $total_gpa_point - $mark->total_gpa_point;
                                                                            }
                                                                        }
                                                                        $temp_gpa[] = $mark->total_gpa_point;
                                                                        $get_subject_marks = subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id);
                                                                        
                                                                        $subject_marks = App\SmStudent::fullMarksBySubject($exam->id, $mark->subject_id);
                                                                        $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id, $mark->subject_id, @$record);
                                                                        $result_subject = 0;
                                                                        if(@generalSetting()->result_type == 'mark'){
                                                                            $grand_total_marks += subject100PercentMark();
                                                                        }else{
                                                                            $grand_total_marks += $get_subject_marks;
                                                                        }
                                                                        if (@$mark->is_absent == 0) {
                                                                            if(@generalSetting()->result_type == 'mark'){
                                                                                $grand_total += @subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                                            }else{
                                                                                $grand_total += @$mark->total_marks;
                                                                            }
                                                                            if ($mark->marks < $subject_marks->pass_mark) {
                                                                                $result_subject++;
                                                                                $result++;
                                                                            }
                                                                        } else {
                                                                            $result_subject++;
                                                                            $result++;
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            {{ !empty($schedule_by_subject->date) ? dateConvert($schedule_by_subject->date) : '' }}
                                                                        </td>
                                                                        <td>
                                                                            {{ @$mark->subject->subject_name }}
                                                                            @if (@generalSetting()->result_type == 'mark')
                                                                                ({{subject100PercentMark()}})
                                                                            @else
                                                                                ({{ @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id) }})
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if (@generalSetting()->result_type == 'mark')
                                                                                {{@subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id))}}
                                                                            @else
                                                                                {{@$mark->total_marks}}
                                                                            @endif
                                                                        </td>
                                                                        @if(@generalSetting()->result_type == 'mark')
                                                                            <td>
                                                                                @php
                                                                                    $totalMark = subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                                                    $passMark = $mark->subject->pass_mark;
                                                                                @endphp
                                                                                @if ($passMark <= $totalMark)
                                                                                    @lang('exam.pass')
                                                                                @else
                                                                                    @lang('exam.fail')
                                                                                @endif
                                                                            </td>
                                                                        @else
                                                                            <td>
                                                                                {{ @$mark->total_gpa_grade }}
                                                                            </td>
                                                                            <td>
                                                                                {{ number_format(@$mark->total_gpa_point, 2, '.', '') }}
                                                                            </td>
                                                                        @endif
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>@lang('exam.position'): {{getStudentMeritPosition($record->class_id, $record->section_id, $exam->id, $record->id)}}</th>
                                                                    <th>
                                                                        @lang('exam.grand_total'):
                                                                        {{ $grand_total }}/{{ $grand_total_marks }}
                                                                    </th>
                                                                    @if (@generalSetting()->result_type == 'mark')
                                                                        <th></th>
                                                                    @else
                                                                        <th>
                                                                            @lang('exam.grade'):
                                                                            @php
                                                                                if (in_array($failgpaname->grade_name, $temp_grade)) {
                                                                                    echo $failgpaname->grade_name;
                                                                                } else {
                                                                                    $final_gpa_point = ($total_gpa_point - $optional_gpa) / ($total_subject - $optional_subject);
                                                                                    $average_grade = 0;
                                                                                    $average_grade_max = 0;
                                                                                    if ($result == 0 && $grand_total_marks != 0) {
                                                                                        $gpa_point = number_format($final_gpa_point, 2, '.', '');
                                                                                        if ($gpa_point >= $maxgpa) {
                                                                                            $average_grade_max = App\SmMarksGrade::where('school_id', Auth::user()->school_id)
                                                                                                ->where('academic_id', getAcademicId())
                                                                                                ->where('from', '<=', $maxgpa)
                                                                                                ->where('up', '>=', $maxgpa)
                                                                                                ->first('grade_name');
                                                                                
                                                                                            echo @$average_grade_max->grade_name;
                                                                                        } else {
                                                                                            $average_grade = App\SmMarksGrade::where('school_id', Auth::user()->school_id)
                                                                                                ->where('academic_id', getAcademicId())
                                                                                                ->where('from', '<=', $final_gpa_point)
                                                                                                ->where('up', '>=', $final_gpa_point)
                                                                                                ->first('grade_name');
                                                                                            echo @$average_grade->grade_name;
                                                                                        }
                                                                                    } else {
                                                                                        echo $failgpaname->grade_name;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                        </th>
                                                                        <th>
                                                                            @lang('exam.gpa')
                                                                            @php
                                                                                $final_gpa_point = 0;
                                                                                $final_gpa_point = ($total_gpa_point - $optional_gpa) / ($total_subject - $optional_subject);
                                                                                $float_final_gpa_point = number_format($final_gpa_point, 2);
                                                                                if ($float_final_gpa_point >= $maxgpa) {
                                                                                    echo $maxgpa;
                                                                                } else {
                                                                                    echo $float_final_gpa_point;
                                                                                }
                                                                            @endphp
                                                                        </th>
                                                                    @endif
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </x-table>
                                                    @endif
                                                @endisset
                                            @endif
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>  
        @endif
    </section>
@endsection
@include('backEnd.partials.data_table_js')