<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmClassRoutine extends Model
{
    use HasFactory;
    public function subject(){
    	return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
    }

    public function class(){
    	return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function section(){
    	return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    
    public static function teacherId($class_id, $section_id, $subject_id){
    	
        try {
            return SmAssignSubject::where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }
}
