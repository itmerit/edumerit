<?php

namespace Database\Seeders\HumanResources;

use App\SmStaff;
use App\SmStaffAttendence;
use Illuminate\Database\Seeder;

class SmStaffAttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id)
    {
        $staffs = SmStaff::where('school_id',$school_id)->get(['id','user_id']);
        $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $status = ['P','L','A'];
        for ($i = 1; $i <= $days; $i++) {
            foreach ($staffs as $staff) {
                if ($i <= 9) {
                    $d = '0' . $i;
                }
                $date = date('Y') . '-' . date('m') . '-' . $d;                    

                $sa = new SmStaffAttendence;
                $sa->staff_id = $staff->id;
                $sa->attendence_type = array_rand($status);
                $sa->notes = 'Sample Attendance for Staff';
                $sa->attendence_date = $date;
                $sa->school_id = $school_id;
                $sa->academic_id = $academic_id;
                $sa->save();
            }
        }
    }
}
