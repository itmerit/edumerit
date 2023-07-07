<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class SmLeaveRequest extends Model
{
	use HasFactory;
    public function leaveType()
	{
	  return $this->belongsTo('App\SmLeaveType', 'type_id');
	}

	public function leaveDefine()
	{
	  return $this->belongsTo('App\SmLeaveDefine', 'leave_define_id', 'id');
	}

	public function staffs()
	{
	  return $this->belongsTo('App\SmStaff', 'staff_id', 'user_id');
	}
	public function student()
	{
	  return $this->belongsTo('App\SmStudent', 'staff_id', 'user_id');
	}

	public function getRemainingDaysAttribute()
	{
		$to = Carbon::parse( $this->leave_from);
		$from = Carbon::parse( $this->leave_to);
		$diff_in_days = $to->diffInDays($from);		
		return $diff_in_days;
	}

	public static function approvedLeave($type_id){
		
		try {
			$user = Auth::user();
				$leaves = SmLeaveRequest::where('role_id', $user->role_id)->where('staff_id', $user->id)
				->where('leave_define_id', $type_id)->where('approve_status', "A")->get();
				
				$approved_days = 0;
				foreach($leaves as $leave){
					$start = strtotime($leave->leave_from);
					$end = strtotime($leave->leave_to);
					$days_between = ceil(abs($end - $start) / 86400);
					$days = $days_between + 1;
					$approved_days += $days;
				}
				return $approved_days;
		} catch (\Exception $e) {
			$data=[];
			return $data;
		}
	}

	public static function approvedLeaveModal($type_id, $role_id, $staff_id){
		
		try {
			$leaves = SmLeaveRequest::where('role_id', $role_id)->where('staff_id', $staff_id)->where('leave_define_id', $type_id)->where('approve_status', "A")->get();
				$approved_days = 0;
				foreach($leaves as $leave){
					$start = strtotime($leave->leave_from);
					$end = strtotime($leave->leave_to);
					$days_between = ceil(abs($end - $start) / 86400);
					$days = $days_between + 1;
					$approved_days += $days;
				}
				return $approved_days;
		} catch (\Exception $e) {
			$data=[];
			return $data;
		}
	}
}