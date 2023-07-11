<?php

namespace Database\Seeders\HomeWork;

use App\SmClass;
use App\SmHomework;
use App\SmHomeworkStudent;
use Faker\Factory as Faker;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;

class SmHomeworkStudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($school_id, $academic_id, $count=5)
    {
        $faker = Faker::create();
        $class = SmClass::where('school_id', $school_id)->where('academic_id', $academic_id)->value('id');
        $students = StudentRecord::where('class_id', 1)->where('school_id', $school_id)->get();
        foreach ($students as $record) {
            $homeworks = SmHomework::where('class_id', $record->class_id)->where('school_id', 1)->get();
            foreach ($homeworks as $homework) {
                $s = new SmHomeworkStudent();
                $s->student_id = $record->student_id;
                // $s->student_record_id = $record->id;
                $s->homework_id = $homework->id;
                $s->marks = rand(5, 10);
                $s->teacher_comments = $faker->text(100);
                $s->complete_status = 'C';
                $s->created_at = date('Y-m-d h:i:s');
                $s->school_id = $school_id;
                $s->academic_id = $academic_id;
                $s->save();
            }
        }
    }
}
