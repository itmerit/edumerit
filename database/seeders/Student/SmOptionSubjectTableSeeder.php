<?php

namespace Database\Seeders\Student;

use App\SmClassSection;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use App\SmOptionalSubjectAssign;
use Illuminate\Support\Facades\DB;

class SmOptionSubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=1)
    {
        $classSection = SmClassSection::where('school_id', $school_id)->where('academic_id', $academic_id)
        ->latest()->first();
      
        $students = StudentRecord::where('class_id', $classSection->class_id)
        ->where('section_id', $classSection->section_id)
        ->where('school_id', $school_id)
        ->where('academic_id', $academic_id)
        ->get();
        if ($students){
            $subjects= DB::table('sm_assign_subjects')->where('class_id',$classSection->class_id)->get();
            if(count($subjects)>0) {
                foreach ($students as $row) {
                    $s = new SmOptionalSubjectAssign();
                    $s->student_id = $row->student_id;
                    $s->session_id = $row->session_id;
                    $s->subject_id = 1;
                    $s->school_id = $school_id;
                    $s->academic_id = $academic_id;
                    $s->save();
                }
            }
        }
    }
}
