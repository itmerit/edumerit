<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmStudentGroup extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveStatusSchoolScope);
    }
    public function students()
    {
        return $this->hasMany(SmStudent::class, 'student_group_id', 'id');
    }

    public function scopeStatus($query)
    {
        return  $query->where('active_status', 1)->where('school_id', auth()->user()->school_id);
    }
}
