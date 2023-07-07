<?php

namespace App;

use App\YearCheck;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;

class SmClassRoutineUpdate extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
  
        static::addGlobalScope(new StatusAcademicSchoolScope);
    }
    
    public static function assingedClassRoutine($class_time, $day, $class_id, $section_id)
    {
        try {

            return SmClassRoutineUpdate::where('class_period_id', $class_time)
            ->where('day', $day)
            ->where('class_id', $class_id)->where('section_id', $section_id)
            ->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public static function teacherAssingedClassRoutine($class_time, $day, $teacher_id)
    {
        try {
            return SmClassRoutineUpdate::where('class_period_id', $class_time)->where('day', $day)->where('class_period_id', $class_time)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->where('teacher_id', $teacher_id)->first();
        } catch (\Exception $e) {
            $data=[];
            return $data;
        }
    }

    public function subject()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id')->withDefault();
    }

    public function saasSubject()
    {
        return $this->belongsTo('App\SmSubject', 'subject_id', 'id')->withoutGlobalScope(StatusAcademicSchoolScope::class)->withDefault();
    }

    
    public function class(){
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }

    public function classRoom()
    {
        return $this->belongsTo('App\SmClassRoom', 'room_id', 'id')->withDefault();
    }

    public function teacherDetail()
    {
        return $this->belongsTo('App\SmStaff', 'teacher_id', 'id')->withDefault();
    }
   

    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }
    public function classTime()
    {
        return $this->belongsTo(SmClassTime::class, 'class_period_id');
    }

    public function saasClassTime()
    {
        return $this->belongsTo(SmClassTime::class, 'class_period_id');
    }

    public function weekend()
    {
        return $this->belongsTo(SmWeekend::class, 'day');
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
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')
        ->withDefault();
    }
    public function unSubject()
    {
        return $this->belongsTo('Modules\University\Entities\UnSubject', 'un_subject_id', 'id')->withDefault();
    }
}
