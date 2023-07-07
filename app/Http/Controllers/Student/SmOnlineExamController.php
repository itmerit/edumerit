<?php

namespace App\Http\Controllers\Student;

use App\SmStudent;
use App\YearCheck;
use App\SmOnlineExam;
use App\ApiBaseMethod;
use App\SmNotification;
use App\SmQuestionBank;
use App\SmAssignSubject;
use App\SmGeneralSettings;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmQuestionBankMuOption;
use App\SmStudentTakeOnlineExam;
use Illuminate\Support\Facades\DB;
use App\SmOnlineExamQuestionAssign;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use App\SmStudentTakeOnlnExQuesOption;
use App\OnlineExamStudentAnswerMarking;
use App\SmStudentTakeOnlineExamQuestion;

class SmOnlineExamController extends Controller
{

    public function __construct()
    {
        $this->middleware('PM');
        // User::checkAuth();
    }

    public function studentOnlineExam()
    {
        try {
            $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                ->where('school_id', Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);
            $student = Auth::user()->student;
            $records = studentRecords(null, $student->id)->get();
            return view('backEnd.studentPanel.online_exam', compact('student', 'records'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function student_online_exam(Request $request)
    {
        try {
            $online_exam_info=SmOnlineExam::find($request->online_exam_id);
            $teacher_info=SmAssignSubject::where('class_id',$online_exam_info->class_id)
            ->where('section_id',$online_exam_info->section_id)
            ->where('subject_id',$online_exam_info->subject_id)
            ->first();
            $obtain_marks=DB::table('online_exam_student_answer_markings')
                ->where('online_exam_id',$request->online_exam_id)
                ->where('student_id', Auth::user()->student->id)
                ->sum('obtain_marks');
            $question_types_array=[];
            if ($online_exam_info->auto_mark==1) {
                $question_types=DB::table('sm_online_exam_question_assigns')->where('online_exam_id',$request->online_exam_id)
                ->leftjoin('sm_question_banks','sm_question_banks.id','=','sm_online_exam_question_assigns.question_bank_id')
                ->get();
                foreach ($question_types as $key => $question) {
                    $question_types_array[]=$question->type;
                }

                if (!in_array('F',$question_types_array)) {
                   $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $request->online_exam_id)
                        ->where('student_id', Auth::user()->student->id)->first();
         
                    $online_take_exam_mark->total_marks=$obtain_marks;
                    $online_take_exam_mark->status=2;
                    $online_take_exam_mark->save();
                }
            }
           
            $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $request->online_exam_id)
            ->where('student_id', Auth::user()->student->id)->first();
            $online_take_exam_mark->student_done=1;
            $online_take_exam_mark->save();
            
            
            $notification = new SmNotification;
            $notification->user_id = $teacher_info->teacher->user_id;
            $notification->role_id = $teacher_info->teacher->role_id;
            $notification->date = date('Y-m-d');
            $notification->message = Auth::user()->student->full_name .' Submit answer for '.$online_exam_info->title;
            $notification->school_id = Auth::user()->school_id;
            $notification->academic_id = getAcademicId();
            $notification->save();

            Toastr::success('Online Exam Taken Successfully', 'Success');
            return redirect('student-online-exam');
        } catch (\Exception $e) {
           
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function takeOnlineExam($id)
    {
        try {

            $online_exam = SmOnlineExam::find($id);

            $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)
            ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)
            ->get();
            return view('backEnd.studentPanel.take_online_exam', compact('online_exam', 'assigned_questions'));
            
            // if (moduleStatusCheck('MultipleImageQuestion')== TRUE) {
            //     $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)
            //     ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)
            //     ->simplePaginate(1);
            //     return view('multipleimagequestion::take_exam', compact('online_exam', 'assigned_questions'));
            // } else {
            //     $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            //     return view('backEnd.studentPanel.take_online_exam', compact('online_exam', 'assigned_questions'));
            // }

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function autoMarking($online_exam_id, $student_id, $question_id, $user_choose,$ajax_post)
    {

        
        $exam_info = SmOnlineExam::find($online_exam_id);
        $question_info = SmQuestionBank::find($question_id);

        if ($question_info->type != 'MI') {
            $question_answer = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)->where('student_id', $student_id)->where('question_id', $question_id)->first();
       
        } else {
            if ($question_info->answer_type=='radio') {
                $question_answer = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)->where('student_id', $student_id)->where('question_id', $question_id)->first();
       
            } else {
                $question_answer = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                ->where('student_id', $student_id)
                ->where('question_id', $question_id)
                ->where('user_answer', $user_choose)
                ->first();
           
            }
        }
        
        if ($question_answer == null) {
            $question_answer = new OnlineExamStudentAnswerMarking();
        }
        $question_answer->online_exam_id = $online_exam_id;
        $question_answer->student_id = $student_id;
        $question_answer->question_id = $question_id;
        $question_answer->user_answer = $user_choose;
        if ($question_info->type == 'M') {
            $currect_answer = $question_info->questionMu->where('status', 1)->first();
            if ($currect_answer && $user_choose == $currect_answer->id) {
                $question_answer->answer_status = 1;
                $question_answer->obtain_marks = $question_info->marks;
            } else {
                $question_answer->answer_status = 0;
                $question_answer->obtain_marks = 0;
            }
            // return $currect_answer;
        }
        if ($question_info->type == 'MI') {
            if ($question_info->answer_type=='radio') {
                // return $question_info;
                $currect_answer = $question_info->questionMu->where('status', 1)->first();
                if ($currect_answer && $user_choose == $currect_answer->id) {
                    $question_answer->answer_status = 1;
                    $question_answer->obtain_marks = $question_info->marks;
                } else {
                    $question_answer->answer_status = 0;
                    $question_answer->obtain_marks = 0;
                }
            } else {
                $question_answer->save();
                if ($ajax_post->submit_value==null) {
                    $user_post = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                    ->where('student_id', $student_id)
                    ->where('question_id', $question_id)
                    ->where('user_answer', $ajax_post->option)
                    ->first();
                    DB::table('online_exam_student_answer_markings')->delete($user_post->id);
                }
                $wrong=OnlineExamStudentAnswerMarking::where('user_answer','=','')->delete();
                $user_answers = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                    ->where('student_id', $student_id)
                    ->where('question_id', $question_id)
                    ->get();
                $student_given_answer=[];
                foreach ($user_answers as $key => $value) {
                    $student_given_answer[]=(int)$value->user_answer;
                }
                // return $student_given_answer;
                $currect_answers =SmQuestionBankMuOption::where('question_bank_id',$question_info->id)->where('status', 1)
                ->get();
                $question_currect_answer=[];
                foreach ($currect_answers as $key => $value) {
                    $question_currect_answer[]=$value->id;
                }
                
                sort($student_given_answer);
                sort($question_currect_answer);

                $answer_marking = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)->where('student_id', $student_id)->where('question_id', $question_id)->first();
       
                if ($student_given_answer===$question_currect_answer) {
                    $answer_marking->answer_status = 1;
                    $answer_marking->obtain_marks = $question_info->marks;
                } else {
                    
                    $answer_marking->answer_status = 0;
                    $answer_marking->obtain_marks = 0;

                    
                    $wrong = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                    ->where('student_id', $student_id)
                    ->where('question_id', $question_id)
                    ->update(array('obtain_marks' => 0,'answer_status' => 0));
                }
                $answer_marking->save();
                
            }
            
            
            // return $currect_answer;
        }
        if ($question_info->type == 'T') {
            $currect_answer = $question_info->trueFalse;
            if ($user_choose == $currect_answer) {
                $question_answer->answer_status = 1;
                $question_answer->obtain_marks = $question_info->marks;
            } else {
                $question_answer->answer_status = 0;
                $question_answer->obtain_marks = 0;
            }
        }

        $question_answer->save();
    }
    public function questionAnswer($online_exam_id, $student_id, $question_id, $user_choose,$ajax_post)
    {

        $exam_info = SmOnlineExam::find($online_exam_id);
        $question_info = SmQuestionBank::find($question_id);

        if ($question_info->type == 'MI') {
            if ($question_info->answer_typr!='radio') {

                if ($ajax_post->submit_value==null) {
                    $user_post = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                    ->where('student_id', $student_id)
                    ->where('question_id', $question_id)
                    ->where('user_answer', $ajax_post->option)
                    ->first();
                    DB::table('online_exam_student_answer_markings')->delete($user_post->id);
                }
                $wrong=OnlineExamStudentAnswerMarking::where('user_answer','=','')->delete();
            }
                $question_answer = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)
                ->where('student_id', $student_id)
                ->where('question_id', $question_id)
                ->where('user_answer', $user_choose)
                ->first();
                if ($question_answer == null) {
                    $question_answer = new OnlineExamStudentAnswerMarking();
                }
        } else {
            $question_answer = OnlineExamStudentAnswerMarking::where('online_exam_id', $online_exam_id)->where('student_id', $student_id)->where('question_id', $question_id)->first();
            if ($question_answer == null) {
                $question_answer = new OnlineExamStudentAnswerMarking();
            }
        }
        
      
        $question_answer->online_exam_id = $online_exam_id;
        $question_answer->student_id = $student_id;
        $question_answer->question_id = $question_id;
        $question_answer->user_answer = $user_choose;
        $question_answer->save();
        return $question_answer;
    }
    public function studentOnlineExamSubmit(Request $request)
    {
        
        $student = Auth::user()->student;
        $exam_status = SmStudentTakeOnlineExam::where('student_id', $student->id)->where('online_exam_id', $request->online_exam_id)->where('school_id', Auth::user()->school_id)->first();
        if (!empty($exam_status) &&  $exam_status->status == 1) {
            Toastr::warning('You are already participated this exam', 'Failed');
            return redirect()->back();
        }
        DB::beginTransaction();

        try {
            $online_exam = SmOnlineExam::findOrFail($request->$request->online_exam_id);
            
            $record_id = studentRecords($request->merge(['class'=>$online_exam->class_id, 'section'=>$online_exam->section_id]), null)->first()->id;
            $take_online_exam = new SmStudentTakeOnlineExam();
            $take_online_exam->online_exam_id = $request->online_exam_id;
            $take_online_exam->student_id = $student->id;
            $take_online_exam->record_id = $record ?? null;
            $take_online_exam->status = 1;
            $take_online_exam->school_id = Auth::user()->school_id;
            $take_online_exam->academic_id =  getAcademicId();
            $take_online_exam->save();
            $take_online_exam->toArray();

            $question_types = [];
            foreach ($request->question_ids as $key => $question_id) {
                $question_bank = SmQuestionBank::find($question_id);
                $trueFalse = 'trueOrFalse_' . $question_id;
                $trueFalse = $request->$trueFalse;

                $suitable_words = 'answer_word_' . $question_id;
                $suitable_words = $request->$suitable_words;

                $exam_question = new SmStudentTakeOnlineExamQuestion();
                $exam_question->take_online_exam_id = $take_online_exam->id;
                $exam_question->question_bank_id = $question_id;
                $exam_question->trueFalse = $trueFalse;
                $exam_question->suitable_words = $suitable_words;
                $exam_question->school_id = Auth::user()->school_id;
                $exam_question->academic_id =  getAcademicId();
                $exam_question->save();
                $exam_question->toArray();

                $question_types[$key] = $question_bank->type;

                $online_exam_info = SmOnlineExam::find($request->online_exam_id);

                if ($question_bank->type == "T") {
                    if ($online_exam_info->auto_mark == 1) {
                        $this->autoMarking($request->online_exam_id, $student->id, $question_id, $request['trueOrFalse_' . $question_id],$request);
                    } else {
                        $this->questionAnswer($request->online_exam_id, $student->id, $question_id, $request['trueOrFalse_' . $question_id],$request);
                    }
                } else if ($question_bank->type == "M") {
                    $question_options = SmQuestionBankMuOption::where('question_bank_id', $question_bank->id)
                        ->where('academic_id', getAcademicId())
                        ->where('school_id', Auth::user()->school_id)
                        ->get();

                    $i = 0;
                    $total_marks = 0;
                    foreach ($question_options as $question_option) {
                        $options = 'options_' . $question_id . '_' . $i++;
                        $exam_question_option = new SmStudentTakeOnlnExQuesOption();
                        $exam_question_option->take_online_exam_question_id = $exam_question->id;
                        $exam_question_option->title = $question_option->title;
                        $exam_question_option->school_id = Auth::user()->school_id;
                        $exam_question_option->academic_id =  getAcademicId();
                        if (isset($request->$options)) {
                            $exam_question_option->status = $request->$options;
                        } else {
                            $exam_question_option->status = 0;
                        }
                        $exam_question_option->save();
                    }
                    if ($online_exam_info->auto_mark == 1) {
                         $this->autoMarking($request->online_exam_id, $student->id, $question_id, $request['options_' . $question_id],$request);
                    } else {
                        $this->questionAnswer($request->online_exam_id, $student->id, $question_id, $request['options_' . $question_id],$request);
                    }
                } else {
                    $this->questionAnswer($request->online_exam_id, $student->id, $question_id, $request['answer_word_' . $question_id],$request);
                }
            }
            if (!in_array("F", $question_types) && $online_exam_info->auto_mark == 1) {
                $online_take_exam_mark = SmStudentTakeOnlineExam::where('online_exam_id', $request->online_exam_id)
                    ->where('student_id', $student->id)->where('academic_id', getAcademicId())->first();
                // return Auth::user()->id;
                if ($online_take_exam_mark) {
                    $total_marks = 0;
                    if (isset($request->marks)) {
                        foreach ($request->marks as $mark) {
                            $question_marks = SmQuestionBank::select('marks')->where('id', $mark)->where('academic_id', getAcademicId())->first();
                            $total_marks = $total_marks + $question_marks->marks;
                        }
                    }
                    $online_take_exam_mark->total_marks = $total_marks;
                    $online_take_exam_mark->status = 2;
                    $online_take_exam_mark->save();
                }
            }
            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect('student-online-exam');
        } catch (\Exception $e) {
            DB::rollBack();
        }
        Toastr::error('Operation Failed', 'Failed');
        return redirect()->back();
    }
    public function AjaxStudentOnlineExamSubmit(Request $request)
    {
        $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                ->where('school_id', Auth::user()->school_id)->first();
            date_default_timezone_set($time_zone_setup->time_zone);

        $online_exam = SmOnlineExam::find($request->online_exam_id);
        $question_info = SmQuestionBank::find($request->question_id);
        $student = Auth::user()->student;
        $startTime = strtotime($online_exam->date . ' ' . $online_exam->start_time);
        $endTime = strtotime($online_exam->date . ' ' . $online_exam->end_time);
        $now = date('h:i:s');
        $now =  strtotime("now");

        if ($now >= $endTime) {
            $msg['message'] = "Exam Time Is Finish";
            $msg['title'] = "Warning";
            $msg['type'] = "warning";
        } else {
            $student = Auth::user()->student;

            $record_id = studentRecords($request->merge(['class'=>$online_exam->class_id,'section'=>$online_exam->section_id]), null)->first('id')->id;
          
            $take_exam = SmStudentTakeOnlineExam::where('student_id', $student->id)
                ->where('online_exam_id', $request->online_exam_id)->where('school_id', Auth::user()->school_id)->first();
            if (empty($take_exam)) {
                $take_exam = new SmStudentTakeOnlineExam();
                $take_exam->student_id = $student->id;
                $take_exam->record_id = $record_id;
                $take_exam->online_exam_id = $request->online_exam_id;
                $take_exam->school_id = Auth::user()->school_id;
                $take_exam->academic_id = getAcademicId();
                $take_exam->save();
            }



            if ($question_info != 'F') {
                if ($online_exam->auto_mark == 1) {
                    $this->autoMarking($request->online_exam_id, $student->id, $request->question_id, $request->submit_value,$request);
                } else {
                    $this->questionAnswer($request->online_exam_id, $student->id, $request->question_id, $request->submit_value,$request);
                }
            } else {
                $this->questionAnswer($request->online_exam_id, $student->id, $request->question_id, $request->submit_value,$request);
            }

            
            //SmStudentTakeOnlineExamQuestion

            $question_bank = SmQuestionBank::find($request->question_id);



            $exam_question =SmStudentTakeOnlineExamQuestion::where('take_online_exam_id',$take_exam->id)->where('question_bank_id',$request->question_id)->first();
            if ($exam_question=="") {
                $exam_question = new SmStudentTakeOnlineExamQuestion();
                $exam_question->take_online_exam_id = $take_exam->id;
                $exam_question->question_bank_id = $request->question_id;
    
                if ($question_bank->type == 'T') {
                    $exam_question->trueFalse = $request->submit_value;
                }
                if ($question_bank->type == 'F') {
                    $exam_question->suitable_words = $request->submit_value;
                }
    
                $exam_question->school_id = Auth::user()->school_id;
                $exam_question->academic_id =  getAcademicId();
                $exam_question->save();
            }
          


            $msg['message'] =Str::limit($question_info->question,50,$end='...') . ' Submited';
            $msg['title'] = "Successful";
            $msg['type'] = "success";

            $obtain_marks = OnlineExamStudentAnswerMarking::where('online_exam_id', $request->online_exam_id)->where('student_id', $student->id)->get();
            $total_marks = 0.00;
            foreach ($obtain_marks as $key => $mark) {
                $total_marks += $mark->obtain_marks;
            }
            $take_exam->status = 1;
            $take_exam->total_marks = $total_marks;
            $take_exam->save();
        }

        return response()->json($msg);
    }

    public function studentViewResult()
    {
        try {
            $time_zone_setup = SmGeneralSettings::join('sm_time_zones', 'sm_time_zones.id', '=', 'sm_general_settings.time_zone_id')
                ->where('school_id', Auth::user()->school_id)->first();

            $result_views = SmStudentTakeOnlineExam::where('active_status', 1)->where('status', 2)
                ->where('academic_id', getAcademicId())
                ->where('student_id', @Auth::user()->student->id)
                ->where('school_id', Auth::user()->school_id)
                ->get();

            return view('backEnd.studentPanel.student_view_result', compact('result_views', 'time_zone_setup'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function viewOnlineExam($id)
    {
        try {
            $online_exam = SmOnlineExam::find($id);
            $assigned_questions = SmOnlineExamQuestionAssign::where('online_exam_id', $online_exam->id)
            ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();

            return view('backEnd.studentPanel.view_online_question', compact('online_exam', 'assigned_questions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentAnswerScript($exam_id, $s_id)
    { 
        try {
            
            $assign_questions=SmOnlineExamQuestionAssign::where('online_exam_id', $exam_id)->get();

            $take_online_exam = SmStudentTakeOnlineExam::where('online_exam_id', $exam_id)->where('student_id', $s_id)->where('school_id', Auth::user()->school_id)->first();
           
            $assigned_question_list=[];
               $assigneds= SmOnlineExamQuestionAssign::where('online_exam_id', $exam_id)->select('question_bank_id')->get();
               
               foreach ($assigneds as $key => $value) {
                    $assigned_question_list[]= $value->question_bank_id;
               }
            $answered_question_list=[];
            $answereds=OnlineExamStudentAnswerMarking::where('online_exam_id', $exam_id)->where('student_id', $s_id)->select('question_id')->distinct()->get();
               foreach ($answereds as $key => $value) {
                $answered_question_list[]= $value->question_id;
               }
           $array_diff=array_diff($assigned_question_list,$answered_question_list);

           $skipped_questions=[];
           if ($array_diff!=null) {
                $skipped_questions=SmOnlineExamQuestionAssign::whereIn('question_bank_id', array($array_diff))->get();
           }
        //    $skipped_questions=SmQuestionBank::whereIn('id', array($array_diff))->get();
           
    //  return $take_online_exam;
            return view('backEnd.examination.online_answer_view_script_modal', compact('take_online_exam', 's_id','skipped_questions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function studentOnlineExamApi(Request $request, $id)
    {

        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $data = [];

                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $now = date('H:i:s');
                $today = date('Y-m-d');

                $online_exams = SmOnlineExam::where('sm_online_exams.status', '=', 1)
                    ->join('sm_subjects', 'sm_online_exams.class_id', '=', 'sm_subjects.id')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('end_time', '>', $now)
                    ->where('date', '=', $today)
                    ->select('sm_online_exams.id as exam_id', 'sm_online_exams.title as exam_title', 'sm_subjects.subject_name', 'sm_online_exams.date', 'sm_online_exams.status as onlineExamStatus', 'sm_online_exams.status as onlineExamTakeStatus')
                    ->where('sm_online_exams.school_id', Auth::user()->school_id)->get();
                $examStatus = '0 = Pending , 1 Published';
                $examTakenStatus = '0 = Take Exam , 1 = Alreday Submitted';
                $data['online_exams'] = $online_exams->toArray();
                $data['online_exams_status'] = $examStatus;
                $data['onlineExamTakenStatus'] = $examTakenStatus;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function chooseExamApi(Request $request, $id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $student_exams = DB::table('sm_online_exams')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('school_id', $student->school_id)
                    ->select('sm_online_exams.title as exam_name', 'id as exam_id')
                    ->where('school_id', Auth::user()->school_id)->get();
                return ApiBaseMethod::sendResponse($student_exams, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function examResultApi(Request $request, $id, $exam_id)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $student = SmStudent::where('user_id', $id)->where('school_id', Auth::user()->school_id)->first();

                $student_exams = DB::table('sm_online_exams')
                    ->where('class_id', $student->class_id)
                    ->where('section_id', $student->section_id)
                    ->where('school_id', $student->school_id)
                    ->select('sm_online_exams.title as exam_name', 'sm_online_exams.id as exam_id')
                    ->where('school_id', Auth::user()->school_id)->get();
                $exam_result = DB::table('sm_student_take_online_exams')
                    ->join('sm_online_exams', 'sm_online_exams.id', '=', 'online_exam_id')
                    ->join('sm_subjects', 'sm_online_exams.subject_id', '=', 'sm_subjects.id')
                    ->where('sm_student_take_online_exams.student_id', $student->id)
                    ->where('sm_student_take_online_exams.school_id', $student->school_id)
                    ->where('sm_online_exams.id', $exam_id)
                    ->where('sm_online_exams.status', '=', 1)
                    ->where('sm_online_exams.school_id', Auth::user()->school_id)
                    ->select(
                        'sm_online_exams.title as exam_name',
                        'sm_online_exams.id as exam_id',
                        'sm_subjects.subject_name',
                        'sm_student_take_online_exams.total_marks as obtained_marks',
                        'sm_online_exams.percentage as pass_mark_percentage',
                        'sm_student_take_online_exams.total_marks'
                    )
                    ->get();
                $gradeArray = [];
                foreach ($exam_result  as $row) {

                    $mark = floor($row->obtained_marks);
                    $grades = DB::table('sm_marks_grades')
                        ->where('percent_from', '<=', $mark)
                        ->where('percent_upto', '>=', $mark)
                        ->select('grade_name')
                        ->where('school_id', Auth::user()->school_id)->first();
                    $gradeArray[] = array(
                        "grade" => $grades->grade_name,
                        "exam_id" => $row->exam_id,
                        "total_marks" => $row->total_marks,
                        "subject_name" => $row->subject_name,
                        "obtained_marks" => $row->obtained_marks,
                        "pass_mark" => $row->pass_mark_percentage,
                        "exam_name" => $row->exam_name
                    );
                }
                $data['student_exams'] = $student_exams->toArray();
                $data['exam_result'] = $gradeArray;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function getGrades(Request $request, $marks)
    {
        try {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $grades = DB::table('sm_marks_grades')
                    ->where('percent_from', '<=', floor($marks))
                    ->where('percent_upto', '>=', floor($marks))
                    ->select('grade_name')
                    ->where('school_id', Auth::user()->school_id)->first();
                return ApiBaseMethod::sendResponse($grades, null);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}
