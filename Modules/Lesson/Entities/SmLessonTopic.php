<?php

namespace Modules\Lesson\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;

class SmLessonTopic extends Model
{
	protected $fillable = [];
	
	protected static function boot(){
		parent::boot();
		static::addGlobalScope(new StatusAcademicSchoolScope);
	}
	public function class(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
	}
	public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
	}

	public function subject(){
		return $this->belongsTo('App\SmSubject', 'subject_id', 'id');
	}
	public function topics(){
		return $this->hasMany('Modules\Lesson\Entities\SmLessonTopicDetail', 'topic_id', 'id');
	}

	public function lesson(){
		return $this->belongsTo('Modules\Lesson\Entities\SmLesson', 'lesson_id', 'id');
	}
	public function unSession()
    {
        return $this->belongsTo('Modules\University\Entities\UnSession', 'un_session_id', 'id')->withDefault();
    }
    public function unFaculty()
    {
        return $this->belongsTo('Modules\University\Entities\UnFaculty', 'un_faculty_id', 'id')->withDefault();
    }
    public function unDepartment()
    {
        return $this->belongsTo('Modules\University\Entities\UnDepartment', 'un_department_id', 'id')->withDefault();
    }
    public function unAcademic()
    {
        return $this->belongsTo('Modules\University\Entities\UnAcademicYear', 'un_academic_id', 'id')->withDefault();
    }
    public function unSemester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }
    public function unSemesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }
    public function unSubject()
    {
        return $this->belongsTo('Modules\University\Entities\UnSubject', 'un_subject_id', 'id')->withDefault();
    }

}
