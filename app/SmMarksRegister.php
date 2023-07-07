<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SmMarksRegister extends Model
{
    use HasFactory;
    public function marksRegisterChilds(){
    	return $this->hasMany('App\SmMarksRegisterChild', 'marks_register_id', 'id');
    }

    public static function marksRegisterChild($student, $exam, $class, $section){
    	
        try {
            $marks_register_id = SmMarksRegister::where('student_id', $student)->where('exam_id', $exam)->where('class_id', $class)->where('section_id', $section)->first();
                if($marks_register_id != ""){
                    return SmMarksRegisterChild::where('marks_register_id', $marks_register_id->id)->get();
                }
                return array();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public function studentInfo(){
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }

    public static function subjectDetails($exam, $class, $section, $subject){
    	
        try {
            $exam_schedule = SmExamSchedule::where('exam_id', $exam)->where('class_id', $class)->where('section_id', $section)->first();
            return SmExamScheduleSubject::where('exam_schedule_id', $exam_schedule->id)->where('subject_id', $subject)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function highestMark($exam_id, $subject_id, $section_id, $class_id){
        
        try {
            $highest_mark = DB::table('sm_result_stores')
                                    ->where('section_id', $section_id)
                                    ->where('class_id', $class_id)
                                    ->where('exam_type_id', $exam_id)
                                    ->where('subject_id', $subject_id)
                                    ->max('total_marks');

                return $highest_mark;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }                  
    }


    public static function is_absent_check($exam_id, $class_id, $section_id, $subject_id, $student_id, $record_id)
    {
            $exam_attendance = SmExamAttendance::where('exam_id', $exam_id)->where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->first();
            if ($exam_attendance) {
                return SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)->where('student_id', $student_id)->where('student_record_id', $record_id)->first();
            }
            return null;
    }

    public static function un_is_absent_check($exam_id, $request, $subject_id, $student_id, $record_id)
    {
        $SmExamAttendance = SmExamAttendance::query();
        $exam_attendance = universityFilter($SmExamAttendance, $request)
                            ->where('exam_id', $exam_id)
                            ->where('un_subject_id', $subject_id)
                            ->orWhereNull('un_section_id')
                            ->first();
                          
       
            if ($exam_attendance) {
                return SmExamAttendanceChild::where('exam_attendance_id', $exam_attendance->id)
                    ->where('student_id', $student_id)
                    ->where('student_record_id', $record_id)
                    ->first();
            }
            return null;
    }
}
