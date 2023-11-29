<?php

namespace Database\Seeders;

use App\SmHomework;
use App\SmHomeworkStudent;
use App\SmStudent;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class sm_homework_studentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //        SmHomeworkStudent::query()->truncate();
        $faker = Faker::create();

        $students = SmStudent::where('class_id', 1)->where('school_id', 1)->get();
        foreach ($students as $student) {
            $homeworks = SmHomework::where('class_id', $student->class_id)->where('school_id', 1)->get();
            foreach ($homeworks as $homework) {
                $s = new SmHomeworkStudent();
                $s->student_id = $student->id;
                $s->homework_id = $homework->id;
                $s->marks = rand(5, 10);
                $s->teacher_comments = $faker->text(100);
                $s->complete_status = 'C';
                $s->created_at = date('Y-m-d h:i:s');
                $s->save();
            }
        }
    }
}
