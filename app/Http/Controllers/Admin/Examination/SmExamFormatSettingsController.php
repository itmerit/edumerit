<?php

namespace App\Http\Controllers\Admin\Examination;


use App\SmClass;
use App\SmExamType;
use App\SmMarkStore;
use App\SmMarksGrade;
use App\SmExamSetting;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\Models\ExamMeritPosition;
use App\Models\AllExamWisePosition;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\Examination\SmExamFormatSettingsRequest;

class SmExamFormatSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function index()
    {
        try {
            $content_infos = SmExamSetting::with('examName')->get();

            $exams = SmExamType::get();

            $already_assigned = [];
            foreach ($content_infos as $content_info) {
                $already_assigned[] = $content_info->exam_type;
            }

            return view('backEnd.examination.exam_settings', compact('content_infos', 'exams', 'already_assigned'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmExamFormatSettingsRequest $request)
    {
        
        try {
            $destination = 'public/uploads/exam/';
            $add_content = new SmExamSetting();
            $add_content->exam_type = $request->exam_type;
            $add_content->title = $request->title;
            $add_content->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $add_content->file = fileUpload($request->file, $destination);
            $add_content->start_date = $request->start_date ? date('Y-m-d', strtotime($request->start_date)): null;
            $add_content->end_date = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null;
            $add_content->school_id = Auth::user()->school_id;
            $add_content->academic_id = getAcademicId();
            $add_content->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('exam-settings');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        try {
            $content_infos = SmExamSetting::with('examName')->get();

            $editData = SmExamSetting::where('id', $id)->first();

            $exams = SmExamType::get();

            $already_assigned = [];
            foreach ($content_infos as $content_info) {
                if ($editData->exam_type != $content_info->exam_type) {
                    $already_assigned[] = $content_info->exam_type;
                }
            }
            // return $already_assigned;
            return view('backEnd.examination.exam_settings', compact('editData', 'content_infos', 'exams', 'already_assigned'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function update(SmExamFormatSettingsRequest $request)
    {

        try {
            $destination = 'public/uploads/exam/';
            $update_add_content = SmExamSetting::find($request->id);
            $update_add_content->exam_type = $request->exam_type;
            $update_add_content->title = $request->title;
            $update_add_content->publish_date = date('Y-m-d', strtotime($request->publish_date));
            $update_add_content->start_date = $request->start_date ? date('Y-m-d', strtotime($request->start_date)) : null;
            $update_add_content->end_date = $request->end_date ? date('Y-m-d', strtotime($request->end_date)) : null;
            $update_add_content->school_id = Auth::user()->school_id;
            $update_add_content->academic_id = getAcademicId();
            $update_add_content->file = fileUpdate($update_add_content->file, $request->file, $destination);
            $result = $update_add_content->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('exam-settings');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $content = SmExamSetting::find($id);
            if ($content->file != '' && file_exists($content->file)) {
                unlink($content->file);
            }
            $content->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect('exam-settings');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function examReportPosition()
    {
        try {
            $exams = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            return view('backEnd..examination.examPositionReport', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function examReportPositionStore(Request $request)
    {
        $request->validate([
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);
        try {
            $exam = $request->exam;
            $class = $request->class;
            $section = $request->section;

            $students = StudentRecord::with(['studentDetail' => function($q){
                return $q->where('active_status', 1);
            }])
                ->where('class_id', $class)
                ->where('section_id', $section)
                ->orderBy('id', 'asc')
                ->get();

            $fail_grade = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->min('gpa');

            $max_gpa = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->max('gpa');

            $totalSubject = SmMarkStore::where('class_id', $class)
                            ->where('section_id', $section)
                            ->get()
                            ->unique();

            $passStudents = [];
            $failStudents = [];

            foreach ($students as $student) {
                $studentMarks = SmMarkStore::where('exam_term_id', $exam)
                    ->where('student_record_id', $student->id)
                    ->get()
                    ->groupBy('subject_id');

                foreach ($studentMarks as $subject_id => $studentMark) {
                    if (markGpa(subjectPercentageMark(@$studentMark->sum('total_marks'), @subjectFullMark($exam, $subject_id, $class, $section)))->gpa != $fail_grade) {
                        $passStudents[] = $student->id;
                    } else {
                        $failStudents[] = $student->id;
                    }
                }
            }

            $studenInfos = array_diff(array_unique($passStudents), array_unique($failStudents));

            if ($studenInfos) {
                $students = StudentRecord::whereIn('id', $studenInfos)->get();

                ExamMeritPosition::where('class_id', $class)
                    ->where('section_id', $section)
                    ->where('exam_term_id', $exam)
                    ->delete();

                foreach ($students as $student) {
                    $allMarks = SmMarkStore::where('exam_term_id', $exam)
                        ->where('student_record_id', $student->id)
                        ->get()
                        ->groupBy('subject_id');

                    $totalGpa = 0;
                    $totalMark = 0;
                    foreach ($allMarks as $subject_id => $allMark) {
                        $totalMark += $allMark->sum('total_marks');
                        $totalGpa += markGpa(subjectPercentageMark(@$allMark->sum('total_marks'), @subjectFullMark($exam, $subject_id, $class, $section)))->gpa;

                    }
                    $gpa = $totalGpa / $totalSubject->count();
                    if ($gpa > $max_gpa) {
                        $gpaData = $max_gpa;
                    } else {
                        $gpaData = $gpa;
                    }


                    $data = new ExamMeritPosition();
                    $data->class_id = $class;
                    $data->section_id = $section;
                    $data->exam_term_id = $exam;
                    $data->total_mark = $totalMark;
                    $data->gpa = number_format($gpaData, 2);
                    $data->grade = gpaResult($gpaData)->grade_name;
                    $data->admission_no = $student->studentDetail->roll_no;
                    $data->record_id = $student->id;
                    $data->school_id = auth()->user()->school_id;
                    $data->academic_id = getAcademicId();
                    $data->save();
                }

                $allStudentMarks = ExamMeritPosition::where('class_id', $class)
                    ->where('section_id', $section)
                    ->where('exam_term_id', $exam)
                    ->orderBy('gpa', 'desc')
                    ->get()
                    ->sort(function ($a, $b) {
                        if ($a->gpa == $b->gpa) {
                            if ($a->total_mark != $b->total_mark) {
                                return $a->total_mark > $b->total_mark ? -1 : 1;
                            }
                        }
                    });

                $position = 0;
                $last_mark = null;

                foreach ($allStudentMarks as $key => $allStudentMark) {

                    if (!$last_mark || ($last_mark->total_mark != $allStudentMark->total_mark) || ($last_mark->gpa != $allStudentMark->gpa)) {
                        $position += 1;
                    }

                    $allStudentMark->position = $position;
                    $allStudentMark->save();

                    $last_mark = $allStudentMark;
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('exam-report-position');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function allExamReportPosition()
    {
        try {
            $exams = SmExamType::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            $classes = SmClass::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->get();

            return view('backEnd..examination.allExamPositionReport', compact('exams', 'classes'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function allExamReportPositionStore(Request $request)
    {
        try {
            $class = $request->class;
            $section = $request->section;

            $students = StudentRecord::with(['studentDetail' => function($q){
                return $q->where('active_status', 1);
            }])
                ->where('class_id', $class)
                ->where('section_id', $section)
                ->whereHas('studentDetail', function($q){
                    return $q->where('active_status', 1);
                })
                ->where('academic_id', getAcademicId())
                ->where('is_promote', 0)
                ->distinct('id')
                ->get();

            $fail_grade = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->min('gpa');

            $max_gpa = SmMarksGrade::where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id', Auth::user()->school_id)
                ->max('gpa');

            $totalSubject = SmMarkStore::where('class_id', $class)
                ->where('section_id', $section)
                ->distinct('exam_term_id')
                ->get()
                ->unique()->count();

            $passStudents = [];
            $failStudents = [];

            foreach ($students as $student) {
                $studentMarks = SmMarkStore::where('student_record_id', $student->id)
                    ->where('academic_id', getAcademicId())
                    ->select('subject_id')->get()
                    ->groupBy('subject_id');

                foreach ($studentMarks as $subject_id => $studentMark) {

                    $dataGroup = $studentMark->groupBy('exam_term_id');

                    foreach($dataGroup as $exam_term_id => $data){
                        $subFullMark = subjectFullMark($exam_term_id, $subject_id, $class, $section);
                        if (markGpa(subjectPercentageMark($data->sum('total_marks'), $subFullMark))->gpa != $fail_grade) {
                            $passStudents[] = $student->id;
                        } else {
                            $failStudents[] = $student->id;
                        }

                    }
                }
            }

            $studenInfos = array_diff(array_unique($passStudents), array_unique($failStudents));

            if ($studenInfos) {
                $students = StudentRecord::whereIn('id', $studenInfos)->get();

                AllExamWisePosition::where('class_id', $class)
                    ->where('section_id', $section)
                    ->delete();

                foreach ($students as $student) {
                    $allMarks = SmMarkStore::where('student_record_id', $student->id)
                        ->where('academic_id', getAcademicId())
                        ->select('subject_id')->get()
                        ->groupBy('subject_id');

                    $totalGpa = 0;
                    $totalMark = 0;
                    $examTerm = 0;
                    foreach ($allMarks as $subject_id => $allMarkes) {
                        foreach ($allMarkes as $allMark) {
                            $fullMark = subjectFullMark($allMark->exam_term_id, $allMark->subject_id, $class, $section);
                            $totalMark += subjectPercentageMark($allMark->total_marks, $fullMark);
                            $totalGpa += markGpa(subjectPercentageMark($allMark->total_marks, $fullMark))->gpa;
                            $examTerm += $allMark->exam_term_id;
                        }
                    }
                    $gpa = $totalGpa / ($totalSubject*$examTerm);
                    if ($gpa > $max_gpa) {
                        $gpaData = $max_gpa;
                    } else {
                        $gpaData = $gpa;
                    }

                    $data = new AllExamWisePosition();
                    $data->class_id = $class;
                    $data->section_id = $section;
                    $data->total_mark = $totalMark;
                    $data->gpa = number_format($gpaData, 2);
                    $data->grade = gpaResult($gpaData)->grade_name;
                    $data->admission_no = $student->studentDetail->roll_no;
                    $data->roll_no = $student->studentDetail->roll_no;
                    $data->record_id = $student->id;
                    $data->school_id = auth()->user()->school_id;
                    $data->academic_id = getAcademicId();
                    $data->save();
                }

                $allStudentMarks = AllExamWisePosition::where('class_id', $class)
                    ->where('section_id', $section)
                    ->orderBy('gpa', 'desc')
                    ->get()
                    ->sort(function ($a, $b) {
                        if ($a->gpa == $b->gpa) {
                            if ($a->total_mark != $b->total_mark) {
                                return $a->total_mark > $b->total_mark ? -1 : 1;
                            }
                        }
                    });

                $position = 0;
                $last_mark = null;

                foreach ($allStudentMarks as $key => $allStudentMark) {

                    if (!$last_mark || ($last_mark->total_mark != $allStudentMark->total_mark) || ($last_mark->gpa != $allStudentMark->gpa)) {
                        $position += 1;
                    }

                    $allStudentMark->position = $position;
                    $allStudentMark->save();

                    $last_mark = $allStudentMark;
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->route('all-exam-report-position');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
