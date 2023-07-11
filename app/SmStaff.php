<?php

namespace App;

use App\Scopes\ActiveStatusSchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SmStaff extends Model
{
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new ActiveStatusSchoolScope);
    }
    protected $guarded = ['id'];
    protected $table = "sm_staffs";

    public function roles()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'role_id', 'id');
    }

    public function departments()
    {
        return $this->belongsTo('App\SmHumanDepartment', 'department_id', 'id')->withDefault();
    }

    public function designations()
    {
        return $this->belongsTo('App\SmDesignation', 'designation_id', 'id')->withDefault();
    }

    public function genders()
    {
        return $this->belongsTo('App\SmBaseSetup', 'gender_id', 'id');
    }

    public function staff_user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function attendances()
    {
        return $this->hasMany(SmStaffAttendence::class, 'staff_id')->where('school_id', auth()->user()->school_id);
    }
    public function getAttendanceType($month)
    {
        $attendances = $this->attendances()->whereMonth('attendence_date', $month)->get();

        return $attendances;
    }

    public function classes()
    {
        return $this->hasMany('App\SmAssignSubject', 'teacher_id', 'id')
            ->join('sm_classes', 'sm_classes.id', 'sm_assign_subjects.class_id')
            ->select('sm_classes.id', 'class_name')
            ->distinct();
    }

    public function scopeStatus($query)
    {
        return $query->where('active_status', 1)->where('school_id', Auth::user()->school_id);
    }

    public function DateWiseStaffAttendance()
    {
        return $this->hasOne('App\SmStaffAttendence', 'staff_id', 'id')->where('attendence_date', date('Y-m-d', strtotime(request()->attendance_date)));
    }

    public function DateWiseStaffAttendanceReport()
    {
        return $this->hasOne('App\SmStaffAttendence', 'staff_id', 'id')->where('attendence_date', 'like', request()->year . '-' . request()->month . '%');
    }

    public function payrollStatus()
    {

        return $this->hasOne('App\SmHrPayrollGenerate', 'staff_id', 'id')
            ->where('payroll_month', request()->payroll_month)
            ->where('payroll_year', request()->payroll_year);

    }

    public function previousRole()
    {
        return $this->belongsTo('Modules\RolePermission\Entities\InfixRole', 'previous_role_id', 'id')->withDefault();
    }
    
    public function scopeWhereRole($query, $role_id)
    {        
        return $query->where(function($q) use ($role_id) {
            $q->where('role_id', $role_id)->orWhere('previous_role_id', $role_id);
        });
    }
    public function scopeWhereTeacher($query)
    {        
        return $query->where(function($q) {
            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
        });
    }

}
