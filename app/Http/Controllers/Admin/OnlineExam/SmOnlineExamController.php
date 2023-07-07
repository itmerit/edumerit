<?php

namespace App\Http\Controllers\Admin\OnlineExam;

use DataTables;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSection;
use App\SmStudent;
use App\SmSubject;
use Carbon\Carbon;
use App\SmOnlineExam;
use App\SmNotification;
use App\SmQuestionBank;
use App\SmAssignSubject;
use App\SmOnlineExamMark;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmOnlineExamQuestion;
use App\SmStudentTakeOnlineExam;
use Illuminate\Support\Facades\DB;
use App\SmOnlineExamQuestionAssign;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\SmOnlineExamQuestionMuOption;
use Illuminate\Support\Facades\Schema;
use App\OnlineExamStudentAnswerMarking;
use Illuminate\Support\Facades\Validator;
use Modules\OnlineExam\Entities\InfixOnlineExam;
use App\Http\Requests\Admin\OnlineExam\SmOnlineExamRequest;
use Modules\OnlineExam\Entities\InfixStudentTakeOnlineExam;
use App\Http\Controllers\Admin\StudentInfo\SmStudentReportController;

class SmOnlineExamController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
    }

    public function index()
    {
        $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
            ->where('school_id',Auth::user()->school_id)->first();
        date_default_timezone_set($time_zone_setup->time_zone);
        try{
            if (!Schema::hasColumn('sm_online_exams', 'auto_mark')) {
                Schema::table('sm_online_exams', function ($table) {
                    $table->integer('auto_mark')->default(0);
                });
            }
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $online_exams = SmOnlineExam::where('status', '!=', 2)
                    ->join('sm_assign_subjects','sm_assign_subjects.subject_id','=','sm_online_exams.subject_id')
                    ->where('sm_assign_subjects.teacher_id', $teacher_info->id)
                    ->where('sm_online_exams.academic_id', getAcademicId())
                    ->where('sm_online_exams.school_id',Auth::user()->school_id)
                    ->select('sm_online_exams.*')
                    ->distinct('id')
                    ->get();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::get();
                $online_exams = SmOnlineExam::with('class','section','subject')->where('status', '!=', 2)->get();
            }
            $sections = SmSection::get();
            $subjects = SmSubject::get();
            $present_date_time = date("Y-m-d H:i:s");
            $present_time = date("H:i:s");
            return view('backEnd.examination.online_exam', compact('online_exams', 'classes', 'sections', 'subjects', 'present_date_time','present_time'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewOnlineExam($id)
    {
        try {
            $online_exam = SmOnlineExam::find($id);
            $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)
                                ->where('academic_id', getAcademicId())
                                ->where('school_id', Auth::user()->school_id)
                                ->get();
            return view('backEnd.examination.view_online_question', compact('online_exam', 'assigned_questions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function store(SmOnlineExamRequest $request)
    {
        DB::beginTransaction();
        try{
            foreach($request->section as $section){
                $online_exam = new SmOnlineExam();
                $online_exam->title = $request->title;
                $online_exam->class_id = $request->class;
                $online_exam->section_id = $section;
                $online_exam->subject_id = $request->subject;
                $online_exam->date = date('Y-m-d', strtotime($request->date));
                $online_exam->start_time = date('H:i:s', strtotime($request->start_time));
                $online_exam->end_time = date('H:i:s', strtotime($request->end_time));
                $online_exam->end_date_time = date('Y-m-d H:i:s', strtotime($request->date . ' ' . $request->end_time));
                $online_exam->percentage = $request->percentage;
                $online_exam->instruction = $request->instruction;
                $online_exam->status = 0;
                if ($request->auto_mark) {
                    $online_exam->auto_mark = $request->auto_mark;
                }
                $online_exam->school_id = Auth::user()->school_id;
                $online_exam->academic_id = getAcademicId();
                $online_exam->save();
            }
            DB::commit();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $time_zone_setup=SmGeneralSettings::with('timeZone')->where('school_id',Auth::user()->school_id)
                        ->first();
        date_default_timezone_set(@$time_zone_setup->timeZone->time_zone);
        try{
            $online_exams = SmOnlineExam::where('school_id',Auth::user()->school_id)->get();
            $online_exam = SmOnlineExam::find($id);
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes= $teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $sections = SmAssignSubject::where('class_id',$online_exam->class_id)->where('subject_id',$online_exam->subject_id)->get();
            $subjects = SmAssignSubject::where('class_id',$online_exam->class_id)->where('section_id',$online_exam->section_id)->select('subject_id')->distinct('subject_id')->get();
            $present_date_time = date("Y-m-d H:i:s");
            $present_time = date("H:i:s");
            return view('backEnd.examination.online_exam', compact('online_exams', 'classes', 'sections', 'subjects', 'online_exam', 'present_date_time','present_time'));
        }catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function update(SmOnlineExamRequest $request, $id)
    {
        try{
            $online_exam = SmOnlineExam::find($id);
            $online_exam->title = $request->title;
            $online_exam->class_id = $request->class;
            $online_exam->section_id = $request->section;
            $online_exam->subject_id = $request->subject;
            $online_exam->date = date('Y-m-d', strtotime($request->date));
            $online_exam->start_time = date('H:i:s', strtotime($request->start_time));
            $online_exam->end_time = date('H:i:s', strtotime($request->end_time));
            $online_exam->end_date_time = date('Y-m-d H:i:s', strtotime($request->end_date . ' ' . $request->end_time));
            $online_exam->percentage = $request->percentage;
            $online_exam->instruction = $request->instruction;
            if ($request->auto_mark) {
                $online_exam->auto_mark = $request->auto_mark;
            }

            $online_exam->save();
            Toastr::success('Operation successful', 'Success');
            return redirect('online-exam');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function section()
    {
        try{
            $id = $_GET['id'];
            return response()->json(['response' => 'This is get method']);
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function delete(Request $request)
    {
        try{
            $id_key = 'online_exam_id';
            $id = $request->online_exam_id;
            $tables = \App\tableList::getTableList($id_key, $id);
            try {
                if ($tables==null) {
                    $delete_query = SmOnlineExam::findOrFail($request->online_exam_id);
                    if ($delete_query){
                        $delete_query->delete();
                    }
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                    Toastr::error($msg, 'Failed');
                    return redirect()->back();
                }
            } catch (\Illuminate\Database\QueryException $e) {
                $msg = 'This data already used in  : ' . $tables . ' Please remove those data first';
                Toastr::error($msg, 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function manageOnlineExamQuestion($id)
    {
        try{
            $online_exam = SmOnlineExam::find($id);
            $question_banks = SmQuestionBank::with('questionGroup')->where('class_id', $online_exam->class_id)
                            ->where('section_id', $online_exam->section_id)
                            ->get();
            $already_assigned = SmOnlineExamQuestionAssign::where('online_exam_id', $id)
                            ->pluck('question_bank_id')
                            ->toArray();
            return view('backEnd.examination.manage_online_exam', compact('online_exam', 'question_banks', 'already_assigned'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function manageOnlineExamQuestionStore(Request $request)
    {
        try{
            if ($request->question_type != 'M') {
                $online_question = new SmOnlineExamQuestion();
                $online_question->online_exam_id = $request->online_exam_id;
                $online_question->type = $request->question_type;
                $online_question->mark = $request->mark;
                $online_question->title = $request->question_title;
                $online_question->school_id = Auth::user()->school_id;
                $online_question->academic_id = getAcademicId();
                if ($request->question_type == "F") {
                    $online_question->suitable_words = $request->suitable_words;
                } elseif ($request->question_type == "T") {
                    $online_question->trueFalse = $request->trueOrFalse;
                }
                $result = $online_question->save();
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                DB::beginTransaction();
                try {
                    $online_question = new SmOnlineExamQuestion();
                    $online_question->online_exam_id = $request->online_exam_id;
                    $online_question->type = $request->question_type;
                    $online_question->mark = $request->mark;
                    $online_question->title = $request->question_title;
                    $online_question->school_id = Auth::user()->school_id;
                    $online_question->academic_id = getAcademicId();
                    $online_question->save();
                    $online_question->toArray();
                    $i = 0;
                    if (isset($request->option)) {
                        foreach ($request->option as $option) {
                            $i++;
                            $option_check = 'option_check_' . $i;
                            $online_question_option = new SmOnlineExamQuestionMuOption();
                            $online_question_option->online_exam_question_id = $online_question->id;
                            $online_question_option->title = $option;
                            $online_question_option->school_id = Auth::user()->school_id;
                            $online_question_option->academic_id = getAcademicId();
                            if (isset($request->$option_check)) {
                                $online_question_option->status = 1;
                            } else {
                                $online_question_option->status = 0;
                            }
                            $online_question_option->save();
                        }
                    }
                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamPublish($id)
    {
        try{
            $time_zone_setup=SmGeneralSettings::join('sm_time_zones','sm_time_zones.id','=','sm_general_settings.time_zone_id')
                ->where('school_id',Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);
            $present_date_time = date("Y-m-d H:i:s");
            $publish = SmOnlineExam::find($id);
            $class_id = $publish->class_id;
            $section_id = $publish->section_id;
            if ($present_date_time > $publish->end_date_time) {
                Toastr::error('Please update exam time', 'Failed');
                return redirect()->back();
            }
            $publish->status = 1;
            $publish->save();
            $student_ids = StudentRecord::where('class_id', $class_id)
                            ->where('section_id', $section_id)
                            ->where('is_promote', 0)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', Auth::user()->school_id)
                            ->pluck('student_id')
                            ->unique();

            $students = SmStudent::whereIn('id', $student_ids)
                        ->where('class_id', $class_id)
                        ->where('section_id', $section_id)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)
                        ->get();

            foreach ($students as $student) {
                $notification = new SmNotification;
                $notification->user_id = $student->user_id;
                $notification->role_id = 2;
                $notification->date = date('Y-m-d');
                $notification->message = 'New online exam published';
                $notification->url = 'student-online-exam';
                $notification->school_id = Auth::user()->school_id;
                $notification->academic_id = getAcademicId();
                $notification->save();

                $parent = SmParent::find($student->parent_id);
                $notidication = new SmNotification();
                $notidication->role_id = 3;
                $notidication->message = "New online exam published for your child";
                $notidication->date = date('Y-m-d');
                $notidication->user_id = $parent->user_id;
                $notification->school_id = Auth::user()->school_id;
                $notidication->academic_id = getAcademicId();
                $notidication->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamPublishCancel($id)
    {
        try{
            $publish = SmOnlineExam::find($id);
            $publish->status = 3;
            $publish->save();
            Toastr::error('Exam Expired', 'Failed');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineQuestionEdit($id, $type, $examId)
    {
        try{
            $online_exam_question = SmOnlineExamQuestion::find($id);
            return view('backEnd.examination.online_exam_question_edit', compact('id', 'type', 'examId', 'online_exam_question'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamQuestionEdit(Request $request)
    {
        try{
            if ($request->question_type != 'M') {
                if (checkAdmin()) {
                    $online_question = SmOnlineExam::find($request->id);
                }else{
                    $online_question = SmOnlineExam::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                }
                $online_question->online_exam_id = $request->online_exam_id;
                $online_question->type = $request->question_type;
                $online_question->mark = $request->mark;
                $online_question->title = $request->question_title;
                if ($request->question_type == "F") {
                    $online_question->suitable_words = $request->suitable_words;
                } elseif ($request->question_type == "T") {
                    $online_question->trueFalse = $request->trueOrFalse;
                }
                $result = $online_question->save();
                if ($result) {
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            } else {
                DB::beginTransaction();
                try {
                    if (checkAdmin()) {
                        $online_question = SmOnlineExamQuestion::find($request->id);
                    }else{
                        $online_question = SmOnlineExamQuestion::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
                    }
                    $online_question->online_exam_id = $request->online_exam_id;
                    $online_question->type = $request->question_type;
                    $online_question->mark = $request->mark;
                    $online_question->title = $request->question_title;
                    $online_question->save();
                    $online_question->toArray();
                    SmOnlineExamQuestionMuOption::where('online_exam_question_id', $online_question->id)->delete();
                    $i = 0;
                    if (isset($request->option)) {
                        foreach ($request->option as $option) {
                            $i++;
                            $option_check = 'option_check_' . $i;
                            $online_question_option = new SmOnlineExamQuestionMuOption();
                            $online_question_option->online_exam_question_id = $online_question->id;
                            $online_question_option->title = $option;
                            $online_question_option->school_id = Auth::user()->school_id;
                            $online_question_option->academic_id = getAcademicId();
                            if (isset($request->$option_check)) {
                                $online_question_option->status = 1;
                            } else {
                                $online_question_option->status = 0;
                            }
                            $online_question_option->save();
                        }
                    }

                    DB::commit();
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamQuestionDelete(Request $request)
    {
        try{
            if (checkAdmin()) {
                $online_exam_question = SmOnlineExamQuestion::find($request->id);
            }else{
                $online_exam_question = SmOnlineExamQuestion::where('id',$request->id)->where('school_id',Auth::user()->school_id)->first();
            }
            if ($online_exam_question->type == "M") {
                SmOnlineExamQuestionMuOption::where('online_exam_question_id', $online_exam_question->id)->delete();
                $online_exam_question->delete();
            } else {
                $online_exam_question->delete();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamMarksRegister($id)
    {
        try {
            $online_exam_question = SmOnlineExam::find($id);
            $studentRecords = StudentRecord::where('class_id', $online_exam_question->class_id)
                ->where('section_id', $online_exam_question->section_id)
                ->where('is_promote', 0)
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $student_ids = $studentRecords->pluck('student_id')->toArray();
            $present_students = [];
            $take_exam = SmStudentTakeOnlineExam::whereIn('student_id', $student_ids)
                ->where('online_exam_id', $online_exam_question->id)
                ->get();
            if ($take_exam != "") {
                $present_students = $take_exam->pluck('student_id')->toArray();
            }
            return view('backEnd.examination.online_exam_marks_register', compact('online_exam_question', 'present_students', 'studentRecords'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamMarksStore(Request $request)
    {
        try{
            if (checkAdmin()) {
                SmOnlineExamMark::where('exam_id', $request->exam_id)->delete();
            }else{
                SmOnlineExamMark::where('exam_id', $request->exam_id)->where('school_id',Auth::user()->school_id)->delete();
            }
            $counter = 0;
            foreach ($request->students as $student) {
                $counter++;
                $marks = 'marks_' . $counter;
                $abs = 'abs_' . $counter;
                $online_mark = new SmOnlineExamMark();
                $online_mark->exam_id = $request->exam_id;
                $online_mark->subject_id = $request->subject_id;
                $online_mark->student_id = $student;
                $online_mark->school_id = Auth::user()->school_id;
                $online_mark->academic_id = getAcademicId();
                if (isset($request->$abs)) {
                    $online_mark->abs = $request->$abs;
                } else {
                    $online_mark->marks = $request->$marks;
                    $online_mark->abs = 0;
                }
                $online_mark->save();
            }

            Toastr::success('Operation successful', 'Success');
            return redirect('online-exam');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamResult($id)
    {
        try{
            $online_exam_question = SmOnlineExam::find($id);
            $studentRecords = StudentRecord::where('class_id', $online_exam_question->class_id)
                ->where('section_id', $online_exam_question->section_id)
                ->where('is_promote', 0)
                ->where('school_id', Auth::user()->school_id)
                ->get();
            $student_ids = $studentRecords->pluck('student_id')->toArray();
            $present_students = [];
            $students = SmStudent::whereIn('id', $studentRecords->pluck('student_id')->toArray())->get();
            foreach ($students as $student) {
                $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                if ($take_exam != "") {
                    $present_students[] = $student->id;
                }
            }
            $total_marks = 0;
            foreach ($online_exam_question->assignQuestions as $assignQuestion) {
                $total_marks = $total_marks + $assignQuestion->questionBank->marks;
            }
            return view('backEnd.examination.online_exam_result_view', compact('online_exam_question', 'students', 'present_students', 'total_marks'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamQuestionAssign(Request $request)
    {
        try{
            if($request->checkbox){
                $assign = new SmOnlineExamQuestionAssign();
                $assign->online_exam_id = $request->online_exam_id;
                $assign->question_bank_id = $request->questions;
                $assign->school_id = Auth::user()->school_id;
                $assign->academic_id = getAcademicId();
                $assign->save();
            } else{
                SmOnlineExamQuestionAssign::where('question_bank_id', $request->questions)->delete();
                if (checkAdmin()) {
                    SmOnlineExamQuestionAssign::where('question_bank_id', $request->questions)->delete();
                }else{
                    SmOnlineExamQuestionAssign::where('question_bank_id', $request->questions)->where('school_id',Auth::user()->school_id)->delete();
                }
            }
            return response()->json('success');
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function viewOnlineQuestionModal($id)
    {
        try{
            $question_bank = SmQuestionBank::find($id);
            return view('backEnd.examination.online_eaxm_question_view_modal', compact('question_bank'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamMarking($exam_id, $s_id)
    {
        $online_exam_info=SmOnlineExam::find($exam_id);
        try{
            $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)
                ->where('student_id', $s_id)
                ->where('academic_id', getAcademicId())
                ->first();
            $assign_questions=SmOnlineExamQuestionAssign::where('online_exam_id', $exam_id)->get();
            $total_mark = 0;
            foreach($assign_questions as $q){
                $marks = SmQuestionBank::find($q->question_bank_id)->marks;
                $total_mark += $marks;
            }
            if ($online_exam_info->auto_mark==1) {
                $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('academic_id', getAcademicId())->first();
                return view('backEnd.examination.online_answer_auto_marking', compact('take_online_exam','online_exam_info','s_id','online_take_exam_mark','assign_questions','total_mark'));
            } else {
                $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('academic_id', getAcademicId())->first();
                return view('backEnd.examination.online_answer_marking', compact('take_online_exam','online_exam_info','s_id','online_take_exam_mark','assign_questions','total_mark'));
            }
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamMarkingStore(Request $request)
    {
        try{
            $exam_questions=SmOnlineExamQuestionAssign::where('online_exam_id',$request->online_exam_id)->get();
            foreach($exam_questions as $question){
                $question_id=$question->question_bank_id;
                $trueFalse = 'trueOrFalse_' . $question_id;
                $trueFalse = $request->$trueFalse;
                $suitable_words = 'suitable_words_' . $question_id;
                $suitable_words = $request->$suitable_words;
                $mcq_answer = 'options_' . $question_id;
                $mcq_answer = $request->$mcq_answer;
                $exam_info=SmOnlineExam::find($request->online_exam_id);
                $question_info=SmQuestionBank::find($question_id);
                $question_answer=OnlineExamStudentAnswerMarking::where('online_exam_id',$request->online_exam_id)
                    ->where('student_id',$request->student_id)->where('question_id',$question_id)->first();
                if ($question_answer==null) {
                    $question_answer=new OnlineExamStudentAnswerMarking();
                }
                $question_answer->online_exam_id=$exam_info->id;
                $question_answer->student_id=$request->student_id;
                $question_answer->question_id=$question_id;
                if ($question_info->type=='M') {
                    $question_answer->user_answer=$mcq_answer;
                    $currect_answer=$question_info->questionMu->where('status',1)->first();
                 
                    if ($mcq_answer!= null && $currect_answer && $mcq_answer== $currect_answer->id) {
                        $question_answer->answer_status=1;
                        $question_answer->obtain_marks=$question_info->marks;
                    }else{
                        $question_answer->answer_status=0;
                        $question_answer->obtain_marks=0;
                    }
                }
                if ($question_info->type=='MI') {
                    if ($question_info->answer_type=='radio') {
                        $question_answer->user_answer=$mcq_answer;
                        $currect_answer=$question_info->questionMu->where('status',1)->first();
                        if ($mcq_answer!= null && $mcq_answer==$currect_answer->id) {
                            $question_answer->answer_status=1;
                            $question_answer->obtain_marks=$question_info->marks;
                        }else{
                            $question_answer->answer_status=0;
                            $question_answer->obtain_marks=0;
                        }
                    } else {
                        $image_answers=OnlineExamStudentAnswerMarking::where('online_exam_id',$request->online_exam_id)
                            ->where('student_id',$request->student_id)->where('question_id',$question_id)->get();
                        $student_answers=[];
                        foreach ($image_answers as $key => $value) {
                            $student_answers[]=(int)$value->user_answer;
                        }
                        $question_answers=[];
                        foreach ($question_info->questionMu as $key => $value) {
                            if ($value->status==1) {
                                $question_answers[]=$value->id;
                            }
                        }
                        sort($student_answers);
                        sort($question_answers);
                        if ($student_answers===$question_answers) {
                            $question_answer->answer_status=1;
                            $question_answer->obtain_marks=$question_info->marks;
                        }else{
                            $question_answer->answer_status=0;
                            $question_answer->obtain_marks=0;
                        }
                    }
                }
                if ($question_info->type=='T') {
                    $question_answer->user_answer=$trueFalse;
                    $currect_answer=$question_info->trueFalse;
                    if ($trueFalse!=null && $trueFalse==$currect_answer) {
                        $question_answer->answer_status=1;
                        $question_answer->obtain_marks=$question_info->marks;
                    }else{
                        $question_answer->answer_status=0;
                        $question_answer->obtain_marks=0;

                    }
                }
                if ($question_info->type=='F') {
                    $question_answer->user_answer=$suitable_words;
                    $currect_answer=$question_info->suitable_words;
                    if ($suitable_words!=null && in_array($question_id,$request->marks)) {
                        $question_answer->answer_status=1;
                        $question_answer->obtain_marks=$question_info->marks;
                    }else{
                        $question_answer->answer_status=0;
                        $question_answer->obtain_marks=0;
                    }
                }
                $question_answer->school_id=Auth::user()->school_id;
                $question_answer->marked_by=Auth::user()->id;
                $question_answer->save();
            }
            $total_obtain_marks=OnlineExamStudentAnswerMarking::where('online_exam_id',$request->online_exam_id)
                ->where('student_id',$request->student_id)->sum('obtain_marks');
            $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $request->online_exam_id)->where('student_id', $request->student_id)->where('academic_id', getAcademicId())->first();
            $online_take_exam_mark->total_marks=$total_obtain_marks;
            $online_take_exam_mark->status=2;
            $online_take_exam_mark->save();
            $wrong=OnlineExamStudentAnswerMarking::where('user_answer','=','')->delete();

            Toastr::success('Operation successful', 'Success');
            return redirect('online-exam-marks-register/' . $request->online_exam_id);
        }catch (\Exception $e) {
             ;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamReport(Request $request)
    {
        try {
            if (moduleStatusCheck('OnlineExam')== TRUE) {
                $exams = InfixOnlineExam::where('active_status', 1)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)
                        ->get();
            } else {
                $exams = SmOnlineExam::get();
            }
            $classes = SmClass::get();
            return view('backEnd.reports.online_exam_report', compact('exams', 'classes'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamReportSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'exam' => 'required',
            'class' => 'required',
            'section' => 'required'
        ]);
        try{
            date_default_timezone_set("Asia/Dhaka");
            $present_date_time = date("Y-m-d H:i:s");
            $student_ids = SmStudentReportController::classSectionStudent($request);
            $students = SmStudent::whereIn('id', $student_ids)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            if ($students->count() == 0 && $online_exam == "") {
                Toastr::error('No Result Found', 'Failed');
                return redirect('online-exam-report');
            }
            if (moduleStatusCheck('OnlineExam')== TRUE) {
                $online_exam_question = InfixOnlineExam::find($request->exam);
                $online_exam = InfixOnlineExam::where('class_id', $request->class)->where('section_id', $request->section)->where('id', $request->exam)->where('end_date_time', '<', $present_date_time)->where('status', 1)->first();
                $present_students = [];
                foreach ($students as $student) {
                    $take_exam = InfixStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                    if ($take_exam != "") {
                        $present_students[] = $student->id;
                    }
                }
                $total_marks = 0;
                foreach ($online_exam_question->assignQuestions as $assignQuestion) {
                    $total_marks = $total_marks + $assignQuestion->questionBank->marks;
                }
                $exams = InfixOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            } else {
                $online_exam_question = SmOnlineExam::find($request->exam);
                $online_exam = SmOnlineExam::where('class_id', $request->class)->where('section_id', $request->section)->where('id', $request->exam)->where('end_date_time', '<', $present_date_time)->where('status', 1)->first();
                $present_students = [];
                foreach ($students as $student) {
                    $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $online_exam_question->id)->first();
                    if ($take_exam != "") {
                        $present_students[] = $student->id;
                    }
                }
                $total_marks = 0;
                foreach ($online_exam_question->assignQuestions as $assignQuestion) {
                    $total_marks = $total_marks + $assignQuestion->questionBank->marks;
                }
                $exams = SmOnlineExam::where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id',Auth::user()->school_id)->get();
            }
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $classes=$teacher_info->classes;
            } else {
                $classes = SmClass::get();
            }
            $class_id = $request->class;
            $section_id = $request->section;
            $exam_id = $request->exam;
            $clas = SmClass::find($request->class);
            $sec = SmSection::find($request->section);
            return view('backEnd.reports.online_exam_report', compact('online_exam_question', 'students', 'present_students', 'total_marks', 'exams', 'classes', 'class_id', 'exam_id', 'section_id', 'clas','sec'));
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function onlineExamDatatable()
    {
        try {
            if (teacherAccess()) {
                $teacher_info=SmStaff::where('user_id',Auth::user()->id)->first();
                $online_exams = SmOnlineExam::query();
                $online_exams->where('status', '!=', 2)
                    ->join('sm_assign_subjects','sm_assign_subjects.subject_id','=','sm_online_exams.subject_id')
                    ->where('sm_assign_subjects.teacher_id', $teacher_info->id)
                    ->where('sm_online_exams.academic_id', getAcademicId())
                    ->where('sm_online_exams.school_id',Auth::user()->school_id)
                    ->select('sm_online_exams.*')
                    ->distinct('id');
            } else {
                $online_exams = SmOnlineExam::query();
                $online_exams->with('class','section','subject')->where('status', '!=', 2);
            }
            return Datatables::of($online_exams)
                    ->addIndexColumn()
                    ->addColumn('class_section', function($row){
                        $btn = ($row->class != '' && $row->section != '') ? $row->class->class_name . '(' . $row->section->section_name . ')' : '' ;
                        return $btn;
                    })
                    ->addColumn('subject_name', function($row){
                        $btn = ($row->subject != '') ? $row->subject->subject_name : '' ;
                        return $btn;
                    })
                    ->addColumn('exam_time', function($row){
                        $btn = (($row->date != '') ? dateConvert($row->date) : '') . '<br>' . __('exam.time') . ':' . date('h:i A', strtotime($row->start_time)) . '-' . (($row->end_date_time != 'NULL') ? date('h:i A', strtotime($row->end_date_time)) : 'Unlimited');
                        return $btn;
                    })
                    ->addColumn('duration', function($row){
                        $totalDuration = $row->end_time != 'NULL' ? Carbon::parse($row->end_time)->diffinminutes(Carbon::parse($row->start_time)) : 0;
                        $btn = ($row->end_time != 'NULL') ? gmdate($totalDuration) : 'Unlimited' . 'Min';
                        return $btn;
                    })
                    ->addColumn('status_button', function($row){
                        if ($row->status == 0){
                            $btn = '<button class="primary-btn small bg-warning text-white border-0">' . __('common.pending') . '</button>';
                        } else{
                            $btn = '<button class="primary-btn small bg-success text-white border-0">' . __('exam.published') . '</button>';
                        }
                        return $btn;
                    })
                    ->addColumn('action', function($row){
                        $view = view('backEnd.examination.online_exam_action', compact('row'));
                        return (string)$view;
                    })
                    ->rawColumns(['status_button', 'exam_time', 'action', 'date'])
                    ->make(true);
        }catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}