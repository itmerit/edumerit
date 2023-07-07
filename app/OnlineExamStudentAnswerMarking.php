<?php

namespace App;

use App\OnlineExamStudentAnswerMarking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineExamStudentAnswerMarking extends Model
{
    use HasFactory;
    public static function StudentGivenAnswer($online_exam_id,$question_id,$student_id){
        // return $question_id;
        $student_answer=OnlineExamStudentAnswerMarking::where('online_exam_id',$online_exam_id)->where('question_id',$question_id)->where('student_id',$student_id)->first();
        return $student_answer;
    }
    public static function StudentImageAnswer($online_exam_id,$question_id,$student_id){
        // return $question_id;
        $student_answer_list=[];
        $student_answer=OnlineExamStudentAnswerMarking::where('online_exam_id',$online_exam_id)->where('question_id',$question_id)->where('student_id',$student_id)->get();
        foreach ($student_answer as $key => $value) {
            $student_answer_list[]=$value->user_answer;
        }
        return $student_answer_list;
    }
    public static function StudentImageAnswerStatus($online_exam_id,$question_id,$student_id){
        // return $question_id;
        $student_answer_status=OnlineExamStudentAnswerMarking::where('online_exam_id',$online_exam_id)
        ->where('question_id',$question_id)
        ->where('student_id',$student_id)
        ->where('answer_status',1)
        ->first();
        if ($student_answer_status) {
            return 1;
        } else {
            return 0;
            # code...
        }
        
    }
}
