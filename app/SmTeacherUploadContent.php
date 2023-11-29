<?php

namespace App;

use App\Scopes\GlobalAcademicScope;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmTeacherUploadContent extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new GlobalAcademicScope);
    }

    public function contentTypes()
    {
        return $this->belongsTo('App\SmContentType', 'content_type', 'id');
    }

    public function roles()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'available_for', 'id');
    }

    public function classes()
    {
        return $this->belongsTo('App\SmClass', 'class', 'id')->withoutGlobalScope(StatusAcademicSchoolScope::class);
    }

    public function globalClasses()
    {
        return $this->belongsTo('App\SmClass', 'class', 'id')->withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class);
    }

    public function sections()
    {
        return $this->belongsTo('App\SmSection', 'section', 'id');
    }
    public function globalSections()
    {
        return $this->belongsTo('App\SmSection', 'section', 'id')->withoutGlobalScope(StatusAcademicSchoolScope::class)->withoutGlobalScope(GlobalAcademicScope::class);
    }

    public function users()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    
    public function unSession()
    {
        return $this->belongsTo('Modules\University\Entities\UnSession', 'un_session_id', 'id')->withDefault();
    }

    public function unSection()
    {
        return $this->belongsTo('App\SmSection', 'un_section_id', 'id')->withDefault();
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

    public function semester()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemester', 'un_semester_id', 'id')->withDefault();
    }

    public function semesterLabel()
    {
        return $this->belongsTo('Modules\University\Entities\UnSemesterLabel', 'un_semester_label_id', 'id')->withDefault();
    }
    public function scopeWhereNullLms($query)
    {
        return $query->whereNull('course_id')->whereNull('chapter_id')->whereNull('lesson_id');
    }
}
