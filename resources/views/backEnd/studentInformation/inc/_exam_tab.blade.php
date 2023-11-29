<div role="tabpanel" class="tab-pane fade" id="studentExam">
    @if (moduleStatusCheck('University'))
        {{-- @includeIf('university::exam.partials._exam_report') --}}
        @includeIf('university::exam.admin_student_exam_tab')
    @else
        @php
            $exam_count = count($exam_terms);
        @endphp
        @if ($exam_count > 1)
            <div class="white-box no-search no-paginate no-table-info mb-2">
                <table class="table school-table-style shadow-none pb-0" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>
                                @lang('student.subject')
                            </th>
                            <th>
                                @lang('student.full_marks')
                            </th>
                            <th>
                                @lang('student.passing_marks')
                            </th>
                            <th>
                                @lang('student.obtained_marks')
                            </th>
                            <th>
                                @lang('student.results')
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endif
        <div class="white-box no-search no-paginate no-table-info mb-2">
            @foreach ($student_detail->studentRecords as $record)
                @foreach ($exam_terms as $key=>$exam)
                    @php
                        $get_results = App\SmStudent::getExamResult(@$exam->id, @$record);                       
                    @endphp
                    @if ($get_results)
                        <div class=@if ($key != 0) mt-40 @endif>
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


                            <table id="table_id" class="table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>@lang('student.date')</th>
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
                                            if (!is_null($student_detail->optionalSubjectSetup) && !is_null($student_detail->optionalSubject)) {
                                                if ($mark->subject_id != @$student_detail->optionalSubject->subject_id) {
                                                    $temp_grade[] = $mark->total_gpa_grade;
                                                }
                                            } else {
                                                $temp_grade[] = $mark->total_gpa_grade;
                                            }
                                            $total_gpa_point += $mark->total_gpa_point;
                                            if (!is_null(@$student_detail->optionalSubject)) {
                                                if (@$student_detail->optionalSubject->subject_id == $mark->subject->id && $mark->total_gpa_point < @$student_detail->optionalSubjectSetup->gpa_above) {
                                                    $total_gpa_point = $total_gpa_point - $mark->total_gpa_point;
                                                }
                                            }
                                            $temp_gpa[] = $mark->total_gpa_point;
                                            $get_subject_marks = subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id);
                                            
                                            $subject_marks = App\SmStudent::fullMarksBySubject($exam->id, $mark->subject_id);
                                            $schedule_by_subject = App\SmStudent::scheduleBySubject($exam->id, $mark->subject_id, @$record);
                                            $result_subject = 0;
                                            if (@generalSetting()->result_type == 'mark') {
                                                $grand_total_marks += subject100PercentMark();
                                            } else {
                                                $grand_total_marks += $get_subject_marks;
                                            }
                                            if (@$mark->is_absent == 0) {
                                                if (@generalSetting()->result_type == 'mark') {
                                                    $grand_total += @subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id));
                                                } else {
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
                                                    ({{ subject100PercentMark() }})
                                                @else
                                                    ({{ @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id) }})
                                                @endif
                                            </td>
                                            <td>
                                                @if (@generalSetting()->result_type == 'mark')
                                                    {{ @subjectPercentageMark(@$mark->total_marks, @subjectFullMark($mark->exam_type_id, $mark->subject_id, $mark->studentRecord->class_id, $mark->studentRecord->section_id)) }}
                                                @else
                                                    {{ @$mark->total_marks }}
                                                @endif
                                            </td>
                                            @if (@generalSetting()->result_type == 'mark')
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
                                        <th>@lang('exam.position'):
                                            {{ getStudentMeritPosition($record->class_id, $record->section_id, $exam->id, $record->id) }}
                                        </th>
                                        <th>
                                            @lang('exam.grand_total'): {{ $grand_total }}/{{ $grand_total_marks }}
                                        </th>
                                        @if (@generalSetting()->result_type == 'mark')
                                            <th></th>
                                        @else
                                            <th>@lang('exam.grade'):
                                                @php
                                                    if (in_array($fail_gpa_name->grade_name, $temp_grade)) {
                                                        echo $fail_gpa_name->grade_name;
                                                    } else {
                                                        $final_gpa_point = ($total_gpa_point - $optional_gpa) / ($total_subject - $optional_subject);
                                                        $average_grade = 0;
                                                        $average_grade_max = 0;
                                                        if ($result == 0 && $grand_total_marks != 0) {
                                                            $gpa_point = number_format($final_gpa_point, 2, '.', '');
                                                            if ($gpa_point >= $max_gpa) {
                                                                $average_grade_max = App\SmMarksGrade::where('school_id', Auth::user()->school_id)
                                                                    ->where('academic_id', getAcademicId())
                                                                    ->where('from', '<=', $max_gpa)
                                                                    ->where('up', '>=', $max_gpa)
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
                                                            echo $fail_gpa_name->grade_name;
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
                                                    if ($float_final_gpa_point >= $max_gpa) {
                                                        echo $max_gpa;
                                                    } else {
                                                        echo $float_final_gpa_point;
                                                    }
                                                @endphp
                                            </th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    @endif
</div>