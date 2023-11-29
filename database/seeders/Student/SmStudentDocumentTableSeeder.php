<?php

namespace Database\Seeders\Student;

use App\SmClassSection;
use App\SmStudentDocument;
use Faker\Factory as Faker;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;

class SmStudentDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=1)
    {
        $faker = Faker::create();
      
        $classSection = SmClassSection::where('school_id',$school_id)->where('academic_id', $academic_id)->first();
        $students = StudentRecord::where('class_id', $classSection->class_id)
                                ->where('section_id', $classSection->section_id)
                                ->where('school_id',$school_id)
                                ->where('academic_id', $academic_id)
                                ->get();
        foreach($students as $student){
            $s = new SmStudentDocument();
            $s->title = $faker->sentence($nbWords =3, $variableNbWords = true);           
            $s->student_staff_id = $student->student_id;
            $s->type = 'stu';
            $s->file = '';
            $s->active_status = 1;
            $s->school_id = $school_id;
            $s->academic_id = $academic_id;
            $s->created_at = date('Y-m-d h:i:s');
            $s->save();
        }
    }
}
