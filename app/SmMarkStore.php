<?php

namespace App;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmMarkStore extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new AcademicSchoolScope);
    }
    
    public function class(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
     public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }

    public function subjectName()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }
 
    public static function get_mark_by_part($student_id, $exam_id, $class_id, $section_id, $subject_id, $exam_setup_id, $record_id){
    	
        try {
            $getMark= SmMarkStore::where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id], 
                ['class_id',$class_id], 
                ['section_id',$section_id], 
                ['exam_setup_id',$exam_setup_id], 
                ['student_record_id', $record_id], 
                ['subject_id',$subject_id]
            ])->first();
            if(!empty($getMark)){
                $output= $getMark->total_marks;
            }else{
                $output= '0';
            }

            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }


    public static function un_get_mark_by_part($student_id, $request, $exam_id, $subject_id, $exam_setup_id, $record_id)
    {
        try {
            $SmMarkStore = SmMarkStore::query();
            $getMark = universityFilter($SmMarkStore, $request)
                ->where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id], 
                ['exam_setup_id',$exam_setup_id], 
                ['student_record_id', $record_id], 
                ['un_subject_id',$subject_id]
            ])->first();
            
            if(!empty($getMark)){
                $output= $getMark->total_marks;
            }else{
                $output= '0';
            }
            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function is_absent_check($student_id, $exam_id, $class_id, $section_id, $subject_id, $record_id)
    {
        
        try {
            $getMark= SmMarkStore::where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id], 
                ['class_id',$class_id], 
                ['student_record_id', $record_id], 
                ['section_id',$section_id], 
                ['subject_id',$subject_id]
            ])->first();
            if (!empty($getMark)) {
                $output= $getMark->is_absent;
            } else {
                $output= '0';
            }
            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function un_is_absent_check($student_id, $exam_id, $request, $subject_id, $record_id)
    {
        try {
            $SmMarkStore = SmMarkStore::query();
            $getMark = universityFilter($SmMarkStore, $request)
            ->where([
                ['student_id',$student_id], 
                ['exam_term_id',$exam_id],
                ['student_record_id', $record_id], 
                ['subject_id',$subject_id]
            ])->first();
            if (!empty($getMark)) {
                $output= $getMark->is_absent;
            } else {
                $output= '0';
            }
            return $output;
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function teacher_remarks($student_id, $exam_id, $class_id, $section_id, $subject_id, $record_id) {
        
        $getMark= SmMarkStore::where([
            ['student_id',$student_id], 
            ['exam_term_id',$exam_id], 
            ['class_id',$class_id], 
            ['section_id',$section_id], 
            ['student_record_id', $record_id], 
            ['subject_id',$subject_id]
        ])->first();

        if($getMark != ""){
            $output= $getMark->teacher_remarks;
        }else{
            $output= '';
        }

        return $output;
    }

    public static function un_teacher_remarks($student_id, $exam_id, $request, $subject_id, $record_id) {
        
        $SmMarkStore = SmMarkStore::query();
            $getMark = universityFilter($SmMarkStore, $request)
            ->where([
            ['student_id',$student_id], 
            ['exam_term_id',$exam_id],
            ['student_record_id', $record_id], 
            ['un_subject_id',$subject_id]
        ])->first();

        if($getMark != ""){
            $output= $getMark->teacher_remarks;
        }else{
            $output= '';
        }

        return $output;
    }

    public static function allMarksArray($exam_id, $class_id, $section_id, $subject_id)
    {
        $all_student_marks = [];

        $marks = SmResultStore::where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->where('exam_type_id', $exam_id)->get();

        foreach($marks as $mark){
            $all_student_marks[] = $mark->total_marks;
        }


        return $all_student_marks;

    }

}
