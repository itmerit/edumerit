<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmStaffAttendanceImport extends Model
{
    protected $fillable  = ['attendence_date','in_time','out_time','attendance_type','notes','staff_id'];
}