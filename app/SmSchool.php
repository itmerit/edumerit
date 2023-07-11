<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\Saas\Entities\SmSubscriptionPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmSchool extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function subscription()
    {
        return $this->hasOne(SmSubscriptionPayment::class, 'school_id')->latest();
    }

    public function academicYears()
    {
        return $this->hasMany(SmAcademicYear::class, 'school_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(SmSection::class, 'school_id');
    }

    public function classes()
    {
        return $this->hasMany(SmClass::class, 'school_id');
    }

    public function classTimes()
    {
        return $this->hasMany(SmClassTime::class, 'school_id')->where('type', 'class');
    }
    public function weekends()
    {
        return $this->hasMany(SmWeekend::class, 'school_id')->where('active_status', 1);
    }
    public function routineUpdates()
    {
        return $this->hasMany(SmClassRoutineUpdate::class, 'school_id','id')->where('active_status', 1);
    }

    public function saasRoutineUpdates()
    {
        return $this->hasMany(SmClassRoutineUpdate::class, 'school_id','id')->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', 1);
    }
}
