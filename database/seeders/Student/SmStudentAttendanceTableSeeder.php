<?php

namespace Database\Seeders\Student;

use App\SmClassSection;
use App\SmStudentAttendance;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;

class SmStudentAttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=1)
    {
        $days = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $classSection = SmClassSection::where('school_id',$school_id)->where('academic_id', $academic_id)->first();
        $students = StudentRecord::where('class_id', $classSection->class_id)
                                ->where('section_id', $classSection->section_id)
                                ->where('school_id',$school_id)
                                ->where('academic_id', $academic_id)
                                ->get();
        for ($i = 1; $i <= $days; $i++) {
            foreach ($students as $record) {
                if ($i <= 9) {
                    $d = '0' . $i;
                } else{
                    $d = $i;
                }
                $date = date('Y') . '-' . date('m') . '-' . $d;
                $sa = new SmStudentAttendance();
                $sa->student_id = $record->student_id;
                $sa->student_record_id = $record->student_id;
                $sa->class_id = $record->class_id;
                $sa->section_id = $record->section_id;
                $sa->attendance_type = 'P';
                $sa->notes = 'Sample Attendance for Student';
                $sa->attendance_date = $date;
                $sa->school_id = $school_id;
                $sa->academic_id = $academic_id;
                $sa->save();
            }
        }
    }
}
