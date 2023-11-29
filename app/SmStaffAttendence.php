<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmStaffAttendence extends Model
{
    use HasFactory;
    protected $table = "sm_staff_attendences";

    public function StaffInfo()
    {
        return $this->belongsTo('App\SmStaff', 'staff_id', 'id');
    }
}
