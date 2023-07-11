<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmAssignClassTeacher extends Model
{
    use HasFactory;
    public function class()
    {
        return $this->belongsTo('App\SmClass', 'class_id', 'id');
    }
    public function section()
    {
        return $this->belongsTo('App\SmSection', 'section_id', 'id');
    }

    public function classTeachers()
    {
        return $this->hasMany('App\SmClassTeacher', 'assign_class_teacher_id', 'id');
    }

    public function scopeStatus($query)
    {
        return $query->where('active_status', 1)->where('school_id', Auth::user()->school_id);
    }

}
