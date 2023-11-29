<?php

namespace App;

use App\Scopes\AcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmHomeworkStudent extends Model
{
    use HasFactory;
    protected static function boot(){
        parent::boot();
    }

    public function studentInfo(){
        return $this->belongsTo('App\SmStudent', 'student_id', 'id');
    }
    
    public function users(){
    	return $this->belongsTo('App\User', 'created_by', 'id');

    }
    public function homeworkDetail(){
    	return $this->belongsTo('App\SmHomework', 'homework_id', 'id');

    }
}
