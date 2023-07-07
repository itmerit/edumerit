<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\SmStudent;
use App\SmStudentTimeline;

class sm_student_timelinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $studentList = SmStudent::where('class_id', 1)->get();
        foreach ($studentList as $student) {
            $st = new SmStudentTimeline();
            $st->staff_student_id = $student->id;
            $st->title = $faker->sentence($nbWords = 3, $variableNbWords = true);
            $st->date = $faker->dateTime()->format('Y-m-d');
            $st->description = $faker->sentence($nbWords = 3, $variableNbWords = true);
            $st->file = '';
            $st->type = 'stu';
            $st->visible_to_student = 1;
            $st->active_status = 1;
            $st->created_at = date('Y-m-d h:i:s');
            $st->save();
        }
    }
}
